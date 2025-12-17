<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MechaLearn - Teknik Sepeda Motor</title>
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
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
            <button class="mobile-menu-btn" id="mobileMenuBtn">
                <i class="fas fa-bars"></i>
            </button>
            <nav id="mainNav">
                <a href="{{ route('welcome') }}" class="nav-link"><i class="fas fa-home"></i> Beranda</a>
                <a href="{{ route('service') }}" class="nav-link"><i class="fas fa-tools"></i> Edukasi Service</a>
                <a href="{{ route('profile') }}" class="nav-link"><i class="fas fa-user"></i> Profil</a>
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="logout-btn" onclick="return confirm('Apakah Anda yakin ingin logout?')"><i class="fas fa-sign-out-alt"></i> Logout</button>
                </form>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container hero-content">
            <div class="hero-text">
                <div class="hero-badge">
                    <i class="fas fa-star"></i> Platform Pembelajaran #1
                </div>
                <h2>Kuasai Teknik Sepeda Motor Dengan <span>MechaLearn</span></h2>
                <p>Platform pembelajaran interaktif yang menggabungkan video tutorial, pengingat service motor, dan sistem gamifikasi untuk membuat belajar teknik motor lebih menyenangkan dan efektif.</p>
                <div class="hero-buttons">
                    <a href="{{ route('dashboard') }}" class="btn-primary">
                        <i class="fas fa-rocket"></i> Mulai Belajar Sekarang
                    </a>
                    <a href="#fitur" class="btn-secondary">
                        <i class="fas fa-info-circle"></i> Pelajari Lebih Lanjut
                    </a>
                </div>
            </div>
            <div class="hero-img">
                <img src="{{ asset('image/assets2.jpg') }}" alt="Belajar Motor">
            </div>
        </div>
    </section>

    <!-- Statistik -->
    <section class="stats">
        <div class="container stats-grid">
            <div class="stat-item">
                <div class="stat-icon"><i class="fas fa-video"></i></div>
                <h3 data-count="{{ $totalVideos }}">0</h3>
                <p>Video Pembelajaran</p>
            </div>
            <div class="stat-item">
                <div class="stat-icon"><i class="fas fa-users"></i></div>
                <h3 data-count="{{ $totalStudents }}">0</h3>
                <p>Siswa Aktif</p>
            </div>
            <div class="stat-item">
                <div class="stat-icon"><i class="fas fa-star"></i></div>
                <h3 data-count="{{ $satisfactionRate }}">0</h3>
                <p>Tingkat Kepuasan</p>
            </div>
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
                    <div class="fitur-icon">
                        <i class="fas fa-video"></i>
                    </div>
                    <h3>Video Pembelajaran Interaktif</h3>
                    <p>Akses ratusan video tutorial teknik sepeda motor dari dasar hingga mahir dengan tracking progress otomatis.</p>
                </div>
                <div class="fitur-item">
                    <div class="fitur-icon">
                        <i class="fas fa-gamepad"></i>
                    </div>
                    <h3>Sistem Gamifikasi</h3>
                    <p>Sistem reminder cerdas untuk jadwal service berkala motor Anda, tidak ada lagi service yang terlewat.</p>
                </div>
                <div class="fitur-item">
                    <div class="fitur-icon">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <h3>Leaderboard Kompetitif</h3>
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
                <ul class="kenapa-list">
                    <li class="kenapa-list-item"><i class="fas fa-check-circle"></i> Belajar fleksibel kapan saja</li>
                    <li class="kenapa-list-item"><i class="fas fa-check-circle"></i> Materi pembelajaran mudah dipahami</li>
                    <li class="kenapa-list-item"><i class="fas fa-check-circle"></i> Instruktur berpengalaman di bidang teknik motor</li>
                    <li class="kenapa-list-item"><i class="fas fa-check-circle"></i> Progress tracking otomatis</li>
                    <li class="kenapa-list-item"><i class="fas fa-check-circle"></i> Sistem gamifikasi menyenangkan</li>
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
        <div class="container">
            <h2>Siap Memulai Perjalanan Belajar Anda?</h2>
            <p>Bergabunglah dengan siswa yang telah meningkatkan skill teknik motor mereka.</p>
            <a href="{{ route('dashboard') }}" class="btn-cta">
                <i class="fas fa-arrow-right"></i> Mulai Sekarang - Gratis!
            </a>
            <div class="cta-footer">
                <div class="cta-badge">
                    <i class="fas fa-shield-alt"></i>
                    <span>Aman Terpercaya</span>
                </div>
                <div class="cta-badge">
                    <i class="fas fa-clock"></i>
                    <span>Akses 24/7</span>
                </div>
                <div class="cta-badge">
                    <i class="fas fa-graduation-cap"></i>
                    <span>Sertifikat Resmi</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Tim -->
    <section class="tim">
        <div class="container">
            <div class="tim-header">
                <h4 class="tag">👥 Tim Kami</h4>
                <h2>Tim Pengembang</h2>
                <p class="subtitle">Dibuat dengan dedikasi oleh tim profesional untuk memberikan pengalaman belajar terbaik</p>
            </div>
            <div class="tim-grid">
                <div class="member">
                    <div class="member-img">
                        <img src="{{ asset('image/abidino.jpg') }}" alt="Abidino">
                    </div>
                    <h3>Abidino</h3>
                    <p class="member-role">Full Stack Developer</p>
                </div>
                <div class="member">
                    <div class="member-img">
                        <img src="{{ asset('image/faiq.jpg') }}" alt="Faiq">
                    </div>
                    <h3>Faiq</h3>
                    <p class="member-role">Backend Developer</p>
                </div>
                <div class="member">
                    <div class="member-img">
                        <img src="{{ asset('image/filia.jpg') }}" alt="Filia">
                    </div>
                    <h3>Filia</h3>
                    <p class="member-role">UI/UX Designer</p>
                </div>
                <div class="member">
                    <div class="member-img">
                        <img src="{{ asset('image/rihan.jpg') }}" alt="Rihan">
                    </div>
                    <h3>Rihan</h3>
                    <p class="member-role">Frontend Developer</p>
                </div>
                <div class="member">
                    <div class="member-img">
                        <img src="{{ asset('image/mbg.jpg') }}" alt="Rega">
                    </div>
                    <h3>Rega</h3>
                    <p class="member-role">Project Manager</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-brand">
                    <div class="footer-logo">
                        <img src="{{ asset('image/logo.png') }}" alt="Logo">
                        <h3>Mecha Learn</h3>
                    </div>
                    <p>Platform pembelajaran teknik sepeda motor terdepan di Indonesia. Belajar dengan cara yang menyenangkan dan efektif.</p>
                </div>
                <div class="footer-links">
                    <div class="footer-column">
                        <h4>Navigasi</h4>
                        <ul>
                            <li><a href="{{ route('welcome') }}">Beranda</a></li>
                            <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li><a href="{{ route('video.index') }}">Video Pembelajaran</a></li>
                            <li><a href="{{ route('game.index') }}">Game</a></li>
                        </ul>
                    </div>
                    <div class="footer-column">
                        <h4>Fitur</h4>
                        <ul>
                            <li><a href="{{ route('service') }}">Edukasi Service</a></li>
                            <li><a href="{{ route('game.leaderboard') }}">Leaderboard</a></li>
                            <li><a href="{{ route('profile') }}">Profil</a></li>
                        </ul>
                    </div>
                    <div class="footer-column">
                        <h4>Kontak</h4>
                        <ul>
                            <li><i class="fas fa-envelope"></i> info@mechalearn.com</li>
                            <li><i class="fas fa-phone"></i> +62 89655827824
                            <li><i class="fas fa-map-marker-alt"></i> Jl. Pangkal Perjuangan KM 1 By Pass, Tanjungpura, Karawang Barat, Karawang, Jawa Barat, Indonesia.</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p class="copyright">© 2025 MechaLearn. Semua hak dilindungi.</p>
            </div>
        </div>
    </footer>

    <!-- Scroll to Top Button -->
    <button class="scroll-to-top" id="scrollToTop" aria-label="Scroll to top">
        <i class="fas fa-arrow-up"></i>
    </button>

    <script>
        // Smooth scroll untuk anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Animated counter untuk statistik
        function animateCounter(element) {
            const target = parseInt(element.getAttribute('data-count'));
            const duration = 2000;
            const increment = target / (duration / 16);
            let current = 0;
            const suffix = element.textContent.includes('%') ? '%' : '+';

            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    element.textContent = target + suffix;
                    clearInterval(timer);
                } else {
                    element.textContent = Math.floor(current) + suffix;
                }
            }, 16);
        }

        // Intersection Observer untuk animasi saat scroll
        const observerOptions = {
            threshold: 0.3,
            rootMargin: '0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate');
                    
                    // Animate counters
                    const counters = entry.target.querySelectorAll('[data-count]');
                    counters.forEach(counter => {
                        if (!counter.classList.contains('counted')) {
                            counter.classList.add('counted');
                            animateCounter(counter);
                        }
                    });
                }
            });
        }, observerOptions);

        // Observe elements untuk animasi
        document.querySelectorAll('.stat-item, .fitur-item, .member').forEach(el => {
            observer.observe(el);
        });

        // Navbar scroll effect
        const navbar = document.querySelector('.navbar');
        let lastScroll = 0;
        
        window.addEventListener('scroll', () => {
            const currentScroll = window.pageYOffset;
            
            if (currentScroll > 100) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
            
            lastScroll = currentScroll;
        });


        // Hover effect untuk fitur items
        document.querySelectorAll('.fitur-item').forEach(item => {
            item.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-10px) scale(1.02)';
            });
            item.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });

        // Animate list items
        const listObserver = new IntersectionObserver((entries) => {
            entries.forEach((entry, index) => {
                if (entry.isIntersecting) {
                    setTimeout(() => {
                        entry.target.classList.add('animate');
                    }, index * 100);
                }
            });
        }, { threshold: 0.1 });

        document.querySelectorAll('.kenapa-list-item').forEach(item => {
            listObserver.observe(item);
        });

        // Scroll to top button
        const scrollToTopBtn = document.getElementById('scrollToTop');
        
        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 300) {
                scrollToTopBtn.classList.add('show');
            } else {
                scrollToTopBtn.classList.remove('show');
            }
        });

        scrollToTopBtn.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        // Animate kenapa section
        const kenapaObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate');
                }
            });
        }, { threshold: 0.2 });

        const kenapaText = document.querySelector('.kenapa-text');
        const kenapaImg = document.querySelector('.kenapa-img');
        if (kenapaText) observer.observe(kenapaText);
        if (kenapaImg) observer.observe(kenapaImg);

        // Animate footer
        const footerObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate');
                }
            });
        }, { threshold: 0.1 });

        const footerContent = document.querySelector('.footer-content');
        if (footerContent) footerObserver.observe(footerContent);

        // Mobile Menu Toggle
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mainNav = document.getElementById('mainNav');

        if (mobileMenuBtn && mainNav) {
            mobileMenuBtn.addEventListener('click', function() {
                mainNav.classList.toggle('active');
            });

            // Close menu when clicking outside
            document.addEventListener('click', function(event) {
                if (window.innerWidth <= 768) {
                    if (!mainNav.contains(event.target) && !mobileMenuBtn.contains(event.target)) {
                        mainNav.classList.remove('active');
                    }
                }
            });
        }
    </script>

</body>
</html>
