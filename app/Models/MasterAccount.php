<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MasterAccount extends Model
{
    /** @use HasFactory<\Database\Factories\MasterAccountFactory> */
    use HasFactory;

    protected $fillable = [
        'kode_akun',
        'nama_akun',
        'kategori_akun',
        'deskripsi',
        'saldo_awal',
        'is_active'
    ];

    protected $casts = [
        'saldo_awal' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function generalLedgerEntries(): HasMany
    {
        return $this->hasMany(GeneralLedger::class, 'account_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'account_id');
    }

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

    // Helper methods
    public function getAccountCodeAttribute()
    {
        return $this->kode_akun;
    }

    public function getAccountNameAttribute()
    {
        return $this->nama_akun;
    }

    public function getAccountCategoryAttribute()
    {
        return $this->kategori_akun;
    }

    public function getCurrentBalance()
    {
        $totalDebit = $this->generalLedgerEntries()->sum('debit');
        $totalCredit = $this->generalLedgerEntries()->sum('credit');
        
        // For asset and expense accounts, debit increases balance
        // For liability, equity, and revenue accounts, credit increases balance
        if (in_array($this->kategori_akun, ['Asset', 'Expense'])) {
            return $this->saldo_awal + $totalDebit - $totalCredit;
        } else {
            return $this->saldo_awal + $totalCredit - $totalDebit;
        }
    }

    public function getFormattedSaldoAwalAttribute()
    {
        return 'Rp ' . number_format($this->saldo_awal, 0, ',', '.');
    }
}
