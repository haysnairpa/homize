<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class RateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Fetch existing customer and shop IDs
        $customers = DB::table('customer')->pluck('id')->toArray();
        $shops = DB::table('shop')->pluck('id')->toArray();

        // Check if we have enough data
        if (empty($customers) || empty($shops)) {
            throw new \Exception("Customers or Shops table is empty!");
        }

        $rates = [];

        for ($i = 0; $i < 25; $i++) {
            $rates[] = [
                'id_customer' => $faker->randomElement($customers),
                'id_shop' => $faker->randomElement($shops),
                'rate' => $faker->numberBetween(1, 5), // Random rating from 1 to 5
                'message' => $faker->sentence(10), // Dummy review message
                'media_url' => $faker->imageUrl(640, 480, 'business', true), // Random image URL
                'created_at' => now(), // Use Laravel's default timestamp field
                'updated_at' => now(),
            ];
        }

        // Insert into the rate table
        DB::table('rate')->insert($rates);
    }
}
