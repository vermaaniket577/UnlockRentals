<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProfessionalService extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'professional_services';

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'status',
        'sort_order',
    ];

    /**
     * Parent Category.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(ProfessionalCategory::class, 'category_id');
    }

    /**
     * Professionals offering this service.
     */
    public function professionals(): BelongsToMany
    {
        return $this->belongsToMany(
            Professional::class,
            'professional_professional_service',
            'professional_service_id',
            'professional_id'
        )->withTimestamps();
    }

    /**
     * Scope active services.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope ordered services.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc')->orderBy('name', 'asc');
    }
}
