<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CartService;

class CartController extends Controller
{
    protected $cartService;

    // Inject Service
    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    // Cart Page
    public function index()
    {
        // Sync DB → Session (important for logged in user)
        $this->cartService->syncCartFromDB();

        $cart = $this->cartService->getCart();
        $total = $this->cartService->getTotal();

        return view('cart.index', compact('cart', 'total'));
    }

    // Add to Cart
    public function add($id)
    {
        if (!auth()->check()) {
        return redirect()->route('login')
            ->with('error', 'Please login or create an account to add items to cart');
    }

    if (!$this->cartService->addToCart($id)) {
        return back()->with('error', 'Product out of stock');
    }

        // $this->cartService->addToCart($id);

        return redirect()->back()->with('success', 'Product added to cart successfully!');
    }

    //  Update Quantity
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $this->cartService->updateQuantity($id, $request->quantity);

        return redirect()->back()->with('success', 'Cart updated successfully!');
    }

    //  Remove Item
    public function remove($id)
    {
        $this->cartService->removeItem($id);

        return redirect()->back()->with('success', 'Item removed from cart!');
    }
}