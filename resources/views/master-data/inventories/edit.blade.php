@extends('adminlte::page')

@section('title', 'Edit Master Persediaan')

@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h1>Edit Master Persediaan</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('master-inventories.index') }}">Master Persediaan</a></li>
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
                    <h3 class="card-title">Form Edit Barang</h3>
                </div>
                <form action="{{ route('master-inventories.update', $inventory->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="kode_barang">Kode Barang <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('kode_barang') is-invalid @enderror" 
                                           id="kode_barang" 
                                           name="kode_barang" 
                                           value="{{ old('kode_barang', $inventory->kode_barang) }}" 
                                           placeholder="Masukkan kode barang"
                                           required>
                                    @error('kode_barang')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="kategori_barang">Kategori Barang <span class="text-danger">*</span></label>
                                    <select class="form-control @error('kategori_barang') is-invalid @enderror" 
                                            id="kategori_barang" 
                                            name="kategori_barang" 
                                            required>
                                        <option value="">Pilih Kategori</option>
                                        <option value="Bahan Baku" {{ old('kategori_barang', $inventory->kategori_barang) == 'Bahan Baku' ? 'selected' : '' }}>Bahan Baku</option>
                                        <option value="Produk Jadi" {{ old('kategori_barang', $inventory->kategori_barang) == 'Produk Jadi' ? 'selected' : '' }}>Produk Jadi</option>
                                        <option value="Barang Dagangan" {{ old('kategori_barang', $inventory->kategori_barang) == 'Barang Dagangan' ? 'selected' : '' }}>Barang Dagangan</option>
                                        <option value="Perlengkapan" {{ old('kategori_barang', $inventory->kategori_barang) == 'Perlengkapan' ? 'selected' : '' }}>Perlengkapan</option>
                                        <option value="Alat Tulis" {{ old('kategori_barang', $inventory->kategori_barang) == 'Alat Tulis' ? 'selected' : '' }}>Alat Tulis</option>
                                    </select>
                                    @error('kategori_barang')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="nama_barang">Nama Barang <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('nama_barang') is-invalid @enderror" 
                                   id="nama_barang" 
                                   name="nama_barang" 
                                   value="{{ old('nama_barang', $inventory->nama_barang) }}" 
                                   placeholder="Masukkan nama barang"
                                   required>
                            @error('nama_barang')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="satuan">Satuan <span class="text-danger">*</span></label>
                                    <select class="form-control @error('satuan') is-invalid @enderror" 
                                            id="satuan" 
                                            name="satuan" 
                                            required>
                                        <option value="">Pilih Satuan</option>
                                        <option value="Pcs" {{ old('satuan', $inventory->satuan) == 'Pcs' ? 'selected' : '' }}>Pcs (Pieces)</option>
                                        <option value="Kg" {{ old('satuan', $inventory->satuan) == 'Kg' ? 'selected' : '' }}>Kg (Kilogram)</option>
                                        <option value="Gram" {{ old('satuan', $inventory->satuan) == 'Gram' ? 'selected' : '' }}>Gram</option>
                                        <option value="Liter" {{ old('satuan', $inventory->satuan) == 'Liter' ? 'selected' : '' }}>Liter</option>
                                        <option value="Meter" {{ old('satuan', $inventory->satuan) == 'Meter' ? 'selected' : '' }}>Meter</option>
                                        <option value="Box" {{ old('satuan', $inventory->satuan) == 'Box' ? 'selected' : '' }}>Box</option>
                                        <option value="Pack" {{ old('satuan', $inventory->satuan) == 'Pack' ? 'selected' : '' }}>Pack</option>
                                        <option value="Unit" {{ old('satuan', $inventory->satuan) == 'Unit' ? 'selected' : '' }}>Unit</option>
                                    </select>
                                    @error('satuan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="harga_beli">Harga Beli</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rp</span>
                                        </div>
                                        <input type="number" 
                                               class="form-control @error('harga_beli') is-invalid @enderror" 
                                               id="harga_beli" 
                                               name="harga_beli" 
                                               value="{{ old('harga_beli', $inventory->harga_beli) }}" 
                                               placeholder="0"
                                               min="0"
                                               step="0.01">
                                        @error('harga_beli')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="harga_jual">Harga Jual</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rp</span>
                                        </div>
                                        <input type="number" 
                                               class="form-control @error('harga_jual') is-invalid @enderror" 
                                               id="harga_jual" 
                                               name="harga_jual" 
                                               value="{{ old('harga_jual', $inventory->harga_jual) }}" 
                                               placeholder="0"
                                               min="0"
                                               step="0.01">
                                        @error('harga_jual')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="stok_minimum">Stok Minimum</label>
                                    <input type="number" 
                                           class="form-control @error('stok_minimum') is-invalid @enderror" 
                                           id="stok_minimum" 
                                           name="stok_minimum" 
                                           value="{{ old('stok_minimum', $inventory->stok_minimum) }}" 
                                           placeholder="0"
                                           min="0">
                                    @error('stok_minimum')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Batas minimum stok untuk peringatan</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="margin">Margin (%)</label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="margin" 
                                           readonly
                                           placeholder="Akan dihitung otomatis">
                                    <small class="form-text text-muted">Margin keuntungan berdasarkan harga beli dan jual</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="deskripsi">Deskripsi</label>
                            <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                      id="deskripsi" 
                                      name="deskripsi" 
                                      rows="3" 
                                      placeholder="Masukkan deskripsi barang (opsional)">{{ old('deskripsi', $inventory->deskripsi) }}</textarea>
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
                                       {{ old('is_active', $inventory->is_active) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_active">Status Aktif</label>
                            </div>
                            <small class="form-text text-muted">Centang untuk mengaktifkan barang ini</small>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Barang
                        </button>
                        <a href="{{ route('master-inventories.show', $inventory->id) }}" class="btn btn-info">
                            <i class="fas fa-eye"></i> Lihat Detail
                        </a>
                        <a href="{{ route('master-inventories.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informasi Barang</h3>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h6><i class="icon fas fa-info"></i> Data Saat Ini:</h6>
                        <ul class="mb-0">
                            <li><strong>Dibuat:</strong> {{ $inventory->created_at->format('d/m/Y H:i') }}</li>
                            <li><strong>Terakhir Update:</strong> {{ $inventory->updated_at->format('d/m/Y H:i') }}</li>
                            <li><strong>Status:</strong> 
                                @if($inventory->is_active)
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
                            <li>Pastikan kode barang tetap unik</li>
                            <li>Perhatikan perubahan harga yang dapat mempengaruhi margin</li>
                            <li>Update stok minimum sesuai kebutuhan operasional</li>
                            <li>Deskripsi dapat diperbarui untuk informasi yang lebih akurat</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Analisis Harga Saat Ini</h3>
                </div>
                <div class="card-body">
                    @php
                        $currentMargin = $inventory->harga_beli > 0 ? (($inventory->harga_jual - $inventory->harga_beli) / $inventory->harga_beli) * 100 : 0;
                        $currentKeuntungan = $inventory->harga_jual - $inventory->harga_beli;
                    @endphp
                    
                    <div class="info-box">
                        <span class="info-box-icon bg-info"><i class="fas fa-calculator"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Keuntungan Saat Ini</span>
                            <span class="info-box-number">
                                Rp {{ number_format($currentKeuntungan, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="progress-group">
                        <span class="float-right"><b>{{ number_format($currentMargin, 1) }}%</b></span>
                        <span>Margin Saat Ini</span>
                        <div class="progress progress-sm">
                            <div class="progress-bar {{ $currentMargin > 20 ? 'bg-success' : ($currentMargin > 10 ? 'bg-warning' : 'bg-danger') }}" 
                                 style="width: {{ min($currentMargin, 100) }}%"></div>
                        </div>
                    </div>
                    
                    <small class="text-muted">
                        Margin akan diperbarui otomatis saat Anda mengubah harga beli atau jual.
                    </small>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
<script>
    // Calculate margin when harga_beli or harga_jual changes
    function calculateMargin() {
        var hargaBeli = parseFloat($('#harga_beli').val()) || 0;
        var hargaJual = parseFloat($('#harga_jual').val()) || 0;
        
        if (hargaBeli > 0) {
            var margin = ((hargaJual - hargaBeli) / hargaBeli) * 100;
            $('#margin').val(margin.toFixed(2) + '%');
            
            // Change color based on margin
            if (margin > 0) {
                $('#margin').removeClass('text-danger').addClass('text-success');
            } else if (margin < 0) {
                $('#margin').removeClass('text-success').addClass('text-danger');
            } else {
                $('#margin').removeClass('text-success text-danger');
            }
        } else {
            $('#margin').val('0%');
            $('#margin').removeClass('text-success text-danger');
        }
    }

    // Initialize margin calculation on page load
    $(document).ready(function() {
        calculateMargin();
    });

    $('#harga_beli, #harga_jual').on('input', calculateMargin);

    // Format currency input
    $('#harga_beli, #harga_jual').on('input', function() {
        var value = $(this).val();
        if (value) {
            // Remove non-numeric characters except decimal point
            value = value.replace(/[^0-9.]/g, '');
            $(this).val(value);
        }
    });
</script>
@stop