<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboard - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <style>
        .leaderboard-card {
            background: white;
        }
        
        .class-filter {
            display: flex;
            gap: 1rem;
            align-items: center;
            flex-wrap: wrap;
        }
        
        .class-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            text-decoration: none;
            color: #666;
            background: #f8f9fa;
            border: 2px solid transparent;
            transition: all 0.3s ease;
            font-weight: 500;
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
            padding: 1rem;
            border-bottom: 1px solid #f0f0f0;
            transition: all 0.3s ease;
        }
        
        .leaderboard-item:hover {
            background: #f8f9fa;
        }
        
        .leaderboard-item:last-child {
            border-bottom: none;
        }
        
        .rank {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.2rem;
            margin-right: 1rem;
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
        }
        
        .student-info {
            flex: 1;
        }
        
        .student-name {
            font-weight: 600;
            color: #333;
            margin-bottom: 0.25rem;
        }
        
        .student-class {
            color: #666;
            font-size: 0.9rem;
        }
        
        .score-info {
            text-align: right;
        }
        
        .score {
            font-size: 1.5rem;
            font-weight: bold;
            color: #dc2626;
            margin-bottom: 0.25rem;
        }
        
        .score-label {
            color: #666;
            font-size: 0.9rem;
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
            <a href="{{ route('admin.leaderboard') }}" class="nav-link active">
                <i class="fas fa-trophy"></i> Leaderboard
            </a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Filter Section -->
        <div class="card filter-card">
            <div class="card-body">
                <h5 class="card-title mb-3">
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
        </div>

        <!-- Leaderboard Section -->
        <div class="card leaderboard-card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-trophy me-2"></i>
                    Leaderboard {{ $selectedClass !== 'all' ? '- ' . $classes->where('class_id', $selectedClass)->first()->class_name : '' }}
                </h5>
            </div>
            <div class="card-body p-0">
                @if($leaderboard->count() > 0)
                    @foreach($leaderboard as $index => $student)
                    <div class="leaderboard-item">
                        <div class="rank {{ $index === 0 ? 'first' : ($index === 1 ? 'second' : ($index === 2 ? 'third' : 'other')) }}">
                            {{ $index + 1 }}
                        </div>
                        <div class="student-info">
                            <div class="student-name">{{ $student->user_name }}</div>
                            <div class="student-class">{{ $student->class_name }}</div>
                        </div>
                        <div class="score-info">
                            <div class="score">{{ $student->total_points ?? 0 }}</div>
                            <div class="score-label">Poin</div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="empty-state">
                        <i class="fas fa-trophy"></i>
                        <h4>Belum Ada Data</h4>
                        <p>Belum ada siswa yang memiliki poin untuk ditampilkan di leaderboard.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>