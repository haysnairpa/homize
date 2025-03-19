<?php

namespace Database\Seeders;

use App\Models\ShopServices;
use Exception;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ShopServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $shops = DB::table('shop')->pluck('id')->toArray();
        $services = DB::table('services')->pluck('id')->toArray();

        $shopServices = [];
        $faker = Faker::create();
        foreach ($shops as $shopId) {
            $assignedServices = $faker->randomElements($services, rand(1, 5));
            foreach ($assignedServices as $serviceId) {
                $shopServices[] = [
                    "id_shop" => $shopId,
                    "id_services" => $serviceId,
                ];
            }
        }

        ShopServices::insert($shopServices);
    }
}
