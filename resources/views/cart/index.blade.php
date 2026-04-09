@extends('layouts.app')

@section('content')

<div class="container mx-auto px-6 py-10">

    <h2 class="text-3xl font-bold mb-6">My Cart</h2>

    {{-- ✅ Success Message --}}
    @if(session('success'))
        <div id="success-message"
            class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg text-center">
            {{ session('success') }}
        </div>
        @elseif(session('exceeded'))
        <div id="success-message"
            class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg text-center">
            {{ session('exceeded') }}
        </div>
        
        <script>
            setTimeout(() => {
                document.getElementById('success-message').style.display = 'none';
            }, 3000);
        </script>
    @endif

    @if(count($cart) > 0)

    <table class="w-full bg-white shadow rounded-lg overflow-hidden">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-3">Image</th>
                <th>Name</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
            @foreach($cart as $id => $item)
            <tr class="text-center border-t">

                <!-- Image -->
                <td class="p-3">
                    <img src="{{ asset('storage/'.$item['image']) }}"
                         class="w-16 h-16 object-cover rounded">
                </td>

                <!-- Name -->
                <td>{{ $item['name'] }}</td>

                <!-- Price -->
                <td>₹{{ $item['price'] }}</td>

                <!-- Quantity -->
                <td>
                    <form action="{{ route('cart.update', $id) }}" method="POST">
                        @csrf
                        <input type="number" name="quantity"
                               value="{{ $item['quantity'] }}"
                               min="1"
                               class="w-16 border rounded text-center">
                        <button class="bg-blue-500 text-white px-2 py-1 rounded mt-1">
                            Update
                        </button>
                    </form>
                </td>

                <!-- Total -->
                <td>
                    ₹{{ $item['price'] * $item['quantity'] }}
                </td>

                <!-- Remove -->
                <td>
                    <form action="{{ route('cart.remove', $id) }}" method="POST">
                        @csrf
                        <button class="bg-red-500 text-white px-3 py-1 rounded">
                            Remove
                        </button>
                    </form>
                </td>

            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Grand Total + Checkout -->
    <div class="mt-6 flex justify-between items-center">

        <h3 class="text-xl font-bold">
            Total: ₹{{ $total }}
        </h3>

        {{-- ✅ Checkout Button --}}
        <a href="{{ route('checkout.index') }}"
           class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700">
            Proceed to Checkout
        </a>

    </div>

    @else

        {{-- Better Empty UI --}}
        <div class="text-center py-10">
            <p class="text-lg mb-4">Your cart is empty 😢</p>
            <a href="{{ route('shop') }}"
               class="bg-pink-600 text-white px-6 py-2 rounded">
                Continue Shopping
            </a>
        </div>

    @endif

</div>

@endsection