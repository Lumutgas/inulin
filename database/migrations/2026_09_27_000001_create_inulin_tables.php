<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', fn (Blueprint $t) => $t->boolean('is_admin')->default(false));
        Schema::create('business_settings', function (Blueprint $t) {
            $t->id(); $t->string('business_name')->default('INULIN'); $t->string('tagline')->default('Instal Ulang Tanpa Ribet.');
            $t->text('description')->nullable(); $t->string('whatsapp')->nullable(); $t->string('phone')->nullable(); $t->string('email')->nullable();
            $t->text('address')->nullable(); $t->string('maps_link')->nullable(); $t->json('social_links')->nullable();
            $t->string('logo')->nullable(); $t->string('favicon')->nullable(); $t->json('operating_days');
            $t->time('opening_time')->default('09:00'); $t->time('closing_time')->default('17:00');
            $t->unsignedSmallInteger('booking_duration')->default(60); $t->unsignedSmallInteger('max_bookings_per_slot')->default(1); $t->timestamps();
        });
        Schema::create('services', function (Blueprint $t) {
            $t->id(); $t->string('name'); $t->string('slug')->unique(); $t->text('description'); $t->unsignedBigInteger('price');
            $t->string('image')->nullable(); $t->unsignedSmallInteger('duration')->nullable(); $t->json('included_items'); $t->json('supported_os');
            $t->boolean('active')->default(true)->index(); $t->timestamps(); $t->softDeletes();
        });
        Schema::create('customers', function (Blueprint $t) {
            $t->id(); $t->string('name'); $t->string('whatsapp', 20)->unique(); $t->timestamps();
        });
        Schema::create('bookings', function (Blueprint $t) {
            $t->id(); $t->uuid('reference')->unique(); $t->foreignId('customer_id')->constrained()->restrictOnDelete();
            $t->foreignId('service_id')->constrained()->restrictOnDelete(); $t->string('customer_name'); $t->string('customer_whatsapp', 20);
            $t->string('service_name'); $t->unsignedBigInteger('service_price'); $t->string('device'); $t->string('os'); $t->string('os_version');
            $t->dateTime('starts_at')->index(); $t->dateTime('ends_at')->index(); $t->text('notes')->nullable();
            $t->enum('status', ['pending','confirmed','in_progress','completed','cancelled'])->default('pending')->index();
            $t->enum('payment_status', ['unpaid','paid'])->default('unpaid'); $t->dateTime('paid_at')->nullable(); $t->dateTime('completed_at')->nullable()->index();
            $t->timestamps(); $t->softDeletes(); $t->index(['status','starts_at','ends_at']);
        });
        Schema::create('testimonials', function (Blueprint $t) {
            $t->id(); $t->string('name'); $t->text('quote'); $t->boolean('active')->default(false); $t->timestamps();
        });
        Schema::create('faqs', function (Blueprint $t) {
            $t->id(); $t->string('question'); $t->text('answer'); $t->unsignedInteger('sort_order')->default(0); $t->boolean('active')->default(true); $t->timestamps();
        });
        Schema::create('galleries', function (Blueprint $t) {
            $t->id(); $t->string('title'); $t->text('description')->nullable(); $t->string('image'); $t->boolean('active')->default(true); $t->timestamps();
        });
        Schema::create('audit_logs', function (Blueprint $t) {
            $t->id(); $t->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); $t->string('action')->index();
            $t->string('subject')->nullable(); $t->json('metadata')->nullable(); $t->string('ip',45)->nullable(); $t->timestamps();
        });
    }
    public function down(): void
    {
        foreach (['audit_logs','galleries','faqs','testimonials','bookings','customers','services','business_settings'] as $table) Schema::dropIfExists($table);
        Schema::table('users', fn (Blueprint $t) => $t->dropColumn('is_admin'));
    }
};
