@extends('layouts.app')

@section('content')

<!-- 🔷 HERO SECTION -->
<section class="bg-pink-100 py-16">
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
    <div class="container mx-auto px-6 flex flex-col md:flex-row items-center">

        <div class="md:w-1/2">
            <h1 class="text-4xl font-bold text-gray-800 mb-4">
                Discover Your Style with Womona
            </h1>
            <p class="text-gray-600 mb-6">
                Trendy collections for modern women. Shop the latest fashion now.
            </p>
            <a href="{{ route('shop') }}"
                class="bg-pink-600 text-white px-6 py-3 rounded-lg hover:bg-pink-700">
                Shop Now
            </a>
        </div>

        <div class="md:w-1/2 mt-8 md:mt-0">
            <img src="{{asset('asset/image/Hero s.jpg')}}"
                class="rounded-lg shadow">
        </div>
    </div>
</section>

<!-- 🔷 CATEGORIES -->
<section class="py-12">
    <div class="container mx-auto px-6">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">
            Shop by Category
        </h2>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @foreach($categories as $category)
            <a href="{{route('shop',['category'=>$category->id])}}">
                <div class="bg-white shadow rounded-lg p-4 text-center hover:shadow-lg">
                    <h3 class="text-lg font-semibold text-gray-700">
                        {{ $category->name }}
                    </h3>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>

<!-- 🔷 FEATURED PRODUCTS -->
<section class="py-12 bg-gray-50">
    <div class="container mx-auto px-6">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">
            Featured Products
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">

            @foreach($products as $product)
            <div class="bg-white shadow rounded-lg p-4 hover:shadow-lg">
                <a href="{{route('product.show',$product->id)}}">
                    <!-- Image -->
                    <img src="{{ $product->image ? asset('storage/'.$product->image) : asset('default.png') }}"
                        class="h-48 w-full object-cover rounded">

                    <!-- Name -->
                    <h3 class="mt-3 font-semibold">
                        {{ $product->name }}
                    </h3>

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

                    @auth
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
                    @endauth

                    <!--  Add to Cart (FIXED) -->
                    @if($product->stock > 0)
                    <form action="{{ route('cart.add', $product->id) }}" method="POST" class="w-1/2">
                        @csrf
                        <button type="submit" class="w-full bg-pink-600 text-white py-2 rounded-lg hover:bg-pink-700 transition">
                            Add to Cart
                        </button>
                    </form>
                    @else
                    <button
                        class="block w-full mt-3 bg-gray-400 text-white py-2 rounded cursor-not-allowed">
                        Out of Stock
                    </button>
                    @endif
                </div>
            </div>
            @endforeach

        </div>
    </div>
</section>

<!-- 🔷 OFFER SECTION -->
<section class="bg-pink-600 text-white py-12 text-center">
    <h2 class="text-3xl font-bold mb-2">
        Flat 30% OFF
    </h2>
    <p class="mb-4">On selected items. Limited time offer!</p>
    <a href="{{ route('shop') }}"
        class="bg-white text-pink-600 px-6 py-2 rounded">
        Shop Now
    </a>
</section>

@endsection