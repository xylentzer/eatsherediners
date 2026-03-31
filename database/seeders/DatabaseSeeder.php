<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Order;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            MenuSeeder::class,
            CustomerSeeder::class,
            OrderSeeder::class,
            RevenueSeeder::class,
        ]);
    }
}
