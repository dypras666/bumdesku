@extends('adminlte::page')

@section('title', 'Laporan Laba Rugi')

@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h1>Laporan Laba Rugi</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('financial-reports.index') }}">Laporan Keuangan</a></li>
                <li class="breadcrumb-item active">Laporan Laba Rugi</li>
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
                        <i class="fas fa-filter"></i> Filter Periode
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('reports.income-statement') }}">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="period_start">Periode Awal</label>
                                    <input type="date" class="form-control" id="period_start" name="period_start" 
                                           value="{{ request('period_start', $periodStart->format('Y-m-d')) }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="period_end">Periode Akhir</label>
                                    <input type="date" class="form-control" id="period_end" name="period_end" 
                                           value="{{ request('period_end', $periodEnd->format('Y-m-d')) }}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <div class="d-block">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-search"></i> Filter
                                        </button>
                                        <a href="{{ route('reports.income-statement') }}" class="btn btn-secondary">
                                            <i class="fas fa-undo"></i> Reset
                                        </a>
                                    </div>
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

            <!-- Income Statement Report -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-line"></i> Laporan Laba Rugi
                    </h3>
                    <div class="card-tools">
                        <!-- Export Dropdown -->
                        <div class="btn-group">
                            <button type="button" class="btn btn-tool dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-download"></i> Export
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="{{ route('reports.income-statement.export-pdf', ['period_start' => request('period_start', $periodStart->format('Y-m-d')), 'period_end' => request('period_end', $periodEnd->format('Y-m-d'))]) }}">
                                    <i class="fas fa-file-pdf text-danger"></i> Export PDF
                                </a>
                                <a class="dropdown-item" href="{{ route('reports.income-statement.export-docx', ['period_start' => request('period_start', $periodStart->format('Y-m-d')), 'period_end' => request('period_end', $periodEnd->format('Y-m-d'))]) }}">
                                    <i class="fas fa-file-word text-primary"></i> Export DOC
                                </a>
                                <a class="dropdown-item" href="{{ route('reports.income-statement.export-excel', ['period_start' => request('period_start', $periodStart->format('Y-m-d')), 'period_end' => request('period_end', $periodEnd->format('Y-m-d'))]) }}">
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
                        <h5 class="font-weight-bold">LAPORAN LABA RUGI</h5>
                        <p class="text-muted">
                            Periode: {{ $periodStart->format('d F Y') }} s/d {{ $periodEnd->format('d F Y') }}
                        </p>
                    </div>

                    <!-- Income Statement Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                <!-- PENDAPATAN -->
                                <tr class="bg-primary text-white">
                                    <td><strong>PENDAPATAN</strong></td>
                                    <td class="text-right"><strong>JUMLAH</strong></td>
                                </tr>
                                @php $totalRevenue = 0; @endphp
                                @if(isset($reportData['revenues']) && count($reportData['revenues']) > 0)
                                    @foreach($reportData['revenues'] as $accountName => $amount)
                                        @php
                                            // Handle both data structures: seeder (array) and controller (key-value)
                                            if (is_array($amount)) {
                                                $displayName = $amount['account'] ?? $accountName;
                                                $displayAmount = $amount['amount'] ?? 0;
                                            } else {
                                                $displayName = $accountName;
                                                $displayAmount = $amount;
                                            }
                                            $displayAmount = (float) $displayAmount;
                                        @endphp
                                        <tr>
                                            <td class="pl-4">{{ $displayName }}</td>
                                            <td class="text-right">{{ format_currency($displayAmount) }}</td>
                                        </tr>
                                        @php $totalRevenue += $displayAmount; @endphp
                                    @endforeach
                                @else
                                    <tr>
                                        <td class="pl-4 text-muted">Tidak ada data pendapatan</td>
                                        <td class="text-right text-muted">-</td>
                                    </tr>
                                @endif
                                <tr class="font-weight-bold bg-light">
                                    <td class="text-right">Total Pendapatan</td>
                                    <td class="text-right">{{ format_currency($totalRevenue) }}</td>
                                </tr>

                                <!-- BEBAN -->
                                <tr class="bg-warning text-dark">
                                    <td><strong>BEBAN OPERASIONAL</strong></td>
                                    <td class="text-right"><strong>JUMLAH</strong></td>
                                </tr>
                                @php $totalExpenses = 0; @endphp
                                @if(isset($reportData['expenses']) && count($reportData['expenses']) > 0)
                                    @foreach($reportData['expenses'] as $accountName => $amount)
                                        @php
                                            // Handle both data structures: seeder (array) and controller (key-value)
                                            if (is_array($amount)) {
                                                $displayName = $amount['account'] ?? $accountName;
                                                $displayAmount = $amount['amount'] ?? 0;
                                            } else {
                                                $displayName = $accountName;
                                                $displayAmount = $amount;
                                            }
                                            $displayAmount = (float) $displayAmount;
                                        @endphp
                                        <tr>
                                            <td class="pl-4">{{ $displayName }}</td>
                                            <td class="text-right">{{ format_currency($displayAmount) }}</td>
                                        </tr>
                                        @php $totalExpenses += $displayAmount; @endphp
                                    @endforeach
                                @else
                                    <tr>
                                        <td class="pl-4 text-muted">Tidak ada data beban</td>
                                        <td class="text-right text-muted">-</td>
                                    </tr>
                                @endif
                                <tr class="font-weight-bold bg-light">
                                    <td class="text-right">Total Beban Operasional</td>
                                    <td class="text-right">{{ format_currency($totalExpenses) }}</td>
                                </tr>

                                <!-- LABA/RUGI BERSIH -->
                                @php 
                                    $netIncome = $totalRevenue - $totalExpenses;
                                    $netIncomeClass = $netIncome >= 0 ? 'text-success' : 'text-danger';
                                    $netIncomeLabel = $netIncome >= 0 ? 'LABA BERSIH' : 'RUGI BERSIH';
                                @endphp
                                <tr class="font-weight-bold bg-dark text-white">
                                    <td class="text-right">{{ $netIncomeLabel }}</td>
                                    <td class="text-right {{ $netIncomeClass }}">{{ format_currency(abs($netIncome)) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="row">
                <div class="col-lg-4 col-6">
                    <div class="info-box">
                        <span class="info-box-icon bg-success"><i class="fas fa-arrow-up"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Pendapatan</span>
                            <span class="info-box-number">{{ format_currency($totalRevenue) }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-6">
                    <div class="info-box">
                        <span class="info-box-icon bg-warning"><i class="fas fa-arrow-down"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Beban</span>
                            <span class="info-box-number">{{ format_currency($totalExpenses) }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-6">
                    <div class="info-box">
                        <span class="info-box-icon {{ $netIncome >= 0 ? 'bg-success' : 'bg-danger' }}">
                            <i class="fas {{ $netIncome >= 0 ? 'fa-chart-line' : 'fa-chart-line-down' }}"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">{{ $netIncomeLabel }}</span>
                            <span class="info-box-number {{ $netIncomeClass }}">{{ format_currency(abs($netIncome)) }}</span>
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
                                <i class="fas fa-chart-pie"></i> Analisis Kinerja
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Rasio Profitabilitas</h5>
                                    @if($totalRevenue > 0)
                                        @php $profitMargin = ($netIncome / $totalRevenue) * 100; @endphp
                                        <p><strong>Margin Laba:</strong> {{ number_format($profitMargin, 2) }}%</p>
                                        <p><strong>Rasio Beban:</strong> {{ number_format(($totalExpenses / $totalRevenue) * 100, 2) }}%</p>
                                    @else
                                        <p class="text-muted">Tidak dapat menghitung rasio karena tidak ada pendapatan</p>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <h5>Ringkasan Periode</h5>
                                    <p><strong>Periode Laporan:</strong> {{ $periodStart->diffInDays($periodEnd) + 1 }} hari</p>
                                    <p><strong>Status Keuangan:</strong> 
                                        <span class="badge {{ $netIncome >= 0 ? 'badge-success' : 'badge-danger' }}">
                                            {{ $netIncome >= 0 ? 'Menguntungkan' : 'Merugi' }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                    <!-- End Standard View -->

                    <!-- Simple View -->
                    <div id="simple-view" style="display: none;">
                        @include('financial-reports.partials.simple-income-statement', [
                            'reportData' => $reportData,
                            'periodStart' => $periodStart,
                            'periodEnd' => $periodEnd,
                            'totalRevenue' => $totalRevenue,
                            'totalExpenses' => $totalExpenses,
                            'netIncome' => $netIncome
                        ])
                    </div>
                    <!-- End Simple View -->
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
            font-size: 1.2rem;
        }
        
        .table td {
            vertical-align: middle;
        }
        
        .bg-primary td {
            background-color: #007bff !important;
            color: white !important;
        }
        
        .bg-warning td {
            background-color: #ffc107 !important;
            color: #212529 !important;
        }
        
        .bg-dark td {
            background-color: #343a40 !important;
            color: white !important;
        }
    </style>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            // Auto-submit form when date changes
            $('#period_start, #period_end').on('change', function() {
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