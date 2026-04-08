@extends('layouts.app')
@section('content')
<div class="container mx-auto px-6 py-8">
    <!--  SUCCESS MESSAGE HERE -->
    @if(session('success'))
    <div id="success-message"
        class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg transition">
        {{ session('success') }}
    </div>

    <script>
        setTimeout(() => {
            document.getElementById('success-message').style.display = 'none';
        }, 3000);
    </script>
    @endif

    <!--  Search + Filter -->
    <form method="GET" action="{{ route('shop') }}"
        class="flex flex-col md:flex-row gap-4 mb-8">

        <!-- Search -->
        <input type="text" name="search" value="{{ request('search') }}"
            placeholder="Search products..."
            class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-pink-400">

        <!-- Category -->
        <select name="category"
            class="w-full md:w-1/4 border rounded-lg px-4 py-2 focus:ring-2 focus:ring-pink-400">
            <option value="">All Categories</option>
            @foreach($categories as $category)
            <option value="{{ $category->id }}"
                {{ request('category') == $category->id ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
            @endforeach
        </select>

        <!-- Button -->
        <button type="submit"
            class="bg-pink-600 text-white px-6 py-2 rounded-lg hover:bg-pink-700 transition">
            Filter
        </button>
    </form>

    <!-- 🛍 Product Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse($products as $product)

        <div class="bg-white shadow rounded-lg p-4 hover:shadow-lg transition">

            <a href="{{route('product.show',$product->id)}}">

                <!-- Image -->
                <img
                    src="{{ $product->image ? asset('storage/'.$product->image) : asset('default.png') }}"
                    class="h-48 w-full object-cover transition-transform duration-300 hover:scale-110 cursor-zoom-in rounded">

                <!-- Name -->
                <h2 class="text-lg font-semibold mt-3">
                    {{ $product->name }}
                </h2>

                <!-- Category -->
                <p class="text-sm text-gray-500">
                    {{ $product->category->name ?? 'No Category' }}
                </p>

                <!-- Price -->
                <p class="text-pink-600 font-bold">
                    ₹{{ $product->price }}
                </p>
            </a>
            <div class="flex gap-3 mt-3">
                <!-- Wishlist  -->

                @php
                $isWishlisted = in_array($product->id, $wishlistItems ?? []);
                @endphp

                <form action="{{ $isWishlisted 
        ? route('wishlist.remove', $product->id) 
        : route('wishlist.add', $product->id) }}"
                    method="POST">

                    @csrf

                    <button type="submit" class="text-2xl transition">

                        @if($isWishlisted)
                        ❤️
                        @else
                        🤍
                        @endif

                    </button>
                </form>

                <!-- Add to Cart -->
                @if($product->stock > 0)
                <form action="{{ route('cart.add', $product->id) }}" method="POST" class="w-1/2">
                    @csrf

                    @guest
                    <input type="hidden" name="redirect_to_login" value="1">
                    @endguest

                    <button type="submit"
                        class="w-full bg-pink-600 text-white py-2 rounded-lg hover:bg-pink-700 transition">
                        Add to Cart
                    </button>
                </form>
                @endif

            </div>
        </div>

        @empty
        <p>No products found</p>
        @endforelse
    </div>

    <!-- 🔢 Pagination -->
    <div class="mt-6">
        {{ $products->appends(request()->query())->links() }}
    </div>

</div>
@endsection