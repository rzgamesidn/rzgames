<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;


// --- AREA PUBLIK ---
Route::get('/', [HomeController::class, 'index'])->name('user.home');
Route::get('/product/{id}', [HomeController::class, 'detail'])->name('user.detail');
Route::get('/search', [HomeController::class, 'search'])->name('products.search');
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

// --- FITUR GUEST (Tamu & User Bisa Akses Tanpa Login) ---
Route::get('/cart', [HomeController::class, 'cart'])->name('cart.index'); // Halaman Keranjang
Route::post('/cart/add/{id}', [HomeController::class, 'addToCart'])->name('cart.add');
Route::post('/cart/remove', [HomeController::class, 'removeFromCart'])->name('cart.remove'); 

Route::get('/favorites', [HomeController::class, 'favorites'])->name('favorite.index'); // Halaman Favorit
Route::post('/favorite/add/{id}', [HomeController::class, 'addToFavorite'])->name('favorite.add'); 

// Rute Checkout & Payment
Route::post('/checkout/{id?}', [HomeController::class, 'checkout'])->name('payment.checkout');
Route::post('/cart-checkout/{id}', [HomeController::class, 'checkout'])->name('cart.checkout');
Route::get('/payment/{id}', [HomeController::class, 'payment'])->name('user.payment');
Route::get('/order-success/{id}', [App\Http\Controllers\HomeController::class, 'checkoutSuccess'])->name('order.success');
Route::post('/update-email-order/{id}', [App\Http\Controllers\HomeController::class, 'updateEmailOrder']);
Route::get('/order/download-invoice/{id}', [HomeController::class, 'downloadInvoice'])->name('order.download_invoice');

// --- AREA USER (Hanya yang Login) ---
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // History transaksi tetep wajib login (buat privasi)
    Route::get('/history', [HomeController::class, 'history'])->name('order.history');
});

// --- 3. AREA ADMIN (Opsional - Jika diperlukan) ---
// Route::middleware(['auth', 'admin'])->group(function () {
//     Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
// });

require __DIR__.'/auth.php';

// 3. Area Admin RZGAMES
Route::middleware(['auth', 'checkRole:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/list-product', [AdminController::class, 'listProducts'])->name('list');
    Route::get('/add-product', [AdminController::class, 'addProduct'])->name('add'); 
    Route::post('/products/store', [AdminController::class, 'store'])->name('store');
    Route::get('/checkout/success/{id}', [HomeController::class, 'checkoutSuccess'])->name('checkout.success');
    Route::get('/edit-product/{id}', [AdminController::class, 'edit'])->name('edit');
    Route::put('/update-product/{id}', [AdminController::class, 'update'])->name('update');
    Route::delete('/delete-product/{id}', [AdminController::class, 'deleteProduct'])->name('delete');
    Route::post('/midtrans/callback', [HomeController::class, 'callback']);
    
    // Perbaikan Fitur Admin (Biar image_5d7323.png beres)
    Route::get('/orders', [AdminController::class, 'orders'])->name('orders'); // Ini akan jadi admin.orders
    Route::patch('/orders/{id}', [AdminController::class, 'updateStatus'])->name('orders.update');// Ini akan jadi admin.orders.update
    Route::get('/account', [AdminController::class, 'account'])->name('account');
});

// 4. Profile System
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

