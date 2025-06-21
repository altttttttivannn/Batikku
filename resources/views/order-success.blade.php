@extends('layouts.app')

@section('content')
<div class="min-h-screen py-10" style="background: url('{{ asset('megamendung/7.png') }}') center center / cover no-repeat;">
    <div class="container mx-auto max-w-2xl bg-white/90 rounded-2xl shadow-lg p-8 text-center">
        <div class="mb-6">
            <svg class="mx-auto mb-3" width="64" height="64" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2l4-4" />
                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="#d1fae5" />
            </svg>
            <h2 class="text-2xl font-bold mb-2 font-righteous text-green-700">Terima Kasih!</h2>
            <p class="text-gray-600 mb-4">Pesanan Anda telah berhasil diproses.</p>
        </div>
        <div class="bg-gray-50 rounded-xl p-4 mb-6 text-left">
            <h5 class="font-bold mb-2">Detail Pesanan <span class="inline-block bg-primary-100 text-primary-700 px-2 py-1 rounded">#{{ $order->id }}</span></h5>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 mb-2">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase">Produk</th>
                            <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase">Ukuran</th>
                            <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 uppercase">Jumlah</th>
                            <th class="px-4 py-2 text-right text-xs font-semibold text-gray-600 uppercase">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @foreach($order->items as $item)
                        <tr>
                            <td class="px-4 py-2">{{ $item->barang->nama }}</td>
                            <td class="px-4 py-2">{{ $item->ukuran }}</td>
                            <td class="px-4 py-2">{{ $item->quantity }}</td>
                            <td class="px-4 py-2 text-right">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                        <tr>
                            <td colspan="3" class="px-4 py-2 text-right font-semibold">Ongkos Kirim</td>
                            <td class="px-4 py-2 text-right">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="px-4 py-2 text-right font-bold">Total</td>
                            <td class="px-4 py-2 text-right font-bold">Rp {{ number_format($order->total_amount + $order->shipping_cost, 0, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="mt-2">
                <span class="font-semibold">Alamat Pengiriman:</span> {{ $order->shipping_address }}
            </div>
        </div>
        <a href="/" class="inline-block px-6 py-2 bg-primary-600 text-white rounded-full font-bold shadow hover:bg-yellow-400 hover:text-black transition">Kembali ke Beranda</a>
    </div>
</div>
@endsection
