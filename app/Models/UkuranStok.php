<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UkuranStok extends Model
{
    protected $table = 'ukuran_stok';
    
    protected $fillable = ['barang_id', 'ukuran', 'stok'];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
