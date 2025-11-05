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
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            <a href="{{ route('admin.videos') }}" class="nav-link">
                <i class="fas fa-video"></i> Kelola Video
            </a>
            <a href="{{ route('admin.teacher-quiz') }}" class="nav-link active">
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

        <!-- Quiz List -->
        <div class="row">
            @forelse($quizzes as $quiz)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-graduation-cap"></i>
                                {{ $quiz->class_name }}
                            </h5>
                            <span class="badge {{ $quiz->is_active ? 'bg-success' : 'bg-secondary' }}">
                                {{ $quiz->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </div>
                        <div class="card-body">
                            <h6 class="card-title">{{ $quiz->quiz_title }}</h6>
                            <p class="card-text text-muted">{{ Str::limit($quiz->quiz_description, 100) }}</p>
                            
                            <div class="row mb-3">
                                <div class="col-6">
                                    <small class="text-muted">
                                        <i class="fas fa-question-circle"></i> {{ $quiz->total_questions }} Soal
                                    </small>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">
                                        <i class="fas fa-clock"></i> {{ $quiz->time_limit }} Menit
                                    </small>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-6">
                                    <small class="text-muted">
                                        <i class="fas fa-star"></i> {{ $quiz->points_per_question }} Poin/Soal
                                    </small>
                                </div>
                                <div class="col-6">
                                    <span class="badge 
                                        @if($quiz->difficulty == 'mudah') bg-success
                                        @elseif($quiz->difficulty == 'sedang') bg-warning
                                        @else bg-danger
                                        @endif">
                                        {{ ucfirst($quiz->difficulty) }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <small class="text-muted">
                                    <i class="fas fa-user"></i> Dibuat oleh: {{ $quiz->created_by_name }}
                                </small>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="btn-group w-100" role="group">
                                <a href="{{ route('admin.show-teacher-quiz', $quiz->id) }}" 
                                   class="btn btn-outline-info btn-sm" title="Lihat">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.edit-teacher-quiz', $quiz->id) }}" 
                                   class="btn btn-outline-primary btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.delete-teacher-quiz', $quiz->id) }}" 
                                      method="POST" class="d-inline" 
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus quiz ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <i class="fas fa-chalkboard-teacher fa-3x text-muted mb-3"></i>
                            <h4>Belum Ada Quiz</h4>
                            <p class="text-muted">Mulai dengan membuat quiz baru untuk kelas yang dipilih.</p>
                            <a href="{{ route('admin.create-teacher-quiz') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Buat Quiz Pertama
                            </a>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>
</body>
</html>
