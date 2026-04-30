<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1 admin
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@techshop.local',
            'role' => UserRole::ADMIN->value,
        ]);

        // 2 testklanten
        User::factory()->create([
            'name' => 'Test Klant 1',
            'email' => 'klant1@techshop.local',
            'role' => UserRole::CUSTOMER->value,
        ]);

        User::factory()->create([
            'name' => 'Test Klant 2',
            'email' => 'klant2@techshop.local',
            'role' => UserRole::CUSTOMER->value,
        ]);

        // 5 categorieën
        // $categories = Category::factory(5)->create();

        // 20 producten verdeeld over de categorieën
        // Product::factory(20)->recycle($categories)->create();

        $this->call(RealTechDataSeeder::class);
    }
}
