<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\PublicController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/services', [PublicController::class, 'services'])->name('services.index');
Route::get('/services/{service:slug}', [PublicController::class, 'service'])->name('services.show');
Route::get('/services/{service:slug}/book', [PublicController::class, 'createBooking'])->name('bookings.create');
Route::get('/cara-kerja', [PublicController::class, 'howItWorks'])->name('how-it-works');
Route::get('/tentang-kami', [PublicController::class, 'about'])->name('about');
Route::get('/galeri', [PublicController::class, 'gallery'])->name('gallery');
Route::get('/faq', [PublicController::class, 'faq'])->name('faq');
Route::get('/book', [PublicController::class, 'createBooking'])->name('bookings.general');
Route::post('/bookings', [PublicController::class, 'storeBooking'])->name('bookings.store');
Route::post('/reviews', [PublicController::class, 'storeReview'])->name('reviews.store');

/*
|--------------------------------------------------------------------------
| Authentication Fallback & Admin Login
|--------------------------------------------------------------------------
*/

Route::get('/login', [AdminController::class, 'login'])->name('login');

Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminController::class, 'login'])->name('admin.login');
    Route::post('/login', [AdminController::class, 'authenticate'])->name('admin.authenticate');
});

/*
|--------------------------------------------------------------------------
| Protected Admin Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::post('/logout', [AdminController::class, 'logout'])->name('admin.logout');
    Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // Bookings
    Route::get('/bookings', [AdminController::class, 'bookings'])->name('admin.bookings');
    Route::patch('/bookings/{booking:id}', [AdminController::class, 'updateBooking'])->name('admin.bookings.update');
    Route::post('/bookings/{booking:id}/reschedule', [AdminController::class, 'rescheduleBooking'])->name('admin.bookings.reschedule');
    Route::delete('/bookings/{booking:id}', [AdminController::class, 'deleteBooking'])->name('admin.bookings.delete');

    // Calendar
    Route::get('/calendar', [AdminController::class, 'calendar'])->name('admin.calendar');

    // Services CRUD (explicit binding by ID)
    Route::get('/services', [AdminController::class, 'services'])->name('admin.services');
    Route::post('/services', [AdminController::class, 'storeService'])->name('admin.services.store');
    Route::match(['put', 'patch'], '/services/{service:id}', [AdminController::class, 'updateService'])->name('admin.services.update');
    Route::post('/services/{service:id}/toggle', [AdminController::class, 'toggleService'])->name('admin.services.toggle');
    Route::delete('/services/{service:id}', [AdminController::class, 'deleteService'])->name('admin.services.delete');

    // Customers
    Route::get('/customers', [AdminController::class, 'customers'])->name('admin.customers');

    // Revenue
    Route::get('/revenue', [AdminController::class, 'revenue'])->name('admin.revenue');

    // Content (Testimonials, FAQs, Gallery)
    Route::get('/content', [AdminController::class, 'content'])->name('admin.content');
    Route::post('/testimonials', [AdminController::class, 'storeTestimonial'])->name('admin.testimonials.store');
    Route::match(['put', 'patch'], '/testimonials/{testimonial:id}', [AdminController::class, 'updateTestimonial'])->name('admin.testimonials.update');
    Route::delete('/testimonials/{testimonial:id}', [AdminController::class, 'deleteTestimonial'])->name('admin.testimonials.delete');

    Route::post('/faqs', [AdminController::class, 'storeFaq'])->name('admin.faqs.store');
    Route::match(['put', 'patch'], '/faqs/{faq:id}', [AdminController::class, 'updateFaq'])->name('admin.faqs.update');
    Route::delete('/faqs/{faq:id}', [AdminController::class, 'deleteFaq'])->name('admin.faqs.delete');

    Route::post('/gallery', [AdminController::class, 'storeGallery'])->name('admin.gallery.store');
    Route::match(['put', 'patch', 'post'], '/gallery/{gallery:id}', [AdminController::class, 'updateGallery'])->name('admin.gallery.update');
    Route::delete('/gallery/{gallery:id}', [AdminController::class, 'deleteGallery'])->name('admin.gallery.delete');

    // Hero Slides (Auto-slide Showcase)
    Route::post('/hero-slides', [AdminController::class, 'storeHeroSlide'])->name('admin.hero-slides.store');
    Route::match(['put', 'patch', 'post'], '/hero-slides/{heroSlide:id}', [AdminController::class, 'updateHeroSlide'])->name('admin.hero-slides.update');
    Route::delete('/hero-slides/{heroSlide:id}', [AdminController::class, 'deleteHeroSlide'])->name('admin.hero-slides.delete');

    // Page Contents (Hero headline, PC Lemot, About story, How-it-works intro)
    Route::match(['put', 'patch', 'post'], '/page-contents/{pageContent:id}', [AdminController::class, 'updatePageContent'])->name('admin.page-contents.update');

    // Workflow Steps (Cara Kerja Steps 1-6)
    Route::post('/workflow-steps', [AdminController::class, 'storeWorkflowStep'])->name('admin.workflow-steps.store');
    Route::match(['put', 'patch', 'post'], '/workflow-steps/{workflowStep:id}', [AdminController::class, 'updateWorkflowStep'])->name('admin.workflow-steps.update');
    Route::delete('/workflow-steps/{workflowStep:id}', [AdminController::class, 'deleteWorkflowStep'])->name('admin.workflow-steps.delete');

    // About Pillars & Equipment (Tentang Kami)
    Route::post('/about-pillars', [AdminController::class, 'storeAboutPillar'])->name('admin.about-pillars.store');
    Route::match(['put', 'patch', 'post'], '/about-pillars/{aboutPillar:id}', [AdminController::class, 'updateAboutPillar'])->name('admin.about-pillars.update');
    Route::delete('/about-pillars/{aboutPillar:id}', [AdminController::class, 'deleteAboutPillar'])->name('admin.about-pillars.delete');

    // Business Settings & Logo Upload & Change Password
    Route::get('/settings', [AdminController::class, 'settings'])->name('admin.settings');
    Route::patch('/settings', [AdminController::class, 'updateSettings'])->name('admin.settings.update');
    Route::put('/password', [AdminController::class, 'updatePassword'])->name('admin.password.update');

    // Audit Logs (Single Delete & Clear All)
    Route::get('/audit-logs', [AdminController::class, 'auditLogs'])->name('admin.audit-logs');
    Route::delete('/audit-logs/{auditLog:id}', [AdminController::class, 'deleteAuditLog'])->name('admin.audit-logs.delete');
    Route::delete('/audit-logs-purge/all', [AdminController::class, 'clearAuditLogs'])->name('admin.audit-logs.clear');

    // Manual Walk-in Booking
    Route::post('/bookings-manual', [AdminController::class, 'storeManualBooking'])->name('admin.bookings.manual');
});
