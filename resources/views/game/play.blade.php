<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kuis Teknik Sepeda Motor - Kelas 10</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/game.css') }}">
</head>
<body>
    <div class="game-play-container">
        <!-- Header -->
        <header class="game-play-header">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <a href="{{ route('game.index') }}" class="btn btn-outline-light">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                    <div class="col-md-6 text-end">
                        <div class="game-info">
                            <span class="points-badge">
                                <i class="fas fa-star"></i> {{ $quiz->total_questions * $quiz->points_per_question }} Poin
                            </span>
                            <span class="difficulty-badge">
                                <i class="fas fa-graduation-cap"></i> Kelas {{ $quiz->class_id }} - {{ ucfirst($quiz->difficulty) }} ({{ $quiz->total_questions }} Soal)
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Game Content -->
        <main class="game-play-main">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <!-- Progress Bar -->
                        <div class="quiz-progress mb-4">
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" style="width: 0%" id="progressBar"></div>
                            </div>
                            <div class="progress-text text-center mt-2">
                                <span id="currentQuestion">1</span> dari {{ $questions->count() }} soal
                            </div>
                        </div>

                        <!-- Quiz Form -->
                        <form action="{{ route('game.submit', $quiz->id) }}" method="POST" class="quiz-form" id="quizForm">
                            @csrf
                            
                            @foreach($questions as $index => $question)
                                <div class="question-card {{ $index === 0 ? 'active' : '' }}" data-question="{{ $index + 1 }}">
                                    <div class="question-header">
                                        <h3>Soal {{ $index + 1 }}</h3>
                                        <div class="question-timer">
                                            <i class="fas fa-clock"></i>
                                            <span id="timer">{{ $quiz->time_limit }}:00</span>
                                        </div>
                                    </div>
                                    
                                    <div class="question-content">
                                        <p class="question-text">{{ $question->question }}</p>
                                        
                                        <div class="options-container">
                                            @if(isset($allOptions[$question->id]))
                                                @foreach($allOptions[$question->id] as $option)
                                                    <div class="option-item">
                                                        <input type="radio" 
                                                               name="answers[{{ $question->id }}]" 
                                                               value="{{ $option->option_label }}" 
                                                               id="option_{{ $question->id }}_{{ $option->option_label }}"
                                                               class="option-input">
                                                        <label for="option_{{ $question->id }}_{{ $option->option_label }}" class="option-label">
                                                            <span class="option-letter">{{ $option->option_label }}</span>
                                                            <span class="option-text">{{ $option->option_text }}</span>
                                                        </label>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="question-actions">
                                        @if($index > 0)
                                            <button type="button" class="btn btn-outline-primary" onclick="previousQuestion()">
                                                <i class="fas fa-arrow-left"></i> Sebelumnya
                                            </button>
                                        @endif
                                        
                                        @if($index < $questions->count() - 1)
                                            <button type="button" class="btn btn-primary" onclick="nextQuestion()" id="nextBtn{{ $index + 1 }}" disabled>
                                                Selanjutnya <i class="fas fa-arrow-right"></i>
                                            </button>
                                        @else
                                            <button type="submit" class="btn btn-success" id="submitBtn" disabled>
                                                <i class="fas fa-check"></i> Selesai Quiz
                                            </button>
                                        @endif
                                        
                                        <!-- Answer validation message -->
                                        <div class="answer-validation mt-2" id="validationMsg{{ $index + 1 }}" style="display: none;">
                                            <small class="text-danger">
                                                <i class="fas fa-exclamation-triangle"></i> 
                                                Silakan pilih jawaban terlebih dahulu!
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const timerElement = document.getElementById('timer');
            let currentQuestionIndex = 0;
            const totalQuestions = {{ $questions->count() }};
            
            // Timer setup using quiz settings
            let timeLimit = {{ $quiz->time_limit }} * 60; // minutes from quiz settings
            
            let timeLeft = timeLimit;
            let timerInterval;

            // Start timer
            function startTimer() {
                timerInterval = setInterval(function() {
                    timeLeft--;
                    updateTimerDisplay();
                    
                    if (timeLeft <= 0) {
                        clearInterval(timerInterval);
                        alert('Waktu habis! Quiz akan otomatis disubmit.');
                        document.getElementById('quizForm').submit();
                    }
                }, 1000);
            }

            // Update timer display
            function updateTimerDisplay() {
                const minutes = Math.floor(timeLeft / 60);
                const seconds = timeLeft % 60;
                timerElement.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                
                // Change color when time is running low
                if (timeLeft <= 60) {
                    timerElement.style.color = '#dc3545';
                    timerElement.style.fontWeight = 'bold';
                }
            }

            // Start timer when page loads
            startTimer();

            // Navigation functions with validation
            window.nextQuestion = function() {
                // Check if current question is answered
                if (!isCurrentQuestionAnswered()) {
                    showValidationMessage();
                    return;
                }
                
                if (currentQuestionIndex < totalQuestions - 1) {
                    currentQuestionIndex++;
                    showQuestion(currentQuestionIndex);
                    updateNavigationButtons();
                }
            };

            window.previousQuestion = function() {
                if (currentQuestionIndex > 0) {
                    currentQuestionIndex--;
                    showQuestion(currentQuestionIndex);
                    updateNavigationButtons();
                }
            };

            // Check if current question is answered
            function isCurrentQuestionAnswered() {
                const currentQuestion = document.querySelector(`[data-question="${currentQuestionIndex + 1}"]`);
                if (!currentQuestion) return false;
                
                const selectedAnswer = currentQuestion.querySelector('input[type="radio"]:checked');
                return selectedAnswer !== null;
            }

            // Show validation message
            function showValidationMessage() {
                const validationMsg = document.getElementById(`validationMsg${currentQuestionIndex + 1}`);
                if (validationMsg) {
                    validationMsg.style.display = 'block';
                    setTimeout(() => {
                        validationMsg.style.display = 'none';
                    }, 3000);
                }
            }

            // Update navigation buttons based on current question
            function updateNavigationButtons() {
                const isAnswered = isCurrentQuestionAnswered();
                const nextBtn = document.getElementById(`nextBtn${currentQuestionIndex + 1}`);
                const submitBtn = document.getElementById('submitBtn');
                
                if (nextBtn) {
                    nextBtn.disabled = !isAnswered;
                }
                if (submitBtn) {
                    submitBtn.disabled = !isAnswered;
                }
            }

            function showQuestion(index) {
                // Hide all questions
                document.querySelectorAll('.question-card').forEach(card => {
                    card.classList.remove('active');
                });
                
                // Show current question
                const currentCard = document.querySelector(`[data-question="${index + 1}"]`);
                if (currentCard) {
                    currentCard.classList.add('active');
                }
                
                // Update progress bar
                const progress = ((index + 1) / totalQuestions) * 100;
                document.getElementById('progressBar').style.width = progress + '%';
                document.getElementById('currentQuestion').textContent = index + 1;
            }

            // Add visual feedback for option selection
            document.querySelectorAll('.option-input').forEach(input => {
                input.addEventListener('change', function() {
                    // Remove active class from all options in current question
                    const currentQuestion = this.closest('.question-card');
                    currentQuestion.querySelectorAll('.option-item').forEach(item => {
                        item.classList.remove('active');
                    });
                    
                    // Add active class to selected option
                    this.closest('.option-item').classList.add('active');
                    
                    // Update navigation buttons when answer is selected
                    updateNavigationButtons();
                });
            });
        });
    </script>
</body>
</html>
