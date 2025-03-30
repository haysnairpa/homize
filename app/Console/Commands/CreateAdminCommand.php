<?php

namespace App\Console\Commands;

use App\Models\Admin;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdminCommand extends Command
{
    protected $signature = 'admin:create {email} {password} {nama}';
    protected $description = 'Create a new admin account';

    public function handle()
    {
        $email = $this->argument('email');
        $password = $this->argument('password');
        $nama = $this->argument('nama');

        // Check if admin already exists
        if (Admin::where('email', $email)->exists()) {
            $this->error('Admin with this email already exists!');
            return 1;
        }

        // Create new admin
        Admin::create([
            'nama' => $nama,
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        $this->info('Admin account created successfully!');
        $this->table(
            ['Field', 'Value'],
            [
                ['Nama', $nama],
                ['Email', $email],
            ]
        );

        return 0;
    }
} 