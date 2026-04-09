

@extends('layouts.app')

@section('title', 'Contact Us')

@section('content')

<div class="max-w-4xl mx-auto px-4 py-10">

    <h1 class="text-3xl font-bold mb-6">Contact Us</h1>

    <p class="text-gray-600 mb-6">
        Have questions? We'd love to hear from you.
    </p>

    <!-- Contact Form -->
    <form action="{{ route('contact.store') }}" method="POST" class="space-y-4">
        @csrf

        <input type="text" name="name" placeholder="Your Name"
            class="w-full border px-4 py-2 rounded">

        <input type="email" name="email" placeholder="Your Email"
            class="w-full border px-4 py-2 rounded">

        <textarea name="message" rows="5" placeholder="Your Message"
            class="w-full border px-4 py-2 rounded"></textarea>

        <button class="bg-pink-600 text-white px-6 py-2 rounded hover:bg-pink-700">
            Send Message
        </button>
    </form>

    <!-- Contact Info -->
   

</div>

@endsection