<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PageContent extends Model
{
    protected $fillable = [
        'page',
        'section_key',
        'title',
        'subtitle',
        'badge',
        'content',
        'image',
        'order',
    ];

    protected $casts = [
        'order' => 'integer',
    ];

    public static function getSection(string $page, string $key, array $defaults = []): ?self
    {
        return static::where('page', $page)->where('section_key', $key)->first();
    }

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
            $time = filemtime(public_path($this->image));
            return asset($this->image) . '?v=' . $time;
        }

        return asset($this->image);
    }
}
