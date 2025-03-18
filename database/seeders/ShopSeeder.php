<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Shop; // Ensure you have this model

class ShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fetch 5 random users from the `users` table
        $shops = DB::select("SELECT `name`, `email`, `created_at` FROM `users` ORDER BY RAND() LIMIT 5");

        // Define some random addresses
        $addresses = [
            "123 Main Street, Jakarta",
            "456 Green Avenue, Surabaya",
            "789 Ocean Drive, Bali",
            "101 Sunset Boulevard, Bandung",
            "555 Mountain Road, Yogyakarta"
        ];

        $shopData = []; // Initialize array

        foreach ($shops as $i => $shop) {
            $shopData[] = [
                "name" => $shop->name,
                "email" => $shop->email,
                "address" => $addresses[$i], // Assigning a random address
                "id_category" => rand(1, 5), // Random category from 1 to 5
                "created_at" => $shop->created_at,
                "updated_at" => now(),
            ];
        }

        // Insert into database
        Shop::insert($shopData);
    }
}
