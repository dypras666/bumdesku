<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class FinancialReport extends Model
{
    /** @use HasFactory<\Database\Factories\FinancialReportFactory> */
    use HasFactory;

    protected $fillable = [
        'report_code',
        'report_type',
        'report_title',
        'period_start',
        'period_end',
        'report_data',
        'report_parameters',
        'status',
        'generated_by',
        'generated_at',
        'finalized_by',
        'finalized_at',
        'notes'
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'report_data' => 'array',
        'report_parameters' => 'array',
        'generated_at' => 'datetime',
        'finalized_at' => 'datetime'
    ];

    // Relationships
    public function generatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'generated_by');
    }

    public function finalizedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'finalized_by');
    }

    // Scopes
    public function scopeByType($query, $type)
    {
        return $query->where('report_type', $type);
    }

    public function scopeByPeriod($query, $startDate, $endDate)
    {
        return $query->whereBetween('period_start', [$startDate, $endDate])
                     ->orWhereBetween('period_end', [$startDate, $endDate]);
    }

    public function scopeGenerated($query)
    {
        return $query->where('status', 'generated');
    }

    public function scopeFinalized($query)
    {
        return $query->where('status', 'finalized');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    // Helper methods
    public static function generateReportCode($type)
    {
        $prefix = match($type) {
            'income_statement' => 'IS',
            'balance_sheet' => 'BS',
            'cash_flow' => 'CF',
            'trial_balance' => 'TB',
            'general_ledger' => 'GL',
            default => 'RPT'
        };

        $date = Carbon::now()->format('Ymd');
        $lastReport = self::where('report_type', $type)
                         ->whereDate('created_at', Carbon::today())
                         ->orderBy('id', 'desc')
                         ->first();
        
        $sequence = $lastReport ? (int)substr($lastReport->report_code, -4) + 1 : 1;
        
        return $prefix . '-' . $date . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    public function getReportTypeLabelAttribute()
    {
        return match($this->report_type) {
            'income_statement' => 'Laporan Laba Rugi',
            'balance_sheet' => 'Neraca',
            'cash_flow' => 'Laporan Arus Kas',
            'trial_balance' => 'Neraca Saldo',
            'general_ledger' => 'Buku Besar',
            default => 'Laporan Lainnya'
        };
    }

    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'draft' => 'Draft',
            'generated' => 'Generated',
            'finalized' => 'Finalized',
            default => 'Unknown'
        };
    }

    public function getStatusBadgeClassAttribute()
    {
        return match($this->status) {
            'draft' => 'badge-warning',
            'generated' => 'badge-info',
            'finalized' => 'badge-success',
            default => 'badge-secondary'
        };
    }

    public function getPeriodLabelAttribute()
    {
        return $this->period_start->format('d M Y') . ' - ' . $this->period_end->format('d M Y');
    }

    public function isGenerated()
    {
        return $this->status === 'generated';
    }

    public function isFinalized()
    {
        return $this->status === 'finalized';
    }

    public function isDraft()
    {
        return $this->status === 'draft';
    }

    public function canBeFinalized()
    {
        return $this->status === 'generated';
    }

    public function canBeRegenerated()
    {
        return in_array($this->status, ['draft', 'generated']);
    }
}
