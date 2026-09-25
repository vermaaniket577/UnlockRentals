<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProfessionalLead extends Model
{
    use HasFactory;

    protected $table = 'professional_leads';

    protected $fillable = [
        'service_request_id',
        'professional_id',
        'customer_id',
        'lead_source',
        'status',
        'professional_response',
        'contacted_at',
        'accepted_at',
        'completed_at',
    ];

    protected $casts = [
        'contacted_at' => 'datetime',
        'accepted_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function serviceRequest(): BelongsTo
    {
        return $this->belongsTo(ServiceRequest::class, 'service_request_id');
    }

    public function professional(): BelongsTo
    {
        return $this->belongsTo(Professional::class, 'professional_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }

    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
}
