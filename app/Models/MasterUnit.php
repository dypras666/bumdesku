<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MasterUnit extends Model
{
    /** @use HasFactory<\Database\Factories\MasterUnitFactory> */
    use HasFactory;

    protected $fillable = [
        'kode_unit',
        'nama_unit',
        'kategori_unit',
        'nilai_aset',
        'alamat',
        'penanggung_jawab_id',
        'is_active'
    ];

    protected $casts = [
        'nilai_aset' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // Scope untuk unit aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope berdasarkan kategori
    public function scopeByCategory($query, $category)
    {
        return $query->where('kategori_unit', $category);
    }

    // Accessor untuk format nilai aset
    public function getFormattedNilaiAsetAttribute()
    {
        return 'Rp ' . number_format($this->nilai_aset, 0, ',', '.');
    }

    // Relasi ke User (Penanggung Jawab)
    public function penanggungJawab(): BelongsTo
    {
        return $this->belongsTo(User::class, 'penanggung_jawab_id');
    }

    // Relasi ke UnitChangeHistory
    public function changeHistories(): HasMany
    {
        return $this->hasMany(UnitChangeHistory::class)->orderBy('created_at', 'desc');
    }
}
