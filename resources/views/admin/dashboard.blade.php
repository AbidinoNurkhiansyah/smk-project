<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Guru - SMK Project</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <!-- Mobile Menu Button -->
    <button class="mobile-menu-btn" id="mobileMenuBtn">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h4><i class="fas fa-graduation-cap"></i> Guru Panel</h4>
        </div>
        <nav class="sidebar-nav">
            <a href="{{ route('admin.dashboard') }}" class="nav-link active">
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
            <a href="{{ route('admin.analytics') }}" class="nav-link">
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
                <h2>Dashboard Guru</h2>
                
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

            <!-- Charts -->
            <div class="row mb-4">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="fas fa-chart-bar"></i> Performa per Kelas</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="performanceChart" style="max-height: 400px;" data-performance='@json($performanceData)'></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="fas fa-chart-pie"></i> Clustering Siswa</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="clusteringChart" style="max-height: 300px;" data-clustering='@json($clusteringData)'></canvas>
                            <div class="mt-3">
                                <div class="clustering-stats d-flex justify-content-around flex-wrap gap-3">
                                    <div class="stat-item text-center">
                                        <div class="stat-value text-success" style="font-size: 1.5rem; font-weight: 700; margin-bottom: 0.25rem;">
                                            {{ $clusteringData['clusteringStats']['rajin_count'] ?? $clusteringData['rajin'] }}
                                        </div>
                                        <div class="stat-label" style="font-size: 0.85rem; color: #6c757d;">Siswa Rajin</div>
                                    </div>
                                    <div class="stat-item text-center">
                                        <div class="stat-value text-warning" style="font-size: 1.5rem; font-weight: 700; margin-bottom: 0.25rem;">
                                            {{ $clusteringData['clusteringStats']['butuh_bimbingan_count'] ?? $clusteringData['butuh_bimbingan'] }}
                                        </div>
                                        <div class="stat-label" style="font-size: 0.85rem; color: #6c757d;">Butuh Bimbingan</div>
                                    </div>
                                    <div class="stat-item text-center">
                                        <div class="stat-value text-primary" style="font-size: 1.5rem; font-weight: 700; margin-bottom: 0.25rem;">
                                            {{ $clusteringData['clusteringStats']['total_students'] ?? $clusteringData['total'] }}
                                        </div>
                                        <div class="stat-label" style="font-size: 0.85rem; color: #6c757d;">Total Siswa</div>
                                    </div>
                                    <div class="stat-item text-center">
                                        <div class="stat-value" style="font-size: 1.5rem; font-weight: 700; margin-bottom: 0.25rem; color: #28a745;">
                                            {{ $clusteringData['clusteringStats']['rajin_percentage'] ?? 0 }}%
                                        </div>
                                        <div class="stat-label" style="font-size: 0.85rem; color: #6c757d;">Persentase Rajin</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sidebar toggle functionality
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.querySelector('.sidebar-toggle');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');

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

        // Performance Bar Chart
        const performanceCtx = document.getElementById('performanceChart');
        if (performanceCtx) {
            const performanceDataStr = performanceCtx.getAttribute('data-performance');
            const performanceData = performanceDataStr ? JSON.parse(performanceDataStr) : [];
            const labels = performanceData.map(function(item) { return item.class_name; });
            const scores = performanceData.map(function(item) {
                const score = parseFloat(item.avg_score) || 0;
                // Only show score if there's actual data (students who took quizzes)
                return score > 0 ? parseFloat(score.toFixed(2)) : 0;
            });

            new Chart(performanceCtx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Rata-rata Skor (%)',
                        data: scores,
                        backgroundColor: scores.map(score => score > 0 ? 'rgba(220, 38, 38, 0.8)' : 'rgba(200, 200, 200, 0.3)'),
                        borderColor: scores.map(score => score > 0 ? 'rgba(220, 38, 38, 1)' : 'rgba(200, 200, 200, 0.5)'),
                        borderWidth: 2,
                        borderRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const score = context.parsed.y;
                                    if (score === 0) {
                                        return 'Belum ada data quiz';
                                    }
                                    return 'Rata-rata Skor: ' + score + '%';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            ticks: {
                                callback: function(value) {
                                    return value + '%';
                                }
                            }
                        }
                    }
                }
            });
        }

        // Clustering Donut Chart
        const clusteringCtx = document.getElementById('clusteringChart');
        if (clusteringCtx) {
            const clusteringDataStr = clusteringCtx.getAttribute('data-clustering');
            const clusteringData = clusteringDataStr ? JSON.parse(clusteringDataStr) : {};
            // Support both old and new data structure
            const rajin = clusteringData.clusteringStats ? clusteringData.clusteringStats.rajin_count : (clusteringData.rajin || 0);
            const butuhBimbingan = clusteringData.clusteringStats ? clusteringData.clusteringStats.butuh_bimbingan_count : (clusteringData.butuh_bimbingan || 0);
            const total = clusteringData.clusteringStats ? clusteringData.clusteringStats.total_students : (clusteringData.total || 1);

            new Chart(clusteringCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Siswa Rajin', 'Butuh Bimbingan'],
                    datasets: [{
                        data: [rajin, butuhBimbingan],
                        backgroundColor: [
                            'rgba(40, 167, 69, 0.8)',
                            'rgba(255, 193, 7, 0.8)'
                        ],
                        borderColor: [
                            'rgba(40, 167, 69, 1)',
                            'rgba(255, 193, 7, 1)'
                        ],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.parsed || 0;
                                    const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                    return label + ': ' + value + ' (' + percentage + '%)';
                                }
                            }
                        }
                    }
                }
            });
        }

        // Mobile Menu Toggle (using mobileMenuBtn)
        if (mobileMenuBtn && sidebar && sidebarOverlay) {
            mobileMenuBtn.addEventListener('click', function() {
                sidebar.classList.toggle('show');
                sidebarOverlay.classList.toggle('show');
                // Prevent body scroll when sidebar is open
                if (sidebar.classList.contains('show')) {
                    document.body.style.overflow = 'hidden';
                } else {
                    document.body.style.overflow = '';
                }
            });

            // Close sidebar when clicking on nav link (mobile)
            const navLinks = document.querySelectorAll('.sidebar-nav .nav-link');
            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth <= 768) {
                        sidebar.classList.remove('show');
                        sidebarOverlay.classList.remove('show');
                        document.body.style.overflow = '';
                    }
                });
            });
        }
    </script>
</body>
</html>
