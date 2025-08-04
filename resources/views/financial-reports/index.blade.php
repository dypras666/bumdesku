@extends('adminlte::page')

@section('title', 'Daftar Laporan Keuangan')

@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h1>Daftar Laporan Keuangan</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Daftar Laporan Keuangan</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Filter & Pencarian</h3>
                    <div class="card-tools">
                        <a href="{{ route('financial-reports.annual') }}" class="btn btn-success btn-sm mr-2">
                            <i class="fas fa-book"></i> Laporan Tahunan
                        </a>
                        <a href="{{ route('financial-reports.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Buat Laporan Baru
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('financial-reports.index') }}">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="report_type">Jenis Laporan</label>
                                    <select name="report_type" id="report_type" class="form-control">
                                        <option value="">Semua Jenis</option>
                                        <option value="income_statement" {{ request('report_type') == 'income_statement' ? 'selected' : '' }}>Laporan Laba Rugi</option>
                                        <option value="balance_sheet" {{ request('report_type') == 'balance_sheet' ? 'selected' : '' }}>Neraca</option>
                                        <option value="cash_flow" {{ request('report_type') == 'cash_flow' ? 'selected' : '' }}>Arus Kas</option>
                                        <option value="trial_balance" {{ request('report_type') == 'trial_balance' ? 'selected' : '' }}>Neraca Saldo</option>
                                        <option value="general_ledger" {{ request('report_type') == 'general_ledger' ? 'selected' : '' }}>Buku Besar</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="">Semua Status</option>
                                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                        <option value="generated" {{ request('status') == 'generated' ? 'selected' : '' }}>Dibuat</option>
                                        <option value="finalized" {{ request('status') == 'finalized' ? 'selected' : '' }}>Final</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="period_start">Periode Mulai</label>
                                    <input type="date" name="period_start" id="period_start" class="form-control" 
                                           value="{{ request('period_start') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="period_end">Periode Akhir</label>
                                    <input type="date" name="period_end" id="period_end" class="form-control" 
                                           value="{{ request('period_end') }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="search">Pencarian</label>
                                    <input type="text" name="search" id="search" class="form-control" 
                                           placeholder="Cari berdasarkan kode atau judul laporan..." 
                                           value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>&nbsp;</label><br>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search"></i> Cari
                                    </button>
                                    <a href="{{ route('financial-reports.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Reset
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        Daftar Laporan 
                        <span class="badge badge-info">{{ $reports->total() }} laporan</span>
                    </h3>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>Kode Laporan</th>
                                <th>Jenis Laporan</th>
                                <th>Judul</th>
                                <th>Periode</th>
                                <th>Status</th>
                                <th>Dibuat Oleh</th>
                                <th>Tanggal Dibuat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reports as $report)
                                <tr>
                                    <td>
                                        <a href="{{ route('financial-reports.show', $report) }}" class="text-primary">
                                            {{ $report->report_code }}
                                        </a>
                                    </td>
                                    <td>
                                        <span class="badge badge-info">
                                            {{ $report->getReportTypeLabel() }}
                                        </span>
                                    </td>
                                    <td>{{ $report->report_title }}</td>
                                    <td>
                                        <small>
                                            {{ $report->getPeriodLabel() }}
                                        </small>
                                    </td>
                                    <td>
                                        <span class="badge {{ $report->getStatusBadgeClass() }}">
                                            {{ $report->getStatusLabel() }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($report->generatedBy)
                                            {{ $report->generatedBy->name }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small>{{ $report->generated_at ? $report->generated_at->format('d/m/Y H:i') : '-' }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('financial-reports.show', $report) }}" 
                                               class="btn btn-info btn-sm" title="Lihat">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            
                                            @if($report->status !== 'finalized')
                                                <a href="{{ route('financial-reports.edit', $report) }}" 
                                                   class="btn btn-warning btn-sm" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                
                                                @if($report->status === 'generated')
                                                    <form action="{{ route('financial-reports.finalize', $report) }}" 
                                                          method="POST" style="display: inline;">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-success btn-sm" 
                                                                title="Finalisasi" 
                                                                onclick="return confirm('Yakin ingin memfinalisasi laporan ini? Laporan yang sudah final tidak dapat diubah.')">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            @endif
                                            
                                            @if($report->status === 'generated' || $report->status === 'finalized')
                                                <form action="{{ route('financial-reports.regenerate', $report) }}" 
                                                      method="POST" style="display: inline;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-primary btn-sm" 
                                                            title="Regenerate">
                                                        <i class="fas fa-sync"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            
                                            @if($report->status !== 'finalized')
                                                <form action="{{ route('financial-reports.destroy', $report) }}" 
                                                      method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" 
                                                            title="Hapus"
                                                            onclick="return confirm('Yakin ingin menghapus laporan ini?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <i class="fas fa-info-circle fa-2x text-muted mb-2"></i>
                                        <br>
                                        <span class="text-muted">Tidak ada laporan keuangan ditemukan</span>
                                        <br>
                                        <a href="{{ route('financial-reports.create') }}" class="btn btn-primary btn-sm mt-2">
                                            <i class="fas fa-plus"></i> Buat Laporan Pertama
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($reports->hasPages())
                    <div class="card-footer">
                        {{ $reports->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Actions Card -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Aksi Cepat</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <a href="{{ route('reports.income-statement') }}" class="btn btn-block btn-outline-primary">
                                <i class="fas fa-chart-line"></i><br>
                                Laporan Laba Rugi
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('reports.balance-sheet') }}" class="btn btn-block btn-outline-success">
                                <i class="fas fa-balance-scale"></i><br>
                                Neraca
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('reports.cash-flow') }}" class="btn btn-block btn-outline-info">
                                <i class="fas fa-money-bill-wave"></i><br>
                                Arus Kas
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('trial-balance') }}" class="btn btn-block btn-outline-warning">
                                <i class="fas fa-calculator"></i><br>
                                Neraca Saldo
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .table th, .table td {
            vertical-align: middle;
        }
        .btn-group-sm > .btn {
            margin-right: 2px;
        }
        .btn-group-sm > .btn:last-child {
            margin-right: 0;
        }
    </style>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            // Auto submit form when filter changes
            $('#report_type, #status').change(function() {
                $(this).closest('form').submit();
            });
        });
    </script>
@stop