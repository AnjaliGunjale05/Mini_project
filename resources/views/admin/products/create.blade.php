
@extends('layouts.dashboard')
@section('dashboard-content')

<div class="max-w-4xl mx-auto bg-white p-6 rounded shadow">
<h1 class="text-3xl font-semibold text-gray-800 mb-6 flex items-center gap-2 border-b pb-2"> Add Products</h1>
<form action="{{route('products.store')}}" method="post" enctype="multipart/form-data">
    @csrf 
    <div class="mb-4">
        <label>Name</label>
        <input type="text" name="name" class="w-full border p-2" required placeholder="Enter Product Name">
    </div>

    <div class="mb-4">
        <label>Product Category</label>
        <select name="category_id" class="w-full border p-2" required placeholder="Select category">
            <option value="" disabled selected> Select Product Category</option>
            @foreach($categories as $category)
            <option value="{{ $category->id }}"
             {{ old('category_id') == $category->id ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
            @endforeach
        </select>
    </div>

    <div class="mb-4">
        <label>Description</label>
        <textarea name="description" class="w-full border p-2" placeholder="Enter product Description" required></textarea>
    </div>

    <div class="mb-4">
        <label>Price</label>
        <input type="number" name="price" class="w-full border p-2" placeholder="Enter product price">
    </div>

    <div class="mb-4">
        <label>Stock</label>
        <input type="number" name="stock" class="w-full border p-2" placeholder="Enter product Stock">
    </div>


    <div class="mb-4">
        <label>Product Image</label>
        <input type="file" name="image" class="w-full border p-2">
    </div>

    <div class="mb-4">
        <label>Product Gallery</label>
        <input type="file" name="images[]" multiple class="w-full border p-2" >
         <small class="text-gray-500">You can upload multiple images</small>
    </div>

    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Add Product </button>
    

</form>
</div>
@endsection
    