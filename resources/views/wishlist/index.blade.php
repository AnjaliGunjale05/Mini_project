@extends('layouts.app')
@section('content')

<div class="container mx-auto px-6 py-8">
    <h1 class="text-2xl font-bold mb-6">❤️ My Wishlist</h1>
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">

        @forelse($wishlist as $item)
        <div class="bg-white shadow rounded-lg p-4">
             <a href="{{route('product.show',$item->product->id)}}">
            <img src="{{ asset('storage/'.$item->product->image) }}" alt="" class="h-48 w-full object-cover rounded">
            <h2 class="text-lg semibold mt-3">{{$item->product->name}}</h2>
            <p class="text-pink-600 font-bold">{{$item->product->price}}</p>
             </a>
            <!-- Remove -->
            <form action="{{route('wishlist.remove',$item->product->id) }}" method="post">
                @csrf
                <button class="bg-red-500 text-white py-2 w-full rounded mt-3 hover:bg-red-600">
                    Remove
                </button>
            </form>
        </div>
        @empty
        <p>No items in wishlist</p>
        @endforelse
    </div>
</div>

@endsection