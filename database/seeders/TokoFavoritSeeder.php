<?php

namespace Database\Seeders;

use App\Models\TokoFavorit;
use App\Models\User;
use App\Models\Merchant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TokoFavoritSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all users who are not merchants
        $merchantUserIds = Merchant::pluck('id_user')->toArray();
        $users = User::whereNotIn('id', $merchantUserIds)->get();

        // Get all merchants
        $merchants = Merchant::all();

        $tokoFavorits = [];

        foreach ($users as $user) {
            // Randomly select merchants for each user
            $selectedMerchants = $merchants->random(rand(1, 4)); // Each user can have 1 to 4 favorite merchants

            foreach ($selectedMerchants as $merchant) {
                $tokoFavorits[] = [
                    'id_user' => $user->id,
                    'id_merchant' => $merchant->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Insert the favorite records into the database
        TokoFavorit::insert($tokoFavorits);
    }
}
