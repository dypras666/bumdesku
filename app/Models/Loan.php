<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;
use App\Models\User;
use App\Models\MasterAccount;
use App\Models\LoanPayment;
use App\Models\Transaction;
use App\Models\GeneralLedger;

class Loan extends Model
{
    /** @use HasFactory<\Database\Factories\LoanFactory> */
    use HasFactory;

    protected $fillable = [
        'loan_code',
        'borrower_name',
        'borrower_phone',
        'borrower_address',
        'borrower_id_number',
        'loan_amount',
        'loan_type',
        'interest_rate',
        'profit_sharing_percentage',
        'expected_profit',
        'admin_fee',
        'loan_term_months',
        'monthly_payment',
        'loan_date',
        'due_date',
        'status',
        'total_paid',
        'remaining_balance',
        'notes',
        'business_description',
        'account_id',
        'created_by',
        'approved_at',
        'approved_by'
    ];

    protected $casts = [
        'loan_amount' => 'decimal:2',
        'interest_rate' => 'decimal:2',
        'profit_sharing_percentage' => 'decimal:2',
        'expected_profit' => 'decimal:2',
        'admin_fee' => 'decimal:2',
        'loan_term_months' => 'integer',
        'monthly_payment' => 'decimal:2',
        'total_paid' => 'decimal:2',
        'remaining_balance' => 'decimal:2',
        'loan_date' => 'date',
        'due_date' => 'date',
        'approved_at' => 'datetime'
    ];

    // Relationships
    public function account(): BelongsTo
    {
        return $this->belongsTo(MasterAccount::class, 'account_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(LoanPayment::class);
    }

    public function approvedPayments(): HasMany
    {
        return $this->hasMany(LoanPayment::class)->where('status', 'approved');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'overdue')
                    ->orWhere(function($q) {
                        $q->where('status', 'active')
                          ->where('due_date', '<', now());
                    });
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    // Accessors
    public function getFormattedLoanAmountAttribute()
    {
        return 'Rp ' . number_format($this->loan_amount, 0, ',', '.');
    }

    public function getFormattedMonthlyPaymentAttribute()
    {
        return 'Rp ' . number_format($this->monthly_payment, 0, ',', '.');
    }

    public function getFormattedTotalPaidAttribute()
    {
        return 'Rp ' . number_format($this->total_paid, 0, ',', '.');
    }

    public function getFormattedRemainingBalanceAttribute()
    {
        return 'Rp ' . number_format($this->remaining_balance, 0, ',', '.');
    }

    public function getProgressPercentageAttribute()
    {
        if ($this->loan_amount == 0) return 0;
        return round(($this->total_paid / $this->loan_amount) * 100, 2);
    }

    public function getDaysOverdueAttribute()
    {
        if ($this->status !== 'overdue' && $this->due_date >= now()) {
            return 0;
        }
        return now()->diffInDays($this->due_date);
    }

    public function getNextPaymentDateAttribute()
    {
        $lastPayment = $this->approvedPayments()->latest('payment_date')->first();
        
        if (!$lastPayment) {
            return Carbon::parse($this->loan_date)->addMonth();
        }
        
        return Carbon::parse($lastPayment->payment_date)->addMonth();
    }

    public function getInstallmentsPaidAttribute()
    {
        return $this->approvedPayments()->count();
    }

    public function getRemainingInstallmentsAttribute()
    {
        return $this->loan_term_months - $this->installments_paid;
    }

    // Accessor untuk jenis pinjaman
    public function getLoanTypeNameAttribute()
    {
        $types = [
            'bunga' => 'Pinjaman dengan Bunga',
            'bagi_hasil' => 'Pinjaman Bagi Hasil',
            'tanpa_bunga' => 'Pinjaman Tanpa Bunga'
        ];
        
        return $types[$this->loan_type] ?? 'Tidak Diketahui';
    }

    public function getFormattedAdminFeeAttribute()
    {
        return 'Rp ' . number_format($this->admin_fee, 0, ',', '.');
    }

    public function getFormattedExpectedProfitAttribute()
    {
        return 'Rp ' . number_format($this->expected_profit, 0, ',', '.');
    }

    public function getFormattedProfitSharingPercentageAttribute()
    {
        return $this->profit_sharing_percentage . '%';
    }

    // Method untuk menghitung pembayaran berdasarkan jenis pinjaman
    public function calculatePaymentByType()
    {
        switch ($this->loan_type) {
            case 'bunga':
                return $this->calculateInterestBasedPayment();
            case 'bagi_hasil':
                return $this->calculateProfitSharingPayment();
            case 'tanpa_bunga':
                return $this->calculateInterestFreePayment();
            default:
                return $this->calculateInterestBasedPayment();
        }
    }

    private function calculateInterestBasedPayment()
    {
        if ($this->interest_rate == 0) {
            return $this->loan_amount / $this->loan_term_months;
        }

        $monthlyRate = $this->interest_rate / 100;
        $numerator = $this->loan_amount * $monthlyRate * pow(1 + $monthlyRate, $this->loan_term_months);
        $denominator = pow(1 + $monthlyRate, $this->loan_term_months) - 1;
        
        return $numerator / $denominator;
    }

    private function calculateProfitSharingPayment()
    {
        // Untuk bagi hasil, pembayaran pokok + estimasi bagi hasil per bulan
        $principalPayment = $this->loan_amount / $this->loan_term_months;
        $monthlyProfitSharing = ($this->expected_profit * $this->profit_sharing_percentage / 100) / $this->loan_term_months;
        
        return $principalPayment + $monthlyProfitSharing;
    }

    private function calculateInterestFreePayment()
    {
        // Tanpa bunga, hanya pembayaran pokok
        return $this->loan_amount / $this->loan_term_months;
    }

    // Method untuk mendapatkan total yang harus dibayar
    public function getTotalPayableAmount()
    {
        switch ($this->loan_type) {
            case 'bunga':
                return $this->monthly_payment * $this->loan_term_months;
            case 'bagi_hasil':
                return $this->loan_amount + ($this->expected_profit * $this->profit_sharing_percentage / 100);
            case 'tanpa_bunga':
                return $this->loan_amount + $this->admin_fee;
            default:
                return $this->monthly_payment * $this->loan_term_months;
        }
    }

    public function getFormattedTotalPayableAmountAttribute()
    {
        return 'Rp ' . number_format($this->getTotalPayableAmount(), 0, ',', '.');
    }

    // Methods
    public static function generateLoanCode()
    {
        $prefix = 'PJM';
        $date = now()->format('Ymd');
        $lastLoan = self::where('loan_code', 'like', $prefix . $date . '%')
                       ->orderBy('loan_code', 'desc')
                       ->first();

        if ($lastLoan) {
            $lastNumber = intval(substr($lastLoan->loan_code, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return $prefix . $date . $newNumber;
    }

    public function calculateMonthlyPayment()
    {
        return $this->calculatePaymentByType();
    }

    public function updateBalance()
    {
        $totalPaid = $this->approvedPayments()->sum('payment_amount');
        
        // Untuk semua jenis pinjaman, sisa pinjaman adalah total yang harus dibayar dikurangi yang sudah dibayar
        $totalPayable = $this->getTotalPayableAmount();
        $remainingBalance = $totalPayable - $totalPaid;
        
        if ($remainingBalance <= 0) {
            $remainingBalance = 0;
            $status = 'completed';
        } else {
            $status = $this->status;
        }
        
        $this->update([
            'total_paid' => $totalPaid,
            'remaining_balance' => $remainingBalance,
            'status' => $status
        ]);
    }

    public function checkOverdue()
    {
        if ($this->status === 'active' && $this->due_date < now()) {
            $this->update(['status' => 'overdue']);
        }
    }

    public function approve($userId)
    {
        $this->update([
            'status' => 'active',
            'approved_at' => now(),
            'approved_by' => $userId
        ]);

        // Create initial transaction for loan disbursement
        $this->createLoanTransaction();
    }

    private function createLoanTransaction()
    {
        // Create transaction for loan disbursement
        $transaction = Transaction::create([
            'transaction_code' => Transaction::generateTransactionCode('income'),
            'transaction_type' => 'income',
            'amount' => $this->loan_amount,
            'description' => "Pencairan pinjaman modal - {$this->borrower_name} ({$this->loan_code})",
            'account_id' => $this->account_id,
            'user_id' => $this->created_by,
            'status' => 'approved',
            'approved_at' => now(),
            'transaction_date' => $this->loan_date
        ]);

        // Create general ledger entry
        GeneralLedger::create([
            'entry_code' => GeneralLedger::generateEntryCode(),
            'transaction_id' => $transaction->id,
            'account_id' => $this->account_id,
            'posting_date' => $this->loan_date,
            'debit' => $this->loan_amount,
            'credit' => 0,
            'description' => $transaction->description,
            'reference_number' => $this->loan_code,
            'status' => 'posted',
            'posted_by' => $this->created_by,
            'posted_at' => now()
        ]);
    }
}
