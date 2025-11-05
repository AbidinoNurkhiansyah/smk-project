<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Progress Siswa - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h4><i class="fas fa-graduation-cap"></i> Admin Panel</h4>
        </div>
        <nav class="sidebar-nav">
            <a href="{{ route('admin.dashboard') }}" class="nav-link">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            <a href="{{ route('admin.videos') }}" class="nav-link">
                <i class="fas fa-video"></i> Kelola Video
            </a>
            <a href="{{ route('admin.teacher-quiz') }}" class="nav-link">
                <i class="fas fa-question-circle"></i> Kelola Quiz
            </a>
            <a href="{{ route('admin.students') }}" class="nav-link active">
                <i class="fas fa-users"></i> Data Siswa
            </a>
            <a href="{{ route('admin.analytics') }}" class="nav-link">
                <i class="fas fa-chart-bar"></i> Analitik
            </a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <header class="header">
            <div class="header-left">
                <button class="sidebar-toggle">
                    <i class="fas fa-bars"></i>
                </button>
                <h2>Progress Siswa</h2>
            </div>
            <div class="header-right">
                <div class="user-actions me-3">
                    <a href="{{ route('profile') }}" class="btn btn-outline-primary btn-sm me-2" title="Profil">
                        <i class="fas fa-user"></i>
                    </a>
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm" title="Logout" onclick="return confirm('Apakah Anda yakin ingin logout?')">
                            <i class="fas fa-sign-out-alt"></i>
                        </button>
                    </form>
                </div>
                <a href="{{ route('admin.students') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </header>

        <!-- Content -->
        <div class="content">
            <!-- Student Info -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-2">
                                    <div class="student-avatar">
                                        <i class="fas fa-user-circle"></i>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h4>{{ $student->user_name }}</h4>
                                    <p class="text-muted mb-1">{{ $student->email }}</p>
                                    <span class="badge bg-primary">{{ $student->class_name }}</span>
                                </div>
                                <div class="col-md-4 text-end">
                                    <div class="points-display">
                                        <h3>{{ $points->total_point ?? 0 }}</h3>
                                        <p class="text-muted">Total Poin</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Progress Statistics -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-video"></i>
                        </div>
                        <div class="stat-content">
                            <h3>{{ $completedVideos }}/{{ $totalVideos }}</h3>
                            <p>Video Selesai</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-gamepad"></i>
                        </div>
                        <div class="stat-content">
                            <h3>{{ $completedGames }}/{{ $totalGames }}</h3>
                            <p>Game Selesai</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-percentage"></i>
                        </div>
                        <div class="stat-content">
                            <h3>{{ $totalVideos > 0 ? round(($completedVideos / $totalVideos) * 100) : 0 }}%</h3>
                            <p>Progress Video</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-trophy"></i>
                        </div>
                        <div class="stat-content">
                            <h3>{{ $totalGames > 0 ? round(($completedGames / $totalGames) * 100) : 0 }}%</h3>
                            <p>Progress Game</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Video Progress -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="fas fa-video"></i> Progress Video</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Judul Video</th>
                                            <th>Durasi</th>
                                            <th>Progress</th>
                                            <th>Status</th>
                                            <th>Terakhir Diupdate</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($videoProgress as $index => $progress)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $progress->judul }}</td>
                                                <td>Video Pembelajaran</td>
                                                <td>
                                                    <div class="progress" style="height: 20px;">
                                                        <div class="progress-bar {{ $progress->progress == 100 ? 'bg-success' : ($progress->progress > 0 ? 'bg-warning' : 'bg-secondary') }}" 
                                                             style="width: {{ $progress->progress }}%">
                                                            {{ $progress->progress }}%
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($progress->progress == 100)
                                                        <span class="badge bg-success">Selesai</span>
                                                    @elseif($progress->progress > 0)
                                                        <span class="badge bg-warning">Sedang Berjalan</span>
                                                    @else
                                                        <span class="badge bg-secondary">Belum Dimulai</span>
                                                    @endif
                                                </td>
                                                <td>{{ \Carbon\Carbon::parse($progress->updated_at)->format('d M Y H:i') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">Belum ada progress video</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Game Progress -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="fas fa-gamepad"></i> Progress Game</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Judul Game</th>
                                            <th>Poin</th>
                                            <th>Status</th>
                                            <th>Skor</th>
                                            <th>Tanggal Selesai</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($gameProgress as $index => $progress)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $progress->question }}</td>
                                                <td>{{ $progress->point }}</td>
                                                <td>
                                                    @if($progress->is_correct)
                                                        <span class="badge bg-success">Benar</span>
                                                    @elseif($progress->is_correct === false)
                                                        <span class="badge bg-danger">Salah</span>
                                                    @else
                                                        <span class="badge bg-secondary">Belum Dikerjakan</span>
                                                    @endif
                                                </td>
                                                <td>{{ $progress->score ?? '-' }}</td>
                                                <td>
                                                    @if($progress->answered_at)
                                                        {{ \Carbon\Carbon::parse($progress->answered_at)->format('d M Y H:i') }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">Belum ada progress game</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sidebar toggle
        document.querySelector('.sidebar-toggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('collapsed');
        });
    </script>
</body>
</html>
