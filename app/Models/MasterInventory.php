<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterInventory extends Model
{
    /** @use HasFactory<\Database\Factories\MasterInventoryFactory> */
    use HasFactory;

    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'kategori_barang',
        'satuan',
        'harga_beli',
        'harga_jual',
        'stok_minimum',
        'deskripsi',
        'is_active'
    ];

    protected $casts = [
        'harga_beli' => 'decimal:2',
        'harga_jual' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // Scope untuk barang aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope berdasarkan kategori
    public function scopeByCategory($query, $category)
    {
        return $query->where('kategori_barang', $category);
    }

    // Accessor untuk format harga beli
    public function getFormattedHargaBeliAttribute()
    {
        return 'Rp ' . number_format($this->harga_beli, 0, ',', '.');
    }

    // Accessor untuk format harga jual
    public function getFormattedHargaJualAttribute()
    {
        return 'Rp ' . number_format($this->harga_jual, 0, ',', '.');
    }

    // Accessor untuk margin keuntungan
    public function getMarginAttribute()
    {
        if ($this->harga_beli > 0) {
            return (($this->harga_jual - $this->harga_beli) / $this->harga_beli) * 100;
        }
        return 0;
    }
}
