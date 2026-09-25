<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProfessionalCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'professional_categories';

    protected $fillable = [
        'name',
        'slug',
        'short_description',
        'description',
        'icon',
        'image',
        'seo_title',
        'seo_description',
        'status',
        'sort_order',
    ];

    /**
     * Accessor to ensure Phosphor icon class always has required prefix (ph-bold).
     */
    public function getIconAttribute($value): string
    {
        $val = trim($value ?? '');
        if (empty($val)) {
            return match ($this->slug) {
                'electrician' => 'ph-bold ph-lightning',
                'plumber' => 'ph-bold ph-drop',
                'carpenter' => 'ph-bold ph-hammer',
                'painter' => 'ph-bold ph-paint-brush',
                'cctv-professional' => 'ph-bold ph-video-camera',
                'it-professional' => 'ph-bold ph-laptop',
                'labour' => 'ph-bold ph-hard-hat',
                'mason' => 'ph-bold ph-wall',
                'mechanic' => 'ph-bold ph-wrench',
                'driver' => 'ph-bold ph-steering-wheel',
                'security-guard' => 'ph-bold ph-shield-check',
                'laundry' => 'ph-bold ph-t-shirt',
                default => 'ph-bold ph-wrench',
            };
        }

        if (str_starts_with($val, 'ph-bold ') || str_starts_with($val, 'ph ') || str_starts_with($val, 'ph-fill ') || str_starts_with($val, 'ph-duotone ')) {
            return $val;
        }

        if (str_starts_with($val, 'ph-')) {
            return 'ph-bold ' . $val;
        }

        return 'ph-bold ph-' . $val;
    }

    /**
     * Phosphor Icon CSS Class alias.
     */
    public function getIconClassAttribute(): string
    {
        return $this->icon;
    }

    /**
     * Scope active categories.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope ordered categories.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc')->orderBy('name', 'asc');
    }

    /**
     * Services under this category.
     */
    public function services(): HasMany
    {
        return $this->hasMany(ProfessionalService::class, 'category_id')->orderBy('sort_order', 'asc');
    }

    /**
     * Active services.
     */
    public function activeServices(): HasMany
    {
        return $this->services()->where('status', 'active');
    }

    /**
     * Professionals listed in this category.
     */
    public function professionals(): HasMany
    {
        return $this->hasMany(Professional::class, 'category_id');
    }

    /**
     * Approved professionals count.
     */
    public function approvedProfessionalsCount(): int
    {
        return $this->professionals()->where('status', 'approved')->count();
    }
}
