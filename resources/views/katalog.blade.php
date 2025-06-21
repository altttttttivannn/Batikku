@extends('layouts.app')

@section('content')
<div class="min-h-screen pt-8 pb-10" style="background: url('{{ asset('megamendung/7.png') }}') center center / cover no-repeat;">
    <div class="max-w-7xl mx-auto bg-white/90 rounded-2xl shadow-lg p-4">        <!-- Include Header -->
        @include('layouts.header')
        <div class="min-h-screen pt-28 pb-8 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex gap-8">
                    <!-- Filter Sidebar -->
                    <div class="w-64 flex-shrink-0">
                        <div class="bg-white rounded-lg shadow p-6">
                            <!-- Kategori Filter -->
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold mb-3">Kategori</h3>
                                <div class="space-y-2">
                                    <label class="flex items-center">
                                        <input type="radio" name="kategori" value="all" class="rounded-full text-blue-600" {{ !request('kategori') || request('kategori') == 'all' ? 'checked' : '' }}>
                                        <span class="ml-2">Semua</span>
                                    </label>
                                    @foreach($kategoris as $kategori)
                                    <label class="flex items-center">
                                        <input type="radio" name="kategori" value="{{ $kategori }}" class="rounded-full text-blue-600" {{ request('kategori') == $kategori ? 'checked' : '' }}>
                                        <span class="ml-2">{{ $kategori }}</span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Range Harga Filter -->
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold mb-3">Range Harga</h3>
                                <div class="space-y-3">
                                    <div>
                                        <input type="number" name="min_price" placeholder="Minimum" 
                                               class="w-full px-3 py-2 border rounded-lg text-sm"
                                               value="{{ request('min_price') }}">
                                    </div>
                                    <div>
                                        <input type="number" name="max_price" placeholder="Maximum" 
                                               class="w-full px-3 py-2 border rounded-lg text-sm"
                                               value="{{ request('max_price') }}">
                                    </div>
                                </div>
                            </div>

                            <!-- Ukuran Filter -->
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold mb-3">Ukuran</h3>
                                <div class="grid grid-cols-2 gap-2">                            @foreach(['S', 'M', 'L', 'XL'] as $size)
                                    <label class="flex items-center">
                                        <input type="checkbox" name="ukuran[]" value="{{ $size }}" 
                                               class="rounded text-blue-600"
                                               {{ is_array(request('ukuran')) && in_array($size, request('ukuran')) ? 'checked' : '' }}>
                                        <span class="ml-2">{{ $size }}</span>
                                        @php
                                            $countStok = App\Models\UkuranStok::where('ukuran', $size)
                                                ->where('stok', '>', 0)
                                                ->count();
                                        @endphp
                                        <span class="ml-1 text-gray-500 text-sm">({{ $countStok }})</span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Reset & Terapkan Filter -->
                            <div class="flex gap-2 mt-4">
                                <button type="button" onclick="resetFilters()" 
                                        class="w-1/2 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                                    Reset Filter
                                </button>
                                <button type="button" onclick="applyFilters()" 
                                        class="w-1/2 px-4 py-2 bg-yellow-400 text-black rounded-lg hover:bg-yellow-500 transition font-semibold">
                                    Terapkan Filter
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Product Grid -->
                    <div class="flex-1">
                        <!-- Sort and Search Header -->
                        <div class="flex justify-between items-center mb-6">
                            <div class="flex items-center space-x-4">
                                <select name="sort" class="border rounded-lg px-3 py-2" onchange="applyFilters()">
                                    <option value="">Urutkan</option>
                                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>
                                        Harga: Rendah ke Tinggi
                                    </option>
                                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>
                                        Harga: Tinggi ke Rendah
                                    </option>
                                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>
                                        Terbaru
                                    </option>
                                </select>
                            </div>
                            <div class="text-sm text-gray-500">
                                @if(request('kategori') && request('kategori') != 'all')
                                    <span class="font-semibold text-black">Menampilkan kategori: {{ ucfirst(request('kategori')) }}</span> |
                                @endif
                                Menampilkan {{ $barangs->total() }} produk
                            </div>
                        </div>                <!-- Products Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 auto-rows-fr">
                            @foreach($barangs as $barang)
                                <x-product-card :barang="$barang" />
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-8">
                            {{ $barangs->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
        function applyFilters() {
            const kategoris = Array.from(document.querySelectorAll('input[name="kategori"]:checked')).map(cb => cb.value);
            const ukurans = Array.from(document.querySelectorAll('input[name="ukuran[]"]:checked')).map(cb => cb.value);
            const minPrice = document.querySelector('input[name="min_price"]').value;
            const maxPrice = document.querySelector('input[name="max_price"]').value;
            const sort = document.querySelector('select[name="sort"]').value;

            let params = new URLSearchParams();
            
            if (kategoris.length) params.append('kategori', kategoris.join(','));
            
            // Menambahkan setiap ukuran sebagai parameter terpisah
            ukurans.forEach(ukuran => {
                params.append('ukuran[]', ukuran);
            });
            
            if (minPrice) params.append('min_price', minPrice);
            if (maxPrice) params.append('max_price', maxPrice);
            if (sort) params.append('sort', sort);

            window.location.href = `/katalog?${params.toString()}`;
        }

        function resetFilters() {
            window.location.href = '/katalog';
        }

        // Add event listeners for filters
        // Hapus auto-apply filter, user harus klik Terapkan Filter
        // document.querySelectorAll('input[type="checkbox"], input[type="number"]').forEach(input => {
        //     input.addEventListener('change', applyFilters);
        // });
        // document.querySelectorAll('input[type="radio"][name="kategori"]').forEach(input => {
        //     input.addEventListener('change', applyFilters);
        // });
        </script>
    </div>
@endsection
