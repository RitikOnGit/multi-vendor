<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;

use App\Http\Controllers\UserOrderController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');


Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/admin/vendors', [AdminController::class, 'vendors'])->name('admin.vendors');
    Route::post('/vendors/{id}/toggle-status', [AdminController::class, 'toggleStatus'])->name('vendors.toggleStatus');
    Route::get('/admin/products', [AdminController::class, 'viewProducts'])->name('admin.products');
    Route::get('/admin/vendor/{vendorId}', [AdminController::class, 'vendorPage'])->name('admin.vendorPage');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/payment/{order}', [PaymentController::class, 'showPaymentPage_razor'])->name('payment.razorpay.page');
    Route::post('/payment/razorpay-success/{order}', [PaymentController::class, 'razorpaySuccess'])->name('payment.razorpay.success');
    Route::post('/payment/success', [PaymentController::class, 'paymentSuccess'])
    ->name('payment.success');


});

Route::get('/payment/mock/{order}', [PaymentController::class, 'showPaymentPage'])->name('mock.payment.page');
Route::post('/payment/{order}/mock-pay', [PaymentController::class, 'mockPay'])->name('payment.mock');


Route::prefix('vendor')->name('vendor.')->middleware(['auth'])->group(function () {
    Route::get('/products', [ProductController::class, 'index'])->name('products');
    Route::post('/product/save', [ProductController::class, 'store'])->name('product.store');
    Route::get('/products/{product}/edit', [ProductController::class, 'editPage'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');


    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::patch('orders/{id}', [OrderController::class, 'updateStatus'])->name('orders.update');
});


Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
Route::patch('/cart/{id}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{id}', [CartController::class, 'destroy'])->name('cart.destroy');
Route::get('/cart/data', [CartController::class, 'getCartData'])->name('cart.data');

Route::middleware('auth')->group(function () {

    Route::get('/my-orders', [UserOrderController::class, 'index'])->name('myOrders');
    Route::post('/checkout', [CheckoutController::class, 'checkoutAll'])->name('checkout');
    Route::post('/checkout/vendor', [CheckoutController::class, 'checkoutVendor'])->name('checkout.vendor');

});
Route::get('/thank-you', function () {
    return view('pages.thank-you');
})->name('thank-you');



require __DIR__ . '/auth.php';
