@extends('layouts.app')

@section('title', 'Edit Transaksi')

@section('content_header')
    <h1>Edit Transaksi</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Form Edit Transaksi</h3>
                    <div class="card-tools">
                        <span class="badge {{ $transaction->getStatusBadgeClass() }}">
                            {{ $transaction->getStatusLabel() }}
                        </span>
                    </div>
                </div>
                
                <form action="{{ route('transactions.update', $transaction) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="transaction_code">Kode Transaksi</label>
                                    <input type="text" class="form-control" value="{{ $transaction->transaction_code }}" readonly>
                                    <small class="text-muted">Kode transaksi tidak dapat diubah</small>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="transaction_type">Jenis Transaksi <span class="text-danger">*</span></label>
                                    <select name="transaction_type" id="transaction_type" 
                                            class="form-control @error('transaction_type') is-invalid @enderror" 
                                            {{ $transaction->status === 'approved' ? 'disabled' : '' }} required>
                                        <option value="">Pilih Jenis Transaksi</option>
                                        <option value="income" {{ old('transaction_type', $transaction->transaction_type) == 'income' ? 'selected' : '' }}>
                                            Pemasukan
                                        </option>
                                        <option value="expense" {{ old('transaction_type', $transaction->transaction_type) == 'expense' ? 'selected' : '' }}>
                                            Pengeluaran
                                        </option>
                                    </select>
                                    @if($transaction->status === 'approved')
                                        <input type="hidden" name="transaction_type" value="{{ $transaction->transaction_type }}">
                                        <small class="text-muted">Jenis transaksi tidak dapat diubah untuk transaksi yang sudah disetujui</small>
                                    @endif
                                    @error('transaction_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="transaction_date">Tanggal Transaksi <span class="text-danger">*</span></label>
                                    <input type="date" name="transaction_date" id="transaction_date" 
                                           class="form-control @error('transaction_date') is-invalid @enderror"
                                           value="{{ old('transaction_date', $transaction->transaction_date->format('Y-m-d')) }}" 
                                           {{ $transaction->status === 'approved' ? 'readonly' : '' }} required>
                                    @if($transaction->status === 'approved')
                                        <small class="text-muted">Tanggal tidak dapat diubah untuk transaksi yang sudah disetujui</small>
                                    @endif
                                    @error('transaction_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="account_id">Akun <span class="text-danger">*</span></label>
                                    <select name="account_id" id="account_id" 
                                            class="form-control @error('account_id') is-invalid @enderror" 
                                            {{ $transaction->status === 'approved' ? 'disabled' : '' }} required>
                                        <option value="">Pilih Akun</option>
                                        @foreach($accounts as $account)
                                            <option value="{{ $account->id }}" 
                                                    {{ old('account_id', $transaction->account_id) == $account->id ? 'selected' : '' }}>
                                                {{ $account->account_code }} - {{ $account->account_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if($transaction->status === 'approved')
                                        <input type="hidden" name="account_id" value="{{ $transaction->account_id }}">
                                        <small class="text-muted">Akun tidak dapat diubah untuk transaksi yang sudah disetujui</small>
                                    @endif
                                    @error('account_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="amount">Jumlah <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="number" name="amount" id="amount" 
                                       class="form-control @error('amount') is-invalid @enderror"
                                       value="{{ old('amount', $transaction->amount) }}" 
                                       min="0" step="0.01" 
                                       {{ $transaction->status === 'approved' ? 'readonly' : '' }} required>
                                @error('amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            @if($transaction->status === 'approved')
                                <small class="text-muted">Jumlah tidak dapat diubah untuk transaksi yang sudah disetujui</small>
                            @endif
                        </div>
                        
                        <div class="form-group">
                            <label for="description">Deskripsi <span class="text-danger">*</span></label>
                            <textarea name="description" id="description" rows="3"
                                      class="form-control @error('description') is-invalid @enderror"
                                      placeholder="Masukkan deskripsi transaksi..." required>{{ old('description', $transaction->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="notes">Catatan</label>
                            <textarea name="notes" id="notes" rows="2"
                                      class="form-control @error('notes') is-invalid @enderror"
                                      placeholder="Catatan tambahan (opsional)">{{ old('notes', $transaction->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        @if($transaction->status !== 'pending')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Status Persetujuan</label>
                                    <p class="form-control-static">
                                        <span class="badge {{ $transaction->getStatusBadgeClass() }}">
                                            {{ $transaction->getStatusLabel() }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                            
                            @if($transaction->approved_at)
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tanggal Persetujuan</label>
                                    <p class="form-control-static">{{ $transaction->approved_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                        
                        @if($transaction->approver)
                        <div class="form-group">
                            <label>Disetujui Oleh</label>
                            <p class="form-control-static">{{ $transaction->approver->name }}</p>
                        </div>
                        @endif
                        @endif
                    </div>
                    
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Transaksi
                        </button>
                        <a href="{{ route('transactions.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                        
                        @if($transaction->status === 'pending')
                        <div class="float-right">
                            <form action="{{ route('transactions.approve', $transaction) }}" 
                                  method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-success" 
                                        onclick="return confirm('Yakin ingin menyetujui transaksi ini?')">
                                    <i class="fas fa-check"></i> Setujui
                                </button>
                            </form>
                            
                            <form action="{{ route('transactions.reject', $transaction) }}" 
                                  method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-danger" 
                                        onclick="return confirm('Yakin ingin menolak transaksi ini?')">
                                    <i class="fas fa-times"></i> Tolak
                                </button>
                            </form>
                        </div>
                        @endif
                    </div>
                </form>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informasi Transaksi</h3>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <td><strong>Kode:</strong></td>
                            <td>{{ $transaction->transaction_code }}</td>
                        </tr>
                        <tr>
                            <td><strong>Dibuat:</strong></td>
                            <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Dibuat Oleh:</strong></td>
                            <td>{{ $transaction->user->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Terakhir Update:</strong></td>
                            <td>{{ $transaction->updated_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    </table>
                    
                    @if($transaction->status === 'approved')
                    <div class="alert alert-success">
                        <h6><i class="icon fas fa-check"></i> Transaksi Disetujui</h6>
                        <p class="mb-0">Transaksi ini sudah disetujui. Beberapa field tidak dapat diubah.</p>
                    </div>
                    @elseif($transaction->status === 'rejected')
                    <div class="alert alert-danger">
                        <h6><i class="icon fas fa-times"></i> Transaksi Ditolak</h6>
                        <p class="mb-0">Transaksi ini ditolak. Anda dapat mengubah data dan mengajukan ulang.</p>
                    </div>
                    @else
                    <div class="alert alert-warning">
                        <h6><i class="icon fas fa-clock"></i> Menunggu Persetujuan</h6>
                        <p class="mb-0">Transaksi ini masih menunggu persetujuan.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@stop