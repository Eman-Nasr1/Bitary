<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PodcastCategory extends Model
{
    protected $fillable = [
        'name_ar',
        'name_en',
    ];

    // Relationships
    public function podcasts()
    {
        return $this->belongsToMany(Podcast::class, 'podcast_category_pivot', 'podcast_category_id', 'podcast_id');
    }
}
