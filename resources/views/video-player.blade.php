<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $video->judul }} - Mecha Learn</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: #f8f9fa;
            line-height: 1.6;
        }
        
        /* Navbar Styles */
        .navbar {
            background: linear-gradient(135deg, #c12727 0%, #ff9100 100%);
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            padding: 0.75rem 1rem;
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        
        .logo-area {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .logo-area img {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            object-fit: cover;
        }
        
        .logo-area h1 {
            color: white;
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0;
        }
        
        .nav-links {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .nav-links a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .nav-links a:hover {
            background: rgba(255,255,255,0.1);
            color: white;
        }
        
        .btn-logout {
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.3);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-logout:hover {
            background: rgba(255,255,255,0.2);
            color: white;
        }
        
        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
        }
        
        /* Main Content */
        .main-content {
            padding: 2rem 1rem;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .video-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            overflow: hidden;
            margin-bottom: 2rem;
        }
        
        .video-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1.5rem;
            text-align: center;
        }
        
        .video-header h1 {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .video-header p {
            opacity: 0.9;
            font-size: 1rem;
        }
        
        .video-wrapper {
            position: relative;
            width: 100%;
            height: 0;
            padding-bottom: 56.25%; /* 16:9 aspect ratio */
            background: #000;
        }
        
        .video-wrapper iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: none;
        }
        
        .video-info {
            padding: 1.5rem;
        }
        
        .video-description {
            font-size: 1rem;
            line-height: 1.6;
            color: #666;
            margin-bottom: 1.5rem;
        }
        
        .progress-section {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .progress-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }
        
        .progress-title {
            font-size: 1rem;
            font-weight: 600;
            color: #333;
            margin: 0;
        }
        
        .progress-percentage {
            font-size: 1rem;
            font-weight: 700;
            color: #667eea;
        }
        
        .progress-bar-container {
            width: 100%;
            height: 8px;
            background: #e9ecef;
            border-radius: 4px;
            overflow: hidden;
            margin-bottom: 0.5rem;
        }
        
        .progress-bar {
            height: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 4px;
            transition: width 0.3s ease;
        }
        
        .video-actions {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }
        
        .btn-action {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .btn-primary-action {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .btn-primary-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
            color: white;
        }
        
        .btn-secondary-action {
            background: #6c757d;
            color: white;
        }
        
        .btn-secondary-action:hover {
            background: #5a6268;
            transform: translateY(-2px);
            color: white;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .mobile-menu-btn {
                display: block;
            }
            
            .nav-links {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: linear-gradient(135deg, #c12727 0%, #ff9100 100%);
                flex-direction: column;
                padding: 1rem;
                box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            }
            
            .nav-links.active {
                display: flex;
            }
            
            .main-content {
                padding: 1rem;
            }
            
            .video-header h1 {
                font-size: 1.4rem;
            }
            
            .video-actions {
                flex-direction: column;
            }
            
            .btn-action {
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <header class="navbar d-flex flex-wrap justify-content-between align-items-center">
        <div class="logo-area d-flex align-items-center gap-2">
            <img src="{{ asset('image/logo.png') }}" alt="Logo">
            <h1 class="mb-0">Mecha Learn</h1>
        </div>
        <button class="mobile-menu-btn" onclick="toggleMobileMenu()">☰</button>
        <nav class="nav-links d-flex flex-wrap align-items-center">
            <a href="{{ route('dashboard') }}" class="nav-link">Beranda</a>
            <a href="{{ route('video.index') }}" class="nav-link">Video Pembelajaran</a>
            <a href="{{ route('kuis') }}" class="nav-link">Kuis</a>
            <a href="{{ url('/') }}" class="btn-logout">Keluar</a>
        </nav>
    </header>

    <main class="main-content">
        <div class="video-container">
            <div class="video-header">
                <h1>{{ $video->judul }}</h1>
                <p>{{ $userData->class_name }} • Teknik Sepeda Motor</p>
            </div>
            
            <div class="video-wrapper">
                <iframe 
                    src="{{ $video->video_url }}" 
                    allowfullscreen
                    id="videoPlayer">
                </iframe>
            </div>
            
            <div class="video-info">
                <div class="video-description">
                    {{ $video->description }}
                </div>
                
                <div class="progress-section">
                    <div class="progress-header">
                        <h3 class="progress-title">Progress Menonton</h3>
                        <span class="progress-percentage">{{ $progressPercentage }}%</span>
                    </div>
                    <div class="progress-bar-container">
                        <div class="progress-bar" style="width: {{ $progressPercentage }}%;"></div>
                    </div>
                </div>
                
                <div class="video-actions">
                    <a href="{{ route('video.index') }}" class="btn-action btn-secondary-action">
                        <i class="fas fa-arrow-left"></i>
                        Kembali ke Video
                    </a>
                    <button onclick="markAsCompleted()" class="btn-action btn-primary-action">
                        <i class="fas fa-check"></i>
                        Tandai Selesai
                    </button>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleMobileMenu() {
            const navLinks = document.querySelector('.nav-links');
            navLinks.classList.toggle('active');
        }

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const navLinks = document.querySelector('.nav-links');
            const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
            
            if (!navLinks.contains(event.target) && !mobileMenuBtn.contains(event.target)) {
                navLinks.classList.remove('active');
            }
        });

        // Close mobile menu when window is resized to desktop
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                document.querySelector('.nav-links').classList.remove('active');
            }
        });

        // Video progress tracking
        let progressUpdateInterval;
        let lastProgress = {{ $progressPercentage }};

        function startProgressTracking() {
            progressUpdateInterval = setInterval(function() {
                // Simulate progress tracking (in real implementation, you'd track actual video time)
                // For now, we'll just update every 10 seconds
                updateProgress(Math.min(lastProgress + 5, 100));
            }, 10000);
        }

        function updateProgress(progress) {
            if (progress !== lastProgress) {
                lastProgress = progress;
                
                // Update UI
                document.querySelector('.progress-percentage').textContent = progress + '%';
                document.querySelector('.progress-bar').style.width = progress + '%';
                
                // Send to server
                fetch('{{ route("video.progress") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        video_id: {{ $video->video_id }},
                        progress: progress
                    })
                });
            }
        }

        function markAsCompleted() {
            updateProgress(100);
            alert('Video berhasil ditandai sebagai selesai!');
        }

        // Start progress tracking when page loads
        document.addEventListener('DOMContentLoaded', function() {
            startProgressTracking();
        });

        // Clean up when page unloads
        window.addEventListener('beforeunload', function() {
            if (progressUpdateInterval) {
                clearInterval(progressUpdateInterval);
            }
        });
    </script>
</body>
</html>
