<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Job extends Model
{
    protected $table = 'job_listings';

    protected $fillable = [
        'specialization_id',
        'provider_id',
        'city_id',
        'title_ar',
        'title_en',
        'job_type',
        'image',
        'description_ar',
        'description_en',
        'responsibilities_ar',
        'responsibilities_en',
        'qualifications_ar',
        'qualifications_en',
        'apply_method',
        'whatsapp_number',
        'email_address',
        'external_link',
        'status',
        'rejection_reason',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    // Relationships
    public function specialization()
    {
        return $this->belongsTo(JobSpecialization::class, 'specialization_id');
    }

    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function applications()
    {
        return $this->hasMany(JobApplication::class);
    }

    // Scopes
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }

    public function scopeForProvider(Builder $query, int $providerId): Builder
    {
        return $query->where('provider_id', $providerId);
    }
}
