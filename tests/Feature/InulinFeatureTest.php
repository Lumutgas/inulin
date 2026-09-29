<?php

namespace Tests\Feature;

use App\Models\AboutPillar;
use App\Models\AuditLog;
use App\Models\Booking;
use App\Models\BusinessSetting;
use App\Models\Customer;
use App\Models\Faq;
use App\Models\HeroSlide;
use App\Models\PageContent;
use App\Models\Service;
use App\Models\Testimonial;
use App\Models\User;
use App\Models\WorkflowStep;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class InulinFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed basic settings
        BusinessSetting::create([
            'id' => 1,
            'business_name' => 'INULIN',
            'tagline' => 'Instal Ulang Tanpa Ribet.',
            'whatsapp' => '6285165017620',
            'phone' => '085165017620',
            'email' => 'halo@inulin.id',
            'operating_days' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'],
            'opening_time' => '09:00',
            'closing_time' => '18:00',
            'booking_duration' => 60,
            'max_bookings_per_slot' => 2,
        ]);
    }

    public function test_homepage_renders_database_driven_services_and_settings(): void
    {
        $service = Service::create([
            'name' => 'Instal Ulang Windows',
            'slug' => 'instal-ulang-windows',
            'description' => 'Clean install master ISO resmi Microsoft.',
            'price' => 50000,
            'duration' => 60,
            'included_items' => ['Driver Komplit', 'Office Utility'],
            'supported_os' => ['Windows 11 Pro'],
            'active' => true,
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Instal Ulang Windows');
        $response->assertSee('Rp50.000');
        $response->assertSee('6285165017620');
    }

    public function test_service_detail_page_renders_inclusions_and_price_from_database(): void
    {
        $service = Service::create([
            'name' => 'Instal Linux & Dual Boot',
            'slug' => 'instal-linux-dual-boot',
            'description' => 'Setup distro Linux dan dual boot GRUB.',
            'price' => 65000,
            'duration' => 75,
            'included_items' => ['Partisi Manual Root & Swap', 'Driver Wi-Fi'],
            'supported_os' => ['Ubuntu LTS 24.04', 'Debian 12'],
            'active' => true,
        ]);

        $response = $this->get('/services/' . $service->slug);

        $response->assertStatus(200);
        $response->assertSee('Instal Linux & Dual Boot');
        $response->assertSee('Rp65.000');
        $response->assertSee('Partisi Manual Root & Swap');
    }

    public function test_booking_creation_with_windows_and_price_snapshot(): void
    {
        $service = Service::create([
            'name' => 'Instal Ulang Windows',
            'slug' => 'instal-ulang-windows',
            'description' => 'Windows installation.',
            'price' => 50000,
            'duration' => 60,
            'included_items' => ['Driver'],
            'supported_os' => ['Windows 11 Pro'],
            'active' => true,
        ]);

        $bookingDate = Carbon::tomorrow()->toDateString();

        $response = $this->post('/bookings', [
            'service_id' => $service->id,
            'name' => 'Budi Pratama',
            'whatsapp' => '081234567890',
            'device' => 'Laptop',
            'os' => 'Windows',
            'windows_version' => 'Windows 11 Pro',
            'date' => $bookingDate,
            'time' => '10:30',
            'notes' => 'Laptop lemot tolong install ulang',
            'payment_method' => 'cash',
        ]);

        $response->assertStatus(200);
        $response->assertSee('Booking Berhasil Disimpan');
        $response->assertSee('Budi Pratama');
        $response->assertSee('Rp50.000');

        $this->assertDatabaseHas('bookings', [
            'customer_name' => 'Budi Pratama',
            'service_price' => 50000,
            'device' => 'Laptop',
            'os' => 'Windows',
            'os_version' => 'Windows 11 Pro',
            'status' => 'pending',
            'payment_status' => 'unpaid',
        ]);

        $this->assertDatabaseHas('customers', [
            'name' => 'Budi Pratama',
            'whatsapp' => '6281234567890',
        ]);
    }

    public function test_booking_creation_with_linux_and_distro(): void
    {
        $service = Service::create([
            'name' => 'Instal Linux & Dual Boot',
            'slug' => 'instal-linux-dual-boot',
            'description' => 'Linux service.',
            'price' => 65000,
            'duration' => 75,
            'included_items' => ['GRUB'],
            'supported_os' => ['Ubuntu'],
            'active' => true,
        ]);

        $bookingDate = Carbon::tomorrow()->toDateString();

        $response = $this->post('/bookings', [
            'service_id' => $service->id,
            'name' => 'Ahmad Sysadmin',
            'whatsapp' => '085712345678',
            'device' => 'PC',
            'os' => 'Linux',
            'linux_distro' => 'Kali Linux 2024',
            'date' => $bookingDate,
            'time' => '13:00',
            'notes' => 'Dual boot dengan Windows',
            'payment_method' => 'cash',
        ]);

        $response->assertStatus(200);
        $response->assertSee('Ahmad Sysadmin');
        $response->assertSee('Kali Linux 2024');
        $response->assertSee('Rp65.000');

        $this->assertDatabaseHas('bookings', [
            'customer_name' => 'Ahmad Sysadmin',
            'os' => 'Linux',
            'os_version' => 'Kali Linux 2024',
            'service_price' => 65000,
        ]);
    }

    public function test_booking_validation_requires_windows_version_when_windows_selected(): void
    {
        $service = Service::create([
            'name' => 'Instal Ulang Windows',
            'slug' => 'win',
            'description' => 'Desc',
            'price' => 50000,
            'active' => true,
        ]);

        $response = $this->post('/bookings', [
            'service_id' => $service->id,
            'name' => 'Budi',
            'whatsapp' => '081234567890',
            'device' => 'Laptop',
            'os' => 'Windows',
            'date' => Carbon::tomorrow()->toDateString(),
            'time' => '10:30',
        ]);

        $response->assertSessionHasErrors('windows_version');
    }

    public function test_booking_validation_requires_linux_distro_when_linux_selected(): void
    {
        $service = Service::create([
            'name' => 'Instal Linux',
            'slug' => 'lin',
            'description' => 'Desc',
            'price' => 65000,
            'active' => true,
        ]);

        $response = $this->post('/bookings', [
            'service_id' => $service->id,
            'name' => 'Budi',
            'whatsapp' => '081234567890',
            'device' => 'Laptop',
            'os' => 'Linux',
            'date' => Carbon::tomorrow()->toDateString(),
            'time' => '10:30',
        ]);

        $response->assertSessionHasErrors('linux_distro');
    }

    public function test_booking_validation_rejects_past_date(): void
    {
        $service = Service::create([
            'name' => 'Instal Ulang Windows',
            'slug' => 'win-past',
            'description' => 'Desc',
            'price' => 50000,
            'active' => true,
        ]);

        $response = $this->post('/bookings', [
            'service_id' => $service->id,
            'name' => 'Budi',
            'whatsapp' => '081234567890',
            'device' => 'Laptop',
            'os' => 'Windows',
            'windows_version' => 'Windows 11',
            'date' => Carbon::yesterday()->toDateString(),
            'time' => '10:30',
        ]);

        $response->assertSessionHasErrors('date');
    }

    public function test_price_snapshot_persists_even_when_service_price_changes_later(): void
    {
        $service = Service::create([
            'name' => 'Instal Ulang',
            'slug' => 'instal-ulang-snapshot',
            'description' => 'Desc',
            'price' => 50000,
            'active' => true,
        ]);

        $this->post('/bookings', [
            'service_id' => $service->id,
            'name' => 'Customer A',
            'whatsapp' => '0811111111',
            'device' => 'Laptop',
            'os' => 'Windows',
            'windows_version' => 'Windows 10',
            'date' => Carbon::tomorrow()->toDateString(),
            'time' => '09:00',
            'payment_method' => 'cash',
        ]);

        $booking = Booking::where('customer_name', 'Customer A')->first();
        $this->assertEquals(50000, $booking->service_price);

        // Admin updates service price to 75000
        $service->update(['price' => 75000]);

        // Historical booking must remain 50000
        $booking->refresh();
        $this->assertEquals(50000, $booking->service_price);

        // New booking gets new price 75000
        $this->post('/bookings', [
            'service_id' => $service->id,
            'name' => 'Customer B',
            'whatsapp' => '0822222222',
            'device' => 'PC',
            'os' => 'Windows',
            'windows_version' => 'Windows 11',
            'date' => Carbon::tomorrow()->toDateString(),
            'time' => '10:30',
            'payment_method' => 'cash',
        ]);

        $bookingB = Booking::where('customer_name', 'Customer B')->first();
        $this->assertEquals(75000, $bookingB->service_price);
    }

    public function test_admin_authentication_and_authorization(): void
    {
        $admin = User::create([
            'name' => 'Admin Inulin',
            'email' => 'admin@inulin.test',
            'password' => bcrypt('secret123'),
            'is_admin' => true,
        ]);

        $regularUser = User::create([
            'name' => 'Regular User',
            'email' => 'user@inulin.test',
            'password' => bcrypt('secret123'),
            'is_admin' => false,
        ]);

        // Unauthenticated access
        $this->get('/admin')->assertRedirect('/admin/login');

        // Regular non-admin user
        $this->actingAs($regularUser)->get('/admin')->assertRedirect('/admin/login');

        // Admin login flow
        $loginRes = $this->post('/admin/login', [
            'email' => 'admin@inulin.test',
            'password' => 'secret123',
        ]);
        $loginRes->assertRedirect('/admin');
        $this->assertAuthenticatedAs($admin);

        // Access dashboard
        $this->actingAs($admin)->get('/admin')->assertStatus(200);
    }

    public function test_admin_can_update_booking_status_and_payment(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('pass'),
            'is_admin' => true,
        ]);

        $service = Service::create([
            'name' => 'Service',
            'slug' => 'srv',
            'description' => 'd',
            'price' => 50000,
            'active' => true,
        ]);

        $customer = Customer::create(['name' => 'C', 'whatsapp' => '628123']);

        $booking = Booking::create([
            'reference' => 'INU-TEST1',
            'customer_id' => $customer->id,
            'service_id' => $service->id,
            'customer_name' => 'C',
            'customer_whatsapp' => '628123',
            'service_name' => 'Service',
            'service_price' => 50000,
            'device' => 'Laptop',
            'os' => 'Windows',
            'os_version' => '11',
            'starts_at' => now(),
            'ends_at' => now()->addHour(),
            'status' => 'pending',
            'payment_status' => 'unpaid',
        ]);

        // Confirm booking
        $this->actingAs($admin)->patch('/admin/bookings/' . $booking->id, [
            'status' => 'confirmed',
        ])->assertSessionHas('success');

        $booking->refresh();
        $this->assertEquals('confirmed', $booking->status);

        // Complete & pay
        $this->actingAs($admin)->patch('/admin/bookings/' . $booking->id, [
            'status' => 'completed',
            'payment_status' => 'paid',
        ])->assertSessionHas('success');

        $booking->refresh();
        $this->assertEquals('completed', $booking->status);
        $this->assertEquals('paid', $booking->payment_status);
        $this->assertNotNull($booking->completed_at);
        $this->assertNotNull($booking->paid_at);
    }

    public function test_admin_services_crud(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin2@test.com',
            'password' => bcrypt('pass'),
            'is_admin' => true,
        ]);

        // Create
        $this->actingAs($admin)->post('/admin/services', [
            'name' => 'Pembersihan Laptop',
            'slug' => 'pembersihan-laptop',
            'description' => 'Cleaning fans and repaste',
            'price' => 75000,
            'duration' => 45,
            'included_items' => "Bongkar laptop\nGanti pasta Arctic MX-4",
            'supported_os' => 'All laptop brands',
            'active' => 1,
        ])->assertSessionHas('success');

        $this->assertDatabaseHas('services', [
            'name' => 'Pembersihan Laptop',
            'price' => 75000,
        ]);

        $service = Service::where('slug', 'pembersihan-laptop')->first();

        // Update
        $this->actingAs($admin)->patch('/admin/services/' . $service->id, [
            'name' => 'Pembersihan Laptop Pro',
            'slug' => 'pembersihan-laptop',
            'description' => 'Updated desc',
            'price' => 80000,
            'duration' => 60,
            'included_items' => "Item 1\nItem 2",
            'supported_os' => 'Windows, Linux',
            'active' => 1,
        ])->assertSessionHas('success');

        $service->refresh();
        $this->assertEquals('Pembersihan Laptop Pro', $service->name);
        $this->assertEquals(80000, $service->price);

        // Delete
        $this->actingAs($admin)->delete('/admin/services/' . $service->id)->assertSessionHas('success');
        $this->assertSoftDeleted('services', ['id' => $service->id]);
    }

    public function test_admin_settings_update_and_logo_upload(): void
    {
        Storage::fake('public');

        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin3@test.com',
            'password' => bcrypt('pass'),
            'is_admin' => true,
        ]);

        $logoFile = UploadedFile::fake()->image('custom_logo.png', 200, 200);

        $response = $this->actingAs($admin)->patch('/admin/settings', [
            'business_name' => 'INULIN OFFICIAL LAB',
            'tagline' => 'Instal Ulang Bergaransi Cepat.',
            'whatsapp' => '6285165017620',
            'phone' => '085165017620',
            'email' => 'admin@inulin.id',
            'address' => 'Yogyakarta',
            'opening_time' => '09:00',
            'closing_time' => '18:00',
            'booking_duration' => 60,
            'max_bookings_per_slot' => 2,
            'operating_days' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'],
            'logo' => $logoFile,
        ]);

        $response->assertSessionHas('success');

        $settings = BusinessSetting::current();
        $this->assertEquals('INULIN OFFICIAL LAB', $settings->business_name);
        $this->assertNotNull($settings->logo);

        Storage::disk('public')->assertExists($settings->logo);
    }

    public function test_booking_creation_generates_correct_whatsapp_url_with_official_phone(): void
    {
        $service = Service::create([
            'name' => 'Instal Windows 11 Official',
            'slug' => 'instal-windows-11-official',
            'description' => 'Instalasi bersih.',
            'price' => 50000,
            'duration' => 60,
            'included_items' => ['Driver Pack', 'Office Standar'],
            'supported_os' => ['Windows 11'],
            'active' => true,
        ]);

        $bookingDate = Carbon::tomorrow()->toDateString();
        $postData = [
            'service_id' => $service->id,
            'name' => 'Budi Santoso',
            'whatsapp' => '081234567890',
            'device' => 'Laptop',
            'os' => 'Windows',
            'windows_version' => 'Windows 11 Pro 64-bit',
            'date' => $bookingDate,
            'time' => '10:00',
            'notes' => 'Tolong partisi C 150GB',
            'payment_method' => 'cash',
        ];

        $response = $this->post('/bookings', $postData);
        $response->assertStatus(200);
        $response->assertSee('Booking Berhasil Disimpan');
        // Official WhatsApp phone number from BusinessSetting
        $response->assertSee('https://wa.me/6285165017620');

        $booking = Booking::where('customer_whatsapp', '6281234567890')->first();
        $this->assertNotNull($booking);
        $response->assertSee($booking->reference);
    }

    public function test_admin_can_reschedule_and_delete_booking(): void
    {
        $admin = User::create([
            'name' => 'Admin Super',
            'email' => 'super@inulin.test',
            'password' => bcrypt('password'),
            'is_admin' => true,
        ]);

        $service = Service::create([
            'name' => 'Dual Boot Setup',
            'slug' => 'dual-boot-setup',
            'description' => 'Dual boot Windows & Linux',
            'price' => 70000,
            'duration' => 90,
            'included_items' => ['GRUB Config'],
            'supported_os' => ['Windows 11', 'Ubuntu 24.04'],
            'active' => true,
        ]);

        $startsAt = Carbon::tomorrow()->setTime(11, 0);
        $endsAt = $startsAt->copy()->addMinutes(60);

        $customer = Customer::create([
            'name' => 'Eko Prasetyo',
            'whatsapp' => '6289988776655',
        ]);

        $booking = Booking::create([
            'reference' => 'INU-TEST-RESCHED',
            'customer_id' => $customer->id,
            'service_id' => $service->id,
            'customer_name' => 'Eko Prasetyo',
            'customer_whatsapp' => '6289988776655',
            'service_name' => $service->name,
            'service_price' => $service->price,
            'device' => 'Laptop',
            'os' => 'Dual Boot',
            'os_version' => 'Windows 11 + Ubuntu',
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'payment_method' => 'cash',
        ]);

        $newDate = Carbon::tomorrow()->addDays(2)->toDateString();
        $rescheduleRes = $this->actingAs($admin)->post("/admin/bookings/{$booking->id}/reschedule", [
            'date' => $newDate,
            'time' => '14:00',
        ]);

        $rescheduleRes->assertSessionHas('success');
        $booking->refresh();
        $this->assertEquals($newDate, $booking->starts_at->format('Y-m-d'));
        $this->assertEquals('14:00', $booking->starts_at->format('H:i'));

        // Delete booking
        $deleteRes = $this->actingAs($admin)->delete("/admin/bookings/{$booking->id}");
        $deleteRes->assertSessionHas('success');
        $this->assertSoftDeleted('bookings', ['id' => $booking->id]);
    }

    public function test_admin_content_management_crud(): void
    {
        $admin = User::create([
            'name' => 'Content Admin',
            'email' => 'content@inulin.test',
            'password' => bcrypt('password'),
            'is_admin' => true,
        ]);

        // FAQ CRUD
        $faqRes = $this->actingAs($admin)->post('/admin/faqs', [
            'question' => 'Apakah bisa instal di rumah?',
            'answer' => 'Saat ini kami melayani sistem drop-off di workshop atau COD area sekitar.',
            'sort_order' => 1,
            'active' => 1,
        ]);
        $faqRes->assertSessionHas('success');
        $this->assertDatabaseHas('faqs', ['question' => 'Apakah bisa instal di rumah?']);

        // Testimonial CRUD
        $testiRes = $this->actingAs($admin)->post('/admin/testimonials', [
            'name' => 'Dimas Anggara',
            'role' => 'Software Engineer',
            'quote' => 'Instalasi Arch Linux sangat rapi dengan Hyprland.',
            'rating' => 5,
            'active' => 1,
        ]);
        $testiRes->assertSessionHas('success');
        $this->assertDatabaseHas('testimonials', [
            'name' => 'Dimas Anggara',
            'rating' => 5,
            'role' => 'Software Engineer'
        ]);
    }

    public function test_admin_calendar_views_and_revenue_reports(): void
    {
        $admin = User::create([
            'name' => 'Reports Admin',
            'email' => 'reports@inulin.test',
            'password' => bcrypt('password'),
            'is_admin' => true,
        ]);

        // Calendar Day view
        $this->actingAs($admin)->get('/admin/calendar?view=day')->assertStatus(200);
        // Calendar Week view
        $this->actingAs($admin)->get('/admin/calendar?view=week')->assertStatus(200);
        // Calendar Month view
        $this->actingAs($admin)->get('/admin/calendar?view=month')->assertStatus(200);

        // Revenue Page
        $this->actingAs($admin)->get('/admin/revenue')->assertStatus(200);

        // Customers Page
        $this->actingAs($admin)->get('/admin/customers')->assertStatus(200);

        // Audit Logs Page
        $this->actingAs($admin)->get('/admin/audit-logs')->assertStatus(200);
    }

    public function test_multi_page_routes_render_successfully(): void
    {
        // 1. Services Catalog page
        $resServices = $this->get('/services');
        $resServices->assertStatus(200);
        $resServices->assertSee('Katalog Layanan');

        // 2. Cara Kerja / Protocols page
        $resWorkflow = $this->get('/cara-kerja');
        $resWorkflow->assertStatus(200);
        $resWorkflow->assertSee('Cara Kerja &amp; Protokol', false);

        // 3. Tentang Kami page
        $resAbout = $this->get('/tentang-kami');
        $resAbout->assertStatus(200);
        $resAbout->assertSee('Tentang Kami');
        $resAbout->assertSee('Laboratorium Sistem Operasi');

        // 4. Portofolio & Galeri page
        $resGallery = $this->get('/galeri');
        $resGallery->assertStatus(200);
        $resGallery->assertSee('Portofolio &amp; Galeri', false);

        // 5. FAQ page
        $resFaq = $this->get('/faq');
        $resFaq->assertStatus(200);
        $resFaq->assertSee('Pertanyaan yang');
    }

    public function test_admin_login_rate_limiting_anti_cyber_attack(): void
    {
        // Attempt 5 bad logins
        for ($i = 0; $i < 5; $i++) {
            $response = $this->post('/admin/login', [
                'email' => 'hacker@attack.test',
                'password' => 'wrongpass' . $i,
            ]);
            $response->assertSessionHasErrors('email');
        }

        // 6th attempt should be blocked by rate limiter
        $blockedResponse = $this->post('/admin/login', [
            'email' => 'hacker@attack.test',
            'password' => 'wrongpass_again',
        ]);

        $blockedResponse->assertSessionHasErrors('email');
        $this->assertStringContainsString('Terlalu banyak percobaan', session('errors')->first('email'));

        // Verify audit log captured the brute-force security event
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'security_brute_force_blocked',
        ]);
    }

    public function test_qris_booking_creation_with_payment_proof_upload_and_admin_acc(): void
    {
        Storage::fake('public');

        $service = Service::create([
            'name' => 'Instal Ulang Windows Pro',
            'slug' => 'instal-windows-qris',
            'description' => 'Instal Windows dengan QRIS',
            'price' => 50000,
            'duration' => 60,
            'active' => true,
        ]);

        $fakeProof = UploadedFile::fake()->image('bukti_transfer.jpg');

        $bookingDate = Carbon::tomorrow()->toDateString();
        $response = $this->post('/bookings', [
            'service_id' => $service->id,
            'name' => 'Fajar Pratama',
            'whatsapp' => '081299887766',
            'device' => 'Laptop',
            'os' => 'Windows',
            'windows_version' => 'Windows 11 Pro',
            'date' => $bookingDate,
            'time' => '10:30',
            'payment_method' => 'qris',
            'payment_proof' => $fakeProof,
        ]);

        $response->assertStatus(200);
        $response->assertSee('QRIS Langsung');
        $response->assertSee('Bukti Transfer QRIS');

        $booking = Booking::where('customer_name', 'Fajar Pratama')->first();
        $this->assertNotNull($booking);
        $this->assertEquals('qris', $booking->payment_method);
        $this->assertEquals('unpaid', $booking->payment_status);
        $this->assertNotNull($booking->payment_proof);
        Storage::disk('public')->assertExists($booking->payment_proof);

        // Admin logs in and ACCs the QRIS payment
        $admin = User::create([
            'name' => 'Admin ACC',
            'email' => 'acc@inulin.test',
            'password' => bcrypt('password'),
            'is_admin' => true,
        ]);

        $patchRes = $this->actingAs($admin)->patch("/admin/bookings/{$booking->id}", [
            'payment_status' => 'paid',
            'status' => 'completed',
        ]);

        $patchRes->assertSessionHas('success');
        $booking->refresh();
        $this->assertEquals('paid', $booking->payment_status);
        $this->assertEquals('completed', $booking->status);
        $this->assertNotNull($booking->paid_at);
        $this->assertNotNull($booking->completed_at);
    }

    public function test_admin_can_update_password_with_current_password_verification(): void
    {
        $admin = User::create([
            'name' => 'Admin Security',
            'email' => 'security@inulin.test',
            'password' => bcrypt('oldpassword123'),
            'is_admin' => true,
        ]);

        // 1. Wrong current password should fail
        $failRes = $this->actingAs($admin)->put('/admin/password', [
            'current_password' => 'wrongcurrentpassword',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);
        $failRes->assertSessionHasErrors('current_password');

        // 2. Correct current password should succeed
        $successRes = $this->actingAs($admin)->put('/admin/password', [
            'current_password' => 'oldpassword123',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);
        $successRes->assertSessionHas('success');

        $admin->refresh();
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('newpassword123', $admin->password));
    }

    public function test_admin_audit_logs_single_deletion_and_clear_all(): void
    {
        $admin = User::create([
            'name' => 'Admin Audit',
            'email' => 'audit@inulin.test',
            'password' => bcrypt('password'),
            'is_admin' => true,
        ]);

        $log1 = AuditLog::create([
            'action' => 'test_action_1',
            'user_id' => $admin->id,
            'user_name' => $admin->name,
        ]);
        $log2 = AuditLog::create([
            'action' => 'test_action_2',
            'user_id' => $admin->id,
            'user_name' => $admin->name,
        ]);

        // Single delete
        $delRes = $this->actingAs($admin)->delete("/admin/audit-logs/{$log1->id}");
        $delRes->assertSessionHas('success');
        $this->assertDatabaseMissing('audit_logs', ['id' => $log1->id]);

        // Clear all
        $clearRes = $this->actingAs($admin)->delete('/admin/audit-logs-purge/all');
        $clearRes->assertSessionHas('success');
        // Only the clear action log itself might remain
        $this->assertDatabaseMissing('audit_logs', ['id' => $log2->id]);
    }

    public function test_admin_can_create_manual_walk_in_booking(): void
    {
        $admin = User::create([
            'name' => 'Admin Walkin',
            'email' => 'walkin@inulin.test',
            'password' => bcrypt('password'),
            'is_admin' => true,
        ]);

        $service = Service::create([
            'name' => 'Instalasi Bersih Walk-in',
            'slug' => 'instalasi-bersih-walk-in',
            'description' => 'Servis langsung di lab',
            'price' => 50000,
            'duration' => 60,
            'active' => true,
        ]);

        $res = $this->actingAs($admin)->post('/admin/bookings-manual', [
            'service_id' => $service->id,
            'customer_name' => 'Pelanggan Langsung',
            'customer_whatsapp' => '081234567899',
            'device' => 'Laptop',
            'os' => 'Windows',
            'os_version' => 'Windows 11 Pro',
            'date' => Carbon::tomorrow()->toDateString(),
            'time' => '13:00',
            'status' => 'in_progress',
            'payment_status' => 'paid',
            'payment_method' => 'cash',
            'notes' => 'Datang langsung ke workshop',
        ]);

        $res->assertSessionHas('success');
        $this->assertDatabaseHas('bookings', [
            'customer_name' => 'Pelanggan Langsung',
            'status' => 'in_progress',
            'payment_status' => 'paid',
            'payment_method' => 'cash',
        ]);
    }

    public function test_qris_paylater_booking_without_proof_upload(): void
    {
        $service = Service::create([
            'name' => 'Instalasi Dual Boot Paylater',
            'slug' => 'dual-boot-paylater',
            'description' => 'Windows & Linux',
            'price' => 75000,
            'duration' => 90,
            'active' => true,
        ]);

        $bookingDate = Carbon::tomorrow()->toDateString();
        $response = $this->post('/bookings', [
            'service_id' => $service->id,
            'name' => 'Doni Paylater',
            'whatsapp' => '081234567811',
            'device' => 'Laptop',
            'os' => 'Windows',
            'windows_version' => 'Windows 11 Home',
            'date' => $bookingDate,
            'time' => '14:00',
            'payment_method' => 'qris',
            'payment_timing' => 'paylater',
        ]);

        $response->assertStatus(200);
        $response->assertSee('QRIS Paylater');
        $response->assertSee('Unduh QRIS');

        $booking = Booking::where('customer_name', 'Doni Paylater')->first();
        $this->assertNotNull($booking);
        $this->assertEquals('qris', $booking->payment_method);
        $this->assertEquals('paylater', $booking->payment_timing);
        $this->assertNull($booking->payment_proof);
        $this->assertEquals('unpaid', $booking->payment_status);

        // Admin can view and ACC the Paylater booking
        $admin = User::create([
            'name' => 'Admin Paylater',
            'email' => 'adminpaylater@inulin.test',
            'password' => bcrypt('password'),
            'is_admin' => true,
        ]);

        $patchRes = $this->actingAs($admin)->patch("/admin/bookings/{$booking->id}", [
            'payment_status' => 'paid',
            'status' => 'completed',
        ]);

        $patchRes->assertSessionHas('success');
        $booking->refresh();
        $this->assertEquals('paid', $booking->payment_status);
        $this->assertEquals('completed', $booking->status);
    }

    public function test_admin_can_update_qris_image_and_merchant_info(): void
    {
        Storage::fake('public');

        $admin = User::create([
            'name' => 'Admin QRIS',
            'email' => 'adminqris@inulin.test',
            'password' => bcrypt('password'),
            'is_admin' => true,
        ]);

        $fakeQris = UploadedFile::fake()->image('qris_custom.png');

        $response = $this->actingAs($admin)->patch('/admin/settings', [
            'business_name' => 'INULIN OFFICIAL',
            'tagline' => 'Instal Ulang Tanpa Ribet',
            'whatsapp' => '081234567890',
            'opening_time' => '08:00',
            'closing_time' => '21:00',
            'booking_duration' => 60,
            'max_bookings_per_slot' => 3,
            'operating_days' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'],
            'qris_image' => $fakeQris,
            'qris_merchant_name' => 'INULIN TEKNOLOGI UTAMA',
            'qris_nmid' => 'ID9988776655443',
        ]);

        $response->assertSessionHas('success');

        $settings = BusinessSetting::current();
        $this->assertEquals('INULIN TEKNOLOGI UTAMA', $settings->qris_merchant_name);
        $this->assertEquals('ID9988776655443', $settings->qris_nmid);
        $this->assertNotNull($settings->qris_image);
        Storage::disk('public')->assertExists($settings->qris_image);

        // Verify helper returns public storage URL with cache bust param
        $imageUrl = $settings->qrisImageUrl();
        $this->assertStringContainsString('/storage/' . $settings->qris_image, $imageUrl);
        $this->assertStringContainsString('?v=', $imageUrl);
    }

    public function test_visitor_can_submit_review_with_star_rating_and_audit_log(): void
    {
        $response = $this->post('/reviews', [
            'name' => 'Ahmad Fauzi',
            'role' => 'Mahasiswa Teknik - Lenovo ThinkPad',
            'rating' => 5,
            'quote' => 'Instalasi sangat cepat, driver terpasang komplit tanpa ada error sama sekali!',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('testimonials', [
            'name' => 'Ahmad Fauzi',
            'rating' => 5,
            'active' => true,
        ]);

        $this->assertDatabaseHas('audit_logs', [
            'action' => 'visitor_review_submitted',
        ]);
    }

    public function test_admin_can_manage_hero_slides_and_page_contents(): void
    {
        Storage::fake('public');

        $admin = User::create([
            'name' => 'Admin Content',
            'email' => 'admincontent@inulin.test',
            'password' => bcrypt('password'),
            'is_admin' => true,
        ]);

        $fakeSlideImage = UploadedFile::fake()->image('slide-custom.jpg');

        // Admin creates Hero Slide
        $resStore = $this->actingAs($admin)->post('/admin/hero-slides', [
            'title' => 'Teknisi Laptop Berpengalaman',
            'subtitle' => 'Pengerjaan transparan di depan Anda.',
            'badge' => 'LABORATORIUM RESMI',
            'button_text' => 'Booking Sekarang',
            'button_link' => '/bookings',
            'order' => 1,
            'image' => $fakeSlideImage,
            'is_active' => '1',
        ]);

        $resStore->assertSessionHas('success');
        $slide = HeroSlide::where('title', 'Teknisi Laptop Berpengalaman')->first();
        $this->assertNotNull($slide);
        $this->assertTrue($slide->is_active);
        Storage::disk('public')->assertExists($slide->image);

        // Admin updates Page Content (e.g. PC Lemot Showcase)
        $content = PageContent::create([
            'page' => 'home',
            'section_key' => 'pclemot_showcase',
            'title' => 'Laptop Lambat Jadi Kencang',
            'subtitle' => 'Solusi tuntas bloatware.',
            'badge' => 'PERFORMA MAKSIMAL',
            'content' => 'Pembersihan registry dan instal master resmi.',
            'order' => 1,
        ]);

        $resUpdate = $this->actingAs($admin)->put("/admin/page-contents/{$content->id}", [
            'title' => 'PC Lemot Jadi Responsif Kembali',
            'subtitle' => 'Hapus tuntas bloatware dan malware.',
            'badge' => 'OPTIMASI SYSTEM',
            'content' => 'Laptop kembali enteng seperti baru.',
        ]);

        $resUpdate->assertSessionHas('success');
        $content->refresh();
        $this->assertEquals('PC Lemot Jadi Responsif Kembali', $content->title);
        $this->assertEquals('OPTIMASI SYSTEM', $content->badge);
    }

    public function test_admin_can_manage_workflow_steps_and_pillars(): void
    {
        $admin = User::create([
            'name' => 'Admin Workflow',
            'email' => 'adminworkflow@inulin.test',
            'password' => bcrypt('password'),
            'is_admin' => true,
        ]);

        // Create Workflow Step
        $resStep = $this->actingAs($admin)->post('/admin/workflow-steps', [
            'step_number' => 1,
            'title' => 'Reservasi Mandiri Online',
            'duration' => '2 Menit',
            'tag' => 'Booking SOP',
            'icon' => 'calendar_month',
            'description' => 'Pelanggan memilih slot waktu dan varian OS.',
            'note' => 'Harga otomatis terkunci di database.',
            'is_active' => '1',
        ]);

        $resStep->assertSessionHas('success');
        $this->assertDatabaseHas('workflow_steps', [
            'step_number' => 1,
            'title' => 'Reservasi Mandiri Online',
        ]);

        // Create About Pillar
        $resPillar = $this->actingAs($admin)->post('/admin/about-pillars', [
            'type' => 'pillar',
            'title' => 'Isolasi Partisi Data',
            'subtitle' => 'Nol risiko kehilangan dokumen kerja.',
            'icon' => 'shield',
            'description' => 'Drive D dan E tidak pernah disentuh saat instalasi.',
            'highlight' => '100% Aman',
            'order' => 1,
            'is_active' => '1',
        ]);

        $resPillar->assertSessionHas('success');
        $this->assertDatabaseHas('about_pillars', [
            'title' => 'Isolasi Partisi Data',
            'type' => 'pillar',
            'highlight' => '100% Aman',
        ]);
    }

    public function test_timezone_is_asia_jakarta_and_timestamps_format_properly(): void
    {
        $this->assertEquals('Asia/Jakarta', config('app.timezone'));

        $now = Carbon::now();
        $this->assertEquals('Asia/Jakarta', $now->getTimezone()->getName());
    }
}


