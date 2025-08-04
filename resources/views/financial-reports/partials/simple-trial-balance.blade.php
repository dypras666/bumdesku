{{-- Simple Trial Balance Template for PDF/Excel Export --}}
<div class="simple-report">
    <!-- Report Header -->
    <div class="report-header">
        <div class="company-name">{{ company_info('name') }}</div>
        <div class="company-address">{{ company_info('address') }}</div>
        <div class="report-title">Neraca Saldo</div>
        <div class="report-period">Per {{ $asOfDate->format('d F Y') }}</div>
    </div>

    <!-- Trial Balance Table -->
    <table class="report-table">
        <thead>
            <tr>
                <th style="width: 15%;">KODE AKUN</th>
                <th style="width: 45%;">NAMA AKUN</th>
                <th style="width: 20%;">DEBIT (Rp)</th>
                <th style="width: 20%;">KREDIT (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @php 
                $totalDebit = 0; 
                $totalCredit = 0; 
            @endphp
            @if(isset($reportData['accounts']) && count($reportData['accounts']) > 0)
                @foreach($reportData['accounts'] as $account)
                    @if(is_array($account))
                        <tr>
                            <td class="text-center">{{ $account['code'] ?? '' }}</td>
                            <td>{{ $account['name'] ?? '' }}</td>
                            <td class="text-right">
                                @if(($account['debit'] ?? 0) > 0)
                                    {{ number_format($account['debit'], 0, ',', '.') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-right">
                                @if(($account['credit'] ?? 0) > 0)
                                    {{ number_format($account['credit'], 0, ',', '.') }}
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        @php 
                            $totalDebit += $account['debit'] ?? 0; 
                            $totalCredit += $account['credit'] ?? 0; 
                        @endphp
                    @endif
                @endforeach
            @else
                <tr>
                    <td colspan="4" class="text-center no-data">Tidak ada data neraca saldo</td>
                </tr>
            @endif
            
            <!-- Total Row -->
            <tr class="grand-total-row">
                <td colspan="2" class="text-right">TOTAL</td>
                <td class="text-right">{{ number_format($totalDebit, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($totalCredit, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Balance Check -->
    @php 
        $balanceDifference = $totalDebit - $totalCredit;
        $isBalanced = abs($balanceDifference) < 0.01;
    @endphp
    <div class="balance-check {{ $isBalanced ? 'balanced' : 'unbalanced' }}">
        @if($isBalanced)
            ✓ NERACA SALDO SEIMBANG
        @else
            ⚠ NERACA SALDO TIDAK SEIMBANG - Selisih: Rp {{ number_format(abs($balanceDifference), 0, ',', '.') }}
            ({{ $balanceDifference > 0 ? 'Debit lebih besar' : 'Kredit lebih besar' }})
        @endif
    </div>

    <!-- Summary Section -->
    <div class="summary-section">
        <div class="summary-title">Ringkasan Neraca Saldo</div>
        <div class="summary-item">
            <span>Total Saldo Debit:</span>
            <span>Rp {{ number_format($totalDebit, 0, ',', '.') }}</span>
        </div>
        <div class="summary-item">
            <span>Total Saldo Kredit:</span>
            <span>Rp {{ number_format($totalCredit, 0, ',', '.') }}</span>
        </div>
        <div class="summary-item">
            <span>Selisih:</span>
            <span>Rp {{ number_format(abs($balanceDifference), 0, ',', '.') }}</span>
        </div>
        <div class="summary-item" style="border-top: 1px solid #000; padding-top: 5px; font-weight: bold;">
            <span>Status Keseimbangan:</span>
            <span>{{ $isBalanced ? 'SEIMBANG' : 'TIDAK SEIMBANG' }}</span>
        </div>
        @if(isset($reportData['accounts']))
            <div class="summary-item">
                <span>Jumlah Akun:</span>
                <span>{{ count($reportData['accounts']) }} akun</span>
            </div>
        @endif
    </div>

    <!-- Account Categories Summary -->
    @if(isset($reportData['accounts']) && count($reportData['accounts']) > 0)
        <div class="summary-section">
            <div class="summary-title">Ringkasan per Kategori Akun</div>
            @php
                $categories = [
                    '1' => ['name' => 'Aset', 'debit' => 0, 'credit' => 0, 'count' => 0],
                    '2' => ['name' => 'Kewajiban', 'debit' => 0, 'credit' => 0, 'count' => 0],
                    '3' => ['name' => 'Modal', 'debit' => 0, 'credit' => 0, 'count' => 0],
                    '4' => ['name' => 'Pendapatan', 'debit' => 0, 'credit' => 0, 'count' => 0],
                    '5' => ['name' => 'Beban', 'debit' => 0, 'credit' => 0, 'count' => 0],
                ];
                
                foreach($reportData['accounts'] as $account) {
                    if(is_array($account)) {
                        $firstDigit = substr($account['code'] ?? '', 0, 1);
                        if(isset($categories[$firstDigit])) {
                            $categories[$firstDigit]['debit'] += $account['debit'] ?? 0;
                            $categories[$firstDigit]['credit'] += $account['credit'] ?? 0;
                            $categories[$firstDigit]['count']++;
                        }
                    }
                }
            @endphp
            
            <table class="report-table" style="margin-top: 10px;">
                <thead>
                    <tr>
                        <th>Kategori</th>
                        <th>Jumlah Akun</th>
                        <th>Total Debit</th>
                        <th>Total Kredit</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $code => $category)
                        @if($category['count'] > 0)
                            <tr>
                                <td>{{ $category['name'] }}</td>
                                <td class="text-center">{{ $category['count'] }}</td>
                                <td class="text-right">{{ number_format($category['debit'], 0, ',', '.') }}</td>
                                <td class="text-right">{{ number_format($category['credit'], 0, ',', '.') }}</td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

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