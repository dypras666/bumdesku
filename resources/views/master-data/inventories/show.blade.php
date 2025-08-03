@extends('adminlte::page')

@section('title', 'Detail Master Persediaan')

@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h1>Detail Master Persediaan</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('master-inventories.index') }}">Master Persediaan</a></li>
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
                    <h3 class="card-title">Informasi Barang</h3>
                    <div class="card-tools">
                        <a href="{{ route('master-inventories.edit', $inventory->id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('master-inventories.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="40%"><strong>Kode Barang:</strong></td>
                                    <td>{{ $inventory->kode_barang }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Nama Barang:</strong></td>
                                    <td>{{ $inventory->nama_barang }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Kategori:</strong></td>
                                    <td>
                                        <span class="badge badge-info">{{ $inventory->kategori_barang }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Satuan:</strong></td>
                                    <td>{{ $inventory->satuan }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        @if($inventory->is_active)
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
                                    <td width="40%"><strong>Harga Beli:</strong></td>
                                    <td>Rp {{ number_format($inventory->harga_beli, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Harga Jual:</strong></td>
                                    <td>Rp {{ number_format($inventory->harga_jual, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Margin:</strong></td>
                                    <td>
                                        @php
                                            $margin = $inventory->harga_beli > 0 ? (($inventory->harga_jual - $inventory->harga_beli) / $inventory->harga_beli) * 100 : 0;
                                        @endphp
                                        <span class="badge {{ $margin > 0 ? 'badge-success' : ($margin < 0 ? 'badge-danger' : 'badge-secondary') }}">
                                            {{ number_format($margin, 2) }}%
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Stok Minimum:</strong></td>
                                    <td>{{ number_format($inventory->stok_minimum, 0, ',', '.') }} {{ $inventory->satuan }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Dibuat:</strong></td>
                                    <td>{{ $inventory->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    @if($inventory->deskripsi)
                        <div class="row mt-3">
                            <div class="col-12">
                                <h6><strong>Deskripsi:</strong></h6>
                                <p class="text-muted">{{ $inventory->deskripsi }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informasi Kategori</h3>
                </div>
                <div class="card-body">
                    @switch($inventory->kategori_barang)
                        @case('Bahan Baku')
                            <div class="alert alert-primary">
                                <h6><i class="icon fas fa-industry"></i> Bahan Baku</h6>
                                <p class="mb-0">Material atau bahan mentah yang digunakan dalam proses produksi untuk menghasilkan produk jadi.</p>
                            </div>
                            @break
                        @case('Produk Jadi')
                            <div class="alert alert-success">
                                <h6><i class="icon fas fa-box"></i> Produk Jadi</h6>
                                <p class="mb-0">Barang yang telah selesai diproduksi dan siap untuk dijual kepada konsumen.</p>
                            </div>
                            @break
                        @case('Barang Dagangan')
                            <div class="alert alert-info">
                                <h6><i class="icon fas fa-shopping-cart"></i> Barang Dagangan</h6>
                                <p class="mb-0">Barang yang dibeli untuk dijual kembali tanpa melalui proses produksi.</p>
                            </div>
                            @break
                        @case('Perlengkapan')
                            <div class="alert alert-warning">
                                <h6><i class="icon fas fa-tools"></i> Perlengkapan</h6>
                                <p class="mb-0">Alat dan perlengkapan yang digunakan untuk mendukung operasional perusahaan.</p>
                            </div>
                            @break
                        @case('Alat Tulis')
                            <div class="alert alert-secondary">
                                <h6><i class="icon fas fa-pen"></i> Alat Tulis</h6>
                                <p class="mb-0">Keperluan administrasi dan alat tulis untuk kegiatan kantor sehari-hari.</p>
                            </div>
                            @break
                        @default
                            <div class="alert alert-light">
                                <h6><i class="icon fas fa-question"></i> Kategori Lainnya</h6>
                                <p class="mb-0">Kategori barang yang tidak termasuk dalam kategori standar.</p>
                            </div>
                    @endswitch
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Analisis Harga</h3>
                </div>
                <div class="card-body">
                    @php
                        $margin = $inventory->harga_beli > 0 ? (($inventory->harga_jual - $inventory->harga_beli) / $inventory->harga_beli) * 100 : 0;
                        $keuntungan = $inventory->harga_jual - $inventory->harga_beli;
                    @endphp
                    
                    <div class="info-box">
                        <span class="info-box-icon bg-info"><i class="fas fa-calculator"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Keuntungan per Unit</span>
                            <span class="info-box-number">
                                Rp {{ number_format($keuntungan, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="progress-group">
                        <span class="float-right"><b>{{ number_format($margin, 1) }}%</b></span>
                        <span>Margin Keuntungan</span>
                        <div class="progress progress-sm">
                            <div class="progress-bar {{ $margin > 20 ? 'bg-success' : ($margin > 10 ? 'bg-warning' : 'bg-danger') }}" 
                                 style="width: {{ min($margin, 100) }}%"></div>
                        </div>
                    </div>
                    
                    <small class="text-muted">
                        @if($margin > 20)
                            <i class="fas fa-thumbs-up text-success"></i> Margin sangat baik
                        @elseif($margin > 10)
                            <i class="fas fa-thumbs-up text-warning"></i> Margin cukup baik
                        @elseif($margin > 0)
                            <i class="fas fa-exclamation-triangle text-warning"></i> Margin rendah
                        @else
                            <i class="fas fa-exclamation-triangle text-danger"></i> Tidak menguntungkan
                        @endif
                    </small>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Riwayat Perubahan</h3>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="time-label">
                            <span class="bg-green">{{ $inventory->created_at->format('d M Y') }}</span>
                        </div>
                        <div>
                            <i class="fas fa-plus bg-blue"></i>
                            <div class="timeline-item">
                                <span class="time"><i class="fas fa-clock"></i> {{ $inventory->created_at->format('H:i') }}</span>
                                <h3 class="timeline-header">Barang dibuat</h3>
                                <div class="timeline-body">
                                    Data barang {{ $inventory->nama_barang }} berhasil ditambahkan ke sistem.
                                </div>
                            </div>
                        </div>
                        
                        @if($inventory->updated_at->format('Y-m-d H:i:s') != $inventory->created_at->format('Y-m-d H:i:s'))
                            <div class="time-label">
                                <span class="bg-yellow">{{ $inventory->updated_at->format('d M Y') }}</span>
                            </div>
                            <div>
                                <i class="fas fa-edit bg-yellow"></i>
                                <div class="timeline-item">
                                    <span class="time"><i class="fas fa-clock"></i> {{ $inventory->updated_at->format('H:i') }}</span>
                                    <h3 class="timeline-header">Terakhir diperbarui</h3>
                                    <div class="timeline-body">
                                        Data barang terakhir kali diperbarui.
                                    </div>
                                </div>
                            </div>
                        @endif
                        
                        <div>
                            <i class="fas fa-clock bg-gray"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop