<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\WishlistController;

use App\Models\State;
use App\Models\City;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// ================= FRONTEND =================

// Home
Route::get('/', [HomeController::class, 'index'])->name('home');

// Shop
Route::get('/shop', [ProductController::class, 'index'])->name('shop');

// Product details

 Route::get('/product/{product}', [ProductController::class, 'show'])->name('product.show');

 // Wishlist 

Route::middleware('auth')->group(function () {
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/add/{id}', [WishlistController::class, 'add'])->name('wishlist.add');
    Route::post('/wishlist/remove/{id}', [WishlistController::class, 'remove'])->name('wishlist.remove');
});

// My Orders User

Route::middleware('auth')->group(function () {
    Route::get('/my-orders', [\App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');
    Route::get('/my-orders/{order}', [\App\Http\Controllers\OrderController::class, 'show'])->name('orders.show');
});

// ================= DASHBOARD REDIRECT =================

Route::get('/dashboard', function () {

    if (Auth::user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    return redirect()->route('home');
})->middleware(['auth'])->name('dashboard');


// ================= ADMIN =================

Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // Products
    Route::resource('products', ProductController::class);
    // Categories
    Route::resource('categories', CategoryController::class);

    // Orders
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('admin.orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('admin.orders.show');
    Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('admin.orders.status');

    //  FIX: admin/profile route
    Route::get('/profile', function () {
        return redirect()->route('profile.edit');
    });
});


// ================= PROFILE (COMMON) =================

Route::middleware(['auth'])->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// ================= CART =================

Route::controller(CartController::class)->group(function () {

    Route::get('/cart', 'index')->name('cart.index');
    Route::post('/cart/add/{id}', 'add')->name('cart.add');
    Route::post('/cart/update/{id}', 'update')->name('cart.update');
    Route::post('/cart/remove/{id}', 'remove')->name('cart.remove');
});


// ================= CHECKOUT =================

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/checkout/confirmation/{order}', [CheckoutController::class, 'confirmation'])->name('checkout.confirmation');

Route::get('/states/{countryId}', function ($countryId) {
    return \App\Models\State::where('country_id', $countryId)->get();
});

Route::get('/cities/{stateId}', function ($stateId) {
    return \App\Models\City::where('state_id', $stateId)->get();
});


// ================= AUTH =================

require __DIR__ . '/auth.php';
