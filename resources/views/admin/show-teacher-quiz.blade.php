<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Quiz - {{ $quiz->quiz_title }}</title>
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
                    <!-- Quiz Info Card -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5><i class="fas fa-info-circle"></i> Informasi Quiz</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h4>{{ $quiz->quiz_title }}</h4>
                                            <p class="text-muted">{{ $quiz->quiz_description }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="quiz-stats">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <div class="stat-item">
                                                            <i class="fas fa-graduation-cap text-primary"></i>
                                                            <span>Kelas: {{ $quiz->class_name }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="stat-item">
                                                            <i class="fas fa-star text-warning"></i>
                                                            <span>Tingkat: {{ ucfirst($quiz->difficulty) }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="stat-item">
                                                            <i class="fas fa-question-circle text-info"></i>
                                                            <span>Jumlah Soal: {{ $quiz->total_questions }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="stat-item">
                                                            <i class="fas fa-clock text-success"></i>
                                                            <span>Waktu: {{ $quiz->time_limit }} menit</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="stat-item">
                                                            <i class="fas fa-coins text-warning"></i>
                                                            <span>Poin per Soal: {{ $quiz->points_per_question }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="stat-item">
                                                            <i class="fas fa-user text-secondary"></i>
                                                            <span>Dibuat oleh: {{ $quiz->created_by_name }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Questions List -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5><i class="fas fa-list"></i> Daftar Soal ({{ $questions->count() }} soal)</h5>
                                </div>
                                <div class="card-body">
                                    @forelse($questions as $index => $question)
                                    <div class="question-item mb-4">
                                        <div class="question-header">
                                            <h6>Soal {{ $index + 1 }} <span class="badge bg-primary">{{ $question->points }} poin</span></h6>
                                        </div>
                                        <div class="question-content">
                                            <p class="question-text">{{ $question->question }}</p>
                                            @if($question->image)
                                                <div class="mb-3">
                                                    <img src="{{ Storage::url($question->image) }}" alt="Gambar soal" class="img-fluid rounded" style="max-width: 500px; max-height: 400px;">
                                                </div>
                                            @endif
                                            <div class="options-list">
                                                @if(isset($allOptions[$question->id]))
                                                    @foreach($allOptions[$question->id] as $option)
                                                        <div class="option-item {{ $option->option_label === $question->correct_answer ? 'correct-answer' : '' }}">
                                                            <span class="option-label">{{ $option->option_label }}.</span>
                                                            <span class="option-text">{{ $option->option_text }}</span>
                                                            @if($option->option_label === $question->correct_answer)
                                                                <i class="fas fa-check-circle text-success ms-2"></i>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    @empty
                                    <div class="text-center py-4">
                                        <i class="fas fa-question-circle fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">Belum ada soal</h5>
                                        <p class="text-muted">Silakan tambahkan soal untuk quiz ini.</p>
                                    </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
