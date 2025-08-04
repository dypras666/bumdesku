@extends('adminlte::page')

@section('title', 'Backup Database')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Backup Database</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active">Backup Database</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <!-- Alert Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <!-- Info Card -->
        <div class="row">
            <div class="col-12">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-info-circle"></i> Informasi Backup Database
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Database:</strong> {{ config('database.connections.mysql.database') }}</p>
                                <p><strong>Host:</strong> {{ config('database.connections.mysql.host') }}</p>
                                <p><strong>Port:</strong> {{ config('database.connections.mysql.port') }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Lokasi Backup:</strong> storage/app/backups/</p>
                                <p><strong>Format File:</strong> SQL (.sql)</p>
                                <p><strong>Total Backup:</strong> {{ count($backups) }} file</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Create Backup Card -->
        <div class="row">
            <div class="col-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-plus"></i> Buat Backup Baru
                        </h3>
                    </div>
                    <div class="card-body">
                        <p>Klik tombol di bawah untuk membuat backup database baru. Proses ini akan membuat file SQL yang berisi seluruh data database.</p>
                        <form action="{{ route('backups.create') }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-lg" onclick="return confirm('Apakah Anda yakin ingin membuat backup database?')">
                                <i class="fas fa-database"></i> Buat Backup Database
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Backup Files List -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-list"></i> Daftar File Backup
                        </h3>
                    </div>
                    <div class="card-body">
                        @if(count($backups) > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th width="5%">#</th>
                                            <th width="35%">Nama File</th>
                                            <th width="15%">Ukuran</th>
                                            <th width="20%">Tanggal Dibuat</th>
                                            <th width="25%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($backups as $index => $backup)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <i class="fas fa-file-archive text-primary"></i>
                                                    {{ $backup['filename'] }}
                                                </td>
                                                <td>
                                                    <span class="badge badge-info">{{ $backup['size'] }}</span>
                                                </td>
                                                <td>
                                                    <i class="fas fa-calendar"></i>
                                                    {{ $backup['created_at']->format('d/m/Y H:i:s') }}
                                                    <br>
                                                    <small class="text-muted">{{ $backup['created_at']->diffForHumans() }}</small>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('backups.download', $backup['filename']) }}" 
                                                           class="btn btn-success btn-sm" 
                                                           title="Download">
                                                            <i class="fas fa-download"></i> Download
                                                        </a>
                                                        <form action="{{ route('backups.destroy', $backup['filename']) }}" 
                                                              method="POST" 
                                                              style="display: inline;"
                                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus file backup ini?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" 
                                                                    class="btn btn-danger btn-sm" 
                                                                    title="Hapus">
                                                                <i class="fas fa-trash"></i> Hapus
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-database fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Belum ada file backup</h5>
                                <p class="text-muted">Klik tombol "Buat Backup Database" untuk membuat backup pertama Anda.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Warning Card -->
        <div class="row">
            <div class="col-12">
                <div class="card card-warning">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-exclamation-triangle"></i> Peringatan Penting
                        </h3>
                    </div>
                    <div class="card-body">
                        <ul class="mb-0">
                            <li>Backup database berisi seluruh data sensitif sistem. Pastikan file backup disimpan di tempat yang aman.</li>
                            <li>Proses backup dapat memakan waktu tergantung ukuran database.</li>
                            <li>Disarankan untuk melakukan backup secara berkala, terutama sebelum melakukan update sistem.</li>
                            <li>File backup yang sudah tidak diperlukan sebaiknya dihapus untuk menghemat ruang penyimpanan.</li>
                            <li>Pastikan mysqldump tersedia di server untuk proses backup berjalan dengan baik.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .alert {
            border-radius: 0.375rem;
        }
        
        .card {
            box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
        }
        
        .btn-group .btn {
            margin-right: 2px;
        }
        
        .btn-group .btn:last-child {
            margin-right: 0;
        }
        
        .table th {
            background-color: #f8f9fa;
            font-weight: 600;
        }
        
        .badge {
            font-size: 0.875em;
        }
    </style>
@stop

@section('js')
    <script>
        // Auto hide alerts after 5 seconds
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);
        
        // Add loading state to backup button
        $('form').on('submit', function() {
            const button = $(this).find('button[type="submit"]');
            const originalText = button.html();
            
            button.prop('disabled', true);
            button.html('<i class="fas fa-spinner fa-spin"></i> Memproses...');
            
            // Re-enable button after 30 seconds (in case of error)
            setTimeout(function() {
                button.prop('disabled', false);
                button.html(originalText);
            }, 30000);
        });
    </script>
@stop