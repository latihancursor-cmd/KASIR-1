<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$kasir = User::where('email', 'kasir@pos.test')->first();
if ($kasir) {
    $kasir->password = Hash::make('secret123');
    $kasir->save();
    echo "Password kasir direset ke secret123\n";
} else {
    echo "User kasir tidak ditemukan\n";
}

$admin = User::where('email', 'admin@pos.test')->first();
if ($admin) {
    $admin->password = Hash::make('secret123');
    $admin->save();
    echo "Password admin direset ke secret123\n";
} else {
    echo "User admin tidak ditemukan\n";
}