<?php

namespace App\Models;

use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Seller extends Model
{
    use Translatable;
    protected $fillable = ['name_en', 'name_ar',  'image', 'description_ar', 'description_en', 'rate','phone','availability'];
    protected $casts = [
        'rate' => 'float',
    ];


    protected $appends = ['name', 'description', 'image_url', 'rating'];
    protected $hidden = ['name_en', 'name_ar', 'description_ar', 'description_en'];

    public function getImageUrlAttribute()
    {
        try {
            if (empty($this->image)) {
                return null;
            }
            
            // If it's an old path format (public folder), convert it
            $image = trim($this->image);
            if (empty($image)) {
                return null;
            }
            
            // Reject temporary file paths and absolute paths
            if (stripos($image, 'tmp') !== false || 
                stripos($image, 'php') !== false || 
                stripos($image, 'xampp') !== false ||
                stripos($image, '\\') !== false ||
                preg_match('/^[a-zA-Z]:\\\\/', $image)) {
                return null;
            }
            
            // Use Storage to get the URL for stored files
            if (Storage::disk('public')->exists($image)) {
                return Storage::disk('public')->url($image);
            }
            
            return null;
        } catch (\Throwable $e) {
            return null;
        }
    }
    public function getNameAttribute()
    {
        return $this->getTranslatedAttribute('name');
    }
    public function getDescriptionAttribute()
    {
        return $this->getTranslatedAttribute('description');
    }



    public function scopeWhereLike(Builder $query, array $filters): Builder
    {
        foreach ($filters as $field => $value) {
            if (!empty($value)) {
                $query->where($field, 'LIKE', '%' . $value . '%');
            }
        }
        return $query;
    }


    public function ratings()
    {
        return $this->morphMany(\App\Models\Rating::class, 'rateable');
    }


    public function averageRating()
    {
        return $this->ratings()->avg('rating');
    }

    public function ratingsCount()
    {
        return $this->ratings()->count();
    }


    public function getRatingAttribute()
    {
        return round($this->ratings()->avg('rating'), 1);
    }
}
