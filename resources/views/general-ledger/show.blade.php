@extends('adminlte::page')

@section('title', 'Detail Entri Buku Besar')

@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h1>Detail Entri Buku Besar</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('general-ledger.index') }}">Daftar Buku Besar</a></li>
                <li class="breadcrumb-item active">Detail Entri</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informasi Entri</h3>
                    <div class="card-tools">
                        <span class="badge {{ $entry->status_badge_class }} badge-lg">
                            {{ $entry->status_label }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Kode Entri:</strong></td>
                                    <td>{{ $entry->entry_code }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tanggal Posting:</strong></td>
                                    <td>{{ $entry->posting_date->format('d F Y') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Akun:</strong></td>
                                    <td>
                                        <strong>{{ $entry->account->account_code }}</strong><br>
                                        {{ $entry->account->account_name }}<br>
                                        <small class="text-muted">{{ $entry->account->account_category }}</small>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        <span class="badge {{ $entry->status_badge_class }}">
                                            {{ $entry->status_label }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Debit:</strong></td>
                                    <td class="text-right">
                                        @if($entry->debit > 0)
                                            <span class="text-success h5">{{ $entry->formatted_debit }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Kredit:</strong></td>
                                    <td class="text-right">
                                        @if($entry->credit > 0)
                                            <span class="text-danger h5">{{ $entry->formatted_credit }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Dibuat:</strong></td>
                                    <td>{{ $entry->created_at->format('d F Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Diperbarui:</strong></td>
                                    <td>{{ $entry->updated_at->format('d F Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12">
                            <h5>Keterangan</h5>
                            <div class="border p-3 bg-light">
                                {{ $entry->description }}
                            </div>
                        </div>
                    </div>

                    @if($entry->reference_type || $entry->reference_number)
                        <div class="row mt-3">
                            <div class="col-12">
                                <h5>Referensi</h5>
                                <table class="table table-borderless">
                                    @if($entry->reference_type)
                                        <tr>
                                            <td width="150"><strong>Jenis Referensi:</strong></td>
                                            <td>{{ ucfirst($entry->reference_type) }}</td>
                                        </tr>
                                    @endif
                                    @if($entry->reference_number)
                                        <tr>
                                            <td><strong>Nomor Referensi:</strong></td>
                                            <td>{{ $entry->reference_number }}</td>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                    @endif

                    @if($entry->transaction)
                        <div class="row mt-3">
                            <div class="col-12">
                                <h5>Transaksi Terkait</h5>
                                <div class="card card-outline card-info">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <strong>Kode Transaksi:</strong> {{ $entry->transaction->transaction_code }}<br>
                                                <strong>Jenis:</strong> {{ $entry->transaction->transaction_type }}<br>
                                                <strong>Tanggal:</strong> {{ $entry->transaction->transaction_date->format('d F Y') }}
                                            </div>
                                            <div class="col-md-6">
                                                <strong>Jumlah:</strong> {{ format_currency($entry->transaction->amount) }}<br>
                                                <strong>Status:</strong> 
                                                <span class="badge {{ $entry->transaction->status_badge_class }}">
                                                    {{ $entry->transaction->status_label }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-12">
                                                <strong>Keterangan:</strong> {{ $entry->transaction->description }}
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-12">
                                                <a href="{{ route('transactions.show', $entry->transaction) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i> Lihat Detail Transaksi
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($entry->posted_by && $entry->posted_at)
                        <div class="row mt-3">
                            <div class="col-12">
                                <h5>Informasi Posting</h5>
                                <table class="table table-borderless">
                                    <tr>
                                        <td width="150"><strong>Diposting oleh:</strong></td>
                                        <td>{{ $entry->postedBy->name }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Tanggal Posting:</strong></td>
                                        <td>{{ $entry->posted_at->format('d F Y H:i') }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="card-footer">
                    @if($entry->status === 'draft')
                        <a href="{{ route('general-ledger.edit', $entry) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('general-ledger.post', $entry) }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-success" onclick="return confirm('Yakin ingin memposting entri ini? Setelah diposting, entri tidak dapat diubah lagi.')">
                                <i class="fas fa-check"></i> Post Entri
                            </button>
                        </form>
                        <form action="{{ route('general-ledger.destroy', $entry) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus entri ini?')">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </form>
                    @endif
                    <a href="{{ route('general-ledger.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Saldo Akun Saat Ini</h3>
                </div>
                <div class="card-body">
                    @php
                        $currentBalance = $entry->account->getCurrentBalance();
                    @endphp
                    <div class="text-center">
                        <h4 class="text-{{ $currentBalance >= 0 ? 'success' : 'danger' }}">
                            {{ format_currency(abs($currentBalance)) }}
                        </h4>
                        <p class="text-muted">
                            {{ $currentBalance >= 0 ? 'Saldo Debit' : 'Saldo Kredit' }}
                        </p>
                    </div>
                    <hr>
                    <small class="text-muted">
                        <i class="fas fa-info-circle"></i>
                        Saldo dihitung berdasarkan semua entri yang telah diposting untuk akun ini.
                    </small>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Entri Terkait</h3>
                </div>
                <div class="card-body">
                    @php
                        $relatedEntries = $entry->account->generalLedgerEntries()
                            ->where('id', '!=', $entry->id)
                            ->where('status', 'posted')
                            ->orderBy('posting_date', 'desc')
                            ->limit(5)
                            ->get();
                    @endphp
                    
                    @if($relatedEntries->count() > 0)
                        @foreach($relatedEntries as $relatedEntry)
                            <div class="border-bottom pb-2 mb-2">
                                <div class="d-flex justify-content-between">
                                    <small>
                                        <strong>{{ $relatedEntry->entry_code }}</strong><br>
                                        {{ $relatedEntry->posting_date->format('d/m/Y') }}
                                    </small>
                                    <small class="text-right">
                                        @if($relatedEntry->debit > 0)
                                            <span class="text-success">+{{ $relatedEntry->formatted_debit }}</span>
                                        @else
                                            <span class="text-danger">-{{ $relatedEntry->formatted_credit }}</span>
                                        @endif
                                    </small>
                                </div>
                                <small class="text-muted">{{ Str::limit($relatedEntry->description, 50) }}</small>
                            </div>
                        @endforeach
                        <div class="text-center mt-2">
                            <a href="{{ route('general-ledger.index', ['account_id' => $entry->account_id]) }}" class="btn btn-sm btn-outline-primary">
                                Lihat Semua Entri Akun
                            </a>
                        </div>
                    @else
                        <p class="text-muted text-center">Tidak ada entri lain untuk akun ini</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .table-borderless td {
            border: none;
            padding: 0.25rem 0.5rem;
        }
        .badge-lg {
            font-size: 0.9rem;
            padding: 0.5rem 1rem;
        }
    </style>
@stop