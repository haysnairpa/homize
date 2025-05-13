<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Log;

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
            $user = \App\Models\User::where('google_id', $googleUser->id)->first();

            // If user doesn't exist, check if the email exists
            if (!$user) {
                $user = \App\Models\User::where('email', $googleUser->email)->first();
                // If user with this email exists, update their google_id
                if ($user) {
                    $user->update([
                        'google_id' => $googleUser->id,
                    ]);
                } else {
                    // Store Google user data in session for phone completion
                    session([
                        'google_user' => [
                            'nama' => $googleUser->name,
                            'email' => $googleUser->email,
                            'google_id' => $googleUser->id,
                            'profile_url' => $googleUser->avatar,
                        ]
                    ]);
                    return redirect()->route('complete.phone');
                }
            }

            // If user exists but phone is missing, ask for phone
            if (empty($user->phone)) {
                session(['google_user' => [
                    'id' => $user->id,
                    'nama' => $user->nama,
                    'email' => $user->email,
                    'google_id' => $user->google_id,
                    'profile_url' => $user->profile_url,
                ]]);
                return redirect()->route('complete.phone');
            }

            // Login the user
            Auth::login($user);
            return redirect()->route('home');
        } catch (\Exception $e) {
            Log::error('Google Auth Exception: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return redirect()->route('login')->with('error', 'Google authentication failed: ' . $e->getMessage());
        }
    }

    /**
     * Show the complete phone form after Google login
     */
    public function showCompletePhoneForm()
    {
        return view('auth.complete-phone');
    }

    /**
     * Handle phone completion after Google login
     */
    public function submitCompletePhone(Request $request)
    {
        $request->validate([
            'phone' => 'required|unique:users,phone',
        ]);
        $googleUser = session('google_user');
        if (!$googleUser) {
            return redirect()->route('login')->with('error', 'Session expired. Please login again.');
        }
        try {
            if (isset($googleUser['id'])) {
                $user = User::find($googleUser['id']);
                if (!$user) {
                    return redirect()->route('login')->with('error', 'User not found.');
                }
                $user->update(['phone' => '+62' . ltrim($request->phone, '0')]);
            } else {
                // Create new user
                $user = User::create([
                    'nama' => $googleUser['nama'],
                    'email' => $googleUser['email'],
                    'google_id' => $googleUser['google_id'],
                    'profile_url' => $googleUser['profile_url'],
                    'password' => Hash::make(Str::random(16)),
                    'phone' => '+62' . ltrim($request->phone, '0'),
                ]);
            }
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == 23000) {
                return back()->withInput()->with('error', 'Nomor telepon sudah digunakan. Silakan gunakan nomor lain.');
            }
            throw $e;
        }
        session()->forget('google_user');
        Auth::login($user);
        return redirect()->route('home');
    }
}
