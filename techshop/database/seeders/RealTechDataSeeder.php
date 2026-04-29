<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RealTechDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data to start fresh (force delete to avoid keeping soft deleted records)
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        \App\Models\Product::withTrashed()->forceDelete();
        \App\Models\Category::withTrashed()->forceDelete();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        $data = [
            'E-readers' => [
                ['name' => 'Kindle Paperwhite', 'price' => 149.99, 'stock' => 50, 'description' => 'The best e-reader with 6.8" display.'],
                ['name' => 'Kobo Libra 2', 'price' => 189.99, 'stock' => 30, 'description' => 'Ergonomic design with physical buttons.'],
                ['name' => 'PocketBook Era', 'price' => 219.99, 'stock' => 20, 'description' => 'Premium e-reader with speaker and Bluetooth.'],
            ],
            'PC\'s' => [
                ['name' => 'Apple MacBook Pro M3', 'price' => 1999.00, 'stock' => 15, 'description' => 'Powerhouse for professionals.'],
                ['name' => 'Dell XPS 15', 'price' => 1749.00, 'stock' => 10, 'description' => 'Stunning 4K OLED display.'],
                ['name' => 'ASUS ROG Zephyrus G14', 'price' => 1599.00, 'stock' => 8, 'description' => 'Compact and powerful gaming laptop.'],
            ],
            'Headphones' => [
                ['name' => 'Sony WH-1000XM5', 'price' => 349.00, 'stock' => 40, 'description' => 'Industry-leading noise cancellation.'],
                ['name' => 'Bose QuietComfort Ultra', 'price' => 429.00, 'stock' => 25, 'description' => 'Incredible comfort and spatial audio.'],
                ['name' => 'Sennheiser Momentum 4', 'price' => 299.00, 'stock' => 35, 'description' => 'Crystal clear sound with 60h battery.'],
            ],
            'Keyboards' => [
                ['name' => 'Logitech MX Keys S', 'price' => 119.00, 'stock' => 60, 'description' => 'The ultimate productivity keyboard.'],
                ['name' => 'Keychron Q6 Pro', 'price' => 210.00, 'stock' => 12, 'description' => 'Custom mechanical keyboard with aluminum body.'],
                ['name' => 'Razer Huntsman V3 Pro', 'price' => 249.00, 'stock' => 20, 'description' => 'Analog optical switches for gaming.'],
            ],
            'Monitors' => [
                ['name' => 'LG UltraGear 27GR95QE', 'price' => 899.00, 'stock' => 15, 'description' => '27-inch OLED gaming monitor at 240Hz.'],
                ['name' => 'Dell UltraSharp U2723QE', 'price' => 599.00, 'stock' => 22, 'description' => '4K USB-C Hub monitor with IPS Black.'],
                ['name' => 'Samsung Odyssey Neo G9', 'price' => 1799.00, 'stock' => 5, 'description' => 'Super ultra-wide 49-inch curved monitor.'],
            ]
        ];

        foreach ($data as $categoryName => $products) {
            $category = \App\Models\Category::create([
                'name' => $categoryName,
                'slug' => \Illuminate\Support\Str::slug($categoryName),
                'description' => "Ontdek onze selectie van high-end $categoryName."
            ]);

            foreach ($products as $productData) {
                \App\Models\Product::create([
                    'category_id' => $category->id,
                    'name' => $productData['name'],
                    'slug' => \Illuminate\Support\Str::slug($productData['name']),
                    'description' => $productData['description'],
                    'price' => $productData['price'],
                    'stock' => $productData['stock'],
                ]);
            }
        }
    }
}
