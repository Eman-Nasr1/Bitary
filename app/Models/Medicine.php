<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Medicine extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_en',
        'name_ar',
        'title_en',
        'title_ar',
        'price',
        'discount_percentage',
        'quantity',
        'rate',
        'image',
        'description_en',
        'description_ar',
        'weight',
        'dimensions',
        'category_id',
        'seller_id',
        'product_type_en',
        'product_type_ar',
        'manufacturer_en',
        'manufacturer_ar',
        'return_policy_en',
        'return_policy_ar',
        'exchange_policy_en',
        'exchange_policy_ar',
    ];

    protected $casts = [
        'rate' => 'float',
    ];

    protected $appends = [
        'name',
        'description',
        'rating',
        'title',
        'product_type',
        'manufacturer',
        'return_policy',
        'exchange_policy',
        'image_url' // شيلها لو مش عامل accessor
    ];

    protected $hidden = [
        'name_en',
        'name_ar',
        'title_en',
        'title_ar',
        'description_en',
        'description_ar',
        'product_type_en',
        'product_type_ar',
        'manufacturer_en',
        'manufacturer_ar',
        'return_policy_en',
        'return_policy_ar',
        'exchange_policy_en',
        'exchange_policy_ar',
    ];
    public function favoritedBy()
    {
        return $this->morphToMany(User::class, 'favoritable', 'favorites')->withTimestamps();
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
    public function getImageUrlAttribute()
    {
        return $this->image
            ? asset($this->image)
            : null;
    }
    public function animals()
    {
        return $this->belongsToMany(Animal::class, 'animal_medicine');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class); // أو Seller::class لو غيرت العمود لـ seller_id
    }

    public function getFinalPriceAttribute()
    {
        return $this->discount_percentage > 0
            ? $this->price - ($this->price * $this->discount_percentage / 100)
            : $this->price;
    }

    public function getNameAttribute()
    {
        return $this->getTranslatedAttribute('name');
    }

    public function getTitleAttribute()
    {
        return $this->getTranslatedAttribute('title');
    }

    public function getProductTypeAttribute()
    {
        return $this->getTranslatedAttribute('product_type');
    }

    public function getManufacturerAttribute()
    {
        return $this->getTranslatedAttribute('manufacturer');
    }

    public function getReturnPolicyAttribute()
    {
        return $this->getTranslatedAttribute('return_policy');
    }

    public function getExchangePolicyAttribute()
    {
        return $this->getTranslatedAttribute('exchange_policy');
    }

    public function getDescriptionAttribute()
    {
        return $this->getTranslatedAttribute('description');
    }

    protected function getTranslatedAttribute($field)
    {
        $locale = app()->getLocale();
        $column = $field . '_' . $locale;
        return $this->{$column};
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
