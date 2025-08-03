<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterAccount extends Model
{
    /** @use HasFactory<\Database\Factories\MasterAccountFactory> */
    use HasFactory;

    protected $fillable = [
        'kode_akun',
        'nama_akun',
        'kategori_akun',
        'deskripsi',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Scope untuk akun aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope berdasarkan kategori
    public function scopeByCategory($query, $category)
    {
        return $query->where('kategori_akun', $category);
    }
}
