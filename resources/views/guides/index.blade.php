@extends('adminlte::page')

@section('title', 'Manajemen Panduan')

@section('content_header')
    <h1>Manajemen Panduan</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="card-title">Daftar Panduan</h3>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('guides.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Tambah Panduan
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filter and Search -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <select class="form-control" id="categoryFilter">
                                <option value="">Semua Kategori</option>
                                @foreach(\App\Models\Guide::getCategories() as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select class="form-control" id="statusFilter">
                                <option value="">Semua Status</option>
                                <option value="1">Dipublikasikan</option>
                                <option value="0">Draft</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Cari panduan..." id="searchInput">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Guides Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="guidesTable">
                            <thead>
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="25%">Judul</th>
                                    <th width="15%">Kategori</th>
                                    <th width="10%">Status</th>
                                    <th width="5%">Urutan</th>
                                    <th width="15%">Dibuat</th>
                                    <th width="15%">Penulis</th>
                                    <th width="10%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($guides as $guide)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="{{ $guide->icon }} me-2 text-primary"></i>
                                            <div>
                                                <strong>{{ $guide->title }}</strong>
                                                <br>
                                                <small class="text-muted">{{ \Illuminate\Support\Str::limit($guide->description, 60) }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-info">
                                            {{ \App\Models\Guide::getCategories()[$guide->category] ?? $guide->category }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($guide->is_published)
                                            <span class="badge badge-success">
                                                <i class="fas fa-check"></i> Dipublikasikan
                                            </span>
                                        @else
                                            <span class="badge badge-warning">
                                                <i class="fas fa-clock"></i> Draft
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-secondary">{{ $guide->order }}</span>
                                    </td>
                                    <td>
                                        <small>
                                            {{ $guide->created_at->format('d M Y') }}<br>
                                            {{ $guide->created_at->format('H:i') }}
                                        </small>
                                    </td>
                                    <td>
                                        @if($guide->creator)
                                            <small>{{ $guide->creator->name }}</small>
                                        @else
                                            <small class="text-muted">-</small>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('guides.public.show', $guide->slug) }}" 
                                               class="btn btn-sm btn-info" 
                                               title="Lihat" 
                                               target="_blank">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('guides.edit', $guide->slug) }}" 
                                               class="btn btn-sm btn-warning" 
                                               title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-sm btn-danger" 
                                                    title="Hapus"
                                                    onclick="deleteGuide({{ $guide->id }}, '{{ $guide->title }}')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <i class="fas fa-book-open text-muted" style="font-size: 3rem;"></i>
                                        <h5 class="mt-3 text-muted">Belum Ada Panduan</h5>
                                        <p class="text-muted">Mulai dengan membuat panduan pertama.</p>
                                        <a href="{{ route('guides.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus"></i> Tambah Panduan
                                        </a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($guides->hasPages() || $guides->total() > 0)
                    <div class="card-footer bg-light">
                        {{ $guides->links('pagination.bootstrap-4') }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus panduan "<span id="guideTitle"></span>"?</p>
                <p class="text-danger"><small>Tindakan ini tidak dapat dibatalkan.</small></p>
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
@endsection

@section('css')
<!-- DataTables Local -->
<link rel="stylesheet" href="{{ asset('vendor/datatables/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/datatables/css/responsive.bootstrap4.min.css') }}">
<style>
.card-title {
    margin-bottom: 0;
}
.table th {
    border-top: none;
}
.btn-group .btn {
    margin-right: 2px;
}
.btn-group .btn:last-child {
    margin-right: 0;
}
</style>
@endsection

@section('js')
<!-- DataTables Local -->
<script src="{{ asset('vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('vendor/datatables/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('vendor/datatables/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('vendor/datatables/js/responsive.bootstrap4.min.js') }}"></script>

<script>
$(document).ready(function() {
    // Initialize DataTable
    var table = $('#guidesTable').DataTable({
        "paging": false,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": false,
        "autoWidth": false,
        "responsive": true,
        "order": [[4, "asc"]] // Sort by order column
    });

    // Custom search
    $('#searchInput').on('keyup', function() {
        table.search(this.value).draw();
    });

    // Category filter
    $('#categoryFilter').on('change', function() {
        var category = this.value;
        if (category) {
            table.column(2).search(category).draw();
        } else {
            table.column(2).search('').draw();
        }
    });

    // Status filter
    $('#statusFilter').on('change', function() {
        var status = this.value;
        if (status !== '') {
            var statusText = status === '1' ? 'Dipublikasikan' : 'Draft';
            table.column(3).search(statusText).draw();
        } else {
            table.column(3).search('').draw();
        }
    });
});

function deleteGuide(id, title) {
    $('#guideTitle').text(title);
    $('#deleteForm').attr('action', '/guides/' + id);
    $('#deleteModal').modal('show');
}
</script>
@endsection