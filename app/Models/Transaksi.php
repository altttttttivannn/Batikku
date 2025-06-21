<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksi'; // tambahkan baris ini!
    protected $fillable = [
        'user_id', 'barang_id', 'jumlah', 'total_harga', 'status', 'ukuran'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
    public function barang() {
        return $this->belongsTo(Barang::class);
    }
}