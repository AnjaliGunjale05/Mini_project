<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <!-- Scripts -->
     <script src="{{ asset('asset/js/checkout.js') }}"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-100">

    <!-- Header  -->
     
    @if(!request()->is('admin/*'))
    @include('layouts.header')
    @endif

      <!--  SUCCESS MESSAGE HERE -->
    @if(session('success'))
    <div id="success-message"
        class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg transition">
        {{ session('success') }}
    </div>

    <script>
        setTimeout(() => {
            document.getElementById('success-message').style.display = 'none';
        }, 3000);
    </script>
    @endif


    <!-- Page Content -->
    <main class="pt-24">
        @yield('content')
    </main>

    <!-- Footer -->
    @if(!request()->is('admin/*'))
    @include('layouts.footer')
    @endif

    <!-- JS -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const btn = document.getElementById('userMenuBtn');
            const dropdown = document.getElementById('userDropdown');

            if (btn && dropdown) {
                btn.addEventListener('click', () => {
                    dropdown.classList.toggle('hidden');
                });

                window.addEventListener('click', function(e) {
                    if (!btn.contains(e.target) && !dropdown.contains(e.target)) {
                        dropdown.classList.add('hidden');
                    }
                });
            }

        });
    </script>
</body>
</html>