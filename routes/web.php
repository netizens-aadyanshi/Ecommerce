<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductImageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Authenticated & Verified Routes (Customers + Admins)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Customer Product Browsing (Strictly Read-Only)
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
});

/*
|--------------------------------------------------------------------------
| Admin-Only Routes (Management)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {

    // Category Management
    Route::resource('categories', CategoryController::class);

    // Product Management Table View
    Route::get('/products', [ProductController::class, 'adminIndex'])->name('products.adminIndex');

    // Product CRUD (Create, Edit, Store, Update, Destroy)
    // Note: The prefix 'admin' makes the URL /admin/products/create
    Route::resource('products', ProductController::class)->except(['index', 'show']);

    // Quick Toggle for Active/Inactive
    Route::post('/products/{product}/toggle', [ProductController::class, 'toggleActive'])->name('products.toggle');

    // Product Image Gallery Management
    Route::get('/product-images/{productImage}/primary', [ProductImageController::class, 'setPrimary'])->name('product-images.setPrimary');
    Route::delete('/product-images/{productImage}', [ProductImageController::class, 'destroy'])->name('product-images.destroy');
});

require __DIR__.'/auth.php';
