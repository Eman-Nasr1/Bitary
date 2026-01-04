<?php

namespace App\Policies;

use App\Models\JobApplication;
use App\Models\User;
use App\Models\Admin;

class JobApplicationPolicy
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
    public function view($user, JobApplication $application): bool
    {
        if ($user instanceof Admin) {
            return true;
        }
        
        if ($user instanceof User) {
            // Admin can view all
            if ($user->isAdmin()) {
                return true;
            }
            // Provider can only view applications for their jobs
            if ($user->isProvider()) {
                return $application->job && $application->job->provider_id == $user->id;
            }
        }
        
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update($user, JobApplication $application): bool
    {
        if ($user instanceof Admin) {
            return true;
        }
        
        if ($user instanceof User) {
            // Admin can update all
            if ($user->isAdmin()) {
                return true;
            }
            // Provider can only update applications for their jobs
            if ($user->isProvider()) {
                return $application->job && $application->job->provider_id == $user->id;
            }
        }
        
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete($user, JobApplication $application): bool
    {
        // Only admin can delete applications
        return $user instanceof Admin;
    }
}
