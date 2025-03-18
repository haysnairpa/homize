<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = DB::select("SELECT `name`, `email`, `created_at`
                                FROM `users`");

        $customer = [[]];

        foreach ($customers as $cust) {
            $customer[] = [
                "name" => $cust->name,
                "email" => $cust->email,
                "created_at" => $cust->created_at,
            ];
        }

        foreach ($customer as $cust) {
            Customer::create($cust);
        }
    }
}
