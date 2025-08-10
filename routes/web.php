<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;

use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');


Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    Route::get('/products', [ProductController::class, 'index'])->name('vendor.products');
    Route::post('/product/save', [ProductController::class, 'store'])->name('vendor.product.store');

});


Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
Route::patch('/cart/{id}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{id}', [CartController::class, 'destroy'])->name('cart.destroy');
Route::get('/cart/data', [CartController::class, 'getCartData'])->name('cart.data');


Route::middleware('auth')->group(function () {
    Route::post('/checkout', [CheckoutController::class, 'checkoutAll'])->name('checkout');
    Route::post('/checkout/vendor', [CheckoutController::class, 'checkoutVendor'])->name('checkout.vendor');

});
Route::get('/thank-you', function () {
    return view('pages.thank-you'); })->name('thank-you');



require __DIR__.'/auth.php';
