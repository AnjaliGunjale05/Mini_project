<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Services\PaymentService;
use App\Services\OrderService;
use Stripe\ApiOperations\Update;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class PaymentController extends Controller
{
    protected $paymentService;
    protected $orderService;

    public function __construct(PaymentService $paymentService, OrderService $orderService)
    {
        $this->paymentService = $paymentService;
        $this->orderService = $orderService;
    }

    //  Show payment page
    public function pay($orderId)
    {
        $order = Order::findOrFail($orderId);
        Stripe::setApiKey(config('services.stripe.secret'));

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'inr',
                    'product_data' => [
                        'name' => 'order #' . $order->id,
                    ],
                    'unit_amount' => $order->total * 100,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',

            // REDIRECT URLS
            'success_url' => route('payment.success', $order->id),
            'cancel_url' => route('checkout.index'),

        ]);

        // Razorpay Details

        // $razorpayOrderId = $this->paymentService->createRazorpayOrder($order);
        // return view('payment', compact('order', 'razorpayOrderId'));

        return redirect($session->url);
    }

    //  Handle payment success (AJAX)
    // public function success(Request $request)
    // {
    //     $order = Order::findOrFail($request->order_id);

    //     $isVerified = $this->paymentService->verifyPayment($request->all(), $order);

    //     if ($isVerified) {

    //         //  Update order + send email + clear cart
    //         $this->orderService->handleSuccessfulOrder(
    //             $order,
    //             $request->razorpay_payment_id
    //         );

    //         return response()->json([
    //             'status' => 'success'
    //         ]);
    //     }

    //     return response()->json([
    //         'status' => 'failed'
    //     ], 400);
    // }

    public function success($orderId){
        $order= Order::findOrFail($orderId);

        $this->orderService->handleSuccessfulOrder(
            $order,
            'stripe_' . uniqid()
            );

        $order->Update([
            'payment_status'=>'paid',
            'transaction_id'=>'stripe_' .uniqid(),
        ]);
        return redirect()->route('checkout_confirmation', $order->id)->with('success','Payment Successful');
    }

    // Success Page
    public function successPage($orderId)
    {
        $order = Order::findOrFail($orderId);

        return view('payment_success', compact('order'));
    }
}
