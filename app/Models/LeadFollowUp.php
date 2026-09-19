<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeadFollowUp extends Model
{
    use HasFactory;

    protected $fillable = [
        'lead_id',
        'assigned_to',
        'scheduled_at',
        'status',
        'note',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    public function assignedAgent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function getNotesAttribute()
    {
        return $this->note;
    }

    public function setNotesAttribute($value)
    {
        $this->attributes['note'] = $value;
    }

    public function getFollowUpTypeAttribute()
    {
        return 'call';
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('scheduled_at', today());
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'pending')->where('scheduled_at', '<', now());
    }

    public function scopeUpcoming($query)
    {
        return $query->where('status', 'pending')->where('scheduled_at', '>', now());
    }
}
