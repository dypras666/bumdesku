{{-- Trial Balance Partial --}}
@if(isset($data) && $data)
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-balance-scale"></i> Neraca Saldo
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
                    <h5>NERACA SALDO</h5>
                    <p>Per {{ \Carbon\Carbon::parse($report->period_end)->format('d F Y') }}</p>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th width="15%">Kode Akun</th>
                            <th width="45%">Nama Akun</th>
                            <th width="20%" class="text-right">Debit ({{ setting('default_currency', 'Rp') }})</th>
                            <th width="20%" class="text-right">Kredit ({{ setting('default_currency', 'Rp') }})</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php 
                            $totalDebit = 0; 
                            $totalCredit = 0;
                            $currentCategory = '';
                        @endphp
                        
                        @foreach($data['accounts'] ?? [] as $account)
                            {{-- Category Header --}}
                            @if($account['category'] !== $currentCategory)
                                @php $currentCategory = $account['category']; @endphp
                                <tr class="bg-light">
                                    <td colspan="4" class="font-weight-bold text-uppercase">
                                        <i class="fas fa-folder"></i> {{ $currentCategory }}
                                    </td>
                                </tr>
                            @endif
                            
                            {{-- Account Row --}}
                            <tr>
                                <td>{{ $account['code'] }}</td>
                                <td class="pl-3">{{ $account['name'] }}</td>
                                <td class="text-right">
                                    @if($account['debit'] > 0)
                                        {{ format_currency($account['debit']) }}
                                        @php $totalDebit += $account['debit']; @endphp
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="text-right">
                                    @if($account['credit'] > 0)
                                        {{ format_currency($account['credit']) }}
                                        @php $totalCredit += $account['credit']; @endphp
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-secondary text-white">
                        <tr class="font-weight-bold">
                            <td colspan="2" class="text-right">TOTAL</td>
                            <td class="text-right">{{ format_currency($totalDebit) }}</td>
                            <td class="text-right">{{ format_currency($totalCredit) }}</td>
                        </tr>
                        <tr class="font-weight-bold">
                            <td colspan="2" class="text-right">SELISIH</td>
                            <td colspan="2" class="text-center">
                                @php $difference = abs($totalDebit - $totalCredit); @endphp
                                @if($difference < 0.01)
                                    <span class="badge badge-success">SEIMBANG</span>
                                @else
                                    <span class="badge badge-danger">{{ format_currency($difference) }}</span>
                                @endif
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            {{-- Summary by Category --}}
            <div class="row mt-4">
                <div class="col-12">
                    <h5><i class="fas fa-chart-pie"></i> Ringkasan per Kategori</h5>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead class="bg-info text-white">
                                <tr>
                                    <th>Kategori Akun</th>
                                    <th class="text-right">Total Debit</th>
                                    <th class="text-right">Total Kredit</th>
                                    <th class="text-right">Saldo Bersih</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $categorySummary = []; @endphp
                                @foreach($data['accounts'] ?? [] as $account)
                                    @php
                                        $category = $account['category'];
                                        if (!isset($categorySummary[$category])) {
                                            $categorySummary[$category] = [
                                                'debit' => 0,
                                                'credit' => 0
                                            ];
                                        }
                                        $categorySummary[$category]['debit'] += $account['debit'];
                                        $categorySummary[$category]['credit'] += $account['credit'];
                                    @endphp
                                @endforeach
                                
                                @foreach($categorySummary as $category => $summary)
                                    @php $netBalance = $summary['debit'] - $summary['credit']; @endphp
                                    <tr>
                                        <td>{{ $category }}</td>
                                        <td class="text-right">{{ format_currency($summary['debit']) }}</td>
                                        <td class="text-right">{{ format_currency($summary['credit']) }}</td>
                                        <td class="text-right {{ $netBalance >= 0 ? 'text-success' : 'text-danger' }}">
                                            {{ format_currency(abs($netBalance)) }}
                                            <small>({{ $netBalance >= 0 ? 'Debit' : 'Kredit' }})</small>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Statistics --}}
            <div class="row mt-4">
                <div class="col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-success"><i class="fas fa-plus"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Debit</span>
                            <span class="info-box-number">{{ format_currency($totalDebit) }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-danger"><i class="fas fa-minus"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Kredit</span>
                            <span class="info-box-number">{{ format_currency($totalCredit) }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-warning"><i class="fas fa-calculator"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Selisih</span>
                            <span class="info-box-number">{{ format_currency($difference) }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-info"><i class="fas fa-list"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Jumlah Akun</span>
                            <span class="info-box-number">{{ count($data['accounts'] ?? []) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Balance Status --}}
            <div class="row mt-3">
                <div class="col-12">
                    @php $isBalanced = $difference < 0.01; @endphp
                    <div class="alert {{ $isBalanced ? 'alert-success' : 'alert-danger' }}">
                        <h5>
                            <i class="icon fas {{ $isBalanced ? 'fa-check-circle' : 'fa-exclamation-triangle' }}"></i>
                            Status Neraca Saldo: {{ $isBalanced ? 'SEIMBANG' : 'TIDAK SEIMBANG' }}
                        </h5>
                        <p class="mb-0">
                            @if($isBalanced)
                                Neraca saldo sudah seimbang. Total debit sama dengan total kredit.
                            @else
                                Terdapat selisih sebesar {{ format_currency($difference) }}. 
                                Silakan periksa kembali pencatatan transaksi.
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            {{-- Account Distribution Chart (Placeholder for future enhancement) --}}
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">
                                <i class="fas fa-chart-donut"></i> Distribusi Saldo per Kategori
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach($categorySummary as $category => $summary)
                                    @php 
                                        $categoryTotal = $summary['debit'] + $summary['credit'];
                                        $grandTotal = $totalDebit + $totalCredit;
                                        $percentage = $grandTotal > 0 ? ($categoryTotal / $grandTotal) * 100 : 0;
                                    @endphp
                                    <div class="col-md-6 mb-3">
                                        <div class="progress-group">
                                            <span class="progress-text">{{ $category }}</span>
                                            <span class="float-right">
                                                <b>{{ format_currency($categoryTotal) }}</b>/{{ format_currency($grandTotal) }}
                                            </span>
                                            <div class="progress progress-sm">
                                                <div class="progress-bar bg-primary"></div>
                                            </div>
                                            <small class="text-muted">{{ number_format($percentage, 1) }}% dari total</small>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
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
        <p>Data neraca saldo belum di-generate. Silakan generate data terlebih dahulu.</p>
        @if($report->status === 'draft')
            <a href="{{ route('financial-reports.edit', $report) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit & Generate
            </a>
        @endif
    </div>
@endif