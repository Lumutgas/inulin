<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class HeroSlide extends Model
{
    protected $fillable = [
        'title',
        'subtitle',
        'badge',
        'image',
        'button_text',
        'button_link',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    public function imageUrl(): string
    {
        if ($this->image) {
            if (str_starts_with($this->image, 'hero/') && Storage::disk('public')->exists($this->image)) {
                $time = file_exists(storage_path('app/public/' . $this->image)) ? filemtime(storage_path('app/public/' . $this->image)) : time();
                return asset('storage/' . $this->image) . '?v=' . $time;
            }
            if (file_exists(public_path('images/' . $this->image))) {
                $time = filemtime(public_path('images/' . $this->image));
                return asset('images/' . $this->image) . '?v=' . $time;
            }
            if (Storage::disk('public')->exists($this->image)) {
                return asset('storage/' . $this->image);
            }
        }

        return asset('images/hero/slide-pclemot.jpg');
    }
}
