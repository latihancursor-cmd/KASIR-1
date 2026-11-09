<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $kasirRole = Role::firstOrCreate(['name' => 'kasir']);

        // Buat Akun Admin
        $admin = User::firstOrCreate([
            'email' => 'admin@pos.test'
        ], [
            'name' => 'Administrator',
            'password' => Hash::make('secret123'),
        ]);
        if (!$admin->hasRole('admin')) {
            $admin->roles()->attach($adminRole);
        }

        // Buat Akun Kasir
        $kasir = User::firstOrCreate([
            'email' => 'kasir@pos.test'
        ], [
            'name' => 'Kasir Utama',
            'password' => Hash::make('secret123'),
        ]);
        if (!$kasir->hasRole('kasir')) {
            $kasir->roles()->attach($kasirRole);
        }
    }
}
