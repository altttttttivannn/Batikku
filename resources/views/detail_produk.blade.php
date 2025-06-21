@extends('layouts.app')

@section('content')
<!-- Include Header -->
@include('layouts.header')

<div class="min-h-screen pt-28 pb-8 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="md:flex">
                <!-- Gambar Produk -->
                <div class="md:w-1/2">
                    <img src="{{ asset('storage/' . $barang->gambar) }}" 
                         alt="{{ $barang->nama }}" 
                         class="w-full h-[500px] object-cover">
                </div>
                
                <!-- Detail Produk -->
                <div class="md:w-1/2 p-8">
                    <h1 class="text-3xl font-bold mb-4">{{ $barang->nama }}</h1>
                    <p class="text-xl font-semibold text-yellow-600 mb-4">Rp {{ number_format($barang->harga, 0, ',', '.') }}</p>
                    <p class="text-gray-600 mb-6">{{ $barang->deskripsi }}</p>
                    
                    <div class="mb-6">
                        <h3 class="font-semibold mb-2">Kategori:</h3>
                        <span class="inline-block bg-gray-100 rounded-full px-4 py-1">{{ $barang->kategori }}</span>
                    </div>

                    @auth
                        <form action="{{ route('cart.add') }}" method="POST" class="space-y-4" id="addToCartForm">
                            @csrf
                            <input type="hidden" name="barang_id" value="{{ $barang->id }}">
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Ukuran</label>
                                <div class="grid grid-cols-4 gap-2">
                                    @foreach($barang->stokUkuran as $stok)
                                        @if($stok->stok > 0)
                                            <label class="relative">
                                                <input type="radio" name="ukuran" value="{{ $stok->ukuran }}" 
                                                       class="peer absolute opacity-0" required>
                                                <div class="border-2 rounded-lg p-2 text-center cursor-pointer
                                                            peer-checked:border-yellow-500 peer-checked:bg-yellow-50
                                                            hover:border-yellow-200">
                                                    {{ $stok->ukuran }}
                                                    <div class="text-sm text-gray-500">Stok: {{ $stok->stok }}</div>
                                                </div>
                                            </label>
                                        @endif
                                    @endforeach
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah</label>
                                <input type="number" name="jumlah" min="1" value="1" required
                                       class="w-24 rounded-lg border-gray-300 shadow-sm focus:border-yellow-500 focus:ring-yellow-500">
                            </div>

                            <button type="submit" 
                                    class="w-full bg-black text-white py-3 px-6 rounded-lg
                                           hover:bg-yellow-500 transition duration-300
                                           flex items-center justify-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                Tambah ke Keranjang
                            </button>
                        </form>
                    @else
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-center">
                            <p class="text-yellow-800 mb-4">Login diperlukan untuk menambahkan produk ke keranjang</p>
                            <a href="{{ route('login') }}" 
                               class="inline-block bg-black text-white py-2 px-6 rounded-lg
                                      hover:bg-yellow-500 transition duration-300">
                                Login untuk Membeli
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('addToCartForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    const form = this;
    fetch(form.action, {
        method: 'POST',
        body: new FormData(form),
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            alert(data.error);
        } else {
            alert(data.message);
            // Update cart count in header if needed
            const cartCountSpan = document.querySelector('.cart-count');
            if (cartCountSpan && data.cart_count) {
                cartCountSpan.textContent = data.cart_count;
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menambahkan ke keranjang');
    });
});
</script>
@endpush
@endsection
