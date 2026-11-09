<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LockController;

Route::get('lock/screen', [LockController::class,'show'])->name('lock.screen');
Route::post('lock/unlock', [LockController::class,'unlock'])->name('lock.unlock');
