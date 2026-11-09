<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $cat1 = Category::firstOrCreate(['name' => 'Makanan'], ['description' => 'Menu makanan']);
        $cat2 = Category::firstOrCreate(['name' => 'Minuman'], ['description' => 'Menu minuman']);

        $products = [
            ['name' => 'Nasi Goreng Spesial', 'sku' => 'NG-01', 'price' => 25000, 'stock' => 50, 'category_id' => $cat1->id],
            ['name' => 'Ayam Bakar Madu', 'sku' => 'AB-01', 'price' => 30000, 'stock' => 40, 'category_id' => $cat1->id],
            ['name' => 'Es Teh Manis', 'sku' => 'ET-01', 'price' => 5000, 'stock' => 100, 'category_id' => $cat2->id],
            ['name' => 'Kopi Hitam', 'sku' => 'KH-01', 'price' => 8000, 'stock' => 80, 'category_id' => $cat2->id],
        ];

        foreach ($products as $p) {
            Product::firstOrCreate(['sku' => $p['sku']], $p);
        }
    }
}
