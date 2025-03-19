<?php

namespace Database\Seeders;

use App\Models\Order;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Fetch existing IDs from the relevant tables
        $customers = DB::table('customer')->pluck('id')->toArray();
        $categories = DB::table('category')->pluck('id')->toArray();
        $shopServices = DB::table('shop_services')->pluck('id')->toArray(); // Use shop_services, not services
        $statuses = [1, 2, 3, 4]; // Status IDs (1-4)

        // Ensure data exists
        if (empty($customers) || empty($categories) || empty($shopServices)) {
            throw new \Exception("Customers, Categories, or Shop Services table is empty!");
        }

        $orders = [];
        $uniqueAddresses = [];

        // Generate 20 unique addresses
        while (count($uniqueAddresses) < 20) {
            $address = $faker->unique()->address;
            $uniqueAddresses[] = $address;
        }

        // Insert 20 orders with 5 per status
        foreach ($statuses as $status) {
            for ($i = 0; $i < 5; $i++) {
                $orders[] = [
                    'id_category' => $faker->randomElement($categories),
                    'id_customer' => $faker->randomElement($customers),
                    'customer_address' => array_pop($uniqueAddresses), // Use a unique address
                    'id_services' => $faker->randomElement($shopServices), // Use shop_services IDs
                    'id_status' => $status, // Ensure 5 orders per status
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Insert into the order table
        Order::insert($orders);
    }
}
