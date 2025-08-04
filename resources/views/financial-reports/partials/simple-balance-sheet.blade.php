{{-- Simple Balance Sheet Template for PDF/Excel Export --}}
<div class="simple-report">
    <!-- Report Header -->
    <div class="report-header">
        <div class="company-name">{{ company_info('name') }}</div>
        <div class="company-address">{{ company_info('address') }}</div>
        <div class="report-title">Neraca</div>
        <div class="report-period">Per {{ $asOfDate->format('d F Y') }}</div>
    </div>

    <!-- Balance Sheet Table -->
    <table class="report-table">
        <thead>
            <tr>
                <th style="width: 70%;">KETERANGAN</th>
                <th style="width: 30%;">JUMLAH (Rp)</th>
            </tr>
        </thead>
        <tbody>
            <!-- ASET -->
            <tr class="section-header">
                <td colspan="2">ASET</td>
            </tr>
            
            <!-- Aset Lancar -->
            <tr class="subsection-header">
                <td>ASET LANCAR</td>
                <td></td>
            </tr>
            @php $totalCurrentAssets = 0; @endphp
            @if(isset($reportData['current_assets']) && is_array($reportData['current_assets']) && count($reportData['current_assets']) > 0)
                @foreach($reportData['current_assets'] as $account)
                    @if(is_array($account) && isset($account['account']) && isset($account['amount']))
                        <tr>
                            <td class="indent-1">{{ $account['account'] }}</td>
                            <td class="text-right">{{ number_format($account['amount'], 0, ',', '.') }}</td>
                        </tr>
                        @php $totalCurrentAssets += $account['amount']; @endphp
                    @endif
                @endforeach
            @else
                <tr>
                    <td class="indent-1 no-data">Tidak ada data aset lancar</td>
                    <td class="text-right">-</td>
                </tr>
            @endif
            <tr class="total-row">
                <td class="text-right">Total Aset Lancar</td>
                <td class="text-right">{{ number_format($totalCurrentAssets, 0, ',', '.') }}</td>
            </tr>

            <!-- Aset Tetap -->
            <tr class="subsection-header">
                <td>ASET TETAP</td>
                <td></td>
            </tr>
            @php $totalFixedAssets = 0; @endphp
            @if(isset($reportData['fixed_assets']) && is_array($reportData['fixed_assets']) && count($reportData['fixed_assets']) > 0)
                @foreach($reportData['fixed_assets'] as $account)
                    @if(is_array($account) && isset($account['account']) && isset($account['amount']))
                        <tr>
                            <td class="indent-1">{{ $account['account'] }}</td>
                            <td class="text-right">{{ number_format($account['amount'], 0, ',', '.') }}</td>
                        </tr>
                        @php $totalFixedAssets += $account['amount']; @endphp
                    @endif
                @endforeach
            @else
                <tr>
                    <td class="indent-1 no-data">Tidak ada data aset tetap</td>
                    <td class="text-right">-</td>
                </tr>
            @endif
            <tr class="total-row">
                <td class="text-right">Total Aset Tetap</td>
                <td class="text-right">{{ number_format($totalFixedAssets, 0, ',', '.') }}</td>
            </tr>

            <!-- Aset Lainnya -->
            @if(isset($reportData['other_assets']) && is_array($reportData['other_assets']) && count($reportData['other_assets']) > 0)
                <tr class="subsection-header">
                    <td>ASET LAINNYA</td>
                    <td></td>
                </tr>
                @php $totalOtherAssets = 0; @endphp
                @foreach($reportData['other_assets'] as $account)
                    @if(is_array($account) && isset($account['account']) && isset($account['amount']))
                        <tr>
                            <td class="indent-1">{{ $account['account'] }}</td>
                            <td class="text-right">{{ number_format($account['amount'], 0, ',', '.') }}</td>
                        </tr>
                        @php $totalOtherAssets += $account['amount']; @endphp
                    @endif
                @endforeach
                <tr class="total-row">
                    <td class="text-right">Total Aset Lainnya</td>
                    <td class="text-right">{{ number_format($totalOtherAssets, 0, ',', '.') }}</td>
                </tr>
            @else
                @php $totalOtherAssets = 0; @endphp
            @endif

            <!-- Total Aset -->
            @php $totalAssets = $totalCurrentAssets + $totalFixedAssets + $totalOtherAssets; @endphp
            <tr class="grand-total-row">
                <td class="text-right">TOTAL ASET</td>
                <td class="text-right">{{ number_format($totalAssets, 0, ',', '.') }}</td>
            </tr>

            <!-- KEWAJIBAN -->
            <tr class="section-header">
                <td colspan="2">KEWAJIBAN</td>
            </tr>

            <!-- Kewajiban Lancar -->
            <tr class="subsection-header">
                <td>KEWAJIBAN LANCAR</td>
                <td></td>
            </tr>
            @php $totalCurrentLiabilities = 0; @endphp
            @if(isset($reportData['current_liabilities']) && is_array($reportData['current_liabilities']) && count($reportData['current_liabilities']) > 0)
                @foreach($reportData['current_liabilities'] as $account)
                    @if(is_array($account) && isset($account['account']) && isset($account['amount']))
                        <tr>
                            <td class="indent-1">{{ $account['account'] }}</td>
                            <td class="text-right">{{ number_format($account['amount'], 0, ',', '.') }}</td>
                        </tr>
                        @php $totalCurrentLiabilities += $account['amount']; @endphp
                    @endif
                @endforeach
            @else
                <tr>
                    <td class="indent-1 no-data">Tidak ada data kewajiban lancar</td>
                    <td class="text-right">-</td>
                </tr>
            @endif
            <tr class="total-row">
                <td class="text-right">Total Kewajiban Lancar</td>
                <td class="text-right">{{ number_format($totalCurrentLiabilities, 0, ',', '.') }}</td>
            </tr>

            <!-- Kewajiban Jangka Panjang -->
            <tr class="subsection-header">
                <td>KEWAJIBAN JANGKA PANJANG</td>
                <td></td>
            </tr>
            @php $totalLongTermLiabilities = 0; @endphp
            @if(isset($reportData['long_term_liabilities']) && is_array($reportData['long_term_liabilities']) && count($reportData['long_term_liabilities']) > 0)
                @foreach($reportData['long_term_liabilities'] as $account)
                    @if(is_array($account) && isset($account['account']) && isset($account['amount']))
                        <tr>
                            <td class="indent-1">{{ $account['account'] }}</td>
                            <td class="text-right">{{ number_format($account['amount'], 0, ',', '.') }}</td>
                        </tr>
                        @php $totalLongTermLiabilities += $account['amount']; @endphp
                    @endif
                @endforeach
            @else
                <tr>
                    <td class="indent-1 no-data">Tidak ada data kewajiban jangka panjang</td>
                    <td class="text-right">-</td>
                </tr>
            @endif
            <tr class="total-row">
                <td class="text-right">Total Kewajiban Jangka Panjang</td>
                <td class="text-right">{{ number_format($totalLongTermLiabilities, 0, ',', '.') }}</td>
            </tr>

            <!-- Total Kewajiban -->
            @php $totalLiabilities = $totalCurrentLiabilities + $totalLongTermLiabilities; @endphp
            <tr class="grand-total-row">
                <td class="text-right">TOTAL KEWAJIBAN</td>
                <td class="text-right">{{ number_format($totalLiabilities, 0, ',', '.') }}</td>
            </tr>

            <!-- MODAL -->
            <tr class="section-header">
                <td colspan="2">MODAL</td>
            </tr>
            @php $totalEquity = 0; @endphp
            @if(isset($reportData['equity']) && is_array($reportData['equity']) && count($reportData['equity']) > 0)
                @foreach($reportData['equity'] as $account)
                    @if(is_array($account) && isset($account['account']) && isset($account['amount']))
                        <tr>
                            <td class="indent-1">{{ $account['account'] }}</td>
                            <td class="text-right">{{ number_format($account['amount'], 0, ',', '.') }}</td>
                        </tr>
                        @php $totalEquity += $account['amount']; @endphp
                    @endif
                @endforeach
            @else
                <tr>
                    <td class="indent-1 no-data">Tidak ada data modal</td>
                    <td class="text-right">-</td>
                </tr>
            @endif
            <tr class="grand-total-row">
                <td class="text-right">TOTAL MODAL</td>
                <td class="text-right">{{ number_format($totalEquity, 0, ',', '.') }}</td>
            </tr>

            <!-- Total Kewajiban & Modal -->
            <tr class="grand-total-row">
                <td class="text-right">TOTAL KEWAJIBAN & MODAL</td>
                <td class="text-right">{{ number_format($totalLiabilities + $totalEquity, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Balance Check -->
    @php 
        $balanceDifference = $totalAssets - ($totalLiabilities + $totalEquity);
        $isBalanced = abs($balanceDifference) < 0.01;
    @endphp
    <div class="balance-check {{ $isBalanced ? 'balanced' : 'unbalanced' }}">
        @if($isBalanced)
            ✓ NERACA SEIMBANG
        @else
            ⚠ NERACA TIDAK SEIMBANG - Selisih: Rp {{ number_format(abs($balanceDifference), 0, ',', '.') }}
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