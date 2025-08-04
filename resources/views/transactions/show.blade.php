@extends('adminlte::page')

@section('title', 'Detail Transaksi')

@section('content_header')
    <h1>Detail Transaksi</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detail Transaksi: {{ $transaction->transaction_code }}</h3>
                    <div class="card-tools">
                        <span class="badge {{ $transaction->getStatusBadgeClass() }} badge-lg">
                            {{ $transaction->getStatusLabel() }}
                        </span>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="40%"><strong>Kode Transaksi:</strong></td>
                                    <td>{{ $transaction->transaction_code }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Jenis Transaksi:</strong></td>
                                    <td>
                                        <span class="badge {{ $transaction->transaction_type === 'income' ? 'bg-success' : 'bg-danger' }}">
                                            {{ $transaction->getTypeLabel() }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Tanggal Transaksi:</strong></td>
                                    <td>{{ $transaction->transaction_date->format('d F Y') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Akun:</strong></td>
                                    <td>
                                        {{ $transaction->account->account_code ?? '-' }} - 
                                        {{ $transaction->account->account_name ?? '-' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Jumlah:</strong></td>
                                    <td>
                                        <span class="h5 {{ $transaction->transaction_type === 'income' ? 'text-success' : 'text-danger' }}">
                                            {{ format_currency($transaction->amount) }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="40%"><strong>Status:</strong></td>
                                    <td>
                                        <span class="badge {{ $transaction->getStatusBadgeClass() }}">
                                            {{ $transaction->getStatusLabel() }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Dibuat Oleh:</strong></td>
                                    <td>{{ $transaction->user->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tanggal Dibuat:</strong></td>
                                    <td>{{ $transaction->created_at->format('d F Y H:i') }}</td>
                                </tr>
                                @if($transaction->approved_at)
                                <tr>
                                    <td><strong>Tanggal Persetujuan:</strong></td>
                                    <td>{{ $transaction->approved_at->format('d F Y H:i') }}</td>
                                </tr>
                                @endif
                                @if($transaction->approver)
                                <tr>
                                    <td><strong>Disetujui Oleh:</strong></td>
                                    <td>{{ $transaction->approver->name }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label><strong>Deskripsi:</strong></label>
                                <div class="border p-3 bg-light">
                                    {{ $transaction->description }}
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @if($transaction->notes)
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label><strong>Catatan:</strong></label>
                                <div class="border p-3 bg-light">
                                    {{ $transaction->notes }}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                
                <div class="card-footer">
                    <a href="{{ route('transactions.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali ke Daftar
                    </a>
                    
                    <a href="{{ route('transactions.edit', $transaction) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    
                    @if($transaction->status === 'approved')
                    <a href="{{ route('transactions.print-receipt', $transaction) }}" class="btn btn-info" target="_blank">
                        <i class="fas fa-print"></i> Cetak Bukti Kas
                    </a>
                    @endif
                    
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
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Ringkasan</h3>
                </div>
                <div class="card-body">
                    <div class="info-box">
                        <span class="info-box-icon {{ $transaction->transaction_type === 'income' ? 'bg-success' : 'bg-danger' }}">
                            <i class="fas {{ $transaction->transaction_type === 'income' ? 'fa-arrow-up' : 'fa-arrow-down' }}"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">{{ $transaction->getTypeLabel() }}</span>
                            <span class="info-box-number">{{ $transaction->getFormattedAmountAttribute() }}</span>
                        </div>
                    </div>
                    
                    <div class="progress-group">
                        Status Transaksi
                        <span class="float-right">
                            <span class="badge {{ $transaction->getStatusBadgeClass() }}">
                                {{ $transaction->getStatusLabel() }}
                            </span>
                        </span>
                        <div class="progress progress-sm">
                            @if($transaction->status === 'pending')
                                <div class="progress-bar bg-warning" style="width: 33%"></div>
                            @elseif($transaction->status === 'approved')
                                <div class="progress-bar bg-success" style="width: 100%"></div>
                            @else
                                <div class="progress-bar bg-danger" style="width: 100%"></div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            @if($transaction->status === 'approved')
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informasi Persetujuan</h3>
                </div>
                <div class="card-body">
                    <div class="alert alert-success">
                        <h6><i class="icon fas fa-check"></i> Transaksi Disetujui</h6>
                        <p><strong>Disetujui oleh:</strong> {{ $transaction->approver->name ?? '-' }}</p>
                        <p><strong>Tanggal:</strong> {{ $transaction->approved_at->format('d F Y H:i') }}</p>
                        <p class="mb-0">Transaksi ini telah disetujui dan telah mempengaruhi saldo keuangan.</p>
                    </div>
                </div>
            </div>
            @elseif($transaction->status === 'rejected')
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informasi Penolakan</h3>
                </div>
                <div class="card-body">
                    <div class="alert alert-danger">
                        <h6><i class="icon fas fa-times"></i> Transaksi Ditolak</h6>
                        <p><strong>Ditolak oleh:</strong> {{ $transaction->approver->name ?? '-' }}</p>
                        <p><strong>Tanggal:</strong> {{ $transaction->approved_at->format('d F Y H:i') }}</p>
                        <p class="mb-0">Transaksi ini ditolak dan tidak mempengaruhi saldo keuangan.</p>
                    </div>
                </div>
            </div>
            @else
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Menunggu Persetujuan</h3>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning">
                        <h6><i class="icon fas fa-clock"></i> Status Pending</h6>
                        <p class="mb-0">Transaksi ini masih menunggu persetujuan dari administrator.</p>
                    </div>
                    
                    <div class="timeline">
                        <div class="time-label">
                            <span class="bg-info">{{ $transaction->created_at->format('d M Y') }}</span>
                        </div>
                        <div>
                            <i class="fas fa-plus bg-blue"></i>
                            <div class="timeline-item">
                                <span class="time"><i class="fas fa-clock"></i> {{ $transaction->created_at->format('H:i') }}</span>
                                <h3 class="timeline-header">Transaksi Dibuat</h3>
                                <div class="timeline-body">
                                    Transaksi dibuat oleh {{ $transaction->user->name ?? '-' }}
                                </div>
                            </div>
                        </div>
                        <div>
                            <i class="fas fa-clock bg-warning"></i>
                            <div class="timeline-item">
                                <h3 class="timeline-header">Menunggu Persetujuan</h3>
                                <div class="timeline-body">
                                    Transaksi sedang menunggu persetujuan dari administrator
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@stop