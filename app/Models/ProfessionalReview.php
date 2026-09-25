<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProfessionalReview extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'professional_reviews';

    protected $fillable = [
        'professional_id',
        'user_id',
        'service_request_id',
        'reviewer_name',
        'reviewer_phone',
        'rating',
        'title',
        'review',
        'status',
        'admin_response',
    ];

    public function professional(): BelongsTo
    {
        return $this->belongsTo(Professional::class, 'professional_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function serviceRequest(): BelongsTo
    {
        return $this->belongsTo(ServiceRequest::class);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }
}
