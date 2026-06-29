<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\PaymentMethod;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Roles
        $adminRole = Role::firstOrCreate(
            ['name' => 'admin'],
            ['description' => 'Administrator']
        );
        $customerRole = Role::firstOrCreate(
            ['name' => 'customer'],
            ['description' => 'Customer']
        );

        // Admin user default
        User::updateOrCreate(
            ['email' => 'admin@zimam.test'],
            [
                'name' => 'Admin Zimam',
                'phone' => '0812xxxxxxx',
                'password' => Hash::make('123'),
                'role_id' => $adminRole->id,
                'is_active' => true,
            ]
        );

        // Customer user default
        User::updateOrCreate(
            ['email' => 'user@zimam.test'],
            [
                'name' => 'Pengguna Zimam',
                'phone' => '0899xxxxxxx',
                'password' => Hash::make('123'),
                'role_id' => $customerRole->id,
                'is_active' => true,
            ]
        );

        // Metode pembayaran dasar
        $methods = [
            ['name' => 'QRIS', 'type' => 'qris'],
            ['name' => 'E-Wallet', 'type' => 'ewallet'],
            ['name' => 'Transfer Bank', 'type' => 'bank_transfer'],
            ['name' => 'Tunai di Toko', 'type' => 'cash'],
            ['name' => 'Cicilan', 'type' => 'installment'],
        ];

        foreach ($methods as $method) {
            PaymentMethod::firstOrCreate(
                ['name' => $method['name']],
                ['type' => $method['type'], 'is_active' => true]
            );
        }

        // Satu contoh produk
        Product::firstOrCreate(
            ['slug' => 'banner-printing'],
            [
                'name' => 'Banner Printing',
                'category' => 'Banner',
                'description' => 'Layanan cetak banner berkualitas untuk kebutuhan promosi Anda.',
                'base_price' => 75000,
                'lead_time_days' => 2,
                'allow_custom_design' => true,
                'allow_design_service' => true,
                'is_active' => true,
            ]
        );
    }
}
