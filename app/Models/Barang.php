<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Barang extends Model
{
    use SoftDeletes;

    protected $table = 'barang'; 
    protected $fillable = [
        'nama', 'deskripsi', 'harga', 'gambar', 'kategori'
    ];

    public function stokUkuran()
    {
        return $this->hasMany(UkuranStok::class);
    }
    
    // Getter untuk total stok dari semua ukuran
    public function getTotalStokAttribute()
    {
        return $this->stokUkuran->sum('stok');
    }
}