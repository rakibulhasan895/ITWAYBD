<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            'name' => 'Laptop',
            'price' => 50000,
            'description' => 'A high-performance laptop.',
            'added_by' => 1,
        ]);

        Product::create([
            'name' => 'Mouse',
            'price' => 800,
            'description' => 'A wireless mouse.',
            'added_by' => 1,
        ]);

        Product::create([
            'name' => 'Keyboard',
            'price' => 1500,
            'description' => 'A mechanical keyboard.',
            'added_by' => 2,
        ]);
    }
}
