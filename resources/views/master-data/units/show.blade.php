@extends('adminlte::page')

@section('title', 'Detail Master Unit')

@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h1>Detail Master Unit</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('master-units.index') }}">Master Unit</a></li>
                <li class="breadcrumb-item active">Detail</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informasi Unit</h3>
                    <div class="card-tools">
                        <a href="{{ route('master-units.edit', $unit->id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('master-units.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="40%"><strong>Kode Unit:</strong></td>
                                    <td>{{ $unit->kode_unit }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Nama Unit:</strong></td>
                                    <td>{{ $unit->nama_unit }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Kategori:</strong></td>
                                    <td>
                                        <span class="badge badge-info">{{ $unit->kategori_unit }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Penanggung Jawab:</strong></td>
                                    <td>
                                        @if($unit->penanggungJawab)
                                            <div>
                                                <strong>{{ $unit->penanggungJawab->name }}</strong><br>
                                                <small class="text-muted">{{ $unit->penanggungJawab->email }}</small>
                                            </div>
                                        @else
                                            <span class="text-muted">Belum ditentukan</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        @if($unit->is_active)
                                            <span class="badge badge-success">Aktif</span>
                                        @else
                                            <span class="badge badge-danger">Tidak Aktif</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="40%"><strong>Nilai Aset:</strong></td>
                                    <td>Rp {{ number_format($unit->nilai_aset, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Alamat:</strong></td>
                                    <td>{{ $unit->alamat ?: '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Dibuat:</strong></td>
                                    <td>{{ $unit->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Diperbarui:</strong></td>
                                    <td>{{ $unit->updated_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informasi Kategori</h3>
                </div>
                <div class="card-body">
                    @switch($unit->kategori_unit)
                        @case('Kantor')
                            <div class="alert alert-primary">
                                <h6><i class="icon fas fa-building"></i> Kantor</h6>
                                <p class="mb-0">Unit yang digunakan untuk kegiatan administrasi dan manajemen perusahaan.</p>
                            </div>
                            @break
                        @case('Produksi')
                            <div class="alert alert-success">
                                <h6><i class="icon fas fa-industry"></i> Produksi</h6>
                                <p class="mb-0">Unit yang digunakan untuk kegiatan produksi barang atau jasa.</p>
                            </div>
                            @break
                        @case('Gudang')
                            <div class="alert alert-info">
                                <h6><i class="icon fas fa-warehouse"></i> Gudang</h6>
                                <p class="mb-0">Unit yang digunakan untuk penyimpanan barang dan persediaan.</p>
                            </div>
                            @break
                        @case('Kendaraan')
                            <div class="alert alert-warning">
                                <h6><i class="icon fas fa-truck"></i> Kendaraan</h6>
                                <p class="mb-0">Unit berupa kendaraan untuk transportasi dan distribusi.</p>
                            </div>
                            @break
                        @case('Peralatan')
                            <div class="alert alert-secondary">
                                <h6><i class="icon fas fa-tools"></i> Peralatan</h6>
                                <p class="mb-0">Unit berupa peralatan dan mesin untuk mendukung operasional.</p>
                            </div>
                            @break
                        @default
                            <div class="alert alert-light">
                                <h6><i class="icon fas fa-question"></i> Kategori Lainnya</h6>
                                <p class="mb-0">Kategori unit yang tidak termasuk dalam kategori standar.</p>
                            </div>
                    @endswitch
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Statistik Unit</h3>
                </div>
                <div class="card-body">
                    <div class="info-box">
                        <span class="info-box-icon bg-info"><i class="fas fa-money-bill-wave"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Nilai Aset</span>
                            <span class="info-box-number">
                                Rp {{ number_format($unit->nilai_aset, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="progress-group">
                        <span class="float-right"><b>{{ $unit->is_active ? '100%' : '0%' }}</b></span>
                        <span>Status Operasional</span>
                        <div class="progress progress-sm">
                            <div class="progress-bar {{ $unit->is_active ? 'bg-success' : 'bg-danger' }}" 
                                 style="width: {{ $unit->is_active ? '100' : '0' }}%"></div>
                        </div>
                    </div>
                    
                    <small class="text-muted">
                        @if($unit->is_active)
                            <i class="fas fa-check-circle text-success"></i> Unit sedang beroperasi
                        @else
                            <i class="fas fa-times-circle text-danger"></i> Unit tidak beroperasi
                        @endif
                    </small>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Riwayat Perubahan</h3>
                </div>
                <div class="card-body">
                    @if($unit->changeHistories->count() > 0)
                        <div class="timeline">
                            @php
                                $groupedHistories = $unit->changeHistories->groupBy(function($item) {
                                    return $item->created_at->format('Y-m-d');
                                });
                            @endphp
                            
                            @foreach($groupedHistories as $date => $histories)
                                <div class="time-label">
                                    <span class="bg-primary">{{ \Carbon\Carbon::parse($date)->format('d M Y') }}</span>
                                </div>
                                
                                @foreach($histories as $history)
                                    <div>
                                        @switch($history->action)
                                            @case('create')
                                                <i class="fas fa-plus bg-success"></i>
                                                @break
                                            @case('update')
                                                <i class="fas fa-edit bg-warning"></i>
                                                @break
                                            @case('delete')
                                                <i class="fas fa-trash bg-danger"></i>
                                                @break
                                            @default
                                                <i class="fas fa-info bg-info"></i>
                                        @endswitch
                                        
                                        <div class="timeline-item">
                                            <span class="time">
                                                <i class="fas fa-clock"></i> {{ $history->created_at->format('H:i') }}
                                            </span>
                                            <h3 class="timeline-header">
                                                @switch($history->action)
                                                    @case('create')
                                                        <span class="text-success">Unit Dibuat</span>
                                                        @break
                                                    @case('update')
                                                        <span class="text-warning">Perubahan Data</span>
                                                        @break
                                                    @case('delete')
                                                        <span class="text-danger">Unit Dihapus</span>
                                                        @break
                                                    @default
                                                        <span class="text-info">Aktivitas</span>
                                                @endswitch
                                                
                                                @if($history->changed_by)
                                                    <small class="text-muted">oleh {{ $history->changed_by }}</small>
                                                @endif
                                            </h3>
                                            <div class="timeline-body">
                                                <p><strong>{{ $history->description }}</strong></p>
                                                
                                                @if($history->action === 'update' && $history->field_name !== 'unit_created')
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <small class="text-muted">Nilai Lama:</small><br>
                                                            <span class="badge badge-light">{{ $history->formatted_old_value }}</span>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <small class="text-muted">Nilai Baru:</small><br>
                                                            <span class="badge badge-primary">{{ $history->formatted_new_value }}</span>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endforeach
                            
                            <div>
                                <i class="fas fa-clock bg-gray"></i>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            Belum ada riwayat perubahan untuk unit ini.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop