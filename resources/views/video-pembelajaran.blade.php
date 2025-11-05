<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video Pembelajaran - Mecha Learn</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/materi.css') }}">
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
            transition: all 0.3s ease;
            padding: 0.5rem 1rem;
            border-radius: 25px;
        }
        
        .nav-links a:hover {
            background: rgba(255,255,255,0.2);
            transform: translateY(-2px);
        }
        
        .btn-logout {
            background: #e63946;
            color: white;
            border: none;
            padding: 0.5rem 1.2rem;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .btn-logout:hover {
            background: #d62828;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(230, 57, 70, 0.3);
        }
        
        /* Mobile Navigation */
        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 5px;
            transition: background 0.3s ease;
        }
        
        .mobile-menu-btn:hover {
            background: rgba(255,255,255,0.1);
        }
        
        /* Main Content */
        .main-content {
            padding: 2rem 1rem;
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .page-header {
            background: linear-gradient(135deg, #fff3e0 0%, #ffe0b2 100%);
            border-left: 5px solid #ff9100;
            padding: 2rem;
            border-radius: 15px;
            margin-bottom: 2rem;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }
        
        .page-header h1 {
            color: #c12727;
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .page-header p {
            color: #666;
            font-size: 1.1rem;
            margin: 0;
        }
        
        
        /* Video Grid */
        .video-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .video-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            position: relative;
        }
        
        .video-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
        }
        
        .video-thumbnail {
            width: 100%;
            height: 200px;
            background: linear-gradient(135deg, #c12727 0%, #ff9100 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 3rem;
            position: relative;
            overflow: hidden;
        }
        
        .video-thumbnail::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.1"/><circle cx="10" cy="60" r="0.5" fill="white" opacity="0.1"/><circle cx="90" cy="40" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }
        
        .completion-badge, .progress-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 0.8rem;
            font-weight: bold;
        }
        
        .completion-badge {
            background: #28a745;
        }
        
        .progress-badge {
            background: #ffc107;
        }
        
        .video-content {
            padding: 1.5rem;
        }
        
        .video-category {
            display: inline-block;
            background: #ff9100;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
        }
        
        .video-title {
            color: #c12727;
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            line-height: 1.3;
        }
        
        .video-description {
            color: #666;
            font-size: 0.95rem;
            margin-bottom: 1rem;
            line-height: 1.5;
        }
        
        .video-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .video-duration {
            color: #666;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }
        
        .video-level {
            background: #e9ecef;
            color: #666;
            padding: 0.25rem 0.5rem;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .video-actions {
            display: flex;
            gap: 0.75rem;
        }
        
        .btn-video {
            flex: 1;
            padding: 0.75rem 1rem;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            text-align: center;
            display: inline-block;
        }
        
        .btn-primary-video {
            background: linear-gradient(135deg, #c12727 0%, #ff9100 100%);
            color: white;
        }
        
        .btn-primary-video:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(193, 39, 39, 0.3);
            color: white;
        }
        
        .btn-secondary-video {
            background: #f8f9fa;
            color: #666;
            border: 2px solid #e9ecef;
        }
        
        .btn-secondary-video:hover {
            background: #e9ecef;
            color: #333;
        }
        
        /* Progress Section */
        .progress-section {
            background: white;
            padding: 1.5rem;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        
        .progress-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .progress-title {
            color: #c12727;
            font-size: 1.2rem;
            font-weight: 700;
        }
        
        .progress-percentage {
            color: #ff9100;
            font-size: 1.1rem;
            font-weight: 700;
        }
        
        .progress-bar-container {
            background: #e9ecef;
            height: 10px;
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 1rem;
        }
        
        .progress-bar {
            background: linear-gradient(135deg, #c12727 0%, #ff9100 100%);
            height: 100%;
            border-radius: 10px;
            transition: width 0.3s ease;
        }
        
        .progress-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 1rem;
        }
        
        .progress-stat {
            text-align: center;
            padding: 0.75rem;
            background: #f8f9fa;
            border-radius: 10px;
        }
        
        .progress-stat-number {
            color: #c12727;
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }
        
        .progress-stat-label {
            color: #666;
            font-size: 0.9rem;
        }
        
        /* Footer */
        footer {
            background: linear-gradient(135deg, #c12727 0%, #ff9100 100%);
            color: white;
            text-align: center;
            padding: 2rem;
            margin-top: 3rem;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .navbar {
                padding: 0.5rem 1rem;
            }
            
            .logo-area h1 {
                font-size: 1.2rem;
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
                gap: 0.5rem;
            }
            
            .nav-links.active {
                display: flex;
            }
            
            .mobile-menu-btn {
                display: block;
            }
            
            .main-content {
                padding: 1rem 0.5rem;
            }
            
            .page-header {
                padding: 1.5rem;
            }
            
            .page-header h1 {
                font-size: 1.6rem;
            }
            
            .filter-row {
                flex-direction: column;
                align-items: stretch;
            }
            
            .search-box {
                min-width: auto;
            }
            
            .video-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            
            .video-actions {
                flex-direction: column;
            }
            
            .progress-stats {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 480px) {
            .page-header h1 {
                font-size: 1.4rem;
            }
            
            .video-card {
                margin-bottom: 1rem;
            }
            
            .video-thumbnail {
                height: 150px;
                font-size: 2rem;
            }
            
            .progress-stats {
                grid-template-columns: 1fr;
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
            <a href="{{ url('/') }}" class="btn-logout">Keluar</a>
        </nav>
    </header>

    <main class="main-content">
        <!-- Page Header -->
        <div class="page-header">
            <h1>🎥 Video Pembelajaran</h1>
            <p>Video pembelajaran khusus untuk <strong>{{ $user['class'] }}</strong> - Teknik Sepeda Motor</p>
        </div>

        <!-- Progress Section -->
        <div class="progress-section">
            <div class="progress-header">
                <h3 class="progress-title">Progress Belajar Anda</h3>
                <span class="progress-percentage">{{ $overallProgress }}%</span>
            </div>
            <div class="progress-bar-container">
                <div class="progress-bar" style="width: {{ $overallProgress }}%;"></div>
            </div>
            <div class="progress-stats">
                <div class="progress-stat">
                    <div class="progress-stat-number">{{ $completedVideos }}</div>
                    <div class="progress-stat-label">Video Selesai</div>
                </div>
                <div class="progress-stat">
                    <div class="progress-stat-number">{{ $inProgressVideos }}</div>
                    <div class="progress-stat-label">Sedang Dipelajari</div>
                </div>
                <div class="progress-stat">
                    <div class="progress-stat-number">{{ $notStartedVideos }}</div>
                    <div class="progress-stat-label">Belum Dimulai</div>
                </div>
                <div class="progress-stat">
                    <div class="progress-stat-number">{{ $totalVideos }}</div>
                    <div class="progress-stat-label">Total Video</div>
                </div>
            </div>
        </div>


        <!-- Video Grid -->
        <div class="video-grid">
            @foreach($videoData as $video)
            <div class="video-card">
                <div class="video-thumbnail">
                    <i class="fas fa-play-circle"></i>
                    @if($video['is_completed'])
                        <div class="completion-badge">
                            <i class="fas fa-check"></i>
                        </div>
                    @elseif($video['is_in_progress'])
                        <div class="progress-badge">
                            <i class="fas fa-clock"></i>
                        </div>
                    @endif
                </div>
                <div class="video-content">
                    <span class="video-category">{{ $user['class'] }}</span>
                    <h3 class="video-title">{{ $video['title'] }}</h3>
                    <p class="video-description">{{ $video['description'] }}</p>
                    <div class="video-meta">
                        <span class="video-duration">
                            <i class="fas fa-clock"></i> {{ $video['duration'] }}
                        </span>
                        <span class="video-level">
                            @if($video['is_completed'])
                                Selesai
                            @elseif($video['is_in_progress'])
                                {{ $video['progress'] }}%
                            @else
                                Belum Dimulai
                            @endif
                        </span>
                    </div>
                    <div class="video-actions">
                        @if($video['is_completed'])
                            <a href="{{ route('video.show', $video['id']) }}" class="btn-video btn-primary-video">Review</a>
                        @elseif($video['is_in_progress'])
                            <a href="{{ route('video.show', $video['id']) }}" class="btn-video btn-primary-video">Lanjutkan</a>
                        @else
                            <a href="{{ route('video.show', $video['id']) }}" class="btn-video btn-primary-video">Mulai Menonton</a>
                        @endif
                        <a href="{{ route('video.show', $video['id']) }}" class="btn-video btn-secondary-video">Lihat Video</a>
                    </div>
                </div>
            </div>
            @endforeach

        </div>
    </main>

    <footer>
        <p>&copy; 2025 Mecha Learn. Semua hak dilindungi.</p>
    </footer>

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

    </script>
</body>
</html>
