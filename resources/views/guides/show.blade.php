@extends('adminlte::page')

@section('title', 'Detail Panduan - ' . $guide->title)

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Detail Panduan</h1>
        <div>
            <a href="{{ route('guides.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <a href="{{ route('guides.edit', $guide->slug) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('guides.public.show', $guide->slug) }}" class="btn btn-info" target="_blank">
                <i class="fas fa-external-link-alt"></i> Lihat Publik
            </a>
        </div>
    </div>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <i class="{{ $guide->icon }} me-3 text-primary" style="font-size: 2rem;"></i>
                        <div>
                            <h3 class="card-title mb-0">{{ $guide->title }}</h3>
                            <small class="text-muted">{{ $guide->description }}</small>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- YouTube Video Section -->
                    @if($guide->youtube_url)
                    <div class="mb-4">
                        <h5><i class="fab fa-youtube text-danger me-2"></i>Video Tutorial</h5>
                        <div class="ratio ratio-16x9">
                            @php
                                $videoId = '';
                                if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $guide->youtube_url, $matches)) {
                                    $videoId = $matches[1];
                                }
                            @endphp
                            @if($videoId)
                                <iframe src="https://www.youtube.com/embed/{{ $videoId }}" 
                                        title="YouTube video player" 
                                        frameborder="0" 
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                        allowfullscreen>
                                </iframe>
                            @else
                                <div class="d-flex align-items-center justify-content-center bg-light">
                                    <div class="text-center">
                                        <i class="fab fa-youtube text-danger" style="font-size: 3rem;"></i>
                                        <p class="mt-2">URL YouTube tidak valid</p>
                                        <small class="text-muted">{{ $guide->youtube_url }}</small>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Content -->
                    <div class="guide-content">
                        <h5><i class="fas fa-file-alt me-2"></i>Konten Panduan</h5>
                        <div class="border rounded p-3 bg-light">
                            {!! $guide->content !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Guide Information -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle"></i> Informasi Panduan
                    </h3>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <td><strong>Status:</strong></td>
                            <td>
                                @if($guide->is_published)
                                    <span class="badge badge-success">
                                        <i class="fas fa-check"></i> Dipublikasikan
                                    </span>
                                @else
                                    <span class="badge badge-warning">
                                        <i class="fas fa-clock"></i> Draft
                                    </span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Kategori:</strong></td>
                            <td>
                                <span class="badge badge-info">
                                    {{ \App\Models\Guide::getCategories()[$guide->category] ?? $guide->category }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Urutan:</strong></td>
                            <td><span class="badge badge-secondary">{{ $guide->order }}</span></td>
                        </tr>
                        <tr>
                            <td><strong>Slug:</strong></td>
                            <td><code>{{ $guide->slug }}</code></td>
                        </tr>
                        <tr>
                            <td><strong>Icon:</strong></td>
                            <td>
                                @if($guide->icon)
                                    <i class="{{ $guide->icon }}"></i> <code>{{ $guide->icon }}</code>
                                @else
                                    <span class="text-muted">Tidak ada</span>
                                @endif
                            </td>
                        </tr>
                        @if($guide->youtube_url)
                        <tr>
                            <td><strong>YouTube URL:</strong></td>
                            <td>
                                <a href="{{ $guide->youtube_url }}" target="_blank" class="btn btn-sm btn-outline-danger">
                                    <i class="fab fa-youtube"></i> Lihat Video
                                </a>
                            </td>
                        </tr>
                        @endif
                        <tr>
                            <td><strong>Dibuat:</strong></td>
                            <td>
                                {{ $guide->created_at->format('d M Y, H:i') }}
                                <br>
                                <small class="text-muted">{{ $guide->created_at->diffForHumans() }}</small>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Diperbarui:</strong></td>
                            <td>
                                {{ $guide->updated_at->format('d M Y, H:i') }}
                                <br>
                                <small class="text-muted">{{ $guide->updated_at->diffForHumans() }}</small>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Penulis:</strong></td>
                            <td>
                                @if($guide->creator)
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-2" 
                                             style="width: 30px; height: 30px;">
                                            <span class="text-white font-weight-bold">
                                                {{ strtoupper(substr($guide->creator->name, 0, 1)) }}
                                            </span>
                                        </div>
                                        <div>
                                            <strong>{{ $guide->creator->name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $guide->creator->email }}</small>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted">Tidak diketahui</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-tools"></i> Aksi Cepat
                    </h3>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('guides.edit', $guide->slug) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit Panduan
                        </a>
                        
                        @if($guide->is_published)
                            <form action="{{ route('guides.update', $guide->slug) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="title" value="{{ $guide->title }}">
                                <input type="hidden" name="content" value="{{ $guide->content }}">
                                <input type="hidden" name="category" value="{{ $guide->category }}">
                                <input type="hidden" name="order" value="{{ $guide->order }}">
                                <input type="hidden" name="description" value="{{ $guide->description }}">
                                <input type="hidden" name="icon" value="{{ $guide->icon }}">
                                <input type="hidden" name="youtube_url" value="{{ $guide->youtube_url }}">
                                <input type="hidden" name="is_published" value="0">
                                <button type="submit" class="btn btn-secondary w-100">
                                    <i class="fas fa-eye-slash"></i> Jadikan Draft
                                </button>
                            </form>
                        @else
                            <form action="{{ route('guides.update', $guide->slug) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="title" value="{{ $guide->title }}">
                                <input type="hidden" name="content" value="{{ $guide->content }}">
                                <input type="hidden" name="category" value="{{ $guide->category }}">
                                <input type="hidden" name="order" value="{{ $guide->order }}">
                                <input type="hidden" name="description" value="{{ $guide->description }}">
                                <input type="hidden" name="icon" value="{{ $guide->icon }}">
                                <input type="hidden" name="youtube_url" value="{{ $guide->youtube_url }}">
                                <input type="hidden" name="is_published" value="1">
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="fas fa-eye"></i> Publikasikan
                                </button>
                            </form>
                        @endif

                        <a href="{{ route('guides.public.show', $guide->slug) }}" class="btn btn-info" target="_blank">
                            <i class="fas fa-external-link-alt"></i> Lihat Publik
                        </a>

                        <button type="button" class="btn btn-danger" onclick="deleteGuide({{ $guide->id }}, {{ json_encode($guide->title) }})">
                            <i class="fas fa-trash"></i> Hapus Panduan
                        </button>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-bar"></i> Statistik
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-right">
                                <h4 class="text-primary">{{ strlen(strip_tags($guide->content)) }}</h4>
                                <small class="text-muted">Karakter</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h4 class="text-info">{{ str_word_count(strip_tags($guide->content)) }}</h4>
                            <small class="text-muted">Kata</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus panduan "<span id="guideTitle"></span>"?</p>
                <p class="text-danger"><small>Tindakan ini tidak dapat dibatalkan.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
<style>
.guide-content {
    line-height: 1.6;
}

.guide-content h1,
.guide-content h2,
.guide-content h3,
.guide-content h4,
.guide-content h5,
.guide-content h6 {
    margin-top: 1.5rem;
    margin-bottom: 1rem;
    font-weight: 600;
}

.guide-content p {
    margin-bottom: 1rem;
}

.guide-content ul,
.guide-content ol {
    margin-bottom: 1rem;
    padding-left: 2rem;
}

.guide-content blockquote {
    border-left: 4px solid #007bff;
    background-color: #f8f9fa;
    padding: 1rem;
    margin: 1rem 0;
    font-style: italic;
}

.guide-content code {
    background-color: #f8f9fa;
    padding: 0.2rem 0.4rem;
    border-radius: 0.25rem;
    font-size: 0.875em;
}

.guide-content pre {
    background-color: #f8f9fa;
    padding: 1rem;
    border-radius: 0.25rem;
    overflow-x: auto;
}

.guide-content img {
    max-width: 100%;
    height: auto;
    border-radius: 0.25rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.guide-content table {
    width: 100%;
    border-collapse: collapse;
    margin: 1rem 0;
}

.guide-content table th,
.guide-content table td {
    border: 1px solid #dee2e6;
    padding: 0.75rem;
    text-align: left;
}

.guide-content table th {
    background-color: #f8f9fa;
    font-weight: 600;
}
</style>
@endsection

@section('js')
<script>
function deleteGuide(id, title) {
    document.getElementById('guideTitle').textContent = title;
    document.getElementById('deleteForm').action = '/guides/' + id;
    $('#deleteModal').modal('show');
}

// Auto-hide alerts after 5 seconds
$(document).ready(function() {
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
});
</script>
@endsection