@extends('adminlte::page')

@section('title', 'Neraca')

@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h1>Neraca</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('financial-reports.index') }}">Laporan Keuangan</a></li>
                <li class="breadcrumb-item active">Neraca</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <!-- Filter Card -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-filter"></i> Filter Tanggal
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('reports.balance-sheet') }}">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="as_of_date">Per Tanggal</label>
                                    <input type="date" class="form-control" id="as_of_date" name="as_of_date" 
                                           value="{{ request('as_of_date', $asOfDate->format('Y-m-d')) }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <div class="d-block">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-search"></i> Filter
                                        </button>
                                        <a href="{{ route('reports.balance-sheet') }}" class="btn btn-secondary">
                                            <i class="fas fa-undo"></i> Reset
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
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

            <!-- Balance Sheet Report -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-balance-scale"></i> Neraca
                    </h3>
                    <div class="card-tools">
                        <!-- Export Dropdown -->
                        <div class="btn-group">
                            <button type="button" class="btn btn-tool dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-download"></i> Export
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="{{ route('reports.balance-sheet.export-pdf', ['as_of_date' => request('as_of_date', $asOfDate->format('Y-m-d'))]) }}">
                                    <i class="fas fa-file-pdf text-danger"></i> Export PDF
                                </a>
                                <a class="dropdown-item" href="{{ route('reports.balance-sheet.export-docx', ['as_of_date' => request('as_of_date', $asOfDate->format('Y-m-d'))]) }}">
                                    <i class="fas fa-file-word text-primary"></i> Export DOC
                                </a>
                                <a class="dropdown-item" href="{{ route('reports.balance-sheet.export-excel', ['as_of_date' => request('as_of_date', $asOfDate->format('Y-m-d'))]) }}">
                                    <i class="fas fa-file-excel text-success"></i> Export Excel
                                </a>
                            </div>
                        </div>
                        <button type="button" class="btn btn-tool" onclick="window.print()">
                            <i class="fas fa-print"></i> Cetak
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="maximize">
                            <i class="fas fa-expand"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Standard View -->
                    <div id="standard-view">
                    <!-- Company Header -->
                    <div class="text-center mb-4">
                        @if(company_info('logo'))
                            <img src="{{ company_info('logo') }}" alt="Logo" style="height: 60px;" class="mb-2">
                        @endif
                        <h4 class="mb-1">{{ company_info('name') }}</h4>
                        <p class="text-muted mb-1">{{ company_info('address') }}</p>
                        <h5 class="font-weight-bold">NERACA</h5>
                        <p class="text-muted">
                            Per Tanggal: {{ $asOfDate->format('d F Y') }}
                        </p>
                    </div>

                    <div class="row">
                        <!-- ASET -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-primary">
                                    <h4 class="card-title text-white mb-0">
                                        <i class="fas fa-building"></i> ASET
                                    </h4>
                                </div>
                                <div class="card-body p-0">
                                    <table class="table table-striped mb-0">
                                        <tbody>
                                            @php $totalAssets = 0; @endphp
                                            @if(isset($reportData['assets']) && count($reportData['assets']) > 0)
                                                @foreach($reportData['assets'] as $accountName => $balance)
                                                    <tr>
                                                        <td>{{ $accountName }}</td>
                                                        <td class="text-right">{{ format_currency($balance) }}</td>
                                                    </tr>
                                                    @php $totalAssets += $balance; @endphp
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td class="text-muted">Tidak ada data aset</td>
                                                    <td class="text-right text-muted">-</td>
                                                </tr>
                                            @endif
                                            <tr class="bg-light font-weight-bold">
                                                <td>TOTAL ASET</td>
                                                <td class="text-right">{{ format_currency($totalAssets) }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- KEWAJIBAN & MODAL -->
                        <div class="col-md-6">
                            <!-- KEWAJIBAN -->
                            <div class="card">
                                <div class="card-header bg-warning">
                                    <h4 class="card-title text-dark mb-0">
                                        <i class="fas fa-credit-card"></i> KEWAJIBAN
                                    </h4>
                                </div>
                                <div class="card-body p-0">
                                    <table class="table table-striped mb-0">
                                        <tbody>
                                            @php $totalLiabilities = 0; @endphp
                                            @if(isset($reportData['liabilities']) && count($reportData['liabilities']) > 0)
                                                @foreach($reportData['liabilities'] as $accountName => $balance)
                                                    <tr>
                                                        <td>{{ $accountName }}</td>
                                                        <td class="text-right">{{ format_currency($balance) }}</td>
                                                    </tr>
                                                    @php $totalLiabilities += $balance; @endphp
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td class="text-muted">Tidak ada data kewajiban</td>
                                                    <td class="text-right text-muted">-</td>
                                                </tr>
                                            @endif
                                            <tr class="bg-light font-weight-bold">
                                                <td>TOTAL KEWAJIBAN</td>
                                                <td class="text-right">{{ format_currency($totalLiabilities) }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- MODAL -->
                            <div class="card">
                                <div class="card-header bg-success">
                                    <h4 class="card-title text-white mb-0">
                                        <i class="fas fa-coins"></i> MODAL
                                    </h4>
                                </div>
                                <div class="card-body p-0">
                                    <table class="table table-striped mb-0">
                                        <tbody>
                                            @php $totalEquity = 0; @endphp
                                            @if(isset($reportData['equity']) && count($reportData['equity']) > 0)
                                                @foreach($reportData['equity'] as $accountName => $balance)
                                                    <tr>
                                                        <td>{{ $accountName }}</td>
                                                        <td class="text-right">{{ format_currency($balance) }}</td>
                                                    </tr>
                                                    @php $totalEquity += $balance; @endphp
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td class="text-muted">Tidak ada data modal</td>
                                                    <td class="text-right text-muted">-</td>
                                                </tr>
                                            @endif
                                            <tr class="bg-light font-weight-bold">
                                                <td>TOTAL MODAL</td>
                                                <td class="text-right">{{ format_currency($totalEquity) }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- TOTAL KEWAJIBAN & MODAL -->
                            <div class="card">
                                <div class="card-header bg-dark">
                                    <h4 class="card-title text-white mb-0">
                                        <i class="fas fa-calculator"></i> TOTAL KEWAJIBAN & MODAL
                                    </h4>
                                </div>
                                <div class="card-body p-0">
                                    <table class="table mb-0">
                                        <tbody>
                                            <tr class="font-weight-bold">
                                                <td>TOTAL KEWAJIBAN & MODAL</td>
                                                <td class="text-right">{{ format_currency($totalLiabilities + $totalEquity) }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Balance Check -->
                    @php 
                        $balanceDifference = $totalAssets - ($totalLiabilities + $totalEquity);
                        $isBalanced = abs($balanceDifference) < 0.01; // Allow for small rounding differences
                    @endphp
                    <div class="alert {{ $isBalanced ? 'alert-success' : 'alert-danger' }} mt-3">
                        <h5>
                            <i class="icon fas {{ $isBalanced ? 'fa-check' : 'fa-exclamation-triangle' }}"></i>
                            Status Neraca
                        </h5>
                        @if($isBalanced)
                            <p class="mb-0">Neraca seimbang! Total Aset = Total Kewajiban + Modal</p>
                        @else
                            <p class="mb-0">
                                <strong>Peringatan:</strong> Neraca tidak seimbang!<br>
                                Selisih: {{ format_currency(abs($balanceDifference)) }}
                                ({{ $balanceDifference > 0 ? 'Aset lebih besar' : 'Kewajiban + Modal lebih besar' }})
                            </p>
                        @endif
                    </div>
                    </div>
                    <!-- End Standard View -->

                    <!-- Simple View -->
                    <div id="simple-view" style="display: none;">
                        @include('financial-reports.partials.simple-balance-sheet', [
                            'reportData' => $reportData,
                            'asOfDate' => $asOfDate,
                            'totalAssets' => $totalAssets,
                            'totalLiabilities' => $totalLiabilities,
                            'totalEquity' => $totalEquity
                        ])
                    </div>
                    <!-- End Simple View -->
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="info-box">
                        <span class="info-box-icon bg-primary"><i class="fas fa-building"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Aset</span>
                            <span class="info-box-number">{{ format_currency($totalAssets) }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="info-box">
                        <span class="info-box-icon bg-warning"><i class="fas fa-credit-card"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Kewajiban</span>
                            <span class="info-box-number">{{ format_currency($totalLiabilities) }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="info-box">
                        <span class="info-box-icon bg-success"><i class="fas fa-coins"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Modal</span>
                            <span class="info-box-number">{{ format_currency($totalEquity) }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="info-box">
                        <span class="info-box-icon {{ $isBalanced ? 'bg-success' : 'bg-danger' }}">
                            <i class="fas {{ $isBalanced ? 'fa-check' : 'fa-times' }}"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Status Neraca</span>
                            <span class="info-box-number">{{ $isBalanced ? 'Seimbang' : 'Tidak Seimbang' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Analysis Section -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-pie"></i> Analisis Neraca
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Struktur Modal</h5>
                                    @if($totalAssets > 0)
                                        @php 
                                            $debtRatio = ($totalLiabilities / $totalAssets) * 100;
                                            $equityRatio = ($totalEquity / $totalAssets) * 100;
                                        @endphp
                                        <p><strong>Rasio Hutang:</strong> {{ number_format($debtRatio, 2) }}%</p>
                                        <p><strong>Rasio Modal:</strong> {{ number_format($equityRatio, 2) }}%</p>
                                        <div class="progress mb-3">
                                            <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $debtRatio }}%" 
                                                 aria-valuenow="{{ $debtRatio }}" aria-valuemin="0" aria-valuemax="100">
                                                Hutang {{ number_format($debtRatio, 1) }}%
                                            </div>
                                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $equityRatio }}%" 
                                                 aria-valuenow="{{ $equityRatio }}" aria-valuemin="0" aria-valuemax="100">
                                                Modal {{ number_format($equityRatio, 1) }}%
                                            </div>
                                        </div>
                                    @else
                                        <p class="text-muted">Tidak dapat menghitung rasio karena tidak ada aset</p>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <h5>Informasi Neraca</h5>
                                    <p><strong>Tanggal Laporan:</strong> {{ $asOfDate->format('d F Y') }}</p>
                                    <p><strong>Status Keseimbangan:</strong> 
                                        <span class="badge {{ $isBalanced ? 'badge-success' : 'badge-danger' }}">
                                            {{ $isBalanced ? 'Seimbang' : 'Tidak Seimbang' }}
                                        </span>
                                    </p>
                                    @if($totalLiabilities > 0 && $totalEquity > 0)
                                        @php $debtToEquityRatio = $totalLiabilities / $totalEquity; @endphp
                                        <p><strong>Debt to Equity Ratio:</strong> {{ number_format($debtToEquityRatio, 2) }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        @media print {
            .card-header, .card-tools, .breadcrumb, .content-header, .main-sidebar, .main-header, .main-footer {
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
        .info-box-number {
            font-size: 1.1rem;
        }
        .table td {
            vertical-align: middle;
        }
        .progress {
            height: 25px;
        }
        .progress-bar {
            line-height: 25px;
            font-size: 12px;
        }
    </style>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            // Auto-submit form when date changes
            $('#as_of_date').on('change', function() {
                // Optional: Auto-submit on date change
                // $(this).closest('form').submit();
            });

            // Set default view to standard
            toggleStyle('standard');
        });

        function toggleStyle(style) {
            if (style === 'simple') {
                $('#standard-view').hide();
                $('#simple-view').show();
                $('#btn-standard').removeClass('btn-primary').addClass('btn-outline-primary');
                $('#btn-simple').removeClass('btn-outline-success').addClass('btn-success');
                
                // Load simple styles
                if (!$('#simple-styles').length) {
                    $('head').append('<link id="simple-styles" rel="stylesheet" href="{{ asset("css/simple-reports.css") }}">');
                }
            } else {
                $('#simple-view').hide();
                $('#standard-view').show();
                $('#btn-simple').removeClass('btn-success').addClass('btn-outline-success');
                $('#btn-standard').removeClass('btn-outline-primary').addClass('btn-primary');
                
                // Remove simple styles
                $('#simple-styles').remove();
            }
        }
    </script>
@stop