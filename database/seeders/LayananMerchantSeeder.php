<?php

namespace Database\Seeders;

use App\Models\LayananMerchant;
use App\Models\Merchant;
use App\Models\Layanan;
use App\Models\SubKategori;
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
            // Get sub-kategori IDs that belong to the merchant's kategori
            $subKategoriIds = SubKategori::where('id_kategori', $merchant->id_kategori)
                ->pluck('id')
                ->toArray();

            // Get layanan that matches any of the merchant's kategori's sub-kategori
            $matchingLayanan = Layanan::whereIn('id_sub_kategori', $subKategoriIds)
                ->pluck('id')
                ->toArray();

            if (!empty($matchingLayanan)) {
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
        }

        if (!empty($layananMerchant)) {
            LayananMerchant::insert($layananMerchant);
        }
    }
}
