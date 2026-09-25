<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceRequest extends Model
{
    use HasFactory;

    protected $table = 'service_requests';

    protected $fillable = [
        'user_id',
        'category_id',
        'service_id',
        'name',
        'phone',
        'email',
        'description',
        'address',
        'state',
        'district',
        'city',
        'locality',
        'pincode',
        'latitude',
        'longitude',
        'preferred_date',
        'preferred_time',
        'budget',
        'status',
    ];

    protected $casts = [
        'preferred_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProfessionalCategory::class, 'category_id');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(ProfessionalService::class, 'service_id');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(ServiceRequestAttachment::class, 'service_request_id');
    }

    public function leads(): HasMany
    {
        return $this->hasMany(ProfessionalLead::class, 'service_request_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(ProfessionalReview::class, 'service_request_id');
    }

    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
}
