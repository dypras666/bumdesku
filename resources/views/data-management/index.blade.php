@extends('adminlte::page')

@section('title', 'Manajemen Data')

@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h1>
                <i class="fas fa-database"></i> Manajemen Data
                <small class="text-danger">Super Administrator Only</small>
            </h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Manajemen Data</li>
            </ol>
        </div>
    </div>
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
            <div class="small-box bg-info" data-toggle="tooltip" data-placement="top" title="Jumlah total semua transaksi yang tercatat dalam sistem">
                <div class="inner">
                    <h3>{{ number_format($stats['total_transactions']) }}</h3>
                    <p>Total Transaksi</p>
                </div>
                <div class="icon">
                    <i class="fas fa-exchange-alt"></i>
                </div>
                <a href="{{ route('transactions.index') }}" class="small-box-footer">
                    Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-success" data-toggle="tooltip" data-placement="top" title="Jumlah total entri yang tercatat dalam buku besar">
                <div class="inner">
                    <h3>{{ number_format($stats['total_general_ledger_entries']) }}</h3>
                    <p>Entri Buku Besar</p>
                </div>
                <div class="icon">
                    <i class="fas fa-book"></i>
                </div>
                <a href="{{ route('general-ledger.index') }}" class="small-box-footer">
                    Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning" data-toggle="tooltip" data-placement="top" title="Jumlah akun yang memiliki saldo (baik saldo awal maupun dari transaksi)">
                <div class="inner">
                    <h3>{{ number_format($stats['total_accounts_with_balance']) }}</h3>
                    <p>Akun dengan Saldo</p>
                </div>
                <div class="icon">
                    <i class="fas fa-wallet"></i>
                </div>
                <a href="{{ route('master-accounts.index') }}" class="small-box-footer">
                    Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-secondary" data-toggle="tooltip" data-placement="top" title="Tahun dari transaksi pertama yang tercatat dalam sistem">
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
                <div class="small-box-footer">
                    @if($stats['earliest_transaction'])
                        {{ $stats['earliest_transaction']->format('d F Y') }}
                    @else
                        Belum ada transaksi
                    @endif
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
                                        <a href="{{ route('data-management.confirm-reset') }}" 
                                           class="btn btn-danger" 
                                           data-toggle="tooltip" 
                                           data-placement="top" 
                                           title="Klik untuk memulai proses reset data transaksi. Akan ada konfirmasi lebih lanjut.">
                                            <i class="fas fa-trash-alt"></i> Reset Data Transaksi
                                        </a>
                                    @else
                                        <button class="btn btn-secondary" 
                                                disabled 
                                                data-toggle="tooltip" 
                                                data-placement="top" 
                                                title="Tidak ada data transaksi yang dapat direset">
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
                                    
                                    <a href="{{ route('backups.index') }}" 
                                       class="btn btn-info" 
                                       data-toggle="tooltip" 
                                       data-placement="top" 
                                       title="Kelola backup database untuk keamanan data">
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
        /* AdminLTE Card Danger Styling */
        .card-danger .card-header {
            background-color: #dc3545;
            border-color: #dc3545;
            color: white;
        }
        
        /* Small Box Icon Positioning */
        .small-box .icon {
            top: -10px;
            right: 10px;
            font-size: 70px;
        }
        
        /* Enhanced Alert Styling */
        .alert-danger {
            border-left: 5px solid #dc3545;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }
        
        .alert-warning {
            border-left: 5px solid #ffc107;
            background-color: #fff3cd;
            border-color: #ffeaa7;
        }
        
        /* Card Outline Styling */
        .card-outline.card-danger {
            border-top: 3px solid #dc3545;
        }
        
        .card-outline.card-info {
            border-top: 3px solid #17a2b8;
        }
        
        /* Enhanced Small Box Hover Effects */
        .small-box:hover {
            transform: translateY(-2px);
            transition: all 0.3s ease;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        /* Breadcrumb Styling */
        .breadcrumb {
            background-color: transparent;
            margin-bottom: 0;
        }
        
        /* Content Header Enhancement */
        .content-header {
            padding: 15px 0.5rem;
        }
        
        /* Statistics Enhancement */
        .small-box h3 {
            font-size: 2.2rem;
            font-weight: bold;
        }
        
        /* Button Enhancements */
        .btn-danger:hover {
            background-color: #c82333;
            border-color: #bd2130;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .btn-info:hover {
            background-color: #138496;
            border-color: #117a8b;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
    </style>
@stop

@section('plugins.Sweetalert2', true)

@section('js')
    <script>
        $(document).ready(function() {
            // Auto-hide success/error messages after 5 seconds
            setTimeout(function() {
                $('.alert-success, .alert-error').fadeOut('slow');
            }, 5000);
            
            // Add confirmation dialog for dangerous actions
            $('.btn-danger[href*="confirm-reset"]').on('click', function(e) {
                e.preventDefault();
                const url = $(this).attr('href');
                
                Swal.fire({
                    title: 'Konfirmasi Reset Data',
                    text: 'Apakah Anda yakin ingin mereset semua data transaksi? Tindakan ini tidak dapat dibatalkan!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Reset Data!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = url;
                    }
                });
            });
            
            // Add hover effects for statistics cards
            $('.small-box').hover(
                function() {
                    $(this).addClass('shadow-lg');
                },
                function() {
                    $(this).removeClass('shadow-lg');
                }
            );
            
            // Add loading state for buttons
            $('.btn').on('click', function() {
                const btn = $(this);
                if (!btn.hasClass('btn-secondary') && !btn.is(':disabled')) {
                    btn.prop('disabled', true);
                    const originalText = btn.html();
                    btn.html('<i class="fas fa-spinner fa-spin"></i> Memproses...');
                    
                    // Re-enable button after 3 seconds (fallback)
                    setTimeout(function() {
                        btn.prop('disabled', false);
                        btn.html(originalText);
                    }, 3000);
                }
            });
            
            // Initialize tooltips for better UX
            $('[data-toggle="tooltip"]').tooltip();
            
            // Add smooth scrolling for anchor links
            $('a[href^="#"]').on('click', function(event) {
                var target = $(this.getAttribute('href'));
                if (target.length) {
                    event.preventDefault();
                    $('html, body').stop().animate({
                        scrollTop: target.offset().top - 100
                    }, 1000);
                }
            });
        });
    </script>
@stop