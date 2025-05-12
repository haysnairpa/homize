<?php

namespace Database\Seeders;

use App\Models\Rating;
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
        // Call other seeders
        $this->call([
            KategoriSeeder::class,
            SubKategoriSeeder::class,
            HariSeeder::class,
        ]);
    }
}
