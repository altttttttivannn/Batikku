<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('profile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        
        // Validasi dasar
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        // Update nama
        $user->name = $request->name;

        // Jika ada input password baru
        if ($request->filled('new_password')) {
            // Validasi password
            $request->validate([
                'current_password' => 'required',
                'new_password' => 'required|min:8|confirmed'
            ]);

            // Cek password lama
            if (!Hash::check($request->current_password, $user->password)) {
                throw ValidationException::withMessages([
                    'current_password' => ['Password saat ini tidak sesuai']
                ]);
            }

            // Cek password baru tidak sama dengan yang lama
            if (Hash::check($request->new_password, $user->password)) {
                throw ValidationException::withMessages([
                    'new_password' => ['Password baru harus berbeda dengan password saat ini']
                ]);
            }

            $user->password = Hash::make($request->new_password);
        }

        $user->save();

        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    }
}
