<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analitik - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
            <a href="{{ route('admin.students') }}" class="nav-link">
                <i class="fas fa-users"></i> Data Siswa
            </a>
            <a href="{{ route('admin.analytics') }}" class="nav-link active">
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
                <h2>Analitik</h2>
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
                <div class="date-range">
                    <i class="fas fa-calendar"></i>
                    <span>Semester Ini</span>
                </div>
            </div>
        </header>

        <!-- Content -->
        <div class="content">
            <!-- Class Progress Chart -->
            <div class="row mb-4">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="fas fa-chart-bar"></i> Progress per Kelas</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="classProgressChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="fas fa-fire"></i> Video Populer</h5>
                        </div>
                        <div class="card-body">
                            @forelse($popularVideos as $index => $video)
                                <div class="popular-item">
                                    <div class="rank">{{ $index + 1 }}</div>
                                    <div class="content">
                                        <h6>{{ $video->judul }}</h6>
                                        <p class="text-muted">{{ $video->view_count }} kali dilihat</p>
                                    </div>
                                </div>
                            @empty
                                <p class="text-muted">Belum ada data</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Leaderboard -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="fas fa-trophy"></i> Leaderboard</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Peringkat</th>
                                            <th>Nama Siswa</th>
                                            <th>Kelas</th>
                                            <th>Total Poin</th>
                                            <th>Badge</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($leaderboard as $index => $student)
                                            <tr>
                                                <td>
                                                    <div class="rank-badge">
                                                        @if($index < 3)
                                                            <i class="fas fa-medal text-{{ $index == 0 ? 'warning' : ($index == 1 ? 'secondary' : 'warning') }}"></i>
                                                        @else
                                                            <span class="rank-number">{{ $index + 1 }}</span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar">
                                                            <i class="fas fa-user"></i>
                                                        </div>
                                                        <div class="ms-2">
                                                            <strong>{{ $student->user_name }}</strong>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-primary">{{ $student->class_name }}</span>
                                                </td>
                                                <td>
                                                    <strong>{{ $student->total_point }}</strong>
                                                </td>
                                                <td>
                                                    @if($index == 0)
                                                        <span class="badge bg-warning">🥇 Juara 1</span>
                                                    @elseif($index == 1)
                                                        <span class="badge bg-secondary">🥈 Juara 2</span>
                                                    @elseif($index == 2)
                                                        <span class="badge bg-warning">🥉 Juara 3</span>
                                                    @else
                                                        <span class="badge bg-info">Peserta</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center">Belum ada data leaderboard</td>
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

        // Class Progress Chart
        const ctx = document.getElementById('classProgressChart').getContext('2d');
        const classProgressChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [
                    @foreach($classProgress as $class)
                        '{{ $class->class_name }}',
                    @endforeach
                ],
                datasets: [{
                    label: 'Progress Rata-rata (%)',
                    data: [
                        @foreach($classProgress as $class)
                            {{ round($class->avg_progress) }},
                        @endforeach
                    ],
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.8)',
                        'rgba(255, 99, 132, 0.8)',
                        'rgba(255, 205, 86, 0.8)',
                        'rgba(75, 192, 192, 0.8)',
                        'rgba(153, 102, 255, 0.8)'
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(255, 205, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    </script>
</body>
</html>
