<?php

namespace Database\Seeders;

use App\Models\LayananMerchant;
use App\Models\Merchant;
use App\Models\Layanan;
use Illuminate\Database\Seeder;

class LayananMerchantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $merchants = Merchant::all();
        $layananMerchant = [];

        foreach ($merchants as $merchant) {
            // Get layanan that matches merchant's sub_kategori
            $matchingLayanan = Layanan::where('id_sub_kategori', $merchant->id_sub_kategori)
                ->pluck('id')
                ->toArray();

            // Assign 3-5 random matching services to each merchant
            $numServices = rand(3, 5);
            $selectedLayanan = array_rand(
                array_flip($matchingLayanan),
                min($numServices, count($matchingLayanan))
            );

            foreach ((array)$selectedLayanan as $layananId) {
                $layananMerchant[] = [
                    'id_merchant' => $merchant->id,
                    'id_layanan' => $layananId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        LayananMerchant::insert($layananMerchant);
    }
}
