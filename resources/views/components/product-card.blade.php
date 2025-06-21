@props(['barang', 'produk' => null])

@php
    $produk = $produk ?? $barang;
@endphp

<div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300 h-full flex flex-col">
    <a href="{{ route('produk.detail', $produk->id) }}" class="flex flex-col h-full">
        <!-- Image Container with fixed aspect ratio -->
        <div class="relative pt-[75%] w-full">
            @if($produk->gambar)
                <img src="/storage/{{ $produk->gambar }}" 
                     alt="{{ $produk->nama }}" 
                     class="absolute top-0 left-0 w-full h-full object-cover rounded-t-xl">
            @else
                <div class="absolute top-0 left-0 w-full h-full bg-gray-200 rounded-t-xl flex items-center justify-center">
                    <span class="text-gray-400">No Image</span>
                </div>
            @endif
        </div>

        <!-- Content Container with fixed heights -->
        <div class="p-4 flex flex-col justify-between gap-4 flex-1">
            <!-- Product Info with fixed heights -->
            <div class="space-y-2">
                <h3 class="text-lg font-semibold h-14 line-clamp-2">{{ $produk->nama }}</h3>
                <p class="text-yellow-600 font-bold h-6">Rp {{ number_format($produk->harga, 0, ',', '.') }}</p>
                <p class="text-sm text-gray-600 h-10 line-clamp-2">{{ $produk->deskripsi }}</p>
            </div>            <!-- Stock Grid with fixed height -->
            <div class="mt-auto">
                <div class="grid grid-cols-4 gap-2 mb-4 h-[72px]">
                    @php
                        $hasStock = false;
                    @endphp
                    @foreach($produk->stokUkuran as $stok)
                        <div class="text-center p-2 rounded-lg {{ $stok->stok > 0 ? 'bg-gray-100' : 'bg-gray-50 opacity-50' }}">
                            <div class="font-medium">{{ $stok->ukuran }}</div>
                            <div class="text-sm text-gray-600">{{ $stok->stok }}</div>
                        </div>
                        @if($stok->stok > 0)
                            @php
                                $hasStock = true;
                            @endphp
                        @endif
                    @endforeach
                </div>

                @if($hasStock)
                    <div class="w-full text-center px-4 py-2 bg-black text-white rounded-lg hover:bg-yellow-500 transition-colors">
                        Detail
                    </div>
                @else
                    <div class="w-full text-center px-4 py-2 bg-gray-300 text-gray-600 rounded-lg cursor-not-allowed">
                        Stok Habis
                    </div>
                @endif
            </div>
        </div>
    </a>
</div>
