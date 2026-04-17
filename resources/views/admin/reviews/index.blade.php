@extends('layouts.dashboard')

@section('dashboard-content')
<div class="container mx-auto px-4 mb-6">
        <h1 class="text-2xl font-bold mb-6">Review Management</h1>

        <table class="w-full border border-separate border-spacing-y-2">
            <thead>
                <tr class="bg-white shadow-sm">
                    <th class="p-2">User</th>
                    <th class="p-2">Product</th>
                    <th class="p-2">Rating</th>
                    <th class="p-2">Review</th>
                    <th class="p-2">Status</th>
                    <th class="p-2">Action</th>
                </tr>
            </thead>

            <tbody>
                @foreach($reviews as $review)
                <tr class="border-b bg-gray-100  shadow-sm">

                    <td class="p-2">{{ $review->user->name }}</td>
                    <td class="p-2">{{ $review->product->name }}</td>
                    <td class="p-2">{{ $review->rating }} ⭐</td>
                    <td class="p-2">{{ $review->review }}</td>

                    <td class="p-2">
                        @if($review->is_approved)
                        <span class="text-green-600">Approved</span>
                        @else
                        <span class="text-yellow-500">Pending</span>
                        @endif
                    </td>

                    <td class="p-2 flex gap-2">

                        @if(!$review->is_approved)
                        <form action="{{ route('admin.review.approve', $review->id) }}" method="POST">
                            @csrf
                            <button class="bg-green-500 text-white px-3 py-1 rounded">
                                Approve
                            </button>
                        </form>
                        @endif

                        <form action="{{ route('admin.review.delete', $review->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="bg-red-500 text-white px-3 py-1 rounded">
                                Delete
                            </button>
                        </form>

                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>

@endsection