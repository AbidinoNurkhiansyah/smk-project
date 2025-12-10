<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Quiz Baru - Kelola Quiz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
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
            <a href="{{ route('admin.videos') }}" class="nav-link">
                <i class="fas fa-video"></i>
                <span>Kelola Video</span>
            </a>
            <a href="{{ route('admin.teacher-quiz') }}" class="nav-link active">
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
        <form action="{{ route('admin.store-teacher-quiz') }}" method="POST" id="quizForm" enctype="multipart/form-data">
            @csrf
            
            <!-- Quiz Information -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="fas fa-info-circle"></i> Informasi Quiz</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="class_id" class="form-label">Kelas <span class="text-danger">*</span></label>
                                        <select class="form-select" id="class_id" name="class_id" required>
                                            <option value="">Pilih Kelas</option>
                                            @foreach($classes as $class)
                                                <option value="{{ $class->class_id }}">{{ $class->class_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="difficulty" class="form-label">Tingkat Kesulitan <span class="text-danger">*</span></label>
                                        <select class="form-select" id="difficulty" name="difficulty" required>
                                            <option value="">Pilih Tingkat</option>
                                            <option value="mudah">Mudah</option>
                                            <option value="sedang">Sedang</option>
                                            <option value="sulit">Sulit</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="quiz_title" class="form-label">Judul Quiz <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="quiz_title" name="quiz_title" 
                                       placeholder="Masukkan judul quiz" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="quiz_description" class="form-label">Deskripsi Quiz</label>
                                <textarea class="form-control" id="quiz_description" name="quiz_description" 
                                          rows="3" placeholder="Masukkan deskripsi quiz (opsional)"></textarea>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="total_questions" class="form-label">Jumlah Soal <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="total_questions" name="total_questions" 
                                               min="1" max="100" value="10" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="time_limit" class="form-label">Waktu (menit) <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="time_limit" name="time_limit" 
                                               min="1" max="180" value="30" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="points_per_question" class="form-label">Poin per Soal <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="points_per_question" name="points_per_question" 
                                               min="1" max="100" value="10" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Questions Section -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5><i class="fas fa-question-circle"></i> Soal Quiz</h5>
                            <button type="button" class="btn btn-success btn-sm" onclick="addQuestion()">
                                <i class="fas fa-plus"></i> Tambah Soal
                            </button>
                        </div>
                        <div class="card-body">
                            <div id="questions-container">
                                <!-- Questions will be added here dynamically -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.teacher-quiz') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Quiz
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let questionCount = 0;

        function addQuestion() {
            questionCount++;
            const container = document.getElementById('questions-container');
            
            const questionHtml = `
                <div class="question-card mb-4" id="question-${questionCount}">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6>Soal ${questionCount}</h6>
                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeQuestion(${questionCount})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Pertanyaan <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="questions[${questionCount - 1}][question]" 
                                          rows="3" placeholder="Masukkan pertanyaan" required></textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Gambar Soal (Opsional)</label>
                                <input type="file" class="form-control" name="questions[${questionCount - 1}][image]" 
                                       accept="image/jpeg,image/png,image/jpg,image/gif,image/webp">
                                <small class="form-text text-muted">Format: JPEG, PNG, JPG, GIF, WEBP (Maks: 2MB)</small>
                                <div class="mt-2 image-preview-container" id="preview-${questionCount}" style="display: none;">
                                    <img src="" alt="Preview" class="img-thumbnail" style="max-width: 300px; max-height: 300px;">
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Jawaban Benar <span class="text-danger">*</span></label>
                                    <select class="form-select" name="questions[${questionCount - 1}][correct_answer]" required>
                                        <option value="">Pilih Jawaban Benar</option>
                                        <option value="A">A</option>
                                        <option value="B">B</option>
                                        <option value="C">C</option>
                                        <option value="D">D</option>
                                        <option value="E">E</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Poin <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="questions[${questionCount - 1}][points]" 
                                           min="1" max="100" value="10" required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Pilihan Jawaban <span class="text-danger">*</span></label>
                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <div class="input-group">
                                            <span class="input-group-text">A</span>
                                            <input type="text" class="form-control" name="questions[${questionCount - 1}][options][0]" 
                                                   placeholder="Pilihan A" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <div class="input-group">
                                            <span class="input-group-text">B</span>
                                            <input type="text" class="form-control" name="questions[${questionCount - 1}][options][1]" 
                                                   placeholder="Pilihan B" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <div class="input-group">
                                            <span class="input-group-text">C</span>
                                            <input type="text" class="form-control" name="questions[${questionCount - 1}][options][2]" 
                                                   placeholder="Pilihan C" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <div class="input-group">
                                            <span class="input-group-text">D</span>
                                            <input type="text" class="form-control" name="questions[${questionCount - 1}][options][3]" 
                                                   placeholder="Pilihan D" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <div class="input-group">
                                            <span class="input-group-text">E</span>
                                            <input type="text" class="form-control" name="questions[${questionCount - 1}][options][4]" 
                                                   placeholder="Pilihan E (opsional)">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            container.insertAdjacentHTML('beforeend', questionHtml);
        }

        function removeQuestion(questionId) {
            const questionCard = document.getElementById(`question-${questionId}`);
            if (questionCard) {
                questionCard.remove();
                // Recalculate question numbers
                const remainingQuestions = document.querySelectorAll('.question-card');
                remainingQuestions.forEach((card, index) => {
                    const header = card.querySelector('.card-header h6');
                    if (header) {
                        header.textContent = `Soal ${index + 1}`;
                    }
                });
            }
        }

        // Add first question on page load
        document.addEventListener('DOMContentLoaded', function() {
            addQuestion();
            
            // Add image preview functionality
            document.addEventListener('change', function(e) {
                if (e.target.type === 'file' && e.target.name.includes('[image]')) {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        const previewContainer = e.target.closest('.card-body').querySelector('.image-preview-container');
                        const previewImg = previewContainer.querySelector('img');
                        
                        reader.onload = function(e) {
                            previewImg.src = e.target.result;
                            previewContainer.style.display = 'block';
                        };
                        
                        reader.readAsDataURL(file);
                    }
                }
            });
        });

        // Form validation
        document.getElementById('quizForm').addEventListener('submit', function(e) {
            const questionCards = document.querySelectorAll('.question-card');
            if (questionCards.length === 0) {
                e.preventDefault();
                alert('Minimal harus ada 1 soal!');
                return false;
            }
            
            // Validate that all questions have required fields
            let isValid = true;
            questionCards.forEach((card, index) => {
                const question = card.querySelector(`textarea[name*="[question]"]`);
                const correctAnswer = card.querySelector(`select[name*="[correct_answer]"]`);
                const options = card.querySelectorAll(`input[name*="[options]"]`);
                
                if (!question || !question.value.trim()) {
                    isValid = false;
                }
                if (!correctAnswer || !correctAnswer.value) {
                    isValid = false;
                }
                let filledOptions = 0;
                options.forEach(opt => {
                    if (opt.value.trim()) filledOptions++;
                });
                if (filledOptions < 2) {
                    isValid = false;
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                alert('Pastikan semua soal memiliki pertanyaan, jawaban benar, dan minimal 2 pilihan jawaban!');
                return false;
            }
        });
    </script>
</body>
</html>
