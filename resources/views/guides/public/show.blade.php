<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $guide->title }} - Panduan BUMDES</title>
    <link href="{{ asset('vendor/bootstrap5/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/prism/prism.min.css') }}" rel="stylesheet">
    <style>
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }
        .guide-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 60px 0;
        }
        .guide-content {
            line-height: 1.8;
        }
        .guide-content h1, .guide-content h2, .guide-content h3 {
            margin-top: 2rem;
            margin-bottom: 1rem;
            color: #2c3e50;
        }
        .guide-content h1 {
            border-bottom: 3px solid #667eea;
            padding-bottom: 0.5rem;
        }
        .guide-content h2 {
            border-left: 4px solid #667eea;
            padding-left: 1rem;
        }
        .guide-content h3 {
            color: #667eea;
        }
        .guide-content ul, .guide-content ol {
            margin-bottom: 1.5rem;
        }
        .guide-content li {
            margin-bottom: 0.5rem;
        }
        .guide-content blockquote {
            border-left: 4px solid #667eea;
            background-color: #f8f9fa;
            padding: 1rem;
            margin: 1.5rem 0;
        }
        .guide-content code {
            background-color: #f8f9fa;
            padding: 0.2rem 0.4rem;
            border-radius: 3px;
            font-size: 0.9rem;
        }
        .guide-content pre {
            background-color: #f8f9fa;
            padding: 1rem;
            border-radius: 5px;
            overflow-x: auto;
        }
        .guide-meta {
            background-color: #f8f9fa;
            padding: 1.5rem;
            border-radius: 10px;
            margin-bottom: 2rem;
        }
        .category-badge {
            font-size: 0.9rem;
            padding: 0.4rem 1rem;
            border-radius: 20px;
        }
        .sidebar {
            position: sticky;
            top: 2rem;
        }
        .toc {
            background-color: #f8f9fa;
            padding: 1.5rem;
            border-radius: 10px;
            margin-bottom: 2rem;
        }
        .toc ul {
            list-style: none;
            padding-left: 0;
        }
        .toc ul ul {
            padding-left: 1rem;
        }
        .toc a {
            text-decoration: none;
            color: #667eea;
            display: block;
            padding: 0.3rem 0;
            border-bottom: 1px solid #e9ecef;
        }
        .toc a:hover {
            color: #764ba2;
            background-color: #e9ecef;
            padding-left: 0.5rem;
            transition: all 0.3s ease;
        }
        .back-to-top {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            background-color: #667eea;
            color: white;
            border: none;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            display: none;
            z-index: 1000;
        }
        .back-to-top:hover {
            background-color: #764ba2;
        }
        .footer {
            background-color: #2c3e50;
            color: white;
            padding: 40px 0;
            margin-top: 4rem;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="fas fa-building text-primary me-2"></i>
                BUMDES
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="{{ url('/') }}">
                    <i class="fas fa-arrow-left me-1"></i>
                    Kembali ke Panduan
                </a>
                <a class="nav-link" href="{{ route('login') }}">
                    <i class="fas fa-sign-in-alt me-1"></i>
                    Login
                </a>
            </div>
        </div>
    </nav>

    <!-- Guide Header -->
    <section class="guide-header">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="d-flex align-items-center mb-3">
                        <i class="{{ $guide->icon }} me-3" style="font-size: 2.5rem;"></i>
                        <span class="badge category-badge bg-light text-dark">
                            {{ ucfirst(str_replace('-', ' ', $guide->category)) }}
                        </span>
                    </div>
                    <h1 class="display-5 fw-bold">{{ $guide->title }}</h1>
                    <p class="lead">{{ $guide->description }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Guide Content -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <!-- Guide Meta -->
                    <div class="guide-meta">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <small class="text-muted">
                                    <i class="fas fa-calendar me-1"></i>
                                    Dipublikasikan: {{ $guide->created_at->format('d M Y') }}
                                </small>
                            </div>
                            <div class="col-md-6 text-md-end">
                                @if($guide->creator)
                                <small class="text-muted">
                                    <i class="fas fa-user me-1"></i>
                                    Oleh: {{ $guide->creator->name }}
                                </small>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- YouTube Video -->
                    @if($guide->youtube_url)
                    <div class="youtube-video mb-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fab fa-youtube text-danger me-2"></i>
                                    Video Tutorial
                                </h5>
                            </div>
                            <div class="card-body p-0">
                                <div class="ratio ratio-16x9">
                                    <iframe src="{{ $guide->youtube_embed_url }}" 
                                            title="YouTube video player" 
                                            frameborder="0" 
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                            allowfullscreen>
                                    </iframe>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Guide Content -->
                    <div class="guide-content">
                        {!! $guide->content !!}
                    </div>

                    <!-- Navigation -->
                    <div class="row mt-5">
                        <div class="col-md-6">
                            @if($previousGuide)
                            <a href="{{ route('guides.public.show', $previousGuide->slug) }}" class="btn btn-outline-primary">
                                <i class="fas fa-arrow-left me-1"></i>
                                {{ $previousGuide->title }}
                            </a>
                            @endif
                        </div>
                        <div class="col-md-6 text-md-end">
                            @if($nextGuide)
                            <a href="{{ route('guides.public.show', $nextGuide->slug) }}" class="btn btn-outline-primary">
                                {{ $nextGuide->title }}
                                <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <div class="sidebar">
                        <!-- Table of Contents -->
                        <div class="toc">
                            <h5 class="mb-3">
                                <i class="fas fa-list me-2"></i>
                                Daftar Isi
                            </h5>
                            <div id="tableOfContents">
                                <!-- TOC will be generated by JavaScript -->
                            </div>
                        </div>

                        <!-- Related Guides -->
                        @if($relatedGuides->count() > 0)
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-book me-2"></i>
                                    Panduan Terkait
                                </h5>
                            </div>
                            <div class="card-body">
                                @foreach($relatedGuides as $related)
                                <div class="mb-3">
                                    <a href="{{ route('guides.public.show', $related->slug) }}" class="text-decoration-none">
                                        <div class="d-flex align-items-start">
                                            <i class="{{ $related->icon }} me-2 mt-1 text-primary"></i>
                                            <div>
                                                <h6 class="mb-1">{{ $related->title }}</h6>
                                                <small class="text-muted">{{ Str::limit($related->description, 80) }}</small>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                @if(!$loop->last)
                                <hr>
                                @endif
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Back to Top Button -->
    <button class="back-to-top" id="backToTop">
        <i class="fas fa-arrow-up"></i>
    </button>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>Sistem BUMDES</h5>
                    <p>Platform manajemen keuangan untuk Badan Usaha Milik Desa yang transparan dan profesional.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <h5>Kontak</h5>
                    <p>
                        <i class="fas fa-envelope me-2"></i>
                        info@bumdes.id<br>
                        <i class="fas fa-phone me-2"></i>
                        (021) 1234-5678
                    </p>
                </div>
            </div>
            <hr class="my-4">
            <div class="text-center">
                <p>&copy; {{ date('Y') }} Sistem BUMDES. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="{{ asset('vendor/bootstrap5/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/prism/prism-core.min.js') }}"></script>
    <!-- Prism autoloader removed - using local core files only -->
    <script src="{{ asset('vendor/marked/marked.min.js') }}"></script>
    <script>
        // Convert Markdown to HTML if needed
        function processContent() {
            const contentDiv = document.querySelector('.guide-content');
            const content = contentDiv.innerHTML.trim();
            
            // Check if content looks like Markdown (starts with # or contains markdown patterns)
            if (content.includes('\n#') || content.match(/^#\s/m)) {
                // Convert Markdown to HTML
                const htmlContent = marked.parse(content);
                contentDiv.innerHTML = htmlContent;
            }
        }

        // Generate Table of Contents
        function generateTOC() {
            const headings = document.querySelectorAll('.guide-content h1, .guide-content h2, .guide-content h3');
            const toc = document.getElementById('tableOfContents');
            
            if (headings.length === 0) {
                toc.innerHTML = '<p class="text-muted">Tidak ada daftar isi</p>';
                return;
            }
            
            let tocHTML = '<ul>';
            headings.forEach((heading, index) => {
                const id = 'heading-' + index;
                heading.id = id;
                
                const level = parseInt(heading.tagName.charAt(1));
                const indent = level > 1 ? 'style="margin-left: ' + ((level - 1) * 20) + 'px;"' : '';
                
                tocHTML += `<li ${indent}><a href="#${id}">${heading.textContent}</a></li>`;
            });
            tocHTML += '</ul>';
            
            toc.innerHTML = tocHTML;
        }

        // Smooth scrolling for TOC links
        function setupSmoothScrolling() {
            document.querySelectorAll('#tableOfContents a').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });
        }

        // Back to top button
        function setupBackToTop() {
            const backToTopButton = document.getElementById('backToTop');
            
            window.addEventListener('scroll', function() {
                if (window.pageYOffset > 300) {
                    backToTopButton.style.display = 'block';
                } else {
                    backToTopButton.style.display = 'none';
                }
            });
            
            backToTopButton.addEventListener('click', function() {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        }

        // Initialize everything when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            processContent();
            generateTOC();
            setupSmoothScrolling();
            setupBackToTop();
        });
    </script>
</body>
</html>