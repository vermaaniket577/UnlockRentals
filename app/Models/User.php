<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the properties owned by this user.
     */
    public function properties(): HasMany
    {
        return $this->hasMany(Property::class);
    }

    /**
     * Get the inquiries sent by this user.
     */
    public function inquiries(): HasMany
    {
        return $this->hasMany(Inquiry::class);
    }

    /**
     * Get all plan subscriptions for this user.
     */
    public function userPlans(): HasMany
    {
        return $this->hasMany(UserPlan::class);
    }

    /**
     * Get all contact views by this user.
     */
    public function contactViews(): HasMany
    {
        return $this->hasMany(ContactView::class);
    }

    /**
     * Get the user's current active plan subscription.
     */
    public function activePlan(): ?UserPlan
    {
        return $this->userPlans()
            ->active()
            ->with('plan')
            ->latest('approved_at')
            ->first();
    }

    /**
     * Check if this user has an active plan.
     */
    public function hasActivePlan(): bool
    {
        return $this->activePlan() !== null;
    }

    /**
     * Check if user can view a property's owner contact.
     * Admins and the property owner themselves can always view.
     */
    public function canViewContact(Property $property): bool
    {
        // Admin always can
        if ($this->isAdmin()) return true;

        // Owner can see their own property contacts
        if ($this->id === $property->user_id) return true;

        // Already viewed this contact? Free re-view
        if ($this->hasViewedContact($property)) return true;

        // Has active plan with remaining contacts?
        $plan = $this->activePlan();
        return $plan && $plan->hasContactsRemaining();
    }

    /**
     * Check if user has already unlocked a specific property's contact.
     */
    public function hasViewedContact(Property $property): bool
    {
        return $this->contactViews()
            ->where('property_id', $property->id)
            ->exists();
    }

    /**
     * Unlock a property's owner contact (deducts from plan).
     * Returns true if successful, false if no plan or limit reached.
     */
    public function viewContact(Property $property): bool
    {
        // Already unlocked? No need to deduct again
        if ($this->hasViewedContact($property)) return true;

        $plan = $this->activePlan();
        if (!$plan || !$plan->hasContactsRemaining()) return false;

        ContactView::create([
            'user_id' => $this->id,
            'property_id' => $property->id,
            'user_plan_id' => $plan->id,
        ]);

        $plan->increment('contacts_used');
        return true;
    }

    /**
     * Check if user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is an owner.
     */
    public function isOwner(): bool
    {
        return $this->role === 'owner';
    }

    /**
     * Check if user is a tenant.
     */
    public function isTenant(): bool
    {
        return $this->role === 'tenant';
    }
}
