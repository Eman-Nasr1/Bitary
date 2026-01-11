<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProviderRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'provider_type',
        'entity_name',
        'specialty',
        'degree',
        'phone',
        'whatsapp',
        'email',
        'address',
        'google_maps_link',
        'image',
        'id_document',
        'license_document',
        'status',
        'admin_note',
        'reviewed_at',
        'reviewed_by',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    protected $appends = ['image_url'];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviewedBy()
    {
        return $this->belongsTo(Admin::class, 'reviewed_by');
    }

    /**
     * Scope for filtering with LIKE for text fields and exact match for enum fields
     */
    public function scopeWhereLike(Builder $query, array $filters): Builder
    {
        foreach ($filters as $field => $value) {
            if (!empty($value)) {
                // Use exact match for enum fields
                if (in_array($field, ['status', 'provider_type'])) {
                    $query->where($field, $value);
                } else {
                    // Use LIKE for text fields
                    $query->where($field, 'LIKE', '%' . $value . '%');
                }
            }
        }
        return $query;
    }

    /**
     * Get the image URL attribute
     */
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

            if (preg_match('/^[a-zA-Z]:\\\\/', $image) || strpos($image, '/') === 0) {
                Log::warning('ProviderRequest: Rejected absolute path', ['image' => $image, 'id' => $this->id ?? null]);
                return null;
            }

            if (!Storage::disk('public')->exists($image)) {
                Log::warning('ProviderRequest: Image file does not exist', ['image' => $image, 'id' => $this->id ?? null]);
                return null;
            }
            
            // Generate absolute URL using the current request's domain
            // This ensures the URL uses the correct domain (vet-icon.com) instead of localhost
            // Fallback to config('app.url') if no request is available (e.g., console commands)
            $baseUrl = request() ? request()->getSchemeAndHttpHost() : config('app.url');
            $url = $baseUrl . '/storage/' . $image;
            Log::info('ProviderRequest: Generated image URL', ['image' => $image, 'url' => $url, 'id' => $this->id ?? null]);
            return $url;
        } catch (\Throwable $e) {
            Log::error('ProviderRequest image URL error: ' . $e->getMessage(), ['image' => $this->attributes['image'] ?? null, 'provider_request_id' => $this->id ?? null, 'trace' => $e->getTraceAsString()]);
            return null;
        }
    }
}
