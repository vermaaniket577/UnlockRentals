<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'visitor_id',
        'user_id',
        'property_id',
        'owner_id',
        'assigned_to',
        'lead_source',
        'lead_status',
        'lead_stage',
        'name',
        'mobile',
        'email',
        'preferred_city',
        'preferred_locality',
        'property_type',
        'purpose',
        'budget_min',
        'budget_max',
        'bedrooms',
        'furnished_status',
        'move_in_date',
        'message',
        'whatsapp_opt_in',
        'marketing_opt_in',
        'consent_text',
        'consent_at',
        'engagement_score',
        'next_follow_up_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'budget_min' => 'decimal:2',
            'budget_max' => 'decimal:2',
            'move_in_date' => 'date',
            'consent_at' => 'datetime',
            'next_follow_up_at' => 'datetime',
            'whatsapp_opt_in' => 'boolean',
            'marketing_opt_in' => 'boolean',
            'engagement_score' => 'integer',
        ];
    }

    public function visitor(): BelongsTo
    {
        return $this->belongsTo(Visitor::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function assignedAgent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function latestFollowUp(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(LeadFollowUp::class)->latestOfMany('scheduled_at');
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(CrmAuditLog::class, 'target_id')->where('target_type', 'lead');
    }

    public function getPhoneAttribute()
    {
        return $this->mobile;
    }

    public function setPhoneAttribute($value)
    {
        $this->attributes['mobile'] = $value;
    }

    public function getIntentAttribute()
    {
        return $this->purpose;
    }

    public function setIntentAttribute($value)
    {
        $this->attributes['purpose'] = $value;
    }

    public function getSourceAttribute()
    {
        return $this->lead_source;
    }

    public function setSourceAttribute($value)
    {
        $this->attributes['lead_source'] = $value;
    }

    public function getBhkPreferenceAttribute()
    {
        return $this->bedrooms;
    }

    public function setBhkPreferenceAttribute($value)
    {
        $this->attributes['bedrooms'] = $value;
    }

    public function getLeadScoreAttribute()
    {
        return $this->engagement_score;
    }

    public function setLeadScoreAttribute($value)
    {
        $this->attributes['engagement_score'] = $value;
    }

    public function followUps(): HasMany
    {
        return $this->hasMany(LeadFollowUp::class)->orderBy('scheduled_at', 'asc');
    }

    public function communicationLogs(): HasMany
    {
        return $this->hasMany(CommunicationLog::class)->latest('sent_at');
    }

    public function consentRecords(): HasMany
    {
        return $this->hasMany(ConsentRecord::class);
    }

    /**
     * Scope: filter by lead status.
     */
    public function scopeStatus($query, ?string $status)
    {
        if (filled($status)) {
            return $query->where('lead_status', $status);
        }
        return $query;
    }

    /**
     * Scope: filter by stage.
     */
    public function scopeStage($query, ?string $stage)
    {
        if (filled($stage)) {
            return $query->where('lead_stage', $stage);
        }
        return $query;
    }

    /**
     * Scope: unassigned leads.
     */
    public function scopeUnassigned($query)
    {
        return $query->whereNull('assigned_to');
    }

    /**
     * Scope: WhatsApp opted in.
     */
    public function scopeWhatsappOptedIn($query)
    {
        return $query->where('whatsapp_opt_in', true);
    }

    /**
     * Get clean 10-digit phone for India.
     */
    public function getCleanPhoneAttribute(): string
    {
        $digits = preg_replace('/[^0-9]/', '', (string) $this->mobile);
        if (strlen($digits) >= 10) {
            return substr($digits, -10);
        }
        return $digits;
    }

    /**
     * Get international phone for WhatsApp (91XXXXXXXXXX).
     */
    public function getWhatsappPhoneAttribute(): string
    {
        $clean = $this->clean_phone;
        return '91' . $clean;
    }

    /**
     * Get formatted display phone (+91 XXXXX XXXXX).
     */
    public function getFormattedPhoneAttribute(): string
    {
        $clean = $this->clean_phone;
        if (strlen($clean) === 10) {
            return '+91 ' . substr($clean, 0, 5) . ' ' . substr($clean, 5);
        }
        return $this->mobile;
    }

    /**
     * Color classes for Lead Status.
     */
    public function getStatusBadgeAttribute(): array
    {
        return match ($this->lead_status) {
            'new' => ['bg' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300', 'label' => 'New Lead'],
            'contacted' => ['bg' => 'bg-cyan-100 text-cyan-800 dark:bg-cyan-900/40 dark:text-cyan-300', 'label' => 'Contacted'],
            'interested' => ['bg' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300', 'label' => 'Interested'],
            'follow_up' => ['bg' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300', 'label' => 'Follow Up'],
            'visit_scheduled' => ['bg' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/40 dark:text-purple-300', 'label' => 'Visit Scheduled'],
            'negotiation' => ['bg' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/40 dark:text-indigo-300', 'label' => 'Negotiation'],
            'converted' => ['bg' => 'bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300', 'label' => 'Converted Deal'],
            'closed' => ['bg' => 'bg-slate-100 text-slate-800 dark:bg-slate-800 dark:text-slate-300', 'label' => 'Closed'],
            'lost' => ['bg' => 'bg-rose-100 text-rose-800 dark:bg-rose-900/40 dark:text-rose-300', 'label' => 'Lost'],
            'invalid' => ['bg' => 'bg-zinc-100 text-zinc-600 dark:bg-zinc-800 dark:text-zinc-400', 'label' => 'Invalid/Spam'],
            default => ['bg' => 'bg-slate-100 text-slate-700', 'label' => ucfirst($this->lead_status)],
        };
    }
}
