@extends('adminlte::page')

@section('title', 'Master Persediaan')

@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h1>Master Persediaan</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Master Persediaan</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Master Persediaan</h3>
                    <div class="card-tools">
                        <a href="{{ route('master-inventories.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Tambah Barang
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="inventoriesTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Barang</th>
                                    <th>Nama Barang</th>
                                    <th>Kategori</th>
                                    <th>Satuan</th>
                                    <th>Harga Beli</th>
                                    <th>Harga Jual</th>
                                    <th>Margin</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($inventories as $index => $inventory)
                                    <tr>
                                        <td>{{ $inventories->firstItem() + $index }}</td>
                                        <td>{{ $inventory->kode_barang }}</td>
                                        <td>{{ $inventory->nama_barang }}</td>
                                        <td>{{ $inventory->kategori_barang }}</td>
                                        <td>{{ $inventory->satuan }}</td>
                                        <td>{{ $inventory->formatted_harga_beli }}</td>
                                        <td>{{ $inventory->formatted_harga_jual }}</td>
                                        <td>
                                            @if($inventory->margin > 0)
                                                <span class="badge badge-success">{{ number_format($inventory->margin, 1) }}%</span>
                                            @elseif($inventory->margin < 0)
                                                <span class="badge badge-danger">{{ number_format($inventory->margin, 1) }}%</span>
                                            @else
                                                <span class="badge badge-secondary">0%</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($inventory->is_active)
                                                <span class="badge badge-success">Aktif</span>
                                            @else
                                                <span class="badge badge-danger">Tidak Aktif</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('master-inventories.show', $inventory) }}" class="btn btn-info btn-sm">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('master-inventories.edit', $inventory) }}" class="btn btn-warning btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-danger btn-sm" onclick="deleteInventory({{ $inventory->id }})">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($inventories->hasPages())
                    <div class="card-footer clearfix">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="dataTables_info">
                                    Menampilkan {{ $inventories->firstItem() }} sampai {{ $inventories->lastItem() }} 
                                    dari {{ $inventories->total() }} data barang
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="dataTables_paginate paging_simple_numbers float-right">
                                    {{ $inventories->links('pagination::bootstrap-4') }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin menghapus barang ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <form id="deleteForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
<script>
    function deleteInventory(id) {
        const form = document.getElementById('deleteForm');
        form.action = '/master-inventories/' + id;
        $('#deleteModal').modal('show');
    }

    // Auto hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
</script>
@stop