<?php

namespace App\Policies;

use App\Models\Property;
use App\Models\User;

class PropertyPolicy
{
    /**
     * Determine if the user can view any properties.
     */
    public function viewAny(?User $user): bool
    {
        return true; // Anyone can browse
    }

    /**
     * Determine if the user can view the property.
     */
    public function view(?User $user, Property $property): bool
    {
        // Anyone can view approved properties
        if ($property->isApproved()) {
            return true;
        }

        // Owner can view their own properties regardless of status
        if ($user && $user->id === $property->user_id) {
            return true;
        }

        // Admin can view any property
        if ($user && $user->isAdmin()) {
            return true;
        }

        return false;
    }

    /**
     * Determine if the user can create properties.
     */
    public function create(User $user): bool
    {
        return $user->isOwner() || $user->isAdmin();
    }

    /**
     * Determine if the user can update the property.
     */
    public function update(User $user, Property $property): bool
    {
        return $user->id === $property->user_id || $user->isAdmin();
    }

    /**
     * Determine if the user can delete the property.
     */
    public function delete(User $user, Property $property): bool
    {
        return $user->id === $property->user_id || $user->isAdmin();
    }
}
