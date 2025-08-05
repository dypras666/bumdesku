<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GeneralLedger extends Model
{
    /** @use HasFactory<\Database\Factories\GeneralLedgerFactory> */
    use HasFactory;

    protected $fillable = [
        'entry_code',
        'account_id',
        'transaction_id',
        'posting_date',
        'debit',
        'credit',
        'description',
        'reference_type',
        'reference_number',
        'posted_by',
        'posted_at',
        'status'
    ];

    protected $casts = [
        'posting_date' => 'date',
        'posted_at' => 'datetime',
        'debit' => 'decimal:2',
        'credit' => 'decimal:2'
    ];

    // Relationships
    public function account(): BelongsTo
    {
        return $this->belongsTo(MasterAccount::class, 'account_id');
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    public function postedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'posted_by');
    }

    // Scopes
    public function scopePosted($query)
    {
        return $query->where('status', 'posted');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeByAccount($query, $accountId)
    {
        return $query->where('account_id', $accountId);
    }

    public function scopeByPeriod($query, $startDate, $endDate)
    {
        return $query->whereBetween('posting_date', [$startDate, $endDate]);
    }

    public function scopeDebits($query)
    {
        return $query->where('debit', '>', 0);
    }

    public function scopeCredits($query)
    {
        return $query->where('credit', '>', 0);
    }

    // Helper methods
    public static function generateEntryCode()
    {
        $date = Carbon::now()->format('Ymd');
        
        // Use database transaction to ensure atomicity
        return DB::transaction(function () use ($date) {
            // Get the highest sequence number for today with row locking
            $lastEntry = self::where('entry_code', 'LIKE', 'GL-' . $date . '-%')
                             ->orderBy('entry_code', 'desc')
                             ->lockForUpdate()
                             ->first();
            
            $sequence = 1;
            if ($lastEntry) {
                // Extract sequence number from entry code (last 4 digits)
                $lastSequence = (int)substr($lastEntry->entry_code, -4);
                $sequence = $lastSequence + 1;
            }
            
            $entryCode = 'GL-' . $date . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
            
            // Double-check uniqueness and increment if needed
            while (self::where('entry_code', $entryCode)->exists()) {
                $sequence++;
                $entryCode = 'GL-' . $date . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
            }
            
            return $entryCode;
        });
    }

    public function getFormattedDebitAttribute()
    {
        return $this->debit > 0 ? format_currency($this->debit) : '-';
    }

    public function getFormattedCreditAttribute()
    {
        return $this->credit > 0 ? format_currency($this->credit) : '-';
    }

    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'draft' => 'Draft',
            'posted' => 'Posted',
            'reversed' => 'Reversed',
            default => 'Unknown'
        };
    }

    public function getStatusBadgeClassAttribute()
    {
        return match($this->status) {
            'draft' => 'badge-warning',
            'posted' => 'badge-success',
            'reversed' => 'badge-danger',
            default => 'badge-secondary'
        };
    }

    public function isPosted()
    {
        return $this->status === 'posted';
    }

    public function isDraft()
    {
        return $this->status === 'draft';
    }

    public function isReversed()
    {
        return $this->status === 'reversed';
    }
}
