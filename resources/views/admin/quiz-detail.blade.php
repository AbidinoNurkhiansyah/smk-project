<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Kuis - {{ $user->user_name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <style>
        .student-header {
            background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
            color: white;
            padding: 2rem;
            border-radius: 15px;
            margin-bottom: 2rem;
        }
        
        .student-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: rgba(255,255,255,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }
        
        .score-display {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        
        .score-circle {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 2rem;
            font-weight: bold;
            color: white;
        }
        
        .score-excellent {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        }
        
        .score-good {
            background: linear-gradient(135deg, #17a2b8 0%, #6f42c1 100%);
        }
        
        .score-fair {
            background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
        }
        
        .score-poor {
            background: linear-gradient(135deg, #dc3545 0%, #e83e8c 100%);
        }
        
        .question-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            border-left: 4px solid #dc2626;
        }
        
        .question-text {
            font-weight: 600;
            color: #333;
            margin-bottom: 1rem;
        }
        
        .option-item {
            padding: 0.75rem;
            margin-bottom: 0.5rem;
            border-radius: 8px;
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }
        
        .option-correct {
            background: #d4edda;
            border-color: #28a745;
            color: #155724;
        }
        
        .option-selected {
            background: #fff3cd;
            border-color: #ffc107;
            color: #856404;
        }
        
        .option-wrong {
            background: #f8d7da;
            border-color: #dc3545;
            color: #721c24;
        }
        
        .option-normal {
            background: #f8f9fa;
            border-color: #e9ecef;
            color: #666;
        }
        
        .option-icon {
            margin-right: 0.5rem;
        }
        
        .back-btn {
            background: #6c757d;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }
        
        .back-btn:hover {
            background: #5a6268;
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
        <!-- Back Button -->
        <div class="mb-3">
            <a href="{{ route('admin.quiz-analytics') }}" class="back-btn">
                <i class="fas fa-arrow-left"></i> Kembali ke Analitik Kuis
            </a>
        </div>

        <!-- Student Header -->
        <div class="student-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="student-avatar">
                        {{ substr($user->user_name, 0, 1) }}
                    </div>
                    <h3 class="mb-1">{{ $user->user_name }}</h3>
                    <p class="mb-0 opacity-75">
                        <i class="fas fa-graduation-cap me-2"></i>{{ $user->class_name }}
                        <span class="ms-3">
                            <i class="fas fa-envelope me-2"></i>{{ $user->email }}
                        </span>
                    </p>
                </div>
                <div class="col-md-4 text-end">
                    <h4 class="mb-1">{{ $quiz->quiz_title }}</h4>
                    <p class="mb-0 opacity-75">
                        <i class="fas fa-calendar me-2"></i>
                        {{ \Carbon\Carbon::parse($answers->first()->answered_at ?? now())->format('d M Y H:i') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Score Display -->
        <div class="score-display">
            <div class="row">
                <div class="col-md-4">
                    @php
                        $scoreClass = 'score-poor';
                        if ($score >= 80) $scoreClass = 'score-excellent';
                        elseif ($score >= 70) $scoreClass = 'score-good';
                        elseif ($score >= 60) $scoreClass = 'score-fair';
                    @endphp
                    <div class="score-circle {{ $scoreClass }}">
                        {{ number_format($score, 1) }}%
                    </div>
                    <h5>Skor Akhir</h5>
                </div>
                <div class="col-md-4">
                    <div class="score-circle" style="background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);">
                        {{ $correctAnswers }}
                    </div>
                    <h5>Jawaban Benar</h5>
                </div>
                <div class="col-md-4">
                    <div class="score-circle" style="background: linear-gradient(135deg, #6c757d 0%, #495057 100%);">
                        {{ $totalQuestions }}
                    </div>
                    <h5>Total Soal</h5>
                </div>
            </div>
        </div>

        <!-- Questions and Answers -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-question-circle me-2"></i>
                    Detail Jawaban
                </h5>
            </div>
            <div class="card-body">
                @foreach($questions as $questionId => $questionOptions)
                    @php
                        $question = $questionOptions->first();
                        $studentAnswer = $answers->where('question_id', $questionId)->first();
                        $selectedOptionId = $studentAnswer ? $studentAnswer->selected_option : null;
                    @endphp
                    
                    <div class="question-card">
                        <div class="question-text">
                            <strong>Soal {{ $loop->iteration }}:</strong> {{ $question->question_text }}
                        </div>
                        
                        <div class="options">
                            @foreach($questionOptions as $option)
                                @php
                                    $isSelected = $selectedOptionId === $option->option_text;
                                    $isCorrect = $option->is_correct;
                                    
                                    $optionClass = 'option-normal';
                                    if ($isCorrect) {
                                        $optionClass = 'option-correct';
                                    } elseif ($isSelected && !$isCorrect) {
                                        $optionClass = 'option-wrong';
                                    } elseif ($isSelected) {
                                        $optionClass = 'option-selected';
                                    }
                                @endphp
                                
                                <div class="option-item {{ $optionClass }}">
                                    <i class="fas fa-circle option-icon"></i>
                                    {{ $option->option_text }}
                                    
                                    @if($isCorrect)
                                        <i class="fas fa-check-circle float-end text-success"></i>
                                    @elseif($isSelected && !$isCorrect)
                                        <i class="fas fa-times-circle float-end text-danger"></i>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
