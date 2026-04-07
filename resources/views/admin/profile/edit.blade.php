@extends('layouts.dashboard')

@section('dashboard-content')

<div class="p-6 bg-gray-100 min-h-screen">

    <div class="max-w-4xl mx-auto space-y-6">

        <!-- Profile Header -->
        <div class="bg-white p-5 rounded-lg shadow flex items-center gap-4">
            
            <!-- Image -->
            <img 
                src="{{ $user->image 
                    ? asset('storage/' . $user->image) 
                    : asset('images/default-user.png') }}" 
                class="w-16 h-16 rounded-full object-cover border">

            <!-- Info -->
            <div>
                <h2 class="text-xl font-semibold text-gray-800">
                    {{ $user->name }}
                </h2>

                <span class="text-xs bg-blue-600 text-white px-2 py-1 rounded">
                    Admin
                </span>
            </div>

        </div>

        <!-- Profile + Password Grid -->
        <div class="grid md:grid-cols-2 gap-6">

            <!-- Update Profile -->
            <div class="bg-white p-5 rounded-lg shadow">
                <h3 class="text-md font-semibold mb-4 border-b pb-2">
                    Update Profile
                </h3>

                @include('profile.partials.update-profile-information-form')
            </div>

            <!-- Change Password -->
            <div class="bg-white p-5 rounded-lg shadow">
                <h3 class="text-md font-semibold mb-4 border-b pb-2">
                    Change Password
                </h3>

                @include('profile.partials.update-password-form')
            </div>

        </div>

    </div>

</div>

@endsection