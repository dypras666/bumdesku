@extends('adminlte::page')

@section('title', 'Tambah Pinjaman Modal')

@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h1>Tambah Pinjaman Modal</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('loans.index') }}">Daftar Pinjaman</a></li>
                <li class="breadcrumb-item active">Tambah Pinjaman</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Form Pinjaman Modal</h3>
        </div>
        <form action="{{ route('loans.store') }}" method="POST">
            @csrf
            <div class="card-body">
                <div class="row">
                    <!-- Data Peminjam -->
                    <div class="col-md-6">
                        <h5 class="text-primary">Data Peminjam</h5>
                        <hr>
                        
                        <div class="form-group">
                            <label for="borrower_name">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('borrower_name') is-invalid @enderror" 
                                   id="borrower_name" name="borrower_name" value="{{ old('borrower_name') }}" required>
                            @error('borrower_name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="borrower_phone">No. HP <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('borrower_phone') is-invalid @enderror" 
                                   id="borrower_phone" name="borrower_phone" value="{{ old('borrower_phone') }}" required>
                            @error('borrower_phone')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="borrower_address">Alamat</label>
                            <textarea class="form-control @error('borrower_address') is-invalid @enderror" 
                                      id="borrower_address" name="borrower_address" rows="3">{{ old('borrower_address') }}</textarea>
                            @error('borrower_address')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="borrower_id_number">No. KTP/Identitas</label>
                            <input type="text" class="form-control @error('borrower_id_number') is-invalid @enderror" 
                                   id="borrower_id_number" name="borrower_id_number" value="{{ old('borrower_id_number') }}">
                            @error('borrower_id_number')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Data Pinjaman -->
                    <div class="col-md-6">
                        <h5 class="text-primary">Data Pinjaman</h5>
                        <hr>

                        <div class="form-group">
                            <label for="loan_type">Jenis Pinjaman <span class="text-danger">*</span></label>
                            <select class="form-control @error('loan_type') is-invalid @enderror" 
                                    id="loan_type" name="loan_type" required>
                                <option value="">Pilih Jenis Pinjaman</option>
                                <option value="bunga" {{ old('loan_type') == 'bunga' ? 'selected' : '' }}>Pinjaman dengan Bunga</option>
                                <option value="bagi_hasil" {{ old('loan_type') == 'bagi_hasil' ? 'selected' : '' }}>Pinjaman Bagi Hasil</option>
                                <option value="tanpa_bunga" {{ old('loan_type') == 'tanpa_bunga' ? 'selected' : '' }}>Pinjaman Tanpa Bunga</option>
                            </select>
                            @error('loan_type')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="loan_amount">Jumlah Pinjaman <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="number" class="form-control @error('loan_amount') is-invalid @enderror" 
                                       id="loan_amount" name="loan_amount" value="{{ old('loan_amount') }}" 
                                       min="100000" step="50000" required>
                            </div>
                            @error('loan_amount')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Field untuk Pinjaman Bunga -->
                        <div class="form-group" id="interest_rate_group">
                            <label for="interest_rate">Suku Bunga (% per tahun) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" class="form-control @error('interest_rate') is-invalid @enderror" 
                                       id="interest_rate" name="interest_rate" value="{{ old('interest_rate', 12) }}" 
                                       min="0" max="100" step="0.1">
                                <div class="input-group-append">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                            @error('interest_rate')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Field untuk Pinjaman Bagi Hasil -->
                        <div class="form-group" id="profit_sharing_group" style="display: none;">
                            <label for="profit_sharing_percentage">Persentase Bagi Hasil (%) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" class="form-control @error('profit_sharing_percentage') is-invalid @enderror" 
                                       id="profit_sharing_percentage" name="profit_sharing_percentage" 
                                       value="{{ old('profit_sharing_percentage', 30) }}" 
                                       min="0" max="100" step="0.1">
                                <div class="input-group-append">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                            <small class="form-text text-muted">Persentase keuntungan yang akan dibagi kepada BUMDES</small>
                            @error('profit_sharing_percentage')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group" id="expected_profit_group" style="display: none;">
                            <label for="expected_profit">Perkiraan Keuntungan <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="number" class="form-control @error('expected_profit') is-invalid @enderror" 
                                       id="expected_profit" name="expected_profit" value="{{ old('expected_profit') }}" 
                                       min="0" step="50000">
                            </div>
                            <small class="form-text text-muted">Perkiraan total keuntungan dari usaha yang akan dijalankan</small>
                            @error('expected_profit')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group" id="business_description_group" style="display: none;">
                            <label for="business_description">Deskripsi Usaha <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('business_description') is-invalid @enderror" 
                                      id="business_description" name="business_description" rows="3" 
                                      placeholder="Jelaskan usaha yang akan dijalankan dengan dana pinjaman ini...">{{ old('business_description') }}</textarea>
                            @error('business_description')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Field untuk semua jenis pinjaman -->
                        <div class="form-group">
                            <label for="admin_fee">Biaya Administrasi</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="number" class="form-control @error('admin_fee') is-invalid @enderror" 
                                       id="admin_fee" name="admin_fee" value="{{ old('admin_fee', 0) }}" 
                                       min="0" step="5000">
                            </div>
                            <small class="form-text text-muted">Biaya administrasi yang dikenakan (opsional)</small>
                            @error('admin_fee')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="loan_term_months">Jangka Waktu (bulan) <span class="text-danger">*</span></label>
                            <select class="form-control @error('loan_term_months') is-invalid @enderror" 
                                    id="loan_term_months" name="loan_term_months" required>
                                <option value="">Pilih Jangka Waktu</option>
                                <option value="6" {{ old('loan_term_months') == 6 ? 'selected' : '' }}>6 Bulan</option>
                                <option value="12" {{ old('loan_term_months') == 12 ? 'selected' : '' }}>12 Bulan</option>
                                <option value="18" {{ old('loan_term_months') == 18 ? 'selected' : '' }}>18 Bulan</option>
                                <option value="24" {{ old('loan_term_months') == 24 ? 'selected' : '' }}>24 Bulan</option>
                                <option value="36" {{ old('loan_term_months') == 36 ? 'selected' : '' }}>36 Bulan</option>
                                <option value="48" {{ old('loan_term_months') == 48 ? 'selected' : '' }}>48 Bulan</option>
                                <option value="60" {{ old('loan_term_months') == 60 ? 'selected' : '' }}>60 Bulan</option>
                            </select>
                            @error('loan_term_months')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="loan_date">Tanggal Pinjaman <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('loan_date') is-invalid @enderror" 
                                   id="loan_date" name="loan_date" value="{{ old('loan_date', date('Y-m-d')) }}" required>
                            @error('loan_date')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="account_id">Akun Piutang <span class="text-danger">*</span></label>
                            <select class="form-control @error('account_id') is-invalid @enderror" 
                                    id="account_id" name="account_id" required>
                                <option value="">Pilih Akun Piutang</option>
                                @foreach($accounts as $account)
                                    <option value="{{ $account->id }}" {{ old('account_id') == $account->id ? 'selected' : '' }}>
                                        {{ $account->kode_akun }} - {{ $account->nama_akun }}
                                    </option>
                                @endforeach
                            </select>
                            @error('account_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="notes">Catatan</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="3" 
                                      placeholder="Catatan tambahan tentang pinjaman ini...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Perhitungan Otomatis -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card bg-light">
                            <div class="card-header">
                                <h5 class="card-title">Perhitungan Angsuran</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <strong>Angsuran per Bulan:</strong>
                                        <div id="monthly_payment_display" class="text-primary h5">Rp 0</div>
                                    </div>
                                    <div class="col-md-4">
                                        <strong>Total Pembayaran:</strong>
                                        <div id="total_payment_display" class="text-info h5">Rp 0</div>
                                    </div>
                                    <div class="col-md-4">
                                        <strong>Total Bunga:</strong>
                                        <div id="total_interest_display" class="text-warning h5">Rp 0</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Pinjaman
                </button>
                <a href="{{ route('loans.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Batal
                </a>
            </div>
        </form>
    </div>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            // Function to toggle fields based on loan type
            function toggleLoanTypeFields() {
                const loanType = $('#loan_type').val();
                
                // Hide all conditional fields first
                $('#interest_rate_group').hide();
                $('#profit_sharing_group').hide();
                $('#expected_profit_group').hide();
                $('#business_description_group').hide();
                
                // Remove required attribute from all conditional fields
                $('#interest_rate, #profit_sharing_percentage, #expected_profit, #business_description').removeAttr('required');
                
                // Show relevant fields based on loan type
                if (loanType === 'bunga') {
                    $('#interest_rate_group').show();
                    $('#interest_rate').attr('required', true);
                } else if (loanType === 'bagi_hasil') {
                    $('#profit_sharing_group').show();
                    $('#expected_profit_group').show();
                    $('#business_description_group').show();
                    $('#profit_sharing_percentage, #expected_profit, #business_description').attr('required', true);
                } else if (loanType === 'tanpa_bunga') {
                    // No additional fields needed for interest-free loans
                }
            }

            // Function to calculate monthly payment based on loan type
            function calculatePayment() {
                const loanAmount = parseFloat($('#loan_amount').val()) || 0;
                const loanTermMonths = parseInt($('#loan_term_months').val()) || 0;
                const loanType = $('#loan_type').val();
                const adminFee = parseFloat($('#admin_fee').val()) || 0;

                if (loanAmount > 0 && loanTermMonths > 0 && loanType) {
                    let monthlyPayment = 0;
                    let totalPayment = 0;
                    let totalInterest = 0;

                    if (loanType === 'bunga') {
                        // Interest-based loan calculation
                        const interestRate = parseFloat($('#interest_rate').val()) || 0;
                        
                        if (interestRate > 0) {
                            const monthlyRate = (interestRate / 100) / 12;
                            monthlyPayment = loanAmount * (monthlyRate * Math.pow(1 + monthlyRate, loanTermMonths)) / 
                                           (Math.pow(1 + monthlyRate, loanTermMonths) - 1);
                            totalPayment = monthlyPayment * loanTermMonths;
                            totalInterest = totalPayment - loanAmount;
                        } else {
                            monthlyPayment = loanAmount / loanTermMonths;
                            totalPayment = loanAmount;
                            totalInterest = 0;
                        }
                    } else if (loanType === 'bagi_hasil') {
                        // Profit-sharing loan calculation
                        const expectedProfit = parseFloat($('#expected_profit').val()) || 0;
                        const profitSharingPercentage = parseFloat($('#profit_sharing_percentage').val()) || 0;
                        
                        const bumdesShare = expectedProfit * (profitSharingPercentage / 100);
                        totalPayment = loanAmount + bumdesShare;
                        monthlyPayment = totalPayment / loanTermMonths;
                        totalInterest = bumdesShare;
                    } else if (loanType === 'tanpa_bunga') {
                        // Interest-free loan calculation
                        monthlyPayment = loanAmount / loanTermMonths;
                        totalPayment = loanAmount;
                        totalInterest = 0;
                    }

                    // Add admin fee to total
                    totalPayment += adminFee;

                    // Update display
                    $('#monthly_payment_display').text('Rp ' + numberFormat(monthlyPayment));
                    $('#total_payment_display').text('Rp ' + numberFormat(totalPayment));
                    
                    if (loanType === 'bagi_hasil') {
                        $('#total_interest_display').text('Rp ' + numberFormat(totalInterest));
                        $('.card-body .col-md-4:last-child strong').text('Bagi Hasil BUMDES:');
                    } else {
                        $('#total_interest_display').text('Rp ' + numberFormat(totalInterest));
                        $('.card-body .col-md-4:last-child strong').text('Total Bunga:');
                    }
                } else {
                    $('#monthly_payment_display').text('Rp 0');
                    $('#total_payment_display').text('Rp 0');
                    $('#total_interest_display').text('Rp 0');
                }
            }

            // Function to format number
            function numberFormat(number) {
                return Math.round(number).toLocaleString('id-ID');
            }

            // Handle loan type change
            $('#loan_type').on('change', function() {
                toggleLoanTypeFields();
                calculatePayment();
            });

            // Bind calculation to input changes
            $('#loan_amount, #interest_rate, #loan_term_months, #expected_profit, #profit_sharing_percentage, #admin_fee').on('input change', calculatePayment);

            // Initial setup
            toggleLoanTypeFields();
            calculatePayment();

            // Format phone number
            $('#borrower_phone').on('input', function() {
                let value = $(this).val().replace(/\D/g, '');
                if (value.startsWith('0')) {
                    value = '62' + value.substring(1);
                }
                $(this).val(value);
            });

            // Format loan amount
            $('#loan_amount').on('input', function() {
                let value = $(this).val();
                // Remove any non-digit characters except decimal point
                value = value.replace(/[^\d]/g, '');
                $(this).val(value);
            });
        });
    </script>
@stop