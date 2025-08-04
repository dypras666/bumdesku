{{-- Simple Cash Flow Statement Template for PDF/Excel Export --}}
<div class="simple-report">
    <!-- Report Header -->
    <div class="report-header">
        <div class="company-name">{{ company_info('name') }}</div>
        <div class="company-address">{{ company_info('address') }}</div>
        <div class="report-title">Laporan Arus Kas</div>
        <div class="report-period">Periode {{ $periodStart->format('d F Y') }} s/d {{ $periodEnd->format('d F Y') }}</div>
    </div>

    <!-- Cash Flow Table -->
    <table class="report-table">
        <thead>
            <tr>
                <th style="width: 70%;">KETERANGAN</th>
                <th style="width: 30%;">JUMLAH (Rp)</th>
            </tr>
        </thead>
        <tbody>
            <!-- ARUS KAS DARI AKTIVITAS OPERASI -->
            <tr class="section-header">
                <td colspan="2">ARUS KAS DARI AKTIVITAS OPERASI</td>
            </tr>
            
            <!-- Penerimaan Kas dari Operasi -->
            <tr class="subsection-header">
                <td>Penerimaan Kas dari Operasi</td>
                <td></td>
            </tr>
            @php $totalOperatingInflows = 0; @endphp
            @if(isset($reportData['operating_activities']))
                @foreach($reportData['operating_activities']->where('debit', '>', 0) as $activity)
                    <tr>
                        <td class="indent-1">{{ $activity->description ?? 'Penerimaan Operasional' }}</td>
                        <td class="text-right">{{ number_format($activity->debit, 0, ',', '.') }}</td>
                    </tr>
                    @php $totalOperatingInflows += $activity->debit; @endphp
                @endforeach
            @endif
            @if($totalOperatingInflows == 0)
                <tr>
                    <td class="indent-1 no-data">Tidak ada penerimaan kas operasi</td>
                    <td class="text-right">-</td>
                </tr>
            @endif
            <tr class="total-row">
                <td class="text-right">Total Penerimaan Kas Operasi</td>
                <td class="text-right">{{ number_format($totalOperatingInflows, 0, ',', '.') }}</td>
            </tr>

            <!-- Pengeluaran Kas untuk Operasi -->
            <tr class="subsection-header">
                <td>Pengeluaran Kas untuk Operasi</td>
                <td></td>
            </tr>
            @php $totalOperatingOutflows = 0; @endphp
            @if(isset($reportData['operating_activities']))
                @foreach($reportData['operating_activities']->where('credit', '>', 0) as $activity)
                    <tr>
                        <td class="indent-1">{{ $activity->description ?? 'Pengeluaran Operasional' }}</td>
                        <td class="text-right">({{ number_format($activity->credit, 0, ',', '.') }})</td>
                    </tr>
                    @php $totalOperatingOutflows += $activity->credit; @endphp
                @endforeach
            @endif
            @if($totalOperatingOutflows == 0)
                <tr>
                    <td class="indent-1 no-data">Tidak ada pengeluaran kas operasi</td>
                    <td class="text-right">-</td>
                </tr>
            @endif
            <tr class="total-row">
                <td class="text-right">Total Pengeluaran Kas Operasi</td>
                <td class="text-right">({{ number_format($totalOperatingOutflows, 0, ',', '.') }})</td>
            </tr>

            <!-- Arus Kas Bersih dari Aktivitas Operasi -->
            @php $netOperatingCashFlow = $totalOperatingInflows - $totalOperatingOutflows; @endphp
            <tr class="grand-total-row">
                <td class="text-right">Arus Kas Bersih dari Aktivitas Operasi</td>
                <td class="text-right">{{ number_format($netOperatingCashFlow, 0, ',', '.') }}</td>
            </tr>

            <!-- ARUS KAS DARI AKTIVITAS INVESTASI -->
            <tr class="section-header">
                <td colspan="2">ARUS KAS DARI AKTIVITAS INVESTASI</td>
            </tr>

            <!-- Penerimaan Kas dari Investasi -->
            <tr class="subsection-header">
                <td>Penerimaan Kas dari Investasi</td>
                <td></td>
            </tr>
            @php $totalInvestingInflows = 0; @endphp
            @if(isset($reportData['investing_activities']))
                @foreach($reportData['investing_activities']->where('debit', '>', 0) as $activity)
                    <tr>
                        <td class="indent-1">{{ $activity->description ?? 'Penerimaan Investasi' }}</td>
                        <td class="text-right">{{ number_format($activity->debit, 0, ',', '.') }}</td>
                    </tr>
                    @php $totalInvestingInflows += $activity->debit; @endphp
                @endforeach
            @endif
            @if($totalInvestingInflows == 0)
                <tr>
                    <td class="indent-1 no-data">Tidak ada penerimaan kas investasi</td>
                    <td class="text-right">-</td>
                </tr>
            @endif
            <tr class="total-row">
                <td class="text-right">Total Penerimaan Kas Investasi</td>
                <td class="text-right">{{ number_format($totalInvestingInflows, 0, ',', '.') }}</td>
            </tr>

            <!-- Pengeluaran Kas untuk Investasi -->
            <tr class="subsection-header">
                <td>Pengeluaran Kas untuk Investasi</td>
                <td></td>
            </tr>
            @php $totalInvestingOutflows = 0; @endphp
            @if(isset($reportData['investing_activities']))
                @foreach($reportData['investing_activities']->where('credit', '>', 0) as $activity)
                    <tr>
                        <td class="indent-1">{{ $activity->description ?? 'Pengeluaran Investasi' }}</td>
                        <td class="text-right">({{ number_format($activity->credit, 0, ',', '.') }})</td>
                    </tr>
                    @php $totalInvestingOutflows += $activity->credit; @endphp
                @endforeach
            @endif
            @if($totalInvestingOutflows == 0)
                <tr>
                    <td class="indent-1 no-data">Tidak ada pengeluaran kas investasi</td>
                    <td class="text-right">-</td>
                </tr>
            @endif
            <tr class="total-row">
                <td class="text-right">Total Pengeluaran Kas Investasi</td>
                <td class="text-right">({{ number_format($totalInvestingOutflows, 0, ',', '.') }})</td>
            </tr>

            <!-- Arus Kas Bersih dari Aktivitas Investasi -->
            @php $netInvestingCashFlow = $totalInvestingInflows - $totalInvestingOutflows; @endphp
            <tr class="grand-total-row">
                <td class="text-right">Arus Kas Bersih dari Aktivitas Investasi</td>
                <td class="text-right">{{ number_format($netInvestingCashFlow, 0, ',', '.') }}</td>
            </tr>

            <!-- ARUS KAS DARI AKTIVITAS PENDANAAN -->
            <tr class="section-header">
                <td colspan="2">ARUS KAS DARI AKTIVITAS PENDANAAN</td>
            </tr>

            <!-- Penerimaan Kas dari Pendanaan -->
            <tr class="subsection-header">
                <td>Penerimaan Kas dari Pendanaan</td>
                <td></td>
            </tr>
            @php $totalFinancingInflows = 0; @endphp
            @if(isset($reportData['financing_activities']))
                @foreach($reportData['financing_activities']->where('debit', '>', 0) as $activity)
                    <tr>
                        <td class="indent-1">{{ $activity->description ?? 'Penerimaan Pendanaan' }}</td>
                        <td class="text-right">{{ number_format($activity->debit, 0, ',', '.') }}</td>
                    </tr>
                    @php $totalFinancingInflows += $activity->debit; @endphp
                @endforeach
            @endif
            @if($totalFinancingInflows == 0)
                <tr>
                    <td class="indent-1 no-data">Tidak ada penerimaan kas pendanaan</td>
                    <td class="text-right">-</td>
                </tr>
            @endif
            <tr class="total-row">
                <td class="text-right">Total Penerimaan Kas Pendanaan</td>
                <td class="text-right">{{ number_format($totalFinancingInflows, 0, ',', '.') }}</td>
            </tr>

            <!-- Pengeluaran Kas untuk Pendanaan -->
            <tr class="subsection-header">
                <td>Pengeluaran Kas untuk Pendanaan</td>
                <td></td>
            </tr>
            @php $totalFinancingOutflows = 0; @endphp
            @if(isset($reportData['financing_activities']))
                @foreach($reportData['financing_activities']->where('credit', '>', 0) as $activity)
                    <tr>
                        <td class="indent-1">{{ $activity->description ?? 'Pengeluaran Pendanaan' }}</td>
                        <td class="text-right">({{ number_format($activity->credit, 0, ',', '.') }})</td>
                    </tr>
                    @php $totalFinancingOutflows += $activity->credit; @endphp
                @endforeach
            @endif
            @if($totalFinancingOutflows == 0)
                <tr>
                    <td class="indent-1 no-data">Tidak ada pengeluaran kas pendanaan</td>
                    <td class="text-right">-</td>
                </tr>
            @endif
            <tr class="total-row">
                <td class="text-right">Total Pengeluaran Kas Pendanaan</td>
                <td class="text-right">({{ number_format($totalFinancingOutflows, 0, ',', '.') }})</td>
            </tr>

            <!-- Arus Kas Bersih dari Aktivitas Pendanaan -->
            @php $netFinancingCashFlow = $totalFinancingInflows - $totalFinancingOutflows; @endphp
            <tr class="grand-total-row">
                <td class="text-right">Arus Kas Bersih dari Aktivitas Pendanaan</td>
                <td class="text-right">{{ number_format($netFinancingCashFlow, 0, ',', '.') }}</td>
            </tr>

            <!-- KENAIKAN/PENURUNAN KAS BERSIH -->
            @php $netCashChange = $netOperatingCashFlow + $netInvestingCashFlow + $netFinancingCashFlow; @endphp
            <tr class="grand-total-row" style="border-top: 3px double #000;">
                <td class="text-right">{{ $netCashChange >= 0 ? 'KENAIKAN' : 'PENURUNAN' }} KAS BERSIH</td>
                <td class="text-right">{{ number_format($netCashChange, 0, ',', '.') }}</td>
            </tr>

            <!-- SALDO KAS -->
            @php 
                $beginningCash = isset($reportData['beginning_cash']) ? $reportData['beginning_cash'] : 0;
                $endingCash = $beginningCash + $netCashChange;
            @endphp
            <tr>
                <td class="text-right">Saldo Kas Awal Periode</td>
                <td class="text-right">{{ number_format($beginningCash, 0, ',', '.') }}</td>
            </tr>
            <tr class="grand-total-row" style="border-bottom: 3px double #000;">
                <td class="text-right">SALDO KAS AKHIR PERIODE</td>
                <td class="text-right">{{ number_format($endingCash, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Summary Section -->
    <div class="summary-section">
        <div class="summary-title">Ringkasan Arus Kas</div>
        <div class="summary-item">
            <span>Arus Kas dari Aktivitas Operasi:</span>
            <span>Rp {{ number_format($netOperatingCashFlow, 0, ',', '.') }}</span>
        </div>
        <div class="summary-item">
            <span>Arus Kas dari Aktivitas Investasi:</span>
            <span>Rp {{ number_format($netInvestingCashFlow, 0, ',', '.') }}</span>
        </div>
        <div class="summary-item">
            <span>Arus Kas dari Aktivitas Pendanaan:</span>
            <span>Rp {{ number_format($netFinancingCashFlow, 0, ',', '.') }}</span>
        </div>
        <div class="summary-item" style="border-top: 1px solid #000; padding-top: 5px; font-weight: bold;">
            <span>{{ $netCashChange >= 0 ? 'Kenaikan' : 'Penurunan' }} Kas Bersih:</span>
            <span>Rp {{ number_format($netCashChange, 0, ',', '.') }}</span>
        </div>
        <div class="summary-item" style="font-weight: bold;">
            <span>Saldo Kas Akhir Periode:</span>
            <span>Rp {{ number_format($endingCash, 0, ',', '.') }}</span>
        </div>
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