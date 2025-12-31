<?php

namespace App\Models;

use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

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
            $image = $this->attributes['image'] ?? null;
            if (empty($image)) {
                return null;
            }
            $image = trim($image);
            if (empty($image)) {
                return null;
            }
            
            // Only reject obviously invalid paths (absolute Windows paths, absolute Unix paths starting with /)
            if (preg_match('/^[a-zA-Z]:\\\\/', $image) || strpos($image, '/') === 0) {
                Log::warning('Seller: Rejected absolute path', ['image' => $image, 'id' => $this->id ?? null]);
                return null;
            }
            
            // Check if file exists in storage
            if (!Storage::disk('public')->exists($image)) {
                Log::warning('Seller: Image file does not exist', ['image' => $image, 'id' => $this->id ?? null]);
                return null;
            }
            
            // Generate relative URL to work with any domain/port
            $url = '/storage/' . $image;
            Log::info('Seller: Generated image URL', ['image' => $image, 'url' => $url, 'id' => $this->id ?? null]);
            return $url;
        } catch (\Throwable $e) {
            Log::error('Seller image URL error: ' . $e->getMessage(), [
                'image' => $this->attributes['image'] ?? null,
                'seller_id' => $this->id ?? null,
                'trace' => $e->getTraceAsString()
            ]);
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
