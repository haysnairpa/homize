<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Shop; // Ensure you have this model
use Faker\Factory as Faker;

class ShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Fetch 40% of users to create shops
        $users = DB::table('users')->inRandomOrder()->limit(40)->get();

        $addresses = [];
        for ($i = 0; $i < 40; $i++) {
            $addresses[] = $faker->unique()->address;
        }

        $shopData = [];
        foreach ($users as $i => $user) {
            $shopData[] = [
                "name" => $user->name,
                "email" => $user->email,
                "address" => $addresses[$i],
                "id_category" => rand(1, 5),
                "created_at" => $user->created_at,
                "updated_at" => now(),
            ];
        }

        Shop::insert($shopData);
    }
}
