@extends('adminlte::page')

@section('title', 'Pengaturan Sistem')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Pengaturan Sistem</h1>
        <div>
            <button type="button" class="btn btn-warning me-2" onclick="clearSystemCache()">
                <i class="fas fa-broom"></i> Clear Cache
            </button>
            <a href="{{ route('system-settings.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Pengaturan
            </a>
        </div>
    </div>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Simplified Update Form -->
                    <form action="{{ route('system-settings.update-batch') }}" method="POST" enctype="multipart/form-data" id="settingsForm">
                        @csrf
                        @method('PUT')

                        @foreach($settings as $group => $groupSettings)
                            <div class="mb-4">
                                <h5 class="text-primary border-bottom pb-2">
                                    @switch($group)
                                        @case('company')
                                            <i class="fas fa-building"></i> Informasi Perusahaan
                                            @break
                                        @case('financial')
                                            <i class="fas fa-money-bill-wave"></i> Pengaturan Keuangan
                                            @break
                                        @case('journal')
                                            <i class="fas fa-book"></i> Pengaturan Jurnal
                                            @break
                                        @case('report')
                                            <i class="fas fa-chart-bar"></i> Pengaturan Laporan
                                            @break
                                        @case('system')
                                            <i class="fas fa-cog"></i> Pengaturan Sistem
                                            @break
                                        @default
                                            <i class="fas fa-cogs"></i> {{ ucfirst($group) }}
                                    @endswitch
                                </h5>

                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th width="40%">Nama Pengaturan</th>
                                                <th width="60%">Nilai</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($groupSettings as $setting)
                                                <tr>
                                                    <td>
                                                        <strong>{{ $setting->description }}</strong>
                                                        <br>
                                                        <small class="text-muted">{{ $setting->key }}</small>
                                                        @if($setting->is_protected)
                                                            <br>
                                                            <small class="text-warning">
                                                                <i class="fas fa-shield-alt"></i> Default
                                                            </small>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($setting->type === 'file')
                                                            <div class="file-input-container">
                                                                @if($setting->value)
                                                                    <div class="current-file mb-2">
                                                                        <img src="{{ asset('storage/' . $setting->value) }}" 
                                                                             alt="{{ $setting->description }}" 
                                                                             class="img-thumbnail file-preview" 
                                                                             style="max-height: 80px; cursor: pointer;"
                                                                             onclick="showImageModal('{{ asset('storage/' . $setting->value) }}', '{{ $setting->description }}')">
                                                                        <br>
                                                                        <small class="text-success">
                                                                            <i class="fas fa-check"></i> File tersimpan
                                                                        </small>
                                                                    </div>
                                                                @endif
                                                                <input type="file" 
                                                                       class="form-control file-input" 
                                                                       name="files[{{ $setting->key }}]"
                                                                       accept="image/*"
                                                                       onchange="previewFile(this, '{{ $setting->key }}')">
                                                                <div class="preview-container-{{ $setting->key }} mt-2" style="display: none;">
                                                                    <img class="img-thumbnail" style="max-height: 80px;">
                                                                    <br>
                                                                    <small class="text-info">Preview file baru</small>
                                                                </div>
                                                            </div>
                                                        @elseif($setting->type === 'boolean')
                                                            <select class="form-select" name="settings[{{ $setting->key }}]">
                                                                <option value="1" {{ $setting->value == '1' ? 'selected' : '' }}>Ya</option>
                                                                <option value="0" {{ $setting->value == '0' ? 'selected' : '' }}>Tidak</option>
                                                            </select>
                                                        @elseif($setting->type === 'number')
                                                            <input type="number" 
                                                                   class="form-control" 
                                                                   name="settings[{{ $setting->key }}]" 
                                                                   value="{{ $setting->value }}"
                                                                   step="0.01">
                                                        @else
                                                            <input type="text" 
                                                                   class="form-control" 
                                                                   name="settings[{{ $setting->key }}]" 
                                                                   value="{{ $setting->value }}">
                                                        @endif
                                                    </td>

                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endforeach

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-save"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>


            </div>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Preview Gambar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" alt="" class="img-fluid">
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
@stop

@section('js')
    <script>
        // Preview file function
        function previewFile(input, settingKey) {
            const file = input.files[0];
            const previewContainer = document.querySelector('.preview-container-' + settingKey);
            const previewImg = previewContainer.querySelector('img');
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    previewContainer.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                previewContainer.style.display = 'none';
            }
        }

        // Show image modal
        function showImageModal(imageSrc, imageTitle) {
            document.getElementById('modalImage').src = imageSrc;
            document.getElementById('imageModalLabel').textContent = imageTitle;
            
            // Use jQuery modal (AdminLTE uses jQuery)
            $('#imageModal').modal('show');
        }

        // Form submission handling for main settings form
        document.getElementById('settingsForm').addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
        });



        // Clear system cache function
        function clearSystemCache() {
            if (confirm('Apakah Anda yakin ingin membersihkan cache sistem?')) {
                const btn = event.target.closest('button');
                const originalText = btn.innerHTML;
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Membersihkan...';
                
                fetch('{{ route("system-settings.clear-cache") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message
                        const alertDiv = document.createElement('div');
                        alertDiv.className = 'alert alert-success alert-dismissible fade show';
                        alertDiv.innerHTML = `
                            ${data.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        `;
                        document.querySelector('.card-body').insertBefore(alertDiv, document.querySelector('.card-body').firstChild);
                        
                        // Auto hide after 5 seconds
                        setTimeout(() => alertDiv.remove(), 5000);
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat membersihkan cache');
                })
                .finally(() => {
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                });
            }
        }

        // Auto-hide alerts after 5 seconds
        $(document).ready(function() {
            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 5000);
        });
    </script>
@stop