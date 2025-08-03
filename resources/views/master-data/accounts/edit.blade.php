@extends('adminlte::page')

@section('title', 'Edit Master Akun')

@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h1>Edit Master Akun</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('master-accounts.index') }}">Master Akun</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Form Edit Akun</h3>
                </div>
                <form action="{{ route('master-accounts.update', $masterAccount) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="kode_akun">Kode Akun <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('kode_akun') is-invalid @enderror" 
                                           id="kode_akun" 
                                           name="kode_akun" 
                                           value="{{ old('kode_akun', $masterAccount->kode_akun) }}" 
                                           placeholder="Masukkan kode akun"
                                           required>
                                    @error('kode_akun')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="kategori_akun">Kategori Akun <span class="text-danger">*</span></label>
                                    <select class="form-control @error('kategori_akun') is-invalid @enderror" 
                                            id="kategori_akun" 
                                            name="kategori_akun" 
                                            required>
                                        <option value="">Pilih Kategori</option>
                                        <option value="Aset" {{ old('kategori_akun', $masterAccount->kategori_akun) == 'Aset' ? 'selected' : '' }}>Aset</option>
                            <option value="Kewajiban" {{ old('kategori_akun', $masterAccount->kategori_akun) == 'Kewajiban' ? 'selected' : '' }}>Kewajiban</option>
                            <option value="Modal" {{ old('kategori_akun', $masterAccount->kategori_akun) == 'Modal' ? 'selected' : '' }}>Modal</option>
                            <option value="Pendapatan" {{ old('kategori_akun', $masterAccount->kategori_akun) == 'Pendapatan' ? 'selected' : '' }}>Pendapatan</option>
                            <option value="Beban" {{ old('kategori_akun', $masterAccount->kategori_akun) == 'Beban' ? 'selected' : '' }}>Beban</option>
                                    </select>
                                    @error('kategori_akun')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="nama_akun">Nama Akun <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('nama_akun') is-invalid @enderror" 
                                   id="nama_akun" 
                                   name="nama_akun" 
                                   value="{{ old('nama_akun', $masterAccount->nama_akun) }}" 
                                   placeholder="Masukkan nama akun"
                                   required>
                            @error('nama_akun')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="deskripsi">Deskripsi</label>
                            <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                      id="deskripsi" 
                                      name="deskripsi" 
                                      rows="3" 
                                      placeholder="Masukkan deskripsi akun (opsional)">{{ old('deskripsi', $masterAccount->deskripsi) }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" 
                                       class="custom-control-input" 
                                       id="is_active" 
                                       name="is_active" 
                                       value="1" 
                                       {{ old('is_active', $masterAccount->is_active) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_active">Status Aktif</label>
                            </div>
                            <small class="form-text text-muted">Centang untuk mengaktifkan akun ini</small>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Akun
                        </button>
                        <a href="{{ route('master-accounts.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Batal
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
                    <div class="alert alert-info">
                        <h6><i class="icon fas fa-info"></i> Petunjuk:</h6>
                        <ul class="mb-0">
                            <li>Kode akun harus unik dan tidak boleh sama dengan akun lain</li>
                            <li>Nama akun harus jelas dan deskriptif</li>
                            <li>Pilih kategori yang sesuai dengan jenis akun</li>
                            <li>Deskripsi bersifat opsional untuk memberikan penjelasan tambahan</li>
                            <li>Status aktif menentukan apakah akun dapat digunakan dalam transaksi</li>
                        </ul>
                    </div>
                    
                    <div class="info-box">
                        <span class="info-box-icon bg-warning">
                            <i class="fas fa-calendar"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Dibuat</span>
                            <span class="info-box-number">{{ $masterAccount->created_at->format('d/m/Y') }}</span>
                        </div>
                    </div>
                    
                    <div class="info-box">
                        <span class="info-box-icon bg-info">
                            <i class="fas fa-edit"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Terakhir Update</span>
                            <span class="info-box-number">{{ $masterAccount->updated_at->format('d/m/Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
<script>
    // Auto-generate kode akun based on kategori
    $('#kategori_akun').change(function() {
        var kategori = $(this).val();
        var kodeAkun = $('#kode_akun');
        
        if (kategori && !kodeAkun.val()) {
            var prefix = '';
            switch(kategori) {
                case 'Aset':
                    prefix = '1';
                    break;
                case 'Kewajiban':
                    prefix = '2';
                    break;
                case 'Modal':
                    prefix = '3';
                    break;
                case 'Pendapatan':
                    prefix = '4';
                    break;
                case 'Beban':
                    prefix = '5';
                    break;
            }
            
            if (prefix) {
                // Generate random 3-digit number
                var randomNum = Math.floor(Math.random() * 900) + 100;
                kodeAkun.val(prefix + randomNum);
            }
        }
    });
</script>
@stop