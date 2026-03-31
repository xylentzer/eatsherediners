<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Menu; 

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $menus = [
            ['name' => 'Pork Tonkatsu', 'category' => 'Main Course', 'description' => 'Crispy pork cutlet with sauce', 'price' => 120],
            ['name' => 'Fried Chicken', 'category' => 'Main Course', 'description' => 'Golden fried chicken served with rice', 'price' => 110],
            ['name' => 'Chicken ala King', 'category' => 'Main Course', 'description' => 'Creamy chicken in white sauce', 'price' => 130],
            ['name' => 'Beef Burger Steak', 'category' => 'Main Course', 'description' => 'Juicy beef patty in gravy sauce', 'price' => 125],
            ['name' => 'Fish Fillet in White Sauce', 'category' => 'Main Course', 'description' => 'Tender fish fillet topped with white sauce', 'price' => 115],
            ['name' => 'Pork BBQ', 'category' => 'Main Course', 'description' => 'Sweet and savory grilled pork skewers', 'price' => 100],
            ['name' => 'Chicken Pastil w/ Egg', 'category' => 'Breakfast', 'description' => 'Steamed rice topped with shredded chicken and egg', 'price' => 70],
            ['name' => 'Hotdog with Rice and Egg', 'category' => 'Breakfast', 'description' => 'Classic breakfast combo', 'price' => 65],
            ['name' => 'Extra Rice', 'category' => 'Add Ons', 'description' => 'Additional serving of rice', 'price' => 14],
            ['name' => '500ml Water', 'category' => 'Add Ons', 'description' => 'Bottled water', 'price' => 14],
        ];

        foreach ($menus as $menu) {
            Menu::create($menu);
        }
    }
}
