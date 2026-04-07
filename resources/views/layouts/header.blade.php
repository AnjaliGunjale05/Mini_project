<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
</head>

<body>
    <!-- Header -->
    <header class="fixed top-0 left-0 w-full bg-white shadow z-50">
        <div class="container mx-auto px-6 py-4 flex items-center justify-between">

            <!-- Logo -->
            <div class="text-2xl font-bold text-pink-600 tracking-wide">
                Womona
            </div>

            <!-- Navigation -->
            <nav class="hidden md:flex space-x-8 text-gray-700 font-medium">
                <a href="{{route('home') }}" class="hover:text-pink-600">Home</a>
                <a href="{{route('shop') }}" class="hover:text-pink-600">Shop</a>

            </nav>

            <!-- Search -->
            <form action="{{ route('shop') }}" method="GET" class="hidden md:flex w-1/3">

                <!-- Search Input -->
                <input
                    type="text"
                    name="search"
                    placeholder="Search dresses, tops..."
                    value="{{ request('search') }}"
                    class="w-full border border-gray-300 px-4 py-2 rounded-l-full focus:outline-none focus:ring-2 focus:ring-pink-400">

                <!-- Search Button -->
                <button
                    type="submit"
                    class="bg-pink-600 text-white px-4 rounded-r-full hover:bg-pink-700">
                    Search
                </button>

            </form>

            <!-- Icons -->
            <div class="flex items-center space-x-5">

                <a href="{{route('wishlist.index')}}" class="relative text-gray-700 hover:text-pink-600">
                    ❤️
                    @if($wishlistCount > 0)
                    <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs px-2 py-1 rounded-full">
                        {{ $wishlistCount }}
                    </span>
                    @endif
                </a>

                <!-- Cart -->
                <a href="{{route('cart.index')}}" class="relative text-gray-700 hover:text-pink-600">
                    🛒
                    @if($cartCount > 0)
                    <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs px-2 py-1 rounded-full">
                        {{ $cartCount }}
                    </span>
                    @endif
                </a>

                @auth
                <a href="{{ route('orders.index') }}" class="hover:text-pink-600">
                    My Orders
                </a>
                @endauth

                <!-- User -->
                <div class="relative">

                    <!-- USER ICON -->

                    <button id="userMenuBtn" class="text-gray-700 hover:text-pink-600 text-xl ">
                        @auth
                        @if(auth()->user()->image)
                        <img src="{{ asset('storage/' . auth()->user()->image) }}"
                            class="w-10 h-10 rounded-full object-cover">
                        @else
                        <img src="{{ asset('storage/uploads/users/dprofile.png')}}"
                            class="w-10 h-10 rounded-full">
                        @endif
                        @endauth

                        @guest
                        <img src="{{ asset('storage/uploads/users/dprofile.png')}}"
                            class="w-10 h-10 rounded-full">
                        @endguest
                    </button>

                    <div id="userDropdown" class="hidden absolute right-0 mt-2 w-40 bg-white shadow-lg rounded-lg z-50">
                        @guest
                        <a href="{{route('login')}}" class="block px-4 py-2 hover:bg-pink-100">
                            Login
                        </a>

                        <a href="{{route('register')}}" class="block px-4 py-2 hover:bg-pink-100">
                            Register
                        </a>
                        @endguest

                        @auth
                        <a href="{{route('profile.edit')}}" class="block px-4 py-2 hover:bg-pink-100">
                            My Profile
                        </a>

                        <form action="{{route('logout')}}" method="post">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 hover:bg-pink-100">
                                Logout
                            </button>
                        </form>
                        @endauth

                    </div>
                </div>

            </div>
        </div>
    </header>
</body>

</html>