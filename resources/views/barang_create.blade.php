@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center" style="background: url('/megamendung/7.png') center center / cover no-repeat;">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-lg">
        <h2 class="text-2xl font-bold mb-6">Tambah Produk</h2>
        <form method="POST" action="/admin/barang" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label class="block font-semibold mb-1">Nama</label>
                <input type="text" name="nama" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="mb-4">
                <label class="block font-semibold mb-1">Deskripsi</label>
                <textarea name="deskripsi" class="w-full border rounded px-3 py-2"></textarea>
            </div>            <div class="mb-4">
                <label class="block font-semibold mb-2">Stok per Ukuran</label>
                <div class="grid grid-cols-4 gap-4">
                    @foreach(['S', 'M', 'L', 'XL'] as $size)
                    <div>
                        <label class="block text-sm mb-1">Ukuran {{ $size }}</label>
                        <input type="number" name="stok[{{ $size }}]" 
                               class="w-full border rounded px-3 py-2" 
                               value="0" min="0" required>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="mb-4">
                <label class="block font-semibold mb-1">Harga</label>
                <input type="number" name="harga" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="mb-4">
                <label class="block font-semibold mb-1">Kategori</label>
                <select name="kategori" class="w-full border rounded px-3 py-2" required>
                    <option value="kemeja">Kemeja</option>
                    <option value="celana">Celana</option>
                    <option value="accesories">Accesories</option>
                </select>
            </div>
            <div class="mb-6">
                <label class="block font-semibold mb-1">Gambar</label>
                <input type="file" name="gambar" class="w-full">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 bg-black text-white rounded font-bold hover:bg-yellow-500 transition">Simpan</button>
                <a href="/admin?tab=produk" class="px-4 py-2 bg-gray-200 text-gray-800 rounded font-bold hover:bg-gray-300 transition">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
