<?php

namespace App\Providers;
use App\Services\CartService;
use App\Models\Wishlist;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema; 

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Schema::defaultStringLength(191);
         view()->composer('*', function ($view) {
       $cartService = app(CartService::class);
        $cartCount = $cartService->getCartCount();

        $wishlistItems = [];
        $wishlistCount=0;

        // Conditional Wishlist Icon
        if (auth()->check()) {
            $wishlistItems = Wishlist::where('user_id', auth()->id())
                ->pluck('product_id')
                ->toArray();
        }
        
        // For counting Items
         
         if (auth()->check()) {
        $wishlistCount = Wishlist::where('user_id', auth()->id())->count();
    }

        $view->with([
            'cartCount' => $cartCount,
            'wishlistItems' => $wishlistItems,
            'wishlistCount' => $wishlistCount
        ]);
    });
    }
}