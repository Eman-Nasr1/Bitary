<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

class Course extends Model
{
    protected $fillable = [
        'title_ar',
        'title_en',
        'image',
        'category',
        'level',
        'language',
        'overview_ar',
        'overview_en',
        'description_ar',
        'description_en',
        'intro_video_url',
        'intro_video_iframe',
        'duration_weeks',
        'hours_per_week',
        'days_per_week',
        'certificate_available',
        'certificate_type',
        'is_free',
        'price',
        'currency',
        'discount_percent',
        'payment_method',
        'whatsapp_join_link',
        'status',
    ];

    protected $appends = ['image_url'];

    protected $casts = [
        'certificate_available' => 'boolean',
        'is_free' => 'boolean',
        'price' => 'decimal:2',
        'discount_percent' => 'decimal:2',
        'duration_weeks' => 'integer',
        'hours_per_week' => 'integer',
        'days_per_week' => 'integer',
    ];

    // Relationships
    public function instructors()
    {
        return $this->belongsToMany(Instructor::class, 'course_instructor');
    }

    public function specializations()
    {
        return $this->belongsToMany(Specialization::class, 'course_specialization');
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

    // Scopes
    public function scopeWhereLike(Builder $query, array $filters): Builder
    {
        foreach ($filters as $field => $value) {
            if (!empty($value)) {
                if (in_array($field, ['status', 'category', 'level', 'language', 'payment_method'])) {
                    $query->where($field, $value);
                } else {
                    $query->where($field, 'LIKE', '%' . $value . '%');
                }
            }
        }
        return $query;
    }
}
