<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

class JobApplication extends Model
{
    protected $fillable = [
        'job_id',
        'provider_id',
        'full_name',
        'phone',
        'email',
        'current_location',
        'cover_letter',
        'extra_info',
        'cv_file',
        'status',
        'notes',
    ];

    protected $appends = ['cv_url'];

    // Relationships
    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

    // Scopes
    public function scopeForProvider(Builder $query, int $providerId): Builder
    {
        return $query->whereHas('job', function($q) use ($providerId) {
            $q->where('provider_id', $providerId);
        });
    }

    public function scopeNew(Builder $query): Builder
    {
        return $query->where('status', 'new');
    }

    // Accessor
    public function getCvUrlAttribute()
    {
        if (!$this->cv_file) {
            return null;
        }
        return Storage::disk('public')->url($this->cv_file);
    }
}
