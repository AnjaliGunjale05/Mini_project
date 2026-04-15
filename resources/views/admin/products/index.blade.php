@extends('layouts.dashboard')
@section('dashboard-content')

<div class="container mx-auto px-4">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">Products</h1>

        <a href="{{route('products.create')}}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            Add Product
        </a>
    </div>

    <!-- SEARCH BAR -->

    <div class="flex justify mb-4">
        <form action="" class="w-11/12">
            <div class="flex gap-4 ">
                <input type="search" name="search" class="w-full border rounded p-2" placeholder="Search here by Product Name" value="" />
                <!-- SEARCH BUTTON -->
                <button class="bg-blue-600 text-white px-4 rounded hover:bg-blue-700">
                    Search
                </button>
                
            </div>

        </form>
    </div>

    @if(session('success'))
    <div class="bg-green-100 text-green-700 p-2 mb-4 rounded">{{ session('success') }}</div>
    @endif

    <table class="min-w-full bg-white">
        <thead>
            <tr>
                <th class="py-2 px-4 border">Sr.No</th>
                <th class="py-2 px-4 border">Name</th>
                <th class="py-2 px-4 border">Product Category</th>
                <th class="py-2 px-4 border">Price</th>
                <th class="py-2 px-4 border">Stock</th>
                <th class="py-2 px-4 border">Image</th>
                <th class="py-2 px-4 border">Actions</th> 
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr>
                <td class="py-2 px-4 border">{{ $loop->iteration}}</td>
                <td class="py-2 px-4 border ">{{ $product->name }}</td>
                <!-- @foreach($product->categories as $category)
                <span class="bg-gray-200 px-2 py-1 rounded">
                    {{$category->name}}
                </span>
                @endforeach -->
                <td class="py-2 px-4 border">{{ $product->categories->pluck('name')->join(', ') }}</td>
                <td class="py-2 px-4 border">{{ $product->price }}</td>
                <td class="py-2 px-4 border">{{ $product->stock }}</td>
                <td class="py-2 px-4 border">
                    @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" alt="" class="w-16 h-16">
                    @endif
                </td>
                <td class="py-2 px-4 border">
                    <a href="{{ route('products.edit', $product->id) }}" class="bg-yellow-400 px-2 py-1 rounded">Edit</a>
                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure?')">
                        @csrf
                        @method('DELETE')
                        <button class="bg-red-500 px-2 py-1 text-white rounded">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $products->links() }}
    </div>
</div>
@endsection