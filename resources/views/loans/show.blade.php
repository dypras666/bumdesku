@extends('adminlte::page')

@section('title', 'Detail Pinjaman Modal')

@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h1>Detail Pinjaman Modal</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('loans.index') }}">Daftar Pinjaman</a></li>
                <li class="breadcrumb-item active">Detail Pinjaman</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <!-- Loan Information -->
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informasi Pinjaman</h3>
                    <div class="card-tools">
                        <a href="{{ route('loans.print-agreement', $loan->id) }}" class="btn btn-info btn-sm" target="_blank">
                            <i class="fas fa-print"></i> Cetak Surat Perjanjian
                        </a>
                        @if($loan->status == 'pending')
                            <a href="{{ route('loans.edit', $loan->id) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form action="{{ route('loans.approve', $loan->id) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm" 
                                        onclick="return confirm('Apakah Anda yakin ingin menyetujui pinjaman ini?')">
                                    <i class="fas fa-check"></i> Setujui
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Kode Pinjaman:</strong></td>
                                    <td>{{ $loan->loan_code }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Nama Peminjam:</strong></td>
                                    <td>{{ $loan->borrower_name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>No. HP:</strong></td>
                                    <td>{{ $loan->borrower_phone }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Alamat:</strong></td>
                                    <td>{{ $loan->borrower_address ?: '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>No. KTP:</strong></td>
                                    <td>{{ $loan->borrower_id_number ?: '-' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Jenis Pinjaman:</strong></td>
                                    <td>
                                        <span class="badge 
                                            @if($loan->loan_type == 'bunga') badge-info
                                            @elseif($loan->loan_type == 'bagi_hasil') badge-warning
                                            @else badge-success
                                            @endif">
                                            {{ $loan->loan_type_name }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Jumlah Pinjaman:</strong></td>
                                    <td>{{ $loan->formatted_loan_amount }}</td>
                                </tr>
                                @if($loan->loan_type == 'bunga')
                                    <tr>
                                        <td><strong>Suku Bunga:</strong></td>
                                        <td>{{ $loan->interest_rate }}% per tahun</td>
                                    </tr>
                                @elseif($loan->loan_type == 'bagi_hasil')
                                    <tr>
                                        <td><strong>Persentase Bagi Hasil:</strong></td>
                                        <td>{{ $loan->formatted_profit_sharing_percentage }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Perkiraan Keuntungan:</strong></td>
                                        <td>{{ $loan->formatted_expected_profit }}</td>
                                    </tr>
                                @endif
                                @if($loan->admin_fee > 0)
                                    <tr>
                                        <td><strong>Biaya Administrasi:</strong></td>
                                        <td>{{ $loan->formatted_admin_fee }}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <td><strong>Jangka Waktu:</strong></td>
                                    <td>{{ $loan->loan_term_months }} bulan</td>
                                </tr>
                                <tr>
                                    <td><strong>Angsuran/Bulan:</strong></td>
                                    <td>{{ $loan->formatted_monthly_payment }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tanggal Pinjaman:</strong></td>
                                    <td>{{ $loan->formatted_loan_date }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Jatuh Tempo:</strong></td>
                                    <td>{{ $loan->formatted_due_date }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    @if($loan->loan_type == 'bagi_hasil' && $loan->business_description)
                        <div class="row">
                            <div class="col-md-12">
                                <hr>
                                <strong>Deskripsi Usaha:</strong>
                                <p>{{ $loan->business_description }}</p>
                            </div>
                        </div>
                    @endif
                    
                    @if($loan->notes)
                        <div class="row">
                            <div class="col-md-12">
                                <hr>
                                <strong>Catatan:</strong>
                                <p>{{ $loan->notes }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Status Card -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Status Pinjaman</h3>
                </div>
                <div class="card-body text-center">
                    @if($loan->status == 'pending')
                        <span class="badge badge-warning badge-lg">Menunggu Persetujuan</span>
                    @elseif($loan->status == 'approved')
                        <span class="badge badge-info badge-lg">Disetujui</span>
                    @elseif($loan->status == 'active')
                        <span class="badge badge-success badge-lg">Aktif</span>
                    @elseif($loan->status == 'completed')
                        <span class="badge badge-primary badge-lg">Lunas</span>
                    @elseif($loan->status == 'overdue')
                        <span class="badge badge-danger badge-lg">Terlambat</span>
                    @endif
                    
                    <div class="mt-3">
                        <div class="progress" style="height: 25px;">
                            <div class="progress-bar" role="progressbar" 
                                 style="width: {{ $loan->progress_percentage }}%"
                                 aria-valuenow="{{ $loan->progress_percentage }}" 
                                 aria-valuemin="0" aria-valuemax="100">
                                {{ number_format($loan->progress_percentage, 1) }}%
                            </div>
                        </div>
                        <small class="text-muted">Progress Pembayaran</small>
                    </div>
                </div>
            </div>

            <!-- Summary Card -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Ringkasan Pembayaran</h3>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td>Total Dibayar:</td>
                            <td class="text-right"><strong>{{ $loan->formatted_total_paid }}</strong></td>
                        </tr>
                        <tr>
                            <td>Sisa Pinjaman:</td>
                            <td class="text-right"><strong>{{ $loan->formatted_remaining_balance }}</strong></td>
                        </tr>
                        @if($loan->status == 'active' && $loan->next_payment_date)
                            <tr>
                                <td>Pembayaran Berikutnya:</td>
                                <td class="text-right">{{ $loan->next_payment_date->format('d/m/Y') }}</td>
                            </tr>
                        @endif
                        @if($loan->days_overdue > 0)
                            <tr>
                                <td>Hari Terlambat:</td>
                                <td class="text-right text-danger"><strong>{{ $loan->days_overdue }} hari</strong></td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment History -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Riwayat Pembayaran</h3>
            <div class="card-tools">
                @if(in_array($loan->status, ['active', 'overdue']))
                    <a href="{{ route('loan-payments.create', ['loan_id' => $loan->id]) }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Tambah Pembayaran
                    </a>
                @endif
            </div>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Kode Pembayaran</th>
                        <th>Tanggal</th>
                        <th>Angsuran Ke-</th>
                        <th>Jumlah Bayar</th>
                        <th>Pokok</th>
                        <th>Bunga</th>
                        <th>Denda</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($loan->payments as $payment)
                        <tr>
                            <td>{{ $payment->payment_code }}</td>
                            <td>{{ $payment->formatted_payment_date }}</td>
                            <td>{{ $payment->installment_number }}</td>
                            <td>{{ $payment->formatted_payment_amount }}</td>
                            <td>{{ $payment->formatted_principal_amount }}</td>
                            <td>{{ $payment->formatted_interest_amount }}</td>
                            <td>{{ $payment->formatted_penalty_amount }}</td>
                            <td>
                                @if($payment->status == 'pending')
                                    <span class="badge badge-warning">Menunggu</span>
                                @elseif($payment->status == 'approved')
                                    <span class="badge badge-success">Disetujui</span>
                                @elseif($payment->status == 'rejected')
                                    <span class="badge badge-danger">Ditolak</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('loan-payments.show', $payment->id) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($payment->status == 'pending')
                                    <form action="{{ route('loan-payments.approve', $payment->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm" 
                                                onclick="return confirm('Setujui pembayaran ini?')">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">Belum ada pembayaran</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Payment Schedule -->
    @if($loan->status != 'pending')
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Jadwal Pembayaran</h3>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Angsuran Ke-</th>
                            <th>Tanggal Jatuh Tempo</th>
                            <th>Saldo Awal</th>
                            <th>Angsuran</th>
                            <th>Pokok</th>
                            <th>Bunga</th>
                            <th>Saldo Akhir</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($paymentSchedule as $schedule)
                            <tr class="{{ $schedule['status'] == 'paid' ? 'table-success' : ($schedule['payment_date']->isPast() && $schedule['status'] != 'paid' ? 'table-danger' : '') }}">
                                <td>{{ $schedule['installment'] }}</td>
                                <td>{{ $schedule['payment_date']->format('d/m/Y') }}</td>
                                <td>{{ number_format($schedule['remaining_balance'] + $schedule['principal_amount'], 0, ',', '.') }}</td>
                                <td>{{ number_format($schedule['total_payment'], 0, ',', '.') }}</td>
                                <td>{{ number_format($schedule['principal_amount'], 0, ',', '.') }}</td>
                                <td>{{ number_format($schedule['interest_amount'], 0, ',', '.') }}</td>
                                <td>{{ number_format($schedule['remaining_balance'], 0, ',', '.') }}</td>
                                <td>
                                    @if($schedule['status'] == 'paid')
                                        <span class="badge badge-success">Lunas</span>
                                    @elseif($schedule['status'] == 'pending')
                                        <span class="badge badge-warning">Menunggu</span>
                                    @elseif($schedule['payment_date']->isPast())
                                        <span class="badge badge-danger">Terlambat</span>
                                    @else
                                        <span class="badge badge-secondary">Belum Bayar</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@stop

@section('css')
    <style>
        .badge-lg {
            font-size: 1.1em;
            padding: 0.5em 1em;
        }
        .progress {
            background-color: #f4f4f4;
        }
        .progress-bar {
            background-color: #007bff;
        }
        .table-success {
            background-color: #d4edda !important;
        }
        .table-danger {
            background-color: #f8d7da !important;
        }
    </style>
@stop