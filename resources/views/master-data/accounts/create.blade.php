@extends('adminlte::page')

@section('title', 'Tambah Master Akun')

@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h1>Tambah Master Akun</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('master-accounts.index') }}">Master Akun</a></li>
                <li class="breadcrumb-item active">Tambah</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Form Tambah Master Akun</h3>
                </div>
                <form action="{{ route('master-accounts.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="form-group">
                            <label for="kode_akun">Kode Akun <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('kode_akun') is-invalid @enderror" 
                                   id="kode_akun" name="kode_akun" value="{{ old('kode_akun') }}" 
                                   placeholder="Masukkan kode akun" required>
                            @error('kode_akun')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="nama_akun">Nama Akun <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama_akun') is-invalid @enderror" 
                                   id="nama_akun" name="nama_akun" value="{{ old('nama_akun') }}" 
                                   placeholder="Masukkan nama akun" required>
                            @error('nama_akun')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="kategori_akun">Kategori Akun <span class="text-danger">*</span></label>
                            <select class="form-control @error('kategori_akun') is-invalid @enderror" 
                                    id="kategori_akun" name="kategori_akun" required>
                                <option value="">Pilih Kategori</option>
                                <option value="Aset" {{ old('kategori_akun') == 'Aset' ? 'selected' : '' }}>Aset</option>
                                <option value="Kewajiban" {{ old('kategori_akun') == 'Kewajiban' ? 'selected' : '' }}>Kewajiban</option>
                                <option value="Modal" {{ old('kategori_akun') == 'Modal' ? 'selected' : '' }}>Modal</option>
                                <option value="Pendapatan" {{ old('kategori_akun') == 'Pendapatan' ? 'selected' : '' }}>Pendapatan</option>
                                <option value="Beban" {{ old('kategori_akun') == 'Beban' ? 'selected' : '' }}>Beban</option>
                            </select>
                            @error('kategori_akun')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="deskripsi">Deskripsi</label>
                            <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                      id="deskripsi" name="deskripsi" rows="3" 
                                      placeholder="Masukkan deskripsi akun">{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="is_active" 
                                       name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_active">Status Aktif</label>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                        <a href="{{ route('master-accounts.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informasi</h3>
                </div>
                <div class="card-body">
                    <p><strong>Kode Akun:</strong> Kode unik untuk identifikasi akun</p>
                    <p><strong>Nama Akun:</strong> Nama lengkap akun</p>
                    <p><strong>Kategori:</strong> Jenis kategori akun sesuai standar akuntansi</p>
                    <p><strong>Status:</strong> Menentukan apakah akun dapat digunakan</p>
                </div>
            </div>
        </div>
    </div>
@stop