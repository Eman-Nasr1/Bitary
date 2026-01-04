<?php

namespace App\Policies;

use App\Models\Medicine;
use App\Models\User;
use App\Models\Admin;

class MedicinePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny($user): bool
    {
        if ($user instanceof Admin) {
            return true;
        }
        return $user instanceof User && ($user->isProvider() || $user->isAdmin());
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view($user, Medicine $medicine): bool
    {
        if ($user instanceof Admin) {
            return true;
        }
        
        if ($user instanceof User) {
            // Admin can view all
            if ($user->isAdmin()) {
                return true;
            }
            // Provider can only view their own medicines
            if ($user->isProvider()) {
                return $medicine->provider_id == $user->id;
            }
        }
        
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create($user): bool
    {
        if ($user instanceof Admin) {
            return true;
        }
        return $user instanceof User && ($user->isProvider() || $user->isAdmin());
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update($user, Medicine $medicine): bool
    {
        if ($user instanceof Admin) {
            return true;
        }
        
        if ($user instanceof User) {
            // Admin can update all
            if ($user->isAdmin()) {
                return true;
            }
            // Provider can only update their own medicines
            if ($user->isProvider()) {
                return $medicine->provider_id == $user->id;
            }
        }
        
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete($user, Medicine $medicine): bool
    {
        if ($user instanceof Admin) {
            return true;
        }
        
        if ($user instanceof User) {
            // Admin can delete all
            if ($user->isAdmin()) {
                return true;
            }
            // Provider can only delete their own medicines
            if ($user->isProvider()) {
                return $medicine->provider_id == $user->id;
            }
        }
        
        return false;
    }
}
