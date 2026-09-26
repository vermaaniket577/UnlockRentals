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
                    DB::raw('(CASE WHEN property_images.image_data IS NOT NULL THEN 1 ELSE 0 END) AS has_image_data'),
                ]);
            }
        });

        // Automatically purge any cached image file when the model is updated or deleted
        static::saved(function ($model) {
            $model->purgeCachedFile();
        });

        static::deleted(function ($model) {
            $model->purgeCachedFile();
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
     * Directory path where binary images are cached on disk for high-speed direct static serving.
     */
    public static function getCacheDir(): string
    {
        return public_path('cache/property-images');
    }

    /**
     * Get path of cached file on disk if it exists.
     */
    public function getCachedFilePath(): ?string
    {
        $dir = self::getCacheDir();
        $candidates = [
            $dir . DIRECTORY_SEPARATOR . $this->id . '.jpg',
            $dir . DIRECTORY_SEPARATOR . $this->id . '.webp',
            $dir . DIRECTORY_SEPARATOR . $this->id . '.png',
        ];
        foreach ($candidates as $cand) {
            if (is_file($cand)) {
                return $cand;
            }
        }
        return null;
    }

    /**
     * Purge the cached file from disk.
     */
    public function purgeCachedFile(): void
    {
        $dir = self::getCacheDir();
        foreach (['.jpg', '.webp', '.png', '.jpeg'] as $ext) {
            $f = $dir . DIRECTORY_SEPARATOR . $this->id . $ext;
            if (is_file($f)) {
                @unlink($f);
            }
        }
    }

    /**
     * Get the URL to display this image.
     * Serves from DB binary if available, otherwise falls back to storage.
     */
    public function imageUrl(): string
    {
        // 1. If static cached file already exists, return the direct static asset URL (bypasses PHP & DB completely)
        $cachedPath = $this->getCachedFilePath();
        if ($cachedPath) {
            $basename = basename($cachedPath);
            return asset('cache/property-images/' . $basename);
        }

        // 2. Check computed flag or raw attribute for binary data in database
        if (!empty($this->has_image_data) || !empty($this->image_data)) {
            return route('property.image', $this->id, false);
        }

        // 3. File path storage handling
        if ($this->path) {
            $p = ltrim($this->path, '/');
            if (filter_var($p, FILTER_VALIDATE_URL)) {
                return $p;
            }

            // Direct static serve check if file already exists in public storage
            $cleanStoragePath = preg_replace('#^storage/#i', '', $p);
            if (is_file(public_path('storage/' . $cleanStoragePath))) {
                return asset('storage/' . $cleanStoragePath);
            }
            if (is_file(public_path($p))) {
                return asset($p);
            }

            return route('property.image.file', ['path' => $p], false);
        }

        return asset('images/luxury_sunlit.webp');
    }

    /**
     * Get the property this image belongs to.
     */
    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }
}
