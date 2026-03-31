<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RevenueSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            DB::table('revenues')->insert([
                'date' => now()->subDays($i),
                'total_sales' => rand(5000, 20000),
                'total_orders' => rand(10, 50),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
