<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserProfilePhotoController extends Controller
{
    /**
     * Update the user's profile photo.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $request->validate([
            'photo' => ['required', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        try {
            $user = Auth::user();
            $user->updateProfilePhoto($request->photo);

            return redirect()->route('profile.show')->with('status', 'profile-photo-updated');
        } catch (\Exception $e) {
            Log::error('Profile photo update error: ' . $e->getMessage());
            return back()->withErrors(['photo' => 'Terjadi kesalahan saat mengunggah foto. Silakan coba lagi.']);
        }
    }
}
