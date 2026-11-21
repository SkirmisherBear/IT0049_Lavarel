<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@overclock.fit')],
            [
                'name'     => env('ADMIN_NAME', 'Overclock Admin'),
                'username' => env('ADMIN_USERNAME', 'admin@overclock.fit'),
                'phone'    => env('ADMIN_PHONE', '09171234567'),
                'role'     => 'admin',
                'password' => Hash::make(env('ADMIN_PASSWORD', '@ArnoldPaulo123')),
            ]
        );

        User::firstOrCreate(
    ['username' => 'boss'],
    [
        'name' => 'Overclock Owner',
        'email' => 'boss@overclockfitness.ph',
        'phone' => '09170000001',
        'password' => Hash::make('password123'),
        'role' => 'admin',
    ]
);

User::firstOrCreate(
    ['username' => 'cashier'],
    [
        'name' => 'Front Desk Cashier',
        'email' => 'cashier@overclockfitness.ph',
        'phone' => '09170000002',
        'password' => Hash::make('password123'),
        'role' => 'staff',
    ]
);

        // Overclock Fitness Products & Gym Passes
        $products = [
            ['name' => 'Stock Settings - Monthly Gym Pass', 'price' => 1500.00, 'stock_quantity' => 50],
            ['name' => 'Liquid Cooled - Hydro-Recharge Pass', 'price' => 2500.00, 'stock_quantity' => 30],
            ['name' => 'Max Overclock - VIP + System Engineer PT', 'price' => 4500.00, 'stock_quantity' => 15],
            ['name' => 'Day Pass (Walk-in Session)', 'price' => 250.00, 'stock_quantity' => 100],
            ['name' => 'Overclock Whey Isolate (1 Scoop Shake)', 'price' => 140.00, 'stock_quantity' => 40],
            ['name' => 'Liquid Cooled Electrolyte Energy Drink', 'price' => 95.00, 'stock_quantity' => 4], // Low stock demo
            ['name' => 'Pre-Workout Overdrive Shot', 'price' => 120.00, 'stock_quantity' => 3], // Low stock demo
            ['name' => 'Overclock Lifting Straps', 'price' => 450.00, 'stock_quantity' => 18],
        ];

        foreach ($products as $item) {
            Product::firstOrCreate(['name' => $item['name']], $item);
        }

        // Sample Gym Members / Customers
        $customers = [
            ['full_name' => 'Marcus Reyes', 'email' => 'marcus.reyes@example.com', 'phone' => '09175550101'],
            ['full_name' => 'Alyssa Santos', 'email' => 'alyssa.santos@example.com', 'phone' => '09185550202'],
            ['full_name' => 'Kenji Mendoza', 'email' => 'kenji.mendoza@example.com', 'phone' => '09195550303'],
            ['full_name' => 'Sofia Cruz', 'email' => 'sofia.cruz@example.com', 'phone' => '09205550404'],
        ];

        foreach ($customers as $member) {
            Customer::firstOrCreate(['email' => $member['email']], $member);
        }
    }
}