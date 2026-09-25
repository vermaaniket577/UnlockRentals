<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class ProfessionalPhoto extends Model
{
    use HasFactory;

    protected $table = 'professional_photos';

    protected $fillable = [
        'professional_id',
        'image',
        'caption',
        'sort_order',
        'status',
    ];

    /**
     * Parent Professional.
     */
    public function professional(): BelongsTo
    {
        return $this->belongsTo(Professional::class, 'professional_id');
    }

    /**
     * Get accessible public photo URL.
     */
    public function getImageUrlAttribute(): string
    {
        if (empty($this->image)) {
            return asset('images/default-avatar.png');
        }

        if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
            return $this->image;
        }

        return asset('storage/' . $this->image);
    }

    /**
     * Scope ordered photos.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc')->latest();
    }
}
