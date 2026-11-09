<?php

namespace Database\Seeders;

use App\Models\StoreSetting;
use Illuminate\Database\Seeder;

class StoreSettingsSeeder extends Seeder
{
    public function run()
    {
        StoreSetting::firstOrCreate([], [
            'store_name' => 'KasirNih',
            'address' => 'Jl. Contoh No. 123, Jakarta',
            'phone' => '+62 812-3456-7890',
            'closing_reminder' => true,
            'closing_time' => '21:00',
        ]);
    }
}
