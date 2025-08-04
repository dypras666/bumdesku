<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Transaction;
use App\Models\GeneralLedger;
use App\Models\User;
use App\Models\Loan;

class LoanPayment extends Model
{
    /** @use HasFactory<\Database\Factories\LoanPaymentFactory> */
    use HasFactory;

    protected $fillable = [
        'payment_code',
        'loan_id',
        'payment_date',
        'payment_amount',
        'principal_amount',
        'interest_amount',
        'penalty_amount',
        'installment_number',
        'payment_method',
        'notes',
        'status',
        'transaction_id',
        'created_by',
        'approved_at',
        'approved_by'
    ];

    protected $casts = [
        'payment_amount' => 'decimal:2',
        'principal_amount' => 'decimal:2',
        'interest_amount' => 'decimal:2',
        'penalty_amount' => 'decimal:2',
        'installment_number' => 'integer',
        'payment_date' => 'date',
        'approved_at' => 'datetime'
    ];

    // Relationships
    public function loan(): BelongsTo
    {
        return $this->belongsTo(Loan::class);
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    // Accessors
    public function getFormattedPaymentAmountAttribute()
    {
        return 'Rp ' . number_format($this->payment_amount, 0, ',', '.');
    }

    public function getFormattedPrincipalAmountAttribute()
    {
        return 'Rp ' . number_format($this->principal_amount, 0, ',', '.');
    }

    public function getFormattedInterestAmountAttribute()
    {
        return 'Rp ' . number_format($this->interest_amount, 0, ',', '.');
    }

    public function getFormattedPenaltyAmountAttribute()
    {
        return 'Rp ' . number_format($this->penalty_amount, 0, ',', '.');
    }

    public function getPaymentMethodLabelAttribute()
    {
        $methods = [
            'cash' => 'Tunai',
            'transfer' => 'Transfer',
            'check' => 'Cek',
            'other' => 'Lainnya'
        ];

        return $methods[$this->payment_method] ?? $this->payment_method;
    }

    public function getStatusLabelAttribute()
    {
        $statuses = [
            'pending' => 'Menunggu Persetujuan',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak'
        ];

        return $statuses[$this->status] ?? $this->status;
    }

    public function getStatusBadgeClassAttribute()
    {
        $classes = [
            'pending' => 'badge-warning',
            'approved' => 'badge-success',
            'rejected' => 'badge-danger'
        ];

        return $classes[$this->status] ?? 'badge-secondary';
    }

    // Methods
    public static function generatePaymentCode()
    {
        $prefix = 'BYR';
        $date = now()->format('Ymd');
        $lastPayment = self::where('payment_code', 'like', $prefix . $date . '%')
                          ->orderBy('payment_code', 'desc')
                          ->first();

        if ($lastPayment) {
            $lastNumber = intval(substr($lastPayment->payment_code, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return $prefix . $date . $newNumber;
    }

    public function approve($userId)
    {
        $this->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => $userId
        ]);

        // Create transaction for payment
        $this->createPaymentTransaction();

        // Update loan balance
        $this->loan->updateBalance();
    }

    public function reject($userId, $reason = null)
    {
        $this->update([
            'status' => 'rejected',
            'approved_by' => $userId,
            'notes' => $reason ? $this->notes . "\nDitolak: " . $reason : $this->notes
        ]);
    }

    private function createPaymentTransaction()
    {
        // Create transaction for loan payment
        $transaction = Transaction::create([
            'transaction_code' => Transaction::generateTransactionCode('income'),
            'transaction_type' => 'income',
            'amount' => $this->payment_amount,
            'description' => "Pembayaran pinjaman cicilan ke-{$this->installment_number} - {$this->loan->borrower_name} ({$this->loan->loan_code})",
            'account_id' => $this->loan->account_id,
            'user_id' => $this->created_by,
            'status' => 'approved',
            'approved_at' => now(),
            'transaction_date' => $this->payment_date
        ]);

        // Update payment with transaction reference
        $this->update(['transaction_id' => $transaction->id]);

        // Create general ledger entry (Credit to reduce receivable)
        GeneralLedger::create([
            'entry_code' => GeneralLedger::generateEntryCode(),
            'transaction_id' => $transaction->id,
            'account_id' => $this->loan->account_id,
            'posting_date' => $this->payment_date,
            'debit' => 0,
            'credit' => $this->payment_amount,
            'description' => $transaction->description,
            'reference_number' => $this->payment_code,
            'posted_by' => $this->created_by,
            'posted_at' => now(),
            'status' => 'posted'
        ]);
    }

    public function calculateBreakdown()
    {
        $loan = $this->loan;
        $remainingBalance = $loan->remaining_balance;
        
        // Calculate interest for this payment
        $interestAmount = 0;
        if ($loan->interest_rate > 0) {
            $interestAmount = $remainingBalance * ($loan->interest_rate / 100);
        }

        // Calculate penalty if overdue
        $penaltyAmount = 0;
        if ($loan->status === 'overdue') {
            $daysOverdue = $loan->days_overdue;
            $penaltyRate = 0.1; // 0.1% per day
            $penaltyAmount = $remainingBalance * ($penaltyRate / 100) * $daysOverdue;
        }

        // Principal is the remainder
        $principalAmount = $this->payment_amount - $interestAmount - $penaltyAmount;

        return [
            'principal_amount' => max(0, $principalAmount),
            'interest_amount' => $interestAmount,
            'penalty_amount' => $penaltyAmount,
            'total_amount' => $this->payment_amount
        ];
    }
}
