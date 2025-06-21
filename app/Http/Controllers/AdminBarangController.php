<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\UkuranStok;

class AdminBarangController extends Controller
{
    public function create()
    {
        $ukuran = ['S', 'M', 'L', 'XL'];
        return view('barang_create', compact('ukuran'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'deskripsi' => 'nullable',
            'harga' => 'required|numeric',
            'gambar' => 'nullable|image',
            'stok.*' => 'required|integer|min:0',
        ]);

        $gambar = $request->file('gambar');
        $gambarPath = $gambar ? $gambar->store('barang', 'public') : null;

        $barang = Barang::create([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
            'gambar' => $gambarPath,
            'kategori' => $request->kategori,
        ]);

        // Simpan stok untuk setiap ukuran
        foreach ($request->stok as $ukuran => $jumlah) {
            $barang->stokUkuran()->create([
                'ukuran' => $ukuran,
                'stok' => $jumlah
            ]);
        }

        return redirect('/admin?tab=produk');
    }

    public function edit($id)
    {
        $barang = Barang::with('stokUkuran')->findOrFail($id);
        $ukuran = ['S', 'M', 'L', 'XL'];
        return view('barang_edit', compact('barang', 'ukuran'));
    }

    public function update(Request $request, $id)
    {
        $barang = Barang::findOrFail($id);
        $request->validate([
            'nama' => 'required',
            'deskripsi' => 'nullable',
            'harga' => 'required|numeric',
            'gambar' => 'nullable|image',
            'stok.*' => 'required|integer|min:0',
        ]);

        $gambar = $request->file('gambar');
        if ($gambar) {
            $barang->gambar = $gambar->store('barang', 'public');
        }

        $barang->nama = $request->nama;
        $barang->deskripsi = $request->deskripsi;
        $barang->harga = $request->harga;
        $barang->kategori = $request->kategori;
        $barang->save();

        // Update stok untuk setiap ukuran
        foreach ($request->stok as $ukuran => $jumlah) {
            $barang->stokUkuran()->updateOrCreate(
                ['ukuran' => $ukuran],
                ['stok' => $jumlah]
            );
        }

        return redirect('/admin?tab=produk');
    }

    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);
        $barang->delete(); // soft delete
        return redirect('/admin?tab=produk');
    }

    public function index(Request $request)
    {
        $query = Barang::with('stokUkuran');
        
        if ($request->has('q')) {
            $query->where('nama', 'like', '%' . $request->q . '%');
        }
        
        $barangs = $query->get();
        return view('admin', compact('barangs'));
    }
}
