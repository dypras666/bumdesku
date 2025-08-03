@extends('adminlte::page')

@section('title', 'Edit Entri Buku Besar')

@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h1>Edit Entri Buku Besar</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('general-ledger.index') }}">Daftar Buku Besar</a></li>
                <li class="breadcrumb-item"><a href="{{ route('general-ledger.show', $entry) }}">Detail Entri</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Form Edit Entri</h3>
                    <div class="card-tools">
                        <span class="badge badge-warning">{{ $entry->entry_code }}</span>
                    </div>
                </div>
                <form action="{{ route('general-ledger.update', $entry) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        @if($entry->status === 'posted')
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i>
                                <strong>Peringatan:</strong> Entri ini sudah diposting dan tidak dapat diubah.
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="posting_date">Tanggal Posting <span class="text-danger">*</span></label>
                                    <input type="date" name="posting_date" id="posting_date" 
                                           class="form-control @error('posting_date') is-invalid @enderror" 
                                           value="{{ old('posting_date', $entry->posting_date->format('Y-m-d')) }}" 
                                           {{ $entry->status === 'posted' ? 'readonly' : 'required' }}>
                                    @error('posting_date')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="account_id">Akun <span class="text-danger">*</span></label>
                                    <select name="account_id" id="account_id" 
                                            class="form-control @error('account_id') is-invalid @enderror" 
                                            {{ $entry->status === 'posted' ? 'disabled' : 'required' }}>
                                        <option value="">Pilih Akun</option>
                                        @foreach($accounts as $account)
                                            <option value="{{ $account->id }}" 
                                                {{ (old('account_id', $entry->account_id) == $account->id) ? 'selected' : '' }}>
                                                {{ $account->account_code }} - {{ $account->account_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if($entry->status === 'posted')
                                        <input type="hidden" name="account_id" value="{{ $entry->account_id }}">
                                    @endif
                                    @error('account_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="debit">Debit</label>
                                    <input type="number" name="debit" id="debit" 
                                           class="form-control @error('debit') is-invalid @enderror" 
                                           value="{{ old('debit', $entry->debit) }}" min="0" step="0.01"
                                           {{ $entry->status === 'posted' ? 'readonly' : '' }}>
                                    @error('debit')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="credit">Kredit</label>
                                    <input type="number" name="credit" id="credit" 
                                           class="form-control @error('credit') is-invalid @enderror" 
                                           value="{{ old('credit', $entry->credit) }}" min="0" step="0.01"
                                           {{ $entry->status === 'posted' ? 'readonly' : '' }}>
                                    @error('credit')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="description">Keterangan <span class="text-danger">*</span></label>
                                    <textarea name="description" id="description" rows="3" 
                                              class="form-control @error('description') is-invalid @enderror" 
                                              placeholder="Masukkan keterangan jurnal..." 
                                              {{ $entry->status === 'posted' ? 'readonly' : 'required' }}>{{ old('description', $entry->description) }}</textarea>
                                    @error('description')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="reference_type">Jenis Referensi</label>
                                    <select name="reference_type" id="reference_type" class="form-control"
                                            {{ $entry->status === 'posted' ? 'disabled' : '' }}>
                                        <option value="">Pilih Jenis Referensi</option>
                                        <option value="transaction" {{ old('reference_type', $entry->reference_type) == 'transaction' ? 'selected' : '' }}>Transaksi</option>
                                        <option value="invoice" {{ old('reference_type', $entry->reference_type) == 'invoice' ? 'selected' : '' }}>Invoice</option>
                                        <option value="receipt" {{ old('reference_type', $entry->reference_type) == 'receipt' ? 'selected' : '' }}>Kwitansi</option>
                                        <option value="adjustment" {{ old('reference_type', $entry->reference_type) == 'adjustment' ? 'selected' : '' }}>Penyesuaian</option>
                                        <option value="other" {{ old('reference_type', $entry->reference_type) == 'other' ? 'selected' : '' }}>Lainnya</option>
                                    </select>
                                    @if($entry->status === 'posted')
                                        <input type="hidden" name="reference_type" value="{{ $entry->reference_type }}">
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="reference_number">Nomor Referensi</label>
                                    <input type="text" name="reference_number" id="reference_number" 
                                           class="form-control" value="{{ old('reference_number', $entry->reference_number) }}" 
                                           placeholder="Masukkan nomor referensi"
                                           {{ $entry->status === 'posted' ? 'readonly' : '' }}>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="transaction_id">Transaksi Terkait</label>
                                    <select name="transaction_id" id="transaction_id" class="form-control"
                                            {{ $entry->status === 'posted' ? 'disabled' : '' }}>
                                        <option value="">Pilih Transaksi (Opsional)</option>
                                        @foreach($transactions as $transaction)
                                            <option value="{{ $transaction->id }}" 
                                                {{ old('transaction_id', $entry->transaction_id) == $transaction->id ? 'selected' : '' }}>
                                                {{ $transaction->transaction_code }} - {{ $transaction->description }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if($entry->status === 'posted')
                                        <input type="hidden" name="transaction_id" value="{{ $entry->transaction_id }}">
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="draft" {{ old('status', $entry->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                        <option value="posted" {{ old('status', $entry->status) == 'posted' ? 'selected' : '' }}>Posted</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        @if($entry->status === 'draft')
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                <strong>Catatan:</strong>
                                <ul class="mb-0 mt-2">
                                    <li>Salah satu dari Debit atau Kredit harus diisi (tidak boleh keduanya kosong)</li>
                                    <li>Jika status diubah ke "Posted", entri tidak dapat diubah lagi</li>
                                    <li>Pastikan semua data sudah benar sebelum memposting</li>
                                </ul>
                            </div>
                        @endif

                        @if($entry->posted_by && $entry->posted_at)
                            <div class="alert alert-warning">
                                <i class="fas fa-info-circle"></i>
                                <strong>Informasi Posting:</strong><br>
                                Diposting oleh: <strong>{{ $entry->postedBy->name }}</strong><br>
                                Tanggal Posting: <strong>{{ $entry->posted_at->format('d F Y H:i') }}</strong>
                            </div>
                        @endif
                    </div>
                    <div class="card-footer">
                        @if($entry->status === 'draft')
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan Perubahan
                            </button>
                            <button type="submit" name="save_and_post" value="1" class="btn btn-success">
                                <i class="fas fa-check"></i> Simpan & Post
                            </button>
                        @endif
                        <a href="{{ route('general-ledger.show', $entry) }}" class="btn btn-info">
                            <i class="fas fa-eye"></i> Lihat Detail
                        </a>
                        <a href="{{ route('general-ledger.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .form-group label {
            font-weight: 600;
        }
        .text-danger {
            color: #dc3545 !important;
        }
        input[readonly], select[disabled], textarea[readonly] {
            background-color: #f8f9fa;
            opacity: 0.8;
        }
    </style>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            var entryStatus = '{{ $entry->status }}';
            
            // Only enable validation if entry is not posted
            if (entryStatus === 'draft') {
                // Validation for debit/credit
                $('#debit, #credit').on('input', function() {
                    var debit = parseFloat($('#debit').val()) || 0;
                    var credit = parseFloat($('#credit').val()) || 0;
                    
                    if (debit > 0 && credit > 0) {
                        alert('Debit dan Kredit tidak boleh diisi bersamaan. Pilih salah satu.');
                        $(this).val(0);
                    }
                });

                // Auto-fill reference number based on transaction
                $('#transaction_id').change(function() {
                    var selectedOption = $(this).find('option:selected');
                    if (selectedOption.val()) {
                        var transactionCode = selectedOption.text().split(' - ')[0];
                        $('#reference_type').val('transaction');
                        $('#reference_number').val(transactionCode);
                    }
                });

                // Form validation before submit
                $('form').on('submit', function(e) {
                    var debit = parseFloat($('#debit').val()) || 0;
                    var credit = parseFloat($('#credit').val()) || 0;
                    
                    if (debit === 0 && credit === 0) {
                        e.preventDefault();
                        alert('Salah satu dari Debit atau Kredit harus diisi!');
                        return false;
                    }
                    
                    if (debit > 0 && credit > 0) {
                        e.preventDefault();
                        alert('Debit dan Kredit tidak boleh diisi bersamaan!');
                        return false;
                    }

                    // Confirm if changing status to posted
                    if ($('#status').val() === 'posted' && entryStatus === 'draft') {
                        if (!confirm('Yakin ingin memposting entri ini? Setelah diposting, entri tidak dapat diubah lagi.')) {
                            e.preventDefault();
                            return false;
                        }
                    }
                });
            }
        });
    </script>
@stop