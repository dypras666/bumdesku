@extends('layouts.app')

@section('title', 'Tambah Transaksi')

@section('content_header')
    <h1>Tambah Transaksi</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Form Tambah Transaksi</h3>
                </div>
                
                <form action="{{ route('transactions.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="transaction_type">Jenis Transaksi <span class="text-danger">*</span></label>
                                    <select name="transaction_type" id="transaction_type" 
                                            class="form-control @error('transaction_type') is-invalid @enderror" required>
                                        <option value="">Pilih Jenis Transaksi</option>
                                        <option value="income" {{ old('transaction_type') == 'income' ? 'selected' : '' }}>
                                            Pemasukan
                                        </option>
                                        <option value="expense" {{ old('transaction_type') == 'expense' ? 'selected' : '' }}>
                                            Pengeluaran
                                        </option>
                                    </select>
                                    @error('transaction_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="transaction_date">Tanggal Transaksi <span class="text-danger">*</span></label>
                                    <input type="date" name="transaction_date" id="transaction_date" 
                                           class="form-control @error('transaction_date') is-invalid @enderror"
                                           value="{{ old('transaction_date', date('Y-m-d')) }}" required>
                                    @error('transaction_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="account_id">Akun <span class="text-danger">*</span></label>
                                    <select name="account_id" id="account_id" 
                                            class="form-control @error('account_id') is-invalid @enderror" required>
                                        <option value="">Pilih Akun</option>
                                        @foreach($accounts as $account)
                                            <option value="{{ $account->id }}" 
                                                    {{ old('account_id') == $account->id ? 'selected' : '' }}>
                                                {{ $account->account_code }} - {{ $account->account_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('account_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="amount">Jumlah <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rp</span>
                                        </div>
                                        <input type="number" name="amount" id="amount" 
                                               class="form-control @error('amount') is-invalid @enderror"
                                               value="{{ old('amount') }}" min="0" step="0.01" required>
                                        @error('amount')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="description">Deskripsi <span class="text-danger">*</span></label>
                            <textarea name="description" id="description" rows="3"
                                      class="form-control @error('description') is-invalid @enderror"
                                      placeholder="Masukkan deskripsi transaksi..." required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="notes">Catatan</label>
                            <textarea name="notes" id="notes" rows="2"
                                      class="form-control @error('notes') is-invalid @enderror"
                                      placeholder="Catatan tambahan (opsional)">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Transaksi
                        </button>
                        <a href="{{ route('transactions.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informasi</h3>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h5><i class="icon fas fa-info"></i> Petunjuk:</h5>
                        <ul class="mb-0">
                            <li>Pilih jenis transaksi (Pemasukan/Pengeluaran)</li>
                            <li>Pilih akun yang sesuai dengan transaksi</li>
                            <li>Masukkan jumlah dalam Rupiah</li>
                            <li>Berikan deskripsi yang jelas</li>
                            <li>Transaksi akan berstatus "Pending" dan perlu persetujuan</li>
                        </ul>
                    </div>
                    
                    <div class="alert alert-warning">
                        <h6><i class="icon fas fa-exclamation-triangle"></i> Perhatian:</h6>
                        <p class="mb-0">Pastikan data yang dimasukkan sudah benar karena transaksi yang sudah disetujui tidak dapat diubah.</p>
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
    // Format input amount
    $('#amount').on('input', function() {
        let value = $(this).val();
        if (value) {
            // Remove any non-numeric characters except decimal point
            value = value.replace(/[^0-9.]/g, '');
            $(this).val(value);
        }
    });
    
    // Auto-generate transaction code preview
    $('#transaction_type, #transaction_date').on('change', function() {
        updateTransactionCodePreview();
    });
    
    function updateTransactionCodePreview() {
        let type = $('#transaction_type').val();
        let date = $('#transaction_date').val();
        
        if (type && date) {
            let dateObj = new Date(date);
            let year = dateObj.getFullYear();
            let month = String(dateObj.getMonth() + 1).padStart(2, '0');
            let day = String(dateObj.getDate()).padStart(2, '0');
            
            let prefix = type === 'income' ? 'IN' : 'OUT';
            let preview = prefix + year + month + day + 'XXX';
            
            // Show preview if not already shown
            if (!$('#code-preview').length) {
                $('#transaction_type').closest('.form-group').after(
                    '<div id="code-preview" class="alert alert-info mt-2">' +
                    '<small><strong>Preview Kode:</strong> ' + preview + '</small>' +
                    '</div>'
                );
            } else {
                $('#code-preview').html('<small><strong>Preview Kode:</strong> ' + preview + '</small>');
            }
        } else {
            $('#code-preview').remove();
        }
    }
});
</script>
@stop