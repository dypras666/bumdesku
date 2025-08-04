@extends('adminlte::page')

@section('title', 'Laporan Arus Kas')

@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h1>Laporan Arus Kas</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('financial-reports.index') }}">Laporan Keuangan</a></li>
                <li class="breadcrumb-item active">Arus Kas</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
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
            <form method="GET" action="{{ route('reports.cash-flow') }}" id="dateFilterForm">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="period_start">Periode Awal</label>
                            <input type="date" class="form-control" id="period_start" name="period_start" 
                                   value="{{ request('period_start', $periodStart->format('Y-m-d')) }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="period_end">Periode Akhir</label>
                            <input type="date" class="form-control" id="period_end" name="period_end" 
                                   value="{{ request('period_end', $periodEnd->format('Y-m-d')) }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <div class="d-block">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Filter
                                </button>
                                <a href="{{ route('reports.cash-flow') }}" class="btn btn-secondary">
                                    <i class="fas fa-undo"></i> Reset
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Pilihan Cepat</label>
                            <div class="d-block">
                                <div class="btn-group-vertical d-block">
                                    <button type="button" class="btn btn-outline-info btn-sm mb-1" onclick="setCurrentMonth()">
                                        <i class="fas fa-calendar"></i> Bulan Ini
                                    </button>
                                    <button type="button" class="btn btn-outline-success btn-sm" onclick="setCurrentYear()">
                                        <i class="fas fa-calendar-alt"></i> Tahun Ini
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-exchange-alt"></i> Laporan Arus Kas
                    </h3>
                    <div class="card-tools">
                        <!-- Export Dropdown -->
                        <div class="btn-group mr-2">
                            <button type="button" class="btn btn-tool dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-download"></i> Export
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="{{ route('reports.cash-flow.export-pdf', ['period_start' => $periodStart, 'period_end' => $periodEnd]) }}">
                                    <i class="fas fa-file-pdf text-danger"></i> Export PDF
                                </a>
                                <a class="dropdown-item" href="{{ route('reports.cash-flow.export-docx', ['period_start' => $periodStart, 'period_end' => $periodEnd]) }}">
                                    <i class="fas fa-file-word text-primary"></i> Export DOC
                                </a>
                                <a class="dropdown-item" href="{{ route('reports.cash-flow.export-excel', ['period_start' => $periodStart, 'period_end' => $periodEnd]) }}">
                                    <i class="fas fa-file-excel text-success"></i> Export Excel
                                </a>
                            </div>
                        </div>
                        <div class="btn-group mr-2" role="group">
                            <button type="button" class="btn btn-outline-primary btn-sm" id="btn-standard" onclick="toggleStyle('standard')">
                                <i class="fas fa-desktop"></i> Standard
                            </button>
                            <button type="button" class="btn btn-outline-success btn-sm" id="btn-simple" onclick="toggleStyle('simple')">
                                <i class="fas fa-file-pdf"></i> Simple
                            </button>
                        </div>
                        <button type="button" class="btn btn-tool" onclick="window.print()">
                            <i class="fas fa-print"></i> Cetak
                        </button>
                        <a href="{{ route('financial-reports.index') }}" class="btn btn-tool">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Standard View -->
                    <div id="standard-view">
                        {{-- Company Header --}}
                        <div class="text-center mb-4">
                            <h3><strong>{{ company_info('name') ?? 'BUMDES' }}</strong></h3>
                            <h4>LAPORAN ARUS KAS</h4>
                            <h5>Periode: {{ \Carbon\Carbon::parse($periodStart)->format('d F Y') }} s/d {{ \Carbon\Carbon::parse($periodEnd)->format('d F Y') }}</h5>
                        </div>

                    {{-- Cash Flow Statement Table --}}
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th width="70%">Keterangan</th>
                                    <th width="30%" class="text-right">Jumlah (Rp)</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Operating Activities --}}
                                <tr class="table-primary">
                                    <td><strong>ARUS KAS DARI AKTIVITAS OPERASI</strong></td>
                                    <td></td>
                                </tr>
                                
                                {{-- Operating Activities --}}
                                @php 
                                    $totalOperatingInflows = 0; 
                                    $totalOperatingOutflows = 0;
                                @endphp
                                @if(isset($reportData['operating_activities']) && is_iterable($reportData['operating_activities']))
                                    @foreach($reportData['operating_activities'] as $activity)
                                        <tr>
                                            <td class="pl-4">{{ $activity['description'] ?? 'Aktivitas Operasional' }}</td>
                                            <td class="text-right">
                                                @if($activity['amount'] >= 0)
                                                    {{ format_currency($activity['amount']) }}
                                                    @php $totalOperatingInflows += $activity['amount']; @endphp
                                                @else
                                                    ({{ format_currency(abs($activity['amount'])) }})
                                                    @php $totalOperatingOutflows += abs($activity['amount']); @endphp
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif

                                {{-- Net Operating Cash Flow --}}
                                @php $netOperatingCashFlow = $reportData['net_operating_cash'] ?? 0; @endphp
                                <tr class="font-weight-bold table-info">
                                    <td class="text-right">Arus Kas Bersih dari Aktivitas Operasi</td>
                                    <td class="text-right">{{ format_currency($netOperatingCashFlow) }}</td>
                                </tr>

                                {{-- Investing Activities --}}
                                <tr class="table-primary">
                                    <td><strong>ARUS KAS DARI AKTIVITAS INVESTASI</strong></td>
                                    <td></td>
                                </tr>
                                
                                {{-- Investing Activities --}}
                                @php 
                                    $totalInvestingInflows = 0; 
                                    $totalInvestingOutflows = 0;
                                @endphp
                                @if(isset($reportData['investing_activities']) && is_iterable($reportData['investing_activities']))
                                    @foreach($reportData['investing_activities'] as $activity)
                                        <tr>
                                            <td class="pl-4">{{ $activity['description'] ?? 'Aktivitas Investasi' }}</td>
                                            <td class="text-right">
                                                @if($activity['amount'] >= 0)
                                                    {{ format_currency($activity['amount']) }}
                                                    @php $totalInvestingInflows += $activity['amount']; @endphp
                                                @else
                                                    ({{ format_currency(abs($activity['amount'])) }})
                                                    @php $totalInvestingOutflows += abs($activity['amount']); @endphp
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif

                                {{-- Net Investing Cash Flow --}}
                                @php $netInvestingCashFlow = $reportData['net_investing_cash'] ?? 0; @endphp
                                <tr class="font-weight-bold table-info">
                                    <td class="text-right">Arus Kas Bersih dari Aktivitas Investasi</td>
                                    <td class="text-right">{{ format_currency($netInvestingCashFlow) }}</td>
                                </tr>

                                {{-- Financing Activities --}}
                                <tr class="table-primary">
                                    <td><strong>ARUS KAS DARI AKTIVITAS PENDANAAN</strong></td>
                                    <td></td>
                                </tr>
                                
                                {{-- Financing Activities --}}
                                @php 
                                    $totalFinancingInflows = 0; 
                                    $totalFinancingOutflows = 0;
                                @endphp
                                @if(isset($reportData['financing_activities']) && is_iterable($reportData['financing_activities']))
                                    @foreach($reportData['financing_activities'] as $activity)
                                        <tr>
                                            <td class="pl-4">{{ $activity['description'] ?? 'Aktivitas Pendanaan' }}</td>
                                            <td class="text-right">
                                                @if($activity['amount'] >= 0)
                                                    {{ format_currency($activity['amount']) }}
                                                    @php $totalFinancingInflows += $activity['amount']; @endphp
                                                @else
                                                    ({{ format_currency(abs($activity['amount'])) }})
                                                    @php $totalFinancingOutflows += abs($activity['amount']); @endphp
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif

                                {{-- Net Financing Cash Flow --}}
                                @php $netFinancingCashFlow = $reportData['net_financing_cash'] ?? 0; @endphp
                                <tr class="font-weight-bold table-info">
                                    <td class="text-right">Arus Kas Bersih dari Aktivitas Pendanaan</td>
                                    <td class="text-right">{{ format_currency($netFinancingCashFlow) }}</td>
                                </tr>

                                {{-- Net Cash Flow --}}
                @php $netCashFlow = $reportData['net_cash_change'] ?? ($netOperatingCashFlow + $netInvestingCashFlow + $netFinancingCashFlow); @endphp
                                <tr class="font-weight-bold bg-secondary text-white">
                                    <td class="text-right">Kenaikan (Penurunan) Kas Bersih</td>
                                    <td class="text-right">{{ format_currency($netCashFlow) }}</td>
                                </tr>

                                {{-- Beginning and Ending Cash --}}
                                @php 
                                    $beginningCash = $reportData['beginning_cash'] ?? 0;
                                    $endingCash = $reportData['ending_cash'] ?? ($beginningCash + $netCashFlow);
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

                    {{-- Summary Cards --}}
                    <div class="row mt-4">
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-info"><i class="fas fa-cogs"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Arus Kas Operasi</span>
                                    <span class="info-box-number">{{ format_currency($reportData['net_operating_cash'] ?? 0) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-warning"><i class="fas fa-chart-line"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Arus Kas Investasi</span>
                                    <span class="info-box-number">{{ format_currency($reportData['net_investing_cash'] ?? 0) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-success"><i class="fas fa-hand-holding-usd"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Arus Kas Pendanaan</span>
                                    <span class="info-box-number">{{ format_currency($reportData['net_financing_cash'] ?? 0) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-primary"><i class="fas fa-coins"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Kas Akhir Periode</span>
                                    <span class="info-box-number">{{ format_currency($reportData['ending_cash'] ?? 0) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Analysis Section --}}
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card card-outline card-info">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-chart-bar"></i> Analisis Arus Kas
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h5>Ringkasan Periode</h5>
                                            <p class="mb-0">
                                Perubahan kas bersih: {{ format_currency($reportData['net_cash_change'] ?? 0) }}
                                @if(($reportData['beginning_cash'] ?? 0) > 0)
                                    ({{ number_format((($reportData['net_cash_change'] ?? 0) / ($reportData['beginning_cash'] ?? 1)) * 100, 1) }}% dari kas awal)
                                @endif
                            </p>
                                            
                                            <p><strong>Rasio Arus Kas Operasi:</strong></p>
                                            <p>{{ ($reportData['net_operating_cash'] ?? 0) > 0 ? 'Positif' : 'Negatif' }}</p>
                                            
                                            <p><strong>Tren Kas:</strong></p>
                            <p>{{ ($reportData['net_cash_change'] ?? 0) > 0 ? 'Meningkat' : 'Menurun' }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <h5>Status Likuiditas</h5>
                                            <p class="mb-0">
                                @if(($reportData['net_cash_change'] ?? 0) > 0)
                                    <span class="badge badge-success">Positif</span> - Kas mengalami peningkatan
                                @elseif(($reportData['net_cash_change'] ?? 0) < 0)
                                    <span class="badge badge-warning">Negatif</span> - Kas mengalami penurunan
                                @else
                                    <span class="badge badge-info">Seimbang</span> - Tidak ada perubahan kas
                                @endif
                            </p>
                                            
                                            <p><strong>Sumber Kas Utama:</strong></p>
                                            @php
                                                $operatingCash = $reportData['net_operating_cash'] ?? 0;
                                                $investingCash = $reportData['net_investing_cash'] ?? 0;
                                                $financingCash = $reportData['net_financing_cash'] ?? 0;
                                                $maxCashFlow = max($operatingCash, $investingCash, $financingCash);
                                                if ($maxCashFlow == $operatingCash) {
                                                    echo 'Aktivitas Operasi';
                                                } elseif ($maxCashFlow == $investingCash) {
                                                    echo 'Aktivitas Investasi';
                                                } else {
                                                    echo 'Aktivitas Pendanaan';
                                                }
                                            @endphp
                                            
                                            <p><strong>Likuiditas:</strong></p>
                                            <p>{{ ($reportData['ending_cash'] ?? 0) > ($reportData['beginning_cash'] ?? 0) ? 'Membaik' : 'Menurun' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>

                    <!-- Simple View -->
                    <div id="simple-view" style="display: none;">
                        @include('financial-reports.partials.simple-cash-flow', [
                            'companyName' => company_info('name') ?? 'BUMDES',
                            'reportTitle' => 'LAPORAN ARUS KAS',
                            'reportPeriod' => \Carbon\Carbon::parse($periodStart)->format('d F Y') . ' s/d ' . \Carbon\Carbon::parse($periodEnd)->format('d F Y'),
                            'reportData' => $reportData,
                            'totalOperatingInflows' => $totalOperatingInflows ?? 0,
                            'totalOperatingOutflows' => $totalOperatingOutflows ?? 0,
                            'netOperatingCashFlow' => $netOperatingCashFlow ?? 0,
                            'totalInvestingInflows' => $totalInvestingInflows ?? 0,
                            'totalInvestingOutflows' => $totalInvestingOutflows ?? 0,
                            'netInvestingCashFlow' => $netInvestingCashFlow ?? 0,
                            'totalFinancingInflows' => $totalFinancingInflows ?? 0,
                            'totalFinancingOutflows' => $totalFinancingOutflows ?? 0,
                            'netFinancingCashFlow' => $netFinancingCashFlow ?? 0,
                            'netCashFlow' => $netCashFlow ?? 0,
                            'beginningCash' => $beginningCash ?? 0,
                            'endingCash' => $endingCash ?? 0
                        ])
                    </div>
                </div>
                <div class="card-footer">
                    <small class="text-muted">
                        Laporan dibuat pada: {{ now()->format('d F Y H:i:s') }}
                    </small>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
<style>
    @media print {
        .card-header .card-tools,
        .breadcrumb,
        .content-header,
        .card-footer {
            display: none !important;
        }
        
        .card {
            border: none !important;
            box-shadow: none !important;
        }
        
        .table {
            font-size: 12px;
        }
        
        .info-box {
            margin-bottom: 10px;
        }
    }
</style>
@stop

@section('js')
<script>
    // Print functionality
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

    // Date filter functions
    function setCurrentMonth() {
        const now = new Date();
        const firstDay = new Date(now.getFullYear(), now.getMonth(), 1);
        const lastDay = new Date(now.getFullYear(), now.getMonth() + 1, 0);
        
        document.getElementById('period_start').value = firstDay.toISOString().split('T')[0];
        document.getElementById('period_end').value = lastDay.toISOString().split('T')[0];
        
        // Auto submit form
        document.getElementById('dateFilterForm').submit();
    }

    function setCurrentYear() {
        const now = new Date();
        const firstDay = new Date(now.getFullYear(), 0, 1);
        const lastDay = new Date(now.getFullYear(), 11, 31);
        
        document.getElementById('period_start').value = firstDay.toISOString().split('T')[0];
        document.getElementById('period_end').value = lastDay.toISOString().split('T')[0];
        
        // Auto submit form
        document.getElementById('dateFilterForm').submit();
    }

    // Update export links when dates change
    function updateExportLinks() {
        const periodStart = document.getElementById('period_start').value;
        const periodEnd = document.getElementById('period_end').value;
        
        if (periodStart && periodEnd) {
            // Validate dates
            if (new Date(periodStart) > new Date(periodEnd)) {
                alert('Tanggal mulai tidak boleh lebih besar dari tanggal selesai');
                return;
            }
            
            // Update PDF export link
            const pdfLink = document.querySelector('a[href*="export-pdf"]');
            if (pdfLink) {
                const url = new URL(pdfLink.href);
                url.searchParams.set('period_start', periodStart);
                url.searchParams.set('period_end', periodEnd);
                pdfLink.href = url.toString();
            }
            
            // Update DOCX export link
            const docxLink = document.querySelector('a[href*="export-docx"]');
            if (docxLink) {
                const url = new URL(docxLink.href);
                url.searchParams.set('period_start', periodStart);
                url.searchParams.set('period_end', periodEnd);
                docxLink.href = url.toString();
            }
            
            // Update Excel export link
            const excelLink = document.querySelector('a[href*="export-excel"]');
            if (excelLink) {
                const url = new URL(excelLink.href);
                url.searchParams.set('period_start', periodStart);
                url.searchParams.set('period_end', periodEnd);
                excelLink.href = url.toString();
            }
        }
    }
    
    // Auto-refresh data every 5 minutes (optional)
    // setInterval(function() {
    //     location.reload();
    // }, 300000);

    $(document).ready(function() {
        // Set default style to standard
        toggleStyle('standard');
        
        // Update export links on page load
        updateExportLinks();
        
        // Update export links when date inputs change
        $('#period_start, #period_end').on('change', function() {
            updateExportLinks();
        });
        
        // Validate date range
        $('#period_start, #period_end').on('change', function() {
            const startDate = new Date(document.getElementById('period_start').value);
            const endDate = new Date(document.getElementById('period_end').value);
            
            if (startDate > endDate) {
                alert('Tanggal mulai tidak boleh lebih besar dari tanggal selesai!');
                return false;
            }
        });
    });
</script>
@stop