<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class ProviderLoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/dashboard/provider/jobs';

    public function __construct()
    {
        $this->middleware('guest:web')->except('logout');
    }

    public function showLoginForm()
    {
        return view('auth.provider-login');
    }

    protected function attemptLogin(Request $request)
    {
        $credentials = $this->credentials($request);
        
        // Check if user is provider
        $user = \App\Models\User::where('email', $credentials['email'])->first();
        
        if (!$user || (!$user->isProvider() && !$user->isAdmin())) {
            return false;
        }

        return Auth::guard('web')->attempt(
            $credentials,
            $request->boolean('remember')
        );
    }

    protected function authenticated(Request $request, $user)
    {
        if ($user->isProvider() || $user->isAdmin()) {
            return redirect()->intended($this->redirectPath());
        }
        
        Auth::guard('web')->logout();
        return redirect()->back()->withErrors([
            'email' => 'You do not have access to the provider dashboard.'
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/provider/login');
    }
}
