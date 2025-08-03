@extends('adminlte::page')

@section('title', 'Detail Master Akun')

@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h1>Detail Master Akun</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('master-accounts.index') }}">Master Akun</a></li>
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
                    <h3 class="card-title">Informasi Akun</h3>
                    <div class="card-tools">
                        <a href="{{ route('master-accounts.edit', $masterAccount) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('master-accounts.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><strong>Kode Akun:</strong></label>
                                <p class="form-control-static">{{ $masterAccount->kode_akun }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><strong>Status:</strong></label>
                                <p class="form-control-static">
                                    @if($masterAccount->is_active)
                                        <span class="badge badge-success">Aktif</span>
                                    @else
                                        <span class="badge badge-danger">Tidak Aktif</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label><strong>Nama Akun:</strong></label>
                        <p class="form-control-static">{{ $masterAccount->nama_akun }}</p>
                    </div>
                    
                    <div class="form-group">
                        <label><strong>Kategori Akun:</strong></label>
                        <p class="form-control-static">
                            <span class="badge badge-info">{{ $masterAccount->kategori_akun }}</span>
                        </p>
                    </div>
                    
                    @if($masterAccount->deskripsi)
                    <div class="form-group">
                        <label><strong>Deskripsi:</strong></label>
                        <p class="form-control-static">{{ $masterAccount->deskripsi }}</p>
                    </div>
                    @endif
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><strong>Dibuat pada:</strong></label>
                                <p class="form-control-static">{{ $masterAccount->created_at->format('d/m/Y H:i:s') }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><strong>Terakhir diupdate:</strong></label>
                                <p class="form-control-static">{{ $masterAccount->updated_at->format('d/m/Y H:i:s') }}</p>
                            </div>
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
                    <div class="info-box">
                        <span class="info-box-icon bg-info">
                            <i class="fas fa-chart-line"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Kategori</span>
                            <span class="info-box-number">{{ $masterAccount->kategori_akun }}</span>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <h6><strong>Deskripsi Kategori:</strong></h6>
                        <p class="text-muted">
                            @switch($masterAccount->kategori_akun)
                                @case('Aset')
                                    Akun yang mencatat sumber daya ekonomi yang dimiliki perusahaan.
                                    @break
                                @case('Kewajiban')
                                    Akun yang mencatat utang atau kewajiban perusahaan kepada pihak lain.
                                    @break
                                @case('Modal')
                                    Akun yang mencatat modal atau ekuitas pemilik perusahaan.
                                    @break
                                @case('Pendapatan')
                                    Akun yang mencatat pemasukan atau pendapatan perusahaan.
                                    @break
                                @case('Beban')
                                    Akun yang mencatat pengeluaran atau beban operasional perusahaan.
                                    @break
                                @default
                                    Kategori akun lainnya.
                            @endswitch
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop