<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = [
            "nama" => "Admin",
            "email" => "admin123@gmail.com",
            "password" => Hash::make("password123"),
            "remember_token" => Hash::make("token123"),
            "created_at" => now(),
            "updated_at" => now(),
        ];

        Admin::insert($admin);
    }
}
