<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'family_name',
        'age',
        'gender',
        'city_id',
        'address',
        'phone',
        'email',
        'password',
        'social_id',
        'social_provider',
        'is_verified',
        'is_provider',
        'role',
        'otp_code',
        'otp_expires_at'
    ];



    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = ['password', 'otp_code', 'remember_token',];
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'is_active' => 'boolean',
            'is_verified' => 'boolean',
            'is_provider' => 'boolean',
            'otp_expires_at' => 'datetime',
        ];
    }


    public function toggleStatus()
    {
        $this->is_active = !$this->is_active;
        $this->save();
    }

    public function toggleProviderStatus()
    {
        $this->is_provider = !$this->is_provider;
        $this->save();
    }

    public function favorites()
    {
        return $this->morphedByMany(
            Model::class, // نموذج عام - هنستخدم طريقة ديناميكية لاحقًا
            'favoritable',
            'favorites'
        )->withTimestamps();
    }

    public function favoriteItems()
    {
        return $this->hasMany(Favorite::class);
    }

    public function providerRequest()
    {
        return $this->hasOne(ProviderRequest::class);
    }

    public function jobs()
    {
        return $this->hasMany(Job::class, 'provider_id');
    }

    public function jobApplications()
    {
        return $this->hasMany(JobApplication::class, 'provider_id');
    }

    public function medicines()
    {
        return $this->hasMany(Medicine::class, 'provider_id');
    }

    // Helper methods
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isProvider(): bool
    {
        return $this->role === 'provider' || $this->is_provider;
    }

    public function isUser(): bool
    {
        return $this->role === 'user' || (!$this->is_provider && !$this->isAdmin());
    }
}
