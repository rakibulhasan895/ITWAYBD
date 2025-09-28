<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('users', UserController::class);
    Route::get('users-trash', [UserController::class, 'trash'])->name('users.trash');
    Route::post('users/{id}/restore', [UserController::class, 'restore'])->name('users.restore');
    Route::delete('users/{id}/force-delete', [UserController::class, 'forceDelete'])->name('users.forceDelete');

    Route::resource('products', ProductController::class);
    Route::get('products-trash', [ProductController::class, 'trash'])->name('products.trash');
    Route::post('products/{id}/restore', [ProductController::class, 'restore'])->name('products.restore');
    Route::delete('products/{id}/force-delete', [ProductController::class, 'forceDelete'])->name('products.forceDelete');

    Route::resource('sales', SaleController::class);

    Route::get('sales-trash', [SaleController::class, 'trash'])->name('sales.trash');
    Route::post('sales/{id}/restore', [SaleController::class, 'restore'])->name('sales.restore');
    Route::delete('sales/{id}/force-delete', [SaleController::class, 'forceDelete'])->name('sales.forceDelete');
});

require __DIR__ . '/auth.php';
