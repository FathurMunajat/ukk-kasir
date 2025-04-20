<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchasesController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserController;

use Illuminate\Http\Request;

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes(['register' => false]);

// Dashboard
Route::get('/Dashboard', [DashboardController::class, 'index'])->name('Dashboard');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');
Route::get('/dashboard/export', [App\Http\Controllers\DashboardController::class, 'export'])->name('dashboard.export');


// Produk
Route::middleware('auth')->group(function () {
    Route::get('/Product', [ProductController::class, 'index'])->name('Product');
    Route::resource('Product', ProductController::class);
    Route::post('Product/{id}/update-stock', [ProductController::class, 'updateStock'])->name('Product.updateStock');
});



// Purchase

Route::get('/Purchase', [PurchasesController::class, 'index'])->name('Purchase.index');
Route::get('/Purchase/create', [PurchasesController::class, 'create'])->name('Purchase.create');
Route::post('/Purchase', [PurchasesController::class, 'store'])->name('Purchase.store');
Route::post('/Purchase/cart', [PurchasesController::class, 'cart'])->name('Purchase.cart');
Route::post('/Purchase/confirm', [PurchasesController::class, 'confirm'])->name('Purchase.confirm');
Route::get('/Purchase/invoice/{id}', [PurchasesController::class, 'invoice'])->name('Purchase.invoice');
Route::get('/Purchase/download/{id}', [App\Http\Controllers\PurchasesController::class, 'download'])->name('Purchase.download');
Route::get('/Purchase/modal/{id}', [PurchasesController::class, 'modal'])->name('Purchase.modal');
Route::get('/Purchase/export/excel', [PurchasesController::class, 'exportExcel'])->name('Purchase.export.excel');




Route::get('/Purchase/cart', function () {
    return redirect()->route('Purchase.create')->with('error', 'Akses langsung ke keranjang tidak diperbolehkan.');
});

Route::get('/Purchase/confirm', function () {
    return redirect()->route('Purchase.create')->with('error', 'Akses langsung ke konfirmasi tidak diperbolehkan.');
});



// User
Route::resource('user', UserController::class);
Route::get('/User', [UserController::class, 'index'])->name('User.index');
Route::post('/User', [UserController::class, 'store'])->name('User.store');
Route::put('/User/{user}', [UserController::class, 'update'])->name('User.update');
Route::delete('/User/{user}', [UserController::class, 'destroy'])->name('User.destroy');
Route::post('/admin/users/store', [UserController::class, 'store'])->name('admin.users.store');


//     Route::prefix('admin')->name('admin.')->group(function () {
//     Route::resource('users', UserController::class);
// });
// Mungkin ini ditulis dua ka

    