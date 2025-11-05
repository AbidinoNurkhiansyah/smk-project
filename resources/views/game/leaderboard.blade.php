<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboard - SMK Project</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/game.css') }}">
</head>
<body>
    <div class="leaderboard-container">
        <!-- Header -->
        <header class="leaderboard-header">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h1 class="leaderboard-title">
                            <i class="fas fa-trophy"></i>
                            Leaderboard
                        </h1>
                    </div>
                    <div class="col-md-6 text-end">
                        <a href="{{ route('game.index') }}" class="btn btn-outline-light">
                            <i class="fas fa-arrow-left"></i> Kembali ke Game
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="leaderboard-main">
            <div class="container">
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
                <div class="leaderboard-section">
                    <div class="section-header">
                        <h2 class="section-title">Daftar Lengkap</h2>
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
                                                        <strong>{{ $player->user_name }}</strong>
                                                        @if($player->user_id == session('user_id'))
                                                            <span class="you-badge">Anda</span>
                                                        @endif
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
                                                    <span class="badge bg-warning">Top 3</span>
                                                @elseif($index < 10)
                                                    <span class="badge bg-info">Top 10</span>
                                                @else
                                                    <span class="badge bg-secondary">Pemain</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="leaderboard-actions">
                    <div class="row">
                        <div class="col-md-6">
                            <a href="{{ route('game.index') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-gamepad"></i> Main Game
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('dashboard') }}" class="btn btn-outline-primary btn-lg">
                                <i class="fas fa-home"></i> Dashboard
                            </a>
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
