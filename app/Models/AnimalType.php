<?php

namespace App\Models;

use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class AnimalType extends Model
{
    use Translatable;
    
    protected $fillable = ['name_en', 'name_ar', 'image'];

    protected $appends = ['name', 'image_url'];
    protected $hidden = ['name_en', 'name_ar'];

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

    public function scopeWhereLike(Builder $query, array $filters): Builder
    {
        foreach ($filters as $field => $value) {
            if (!empty($value)) {
                $query->where($field, 'LIKE', '%' . $value . '%');
            }
        }
        return $query;
    }

    public function animals()
    {
        return $this->hasMany(Animal::class);
    }
}
