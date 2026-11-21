<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class StaffSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@qresto.test'],
            [
                'name' => 'Admin QResto',
                'password' => 'AdminBaru2026!',
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'kasir@qresto.test'],
            [
                'name' => 'Kasir QResto',
                'password' => 'KasirBaru2026!',
                'role' => 'cashier',
            ]
        );

        User::updateOrCreate(
            ['email' => 'dapur@qresto.test'],
            [
                'name' => 'Dapur QResto',
                'password' => 'DapurBaru2026!',
                'role' => 'kitchen',
            ]
        );
    }
}
