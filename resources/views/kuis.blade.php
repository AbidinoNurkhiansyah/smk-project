<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kuis - Mecha Learn</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/kuis.css') }}" rel="stylesheet">
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-danger shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">
                <img src="{{ asset('logo.png') }}" alt="Logo" width="35" class="me-2">
                Mecha Learn
            </a>
            <div class="d-flex">
                <a href="{{ route('dashboard') }}" class="btn btn-light btn-sm me-2">Dashboard</a>
                <a href="{{ url('/') }}" class="btn btn-outline-light btn-sm">Keluar</a>
            </div>
        </div>
    </nav>

    <!-- Kuis Container -->
    <section class="container my-5">
        <div class="quiz-box shadow p-4 rounded-4 bg-white">
            <h2 class="fw-bold text-danger text-center mb-4">🧠 Kuis Teknik Sepeda Motor</h2>
            <p class="text-center text-muted">Jawab pertanyaan dengan benar sebelum waktu habis!</p>

            <!-- Progress bar -->
            <div class="progress mb-4" style="height: 10px;">
                <div id="progress-bar" class="progress-bar bg-danger" style="width: 0%;"></div>
            </div>

            <!-- Timer -->
            <div class="text-end mb-3">
                <span class="badge bg-danger p-2">⏰ <span id="timer">60</span> detik</span>
            </div>

            <form id="quizForm">
                <div class="question mb-4">
                    <h5>1. Apa fungsi dari karburator pada sepeda motor?</h5>
                    <div class="form-check">
                        <input type="radio" class="form-check-input" name="q1" value="a">
                        <label class="form-check-label">Mengatur suhu mesin</label><br>
                        <input type="radio" class="form-check-input" name="q1" value="b">
                        <label class="form-check-label">Mencampur udara dan bahan bakar</label><br>
                        <input type="radio" class="form-check-input" name="q1" value="c">
                        <label class="form-check-label">Mengatur tekanan oli</label>
                    </div>
                </div>

                <div class="question mb-4">
                    <h5>2. Apa fungsi dari busi?</h5>
                    <div class="form-check">
                        <input type="radio" class="form-check-input" name="q2" value="a">
                        <label class="form-check-label">Mendinginkan mesin</label><br>
                        <input type="radio" class="form-check-input" name="q2" value="b">
                        <label class="form-check-label">Membakar campuran bahan bakar dan udara</label><br>
                        <input type="radio" class="form-check-input" name="q2" value="c">
                        <label class="form-check-label">Menyalurkan bahan bakar ke ruang bakar</label>
                    </div>
                </div>

                <div class="question mb-4">
                    <h5>3. Komponen berikut yang berfungsi menggerakkan klep adalah...</h5>
                    <div class="form-check">
                        <input type="radio" class="form-check-input" name="q3" value="a">
                        <label class="form-check-label">Camshaft (poros nok)</label><br>
                        <input type="radio" class="form-check-input" name="q3" value="b">
                        <label class="form-check-label">Piston</label><br>
                        <input type="radio" class="form-check-input" name="q3" value="c">
                        <label class="form-check-label">Crankshaft (poros engkol)</label>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <button type="button" class="btn btn-danger px-4 py-2 rounded-pill" onclick="submitQuiz()">Kirim Jawaban</button>
                </div>
            </form>

            <div id="resultBox" class="text-center mt-4 d-none">
                <h4 class="fw-bold">🎉 Hasil Kuis Kamu</h4>
                <p id="scoreText" class="text-muted"></p>
                <a href="{{ route('dashboard') }}" class="btn btn-outline-danger rounded-pill mt-3">Kembali ke Dashboard</a>
            </div>
        </div>
    </section>

    <footer class="text-center py-3 bg-light text-muted">
        &copy; 2025 Mecha Learn. Semua hak dilindungi.
    </footer>

    <script>
        let timeLeft = 60;
        let timer = setInterval(() => {
            document.getElementById("timer").textContent = timeLeft;
            document.getElementById("progress-bar").style.width = `${(60 - timeLeft) / 60 * 100}%`;
            timeLeft--;
            if (timeLeft < 0) {
                clearInterval(timer);
                submitQuiz(true);
            }
        }, 1000);

        function submitQuiz(auto = false) {
            clearInterval(timer);
            let score = 0;
            const answers = { q1: "b", q2: "b", q3: "a" };

            for (let key in answers) {
                const selected = document.querySelector(`input[name="${key}"]:checked`);
                if (selected && selected.value === answers[key]) score++;
            }

            document.getElementById("quizForm").classList.add("d-none");
            document.getElementById("resultBox").classList.remove("d-none");

            document.getElementById("scoreText").innerText =
                auto
                    ? `Waktu habis! Kamu menjawab benar ${score} dari 3 pertanyaan.`
                    : `Kamu menjawab benar ${score} dari 3 pertanyaan.`;
        }
    </script>

</body>
</html>
