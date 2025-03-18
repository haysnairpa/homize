<?php

namespace Database\Seeders;

use Exception;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShopServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $shops_data = DB::select("SELECT id FROM `shop`");
        $services_data = DB::select("SELECT id FROM `services`");

        // Convert services_data to a simple array of IDs
        $service_ids = array_map(fn($service) => $service->id, $services_data);

        // Ensure we have enough services
        if (count($service_ids) < count($shops_data)) {
            throw new Exception("Not enough services for unique assignments!");
        }

        $shop_services = [];
        shuffle($service_ids); // Shuffle to make assignments random

        foreach ($shops_data as $index => $shop_data) {
            $shop_services[] = [
                "id_shop" => $shop_data->id,
                "id_services" => $service_ids[$index], // Assign unique service
            ];
        }

        // Insert into the pivot table
        DB::table('shop_services')->insert($shop_services);
    }
}
