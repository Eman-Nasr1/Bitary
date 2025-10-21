<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\SocialAccount;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;



class SocialApiController extends Controller
{
    public function redirect(string $provider)
    {

        $url = $provider === 'facebook'
            ? Socialite::driver('facebook')->scopes(['email'])->fields(['name','first_name','last_name','email'])->stateless()->redirect()->getTargetUrl()
            : Socialite::driver('google')->scopes(['openid','profile','email'])->stateless()->redirect()->getTargetUrl();

        return response()->json(['auth_url' => $url]);
    }

    public function callback(string $provider)
    {
     $socialUser = Socialite::driver($provider)->stateless()->user();


        $providerId = $socialUser->getId();
        $email      = $socialUser->getEmail();
   
        $name       = $socialUser->getName() ?: 'User';
        $avatar     = $socialUser->getAvatar();

        $account = SocialAccount::where('provider_name',$provider)
                    ->where('provider_id',$providerId)->first();

        if ($account) {
            $user = $account->user;
        } else {
            $user = $email ? User::where('email',$email)->first() : null;
            if (! $user) {
                $user = User::create([
                    'name' => $name,
                    'email' => $email ?? "{$provider}_{$providerId}@example.local",
                    'password' => bcrypt(Str::random(32)),
                    'email_verified_at' => now(),
                ]);
            }
            SocialAccount::create([
                'user_id' => $user->id,
                'provider_name' => $provider,
                'provider_id' => $providerId,
                'avatar' => $avatar,
            ]);
        }

        // إصدار توكن (Sanctum)
        $token = $user->createToken('mobile')->plainTextToken;

        // هنا ممكن ترجعي Redirect لـ Deep Link: myapp://oauth?token=...
        // أو JSON:
        return response()->json([
            'token' => $token,
            'user'  => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
        ]);
    }
}
