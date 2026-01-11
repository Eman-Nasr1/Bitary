<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class StaticPage extends Model
{
    protected $fillable = [
        'title_ar',
        'title_en',
        'slug',
        'description_ar',
        'description_en',
        'content_ar',
        'content_en',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    // Auto-generate slug from title_ar if not provided
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($staticPage) {
            if (empty($staticPage->slug)) {
                $staticPage->slug = Str::slug($staticPage->title_ar);
            }
        });

        static::updating(function ($staticPage) {
            if ($staticPage->isDirty('title_ar') && empty($staticPage->slug)) {
                $staticPage->slug = Str::slug($staticPage->title_ar);
            }
        });
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }
}
