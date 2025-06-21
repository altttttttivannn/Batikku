@extends('layouts.app')

@section('content')
<div class="min-h-screen py-10" style="background: url('/megamendung/7.png') center center / cover no-repeat;">
    <div class="max-w-5xl mx-auto bg-white/90 rounded-2xl shadow-lg p-8">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold">Admin Management</h1>
            <div class="flex gap-2">
                <a href="/" class="px-4 py-2 bg-yellow-400 text-black rounded-full font-semibold hover:bg-yellow-500 transition">Go to Homepage</a>
                <form method="POST" action="/logout">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-full font-semibold hover:bg-gray-300 transition">Log out</button>
                </form>
            </div>
        </div>
        <div class="flex gap-4 mb-8">
            <a href="?tab=produk" class="tab-btn {{ request('tab', 'produk') == 'produk' ? 'bg-black text-white' : 'bg-gray-200 text-gray-800' }} px-6 py-2 rounded-full font-semibold transition">Produk</a>
            <a href="?tab=transaksi" class="tab-btn {{ request('tab') == 'transaksi' ? 'bg-black text-white' : 'bg-gray-200 text-gray-800' }} px-6 py-2 rounded-full font-semibold transition">Transaksi</a>
            <a href="?tab=grafik" class="tab-btn {{ request('tab') == 'grafik' ? 'bg-black text-white' : 'bg-gray-200 text-gray-800' }} px-6 py-2 rounded-full font-semibold transition">Grafik Penjualan</a>
        </div>
        @if(request('tab', 'produk') == 'produk')
            <div>
                <div class="flex justify-between items-center mb-4 gap-2">
                    <form method="GET" action="/admin" class="flex items-center gap-2">
                        <input type="hidden" name="tab" value="produk">
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama produk..." class="border rounded px-3 py-2" />
                        <label class="font-semibold ml-2">Kategori:</label>
                        <select name="kategori" class="border rounded px-3 py-2">
                            <option value="">Semua</option>
                            @foreach($kategoriList as $kategori)
                                <option value="{{ $kategori }}" {{ request('kategori') == $kategori ? 'selected' : '' }}>{{ ucfirst($kategori) }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="px-3 py-2 bg-black text-white rounded hover:bg-yellow-500 transition">Cari / Terapkan</button>
                        @if(request('q') || request('kategori'))
                            <a href="/admin?tab=produk" class="ml-2 px-3 py-2 bg-gray-200 text-gray-800 rounded hover:bg-red-400 hover:text-white font-bold transition">&times;</a>
                        @endif
                    </form>
                    <a href="/admin/barang/create" class="px-4 py-2 bg-yellow-400 text-black rounded-full font-semibold hover:bg-yellow-500 transition">Tambah Produk</a>
                </div>
                @if(count($barangs) > 0)
                <div class="overflow-x-auto rounded-lg border">
                    <table class="min-w-full bg-white">
                        <thead>                            <tr class="bg-gray-100 text-left">
                                <th class="py-2 px-4">Nama</th>
                                <th class="py-2 px-4">Deskripsi</th>
                                <th class="py-2 px-4">Stok per Ukuran</th>
                                <th class="py-2 px-4">Total Stok</th>
                                <th class="py-2 px-4">Harga</th>
                                <th class="py-2 px-4">Gambar</th>
                                <th class="py-2 px-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($barangs as $barang)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-2 px-4">{{ $barang->nama }}</td>
                                <td class="py-2 px-4">{{ $barang->deskripsi }}</td>
                                <td class="py-2 px-4">
                                    <div class="flex gap-2 text-sm">
                                    @foreach(['S', 'M', 'L', 'XL'] as $size)
                                        <span class="px-2 py-1 bg-gray-100 rounded">
                                            {{ $size }}: {{ $barang->stokUkuran->firstWhere('ukuran', $size)->stok ?? 0 }}
                                        </span>
                                    @endforeach
                                    </div>
                                </td>
                                <td class="py-2 px-4">{{ $barang->total_stok }}</td>
                                <td class="py-2 px-4">Rp {{ number_format($barang->harga,0,',','.') }}</td>
                                <td class="py-2 px-4">
                                    @if($barang->gambar)
                                        <img src="/storage/{{ $barang->gambar }}" alt="{{ $barang->nama }}" class="w-16 h-16 object-cover rounded-lg">
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="py-2 px-4 flex gap-2">
                                    <a href="/admin/barang/{{ $barang->id }}/edit" class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 text-sm">Edit</a>
                                    <form method="POST" action="/admin/barang/{{ $barang->id }}" onsubmit="return confirm('Yakin hapus produk?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 text-sm">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                    <div class="text-center text-gray-500 py-10">Tidak ada item</div>
                @endif
            </div>
        @elseif(request('tab') == 'transaksi')
            <div>
                <h2 class="text-xl font-bold mb-4">Daftar Order</h2>
                @if(count($orders) > 0)
                <div class="overflow-x-auto rounded-lg border">
                    <table class="min-w-full bg-white">
                        <thead>
                            <tr class="bg-gray-100 text-left">
                                <th class="py-2 px-4">Order ID</th>
                                <th class="py-2 px-4">User</th>
                                <th class="py-2 px-4">Status</th>
                                <th class="py-2 px-4">Total</th>
                                <th class="py-2 px-4">Alamat</th>
                                <th class="py-2 px-4">Items</th>
                                <th class="py-2 px-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($orders as $order)
                            <tr class="border-b hover:bg-gray-50 align-top">
                                <td class="py-2 px-4 font-mono">{{ $order->id }}</td>
                                <td class="py-2 px-4">{{ $order->user->name ?? '-' }}</td>
                                <td class="py-2 px-4">{{ $order->order_status }}</td>
                                <td class="py-2 px-4">Rp {{ number_format($order->total_amount,0,',','.') }}</td>
                                <td class="py-2 px-4 text-xs">{{ $order->shipping_address }}</td>
                                <td class="py-2 px-4">
                                    <ul class="list-disc ml-4">
                                    @foreach($order->items as $item)
                                        <li>
                                            {{ $item->barang->nama ?? '-' }}
                                            ({{ $item->ukuran }}, {{ $item->quantity }}x, Rp{{ number_format($item->price,0,',','.') }})
                                        </li>
                                    @endforeach
                                    </ul>
                                </td>
                                <td class="py-2 px-4 flex gap-2">
                                    <a href="/admin/order/{{ $order->id }}/edit" class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 text-sm">Edit</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                    <div class="text-center text-gray-500 py-10">Tidak ada order</div>
                @endif
            </div>
        @elseif(request('tab') == 'grafik')
            <div>
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold">Grafik Penjualan</h2>
                    <button type="button" 
                            onclick="toggleFilter()"
                            class="flex items-center gap-2 px-4 py-2 bg-white rounded-full border hover:bg-gray-50 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" />
                        </svg>
                        Filter
                    </button>
                </div>

                <div id="filterPanel" class="hidden mb-6">
                    <form method="GET" class="bg-white p-4 rounded-xl shadow-sm border">
                        <input type="hidden" name="tab" value="grafik">
                        <div class="grid gap-4">
                            <div>
                                <label class="block font-semibold mb-1.5">Periode:</label>
                                <select name="filter" 
                                        class="w-full border rounded-lg px-3 py-2 bg-white focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400">
                                    <option value="bulan" {{ $filter == 'bulan' ? 'selected' : '' }}>Per Bulan</option>
                                    <option value="minggu" {{ $filter == 'minggu' ? 'selected' : '' }}>Per Minggu</option>
                                    <option value="hari" {{ $filter == 'hari' ? 'selected' : '' }}>Per Hari</option>
                                </select>
                            </div>
                            <div>
                                <label class="block font-semibold mb-1.5">Pilih Produk:</label>
                                <div class="flex flex-wrap gap-2 max-h-32 overflow-y-auto p-2 border rounded-lg">
                                    @foreach($barangs as $barang)
                                        <label class="inline-flex items-center gap-2 px-3 py-1.5 bg-gray-100 rounded-full hover:bg-gray-200 cursor-pointer">
                                            <input type="checkbox" 
                                                   name="produk_id[]" 
                                                   value="{{ $barang->id }}" 
                                                   {{ in_array($barang->id, request('produk_id', [])) ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-yellow-500 focus:ring-yellow-400">
                                            <span class="text-sm">{{ $barang->nama }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <button type="submit" 
                                        class="px-4 py-2 bg-black text-white rounded-full hover:bg-yellow-500 transition-colors duration-200">
                                    Terapkan Filter
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="bg-white rounded-xl shadow p-6 mb-6">
                    <canvas id="salesChart" height="120"></canvas>
                </div>

                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                <script>
                    function toggleFilter() {
                        const panel = document.getElementById('filterPanel');
                        panel.classList.toggle('hidden');
                    }

                    const salesChart = document.getElementById('salesChart').getContext('2d');
                    const chart = new Chart(salesChart, {
                        type: 'line',
                        data: {
                            labels: {!! json_encode($labels) !!},
                            datasets: {!! json_encode($datasets) !!}
                        },
                        options: {
                            responsive: true,
                            interaction: {
                                mode: 'index',
                                intersect: false,
                            },
                            plugins: {
                                legend: { 
                                    display: true,
                                    position: 'top'
                                },
                                title: {
                                    display: true,
                                    text: 'Total Penjualan per Produk',
                                    font: { size: 18 }
                                }
                            },
                            scales: {
                                x: {
                                    grid: { display: false },
                                    title: { display: true, text: 'Periode' }
                                },
                                y: {
                                    beginAtZero: true,
                                    title: { display: true, text: 'Total Penjualan (Rp)' }
                                }
                            }
                        }
                    });
                </script>
            </div>
        @endif
    </div>
</div>
@endsection
