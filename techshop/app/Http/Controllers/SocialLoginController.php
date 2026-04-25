<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class SocialLoginController extends Controller
{
    /**
     * Redirect the user to the provider authentication page.
     */
    public function redirect(string $provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from the provider.
     */
    public function callback(string $provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();

            $user = User::where('email', $socialUser->getEmail())->first();

            if ($user) {
                // Accountkoppeling: update enkel met provider ID
                $user->update([
                    "{$provider}_id" => $socialUser->getId(),
                ]);
            } else {
                // Nieuwe registratie via Social Login
                $user = User::create([
                    'name' => $socialUser->getName() ?? $socialUser->getNickname(),
                    'email' => $socialUser->getEmail(),
                    "{$provider}_id" => $socialUser->getId(),
                    'password' => bcrypt(Str::random(24)),
                ]);
            }

            Auth::login($user);

            return redirect()->intended('/dashboard');
        } catch (\Exception $e) {
            return redirect('/login')->withErrors(['social' => 'Er is een probleem opgetreden met de authenticatie via ' . ucfirst($provider)]);
        }
    }
}
