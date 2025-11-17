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
                                        @if($question->image)
                                            <div class="mb-3">
                                                <img src="{{ Storage::url($question->image) }}" alt="Gambar soal" class="img-fluid rounded" style="max-width: 100%; max-height: 400px;">
                                            </div>
                                        @endif
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
            var timerElement = document.getElementById('timer');
            var currentQuestionIndex = 0;
            var totalQuestions = parseInt('{{ $questions->count() }}');
            
            // Timer setup using quiz settings
            var timeLimit = parseInt('{{ $quiz->time_limit }}') * 60;
            
            var timeLeft = timeLimit;
            var timerInterval;

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
                var minutes = Math.floor(timeLeft / 60);
                var seconds = timeLeft % 60;
                var minutesStr = minutes.toString().padStart(2, '0');
                var secondsStr = seconds.toString().padStart(2, '0');
                timerElement.textContent = minutesStr + ':' + secondsStr;
                
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
                var questionSelector = '[data-question="' + (currentQuestionIndex + 1) + '"]';
                var currentQuestion = document.querySelector(questionSelector);
                if (!currentQuestion) return false;
                
                var selectedAnswer = currentQuestion.querySelector('input[type="radio"]:checked');
                return selectedAnswer !== null;
            }

            // Show validation message
            function showValidationMessage() {
                var validationMsgId = 'validationMsg' + (currentQuestionIndex + 1);
                var validationMsg = document.getElementById(validationMsgId);
                if (validationMsg) {
                    validationMsg.style.display = 'block';
                    setTimeout(function() {
                        validationMsg.style.display = 'none';
                    }, 3000);
                }
            }

            // Update navigation buttons based on current question
            function updateNavigationButtons() {
                var isAnswered = isCurrentQuestionAnswered();
                var nextBtnId = 'nextBtn' + (currentQuestionIndex + 1);
                var nextBtn = document.getElementById(nextBtnId);
                var submitBtn = document.getElementById('submitBtn');
                
                if (nextBtn) {
                    nextBtn.disabled = !isAnswered;
                }
                if (submitBtn) {
                    submitBtn.disabled = !isAnswered;
                }
            }

            function showQuestion(index) {
                // Hide all questions
                var questionCards = document.querySelectorAll('.question-card');
                for (var i = 0; i < questionCards.length; i++) {
                    questionCards[i].classList.remove('active');
                }
                
                // Show current question
                var questionSelector = '[data-question="' + (index + 1) + '"]';
                var currentCard = document.querySelector(questionSelector);
                if (currentCard) {
                    currentCard.classList.add('active');
                }
                
                // Update progress bar
                var progress = ((index + 1) / totalQuestions) * 100;
                document.getElementById('progressBar').style.width = progress + '%';
                document.getElementById('currentQuestion').textContent = index + 1;
            }

            // Add visual feedback for option selection
            var optionInputs = document.querySelectorAll('.option-input');
            for (var i = 0; i < optionInputs.length; i++) {
                optionInputs[i].addEventListener('change', function() {
                    // Remove active class from all options in current question
                    var currentQuestion = this.closest('.question-card');
                    var optionItems = currentQuestion.querySelectorAll('.option-item');
                    for (var j = 0; j < optionItems.length; j++) {
                        optionItems[j].classList.remove('active');
                    }
                    
                    // Add active class to selected option
                    this.closest('.option-item').classList.add('active');
                    
                    // Update navigation buttons when answer is selected
                    updateNavigationButtons();
                });
            }
        });
    </script>
</body>
</html>
