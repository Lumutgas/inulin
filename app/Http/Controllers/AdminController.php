<?php

namespace App\Http\Controllers;

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
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    /* =========================================================================
     * AUTHENTICATION
     * ========================================================================= */

    public function login()
    {
        if (Auth::check() && auth()->user()->is_admin) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login', [
            'settings' => BusinessSetting::current(),
        ]);
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Anti Cyber-Attack: Brute-Force Rate Limiting (Max 5 attempts per 5 minutes per IP/Email)
        $throttleKey = Str::transliterate(Str::lower($credentials['email']) . '|' . $request->ip());

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            AuditLog::record('security_brute_force_blocked', null, [
                'email' => $credentials['email'],
                'ip' => $request->ip(),
                'seconds_remaining' => $seconds,
                'user_agent' => $request->userAgent(),
            ]);

            return back()->withErrors([
                'email' => "Terlalu banyak percobaan masuk yang mencurigakan. Akses dibatasi sementara demi keamanan. Coba lagi dalam {$seconds} detik."
            ])->withInput();
        }

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            RateLimiter::hit($throttleKey, 300); // 5 minutes lock increment
            AuditLog::record('security_login_failed', null, [
                'email' => $credentials['email'],
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return back()->withErrors(['email' => 'Kredensial autentikasi yang Anda masukkan tidak valid.'])->withInput();
        }

        if (!auth()->user()->is_admin) {
            Auth::logout();
            AuditLog::record('security_unauthorized_admin_attempt', null, [
                'email' => $credentials['email'],
                'ip' => $request->ip(),
            ]);
            return back()->withErrors(['email' => 'Akun Anda tidak memiliki hak akses administrator.']);
        }

        // Reset rate limiter on successful authentication
        RateLimiter::clear($throttleKey);

        // Regenerate session to prevent session fixation attacks
        $request->session()->regenerate();
        AuditLog::record('login_success', auth()->user(), [
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return redirect()->intended(route('admin.dashboard'))->with('success', 'Selamat datang kembali, ' . auth()->user()->name);
    }

    public function logout(Request $request)
    {
        AuditLog::record('logout', auth()->user(), ['ip' => $request->ip()]);
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'Anda telah keluar.');
    }

    /* =========================================================================
     * DASHBOARD
     * ========================================================================= */

    public function dashboard()
    {
        $counts = [
            'total' => Booking::count(),
            'today' => Booking::whereDate('starts_at', today())->count(),
            'pending' => Booking::where('status', 'pending')->count(),
            'confirmed' => Booking::where('status', 'confirmed')->count(),
            'in_progress' => Booking::where('status', 'in_progress')->count(),
            'completed' => Booking::where('status', 'completed')->count(),
            'cancelled' => Booking::where('status', 'cancelled')->count(),
        ];

        $revenue = [
            'realized' => Booking::realized()->sum('service_price'),
            'today' => Booking::realized()->whereDate('completed_at', today())->sum('service_price'),
            'pipeline' => Booking::whereIn('status', ['pending', 'confirmed', 'in_progress'])->sum('service_price'),
        ];

        $upcoming = Booking::with(['customer', 'service'])
            ->whereIn('status', ['pending', 'confirmed', 'in_progress'])
            ->where('starts_at', '>=', now()->startOfDay())
            ->orderBy('starts_at')
            ->limit(6)
            ->get();

        $recent = Booking::with(['customer', 'service'])
            ->latest()
            ->limit(8)
            ->get();

        return view('admin.dashboard', compact('counts', 'revenue', 'upcoming', 'recent'));
    }

    /* =========================================================================
     * BOOKINGS MANAGEMENT
     * ========================================================================= */

    public function bookings(Request $request)
    {
        $query = Booking::with(['customer', 'service']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_whatsapp', 'like', "%{$search}%")
                    ->orWhere('reference', 'like', "%{$search}%")
                    ->orWhere('service_name', 'like', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($paymentStatus = $request->input('payment_status')) {
            $query->where('payment_status', $paymentStatus);
        }

        $sort = $request->input('sort', 'starts_at_desc');
        switch ($sort) {
            case 'starts_at_asc':
                $query->orderBy('starts_at', 'asc');
                break;
            case 'created_at_desc':
                $query->orderBy('created_at', 'desc');
                break;
            case 'starts_at_desc':
            default:
                $query->orderBy('starts_at', 'desc');
                break;
        }

        $bookings = $query->paginate(15)->withQueryString();
        $services = Service::where('active', true)->get();

        return view('admin.bookings', compact('bookings', 'services'));
    }

    public function updateBooking(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'status' => 'nullable|in:pending,confirmed,in_progress,completed,cancelled',
            'payment_status' => 'nullable|in:unpaid,paid',
            'notes' => 'nullable|string|max:1000',
        ]);

        $updates = [];

        if (isset($validated['status'])) {
            $updates['status'] = $validated['status'];
            if ($validated['status'] === 'completed' && !$booking->completed_at) {
                $updates['completed_at'] = now();
            }
        }

        if (isset($validated['payment_status'])) {
            $updates['payment_status'] = $validated['payment_status'];
            if ($validated['payment_status'] === 'paid' && !$booking->paid_at) {
                $updates['paid_at'] = now();
            } elseif ($validated['payment_status'] === 'unpaid') {
                $updates['paid_at'] = null;
            }
        }

        if (isset($validated['notes'])) {
            $updates['notes'] = $validated['notes'];
        }

        $booking->update($updates);
        AuditLog::record('booking_updated', $booking, $updates);

        return back()->with('success', "Status booking #{$booking->reference} berhasil diperbarui.");
    }

    public function rescheduleBooking(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
        ]);

        $settings = BusinessSetting::current();
        $start = Carbon::parse($validated['date'] . ' ' . $validated['time']);
        $end = $start->copy()->addMinutes($settings->booking_duration);

        $booking->update([
            'starts_at' => $start,
            'ends_at' => $end,
        ]);

        AuditLog::record('booking_rescheduled', $booking, [
            'new_starts_at' => $start->toDateTimeString(),
            'new_ends_at' => $end->toDateTimeString(),
        ]);

        return back()->with('success', "Jadwal booking #{$booking->reference} berhasil dijadwalkan ulang ke {$start->format('d M Y H:i')} WIB.");
    }

    public function deleteBooking(Booking $booking)
    {
        $ref = $booking->reference;
        $booking->delete();
        AuditLog::record('booking_deleted', $booking, ['reference' => $ref]);

        return back()->with('success', "Booking #{$ref} berhasil dihapus.");
    }

    /* =========================================================================
     * CALENDAR
     * ========================================================================= */

    public function calendar(Request $request)
    {
        $mode = $request->input('mode', 'month');
        $dateStr = $request->input('date', now()->toDateString());
        $currentDate = Carbon::parse($dateStr);
        $settings = BusinessSetting::current();

        if ($mode === 'day') {
            $startRange = $currentDate->copy()->startOfDay();
            $endRange = $currentDate->copy()->endOfDay();
            $bookings = Booking::with(['customer', 'service'])
                ->whereBetween('starts_at', [$startRange, $endRange])
                ->orderBy('starts_at')
                ->get();
        } elseif ($mode === 'week') {
            $startRange = $currentDate->copy()->startOfWeek();
            $endRange = $currentDate->copy()->endOfWeek();
            $bookings = Booking::with(['customer', 'service'])
                ->whereBetween('starts_at', [$startRange, $endRange])
                ->orderBy('starts_at')
                ->get();
        } else {
            $mode = 'month';
            $startRange = $currentDate->copy()->startOfMonth()->startOfWeek();
            $endRange = $currentDate->copy()->endOfMonth()->endOfWeek();
            $bookings = Booking::with(['customer', 'service'])
                ->whereBetween('starts_at', [$startRange, $endRange])
                ->orderBy('starts_at')
                ->get();
        }

        return view('admin.calendar', compact('mode', 'currentDate', 'bookings', 'settings', 'startRange', 'endRange'));
    }

    /* =========================================================================
     * SERVICES CRUD
     * ========================================================================= */

    public function services()
    {
        $services = Service::withTrashed()->latest()->paginate(15);
        return view('admin.services', compact('services'));
    }

    public function storeService(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:120',
            'slug' => 'nullable|string|max:120|unique:services,slug',
            'description' => 'required|string|max:2000',
            'price' => 'required|integer|min:0',
            'duration' => 'nullable|integer|min:15|max:480',
            'included_items' => 'nullable|string',
            'supported_os' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'active' => 'nullable|boolean',
        ]);

        $slug = !empty($validated['slug']) ? Str::slug($validated['slug']) : Str::slug($validated['name']);
        if (Service::where('slug', $slug)->exists()) {
            $slug .= '-' . Str::random(4);
        }

        $includedItems = array_values(array_filter(array_map('trim', explode("\n", $validated['included_items'] ?? ''))));
        $supportedOs = array_values(array_filter(array_map('trim', explode(',', $validated['supported_os'] ?? ''))));

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('services', 'public');
        }

        $service = Service::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'],
            'price' => $validated['price'],
            'duration' => $validated['duration'] ?? 60,
            'included_items' => $includedItems,
            'supported_os' => $supportedOs,
            'image' => $imagePath,
            'active' => $request->boolean('active', true),
        ]);

        AuditLog::record('service_created', $service, ['name' => $service->name, 'price' => $service->price]);

        return back()->with('success', "Layanan '{$service->name}' berhasil ditambahkan.");
    }

    public function updateService(Request $request, Service $service)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:120',
            'slug' => 'required|string|max:120|unique:services,slug,' . $service->id,
            'description' => 'required|string|max:2000',
            'price' => 'required|integer|min:0',
            'duration' => 'nullable|integer|min:15|max:480',
            'included_items' => 'nullable|string',
            'supported_os' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'active' => 'nullable|boolean',
        ]);

        $includedItems = array_values(array_filter(array_map('trim', explode("\n", $validated['included_items'] ?? ''))));
        $supportedOs = array_values(array_filter(array_map('trim', explode(',', $validated['supported_os'] ?? ''))));

        $updates = [
            'name' => $validated['name'],
            'slug' => Str::slug($validated['slug']),
            'description' => $validated['description'],
            'price' => $validated['price'],
            'duration' => $validated['duration'] ?? 60,
            'included_items' => $includedItems,
            'supported_os' => $supportedOs,
            'active' => $request->boolean('active', true),
        ];

        if ($request->hasFile('image')) {
            if ($service->image) {
                Storage::disk('public')->delete($service->image);
            }
            $updates['image'] = $request->file('image')->store('services', 'public');
        }

        $service->update($updates);
        AuditLog::record('service_updated', $service, ['name' => $service->name, 'price' => $service->price]);

        return back()->with('success', "Layanan '{$service->name}' berhasil diperbarui.");
    }

    public function toggleService(Service $service)
    {
        $service->update(['active' => !$service->active]);
        $statusStr = $service->active ? 'diaktifkan' : 'dinonaktifkan';
        AuditLog::record('service_toggled', $service, ['active' => $service->active]);

        return back()->with('success', "Layanan '{$service->name}' berhasil {$statusStr}.");
    }

    public function deleteService(Service $service)
    {
        $name = $service->name;
        $service->delete();
        AuditLog::record('service_deleted', $service, ['name' => $name]);

        return back()->with('success', "Layanan '{$name}' berhasil dihapus.");
    }

    /* =========================================================================
     * CUSTOMERS
     * ========================================================================= */

    public function customers(Request $request)
    {
        $query = Customer::withCount('bookings')
            ->withMax('bookings', 'starts_at')
            ->withSum(['bookings as total_spent' => fn($q) => $q->realized()], 'service_price');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('whatsapp', 'like', "%{$search}%");
            });
        }

        $customers = $query->orderByDesc('bookings_count')->paginate(20)->withQueryString();

        return view('admin.customers', compact('customers'));
    }

    /* =========================================================================
     * REVENUE
     * ========================================================================= */

    public function revenue(Request $request)
    {
        $todayRevenue = Booking::realized()->whereDate('completed_at', today())->sum('service_price');
        $weekRevenue = Booking::realized()->whereBetween('completed_at', [now()->startOfWeek(), now()->endOfWeek()])->sum('service_price');
        $monthRevenue = Booking::realized()->whereMonth('completed_at', now()->month)->whereYear('completed_at', now()->year)->sum('service_price');
        $totalRevenue = Booking::realized()->sum('service_price');

        $pipelineRevenue = Booking::whereIn('status', ['pending', 'confirmed', 'in_progress'])->sum('service_price');
        $unpaidCompletedRevenue = Booking::where('status', 'completed')->where('payment_status', 'unpaid')->sum('service_price');

        $transactions = Booking::with('service')
            ->realized()
            ->latest('completed_at')
            ->paginate(15);

        return view('admin.revenue', compact(
            'todayRevenue',
            'weekRevenue',
            'monthRevenue',
            'totalRevenue',
            'pipelineRevenue',
            'unpaidCompletedRevenue',
            'transactions'
        ));
    }

    /* =========================================================================
     * CONTENT MANAGEMENT (TESTIMONIALS, FAQS, GALLERY)
     * ========================================================================= */

    public function content()
    {
        $testimonials = Testimonial::latest()->get();
        $faqs = Faq::orderBy('sort_order')->get();
        $galleries = Gallery::latest()->get();
        $heroSlides = HeroSlide::orderBy('order')->get();
        $pageContents = PageContent::orderBy('page')->orderBy('order')->get();
        $workflowSteps = WorkflowStep::orderBy('step_number')->get();
        $aboutPillars = AboutPillar::orderBy('type')->orderBy('order')->get();

        return view('admin.content', compact(
            'testimonials',
            'faqs',
            'galleries',
            'heroSlides',
            'pageContents',
            'workflowSteps',
            'aboutPillars'
        ));
    }

    public function storeTestimonial(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:120',
            'role' => 'nullable|string|max:100',
            'quote' => 'required|string|max:1000',
            'rating' => 'required|integer|min:1|max:5',
        ]);
        $validated['active'] = $request->boolean('active', true);

        $item = Testimonial::create($validated);
        AuditLog::record('testimonial_created', $item);

        return back()->with('success', 'Testimonial berhasil ditambahkan.');
    }

    public function updateTestimonial(Request $request, Testimonial $testimonial)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:120',
            'role' => 'nullable|string|max:100',
            'quote' => 'required|string|max:1000',
            'rating' => 'required|integer|min:1|max:5',
        ]);
        $validated['active'] = $request->boolean('active');

        $testimonial->update($validated);
        AuditLog::record('testimonial_updated', $testimonial);

        return back()->with('success', 'Testimonial berhasil diperbarui.');
    }

    public function deleteTestimonial(Testimonial $testimonial)
    {
        $testimonial->delete();
        AuditLog::record('testimonial_deleted', $testimonial);

        return back()->with('success', 'Testimonial berhasil dihapus.');
    }

    public function storeFaq(Request $request)
    {
        $validated = $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string|max:2000',
            'sort_order' => 'nullable|integer|min:0',
        ]);
        $validated['active'] = $request->boolean('active', true);
        $validated['sort_order'] = $validated['sort_order'] ?? (Faq::max('sort_order') + 1);

        $item = Faq::create($validated);
        AuditLog::record('faq_created', $item);

        return back()->with('success', 'FAQ berhasil ditambahkan.');
    }

    public function updateFaq(Request $request, Faq $faq)
    {
        $validated = $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string|max:2000',
            'sort_order' => 'nullable|integer|min:0',
        ]);
        $validated['active'] = $request->boolean('active');

        $faq->update($validated);
        AuditLog::record('faq_updated', $faq);

        return back()->with('success', 'FAQ berhasil diperbarui.');
    }

    public function deleteFaq(Faq $faq)
    {
        $faq->delete();
        AuditLog::record('faq_deleted', $faq);

        return back()->with('success', 'FAQ berhasil dihapus.');
    }

    public function storeGallery(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:120',
            'description' => 'nullable|string|max:500',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $validated['image'] = $request->file('image')->store('gallery', 'public');
        $validated['active'] = $request->boolean('active', true);

        $item = Gallery::create($validated);
        AuditLog::record('gallery_created', $item);

        return back()->with('success', "Foto galeri '{$item->title}' berhasil diunggah.");
    }

    public function updateGallery(Request $request, Gallery $gallery)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:120',
            'description' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);
        $validated['active'] = $request->boolean('active');

        if ($request->hasFile('image')) {
            if ($gallery->image && Storage::disk('public')->exists($gallery->image)) {
                Storage::disk('public')->delete($gallery->image);
            }
            $validated['image'] = $request->file('image')->store('gallery', 'public');
        }

        $gallery->update($validated);
        AuditLog::record('gallery_updated', $gallery);

        return back()->with('success', "Foto galeri '{$gallery->title}' berhasil diperbarui.");
    }

    public function deleteGallery(Gallery $gallery)
    {
        if ($gallery->image && Storage::disk('public')->exists($gallery->image)) {
            Storage::disk('public')->delete($gallery->image);
        }
        $gallery->delete();
        AuditLog::record('gallery_deleted', $gallery);

        return back()->with('success', 'Foto galeri berhasil dihapus.');
    }

    /* =========================================================================
     * HERO SLIDES (AUTO-SLIDE CAROUSEL)
     * ========================================================================= */

    public function storeHeroSlide(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:120',
            'subtitle' => 'nullable|string|max:500',
            'badge' => 'nullable|string|max:80',
            'button_text' => 'nullable|string|max:60',
            'button_link' => 'nullable|string|max:255',
            'order' => 'nullable|integer|min:0',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['order'] = $validated['order'] ?? (HeroSlide::max('order') + 1);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('hero', 'public');
        }

        $slide = HeroSlide::create($validated);
        AuditLog::record('hero_slide_created', $slide);

        return back()->with('success', "Slide hero '{$slide->title}' berhasil ditambahkan.");
    }

    public function updateHeroSlide(Request $request, HeroSlide $heroSlide)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:120',
            'subtitle' => 'nullable|string|max:500',
            'badge' => 'nullable|string|max:80',
            'button_text' => 'nullable|string|max:60',
            'button_link' => 'nullable|string|max:255',
            'order' => 'nullable|integer|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);
        $validated['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('image')) {
            if ($heroSlide->image && Storage::disk('public')->exists($heroSlide->image)) {
                Storage::disk('public')->delete($heroSlide->image);
            }
            $validated['image'] = $request->file('image')->store('hero', 'public');
        }

        $heroSlide->update($validated);
        AuditLog::record('hero_slide_updated', $heroSlide);

        return back()->with('success', "Slide hero '{$heroSlide->title}' berhasil diperbarui.");
    }

    public function deleteHeroSlide(HeroSlide $heroSlide)
    {
        if ($heroSlide->image && Storage::disk('public')->exists($heroSlide->image)) {
            Storage::disk('public')->delete($heroSlide->image);
        }
        $heroSlide->delete();
        AuditLog::record('hero_slide_deleted', $heroSlide);

        return back()->with('success', 'Slide hero berhasil dihapus.');
    }

    /* =========================================================================
     * PAGE CONTENT (HOME HEADLINES, PC LEMOT, ABOUT, HOW-IT-WORKS)
     * ========================================================================= */

    public function updatePageContent(Request $request, PageContent $pageContent)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:1000',
            'badge' => 'nullable|string|max:120',
            'content' => 'nullable|string|max:5000',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        if ($request->hasFile('image')) {
            if ($pageContent->image && Storage::disk('public')->exists($pageContent->image)) {
                Storage::disk('public')->delete($pageContent->image);
            }
            $validated['image'] = $request->file('image')->store('pages', 'public');
        }

        $pageContent->update($validated);
        AuditLog::record('page_content_updated', $pageContent);

        return back()->with('success', "Bagian konten '{$pageContent->title}' berhasil diperbarui.");
    }

    /* =========================================================================
     * WORKFLOW STEPS (CARA KERJA STEPS 1-6)
     * ========================================================================= */

    public function storeWorkflowStep(Request $request)
    {
        $validated = $request->validate([
            'step_number' => 'required|integer|min:1|max:20',
            'title' => 'required|string|max:120',
            'duration' => 'nullable|string|max:60',
            'tag' => 'nullable|string|max:60',
            'icon' => 'nullable|string|max:60',
            'description' => 'required|string|max:1000',
            'note' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['order'] = $validated['step_number'];

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('workflow', 'public');
        }

        $step = WorkflowStep::create($validated);
        AuditLog::record('workflow_step_created', $step);

        return back()->with('success', "Langkah alur #{$step->step_number} berhasil ditambahkan.");
    }

    public function updateWorkflowStep(Request $request, WorkflowStep $workflowStep)
    {
        $validated = $request->validate([
            'step_number' => 'required|integer|min:1|max:20',
            'title' => 'required|string|max:120',
            'duration' => 'nullable|string|max:60',
            'tag' => 'nullable|string|max:60',
            'icon' => 'nullable|string|max:60',
            'description' => 'required|string|max:1000',
            'note' => 'nullable|string|max:500',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);
        $validated['is_active'] = $request->boolean('is_active');
        $validated['order'] = $validated['step_number'];

        if ($request->hasFile('image')) {
            if ($workflowStep->image && Storage::disk('public')->exists($workflowStep->image)) {
                Storage::disk('public')->delete($workflowStep->image);
            }
            $validated['image'] = $request->file('image')->store('workflow', 'public');
        }

        $workflowStep->update($validated);
        AuditLog::record('workflow_step_updated', $workflowStep);

        return back()->with('success', "Langkah alur #{$workflowStep->step_number} berhasil diperbarui.");
    }

    public function deleteWorkflowStep(WorkflowStep $workflowStep)
    {
        if ($workflowStep->image && Storage::disk('public')->exists($workflowStep->image)) {
            Storage::disk('public')->delete($workflowStep->image);
        }
        $workflowStep->delete();
        AuditLog::record('workflow_step_deleted', $workflowStep);

        return back()->with('success', "Langkah alur berhasil dihapus.");
    }

    /* =========================================================================
     * ABOUT PILLARS & EQUIPMENT (TENTANG KAMI)
     * ========================================================================= */

    public function storeAboutPillar(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:pillar,equipment,metric',
            'title' => 'required|string|max:120',
            'subtitle' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:60',
            'description' => 'nullable|string|max:1000',
            'highlight' => 'nullable|string|max:60',
            'order' => 'nullable|integer|min:0',
        ]);
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['order'] = $validated['order'] ?? (AboutPillar::max('order') + 1);

        $pillar = AboutPillar::create($validated);
        AuditLog::record('about_pillar_created', $pillar);

        return back()->with('success', "Pilar/Fasilitas '{$pillar->title}' berhasil ditambahkan.");
    }

    public function updateAboutPillar(Request $request, AboutPillar $aboutPillar)
    {
        $validated = $request->validate([
            'type' => 'required|in:pillar,equipment,metric',
            'title' => 'required|string|max:120',
            'subtitle' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:60',
            'description' => 'nullable|string|max:1000',
            'highlight' => 'nullable|string|max:60',
            'order' => 'nullable|integer|min:0',
        ]);
        $validated['is_active'] = $request->boolean('is_active');

        $aboutPillar->update($validated);
        AuditLog::record('about_pillar_updated', $aboutPillar);

        return back()->with('success', "Pilar/Fasilitas '{$aboutPillar->title}' berhasil diperbarui.");
    }

    public function deleteAboutPillar(AboutPillar $aboutPillar)
    {
        $aboutPillar->delete();
        AuditLog::record('about_pillar_deleted', $aboutPillar);

        return back()->with('success', "Pilar/Fasilitas berhasil dihapus.");
    }

    /* =========================================================================
     * SETTINGS (INCLUDING LOGO & FAVICON UPLOAD)
     * ========================================================================= */

    public function settings()
    {
        $settings = BusinessSetting::current();
        return view('admin.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'business_name' => 'required|string|max:120',
            'tagline' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'whatsapp' => 'required|string|max:25',
            'phone' => 'nullable|string|max:25',
            'email' => 'nullable|email|max:120',
            'address' => 'nullable|string|max:255',
            'maps_link' => 'nullable|string|max:500',
            'opening_time' => 'required|date_format:H:i',
            'closing_time' => 'required|date_format:H:i',
            'booking_duration' => 'required|integer|min:15|max:240',
            'max_bookings_per_slot' => 'required|integer|min:1|max:10',
            'operating_days' => 'required|array|min:1',
            'operating_days.*' => 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'favicon' => 'nullable|image|mimes:jpg,jpeg,png,webp,ico|max:1024',
            'qris_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'qris_merchant_name' => 'nullable|string|max:120',
            'qris_nmid' => 'nullable|string|max:120',
        ]);

        $settings = BusinessSetting::current();

        // Logo Upload
        if ($request->hasFile('logo')) {
            if ($settings->logo) {
                Storage::disk('public')->delete($settings->logo);
            }
            $validated['logo'] = $request->file('logo')->store('branding', 'public');
            AuditLog::record('logo_changed', null, ['path' => $validated['logo']]);
        }

        // Favicon Upload
        if ($request->hasFile('favicon')) {
            if ($settings->favicon) {
                Storage::disk('public')->delete($settings->favicon);
            }
            $validated['favicon'] = $request->file('favicon')->store('branding', 'public');
            AuditLog::record('favicon_changed', null, ['path' => $validated['favicon']]);
        }

        // QRIS Image Upload
        if ($request->hasFile('qris_image')) {
            if ($settings->qris_image) {
                Storage::disk('public')->delete($settings->qris_image);
            }
            $validated['qris_image'] = $request->file('qris_image')->store('qris', 'public');
            AuditLog::record('qris_image_changed', null, ['path' => $validated['qris_image']]);
        }

        // Normalize WhatsApp number
        $validated['whatsapp'] = preg_replace('/[^0-9]/', '', $validated['whatsapp']);

        $settings->update($validated);
        AuditLog::record('settings_updated', null, ['name' => $settings->business_name]);

        return back()->with('success', 'Pengaturan bisnis, branding, dan QRIS berhasil disimpan.');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'current_password.required' => 'Kata sandi saat ini wajib diisi.',
            'password.required' => 'Kata sandi baru wajib diisi.',
            'password.min' => 'Kata sandi baru minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
        ]);

        if (!Hash::check($validated['current_password'], auth()->user()->password)) {
            return back()->withErrors(['current_password' => 'Kata sandi saat ini tidak valid.']);
        }

        auth()->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        AuditLog::record('admin_password_changed', auth()->user(), ['ip' => $request->ip()]);

        return back()->with('success', 'Kata sandi akun administrator berhasil diperbarui.');
    }

    /* =========================================================================
     * AUDIT LOGS
     * ========================================================================= */

    public function auditLogs(Request $request)
    {
        $query = AuditLog::with('user');

        if ($action = $request->input('action')) {
            $query->where('action', $action);
        }

        $logs = $query->latest()->paginate(25)->withQueryString();
        $actions = AuditLog::distinct()->pluck('action');

        return view('admin.audit-logs', compact('logs', 'actions'));
    }

    public function deleteAuditLog(AuditLog $auditLog)
    {
        $auditLog->delete();
        return back()->with('success', 'Entri riwayat audit berhasil dihapus.');
    }

    public function clearAuditLogs()
    {
        $count = AuditLog::count();
        AuditLog::query()->delete();
        AuditLog::record('audit_logs_purged', auth()->user(), ['cleared_count' => $count]);

        return back()->with('success', "Seluruh {$count} entri riwayat audit berhasil dibersihkan.");
    }

    /* =========================================================================
     * MANUAL / WALK-IN BOOKING (ADMIN CREATE)
     * ========================================================================= */

    public function storeManualBooking(Request $request)
    {
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'customer_name' => 'required|string|max:120',
            'customer_whatsapp' => 'required|string|max:25',
            'device' => 'required|in:Laptop,PC',
            'os' => 'required|in:Windows,Linux,Dual Boot',
            'os_version' => 'nullable|string|max:80',
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
            'notes' => 'nullable|string|max:1000',
            'status' => 'required|in:pending,confirmed,in_progress,completed',
            'payment_status' => 'required|in:unpaid,paid',
            'payment_method' => 'required|in:cash,qris',
            'payment_timing' => 'nullable|in:direct,paylater',
        ]);

        $service = Service::findOrFail($validated['service_id']);
        $phone = preg_replace('/[^0-9]/', '', $validated['customer_whatsapp']);
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }

        $customer = Customer::firstOrCreate(
            ['whatsapp' => $phone],
            ['name' => $validated['customer_name']]
        );

        $settings = BusinessSetting::current();
        $start = Carbon::parse($validated['date'] . ' ' . $validated['time']);
        $end = $start->copy()->addMinutes($settings->booking_duration);

        $booking = Booking::create([
            'reference' => 'INU-' . strtoupper(Str::random(8)),
            'customer_id' => $customer->id,
            'service_id' => $service->id,
            'customer_name' => $validated['customer_name'],
            'customer_whatsapp' => $phone,
            'service_name' => $service->name,
            'service_price' => $service->price,
            'device' => $validated['device'],
            'os' => $validated['os'],
            'os_version' => $validated['os_version'] ?? $validated['os'],
            'starts_at' => $start,
            'ends_at' => $end,
            'notes' => $validated['notes'],
            'status' => $validated['status'],
            'payment_status' => $validated['payment_status'],
            'payment_method' => $validated['payment_method'],
            'payment_timing' => $validated['payment_timing'] ?? 'direct',
            'paid_at' => $validated['payment_status'] === 'paid' ? now() : null,
            'completed_at' => $validated['status'] === 'completed' ? now() : null,
        ]);

        AuditLog::record('manual_booking_created', $booking);

        return back()->with('success', "Pesanan walk-in #{$booking->reference} untuk {$booking->customer_name} berhasil dibuat.");
    }
}
