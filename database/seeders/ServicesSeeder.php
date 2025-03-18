<?php

namespace Database\Seeders;

use App\Models\Services;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            ["name" => "Wedding Photography Package", "price" => 2500000],
            ["name" => "Event DJ Services", "price" => 1800000],
            ["name" => "Home Cleaning Service", "price" => 350000],
            ["name" => "Personal Fitness Training", "price" => 500000],
            ["name" => "Freelance Graphic Design", "price" => 1200000],
            ["name" => "Professional Makeup Service", "price" => 800000],
            ["name" => "Car Repair & Maintenance", "price" => 700000],
            ["name" => "Pet Grooming Service", "price" => 300000],
            ["name" => "Custom Website Development", "price" => 5000000],
            ["name" => "Home Renovation & Repair", "price" => 3000000],
            ["name" => "Motorcycle Wash & Detailing", "price" => 150000],
            ["name" => "Mobile Phone Repair", "price" => 400000],
            ["name" => "Photography for Social Media", "price" => 600000],
            ["name" => "Wedding Planning Service", "price" => 3200000],
            ["name" => "Private English Tutoring", "price" => 250000],
            ["name" => "Air Conditioner Maintenance", "price" => 450000],
            ["name" => "Massage Therapy Home Service", "price" => 500000],
            ["name" => "Drone Video Shooting", "price" => 2000000],
            ["name" => "Custom T-Shirt Printing", "price" => 350000],
            ["name" => "Courier & Delivery Service", "price" => 100000]
        ];

        Services::insert($services);
    }
}
