@extends('adminlte::page')

@section('title', 'Konfirmasi Reset Data Transaksi')

@section('content_header')
    <h1>
        <i class="fas fa-exclamation-triangle text-danger"></i> Konfirmasi Reset Data Transaksi
        <small class="text-danger">Operasi Berbahaya</small>
    </h1>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-danger">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-exclamation-triangle"></i> Konfirmasi Reset Data Transaksi
                    </h3>
                </div>
                <div class="card-body">
                    <div class="alert alert-danger">
                        <h4><i class="icon fas fa-ban"></i> PERINGATAN KERAS!</h4>
                        <p>
                            Anda akan menghapus <strong>SEMUA DATA TRANSAKSI</strong> dari sistem. 
                            Operasi ini <strong>TIDAK DAPAT DIBATALKAN</strong> dan akan menghapus data secara permanen.
                        </p>
                    </div>

                    <!-- Data yang akan dihapus -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card card-outline card-danger">
                                <div class="card-header">
                                    <h3 class="card-title">Data yang Akan Dihapus</h3>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span><i class="fas fa-exchange-alt text-danger"></i> Transaksi</span>
                                            <span class="badge badge-danger badge-pill">{{ number_format($stats['total_transactions']) }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span><i class="fas fa-book text-danger"></i> Entri Buku Besar</span>
                                            <span class="badge badge-danger badge-pill">{{ number_format($stats['total_general_ledger_entries']) }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span><i class="fas fa-calendar text-danger"></i> Rentang Waktu</span>
                                            <span class="text-muted">
                                                @if($stats['earliest_transaction'] && $stats['latest_transaction'])
                                                    {{ $stats['earliest_transaction']->format('d/m/Y') }} - {{ $stats['latest_transaction']->format('d/m/Y') }}
                                                @else
                                                    -
                                                @endif
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card card-outline card-success">
                                <div class="card-header">
                                    <h3 class="card-title">Data yang Tetap Ada</h3>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item">
                                            <i class="fas fa-list text-success"></i> Master data akun
                                        </li>
                                        <li class="list-group-item">
                                            <i class="fas fa-money-bill text-success"></i> Saldo awal akun
                                        </li>
                                        <li class="list-group-item">
                                            <i class="fas fa-users text-success"></i> Data pengguna
                                        </li>
                                        <li class="list-group-item">
                                            <i class="fas fa-cogs text-success"></i> Pengaturan sistem
                                        </li>
                                        <li class="list-group-item">
                                            <i class="fas fa-building text-success"></i> Data perusahaan
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Checklist konfirmasi -->
                    <div class="card card-outline card-warning mt-3">
                        <div class="card-header">
                            <h3 class="card-title">Checklist Konfirmasi</h3>
                        </div>
                        <div class="card-body">
                            <p class="text-muted">Pastikan Anda telah melakukan hal-hal berikut sebelum melanjutkan:</p>
                            
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="backup_check" required>
                                <label class="form-check-label" for="backup_check">
                                    <strong>Saya telah membuat backup database</strong>
                                </label>
                            </div>
                            
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="understand_check" required>
                                <label class="form-check-label" for="understand_check">
                                    <strong>Saya memahami bahwa operasi ini tidak dapat dibatalkan</strong>
                                </label>
                            </div>
                            
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="authority_check" required>
                                <label class="form-check-label" for="authority_check">
                                    <strong>Saya memiliki wewenang untuk melakukan operasi ini</strong>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Konfirmasi teks -->
                    <div class="card card-outline card-danger mt-3">
                        <div class="card-header">
                            <h3 class="card-title">Konfirmasi Akhir</h3>
                        </div>
                        <div class="card-body">
                            <p>Untuk melanjutkan, ketik <strong class="text-danger">RESET DATA TRANSAKSI</strong> di bawah ini:</p>
                            
                            <form id="resetForm" action="{{ route('data-management.reset-transaction-data') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <input type="text" 
                                           class="form-control" 
                                           id="confirmation_text" 
                                           name="confirmation_text"
                                           placeholder="Ketik: RESET DATA TRANSAKSI"
                                           required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="reset_reason">Alasan Reset (Opsional):</label>
                                    <textarea class="form-control" 
                                              id="reset_reason" 
                                              name="reset_reason"
                                              rows="3"
                                              placeholder="Jelaskan alasan melakukan reset data transaksi..."></textarea>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <div class="card-footer">
                    <div class="row">
                        <div class="col-6">
                            <a href="{{ route('data-management.index') }}" class="btn btn-secondary btn-block">
                                <i class="fas fa-arrow-left"></i> Batal
                            </a>
                        </div>
                        <div class="col-6">
                            <button type="submit" 
                                    form="resetForm"
                                    class="btn btn-danger btn-block" 
                                    id="confirmResetBtn"
                                    disabled>
                                <i class="fas fa-trash-alt"></i> Ya, Reset Data Transaksi
                            </button>
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
        
        .list-group-item {
            border: none;
            padding: 0.5rem 0;
        }
        
        .form-check {
            margin-bottom: 1rem;
        }
        
        .form-check-label {
            margin-left: 0.5rem;
        }
        
        #confirmation_text {
            font-family: monospace;
            font-weight: bold;
        }
        
        .alert-danger {
            border-left: 5px solid #dc3545;
        }
    </style>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            // Check if all requirements are met
            function checkRequirements() {
                const backupCheck = $('#backup_check').is(':checked');
                const understandCheck = $('#understand_check').is(':checked');
                const authorityCheck = $('#authority_check').is(':checked');
                const confirmationText = $('#confirmation_text').val().trim();
                
                const allChecked = backupCheck && understandCheck && authorityCheck;
                const textMatches = confirmationText === 'RESET DATA TRANSAKSI';
                
                $('#confirmResetBtn').prop('disabled', !(allChecked && textMatches));
            }
            
            // Bind events
            $('.form-check-input').on('change', checkRequirements);
            $('#confirmation_text').on('input', checkRequirements);
            
            // Form submission confirmation
            $('#resetForm').on('submit', function(e) {
                if (!confirm('Apakah Anda benar-benar yakin ingin menghapus SEMUA data transaksi? Operasi ini TIDAK DAPAT DIBATALKAN!')) {
                    e.preventDefault();
                    return false;
                }
                
                // Show loading state
                $('#confirmResetBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Memproses...');
            });
            
            // Prevent accidental form submission
            $('#confirmation_text').on('keypress', function(e) {
                if (e.which === 13) { // Enter key
                    e.preventDefault();
                }
            });
        });
    </script>
@stop