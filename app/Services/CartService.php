<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Product;

class CartService
{
    // Get Cart (from session)
    public function getCart()
    {
        return session()->get('cart', []);
    }

    // Add to Cart
    public function addToCart($id)
    {
        $product = Product::findOrFail($id);

        // Stock check 
        if ($product->stock <= 0) {
            return false;
        }

        if (auth()->check()) {

            $userId = auth()->id();

            $cartItem = Cart::where('user_id', $userId)
                ->where('product_id', $id)
                ->first();

                $currentQty= $cartItem ? $cartItem->quantity : 0;

                if($currentQty +1 > $product->stock){
                    return 'exceeded';
                }

            if ($cartItem) {
                $cartItem->increment('quantity');
            } else {
                Cart::create([
                    'user_id' => $userId,
                    'product_id' => $id,
                    'quantity' => 1,
                ]);
            }

            // Sync DB → Session
            $this->syncCartFromDB();
        } else {

            $cart = session()->get('cart', []);
            $currentQty= isset($cart[$id]) ? $cart[$id]['quantity'] :0;
            
            if($currentQty +1 > $product->stock){
                return 'exceeded';
            }

            if (isset($cart[$id])) {
                $cart[$id]['quantity']++;
            } else {
                $cart[$id] = [
                    'name' => $product->name,
                    'price' => $product->price,
                    'image' => $product->image,
                    'quantity' => 1,
                ];
            }

            session()->put('cart', $cart);
        }

        return true;
    }

    // Update Quantity
    public function updateQuantity($id, $qty)
    {
        $product= Product::findOrFail($id);
        //  prevent 0 or negative
        $qty = max(1, (int) $qty); 

        // Stock validation
        if($qty > $product->stock){
            return 'exceeded';
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {

            $cart[$id]['quantity'] = $qty;

            if (auth()->check()) {
                Cart::where('user_id', auth()->id())
                    ->where('product_id', $id)
                    ->update(['quantity' => $qty]);
            }

            session()->put('cart', $cart);
        }
    }

    // Remove Item
    public function removeItem($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {

            unset($cart[$id]);

            if (auth()->check()) {
                Cart::where('user_id', auth()->id())
                    ->where('product_id', $id)
                    ->delete();
            }

            session()->put('cart', $cart);
        }
    }

    // Get Total
    public function getTotal()
    {
        return collect($this->getCart())
            ->sum(fn($item) => $item['price'] * $item['quantity']);
    }

    // Sync DB → Session
    public function syncCartFromDB()
    {
        if (!auth()->check()) return;

        $cartItems = Cart::with('product')
            ->where('user_id', auth()->id())
            ->get();

        $cart = [];

        foreach ($cartItems as $item) {
            if ($item->product) {
                $cart[$item->product_id] = [
                    "name" => $item->product->name,
                    "price" => $item->product->price,
                    "image" => $item->product->image,
                    "quantity" => $item->quantity,
                ];
            }
        }

        session()->put('cart', $cart);
    }

    // Sync After Login
    public function syncCartAfterLogin()
    {
        if (!auth()->check()) return;

        // Merge session cart into DB
        $sessionCart = session()->get('cart', []);

        foreach ($sessionCart as $productId => $item) {

            $cartItem = Cart::where('user_id', auth()->id())
                ->where('product_id', $productId)
                ->first();

            if ($cartItem) {
                $cartItem->quantity = max($cartItem->quantity, $item['quantity']);
                $cartItem->save();
            } else {
                Cart::create([
                    'user_id' => auth()->id(),
                    'product_id' => $productId,
                    'quantity' => $item['quantity'],
                ]);
            }
        }

        // Sync back to session
        $this->syncCartFromDB();
    }

    // Cart Count
    public function getCartCount()
    {
        if (auth()->check()) {
            return Cart::where('user_id', auth()->id())
                ->sum('quantity'); // FIXED (was count)
        }

        $cart = session()->get('cart', []);

        return array_sum(array_column($cart, 'quantity'));
    }

    // Clear Cart

    public function clearCart()
    {
        // Clear session
        session()->forget('cart');

        // Clear DB cart for logged-in user
        if (auth()->check()) {
            Cart::where('user_id', auth()->id())->delete();
        }
    }

    public function mergeGuestCartWithUserCart()
    {
        if (!auth()->check()) return;
        $sessionCart = session()->get('cart', []);
        $userId = auth()->id();

        if (empty($sessionCart)) {
            return;
        }

        foreach ($sessionCart as $productId => $item) {
            $existing = Cart::where('user_id', $userId)
                ->where('product_id', $productId)
                ->first();

            if ($existing) {
                // If already exists → increase quantity
                $existing->quantity += $item['quantity'];
                $existing->save();
            } else {
                // Insert new
                \App\Models\Cart::create([
                    'user_id' => $userId,
                    'product_id' => $productId,
                    'quantity' => $item['quantity'],
                ]);
            }
        }

        // Clear session cart after merge
        session()->forget('cart');
        // Sync DB -> session
        $this->syncCartFromDB();
    }
}
