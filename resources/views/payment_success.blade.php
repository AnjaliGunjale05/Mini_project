@extends('layouts.app')

@section('content')
<div class="text-center py-20">

    <h1 class="text-3xl font-bold text-green-600 mb-4">
        Payment Successful 🎉
    </h1>

    <p class="text-gray-600 mb-6">
        Your order has been placed successfully.
    </p>

    <a href="{{ route('orders.show', $order->id) }}"
        class="bg-blue-600 text-white px-6 py-2 rounded">
        View Order
    </a>

</div>
@endsection