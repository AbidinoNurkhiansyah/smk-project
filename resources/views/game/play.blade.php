<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <title>Kuis Teknik Sepeda Motor - Kelas 10</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/game.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="game-play-container">
        <!-- Header -->
        <header class="game-play-header">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-12 col-md-6 mb-2 mb-md-0">
                        <a href="{{ route('game.index') }}" class="btn btn-outline-light">
                            <i class="fas fa-arrow-left"></i> <span class="d-none d-sm-inline">Kembali</span>
                        </a>
                    </div>
                    <div class="col-12 col-md-6 text-md-end">
                        <div class="game-info d-flex flex-wrap gap-2 justify-content-center justify-content-md-end">
                            <span class="points-badge">
                                <i class="fas fa-star"></i> <span class="d-none d-sm-inline">{{ $quiz->total_questions * $quiz->points_per_question }} Poin</span><span class="d-sm-none">{{ $quiz->total_questions * $quiz->points_per_question }}</span>
                            </span>
                            <span class="difficulty-badge">
                                <i class="fas fa-graduation-cap"></i> <span class="d-none d-md-inline">Kelas {{ $quiz->class_id }} - {{ ucfirst($quiz->difficulty) }} ({{ $quiz->total_questions }} Soal)</span><span class="d-md-none">{{ $quiz->class_id }} - {{ ucfirst($quiz->difficulty) }}</span>
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
                                <div class="progress-bar" role="progressbar" style="width: 0%" id="progressBar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
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
                                        <h3 class="question-number">Soal {{ $index + 1 }}</h3>
                                        <div class="question-timer">
                                            <i class="fas fa-clock"></i>
                                            <span class="timer-display">{{ $quiz->time_limit }}:00</span>
                                        </div>
                                    </div>
                                    
                                    <div class="question-content">
                                        <p class="question-text">{{ $question->question }}</p>
                                        @if($question->image)
                                            <div class="question-image-wrapper">
                                                <img src="{{ Storage::url($question->image) }}" alt="Gambar soal" class="question-image">
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
                                        <div class="d-flex flex-column flex-sm-row gap-2 w-100">
                                            @if($index > 0)
                                                <button type="button" class="btn btn-outline-primary flex-fill" onclick="previousQuestion()">
                                                    <i class="fas fa-arrow-left"></i> <span class="d-none d-sm-inline">Sebelumnya</span>
                                                </button>
                                            @endif
                                            
                                            @if($index < $questions->count() - 1)
                                                <button type="button" class="btn btn-primary flex-fill" onclick="nextQuestion()" id="nextBtn{{ $index + 1 }}" disabled>
                                                    <span class="d-none d-sm-inline">Selanjutnya</span> <i class="fas fa-arrow-right"></i>
                                                </button>
                                            @else
                                                <button type="submit" class="btn btn-success flex-fill" id="submitBtn" disabled>
                                                    <i class="fas fa-check"></i> <span class="d-none d-sm-inline">Selesai Quiz</span><span class="d-sm-none">Selesai</span>
                                                </button>
                                            @endif
                                        </div>
                                        
                                        <!-- Answer validation message -->
                                        <div class="answer-validation mt-2 text-center" id="validationMsg{{ $index + 1 }}" style="display: none;">
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

    <!-- Time Up Modal -->
    <div class="time-up-modal" id="timeUpModal">
        <div class="time-up-modal-overlay"></div>
        <div class="time-up-modal-content">
            <div class="time-up-icon">
                <i class="fas fa-clock"></i>
            </div>
            <h2 class="time-up-title">Waktu Habis!</h2>
            <p class="time-up-message">Quiz akan otomatis disubmit.</p>
            <div class="time-up-spinner">
                <div class="spinner-border text-danger" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var currentQuestionIndex = 0;
            var totalQuestions = parseInt('{{ $questions->count() }}');
            var quizId = parseInt('{{ $quiz->id }}');
            
            // Timer variables
            var timeLeft = 0;
            var timerInterval;
            var isTimerRunning = false;
            var serverEndTime = 0;
            var clientStartTime = Date.now();
            var serverStartTime = 0;
            var timeOffset = 0; // Difference between server and client time

            // Get all timer display elements
            function getTimerElements() {
                return document.querySelectorAll('.timer-display');
            }

            // Sync with server time (Indonesia timezone)
            function syncWithServerTime() {
                fetch('/game/time/' + quizId, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        console.error('Error syncing time:', data.error);
                        return;
                    }
                    
                    // Calculate time offset between server and client
                    serverEndTime = data.end_time * 1000; // Convert to milliseconds
                    serverStartTime = data.start_time * 1000;
                    var serverCurrentTime = data.server_time * 1000;
                    clientStartTime = Date.now();
                    timeOffset = serverCurrentTime - clientStartTime;
                    
                    // Calculate remaining time based on server time
                    calculateRemainingTime();
                    
                    // Start timer if not already running
                    if (!isTimerRunning && timeLeft > 0) {
                        startTimer();
                    }
                })
                .catch(error => {
                    console.error('Error fetching server time:', error);
                });
            }

            // Calculate remaining time based on server time
            function calculateRemainingTime() {
                var currentClientTime = Date.now();
                var currentServerTime = currentClientTime + timeOffset;
                var remaining = Math.max(0, Math.floor((serverEndTime - currentServerTime) / 1000));
                timeLeft = remaining;
            }

            // Start timer
            function startTimer() {
                // Clear existing timer if any
                if (timerInterval) {
                    clearInterval(timerInterval);
                }
                
                // Only start if time is still available
                if (timeLeft <= 0) {
                    return;
                }
                
                isTimerRunning = true;
                timerInterval = setInterval(function() {
                    // Recalculate remaining time based on server time
                    calculateRemainingTime();
                    updateTimerDisplay();
                    
                    if (timeLeft <= 0) {
                        clearInterval(timerInterval);
                        isTimerRunning = false;
                        showTimeUpModal();
                        // Auto submit after 2 seconds
                        setTimeout(function() {
                            document.getElementById('quizForm').submit();
                        }, 2000);
                    }
                }, 1000);
            }

            // Update timer display on all question cards
            function updateTimerDisplay() {
                var minutes = Math.floor(timeLeft / 60);
                var seconds = timeLeft % 60;
                var minutesStr = minutes.toString().padStart(2, '0');
                var secondsStr = seconds.toString().padStart(2, '0');
                var timeString = minutesStr + ':' + secondsStr;
                
                // Update all timer displays
                var timerElements = getTimerElements();
                for (var i = 0; i < timerElements.length; i++) {
                    timerElements[i].textContent = timeString;
                    
                    // Change color when time is running low
                    if (timeLeft <= 60) {
                        timerElements[i].style.color = '#dc3545';
                        timerElements[i].style.fontWeight = 'bold';
                    } else {
                        timerElements[i].style.color = '';
                        timerElements[i].style.fontWeight = '';
                    }
                }
            }

            // Initial sync with server time (Indonesia timezone)
            syncWithServerTime();
            
            // Sync with server every 10 seconds to ensure accuracy
            setInterval(syncWithServerTime, 10000);

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
                    
                    // Ensure timer continues running
                    if (!isTimerRunning && timeLeft > 0) {
                        startTimer();
                    }
                }
            };

            window.previousQuestion = function() {
                if (currentQuestionIndex > 0) {
                    currentQuestionIndex--;
                    showQuestion(currentQuestionIndex);
                    updateNavigationButtons();
                    
                    // Ensure timer continues running
                    if (!isTimerRunning && timeLeft > 0) {
                        startTimer();
                    }
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
                
                // Recalculate remaining time based on server time
                calculateRemainingTime();
                
                // Ensure timer is still running and visible
                if (!isTimerRunning && timeLeft > 0) {
                    startTimer();
                }
                
                // Update timer display immediately when switching questions
                updateTimerDisplay();
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

            // Show time up modal with animation
            function showTimeUpModal() {
                var modal = document.getElementById('timeUpModal');
                if (modal) {
                    modal.classList.add('show');
                }
            }
        });
    </script>
</body>
</html>
