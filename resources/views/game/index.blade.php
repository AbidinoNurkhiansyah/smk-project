<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <title>Quiz Pembelajaran - SMK Project</title>
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
                            <i class="fas fa-question-circle"></i>
                            Quiz Pembelajaran
                        </h1>
                    </div>
                    <div class="col-12 col-md-5">
                        <nav class="header-nav">
                            <a href="{{ route('video.index') }}" class="nav-btn">
                                <i class="fas fa-play-circle"></i>
                                <span>Video</span>
                            </a>
                            <a href="{{ route('game.index') }}" class="nav-btn active">
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
                                <i class="fas fa-question-circle"></i>
                            </div>
                            <div class="stat-content">
                                <h3>{{ $quizzes->count() }}</h3>
                                <p>Total Quiz</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-star"></i>
                            </div>
                            <div class="stat-content">
                                <h3>{{ DB::table('points')->where('user_id', session('user_id'))->value('total_point') ?? 0 }}</h3>
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
                                <h3>{{ $completedQuizzesCount ?? 0 }}</h3>
                                <p>Quiz Selesai</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-medal"></i>
                            </div>
                            <div class="stat-content">
                                <h3>{{ DB::table('users')->join('points', 'users.user_id', '=', 'points.user_id')->where('users.role', 'siswa')->where('points.total_point', '>', DB::table('points')->where('user_id', session('user_id'))->value('total_point') ?? 0)->count() + 1 }}</h3>
                                <p>Leaderboard</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Game List -->
                <div class="row">
                    <div class="col-12">
                        <div class="game-section">
                            <div class="section-header">
                                <h2><i class="fas fa-question-circle"></i> Quiz Pembelajaran</h2>
                            </div>
                            
                            <div class="row g-3">
                                @forelse($quizzes as $quiz)
                                <div class="col-12 col-md-6 col-lg-4">
                                    <div class="quiz-card {{ $quiz->is_completed ? 'quiz-completed' : '' }}">
                                        <div class="quiz-header">
                                            @if($quiz->is_completed)
                                            <div class="quiz-completed-badge">
                                                <i class="fas fa-check-circle"></i> Selesai
                                            </div>
                                            @endif
                                            <div class="quiz-title">
                                                <h3><i class="fas fa-brain"></i> {{ $quiz->quiz_title }}</h3>
                                            </div>
                                            <p class="quiz-subtitle">
                                                <i class="fas fa-graduation-cap"></i> 
                                                @if(isset($user->class_name) && $user->class_name)
                                                    Kelas {{ $user->class_name }}
                                                @else
                                                    Kelas TSM X
                                                @endif
                                                <span class="separator">•</span> 
                                                Tingkat {{ ucfirst($quiz->difficulty) }}
                                            </p>
                                            @if($quiz->quiz_description)
                                            <p class="quiz-description">{{ strlen($quiz->quiz_description) > 80 ? substr($quiz->quiz_description, 0, 80) . '...' : $quiz->quiz_description }}</p>
                                            @endif
                                            <div class="quiz-badges">
                                                <span class="badge bg-primary">
                                                    <i class="fas fa-question-circle"></i> {{ $quiz->total_questions }} Soal
                                                </span>
                                                <span class="badge bg-success">
                                                    <i class="fas fa-clock"></i> {{ $quiz->time_limit }} Menit
                                                </span>
                                                <span class="badge bg-warning">
                                                    <i class="fas fa-coins"></i> {{ $quiz->total_questions * $quiz->points_per_question }} Poin
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <div class="quiz-footer">
                                            @if($quiz->is_completed)
                                            <a href="{{ route('game.play', $quiz->id) }}" class="btn btn-success btn-lg btn-start-quiz w-100">
                                                <i class="fas fa-redo"></i> Kerjakan Lagi
                                            </a>
                                            @else
                                            <a href="{{ route('game.play', $quiz->id) }}" class="btn btn-primary btn-lg btn-start-quiz w-100">
                                                <i class="fas fa-play"></i> Mulai Quiz
                                            </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <div class="col-12">
                                    <div class="text-center py-5">
                                        <i class="fas fa-question-circle fa-3x text-muted mb-3"></i>
                                        <h4 class="text-muted">Belum ada quiz yang tersedia</h4>
                                        <p class="text-muted">Silakan hubungi guru untuk membuat quiz untuk kelas Anda.</p>
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
        // Add some interactive effects
        document.addEventListener('DOMContentLoaded', function() {
            // Animate stat cards
            const statCards = document.querySelectorAll('.stat-card');
            statCards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
                card.classList.add('animate-in');
            });

            // Add hover effects to game cards
            const gameCards = document.querySelectorAll('.game-card');
            gameCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-5px)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });
        });
    </script>
</body>
</html>
