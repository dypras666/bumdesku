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
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-exchange-alt"></i> Laporan Arus Kas
                    </h3>
                    <div class="card-tools">
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
                                
                                {{-- Operating Cash Inflows --}}
                                <tr class="bg-light">
                                    <td class="pl-3"><strong>Penerimaan Kas dari Operasi:</strong></td>
                                    <td></td>
                                </tr>
                                @php $totalOperatingInflows = 0; @endphp
                                @if(isset($reportData['operating_activities']))
                                    @foreach($reportData['operating_activities']->where('debit', '>', 0) as $activity)
                                        <tr>
                                            <td class="pl-4">{{ $activity->description ?? 'Penerimaan Operasional' }}</td>
                                            <td class="text-right">{{ format_currency($activity->debit) }}</td>
                                        </tr>
                                        @php $totalOperatingInflows += $activity->debit; @endphp
                                    @endforeach
                                @endif
                                <tr class="font-weight-bold">
                                    <td class="text-right pl-3">Total Penerimaan Kas dari Operasi</td>
                                    <td class="text-right">{{ format_currency($totalOperatingInflows) }}</td>
                                </tr>

                                {{-- Operating Cash Outflows --}}
                                <tr class="bg-light">
                                    <td class="pl-3"><strong>Pengeluaran Kas untuk Operasi:</strong></td>
                                    <td></td>
                                </tr>
                                @php $totalOperatingOutflows = 0; @endphp
                                @if(isset($reportData['operating_activities']))
                                    @foreach($reportData['operating_activities']->where('credit', '>', 0) as $activity)
                                        <tr>
                                            <td class="pl-4">{{ $activity->description ?? 'Pengeluaran Operasional' }}</td>
                                            <td class="text-right">({{ format_currency($activity->credit) }})</td>
                                        </tr>
                                        @php $totalOperatingOutflows += $activity->credit; @endphp
                                    @endforeach
                                @endif
                                <tr class="font-weight-bold">
                                    <td class="text-right pl-3">Total Pengeluaran Kas untuk Operasi</td>
                                    <td class="text-right">({{ format_currency($totalOperatingOutflows) }})</td>
                                </tr>

                                {{-- Net Operating Cash Flow --}}
                                @php $netOperatingCashFlow = $totalOperatingInflows - $totalOperatingOutflows; @endphp
                                <tr class="font-weight-bold table-info">
                                    <td class="text-right">Arus Kas Bersih dari Aktivitas Operasi</td>
                                    <td class="text-right">{{ format_currency($netOperatingCashFlow) }}</td>
                                </tr>

                                {{-- Investing Activities --}}
                                <tr class="table-primary">
                                    <td><strong>ARUS KAS DARI AKTIVITAS INVESTASI</strong></td>
                                    <td></td>
                                </tr>
                                
                                {{-- Investing Cash Inflows --}}
                                <tr class="bg-light">
                                    <td class="pl-3"><strong>Penerimaan Kas dari Investasi:</strong></td>
                                    <td></td>
                                </tr>
                                @php $totalInvestingInflows = 0; @endphp
                                @if(isset($reportData['investing_activities']))
                                    @foreach($reportData['investing_activities']->where('debit', '>', 0) as $activity)
                                        <tr>
                                            <td class="pl-4">{{ $activity->description ?? 'Penerimaan Investasi' }}</td>
                                            <td class="text-right">{{ format_currency($activity->debit) }}</td>
                                        </tr>
                                        @php $totalInvestingInflows += $activity->debit; @endphp
                                    @endforeach
                                @endif
                                <tr class="font-weight-bold">
                                    <td class="text-right pl-3">Total Penerimaan Kas dari Investasi</td>
                                    <td class="text-right">{{ format_currency($totalInvestingInflows) }}</td>
                                </tr>

                                {{-- Investing Cash Outflows --}}
                                <tr class="bg-light">
                                    <td class="pl-3"><strong>Pengeluaran Kas untuk Investasi:</strong></td>
                                    <td></td>
                                </tr>
                                @php $totalInvestingOutflows = 0; @endphp
                                @if(isset($reportData['investing_activities']))
                                    @foreach($reportData['investing_activities']->where('credit', '>', 0) as $activity)
                                        <tr>
                                            <td class="pl-4">{{ $activity->description ?? 'Pengeluaran Investasi' }}</td>
                                            <td class="text-right">({{ format_currency($activity->credit) }})</td>
                                        </tr>
                                        @php $totalInvestingOutflows += $activity->credit; @endphp
                                    @endforeach
                                @endif
                                <tr class="font-weight-bold">
                                    <td class="text-right pl-3">Total Pengeluaran Kas untuk Investasi</td>
                                    <td class="text-right">({{ format_currency($totalInvestingOutflows) }})</td>
                                </tr>

                                {{-- Net Investing Cash Flow --}}
                                @php $netInvestingCashFlow = $totalInvestingInflows - $totalInvestingOutflows; @endphp
                                <tr class="font-weight-bold table-info">
                                    <td class="text-right">Arus Kas Bersih dari Aktivitas Investasi</td>
                                    <td class="text-right">{{ format_currency($netInvestingCashFlow) }}</td>
                                </tr>

                                {{-- Financing Activities --}}
                                <tr class="table-primary">
                                    <td><strong>ARUS KAS DARI AKTIVITAS PENDANAAN</strong></td>
                                    <td></td>
                                </tr>
                                
                                {{-- Financing Cash Inflows --}}
                                <tr class="bg-light">
                                    <td class="pl-3"><strong>Penerimaan Kas dari Pendanaan:</strong></td>
                                    <td></td>
                                </tr>
                                @php $totalFinancingInflows = 0; @endphp
                                @if(isset($reportData['financing_activities']))
                                    @foreach($reportData['financing_activities']->where('debit', '>', 0) as $activity)
                                        <tr>
                                            <td class="pl-4">{{ $activity->description ?? 'Penerimaan Pendanaan' }}</td>
                                            <td class="text-right">{{ format_currency($activity->debit) }}</td>
                                        </tr>
                                        @php $totalFinancingInflows += $activity->debit; @endphp
                                    @endforeach
                                @endif
                                <tr class="font-weight-bold">
                                    <td class="text-right pl-3">Total Penerimaan Kas dari Pendanaan</td>
                                    <td class="text-right">{{ format_currency($totalFinancingInflows) }}</td>
                                </tr>

                                {{-- Financing Cash Outflows --}}
                                <tr class="bg-light">
                                    <td class="pl-3"><strong>Pengeluaran Kas untuk Pendanaan:</strong></td>
                                    <td></td>
                                </tr>
                                @php $totalFinancingOutflows = 0; @endphp
                                @if(isset($reportData['financing_activities']))
                                    @foreach($reportData['financing_activities']->where('credit', '>', 0) as $activity)
                                        <tr>
                                            <td class="pl-4">{{ $activity->description ?? 'Pengeluaran Pendanaan' }}</td>
                                            <td class="text-right">({{ format_currency($activity->credit) }})</td>
                                        </tr>
                                        @php $totalFinancingOutflows += $activity->credit; @endphp
                                    @endforeach
                                @endif
                                <tr class="font-weight-bold">
                                    <td class="text-right pl-3">Total Pengeluaran Kas untuk Pendanaan</td>
                                    <td class="text-right">({{ format_currency($totalFinancingOutflows) }})</td>
                                </tr>

                                {{-- Net Financing Cash Flow --}}
                                @php $netFinancingCashFlow = $totalFinancingInflows - $totalFinancingOutflows; @endphp
                                <tr class="font-weight-bold table-info">
                                    <td class="text-right">Arus Kas Bersih dari Aktivitas Pendanaan</td>
                                    <td class="text-right">{{ format_currency($netFinancingCashFlow) }}</td>
                                </tr>

                                {{-- Net Cash Flow --}}
                                @php $netCashFlow = $netOperatingCashFlow + $netInvestingCashFlow + $netFinancingCashFlow; @endphp
                                <tr class="font-weight-bold bg-secondary text-white">
                                    <td class="text-right">Kenaikan (Penurunan) Kas Bersih</td>
                                    <td class="text-right">{{ format_currency($netCashFlow) }}</td>
                                </tr>

                                {{-- Beginning and Ending Cash --}}
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

                    {{-- Summary Cards --}}
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
                                                Perubahan kas bersih: {{ format_currency($netCashFlow) }}
                                                @if($beginningCash > 0)
                                                    ({{ number_format(($netCashFlow / $beginningCash) * 100, 1) }}% dari kas awal)
                                                @endif
                                            </p>
                                        </div>
                                        <div class="col-md-6">
                                            <h5>Status Likuiditas</h5>
                                            <p class="mb-0">
                                                @if($netCashFlow > 0)
                                                    <span class="badge badge-success">Positif</span> - Kas mengalami peningkatan
                                                @elseif($netCashFlow < 0)
                                                    <span class="badge badge-warning">Negatif</span> - Kas mengalami penurunan
                                                @else
                                                    <span class="badge badge-info">Seimbang</span> - Tidak ada perubahan kas
                                                @endif
                                            </p>
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
    
    // Auto-refresh data every 5 minutes (optional)
    // setInterval(function() {
    //     location.reload();
    // }, 300000);

    $(document).ready(function() {
        // Set default style to standard
        toggleStyle('standard');
    });
</script>
@stop