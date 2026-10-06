<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZonaRossaMedia extends Model
{
    use HasFactory;

    protected $table = 'zona_rossa_media';

    protected $fillable = [
        'file_path',
        'media_type',
        'title',
        'caption',
        'sort_order',
    ];

    /**
     * Get full public URL for the media asset.
     */
    public function getMediaUrlAttribute(): string
    {
        if (filter_var($this->file_path, FILTER_VALIDATE_URL)) {
            return $this->file_path;
        }

        if (str_starts_with($this->file_path, 'images/') || str_starts_with($this->file_path, '/images/')) {
            return asset(ltrim($this->file_path, '/'));
        }

        return asset('storage/' . ltrim($this->file_path, '/'));
    }

    /**
     * Check if media is video.
     */
    public function isVideo(): bool
    {
        return $this->media_type === 'video' || in_array(strtolower(pathinfo($this->file_path, PATHINFO_EXTENSION)), ['mp4', 'webm', 'mov', 'avi']);
    }
}
