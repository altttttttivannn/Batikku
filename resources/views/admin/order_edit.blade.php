@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center" style="background: url('/megamendung/7.png') center center / cover no-repeat;">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-lg">
        <h2 class="text-2xl font-bold mb-6">Edit Order #{{ $order->id }}</h2>
        <form method="POST" action="/admin/order/{{ $order->id }}">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="block font-semibold mb-1">Status Order</label>
                <select name="order_status" class="w-full border rounded px-3 py-2" required>
                    <option value="pending" {{ $order->order_status === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="processing" {{ $order->order_status === 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="shipped" {{ $order->order_status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                    <option value="completed" {{ $order->order_status === 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block font-semibold mb-1">Status Pembayaran</label>
                <div class="w-full px-3 py-2 border rounded bg-gray-50">
                    {{ $order->payment_status === 'pending' ? 'Pending' : 'Paid' }}
                </div>
                <input type="hidden" name="payment_status" value="{{ $order->payment_status }}">
            </div>
            <div class="mb-4">
                <label class="block font-semibold mb-1">Alamat Pengiriman</label>
                <div class="w-full px-3 py-2 border rounded bg-gray-50 whitespace-pre-wrap">{{ $order->shipping_address }}</div>
                <input type="hidden" name="shipping_address" value="{{ $order->shipping_address }}">
            </div>
            <div class="mb-6">
                <label class="block font-semibold mb-1">Items</label>
                <ul class="list-disc ml-4 text-sm">
                    @foreach($order->items as $item)
                        <li>
                            {{ $item->barang->nama ?? '-' }} ({{ $item->ukuran }}, {{ $item->quantity }}x, Rp{{ number_format($item->price,0,',','.') }})
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 bg-black text-white rounded font-bold hover:bg-yellow-500 transition">Update</button>
                <a href="/admin?tab=transaksi" class="px-4 py-2 bg-gray-200 text-gray-800 rounded font-bold hover:bg-gray-300 transition">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
