<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panduan Sistem BUMDES</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 80px 0;
        }
        .guide-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
            border-radius: 15px;
            overflow: hidden;
        }
        .guide-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .category-badge {
            font-size: 0.8rem;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
        }
        .guide-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: #667eea;
        }
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }
        .footer {
            background-color: #2c3e50;
            color: white;
            padding: 40px 0;
        }
        .category-filter {
            margin-bottom: 2rem;
        }
        .category-filter .btn {
            margin: 0.2rem;
            border-radius: 20px;
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
                <a class="nav-link" href="{{ route('login') }}">
                    <i class="fas fa-sign-in-alt me-1"></i>
                    Login
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container text-center">
            <h1 class="display-4 fw-bold mb-4">Panduan Sistem BUMDES</h1>
            <p class="lead mb-4">Pelajari cara menggunakan sistem manajemen keuangan BUMDES dengan panduan lengkap dan mudah dipahami</p>
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="input-group input-group-lg">
                        <input type="text" class="form-control" placeholder="Cari panduan..." id="searchGuides">
                        <button class="btn btn-light" type="button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Category Filter -->
    <section class="py-4 bg-light">
        <div class="container">
            <div class="category-filter text-center">
                <button class="btn btn-outline-primary active" data-category="all">
                    <i class="fas fa-th-large me-1"></i>
                    Semua Panduan
                </button>
                @foreach($categories as $key => $name)
                <button class="btn btn-outline-primary" data-category="{{ $key }}">
                    @switch($key)
                        @case('getting-started')
                            <i class="fas fa-play-circle me-1"></i>
                            @break
                        @case('financial-management')
                            <i class="fas fa-chart-line me-1"></i>
                            @break
                        @case('system-administration')
                            <i class="fas fa-cogs me-1"></i>
                            @break
                        @case('troubleshooting')
                            <i class="fas fa-tools me-1"></i>
                            @break
                        @case('best-practices')
                            <i class="fas fa-star me-1"></i>
                            @break
                        @case('comprehensive')
                            <i class="fas fa-graduation-cap me-1"></i>
                            @break
                        @default
                            <i class="fas fa-book me-1"></i>
                    @endswitch
                    {{ $name }}
                </button>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Guides Grid -->
    <section class="py-5">
        <div class="container">
            <div class="row" id="guidesContainer">
                @php
                    $allGuides = collect();
                    foreach($guidesByCategory as $categoryGuides) {
                        $allGuides = $allGuides->merge($categoryGuides);
                    }
                @endphp
                
                @foreach($allGuides as $guide)
                <div class="col-lg-4 col-md-6 mb-4 guide-item" data-category="{{ $guide->category }}">
                    <div class="card guide-card h-100">
                        <div class="card-body text-center">
                            <div class="guide-icon">
                                <i class="{{ $guide->icon }}"></i>
                            </div>
                            <h5 class="card-title">{{ $guide->title }}</h5>
                            <p class="card-text text-muted">{{ $guide->description }}</p>
                            <span class="badge category-badge bg-primary">
                                {{ $categories[$guide->category] ?? ucfirst(str_replace('-', ' ', $guide->category)) }}
                            </span>
                        </div>
                        <div class="card-footer bg-transparent border-0 text-center">
                            <a href="{{ url('/panduan/' . $guide->slug) }}" class="btn btn-outline-primary">
                                <i class="fas fa-book-open me-1"></i>
                                Baca Panduan
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            @if($allGuides->isEmpty())
            <div class="text-center py-5">
                <i class="fas fa-book-open text-muted" style="font-size: 4rem;"></i>
                <h3 class="mt-3 text-muted">Belum Ada Panduan</h3>
                <p class="text-muted">Panduan akan segera tersedia.</p>
            </div>
            @endif
        </div>
    </section>

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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Category filter functionality
        document.querySelectorAll('[data-category]').forEach(button => {
            button.addEventListener('click', function() {
                const category = this.dataset.category;
                
                // Update active button
                document.querySelectorAll('[data-category]').forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                
                // Filter guides
                document.querySelectorAll('.guide-item').forEach(item => {
                    if (category === 'all' || item.dataset.category === category) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        });

        // Search functionality
        document.getElementById('searchGuides').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            
            document.querySelectorAll('.guide-item').forEach(item => {
                const title = item.querySelector('.card-title').textContent.toLowerCase();
                const description = item.querySelector('.card-text').textContent.toLowerCase();
                
                if (title.includes(searchTerm) || description.includes(searchTerm)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>