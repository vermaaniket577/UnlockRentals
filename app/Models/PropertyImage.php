<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\URL;

class PropertyImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'path',
        'image_data',
        'is_primary',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_primary' => 'boolean',
        ];
    }

    /**
     * Get the URL to display this image.
     * Serves from DB binary if available, otherwise falls back to storage.
     */
    public function imageUrl(): string
    {
        if (!empty($this->image_data)) {
            return route('property.image', $this->id);
        }

        if ($this->path) {
            return asset('storage/' . $this->path);
        }

        return asset('images/luxury_sunlit.png');
    }

    /**
     * Get the property this image belongs to.
     */
    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }
}
