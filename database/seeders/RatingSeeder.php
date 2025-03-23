<?php

namespace Database\Seeders;

use App\Models\Rating;
use App\Models\User;
use App\Models\Layanan;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class RatingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // Get all users and layanan
        $users = User::pluck('id')->toArray();
        $layananIds = Layanan::pluck('id')->toArray();

        $ratings = [];
        $ratingsPerLayanan = ceil(500 / count($layananIds)); // Ensure even distribution

        // Create ratings for each layanan
        foreach ($layananIds as $layananId) {
            // Randomly select users for this layanan
            $selectedUsers = array_rand(array_flip($users), min($ratingsPerLayanan, count($users)));

            foreach ($selectedUsers as $userId) {
                // Generate random timestamp within the last week
                $timestamps = [
                    now(),
                    now()->subDay(),
                    now()->subDays(3),
                    now()->subWeek()
                ];
                $timestamp = $faker->randomElement($timestamps);

                // Generate a media URL (70% chance of having an image)
                $mediaTypes = ['review', 'service', 'feedback', 'testimonial'];
                $mediaUrl = $faker->boolean(70)
                    ? $faker->imageUrl(640, 480, $faker->randomElement($mediaTypes))
                    : $faker->imageUrl(320, 240, 'review'); // Default smaller image if no specific type

                $ratings[] = [
                    'id_user' => $userId,
                    'id_layanan' => $layananId,
                    'rate' => $faker->numberBetween(3, 5), // Slightly biased towards positive ratings
                    'message' => $faker->realText(100),
                    'media_url' => $mediaUrl, // Always provide a URL
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                ];
            }
        }

        // If we haven't reached 500 ratings yet, add more random ratings
        $remainingRatings = 500 - count($ratings);
        if ($remainingRatings > 0) {
            for ($i = 0; $i < $remainingRatings; $i++) {
                $timestamp = $faker->randomElement([
                    now(),
                    now()->subDay(),
                    now()->subDays(3),
                    now()->subWeek()
                ]);

                $mediaTypes = ['review', 'service', 'feedback', 'testimonial'];
                $mediaUrl = $faker->boolean(70)
                    ? $faker->imageUrl(640, 480, $faker->randomElement($mediaTypes))
                    : $faker->imageUrl(320, 240, 'review');

                $ratings[] = [
                    'id_user' => $faker->randomElement($users),
                    'id_layanan' => $faker->randomElement($layananIds),
                    'rate' => $faker->numberBetween(3, 5),
                    'message' => $faker->realText(100),
                    'media_url' => $mediaUrl,
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                ];
            }
        }

        // Shuffle the ratings to randomize the order
        shuffle($ratings);

        // Insert in chunks to avoid memory issues
        foreach (array_chunk($ratings, 100) as $chunk) {
            Rating::insert($chunk);
        }
    }
}
