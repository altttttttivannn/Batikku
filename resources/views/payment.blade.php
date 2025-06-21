@extends('layouts.app')

@section('content')
<div class="min-h-screen py-10" style="background: url('{{ asset('megamendung/7.png') }}') center center / cover no-repeat;">
    <div class="container mx-auto max-w-2xl bg-white/90 rounded-2xl shadow-lg p-8">
        <div class="flex flex-col md:flex-row items-center gap-8 mb-8">
            <img src="/megamendung/qr-dummy.png" alt="QR Code" class="w-40 h-40 object-contain rounded-xl shadow-md">
            <div class="flex-1 text-center md:text-left">
                <h2 class="text-2xl font-bold mb-2 font-righteous">Pembayaran Pesanan</h2>
                <div class="mb-2">
                    <span class="inline-block bg-green-100 text-green-700 px-3 py-1 rounded-full font-semibold text-lg">Total: Rp {{ number_format($order->total_amount + $order->shipping_cost, 0, ',', '.') }}</span>
                </div>
                <p class="text-gray-600 mb-4">Scan QR Code di atas untuk membayar.<br>Pembayaran diverifikasi otomatis.</p>
                <form action="{{ route('payment.confirm', $order->id) }}" method="POST" class="inline-block">
                    @csrf
                    <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-full font-bold shadow hover:bg-yellow-400 hover:text-black transition">Konfirmasi Pembayaran</button>
                </form>
            </div>
        </div>
        <h3 class="text-lg font-bold mb-3 text-center">Detail Pesanan</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
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
    </div>
</div>
@endsection
