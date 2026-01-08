<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Podcast extends Model
{
    protected $fillable = [
        'title_ar',
        'title_en',
        'description_ar',
        'description_en',
        'cover_image',
        'podcast_type',
        'status',
        'youtube_channel_url',
        'spotify_url',
        'apple_podcasts_url',
    ];

    protected $appends = ['cover_image_url'];

    // Relationships
    public function episodes()
    {
        return $this->hasMany(PodcastEpisode::class);
    }

    public function categories()
    {
        return $this->belongsToMany(PodcastCategory::class, 'podcast_category_pivot', 'podcast_id', 'podcast_category_id');
    }

    // Accessor for cover image URL
    public function getCoverImageUrlAttribute()
    {
        if (!$this->cover_image) {
            return null;
        }

        if (Storage::disk('public')->exists($this->cover_image)) {
            return url('/storage/' . $this->cover_image);
        }

        return null;
    }
}
