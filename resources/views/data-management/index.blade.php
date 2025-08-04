@extends('layouts.app')

@section('title', 'Manajemen Data')

@section('content_header')
    <h1>
        <i class="fas fa-database"></i> Manajemen Data
        <small class="text-danger">Super Administrator Only</small>
    </h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <!-- Alert for Super Admin Only -->
            <div class="alert alert-warning">
                <h5><i class="icon fas fa-exclamation-triangle"></i> Peringatan!</h5>
                Halaman ini hanya dapat diakses oleh Super Administrator. Fitur-fitur di halaman ini dapat mengubah atau menghapus data secara permanen.
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ number_format($stats['total_transactions']) }}</h3>
                    <p>Total Transaksi</p>
                </div>
                <div class="icon">
                    <i class="fas fa-exchange-alt"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ number_format($stats['total_general_ledger_entries']) }}</h3>
                    <p>Entri Buku Besar</p>
                </div>
                <div class="icon">
                    <i class="fas fa-book"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ number_format($stats['total_accounts_with_balance']) }}</h3>
                    <p>Akun dengan Saldo</p>
                </div>
                <div class="icon">
                    <i class="fas fa-wallet"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-secondary">
                <div class="inner">
                    <h3>
                        @if($stats['earliest_transaction'])
                            {{ $stats['earliest_transaction']->format('Y') }}
                        @else
                            -
                        @endif
                    </h3>
                    <p>Tahun Transaksi Pertama</p>
                </div>
                <div class="icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Information -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle"></i> Informasi Data
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Total Transaksi:</strong></td>
                                    <td>{{ number_format($stats['total_transactions']) }} transaksi</td>
                                </tr>
                                <tr>
                                    <td><strong>Total Entri Buku Besar:</strong></td>
                                    <td>{{ number_format($stats['total_general_ledger_entries']) }} entri</td>
                                </tr>
                                <tr>
                                    <td><strong>Akun dengan Saldo:</strong></td>
                                    <td>{{ number_format($stats['total_accounts_with_balance']) }} akun</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Transaksi Pertama:</strong></td>
                                    <td>
                                        @if($stats['earliest_transaction'])
                                            {{ $stats['earliest_transaction']->format('d F Y') }}
                                        @else
                                            <em>Belum ada transaksi</em>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Transaksi Terakhir:</strong></td>
                                    <td>
                                        @if($stats['latest_transaction'])
                                            {{ $stats['latest_transaction']->format('d F Y') }}
                                        @else
                                            <em>Belum ada transaksi</em>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Rentang Waktu:</strong></td>
                                    <td>
                                        @if($stats['earliest_transaction'] && $stats['latest_transaction'])
                                            {{ $stats['earliest_transaction']->diffInDays($stats['latest_transaction']) }} hari
                                        @else
                                            <em>-</em>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Danger Zone -->
    <div class="row">
        <div class="col-12">
            <div class="card card-danger">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-exclamation-triangle"></i> Zona Berbahaya
                    </h3>
                </div>
                <div class="card-body">
                    <div class="alert alert-danger">
                        <h5><i class="icon fas fa-ban"></i> Peringatan Keras!</h5>
                        Operasi di bawah ini akan menghapus data secara permanen dan tidak dapat dikembalikan. 
                        Pastikan Anda telah membuat backup sebelum melanjutkan.
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card card-outline card-danger">
                                <div class="card-header">
                                    <h3 class="card-title">Reset Data Transaksi</h3>
                                </div>
                                <div class="card-body">
                                    <p class="text-muted">
                                        Menghapus semua data transaksi dan entri buku besar. 
                                        Master data akun akan tetap ada, namun semua transaksi akan dihapus.
                                    </p>
                                    
                                    <p><strong>Yang akan dihapus:</strong></p>
                                    <ul>
                                        <li>{{ number_format($stats['total_transactions']) }} transaksi</li>
                                        <li>{{ number_format($stats['total_general_ledger_entries']) }} entri buku besar</li>
                                        <li>Semua riwayat transaksi</li>
                                    </ul>

                                    <p><strong>Yang akan tetap ada:</strong></p>
                                    <ul>
                                        <li>Master data akun</li>
                                        <li>Saldo awal akun</li>
                                        <li>Data pengguna</li>
                                        <li>Pengaturan sistem</li>
                                    </ul>

                                    @if($stats['total_transactions'] > 0)
                                        <a href="{{ route('data-management.confirm-reset') }}" class="btn btn-danger">
                                            <i class="fas fa-trash-alt"></i> Reset Data Transaksi
                                        </a>
                                    @else
                                        <button class="btn btn-secondary" disabled>
                                            <i class="fas fa-info-circle"></i> Tidak Ada Data untuk Direset
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card card-outline card-info">
                                <div class="card-header">
                                    <h3 class="card-title">Backup Database</h3>
                                </div>
                                <div class="card-body">
                                    <p class="text-muted">
                                        Sebelum melakukan reset data, sangat disarankan untuk membuat backup database terlebih dahulu.
                                    </p>
                                    
                                    <a href="{{ route('backups.index') }}" class="btn btn-info">
                                        <i class="fas fa-download"></i> Kelola Backup
                                    </a>
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
        .card-danger .card-header {
            background-color: #dc3545;
            border-color: #dc3545;
            color: white;
        }
        
        .small-box .icon {
            top: -10px;
            right: 10px;
        }
        
        .alert-danger {
            border-left: 5px solid #dc3545;
        }
        
        .alert-warning {
            border-left: 5px solid #ffc107;
        }
    </style>
@stop

@section('js')
    <script>
        // Auto-hide success/error messages after 5 seconds
        setTimeout(function() {
            $('.alert-success, .alert-error').fadeOut('slow');
        }, 5000);
    </script>
@stop