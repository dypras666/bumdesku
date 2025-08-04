@extends('layouts.app')

@section('title', 'Tambah Panduan')

@section('content_header')
    <h1>Tambah Panduan</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="card-title">Form Tambah Panduan</h3>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('guides.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>
                </div>
                <form action="{{ route('guides.store') }}" method="POST" id="guideForm">
                    @csrf
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
                                           value="{{ old('title') }}" 
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
                                           value="{{ old('slug') }}" 
                                           placeholder="akan-dibuat-otomatis"
                                           readonly>
                                    <small class="form-text text-muted">URL akan dibuat otomatis dari judul</small>
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
                                              required>{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Content -->
                                <div class="form-group">
                                    <label for="content" class="required">Konten Panduan</label>
                                    <textarea class="form-control @error('content') is-invalid @enderror" 
                                              id="content" 
                                              name="content" 
                                              rows="20" 
                                              placeholder="Tulis konten panduan menggunakan Markdown"
                                              required>{{ old('content') }}</textarea>
                                    <small class="form-text text-muted">
                                        Gunakan format Markdown untuk formatting. 
                                        <a href="#" data-toggle="modal" data-target="#markdownHelp">Lihat panduan Markdown</a>
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
                                            <option value="{{ $key }}" {{ old('category') == $key ? 'selected' : '' }}>
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
                                                <i id="iconPreview" class="{{ old('icon', 'fas fa-book') }}"></i>
                                            </span>
                                        </div>
                                        <input type="text" 
                                               class="form-control @error('icon') is-invalid @enderror" 
                                               id="icon" 
                                               name="icon" 
                                               value="{{ old('icon', 'fas fa-book') }}" 
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
                                           value="{{ old('order', 0) }}" 
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
                                               {{ old('is_published') ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="is_published">
                                            Publikasikan panduan
                                        </label>
                                    </div>
                                    <small class="form-text text-muted">
                                        Panduan yang dipublikasikan akan tampil di homepage
                                    </small>
                                </div>

                                <!-- Preview Card -->
                                <div class="card bg-light">
                                    <div class="card-header">
                                        <h6 class="card-title mb-0">Preview</h6>
                                    </div>
                                    <div class="card-body">
                                        <div id="previewCard" class="text-center">
                                            <i id="previewIcon" class="fas fa-book text-primary mb-2" style="font-size: 2rem;"></i>
                                            <h6 id="previewTitle" class="mb-1">Judul Panduan</h6>
                                            <p id="previewDescription" class="text-muted small mb-0">Deskripsi panduan akan muncul di sini</p>
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
                                    <i class="fas fa-save"></i> Simpan Panduan
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Markdown Help Modal -->
<div class="modal fade" id="markdownHelp" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Panduan Markdown</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Format Dasar</h6>
                        <pre><code># Heading 1
## Heading 2
### Heading 3

**Bold text**
*Italic text*
~~Strikethrough~~

- List item 1
- List item 2
  - Sub item

1. Numbered list
2. Item 2</code></pre>
                    </div>
                    <div class="col-md-6">
                        <h6>Format Lanjutan</h6>
                        <pre><code>[Link text](URL)

![Image alt](image-url)

`Inline code`

```
Code block
```

> Blockquote

| Table | Header |
|-------|--------|
| Cell  | Cell   |</code></pre>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
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
</style>
@endsection

@section('js')
<script>
$(document).ready(function() {
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
        var content = $('#content').val().trim();
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