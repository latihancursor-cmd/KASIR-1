<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$users = App\Models\User::with('roles')->get();
foreach($users as $u){
    echo $u->email . ' roles: ' . implode(',', $u->roles->pluck('name')->toArray()) . PHP_EOL;
}