<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Selfie extends Model
{
    use HasFactory;

    protected $fillable = [
        'image_path',
        'caption',
    ];

    /**
     * Get full public URL for the selfie image.
     */
    public function getImageUrlAttribute(): string
    {
        if (filter_var($this->image_path, FILTER_VALIDATE_URL)) {
            return $this->image_path;
        }

        if (str_starts_with($this->image_path, 'images/') || str_starts_with($this->image_path, '/images/')) {
            return asset(ltrim($this->image_path, '/'));
        }

        return asset('storage/' . ltrim($this->image_path, '/'));
    }
}
