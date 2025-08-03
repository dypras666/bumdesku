{{-- General Ledger Partial --}}
@if(isset($reportData) && $reportData)
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-book"></i> Buku Besar
                @if(isset($reportData['account_info']))
                    - {{ $reportData['account_info']['account_code'] }} {{ $reportData['account_info']['account_name'] }}
                @endif
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
                    <h5>BUKU BESAR</h5>
                    @if(isset($reportData['account_info']))
                        <h6>{{ $reportData['account_info']['account_code'] }} - {{ $reportData['account_info']['account_name'] }}</h6>
                    @endif
                    <p>Periode {{ \Carbon\Carbon::parse($report->period_start)->format('d F Y') }} s/d {{ \Carbon\Carbon::parse($report->period_end)->format('d F Y') }}</p>
                </div>
            </div>

            {{-- Account Information --}}
            @if(isset($reportData['account_info']))
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-info"><i class="fas fa-info-circle"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Informasi Akun</span>
                                <span class="info-box-number">{{ $reportData['account_info']['account_code'] }}</span>
                                <div class="progress">
                                    <div class="progress-bar bg-info"></div>
                                </div>
                                <span class="progress-description">
                                    {{ $reportData['account_info']['account_name'] }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-warning"><i class="fas fa-tag"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Kategori</span>
                                <span class="info-box-number">{{ $reportData['account_info']['category'] ?? 'N/A' }}</span>
                                <div class="progress">
                                    <div class="progress-bar bg-warning"></div>
                                </div>
                                <span class="progress-description">
                                    Saldo Awal: {{ format_currency($reportData['beginning_balance'] ?? 0) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Ledger Entries --}}
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th width="10%">Tanggal</th>
                            <th width="15%">Kode Transaksi</th>
                            <th width="35%">Keterangan</th>
                            <th width="15%" class="text-right">Debit ({{ setting('default_currency', 'Rp') }})</th>
                            <th width="15%" class="text-right">Kredit ({{ setting('default_currency', 'Rp') }})</th>
                            <th width="10%" class="text-right">Saldo</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Beginning Balance --}}
                        @php 
                            $runningBalance = $reportData['beginning_balance'] ?? 0;
                            $totalDebit = 0;
                            $totalCredit = 0;
                        @endphp
                        @if($runningBalance != 0)
                            <tr class="bg-light font-weight-bold">
                                <td colspan="3" class="text-center">SALDO AWAL</td>
                                <td class="text-right">
                                    @if($runningBalance > 0)
                                        {{ format_currency($runningBalance) }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="text-right">
                                    @if($runningBalance < 0)
                                        {{ format_currency(abs($runningBalance)) }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="text-right">{{ format_currency(abs($runningBalance)) }}</td>
                            </tr>
                        @endif

                        {{-- Ledger Entries --}}
                        @forelse($reportData['entries'] ?? [] as $entry)
                            @php
                                $debitAmount = $entry['debit'] ?? 0;
                                $creditAmount = $entry['credit'] ?? 0;
                                $runningBalance += ($debitAmount - $creditAmount);
                                $totalDebit += $debitAmount;
                                $totalCredit += $creditAmount;
                            @endphp
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($entry['posting_date'])->format('d/m/Y') }}</td>
                                <td>{{ $entry['transaction_code'] ?? '-' }}</td>
                                <td>{{ $entry['description'] }}</td>
                                <td class="text-right">
                                    @if($debitAmount > 0)
                                        {{ format_currency($debitAmount) }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="text-right">
                                    @if($creditAmount > 0)
                                        {{ format_currency($creditAmount) }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="text-right {{ $runningBalance >= 0 ? 'text-success' : 'text-danger' }}">
                                    {{ format_currency(abs($runningBalance)) }}
                                    <small>({{ $runningBalance >= 0 ? 'D' : 'K' }})</small>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">
                                    <i class="fas fa-info-circle"></i> Tidak ada transaksi dalam periode ini
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="bg-secondary text-white">
                        <tr class="font-weight-bold">
                            <td colspan="3" class="text-right">TOTAL MUTASI</td>
                            <td class="text-right">{{ format_currency($totalDebit) }}</td>
                            <td class="text-right">{{ format_currency($totalCredit) }}</td>
                            <td class="text-right">{{ format_currency(abs($runningBalance)) }}</td>
                        </tr>
                        <tr class="font-weight-bold">
                            <td colspan="3" class="text-right">SALDO AKHIR</td>
                            <td colspan="2" class="text-center">
                                @if($runningBalance >= 0)
                                    <span class="badge badge-success">DEBIT</span>
                                @else
                                    <span class="badge badge-danger">KREDIT</span>
                                @endif
                            </td>
                            <td class="text-right">{{ format_currency(abs($runningBalance)) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            {{-- Summary Statistics --}}
            <div class="row mt-4">
                <div class="col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-success"><i class="fas fa-arrow-up"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Debit</span>
                            <span class="info-box-number">{{ format_currency($totalDebit) }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-danger"><i class="fas fa-arrow-down"></i></span>
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
                            <span class="info-box-text">Mutasi Bersih</span>
                            <span class="info-box-number">{{ format_currency(abs($totalDebit - $totalCredit)) }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-info"><i class="fas fa-list-ol"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Jumlah Transaksi</span>
                            <span class="info-box-number">{{ count($reportData['entries'] ?? []) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Account Movement Analysis --}}
            <div class="row mt-3">
                <div class="col-12">
                    @php 
                        $netMovement = $totalDebit - $totalCredit;
                        $movementType = '';
                        $movementClass = 'info';
                        
                        if ($netMovement > 0) {
                            $movementType = 'Kenaikan Saldo Debit';
                            $movementClass = 'success';
                        } elseif ($netMovement < 0) {
                            $movementType = 'Kenaikan Saldo Kredit';
                            $movementClass = 'danger';
                        } else {
                            $movementType = 'Tidak Ada Perubahan Saldo';
                            $movementClass = 'info';
                        }
                    @endphp
                    <div class="alert alert-{{ $movementClass }}">
                        <h5>
                            <i class="icon fas {{ $movementClass === 'success' ? 'fa-arrow-up' : ($movementClass === 'danger' ? 'fa-arrow-down' : 'fa-minus') }}"></i>
                            Analisis Pergerakan: {{ $movementType }}
                        </h5>
                        <p class="mb-0">
                            Saldo Awal: {{ format_currency(abs($reportData['beginning_balance'] ?? 0)) }} |
                            Saldo Akhir: {{ format_currency(abs($runningBalance)) }} |
                            Perubahan: {{ format_currency(abs($netMovement)) }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Monthly Summary (if period spans multiple months) --}}
            @php
                $startDate = \Carbon\Carbon::parse($report->period_start);
                $endDate = \Carbon\Carbon::parse($report->period_end);
                $monthsDiff = $startDate->diffInMonths($endDate);
            @endphp
            @if($monthsDiff > 0 && isset($reportData['monthly_summary']))
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">
                                    <i class="fas fa-calendar-alt"></i> Ringkasan Bulanan
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered">
                                        <thead class="bg-info text-white">
                                            <tr>
                                                <th>Bulan</th>
                                                <th class="text-right">Total Debit</th>
                                                <th class="text-right">Total Kredit</th>
                                                <th class="text-right">Mutasi Bersih</th>
                                                <th class="text-center">Jumlah Transaksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($reportData['monthly_summary'] as $month => $summary)
                                                <tr>
                                                    <td>{{ \Carbon\Carbon::parse($month)->format('F Y') }}</td>
                                                    <td class="text-right">{{ format_currency($summary['debit']) }}</td>
                                                    <td class="text-right">{{ format_currency($summary['credit']) }}</td>
                                                    <td class="text-right {{ ($summary['debit'] - $summary['credit']) >= 0 ? 'text-success' : 'text-danger' }}">
                                                        {{ format_currency(abs($summary['debit'] - $summary['credit'])) }}
                                                        <small>({{ ($summary['debit'] - $summary['credit']) >= 0 ? 'D' : 'K' }})</small>
                                                    </td>
                                                    <td class="text-center">{{ $summary['count'] }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
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
        <p>Data buku besar belum di-generate. Silakan generate data terlebih dahulu.</p>
        @if($report->status === 'draft')
            <a href="{{ route('financial-reports.edit', $report) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit & Generate
            </a>
        @endif
    </div>
@endif