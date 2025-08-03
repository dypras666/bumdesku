@extends('layouts.app')

@section('title', 'Daftar Transaksi')

@section('content_header')
    <h1>Daftar Transaksi</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Transaksi</h3>
                    <div class="card-tools">
                        <a href="{{ route('transactions.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Tambah Transaksi
                        </a>
                    </div>
                </div>
                
                <!-- Filter Form -->
                <div class="card-body">
                    <form method="GET" action="{{ route('transactions.index') }}" class="mb-3">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="type">Jenis Transaksi</label>
                                    <select name="type" id="type" class="form-control">
                                        <option value="">Semua</option>
                                        <option value="income" {{ request('type') == 'income' ? 'selected' : '' }}>Pemasukan</option>
                                        <option value="expense" {{ request('type') == 'expense' ? 'selected' : '' }}>Pengeluaran</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="">Semua</option>
                                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="search">Pencarian</label>
                                    <input type="text" name="search" id="search" class="form-control" 
                                           placeholder="Kode atau deskripsi..." value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <div>
                                        <button type="submit" class="btn btn-info">
                                            <i class="fas fa-search"></i> Filter
                                        </button>
                                        <a href="{{ route('transactions.index') }}" class="btn btn-secondary">
                                            <i class="fas fa-times"></i> Reset
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>Kode Transaksi</th>
                                <th>Tanggal</th>
                                <th>Jenis</th>
                                <th>Deskripsi</th>
                                <th>Akun</th>
                                <th>Jumlah</th>
                                <th>Status</th>
                                <th>Dibuat Oleh</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $transaction)
                            <tr>
                                <td>{{ $transaction->transaction_code }}</td>
                                <td>{{ $transaction->transaction_date->format('d/m/Y') }}</td>
                                <td>
                                    <span class="badge {{ $transaction->transaction_type === 'income' ? 'bg-success' : 'bg-danger' }}">
                                        {{ $transaction->getTypeLabel() }}
                                    </span>
                                </td>
                                <td>{{ Str::limit($transaction->description, 50) }}</td>
                                <td>{{ $transaction->account->account_name ?? '-' }}</td>
                                <td class="{{ $transaction->transaction_type === 'income' ? 'text-success' : 'text-danger' }}">
                                    {{ format_currency($transaction->amount) }}
                                </td>
                                <td>
                                    <span class="badge {{ $transaction->getStatusBadgeClass() }}">
                                        {{ $transaction->getStatusLabel() }}
                                    </span>
                                </td>
                                <td>{{ $transaction->user->name ?? '-' }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('transactions.show', $transaction) }}" 
                                           class="btn btn-info btn-sm" title="Lihat">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        @if($transaction->status === 'pending')
                                            <a href="{{ route('transactions.edit', $transaction) }}" 
                                               class="btn btn-warning btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            
                                            <form action="{{ route('transactions.approve', $transaction) }}" 
                                                  method="POST" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm" 
                                                        title="Setujui" onclick="return confirm('Yakin ingin menyetujui transaksi ini?')">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                            
                                            <form action="{{ route('transactions.reject', $transaction) }}" 
                                                  method="POST" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-danger btn-sm" 
                                                        title="Tolak" onclick="return confirm('Yakin ingin menolak transaksi ini?')">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        @else
                                            <a href="{{ route('transactions.edit', $transaction) }}" 
                                               class="btn btn-warning btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endif
                                        
                                        <form action="{{ route('transactions.destroy', $transaction) }}" 
                                              method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" 
                                                    title="Hapus" onclick="return confirm('Yakin ingin menghapus transaksi ini?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center">Tidak ada data transaksi</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($transactions->hasPages())
                <div class="card-footer">
                    {{ $transactions->appends(request()->query())->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@stop