{{-- Cash Flow Partial --}}
@if(isset($reportData) && $reportData)
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-exchange-alt"></i> Laporan Arus Kas
            </h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" onclick="window.print()">
                    <i class="fas fa-print"></i> Cetak
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-12 text-center">
                    <h4>{{ company_info('name') }}</h4>
                    <h5>LAPORAN ARUS KAS</h5>
                    <p>Periode {{ \Carbon\Carbon::parse($report->period_start)->format('d F Y') }} s/d {{ \Carbon\Carbon::parse($report->period_end)->format('d F Y') }}</p>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th width="70%">Keterangan</th>
                            <th width="30%" class="text-right">Jumlah ({{ setting('default_currency', 'Rp') }})</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- ARUS KAS DARI AKTIVITAS OPERASI --}}
                        <tr class="bg-info text-white">
                            <td><strong>ARUS KAS DARI AKTIVITAS OPERASI</strong></td>
                            <td></td>
                        </tr>
                        
                        {{-- Penerimaan Kas dari Operasi --}}
                        <tr class="bg-light">
                            <td class="pl-3"><strong>Penerimaan Kas dari Operasi:</strong></td>
                            <td></td>
                        </tr>
                        @php $totalOperatingInflows = 0; @endphp
                        @foreach($reportData['operating_inflows'] ?? [] as $item)
                            <tr>
                                <td class="pl-4">{{ $item['description'] }}</td>
                                <td class="text-right">{{ format_currency($item['amount']) }}</td>
                            </tr>
                            @php $totalOperatingInflows += $item['amount']; @endphp
                        @endforeach
                        <tr class="font-weight-bold">
                            <td class="text-right pl-3">Total Penerimaan Kas dari Operasi</td>
                            <td class="text-right">{{ format_currency($totalOperatingInflows) }}</td>
                        </tr>

                        {{-- Pengeluaran Kas untuk Operasi --}}
                        <tr class="bg-light">
                            <td class="pl-3"><strong>Pengeluaran Kas untuk Operasi:</strong></td>
                            <td></td>
                        </tr>
                        @php $totalOperatingOutflows = 0; @endphp
                        @foreach($reportData['operating_outflows'] ?? [] as $item)
                            <tr>
                                <td class="pl-4">{{ $item['description'] }}</td>
                                <td class="text-right">({{ format_currency($item['amount']) }})</td>
                            </tr>
                            @php $totalOperatingOutflows += $item['amount']; @endphp
                        @endforeach
                        <tr class="font-weight-bold">
                            <td class="text-right pl-3">Total Pengeluaran Kas untuk Operasi</td>
                            <td class="text-right">({{ format_currency($totalOperatingOutflows) }})</td>
                        </tr>

                        {{-- Net Operating Cash Flow --}}
                        @php $netOperatingCashFlow = $totalOperatingInflows - $totalOperatingOutflows; @endphp
                        <tr class="font-weight-bold bg-info text-white">
                            <td class="text-right">Arus Kas Bersih dari Aktivitas Operasi</td>
                            <td class="text-right">{{ format_currency($netOperatingCashFlow) }}</td>
                        </tr>

                        {{-- ARUS KAS DARI AKTIVITAS INVESTASI --}}
                        <tr class="bg-warning text-dark">
                            <td><strong>ARUS KAS DARI AKTIVITAS INVESTASI</strong></td>
                            <td></td>
                        </tr>
                        
                        {{-- Penerimaan Kas dari Investasi --}}
                        <tr class="bg-light">
                            <td class="pl-3"><strong>Penerimaan Kas dari Investasi:</strong></td>
                            <td></td>
                        </tr>
                        @php $totalInvestingInflows = 0; @endphp
                        @foreach($reportData['investing_inflows'] ?? [] as $item)
                            <tr>
                                <td class="pl-4">{{ $item['description'] }}</td>
                                <td class="text-right">{{ format_currency($item['amount']) }}</td>
                            </tr>
                            @php $totalInvestingInflows += $item['amount']; @endphp
                        @endforeach
                        <tr class="font-weight-bold">
                            <td class="text-right pl-3">Total Penerimaan Kas dari Investasi</td>
                            <td class="text-right">{{ format_currency($totalInvestingInflows) }}</td>
                        </tr>

                        {{-- Pengeluaran Kas untuk Investasi --}}
                        <tr class="bg-light">
                            <td class="pl-3"><strong>Pengeluaran Kas untuk Investasi:</strong></td>
                            <td></td>
                        </tr>
                        @php $totalInvestingOutflows = 0; @endphp
                        @foreach($reportData['investing_outflows'] ?? [] as $item)
                            <tr>
                                <td class="pl-4">{{ $item['description'] }}</td>
                                <td class="text-right">({{ format_currency($item['amount']) }})</td>
                            </tr>
                            @php $totalInvestingOutflows += $item['amount']; @endphp
                        @endforeach
                        <tr class="font-weight-bold">
                            <td class="text-right pl-3">Total Pengeluaran Kas untuk Investasi</td>
                            <td class="text-right">({{ format_currency($totalInvestingOutflows) }})</td>
                        </tr>

                        {{-- Net Investing Cash Flow --}}
                        @php $netInvestingCashFlow = $totalInvestingInflows - $totalInvestingOutflows; @endphp
                        <tr class="font-weight-bold bg-warning text-dark">
                            <td class="text-right">Arus Kas Bersih dari Aktivitas Investasi</td>
                            <td class="text-right">{{ format_currency($netInvestingCashFlow) }}</td>
                        </tr>

                        {{-- ARUS KAS DARI AKTIVITAS PENDANAAN --}}
                        <tr class="bg-success text-white">
                            <td><strong>ARUS KAS DARI AKTIVITAS PENDANAAN</strong></td>
                            <td></td>
                        </tr>
                        
                        {{-- Penerimaan Kas dari Pendanaan --}}
                        <tr class="bg-light">
                            <td class="pl-3"><strong>Penerimaan Kas dari Pendanaan:</strong></td>
                            <td></td>
                        </tr>
                        @php $totalFinancingInflows = 0; @endphp
                        @foreach($reportData['financing_inflows'] ?? [] as $item)
                            <tr>
                                <td class="pl-4">{{ $item['description'] }}</td>
                                <td class="text-right">{{ format_currency($item['amount']) }}</td>
                            </tr>
                            @php $totalFinancingInflows += $item['amount']; @endphp
                        @endforeach
                        <tr class="font-weight-bold">
                            <td class="text-right pl-3">Total Penerimaan Kas dari Pendanaan</td>
                            <td class="text-right">{{ format_currency($totalFinancingInflows) }}</td>
                        </tr>

                        {{-- Pengeluaran Kas untuk Pendanaan --}}
                        <tr class="bg-light">
                            <td class="pl-3"><strong>Pengeluaran Kas untuk Pendanaan:</strong></td>
                            <td></td>
                        </tr>
                        @php $totalFinancingOutflows = 0; @endphp
                        @foreach($reportData['financing_outflows'] ?? [] as $item)
                            <tr>
                                <td class="pl-4">{{ $item['description'] }}</td>
                                <td class="text-right">({{ format_currency($item['amount']) }})</td>
                            </tr>
                            @php $totalFinancingOutflows += $item['amount']; @endphp
                        @endforeach
                        <tr class="font-weight-bold">
                            <td class="text-right pl-3">Total Pengeluaran Kas untuk Pendanaan</td>
                            <td class="text-right">({{ format_currency($totalFinancingOutflows) }})</td>
                        </tr>

                        {{-- Net Financing Cash Flow --}}
                        @php $netFinancingCashFlow = $totalFinancingInflows - $totalFinancingOutflows; @endphp
                        <tr class="font-weight-bold bg-success text-white">
                            <td class="text-right">Arus Kas Bersih dari Aktivitas Pendanaan</td>
                            <td class="text-right">{{ format_currency($netFinancingCashFlow) }}</td>
                        </tr>

                        {{-- KENAIKAN/PENURUNAN KAS BERSIH --}}
                        @php $netCashFlow = $netOperatingCashFlow + $netInvestingCashFlow + $netFinancingCashFlow; @endphp
                        <tr class="font-weight-bold bg-secondary text-white">
                            <td class="text-right">Kenaikan (Penurunan) Kas Bersih</td>
                            <td class="text-right">{{ format_currency($netCashFlow) }}</td>
                        </tr>

                        {{-- SALDO KAS --}}
                        @php 
                            $beginningCash = $reportData['beginning_cash'] ?? 0;
                            $endingCash = $beginningCash + $netCashFlow;
                        @endphp
                        <tr>
                            <td class="text-right">Kas dan Setara Kas Awal Periode</td>
                            <td class="text-right">{{ format_currency($beginningCash) }}</td>
                        </tr>
                        <tr class="font-weight-bold bg-primary text-white">
                            <td class="text-right">Kas dan Setara Kas Akhir Periode</td>
                            <td class="text-right">{{ format_currency($endingCash) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Summary Statistics --}}
            <div class="row mt-4">
                <div class="col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-info"><i class="fas fa-cogs"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Arus Kas Operasi</span>
                            <span class="info-box-number">{{ format_currency($netOperatingCashFlow) }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-warning"><i class="fas fa-chart-line"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Arus Kas Investasi</span>
                            <span class="info-box-number">{{ format_currency($netInvestingCashFlow) }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-success"><i class="fas fa-hand-holding-usd"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Arus Kas Pendanaan</span>
                            <span class="info-box-number">{{ format_currency($netFinancingCashFlow) }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-primary"><i class="fas fa-coins"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Kas Akhir Periode</span>
                            <span class="info-box-number">{{ format_currency($endingCash) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Cash Flow Analysis --}}
            <div class="row mt-3">
                <div class="col-12">
                    @php 
                        $cashFlowHealth = 'warning';
                        $cashFlowMessage = 'Netral';
                        
                        if ($netCashFlow > 0) {
                            $cashFlowHealth = 'success';
                            $cashFlowMessage = 'Positif - Kas meningkat';
                        } elseif ($netCashFlow < 0) {
                            $cashFlowHealth = 'danger';
                            $cashFlowMessage = 'Negatif - Kas menurun';
                        }
                    @endphp
                    <div class="alert alert-{{ $cashFlowHealth }}">
                        <h5>
                            <i class="icon fas {{ $cashFlowHealth === 'success' ? 'fa-arrow-up' : ($cashFlowHealth === 'danger' ? 'fa-arrow-down' : 'fa-minus') }}"></i>
                            Analisis Arus Kas: {{ $cashFlowMessage }}
                        </h5>
                        <p class="mb-0">
                            Perubahan kas bersih: {{ format_currency($netCashFlow) }}
                            @if($beginningCash > 0)
                                ({{ number_format(($netCashFlow / $beginningCash) * 100, 1) }}% dari kas awal)
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <small class="text-muted">
                Laporan dibuat pada: {{ now()->format('d F Y H:i:s') }} | 
                Status: <span class="badge {{ $report->getStatusBadgeClass() }}">{{ $report->getStatusLabel() }}</span>
            </small>
        </div>
    </div>
@else
    <div class="alert alert-warning">
        <h5><i class="icon fas fa-exclamation-triangle"></i> Data Belum Tersedia!</h5>
        <p>Data arus kas belum di-generate. Silakan generate data terlebih dahulu.</p>
        @if($report->status === 'draft')
            <a href="{{ route('financial-reports.edit', $report) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit & Generate
            </a>
        @endif
    </div>
@endif