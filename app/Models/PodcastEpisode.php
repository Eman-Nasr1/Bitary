<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PodcastEpisode extends Model
{
    protected $fillable = [
        'podcast_id',
        'instructor_id',
        'title_ar',
        'title_en',
        'description_ar',
        'description_en',
        'thumbnail_image',
        'episode_type',
        'youtube_url',
        'audio_file',
        'spotify_url',
        'apple_podcasts_url',
        'published_at',
        'status',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    protected $appends = ['thumbnail_image_url', 'audio_file_url'];

    // Relationships
    public function podcast()
    {
        return $this->belongsTo(Podcast::class);
    }

    public function instructor()
    {
        return $this->belongsTo(Instructor::class);
    }

    // Accessor for thumbnail image URL
    public function getThumbnailImageUrlAttribute()
    {
        if (!$this->thumbnail_image) {
            return null;
        }

        if (Storage::disk('public')->exists($this->thumbnail_image)) {
            return url('/storage/' . $this->thumbnail_image);
        }

        return null;
    }

    // Accessor for audio file URL
    public function getAudioFileUrlAttribute()
    {
        if (!$this->audio_file) {
            return null;
        }

        if (Storage::disk('public')->exists($this->audio_file)) {
            return url('/storage/' . $this->audio_file);
        }

        return null;
    }
}
