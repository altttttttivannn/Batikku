@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center" style="background: url('/megamendung/7.png') center center / cover no-repeat;">
    <div class="w-full max-w-md">
        <a href="/" class="inline-block mb-4 px-4 py-2 bg-black text-white rounded-full font-semibold hover:bg-yellow-500 transition">Back to Home</a>
        <div class="bg-white rounded-lg shadow-lg p-8 relative z-10">
            <h2 class="text-2xl font-bold mb-6 text-center">Sign Up Batikku</h2>
            @if($errors->any())
                <div class="mb-4 text-red-600">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form method="POST" action="/register">
                @csrf
                <div class="mb-4">
                    <label class="block mb-1 font-semibold">Nama</label>
                    <input type="text" name="name" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring" required>
                </div>
                <div class="mb-4">
                    <label class="block mb-1 font-semibold">Email</label>
                    <input type="email" 
                           name="email" 
                           pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
                           class="w-full px-3 py-2 border rounded focus:outline-none focus:ring" 
                           title="Email harus mengandung @ dan . (contoh: nama@domain.com)"
                           required>
                    <small class="text-gray-500">Format: nama@domain.com</small>
                </div>
                <div class="mb-6">
                    <label class="block mb-1 font-semibold">Password</label>
                    <input type="password" name="password" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring" required>
                </div>
                <button type="submit" class="w-full py-2 bg-black text-white rounded font-bold hover:bg-yellow-500 transition">Sign Up</button>
            </form>
            <div class="mt-4 text-center">
                <span>Sudah punya akun?</span>
                <a href="/login" class="text-blue-600 hover:underline">Login</a>
            </div>
        </div>
    </div>
</div>
@endsection
