<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AboutPillar extends Model
{
    protected $fillable = [
        'type', // pillar, equipment, metric
        'title',
        'subtitle',
        'icon',
        'description',
        'highlight',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];
}
