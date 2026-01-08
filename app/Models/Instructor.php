<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Instructor extends Model
{
    protected $fillable = [
        'name_ar',
        'name_en',
        'bio_ar',
        'bio_en',
        'email',
        'phone',
        'image',
    ];

    protected $appends = ['image_url'];

    // Relationships
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_instructor');
    }

    public function podcastEpisodes()
    {
        return $this->hasMany(PodcastEpisode::class);
    }

    // Accessor for image URL
    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return null;
        }

        if (Storage::disk('public')->exists($this->image)) {
            return url('/storage/' . $this->image);
        }

        return null;
    }
}
