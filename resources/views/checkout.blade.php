@extends('layouts.app')

@section('content')
<div class="min-h-screen py-10" style="background: url('{{ asset('megamendung/7.png') }}') center center / cover no-repeat;">
    <div class="max-w-7xl mx-auto bg-white/90 rounded-2xl shadow-lg p-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="md:col-span-2">
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h1 class="text-2xl font-bold mb-6">Checkout</h1>
                    
                    @if(session('error'))
                        <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-6">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('checkout.process') }}" method="POST" class="space-y-6">
                        @csrf
                        
                        <div class="space-y-4">
                            <h2 class="text-xl font-semibold">Alamat Pengiriman</h2>
                            <textarea name="shipping_address" 
                                    class="w-full border-gray-300 rounded-lg shadow-sm @error('shipping_address') border-red-500 @enderror" 
                                    rows="3" 
                                    required>{{ old('shipping_address') }}</textarea>
                            @error('shipping_address')
                                <p class="text-red-500 text-sm">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-4">
                            <h2 class="text-xl font-semibold">Pesanan Anda</h2>
                            <div class="overflow-x-auto">
                                <table class="min-w-full">
                                    <thead class="border-b">
                                        <tr>
                                            <th class="text-left py-3">Produk</th>
                                            <th class="text-left py-3">Ukuran</th>
                                            <th class="text-left py-3">Jumlah</th>
                                            <th class="text-right py-3">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y">
                                        @foreach($cartItems as $item)
                                        <tr>
                                            <td class="py-4">
                                                <div class="flex items-center space-x-4">
                                                    @if($item->barang->gambar)
                                                        <img src="/storage/{{ $item->barang->gambar }}" 
                                                             alt="{{ $item->barang->nama }}" 
                                                             class="w-16 h-16 object-cover rounded">
                                                    @endif
                                                    <span>{{ $item->barang->nama }}</span>
                                                </div>
                                            </td>
                                            <td class="py-4">{{ $item->ukuran }}</td>
                                            <td class="py-4">{{ $item->jumlah }}</td>
                                            <td class="py-4 text-right">
                                                Rp {{ number_format($item->barang->harga * $item->jumlah, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <button type="submit" 
                                class="w-full bg-black text-white py-3 rounded-lg font-semibold 
                                       hover:bg-yellow-500 transition">
                            Lanjut ke Pembayaran
                        </button>
                    </form>
                </div>
            </div>

            <div class="md:col-span-1">
                <div class="bg-white rounded-xl shadow-lg p-6 sticky top-6">
                    <h2 class="text-xl font-semibold mb-4">Ringkasan Order</h2>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span>Subtotal</span>
                            <span>Rp {{ number_format($total, 1, ',', '.') }}</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span>Ongkos Kirim</span>
                            <span>Rp {{ number_format($shipping_cost, 1, ',', '.') }}</span>
                        </div>
                        
                        <div class="pt-3 border-t">
                            <div class="flex justify-between font-semibold">
                                <span>Total</span>
                                <span>Rp {{ number_format($grand_total, 1, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
