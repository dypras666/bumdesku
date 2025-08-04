@extends('adminlte::page')

@section('title', 'Laporan Keuangan Tahunan')

@section('content_header')
    <h1>Laporan Keuangan Tahunan</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Generate Laporan Keuangan Tahunan</h3>
                    <div class="card-tools">
                        <a href="{{ route('financial-reports.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>

                <form action="{{ route('financial-reports.annual.generate') }}" method="POST" id="annualReportForm">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <!-- Basic Information -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="year">Tahun Laporan <span class="text-danger">*</span></label>
                                    <select name="year" id="year" class="form-control @error('year') is-invalid @enderror" required>
                                        <option value="">Pilih Tahun</option>
                                        @for($i = date('Y'); $i >= 2020; $i--)
                                            <option value="{{ $i }}" {{ old('year') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                        @endfor
                                    </select>
                                    @error('year')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="cover_title">Judul Cover</label>
                                    <input type="text" name="cover_title" id="cover_title" 
                                           class="form-control @error('cover_title') is-invalid @enderror" 
                                           value="{{ old('cover_title') }}" 
                                           placeholder="Laporan Keuangan Tahunan [Tahun]">
                                    @error('cover_title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Kosongkan untuk menggunakan judul default</small>
                                </div>
                            </div>

                            <!-- Company Info Preview -->
                            <div class="col-md-6">
                                <div class="card card-outline card-info">
                                    <div class="card-header">
                                        <h3 class="card-title">Informasi Perusahaan</h3>
                                    </div>
                                    <div class="card-body">
                                        @if(company_info('logo'))
                                            <div class="text-center mb-3">
                                                <img src="{{ company_info('logo') }}" 
                                                     alt="Logo" class="img-thumbnail" style="max-height: 80px;">
                                            </div>
                                        @endif
                                        <p><strong>{{ company_info('name') ?: 'Nama Perusahaan' }}</strong></p>
                                        <p class="mb-1">{{ company_info('address') ?: 'Alamat Perusahaan' }}</p>
                                        <p class="mb-1">{{ company_info('phone') ?: 'No. Telepon' }}</p>
                                        <p class="mb-0">{{ company_info('email') ?: 'Email' }}</p>
                                        <small class="text-muted">Informasi ini akan muncul di cover laporan</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Accountability Sheet -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <h5>Lembar Pertanggungjawaban</h5>
                                <div class="form-group">
                                    <label for="accountability_text">Teks Pertanggungjawaban</label>
                                    <textarea name="accountability_text" id="accountability_text" 
                                              class="form-control @error('accountability_text') is-invalid @enderror" 
                                              rows="4" placeholder="Masukkan teks pertanggungjawaban...">{{ old('accountability_text') }}</textarea>
                                    @error('accountability_text')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Custom Pages -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5>Halaman Kustom</h5>
                                    <button type="button" class="btn btn-primary btn-sm" id="addPage">
                                        <i class="fas fa-plus"></i> Tambah Halaman
                                    </button>
                                </div>
                                
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i>
                                    <strong>Halaman Kustom:</strong> Anda dapat menambahkan halaman dengan konten bebas seperti:
                                    <ul class="mb-0 mt-2">
                                        <li>Kata Pengantar</li>
                                        <li>Profil Perusahaan</li>
                                        <li>Analisis Keuangan</li>
                                        <li>Rencana Strategis</li>
                                        <li>Kesimpulan dan Rekomendasi</li>
                                        <li>Lampiran</li>
                                    </ul>
                                </div>
                                
                                <div id="pagesContainer">
                                    <!-- Custom pages will be added here dynamically -->
                                </div>
                            </div>
                        </div>

                        <!-- Report Components -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <h5>Komponen Laporan yang Akan Disertakan</h5>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i>
                                    Laporan berikut akan otomatis disertakan dalam laporan tahunan:
                                    <ul class="mb-0 mt-2">
                                        <li>Laporan Laba Rugi</li>
                                        <li>Neraca (Balance Sheet)</li>
                                        <li>Laporan Arus Kas</li>
                                        <li>Buku Besar (General Ledger)</li>
                                        <li>Neraca Saldo (Trial Balance)</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="row">
                            <div class="col-md-6">
                                <button type="submit" name="preview" value="1" class="btn btn-info">
                                    <i class="fas fa-eye"></i> Preview
                                </button>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-file-pdf"></i> Generate PDF
                                </button>
                                <button type="submit" name="export_docx" value="1" class="btn btn-primary">
                                    <i class="fas fa-file-word"></i> Export DOCX
                                </button>
                            </div>
                            <div class="col-md-6 text-right">
                                <small class="text-muted">Format: PDF Letter / DOCX</small>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Custom Page Template -->
<template id="pageTemplate">
    <div class="card card-outline card-primary page-item mb-3">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="card-title">
                    <i class="fas fa-file-alt"></i> 
                    Halaman <span class="page-number"></span>
                </h3>
                <div class="btn-group">
                    <button type="button" class="btn btn-sm btn-outline-secondary move-up" title="Pindah ke atas">
                        <i class="fas fa-arrow-up"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary move-down" title="Pindah ke bawah">
                        <i class="fas fa-arrow-down"></i>
                    </button>
                    <button type="button" class="btn btn-danger btn-sm remove-page" title="Hapus halaman">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label>Judul Halaman <span class="text-danger">*</span></label>
                        <input type="text" name="pages[INDEX][title]" class="form-control page-title" 
                               placeholder="Contoh: Kata Pengantar, Profil Perusahaan, dll..." required>
                        <small class="form-text text-muted">Judul ini akan muncul di daftar isi</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Tipe Halaman</label>
                        <select name="pages[INDEX][type]" class="form-control page-type">
                            <option value="content">Konten Bebas</option>
                            <option value="introduction">Kata Pengantar</option>
                            <option value="profile">Profil Perusahaan</option>
                            <option value="analysis">Analisis</option>
                            <option value="conclusion">Kesimpulan</option>
                            <option value="appendix">Lampiran</option>
                        </select>
                        <small class="form-text text-muted">Membantu kategorisasi halaman</small>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label>Konten Halaman</label>
                <textarea name="pages[INDEX][content]" class="form-control page-content wysiwyg" 
                          rows="8" placeholder="Masukkan konten halaman..."></textarea>
                <small class="form-text text-muted">Gunakan editor untuk memformat teks, menambah gambar, tabel, dll.</small>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" name="pages[INDEX][show_in_toc]" 
                                   id="showInToc_INDEX" value="1" checked>
                            <label class="custom-control-label" for="showInToc_INDEX">
                                Tampilkan di Daftar Isi
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" name="pages[INDEX][page_break]" 
                                   id="pageBreak_INDEX" value="1" checked>
                            <label class="custom-control-label" for="pageBreak_INDEX">
                                Mulai di Halaman Baru
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
@endsection

@section('plugins.Summernote', true)

@push('js')
<script>
$(document).ready(function() {
    let pageIndex = 0;
    
    console.log('Document ready, initializing Summernote...');

    // Initialize Summernote for accountability text
    $('#accountability_text').summernote({
        height: 150,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'clear']],
            ['fontname', ['fontname']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ],
        placeholder: 'Masukkan teks pertanggungjawaban...',
        callbacks: {
            onInit: function() {
                console.log('Summernote initialized for accountability text');
            }
        }
    });

    // Add page functionality
    $('#addPage').click(function() {
        addPage();
    });

    // Remove page functionality
    $(document).on('click', '.remove-page', function() {
        let pageItem = $(this).closest('.page-item');
        
        // Destroy Summernote instance before removing
        pageItem.find('.wysiwyg').summernote('destroy');
        pageItem.remove();
        
        updatePageNumbers();
    });

    // Move page up
    $(document).on('click', '.move-up', function() {
        let pageItem = $(this).closest('.page-item');
        let prevItem = pageItem.prev('.page-item');
        
        if (prevItem.length) {
            pageItem.insertBefore(prevItem);
            updatePageNumbers();
        }
    });

    // Move page down
    $(document).on('click', '.move-down', function() {
        let pageItem = $(this).closest('.page-item');
        let nextItem = pageItem.next('.page-item');
        
        if (nextItem.length) {
            pageItem.insertAfter(nextItem);
            updatePageNumbers();
        }
    });

    function addPage() {
        pageIndex++;
        console.log('Adding page:', pageIndex);
        
        let template = $('#pageTemplate').html();
        template = template.replace(/INDEX/g, pageIndex);
        
        let pageElement = $(template);
        pageElement.find('.page-number').text(pageIndex);
        
        $('#pagesContainer').append(pageElement);
        
        // Initialize Summernote for the new textarea
        pageElement.find('.wysiwyg').summernote({
            height: 250,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ],
            placeholder: 'Masukkan konten halaman...',
            callbacks: {
                onInit: function() {
                    console.log('Summernote initialized for page ' + pageIndex);
                }
            }
        });
        
        updatePageNumbers();
        console.log('Page added successfully');
        
        // Scroll to the new page
        pageElement[0].scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    function updatePageNumbers() {
        $('.page-item').each(function(index) {
            $(this).find('.page-number').text(index + 1);
            $(this).find('input, textarea, select').each(function() {
                let name = $(this).attr('name');
                let id = $(this).attr('id');
                
                if (name) {
                    name = name.replace(/pages\[\d+\]/, 'pages[' + index + ']');
                    $(this).attr('name', name);
                }
                
                if (id) {
                    id = id.replace(/_\d+$/, '_' + index);
                    $(this).attr('id', id);
                    
                    // Update corresponding label
                    let label = $(this).closest('.page-item').find('label[for="' + $(this).attr('id').replace(/_\d+$/, '_' + (index + 1)) + '"]');
                    if (label.length) {
                        label.attr('for', id);
                    }
                }
            });
            
            // Update move buttons state
            $(this).find('.move-up').prop('disabled', index === 0);
            $(this).find('.move-down').prop('disabled', index === $('.page-item').length - 1);
        });
    }

    // Form validation
    $('#annualReportForm').submit(function(e) {
        if (!$('#year').val()) {
            e.preventDefault();
            alert('Silakan pilih tahun laporan terlebih dahulu.');
            $('#year').focus();
            return false;
        }

        // Validate page titles
        let hasEmptyTitle = false;
        $('.page-title').each(function() {
            if (!$(this).val().trim()) {
                hasEmptyTitle = true;
                $(this).addClass('is-invalid');
            } else {
                $(this).removeClass('is-invalid');
            }
        });

        if (hasEmptyTitle) {
            e.preventDefault();
            alert('Silakan isi judul untuk semua halaman kustom.');
            return false;
        }

        // Sync Summernote content before submit
        $('.wysiwyg').each(function() {
            if ($(this).summernote('codeview.isActivated')) {
                $(this).summernote('codeview.deactivate');
            }
        });
    });

    // Auto-save functionality (optional)
    let autoSaveTimer;
    $(document).on('input', '.page-title, .page-content', function() {
        clearTimeout(autoSaveTimer);
        autoSaveTimer = setTimeout(function() {
            console.log('Auto-saving draft...');
            // Implement auto-save logic here if needed
        }, 2000);
    });
});
</script>
@endpush