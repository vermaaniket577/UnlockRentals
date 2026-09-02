<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

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

    /**
     * Exclude heavy binary blob from serialized models to drastically reduce RAM & network load.
     */
    protected $hidden = [
        'image_data',
    ];

    protected function casts(): array
    {
        return [
            'is_primary' => 'boolean',
        ];
    }

    /**
     * The "booted" method of the model.
     * Automatically selects lightweight columns and computes a boolean flag for blob presence
     * so MySQL never transfers megabytes of raw image data during page renders.
     */
    protected static function booted(): void
    {
        static::addGlobalScope('withoutBlob', function (Builder $builder) {
            if (is_null($builder->getQuery()->columns)) {
                $builder->select([
                    'property_images.id',
                    'property_images.property_id',
                    'property_images.path',
                    'property_images.is_primary',
                    'property_images.sort_order',
                    'property_images.created_at',
                    'property_images.updated_at',
                    DB::raw('(CASE WHEN property_images.image_data IS NOT NULL AND LENGTH(property_images.image_data) > 0 THEN 1 ELSE 0 END) AS has_image_data'),
                ]);
            }
        });
    }

    /**
     * Scope to include the raw binary image data (used only when streaming the actual image file).
     */
    public function scopeWithImageData(Builder $query): Builder
    {
        return $query->withoutGlobalScope('withoutBlob')->select(['id', 'image_data']);
    }

    /**
     * Get the URL to display this image.
     * Serves from DB binary if available, otherwise falls back to storage.
     */
    public function imageUrl(): string
    {
        // Check computed flag or raw attribute without loading entire binary blob
        if (!empty($this->has_image_data) || !empty($this->image_data)) {
            return route('property.image', $this->id, false);
        }

        if ($this->path) {
            $p = ltrim($this->path, '/');
            if (filter_var($p, FILTER_VALIDATE_URL)) {
                return $p;
            }
            return route('property.image.file', ['path' => $p], false);
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
