<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class BusinessSetting extends Model
{
    protected $fillable = [
        'business_name',
        'tagline',
        'description',
        'whatsapp',
        'phone',
        'email',
        'address',
        'maps_link',
        'social_links',
        'logo',
        'favicon',
        'qris_image',
        'qris_merchant_name',
        'qris_nmid',
        'operating_days',
        'opening_time',
        'closing_time',
        'booking_duration',
        'max_bookings_per_slot',
    ];

    protected $casts = [
        'operating_days' => 'array',
        'social_links' => 'array',
    ];

    public static function current(): self
    {
        return static::firstOrCreate(
            ['id' => 1],
            [
                'operating_days' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'],
                'qris_merchant_name' => 'Kedai Beloz Wk17620',
                'qris_nmid' => 'ID2025443145250',
            ]
        );
    }

    public function logoUrl(): string
    {
        return $this->logo && Storage::disk('public')->exists($this->logo)
            ? asset('storage/' . $this->logo) . '?v=' . ($this->updated_at ? $this->updated_at->timestamp : time())
            : asset('brand/inulin.png');
    }

    public function faviconUrl(): string
    {
        return $this->favicon && Storage::disk('public')->exists($this->favicon)
            ? asset('storage/' . $this->favicon) . '?v=' . ($this->updated_at ? $this->updated_at->timestamp : time())
            : $this->logoUrl();
    }

    public function qrisImageUrl(): string
    {
        if ($this->qris_image && Storage::disk('public')->exists($this->qris_image)) {
            return asset('storage/' . $this->qris_image) . '?v=' . ($this->updated_at ? $this->updated_at->timestamp : time());
        }

        if (file_exists(public_path('images/qris.png'))) {
            return asset('images/qris.png') . '?v=' . ($this->updated_at ? $this->updated_at->timestamp : time());
        }

        return asset('images/qris.png');
    }
}
