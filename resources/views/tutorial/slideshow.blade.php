<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tutorial BUMDES - Panduan Penggunaan Sistem</title>
    <link href="{{ asset('vendor/bootstrap5/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow: hidden;
        }

        .slideshow-container {
            position: relative;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .slide {
            display: none;
            text-align: center;
            color: white;
            max-width: 800px;
            padding: 40px;
            animation: fadeIn 0.5s ease-in-out;
        }

        .slide.active {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .slide-icon {
            font-size: 4rem;
            margin-bottom: 30px;
            color: #ffd700;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .slide-title {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 15px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .slide-subtitle {
            font-size: 1.3rem;
            margin-bottom: 25px;
            opacity: 0.9;
        }

        .slide-content {
            font-size: 1.1rem;
            line-height: 1.6;
            margin-bottom: 30px;
            opacity: 0.95;
        }

        .steps-list, .tips-list {
            text-align: left;
            max-width: 600px;
            margin: 0 auto;
            background: rgba(255,255,255,0.1);
            padding: 25px;
            border-radius: 15px;
            backdrop-filter: blur(10px);
        }

        .steps-list li, .tips-list li {
            margin-bottom: 12px;
            padding: 8px 0;
            border-bottom: 1px solid rgba(255,255,255,0.2);
        }

        .steps-list li:last-child, .tips-list li:last-child {
            border-bottom: none;
        }

        .navigation {
            position: fixed;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .nav-btn {
            background: rgba(255,255,255,0.2);
            border: 2px solid rgba(255,255,255,0.3);
            color: white;
            padding: 12px 20px;
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .nav-btn:hover {
            background: rgba(255,255,255,0.3);
            transform: translateY(-2px);
        }

        .nav-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .slide-counter {
            background: rgba(255,255,255,0.2);
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: bold;
            backdrop-filter: blur(10px);
        }

        .progress-bar {
            position: fixed;
            top: 0;
            left: 0;
            height: 4px;
            background: #ffd700;
            transition: width 0.3s ease;
            z-index: 1000;
        }

        .close-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            background: rgba(255,255,255,0.2);
            border: none;
            color: white;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            font-size: 1.2rem;
            cursor: pointer;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .close-btn:hover {
            background: rgba(255,255,255,0.3);
            transform: rotate(90deg);
        }

        .welcome-slide {
            background: radial-gradient(circle, rgba(255,215,0,0.2) 0%, transparent 70%);
        }

        .success-slide {
            background: radial-gradient(circle, rgba(40,167,69,0.2) 0%, transparent 70%);
        }

        .auto-play-toggle {
            position: fixed;
            top: 20px;
            left: 20px;
            background: rgba(255,255,255,0.2);
            border: none;
            color: white;
            padding: 10px 15px;
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .auto-play-toggle:hover {
            background: rgba(255,255,255,0.3);
        }

        .auto-play-toggle.active {
            background: rgba(40,167,69,0.3);
        }
    </style>
</head>
<body>
    <div class="progress-bar" id="progressBar"></div>
    
    <button class="close-btn" onclick="closeTutorial()">
        <i class="fas fa-times"></i>
    </button>

    <button class="auto-play-toggle" id="autoPlayBtn" onclick="toggleAutoPlay()">
        <i class="fas fa-play"></i> Auto
    </button>

    <div class="slideshow-container" data-total-slides="{{ $totalSlides }}">
        @foreach($slides as $index => $slide)
        <div class="slide {{ $index === 0 ? 'active' : '' }} {{ $slide['type'] === 'welcome' ? 'welcome-slide' : '' }} {{ $slide['type'] === 'success' ? 'success-slide' : '' }}" data-slide="{{ $index }}">
            <div class="slide-icon">
                <i class="{{ $slide['image'] }}"></i>
            </div>
            
            <h1 class="slide-title">{{ $slide['title'] }}</h1>
            <h2 class="slide-subtitle">{{ $slide['subtitle'] }}</h2>
            <p class="slide-content">{{ $slide['content'] }}</p>

            @if(isset($slide['steps']))
            <ol class="steps-list">
                @foreach($slide['steps'] as $step)
                <li><i class="fas fa-check-circle text-success me-2"></i>{{ $step }}</li>
                @endforeach
            </ol>
            @endif

            @if(isset($slide['tips']))
            <ul class="tips-list">
                @foreach($slide['tips'] as $tip)
                <li><i class="fas fa-lightbulb text-warning me-2"></i>{{ $tip }}</li>
                @endforeach
            </ul>
            @endif
        </div>
        @endforeach
    </div>

    <div class="navigation">
        <button class="nav-btn" id="prevBtn" onclick="changeSlide(-1)">
            <i class="fas fa-chevron-left"></i> Sebelumnya
        </button>
        
        <div class="slide-counter">
            <span id="currentSlide">1</span> / <span id="totalSlides">{{ $totalSlides }}</span>
        </div>
        
        <button class="nav-btn" id="nextBtn" onclick="changeSlide(1)">
            Selanjutnya <i class="fas fa-chevron-right"></i>
        </button>
    </div>

    <script src="{{ asset('vendor/bootstrap5/bootstrap.bundle.min.js') }}"></script>
    <script>
        let currentSlideIndex = 0;
        const totalSlides = parseInt(document.querySelector('.slideshow-container').dataset.totalSlides);
        let autoPlay = false;
        let autoPlayInterval;

        function showSlide(index) {
            const slides = document.querySelectorAll('.slide');
            
            // Hide all slides
            slides.forEach(slide => slide.classList.remove('active'));
            
            // Show current slide
            slides[index].classList.add('active');
            
            // Update counter
            document.getElementById('currentSlide').textContent = index + 1;
            
            // Update progress bar
            const progress = ((index + 1) / totalSlides) * 100;
            document.getElementById('progressBar').style.width = progress + '%';
            
            // Update navigation buttons
            document.getElementById('prevBtn').disabled = index === 0;
            document.getElementById('nextBtn').disabled = index === totalSlides - 1;
            
            if (index === totalSlides - 1) {
                document.getElementById('nextBtn').innerHTML = 'Selesai <i class="fas fa-check"></i>';
            } else {
                document.getElementById('nextBtn').innerHTML = 'Selanjutnya <i class="fas fa-chevron-right"></i>';
            }
        }

        function changeSlide(direction) {
            const newIndex = currentSlideIndex + direction;
            
            if (newIndex >= 0 && newIndex < totalSlides) {
                currentSlideIndex = newIndex;
                showSlide(currentSlideIndex);
            } else if (newIndex >= totalSlides) {
                // Tutorial selesai
                closeTutorial();
            }
        }

        function toggleAutoPlay() {
            autoPlay = !autoPlay;
            const btn = document.getElementById('autoPlayBtn');
            
            if (autoPlay) {
                btn.classList.add('active');
                btn.innerHTML = '<i class="fas fa-pause"></i> Auto';
                startAutoPlay();
            } else {
                btn.classList.remove('active');
                btn.innerHTML = '<i class="fas fa-play"></i> Auto';
                stopAutoPlay();
            }
        }

        function startAutoPlay() {
            autoPlayInterval = setInterval(() => {
                if (currentSlideIndex < totalSlides - 1) {
                    changeSlide(1);
                } else {
                    stopAutoPlay();
                }
            }, 5000); // 5 detik per slide
        }

        function stopAutoPlay() {
            if (autoPlayInterval) {
                clearInterval(autoPlayInterval);
                autoPlayInterval = null;
            }
            autoPlay = false;
            const btn = document.getElementById('autoPlayBtn');
            btn.classList.remove('active');
            btn.innerHTML = '<i class="fas fa-play"></i> Auto';
        }

        function closeTutorial() {
            if (confirm('Apakah Anda yakin ingin menutup tutorial ini?')) {
                window.location.href = '{{ route("dashboard") }}';
            }
        }

        // Keyboard navigation
        document.addEventListener('keydown', function(e) {
            switch(e.key) {
                case 'ArrowLeft':
                    changeSlide(-1);
                    break;
                case 'ArrowRight':
                case ' ':
                    e.preventDefault();
                    changeSlide(1);
                    break;
                case 'Escape':
                    closeTutorial();
                    break;
                case 'p':
                case 'P':
                    toggleAutoPlay();
                    break;
            }
        });

        // Initialize
        showSlide(0);

        // Touch/swipe support for mobile
        let touchStartX = 0;
        let touchEndX = 0;

        document.addEventListener('touchstart', function(e) {
            touchStartX = e.changedTouches[0].screenX;
        });

        document.addEventListener('touchend', function(e) {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
        });

        function handleSwipe() {
            const swipeThreshold = 50;
            const diff = touchStartX - touchEndX;
            
            if (Math.abs(diff) > swipeThreshold) {
                if (diff > 0) {
                    // Swipe left - next slide
                    changeSlide(1);
                } else {
                    // Swipe right - previous slide
                    changeSlide(-1);
                }
            }
        }
    </script>
</body>
</html>