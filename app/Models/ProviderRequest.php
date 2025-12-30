<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProviderRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
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
}
