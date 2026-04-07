@extends('layouts.app')

@section('content')

<div class="max-w-6xl mx-auto px-4 py-10">

    <h2 class="text-2xl font-bold mb-6">My Orders</h2>
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="w-full">
            <!-- header -->
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left">Order Id</th>
                    <th class="p-3 text-left">Date</th>
                    <th class="p-3 text-left">Address</th>
                    <th class="p-3 text-left">Total</th>
                    <th class="p-3 text-left">Status</th>
                    <th class="p-3 text-left">Action</th>
                </tr>
            </thead>
            <!-- Body -->
            <tbody>

                @forelse($orders as $order)
                <tr class="border-t hover:bg-gray-50">

                    <!-- Order ID -->
                    <td class="p-3 font-medium">#{{ $order->id }}</td>

                    <!-- Order Date -->
                    <td class="p-3"> {{ $order->created_at->format('d M Y') }}</td>

                    <!-- Address-->

                    <td class="text-gray-600 text-sm break-words whitespace-normal max-w-xs"> 
                        {{$order->city ?? 'N/A'}},
                        {{$order->landmark ?? ''}} <br> 
                        {{$order->state ?? 'N/A'}},
                        {{$order->country ?? 'N/A' }},
                         {{$order->postal_code ?? 'N/A'}}
                    </td>

                    <!-- Total -->
                    <td class="p-3 font-semibold">Rs. {{$order->total}}</td>



                    <!-- Status -->
                    <td class="p-3">
                        <span class="px-3 py-1 rounded text-white
                @if($order->status == 'pending') bg-yellow-500
                @elseif($order->status == 'shipped') bg-blue-500
                @else bg-green-500 @endif">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>

                    <!-- Action -->
                    <td class="p-3">
                        <a href="{{ route('orders.show', $order->id) }}"
                            class="text-blue-600 hover:underline">
                            View
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td class="text-center p-4 text-gray-500">No Orders Found! </td>
                </tr>

                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection