<?php

namespace App\Services;

use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;
use App\Models\Order;
use Exception;

class PaymentService
{
    // ✅ Create Razorpay Order
    public function createRazorpayOrder($order)
    {
        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

        $razorpayOrder = $api->order->create([
            'receipt' => 'order_' . $order->id,
            'amount' => $order->total * 100, // paise
            'currency' => 'INR'
        ]);

        // Save Razorpay order ID in DB
        $order->update([
            'razorpay_order_id' => $razorpayOrder['id']
        ]);

        return $razorpayOrder['id'];
    }

    // ✅ Verify Payment ONLY (no DB update here)
    public function verifyPayment($data, $order)
    {
        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

        try {
            $attributes = [
                'razorpay_order_id' => $order->razorpay_order_id,
                'razorpay_payment_id' => $data['razorpay_payment_id'],
                'razorpay_signature' => $data['razorpay_signature'],
            ];

            $api->utility->verifyPaymentSignature($attributes);

            return true;
        } catch (SignatureVerificationError $e) {
            return false;
        }
    }

    // (Optional fallback if needed)
    public function handlePaymentSuccess($data)
    {
        try {
            $order = Order::find($data['order_id']);

            if (!$order) {
                throw new Exception("Order not found");
            }

            $order->update([
                'transaction_id' => $data['razorpay_payment_id'],
                'payment_status' => 'paid'
            ]);

            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}