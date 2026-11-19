<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RestaurantTable;
use Illuminate\Support\Str;

class RestaurantTableSeeder extends Seeder
{
    public function run(): void
    {
        $tables = [
            [
                'code' => 'MEJA01',
                'name' => 'Meja 01',
            ],
            [
                'code' => 'MEJA02',
                'name' => 'Meja 02',
            ],
        ];

        foreach ($tables as $table) {
            RestaurantTable::firstOrCreate(
                ['code' => $table['code']],
                [
                    'name' => $table['name'],
                    'qr_token' => (string) Str::uuid(),
                    'status' => 'available',
                ]
            );
        }
    }
}