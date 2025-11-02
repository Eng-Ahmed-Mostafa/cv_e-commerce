<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class SocialiteController extends Controller
{
    public function redirectToGithub()
    {
        return Socialite::driver('github')->redirect();
    }

    public function handleGithubCallback()
    {
        try {
            $githubUser = Socialite::driver('github')->user();
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'فشل تسجيل الدخول عبر GitHub');
        }

        $user = User::where('email', $githubUser->getEmail())->first();

        if (!$user) {

            $user = User::create([
                'name' => $githubUser->getName() ?? $githubUser->getNickname(),
                'email' => $githubUser->getEmail(),
                'password' => bcrypt(uniqid()), 
            ]);
        }

        Auth::login($user);

        return redirect('/dashboard');
    }
}
