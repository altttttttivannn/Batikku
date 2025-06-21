@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center" style="background: url('/megamendung/7.png') center center / cover no-repeat;">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-lg">
        <h2 class="text-2xl font-bold mb-6">Edit Transaksi</h2>
        <form method="POST" action="/admin/transaksi/{{ $trx->id }}">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="block font-semibold mb-1">Jumlah</label>
                <input type="number" name="jumlah" class="w-full border rounded px-3 py-2" value="{{ $trx->jumlah }}" required>
            </div>
            <div class="mb-4">
                <label class="block font-semibold mb-1">Total Harga</label>
                <input type="number" name="total_harga" class="w-full border rounded px-3 py-2" value="{{ $trx->total_harga }}" required>
            </div>            <div class="mb-4">
                <label class="block font-semibold mb-1">Ukuran</label>
                <select name="ukuran" class="w-full border rounded px-3 py-2" required>
                    @foreach(['S', 'M', 'L', 'XL'] as $size)
                        <option value="{{ $size }}" {{ $trx->ukuran === $size ? 'selected' : '' }}>
                            {{ $size }} (Stok: {{ $trx->barang->stokUkuran->firstWhere('ukuran', $size)->stok ?? 0 }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-6">
                <label class="block font-semibold mb-1">Status</label>
                <select name="status" class="w-full border rounded px-3 py-2" required>
                    <option value="pending" {{ $trx->status === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="success" {{ $trx->status === 'success' ? 'selected' : '' }}>Success</option>
                    <option value="failed" {{ $trx->status === 'failed' ? 'selected' : '' }}>Failed</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 bg-black text-white rounded font-bold hover:bg-yellow-500 transition">Update</button>
                <a href="/admin?tab=transaksi" class="px-4 py-2 bg-gray-200 text-gray-800 rounded font-bold hover:bg-gray-300 transition">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
