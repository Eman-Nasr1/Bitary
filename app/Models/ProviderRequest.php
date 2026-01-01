<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

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
}
