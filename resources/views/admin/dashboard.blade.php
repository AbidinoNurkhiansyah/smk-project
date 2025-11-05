<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - SMK Project</title>
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
            <a href="{{ route('admin.dashboard') }}" class="nav-link active">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            <a href="{{ route('admin.videos') }}" class="nav-link">
                <i class="fas fa-video"></i> Kelola Video
            </a>
            <a href="{{ route('admin.teacher-quiz') }}" class="nav-link">
                <i class="fas fa-chalkboard-teacher"></i> Kelola Quiz
            </a>
            <a href="{{ route('admin.students') }}" class="nav-link">
                <i class="fas fa-users"></i> Data Siswa
            </a>
            <a href="{{ route('admin.analytics') }}" class="nav-link">
                <i class="fas fa-chart-bar"></i> Analitik
            </a>
            <a href="{{ route('admin.quiz-analytics') }}" class="nav-link">
                <i class="fas fa-chart-line"></i> Analitik Kuis
            </a>
            <a href="{{ route('admin.leaderboard') }}" class="nav-link">
                <i class="fas fa-trophy"></i> Leaderboard
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
                <h2>Dashboard Admin</h2>
            </div>
            <div class="header-right">
                <div class="user-info">
                    <i class="fas fa-user-circle"></i>
                    <span>{{ session('user_name', 'Admin') }}</span>
                </div>
                <div class="user-actions">
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
            </div>
        </header>

        <!-- Content -->
        <div class="content">
            <!-- Class Filter -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title mb-3">
                                <i class="fas fa-filter me-2"></i>
                                Filter Kelas
                            </h5>
                            <div class="class-filter">
                                <a href="{{ route('admin.dashboard') }}" 
                                   class="class-badge {{ $selectedClass === 'all' ? 'active' : '' }}">
                                    <i class="fas fa-globe me-1"></i>
                                    Semua Kelas
                                </a>
                                @foreach($classes as $class)
                                <a href="{{ route('admin.dashboard', ['class_id' => $class->class_id]) }}" 
                                   class="class-badge {{ $selectedClass == $class->class_id ? 'active' : '' }}">
                                    <i class="fas fa-graduation-cap me-1"></i>
                                    {{ $class->class_name }}
                                </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-content">
                            <h3>{{ $totalStudents }}</h3>
                            <p>Total Siswa</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
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
                <div class="col-md-3">
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
                <div class="col-md-3">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-school"></i>
                        </div>
                        <div class="stat-content">
                            <h3>{{ $totalClasses }}</h3>
                            <p>Total Kelas</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activities -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="fas fa-video"></i> Video Terbaru</h5>
                        </div>
                        <div class="card-body">
                            @if($recentVideos->count() > 0)
                                @foreach($recentVideos as $video)
                                    <div class="activity-item">
                                        <div class="activity-icon">
                                            <i class="fas fa-play-circle"></i>
                                        </div>
                                        <div class="activity-content">
                                            <h6>{{ $video->judul }}</h6>
                                            <p class="text-muted">Video Pembelajaran</p>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-muted">Belum ada video</p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="fas fa-chart-line"></i> Progress Terbaru</h5>
                        </div>
                        <div class="card-body">
                            @if($recentProgress->count() > 0)
                                @foreach($recentProgress as $progress)
                                    <div class="activity-item">
                                        <div class="activity-icon">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div class="activity-content">
                                            <h6>{{ $progress->user_name }}</h6>
                                            <p class="text-muted">{{ $progress->judul }} - {{ $progress->progress }}%</p>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-muted">Belum ada progress</p>
                            @endif
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
