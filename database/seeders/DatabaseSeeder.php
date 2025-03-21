<?php

namespace Database\Seeders;

use App\Models\ShopServices;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Generate 100 unique users
        $users = [];
        for ($i = 0; $i < 100; $i++) {
            $users[] = [
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        User::insert($users);

        // Call other seeders
        $this->call([
            KategoriSeeder::class,
            SubKategoriSeeder::class,
            StatusSeeder::class,
            HariSeeder::class,
        ]);
    }
}
