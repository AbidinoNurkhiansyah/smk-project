<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <title>Video Pembelajaran - SMK Project</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/game.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="game-container">
        <!-- Header -->
        <header class="game-header">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-12 col-md-4">
                        <h1 class="game-title">
                            <i class="fas fa-play-circle"></i>
                            Video Pembelajaran
                        </h1>
                    </div>
                    <div class="col-12 col-md-5">
                        <nav class="header-nav">
                            <a href="{{ route('video.index') }}" class="nav-btn active">
                                <i class="fas fa-play-circle"></i>
                                <span>Video</span>
                            </a>
                            <a href="{{ route('game.index') }}" class="nav-btn">
                                <i class="fas fa-clipboard-question"></i>
                                <span>Quiz</span>
                            </a>
                            <a href="{{ route('game.leaderboard') }}" class="nav-btn">
                                <i class="fas fa-trophy"></i>
                                <span>Ranking</span>
                            </a>
                        </nav>
                    </div>
                    <div class="col-12 col-md-3 text-end">
                        <div class="user-info">
                            <span class="user-name d-none d-md-inline">{{ session('user_name') }}</span>
                            <a href="{{ route('dashboard') }}" class="btn btn-outline-light btn-sm">
                                <i class="fas fa-home"></i> <span class="d-none d-sm-inline">Dashboard</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="game-main">
            <div class="container">
                <!-- Stats Cards -->
                <div class="row mb-3 g-3 mt-4">
                    <div class="col-6 col-md-3">
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-video"></i>
                            </div>
                            <div class="stat-content">
                                <h3>{{ $totalVideos }}</h3>
                                <p>Total Video</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-star"></i>
                            </div>
                            <div class="stat-content">
                                <h3>{{ $totalPoints }}</h3>
                                <p>Total Poin</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="stat-content">
                                <h3>{{ $completedVideos }}</h3>
                                <p>Video Selesai</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-medal"></i>
                            </div>
                            <div class="stat-content">
                                <h3>{{ $userRanking }}</h3>
                                <p>Leaderboard</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Video List -->
                <div class="row">
                    <div class="col-12">
                        <div class="game-section">
                            <div class="section-header">
                                <h2><i class="fas fa-video"></i> Video Pembelajaran</h2>
                            </div>
                            
                            <div class="row g-3">
                                @forelse($videoData as $video)
                                <div class="col-12 col-md-6 col-lg-4">
                                    <div class="quiz-card">
                                        <div class="quiz-header">
                                            <div class="quiz-title">
                                                <h3><i class="fas fa-play-circle"></i> {{ $video['title'] }}</h3>
                                            </div>
                                            <p class="quiz-subtitle">Kelas {{ $user['class'] }}</p>
                                            <div class="quiz-badges">
                                                <span class="badge bg-primary">
                                                    <i class="fas fa-clock"></i> {{ $video['duration'] }}
                                                </span>
                                                @if($video['is_completed'])
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check"></i> Selesai
                                                </span>
                                                @elseif($video['is_in_progress'])
                                                <span class="badge bg-warning">
                                                    <i class="fas fa-spinner"></i> {{ $video['progress'] }}%
                                                </span>
                                                @else
                                                <span class="badge bg-secondary">
                                                    <i class="fas fa-play"></i> Belum Dimulai
                                                </span>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="quiz-footer">
                                            @if($video['is_completed'])
                                                <a href="{{ route('video.show', $video['id']) }}" class="btn btn-primary btn-lg btn-start-quiz w-100">
                                                    <i class="fas fa-redo"></i> Review Video
                                                </a>
                                            @elseif($video['is_in_progress'])
                                                <a href="{{ route('video.show', $video['id']) }}" class="btn btn-primary btn-lg btn-start-quiz w-100">
                                                    <i class="fas fa-play"></i> Lanjutkan
                                                </a>
                                            @else
                                                <a href="{{ route('video.show', $video['id']) }}" class="btn btn-primary btn-lg btn-start-quiz w-100">
                                                    <i class="fas fa-play"></i> Mulai Menonton
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <div class="col-12">
                                    <div class="text-center py-5">
                                        <i class="fas fa-video fa-3x text-muted mb-3"></i>
                                        <h4 class="text-muted">Belum ada video yang tersedia</h4>
                                        <p class="text-muted">Silakan hubungi guru untuk menambahkan video untuk kelas Anda.</p>
                                    </div>
                                </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Animate stat cards
            const statCards = document.querySelectorAll('.stat-card');
            statCards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
                card.classList.add('animate-in');
            });
        });
    </script>
</body>
</html>
