<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class SembakoSeeder extends Seeder
{
    public function run()
    {
        // Categories
        $smb = Category::firstOrCreate(['name' => 'Sembako'], ['code' => 'SMB']);
        $bmb = Category::firstOrCreate(['name' => 'Bumbu Dapur'], ['code' => 'BMB']);
        $mnm = Category::firstOrCreate(['name' => 'Minuman'], ['code' => 'MNM']);
        $snk = Category::firstOrCreate(['name' => 'Makanan Ringan'], ['code' => 'SNK']);
        $rmh = Category::firstOrCreate(['name' => 'Kebutuhan Rumah'], ['code' => 'RMH']);
        $prw = Category::firstOrCreate(['name' => 'Perawatan Diri'], ['code' => 'PRW']);

        // Products from screenshot
        $products = [
            [
                'name' => 'Air Mineral Aqua 1 dus 600 ml',
                'sku' => 'MNM-002',
                'barcode' => '8991001000022',
                'category_id' => $mnm->id,
                'cost_price' => 48000,
                'price' => 58000,
                'stock' => 9,
                'min_stock' => 3,
                'unit' => 'dus',
            ],
            [
                'name' => 'Air Mineral Aqua 600 ml',
                'sku' => 'MNM-001',
                'barcode' => '8991001000015',
                'category_id' => $mnm->id,
                'cost_price' => 2800,
                'price' => 4000,
                'stock' => 143,
                'min_stock' => 24,
                'unit' => 'botol',
            ],
            [
                'name' => 'Bawang Merah',
                'sku' => 'BMB-005',
                'barcode' => null,
                'category_id' => $bmb->id,
                'cost_price' => 32000,
                'price' => 40000,
                'stock' => 8,
                'min_stock' => 3,
                'unit' => 'kg',
            ],
            [
                'name' => 'Beras Pandan Wangi 5 kg',
                'sku' => 'SMB-001',
                'barcode' => '8992002000011',
                'category_id' => $smb->id,
                'cost_price' => 62000,
                'price' => 72000,
                'stock' => 24,
                'min_stock' => 5,
                'unit' => 'karung',
            ],
            [
                'name' => 'Beras Setra Ramos 10 kg',
                'sku' => 'SMB-002',
                'barcode' => '8992002000028',
                'category_id' => $smb->id,
                'cost_price' => 112000,
                'price' => 128000,
                'stock' => 12,
                'min_stock' => 3,
                'unit' => 'karung',
            ],
            [
                'name' => 'Tisu Basah Mitu 50 lembar',
                'sku' => 'PRW-003',
                'barcode' => '8993003000010',
                'category_id' => $prw->id,
                'cost_price' => 12000,
                'price' => 15000,
                'stock' => 2,
                'min_stock' => 5,
                'unit' => 'pack',
            ],
        ];

        foreach ($products as $prod) {
            Product::firstOrCreate(
                ['sku' => $prod['sku']],
                $prod
            );
        }
    }
}
