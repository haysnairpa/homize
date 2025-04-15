<?php

namespace Database\Seeders;

use App\Models\Merchant;
use App\Models\User;
use App\Models\Kategori;
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

        // Get all users and randomly select 30-35%
        $users = User::pluck('id')->toArray();
        $percentage = rand(30, 35) / 100;
        $selectedUsers = array_rand(array_flip($users), ceil(count($users) * $percentage));

        // Get all categories
        $kategories = Kategori::all();

        $merchants = [];

        // Ensure each category has at least one merchant
        foreach ($kategories as $kategori) {
            if (empty($selectedUsers)) break;

            $userId = array_pop($selectedUsers);
            $namaPrefixes = ['Jasa', 'Layanan', 'Servis', 'Pro'];
            $namaSuffixes = ['Professional', 'Terpercaya', 'Handal', 'Express', 'Gacor'];

            $merchants[] = [
                'id_user' => $userId,
                'id_kategori' => $kategori->id,
                'nama_usaha' => $faker->randomElement($namaPrefixes) . ' ' .
                    $kategori->nama . ' ' .
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
                'verification_status' => 'approved',
                'rejection_reason' => null,
                'verified_at' => now()
            ];
        }

        // Fill remaining merchants randomly
        while (!empty($selectedUsers)) {
            $userId = array_pop($selectedUsers);
            $kategori = $kategories->random();

            $merchants[] = [
                'id_user' => $userId,
                'id_kategori' => $kategori->id,
                'nama_usaha' => $faker->randomElement(['Jasa', 'Layanan', 'Servis', 'Pro']) . ' ' .
                    $kategori->nama . ' ' .
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
                'verification_status' => 'approved',
                'rejection_reason' => null,
                'verified_at' => now()
            ];
        }

        Merchant::insert($merchants);
    }
}
