<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clustering - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Analytics Page Styles */
        .analytics-card {
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            border: none;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
            overflow: hidden;
        }

        .analytics-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        }

        .analytics-card .card-header {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            color: white;
            border-radius: 12px 12px 0 0;
            padding: 1.25rem;
            border: none;
        }

        .analytics-card .card-header h5 {
            margin: 0;
            font-weight: 600;
            font-size: 1.1rem;
        }

        .analytics-card .card-header h5 i {
            margin-right: 10px;
        }

        .analytics-card .card-body {
            padding: 1.5rem;
        }

        /* Chart Container */
        .chart-container {
            position: relative;
            height: 350px;
            padding: 15px;
        }

        /* Popular Videos */
        .popular-item {
            display: flex;
            align-items: flex-start;
            gap: 15px;
            padding: 15px;
            margin-bottom: 10px;
            background: #f8f9fa;
            border-radius: 10px;
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }

        .popular-item:hover {
            background: #fff;
            border-left-color: #dc2626;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transform: translateX(5px);
        }

        .popular-item .rank {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 16px;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(220, 38, 38, 0.3);
        }

        .popular-item .content h6 {
            margin: 0 0 8px 0;
            font-weight: 600;
            color: #333;
            font-size: 0.95rem;
            line-height: 1.4;
        }

        .popular-item .content p {
            margin: 0;
            font-size: 0.85rem;
            color: #6c757d;
        }

        /* Leaderboard Table */
        .leaderboard-table thead {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            color: white;
        }

        .leaderboard-table thead th {
            border: none;
            padding: 1rem;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }

        .leaderboard-table tbody tr {
            transition: all 0.3s ease;
        }

        .leaderboard-table tbody tr:hover {
            background: #f8f9fa;
            transform: scale(1.01);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .rank-badge {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 45px;
            height: 45px;
        }

        .rank-badge i {
            font-size: 1.5rem;
        }

        .rank-number {
            font-weight: 700;
            font-size: 1.1rem;
            color: #333;
            background: #f0f0f0;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .refresh-indicator {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 0.75rem;
            color: #6c757d;
        }

        /* Clustering Styles */
        .clustering-stats {
            display: flex;
            align-items: center;
            justify-content: flex-end;
        }

        .stat-item {
            text-align: center;
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }

        .stat-label {
            font-size: 0.85rem;
            color: #6c757d;
        }

        .clustering-table {
            margin: 0;
        }

        .clustering-table thead {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            color: white;
        }

        .clustering-table thead th {
            border: none;
            padding: 1rem;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }

        .clustering-table tbody tr {
            transition: all 0.3s ease;
        }

        .clustering-table tbody tr:hover {
            background: #f8f9fa;
            transform: scale(1.01);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .clustering-table tbody td {
            padding: 1rem;
            vertical-align: middle;
        }

        .nav-tabs {
            border-bottom: 2px solid #e9ecef;
        }

        .nav-tabs .nav-link {
            border: none;
            color: #6c757d;
            font-weight: 500;
            padding: 0.75rem 1.5rem;
            transition: all 0.3s ease;
        }

        .nav-tabs .nav-link:hover {
            color: #dc2626;
            border-color: transparent;
        }

        .nav-tabs .nav-link.active {
            color: #dc2626;
            background: transparent;
            border-bottom: 3px solid #dc2626;
            font-weight: 600;
        }

        .progress {
            border-radius: 10px;
            overflow: hidden;
        }

        .progress-bar {
            font-size: 0.75rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h4><i class="fas fa-graduation-cap"></i> Admin Panel</h4>
        </div>
        <nav class="sidebar-nav">
            <a href="{{ route('admin.dashboard') }}" class="nav-link">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('admin.videos') }}" class="nav-link">
                <i class="fas fa-video"></i>
                <span>Kelola Video</span>
            </a>
            <a href="{{ route('admin.teacher-quiz') }}" class="nav-link">
                <i class="fas fa-chalkboard-teacher"></i>
                <span>Kelola Quiz</span>
            </a>
            <a href="{{ route('admin.students') }}" class="nav-link">
                <i class="fas fa-users"></i>
                <span>Data Siswa</span>
            </a>
            <a href="{{ route('admin.analytics') }}" class="nav-link active">
                <i class="fas fa-chart-bar"></i>
                <span>Clustering</span>
            </a>
            <a href="{{ route('admin.quiz-analytics') }}" class="nav-link">
                <i class="fas fa-chart-line"></i>
                <span>Analitik Kuis</span>
            </a>
            <a href="{{ route('admin.leaderboard') }}" class="nav-link">
                <i class="fas fa-trophy"></i>
                <span>Leaderboard</span>
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
                <h2>Clustering</h2>
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
            <!-- Clustering Section -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card analytics-card">
                        <div class="card-header">
                            <h5><i class="fas fa-users-cog"></i> Klastering Siswa</h5>
                        </div>
                        <div class="card-body">
                            <!-- Filter -->
                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <form method="GET" action="{{ route('admin.analytics') }}" class="d-flex gap-2">
                                        <select name="class_id" id="classFilter" class="form-select" onchange="this.form.submit()">
                                            <option value="all" {{ $selectedClass == 'all' ? 'selected' : '' }}>Semua Kelas</option>
                                            @foreach($classes as $class)
                                                <option value="{{ $class->class_id }}" {{ $selectedClass == $class->class_id ? 'selected' : '' }}>
                                                    {{ $class->class_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </form>
                                </div>
                                <div class="col-md-8">
                                    <div class="clustering-stats d-flex gap-4">
                                        <div class="stat-item">
                                            <div class="stat-value text-success">{{ $clusteringStats['rajin_count'] }}</div>
                                            <div class="stat-label">Siswa Rajin</div>
                                        </div>
                                        <div class="stat-item">
                                            <div class="stat-value text-warning">{{ $clusteringStats['butuh_bimbingan_count'] }}</div>
                                            <div class="stat-label">Butuh Bimbingan</div>
                                        </div>
                                        <div class="stat-item">
                                            <div class="stat-value text-primary">{{ $clusteringStats['total_students'] }}</div>
                                            <div class="stat-label">Total Siswa</div>
                                        </div>
                                        <div class="stat-item">
                                            <div class="stat-value" style="color: #28a745;">{{ $clusteringStats['rajin_percentage'] }}%</div>
                                            <div class="stat-label">Persentase Rajin</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Clustering Tabs -->
                            <ul class="nav nav-tabs mb-3" id="clusteringTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="rajin-tab" data-bs-toggle="tab" data-bs-target="#rajin" type="button" role="tab">
                                        <i class="fas fa-star text-warning"></i> Siswa Rajin ({{ $clusteringData['rajin']->count() }})
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="butuh-bimbingan-tab" data-bs-toggle="tab" data-bs-target="#butuh-bimbingan" type="button" role="tab">
                                        <i class="fas fa-exclamation-triangle text-danger"></i> Butuh Bimbingan ({{ $clusteringData['butuh_bimbingan']->count() }})
                                    </button>
                                </li>
                            </ul>

                            <!-- Clustering Content -->
                            <div class="tab-content" id="clusteringTabContent">
                                <!-- Rajin Tab -->
                                <div class="tab-pane fade show active" id="rajin" role="tabpanel">
                                    @if($clusteringData['rajin']->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-hover clustering-table">
                                                <thead>
                                                    <tr>
                                                        <th>Nama Siswa</th>
                                                        <th>Kelas</th>
                                                        <th>Video Ditonton</th>
                                                        <th>Progress Video</th>
                                                        <th>Quiz Dikerjakan</th>
                                                        <th>Akurasi Quiz</th>
                                                        <th>Total Poin</th>
                                                        <th>Skor Aktivitas</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($clusteringData['rajin']->sortByDesc('activity_score') as $student)
                                                        <tr>
                                                            <td>
                                                                <strong>{{ $student['user_name'] }}</strong>
                                                                <br><small class="text-muted">{{ $student['email'] }}</small>
                                                            </td>
                                                            <td>
                                                                <span class="badge bg-primary">{{ $student['class_name'] }}</span>
                                                            </td>
                                                            <td>
                                                                {{ $student['total_videos_watched'] }}
                                                                @if(isset($student['total_videos_in_class']) && $student['total_videos_in_class'] > 0)
                                                                    <br><small class="text-muted">dari {{ $student['total_videos_in_class'] }} video</small>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <div class="progress" style="height: 20px;">
                                                                    <div class="progress-bar bg-success" style="width: {{ min($student['avg_video_progress'], 100) }}%">
                                                                        {{ $student['avg_video_progress'] }}%
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>{{ $student['total_quizzes_taken'] }}</td>
                                                            <td>
                                                                @if(isset($student['total_quiz_answers']) && $student['total_quiz_answers'] > 0)
                                                                    {{ round(($student['total_correct_answers'] / $student['total_quiz_answers']) * 100, 1) }}%
                                                                @else
                                                                    -
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <strong style="color: #28a745;">{{ number_format($student['total_points']) }}</strong>
                                                            </td>
                                                            <td>
                                                                <span class="badge bg-success">{{ $student['activity_score'] }}/100</span>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="text-center py-4">
                                            <i class="fas fa-users" style="font-size: 3rem; color: #ddd; margin-bottom: 1rem; display: block;"></i>
                                            <p class="text-muted">Tidak ada siswa dalam kategori ini</p>
                                        </div>
                                    @endif
                                </div>

                                <!-- Butuh Bimbingan Tab -->
                                <div class="tab-pane fade" id="butuh-bimbingan" role="tabpanel">
                                    @if($clusteringData['butuh_bimbingan']->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-hover clustering-table">
                                                <thead>
                                                    <tr>
                                                        <th>Nama Siswa</th>
                                                        <th>Kelas</th>
                                                        <th>Video Ditonton</th>
                                                        <th>Progress Video</th>
                                                        <th>Quiz Dikerjakan</th>
                                                        <th>Akurasi Quiz</th>
                                                        <th>Total Poin</th>
                                                        <th>Skor Aktivitas</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($clusteringData['butuh_bimbingan']->sortBy('activity_score') as $student)
                                                        <tr>
                                                            <td>
                                                                <strong>{{ $student['user_name'] }}</strong>
                                                                <br><small class="text-muted">{{ $student['email'] }}</small>
                                                            </td>
                                                            <td>
                                                                <span class="badge bg-primary">{{ $student['class_name'] }}</span>
                                                            </td>
                                                            <td>{{ $student['total_videos_watched'] }}</td>
                                                            <td>
                                                                <div class="progress" style="height: 20px;">
                                                                    <div class="progress-bar bg-warning" style="width: {{ $student['avg_video_progress'] }}%">
                                                                        {{ $student['avg_video_progress'] }}%
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>{{ $student['total_quizzes_taken'] }}</td>
                                                            <td>
                                                                @if(isset($student['total_quiz_answers']) && $student['total_quiz_answers'] > 0)
                                                                    {{ round(($student['total_correct_answers'] / $student['total_quiz_answers']) * 100, 1) }}%
                                                                @else
                                                                    -
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <strong style="color: #dc2626;">{{ number_format($student['total_points']) }}</strong>
                                                            </td>
                                                            <td>
                                                                <span class="badge bg-warning">{{ $student['activity_score'] }}/100</span>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="text-center py-4">
                                            <i class="fas fa-users" style="font-size: 3rem; color: #ddd; margin-bottom: 1rem; display: block;"></i>
                                            <p class="text-muted">Tidak ada siswa dalam kategori ini</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Sidebar Overlay for Mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sidebar toggle functionality
        const sidebar = document.querySelector('.sidebar');
        const sidebarToggle = document.querySelector('.sidebar-toggle');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        // Function to check if mobile
        function isMobile() {
            return window.innerWidth <= 768;
        }

        // Toggle sidebar
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                if (isMobile()) {
                    // Mobile: show/hide sidebar with overlay
                    sidebar.classList.toggle('show');
                    sidebarOverlay.classList.toggle('show');
                    // Prevent body scroll when sidebar is open
                    if (sidebar.classList.contains('show')) {
                        document.body.style.overflow = 'hidden';
                    } else {
                        document.body.style.overflow = '';
                    }
                } else {
                    // Desktop: collapse/expand sidebar
                    sidebar.classList.toggle('collapsed');
                }
            });
        }

        // Close sidebar when clicking overlay (mobile only)
        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', function() {
                if (isMobile()) {
                    sidebar.classList.remove('show');
                    sidebarOverlay.classList.remove('show');
                    document.body.style.overflow = '';
                }
            });
        }

        // Close sidebar when clicking outside (mobile only)
        document.addEventListener('click', function(e) {
            if (isMobile() && sidebar && sidebarOverlay) {
                const isClickInsideSidebar = sidebar.contains(e.target);
                const isClickOnToggle = sidebarToggle && sidebarToggle.contains(e.target);
                
                if (!isClickInsideSidebar && !isClickOnToggle && sidebar.classList.contains('show')) {
                    sidebar.classList.remove('show');
                    sidebarOverlay.classList.remove('show');
                    document.body.style.overflow = '';
                }
            }
        });

        // Handle window resize
        let resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                // Reset sidebar state on breakpoint change
                if (!isMobile()) {
                    sidebar.classList.remove('show');
                    sidebarOverlay.classList.remove('show');
                    document.body.style.overflow = '';
                }
            }, 250);
        });

        // Close sidebar on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && sidebar && sidebar.classList.contains('show')) {
                sidebar.classList.remove('show');
                sidebarOverlay.classList.remove('show');
                document.body.style.overflow = '';
            }
        });

    </script>
</body>
</html>
