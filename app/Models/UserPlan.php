<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserPlan extends Model
{
    protected $fillable = [
        'user_id',
        'plan_id',
        'status',
        'contacts_used',
        'approved_at',
        'expires_at',
        'admin_note',
        'payment_reference',
    ];

    protected function casts(): array
    {
        return [
            'approved_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    /**
     * The user who purchased this plan.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The plan details.
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Contact views made under this subscription.
     */
    public function contactViews(): HasMany
    {
        return $this->hasMany(ContactView::class);
    }

    /**
     * Check if this subscription is currently active.
     */
    public function isActive(): bool
    {
        return $this->status === 'approved'
            && $this->expires_at
            && $this->expires_at->isFuture();
    }

    /**
     * Check if the user still has contact views remaining.
     */
    public function hasContactsRemaining(): bool
    {
        return $this->contacts_used < $this->plan->contact_limit;
    }

    /**
     * Get remaining contact views.
     */
    public function getRemainingContactsAttribute(): int
    {
        return max(0, $this->plan->contact_limit - $this->contacts_used);
    }

    /**
     * Scope: only approved & not expired.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'approved')
                     ->where('expires_at', '>', now());
    }

    /**
     * Scope: pending approval.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
