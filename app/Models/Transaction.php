<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    /** @use HasFactory<\Database\Factories\TransactionFactory> */
    use HasFactory;

    protected $fillable = [
        'transaction_code',
        'transaction_type',
        'transaction_date',
        'amount',
        'description',
        'account_id',
        'user_id',
        'status',
        'approved_at',
        'approved_by',
        'notes',
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'amount' => 'decimal:2',
        'approved_at' => 'datetime',
    ];

    /**
     * Relationship dengan MasterAccount
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(MasterAccount::class, 'account_id');
    }

    /**
     * Relationship dengan User (yang input transaksi)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relationship dengan User (yang approve transaksi)
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Scope untuk transaksi pemasukan
     */
    public function scopeIncome($query)
    {
        return $query->where('transaction_type', 'income');
    }

    /**
     * Scope untuk transaksi pengeluaran
     */
    public function scopeExpense($query)
    {
        return $query->where('transaction_type', 'expense');
    }

    /**
     * Scope untuk transaksi yang sudah disetujui
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope untuk transaksi pending
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Generate kode transaksi otomatis
     */
    public static function generateTransactionCode($type)
    {
        $prefix = $type === 'income' ? 'IN' : 'OUT';
        $date = now()->format('Ymd');
        $lastTransaction = self::where('transaction_code', 'like', $prefix . $date . '%')
                              ->orderBy('transaction_code', 'desc')
                              ->first();
        
        if ($lastTransaction) {
            $lastNumber = (int) substr($lastTransaction->transaction_code, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . $date . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get formatted amount
     */
    public function getFormattedAmountAttribute()
    {
        return format_currency($this->amount);
    }

    /**
     * Get transaction type label
     */
    public function getTypeLabel()
    {
        return $this->transaction_type === 'income' ? 'Pemasukan' : 'Pengeluaran';
    }

    /**
     * Get status label
     */
    public function getStatusLabel()
    {
        return match($this->status) {
            'pending' => 'Menunggu',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            default => 'Unknown'
        };
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClass()
    {
        return match($this->status) {
            'pending' => 'badge-warning',
            'approved' => 'badge-success',
            'rejected' => 'badge-danger',
            default => 'badge-secondary'
        };
    }
}
