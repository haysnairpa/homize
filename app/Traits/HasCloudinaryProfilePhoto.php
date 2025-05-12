<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Cloudinary\Cloudinary;
use Cloudinary\Api\Upload\UploadApi;

trait HasCloudinaryProfilePhoto
{
    /**
     * Update the user's profile photo to Cloudinary.
     *
     * @param  \Illuminate\Http\UploadedFile  $photo
     * @return void
     */
    public function updateProfilePhoto(UploadedFile $photo)
    {
        try {
            // Delete previous photo if exists
            if ($this->profile_photo_path) {
                $this->deleteProfilePhoto();
            }

            // Upload to Cloudinary
            $uploadedFile = (new UploadApi())->upload($photo->getRealPath(), [
                'folder' => 'homize/users/profile',
                'upload_preset' => config('cloudinary.upload_presets.user_profile'),
                'public_id' => 'user_' . $this->id . '_' . time(),
            ]);

            // Save the Cloudinary URL to the database
            $this->forceFill([
                'profile_photo_path' => $uploadedFile['secure_url'],
            ])->save();

        } catch (\Exception $e) {
            Log::error('Cloudinary upload error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Delete the user's profile photo.
     *
     * @return void
     */
    public function deleteProfilePhoto()
    {
        if (is_null($this->profile_photo_path)) {
            return;
        }

        // Extract the public_id from the Cloudinary URL
        if (strpos($this->profile_photo_path, 'cloudinary.com') !== false) {
            try {
                $parts = parse_url($this->profile_photo_path);
                $path = $parts['path'] ?? '';
                $pathParts = explode('/', $path);
                
                // Remove file extension
                $lastPart = end($pathParts);
                $publicId = pathinfo($lastPart, PATHINFO_FILENAME);
                
                // Construct the full public_id including folder
                $folder = 'homize/users/profile';
                $fullPublicId = $folder . '/' . $publicId;
                
                // Delete from Cloudinary
                (new UploadApi())->destroy($fullPublicId);
            } catch (\Exception $e) {
                Log::error('Cloudinary delete error: ' . $e->getMessage());
            }
        }

        $this->forceFill([
            'profile_photo_path' => null,
        ])->save();
    }

    /**
     * Get the URL to the user's profile photo.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function profilePhotoUrl(): Attribute
    {
        return Attribute::get(function (): string {
            return $this->profile_photo_path
                    ? $this->profile_photo_path
                    : $this->defaultProfilePhotoUrl();
        });
    }

    /**
     * Get the default profile photo URL if no profile photo has been uploaded.
     *
     * @return string
     */
    protected function defaultProfilePhotoUrl()
    {
        $name = trim(collect(explode(' ', $this->nama))->map(function ($segment) {
            return mb_substr($segment, 0, 1);
        })->join(' '));

        return 'https://ui-avatars.com/api/?name='.urlencode($name).'&color=7F9CF5&background=EBF4FF';
    }
}
