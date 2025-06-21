<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class KatalogController extends Controller
{
    public function index(Request $request)
    {
        $query = Barang::with('stokUkuran');

        // Filter by kategori
        if ($request->has('kategori') && $request->kategori !== 'all') {
            $kategoris = explode(',', $request->kategori);
            $query->whereIn('kategori', $kategoris);
        }

        // Filter by price range
        if ($request->has('min_price')) {
            $query->where('harga', '>=', $request->min_price);
        }
        if ($request->has('max_price')) {
            $query->where('harga', '<=', $request->max_price);
        }        // Filter by ukuran with stock
        if ($request->has('ukuran') && !empty($request->ukuran)) {
            $ukurans = is_array($request->ukuran) ? $request->ukuran : explode(',', $request->ukuran);
            $query->whereHas('stokUkuran', function($q) use ($ukurans) {
                $q->whereIn('ukuran', $ukurans)
                  ->where('stok', '>', 0);
            });
        }

        // Sorting
        switch ($request->sort) {
            case 'price_asc':
                $query->orderBy('harga', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('harga', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        $barangs = $query->paginate(9);
        $kategoris = Barang::distinct()->pluck('kategori')->filter(); // Get unique kategoris

        return view('katalog', compact('barangs', 'kategoris'));
    }
}
