@extends('layouts.app')

@section('content')
<div class="min-h-screen py-10" style="background: url('{{ asset('megamendung/7.png') }}') center center / cover no-repeat;">
    <div class="max-w-4xl mx-auto bg-white/90 rounded-2xl shadow-lg p-8">
        <h2 class="text-2xl md:text-3xl font-bold text-center mb-8 font-righteous">Riwayat Pesanan Saya</h2>
        @if($orders->count() === 0)
            <div class="text-center text-gray-500 py-8">Belum ada pesanan.</div>
        @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase">#</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Tanggal</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Status</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Total</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Detail</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @foreach($orders as $order)
                    <tr>
                        <td class="px-4 py-3 text-center font-mono">{{ $order->id }}</td>
                        <td class="px-4 py-3 text-center">{{ $order->created_at->format('d M Y H:i') }}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-bold {{
                                $order->order_status === 'success' ? 'bg-green-100 text-green-700' :
                                ($order->order_status === 'processing' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-200 text-gray-700')
                            }}">
                                {{ ucfirst($order->order_status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center font-semibold">Rp {{ number_format($order->total_amount + $order->shipping_cost, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-center">
                            <button onclick="toggleDetail('orderDetail{{ $order->id }}')" class="text-blue-600 hover:underline font-semibold">Lihat</button>
                        </td>
                    </tr>
                    <tr id="orderDetail{{ $order->id }}" class="hidden bg-gray-50">
                        <td colspan="5" class="px-6 py-4">
                            <div class="mb-2"><span class="font-semibold">Alamat Pengiriman:</span> {{ $order->shipping_address }}</div>
                            <div class="mb-2 font-semibold">Item:</div>
                            <ul class="list-disc list-inside text-sm text-gray-700">
                                @foreach($order->items as $item)
                                <li>{{ $item->barang->nama }} (Ukuran: {{ $item->ukuran }}, Jumlah: {{ $item->quantity }}, Subtotal: Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }})</li>
                                @endforeach
                            </ul>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="flex justify-center mt-6">
            {{ $orders->links() }}
        </div>
        @endif
    </div>
</div>
<script>
function toggleDetail(id) {
    const row = document.getElementById(id);
    if (row.classList.contains('hidden')) {
        row.classList.remove('hidden');
    } else {
        row.classList.add('hidden');
    }
}
</script>
@endsection
