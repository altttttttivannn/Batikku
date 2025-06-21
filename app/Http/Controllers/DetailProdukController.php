<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class DetailProdukController extends Controller
{
    public function show($id)
    {
        $barang = Barang::with('stokUkuran')->findOrFail($id);
        return view('detail_produk', compact('barang'));
    }
}
