<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $images = [
            'uploads/products/p1.jpg',
            'uploads/products/p2.jpg',
            'uploads/products/p3.jpg',
            'uploads/products/p4.jpg',
        ];

        for ($i = 1; $i <= 20; $i++) {
            Product::create([
                'name' => 'Product ' . $i,
                'description' => 'Description for product ' . $i,
                'price' => rand(100, 5000),
                'image' => $images[array_rand($images)], // same format as service
                'category_id' => Category::inRandomOrder()->first()->id,
            ]);
        }
    }
}