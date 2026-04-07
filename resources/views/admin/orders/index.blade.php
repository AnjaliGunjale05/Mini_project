@extends('layouts.dashboard')

@section('dashboard-content')

<h2 class="text-2xl font-bold mb-6">Orders</h2>

<table class="w-full border rounded-lg overflow-hidden">
    <thead class="bg-gray-100">
        <tr>
            <th class="p-3 text-left">Order ID</th>
            <th class="p-3 text-left">Customer</th>
            <th class="p-3 text-left">Total</th>
            <th class="p-3 text-left">Status</th>
            <th class="p-3 text-left">Date</th>
            <th class="p-3 text-left">Action</th>
        </tr>
    </thead>

    <tbody>
        @forelse($orders as $order)
        <tr class="border-t hover:bg-gray-50">

            <td class="p-3">#{{ $order->id }}</td>

            <td class="p-3">
                {{ $order->user->name ?? 'Guest' }}
            </td>

            <td class="p-3">₹{{ $order->total }}</td>

            <td class="p-3">
                <span class="px-2 py-1 rounded text-white
                    @if($order->status == 'pending') bg-yellow-500
                    @elseif($order->status == 'shipped') bg-blue-500
                    @else bg-green-500 @endif">
                    {{ ucfirst($order->status) }}
                </span>
            </td>

            <td class="p-3">
                {{ $order->created_at->format('d M Y') }}
            </td>

            <td class="p-3">
                <a href="{{ route('admin.orders.show', $order->id) }}"
                   class="text-blue-600 hover:underline">
                    View
                </a>
            </td>

        </tr>
        @empty
        <tr>
            <td colspan="6" class="text-center p-4 text-gray-500">
                No orders found
            </td>
        </tr>
        @endforelse
    </tbody>
</table>

<div class="mt-4">
    {{ $orders->links() }}
</div>

@endsection