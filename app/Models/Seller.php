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
        return $this->image
            ? asset($this->image)
            : null;
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
