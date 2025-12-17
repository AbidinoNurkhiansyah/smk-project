<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboard - Guru Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <style>
        .leaderboard-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .filter-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .class-filter {
            display: flex;
            gap: 0.75rem;
            align-items: center;
            flex-wrap: wrap;
        }
        
        .class-badge {
            padding: 0.5rem 1.25rem;
            border-radius: 20px;
            text-decoration: none;
            color: #666;
            background: #f8f9fa;
            border: 2px solid transparent;
            transition: all 0.3s ease;
            font-weight: 500;
            font-size: 0.9rem;
        }
        
        .class-badge:hover {
            background: #dc2626;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(220, 38, 38, 0.3);
        }
        
        .class-badge.active {
            background: #dc2626;
            color: white;
            border-color: #b91c1c;
        }
        
        .leaderboard-item {
            display: flex;
            align-items: center;
            padding: 1.25rem;
            border-bottom: 1px solid #f0f0f0;
            transition: all 0.3s ease;
        }
        
        .leaderboard-item:hover {
            background: #f8f9fa;
            transform: translateX(5px);
        }
        
        .leaderboard-item:last-child {
            border-bottom: none;
        }
        
        .rank {
            width: 55px;
            height: 55px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.3rem;
            margin-right: 1.25rem;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .rank.first {
            background: linear-gradient(135deg, #ffd700 0%, #ffed4e 100%);
            color: #b8860b;
        }
        
        .rank.second {
            background: linear-gradient(135deg, #c0c0c0 0%, #e5e5e5 100%);
            color: #666;
        }
        
        .rank.third {
            background: linear-gradient(135deg, #cd7f32 0%, #daa520 100%);
            color: #8b4513;
        }
        
        .rank.other {
            background: #f8f9fa;
            color: #666;
            border: 2px solid #e9ecef;
        }
        
        .student-info {
            flex: 1;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .student-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 1.2rem;
            flex-shrink: 0;
        }
        
        .student-details {
            flex: 1;
        }
        
        .student-name {
            font-weight: 600;
            color: #333;
            margin-bottom: 0.25rem;
            font-size: 1.05rem;
        }
        
        .student-class {
            color: #666;
            font-size: 0.9rem;
        }
        
        .student-email {
            color: #999;
            font-size: 0.85rem;
            margin-top: 0.15rem;
        }
        
        .score-info {
            text-align: right;
            margin-left: 1rem;
        }
        
        .score {
            font-size: 1.75rem;
            font-weight: bold;
            color: #dc2626;
            margin-bottom: 0.25rem;
        }
        
        .score-label {
            color: #666;
            font-size: 0.85rem;
        }
        
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: #666;
        }
        
        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            color: #dee2e6;
        }
        
        .card-header {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            color: white;
            border-radius: 12px 12px 0 0;
            padding: 1.25rem;
        }
        
        .card-header h5 {
            margin: 0;
            font-weight: 600;
        }
    </style>
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
            <a href="{{ route('admin.analytics') }}" class="nav-link">
                <i class="fas fa-chart-bar"></i>
                <span>Clustering</span>
            </a>
            <a href="{{ route('admin.quiz-analytics') }}" class="nav-link">
                <i class="fas fa-chart-line"></i>
                <span>Analitik Kuis</span>
            </a>
            <a href="{{ route('admin.leaderboard') }}" class="nav-link active">
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
                <h2>Leaderboard</h2>
            </div>
            <div class="header-right">
                <div class="user-info">
                    <i class="fas fa-user-circle"></i>
                    <span>{{ session('user_name', 'Guru') }}</span>
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
            <!-- Filter Section -->
            <div class="filter-card">
                <h5 class="mb-3">
                    <i class="fas fa-filter me-2"></i>
                    Filter Kelas
                </h5>
                <div class="class-filter">
                    <a href="{{ route('admin.leaderboard') }}" 
                       class="class-badge {{ $selectedClass === 'all' ? 'active' : '' }}">
                        <i class="fas fa-globe me-1"></i>
                        Semua Kelas
                    </a>
                    @foreach($classes as $class)
                    <a href="{{ route('admin.leaderboard', ['class_id' => $class->class_id]) }}" 
                       class="class-badge {{ $selectedClass == $class->class_id ? 'active' : '' }}">
                        <i class="fas fa-graduation-cap me-1"></i>
                        {{ $class->class_name }}
                    </a>
                    @endforeach
                </div>
            </div>

            <!-- Leaderboard Section -->
            <div class="card leaderboard-card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-trophy me-2"></i>
                        Leaderboard {{ $selectedClass !== 'all' ? '- ' . $classes->where('class_id', $selectedClass)->first()->class_name : 'Semua Kelas' }}
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if($leaderboard->count() > 0)
                        @foreach($leaderboard as $student)
                        <div class="leaderboard-item">
                            <div class="rank {{ $student->ranking === 1 ? 'first' : ($student->ranking === 2 ? 'second' : ($student->ranking === 3 ? 'third' : 'other')) }}">
                                @if($student->ranking <= 3)
                                    <i class="fas fa-trophy"></i>
                                @else
                                    {{ $student->ranking }}
                                @endif
                            </div>
                            <div class="student-info">
                                <div class="student-avatar">
                                    {{ strtoupper(substr($student->user_name, 0, 1)) }}
                                </div>
                                <div class="student-details">
                                    <div class="student-name">{{ $student->user_name }}</div>
                                    <div class="student-class">
                                        <i class="fas fa-graduation-cap me-1"></i>{{ $student->class_name }}
                                    </div>
                                    @if(isset($student->email))
                                    <div class="student-email">{{ $student->email }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="score-info">
                                <div class="score">{{ number_format($student->total_point) }}</div>
                                <div class="score-label">Poin</div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="empty-state">
                            <i class="fas fa-trophy"></i>
                            <h4>Belum Ada Data</h4>
                            <p class="text-muted">Belum ada siswa yang memiliki poin untuk ditampilkan di leaderboard.</p>
                        </div>
                    @endif
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
