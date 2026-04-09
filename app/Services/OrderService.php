<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderPlacedMail;
use Exception;

class OrderService
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    // Create Order (NO payment logic here)
    public function placeOrder(array $data, ?int $userId = null)
    {
        $cart = $this->cartService->getCart();

        if (empty($cart)) {
            return null;
        }

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
            'transaction_id' => null,
        ]);

        foreach ($cart as $productId => $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $productId,
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }

        $order->load('items.product');

        return $order;
    }

    //  Call AFTER successful payment
    public function handleSuccessfulOrder($order, $transactionId)
    {
        $order->update([
            'transaction_id' => $transactionId,
            'payment_status' => 'paid',
        ]);

        // Send email after payment success
        try {
            if ($order->email) {
                Mail::to($order->email)->send(new OrderPlacedMail($order));
            }
        } catch (Exception $e) {
            \Log::error('Mail Error: ' . $e->getMessage());
        }

        // Clear cart
        $this->cartService->clearCart();

        return true;
    }
}