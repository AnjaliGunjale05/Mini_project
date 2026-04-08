@extends('layouts.app')

@section('content')
<div class="bg-gray-100 min-h-screen py-10">
    <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8 px-4">

        <!-- Left Checkout Form -->
        <div class="md:col-span-2 bg-white p-6 rounded-xl shadow">

            <h2 class="text-2xl font-semibold mb-6 border-b pb-3">Shipping Details </h2>

            <form id="checkout-form" action="{{ route('checkout.store') }}" method="POST">
                @csrf

                <!-- Name -->
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Full Name</label>
                    <input type="text" name="name"
                        value="{{ old('name', auth()->user()->name ?? '') }}"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 outline-none">
                    @error('name') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                </div>

                <!-- Email -->
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Email Address</label>
                    <input type="email" name="email"
                        value="{{ old('email', auth()->user()->email ?? '') }}"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 outline-none">
                    @error('email') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                </div>

                <!-- Phone -->
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Phone Number</label>
                    <input type="text" name="phone"
                        value="{{ old('phone') }}"
                        class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 outline-none">
                    @error('phone') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                </div>

                <!-- Address Section -->
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Country</label>
                    <select name="country" id="country"
                        class="w-full border rounded-lg px-3 py-2">
                        <option value="" selected disabled>Select Country</option>
                        @foreach($countries as $country)
                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">State</label>
                    <select name="state" id="state" class="w-full border rounded-lg px-3 py-2">
                        <option value="" selected disabled>Select State</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">City</label>
                    <input name="city" id="city"
                        class="w-full border rounded-lg px-3 py-2">
                       
                    </input>
                    @error('phone') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                </div>


                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Landmark</label>
                    <input type="text" name="landmark"
                        class="w-full border rounded-lg px-3 py-2"
                        placeholder="Near temple, school, etc.">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">Postal Code</label>
                    <input type="text" name="postal_code"
                        class="w-full border rounded-lg px-3 py-2"
                        placeholder="Enter PIN Code">
                </div>

                <!-- Button -->
                <button type="button" id="checkout-btn"
                    class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
                    Place Order & Pay
                </button>
            </form>
        </div>

        <!-- RIGHT: Order Summary -->

        <div class="bg-white p-6 rounded-xl shadow h-fit">
            <h2 class="text-xl font-semibold mb-4 border-b pb-3">Order Summary</h2>

            <div class="space-y-4 max-h-80 overflow-y-auto">
                @foreach(session('cart', []) as $id => $item)
                <div class="flex items-center gap-4 border-b pb-3">
                    <!-- Product Image -->
                    <img src="{{ $item['image']? asset('storage/'.$item['image']): 'https://via.placeholder.com/80' }}"
                        class="w-16 h-16 object-cover rounded-lg border">

                    <!-- Details -->
                    <div class="flex-1">
                        <h4 class="font-medium text-sm">{{ $item['name'] }}</h4>
                        <p class="text-gray-500 text-xs">Qty: {{ $item['quantity'] }}</p>
                    </div>

                    <!-- Price -->
                    <div class="font-semibold text-sm">
                        ₹{{ $item['price'] * $item['quantity'] }}
                    </div>
                </div>
                @endforeach
            </div>
            <div class="mt-6 border-t pt-4">
                <div class="flex justify-between text-lg font-semibold">
                    <span>Total</span>
                    <span>
                        <h3 class="mt-4 font-bold">₹{{ collect(session('cart', []))->sum(fn($i) => $i['price'] * $i['quantity']) }}</h3>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script>
document.getElementById('checkout-btn').onclick = function () {

    fetch("{{ route('checkout.store') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({
            name: document.querySelector('[name=name]').value,
            email: document.querySelector('[name=email]').value,
            phone: document.querySelector('[name=phone]').value,
            country: document.querySelector('[name=country]').value,
            state: document.querySelector('[name=state]').value,
            city: document.querySelector('[name=city]').value,
            landmark: document.querySelector('[name=landmark]').value,
            postal_code: document.querySelector('[name=postal_code]').value
        })
    })
    .then(res => res.json())
    .then(data => {
        window.location.href = "/payment/" + data.order_id;
    });
};
</script>
@endsection