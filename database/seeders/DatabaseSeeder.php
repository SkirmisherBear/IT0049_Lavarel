<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $adminConfig = config('overclock.admin');
        $adminEmail = $adminConfig['email'];
        $adminPassword = $adminConfig['password'];

        if (blank($adminEmail) || blank($adminPassword)) {
            throw new RuntimeException('Set ADMIN_EMAIL and ADMIN_PASSWORD in .env before seeding the admin account.');
        }

        $admin = User::updateOrCreate(
            ['email' => $adminEmail],
            [
                'name' => $adminConfig['name'],
                'username' => $adminConfig['username'],
                'phone' => $adminConfig['phone'],
                'password' => Hash::make($adminPassword),
            ]
        );
        $admin->role = 'admin';
        $admin->save();

        // Overclock Fitness Products & Gym Passes
        $products = [
            ['name' => 'Stock Settings - Monthly Gym Pass', 'price' => 1500.00, 'stock_quantity' => 50, 'category' => 'month_pass'],
            ['name' => 'Liquid Cooled - Annual Gym Pass', 'price' => 2500.00, 'stock_quantity' => 30, 'category' => 'year_pass'],
            ['name' => 'Max Overclock - System Engineer Training Session', 'price' => 4500.00, 'stock_quantity' => 15, 'category' => 'training_session'],
            ['name' => 'Day Pass (Walk-in Session)', 'price' => 250.00, 'stock_quantity' => 100, 'category' => 'day_pass'],
            ['name' => 'Overclock Whey Isolate (1 Scoop Shake)', 'price' => 140.00, 'stock_quantity' => 40, 'category' => 'supplement'],
            ['name' => 'Liquid Cooled Electrolyte Energy Drink', 'price' => 95.00, 'stock_quantity' => 4, 'category' => 'drink'],
            ['name' => 'Pre-Workout Overdrive Shot', 'price' => 120.00, 'stock_quantity' => 3, 'category' => 'supplement'],
            ['name' => 'Overclock Lifting Straps', 'price' => 450.00, 'stock_quantity' => 18, 'category' => 'merch'],
        ];

        foreach ($products as $item) {
            $product = Product::firstOrCreate(['name' => $item['name']], $item);
            $product->update(['category' => $item['category']]);
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
