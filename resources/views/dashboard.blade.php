<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Mecha Learn</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
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
            background: #fff;
            line-height: 1.6;
        }
        
        /* Header Styles */
        .header {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 1rem 2rem;
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .logo-section {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .logo-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            background: #dc2626;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
        }
        
        .logo-text h1 {
            color: #333;
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0;
        }
        
        .logo-text p {
            color: #666;
            font-size: 0.9rem;
            margin: 0;
        }
        
        .user-section {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .user-info {
            text-align: right;
        }
        
        .user-name {
            color: #333;
            font-size: 1rem;
            font-weight: 600;
            margin: 0;
        }
        
        .class-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: #dc2626;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 500;
            margin-top: 0.5rem;
            box-shadow: 0 2px 8px rgba(220, 38, 38, 0.3);
        }
        
        .class-badge i {
            font-size: 0.8rem;
        }
        
        .user-actions {
            display: flex;
            gap: 0.5rem;
        }
        
        .btn-icon {
            width: 40px;
            height: 40px;
            border: none;
            border-radius: 50%;
            background: #f8f9fa;
            color: #666;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .btn-icon:hover {
            background: #e9ecef;
            color: #333;
            transform: translateY(-2px);
        }
        
        .btn-icon.home-btn:hover {
            background: #dc2626;
            color: white;
        }
        
        /* Main Content */
        .main-content {
            padding: 2rem;
            max-width: 1400px;
            margin: 0 auto;
        }
        
        /* Welcome Box */
        .welcome-box {
            background: #dc2626;
            color: white;
            padding: 2rem;
            border-radius: 15px;
            margin-bottom: 2rem;
            box-shadow: 0 8px 25px rgba(220, 38, 38, 0.3);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .welcome-content h2 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: white;
        }
        
        .welcome-content p {
            font-size: 1rem;
            margin-bottom: 1.5rem;
            color: white;
            opacity: 0.9;
        }
        
        .welcome-actions {
            display: flex;
            gap: 1rem;
        }
        
        .btn-primary {
            background: white;
            color: #dc2626;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 25px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
        }
        
        .btn-secondary {
            background: #e63946;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 25px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .btn-secondary:hover {
            background: #c62828;
            transform: translateY(-2px);
        }
        
        .progress-circle {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }
        
        .progress-circle::before {
            content: '';
            position: absolute;
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: conic-gradient(#e63946 0deg 90deg, #e9ecef 90deg 360deg);
            z-index: 1;
        }
        
        .progress-circle::after {
            content: '25%';
            position: relative;
            z-index: 2;
            color: #dc2626;
            font-weight: 700;
            font-size: 0.9rem;
        }
        
        /* Stats Cards */
        .stats-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .stats-card {
            background: white;
            padding: 1.5rem;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .stats-card.red {
            background: #dc2626;
            color: white;
        }
        
        .stats-content {
            flex: 1;
        }
        
        .stats-number {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }
        
        .stats-label {
            font-size: 0.9rem;
            opacity: 0.8;
        }
        
        .stats-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
        
        .stats-card.red .stats-icon {
            background: rgba(255,255,255,0.2);
        }
        
        .stats-card:not(.red) .stats-icon {
            background: #f8f9fa;
            color: #666;
        }
        
        /* Gamification Card */
        .gamification-card {
            background: #e63946;
            color: white;
            padding: 1.5rem;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(230, 57, 70, 0.3);
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .gamification-icon {
            width: 60px;
            height: 60px;
            background: rgba(255,255,255,0.2);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
        }
        
        .gamification-content h3 {
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .gamification-content p {
            font-size: 0.9rem;
            opacity: 0.9;
        }
        
        /* Learning Section */
        .learning-section {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .section-tabs {
            display: flex;
            border-bottom: 2px solid #f8f9fa;
        }
        
        .tab-button {
            flex: 1;
            padding: 1rem 1.5rem;
            border: none;
            background: none;
            cursor: pointer;
            font-weight: 600;
            color: #666;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        
        .tab-button.active {
            color: #dc2626;
            border-bottom: 3px solid #dc2626;
        }
        
        .tab-content {
            padding: 2rem;
        }
        
        .progress-overview {
            margin-bottom: 2rem;
        }
        
        .progress-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 1rem;
        }
        
        .progress-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }
        
        .progress-card {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 10px;
            text-align: center;
        }
        
        .progress-card.completed {
            background: white;
            color: #dc2626;
            border: 2px solid #dc2626;
        }
        
        .progress-card.watching {
            background: #e63946;
            color: white;
        }
        
        .progress-card-number {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }
        
        .progress-card-label {
            font-size: 0.9rem;
        }
        
        /* Video Grid */
        .video-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
        }
        
        .video-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        
        .video-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        
        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }
        
        .action-btn {
            flex: 1;
            min-width: 200px;
            padding: 1rem 1.5rem;
            border-radius: 15px;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 600;
            transition: all 0.3s ease;
            text-align: center;
            justify-content: center;
        }
        
        .action-btn.primary {
            background: #dc2626;
            color: white;
        }
        
        .action-btn.secondary {
            background: #e63946;
            color: white;
        }
        
        .action-btn.tertiary {
            background: #c62828;
            color: white;
        }
        
        .action-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
            color: white;
            text-decoration: none;
        }
        
        .action-btn i {
            font-size: 1.2rem;
        }
        
        .video-thumbnail {
            width: 100%;
            height: 180px;
            background: #dc2626;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 3rem;
        }
        
        .video-category {
            position: absolute;
            top: 10px;
            left: 10px;
            background: #e63946;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .video-duration {
            position: absolute;
            bottom: 10px;
            right: 10px;
            background: rgba(0,0,0,0.7);
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 5px;
            font-size: 0.8rem;
        }
        
        .video-completed {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #28a745;
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
        }
        
        .video-content {
            padding: 1.5rem;
        }
        
        .video-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
        }
        
        .video-class-info {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #dc2626;
            font-size: 0.85rem;
            font-weight: 500;
            margin-bottom: 0.75rem;
        }
        
        .video-class-info i {
            font-size: 0.8rem;
        }
        
        .video-progress {
            margin-bottom: 0.5rem;
        }
        
        .progress-label {
            font-size: 0.8rem;
            color: #666;
            margin-bottom: 0.25rem;
        }
        
        .progress-bar {
            width: 100%;
            height: 6px;
            background: #e9ecef;
            border-radius: 3px;
            overflow: hidden;
        }
        
        .progress-fill {
            height: 100%;
            background: #dc2626;
            border-radius: 3px;
            transition: width 0.3s ease;
        }
        
        /* Mobile Navigation */
        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            color: #666;
            font-size: 1.5rem;
            cursor: pointer;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .header {
                padding: 1rem;
            }
            
            .header-content {
                flex-direction: column;
                gap: 1rem;
            }
            
            .user-section {
                width: 100%;
                justify-content: space-between;
            }
            
            .main-content {
                padding: 1rem;
            }
            
            .welcome-box {
                flex-direction: column;
                text-align: center;
                gap: 1rem;
            }
            
            .welcome-actions {
                flex-direction: column;
                width: 100%;
            }
            
            .stats-section {
                grid-template-columns: 1fr;
            }
            
            .video-grid {
                grid-template-columns: 1fr;
            }
            
            .section-tabs {
                flex-direction: column;
            }
            
            .tab-button {
                justify-content: flex-start;
            }
        }
        
        @media (max-width: 480px) {
            .main-content {
                padding: 0.5rem;
            }
            
            .welcome-content h2 {
                font-size: 1.5rem;
            }
            
            .video-grid {
                grid-template-columns: 1fr;
            }
            
            .progress-cards {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-content">
            <div class="logo-section">
                <div class="logo-icon">
                    <i class="fas fa-user-circle"></i>
                </div>
                <div class="logo-text">
                    <h1>MechaLearn</h1>
                    <p>Teknik Sepeda Motor</p>
                </div>
            </div>
            <div class="user-section">
                <div class="user-info">
                    <h3 class="user-name">{{ $user['name'] }}</h3>
                    <div class="class-badge">
                        <i class="fas fa-graduation-cap"></i>
                        <span>{{ $user['class'] }}</span>
                    </div>
                </div>
                <div class="user-actions">
                    <button class="btn-icon home-btn" title="Kembali ke Beranda" onclick="window.location='{{ route('welcome') }}'">
                        <i class="fas fa-home"></i>
                    </button>
                    @if(session('role') === 'kajur' || session('role') === 'guru')
                        <button class="btn-icon" title="Admin Panel" onclick="window.location='{{ route('admin.dashboard') }}'">
                            <i class="fas fa-user-shield"></i>
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Welcome Box -->
        <div class="welcome-box">
            <div class="welcome-content">
                <h2>Selamat Datang Kembali! 👋</h2>
                <p>Lanjutkan perjalanan belajar teknik sepeda motor Anda. Jangan lupa untuk melakukan service berkala!</p>
            </div>
        </div>


        <!-- Gamification Card -->
        <div class="gamification-card">
            <div class="gamification-icon">
                <i class="fas fa-gamepad"></i>
            </div>
            <div class="gamification-content">
                <h3>Bengkel Pintar</h3>
                <p>Tingkatkan skill dengan cara yang menyenangkan! Selesaikan quest, jawab quiz.</p>
                    </div>
                </div>

        <!-- Learning Section -->
        <div class="learning-section">
            <div class="section-tabs">
                <button class="tab-button active">
                    <i class="fas fa-book"></i>
                    Pembelajaran
                </button>
                <button class="tab-button">
                    <i class="fas fa-users"></i>
                    Leaderboard
                </button>
            </div>
            <div class="tab-content">
                <!-- Progress Overview -->
                <div class="progress-overview">
                    <h3 class="progress-title">Progress Keseluruhan</h3>
                    <div class="progress-cards">
                        <div class="progress-card completed">
                            <div class="progress-card-number">{{ $completedVideos }}</div>
                            <div class="progress-card-label">Video selesai</div>
                        </div>
                        <div class="progress-card watching">
                            <div class="progress-card-number">{{ $inProgressVideos }}</div>
                            <div class="progress-card-label">Sedang Ditonton</div>
                        </div>
                        <div class="progress-card">
                            <div class="progress-card-number">{{ $notStartedVideos }}</div>
                            <div class="progress-card-label">Belum Ditonton</div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <a href="{{ route('video.index') }}" class="action-btn primary">
                        <i class="fas fa-video"></i>
                        <span>Video Pembelajaran</span>
                    </a>
                    <a href="{{ route('game.index') }}" class="action-btn secondary">
                        <i class="fas fa-gamepad"></i>
                        <span>Main Game</span>
                    </a>
                    <a href="{{ route('game.leaderboard') }}" class="action-btn tertiary">
                        <i class="fas fa-trophy"></i>
                        <span>Leaderboard</span>
                    </a>
                </div>

                <!-- Video Grid -->
                <div class="video-grid">
                    @foreach($videoData as $video)
                    <div class="video-card">
                        <div class="video-thumbnail">
                            <i class="fas fa-{{ $video['thumbnail'] ?? 'play' }}"></i>
                            <div class="video-category">{{ $video['category'] ?? 'General' }}</div>
                            <div class="video-duration">{{ $video['duration'] ?? '00:00' }}</div>
                            @if($video['is_completed'])
                            <div class="video-completed">✓</div>
                            @endif
                        </div>
                        <div class="video-content">
                            <h4 class="video-title">{{ $video['title'] }}</h4>
                            <div class="video-class-info">
                                <i class="fas fa-graduation-cap"></i>
                                <span>{{ $user['class'] }}</span>
                            </div>
                            <div class="video-progress">
                                <div class="progress-label">Progress</div>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: {{ $video['progress'] }}%;"></div>
                                </div>
                                <div class="progress-text">{{ $video['progress'] }}%</div>
                            </div>
                        </div>
        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 Mecha Learn. Semua hak dilindungi.</p>
    </footer>

    <script>
        // Tab functionality
        document.querySelectorAll('.tab-button').forEach(button => {
            button.addEventListener('click', function() {
                // Remove active class from all tabs
                document.querySelectorAll('.tab-button').forEach(tab => {
                    tab.classList.remove('active');
                });
                
                // Add active class to clicked tab
                this.classList.add('active');
            });
        });

        // Video card click functionality
        document.querySelectorAll('.video-card').forEach(card => {
            card.addEventListener('click', function() {
                // Add click animation
                this.style.transform = 'scale(0.98)';
                setTimeout(() => {
                    this.style.transform = '';
                }, 150);
            });
        });

        // Button click animations
        document.querySelectorAll('.btn-primary, .btn-secondary').forEach(button => {
            button.addEventListener('click', function() {
                this.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    this.style.transform = '';
                }, 150);
            });
        });
    </script>
</body>
</html>

