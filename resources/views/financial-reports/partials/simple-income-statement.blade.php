{{-- Simple Income Statement Template for PDF/Excel Export --}}
<div class="simple-report">
    <!-- Report Header -->
    <div class="report-header">
        <div class="company-name">{{ company_info('name') }}</div>
        <div class="company-address">{{ company_info('address') }}</div>
        <div class="report-title">Laporan Laba Rugi</div>
        <div class="report-period">Periode {{ $periodStart->format('d F Y') }} s/d {{ $periodEnd->format('d F Y') }}</div>
    </div>

    <!-- Income Statement Table -->
    <table class="report-table">
        <thead>
            <tr>
                <th style="width: 70%;">KETERANGAN</th>
                <th style="width: 30%;">JUMLAH (Rp)</th>
            </tr>
        </thead>
        <tbody>
            <!-- PENDAPATAN -->
            <tr class="section-header">
                <td colspan="2">PENDAPATAN</td>
            </tr>
            @php $totalRevenue = 0; @endphp
            @if(isset($reportData['revenues']) && count($reportData['revenues']) > 0)
                @foreach($reportData['revenues'] as $accountName => $amount)
                    <tr>
                        <td class="indent-1">{{ $accountName }}</td>
                        <td class="text-right">{{ number_format($amount, 0, ',', '.') }}</td>
                    </tr>
                    @php $totalRevenue += $amount; @endphp
                @endforeach
            @else
                <tr>
                    <td class="indent-1 no-data">Tidak ada data pendapatan</td>
                    <td class="text-right">-</td>
                </tr>
            @endif
            <tr class="total-row">
                <td class="text-right">Total Pendapatan</td>
                <td class="text-right">{{ number_format($totalRevenue, 0, ',', '.') }}</td>
            </tr>

            <!-- HARGA POKOK PENJUALAN -->
            @if(isset($reportData['cost_of_goods_sold']) && count($reportData['cost_of_goods_sold']) > 0)
                <tr class="section-header">
                    <td colspan="2">HARGA POKOK PENJUALAN</td>
                </tr>
                @php $totalCOGS = 0; @endphp
                @foreach($reportData['cost_of_goods_sold'] as $accountName => $amount)
                    <tr>
                        <td class="indent-1">{{ $accountName }}</td>
                        <td class="text-right">{{ number_format($amount, 0, ',', '.') }}</td>
                    </tr>
                    @php $totalCOGS += $amount; @endphp
                @endforeach
                <tr class="total-row">
                    <td class="text-right">Total Harga Pokok Penjualan</td>
                    <td class="text-right">{{ number_format($totalCOGS, 0, ',', '.') }}</td>
                </tr>
                
                <!-- LABA KOTOR -->
                @php $grossProfit = $totalRevenue - $totalCOGS; @endphp
                <tr class="grand-total-row">
                    <td class="text-right">LABA KOTOR</td>
                    <td class="text-right">{{ number_format($grossProfit, 0, ',', '.') }}</td>
                </tr>
            @else
                @php $totalCOGS = 0; $grossProfit = $totalRevenue; @endphp
            @endif

            <!-- BEBAN OPERASIONAL -->
            <tr class="section-header">
                <td colspan="2">BEBAN OPERASIONAL</td>
            </tr>
            @php $totalExpenses = 0; @endphp
            @if(isset($reportData['expenses']) && count($reportData['expenses']) > 0)
                @foreach($reportData['expenses'] as $accountName => $amount)
                    <tr>
                        <td class="indent-1">{{ $accountName }}</td>
                        <td class="text-right">{{ number_format($amount, 0, ',', '.') }}</td>
                    </tr>
                    @php $totalExpenses += $amount; @endphp
                @endforeach
            @else
                <tr>
                    <td class="indent-1 no-data">Tidak ada data beban operasional</td>
                    <td class="text-right">-</td>
                </tr>
            @endif
            <tr class="total-row">
                <td class="text-right">Total Beban Operasional</td>
                <td class="text-right">{{ number_format($totalExpenses, 0, ',', '.') }}</td>
            </tr>

            <!-- LABA OPERASIONAL -->
            @php $operatingIncome = $grossProfit - $totalExpenses; @endphp
            <tr class="grand-total-row">
                <td class="text-right">LABA OPERASIONAL</td>
                <td class="text-right">{{ number_format($operatingIncome, 0, ',', '.') }}</td>
            </tr>

            <!-- PENDAPATAN & BEBAN LAIN-LAIN -->
            @php $totalOtherIncome = 0; $totalOtherExpenses = 0; @endphp
            @if(isset($reportData['other_income']) && count($reportData['other_income']) > 0)
                <tr class="section-header">
                    <td colspan="2">PENDAPATAN LAIN-LAIN</td>
                </tr>
                @foreach($reportData['other_income'] as $accountName => $amount)
                    <tr>
                        <td class="indent-1">{{ $accountName }}</td>
                        <td class="text-right">{{ number_format($amount, 0, ',', '.') }}</td>
                    </tr>
                    @php $totalOtherIncome += $amount; @endphp
                @endforeach
                <tr class="total-row">
                    <td class="text-right">Total Pendapatan Lain-lain</td>
                    <td class="text-right">{{ number_format($totalOtherIncome, 0, ',', '.') }}</td>
                </tr>
            @endif

            @if(isset($reportData['other_expenses']) && count($reportData['other_expenses']) > 0)
                <tr class="section-header">
                    <td colspan="2">BEBAN LAIN-LAIN</td>
                </tr>
                @foreach($reportData['other_expenses'] as $accountName => $amount)
                    <tr>
                        <td class="indent-1">{{ $accountName }}</td>
                        <td class="text-right">{{ number_format($amount, 0, ',', '.') }}</td>
                    </tr>
                    @php $totalOtherExpenses += $amount; @endphp
                @endforeach
                <tr class="total-row">
                    <td class="text-right">Total Beban Lain-lain</td>
                    <td class="text-right">{{ number_format($totalOtherExpenses, 0, ',', '.') }}</td>
                </tr>
            @endif

            <!-- LABA SEBELUM PAJAK -->
            @php $incomeBeforeTax = $operatingIncome + $totalOtherIncome - $totalOtherExpenses; @endphp
            @if($totalOtherIncome > 0 || $totalOtherExpenses > 0)
                <tr class="grand-total-row">
                    <td class="text-right">LABA SEBELUM PAJAK</td>
                    <td class="text-right">{{ number_format($incomeBeforeTax, 0, ',', '.') }}</td>
                </tr>
            @endif

            <!-- PAJAK -->
            @php $taxExpense = 0; @endphp
            @if(isset($reportData['tax_expense']) && count($reportData['tax_expense']) > 0)
                <tr class="section-header">
                    <td colspan="2">PAJAK</td>
                </tr>
                @foreach($reportData['tax_expense'] as $accountName => $amount)
                    <tr>
                        <td class="indent-1">{{ $accountName }}</td>
                        <td class="text-right">{{ number_format($amount, 0, ',', '.') }}</td>
                    </tr>
                    @php $taxExpense += $amount; @endphp
                @endforeach
                <tr class="total-row">
                    <td class="text-right">Total Pajak</td>
                    <td class="text-right">{{ number_format($taxExpense, 0, ',', '.') }}</td>
                </tr>
            @endif

            <!-- LABA BERSIH -->
            @php 
                $netIncome = ($totalOtherIncome > 0 || $totalOtherExpenses > 0 || $taxExpense > 0) 
                    ? $incomeBeforeTax - $taxExpense 
                    : $operatingIncome;
                $netIncomeLabel = $netIncome >= 0 ? 'LABA BERSIH' : 'RUGI BERSIH';
            @endphp
            <tr class="grand-total-row" style="border-top: 3px double #000; border-bottom: 3px double #000;">
                <td class="text-right" style="font-size: 14px;">{{ $netIncomeLabel }}</td>
                <td class="text-right" style="font-size: 14px;">{{ number_format(abs($netIncome), 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Summary Section -->
    <div class="summary-section">
        <div class="summary-title">Ringkasan Kinerja</div>
        <div class="summary-item">
            <span>Total Pendapatan:</span>
            <span>Rp {{ number_format($totalRevenue, 0, ',', '.') }}</span>
        </div>
        @if($totalCOGS > 0)
            <div class="summary-item">
                <span>Harga Pokok Penjualan:</span>
                <span>Rp {{ number_format($totalCOGS, 0, ',', '.') }}</span>
            </div>
            <div class="summary-item">
                <span>Laba Kotor:</span>
                <span>Rp {{ number_format($grossProfit, 0, ',', '.') }}</span>
            </div>
        @endif
        <div class="summary-item">
            <span>Total Beban Operasional:</span>
            <span>Rp {{ number_format($totalExpenses, 0, ',', '.') }}</span>
        </div>
        <div class="summary-item" style="border-top: 1px solid #000; padding-top: 5px; font-weight: bold;">
            <span>{{ $netIncomeLabel }}:</span>
            <span>Rp {{ number_format(abs($netIncome), 0, ',', '.') }}</span>
        </div>
        @if($totalRevenue > 0)
            <div class="summary-item">
                <span>Margin Laba:</span>
                <span>{{ number_format(($netIncome / $totalRevenue) * 100, 2) }}%</span>
            </div>
        @endif
    </div>

    <!-- Report Footer -->
    <div class="report-footer">
        <div style="display: flex; justify-content: space-between;">
            <div>Dicetak pada: {{ now()->format('d F Y H:i') }}</div>
            <div>Halaman 1 dari 1</div>
        </div>
        
        <div class="signature-section">
            <div class="signature-box">
                <div>Disiapkan oleh:</div>
                <div class="signature-line"></div>
                <div>Staff Keuangan</div>
            </div>
            <div class="signature-box">
                <div>Diperiksa oleh:</div>
                <div class="signature-line"></div>
                <div>Kepala Keuangan</div>
            </div>
            <div class="signature-box">
                <div>Disetujui oleh:</div>
                <div class="signature-line"></div>
                <div>Direktur</div>
            </div>
        </div>
    </div>
</div>