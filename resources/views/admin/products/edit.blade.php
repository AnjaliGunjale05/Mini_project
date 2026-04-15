@extends('layouts.dashboard')
@section('dashboard-content')
<div class="max-w-4xl mx-auto bg-white p-6 rounded shadow">
    <h1 class="text-3xl font-semibold text-gray-800 mb-6 flex items-center gap-2 border-b pb-2">
        Edit Product
    </h1>
    {{-- Success Message --}}
    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-2 mb-4 rounded">
            {{ session('success') }}
        </div>
    @endif

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="bg-red-100 text-red-700 p-3 mb-4 rounded">
            <ul>
                @foreach($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{route('products.update',$product->id)}}" method="post" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label>Name</label>
            <input type="text" name="name" class="w-full border p-2" required placeholder="Enter Product Name" value="{{old('name',$product->name)}}">
        </div>

        <div class="mb-4">
        <label>Product Category</label>
        <select name="categories[]" id="categorySelect" multiple class="w-full border p-2" required placeholder="Select category">
            <option value="" disabled selected> Select Product Category</option>
            @foreach($categories as $category)
            <option value="{{ $category->id }}"
             {{in_array($category->id, old('categories',$product->categories->pluck('id')->toArray())) ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
            @endforeach
        </select>
    </div>

        <div class="mb-4">
            <label>Description</label>
            <textarea name="description" class="w-full border p-2" placeholder="Enter product Description" required>{{old('description',$product->description)}}</textarea>
        </div>

        <div class="mb-4">
            <label>Price</label>
            <input type="number" name="price" class="w-full border p-2" placeholder="Enter product price" value="{{old('price',$product->price)}}">
        </div>

        <div class="mb-4">
            <label>Stock</label>
            <input type="number" name="stock" class="w-full border p-2" placeholder="Enter product Stock" value="{{old('stock',$product->stock)}}">
        </div>

        {{-- Current Image --}}
        @if($product->image)
        <div>
        <label class="block mb-1 font-medium"> Current Image</label>
        <img src="{{asset('storage/'.$product->image)}}" class="w-24 h-24 rounded">
        </div>

        @endif

        <div class="mb-4">
            <label>Change Image</label>
            <input type="file" name="image" class="w-full border p-2" placeholder="Update image">
        </div>

         <div class="mb-4">
        <label>Product Gallery</label>
        <input type="file" name="images[]" multiple class="w-full border p-2" >
         <small class="text-gray-500">You can upload multiple images</small>
    </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"">Update Product </button>
        <a href="{{ route('products.index') }}" class="bg-gray-400 text-white px-4 py-2 rounded">
                Cancel
            </a>
</form>
</div>

<!-- CDN -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        $('#categorySelect').select2({
            placeholder: "Select Categories",
            closeOnSelect: false,
            width: '100%'
        });
    });
</script>

@endsection