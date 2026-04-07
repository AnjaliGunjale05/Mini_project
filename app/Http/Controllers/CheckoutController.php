<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;
use App\Services\CartService;
use App\Services\OrderService;
use App\Models\State;
use App\Models\City;

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
        app(\App\Services\CartService::class)->syncCartFromDB();
    } 
        $cart = $this->cartService->getCart();

        if (empty($cart)) {
            return redirect()->route('products.index')->with('error', 'Your cart is empty.');
        }
        $countries=Country::all();

        return view('checkout', compact('cart','countries'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'required|regex:/^[0-9]{10}$/',
            'country' => 'required',
            'state' => 'required',
            'city' => 'required|max:255|string',
            'landmark' => 'nullable|string',
            'postal_code' => 'required|digits:6',
        ]);
 
        // Converting Id's into Name 
         $countryName = Country::find($request->country)?->name;
    $stateName   = State::find($request->state)?->name;
    $cityName    = $request->city;

    $data = [
        'name'        => $request->name,
        'email'       => $request->email,
        'phone'       => $request->phone,
        'country'     => $countryName,
        'state'       => $stateName,
        'city'        => $cityName,
        'landmark'    => $request->landmark,
        'postal_code' => $request->postal_code,
    ];

        $order = $this->orderService->placeOrder($data,auth()->id() );

        return redirect()->route('checkout.confirmation', $order->id)->with('success',"Order Placed Successfully !");
    }

    public function confirmation($orderId)
    {
        $order = \App\Models\Order::with('items')->findOrFail($orderId);
        return view('checkout_confirmation', compact('order'));
    }
}
