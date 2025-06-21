<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Batikku</title>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        [x-cloak] { display: none !important; }
        @keyframes bounce-rotate {
            0%, 100% {
                transform: translateY(-25%) rotate(0deg);
            }
            50% {
                transform: translateY(5%) rotate(10deg);
            }
        }
        .bounce-rotate {
            animation: bounce-rotate 2s infinite;
        }
        @keyframes fadeOut {
            from { opacity: 1; }
            to { opacity: 0; visibility: hidden; }
        }
        .fade-out {
            animation: fadeOut 0.5s ease-in-out forwards;
        }
    </style>
    @stack('styles')
    <!-- Favicon -->
    <link rel="icon" href="/favicon.ico">
    <!-- Meta -->
    <meta name="description" content="Batikku - Website Commerce Batik dengan Pengrajin Desa">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-white min-h-screen font-sans">
    <!-- Loading Screen -->
    <div x-data="{ 
            isLoading: true,
            isAdminPage: window.location.pathname.startsWith('/admin')
         }" 
         x-init="setTimeout(() => isLoading = false, 1500)"
         x-show="isLoading && !isAdminPage"
         x-transition.duration.500ms
         class="fixed inset-0 z-50 flex items-center justify-center bg-amber-50"
         style="backdrop-filter: blur(5px);">
        <div class="flex flex-col items-center">
            <!-- Batik Pattern Background -->
            <div class="relative w-24 h-24 mb-4">
                <div class="absolute inset-0 bg-amber-100 rounded-full opacity-50"></div>
                <!-- Stylized B Logo -->
                <div class="relative flex items-center justify-center w-full h-full bounce-rotate">
                    <span class="text-6xl font-bold text-amber-800 font-serif" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.1);">
                        B
                    </span>
                </div>
            </div>
            <!-- Loading Text -->
            <p class="text-amber-800 text-lg font-medium">Memuat...</p>
        </div>
    </div>

    <main class="min-h-screen flex flex-col">
        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>
