@extends('adminlte::page')

@section('title', 'Neraca Saldo')

@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h1>Neraca Saldo</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('general-ledger.index') }}">Daftar Buku Besar</a></li>
                <li class="breadcrumb-item active">Neraca Saldo</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Filter Periode</h3>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('trial-balance') }}">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="start_date">Tanggal Mulai</label>
                                    <input type="date" name="start_date" id="start_date" class="form-control" 
                                           value="{{ request('start_date', date('Y-m-01')) }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="end_date">Tanggal Akhir</label>
                                    <input type="date" name="end_date" id="end_date" class="form-control" 
                                           value="{{ request('end_date', date('Y-m-t')) }}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>&nbsp;</label><br>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search"></i> Tampilkan
                                    </button>
                                    <button type="button" class="btn btn-success" onclick="printReport()">
                                        <i class="fas fa-print"></i> Cetak
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="simple_style">Gaya Tampilan</label>
                                    <div class="d-block">
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-outline-primary" id="btn-standard" onclick="toggleStyle('standard')">
                                                <i class="fas fa-desktop"></i> Standard
                                            </button>
                                            <button type="button" class="btn btn-outline-success" id="btn-simple" onclick="toggleStyle('simple')">
                                                <i class="fas fa-file-pdf"></i> Simple
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Standard View -->
    <div id="standard-view">
        <div class="row">
            <div class="col-12">
                <div class="card" id="trial-balance-report">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-md-8 text-center">
                                <h3 class="card-title mb-0">
                                    <strong>{{ company_info('name') ?? 'BUMDES' }}</strong><br>
                                    <span class="h4">NERACA SALDO</span><br>
                                    <span class="h5">Periode: {{ request('start_date', date('Y-m-01')) ? \Carbon\Carbon::parse(request('start_date', date('Y-m-01')))->format('d F Y') : '' }} 
                                    s/d {{ request('end_date', date('Y-m-t')) ? \Carbon\Carbon::parse(request('end_date', date('Y-m-t')))->format('d F Y') : '' }}</span>
                                </h3>
                            </div>
                            <div class="col-md-4">
                                <div class="card-tools float-right">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-download"></i> Export
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="{{ route('trial-balance.export-pdf', request()->all()) }}">
                                                <i class="fas fa-file-pdf text-danger"></i> Export PDF
                                            </a>
                                            <a class="dropdown-item" href="{{ route('trial-balance.export-docx', request()->all()) }}">
                                                <i class="fas fa-file-word text-primary"></i> Export DOCX
                                            </a>
                                            <a class="dropdown-item" href="{{ route('trial-balance.export-excel', request()->all()) }}">
                                                <i class="fas fa-file-excel text-success"></i> Export Excel
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th rowspan="2" class="text-center align-middle">Kode Akun</th>
                                    <th rowspan="2" class="text-center align-middle">Nama Akun</th>
                                    <th colspan="2" class="text-center">Saldo</th>
                                </tr>
                                <tr>
                                    <th class="text-center">Debit</th>
                                    <th class="text-center">Kredit</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $totalDebit = 0;
                                    $totalCredit = 0;
                                @endphp
                                @foreach($trialBalance as $account)
                                    @php
                                        $balance = $account['balance'];
                                        $debitBalance = $balance >= 0 ? $balance : 0;
                                        $creditBalance = $balance < 0 ? abs($balance) : 0;
                                        $totalDebit += $debitBalance;
                                        $totalCredit += $creditBalance;
                                    @endphp
                                    <tr>
                                        <td>{{ $account['account']->account_code }}</td>
                                        <td>{{ $account['account']->account_name }}</td>
                                        <td class="text-right">
                                            @if($debitBalance > 0)
                                                {{ format_currency($debitBalance) }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            @if($creditBalance > 0)
                                                {{ format_currency($creditBalance) }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="thead-light">
                                <tr>
                                    <th colspan="2" class="text-center">TOTAL</th>
                                    <th class="text-right">{{ format_currency($totalDebit) }}</th>
                                    <th class="text-right">{{ format_currency($totalCredit) }}</th>
                                </tr>
                                <tr>
                                    <th colspan="2" class="text-center">SELISIH</th>
                                    <th colspan="2" class="text-center">
                                        @php
                                            $difference = $totalDebit - $totalCredit;
                                        @endphp
                                        <span class="badge {{ abs($difference) < 0.01 ? 'badge-success' : 'badge-danger' }} badge-lg">
                                            {{ format_currency(abs($difference)) }}
                                            @if(abs($difference) < 0.01)
                                                (SEIMBANG)
                                            @else
                                                ({{ $difference > 0 ? 'DEBIT LEBIH' : 'KREDIT LEBIH' }})
                                            @endif
                                        </span>
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    @if(count($trialBalance) === 0)
                        <div class="text-center py-4">
                            <i class="fas fa-info-circle fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Tidak ada data untuk periode yang dipilih</h5>
                            <p class="text-muted">Silakan pilih periode yang berbeda atau pastikan sudah ada entri buku besar yang diposting.</p>
                        </div>
                    @endif
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-6">
                            <small class="text-muted">
                                <i class="fas fa-info-circle"></i>
                                Laporan ini menampilkan saldo semua akun berdasarkan entri buku besar yang telah diposting.
                            </small>
                        </div>
                        <div class="col-md-6 text-right">
                            <small class="text-muted">
                                Dicetak pada: {{ now()->format('d F Y H:i') }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(count($trialBalance) > 0)
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Ringkasan per Kategori</h3>
                    </div>
                    <div class="card-body">
                        @php
                            $categories = collect($trialBalance)->groupBy(function($item) {
                                return $item['account']->account_category;
                            });
                        @endphp
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Kategori</th>
                                        <th class="text-right">Jumlah Akun</th>
                                        <th class="text-right">Total Saldo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($categories as $category => $accounts)
                                        @php
                                            $categoryTotal = $accounts->sum('balance');
                                        @endphp
                                        <tr>
                                            <td>{{ $category }}</td>
                                            <td class="text-right">{{ $accounts->count() }}</td>
                                            <td class="text-right">
                                                <span class="badge {{ $categoryTotal >= 0 ? 'badge-success' : 'badge-danger' }}">
                                                    {{ format_currency(abs($categoryTotal)) }}
                                                    {{ $categoryTotal >= 0 ? '(D)' : '(K)' }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Statistik</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="info-box">
                                    <span class="info-box-icon bg-success"><i class="fas fa-plus"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Total Debit</span>
                                        <span class="info-box-number">{{ format_currency($totalDebit) }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="info-box">
                                    <span class="info-box-icon bg-danger"><i class="fas fa-minus"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Total Kredit</span>
                                        <span class="info-box-number">{{ format_currency($totalCredit) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="info-box">
                                    <span class="info-box-icon bg-info"><i class="fas fa-list"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Jumlah Akun</span>
                                        <span class="info-box-number">{{ count($trialBalance) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    </div>

    <!-- Simple View -->
    <div id="simple-view" style="display: none;">
        @php
            // Transform trial balance data for the simple view
            $reportData = [
                'accounts' => $trialBalance->map(function($item) {
                    return [
                        'code' => $item['account']->kode_akun,
                        'name' => $item['account']->nama_akun,
                        'debit' => $item['debit'],
                        'credit' => $item['credit']
                    ];
                })->toArray()
            ];
        @endphp
        @include('financial-reports.partials.simple-trial-balance', [
            'companyName' => company_info('name') ?? 'BUMDES',
            'reportTitle' => 'NERACA SALDO',
            'reportPeriod' => $startDate->format('d/m/Y') . ' s/d ' . $endDate->format('d/m/Y'),
            'reportData' => $reportData,
            'asOfDate' => $asOfDate,
            'totalDebit' => $totalDebit,
            'totalCredit' => $totalCredit
        ])
    </div>
@stop

@section('css')
    <style>
        .table th, .table td {
            vertical-align: middle;
        }
        .badge-lg {
            font-size: 1rem;
            padding: 0.5rem 1rem;
        }
        @media print {
            .card-header .btn, .card-footer, .breadcrumb, .content-header {
                display: none !important;
            }
            .card {
                border: none !important;
                box-shadow: none !important;
            }
            .card-body {
                padding: 0 !important;
            }
        }
    </style>
@stop

@section('js')
    <script>
        function printReport() {
            window.print();
        }

        function toggleStyle(style) {
            const standardView = document.getElementById('standard-view');
            const simpleView = document.getElementById('simple-view');
            const btnStandard = document.getElementById('btn-standard');
            const btnSimple = document.getElementById('btn-simple');
            
            if (style === 'simple') {
                standardView.style.display = 'none';
                simpleView.style.display = 'block';
                btnStandard.classList.remove('btn-primary');
                btnStandard.classList.add('btn-outline-primary');
                btnSimple.classList.remove('btn-outline-success');
                btnSimple.classList.add('btn-success');
                
                // Load simple reports CSS
                if (!document.getElementById('simple-reports-css')) {
                    const link = document.createElement('link');
                    link.id = 'simple-reports-css';
                    link.rel = 'stylesheet';
                    link.href = '{{ asset("css/simple-reports.css") }}';
                    document.head.appendChild(link);
                }
            } else {
                standardView.style.display = 'block';
                simpleView.style.display = 'none';
                btnStandard.classList.remove('btn-outline-primary');
                btnStandard.classList.add('btn-primary');
                btnSimple.classList.remove('btn-success');
                btnSimple.classList.add('btn-outline-success');
                
                // Remove simple reports CSS
                const simpleCSS = document.getElementById('simple-reports-css');
                if (simpleCSS) {
                    simpleCSS.remove();
                }
            }
        }

        $(document).ready(function() {
            // Auto submit when date changes
            $('#start_date, #end_date').change(function() {
                $(this).closest('form').submit();
            });
            
            // Set default style to standard
            toggleStyle('standard');
        });
    </script>
@stop