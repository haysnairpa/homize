<?php

namespace Database\Seeders;

use App\Models\ShopServices;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->createMany([
            ['name' => 'John Doe', 'email' => 'john.doe@example.com'],
            ['name' => 'Jane Smith', 'email' => 'jane.smith@example.com'],
            ['name' => 'Michael Johnson', 'email' => 'michael.johnson@example.com'],
            ['name' => 'Emily Davis', 'email' => 'emily.davis@example.com'],
            ['name' => 'David Wilson', 'email' => 'david.wilson@example.com'],
            ['name' => 'Sarah Brown', 'email' => 'sarah.brown@example.com'],
            ['name' => 'Robert Garcia', 'email' => 'robert.garcia@example.com'],
            ['name' => 'Linda Martinez', 'email' => 'linda.martinez@example.com'],
            ['name' => 'James Anderson', 'email' => 'james.anderson@example.com'],
            ['name' => 'Patricia Thomas', 'email' => 'patricia.thomas@example.com'],
            ['name' => 'Mark Lee', 'email' => 'mark.lee@example.com'],
            ['name' => 'Laura Hernandez', 'email' => 'laura.hernandez@example.com'],
        ]);
        $this->call([
            CustomerSeeder::class,
            JasaCategorySeeder::class,
            CategorySeeder::class,
            ShopSeeder::class,
            ServicesSeeder::class,
            ShopServiceSeeder::class,
            RateSeeder::class,
            StatusSeeder::class,
        ]);
    }
}
