<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class News extends Model
{
    protected $fillable = [
        'title_ar',
        'title_en',
        'summary_ar',
        'summary_en',
        'content_ar',
        'content_en',
        'cover_image',
        'category',
        'tags',
        'status',
        'published_at',
        'author_name',
    ];

    protected $casts = [
        'tags' => 'array',
        'published_at' => 'datetime',
    ];

    protected $appends = ['cover_image_url', 'category_label'];

    // Relationships
    public function comments()
    {
        return $this->hasMany(NewsComment::class);
    }

    public function approvedComments()
    {
        return $this->hasMany(NewsComment::class)->where('status', 'approved');
    }

    public function categoryModel(): BelongsTo
    {
        return $this->belongsTo(NewsCategory::class, 'category', 'slug');
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

    public function getCategoryLabelAttribute(): string
    {
        if ($this->relationLoaded('categoryModel') && $this->categoryModel) {
            return $this->categoryModel->name;
        }

        $matchedCategory = NewsCategory::where('slug', $this->category)->first();
        if ($matchedCategory) {
            return $matchedCategory->name;
        }

        return ucfirst(str_replace('_', ' ', (string) $this->category));
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }
}
