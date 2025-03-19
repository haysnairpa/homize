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

        $customers = DB::table('customer')->pluck('id')->toArray();
        $categories = DB::table('category')->pluck('id')->toArray();
        $shopServices = DB::table('shop_services')->pluck('id')->toArray();
        $statuses = [1, 2, 3, 4];

        $orders = [];
        for ($i = 0; $i < 100; $i++) {
            $orders[] = [
                'id_category' => $faker->randomElement($categories),
                'id_customer' => $faker->randomElement($customers),
                'customer_address' => $faker->address,
                'id_services' => $faker->randomElement($shopServices),
                'id_status' => $faker->randomElement($statuses),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Order::insert($orders);
    }
}
