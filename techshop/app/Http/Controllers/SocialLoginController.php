<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialLoginController extends Controller
{
    /**
     * Redirect the user to the provider authentication page.
     */
    public function redirect(string $provider)
    {
        if ($provider === 'github') {
            return Socialite::driver($provider)->scopes(['read:user', 'user:email'])->redirect();
        }

        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from the provider.
     */
    public function callback(string $provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();

            if (! $socialUser->getEmail()) {
                throw new \Exception('Geen e-mailadres ontvangen van '.ucfirst($provider));
            }

            $user = User::where('email', $socialUser->getEmail())->first();

            if ($user) {
                // Accountkoppeling: update enkel met provider ID
                $user->update([
                    "{$provider}_id" => $socialUser->getId(),
                ]);
            } else {
                // Nieuwe registratie via Social Login
                $user = User::create([
                    'name' => $socialUser->getName() ?? $socialUser->getNickname() ?? 'User',
                    'email' => $socialUser->getEmail(),
                    "{$provider}_id" => $socialUser->getId(),
                    'role' => 'customer',
                    'password' => Hash::make(Str::random(24)),
                ]);
            }

            Auth::login($user);

            return redirect()->intended('/dashboard');
        } catch (\Exception $e) {
            Log::error('Social Login Error ['.$provider.']: '.$e->getMessage());

            return redirect('/login')->withErrors(['social' => 'Er is een probleem opgetreden met de authenticatie via '.ucfirst($provider).': '.$e->getMessage()]);
        }
    }
}
