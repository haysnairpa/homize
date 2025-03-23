<?php

namespace Database\Seeders;

use App\Models\Merchant;
use App\Models\User;
use App\Models\SubKategori;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class MerchantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // Get all users and randomly select 50%
        $users = User::pluck('id')->toArray();
        $selectedUsers = array_rand(array_flip($users), ceil(count($users) / 2));

        // Get all sub categories
        $subKategories = SubKategori::all();

        $merchants = [];

        // Ensure each subcategory has at least one merchant
        foreach ($subKategories as $subKategori) {
            if (empty($selectedUsers)) break;

            $userId = array_pop($selectedUsers);
            $namaPrefixes = ['Jasa', 'Layanan', 'Servis', 'Pro'];
            $namaSuffixes = ['Professional', 'Terpercaya', 'Handal', 'Express', 'Gacor'];

            $merchants[] = [
                'id_user' => $userId,
                'id_sub_kategori' => $subKategori->id,
                'nama_usaha' => $faker->randomElement($namaPrefixes) . ' ' .
                    $subKategori->nama . ' ' .
                    $faker->randomElement($namaSuffixes),
                'profile_url' => $faker->imageUrl(640, 480, 'business'),
                'alamat' => $faker->address,
                'media_sosial' => json_encode([
                    'instagram' => '@' . $faker->userName,
                    'facebook' => $faker->userName,
                    'whatsapp' => $faker->phoneNumber
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Fill remaining merchants randomly
        while (!empty($selectedUsers)) {
            $userId = array_pop($selectedUsers);
            $subKategori = $subKategories->random();

            $merchants[] = [
                'id_user' => $userId,
                'id_sub_kategori' => $subKategori->id,
                'nama_usaha' => $faker->randomElement(['Jasa', 'Layanan', 'Servis', 'Pro']) . ' ' .
                    $subKategori->nama . ' ' .
                    $faker->randomElement(['Professional', 'Terpercaya', 'Handal', 'Express', 'Gacor']),
                'profile_url' => $faker->imageUrl(640, 480, 'business'),
                'alamat' => $faker->address,
                'media_sosial' => json_encode([
                    'instagram' => '@' . $faker->userName,
                    'facebook' => $faker->userName,
                    'whatsapp' => $faker->phoneNumber
                ]),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Merchant::insert($merchants);
    }
}
