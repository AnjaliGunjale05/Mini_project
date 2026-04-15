<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>

<body>
    <!-- Footer -->
    <footer class="bg-pink-50 mt-12">
        <div class="container mx-auto px-6 py-12 grid grid-cols-1 md:grid-cols-4 gap-8">

            <!-- Brand -->
            <div>
                <h2 class="text-2xl font-bold text-pink-600 mb-3">Womona</h2>
                <p class="text-gray-600">
                    Discover the latest trends in women's fashion. Style that defines you.
                </p>
            </div>

            <!-- Shop -->
            <!-- Links -->
            <div>
                <h3 class="font-bold text-lg mb-2">Quick Links</h3>
                <ul class="text-gray-600 space-y-1">
                    <li><a href="{{ route('about') }}">About Us</a></li>
                    <li><a href="{{ route('contact') }}">Contact</a></li>
                    <li><a href="{{ route('privacy') }}">Privacy Policy</a></li>
                </ul>
            </div>

            <div>
                <h3 class="font-bold text-lg mb-2">Address Links</h3>
                <p>
                    📧
                    <a href="mailto:connecttoexplorewithus@gmail.com " class="hover:text-pink-600">connecttoexplorewithus@gmail.com

                    </a>
                </p>
                <p>
                    📞
                    <a href="tel:+919307656794" class="hover:text-pink-600"> +91 9876543210

                    </a>
                </p>
                <p>
                    📍
                    <a href="https://www.google.com/maps?q=Mumbai,India"
                        target="_blank"
                        class="hover:text-pink-600">
                        Mumbai, India
                    </a>
                </p>
            </div>

            <div class="flex space-x-4 mt-4 text-xl">
                <h3 class="font-bold text-lg mb-2">Follow us</h3>

                <a href="https://www.facebook.com/womona" target="_blank" class="hover:text-pink-600">
                    <i class="fab fa-facebook"></i>
                </a>

                <a href="https://www.instagram.com/womona__official" target="_blank" class="hover:text-pink-600">
                    <i class="fab fa-instagram"></i>
                </a>

                <a href="https://twitter.com/womona" target="_blank" class="hover:text-pink-600">
                    <i class="fab fa-twitter"></i>
                </a>

            </div>

        </div>

        <!-- Bottom -->
        <div class="bg-pink-100 text-center py-4 text-gray-600">
            © 2026 Womona. All rights reserved.
        </div>
    </footer>
</body>

</html>