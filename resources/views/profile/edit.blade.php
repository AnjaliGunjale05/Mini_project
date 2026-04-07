@extends('layouts.app')

@section('content')

<div class="pt-24 pb-10 bg-gray-100 min-h-screen">

    <div class="max-w-5xl mx-auto space-y-6">

        <!-- Title -->
        <h2 class="text-3xl font-bold text-gray-800">My Profile</h2>

        <!-- Update Profile -->
        <div class="bg-white p-6 rounded-xl shadow">
            <h3 class="text-lg font-semibold mb-4">Update Profile</h3>
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <!-- Change Password -->
        <div class="bg-white p-6 rounded-xl shadow">
            <h3 class="text-lg font-semibold mb-4">Change Password</h3>
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>


    </div>

</div>

@endsection