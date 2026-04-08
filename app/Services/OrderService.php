<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;

class OrderService
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }


    public function placeOrder(array $data, ?int $userId = null)
    {
        $cart = $this->cartService->getCart();

        if (empty($cart)) {
            return null; // nothing to order
        }

        // Create Order
        $order = Order::create([
            'user_id' => $userId,
            'name' => $data['name'],
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'],
            'country' => $data['country'],
            'state' => $data['state'],
            'city' => $data['city'],
            'landmark' => $data['landmark'],
            'postal_code' => $data['postal_code'],
            'total' => $this->cartService->getTotal(),
            'payment_status' => 'pending',
            'transaction_id' => null
        ]);

        // Create OrderItems
        foreach ($cart as $productId => $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $productId,
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }

        // Clear cart
        // $this->cartService->clearCart();

        return $order;
    }
    public function markAsPaid($orderId, $transactionId)
    {
        $order = Order::findOrFail($orderId);

        $order->update([
            'transaction_id' => $transactionId,
            'payment_status' => 'paid'
        ]);

        // Clear cart AFTER payment success
        $this->cartService->clearCart();

        return true;
    }
}
