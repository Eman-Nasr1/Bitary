<?php

namespace App\Modules\User\Repositories;

use App\Models\User;
use App\Modules\User\Requests\CreateUserRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendOtp;
use App\Modules\Shared\Repositories\BaseRepository;

class UsersRepository extends BaseRepository
{
    public function __construct(private User $model)
    {
        parent::__construct($model);
    }
    public function getAuthenticatedUser(): ?User
    {
        return auth('sanctum')->user();
    }
    public function create($user): User
    {
        $isVerified = $user['is_verified'] ?? false;
        $isProvider = $user['is_provider'] ?? false;
        
        $userData = [
            'name' => $user['name'],
            'family_name' => $user['family_name'] ?? null,
            'age' => $user['age'] ?? null,
            'gender' => $user['gender'] ?? null,
            'city_id' => $user['city_id'] ?? null,
            'address' => $user['address'] ?? null,
            'email' => $user['email'],
            'phone' => $user['phone'],
            'password' => Hash::make($user['password']),
            'is_verified' => $isVerified,
            'is_provider' => $isProvider,
            'is_active' => $user['is_active'] ?? true,
        ];

        // Only send OTP if user is not verified
        if (!$isVerified) {
            $otp = rand(100000, 999999);
            $userData['otp_code'] = $otp;
            $userData['otp_expires_at'] = Carbon::now()->addMinutes(10);
        }

        $user = User::create($userData);

        // Send OTP email only if user is not verified
        if (!$isVerified && isset($otp)) {
            Mail::to($user->email)->send(new SendOtp($otp));
        }

        return $user;
    }
  
   public function verifyUserOtp($email, $otp)
    {
        $user = User::where('email', $email)
            ->where('otp_code', $otp)
            ->where('otp_expires_at', '>=', now())
            ->first();

        if (! $user) {
            return false;
        }

        $user->update([
            'is_verified' => true,
            'otp_code' => null,
            'otp_expires_at' => null,
        ]);

        return $user;
    }
    public function login(array $credentials)
    {
        // Login with email or phone
        if (isset($credentials['email'])) {
            $user = User::where('email', $credentials['email'])->first();
        } elseif (isset($credentials['phone'])) {
            $user = User::where('phone', $credentials['phone'])->first();
        } else {
            return null;
        }
    
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return null;
        }
    
        return $user;
    }
 
    
    

    public function getUserByEmail($email){
        $user = $this->model::where('email',$email)->first();
        return $user;
    }

    public function resendOtp($email): ?User
    {
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            return null;
        }

        // Generate new OTP
        $otp = rand(100000, 999999);
        
        // Update user with new OTP
        $user->update([
            'otp_code' => $otp,
            'otp_expires_at' => Carbon::now()->addMinutes(10),
        ]);

        // Send OTP email
        Mail::to($user->email)->send(new SendOtp($otp));

        return $user;
    }
}
