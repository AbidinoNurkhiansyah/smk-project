<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Quiz - Kuis Teknik Sepeda Motor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/game.css') }}">
</head>
<body>
    <div class="game-result-container">
        <!-- Header -->
        <header class="game-result-header">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h1 class="result-title">
                            <i class="fas fa-trophy"></i>
                            Hasil Quiz
                        </h1>
                    </div>
                    <div class="col-md-6 text-end">
                        <a href="{{ route('game.index') }}" class="btn btn-outline-light">
                            <i class="fas fa-home"></i> Kembali ke Game
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <!-- Result Content -->
        <main class="game-result-main">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <!-- Main Result Card -->
                        <div class="result-card-main animate-in">
                            <div class="result-header-gradient">
                                <div class="result-icon-wrapper">
                                    @if($percentage >= 80)
                                        <i class="fas fa-trophy result-icon-gold"></i>
                                    @elseif($percentage >= 60)
                                        <i class="fas fa-medal result-icon-silver"></i>
                                    @else
                                        <i class="fas fa-star result-icon-bronze"></i>
                                    @endif
                                </div>
                                <h2 class="result-status-title">
                                    @if($percentage >= 80)
                                        Selamat! Nilai Kamu Sangat Bagus! 🎉
                                    @elseif($percentage >= 60)
                                        Bagus! Terus Berlatih! 💪
                                    @else
                                        Jangan Menyerah! Terus Belajar! 📚
                                    @endif
                                </h2>
                                <p class="result-subtitle">Quiz: {{ $quiz->quiz_title ?? 'Quiz' }}</p>
                            </div>
                            
                            <div class="result-content-body">
                                <!-- Score Circle -->
                                <div class="score-circle-wrapper">
                                    <div class="score-circle" data-percentage="{{ $percentage }}">
                                        <svg class="score-circle-svg" viewBox="0 0 200 200">
                                            <defs>
                                                <linearGradient id="scoreGradient" x1="0%" y1="0%" x2="100%" y2="0%">
                                                    <stop offset="0%" style="stop-color:#c12727;stop-opacity:1" />
                                                    <stop offset="100%" style="stop-color:#ff9100;stop-opacity:1" />
                                                </linearGradient>
                                            </defs>
                                            <circle class="score-circle-bg" cx="100" cy="100" r="90"></circle>
                                            <circle class="score-circle-progress" cx="100" cy="100" r="90" 
                                                    data-percentage="{{ $percentage }}"></circle>
                                        </svg>
                                        <div class="score-circle-content">
                                            <span class="score-percentage">{{ $percentage }}%</span>
                                            <span class="score-grade">Grade {{ $grade }}</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Stats Grid -->
                                <div class="result-stats-grid">
                                    <div class="stat-card-item">
                                        <div class="stat-icon-box stat-icon-total">
                                            <i class="fas fa-list-alt"></i>
                                        </div>
                                        <div class="stat-content-box">
                                            <h3 class="stat-number">{{ $totalQuestions }}</h3>
                                            <p class="stat-label">Total Soal</p>
                                        </div>
                                    </div>
                                    
                                    <div class="stat-card-item">
                                        <div class="stat-icon-box stat-icon-correct">
                                            <i class="fas fa-check-circle"></i>
                                        </div>
                                        <div class="stat-content-box">
                                            <h3 class="stat-number text-success">{{ $correctAnswers }}</h3>
                                            <p class="stat-label">Jawaban Benar</p>
                                        </div>
                                    </div>
                                    
                                    <div class="stat-card-item">
                                        <div class="stat-icon-box stat-icon-wrong">
                                            <i class="fas fa-times-circle"></i>
                                        </div>
                                        <div class="stat-content-box">
                                            <h3 class="stat-number text-danger">{{ $totalQuestions - $correctAnswers }}</h3>
                                            <p class="stat-label">Jawaban Salah</p>
                                        </div>
                                    </div>
                                    
                                    <div class="stat-card-item">
                                        <div class="stat-icon-box stat-icon-points">
                                            <i class="fas fa-coins"></i>
                                        </div>
                                        <div class="stat-content-box">
                                            <h3 class="stat-number points-highlight">{{ $totalScore }}</h3>
                                            <p class="stat-label">Poin Didapat</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Progress Bar -->
                                <div class="result-progress-section">
                                    <div class="progress-header-text">
                                        <span>Nilai Kamu</span>
                                        <span class="progress-percentage-text">{{ $percentage }}%</span>
                                    </div>
                                    <div class="result-progress-bar">
                                        <div class="result-progress-fill" data-percentage="{{ $percentage }}"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="result-actions-wrapper">
                            <div class="result-actions-grid">
                                <a href="{{ route('game.play', $quiz->id) }}" class="action-btn action-btn-primary">
                                    <i class="fas fa-redo"></i>
                                    <span>Coba Lagi</span>
                                </a>
                                <a href="{{ route('game.index') }}" class="action-btn action-btn-secondary">
                                    <i class="fas fa-list"></i>
                                    <span>Game Lainnya</span>
                                </a>
                                <a href="{{ route('game.leaderboard') }}" class="action-btn action-btn-accent">
                                    <i class="fas fa-trophy"></i>
                                    <span>Leaderboard</span>
                                </a>
                            </div>
                        </div>

                        <!-- User Stats Info -->
                        <div class="user-stats-card">
                            <h3 class="user-stats-title">
                                <i class="fas fa-chart-line"></i> Statistik Kamu
                            </h3>
                            <div class="user-stats-grid">
                                <div class="user-stat-item">
                                    <div class="user-stat-icon user-stat-points">
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <div class="user-stat-content">
                                        <h4>{{ DB::table('points')->where('user_id', session('user_id'))->value('total_point') ?? 0 }}</h4>
                                        <p>Total Poin</p>
                                    </div>
                                </div>
                                <div class="user-stat-item">
                                    <div class="user-stat-icon user-stat-completed">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                    <div class="user-stat-content">
                                        <h4>{{ DB::table('user_challenges')->where('user_id', session('user_id'))->where('is_correct', 1)->count() }}</h4>
                                        <p>Game Selesai</p>
                                    </div>
                                </div>
                                <div class="user-stat-item">
                                    <div class="user-stat-icon user-stat-ranking">
                                        <i class="fas fa-medal"></i>
                                    </div>
                                    <div class="user-stat-content">
                                        <h4>#{{ DB::table('users')->join('points', 'users.user_id', '=', 'points.user_id')->where('users.role', 'siswa')->where('points.total_point', '>', DB::table('points')->where('user_id', session('user_id'))->value('total_point') ?? 0)->count() + 1 }}</h4>
                                        <p>Ranking</p>
                                    </div>
                                </div>
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
            var percentage = parseFloat('{{ $percentage }}');
            var isGoodScore = percentage >= 80;
            
            // Add celebration animation for good scores
            var resultCard = document.querySelector('.result-card-main');
            if (isGoodScore && resultCard) {
                resultCard.classList.add('celebrate');
                
                // Add confetti effect (simple version)
                setTimeout(function() {
                    resultCard.style.animation = 'bounce 0.6s ease-in-out';
                }, 500);
            }
            
            // Animate progress bar
            var progressFill = document.querySelector('.result-progress-fill');
            if (progressFill) {
                var progressPercentage = progressFill.getAttribute('data-percentage');
                setTimeout(function() {
                    progressFill.style.width = progressPercentage + '%';
                }, 300);
            }
            
            // Animate score circle
            var scoreCircle = document.querySelector('.score-circle-progress');
            if (scoreCircle) {
                var circlePercentage = parseFloat(scoreCircle.getAttribute('data-percentage'));
                var offset = 565.48 - (565.48 * circlePercentage / 100);
                setTimeout(function() {
                    scoreCircle.style.strokeDashoffset = offset;
                }, 500);
            }
        });
    </script>
</body>
</html>
