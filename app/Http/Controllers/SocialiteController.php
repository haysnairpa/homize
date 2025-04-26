<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Check if user already exists with this google_id
            $user = User::where('google_id', $googleUser->id)->first();
            
            // If user doesn't exist, check if the email exists
            if (!$user) {
                $user = User::where('email', $googleUser->email)->first();
                
                // If user with this email exists, update their google_id
                if ($user) {
                    $user->update([
                        'google_id' => $googleUser->id,
                    ]);
                } else {
                    // Create a new user
                    $user = User::create([
                        'nama' => $googleUser->name,
                        'email' => $googleUser->email,
                        'google_id' => $googleUser->id,
                        'profile_url' => $googleUser->avatar,
                        'password' => Hash::make(Str::random(16)), // Random password as it won't be used
                    ]);
                }
            }
            
            // Login the user
            Auth::login($user);
            
            return redirect()->route('dashboard');
            
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Google authentication failed. Please try again.');
        }
    }
}
