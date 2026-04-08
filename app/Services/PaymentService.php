<?php

namespace App\Services;

use App\Models\Order;
use Exception;

class PaymentService
{
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