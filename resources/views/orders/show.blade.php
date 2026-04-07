@extends('layouts.app')

@section('content')

<div class="max-w-5xl mx-auto px-4 py-10">
    <!-- Title -->
    <h2 class="text-2xl font-bold mb-6"> Order Details</h2>

    <!-- Order Info -->
    <div class="bg-white p-5 rounded-lg shadow mb-6">
        <div class="flex justify-between items-center mb-4">
            <div>
                <p class="font-semibold text-lg">Order: {{ $order->id }}</p>
                <p class="text-gray-500 text-sm">
                    {{ $order->created_at->format('d M Y, h:i A') }}
                </p>
            </div>
            <!-- Status -->
            <span class="px-3 py-1 rounded text-white
                @if($order->status == 'pending') bg-yellow-500
                @elseif($order->status == 'shipped') bg-blue-500
                @else bg-green-500 @endif">
                {{ ucfirst($order->status) }}
            </span>
        </div>
        <!-- Address -->
        <div class="mb-4">
            <h4 class="font-semibold">Shipping Address:</h4>
            <p class="text-gray-600 text-sm">
                {{$order->city ?? 'N/A'}},
                {{$order->landmark ?? ''}} ,<br> {{$order->state ?? 'N/A'}},
                {{$order->country ?? 'N/A' }}, {{$order->postal_code ?? 'N/A'}}
            </p>
        </div>

        <!-- Total -->
        <div class="text-right font-semibold text-lg">
            Total: ₹{{ $order->total }}
        </div>

    </div>

    <!-- Items Table  -->

    <div class="bg-white rounded-lg shadow overflow-hidden">

        <table class="w-full">

            <!-- Header -->
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left">Product</th>
                    <th class="p-3 text-left">Image</th>
                    <th class="p-3 text-left">Price</th>
                    <th class="p-3 text-left">Quantity</th>
                    <th class="p-3 text-left">Subtotal</th>
                </tr>
            </thead>

            <!-- Body -->
            <tbody>
                @foreach($order->items as $item)
                <tr class="border-t">

                    <!-- Product Name -->
                    <td class="p-3 font-medium">
                        {{ $item->product->name ?? 'Product not found' }}
                    </td>

                    <!-- Product Image -->
                    <td class="p-3">
                        <img 
                            src="{{ $item->product && $item->product->image ? asset('storage/'.$item->product->image) : 'https://via.placeholder.com/80' }}" 
                            class="w-16 h-16 object-cover rounded">
                    </td>

                    <!-- Price -->
                    <td class="p-3">
                        ₹{{ $item->price }}
                    </td>

                    <!-- Quantity -->
                    <td class="p-3">
                        {{ $item->quantity }}
                    </td>

                    <!-- Subtotal -->
                    <td class="p-3 font-semibold">
                        ₹{{ $item->price * $item->quantity }}
                    </td>

                </tr>
                @endforeach
            </tbody>

        </table>

    </div>

    <!-- Back Button -->
    <div class="mt-6">
        <a href="{{ route('orders.index') }}"
           class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
            ← Back to Orders
        </a>
    </div>

</div>
@endsection