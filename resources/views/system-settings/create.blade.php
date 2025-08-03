@extends('adminlte::page')

@section('title', 'Tambah Pengaturan Sistem')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Tambah Pengaturan Sistem</h1>
        <a href="{{ route('system-settings.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
@stop

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('system-settings.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="key" class="form-label">Key <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('key') is-invalid @enderror" 
                                   id="key" 
                                   name="key" 
                                   value="{{ old('key') }}" 
                                   required>
                            <small class="text-muted">Gunakan format snake_case (contoh: company_name)</small>
                            @error('key')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('description') is-invalid @enderror" 
                                   id="description" 
                                   name="description" 
                                   value="{{ old('description') }}" 
                                   required>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="type" class="form-label">Tipe <span class="text-danger">*</span></label>
                            <select class="form-select @error('type') is-invalid @enderror" 
                                    id="type" 
                                    name="type" 
                                    required 
                                    onchange="toggleValueInput()">
                                <option value="">Pilih Tipe</option>
                                <option value="text" {{ old('type') == 'text' ? 'selected' : '' }}>Text</option>
                                <option value="number" {{ old('type') == 'number' ? 'selected' : '' }}>Number</option>
                                <option value="boolean" {{ old('type') == 'boolean' ? 'selected' : '' }}>Boolean</option>
                                <option value="file" {{ old('type') == 'file' ? 'selected' : '' }}>File</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="group" class="form-label">Grup <span class="text-danger">*</span></label>
                            <select class="form-select @error('group') is-invalid @enderror" 
                                    id="group" 
                                    name="group" 
                                    required>
                                <option value="">Pilih Grup</option>
                                <option value="company" {{ old('group') == 'company' ? 'selected' : '' }}>Company</option>
                                <option value="financial" {{ old('group') == 'financial' ? 'selected' : '' }}>Financial</option>
                                <option value="journal" {{ old('group') == 'journal' ? 'selected' : '' }}>Journal</option>
                                <option value="report" {{ old('group') == 'report' ? 'selected' : '' }}>Report</option>
                                <option value="system" {{ old('group') == 'system' ? 'selected' : '' }}>System</option>
                                <option value="general" {{ old('group') == 'general' ? 'selected' : '' }}>General</option>
                            </select>
                            @error('group')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Value Input (for non-file types) -->
                        <div class="mb-3" id="value-input">
                            <label for="value" class="form-label">Nilai</label>
                            <input type="text" 
                                   class="form-control @error('value') is-invalid @enderror" 
                                   id="value" 
                                   name="value" 
                                   value="{{ old('value') }}">
                            @error('value')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Boolean Select (hidden by default) -->
                        <div class="mb-3" id="boolean-input" style="display: none;">
                            <label for="boolean_value" class="form-label">Nilai Boolean</label>
                            <select class="form-select" id="boolean_value" name="boolean_value">
                                <option value="1" {{ old('value') == '1' ? 'selected' : '' }}>Ya</option>
                                <option value="0" {{ old('value') == '0' ? 'selected' : '' }}>Tidak</option>
                            </select>
                        </div>

                        <!-- File Input (hidden by default) -->
                        <div class="mb-3" id="file-input" style="display: none;">
                            <label for="file" class="form-label">File</label>
                            <input type="file" 
                                   class="form-control @error('file') is-invalid @enderror" 
                                   id="file" 
                                   name="file"
                                   accept="image/*">
                            <small class="text-muted">Format yang didukung: JPG, JPEG, PNG, GIF, SVG (Max: 2MB)</small>
                            @error('file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('system-settings.index') }}" class="btn btn-secondary me-md-2">Batal</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan
                            </button>
                        </div>
                    </form>
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
        function toggleValueInput() {
            const type = document.getElementById('type').value;
            const valueInput = document.getElementById('value-input');
            const booleanInput = document.getElementById('boolean-input');
            const fileInput = document.getElementById('file-input');
            
            // Hide all inputs first
            valueInput.style.display = 'none';
            booleanInput.style.display = 'none';
            fileInput.style.display = 'none';
            
            // Show appropriate input based on type
            if (type === 'boolean') {
                booleanInput.style.display = 'block';
            } else if (type === 'file') {
                fileInput.style.display = 'block';
            } else if (type === 'text' || type === 'number') {
                valueInput.style.display = 'block';
                document.getElementById('value').type = type === 'number' ? 'number' : 'text';
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            toggleValueInput();
        });
    </script>
@stop