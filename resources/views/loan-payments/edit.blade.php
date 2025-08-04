@extends('adminlte::page')

@section('title', 'Edit Pembayaran Angsuran')

@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h1>Edit Pembayaran Angsuran</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('loan-payments.index') }}">Pembayaran Angsuran</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    @if($payment->status != 'pending')
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle"></i>
            <strong>Peringatan:</strong> Pembayaran ini sudah {{ $payment->status == 'approved' ? 'disetujui' : 'ditolak' }} 
            dan tidak dapat diubah.
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Form Edit Pembayaran Angsuran</h3>
        </div>
        <form action="{{ route('loan-payments.update', $payment->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="row">
                    <!-- Loan Information (Read-only) -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Pinjaman</label>
                            <input type="text" class="form-control" 
                                   value="{{ $payment->loan->loan_code }} - {{ $payment->loan->borrower_name }}" 
                                   readonly>
                            <input type="hidden" name="loan_id" value="{{ $payment->loan_id }}">
                        </div>
                    </div>

                    <!-- Payment Code (Read-only) -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Kode Pembayaran</label>
                            <input type="text" class="form-control" value="{{ $payment->payment_code }}" readonly>
                        </div>
                    </div>
                </div>

                <!-- Loan Information Display -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <h5><i class="icon fas fa-info"></i> Informasi Pinjaman</h5>
                            <div class="row">
                                <div class="col-md-3">
                                    <strong>Peminjam:</strong><br>
                                    {{ $payment->loan->borrower_name }}
                                </div>
                                <div class="col-md-3">
                                    <strong>Angsuran Bulanan:</strong><br>
                                    {{ $payment->loan->formatted_monthly_payment }}
                                </div>
                                <div class="col-md-3">
                                    <strong>Sisa Saldo:</strong><br>
                                    {{ $payment->loan->formatted_remaining_balance }}
                                </div>
                                <div class="col-md-3">
                                    <strong>Angsuran Ke:</strong><br>
                                    {{ $payment->installment_number }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Payment Date -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="payment_date">Tanggal Pembayaran <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('payment_date') is-invalid @enderror" 
                                   id="payment_date" name="payment_date" 
                                   value="{{ old('payment_date', $payment->payment_date->format('Y-m-d')) }}" 
                                   {{ $payment->status != 'pending' ? 'readonly' : 'required' }}>
                            @error('payment_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Payment Amount -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="payment_amount">Jumlah Pembayaran <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('payment_amount') is-invalid @enderror" 
                                   id="payment_amount" name="payment_amount" 
                                   value="{{ old('payment_amount', $payment->payment_amount) }}" 
                                   step="0.01" min="0" 
                                   {{ $payment->status != 'pending' ? 'readonly' : 'required' }}>
                            @error('payment_amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Payment Method -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="payment_method">Metode Pembayaran <span class="text-danger">*</span></label>
                            <select class="form-control @error('payment_method') is-invalid @enderror" 
                                    id="payment_method" name="payment_method" 
                                    {{ $payment->status != 'pending' ? 'disabled' : 'required' }}>
                                <option value="">-- Pilih Metode --</option>
                                <option value="cash" {{ old('payment_method', $payment->payment_method) == 'cash' ? 'selected' : '' }}>Tunai</option>
                                <option value="transfer" {{ old('payment_method', $payment->payment_method) == 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                                <option value="check" {{ old('payment_method', $payment->payment_method) == 'check' ? 'selected' : '' }}>Cek</option>
                                <option value="other" {{ old('payment_method', $payment->payment_method) == 'other' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                            @if($payment->status != 'pending')
                                <input type="hidden" name="payment_method" value="{{ $payment->payment_method }}">
                            @endif
                            @error('payment_method')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Installment Number (Read-only) -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Angsuran Ke-</label>
                            <input type="number" class="form-control" 
                                   value="{{ $payment->installment_number }}" readonly>
                            <input type="hidden" name="installment_number" value="{{ $payment->installment_number }}">
                        </div>
                    </div>
                </div>

                <!-- Payment Breakdown -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-secondary">
                            <div class="card-header">
                                <h3 class="card-title">Rincian Pembayaran</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="principal_amount">Pokok</label>
                                            <input type="number" class="form-control" id="principal_amount" 
                                                   name="principal_amount" step="0.01" 
                                                   value="{{ old('principal_amount', $payment->principal_amount) }}" 
                                                   {{ $payment->status != 'pending' ? 'readonly' : '' }}>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="interest_amount">Bunga</label>
                                            <input type="number" class="form-control" id="interest_amount" 
                                                   name="interest_amount" step="0.01" 
                                                   value="{{ old('interest_amount', $payment->interest_amount) }}" 
                                                   {{ $payment->status != 'pending' ? 'readonly' : '' }}>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="penalty_amount">Denda</label>
                                            <input type="number" class="form-control" id="penalty_amount" 
                                                   name="penalty_amount" step="0.01" 
                                                   value="{{ old('penalty_amount', $payment->penalty_amount) }}" 
                                                   {{ $payment->status != 'pending' ? 'readonly' : '' }}>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Total</label>
                                            <input type="text" class="form-control" id="total_breakdown" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notes -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="notes">Catatan</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="3" 
                                      placeholder="Catatan tambahan (opsional)"
                                      {{ $payment->status != 'pending' ? 'readonly' : '' }}>{{ old('notes', $payment->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                @if($payment->status == 'pending')
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Pembayaran
                    </button>
                @endif
                <a href="{{ route('loan-payments.show', $payment->id) }}" class="btn btn-info">
                    <i class="fas fa-eye"></i> Lihat Detail
                </a>
                <a href="{{ route('loan-payments.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
    </div>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            // Calculate total breakdown
            function calculateTotal() {
                const principal = parseFloat($('#principal_amount').val()) || 0;
                const interest = parseFloat($('#interest_amount').val()) || 0;
                const penalty = parseFloat($('#penalty_amount').val()) || 0;
                const total = principal + interest + penalty;
                
                $('#total_breakdown').val(numberFormat(total));
                
                // Update payment amount if breakdown changes
                if (total > 0) {
                    $('#payment_amount').val(total);
                }
            }

            // Handle payment amount change to recalculate breakdown
            $('#payment_amount, #payment_date').on('input change', function() {
                if ($('#payment_amount').val() && $('#payment_date').val()) {
                    calculatePaymentBreakdown();
                }
            });

            // Handle breakdown changes
            $('#principal_amount, #interest_amount, #penalty_amount').on('input', function() {
                calculateTotal();
            });

            function calculatePaymentBreakdown() {
                const loanId = {{ $payment->loan_id }};
                const paymentAmount = $('#payment_amount').val();
                const paymentDate = $('#payment_date').val();

                if (loanId && paymentAmount && paymentDate) {
                    $.ajax({
                        url: '{{ route("loan-payments.get-installment-details") }}',
                        method: 'GET',
                        data: {
                            loan_id: loanId,
                            payment_amount: paymentAmount,
                            payment_date: paymentDate
                        },
                        success: function(response) {
                            if (response.success) {
                                $('#principal_amount').val(response.data.principal_amount);
                                $('#interest_amount').val(response.data.interest_amount);
                                $('#penalty_amount').val(response.data.penalty_amount);
                                calculateTotal();
                            }
                        },
                        error: function() {
                            console.log('Error calculating payment breakdown');
                        }
                    });
                }
            }

            function numberFormat(number) {
                return new Intl.NumberFormat('id-ID').format(number);
            }

            // Initial calculation
            calculateTotal();
        });
    </script>
@stop