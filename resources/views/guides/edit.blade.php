@extends('adminlte::page')

@section('title', 'Edit Panduan')

@section('content_header')
    <h1>Edit Panduan</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="card-title">Form Edit Panduan</h3>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('guides.public.show', $guide->slug) }}" 
                               class="btn btn-info" 
                               target="_blank">
                                <i class="fas fa-eye"></i> Lihat
                            </a>
                            <a href="{{ route('guides.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>
                </div>
                <form action="{{ route('guides.update', $guide->id) }}" method="POST" id="guideForm">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <!-- Left Column -->
                            <div class="col-md-8">
                                <!-- Title -->
                                <div class="form-group">
                                    <label for="title" class="required">Judul Panduan</label>
                                    <input type="text" 
                                           class="form-control @error('title') is-invalid @enderror" 
                                           id="title" 
                                           name="title" 
                                           value="{{ old('title', $guide->title) }}" 
                                           placeholder="Masukkan judul panduan"
                                           required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Slug -->
                                <div class="form-group">
                                    <label for="slug">Slug (URL)</label>
                                    <input type="text" 
                                           class="form-control @error('slug') is-invalid @enderror" 
                                           id="slug" 
                                           name="slug" 
                                           value="{{ old('slug', $guide->slug) }}" 
                                           placeholder="akan-dibuat-otomatis">
                                    <small class="form-text text-muted">
                                        URL saat ini: <strong>{{ url('/panduan/' . $guide->slug) }}</strong>
                                    </small>
                                    @error('slug')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Description -->
                                <div class="form-group">
                                    <label for="description" class="required">Deskripsi Singkat</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" 
                                              name="description" 
                                              rows="3" 
                                              placeholder="Deskripsi singkat tentang panduan ini"
                                              required>{{ old('description', $guide->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- YouTube URL -->
                                <div class="form-group">
                                    <label for="youtube_url">Link YouTube</label>
                                    <input type="url" 
                                           class="form-control @error('youtube_url') is-invalid @enderror" 
                                           id="youtube_url" 
                                           name="youtube_url" 
                                           value="{{ old('youtube_url', $guide->youtube_url) }}" 
                                           placeholder="https://www.youtube.com/watch?v=...">
                                    <small class="form-text text-muted">
                                        Link video YouTube untuk tutorial detail (opsional)
                                    </small>
                                    @error('youtube_url')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Content -->
                                <div class="form-group">
                                    <label for="content" class="required">Konten Panduan</label>
                                    <textarea class="form-control @error('content') is-invalid @enderror" 
                                              id="content" 
                                              name="content" 
                                              placeholder="Tulis konten panduan dengan rich text editor"
                                              required>{{ old('content', $guide->content) }}</textarea>
                                    <small class="form-text text-muted">
                                        Gunakan editor untuk formatting teks, menambahkan gambar, dan styling konten.
                                    </small>
                                    @error('content')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="col-md-4">
                                <!-- Category -->
                                <div class="form-group">
                                    <label for="category" class="required">Kategori</label>
                                    <select class="form-control @error('category') is-invalid @enderror" 
                                            id="category" 
                                            name="category" 
                                            required>
                                        <option value="">Pilih Kategori</option>
                                        @foreach(\App\Models\Guide::getCategories() as $key => $value)
                                            <option value="{{ $key }}" {{ old('category', $guide->category) == $key ? 'selected' : '' }}>
                                                {{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Icon -->
                                <div class="form-group">
                                    <label for="icon">Icon</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i id="iconPreview" class="{{ old('icon', $guide->icon) }}"></i>
                                            </span>
                                        </div>
                                        <input type="text" 
                                               class="form-control @error('icon') is-invalid @enderror" 
                                               id="icon" 
                                               name="icon" 
                                               value="{{ old('icon', $guide->icon) }}" 
                                               placeholder="fas fa-book">
                                    </div>
                                    <small class="form-text text-muted">
                                        Gunakan kelas FontAwesome. 
                                        <a href="https://fontawesome.com/icons" target="_blank">Lihat icon</a>
                                    </small>
                                    @error('icon')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Order -->
                                <div class="form-group">
                                    <label for="order">Urutan</label>
                                    <input type="number" 
                                           class="form-control @error('order') is-invalid @enderror" 
                                           id="order" 
                                           name="order" 
                                           value="{{ old('order', $guide->order) }}" 
                                           min="0" 
                                           step="1">
                                    <small class="form-text text-muted">Urutan tampil (0 = paling atas)</small>
                                    @error('order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Status -->
                                <div class="form-group">
                                    <label for="is_published">Status</label>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" 
                                               class="custom-control-input" 
                                               id="is_published" 
                                               name="is_published" 
                                               value="1" 
                                               {{ old('is_published', $guide->is_published) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="is_published">
                                            Publikasikan panduan
                                        </label>
                                    </div>
                                    <small class="form-text text-muted">
                                        Panduan yang dipublikasikan akan tampil di homepage
                                    </small>
                                </div>

                                <!-- Meta Information -->
                                <div class="card bg-light">
                                    <div class="card-header">
                                        <h6 class="card-title mb-0">Informasi</h6>
                                    </div>
                                    <div class="card-body">
                                        <small class="text-muted">
                                            <strong>Dibuat:</strong><br>
                                            {{ $guide->created_at->format('d M Y H:i') }}<br><br>
                                            
                                            <strong>Diperbarui:</strong><br>
                                            {{ $guide->updated_at->format('d M Y H:i') }}<br><br>
                                            
                                            @if($guide->creator)
                                            <strong>Penulis:</strong><br>
                                            {{ $guide->creator->name }}<br><br>
                                            @endif
                                            
                                            <strong>URL:</strong><br>
                                            <a href="{{ route('guides.public.show', $guide->slug) }}" target="_blank">
                                                /panduan/{{ $guide->slug }}
                                            </a>
                                        </small>
                                    </div>
                                </div>

                                <!-- Preview Card -->
                                <div class="card bg-light mt-3">
                                    <div class="card-header">
                                        <h6 class="card-title mb-0">Preview</h6>
                                    </div>
                                    <div class="card-body">
                                        <div id="previewCard" class="text-center">
                                            <i id="previewIcon" class="{{ $guide->icon }} text-primary mb-2" style="font-size: 2rem;"></i>
                                            <h6 id="previewTitle" class="mb-1">{{ $guide->title }}</h6>
                                            <p id="previewDescription" class="text-muted small mb-0">{{ $guide->description }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col">
                                <button type="button" class="btn btn-secondary" onclick="window.history.back()">
                                    <i class="fas fa-times"></i> Batal
                                </button>
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Simpan Perubahan
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


@endsection

@section('css')
<!-- Summernote CSS -->
<link href="{{ asset('vendor/summernote/summernote-bs4.min.css') }}" rel="stylesheet">
<style>
.required::after {
    content: " *";
    color: red;
}
.card-title {
    margin-bottom: 0;
}
#previewCard {
    min-height: 120px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}
pre {
    background: #f8f9fa;
    padding: 10px;
    border-radius: 4px;
    font-size: 12px;
}
.note-editor {
    border: 1px solid #ced4da;
}
.note-editor.note-frame .note-editing-area .note-editable {
    min-height: 300px;
}
</style>
@endsection

@section('js')
<!-- Summernote JS -->
<script src="{{ asset('vendor/summernote/summernote-bs4.min.js') }}"></script>
<script>
$(document).ready(function() {
    // Initialize Summernote
    $('#content').summernote({
        height: 300,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['fontname', ['fontname']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'picture', 'video']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ],
        callbacks: {
            onImageUpload: function(files) {
                uploadImage(files[0]);
            },
            onPaste: function(e) {
                var clipboardData = e.originalEvent.clipboardData;
                if (clipboardData && clipboardData.items && clipboardData.items.length) {
                    var item = clipboardData.items[0];
                    if (item.kind === 'file' && item.type.indexOf('image/') !== -1) {
                        e.preventDefault();
                        uploadImage(item.getAsFile());
                    }
                }
            }
        }
    });

    function uploadImage(file) {
        var data = new FormData();
        data.append("image", file);
        data.append("_token", $('meta[name="csrf-token"]').attr('content'));

        $.ajax({
            url: "{{ route('upload.image') }}",
            cache: false,
            contentType: false,
            processData: false,
            data: data,
            type: "POST",
            success: function(response) {
                if (response.success) {
                    $('#content').summernote('insertImage', response.url);
                } else {
                    alert('Gagal mengupload gambar: ' + response.message);
                }
            },
            error: function(xhr) {
                alert('Terjadi kesalahan saat mengupload gambar');
            }
        });
    }

    // Auto-generate slug from title
    $('#title').on('input', function() {
        var title = $(this).val();
        var slug = title.toLowerCase()
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .trim('-');
        $('#slug').val(slug);
        updatePreview();
    });

    // Update icon preview
    $('#icon').on('input', function() {
        var iconClass = $(this).val() || 'fas fa-book';
        $('#iconPreview').attr('class', iconClass);
        $('#previewIcon').attr('class', iconClass + ' text-primary mb-2');
        updatePreview();
    });

    // Update description preview
    $('#description').on('input', function() {
        updatePreview();
    });

    function updatePreview() {
        var title = $('#title').val() || 'Judul Panduan';
        var description = $('#description').val() || 'Deskripsi panduan akan muncul di sini';
        var icon = $('#icon').val() || 'fas fa-book';
        
        $('#previewTitle').text(title);
        $('#previewDescription').text(description);
        $('#previewIcon').attr('class', icon + ' text-primary mb-2');
    }

    // Form validation
    $('#guideForm').on('submit', function(e) {
        var title = $('#title').val().trim();
        var description = $('#description').val().trim();
        var content = $('#content').summernote('code').trim();
        var category = $('#category').val();

        if (!title || !description || !content || !category) {
            e.preventDefault();
            alert('Mohon lengkapi semua field yang wajib diisi.');
            return false;
        }
    });
});
</script>
@endsection