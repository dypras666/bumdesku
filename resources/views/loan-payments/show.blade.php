@extends('adminlte::page')

@section('title', 'Detail Pembayaran Angsuran')

@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h1>Detail Pembayaran Angsuran</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('loan-payments.index') }}">Pembayaran Angsuran</a></li>
                <li class="breadcrumb-item active">Detail</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <!-- Payment Information -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informasi Pembayaran</h3>
                    <div class="card-tools">
                        @if($payment->status == 'pending')
                            <span class="badge badge-warning">Menunggu Persetujuan</span>
                        @elseif($payment->status == 'approved')
                            <span class="badge badge-success">Disetujui</span>
                        @elseif($payment->status == 'rejected')
                            <span class="badge badge-danger">Ditolak</span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Kode Pembayaran:</strong></td>
                                    <td>{{ $payment->payment_code }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Kode Pinjaman:</strong></td>
                                    <td>{{ $payment->loan->loan_code }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Peminjam:</strong></td>
                                    <td>{{ $payment->loan->borrower_name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Angsuran Ke-:</strong></td>
                                    <td>{{ $payment->installment_number }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tanggal Pembayaran:</strong></td>
                                    <td>{{ $payment->formatted_payment_date }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Metode Pembayaran:</strong></td>
                                    <td>{{ $payment->payment_method_label }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Jumlah Pembayaran:</strong></td>
                                    <td class="text-primary"><strong>{{ $payment->formatted_payment_amount }}</strong></td>
                                </tr>
                                <tr>
                                    <td><strong>Pokok:</strong></td>
                                    <td>{{ $payment->formatted_principal_amount }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Bunga:</strong></td>
                                    <td>{{ $payment->formatted_interest_amount }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Denda:</strong></td>
                                    <td>{{ $payment->formatted_penalty_amount }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Dibuat Oleh:</strong></td>
                                    <td>{{ $payment->creator->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tanggal Dibuat:</strong></td>
                                    <td>{{ $payment->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($payment->notes)
                        <div class="row">
                            <div class="col-md-12">
                                <hr>
                                <h5>Catatan:</h5>
                                <p>{{ $payment->notes }}</p>
                            </div>
                        </div>
                    @endif

                    @if($payment->status == 'approved')
                        <div class="row">
                            <div class="col-md-12">
                                <hr>
                                <h5>Informasi Persetujuan:</h5>
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Disetujui Oleh:</strong></td>
                                        <td>{{ $payment->approver->name ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Tanggal Persetujuan:</strong></td>
                                        <td>{{ $payment->approved_at ? $payment->approved_at->format('d/m/Y H:i') : '-' }}</td>
                                    </tr>
                                    @if($payment->transaction_id)
                                        <tr>
                                            <td><strong>ID Transaksi:</strong></td>
                                            <td>{{ $payment->transaction_id }}</td>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                    @endif

                    @if($payment->status == 'rejected' && $payment->rejection_reason)
                        <div class="row">
                            <div class="col-md-12">
                                <hr>
                                <div class="alert alert-danger">
                                    <h5><i class="icon fas fa-ban"></i> Alasan Penolakan:</h5>
                                    {{ $payment->rejection_reason }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Actions and Loan Summary -->
        <div class="col-md-4">
            <!-- Actions -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Aksi</h3>
                </div>
                <div class="card-body">
                    @if($payment->status == 'approved')
                        <a href="{{ route('loan-payments.print-receipt', $payment->id) }}" class="btn btn-primary btn-block mb-2" target="_blank">
                            <i class="fas fa-print"></i> Cetak Bukti Pembayaran
                        </a>
                    @endif

                    @if($payment->status == 'pending')
                        <form action="{{ route('loan-payments.approve', $payment->id) }}" method="POST" class="mb-2">
                            @csrf
                            <button type="submit" class="btn btn-success btn-block" 
                                    onclick="return confirm('Apakah Anda yakin ingin menyetujui pembayaran ini?')">
                                <i class="fas fa-check"></i> Setujui Pembayaran
                            </button>
                        </form>

                        <button type="button" class="btn btn-danger btn-block mb-2" 
                                onclick="rejectPayment({{ $payment->id }})">
                            <i class="fas fa-times"></i> Tolak Pembayaran
                        </button>

                        <a href="{{ route('loan-payments.edit', $payment->id) }}" class="btn btn-warning btn-block mb-2">
                            <i class="fas fa-edit"></i> Edit Pembayaran
                        </a>

                        <form action="{{ route('loan-payments.destroy', $payment->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-secondary btn-block" 
                                    onclick="return confirm('Apakah Anda yakin ingin menghapus pembayaran ini?')">
                                <i class="fas fa-trash"></i> Hapus Pembayaran
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('loan-payments.index') }}" class="btn btn-info btn-block mt-2">
                        <i class="fas fa-arrow-left"></i> Kembali ke Daftar
                    </a>
                </div>
            </div>

            <!-- Loan Summary -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Ringkasan Pinjaman</h3>
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-sm">
                        <tr>
                            <td><strong>Jumlah Pinjaman:</strong></td>
                            <td>{{ $payment->loan->formatted_loan_amount }}</td>
                        </tr>
                        <tr>
                            <td><strong>Suku Bunga:</strong></td>
                            <td>{{ $payment->loan->interest_rate }}% / tahun</td>
                        </tr>
                        <tr>
                            <td><strong>Jangka Waktu:</strong></td>
                            <td>{{ $payment->loan->loan_term_months }} bulan</td>
                        </tr>
                        <tr>
                            <td><strong>Angsuran Bulanan:</strong></td>
                            <td>{{ $payment->loan->formatted_monthly_payment }}</td>
                        </tr>
                        <tr>
                            <td><strong>Total Dibayar:</strong></td>
                            <td>{{ $payment->loan->formatted_total_paid }}</td>
                        </tr>
                        <tr>
                            <td><strong>Sisa Saldo:</strong></td>
                            <td class="text-danger"><strong>{{ $payment->loan->formatted_remaining_balance }}</strong></td>
                        </tr>
                        <tr>
                            <td><strong>Status:</strong></td>
                            <td>
                                @if($payment->loan->status == 'pending')
                                    <span class="badge badge-warning">Menunggu Persetujuan</span>
                                @elseif($payment->loan->status == 'active')
                                    <span class="badge badge-success">Aktif</span>
                                @elseif($payment->loan->status == 'completed')
                                    <span class="badge badge-primary">Lunas</span>
                                @elseif($payment->loan->status == 'overdue')
                                    <span class="badge badge-danger">Menunggak</span>
                                @endif
                            </td>
                        </tr>
                    </table>

                    <div class="progress mb-2">
                        <div class="progress-bar" role="progressbar" 
                             style="width: {{ $payment->loan->progress_percentage }}%">
                            {{ number_format($payment->loan->progress_percentage, 1) }}%
                        </div>
                    </div>

                    <a href="{{ route('loans.show', $payment->loan->id) }}" class="btn btn-outline-primary btn-sm btn-block">
                        <i class="fas fa-eye"></i> Lihat Detail Pinjaman
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Reject Payment Modal -->
    <div class="modal fade" id="rejectModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tolak Pembayaran</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form id="rejectForm" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="rejection_reason">Alasan Penolakan <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="rejection_reason" name="rejection_reason" 
                                      rows="3" required placeholder="Masukkan alasan penolakan..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Tolak Pembayaran</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script>
        function rejectPayment(paymentId) {
            $('#rejectForm').attr('action', '/loan-payments/' + paymentId + '/reject');
            $('#rejectModal').modal('show');
        }
    </script>
@stop