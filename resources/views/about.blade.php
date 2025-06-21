@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-yellow-50">
    <div class="w-full bg-yellow-300 py-16 px-4 md:px-0">
        <div class="max-w-4xl mx-auto text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4 font-righteous text-gray-900">Tentang Batikku</h1>
            <p class="text-lg md:text-xl text-gray-800 mb-4">Batikku adalah website e-commerce yang menghubungkan Anda dengan ribuan desa pengrajin batik di seluruh Indonesia. Kami hadir untuk memudahkan siapa saja mendapatkan batik berkualitas langsung dari sumbernya, sekaligus memberdayakan para pengrajin lokal.</p>
            <p class="text-md md:text-lg text-gray-700">Shoutout untuk ribuan desa pengrajin batik yang terafiliasi dan terus melestarikan warisan budaya bangsa. Setiap pembelian Anda adalah dukungan nyata untuk mereka.</p>
        </div>
    </div>
    <div class="max-w-4xl mx-auto py-16 px-4 md:px-0">
        <h2 class="text-2xl md:text-3xl font-bold text-center mb-10 font-righteous">Tim Pengembang</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="flex flex-col items-center bg-white rounded-xl shadow-lg p-6">
                <div class="w-32 h-32 bg-gray-200 rounded-full mb-4 overflow-hidden flex items-center justify-center">
                    <img src="/placeholder-farell.jpg" alt="Muhammad Farell Altivan Ramadhan" class="object-cover w-full h-full">
                </div>
                <div class="text-lg font-bold">Muhammad Farell Altivan Ramadhan</div>
                <div class="text-gray-600">Developer</div>
            </div>
            <div class="flex flex-col items-center bg-white rounded-xl shadow-lg p-6">
                <div class="w-32 h-32 bg-gray-200 rounded-full mb-4 overflow-hidden flex items-center justify-center">
                    <img src="/placeholder-arya.jpg" alt="Arya Kusuma Negara" class="object-cover w-full h-full">
                </div>
                <div class="text-lg font-bold">Arya Kusuma Negara</div>
                <div class="text-gray-600">Developer</div>
            </div>
            <div class="flex flex-col items-center bg-white rounded-xl shadow-lg p-6">
                <div class="w-32 h-32 bg-gray-200 rounded-full mb-4 overflow-hidden flex items-center justify-center">
                    <img src="/placeholder-devi.jpg" alt="Devi Ayu Puspita Sari" class="object-cover w-full h-full">
                </div>
                <div class="text-lg font-bold">Devi Ayu Puspita Sari</div>
                <div class="text-gray-600">Developer</div>
            </div>
        </div>
    </div>
</div>
@endsection
