<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Validate and update the given user's profile information.
     *
     * @param  array<string, mixed>  $input
     */
    public function update(User $user, array $input): void
    {
        // Basic validation for required fields
        Validator::make($input, [
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'photo' => ['nullable', 'mimes:jpg,jpeg,png,webp,gif,bmp,svg', 'max:2048'],
        ])->validateWithBag('updateProfileInformation');
        
        // Process phone number separately without validation
        if (isset($input['phone'])) {
            // Remove any non-numeric characters
            $phone = preg_replace('/[^0-9]/', '', $input['phone']);
            // Remove leading zeros
            $phone = ltrim($phone, '0');
            // Store the cleaned phone number
            $input['phone'] = $phone;
        }

        if (isset($input['photo'])) {
            $user->updateProfilePhoto($input['photo']);
        }

        if ($input['email'] !== $user->email &&
            $user instanceof MustVerifyEmail) {
            $this->updateVerifiedUser($user, $input);
        } else {
            // Format the phone number with +62 prefix
            $phoneWithPrefix = isset($input['phone']) ? '+62' . $input['phone'] : $user->phone;
            
            $user->forceFill([
                'nama' => $input['nama'],
                'email' => $input['email'],
                'phone' => $phoneWithPrefix,
            ])->save();
        }
    }

    /**
     * Update the given verified user's profile information.
     *
     * @param  array<string, string>  $input
     */
    protected function updateVerifiedUser(User $user, array $input): void
    {
        // Process phone number if it exists
        if (isset($input['phone'])) {
            // Remove any non-numeric characters
            $phone = preg_replace('/[^0-9]/', '', $input['phone']);
            // Remove leading zeros
            $phone = ltrim($phone, '0');
            // Update the input array
            $input['phone'] = $phone;
        }
        
        $user->forceFill([
            'nama' => $input['nama'],
            'email' => $input['email'],
            'phone' => '+62' . $input['phone'],
            'email_verified_at' => null,
        ])->save();

        $user->sendEmailVerificationNotification();
    }
}
