@extends('layouts.app')

@section('content')

<div class="max-w-6xl mx-auto px-4 py-10">

    <!-- Breadcrumb -->
    <div class="text-sm text-gray-500 mb-5">
        <a href="{{ route('home') }}" class="hover:underline">Home</a> →
        <a href="{{route('shop')}}" class="hover:underline">{{ $product->categories->pluck('name')->join(', ') ?? 'Category' }}</a> →
        <span class="text-gray-700">{{ $product->name }}</span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-10 bg-white p-6 rounded-xl shadow">

        <!-- LEFT: Product Image -->
        <div>
            <div class="relative">

                <!-- LEFT ARROW -->
                <button id="main-left"
                    onclick="prevImage()"
                    class="hidden absolute left-2 top-1/2 -translate-y-1/2 bg-white shadow p-3 rounded-full z-10">
                    &#10094;
                </button>

                <!-- IMAGE -->
                <img id="main-image"
                    src="{{ $product->image ? asset('storage/'.$product->image) : 'https://via.placeholder.com/400' }}"
                    class="w-full h-96 object-cover rounded-lg border">

                <!-- RIGHT ARROW -->
                <button id="main-right"
                    onclick="nextImage()"
                    class="absolute right-2 top-1/2 -translate-y-1/2 bg-white shadow p-3 rounded-full z-10">
                    &#10095;
                </button>

            </div>

            <!-- Thumbnails -->
            <div class="relative mt-4">

                <button id="thumb-left"
                    onclick="scrollThumb(-200)"
                    class="hidden absolute left-0 top-1/2 -translate-y-1/2 bg-white shadow p-2 rounded-full z-10">
                    &#10094;
                </button>

                <button id="thumb-right"
                    onclick="scrollThumb(200)"
                    class="absolute right-0 top-1/2 -translate-y-1/2 bg-white shadow p-2 rounded-full z-10">
                    &#10095;
                </button>

                <div id="thumb-container"
                    class="flex gap-3 overflow-x-auto scroll-smooth no-scrollbar px-8">

                    <img onclick="setImage(0)"
                        src="{{ asset('storage/'.$product->image) }}"
                        class="w-20 h-20 flex-shrink-0 cursor-pointer">

                    @foreach($product->images as $img)
                    <img onclick="setImage({{ $loop->index + 1 }})"
                        src="{{ asset('storage/'.$img->image_path) }}"
                        class="w-20 h-20 flex-shrink-0 cursor-pointer">
                    @endforeach

                </div>
            </div>
        </div>

        <!-- Right Product Details  -->

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
                    {{ $product->categories->pluck('name')->join(', ') ?: 'N/A' }}
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

    <!-- Related Products -->

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

    <!-- Product Reviews  and Ratings -->
    <div class="mt-12 bg-white p-6 rounded-xl shadow">
        <h2 class="text-2xl font-semibold mb-4"> Customer Reviews</h2>

        <!-- Average Rating -->
        @php
        $rating = round($avgRating ?? 0);
        @endphp
        <div class="flex items-center gap-3 mb-6">
            <div class="flex text-yellow-500 text-xl">
                @for($i = 1; $i <= 5; $i++)
                    @if($i <=$rating)
                    ★
                    @else
                    ☆
                    @endif
                    @endfor
                    </div>
                    <span class="text-gray-600">
                        ({{number_format($avgRating ?? 0 , 1)}} / 5)
                    </span>
            </div>

            @auth
            <form action="{{ route('review.store') }}" method="post" class="mb-8">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">

                <!-- STAR Rating Input -->

                <div class="mb-4">
                    <label for="" class="block mb-2 font-medium"> Your Rating</label>

                    <div id="star-rating" class="flex gap-1 text-2xl cursor-pointer">
                        @for($i = 1; $i <= 5; $i++)
                            <span onclick="setRating({{ $i }})" id="star-{{ $i }}">☆</span>
                            @endfor
                    </div>
                    <input type="hidden" name="rating" id="rating-input">
                </div>
                <!-- Review Text -->
                <div class="mb-4">
                    <label class="block mb-2 font-medium">Your Review</label>
                    <textarea name="review" rows="3"
                        class="w-full border rounded-lg p-3 focus:ring-2 focus:ring-pink-400"
                        placeholder="Write your experience..."></textarea>
                </div>

                <!-- Submit -->
                <button type="submit"
                    class="bg-pink-600 text-white px-6 py-2 rounded-lg hover:bg-pink-700">
                    Submit Review
                </button>
            </form>

            @else
            <p class="text-gray-500 mb-6">
                Please <a href="{{ route('login') }}" class="text-pink-600 underline">login</a> to write a review.
            </p>
            @endauth

            <!-- 📝 Recent Reviews -->
            <div class="space-y-4">
                @forelse($reviews as $review)
                <div class="border-b pb-3">

                    <!-- Stars -->
                    <div class="text-yellow-400">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <=$review->rating)
                            ★
                            @else
                            ☆
                            @endif
                            @endfor
                    </div>

                    <!-- Review -->
                    <p class="text-gray-700 mt-1">
                        {{ $review->review ?? 'No comment provided.' }}
                    </p>

                    <!-- User -->
                    <span class="text-xs text-gray-500">
                        By {{ $review->user->name ?? 'User' }} • {{ $review->created_at->diffForHumans() }}
                    </span>
                </div>
                @empty
                <p class="text-gray-500">No reviews yet.</p>
                @endforelse
            </div>
        </div>

        <!-- Recently Viewed Products -->

        @if(isset($recentProducts) && $recentProducts->count())
        <div class="relative mt-12 bg-white p-6 rounded-xl shadow">
            <h2 class="text-2xl font-semibold mb-6"> Recently Viewed Products </h2>

            <button id="recent-left"
                onclick="scrollRecent(-300)"
                class="hidden absolute left-0 top-1/2 -translate-y-1/2 bg-white shadow p-2 rounded-full z-10">
                &#10094;
            </button>

            <button id="recent-right"
                onclick="scrollRecent(300)"
                class="absolute right-0 top-1/2 -translate-y-1/2 bg-white shadow p-2 rounded-full z-10">
                &#10095;
            </button>

            <div id="recent-container" class="flex gap-6 overflow-x-auto scroll-smooth no-scrollbar px-10">
                @foreach($recentProducts as $item)
                <a href="{{route('product.show',$item->id)}}"
                    class="min-w-[200px] bg-white p-4 shadow rounded">

                    <!-- Image -->
                    <img src="{{ $item->image ? asset('storage/'.$item->image) : 'https://via.placeholder.com/300' }}"
                        alt="" class="w-full h-60 object-cover rounded-lg border mb-2">

                    <!-- Name  -->
                    <h3 class="text-sm">
                        {{$item->name}}
                    </h3>

                    <!-- Price -->
                    <p class="text-green-600 font-semibold text-sm mt-1">
                        ₹{{number_format($item->price, 2)}}
                    </p>

                    <!-- Badge -->
                    <span class="text-xs text-gray-500">Recently Viewed </span>
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>

<<<<<<< HEAD
    <!-- JS for Image Change -->
    <script>
        // Array Images (Main + Thumbnails)
        let images = [
            "{{ $product->image ? asset('storage/'.$product->image) : 'https://via.placeholder.com/400' }}",
            @foreach($product->images as $img)
            "{{ asset('storage/'.$img->image_path) }}",
            @endforeach
        ];
=======
<!-- JS for Image Change -->
<script>
    let images = [
        "{{ asset('storage/'.$product->image) }}",
        @foreach($product->images as $img)
        "{{ asset('storage/'.$img->image_path) }}",
        @endforeach
    ];
>>>>>>> 'Fixed-arrow'

        let currentIndex = 0;

        function showImage(index) {
            document.getElementById('main-image').src = images[index];
            updateMainArrows();
        }

        function nextImage() {
            if (currentIndex < images.length - 1) {
                currentIndex++;
                showImage(currentIndex);
            }
        }

        function prevImage() {
            if (currentIndex > 0) {
                currentIndex--;
                showImage(currentIndex);
            }
        }

        function setImage(index) {
            currentIndex = index;
            showImage(index);
        }

        function updateMainArrows() {
            document.getElementById('main-left').style.display =
                currentIndex === 0 ? 'none' : 'block';

            document.getElementById('main-right').style.display =
                currentIndex === images.length - 1 ? 'none' : 'block';
        }

        // THUMB SCROLL
        function scrollThumb(value) {
            const c = document.getElementById('thumb-container');
            c.scrollBy({
                left: value,
                behavior: 'smooth'
            });
        }

        function updateThumbArrows() {
            const c = document.getElementById('thumb-container');

            document.getElementById('thumb-left').style.display =
                c.scrollLeft <= 0 ? 'none' : 'block';

            document.getElementById('thumb-right').style.display =
                c.scrollLeft + c.clientWidth >= c.scrollWidth ? 'none' : 'block';
        }

        document.getElementById('thumb-container')
            .addEventListener('scroll', updateThumbArrows);

        // RECENT SCROLL
        function scrollRecent(value) {
            const c = document.getElementById('recent-container');
            c.scrollBy({
                left: value,
                behavior: 'smooth'
            });
        }

        function updateRecentArrows() {
            const c = document.getElementById('recent-container');

            document.getElementById('recent-left').style.display =
                c.scrollLeft <= 0 ? 'none' : 'block';

            document.getElementById('recent-right').style.display =
                c.scrollLeft + c.clientWidth >= c.scrollWidth ? 'none' : 'block';
        }

        const recent = document.getElementById('recent-container');
        if (recent) {
            recent.addEventListener('scroll', updateRecentArrows);
        }

        // INIT
        window.onload = () => {
            updateMainArrows();
            updateThumbArrows();
            updateRecentArrows();
        };

        //  Star rating and reviews

        function setRating(rating) {
            document.getElementById('rating-input').value = rating;

            for (let i = 1; i <= 5; i++) {
                document.getElementById('star-' + i).innerText = i <= rating ? '★' : '☆';
            }
        }
    </script>

    @endsection