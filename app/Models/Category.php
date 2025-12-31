<?php

namespace App\Models;

use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class Category extends Model
{
    use Translatable;
    protected $fillable = ['name_en', 'name_ar',  'image'];


    protected $appends = ['image_url'];
    protected $hidden = [];

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
                Log::warning('Category: Rejected absolute path', ['image' => $image, 'id' => $this->id ?? null]);
                return null;
            }
            
            // Check if file exists in storage
            if (!Storage::disk('public')->exists($image)) {
                Log::warning('Category: Image file does not exist', ['image' => $image, 'id' => $this->id ?? null]);
                return null;
            }
            
            // Generate relative URL to work with any domain/port
            $url = '/storage/' . $image;
            Log::info('Category: Generated image URL', ['image' => $image, 'url' => $url, 'id' => $this->id ?? null]);
            return $url;
        } catch (\Throwable $e) {
            Log::error('Category image URL error: ' . $e->getMessage(), [
                'image' => $this->attributes['image'] ?? null,
                'category_id' => $this->id ?? null,
                'trace' => $e->getTraceAsString()
            ]);
            return null;
        }
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
}
