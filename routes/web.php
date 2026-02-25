<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// --- ADMIN AREA ---
// We add 'admin' here. Only the AdminSeeder user can see this.
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified', 'admin'])->name('dashboard');

// --- SHARED AUTH AREA ---
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Customers and Admins can see products, but they MUST be verified
    Route::get('/products', function () {
        return view('products.index');
    })->middleware(['verified'])->name('products.index');
});

require __DIR__.'/auth.php';
