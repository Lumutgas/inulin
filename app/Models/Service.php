<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'image',
        'duration',
        'included_items',
        'supported_os',
        'active',
    ];

    protected $casts = [
        'included_items' => 'array',
        'supported_os' => 'array',
        'active' => 'boolean',
        'price' => 'integer',
    ];

    protected $attributes = [
        'included_items' => '[]',
        'supported_os' => '[]',
        'active' => true,
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
