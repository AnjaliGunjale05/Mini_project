@extends('layouts.app')

@section('content')

<div class="max-w-6xl mx-auto px-4 py-10">

    <!-- Breadcrumb -->
    <div class="text-sm text-gray-500 mb-5">
        <a href="{{ route('home') }}" class="hover:underline">Home</a> →
        <a href="{{route('shop')}}" class="hover:underline">{{ $product->category->name ?? 'Category' }}</a> →
        <span class="text-gray-700">{{ $product->name }}</span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-10 bg-white p-6 rounded-xl shadow">

        <!-- LEFT: Product Image -->
        <div>
            <img id="main-image"
                src="{{ $product->image ? asset('storage/'.$product->image) : 'https://via.placeholder.com/400' }}"
                class="w-full h-96 object-cover transition-transform duration-300 hover:scale-110 cursor-zoom-in rounded-lg border">

            <!-- Thumbnails -->
            <div class="flex gap-3 mt-4 flex-wrap">
                <!-- Main image thumbnail -->
                <img onclick="changeImage(this)"
                    src="{{ asset('storage/' . $product->image) }}"
                    class="w-20 h-20 object-cover transition-transform duration-300 hover:scale-110 cursor-zoom-in rounded cursor-pointer border hover:border-pink-500">

                <!-- Gallery images -->
                @foreach($product->images as $img)
                <img onclick="changeImage(this)"
                    src="{{ asset('storage/' . $img->image_path) }}"
                    class="w-20 h-20 object-cover transition-transform duration-300 hover:scale-110 cursor-zoom-in rounded cursor-pointer border hover:border-pink-500">
                @endforeach
            </div>
        </div>

        <!-- RIGHT: Product Details -->
        <div>

            <!-- Name -->
            <h1 class="text-2xl font-bold mb-3">{{ $product->name }}</h1>

            <!-- Price -->
            <p class="text-xl text-green-600 font-semibold mb-3">
                ₹{{ number_format($product->price, 2) }}
            </p>

            <!-- Category -->
            <p class="text-gray-600 mb-3">
                Category:
                <span class="font-medium">
                    {{ $product->category->name ?? 'N/A' }}
                </span>
            </p>

            <!-- Stock -->
            <p class="mb-4">
                @if($product->stock > 0)
                <span class="text-green-600 font-semibold">In Stock</span>
                @else
                <span class="text-red-500 font-semibold">Out of Stock</span>
                @endif
            </p>

            <!-- Description -->
            <div class="mb-5">
                <h3 class="font-semibold mb-2">Description:</h3>
                <p class="text-gray-700 leading-relaxed">
                    {{ $product->description }}
                </p>
            </div>
            <div class="flex gap-3 mt-3">
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
                <form action="{{ route('cart.add', $product->id) }}" method="POST">
                    @csrf
                    <button class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                        Add to Cart
                    </button>
                </form>
                @else
                <button disabled class="bg-gray-400 text-white px-6 py-2 rounded-lg cursor-not-allowed">
                    Out of Stock
                </button>
                @endif
            </div>
        </div>


    </div>
    <div class="mt-12">
        <h2 class="text-2xl font-semibold mb-6">Related Products</h2>

        <div class="grid grid-cols-2  md:grid-cols-4 gap-6">
            @forelse($relatedProducts as $item)

            <a href="{{route('product.show',$item->id)}}"
                class="bg-white p-4 rounded-lg shadow transition-transform duration-300 hover:scale-110 cursor-zoom-in">
                <!-- Image -->

                <img src="{{ $item->image ? asset('storage/'.$item->image) : 'https://via.placeholder.com/300' }}"
                    class="w-full h-96 object-cover transition-transform duration-300 hover:scale-110 cursor-zoom-in rounded-lg border" alt="">

                <!-- Name -->

                <h3 class="text-sm font-medium text-gray-800">
                    {{$item->name}}
                </h3>

                <!-- price -->

                <p class="text-green-600 font-semibold text-sm mt-1">
                    ₹{{number_format($item->price, 2)}}
                </p>
            </a>
            @empty
            <p class="text-gray-500">No Related Products!</p>

            @endforelse
        </div>
    </div>
</div>


<!-- JS for Image Change -->
<script>
    function changeImage(element) {
        document.getElementById('main-image').src = element.src;
    }
</script>

@endsection