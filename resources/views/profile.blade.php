@extends('layouts.app')

@section('content')
<div class="min-h-screen pt-24 pb-10" style="background: url('{{ asset('megamendung/7.png') }}') center center / cover no-repeat;">
    <!-- Include Header -->
    @include('layouts.header')

    <div class="max-w-4xl mx-auto bg-white/90 rounded-2xl shadow-lg p-8">
        <h1 class="text-3xl font-bold mb-8">Profil Saya</h1>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="space-y-2">
                <label for="name" class="block font-medium text-gray-700">Nama</label>
                <input type="text" 
                       name="name" 
                       id="name" 
                       value="{{ old('name', $user->name) }}"
                       class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-yellow-400 focus:border-transparent"
                       required>
                @error('name')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-2">
                <label for="email" class="block font-medium text-gray-700">Email</label>
                <div class="w-full px-4 py-2 rounded-lg border border-gray-300 bg-gray-50">{{ $user->email }}</div>
                <p class="text-sm text-gray-500">Email tidak dapat diubah</p>
            </div>

            <div class="border-t border-gray-200 pt-6 mt-6">
                <h2 class="text-xl font-semibold mb-4">Ubah Password</h2>
                <p class="text-gray-600 text-sm mb-4">Kosongkan field di bawah jika tidak ingin mengubah password</p>

                <div class="space-y-4">
                    <div class="space-y-2">
                        <label for="current_password" class="block font-medium text-gray-700">Password Saat Ini</label>
                        <input type="password" 
                               name="current_password" 
                               id="current_password"
                               class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-yellow-400 focus:border-transparent">
                        @error('current_password')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="new_password" class="block font-medium text-gray-700">Password Baru</label>
                        <input type="password" 
                               name="new_password" 
                               id="new_password"
                               class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-yellow-400 focus:border-transparent">
                        @error('new_password')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="new_password_confirmation" class="block font-medium text-gray-700">Konfirmasi Password Baru</label>
                        <input type="password" 
                               name="new_password_confirmation" 
                               id="new_password_confirmation"
                               class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-yellow-400 focus:border-transparent">
                    </div>
                </div>
            </div>

            <div class="flex justify-end mt-8">
                <button type="submit"
                    class="px-6 py-2 bg-black text-white rounded-full font-bold transition-all hover:bg-yellow-400 hover:shadow-lg active:scale-95">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
