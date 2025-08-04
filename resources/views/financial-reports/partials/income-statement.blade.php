{{-- Income Statement Partial --}}
@if(isset($data) && $data)
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
                    <p>Periode: {{ $data['period']['description'] ?? 'Periode tidak tersedia' }}</p>
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
                        @foreach($data['revenues'] ?? [] as $accountName => $amount)
                            <tr>
                                <td class="pl-4">{{ $accountName }}</td>
                                <td class="text-right">{{ format_currency($amount) }}</td>
                            </tr>
                            @php $totalRevenue += $amount; @endphp
                        @endforeach
                        <tr class="font-weight-bold">
                            <td class="text-right">Total Pendapatan</td>
                            <td class="text-right">{{ format_currency($data['total_revenue'] ?? $totalRevenue) }}</td>
                        </tr>

                        {{-- BEBAN OPERASIONAL --}}
                        <tr class="bg-info text-white">
                            <td><strong>BEBAN OPERASIONAL</strong></td>
                            <td></td>
                        </tr>
                        @php $totalExpenses = 0; @endphp
                        @foreach($data['expenses'] ?? [] as $accountName => $amount)
                            <tr>
                                <td class="pl-4">{{ $accountName }}</td>
                                <td class="text-right">{{ format_currency($amount) }}</td>
                            </tr>
                            @php $totalExpenses += $amount; @endphp
                        @endforeach
                        <tr class="font-weight-bold">
                            <td class="text-right">Total Beban Operasional</td>
                            <td class="text-right">{{ format_currency($data['total_expenses'] ?? $totalExpenses) }}</td>
                        </tr>

                        {{-- LABA BERSIH --}}
                        @php 
                            $netIncome = $data['net_income'] ?? ($totalRevenue - $totalExpenses);
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
                            <span class="info-box-number">{{ format_currency($data['total_revenue'] ?? 0) }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-box">
                        <span class="info-box-icon bg-warning"><i class="fas fa-arrow-down"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Beban</span>
                            <span class="info-box-number">{{ format_currency($data['total_expenses'] ?? 0) }}</span>
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
                                @php $totalRev = $data['total_revenue'] ?? 0; @endphp
                                Margin: {{ $totalRev > 0 ? number_format(abs($netIncome / $totalRev * 100), 2) : 0 }}%
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <small class="text-muted">
                Laporan dibuat pada: {{ now()->format('d F Y H:i:s') }}
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