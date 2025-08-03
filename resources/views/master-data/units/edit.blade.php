@extends('adminlte::page')

@section('title', 'Edit Master Unit')

@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h1>Edit Master Unit</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('master-units.index') }}">Master Unit</a></li>
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
                    <h3 class="card-title">Form Edit Unit</h3>
                </div>
                <form action="{{ route('master-units.update', $unit->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="kode_unit">Kode Unit <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('kode_unit') is-invalid @enderror" 
                                           id="kode_unit" 
                                           name="kode_unit" 
                                           value="{{ old('kode_unit', $unit->kode_unit) }}" 
                                           placeholder="Masukkan kode unit"
                                           required>
                                    @error('kode_unit')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="kategori_unit">Kategori Unit <span class="text-danger">*</span></label>
                                    <select class="form-control @error('kategori_unit') is-invalid @enderror" 
                                            id="kategori_unit" 
                                            name="kategori_unit" 
                                            required>
                                        <option value="">Pilih Kategori</option>
                                        <option value="Kantor" {{ old('kategori_unit', $unit->kategori_unit) == 'Kantor' ? 'selected' : '' }}>Kantor</option>
                                        <option value="Produksi" {{ old('kategori_unit', $unit->kategori_unit) == 'Produksi' ? 'selected' : '' }}>Produksi</option>
                                        <option value="Gudang" {{ old('kategori_unit', $unit->kategori_unit) == 'Gudang' ? 'selected' : '' }}>Gudang</option>
                                        <option value="Kendaraan" {{ old('kategori_unit', $unit->kategori_unit) == 'Kendaraan' ? 'selected' : '' }}>Kendaraan</option>
                                        <option value="Peralatan" {{ old('kategori_unit', $unit->kategori_unit) == 'Peralatan' ? 'selected' : '' }}>Peralatan</option>
                                    </select>
                                    @error('kategori_unit')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="nama_unit">Nama Unit <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('nama_unit') is-invalid @enderror" 
                                   id="nama_unit" 
                                   name="nama_unit" 
                                   value="{{ old('nama_unit', $unit->nama_unit) }}" 
                                   placeholder="Masukkan nama unit"
                                   required>
                            @error('nama_unit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nilai_aset_display">Nilai Aset</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rp</span>
                                        </div>
                                        <input type="text" 
                                               class="form-control @error('nilai_aset') is-invalid @enderror" 
                                               id="nilai_aset_display" 
                                               value="{{ old('nilai_aset', number_format($unit->nilai_aset, 0, ',', '.')) }}" 
                                               placeholder="0">
                                        <input type="hidden" 
                                               id="nilai_aset" 
                                               name="nilai_aset" 
                                               value="{{ old('nilai_aset', $unit->nilai_aset) }}">
                                        @error('nilai_aset')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <small class="form-text text-muted">Nilai aset unit dalam rupiah</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="penanggung_jawab_id">Penanggung Jawab</label>
                                    <select class="form-control @error('penanggung_jawab_id') is-invalid @enderror" 
                                            id="penanggung_jawab_id" 
                                            name="penanggung_jawab_id">
                                        <option value="">-- Pilih Penanggung Jawab --</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ old('penanggung_jawab_id', $unit->penanggung_jawab_id) == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }} ({{ $user->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('penanggung_jawab_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Pilih user yang akan bertanggung jawab atas unit ini</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="alamat">Alamat</label>
                            <textarea class="form-control @error('alamat') is-invalid @enderror" 
                                      id="alamat" 
                                      name="alamat" 
                                      rows="3" 
                                      placeholder="Masukkan alamat unit (opsional)">{{ old('alamat', $unit->alamat) }}</textarea>
                            @error('alamat')
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
                                       {{ old('is_active', $unit->is_active) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_active">Status Aktif</label>
                            </div>
                            <small class="form-text text-muted">Centang untuk mengaktifkan unit ini</small>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Unit
                        </button>
                        <a href="{{ route('master-units.show', $unit->id) }}" class="btn btn-info">
                            <i class="fas fa-eye"></i> Lihat Detail
                        </a>
                        <a href="{{ route('master-units.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informasi Unit</h3>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h6><i class="icon fas fa-info"></i> Data Saat Ini:</h6>
                        <ul class="mb-0">
                            <li><strong>Dibuat:</strong> {{ $unit->created_at->format('d/m/Y H:i') }}</li>
                            <li><strong>Terakhir Update:</strong> {{ $unit->updated_at->format('d/m/Y H:i') }}</li>
                            <li><strong>Status:</strong> 
                                @if($unit->is_active)
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-danger">Tidak Aktif</span>
                                @endif
                            </li>
                        </ul>
                    </div>
                    
                    <div class="callout callout-warning">
                        <h6>Petunjuk Edit:</h6>
                        <ul class="mb-0">
                            <li>Pastikan kode unit tetap unik</li>
                            <li>Pilih kategori yang sesuai dengan fungsi unit</li>
                            <li>Update nilai aset jika ada perubahan</li>
                            <li>Pastikan penanggung jawab masih aktif</li>
                            <li>Alamat dapat diperbarui jika unit berpindah lokasi</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informasi Kategori</h3>
                </div>
                <div class="card-body">
                    <div class="callout callout-info">
                        <h6>Kategori Unit:</h6>
                        <ul class="mb-0">
                            <li><strong>Kantor:</strong> Unit administrasi dan manajemen</li>
                            <li><strong>Produksi:</strong> Unit untuk kegiatan produksi</li>
                            <li><strong>Gudang:</strong> Unit penyimpanan barang</li>
                            <li><strong>Kendaraan:</strong> Unit transportasi</li>
                            <li><strong>Peralatan:</strong> Unit peralatan dan mesin</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Nilai Aset Saat Ini</h3>
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
                    
                    <small class="text-muted">
                        Nilai aset akan diperbarui sesuai dengan input baru Anda.
                    </small>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
<script>
    // Format rupiah untuk nilai aset
    function formatRupiah(angka, prefix = '') {
        var number_string = angka.replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix == undefined ? rupiah : (rupiah ? prefix + rupiah : '');
    }

    // Event listener untuk format rupiah pada nilai aset
    $('#nilai_aset_display').on('keyup', function(e) {
        var value = $(this).val();
        
        // Remove all non-numeric characters except comma
        var numericValue = value.replace(/[^0-9,]/g, '');
        
        // Format as rupiah
        var formattedValue = formatRupiah(numericValue);
        $(this).val(formattedValue);
        
        // Update hidden input with numeric value only
        var numericOnly = numericValue.replace(/[^0-9]/g, '');
        $('#nilai_aset').val(numericOnly);
    });

    // Prevent non-numeric input
    $('#nilai_aset_display').on('keypress', function(e) {
        var charCode = (e.which) ? e.which : e.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode !== 44) {
            return false;
        }
    });

    // Auto-generate kode unit based on kategori if kode is empty
    $('#kategori_unit').change(function() {
        var kategori = $(this).val();
        var kodeUnit = $('#kode_unit');
        
        // Only auto-generate if kode_unit is empty or matches a previous auto-generated pattern
        var currentKode = kodeUnit.val();
        var isAutoGenerated = /^(KT|PR|GD|KD|PL)\d{4}$/.test(currentKode);
        
        if (kategori && (!currentKode || isAutoGenerated)) {
            var prefix = '';
            switch(kategori) {
                case 'Kantor':
                    prefix = 'KT';
                    break;
                case 'Produksi':
                    prefix = 'PR';
                    break;
                case 'Gudang':
                    prefix = 'GD';
                    break;
                case 'Kendaraan':
                    prefix = 'KD';
                    break;
                case 'Peralatan':
                    prefix = 'PL';
                    break;
            }
            
            if (prefix) {
                // Generate random 4-digit number
                var randomNum = Math.floor(Math.random() * 9000) + 1000;
                kodeUnit.val(prefix + randomNum);
            }
        }
    });
</script>
@stop