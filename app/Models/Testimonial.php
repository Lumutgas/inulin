<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $fillable = ['name', 'role', 'quote', 'rating', 'active'];
    protected $casts = [
        'active' => 'boolean',
        'rating' => 'integer',
    ];
}
