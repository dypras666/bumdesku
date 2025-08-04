{{-- Balance Sheet Partial --}}
@if(isset($data) && $data)
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-balance-scale"></i> Neraca
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
                    <h5>NERACA</h5>
                    <p>Per {{ \Carbon\Carbon::parse($report->period_end)->format('d F Y') }}</p>
                </div>
            </div>

            <div class="row">
                {{-- ASET --}}
                <div class="col-md-6">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th colspan="2" class="text-center">ASET</th>
                                </tr>
                                <tr>
                                    <th width="70%">Keterangan</th>
                                    <th width="30%" class="text-right">Jumlah ({{ setting('default_currency', 'Rp') }})</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- ASET LANCAR --}}
                                <tr class="bg-info text-white">
                                    <td><strong>ASET LANCAR</strong></td>
                                    <td></td>
                                </tr>
                                @php $totalCurrentAssets = 0; @endphp
                                @foreach($data['current_assets'] ?? [] as $account)
                                    <tr>
                                        <td class="pl-3">{{ $account['account'] }}</td>
                                        <td class="text-right">{{ format_currency($account['amount']) }}</td>
                                    </tr>
                                    @php $totalCurrentAssets += $account['amount']; @endphp
                                @endforeach
                                <tr class="font-weight-bold">
                                    <td class="text-right">Total Aset Lancar</td>
                                    <td class="text-right">{{ format_currency($totalCurrentAssets) }}</td>
                                </tr>

                                {{-- ASET TETAP --}}
                                <tr class="bg-info text-white">
                                    <td><strong>ASET TETAP</strong></td>
                                    <td></td>
                                </tr>
                                @php $totalFixedAssets = 0; @endphp
                                @foreach($data['fixed_assets'] ?? [] as $account)
                                    <tr>
                                        <td class="pl-3">{{ $account['account'] }}</td>
                                        <td class="text-right">{{ format_currency($account['amount']) }}</td>
                                    </tr>
                                    @php $totalFixedAssets += $account['amount']; @endphp
                                @endforeach
                                <tr class="font-weight-bold">
                                    <td class="text-right">Total Aset Tetap</td>
                                    <td class="text-right">{{ format_currency($totalFixedAssets) }}</td>
                                </tr>

                                {{-- ASET LAIN-LAIN --}}
                                @if(isset($data['other_assets']) && count($data['other_assets']) > 0)
                                    <tr class="bg-info text-white">
                                        <td><strong>ASET LAIN-LAIN</strong></td>
                                        <td></td>
                                    </tr>
                                    @php $totalOtherAssets = 0; @endphp
                                    @foreach($data['other_assets'] as $account)
                                        <tr>
                                            <td class="pl-3">{{ $account['account'] }}</td>
                                            <td class="text-right">{{ format_currency($account['amount']) }}</td>
                                        </tr>
                                        @php $totalOtherAssets += $account['amount']; @endphp
                                    @endforeach
                                    <tr class="font-weight-bold">
                                        <td class="text-right">Total Aset Lain-lain</td>
                                        <td class="text-right">{{ format_currency($totalOtherAssets) }}</td>
                                    </tr>
                                @endif

                                {{-- TOTAL ASET --}}
                                @php 
                                    $totalAssets = $totalCurrentAssets + $totalFixedAssets;
                                    if (isset($totalOtherAssets)) $totalAssets += $totalOtherAssets;
                                @endphp
                                <tr class="font-weight-bold bg-primary text-white">
                                    <td class="text-right">TOTAL ASET</td>
                                    <td class="text-right">{{ format_currency($totalAssets) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- KEWAJIBAN & EKUITAS --}}
                <div class="col-md-6">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="bg-warning text-dark">
                                <tr>
                                    <th colspan="2" class="text-center">KEWAJIBAN & EKUITAS</th>
                                </tr>
                                <tr>
                                    <th width="70%">Keterangan</th>
                                    <th width="30%" class="text-right">Jumlah ({{ setting('default_currency', 'Rp') }})</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- KEWAJIBAN LANCAR --}}
                                <tr class="bg-danger text-white">
                                    <td><strong>KEWAJIBAN LANCAR</strong></td>
                                    <td></td>
                                </tr>
                                @php $totalCurrentLiabilities = 0; @endphp
                                @foreach($data['current_liabilities'] ?? [] as $account)
                                    <tr>
                                        <td class="pl-3">{{ $account['account'] }}</td>
                                        <td class="text-right">{{ format_currency($account['amount']) }}</td>
                                    </tr>
                                    @php $totalCurrentLiabilities += $account['amount']; @endphp
                                @endforeach
                                <tr class="font-weight-bold">
                                    <td class="text-right">Total Kewajiban Lancar</td>
                                    <td class="text-right">{{ format_currency($totalCurrentLiabilities) }}</td>
                                </tr>

                                {{-- KEWAJIBAN JANGKA PANJANG --}}
                                <tr class="bg-danger text-white">
                                    <td><strong>KEWAJIBAN JANGKA PANJANG</strong></td>
                                    <td></td>
                                </tr>
                                @php $totalLongTermLiabilities = 0; @endphp
                                @foreach($data['long_term_liabilities'] ?? [] as $account)
                                    <tr>
                                        <td class="pl-3">{{ $account['account'] }}</td>
                                        <td class="text-right">{{ format_currency($account['amount']) }}</td>
                                    </tr>
                                    @php $totalLongTermLiabilities += $account['amount']; @endphp
                                @endforeach
                                <tr class="font-weight-bold">
                                    <td class="text-right">Total Kewajiban Jangka Panjang</td>
                                    <td class="text-right">{{ format_currency($totalLongTermLiabilities) }}</td>
                                </tr>

                                {{-- TOTAL KEWAJIBAN --}}
                                @php $totalLiabilities = $totalCurrentLiabilities + $totalLongTermLiabilities; @endphp
                                <tr class="font-weight-bold bg-danger text-white">
                                    <td class="text-right">TOTAL KEWAJIBAN</td>
                                    <td class="text-right">{{ format_currency($totalLiabilities) }}</td>
                                </tr>

                                {{-- EKUITAS --}}
                                <tr class="bg-success text-white">
                                    <td><strong>EKUITAS</strong></td>
                                    <td></td>
                                </tr>
                                @php $totalEquity = 0; @endphp
                                @foreach($data['equity'] ?? [] as $account)
                                    <tr>
                                        <td class="pl-3">{{ $account['account'] }}</td>
                                        <td class="text-right">{{ format_currency($account['amount']) }}</td>
                                    </tr>
                                    @php $totalEquity += $account['amount']; @endphp
                                @endforeach
                                <tr class="font-weight-bold">
                                    <td class="text-right">Total Ekuitas</td>
                                    <td class="text-right">{{ format_currency($totalEquity) }}</td>
                                </tr>

                                {{-- TOTAL KEWAJIBAN & EKUITAS --}}
                                @php $totalLiabilitiesEquity = $totalLiabilities + $totalEquity; @endphp
                                <tr class="font-weight-bold bg-warning text-dark">
                                    <td class="text-right">TOTAL KEWAJIBAN & EKUITAS</td>
                                    <td class="text-right">{{ format_currency($totalLiabilitiesEquity) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Balance Check --}}
            <div class="row mt-3">
                <div class="col-12">
                    @php $isBalanced = abs($totalAssets - $totalLiabilitiesEquity) < 0.01; @endphp
                    <div class="alert {{ $isBalanced ? 'alert-success' : 'alert-danger' }}">
                        <h5>
                            <i class="icon fas {{ $isBalanced ? 'fa-check' : 'fa-exclamation-triangle' }}"></i>
                            Status Neraca: {{ $isBalanced ? 'SEIMBANG' : 'TIDAK SEIMBANG' }}
                        </h5>
                        <p class="mb-0">
                            Total Aset: {{ format_currency($totalAssets) }} | 
                            Total Kewajiban & Ekuitas: {{ format_currency($totalLiabilitiesEquity) }}
                            @if(!$isBalanced)
                                | Selisih: {{ format_currency(abs($totalAssets - $totalLiabilitiesEquity)) }}
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            {{-- Summary Statistics --}}
            <div class="row mt-4">
                <div class="col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-info"><i class="fas fa-coins"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Aset</span>
                            <span class="info-box-number">{{ format_currency($totalAssets) }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-danger"><i class="fas fa-credit-card"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Kewajiban</span>
                            <span class="info-box-number">{{ format_currency($totalLiabilities) }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-success"><i class="fas fa-chart-pie"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Ekuitas</span>
                            <span class="info-box-number">{{ format_currency($totalEquity) }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-warning"><i class="fas fa-percentage"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Debt to Equity</span>
                            <span class="info-box-number">
                                {{ $totalEquity > 0 ? number_format($totalLiabilities / $totalEquity * 100, 1) : 0 }}%
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
        <p>Data neraca belum di-generate. Silakan generate data terlebih dahulu.</p>
        @if($report->status === 'draft')
            <a href="{{ route('financial-reports.edit', $report) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit & Generate
            </a>
        @endif
    </div>
@endif