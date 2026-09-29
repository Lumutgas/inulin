<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Models\AboutPillar;
use App\Models\AuditLog;
use App\Models\Booking;
use App\Models\BusinessSetting;
use App\Models\Customer;
use App\Models\Faq;
use App\Models\Gallery;
use App\Models\HeroSlide;
use App\Models\PageContent;
use App\Models\Service;
use App\Models\Testimonial;
use App\Models\WorkflowStep;
use App\Support\Phone;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PublicController extends Controller
{
    public function home()
    {
        $settings = BusinessSetting::current();
        $services = Service::where('active', true)->get();
        $faqs = Faq::where('active', true)->orderBy('sort_order')->get();
        $testimonials = Testimonial::where('active', true)->latest()->get();
        $galleries = Gallery::where('active', true)->latest()->get();
        $heroSlides = HeroSlide::where('is_active', true)->orderBy('order')->get();
        $pcLemotSection = PageContent::getSection('home', 'pclemot_showcase');
        $heroHeadline = PageContent::getSection('home', 'hero_headline');

        return view('home', compact(
            'settings',
            'services',
            'faqs',
            'testimonials',
            'galleries',
            'heroSlides',
            'pcLemotSection',
            'heroHeadline'
        ));
    }

    public function services(Request $request)
    {
        $settings = BusinessSetting::current();
        $services = Service::where('active', true)->get();

        return view('services', compact('settings', 'services'));
    }

    public function howItWorks()
    {
        $settings = BusinessSetting::current();
        $services = Service::where('active', true)->get();
        $introSection = PageContent::getSection('how_it_works', 'how_it_works_intro');
        $steps = WorkflowStep::where('is_active', true)->orderBy('step_number')->get();

        return view('how-it-works', compact('settings', 'services', 'introSection', 'steps'));
    }

    public function about()
    {
        $settings = BusinessSetting::current();
        $introSection = PageContent::getSection('about', 'about_story');
        $pillars = AboutPillar::where('type', 'pillar')->where('is_active', true)->orderBy('order')->get();
        $equipments = AboutPillar::where('type', 'equipment')->where('is_active', true)->orderBy('order')->get();

        return view('about', compact('settings', 'introSection', 'pillars', 'equipments'));
    }

    public function gallery()
    {
        $settings = BusinessSetting::current();
        $galleries = Gallery::where('active', true)->latest()->get();
        $testimonials = Testimonial::where('active', true)->latest()->get();

        return view('gallery', compact('settings', 'galleries', 'testimonials'));
    }

    public function storeReview(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:120',
            'role' => 'nullable|string|max:120',
            'rating' => 'required|integer|min:1|max:5',
            'quote' => 'required|string|min:10|max:1000',
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'rating.required' => 'Silakan pilih rating bintang 1 hingga 5.',
            'rating.min' => 'Rating minimal 1 bintang.',
            'rating.max' => 'Rating maksimal 5 bintang.',
            'quote.required' => 'Ulasan wajib diisi minimal 10 karakter.',
            'quote.min' => 'Ulasan minimal 10 karakter agar informatif bagi calon pelanggan.',
        ]);

        $testi = Testimonial::create([
            'name' => $validated['name'],
            'role' => $validated['role'] ?: 'Pengunjung Web Terverifikasi',
            'rating' => (int) $validated['rating'],
            'quote' => $validated['quote'],
            'active' => true,
        ]);

        AuditLog::record('visitor_review_submitted', $testi, [
            'name' => $validated['name'],
            'rating' => (int) $validated['rating'],
            'role' => $validated['role'] ?: 'Pengunjung Web Terverifikasi',
        ]);

        return back()->with('success', 'Terima kasih atas ulasan Anda! Rating dan testimoni Anda berhasil dipublikasikan ke website.');
    }

    public function faq()
    {
        $settings = BusinessSetting::current();
        $faqs = Faq::where('active', true)->orderBy('sort_order')->get();

        return view('faq', compact('settings', 'faqs'));
    }

    public function service(Service $service)
    {
        abort_unless($service->active, 404);
        $settings = BusinessSetting::current();
        $testimonials = Testimonial::where('active', true)->latest()->limit(4)->get();
        $allServices = Service::where('active', true)->get();

        return view('service', compact('service', 'settings', 'testimonials', 'allServices'));
    }

    public function createBooking(Request $request, ?Service $service = null)
    {
        $allServices = Service::where('active', true)->get();
        
        if (!$service || !$service->exists) {
            $service = $allServices->first();
            abort_unless($service, 404, 'Belum ada layanan aktif yang tersedia.');
        }

        abort_unless($service->active, 404);
        $settings = BusinessSetting::current();

        return view('booking', compact('service', 'allServices', 'settings'));
    }

    public function storeBooking(StoreBookingRequest $request)
    {
        $service = Service::whereKey($request->service_id)->where('active', true)->firstOrFail();
        $phone = Phone::normalize($request->whatsapp);

        $customer = Customer::firstOrCreate(
            ['whatsapp' => $phone],
            ['name' => $request->name]
        );
        $customer->update(['name' => $request->name]);

        $settings = BusinessSetting::current();
        $start = Carbon::parse($request->date . ' ' . $request->time);
        $end = $start->copy()->addMinutes($settings->booking_duration);

        $osVersion = $request->os === 'Linux' ? $request->linux_distro : $request->windows_version;

        $paymentProofPath = null;
        if ($request->hasFile('payment_proof')) {
            $paymentProofPath = $request->file('payment_proof')->store('payments', 'public');
        }

        $paymentTiming = $request->input('payment_method') === 'qris' 
            ? $request->input('payment_timing', 'direct') 
            : 'paylater';

        // Snapshot price from DB
        $booking = Booking::create([
            'reference' => 'INU-' . strtoupper(Str::random(8)),
            'customer_id' => $customer->id,
            'service_id' => $service->id,
            'customer_name' => $request->name,
            'customer_whatsapp' => $phone,
            'service_name' => $service->name,
            'service_price' => $service->price,
            'device' => $request->device,
            'os' => $request->os,
            'os_version' => $osVersion,
            'starts_at' => $start,
            'ends_at' => $end,
            'notes' => $request->notes,
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'payment_method' => $request->input('payment_method', 'cash'),
            'payment_timing' => $paymentTiming,
            'payment_proof' => $paymentProofPath,
        ]);

        AuditLog::record('booking_created', $booking, [
            'customer_name' => $booking->customer_name,
            'service' => $booking->service_name,
            'payment_method' => $booking->payment_method,
            'payment_timing' => $booking->payment_timing,
            'price' => $booking->service_price,
        ]);

        $whatsappTarget = preg_replace('/[^0-9]/', '', $settings->whatsapp ?: '6285165017620');
        if (str_starts_with($whatsappTarget, '0')) {
            $whatsappTarget = '62' . substr($whatsappTarget, 1);
        }

        $formattedDate = $booking->starts_at->translatedFormat('d F Y, H:i');
        $formattedPrice = 'Rp' . number_format($booking->service_price, 0, ',', '.');
        
        if ($booking->payment_method === 'qris') {
            $paymentMethodText = $booking->payment_timing === 'paylater' 
                ? 'QRIS Paylater (Bayar Setelah Jadi / Pasca Servis)' 
                : 'QRIS Langsung (Bukti Transfer Dilampirkan)';
        } else {
            $paymentMethodText = 'Tunai di Workshop Lab (Bayar Pasca Cek)';
        }

        $msg = "Halo INULIN, saya mau konfirmasi booking instal ulang:\n\n"
            . "Kode Ref: #{$booking->reference}\n"
            . "Nama: {$booking->customer_name}\n"
            . "WhatsApp: {$booking->customer_whatsapp}\n"
            . "Perangkat: {$booking->device}\n"
            . "OS: {$booking->os} ({$booking->os_version})\n"
            . "Jadwal: {$formattedDate} WIB\n"
            . "Layanan: {$booking->service_name}\n"
            . "Total: {$formattedPrice}\n"
            . "Metode Pembayaran: {$paymentMethodText}\n"
            . "Catatan: " . ($booking->notes ?: '-');

        $whatsappUrl = 'https://wa.me/' . $whatsappTarget . '?text=' . rawurlencode($msg);

        return view('booking-success', [
            'booking' => $booking,
            'whatsappUrl' => $whatsappUrl,
            'messageText' => $msg,
            'settings' => $settings,
        ]);
    }
}
