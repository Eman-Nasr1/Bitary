<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketPrice extends Model
{
    protected $fillable = [
        'product_name_ar',
        'product_name_en',
        'price',
        'currency',
        'change_percent',
        'trend',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'change_percent' => 'decimal:2',
    ];

    // Auto-calculate trend based on change_percent
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($marketPrice) {
            if ($marketPrice->change_percent !== null) {
                if ($marketPrice->change_percent > 0) {
                    $marketPrice->trend = 'up';
                } elseif ($marketPrice->change_percent < 0) {
                    $marketPrice->trend = 'down';
                } else {
                    $marketPrice->trend = 'stable';
                }
            } else {
                $marketPrice->trend = 'stable';
            }
        });
    }
}
