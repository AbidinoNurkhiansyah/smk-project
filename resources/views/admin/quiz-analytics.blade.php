<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analitik Kuis - Guru Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <style>
        .stats-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            border-left: 4px solid #dc2626;
            transition: transform 0.3s ease;
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
        }
        
        .stats-number {
            font-size: 2rem;
            font-weight: bold;
            color: #dc2626;
        }
        
        .stats-label {
            color: #666;
            font-size: 0.9rem;
            margin-top: 0.5rem;
        }
        
        .filter-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .quiz-item {
            border: 1px solid #e9ecef;
            border-radius: 10px;
            padding: 1.25rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
            background: white;
        }
        
        .quiz-item:hover {
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transform: translateY(-2px);
            border-color: #dc2626;
        }
        
        .score-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: bold;
            font-size: 0.9rem;
            display: inline-block;
        }
        
        .score-excellent {
            background: #d4edda;
            color: #155724;
        }
        
        .score-good {
            background: #d1ecf1;
            color: #0c5460;
        }
        
        .score-fair {
            background: #fff3cd;
            color: #856404;
        }
        
        .score-poor {
            background: #f8d7da;
            color: #721c24;
        }
        
        .student-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .student-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 1.1rem;
        }
        
        .quiz-details {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #e9ecef;
        }
        
        .detail-item {
            text-align: center;
        }
        
        .detail-value {
            font-weight: bold;
            color: #333;
            font-size: 1.1rem;
        }
        
        .detail-label {
            font-size: 0.8rem;
            color: #666;
            margin-top: 0.25rem;
        }
        
        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #666;
        }
        
        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            color: #dee2e6;
        }
        
        .btn-detail {
            background: #dc2626;
            color: white;
            border: none;
            padding: 0.5rem 1.25rem;
            border-radius: 6px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-detail:hover {
            background: #b91c1c;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(220, 38, 38, 0.3);
        }
        
        .quiz-title {
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
        }
        
        .student-name {
            font-weight: 600;
            color: #333;
            margin-bottom: 0.25rem;
        }
        
        .student-email {
            font-size: 0.85rem;
            color: #6c757d;
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
            <a href="{{ route('admin.quiz-analytics') }}" class="nav-link active">
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
                <h2>Analitik Kuis</h2>
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
            <!-- Filter Card -->
            <div class="filter-card">
                <h5 class="mb-3"><i class="fas fa-filter me-2"></i>Filter Data</h5>
                <form method="GET" action="{{ route('admin.quiz-analytics') }}">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Kelas</label>
                            <select name="class_id" class="form-select">
                                <option value="all" {{ $classId == 'all' ? 'selected' : '' }}>Semua Kelas</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->class_id }}" {{ $classId == $class->class_id ? 'selected' : '' }}>
                                        {{ $class->class_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Quiz</label>
                            <select name="quiz_id" class="form-select">
                                <option value="all" {{ $quizId == 'all' ? 'selected' : '' }}>Semua Quiz</option>
                                @foreach($quizzes as $quiz)
                                    <option value="{{ $quiz->id }}" {{ $quizId == $quiz->id ? 'selected' : '' }}>
                                        {{ $quiz->quiz_title }} ({{ $quiz->class_name }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Dari Tanggal</label>
                            <input type="date" name="date_from" class="form-control" value="{{ $dateFrom }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Sampai Tanggal</label>
                            <input type="date" name="date_to" class="form-control" value="{{ $dateTo }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search me-1"></i> Filter
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="stats-number">{{ number_format($stats['average_score'], 1) }}%</div>
                        <div class="stats-label">Rata-rata Skor</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="stats-number">{{ number_format($stats['highest_score'], 1) }}%</div>
                        <div class="stats-label">Skor Tertinggi</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="stats-number">{{ number_format($stats['lowest_score'], 1) }}%</div>
                        <div class="stats-label">Skor Terendah</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="stats-number">{{ $stats['total_attempts'] }}</div>
                        <div class="stats-label">Total Percobaan</div>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="stats-card">
                        <div class="stats-number">{{ $stats['total_students'] }}</div>
                        <div class="stats-label">Total Siswa yang Mengerjakan</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="stats-card">
                        <div class="stats-number">{{ $stats['total_quizzes'] }}</div>
                        <div class="stats-label">Total Quiz yang Dikerjakan</div>
                    </div>
                </div>
            </div>

            <!-- Quiz Results -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-list me-2"></i>Hasil Quiz Siswa</h5>
                </div>
                <div class="card-body">
                    @if($quizSummary->count() > 0)
                        @foreach($quizSummary as $summary)
                            @php
                                $scoreClass = 'score-poor';
                                if ($summary['score'] >= 80) {
                                    $scoreClass = 'score-excellent';
                                } elseif ($summary['score'] >= 70) {
                                    $scoreClass = 'score-good';
                                } elseif ($summary['score'] >= 60) {
                                    $scoreClass = 'score-fair';
                                }
                            @endphp
                            <div class="quiz-item">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div class="flex-grow-1">
                                        <div class="student-info mb-2">
                                            <div class="student-avatar">
                                                {{ strtoupper(substr($summary['user_name'], 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="student-name">{{ $summary['user_name'] }}</div>
                                                <div class="student-email">{{ $summary['email'] }}</div>
                                            </div>
                                        </div>
                                        <div class="quiz-title">
                                            <i class="fas fa-question-circle me-2"></i>{{ $summary['quiz_title'] }}
                                        </div>
                                        <div class="mt-2">
                                            <span class="badge bg-primary">{{ $summary['class_name'] }}</span>
                                            <span class="badge bg-secondary ms-2">
                                                <i class="fas fa-calendar me-1"></i>
                                                {{ \Carbon\Carbon::parse($summary['answered_at'])->format('d M Y H:i') }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <span class="score-badge {{ $scoreClass }}">
                                            {{ number_format($summary['score'], 1) }}%
                                        </span>
                                    </div>
                                </div>
                                <div class="quiz-details">
                                    <div class="detail-item">
                                        <div class="detail-value">{{ $summary['correct_answers'] }}</div>
                                        <div class="detail-label">Jawaban Benar</div>
                                    </div>
                                    <div class="detail-item">
                                        <div class="detail-value">{{ $summary['total_questions'] }}</div>
                                        <div class="detail-label">Total Soal</div>
                                    </div>
                                    <div class="detail-item">
                                        <div class="detail-value">
                                            {{ number_format(($summary['correct_answers'] / $summary['total_questions']) * 100, 1) }}%
                                        </div>
                                        <div class="detail-label">Akurasi</div>
                                    </div>
                                    <div class="detail-item">
                                        <a href="{{ route('admin.quiz-detail', ['userId' => $summary['user_id'], 'quizId' => $summary['quiz_id']]) }}" class="btn-detail">
                                            <i class="fas fa-eye me-1"></i>Detail
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="empty-state">
                            <i class="fas fa-clipboard-list"></i>
                            <h5>Tidak ada data quiz</h5>
                            <p class="text-muted">Belum ada siswa yang mengerjakan quiz dengan filter yang dipilih.</p>
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
