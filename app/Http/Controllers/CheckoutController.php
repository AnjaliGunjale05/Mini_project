<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;
use App\Services\CartService;
use App\Services\OrderService;

class CheckoutController extends Controller
{
    protected $cartService;
    protected $orderService;

    public function __construct(CartService $cartService, OrderService $orderService)
    {
        $this->cartService = $cartService;
        $this->orderService = $orderService;
    }

    public function index()
    {
        if (auth()->check()) {
            $this->cartService->syncCartFromDB();
        }

        $cart = $this->cartService->getCart();

        if (empty($cart)) {
            return redirect()->route('shop')
                ->with('error', 'Your cart is empty.');
        }

        $countries = Country::all();

        return view('checkout', compact('cart', 'countries'));
    }

    public function store(Request $request)
    {
        try {

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'nullable|email',
                'phone' => 'required|regex:/^[0-9]{10}$/',
                'country' => 'required',
                'state' => 'required',
                'city' => 'required|max:255|string',
                'landmark' => 'nullable|string',
                'postal_code' => 'required|digits:6',
            ]);

            $countryName = \App\Models\Country::find($request->country)?->name;
            $stateName   = \App\Models\State::find($request->state)?->name;

            $data = [
                'name'        => $request->name,
                'email'       => $request->email,
                'phone'       => $request->phone,
                'country'     => $countryName,
                'state'       => $stateName,
                'city'        => $request->city,
                'landmark'    => $request->landmark,
                'postal_code' => $request->postal_code,
            ];

            $order = $this->orderService->placeOrder($data, auth()->id());

            if (!$order) {
                return response()->json(['error' => 'Cart is empty'], 400);
            }

            return response()->json([
                'order_id' => $order->id,
                'amount'   => $order->total
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function confirmation($orderId)
    {
        $order = \App\Models\Order::with('items.product')->findOrFail($orderId);

        return view('checkout_confirmation', compact('order'));
    }
}