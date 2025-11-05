<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edukasi Service Motor - Mecha Learn</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
    <link rel="stylesheet" href="{{ asset('css/service.css') }}">
</head>
<body>

    <!-- Service Hero Section -->
    <section class="service-hero">
        <div class="container">
            <h1><i class="fas fa-tools"></i> Edukasi Service Motor</h1>
            <p>Pelajari cara merawat dan melakukan service motor dengan benar untuk menjaga performa dan keamanan berkendara Anda</p>
        </div>
    </section>

    <!-- Service Content -->
    <section class="service-content">
        <div class="container">
            <div class="row">
                <!-- Service Rutin -->
                <div class="col-md-6">
                    <div class="service-card">
                        <h3><i class="fas fa-calendar-check icon"></i> Service Rutin</h3>
                        <p class="text-muted mb-3">Service yang harus dilakukan secara berkala untuk menjaga kondisi motor</p>
                        <ul class="service-list">
                            <li><i class="fas fa-check"></i><span>Ganti oli mesin setiap 1.000-2.000 km</span></li>
                            <li><i class="fas fa-check"></i><span>Cek tekanan ban setiap minggu</span></li>
                            <li><i class="fas fa-check"></i><span>Bersihkan filter udara setiap 3.000 km</span></li>
                            <li><i class="fas fa-check"></i><span>Cek sistem rem setiap bulan</span></li>
                            <li><i class="fas fa-check"></i><span>Ganti busi setiap 5.000 km</span></li>
                            <li><i class="fas fa-check"></i><span>Cek rantai dan gear setiap 1.000 km</span></li>
                        </ul>
                    </div>
                </div>

                <!-- Service Berkala -->
                <div class="col-md-6">
                    <div class="service-card">
                        <h3><i class="fas fa-wrench icon"></i> Service Berkala</h3>
                        <p class="text-muted mb-3">Service yang dilakukan berdasarkan jarak tempuh atau waktu tertentu</p>
                        <ul class="service-list">
                            <li><i class="fas fa-check"></i><span>Service ringan setiap 2.500 km</span></li>
                            <li><i class="fas fa-check"></i><span>Service sedang setiap 5.000 km</span></li>
                            <li><i class="fas fa-check"></i><span>Service besar setiap 10.000 km</span></li>
                            <li><i class="fas fa-check"></i><span>Overhaul mesin setiap 25.000 km</span></li>
                            <li><i class="fas fa-check"></i><span>Service tahunan untuk semua komponen</span></li>
                            <li><i class="fas fa-check"></i><span>Inspeksi keamanan berkala</span></li>
                        </ul>
                    </div>
                </div>

                <!-- Jadwal Service -->
                <div class="col-12">
                    <div class="schedule-card">
                        <h4><i class="fas fa-clock"></i> Jadwal Service Motor</h4>
                        <div class="schedule-item">
                            <span class="service-name">Ganti Oli Mesin</span>
                            <span class="service-interval">Setiap 1.000-2.000 km</span>
                        </div>
                        <div class="schedule-item">
                            <span class="service-name">Ganti Filter Udara</span>
                            <span class="service-interval">Setiap 3.000 km</span>
                        </div>
                        <div class="schedule-item">
                            <span class="service-name">Ganti Busi</span>
                            <span class="service-interval">Setiap 5.000 km</span>
                        </div>
                        <div class="schedule-item">
                            <span class="service-name">Service Ringan</span>
                            <span class="service-interval">Setiap 2.500 km</span>
                        </div>
                        <div class="schedule-item">
                            <span class="service-name">Service Sedang</span>
                            <span class="service-interval">Setiap 5.000 km</span>
                        </div>
                        <div class="schedule-item">
                            <span class="service-name">Service Besar</span>
                            <span class="service-interval">Setiap 10.000 km</span>
                        </div>
                        <div class="schedule-item">
                            <span class="service-name">Cek Sistem Rem</span>
                            <span class="service-interval">Setiap bulan</span>
                        </div>
                        <div class="schedule-item">
                            <span class="service-name">Cek Tekanan Ban</span>
                            <span class="service-interval">Setiap minggu</span>
                        </div>
                    </div>
                </div>

                <!-- Tips Perawatan -->
                <div class="col-12">
                    <div class="tips-section">
                        <h3><i class="fas fa-lightbulb"></i> Tips Perawatan Motor</h3>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="tip-item">
                                    <h5><i class="fas fa-oil-can"></i> Perawatan Oli</h5>
                                    <p>Gunakan oli yang sesuai dengan spesifikasi motor dan ganti secara berkala untuk menjaga performa mesin.</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="tip-item">
                                    <h5><i class="fas fa-tire"></i> Perawatan Ban</h5>
                                    <p>Cek tekanan ban secara rutin dan perhatikan kondisi tapak ban untuk keamanan berkendara.</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="tip-item">
                                    <h5><i class="fas fa-shield-alt"></i> Sistem Rem</h5>
                                    <p>Pastikan sistem rem berfungsi dengan baik dan ganti kampas rem jika sudah aus.</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="tip-item">
                                    <h5><i class="fas fa-battery-half"></i> Sistem Kelistrikan</h5>
                                    <p>Cek kondisi aki dan sistem kelistrikan secara berkala untuk menghindari masalah di jalan.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <p>&copy; 2025 Mecha Learn. Semua hak dilindungi.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
