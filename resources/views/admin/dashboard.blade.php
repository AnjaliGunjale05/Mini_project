@extends('layouts.dashboard')

@section('dashboard-content')
<h1>Admin Dashboard</h1>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">

    <!-- Total Categories -->
    <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-lg text-gray-600">Total Categories</h2>
        <p class="text-2xl font-bold text-blue-500">
            {{ \App\Models\Category::count() }}
        </p>
    </div>

    <!-- Total Products -->
    <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-lg text-gray-600">Total Products</h2>
        <p class="text-2xl font-bold text-blue-500">
            {{ \App\Models\Product::count() }}
        </p>
    </div>

    <!-- Total Users -->
    <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-lg text-gray-600">Total Users</h2>
        <p class="text-2xl font-bold text-green-500">
            {{ \App\Models\User::count() }}
        </p>
    </div>

    <div class="bg-white p-6 rounded shadow">
        <h2 class="text-gray-600">Total Orders</h2>
        <p class="text-2xl font-bold text-purple-500">
            {{ \App\Models\Order::count() }}
        </p>
    </div>

    <div class="bg-white p-6 rounded shadow">
        <h2 class="text-gray-600">Pending Orders</h2>
        <p class="text-2xl font-bold text-yellow-500">
            {{ \App\Models\Order::where('status','pending')->count() }}
        </p>
    </div>

    <div class="bg-white p-6 rounded shadow">
        <h2 class="text-gray-600">Revenue</h2>
        <p class="text-2xl font-bold text-green-500">
            ₹{{ \App\Models\Order::sum('total') }}
        </p>
    </div>


</div>

<!-- Button -->
<div class="flex justify mb-4">
    <div class="flex gap-4">
        <!-- <div class="w-full border rounded p-2"> -->
        <div class="mt-6">
            <a href="{{ route('products.index') }}"
                class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                Manage Products
            </a>
        </div>
        <div class="mt-6">
            <a href="{{ route('categories.index') }}"
                class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                Manage Categories
            </a>
        </div>
        <div class="mt-6">
            <a href="{{ route('admin.orders.index') }}"
                class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
                Orders
            </a>
        </div>
    </div>
</div>
</form>
</div>


@endsection