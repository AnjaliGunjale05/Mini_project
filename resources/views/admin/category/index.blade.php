@extends('layouts.dashboard')

@section('dashboard-content')

<div class="container mx-auto px-4">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">Categories</h1>

        <a href="{{ route('categories.create') }}" 
           class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            Add Category
        </a>
    </div>

    <!-- SEARCH BAR -->
    <div class="flex justify-between mb-4">
        <form action="{{ route('categories.index') }}" class="w-11/12">
            <div class="flex gap-4">
                <input type="search" name="search"
                       class="w-full border rounded p-2"
                       placeholder="Search category..."
                       value="{{ request('search') }}" />

                <button class="bg-blue-600 text-white px-4 rounded hover:bg-blue-700">
                    Search
                </button>
            </div>
        </form>
    </div>

    <!-- SUCCESS MESSAGE -->
    @if(session('success'))
    <div class="bg-green-100 text-green-700 p-2 mb-4 rounded">
        {{ session('success') }}
    </div>
    @endif

    <!-- CATEGORY TABLE -->
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border">
            <thead class="bg-gray-100">
                <tr>
                    <th class="py-2 px-4 border">Sr.No</th>
                    <th class="py-2 px-4 border">Name</th>
                    <th class="py-2 px-4 border">Slug</th>
                    <th class="py-2 px-4 border">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                <tr class="hover:bg-gray-50">
                    <td class="py-2 px-4 border">{{ $loop->iteration }}</td>
                    <td class="py-2 px-4 border">{{ $category->name }}</td>
                    <td class="py-2 px-4 border">{{ $category->slug }}</td>
                    <td class="py-2 px-4 border flex gap-2">
                        <a href="{{ route('categories.edit', $category->id) }}" 
                           class="bg-yellow-400 px-2 py-1 rounded hover:bg-yellow-500">
                           Edit
                        </a>

                        <form action="{{ route('categories.destroy', $category->id) }}" 
                              method="POST" 
                              onsubmit="return confirm('Are you sure?')">
                            @csrf
                            @method('DELETE')
                            <button class="bg-red-500 px-2 py-1 text-white rounded hover:bg-red-600">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-4">No categories found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- PAGINATION -->
    <div class="mt-4">
        {{ $categories->links() }}
    </div>
</div>

@endsection