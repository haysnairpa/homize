<?php

namespace Database\Seeders;

use App\Models\Services;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ServicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $services = [];
        for ($i = 0; $i < 50; $i++) {
            $services[] = [
                "name" => $faker->catchPhrase,
                "price" => $faker->numberBetween(100000, 5000000),
                "created_at" => now(),
                "updated_at" => now(),
            ];
        }

        Services::insert($services);
    }
}
