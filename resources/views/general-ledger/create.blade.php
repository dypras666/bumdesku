@extends('adminlte::page')

@section('title', 'Tambah Entri Buku Besar')

@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h1>Tambah Entri Buku Besar</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('general-ledger.index') }}">Daftar Buku Besar</a></li>
                <li class="breadcrumb-item active">Tambah Entri</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Form Tambah Entri</h3>
                </div>
                <form action="{{ route('general-ledger.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="posting_date">Tanggal Posting <span class="text-danger">*</span></label>
                                    <input type="date" name="posting_date" id="posting_date" 
                                           class="form-control @error('posting_date') is-invalid @enderror" 
                                           value="{{ old('posting_date', date('Y-m-d')) }}" required>
                                    @error('posting_date')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="account_id">Akun <span class="text-danger">*</span></label>
                                    <select name="account_id" id="account_id" 
                                            class="form-control @error('account_id') is-invalid @enderror" required>
                                        <option value="">Pilih Akun</option>
                                        @foreach($accounts as $account)
                                            <option value="{{ $account->id }}" 
                                                {{ old('account_id') == $account->id ? 'selected' : '' }}>
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
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="debit">Debit</label>
                                    <input type="number" name="debit" id="debit" 
                                           class="form-control @error('debit') is-invalid @enderror" 
                                           value="{{ old('debit') }}" min="0" step="0.01">
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
                                           value="{{ old('credit') }}" min="0" step="0.01">
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
                                              placeholder="Masukkan keterangan jurnal..." required>{{ old('description') }}</textarea>
                                    @error('description')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="reference_type">Jenis Referensi <span class="text-danger">*</span></label>
                                    <select name="reference_type" id="reference_type" 
                                            class="form-control @error('reference_type') is-invalid @enderror" required>
                                        <option value="">Pilih Jenis Referensi</option>
                                        <option value="transaction" {{ old('reference_type') == 'transaction' ? 'selected' : '' }}>Transaksi</option>
                                        <option value="invoice" {{ old('reference_type') == 'invoice' ? 'selected' : '' }}>Invoice</option>
                                        <option value="receipt" {{ old('reference_type') == 'receipt' ? 'selected' : '' }}>Kwitansi</option>
                                        <option value="adjustment" {{ old('reference_type') == 'adjustment' ? 'selected' : '' }}>Penyesuaian</option>
                                        <option value="other" {{ old('reference_type') == 'other' ? 'selected' : '' }}>Lainnya</option>
                                    </select>
                                    @error('reference_type')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="reference_number">Nomor Referensi</label>
                                    <input type="text" name="reference_number" id="reference_number" 
                                           class="form-control @error('reference_number') is-invalid @enderror" 
                                           value="{{ old('reference_number') }}" 
                                           placeholder="Masukkan nomor referensi">
                                    @error('reference_number')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="transaction_id">Transaksi Terkait</label>
                                    <select name="transaction_id" id="transaction_id" 
                                            class="form-control @error('transaction_id') is-invalid @enderror">
                                        <option value="">Pilih Transaksi (Opsional)</option>
                                    </select>
                                    @error('transaction_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>Draft</option>
                                        <option value="posted" {{ old('status') == 'posted' ? 'selected' : '' }}>Posted</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Catatan:</strong>
                            <ul class="mb-0 mt-2">
                                <li>Salah satu dari Debit atau Kredit harus diisi (tidak boleh keduanya kosong)</li>
                                <li>Debit dan Kredit tidak boleh diisi bersamaan</li>
                                <li>Jika status "Posted", entri tidak dapat diubah lagi</li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                        <a href="{{ route('general-ledger.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('js')
<script>
$(document).ready(function() {
    // Simple validation for debit and credit
    $('#debit, #credit').on('input', function() {
        var debitVal = parseFloat($('#debit').val()) || 0;
        var creditVal = parseFloat($('#credit').val()) || 0;
        
        if (debitVal > 0 && creditVal > 0) {
            if ($(this).attr('id') === 'debit') {
                $('#credit').val('');
            } else {
                $('#debit').val('');
            }
        }
    });

    // Form validation before submit
    $('form').on('submit', function(e) {
        var debitVal = parseFloat($('#debit').val()) || 0;
        var creditVal = parseFloat($('#credit').val()) || 0;
        
        if (debitVal === 0 && creditVal === 0) {
            e.preventDefault();
            alert('Salah satu dari Debit atau Kredit harus diisi!');
            return false;
        }
        
        if (debitVal > 0 && creditVal > 0) {
            e.preventDefault();
            alert('Debit dan Kredit tidak boleh diisi bersamaan!');
            return false;
        }
    });
});
</script>
@stop