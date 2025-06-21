<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Auth::user()->cart()->with('barang')->get();
        return view('cart', compact('cartItems'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barang,id',
            'ukuran' => 'required|in:S,M,L,XL',
            'jumlah' => 'required|integer|min:1'
        ]);

        $barang = Barang::findOrFail($request->barang_id);
        $stokUkuran = $barang->stokUkuran()
            ->where('ukuran', $request->ukuran)
            ->first();

        // Cek stok
        if (!$stokUkuran || $stokUkuran->stok < $request->jumlah) {
            return response()->json([
                'error' => 'Stok tidak mencukupi'
            ], 422);
        }

        // Cek jika barang sudah ada di cart dengan ukuran yang sama
        $existingCart = Cart::where('user_id', Auth::id())
            ->where('barang_id', $request->barang_id)
            ->where('ukuran', $request->ukuran)
            ->first();

        if ($existingCart) {
            $newJumlah = $existingCart->jumlah + $request->jumlah;
            if ($stokUkuran->stok < $newJumlah) {
                return response()->json([
                    'error' => 'Total jumlah melebihi stok yang tersedia'
                ], 422);
            }
            $existingCart->jumlah = $newJumlah;
            $existingCart->save();
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'barang_id' => $request->barang_id,
                'ukuran' => $request->ukuran,
                'jumlah' => $request->jumlah
            ]);
        }

        return response()->json([
            'message' => 'Berhasil ditambahkan ke keranjang',
            'cart_count' => Auth::user()->cart()->count()
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'jumlah' => 'required|integer|min:1'
        ]);

        $cartItem = Cart::findOrFail($id);
        
        // Pastikan cart item milik user yang login
        if ($cartItem->user_id !== Auth::id()) {
            return response()->json([
                'error' => 'Unauthorized'
            ], 403);
        }

        $stokUkuran = $cartItem->barang->stokUkuran()
            ->where('ukuran', $cartItem->ukuran)
            ->first();

        if (!$stokUkuran || $stokUkuran->stok < $request->jumlah) {
            return response()->json([
                'error' => 'Stok tidak mencukupi'
            ], 422);
        }

        $cartItem->jumlah = $request->jumlah;
        $cartItem->save();

        return response()->json([
            'message' => 'Jumlah berhasil diupdate',
            'new_total' => $cartItem->jumlah * $cartItem->barang->harga
        ]);
    }

    public function destroy($id)
    {
        $cartItem = Cart::findOrFail($id);
        
        if ($cartItem->user_id !== Auth::id()) {
            return response()->json([
                'error' => 'Unauthorized'
            ], 403);
        }

        $cartItem->delete();

        return response()->json([
            'message' => 'Item berhasil dihapus',
            'cart_count' => Auth::user()->cart()->count()
        ]);
    }

    public function checkout()
    {
        $cartItems = Auth::user()->cart()->with('barang')->get();
        
        if ($cartItems->isEmpty()) {
            return redirect()->back()->with('error', 'Keranjang kosong');
        }

        // Validasi stok untuk semua item
        foreach ($cartItems as $item) {
            $stokUkuran = $item->barang->stokUkuran()
                ->where('ukuran', $item->ukuran)
                ->first();

            if (!$stokUkuran || $stokUkuran->stok < $item->jumlah) {
                return redirect()->back()->with('error', "Stok {$item->barang->nama} ukuran {$item->ukuran} tidak mencukupi");
            }
        }

        // Buat transaksi untuk setiap item
        foreach ($cartItems as $item) {
            $transaksi = Auth::user()->transaksi()->create([
                'barang_id' => $item->barang_id,
                'ukuran' => $item->ukuran,
                'jumlah' => $item->jumlah,
                'total_harga' => $item->barang->harga * $item->jumlah,
                'status' => 'pending'
            ]);

            // Kurangi stok
            $stokUkuran = $item->barang->stokUkuran()
                ->where('ukuran', $item->ukuran)
                ->first();
            $stokUkuran->stok -= $item->jumlah;
            $stokUkuran->save();

            // Hapus item dari cart
            $item->delete();
        }

        return redirect('/admin?tab=transaksi')->with('success', 'Pesanan berhasil dibuat!');
    }
}
