<?php

namespace App\Policies;

use App\Models\Job;
use App\Models\User;
use App\Models\Admin;

class JobPolicy
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
    public function view($user, Job $job): bool
    {
        if ($user instanceof Admin) {
            return true;
        }
        
        if ($user instanceof User) {
            // Admin can view all
            if ($user->isAdmin()) {
                return true;
            }
            // Provider can only view their own jobs
            if ($user->isProvider()) {
                return $job->provider_id == $user->id;
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
    public function update($user, Job $job): bool
    {
        if ($user instanceof Admin) {
            return true;
        }
        
        if ($user instanceof User) {
            // Admin can update all
            if ($user->isAdmin()) {
                return true;
            }
            // Provider can only update their own jobs (and not published ones)
            if ($user->isProvider()) {
                return $job->provider_id == $user->id && $job->status != 'published';
            }
        }
        
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete($user, Job $job): bool
    {
        if ($user instanceof Admin) {
            return true;
        }
        
        if ($user instanceof User) {
            // Admin can delete all
            if ($user->isAdmin()) {
                return true;
            }
            // Provider can only delete their own jobs (and not published ones)
            if ($user->isProvider()) {
                return $job->provider_id == $user->id && $job->status != 'published';
            }
        }
        
        return false;
    }
}
