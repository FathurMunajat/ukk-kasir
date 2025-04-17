<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

// Dashboard
Route::get('/Dashboard', [DashboardController::class, 'index'])->name('Dashboard');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');

// Produk
Route::middleware('auth')->group(function () {
    Route::get('/Product', [ProductController::class, 'index'])->name('Product');
    Route::resource('Product', ProductController::class);
    Route::post('Product/{id}/update-stock', [ProductController::class, 'updateStock'])->name('Product.updateStock');
});








    