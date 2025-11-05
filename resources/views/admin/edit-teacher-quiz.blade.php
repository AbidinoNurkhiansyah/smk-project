<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Quiz - {{ $quiz->quiz_title }}</title>
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
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="card">
                                <div class="card-header">
                                    <h5><i class="fas fa-edit"></i> Edit Informasi Quiz</h5>
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="{{ route('admin.update-teacher-quiz', $quiz->id) }}">
                                        @csrf
                                        @method('PUT')
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="quiz_title" class="form-label">Judul Quiz</label>
                                                    <input type="text" class="form-control" id="quiz_title" name="quiz_title" value="{{ old('quiz_title', $quiz->quiz_title) }}" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="class_id" class="form-label">Kelas</label>
                                                    <select class="form-select" id="class_id" name="class_id" required>
                                                        <option value="1" {{ $quiz->class_id == 1 ? 'selected' : '' }}>TSM X</option>
                                                        <option value="2" {{ $quiz->class_id == 2 ? 'selected' : '' }}>TSM XI</option>
                                                        <option value="3" {{ $quiz->class_id == 3 ? 'selected' : '' }}>TSM XII</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="quiz_description" class="form-label">Deskripsi Quiz</label>
                                            <textarea class="form-control" id="quiz_description" name="quiz_description" rows="3">{{ old('quiz_description', $quiz->quiz_description) }}</textarea>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="difficulty" class="form-label">Tingkat Kesulitan</label>
                                                    <select class="form-select" id="difficulty" name="difficulty" required>
                                                        <option value="mudah" {{ $quiz->difficulty == 'mudah' ? 'selected' : '' }}>Mudah</option>
                                                        <option value="sedang" {{ $quiz->difficulty == 'sedang' ? 'selected' : '' }}>Sedang</option>
                                                        <option value="sulit" {{ $quiz->difficulty == 'sulit' ? 'selected' : '' }}>Sulit</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="time_limit" class="form-label">Waktu (menit)</label>
                                                    <input type="number" class="form-control" id="time_limit" name="time_limit" value="{{ old('time_limit', $quiz->time_limit) }}" min="1" required>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label for="points_per_question" class="form-label">Poin per Soal</label>
                                                    <input type="number" class="form-control" id="points_per_question" name="points_per_question" value="{{ old('points_per_question', $quiz->points_per_question) }}" min="1" required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ $quiz->is_active ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_active">
                                                    Quiz Aktif
                                                </label>
                                            </div>
                                        </div>

                                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                            <a href="{{ route('admin.show-teacher-quiz', $quiz->id) }}" class="btn btn-outline-secondary me-md-2">Batal</a>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-save"></i> Simpan Perubahan
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5><i class="fas fa-info-circle"></i> Informasi Quiz</h5>
                                </div>
                                <div class="card-body">
                                    <div class="quiz-info">
                                        <div class="info-item">
                                            <i class="fas fa-question-circle text-primary"></i>
                                            <span>Total Soal: {{ $quiz->total_questions }}</span>
                                        </div>
                                        <div class="info-item">
                                            <i class="fas fa-coins text-warning"></i>
                                            <span>Total Poin: {{ $quiz->total_questions * $quiz->points_per_question }}</span>
                                        </div>
                                        <div class="info-item">
                                            <i class="fas fa-user text-secondary"></i>
                                            <span>Dibuat oleh: {{ $quiz->created_by_name }}</span>
                                        </div>
                                        <div class="info-item">
                                            <i class="fas fa-calendar text-info"></i>
                                            <span>Dibuat: {{ \Carbon\Carbon::parse($quiz->created_at)->format('d M Y H:i') }}</span>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
