@extends('adminlte::page')

@section('title', 'Daftar Pinjaman Modal')

@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h1>Daftar Pinjaman Modal</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Daftar Pinjaman Modal</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $statistics['total_loans'] }}</h3>
                    <p>Total Pinjaman</p>
                </div>
                <div class="icon">
                    <i class="fas fa-hand-holding-usd"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $statistics['active_loans'] }}</h3>
                    <p>Pinjaman Aktif</p>
                </div>
                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $statistics['overdue_loans'] }}</h3>
                    <p>Pinjaman Terlambat</p>
                </div>
                <div class="icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>{{ number_format($statistics['total_amount'], 0, ',', '.') }}</h3>
                    <p>Total Nilai Pinjaman</p>
                </div>
                <div class="icon">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Loan Type Statistics -->
    <div class="row">
        <div class="col-lg-4 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $statistics['bunga_loans'] }}</h3>
                    <p>Pinjaman dengan Bunga</p>
                    <small>Total: Rp {{ number_format($statistics['bunga_amount'], 0, ',', '.') }}</small>
                </div>
                <div class="icon">
                    <i class="fas fa-percentage"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $statistics['bagi_hasil_loans'] }}</h3>
                    <p>Pinjaman Bagi Hasil</p>
                    <small>Total: Rp {{ number_format($statistics['bagi_hasil_amount'], 0, ',', '.') }}</small>
                </div>
                <div class="icon">
                    <i class="fas fa-handshake"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $statistics['tanpa_bunga_loans'] }}</h3>
                    <p>Pinjaman Tanpa Bunga</p>
                    <small>Total: Rp {{ number_format($statistics['tanpa_bunga_amount'], 0, ',', '.') }}</small>
                </div>
                <div class="icon">
                    <i class="fas fa-heart"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Filter Pinjaman</h3>
            <div class="card-tools">
                <a href="{{ route('loans.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Tambah Pinjaman
                </a>
            </div>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('loans.index') }}">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                <option value="">Semua Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu Persetujuan</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Lunas</option>
                                <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Terlambat</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Jenis Pinjaman</label>
                            <select name="loan_type" class="form-control">
                                <option value="">Semua Jenis</option>
                                <option value="bunga" {{ request('loan_type') == 'bunga' ? 'selected' : '' }}>Pinjaman dengan Bunga</option>
                                <option value="bagi_hasil" {{ request('loan_type') == 'bagi_hasil' ? 'selected' : '' }}>Pinjaman Bagi Hasil</option>
                                <option value="tanpa_bunga" {{ request('loan_type') == 'tanpa_bunga' ? 'selected' : '' }}>Pinjaman Tanpa Bunga</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Nama Peminjam</label>
                            <input type="text" name="borrower_name" class="form-control" 
                                   value="{{ request('borrower_name') }}" placeholder="Cari nama peminjam...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Tanggal Mulai</label>
                            <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Tanggal Akhir</label>
                            <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-info">
                            <i class="fas fa-search"></i> Filter
                        </button>
                        <a href="{{ route('loans.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Loans Table -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Data Pinjaman</h3>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Pinjaman</th>
                        <th>Nama Peminjam</th>
                        <th>No. HP</th>
                        <th>Jenis Pinjaman</th>
                        <th>Jumlah Pinjaman</th>
                        <th>Sisa Pinjaman</th>
                        <th>Angsuran/Bulan</th>
                        <th>Jangka Waktu</th>
                        <th>Tanggal Pinjaman</th>
                        <th>Status</th>
                        <th>Telat (Hari)</th>
                        <th>Progress</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($loans as $loan)
                        <tr>
                            <td>{{ $loans->firstItem() + $loop->index }}</td>
                            <td>{{ $loan->loan_code }}</td>
                            <td>{{ $loan->borrower_name }}</td>
                            <td>{{ $loan->borrower_phone }}</td>
                            <td>
                                <span class="badge 
                                    @if($loan->loan_type == 'bunga') badge-info
                                    @elseif($loan->loan_type == 'bagi_hasil') badge-warning
                                    @else badge-success
                                    @endif">
                                    {{ $loan->loan_type_name }}
                                </span>
                            </td>
                            <td>{{ $loan->formatted_loan_amount }}</td>
                            <td>
                                @if($loan->status == 'completed')
                                    <span class="text-success font-weight-bold">Lunas</span>
                                @else
                                    {{ $loan->formatted_remaining_balance }}
                                @endif
                            </td>
                            <td>{{ $loan->formatted_monthly_payment }}</td>
                            <td>{{ $loan->loan_term_months }} bulan</td>
                            <td>{{ $loan->formatted_loan_date }}</td>
                            <td>
                                @if($loan->status == 'pending')
                                    <span class="badge badge-warning">Menunggu Persetujuan</span>
                                @elseif($loan->status == 'approved')
                                    <span class="badge badge-info">Disetujui</span>
                                @elseif($loan->status == 'active')
                                    <span class="badge badge-success">Aktif</span>
                                @elseif($loan->status == 'completed')
                                    <span class="badge badge-primary">Lunas</span>
                                @elseif($loan->status == 'overdue')
                                    <span class="badge badge-danger">Terlambat</span>
                                @endif
                            </td>
                            <td>
                                @if($loan->status == 'overdue')
                                    <span class="badge badge-danger">{{ $loan->days_overdue }} hari</span>
                                @elseif($loan->status == 'completed')
                                    <span class="text-muted">-</span>
                                @else
                                    <span class="text-muted">0 hari</span>
                                @endif
                            </td>
                            <td>
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar" role="progressbar" 
                                         style="width: {{ $loan->progress_percentage }}%"
                                         aria-valuenow="{{ $loan->progress_percentage }}" 
                                         aria-valuemin="0" aria-valuemax="100">
                                        {{ number_format($loan->progress_percentage, 1) }}%
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('loans.show', $loan->id) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($loan->status == 'pending')
                                        <a href="{{ route('loans.edit', $loan->id) }}" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('loans.approve', $loan->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-success btn-sm" 
                                                    onclick="return confirm('Apakah Anda yakin ingin menyetujui pinjaman ini?')">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    @endif
                                    @if(in_array($loan->status, ['pending', 'approved']))
                                        <form action="{{ route('loans.destroy', $loan->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" 
                                                    onclick="return confirm('Apakah Anda yakin ingin menghapus pinjaman ini?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="14" class="text-center">Tidak ada data pinjaman</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($loans->hasPages())
            <div class="card-footer">
                {{ $loans->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
@stop

@section('css')
    <style>
        .small-box .inner h3 {
            font-size: 2.2rem;
        }
        .progress {
            background-color: #f4f4f4;
        }
        .progress-bar {
            background-color: #007bff;
        }
    </style>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            // Auto-submit form when status changes
            $('select[name="status"]').change(function() {
                $(this).closest('form').submit();
            });
        });
    </script>
@stop