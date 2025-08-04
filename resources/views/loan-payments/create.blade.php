@extends('adminlte::page')

@section('title', 'Tambah Pembayaran Angsuran')

@section('css')
    <!-- Select2 CSS -->
    <link rel="stylesheet" href="{{ asset('vendor/select2/select2.min.css') }}">
    <style>
        .select2-container--bootstrap4 .select2-selection--single {
            height: calc(2.25rem + 2px);
        }
        .select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered {
            padding-left: 0.75rem;
            padding-right: 0.75rem;
            line-height: calc(2.25rem);
        }
    </style>
@stop

@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h1>Tambah Pembayaran Angsuran</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('loan-payments.index') }}">Pembayaran Angsuran</a></li>
                <li class="breadcrumb-item active">Tambah</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Form Pembayaran Angsuran</h3>
        </div>
        <form action="{{ route('loan-payments.store') }}" method="POST">
            @csrf
            <div class="card-body">
                <div class="row">
                    <!-- Loan Selection -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="loan_id">Pilih Pinjaman <span class="text-danger">*</span></label>
                            <select class="form-control select2 @error('loan_id') is-invalid @enderror" 
                                    id="loan_id" name="loan_id" required
                                    data-placeholder="Ketik nama peminjam atau kode pinjaman..."
                                    data-allow-clear="true">
                                <option value="">-- Pilih Pinjaman --</option>
                                @foreach($loans as $loan)
                                    <option value="{{ $loan->id }}" 
                                            data-borrower="{{ $loan->borrower_name }}"
                                            data-monthly-payment="{{ $loan->monthly_payment }}"
                                            data-remaining-balance="{{ $loan->remaining_balance }}"
                                            {{ old('loan_id') == $loan->id ? 'selected' : '' }}>
                                        {{ $loan->loan_code }} - {{ $loan->borrower_name }} 
                                        (Sisa: {{ number_format($loan->remaining_balance, 0, ',', '.') }})
                                    </option>
                                @endforeach
                            </select>
                            @error('loan_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Payment Date -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="payment_date">Tanggal Pembayaran <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('payment_date') is-invalid @enderror" 
                                   id="payment_date" name="payment_date" 
                                   value="{{ old('payment_date', date('Y-m-d')) }}" required>
                            @error('payment_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Loan Information Display -->
                <div id="loan-info" class="row" style="display: none;">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <h5><i class="icon fas fa-info"></i> Informasi Pinjaman</h5>
                            <div class="row">
                                <div class="col-md-3">
                                    <strong>Peminjam:</strong><br>
                                    <span id="borrower-name">-</span>
                                </div>
                                <div class="col-md-3">
                                    <strong>Angsuran Bulanan:</strong><br>
                                    <span id="monthly-payment">-</span>
                                </div>
                                <div class="col-md-3">
                                    <strong>Sisa Saldo:</strong><br>
                                    <span id="remaining-balance">-</span>
                                </div>
                                <div class="col-md-3">
                                    <strong>Angsuran Ke:</strong><br>
                                    <span id="installment-number">-</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Payment Amount -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="payment_amount">Jumlah Pembayaran <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('payment_amount') is-invalid @enderror" 
                                   id="payment_amount" name="payment_amount" 
                                   value="{{ old('payment_amount') }}" 
                                   step="0.01" min="0" required>
                            @error('payment_amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="payment_method">Metode Pembayaran <span class="text-danger">*</span></label>
                            <select class="form-control @error('payment_method') is-invalid @enderror" 
                                    id="payment_method" name="payment_method" required>
                                <option value="">-- Pilih Metode --</option>
                                <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Tunai</option>
                                <option value="transfer" {{ old('payment_method') == 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                                <option value="check" {{ old('payment_method') == 'check' ? 'selected' : '' }}>Cek</option>
                                <option value="other" {{ old('payment_method') == 'other' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                            @error('payment_method')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Payment Breakdown -->
                <div id="payment-breakdown" class="row" style="display: none;">
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
                                                   name="principal_amount" step="0.01" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="interest_amount">Bunga</label>
                                            <input type="number" class="form-control" id="interest_amount" 
                                                   name="interest_amount" step="0.01" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="penalty_amount">Denda</label>
                                            <input type="number" class="form-control" id="penalty_amount" 
                                                   name="penalty_amount" step="0.01" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="installment_number_input">Angsuran Ke-</label>
                                            <input type="number" class="form-control" id="installment_number_input" 
                                                   name="installment_number" readonly>
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
                                      placeholder="Catatan tambahan (opsional)">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Pembayaran
                </button>
                <a href="{{ route('loan-payments.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Batal
                </a>
            </div>
        </form>
    </div>
@stop

@section('js')
    <!-- Select2 JS -->
    <script src="{{ asset('vendor/select2/select2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Initialize Select2 for loan dropdown
            $('#loan_id').select2({
                theme: 'bootstrap4',
                placeholder: 'Ketik nama peminjam atau kode pinjaman...',
                allowClear: true,
                width: '100%',
                language: {
                    noResults: function() {
                        return "Tidak ada pinjaman yang ditemukan";
                    },
                    searching: function() {
                        return "Mencari...";
                    }
                }
            });

            // Handle loan selection change
            $('#loan_id').change(function() {
                const selectedOption = $(this).find('option:selected');
                
                if (selectedOption.val()) {
                    // Show loan information
                    $('#borrower-name').text(selectedOption.data('borrower'));
                    $('#monthly-payment').text('Rp ' + numberFormat(selectedOption.data('monthly-payment')));
                    $('#remaining-balance').text('Rp ' + numberFormat(selectedOption.data('remaining-balance')));
                    $('#loan-info').show();
                    
                    // Set default payment amount to monthly payment
                    $('#payment_amount').val(selectedOption.data('monthly-payment'));
                    
                    // Calculate payment breakdown
                    calculatePaymentBreakdown();
                } else {
                    $('#loan-info').hide();
                    $('#payment-breakdown').hide();
                    clearForm();
                }
            });

            // Handle payment amount change
            $('#payment_amount, #payment_date').on('input change', function() {
                if ($('#loan_id').val() && $('#payment_amount').val()) {
                    calculatePaymentBreakdown();
                }
            });

            function calculatePaymentBreakdown() {
                const loanId = $('#loan_id').val();
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
                                $('#installment_number_input').val(response.data.installment_number);
                                $('#installment-number').text(response.data.installment_number);
                                $('#payment-breakdown').show();
                            } else {
                                alert('Error: ' + response.message);
                                $('#payment-breakdown').hide();
                            }
                        },
                        error: function() {
                            alert('Terjadi kesalahan saat menghitung rincian pembayaran');
                            $('#payment-breakdown').hide();
                        }
                    });
                }
            }

            function clearForm() {
                $('#payment_amount').val('');
                $('#principal_amount').val('');
                $('#interest_amount').val('');
                $('#penalty_amount').val('');
                $('#installment_number_input').val('');
            }

            function numberFormat(number) {
                return new Intl.NumberFormat('id-ID').format(number);
            }

            // Trigger calculation if loan is pre-selected (from old input)
            if ($('#loan_id').val()) {
                $('#loan_id').trigger('change');
            }
        });
    </script>
@stop