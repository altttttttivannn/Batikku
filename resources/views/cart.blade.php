@extends('layouts.app')

@section('content')
<div class="min-h-screen py-10" style="background: url('{{ asset('megamendung/7.png') }}') center center / cover no-repeat;">
    <div class="max-w-4xl mx-auto bg-white/90 rounded-2xl shadow-lg p-8">
        <h1 class="text-2xl font-bold mb-6">Keranjang Belanja</h1>

        @if(session('error'))
            <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-6">
                {{ session('error') }}
            </div>
        @endif

        @if($cartItems->isEmpty())
            <div class="text-center py-8">
                <p class="text-gray-500 mb-4">Keranjang belanja kosong</p>
                <a href="/katalog" class="inline-block px-6 py-2 bg-black text-white rounded-lg hover:bg-yellow-500 transition">
                    Mulai Belanja
                </a>
            </div>
        @else
            <div class="space-y-6">
                @foreach($cartItems as $item)
                <div class="flex gap-4 p-4 bg-gray-50 rounded-lg cart-item" data-id="{{ $item->id }}">
                    <!-- Product Image -->
                    <div class="w-24 h-24">
                        @if($item->barang->gambar)
                            <img src="/storage/{{ $item->barang->gambar }}" 
                                 alt="{{ $item->barang->nama }}" 
                                 class="w-full h-full object-cover rounded">
                        @else
                            <div class="w-full h-full bg-gray-200 rounded flex items-center justify-center">
                                <span class="text-gray-400">No Image</span>
                            </div>
                        @endif
                    </div>

                    <!-- Product Details -->
                    <div class="flex-1">
                        <h3 class="font-semibold text-lg">{{ $item->barang->nama }}</h3>
                        <p class="text-gray-600 text-sm mb-2">{{ $item->barang->deskripsi }}</p>
                        <div class="flex items-center gap-4">
                            <span class="text-sm">Ukuran: {{ $item->ukuran }}</span>
                            <div class="flex items-center gap-2">
                                <label class="text-sm">Jumlah:</label>
                                <input type="number" value="{{ $item->jumlah }}" min="1" 
                                       class="w-16 border rounded px-2 py-1 text-sm quantity-input"
                                       data-id="{{ $item->id }}">
                            </div>
                            <span class="font-semibold item-total">
                                Rp {{ number_format($item->jumlah * $item->barang->harga, 1, '.', '.') }}
                            </span>
                        </div>
                    </div>

                    <!-- Remove Button -->
                    <button class="text-red-500 hover:text-red-700 remove-item" data-id="{{ $item->id }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                @endforeach

                <!-- Cart Total -->
                <div class="border-t pt-4 mt-6">
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-lg font-semibold">Total:</span>
                        <span class="text-2xl font-bold" id="cart-total">
                            Rp {{ number_format($cartItems->sum(function($item) {
                                return $item->jumlah * $item->barang->harga;
                            }), 0, ',', '.') }}
                        </span>
                    </div>
                    
                    <div class="flex justify-end gap-4">
                        <a href="/katalog" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                            Lanjut Belanja
                        </a>                            <a href="{{ route('checkout') }}" class="px-6 py-2 bg-black text-white rounded-lg hover:bg-yellow-500 transition inline-block">
                            Checkout
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Update quantity
    document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('change', async function() {
            const id = this.dataset.id;
            const quantity = this.value;
            
            try {
                const response = await fetch(`/carts/${id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ jumlah: quantity })
                });
                
                const data = await response.json();
                
                if (response.ok) {
                    // Update item total
                    const itemEl = this.closest('.cart-item');
                    itemEl.querySelector('.item-total').textContent = 
                        'Rp ' + new Intl.NumberFormat('id').format(data.new_total);
                    
                    // Update cart total
                    updateCartTotal();
                } else {
                    alert(data.error);
                    // Reset to previous value
                    this.value = this.defaultValue;
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan. Silakan coba lagi.');
            }
        });
    });

    // Remove item
    document.querySelectorAll('.remove-item').forEach(button => {
        button.addEventListener('click', async function() {
            if (!confirm('Apakah Anda yakin ingin menghapus item ini?')) return;
            
            const id = this.dataset.id;
            try {
                const response = await fetch(`/cart/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                if (response.ok) {
                    // Refresh page after successful deletion
                    window.location.reload();
                } else {
                    alert('Terjadi kesalahan saat menghapus item');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan. Silakan coba lagi.');
            }
        });
    });

    function updateCartTotal() {
        const total = Array.from(document.querySelectorAll('.item-total'))
            .reduce((sum, el) => {
                const price = parseInt(el.textContent.replace('Rp ', '').replace(/\./g, ''));
                return sum + (isNaN(price) ? 0 : price);
            }, 0);
        
        document.getElementById('cart-total').textContent = 
            'Rp ' + new Intl.NumberFormat('id').format(total);
    }
});
</script>
@endsection
