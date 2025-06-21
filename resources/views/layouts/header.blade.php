<!-- Header -->
<header class="fixed top-5 left-1/2 transform -translate-x-1/2 w-[95%] bg-black/60 backdrop-blur-lg rounded-2xl px-8 py-4 flex justify-between items-center z-50 shadow-lg">
    <div class="text-2xl font-bold text-white font-righteous">
        <a href="/">Batikku</a>
    </div>
    <div class="flex gap-6 items-center">
        @if(request()->is('katalog*'))
            <a href="/" class="text-white font-bold hover:text-yellow-400 transition-colors">Beranda</a>
        @else
            <a href="/katalog" class="text-white font-bold hover:text-yellow-400 transition-colors">Katalog</a>
        @endif
        <a href="/about" class="text-white font-bold hover:text-yellow-400 transition-colors">About</a>
        @auth
            @if(Auth::user() && Auth::user()->role === 'admin')
                <a href="/admin" class="text-white font-bold hover:text-yellow-400 transition-colors border border-yellow-400 rounded-full px-4 py-1">Management</a>
            @endif
            <a href="/cart" class="text-white font-bold hover:text-yellow-400 transition-colors relative">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                @php
                    $cartCount = Auth::user()->cart()->count();
                @endphp
                @if($cartCount > 0)
                    <span class="absolute -top-2 -right-2 bg-yellow-400 text-black text-xs rounded-full w-5 h-5 flex items-center justify-center">
                        {{ $cartCount }}
                    </span>
                @endif
            </a>
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" @keydown.escape="open = false" class="px-6 py-2 bg-white text-black rounded-full font-bold transition-all hover:bg-yellow-400 hover:shadow-lg active:scale-95">
                    {{ Auth::user()->name }}
                </button>
                <div x-show="open" @mouseenter="open = true" @mouseleave="open = false" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50 transition-all duration-200" x-cloak>
                    <a href="{{ route('profile') }}" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Profil</a>
                    <a href="{{ route('orders.history') }}" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Pesanan Saya</a>
                    <form method="POST" action="/logout" class="block">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 text-gray-800 hover:bg-gray-100">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        @else
            <a href="/login">
                <button class="px-6 py-2 bg-white text-black rounded-full font-bold transition-all hover:bg-yellow-400 hover:shadow-lg active:scale-95">
                    Login
                </button>
            </a>
        @endauth
    </div>
</header>
