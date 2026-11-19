<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $menus = [
            [
                'name' => 'Mie Pedas',
                'description' => 'Mie dengan pilihan tingkat kepedasan.',
                'price' => 15000,
                'category' => 'makanan',
            ],
            [
                'name' => 'Ayam Geprek',
                'description' => 'Ayam goreng dengan sambal pilihan.',
                'price' => 18000,
                'category' => 'makanan',
            ],
            [
                'name' => 'Kentang Goreng',
                'description' => 'Kentang goreng renyah.',
                'price' => 12000,
                'category' => 'makanan',
            ],
            [
                'name' => 'Es Teh',
                'description' => 'Teh segar dengan es.',
                'price' => 5000,
                'category' => 'minuman',
            ],
            [
                'name' => 'Es Jeruk',
                'description' => 'Minuman jeruk segar.',
                'price' => 8000,
                'category' => 'minuman',
            ],
        ];

        foreach ($menus as $menu) {
            Menu::firstOrCreate(
                ['name' => $menu['name']],
                $menu
            );
        }
    }
}