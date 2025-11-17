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
                    <button class="btn-icon home-btn" title="Kembali ke Beranda" data-url="{{ route('welcome') }}">
                        <i class="fas fa-home"></i>
                    </button>
                    @if(session('role') === 'kajur' || session('role') === 'guru')
                        <button class="btn-icon" title="Admin Panel" data-url="{{ route('admin.dashboard') }}">
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
                                    <div class="progress-fill" data-progress="{{ $video['progress'] }}"></div>
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
        // Set progress bars from data attributes
        document.addEventListener('DOMContentLoaded', function() {
            var progressFills = document.querySelectorAll('.progress-fill[data-progress]');
            for (var i = 0; i < progressFills.length; i++) {
                var progress = progressFills[i].getAttribute('data-progress');
                progressFills[i].style.width = progress + '%';
            }
            
            // Handle button clicks with data-url
            var actionButtons = document.querySelectorAll('.user-actions .btn-icon[data-url]');
            for (var i = 0; i < actionButtons.length; i++) {
                actionButtons[i].addEventListener('click', function() {
                    var url = this.getAttribute('data-url');
                    if (url) {
                        window.location.href = url;
                    }
                });
            }
        });

        // Tab functionality
        var tabButtons = document.querySelectorAll('.tab-button');
        for (var i = 0; i < tabButtons.length; i++) {
            tabButtons[i].addEventListener('click', function() {
                // Remove active class from all tabs
                var allTabs = document.querySelectorAll('.tab-button');
                for (var j = 0; j < allTabs.length; j++) {
                    allTabs[j].classList.remove('active');
                }
                
                // Add active class to clicked tab
                this.classList.add('active');
            });
        }

        // Video card click functionality
        var videoCards = document.querySelectorAll('.video-card');
        for (var i = 0; i < videoCards.length; i++) {
            videoCards[i].addEventListener('click', function() {
                var card = this;
                // Add click animation
                card.style.transform = 'scale(0.98)';
                setTimeout(function() {
                    card.style.transform = '';
                }, 150);
            });
        }

        // Button click animations
        var buttons = document.querySelectorAll('.btn-primary, .btn-secondary');
        for (var i = 0; i < buttons.length; i++) {
            buttons[i].addEventListener('click', function() {
                var btn = this;
                btn.style.transform = 'scale(0.95)';
                setTimeout(function() {
                    btn.style.transform = '';
                }, 150);
            });
        }
    </script>
</body>
</html>

