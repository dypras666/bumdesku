@extends('adminlte::page')

@section('title', 'Daftar Buku Besar')

@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h1>Daftar Buku Besar</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Daftar Buku Besar</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Filter</h3>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('general-ledger.index') }}">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="account_id">Akun</label>
                                    <select name="account_id" id="account_id" class="form-control">
                                        <option value="">Semua Akun</option>
                                        @foreach($accounts as $account)
                                            <option value="{{ $account->id }}" {{ request('account_id') == $account->id ? 'selected' : '' }}>
                                                {{ $account->account_code }} - {{ $account->account_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="start_date">Tanggal Mulai</label>
                                    <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="end_date">Tanggal Akhir</label>
                                    <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="">Semua Status</option>
                                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                        <option value="posted" {{ request('status') == 'posted' ? 'selected' : '' }}>Posted</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Filter
                                </button>
                                <a href="{{ route('general-ledger.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Reset
                                </a>
                                <a href="{{ route('general-ledger.create') }}" class="btn btn-success float-right">
                                    <i class="fas fa-plus"></i> Input Jurnal Baru
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Entri Buku Besar</h3>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Kode Entri</th>
                                <th>Tanggal Posting</th>
                                <th>Akun</th>
                                <th>Keterangan</th>
                                <th>Referensi</th>
                                <th>Debit</th>
                                <th>Kredit</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($entries as $entry)
                                <tr>
                                    <td>{{ $entry->entry_code }}</td>
                                    <td>{{ $entry->posting_date->format('d/m/Y') }}</td>
                                    <td>
                                        <strong>{{ $entry->account->account_code }}</strong><br>
                                        <small>{{ $entry->account->account_name }}</small>
                                    </td>
                                    <td>{{ $entry->description }}</td>
                                    <td>
                                        @if($entry->reference_type && $entry->reference_number)
                                            <small class="text-muted">{{ $entry->reference_type }}: {{ $entry->reference_number }}</small>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        @if($entry->debit > 0)
                                            <span class="text-success">{{ $entry->formatted_debit }}</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        @if($entry->credit > 0)
                                            <span class="text-danger">{{ $entry->formatted_credit }}</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge {{ $entry->status_badge_class }}">
                                            {{ $entry->status_label }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('general-ledger.show', $entry) }}" class="btn btn-sm btn-info" title="Lihat">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($entry->status === 'draft')
                                                <a href="{{ route('general-ledger.edit', $entry) }}" class="btn btn-sm btn-warning" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('general-ledger.post', $entry) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success" title="Post" onclick="return confirm('Yakin ingin memposting entri ini?')">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                                <form action="{{ route('general-ledger.destroy', $entry) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Hapus" onclick="return confirm('Yakin ingin menghapus entri ini?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">Tidak ada data entri buku besar</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($entries->hasPages() || $entries->total() > 0)
                    <div class="card-footer bg-light">
                        {{ $entries->appends(request()->query())->links('pagination.bootstrap-4') }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    @if(request()->has(['account_id', 'start_date', 'end_date']) && request('account_id'))
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Saldo Akun</h3>
                    </div>
                    <div class="card-body">
                        @php
                            $account = $accounts->find(request('account_id'));
                            $balance = $account ? $account->getCurrentBalance(request('start_date'), request('end_date')) : 0;
                        @endphp
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Akun:</strong> {{ $account->account_code ?? '' }} - {{ $account->account_name ?? '' }}
                            </div>
                            <div class="col-md-6 text-right">
                                <strong>Saldo:</strong> 
                                <span class="badge {{ $balance >= 0 ? 'badge-success' : 'badge-danger' }}">
                                    {{ format_currency(abs($balance)) }} {{ $balance >= 0 ? '(Debit)' : '(Kredit)' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@stop

@section('css')
    <style>
        .table th {
            white-space: nowrap;
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
        }
        .btn-group .btn {
            margin-right: 2px;
        }
        
        /* Pagination Styling */
        .pagination-info {
            font-size: 0.875rem;
            color: #6c757d;
        }
        
        .pagination .page-link {
            border-radius: 0.25rem;
            margin: 0 2px;
            transition: all 0.2s ease-in-out;
        }
        
        .pagination .page-link:hover {
            background-color: #e9ecef;
            border-color: #dee2e6;
            transform: translateY(-1px);
        }
        
        .pagination .page-item.active .page-link {
            background-color: #007bff;
            border-color: #007bff;
            box-shadow: 0 2px 4px rgba(0,123,255,0.25);
        }
        
        .pagination .page-item.disabled .page-link {
            background-color: #f8f9fa;
            border-color: #dee2e6;
            color: #6c757d;
        }
        
        .card-footer {
            padding: 1rem 1.25rem;
            border-top: 1px solid #dee2e6;
        }
        
        /* Responsive pagination */
        @media (max-width: 576px) {
            .pagination-info {
                text-align: center;
                margin-bottom: 0.5rem;
            }
            
            .pagination {
                justify-content: center;
            }
            
            .pagination .page-link {
                padding: 0.375rem 0.5rem;
                font-size: 0.875rem;
            }
        }
        
        /* Table responsive improvements */
        .table-responsive {
            border-radius: 0.25rem;
        }
        
        .table tbody tr:hover {
            background-color: #f8f9fa;
        }
        
        .badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }
        
        /* Status badges */
        .badge-success {
            background-color: #28a745;
        }
        
        .badge-warning {
            background-color: #ffc107;
            color: #212529;
        }
        
        .badge-secondary {
            background-color: #6c757d;
        }
    </style>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            // Auto submit form when filter changes
            $('#account_id, #status').change(function() {
                $(this).closest('form').submit();
            });
        });
    </script>
@stop