@extends('layouts.dashboard')

@section('dashboard-content')

<div class="max-w-5xl mx-auto bg-white p-6 rounded-lg shadow">

    <!-- Title -->
    <h2 class="text-2xl font-bold mb-6">
        Order: {{ $order->id }}
    </h2>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- ================= CUSTOMER INFO ================= -->
    <div class="mb-6">
        <h3 class="text-lg font-semibold mb-2 border-b pb-1">Customer Details</h3>

        <p><strong>Name:</strong> {{ $order->user->name ?? 'Guest' }}</p>
        <p><strong>Email:</strong> {{ $order->user->email ?? 'N/A' }}</p>
    </div>

    <!-- ================= ORDER INFO ================= -->
    <div class="mb-6">
        <h3 class="text-lg font-semibold mb-2 border-b pb-1">Order Info</h3>

        <p><strong>Total:</strong> ₹{{ $order->total }}</p>

        <p>
            <strong>Status:</strong>
            <span class="px-2 py-0 rounded text-white
                @if($order->status == 'pending') bg-yellow-500
                @elseif($order->status == 'shipped') bg-blue-500
                @else bg-green-500 @endif">
                {{ ucfirst($order->status) }}
            </span>
        </p>

        <p><strong>Date:</strong> {{ $order->created_at->format('d M Y, h:i A') }}</p>
    </div>

    <!-- ================= UPDATE STATUS ================= -->
    <div class="mb-6">
        <h3 class="text-lg font-semibold mb-2 border-b pb-1">Update Status</h3>

        <form method="POST" action="{{ route('admin.orders.status', $order->id) }}">
            @csrf
            @method('PATCH')

            <div class="flex items-center gap-3">
                <select name="status" class="border p-2 rounded">
                    <option value="pending" {{ $order->status=='pending' ? 'selected' : '' }}>Pending</option>
                    <option value="shipped" {{ $order->status=='shipped' ? 'selected' : '' }}>Shipped</option>
                    <option value="delivered" {{ $order->status=='delivered' ? 'selected' : '' }}>Delivered</option>
                </select>

                <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Update
                </button>
            </div>
        </form>
    </div>

    <!-- ================= ORDER ITEMS ================= -->
    <div>
        <h3 class="text-lg font-semibold mb-3 border-b pb-1">Order Items</h3>

        <table class="w-full border rounded-lg overflow-hidden">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left">Product</th>
                    <th class="p-3 text-left">Image</th>
                    <th class="p-3 text-left">Price</th>
                    <th class="p-3 text-left">Quantity</th>
                    <th class="p-3 text-left">Subtotal</th>
                </tr>
            </thead>

            <tbody>
                @forelse($order->items as $item)
                <tr class="border-t hover:bg-gray-50">

                    <!-- Product Name -->
                    <td class="p-3">
                        {{ $item->product->name ?? 'Deleted Product' }}
                    </td>

                    <!-- Product Image -->
                    <td class="p-3">
                        <img 
                            src="{{ $item->product && $item->product->image ? asset('storage/'.$item->product->image) : 'https://via.placeholder.com/80' }}"
                            class="w-16 h-16 object-cover rounded border">
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
                    <td class="p-3">
                        ₹{{ $item->price * $item->quantity }}
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center p-4 text-gray-500">
                        No items found
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

@endsection