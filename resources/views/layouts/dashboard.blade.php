<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body>

    <div class="flex min-h-screen">

        <!-- Sidebar -->
        <div class="w-64 bg-gray-800 text-white p-5 flex flex-col justify-between">

            <div>
                <h2 class="text-2xl font-bold mb-6">Admin Dashboard</h2>

                <ul class="space-y-3">
                    <li>
                        <a href="{{ route('admin.dashboard') }}" class="block p-2 hover:bg-gray-700 rounded">
                            Dashboard
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('products.index') }}" class="block p-2 hover:bg-gray-700 rounded">
                            Manage Products
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('categories.index') }}" class="block p-2 hover:bg-gray-700 rounded">
                            Manage Categories
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.orders.index') }}" class="block p-2 hover:bg-gray-700 rounded">
                            Manage Orders
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.reviews') }}" class="block py-2 px-4 hover:bg-gray-700 rounded">
                            Manage Reviews
                        </a>
                    </li>
                </ul>
            </div>

            <!-- User Dropdown -->
            <div class="border-t border-gray-700 pt-4">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="w-full flex justify-between items-center px-3 py-2 bg-gray-700 rounded">
                            {{ Auth::user()->name ?? 'Guest' }}
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            Profile
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                Logout
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

        </div>

        <!-- Main Content -->
        <div class="flex-1 p-6 bg-gray-100">
            @yield('dashboard-content')
        </div>

    </div>

</body>

</html>