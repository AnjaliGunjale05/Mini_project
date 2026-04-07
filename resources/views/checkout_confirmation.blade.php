@extends('layouts.app')
@section('content')

<div class="bg-gray-100 min-h-screen py-10">
    <div class="max-w-4xl mx-auto bg-white p-8 rounded-xl shadow">
        <!-- Success Message  -->
        @if(session('success'))
        <div class="bg-green-100 text-green-700 p-4 rounded mb-5 border border-green-300">
            {{ session('success') }}
        </div>
        @endif
        <!-- Thank You Message  -->
        <div class="text-center mb-8">
            <!-- <h1 class="text-2xl font-bold mb-5">Thank You!</h1> -->
            <p class="text-gray-600 mt-2">Your order has been placed successfully.</p>
        </div>
        <!-- ✅ Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-green-600">Order Confirmed 🎉</h1>
            <p class="text-gray-600 mt-2">Thank you for shopping with us!</p>
        </div>
        <!-- Order Info -->
        <div class="border rounded-lg p-4 mb-6 bg-gray-50">
            <div class="flex justify-between mb-2">
                <span class="font-medium">Order ID: </span>
                <span>#{{ $order->id }} </span>
            </div>
            <div class="flex justify-between mb-2">
                <span class="font-medium">Status: </span>
                <span>{{ $order->status ?? 'Pending' }}</span>
            </div>
            <div class="flex justify-between mb-2">
                <span class="font-medium">Total: </span>
                <span>₹{{ number_format($order->total, 2) }}</span>
            </div>
        </div>

        <!-- Shipping Address -->

        <div class="mb-6">
            <h2 class="text-lg font-semibold mb-2">📦 Shipping Address</h2>
            <p class="text-grey-700">
                {{$order->name}} <br>
                {{$order->address ?? 'Address-'}} {{$order->landmark}} <br>
                {{$order->city}}, {{$order->state}} <br>
                {{$order->country}}, {{$order->postal_code}} <br>
                📞 {{$order->phone}} <br>
            </p>
        </div>

        <!-- Order Items -->
        <div>

            <h2 class="text-xl font-semibold mb-4">Order Items: </h2>
            <div class="space-y-4">
                <div class="space-y-4">
                    @foreach($order->items as $item)
                    <div class="flex items-center gap-4 border-b pb-3">

                        <!-- Product Image -->
                        <img src="{{ asset('storage/'.$item->product->image) }}"
                            class="w-16 h-16 object-cover rounded-lg border">

                        <!-- Details -->
                        <div class="flex-1">
                            <h4 class="font-medium">{{ $item->product->name }}</h4>
                            <p class="text-gray-500 text-sm">Qty: {{ $item->quantity }}</p>
                        </div>

                        <!-- Price -->
                        <div class="font-semibold">
                            ₹{{ number_format($item->price * $item->quantity, 2) }}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="mt-8 flex justify-between">
                <a href="{{ route('shop') }}" class="mt-5 inline-block text-blue-600 hover:underline">
                    Continue Shopping
                </a>
                <a href="{{ route('home') }}" class="mt-5 inline-block text-blue-600 hover:underline">
                    Go to Home
                </a>
            </div>
        </div>
    </div>
</div>
@endsection