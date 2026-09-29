<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('business_settings', function (Blueprint $table) {
            $table->string('qris_image')->nullable()->after('favicon');
            $table->string('qris_merchant_name')->default('Kedai Beloz Wk17620')->after('qris_image');
            $table->string('qris_nmid')->default('ID2025443145250')->after('qris_merchant_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('business_settings', function (Blueprint $table) {
            $table->dropColumn(['qris_image', 'qris_merchant_name', 'qris_nmid']);
        });
    }
};
