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
                    <div class="col-lg-8">
                        <div class="result-card">
                            <div class="result-icon">
                                <i class="fas fa-trophy"></i>
                            </div>
                            
                            <div class="result-content">
                                <h2 class="result-status">
                                    Quiz Selesai!
                                </h2>
                                
                                <div class="result-details">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="detail-item">
                                                <strong>Total Soal:</strong>
                                                <p>{{ $totalQuestions }} soal</p>
                                            </div>
                                            
                                            <div class="detail-item">
                                                <strong>Jawaban Benar:</strong>
                                                <p class="text-success">{{ $correctAnswers }} soal</p>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="detail-item">
                                                <strong>Nilai:</strong>
                                                <p class="text-primary">{{ $percentage }}%</p>
                                            </div>
                                            
                                            <div class="detail-item">
                                                <strong>Grade:</strong>
                                                <p class="grade-badge grade-{{ strtolower($grade) }}">{{ $grade }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="detail-item">
                                        <strong>Total Poin:</strong>
                                        <span class="points-earned">{{ $totalScore }} Poin</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="result-actions">
                            <div class="row">
                                <div class="col-md-6">
                                    <a href="{{ route('game.play', 'class_1_quiz') }}" class="btn btn-primary btn-lg btn-block">
                                        <i class="fas fa-redo"></i> Coba Lagi
                                    </a>
                                </div>
                                <div class="col-md-6">
                                    <a href="{{ route('game.index') }}" class="btn btn-success btn-lg btn-block">
                                        <i class="fas fa-list"></i> Game Lainnya
                                    </a>
                                </div>
                            </div>
                            
                            <div class="row mt-3">
                                <div class="col-12 text-center">
                                    <a href="{{ route('game.leaderboard') }}" class="btn btn-outline-warning">
                                        <i class="fas fa-trophy"></i> Lihat Leaderboard
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Progress Info -->
                        <div class="progress-info">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="progress-stat">
                                        <i class="fas fa-star"></i>
                                        <h4>{{ DB::table('points')->where('user_id', session('user_id'))->value('total_point') ?? 0 }}</h4>
                                        <p>Total Poin</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="progress-stat">
                                        <i class="fas fa-check-circle"></i>
                                        <h4>{{ DB::table('user_challenges')->where('user_id', session('user_id'))->where('is_correct', 1)->count() }}</h4>
                                        <p>Game Selesai</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="progress-stat">
                                        <i class="fas fa-medal"></i>
                                        <h4>{{ DB::table('users')->join('points', 'users.user_id', '=', 'points.user_id')->where('users.role', 'siswa')->where('points.total_point', '>', DB::table('points')->where('user_id', session('user_id'))->value('total_point') ?? 0)->count() + 1 }}</h4>
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
            // Add celebration animation for good scores
            if ({{ $percentage >= 70 ? 'true' : 'false' }}) {
                const resultCard = document.querySelector('.result-card');
                resultCard.classList.add('celebrate');
                
                // Add confetti effect (simple version)
                setTimeout(() => {
                    resultCard.style.animation = 'bounce 0.6s ease-in-out';
                }, 500);
            }
        });
    </script>
</body>
</html>
