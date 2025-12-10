<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <title>Leaderboard - SMK Project</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/game.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="leaderboard-container">
        <!-- Header -->
        <header class="game-header">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-12 col-md-4">
                        <h1 class="game-title">
                            <i class="fas fa-trophy"></i>
                            Leaderboard
                        </h1>
                    </div>
                    <div class="col-12 col-md-5">
                        <nav class="header-nav">
                            <a href="{{ route('video.index') }}" class="nav-btn">
                                <i class="fas fa-play-circle"></i>
                                <span>Video</span>
                            </a>
                            <a href="{{ route('game.index') }}" class="nav-btn">
                                <i class="fas fa-clipboard-question"></i>
                                <span>Quiz</span>
                            </a>
                            <a href="{{ route('game.leaderboard') }}" class="nav-btn active">
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
                                <h3>{{ $totalQuizzes }}</h3>
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
                                <h3>{{ $completedQuizzes }}</h3>
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
                                <h3>{{ $userRanking }}</h3>
                                <p>Leaderboard</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Top 3 Podium -->
                @if($leaderboard->count() >= 3)
                    <div class="podium-section">
                        <h2 class="section-title">Top 3 Pemain Terbaik</h2>
                        <div class="podium">
                            @foreach($leaderboard->take(3) as $index => $player)
                                <div class="podium-item position-{{ $index + 1 }}">
                                    <div class="podium-rank">
                                        @if($index == 0)
                                            <i class="fas fa-crown gold"></i>
                                        @elseif($index == 1)
                                            <i class="fas fa-medal silver"></i>
                                        @else
                                            <i class="fas fa-award bronze"></i>
                                        @endif
                                    </div>
                                    <div class="podium-player">
                                        <div class="player-avatar">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div class="player-info">
                                            <h4>{{ $player->user_name }}</h4>
                                            <p>{{ $player->class_name }}</p>
                                        </div>
                                    </div>
                                    <div class="podium-score">
                                        <h3>{{ $player->total_point }}</h3>
                                        <p>Poin</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Full Leaderboard -->
                <div class="game-section">
                    <div class="section-header">
                        <h2><i class="fas fa-trophy"></i> Daftar Lengkap</h2>
                        <div class="leaderboard-stats">
                            <span class="stat-item">
                                <i class="fas fa-users"></i>
                                {{ $leaderboard->count() }} Pemain
                            </span>
                        </div>
                    </div>

                    <div class="leaderboard-table">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Ranking</th>
                                        <th>Nama</th>
                                        <th>Kelas</th>
                                        <th>Total Poin</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($leaderboard as $index => $player)
                                        <tr class="{{ $player->user_id == session('user_id') ? 'current-user' : '' }}">
                                            <td>
                                                <div class="rank-badge rank-{{ $index + 1 }}">
                                                    @if($index < 3)
                                                        @if($index == 0)
                                                            <i class="fas fa-crown"></i>
                                                        @elseif($index == 1)
                                                            <i class="fas fa-medal"></i>
                                                        @else
                                                            <i class="fas fa-award"></i>
                                                        @endif
                                                    @else
                                                        {{ $index + 1 }}
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div class="player-cell">
                                                    <div class="player-avatar-small">
                                                        <i class="fas fa-user"></i>
                                                    </div>
                                                    <div class="player-details">
                                                        <strong>
                                                            {{ $player->user_name }}
                                                            @if($player->user_id == session('user_id'))
                                                                <span class="you-badge">Anda</span>
                                                            @endif
                                                        </strong>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $player->class_name }}</td>
                                            <td>
                                                <span class="points-display">
                                                    <i class="fas fa-star"></i>
                                                    {{ $player->total_point }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($index < 3)
                                                    <span class="badge" style="background: #ffc107; color: #000; font-weight: 600; padding: 0.4rem 0.75rem; border-radius: 8px;">Top 3</span>
                                                @elseif($index < 10)
                                                    <span class="badge" style="background: #17a2b8; color: #fff; font-weight: 600; padding: 0.4rem 0.75rem; border-radius: 8px;">Top 10</span>
                                                @else
                                                    <span class="badge" style="background: #6c757d; color: #fff; font-weight: 500; padding: 0.4rem 0.75rem; border-radius: 8px;">Pemain</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add animation to leaderboard rows
            const rows = document.querySelectorAll('tbody tr');
            rows.forEach((row, index) => {
                row.style.animationDelay = `${index * 0.1}s`;
                row.classList.add('animate-in');
            });

            // Highlight current user
            const currentUserRow = document.querySelector('.current-user');
            if (currentUserRow) {
                currentUserRow.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        });
    </script>
</body>
</html>
