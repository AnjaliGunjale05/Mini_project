<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\{
    ProfileController,
    ProductController,
    CategoryController,
    CartController,
    HomeController,
    CheckoutController,
    AdminOrderController,
    WishlistController,
    PaymentController,
    ContactController,
    OrderController
};

use App\Models\State;
use App\Models\City;

// ================= FRONTEND =================

// Home
Route::get('/', [HomeController::class, 'index'])->name('home');

// Shop
Route::get('/shop', [ProductController::class, 'index'])->name('shop');

// Product Details
Route::get('/product/{product}', [ProductController::class, 'show'])->name('product.show');
Route::post('/review/store', [ProductController::class, 'storeReview'])->name('review.store');


// ================= WISHLIST =================
Route::middleware('auth')->group(function () {
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/add/{id}', [WishlistController::class, 'add'])->name('wishlist.add');
    Route::post('/wishlist/remove/{id}', [WishlistController::class, 'remove'])->name('wishlist.remove');
});

// ================= USER ORDERS =================
Route::middleware('auth')->group(function () {
    Route::get('/my-orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/my-orders/{order}', [OrderController::class, 'show'])->name('orders.show');
});

// ================= PAYMENT =================
Route::get('/payment/{id}', [PaymentController::class, 'pay'])->name('payment.pay');
Route::get('/payment/success/{id}', [PaymentController::class, 'success'])->name('payment.success');

// Confirmation Page
Route::get('/checkout/confirmation/{order}', [CheckoutController::class, 'confirmation'])
    ->name('checkout_confirmation');

// ================= STATIC PAGES =================
Route::view('/about', 'layouts.footerlinks.about')->name('about');
Route::view('/contact', 'layouts.footerlinks.contact')->name('contact');
Route::view('/privacy', 'layouts.footerlinks.privacy')->name('privacy');

Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');


// ================= DASHBOARD =================
Route::get('/dashboard', function () {

    if (Auth::user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    return redirect()->route('home');

})->middleware(['auth'])->name('dashboard');

// ================= ADMIN =================
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {

    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::resource('products', ProductController::class);
    Route::resource('categories', CategoryController::class);

    Route::get('/orders', [AdminOrderController::class, 'index'])->name('admin.orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('admin.orders.show');
    Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('admin.orders.status');

    Route::get('/profile', function () {
        return redirect()->route('profile.edit');
    });
});

// ================= PROFILE =================
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

// ================= LOCATION APIs =================
Route::get('/states/{countryId}', function ($countryId) {
    return State::where('country_id', $countryId)->get();
});

Route::get('/cities/{stateId}', function ($stateId) {
    return City::where('state_id', $stateId)->get();
});

// ================= AUTH =================
require __DIR__ . '/auth.php';