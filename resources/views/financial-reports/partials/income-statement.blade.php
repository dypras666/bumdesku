{{-- Income Statement Partial --}}
@if(isset($reportData) && $reportData)
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-chart-line"></i> Laporan Laba Rugi
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
                    <h5>LAPORAN LABA RUGI</h5>
                    <p>Periode: {{ \Carbon\Carbon::parse($report->period_start)->format('d F Y') }} - {{ \Carbon\Carbon::parse($report->period_end)->format('d F Y') }}</p>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="bg-light">
                        <tr>
                            <th width="60%">Keterangan</th>
                            <th width="40%" class="text-right">Jumlah ({{ setting('default_currency', 'Rp') }})</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- PENDAPATAN --}}
                        <tr class="bg-primary text-white">
                            <td><strong>PENDAPATAN</strong></td>
                            <td></td>
                        </tr>
                        @php $totalRevenue = 0; @endphp
                        @foreach($reportData['revenue'] ?? [] as $account)
                            <tr>
                                <td class="pl-4">{{ $account['account_name'] }}</td>
                                <td class="text-right">{{ format_currency($account['amount']) }}</td>
                            </tr>
                            @php $totalRevenue += $account['amount']; @endphp
                        @endforeach
                        <tr class="font-weight-bold">
                            <td class="text-right">Total Pendapatan</td>
                            <td class="text-right">{{ format_currency($totalRevenue) }}</td>
                        </tr>

                        {{-- BEBAN POKOK PENJUALAN --}}
                        @if(isset($reportData['cogs']) && count($reportData['cogs']) > 0)
                            <tr class="bg-warning">
                                <td><strong>BEBAN POKOK PENJUALAN</strong></td>
                                <td></td>
                            </tr>
                            @php $totalCogs = 0; @endphp
                            @foreach($reportData['cogs'] as $account)
                                <tr>
                                    <td class="pl-4">{{ $account['account_name'] }}</td>
                                    <td class="text-right">{{ format_currency($account['amount']) }}</td>
                                </tr>
                                @php $totalCogs += $account['amount']; @endphp
                            @endforeach
                            <tr class="font-weight-bold">
                                <td class="text-right">Total Beban Pokok Penjualan</td>
                                <td class="text-right">{{ format_currency($totalCogs) }}</td>
                            </tr>
                            
                            {{-- LABA KOTOR --}}
                            @php $grossProfit = $totalRevenue - $totalCogs; @endphp
                            <tr class="font-weight-bold bg-light">
                                <td class="text-right">LABA KOTOR</td>
                                <td class="text-right">{{ format_currency($grossProfit) }}</td>
                            </tr>
                        @endif

                        {{-- BEBAN OPERASIONAL --}}
                        <tr class="bg-info text-white">
                            <td><strong>BEBAN OPERASIONAL</strong></td>
                            <td></td>
                        </tr>
                        @php $totalExpenses = 0; @endphp
                        @foreach($reportData['expenses'] ?? [] as $account)
                            <tr>
                                <td class="pl-4">{{ $account['account_name'] }}</td>
                                <td class="text-right">{{ format_currency($account['amount']) }}</td>
                            </tr>
                            @php $totalExpenses += $account['amount']; @endphp
                        @endforeach
                        <tr class="font-weight-bold">
                            <td class="text-right">Total Beban Operasional</td>
                            <td class="text-right">{{ format_currency($totalExpenses) }}</td>
                        </tr>

                        {{-- PENDAPATAN/BEBAN LAIN-LAIN --}}
                        @if(isset($reportData['other_income']) && count($reportData['other_income']) > 0)
                            <tr class="bg-secondary text-white">
                                <td><strong>PENDAPATAN LAIN-LAIN</strong></td>
                                <td></td>
                            </tr>
                            @php $totalOtherIncome = 0; @endphp
                            @foreach($reportData['other_income'] as $account)
                                <tr>
                                    <td class="pl-4">{{ $account['account_name'] }}</td>
                                    <td class="text-right">{{ format_currency($account['amount']) }}</td>
                                </tr>
                                @php $totalOtherIncome += $account['amount']; @endphp
                            @endforeach
                            <tr class="font-weight-bold">
                                <td class="text-right">Total Pendapatan Lain-lain</td>
                                <td class="text-right">{{ format_currency($totalOtherIncome) }}</td>
                            </tr>
                        @endif

                        @if(isset($reportData['other_expenses']) && count($reportData['other_expenses']) > 0)
                            <tr class="bg-secondary text-white">
                                <td><strong>BEBAN LAIN-LAIN</strong></td>
                                <td></td>
                            </tr>
                            @php $totalOtherExpenses = 0; @endphp
                            @foreach($reportData['other_expenses'] as $account)
                                <tr>
                                    <td class="pl-4">{{ $account['account_name'] }}</td>
                                    <td class="text-right">{{ format_currency($account['amount']) }}</td>
                                </tr>
                                @php $totalOtherExpenses += $account['amount']; @endphp
                            @endforeach
                            <tr class="font-weight-bold">
                                <td class="text-right">Total Beban Lain-lain</td>
                                <td class="text-right">{{ format_currency($totalOtherExpenses) }}</td>
                            </tr>
                        @endif

                        {{-- LABA BERSIH --}}
                        @php 
                            $netIncome = $totalRevenue - $totalExpenses;
                            if (isset($totalOtherIncome)) $netIncome += $totalOtherIncome;
                            if (isset($totalOtherExpenses)) $netIncome -= $totalOtherExpenses;
                            if (isset($totalCogs)) $netIncome -= $totalCogs;
                        @endphp
                        <tr class="font-weight-bold {{ $netIncome >= 0 ? 'bg-success' : 'bg-danger' }} text-white">
                            <td class="text-right">{{ $netIncome >= 0 ? 'LABA BERSIH' : 'RUGI BERSIH' }}</td>
                            <td class="text-right">{{ format_currency(abs($netIncome)) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Summary Statistics --}}
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="info-box">
                        <span class="info-box-icon bg-info"><i class="fas fa-arrow-up"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Pendapatan</span>
                            <span class="info-box-number">{{ format_currency($totalRevenue) }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-box">
                        <span class="info-box-icon bg-warning"><i class="fas fa-arrow-down"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Beban</span>
                            <span class="info-box-number">{{ format_currency($totalExpenses) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="info-box">
                        <span class="info-box-icon {{ $netIncome >= 0 ? 'bg-success' : 'bg-danger' }}">
                            <i class="fas {{ $netIncome >= 0 ? 'fa-thumbs-up' : 'fa-thumbs-down' }}"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">{{ $netIncome >= 0 ? 'Laba Bersih' : 'Rugi Bersih' }}</span>
                            <span class="info-box-number">{{ format_currency(abs($netIncome)) }}</span>
                            <div class="progress">
                                <div class="progress-bar {{ $netIncome >= 0 ? 'bg-success' : 'bg-danger' }}"></div>
                            </div>
                            <span class="progress-description">
                                Margin: {{ $totalRevenue > 0 ? number_format(abs($netIncome / $totalRevenue * 100), 2) : 0 }}%
                            </span>
                        </div>
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
        <p>Data laporan laba rugi belum di-generate. Silakan generate data terlebih dahulu.</p>
        @if($report->status === 'draft')
            <a href="{{ route('financial-reports.edit', $report) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit & Generate
            </a>
        @endif
    </div>
@endif