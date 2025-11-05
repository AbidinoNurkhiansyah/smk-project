<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analitik Kuis - Admin Panel</title>
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
            margin-bottom: 2rem;
        }
        
        .quiz-summary-card {
            background: white;
        }
        
        .quiz-item {
            border: 1px solid #e9ecef;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }
        
        .quiz-item:hover {
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }
        
        .score-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: bold;
            font-size: 0.9rem;
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
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }
        
        .quiz-details {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 1rem;
        }
        
        .detail-item {
            text-align: center;
        }
        
        .detail-value {
            font-weight: bold;
            color: #333;
        }
        
        .detail-label {
            font-size: 0.8rem;
            color: #666;
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
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }
        
        .btn-detail:hover {
            background: #b91c1c;
            color: white;
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
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            <a href="{{ route('admin.videos') }}" class="nav-link">
                <i class="fas fa-video"></i> Kelola Video
            </a>
            <a href="{{ route('admin.teacher-quiz') }}" class="nav-link">
                <i class="fas fa-chalkboard-teacher"></i> Kelola Quiz
            </a>
            <a href="{{ route('admin.students') }}" class="nav-link">
                <i class="fas fa-users"></i> Data Siswa
            </a>
            <a href="{{ route('admin.analytics') }}" class="nav-link">
                <i class="fas fa-chart-bar"></i> Analitik
            </a>
            <a href="{{ route('admin.quiz-analytics') }}" class="nav-link active">
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
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-chart-line me-2"></i> Analitik Kuis Siswa</h2>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-2">
                <div class="stats-card">
                    <div class="stats-number">{{ $stats['total_attempts'] }}</div>
                    <div class="stats-label">Total Percobaan</div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="stats-card">
                    <div class="stats-number">{{ number_format($stats['average_score'], 1) }}%</div>
                    <div class="stats-label">Rata-rata Skor</div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="stats-card">
                    <div class="stats-number">{{ number_format($stats['highest_score'], 1) }}%</div>
                    <div class="stats-label">Skor Tertinggi</div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="stats-card">
                    <div class="stats-number">{{ number_format($stats['lowest_score'], 1) }}%</div>
                    <div class="stats-label">Skor Terendah</div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="stats-card">
                    <div class="stats-number">{{ $stats['total_students'] }}</div>
                    <div class="stats-label">Total Siswa</div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="stats-card">
                    <div class="stats-number">{{ $stats['total_quizzes'] }}</div>
                    <div class="stats-label">Total Quiz</div>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="card filter-card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-filter me-2"></i>
                    Filter Data
                </h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('admin.quiz-analytics') }}">
                    <div class="row">
                        <div class="col-md-3">
                            <label class="form-label">Kelas</label>
                            <select name="class_id" class="form-select">
                                <option value="all" {{ $classId === 'all' ? 'selected' : '' }}>Semua Kelas</option>
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
                                <option value="all" {{ $quizId === 'all' ? 'selected' : '' }}>Semua Quiz</option>
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
        </div>

        <!-- Quiz Results -->
        <div class="card quiz-summary-card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-list me-2"></i>
                    Hasil Kuis Siswa
                </h5>
            </div>
            <div class="card-body">
                @if($quizSummary->count() > 0)
                    @foreach($quizSummary as $result)
                    <div class="quiz-item">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <div class="student-info">
                                    <div class="student-avatar">
                                        {{ substr($result['user_name'], 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold">{{ $result['user_name'] }}</div>
                                        <div class="text-muted small">{{ $result['class_name'] }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="fw-bold">{{ $result['quiz_title'] }}</div>
                                <div class="text-muted small">
                                    <i class="fas fa-clock me-1"></i>
                                    {{ \Carbon\Carbon::parse($result['answered_at'])->format('d M Y H:i') }}
                                </div>
                            </div>
                            <div class="col-md-2">
                                @php
                                    $scoreClass = 'score-poor';
                                    if ($result['score'] >= 80) $scoreClass = 'score-excellent';
                                    elseif ($result['score'] >= 70) $scoreClass = 'score-good';
                                    elseif ($result['score'] >= 60) $scoreClass = 'score-fair';
                                @endphp
                                <span class="score-badge {{ $scoreClass }}">
                                    {{ number_format($result['score'], 1) }}%
                                </span>
                            </div>
                            <div class="col-md-2">
                                <div class="quiz-details">
                                    <div class="detail-item">
                                        <div class="detail-value">{{ $result['correct_answers'] }}</div>
                                        <div class="detail-label">Benar</div>
                                    </div>
                                    <div class="detail-item">
                                        <div class="detail-value">{{ $result['total_questions'] }}</div>
                                        <div class="detail-label">Total</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <a href="{{ route('admin.quiz-detail', ['userId' => $result['user_id'], 'quizId' => $result['quiz_id']]) }}" 
                                   class="btn btn-detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="empty-state">
                        <i class="fas fa-chart-line"></i>
                        <h4>Belum Ada Data</h4>
                        <p>Belum ada siswa yang mengerjakan kuis atau data tidak ditemukan dengan filter yang dipilih.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
