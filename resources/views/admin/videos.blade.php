<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Video - Admin</title>
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
            <a href="{{ route('admin.videos') }}" class="nav-link active">
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
                <h2>Kelola Video</h2>
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
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addVideoModal">
                    <i class="fas fa-plus"></i> Tambah Video
                </button>
            </div>
        </header>

        <!-- Content -->
        <div class="content">
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
                                <a href="{{ route('admin.videos') }}" 
                                   class="class-badge {{ $selectedClass === 'all' ? 'active' : '' }}">
                                    <i class="fas fa-globe me-1"></i>
                                    Semua Kelas
                                </a>
                                @foreach($classes as $class)
                                <a href="{{ route('admin.videos', ['class_id' => $class->class_id]) }}" 
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

            <!-- Videos Table -->
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-video"></i> Daftar Video</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">No</th>
                                    <th>Judul</th>
                                    <th style="width: 120px;">Kelas</th>
                                    <th style="width: 100px;">Durasi</th>
                                    <th style="width: 100px;">URL</th>
                                    <th style="width: 100px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($videos as $index => $video)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td><small>{{ $video->judul }}</small></td>
                                        <td>
                                            <span class="badge bg-info" style="font-size: 0.75rem;">{{ $video->class_name }}</span>
                                        </td>
                                        <td><small>{{ $video->duration ?? 'N/A' }} m</small></td>
                                        <td>
                                            <a href="{{ $video->video_url }}" target="_blank" class="btn btn-xs btn-outline-primary" style="padding: 0.2rem 0.4rem; font-size: 0.75rem;">
                                                <i class="fas fa-external-link-alt"></i>
                                            </a>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group" style="gap: 0.2rem;">
                                                <a href="{{ route('admin.edit-video', $video->video_id) }}" class="btn btn-sm btn-warning" style="padding: 0.2rem 0.4rem; font-size: 0.75rem;">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.delete-video', $video->video_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus video ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" style="padding: 0.2rem 0.4rem; font-size: 0.75rem;">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Belum ada video</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Video Modal -->
    <div class="modal fade" id="addVideoModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Video Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.add-video') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="judul" class="form-label">Judul Video</label>
                                    <input type="text" class="form-control" id="judul" name="judul" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="class_id" class="form-label">Kelas <span class="text-danger">*</span></label>
                                    <select class="form-select" id="class_id" name="class_id" required>
                                        <option value="">Pilih Kelas</option>
                                        <option value="all">Semua Kelas</option>
                                        @foreach($classes as $class)
                                            <option value="{{ $class->class_id }}">{{ $class->class_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="video_url" class="form-label">URL Video</label>
                                    <input type="url" class="form-control" id="video_url" name="video_url" required>
                                    <small class="text-muted">Masukkan URL video (YouTube atau Google Drive)</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="duration" class="form-label">Durasi <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="duration" name="duration" pattern="[0-9]+(\.[0-9]{1,2})?" placeholder="Contoh: 5.26" required>
                                    <small class="text-muted">Format: menit.detik (contoh: 5.26 = 5 menit 26 detik, 10.30 = 10 menit 30 detik)</small>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Video</button>
                    </div>
                </form>
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
