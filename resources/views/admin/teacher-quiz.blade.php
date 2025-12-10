<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Quiz - Admin Panel</title>
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
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('admin.videos') }}" class="nav-link">
                <i class="fas fa-video"></i>
                <span>Kelola Video</span>
            </a>
            <a href="{{ route('admin.teacher-quiz') }}" class="nav-link active">
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
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

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
                            <a href="{{ route('admin.teacher-quiz') }}" 
                               class="class-badge {{ $selectedClass === 'all' ? 'active' : '' }}">
                                <i class="fas fa-globe me-1"></i>
                                Semua Kelas
                            </a>
                            @foreach($classes as $class)
                            <a href="{{ route('admin.teacher-quiz', ['class_id' => $class->class_id]) }}" 
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

        <!-- Action Buttons -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h4><i class="fas fa-list"></i> Daftar Quiz</h4>
                    <a href="{{ route('admin.create-teacher-quiz') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Buat Quiz Baru
                    </a>
                </div>
            </div>
        </div>

        <!-- Search and Table -->
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5 class="mb-0"><i class="fas fa-table"></i> Daftar Quiz</h5>
                    </div>
                    <div class="col-md-6">
                        <input type="text" id="searchQuiz" class="form-control" placeholder="Cari quiz (judul, kelas, pembuat...)">
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="quizTable">
                        <thead>
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th>Judul Quiz</th>
                                <th style="width: 120px;">Kelas</th>
                                <th style="width: 100px;">Soal</th>
                                <th style="width: 100px;">Waktu</th>
                                <th style="width: 100px;">Tingkat</th>
                                <th style="width: 100px;">Pembuat</th>
                                <th style="width: 100px;">Status</th>
                                <th style="width: 150px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($quizzes as $index => $quiz)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <strong>{{ $quiz->quiz_title }}</strong>
                                        @if($quiz->quiz_description)
                                            <br><small class="text-muted">{{ Str::limit($quiz->quiz_description, 50) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $quiz->class_name }}</span>
                                    </td>
                                    <td>{{ $quiz->total_questions }}</td>
                                    <td>{{ $quiz->time_limit }} m</td>
                                    <td>
                                        <span class="badge 
                                            @if($quiz->difficulty == 'mudah') bg-success
                                            @elseif($quiz->difficulty == 'sedang') bg-warning
                                            @else bg-danger
                                            @endif">
                                            {{ ucfirst($quiz->difficulty) }}
                                        </span>
                                    </td>
                                    <td><small>{{ $quiz->created_by_name }}</small></td>
                                    <td>
                                        <span class="badge {{ $quiz->is_active ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $quiz->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('admin.show-teacher-quiz', $quiz->id) }}" 
                                               class="btn btn-outline-info" title="Lihat">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.edit-teacher-quiz', $quiz->id) }}" 
                                               class="btn btn-outline-primary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.delete-teacher-quiz', $quiz->id) }}" 
                                                  method="POST" class="d-inline" 
                                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus quiz ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        <i class="fas fa-chalkboard-teacher fa-2x text-muted mb-2 d-block"></i>
                                        <p class="text-muted mb-0">Belum ada quiz</p>
                                        <a href="{{ route('admin.create-teacher-quiz') }}" class="btn btn-primary btn-sm mt-2">
                                            <i class="fas fa-plus"></i> Buat Quiz Pertama
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
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

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);

        // Search functionality
        const searchInput = document.getElementById('searchQuiz');
        const quizTable = document.getElementById('quizTable');
        
        if (searchInput && quizTable) {
            searchInput.addEventListener('keyup', function() {
                const searchTerm = this.value.toLowerCase();
                const rows = quizTable.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
                
                for (let i = 0; i < rows.length; i++) {
                    const row = rows[i];
                    const text = row.textContent || row.innerText;
                    
                    if (text.toLowerCase().indexOf(searchTerm) > -1) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                }
            });
        }
    </script>
</body>
</html>
