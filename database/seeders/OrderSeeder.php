<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            DB::table('orders')->insert([
                'customer_name' => "Customer $i",
                'menu_item' => "Menu Item $i",
                'quantity' => rand(1, 5),
                'status' => ['Pending', 'Accepted', 'In Progress', 'Delivered'][array_rand(['Pending', 'Accepted', 'In Progress', 'Delivered'])],
                'total_price' => rand(100, 1000),
                'order_type' => ['Delivery', 'Pick-up'][array_rand(['Delivery', 'Pick-up'])],
                'destination' => "Sample Address $i",
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
