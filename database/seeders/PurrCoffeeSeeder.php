<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;

class PurrCoffeeSeeder extends Seeder
{
    public function run()
    {
        // Buat Kategori
        $kopi = Category::firstOrCreate(['name' => 'Kopi'], ['description' => 'Menu berbasis kopi espresso']);
        $nonKopi = Category::firstOrCreate(['name' => 'Non-Kopi'], ['description' => 'Minuman tanpa kafein']);
        $makanan = Category::firstOrCreate(['name' => 'Makanan'], ['description' => 'Hidangan utama']);
        $cemilan = Category::firstOrCreate(['name' => 'Cemilan'], ['description' => 'Snack dan pastry']);
        $dessert = Category::firstOrCreate(['name' => 'Dessert'], ['description' => 'Penutup manis']);

        // Produk Kopi
        $coffeeProducts = [
            ['name' => 'Cappuccino', 'sku' => 'KOPI-001', 'price' => 25000, 'stock' => 50, 'description' => 'Espresso dengan susu hangat dan busa lembut khas cappuccino.'],
            ['name' => 'Coffee Latte', 'sku' => 'KOPI-002', 'price' => 28000, 'stock' => 50, 'description' => 'Espresso dengan fresh milk, creamy dan smooth.'],
            ['name' => 'Americano', 'sku' => 'KOPI-003', 'price' => 22000, 'stock' => 50, 'description' => 'Espresso shot diseduh dengan air panas.'],
            ['name' => 'V60 Manual Brew', 'sku' => 'KOPI-004', 'price' => 35000, 'stock' => 30, 'description' => 'Seduhan manual dengan biji kopi pilihan single origin.'],
            ['name' => 'Espresso', 'sku' => 'KOPI-005', 'price' => 20000, 'stock' => 50, 'description' => 'Single/Double shot espresso murni.'],
            ['name' => 'Caramel Macchiato', 'sku' => 'KOPI-006', 'price' => 32000, 'stock' => 40, 'description' => 'Espresso dengan vanilla, susu, dan caramel drizzle.'],
        ];

        foreach ($coffeeProducts as $prod) {
            Product::firstOrCreate(
                ['sku' => $prod['sku']],
                array_merge($prod, ['category_id' => $kopi->id])
            );
        }

        // Produk Non-Kopi
        $nonCoffeeProducts = [
            ['name' => 'Matcha Latte', 'sku' => 'NON-001', 'price' => 30000, 'stock' => 40, 'description' => 'Matcha Jepang premium dengan fresh milk.'],
            ['name' => 'Chocolate', 'sku' => 'NON-002', 'price' => 28000, 'stock' => 40, 'description' => 'Cokelat hangat creamy dengan whipped cream.'],
            ['name' => 'Lemon Tea', 'sku' => 'NON-003', 'price' => 18000, 'stock' => 50, 'description' => 'Teh segar dengan perasan lemon asli.'],
            ['name' => 'Vanilla Milkshake', 'sku' => 'NON-004', 'price' => 32000, 'stock' => 30, 'description' => 'Milkshake vanilla dengan es krim premium.'],
        ];

        foreach ($nonCoffeeProducts as $prod) {
            Product::firstOrCreate(
                ['sku' => $prod['sku']],
                array_merge($prod, ['category_id' => $nonKopi->id])
            );
        }

        // Produk Makanan
        $foodProducts = [
            ['name' => 'Nasi Goreng Special', 'sku' => 'FOOD-001', 'price' => 35000, 'stock' => 25, 'description' => 'Nasi goreng dengan ayam dan telur mata sapi.'],
            ['name' => 'Mie Goreng', 'sku' => 'FOOD-002', 'price' => 30000, 'stock' => 25, 'description' => 'Mie goreng pedas dengan sayuran.'],
            ['name' => 'Sandwich Club', 'sku' => 'FOOD-003', 'price' => 32000, 'stock' => 20, 'description' => 'Triple decker sandwich dengan ayam, telur, dan sayuran.'],
        ];

        foreach ($foodProducts as $prod) {
            Product::firstOrCreate(
                ['sku' => $prod['sku']],
                array_merge($prod, ['category_id' => $makanan->id])
            );
        }

        // Produk Cemilan
        $snackProducts = [
            ['name' => 'Croissant Butter', 'sku' => 'SNACK-001', 'price' => 18000, 'stock' => 30, 'description' => 'Croissant renyah dengan mentega premium.'],
            ['name' => 'French Fries', 'sku' => 'SNACK-002', 'price' => 15000, 'stock' => 40, 'description' => 'Kentang goreng crispy dengan saus pilihan.'],
            ['name' => 'Chicken Wings', 'sku' => 'SNACK-003', 'price' => 28000, 'stock' => 25, 'description' => 'Sayap ayam goreng dengan bumbu spesial.'],
        ];

        foreach ($snackProducts as $prod) {
            Product::firstOrCreate(
                ['sku' => $prod['sku']],
                array_merge($prod, ['category_id' => $cemilan->id])
            );
        }

        // Produk Dessert
        $dessertProducts = [
            ['name' => 'Tiramisu', 'sku' => 'DESS-001', 'price' => 35000, 'stock' => 15, 'description' => 'Tiramisu klasik Italia dengan mascarpone.'],
            ['name' => 'Chocolate Lava Cake', 'sku' => 'DESS-002', 'price' => 38000, 'stock' => 15, 'description' => 'Kue cokelat lumer dengan vanilla ice cream.'],
            ['name' => 'Cheesecake', 'sku' => 'DESS-003', 'price' => 32000, 'stock' => 20, 'description' => 'New York style cheesecake dengan blueberry sauce.'],
        ];

        foreach ($dessertProducts as $prod) {
            Product::firstOrCreate(
                ['sku' => $prod['sku']],
                array_merge($prod, ['category_id' => $dessert->id])
            );
        }

        $this->command->info('✅ Data Purr\'Coffee berhasil ditambahkan!');
    }
}
