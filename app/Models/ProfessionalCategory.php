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
