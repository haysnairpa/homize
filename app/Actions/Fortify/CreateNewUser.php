<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers

{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        // Normalisasi nomor telepon: hanya angka, tanpa karakter lain
        $input['phone'] = preg_replace('/[^0-9]/', '', $input['phone']);
        $input['phone'] = ltrim($input['phone'], '0');
        Log::info('Phone before validation: ' . $input['phone']);

        try {
            Validator::make($input, [
            'nama' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users',
                'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/'
            ],
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
            'phone' => [
                'required',
                'string',
                'min:9',
                'max:13',
                'regex:/^[0-9]+$/',
                'unique:users,phone'
            ], 
        ], [
            'nama.required' => 'Nama lengkap wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.regex' => 'Password harus mengandung huruf besar, huruf kecil, angka, dan karakter khusus',
            'password.confirmed' => 'Konfirmasi password tidak sesuai',
            'phone.regex' => 'Nomor telepon hanya boleh angka.',
            'phone.min' => 'Nomor telepon minimal 9 digit.',
            'phone.max' => 'Nomor telepon maksimal 13 digit.',
            'phone.unique' => 'Nomor telepon sudah terdaftar'
        ])->validate();
            Log::info('Phone before insert: ' . $input['phone']);

            return User::create([
                'nama' => $input['nama'],
                'email' => $input['email'],
                'phone' => '+62' . $input['phone'],
                'password' => Hash::make($input['password']),
                'profile_url' => 'https://ui-avatars.com/api/?name=' . urlencode($input['nama']) . '&color=7F9CF5&background=EBF4FF',
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == 23000 && str_contains($e->getMessage(), 'users_phone_unique')) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'phone' => ['Nomor telepon sudah terdaftar']
                ]);
            }
            throw $e;
        }
    }
}
