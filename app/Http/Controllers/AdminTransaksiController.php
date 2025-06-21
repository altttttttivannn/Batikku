<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;

class AdminTransaksiController extends Controller
{
    public function edit($id)
    {
        $trx = Transaksi::findOrFail($id);
        return view('transaksi_edit', compact('trx'));
    }    public function update(Request $request, $id)
    {
        $trx = Transaksi::findOrFail($id);
        $oldStatus = $trx->status;
        
        $request->validate([
            'jumlah' => 'required|integer',
            'total_harga' => 'required|numeric',
            'status' => 'required',
        ]);

        // Jika status berubah menjadi 'success', kurangi stok
        if ($request->status === 'success' && $oldStatus !== 'success') {
            $stokUkuran = $trx->barang->stokUkuran()
                ->where('ukuran', $trx->ukuran)
                ->first();

            if ($stokUkuran) {
                if ($stokUkuran->stok < $request->jumlah) {
                    return back()->with('error', 'Stok tidak mencukupi!');
                }
                $stokUkuran->stok -= $request->jumlah;
                $stokUkuran->save();
            }
        }
        // Jika status berubah dari 'success' ke status lain, kembalikan stok
        elseif ($oldStatus === 'success' && $request->status !== 'success') {
            $stokUkuran = $trx->barang->stokUkuran()
                ->where('ukuran', $trx->ukuran)
                ->first();

            if ($stokUkuran) {
                $stokUkuran->stok += $trx->jumlah;
                $stokUkuran->save();
            }
        }

        $trx->jumlah = $request->jumlah;
        $trx->total_harga = $request->total_harga;
        $trx->status = $request->status;
        $trx->save();
        
        return redirect('/admin?tab=transaksi');
    }

    public function destroy($id)
    {
        $trx = Transaksi::findOrFail($id);
        $trx->delete();
        return redirect('/admin?tab=transaksi');
    }
}
