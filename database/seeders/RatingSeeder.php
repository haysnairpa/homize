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
        $targetRatings = 500;
        $ratingsPerLayanan = ceil($targetRatings / count($layananIds));

        // Create ratings for each layanan
        foreach ($layananIds as $layananId) {
            // Get random users for this layanan
            $numRatings = min($ratingsPerLayanan, count($users));
            $shuffledUsers = $users;
            shuffle($shuffledUsers);
            $selectedUsers = array_slice($shuffledUsers, 0, $numRatings);

            foreach ($selectedUsers as $userId) {
                // Generate random timestamp within the last week
                $timestamps = [
                    now(),
                    now()->subDay(),
                    now()->subDays(3),
                    now()->subWeek(),
                    now()->subDays(rand(1, 7)) // Add some random days for variety
                ];
                $timestamp = $faker->randomElement($timestamps);

                // Generate review messages based on rating
                $rate = $faker->numberBetween(3, 5); // Slightly biased towards positive
                $message = $this->generateReviewMessage($rate, $faker);

                // Generate a media URL (70% chance of having an image)
                $mediaTypes = ['review', 'service', 'feedback', 'testimonial'];
                $mediaUrl = $faker->boolean(70)
                    ? $faker->imageUrl(640, 480, $faker->randomElement($mediaTypes))
                    : $faker->imageUrl(320, 240, 'review');

                $ratings[] = [
                    'id_user' => $userId,
                    'id_layanan' => $layananId,
                    'rate' => $rate,
                    'message' => $message,
                    'media_url' => $mediaUrl,
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                ];
            }
        }

        // If we haven't reached target ratings yet, add more random ratings
        $remainingRatings = $targetRatings - count($ratings);
        if ($remainingRatings > 0) {
            for ($i = 0; $i < $remainingRatings; $i++) {
                $timestamp = $faker->randomElement([
                    now(),
                    now()->subDay(),
                    now()->subDays(3),
                    now()->subWeek(),
                    now()->subDays(rand(1, 7))
                ]);

                $rate = $faker->numberBetween(3, 5);

                $ratings[] = [
                    'id_user' => $faker->randomElement($users),
                    'id_layanan' => $faker->randomElement($layananIds),
                    'rate' => $rate,
                    'message' => $this->generateReviewMessage($rate, $faker),
                    'media_url' => $faker->imageUrl(640, 480, 'review'),
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

    private function generateReviewMessage($rate, $faker)
    {
        $positiveAdjectives = ['bagus', 'memuaskan', 'profesional', 'tepat waktu', 'ramah'];
        $negativeAdjectives = ['kurang', 'bisa lebih baik', 'perlu peningkatan'];

        if ($rate >= 4) {
            return "Pelayanan sangat " . $faker->randomElement($positiveAdjectives) .
                ". " . $faker->realText(50);
        } else {
            return "Layanan " . $faker->randomElement($negativeAdjectives) .
                ". " . $faker->realText(50);
        }
    }
}
