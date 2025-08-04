@extends('adminlte::page')

@section('title', 'Detail Laporan Keuangan')

@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h1>Detail Laporan Keuangan</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('financial-reports.index') }}">Daftar Laporan Keuangan</a></li>
                <li class="breadcrumb-item active">Detail Laporan</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $report->report_title }}</h3>
                    <div class="card-tools">
                        <span class="badge {{ $report->getStatusBadgeClass() }} badge-lg">
                            {{ $report->getStatusLabel() }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td><strong>Kode Laporan:</strong></td>
                                    <td>{{ $report->report_code }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Jenis Laporan:</strong></td>
                                    <td>
                                        <span class="badge badge-info">
                                            {{ $report->getReportTypeLabel() }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Periode:</strong></td>
                                    <td>{{ $report->getPeriodLabel() }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        <span class="badge {{ $report->getStatusBadgeClass() }}">
                                            {{ $report->getStatusLabel() }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td><strong>Dibuat Oleh:</strong></td>
                                    <td>
                                        @if($report->generatedBy)
                                            {{ $report->generatedBy->name }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Tanggal Dibuat:</strong></td>
                                    <td>{{ $report->generated_at ? $report->generated_at->format('d F Y H:i') : '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Difinalisasi Oleh:</strong></td>
                                    <td>
                                        @if($report->finalizedBy)
                                            {{ $report->finalizedBy->name }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Tanggal Final:</strong></td>
                                    <td>{{ $report->finalized_at ? $report->finalized_at->format('d F Y H:i') : '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($report->notes)
                        <div class="row">
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <h5><i class="icon fas fa-info"></i> Catatan</h5>
                                    {{ $report->notes }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            @if($report->status !== 'draft' && $report->report_data)
                <div class="card" id="report-content">
                    <div class="card-header text-center">
                        <h3 class="card-title">
                            <strong>{{ company_info('name') ?? 'BUMDES' }}</strong><br>
                            <span class="h4">{{ strtoupper($report->getReportTypeLabel()) }}</span><br>
                            <span class="h5">{{ $report->getPeriodLabel() }}</span>
                        </h3>
                    </div>
                    <div class="card-body">
                        @if($report->report_type === 'income_statement')
                            @include('financial-reports.partials.income-statement', ['data' => $report->report_data])
                        @elseif($report->report_type === 'balance_sheet')
                            @include('financial-reports.partials.balance-sheet', ['data' => $report->report_data])
                        @elseif($report->report_type === 'cash_flow')
                            @include('financial-reports.partials.cash-flow', ['data' => $report->report_data])
                        @elseif($report->report_type === 'trial_balance')
                            @include('financial-reports.partials.trial-balance', ['data' => $report->report_data])
                        @elseif($report->report_type === 'general_ledger')
                            @include('financial-reports.partials.general-ledger', ['data' => $report->report_data])
                        @endif
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-md-6">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle"></i>
                                    Laporan dibuat pada: {{ $report->generated_at ? $report->generated_at->format('d F Y H:i') : '-' }}
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
            @else
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Data Laporan Belum Tersedia</h5>
                        <p class="text-muted">
                            @if($report->status === 'draft')
                                Laporan masih dalam status draft. Generate laporan untuk melihat data.
                            @else
                                Data laporan tidak tersedia atau belum di-generate.
                            @endif
                        </p>
                        @if($report->status === 'draft')
                            <form action="{{ route('financial-reports.regenerate', $report) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-sync"></i> Generate Laporan
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Aksi</h3>
                </div>
                <div class="card-body">
                    @if($report->status !== 'finalized')
                        <a href="{{ route('financial-reports.edit', $report) }}" class="btn btn-warning btn-block mb-2">
                            <i class="fas fa-edit"></i> Edit Laporan
                        </a>
                    @endif

                    @if($report->status === 'generated')
                        <form action="{{ route('financial-reports.finalize', $report) }}" method="POST" class="mb-2">
                            @csrf
                            <button type="submit" class="btn btn-success btn-block" 
                                    onclick="return confirm('Yakin ingin memfinalisasi laporan ini? Laporan yang sudah final tidak dapat diubah.')">
                                <i class="fas fa-check"></i> Finalisasi Laporan
                            </button>
                        </form>
                    @endif

                    @if($report->status === 'generated' || $report->status === 'finalized')
                        <form action="{{ route('financial-reports.regenerate', $report) }}" method="POST" class="mb-2">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-sync"></i> Regenerate Data
                            </button>
                        </form>
                    @endif

                    @if($report->status !== 'draft' && $report->report_data)
                        <!-- Export Dropdown -->
                        <div class="btn-group btn-block mb-2" role="group">
                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-download"></i> Export
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="{{ route('financial-reports.export-pdf', $report) }}">
                                    <i class="fas fa-file-pdf text-danger"></i> Export PDF
                                </a>
                                <a class="dropdown-item" href="{{ route('financial-reports.export-docx', $report) }}">
                                    <i class="fas fa-file-word text-primary"></i> Export DOC
                                </a>
                                <a class="dropdown-item" href="{{ route('financial-reports.export-excel', $report) }}">
                                    <i class="fas fa-file-excel text-success"></i> Export Excel
                                </a>
                            </div>
                        </div>
                        
                        <button type="button" class="btn btn-info btn-block mb-2" onclick="printReport()">
                            <i class="fas fa-print"></i> Cetak Laporan
                        </button>
                    @endif

                    @if($report->status !== 'finalized')
                        <form action="{{ route('financial-reports.destroy', $report) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-block" 
                                    onclick="return confirm('Yakin ingin menghapus laporan ini?')">
                                <i class="fas fa-trash"></i> Hapus Laporan
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            @if($report->report_parameters)
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Parameter Laporan</h3>
                    </div>
                    <div class="card-body">
                        @foreach($report->report_parameters as $key => $value)
                            <div class="row mb-2">
                                <div class="col-6">
                                    <strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong>
                                </div>
                                <div class="col-6">
                                    {{ is_array($value) ? implode(', ', $value) : $value }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informasi Sistem</h3>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td><strong>Dibuat:</strong></td>
                            <td>{{ $report->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Diperbarui:</strong></td>
                            <td>{{ $report->updated_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        @if($report->report_data)
                            <tr>
                                <td><strong>Ukuran Data:</strong></td>
                                <td>{{ number_format(strlen(json_encode($report->report_data)) / 1024, 2) }} KB</td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Laporan Lainnya</h3>
                </div>
                <div class="card-body">
                    <a href="{{ route('reports.income-statement') }}" class="btn btn-block btn-outline-primary btn-sm mb-2">
                        <i class="fas fa-chart-line"></i> Laporan Laba Rugi
                    </a>
                    <a href="{{ route('reports.balance-sheet') }}" class="btn btn-block btn-outline-success btn-sm mb-2">
                        <i class="fas fa-balance-scale"></i> Neraca
                    </a>
                    <a href="{{ route('reports.cash-flow') }}" class="btn btn-block btn-outline-info btn-sm mb-2">
                        <i class="fas fa-money-bill-wave"></i> Arus Kas
                    </a>
                    <a href="{{ route('trial-balance') }}" class="btn btn-block btn-outline-warning btn-sm">
                        <i class="fas fa-calculator"></i> Neraca Saldo
                    </a>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .badge-lg {
            font-size: 1rem;
            padding: 0.5rem 1rem;
        }
        .table-borderless td {
            border: none;
            padding: 0.25rem 0.5rem;
        }
        @media print {
            .card-header .btn, .card-footer, .breadcrumb, .content-header, .col-md-4 {
                display: none !important;
            }
            .col-md-8 {
                width: 100% !important;
                max-width: 100% !important;
            }
            .card {
                border: none !important;
                box-shadow: none !important;
            }
            .card-body {
                padding: 0 !important;
            }
            #report-content {
                page-break-inside: avoid;
            }
        }
    </style>
@stop

@section('js')
    <script>
        function printReport() {
            window.print();
        }
    </script>
@stop