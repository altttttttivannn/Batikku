@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Admin Dashboard</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-semibold mb-4">Manajemen Barang</h2>
            <a href="/admin/barang/create" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Tambah Barang</a>
        </div>
        
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-semibold mb-4">Manajemen Transaksi</h2>
            <a href="/admin/transaksi" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Lihat Transaksi</a>
        </div>
    </div>
</div>
@endsection
