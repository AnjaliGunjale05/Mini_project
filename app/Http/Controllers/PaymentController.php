<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class PaymentController extends Controller
{
    public function pay($orderId)
    {
        $order = Order::findOrFail($orderId);

        return view('payment', compact('order'));
    }

    public function success(Request $request)
    {
        $order = Order::find($request->order_id);

        // if ($order) {
        //     $order->update([
        //         'payment_status' => 'paid',
        //         'transaction_id' => $request->razorpay_payment_id,
        //     ]);
        // }

        return redirect()->route('checkout.confirmation', $order->id)
            ->with('success', 'Payment Successful!');
    }
}