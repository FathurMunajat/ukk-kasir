<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\UserController;
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

// Purchase
Route::get('/Purchase', [PurchaseController::class, 'index'])->name('Purchase.index');
Route::get('/Purchase/create', [PurchaseController::class, 'create'])->name('Purchase.create');


// User
Route::resource('user', UserController::class);
Route::get('/User', [UserController::class, 'index'])->name('User.index');
Route::post('/User', [UserController::class, 'store'])->name('User.store');
Route::put('/User/{user}', [UserController::class, 'update'])->name('User.update');
Route::delete('/User/{user}', [UserController::class, 'destroy'])->name('User.destroy');
Route::post('/admin/users/store', [UserController::class, 'store'])->name('admin.users.store');


// Route::prefix('adimin')->name('admin.')->group(function () {
//     Route::resource('users', UserController::class);
// });


    