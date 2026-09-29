<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class WorkflowStep extends Model
{
    protected $fillable = [
        'step_number',
        'title',
        'duration',
        'tag',
        'icon',
        'description',
        'note',
        'image',
        'order',
        'is_active',
    ];

    protected $casts = [
        'step_number' => 'integer',
        'order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function imageUrl(): ?string
    {
        if (!$this->image) {
            return null;
        }

        if (Storage::disk('public')->exists($this->image)) {
            $time = file_exists(storage_path('app/public/' . $this->image)) ? filemtime(storage_path('app/public/' . $this->image)) : time();
            return asset('storage/' . $this->image) . '?v=' . $time;
        }

        if (file_exists(public_path($this->image))) {
            return asset($this->image);
        }

        return null;
    }
}
