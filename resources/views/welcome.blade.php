<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MechaLearn - Teknik Sepeda Motor</title>
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
</head>
<body>

    <!-- Navbar -->
    <header class="navbar">
        <div class="container nav-content">
            <div class="logo">
                <img src="{{ asset('image/logo.png') }}" alt="Logo">
                <div class="logo-text">
                    <h1>Mecha Learn</h1>
                    <p>Teknik Sepeda Motor</p>
                </div>
            </div>
            <nav>
                <a href="{{ route('welcome') }}">Beranda</a>
                <a href="{{ route('service') }}">Edukasi Service</a>
                <a href="{{ route('profile') }}">Profil</a>
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="logout-btn" onclick="return confirm('Apakah Anda yakin ingin logout?')">Logout</button>
                </form>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container hero-content">
            <div class="hero-text">
                <h2>Kuasai Teknik Sepeda Motor Dengan <span>MechaLearn</span></h2>
                <p>Platform pembelajaran interaktif yang menggabungkan video tutorial, pengingat service motor, dan sistem gamifikasi untuk membuat belajar teknik motor lebih menyenangkan dan efektif.</p>
            </div>
            <div class="hero-img">
                <img src="{{ asset('image/assets2.jpg') }}" alt="Belajar Motor">
            </div>
        </div>
    </section>

    <!-- Statistik -->
    <section class="stats">
        <div class="container stats-grid">
            <div><h3>100+</h3><p>Video Pembelajaran</p></div>
            <div><h3>65+</h3><p>Siswa Aktif</p></div>
            <div><h3>95%</h3><p>Tingkat Kepuasan</p></div>
        </div>
    </section>

    <!-- Fitur Unggulan -->
    <section id="fitur" class="fitur">
        <div class="container">
            <h4 class="tag">⚡ Fitur Unggulan</h4>
            <h2>Semua Yang Anda Butuhkan Untuk Belajar</h2>
            <p class="subtitle">Platform lengkap dengan berbagai fitur canggih untuk mendukung perjalanan belajar teknik sepeda motor Anda.</p>

            <div class="fitur-grid">
                <div class="fitur-item">
                    <h3>🎥 Video Pembelajaran Interaktif</h3>
                    <p>Akses ratusan video tutorial teknik sepeda motor dari dasar hingga mahir dengan tracking progress otomatis.</p>
                </div>
                <div class="fitur-item">
                    <h3>🏆 Sistem Gamifikasi</h3>
                    <p>Sistem reminder cerdas untuk jadwal service berkala motor Anda, tidak ada lagi service yang terlewat.</p>
                </div>
                <div class="fitur-item">
                    <h3>📊 Leaderboard Kompetitif</h3>
                    <p>Raih badge, naik level, dan bersaing dengan siswa lain berdasarkan leaderboard untuk motivasi belajar lebih seru.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Kenapa MechaLearn -->
    <section id="tentang" class="kenapa">
        <div class="container kenapa-content">
            <div class="kenapa-text">
                <h4>🧠 Kenapa MechaLearn?</h4>
                <h2>Belajar Lebih Efektif dengan Metode Terbukti</h2>
                <p>Kami menggunakan pendekatan pembelajaran modern yang telah terbukti meningkatkan pemahaman dan retensi siswa hingga 85%.</p>
                <ul>
                    <li>✅ Belajar fleksibel kapan saja</li>
                    <li>✅ Materi pembelajaran mudah dipahami</li>
                    <li>✅ Instruktur berpengalaman di bidang teknik motor</li>
                    <li>✅ Progress tracking otomatis</li>
                    <li>✅ Sistem gamifikasi menyenangkan</li>
                </ul>
                <a href="{{ route('dashboard') }}" class="btn-primary">Mulai Sekarang</a>
            </div>
            <div class="kenapa-img">
                <img src="{{ asset('image/assets.jpg') }}" alt="Peralatan Motor">
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="cta">
        <h2>Siap Memulai Perjalanan Belajar Anda?</h2>
        <p>Bergabunglah dengan siswa yang telah meningkatkan skill teknik motor mereka.</p>
        <div class="cta-footer">
            <span>🔒 Aman Terpercaya</span>
            <span>🕓 Akses 24/7</span>
        </div>
    </section>

    <!-- Tim -->
    <section class="tim">
        <h2>Tim Pengembang</h2>
        <div class="container tim-grid">
            <div class="member"><img src="{{ asset('image/abidino.jpg') }}"><p>Abidino</p></div>
            <div class="member"><img src="{{ asset('image/faiq.jpg') }}"><p>Faiq</p></div>
            <div class="member"><img src="{{ asset('image/filia.jpg') }}"><p>Filia</p></div>
            <div class="member"><img src="{{ asset('image/rihan.jpg') }}"><p>Rihan</p></div>
            <div class="member"><img src="{{ asset('image/rega.jpg') }}"><p>Rega</p></div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container footer-grid">
            <div>
                <h3>Mecha Learn</h3>
                <p>Platform pembelajaran teknik sepeda motor terdepan di Indonesia.</p>
            </div>
            <div>
                <h3>Platform</h3>
                <a href="#">Video Pembelajaran</a>
                <a href="#">Leaderboard</a>
            </div>
            <div>
                <h3>Perusahaan</h3>
                <a href="#">Tentang Kami</a>
                <a href="#">Kontak</a>
            </div>
            <div>
                <h3>Legal</h3>
                <a href="#">Syarat & Ketentuan</a>
                <a href="#">Kebijakan Privasi</a>
            </div>
        </div>
        <p class="copyright">© 2025 MechaLearn. All rights reserved.</p>
    </footer>

</body>
</html>
