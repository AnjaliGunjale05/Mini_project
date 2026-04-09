<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Services\PaymentService;
use App\Services\OrderService;

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

        $razorpayOrderId = $this->paymentService->createRazorpayOrder($order);

        return view('payment', compact('order', 'razorpayOrderId'));
    }

    //  Handle payment success (AJAX)
    public function success(Request $request)
    {
        $order = Order::findOrFail($request->order_id);

        $isVerified = $this->paymentService->verifyPayment($request->all(), $order);

        if ($isVerified) {

            //  Update order + send email + clear cart
            $this->orderService->handleSuccessfulOrder(
                $order,
                $request->razorpay_payment_id
            );

            return response()->json([
                'status' => 'success'
            ]);
        }

        return response()->json([
            'status' => 'failed'
        ], 400);
    }

    // Success Page
    public function successPage($orderId)
    {
        $order = Order::findOrFail($orderId);

        return view('payment_success', compact('order'));
    }
}