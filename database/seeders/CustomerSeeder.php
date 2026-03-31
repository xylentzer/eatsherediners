<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            DB::table('customers')->insert([
                'name' => "Customer $i",
                'email' => "customer$i@example.com",
                'phone' => "09" . rand(100000000, 999999999),
                'total_orders' => rand(1, 20),
                'rating' => rand(1, 5),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
