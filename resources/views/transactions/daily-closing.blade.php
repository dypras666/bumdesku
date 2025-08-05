@extends('adminlte::page')

@section('title', 'Closing Harian Transaksi')

@section('content_header')
    <h1>Closing Harian Transaksi</h1>
@stop

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-calendar-check mr-2"></i>
                        Closing Harian Transaksi
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('transactions.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="alert alert-info">
                                <h5><i class="icon fas fa-info-circle"></i> Informasi Closing Harian</h5>
                                <p>Fitur ini akan memindahkan semua transaksi yang sudah disetujui pada tanggal tertentu ke jurnal umum secara otomatis.</p>
                                <ul class="mb-0">
                                    <li>Transaksi pemasukan akan dicatat sebagai: <strong>Debit Kas/Bank, Kredit Pendapatan</strong></li>
                                    <li>Transaksi pengeluaran akan dicatat sebagai: <strong>Debit Beban, Kredit Kas/Bank</strong></li>
                                    <li>Hanya transaksi dengan status "Disetujui" yang akan diproses</li>
                                    <li>Transaksi yang sudah pernah diposting tidak akan diproses ulang</li>
                                </ul>
                            </div>

                            <form action="{{ route('transactions.daily-closing') }}" method="POST" id="dailyClosingForm">
                                @csrf
                                <div class="form-group">
                                    <label for="closing_date">Tanggal Closing <span class="text-danger">*</span></label>
                                    <input type="date" 
                                           class="form-control @error('closing_date') is-invalid @enderror" 
                                           id="closing_date" 
                                           name="closing_date" 
                                           value="{{ old('closing_date', $defaultDate) }}"
                                           max="{{ now()->format('Y-m-d') }}"
                                           required>
                                    @error('closing_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Pilih tanggal transaksi yang akan di-closing. Maksimal tanggal hari ini.
                                    </small>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary" id="submitBtn">
                                        <i class="fas fa-calendar-check"></i> Proses Closing Harian
                                    </button>
                                    <button type="button" class="btn btn-info" id="checkBtn">
                                        <i class="fas fa-search"></i> Cek Transaksi
                                    </button>
                                </div>
                            </form>
                        </div>

                        <div class="col-md-4">
                            <div class="card card-outline card-info">
                                <div class="card-header">
                                    <h3 class="card-title">Status Transaksi Hari Ini</h3>
                                </div>
                                <div class="card-body">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-warning">
                                            <i class="fas fa-clock"></i>
                                        </span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Belum Diposting</span>
                                            <span class="info-box-number" id="pendingCount">{{ $pendingTransactions }}</span>
                                        </div>
                                    </div>
                                    <p class="text-muted">
                                        <small>Jumlah transaksi approved yang belum diposting ke jurnal umum untuk tanggal {{ $defaultDate }}</small>
                                    </p>
                                </div>
                            </div>

                            <div class="card card-outline card-success" id="transactionDetails" style="display: none;">
                                <div class="card-header">
                                    <h3 class="card-title">Detail Transaksi</h3>
                                </div>
                                <div class="card-body" id="transactionDetailsBody">
                                    <!-- Detail transaksi akan dimuat di sini -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
<script>
$(document).ready(function() {
    // Check transactions when date changes
    $('#closing_date').on('change', function() {
        checkTransactions();
    });

    // Check transactions button
    $('#checkBtn').on('click', function() {
        checkTransactions();
    });

    // Form submission
    $('#dailyClosingForm').on('submit', function(e) {
        e.preventDefault();
        
        const closingDate = $('#closing_date').val();
        if (!closingDate) {
            Swal.fire('Error', 'Silakan pilih tanggal closing', 'error');
            return;
        }

        Swal.fire({
            title: 'Konfirmasi Closing Harian',
            text: `Apakah Anda yakin ingin melakukan closing untuk tanggal ${closingDate}?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Proses!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                processClosing();
            }
        });
    });

    function checkTransactions() {
        const closingDate = $('#closing_date').val();
        if (!closingDate) {
            return;
        }

        $('#checkBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Mengecek...');

        $.ajax({
            url: '{{ route("api.transactions.index") }}',
            method: 'GET',
            data: {
                status: 'approved',
                date_from: closingDate,
                date_to: closingDate,
                per_page: 100
            },
            success: function(response) {
                const transactions = response.data || [];
                const unpostedTransactions = transactions.filter(t => !t.is_posted);
                
                $('#pendingCount').text(unpostedTransactions.length);
                
                if (unpostedTransactions.length > 0) {
                    let detailsHtml = '<div class="table-responsive">';
                    detailsHtml += '<table class="table table-sm">';
                    detailsHtml += '<thead><tr><th>Kode</th><th>Jenis</th><th>Jumlah</th></tr></thead>';
                    detailsHtml += '<tbody>';
                    
                    let totalAmount = 0;
                    unpostedTransactions.forEach(function(transaction) {
                        detailsHtml += `<tr>
                            <td>${transaction.transaction_code}</td>
                            <td><span class="badge ${transaction.transaction_type === 'income' ? 'badge-success' : 'badge-danger'}">${transaction.transaction_type === 'income' ? 'Masuk' : 'Keluar'}</span></td>
                            <td>${formatCurrency(transaction.amount)}</td>
                        </tr>`;
                        totalAmount += parseFloat(transaction.amount);
                    });
                    
                    detailsHtml += '</tbody>';
                    detailsHtml += `<tfoot><tr><th colspan="2">Total</th><th>${formatCurrency(totalAmount)}</th></tr></tfoot>`;
                    detailsHtml += '</table></div>';
                    
                    $('#transactionDetailsBody').html(detailsHtml);
                    $('#transactionDetails').show();
                } else {
                    $('#transactionDetails').hide();
                }
            },
            error: function(xhr) {
                console.error('Error checking transactions:', xhr);
                Swal.fire('Error', 'Gagal mengecek transaksi', 'error');
            },
            complete: function() {
                $('#checkBtn').prop('disabled', false).html('<i class="fas fa-search"></i> Cek Transaksi');
            }
        });
    }

    function processClosing() {
        const formData = new FormData($('#dailyClosingForm')[0]);
        
        $('#submitBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Memproses...');

        $.ajax({
            url: '{{ route("api.transactions.daily-closing") }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: response.message,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.href = '{{ route("transactions.index") }}';
                    });
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            },
            error: function(xhr) {
                let errorMessage = 'Terjadi kesalahan saat memproses closing';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                Swal.fire('Error', errorMessage, 'error');
            },
            complete: function() {
                $('#submitBtn').prop('disabled', false).html('<i class="fas fa-calendar-check"></i> Proses Closing Harian');
            }
        });
    }

    function formatCurrency(amount) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(amount);
    }

    // Initial check
    checkTransactions();
});
</script>
@stop