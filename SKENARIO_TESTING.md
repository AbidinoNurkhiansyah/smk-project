# SKENARIO TESTING SISTEM/FUNGSIONALITAS
## Platform Pembelajaran SMK

---

## DAFTAR ISI
1. [Pengujian Autentikasi & Autorisasi](#1-pengujian-autentikasi--autorisasi)
2. [Pengujian Dashboard & Tracking Progress](#2-pengujian-dashboard--tracking-progress)
3. [Pengujian Sistem Video Pembelajaran](#3-pengujian-sistem-video-pembelajaran)
4. [Pengujian Sistem Quiz/Game](#4-pengujian-sistem-quizgame)
5. [Pengujian Panel Admin](#5-pengujian-panel-admin)
6. [Pengujian API Endpoints](#6-pengujian-api-endpoints)
7. [Pengujian Integrasi](#7-pengujian-integrasi)
8. [Pengujian Sistem End-to-End](#8-pengujian-sistem-end-to-end)

---

## 1. PENGUJIAN AUTENTIKASI & AUTORISASI

### 1.1. Login
**ID Test:** AUTH-001  
**Deskripsi:** Pengguna dapat login dengan kredensial yang valid  
**Precondition:** Pengguna sudah terdaftar di sistem  
**Test Steps:**
1. Buka halaman login (`/login`)
2. Masukkan email yang valid
3. Masukkan password yang benar
4. Klik tombol "Login"

**Expected Result:**
- Pengguna berhasil login
- Redirect ke halaman dashboard
- Session terbuat
- Token autentikasi tersimpan (untuk API)

**Test Data:**
- Email: `siswa@example.com`
- Password: `password123`

---

**ID Test:** AUTH-002  
**Deskripsi:** Login gagal dengan password salah  
**Precondition:** Pengguna sudah terdaftar  
**Test Steps:**
1. Buka halaman login
2. Masukkan email yang valid
3. Masukkan password yang salah
4. Klik tombol "Login"

**Expected Result:**
- Login gagal
- Menampilkan pesan error "Email atau password salah"
- Tidak ada redirect
- Session tidak terbuat

---

**ID Test:** AUTH-003  
**Deskripsi:** Login gagal dengan email tidak terdaftar  
**Test Steps:**
1. Buka halaman login
2. Masukkan email yang tidak terdaftar
3. Masukkan password apapun
4. Klik tombol "Login"

**Expected Result:**
- Login gagal
- Menampilkan pesan error "Email atau password salah"

---

**ID Test:** AUTH-004  
**Deskripsi:** Validasi form login - field kosong  
**Test Steps:**
1. Buka halaman login
2. Biarkan email kosong
3. Biarkan password kosong
4. Klik tombol "Login"

**Expected Result:**
- Form tidak submit
- Menampilkan pesan validasi untuk setiap field yang kosong

---

**ID Test:** AUTH-005  
**Deskripsi:** Validasi format email  
**Test Steps:**
1. Buka halaman login
2. Masukkan email dengan format tidak valid (contoh: `invalid-email`)
3. Masukkan password
4. Klik tombol "Login"

**Expected Result:**
- Menampilkan pesan validasi "Format email tidak valid"

---

### 1.2. Register
**ID Test:** AUTH-006  
**Deskripsi:** Pengguna baru dapat mendaftar  
**Precondition:** Email belum terdaftar  
**Test Steps:**
1. Buka halaman register (`/register`)
2. Isi field nama lengkap
3. Isi field email yang belum terdaftar
4. Isi field password
5. Isi field konfirmasi password (sama dengan password)
6. Pilih kelas
7. Klik tombol "Daftar"

**Expected Result:**
- Registrasi berhasil
- Redirect ke halaman login atau dashboard
- Data pengguna tersimpan di database
- Password ter-hash dengan aman

**Test Data:**
- Nama: `Siswa Baru`
- Email: `siswabaru@example.com`
- Password: `password123`
- Konfirmasi Password: `password123`
- Kelas: `Kelas X`

---

**ID Test:** AUTH-007  
**Deskripsi:** Registrasi gagal jika email sudah terdaftar  
**Precondition:** Email sudah terdaftar  
**Test Steps:**
1. Buka halaman register
2. Isi semua field dengan data valid
3. Masukkan email yang sudah terdaftar
4. Klik tombol "Daftar"

**Expected Result:**
- Registrasi gagal
- Menampilkan pesan error "Email sudah terdaftar"
- Data tidak tersimpan

---

**ID Test:** AUTH-008  
**Deskripsi:** Validasi password tidak cocok  
**Test Steps:**
1. Buka halaman register
2. Isi semua field dengan data valid
3. Masukkan password: `password123`
4. Masukkan konfirmasi password: `password456` (berbeda)
5. Klik tombol "Daftar"

**Expected Result:**
- Menampilkan pesan error "Password dan konfirmasi password tidak cocok"
- Form tidak submit

---

**ID Test:** AUTH-009  
**Deskripsi:** Validasi panjang password minimum  
**Test Steps:**
1. Buka halaman register
2. Isi semua field dengan data valid
3. Masukkan password kurang dari 8 karakter (contoh: `pass123`)
4. Klik tombol "Daftar"

**Expected Result:**
- Menampilkan pesan validasi "Password minimal 8 karakter"

---

### 1.3. Logout
**ID Test:** AUTH-010  
**Deskripsi:** Pengguna dapat logout  
**Precondition:** Pengguna sudah login  
**Test Steps:**
1. Login sebagai pengguna
2. Klik tombol/logout link
3. Konfirmasi logout (jika ada)

**Expected Result:**
- Session dihapus
- Token autentikasi dihapus (untuk API)
- Redirect ke halaman login
- Tidak dapat mengakses halaman yang memerlukan autentikasi

---

### 1.4. Forgot Password
**ID Test:** AUTH-011  
**Deskripsi:** Request reset password dengan email valid  
**Test Steps:**
1. Buka halaman forgot password (`/forgot-password`)
2. Masukkan email yang terdaftar
3. Klik tombol "Kirim Link Reset"

**Expected Result:**
- Menampilkan pesan sukses
- Email reset password terkirim (jika email service aktif)
- Token reset password tersimpan di database

---

**ID Test:** AUTH-012  
**Deskripsi:** Request reset password dengan email tidak terdaftar  
**Test Steps:**
1. Buka halaman forgot password
2. Masukkan email yang tidak terdaftar
3. Klik tombol "Kirim Link Reset"

**Expected Result:**
- Menampilkan pesan sukses (untuk keamanan, tidak mengungkapkan email tidak terdaftar)
- Atau menampilkan pesan error sesuai kebijakan keamanan

---

**ID Test:** AUTH-013  
**Deskripsi:** Reset password dengan token valid  
**Precondition:** Token reset password sudah diterima  
**Test Steps:**
1. Buka link reset password dari email
2. Masukkan password baru
3. Masukkan konfirmasi password baru
4. Klik tombol "Reset Password"

**Expected Result:**
- Password berhasil diubah
- Token reset password dihapus/dinonaktifkan
- Redirect ke halaman login
- Dapat login dengan password baru

---

**ID Test:** AUTH-014  
**Deskripsi:** Reset password dengan token tidak valid/kadaluarsa  
**Test Steps:**
1. Buka link reset password dengan token yang tidak valid
2. Coba reset password

**Expected Result:**
- Menampilkan pesan error "Token tidak valid atau sudah kadaluarsa"
- Password tidak berubah

---

### 1.5. Change Password
**ID Test:** AUTH-015  
**Deskripsi:** Ubah password dari profil  
**Precondition:** Pengguna sudah login  
**Test Steps:**
1. Buka halaman profil (`/profile`)
2. Klik tab/button "Ubah Password"
3. Masukkan password lama yang benar
4. Masukkan password baru
5. Masukkan konfirmasi password baru
6. Klik tombol "Simpan"

**Expected Result:**
- Password berhasil diubah
- Menampilkan pesan sukses
- Dapat login dengan password baru

---

**ID Test:** AUTH-016  
**Deskripsi:** Ubah password gagal jika password lama salah  
**Test Steps:**
1. Buka halaman ubah password
2. Masukkan password lama yang salah
3. Masukkan password baru
4. Masukkan konfirmasi password baru
5. Klik tombol "Simpan"

**Expected Result:**
- Menampilkan pesan error "Password lama tidak benar"
- Password tidak berubah

---

### 1.6. Profile Management
**ID Test:** AUTH-017  
**Deskripsi:** Update profil pengguna  
**Precondition:** Pengguna sudah login  
**Test Steps:**
1. Buka halaman profil (`/profile`)
2. Ubah nama lengkap
3. Ubah email (jika diizinkan)
4. Klik tombol "Simpan"

**Expected Result:**
- Data profil berhasil diupdate
- Perubahan tersimpan di database
- Menampilkan pesan sukses

---

**ID Test:** AUTH-018  
**Deskripsi:** Upload foto profil  
**Test Steps:**
1. Buka halaman profil
2. Klik tombol "Unggah Foto"
3. Pilih file gambar (format: JPG, PNG)
4. Klik tombol "Simpan"

**Expected Result:**
- Foto profil berhasil diunggah
- File tersimpan di storage
- Foto ditampilkan di profil
- Path foto tersimpan di database

---

**ID Test:** AUTH-019  
**Deskripsi:** Upload foto profil dengan format tidak valid  
**Test Steps:**
1. Buka halaman profil
2. Klik tombol "Unggah Foto"
3. Pilih file dengan format tidak valid (contoh: PDF, DOC)
4. Klik tombol "Simpan"

**Expected Result:**
- Menampilkan pesan error "Format file tidak didukung"
- Foto tidak terunggah

---

**ID Test:** AUTH-020  
**Deskripsi:** Hapus foto profil  
**Precondition:** Pengguna memiliki foto profil  
**Test Steps:**
1. Buka halaman profil
2. Klik tombol "Hapus Foto"
3. Konfirmasi penghapusan

**Expected Result:**
- Foto profil terhapus
- File dihapus dari storage
- Path foto dihapus dari database
- Foto default ditampilkan

---

### 1.7. Authorization & Access Control
**ID Test:** AUTH-021  
**Deskripsi:** Akses halaman yang memerlukan autentikasi tanpa login  
**Test Steps:**
1. Pastikan tidak dalam keadaan login
2. Akses langsung URL `/dashboard`
3. Akses langsung URL `/video-pembelajaran`
4. Akses langsung URL `/admin`

**Expected Result:**
- Redirect ke halaman login
- Setelah login, redirect ke halaman yang diminta (jika ada)

---

**ID Test:** AUTH-022  
**Deskripsi:** Siswa tidak dapat mengakses halaman admin  
**Precondition:** Login sebagai siswa  
**Test Steps:**
1. Login sebagai siswa
2. Akses langsung URL `/admin/dashboard`
3. Akses langsung URL `/admin/videos`

**Expected Result:**
- Redirect ke halaman yang sesuai (dashboard siswa)
- Atau menampilkan pesan error "Akses ditolak"
- Tidak dapat mengakses fitur admin

---

**ID Test:** AUTH-023  
**Deskripsi:** Admin dapat mengakses semua halaman  
**Precondition:** Login sebagai admin  
**Test Steps:**
1. Login sebagai admin
2. Akses halaman admin (`/admin/dashboard`)
3. Akses halaman siswa (`/dashboard`)

**Expected Result:**
- Admin dapat mengakses semua halaman
- Tidak ada pembatasan akses

---

## 2. PENGUJIAN DASHBOARD & TRACKING PROGRESS

### 2.1. Dashboard Display
**ID Test:** DASH-001  
**Deskripsi:** Menampilkan dashboard dengan data lengkap  
**Precondition:** Pengguna sudah login  
**Test Steps:**
1. Login sebagai siswa
2. Akses halaman dashboard (`/dashboard`)

**Expected Result:**
- Menampilkan informasi pengguna (nama, kelas, foto profil)
- Menampilkan statistik:
  - Total video
  - Video selesai
  - Video sedang berjalan
  - Video belum dimulai
  - Total waktu menonton
  - Progress keseluruhan (persentase)
  - Total poin pengguna
- Menampilkan daftar video pembelajaran
- Data sesuai dengan kelas pengguna

---

**ID Test:** DASH-002  
**Deskripsi:** Dashboard untuk pengguna baru (belum ada progress)  
**Precondition:** Pengguna baru terdaftar dan belum menonton video  
**Test Steps:**
1. Login sebagai pengguna baru
2. Akses halaman dashboard

**Expected Result:**
- Statistik menampilkan 0 untuk semua metrik
- Progress keseluruhan: 0%
- Daftar video ditampilkan dengan status "Belum Dimulai"

---

**ID Test:** DASH-003  
**Deskripsi:** Dashboard menampilkan progress real-time  
**Precondition:** Pengguna sudah menonton beberapa video  
**Test Steps:**
1. Login sebagai siswa
2. Tonton beberapa video (selesai sebagian)
3. Akses halaman dashboard
4. Refresh halaman

**Expected Result:**
- Statistik terupdate sesuai progress terbaru
- Progress keseluruhan dihitung dengan benar
- Video yang sudah ditonton menampilkan progress bar

---

### 2.2. Progress Tracking
**ID Test:** DASH-004  
**Deskripsi:** Update progress video dari dashboard  
**Precondition:** Video sedang ditonton  
**Test Steps:**
1. Login sebagai siswa
2. Buka video pembelajaran
3. Tonton video hingga 50%
4. Progress otomatis tersimpan

**Expected Result:**
- Progress tersimpan di database
- Dashboard menampilkan progress terbaru
- Video ditandai sebagai "Sedang Berjalan"

---

**ID Test:** DASH-005  
**Deskripsi:** Video selesai ditonton (100%)  
**Test Steps:**
1. Login sebagai siswa
2. Tonton video hingga selesai (100%)
3. Progress tersimpan

**Expected Result:**
- Video ditandai sebagai "Selesai"
- Poin ditambahkan ke akun pengguna
- Statistik dashboard terupdate
- Progress keseluruhan meningkat

---

**ID Test:** DASH-006  
**Deskripsi:** Progress tidak bisa lebih dari 100%  
**Test Steps:**
1. Login sebagai siswa
2. Coba update progress video menjadi 150%

**Expected Result:**
- Progress dibatasi maksimal 100%
- Atau menampilkan pesan error jika input tidak valid

---

**ID Test:** DASH-007  
**Deskripsi:** Progress tidak bisa negatif  
**Test Steps:**
1. Login sebagai siswa
2. Coba update progress video menjadi -10%

**Expected Result:**
- Progress dibatasi minimal 0%
- Atau menampilkan pesan error jika input tidak valid

---

### 2.3. Statistics Calculation
**ID Test:** DASH-008  
**Deskripsi:** Perhitungan progress keseluruhan benar  
**Precondition:** Ada beberapa video dengan progress berbeda  
**Test Steps:**
1. Login sebagai siswa
2. Tonton 3 video:
   - Video 1: 100% (selesai)
   - Video 2: 50% (sedang berjalan)
   - Video 3: 0% (belum dimulai)
3. Akses dashboard

**Expected Result:**
- Progress keseluruhan = (100 + 50 + 0) / 3 = 50%
- Atau sesuai dengan formula yang digunakan

---

**ID Test:** DASH-009  
**Deskripsi:** Perhitungan total waktu menonton  
**Test Steps:**
1. Login sebagai siswa
2. Tonton beberapa video dengan durasi berbeda
3. Akses dashboard

**Expected Result:**
- Total waktu menonton dihitung dengan benar
- Menampilkan format waktu yang mudah dibaca (jam:menit)

---

## 3. PENGUJIAN SISTEM VIDEO PEMBELAJARAN

### 3.1. Video List
**ID Test:** VIDEO-001  
**Deskripsi:** Menampilkan daftar video pembelajaran  
**Precondition:** Pengguna sudah login  
**Test Steps:**
1. Login sebagai siswa
2. Akses halaman video pembelajaran (`/video-pembelajaran`)

**Expected Result:**
- Menampilkan daftar video sesuai kelas pengguna
- Setiap video menampilkan:
  - Thumbnail
  - Judul
  - Deskripsi
  - Durasi
  - Kategori
  - Tingkat kesulitan
  - Progress (jika sudah ditonton)
- Video diurutkan berdasarkan order_index

---

**ID Test:** VIDEO-002  
**Deskripsi:** Filter video berdasarkan kategori  
**Test Steps:**
1. Akses halaman video pembelajaran
2. Pilih kategori dari filter
3. Klik tombol "Filter"

**Expected Result:**
- Hanya menampilkan video dengan kategori yang dipilih
- Filter bekerja dengan benar

---

**ID Test:** VIDEO-003  
**Deskripsi:** Filter video berdasarkan tingkat kesulitan  
**Test Steps:**
1. Akses halaman video pembelajaran
2. Pilih tingkat kesulitan dari filter
3. Klik tombol "Filter"

**Expected Result:**
- Hanya menampilkan video dengan tingkat kesulitan yang dipilih
- Filter bekerja dengan benar

---

**ID Test:** VIDEO-004  
**Deskripsi:** Pencarian video berdasarkan judul  
**Test Steps:**
1. Akses halaman video pembelajaran
2. Masukkan kata kunci di search box
3. Tekan Enter atau klik tombol search

**Expected Result:**
- Menampilkan video yang judulnya mengandung kata kunci
- Pencarian case-insensitive
- Menampilkan pesan jika tidak ada hasil

---

### 3.2. Video Player
**ID Test:** VIDEO-005  
**Deskripsi:** Memutar video pembelajaran  
**Precondition:** Video tersedia  
**Test Steps:**
1. Login sebagai siswa
2. Klik salah satu video dari daftar
3. Video player terbuka

**Expected Result:**
- Video player menampilkan video yang dipilih
- Video dapat diputar (play)
- Video dapat di-pause
- Video dapat di-seek (maju/mundur)
- Menampilkan kontrol volume
- Menampilkan kontrol fullscreen

---

**ID Test:** VIDEO-006  
**Deskripsi:** Progress bar video menampilkan progress yang benar  
**Precondition:** Video sudah pernah ditonton  
**Test Steps:**
1. Login sebagai siswa
2. Buka video yang sudah pernah ditonton (misal 50%)
3. Video player terbuka

**Expected Result:**
- Progress bar menampilkan posisi 50%
- Video dapat dilanjutkan dari posisi terakhir
- Atau mulai dari awal sesuai preferensi

---

**ID Test:** VIDEO-007  
**Deskripsi:** Update progress video secara otomatis  
**Test Steps:**
1. Login sebagai siswa
2. Buka video
3. Tonton video hingga 30%
4. Progress otomatis tersimpan

**Expected Result:**
- Progress tersimpan di database setiap interval tertentu (misal setiap 10 detik)
- Progress terupdate di dashboard
- Tidak perlu refresh halaman

---

**ID Test:** VIDEO-008  
**Deskripsi:** Video selesai ditonton (100%)  
**Test Steps:**
1. Login sebagai siswa
2. Tonton video hingga akhir
3. Video mencapai 100%

**Expected Result:**
- Video ditandai sebagai selesai
- Poin ditambahkan ke akun pengguna
- Menampilkan pesan sukses (opsional)
- Progress tersimpan sebagai 100%

---

**ID Test:** VIDEO-009  
**Deskripsi:** Video tidak dapat diakses jika tidak aktif  
**Precondition:** Admin menonaktifkan video  
**Test Steps:**
1. Admin menonaktifkan video
2. Siswa mencoba mengakses video tersebut

**Expected Result:**
- Video tidak muncul di daftar
- Atau menampilkan pesan "Video tidak tersedia"
- Tidak dapat mengakses video player

---

**ID Test:** VIDEO-010  
**Deskripsi:** Video hanya dapat diakses oleh kelas yang sesuai  
**Precondition:** Video dikhususkan untuk kelas tertentu  
**Test Steps:**
1. Login sebagai siswa kelas X
2. Coba akses video yang khusus untuk kelas XI

**Expected Result:**
- Video tidak muncul di daftar
- Atau menampilkan pesan "Video tidak tersedia untuk kelas Anda"

---

### 3.3. Video Progress API
**ID Test:** VIDEO-011  
**Deskripsi:** API update progress video  
**Precondition:** Pengguna sudah login dan memiliki token  
**Test Steps:**
1. Login melalui API dan dapatkan token
2. Panggil API `POST /api/videos/{id}/progress`
3. Kirim data: `{"progress": 75}`

**Expected Result:**
- API mengembalikan response sukses
- Progress tersimpan di database
- Response berisi data progress terbaru

**Expected Response:**
```json
{
  "success": true,
  "message": "Progress video berhasil diperbarui.",
  "data": {
    "progress": 75,
    "is_completed": false,
    "points_earned": 0
  }
}
```

---

## 4. PENGUJIAN SISTEM QUIZ/GAME

### 4.1. Quiz List
**ID Test:** QUIZ-001  
**Deskripsi:** Menampilkan daftar quiz yang tersedia  
**Precondition:** Pengguna sudah login  
**Test Steps:**
1. Login sebagai siswa
2. Akses halaman quiz/game (`/game`)

**Expected Result:**
- Menampilkan daftar quiz sesuai kelas pengguna
- Setiap quiz menampilkan:
  - Judul quiz
  - Deskripsi
  - Jumlah soal
  - Waktu yang diberikan
  - Tingkat kesulitan
  - Status (sudah dikerjakan/belum)
  - Skor tertinggi (jika sudah dikerjakan)

---

**ID Test:** QUIZ-002  
**Deskripsi:** Quiz yang sudah dikerjakan ditandai  
**Precondition:** Pengguna sudah mengerjakan beberapa quiz  
**Test Steps:**
1. Login sebagai siswa
2. Akses halaman quiz
3. Lihat daftar quiz

**Expected Result:**
- Quiz yang sudah dikerjakan ditandai dengan badge/indikator
- Menampilkan skor tertinggi yang diperoleh
- Menampilkan tanggal terakhir dikerjakan

---

### 4.2. Quiz Play
**ID Test:** QUIZ-003  
**Deskripsi:** Memulai quiz baru  
**Precondition:** Quiz tersedia dan belum dikerjakan  
**Test Steps:**
1. Login sebagai siswa
2. Klik quiz yang ingin dikerjakan
3. Klik tombol "Mulai Quiz"

**Expected Result:**
- Quiz dimulai
- Timer mulai berjalan
- Soal pertama ditampilkan
- Waktu mulai dicatat di database

---

**ID Test:** QUIZ-004  
**Deskripsi:** Menampilkan soal quiz dengan benar  
**Test Steps:**
1. Mulai quiz
2. Lihat soal yang ditampilkan

**Expected Result:**
- Soal ditampilkan dengan jelas
- Pilihan jawaban (A, B, C, D) ditampilkan
- Gambar soal ditampilkan (jika ada)
- Nomor soal dan total soal ditampilkan
- Progress bar menampilkan progress pengerjaan

---

**ID Test:** QUIZ-005  
**Deskripsi:** Navigasi antar soal  
**Test Steps:**
1. Mulai quiz
2. Pilih jawaban untuk soal pertama
3. Klik tombol "Soal Berikutnya"
4. Klik tombol "Soal Sebelumnya"

**Expected Result:**
- Dapat berpindah ke soal berikutnya
- Dapat kembali ke soal sebelumnya
- Jawaban yang sudah dipilih tersimpan
- Dapat mengubah jawaban sebelum submit

---

**ID Test:** QUIZ-006  
**Deskripsi:** Timer quiz berjalan dengan benar  
**Test Steps:**
1. Mulai quiz dengan waktu 30 menit
2. Perhatikan timer
3. Tunggu beberapa detik

**Expected Result:**
- Timer menampilkan waktu tersisa
- Timer berkurang setiap detik
- Timer menampilkan format yang mudah dibaca (MM:SS atau HH:MM:SS)

---

**ID Test:** QUIZ-007  
**Deskripsi:** Quiz otomatis submit ketika waktu habis  
**Precondition:** Timer quiz mencapai 0  
**Test Steps:**
1. Mulai quiz
2. Tunggu hingga waktu habis (atau set timer untuk testing)

**Expected Result:**
- Quiz otomatis submit ketika waktu habis
- Jawaban yang sudah dipilih tetap tersimpan
- Soal yang belum dijawab dianggap salah/kosong
- Menampilkan hasil quiz

---

**ID Test:** QUIZ-008  
**Deskripsi:** Submit quiz sebelum waktu habis  
**Test Steps:**
1. Mulai quiz
2. Jawab beberapa soal
3. Klik tombol "Submit Quiz"
4. Konfirmasi submit

**Expected Result:**
- Quiz berhasil di-submit
- Timer berhenti
- Menampilkan hasil quiz
- Skor dihitung dan disimpan

---

**ID Test:** QUIZ-009  
**Deskripsi:** Validasi submit quiz - ada soal yang belum dijawab  
**Test Steps:**
1. Mulai quiz
2. Jawab beberapa soal (tidak semua)
3. Klik tombol "Submit Quiz"

**Expected Result:**
- Menampilkan peringatan "Ada soal yang belum dijawab"
- Atau langsung submit dengan soal kosong dianggap salah
- Konfirmasi submit tetap muncul

---

### 4.3. Quiz Results
**ID Test:** QUIZ-010  
**Deskripsi:** Menampilkan hasil quiz dengan benar  
**Precondition:** Quiz sudah di-submit  
**Test Steps:**
1. Submit quiz
2. Lihat halaman hasil

**Expected Result:**
- Menampilkan:
  - Total skor
  - Jumlah jawaban benar
  - Jumlah jawaban salah
  - Total soal
  - Persentase benar
  - Grade (A, B, C, D, E)
  - Poin yang diperoleh
- Menampilkan detail jawaban (soal, jawaban user, jawaban benar)
- Menampilkan tombol "Kembali ke Daftar Quiz"

---

**ID Test:** QUIZ-011  
**Deskripsi:** Poin ditambahkan setelah quiz selesai  
**Precondition:** Quiz berhasil di-submit  
**Test Steps:**
1. Catat poin awal pengguna
2. Selesaikan quiz dengan skor tertentu
3. Cek poin pengguna setelah quiz

**Expected Result:**
- Poin ditambahkan sesuai skor quiz
- Poin terupdate di database
- Poin terupdate di dashboard

---

**ID Test:** QUIZ-012  
**Deskripsi:** Bonus poin untuk attempt pertama  
**Precondition:** Quiz belum pernah dikerjakan  
**Test Steps:**
1. Kerjakan quiz untuk pertama kali
2. Selesaikan quiz
3. Lihat poin yang diperoleh

**Expected Result:**
- Poin dasar dari skor quiz
- Bonus poin untuk attempt pertama (jika ada)
- Total poin = poin dasar + bonus

---

**ID Test:** QUIZ-013  
**Deskripsi:** Quiz dapat dikerjakan ulang  
**Precondition:** Quiz sudah pernah dikerjakan  
**Test Steps:**
1. Login sebagai siswa
2. Akses quiz yang sudah pernah dikerjakan
3. Klik tombol "Kerjakan Lagi"

**Expected Result:**
- Quiz dapat dimulai lagi
- Skor sebelumnya tetap tersimpan
- Skor baru akan menggantikan atau ditambahkan sesuai kebijakan

---

### 4.4. Leaderboard
**ID Test:** QUIZ-014  
**Deskripsi:** Menampilkan leaderboard untuk kelas  
**Precondition:** Beberapa siswa sudah mengerjakan quiz  
**Test Steps:**
1. Login sebagai siswa
2. Akses halaman leaderboard (`/game/leaderboard`)

**Expected Result:**
- Menampilkan ranking siswa berdasarkan poin
- Menampilkan:
  - Peringkat
  - Nama siswa
  - Foto profil
  - Total poin
  - Jumlah quiz yang dikerjakan
- Leaderboard diurutkan dari poin tertinggi ke terendah
- Posisi pengguna ditandai (highlight)

---

**ID Test:** QUIZ-015  
**Deskripsi:** Leaderboard hanya menampilkan siswa dari kelas yang sama  
**Precondition:** Ada siswa dari kelas berbeda  
**Test Steps:**
1. Login sebagai siswa kelas X
2. Akses leaderboard

**Expected Result:**
- Hanya menampilkan siswa dari kelas X
- Tidak menampilkan siswa dari kelas lain

---

**ID Test:** QUIZ-016  
**Deskripsi:** Leaderboard real-time update  
**Precondition:** Beberapa siswa aktif mengerjakan quiz  
**Test Steps:**
1. Akses leaderboard
2. Selesaikan quiz untuk mendapatkan poin
3. Refresh leaderboard

**Expected Result:**
- Peringkat terupdate sesuai poin terbaru
- Posisi pengguna berubah jika poin meningkat

---

### 4.5. Quiz API
**ID Test:** QUIZ-017  
**Deskripsi:** API mendapatkan daftar quiz  
**Precondition:** Pengguna sudah login dan memiliki token  
**Test Steps:**
1. Login melalui API dan dapatkan token
2. Panggil API `GET /api/quizzes`

**Expected Result:**
- API mengembalikan daftar quiz
- Response berisi data quiz lengkap

---

**ID Test:** QUIZ-018  
**Deskripsi:** API mendapatkan detail quiz  
**Test Steps:**
1. Login melalui API
2. Panggil API `GET /api/quizzes/{id}`

**Expected Result:**
- API mengembalikan detail quiz dan soal-soalnya
- Response berisi data lengkap quiz

**Expected Response:**
```json
{
  "success": true,
  "data": {
    "quiz": {
      "id": 1,
      "quiz_title": "Quiz Matematika",
      "quiz_description": "...",
      "total_questions": 10,
      "time_limit": 30,
      "difficulty": "sedang"
    },
    "start_time": 1234567890,
    "end_time": 1234569690,
    "time_limit_seconds": 1800,
    "questions": [...]
  }
}
```

---

**ID Test:** QUIZ-019  
**Deskripsi:** API submit quiz  
**Test Steps:**
1. Login melalui API
2. Mulai quiz melalui API
3. Panggil API `POST /api/quizzes/{id}/submit`
4. Kirim data jawaban

**Expected Result:**
- API mengembalikan hasil quiz
- Skor dihitung dengan benar
- Poin ditambahkan ke akun pengguna

**Request Body:**
```json
{
  "answers": {
    "1": "A",
    "2": "B",
    "3": "C"
  }
}
```

---

## 5. PENGUJIAN PANEL ADMIN

### 5.1. Admin Dashboard
**ID Test:** ADMIN-001  
**Deskripsi:** Menampilkan dashboard admin  
**Precondition:** Login sebagai admin  
**Test Steps:**
1. Login sebagai admin
2. Akses halaman admin dashboard (`/admin/dashboard`)

**Expected Result:**
- Menampilkan statistik:
  - Total siswa
  - Total video
  - Total quiz
  - Total kelas
  - Rata-rata progress siswa
  - Aktivitas terkini
- Menampilkan grafik/chart (jika ada)
- Menampilkan menu navigasi admin

---

### 5.2. Video Management
**ID Test:** ADMIN-002  
**Deskripsi:** Menampilkan daftar video  
**Test Steps:**
1. Login sebagai admin
2. Akses halaman admin videos (`/admin/videos`)

**Expected Result:**
- Menampilkan daftar semua video
- Setiap video menampilkan:
  - Thumbnail
  - Judul
  - Kategori
  - Durasi
  - Status (aktif/tidak aktif)
  - Jumlah penonton
  - Tombol edit dan delete

---

**ID Test:** ADMIN-003  
**Deskripsi:** Menambah video baru  
**Test Steps:**
1. Login sebagai admin
2. Akses halaman admin videos
3. Klik tombol "Tambah Video"
4. Isi form:
   - Judul
   - Deskripsi
   - Kategori
   - Durasi
   - Upload thumbnail
   - Upload video file atau masukkan URL
   - Tingkat kesulitan
   - Pilih kelas yang dapat mengakses
   - Order index
   - Status (aktif/tidak aktif)
5. Klik tombol "Simpan"

**Expected Result:**
- Video berhasil ditambahkan
- Data tersimpan di database
- File video dan thumbnail tersimpan di storage
- Video muncul di daftar
- Video dapat diakses oleh siswa kelas yang dipilih

---

**ID Test:** ADMIN-004  
**Deskripsi:** Validasi form tambah video  
**Test Steps:**
1. Login sebagai admin
2. Akses form tambah video
3. Biarkan beberapa field wajib kosong
4. Klik tombol "Simpan"

**Expected Result:**
- Menampilkan pesan validasi untuk setiap field yang kosong
- Form tidak submit
- Data tidak tersimpan

---

**ID Test:** ADMIN-005  
**Deskripsi:** Edit video  
**Precondition:** Video sudah ada  
**Test Steps:**
1. Login sebagai admin
2. Akses daftar video
3. Klik tombol "Edit" pada salah satu video
4. Ubah beberapa data
5. Klik tombol "Simpan"

**Expected Result:**
- Data video berhasil diupdate
- Perubahan tersimpan di database
- Video terupdate di daftar
- Perubahan terlihat oleh siswa

---

**ID Test:** ADMIN-006  
**Deskripsi:** Hapus video  
**Precondition:** Video sudah ada  
**Test Steps:**
1. Login sebagai admin
2. Akses daftar video
3. Klik tombol "Delete" pada salah satu video
4. Konfirmasi penghapusan

**Expected Result:**
- Video berhasil dihapus
- Data terhapus dari database
- File video dan thumbnail terhapus dari storage
- Video tidak muncul di daftar
- Video tidak dapat diakses oleh siswa

---

**ID Test:** ADMIN-007  
**Deskripsi:** Aktifkan/nonaktifkan video  
**Test Steps:**
1. Login sebagai admin
2. Akses daftar video
3. Klik toggle untuk mengaktifkan/menonaktifkan video

**Expected Result:**
- Status video berubah
- Video aktif dapat diakses oleh siswa
- Video tidak aktif tidak dapat diakses oleh siswa
- Status terupdate di database

---

### 5.3. Student Management
**ID Test:** ADMIN-008  
**Deskripsi:** Menampilkan daftar siswa  
**Test Steps:**
1. Login sebagai admin
2. Akses halaman admin students (`/admin/students`)

**Expected Result:**
- Menampilkan daftar semua siswa
- Setiap siswa menampilkan:
  - Nama
  - Email
  - Kelas
  - Foto profil
  - Total poin
  - Progress keseluruhan
  - Tanggal registrasi
  - Tombol lihat detail dan delete

---

**ID Test:** ADMIN-009  
**Deskripsi:** Filter siswa berdasarkan kelas  
**Test Steps:**
1. Login sebagai admin
2. Akses daftar siswa
3. Pilih kelas dari filter
4. Klik tombol "Filter"

**Expected Result:**
- Hanya menampilkan siswa dari kelas yang dipilih
- Filter bekerja dengan benar

---

**ID Test:** ADMIN-010  
**Deskripsi:** Pencarian siswa  
**Test Steps:**
1. Login sebagai admin
2. Akses daftar siswa
3. Masukkan kata kunci di search box
4. Tekan Enter

**Expected Result:**
- Menampilkan siswa yang namanya atau emailnya mengandung kata kunci
- Pencarian case-insensitive

---

**ID Test:** ADMIN-011  
**Deskripsi:** Lihat detail progress siswa  
**Precondition:** Siswa sudah ada  
**Test Steps:**
1. Login sebagai admin
2. Akses daftar siswa
3. Klik tombol "Lihat Progress" pada salah satu siswa

**Expected Result:**
- Menampilkan detail progress siswa:
  - Daftar video yang ditonton
  - Progress setiap video
  - Daftar quiz yang dikerjakan
  - Skor setiap quiz
  - Total poin
  - Grafik progress (jika ada)

---

**ID Test:** ADMIN-012  
**Deskripsi:** Hapus siswa  
**Precondition:** Siswa sudah ada  
**Test Steps:**
1. Login sebagai admin
2. Akses daftar siswa
3. Klik tombol "Delete" pada salah satu siswa
4. Konfirmasi penghapusan

**Expected Result:**
- Siswa berhasil dihapus
- Data terhapus dari database
- Progress dan data terkait terhapus (atau di-soft delete)
- Siswa tidak dapat login lagi

---

**ID Test:** ADMIN-013  
**Deskripsi:** Export data siswa ke Excel/CSV  
**Test Steps:**
1. Login sebagai admin
2. Akses daftar siswa
3. Klik tombol "Export"
4. Pilih format (Excel/CSV)

**Expected Result:**
- File Excel/CSV terunduh
- File berisi data siswa lengkap
- Format file benar dan dapat dibuka

---

### 5.4. Analytics
**ID Test:** ADMIN-014  
**Deskripsi:** Menampilkan halaman analytics  
**Test Steps:**
1. Login sebagai admin
2. Akses halaman analytics (`/admin/analytics`)

**Expected Result:**
- Menampilkan berbagai analitik:
  - Grafik progress per kelas
  - Grafik aktivitas siswa
  - Statistik video yang paling banyak ditonton
  - Statistik quiz yang paling banyak dikerjakan
  - Rata-rata skor quiz per kelas
  - Clustering data siswa (jika ada)

---

**ID Test:** ADMIN-015  
**Deskripsi:** Filter analytics berdasarkan periode  
**Test Steps:**
1. Login sebagai admin
2. Akses halaman analytics
3. Pilih periode (minggu ini, bulan ini, tahun ini, custom)
4. Klik tombol "Filter"

**Expected Result:**
- Data analytics terfilter sesuai periode
- Grafik terupdate
- Data yang ditampilkan sesuai periode yang dipilih

---

**ID Test:** ADMIN-016  
**Deskripsi:** Export analytics ke Excel/CSV  
**Test Steps:**
1. Login sebagai admin
2. Akses halaman analytics
3. Klik tombol "Export Analytics"

**Expected Result:**
- File Excel/CSV terunduh
- File berisi data analytics lengkap
- Format file benar

---

### 5.5. Quiz Management (Teacher Quiz)
**ID Test:** ADMIN-017  
**Deskripsi:** Menampilkan daftar quiz  
**Test Steps:**
1. Login sebagai admin
2. Akses halaman teacher quiz (`/admin/teacher-quiz`)

**Expected Result:**
- Menampilkan daftar semua quiz
- Setiap quiz menampilkan:
  - Judul quiz
  - Deskripsi
  - Jumlah soal
  - Waktu yang diberikan
  - Tingkat kesulitan
  - Kelas yang dapat mengakses
  - Status
  - Tombol edit dan delete

---

**ID Test:** ADMIN-018  
**Deskripsi:** Membuat quiz baru  
**Test Steps:**
1. Login sebagai admin
2. Akses halaman teacher quiz
3. Klik tombol "Buat Quiz"
4. Isi form:
   - Judul quiz
   - Deskripsi
   - Waktu yang diberikan
   - Tingkat kesulitan
   - Pilih kelas
5. Tambahkan soal-soal:
   - Pertanyaan
   - Upload gambar (opsional)
   - Pilihan jawaban (A, B, C, D)
   - Jawaban benar
   - Poin
6. Klik tombol "Simpan"

**Expected Result:**
- Quiz berhasil dibuat
- Data tersimpan di database
- Soal-soal tersimpan
- Quiz dapat diakses oleh siswa kelas yang dipilih

---

**ID Test:** ADMIN-019  
**Deskripsi:** Edit quiz  
**Precondition:** Quiz sudah ada  
**Test Steps:**
1. Login sebagai admin
2. Akses daftar quiz
3. Klik tombol "Edit" pada salah satu quiz
4. Ubah beberapa data
5. Tambah/hapus/edit soal
6. Klik tombol "Simpan"

**Expected Result:**
- Quiz berhasil diupdate
- Perubahan tersimpan di database
- Quiz terupdate di daftar
- Perubahan terlihat oleh siswa

---

**ID Test:** ADMIN-020  
**Deskripsi:** Hapus quiz  
**Precondition:** Quiz sudah ada  
**Test Steps:**
1. Login sebagai admin
2. Akses daftar quiz
3. Klik tombol "Delete" pada salah satu quiz
4. Konfirmasi penghapusan

**Expected Result:**
- Quiz berhasil dihapus
- Data terhapus dari database
- Soal-soal terhapus
- Quiz tidak muncul di daftar
- Quiz tidak dapat diakses oleh siswa

---

### 5.6. Quiz Analytics
**ID Test:** ADMIN-021  
**Deskripsi:** Menampilkan quiz analytics  
**Test Steps:**
1. Login sebagai admin
2. Akses halaman quiz analytics (`/admin/quiz-analytics`)

**Expected Result:**
- Menampilkan analitik quiz:
  - Daftar quiz dan jumlah siswa yang mengerjakan
  - Rata-rata skor per quiz
  - Distribusi skor
  - Soal yang paling banyak salah
  - Grafik performa siswa per quiz

---

**ID Test:** ADMIN-022  
**Deskripsi:** Lihat detail hasil quiz siswa  
**Test Steps:**
1. Login sebagai admin
2. Akses quiz analytics
3. Klik salah satu siswa
4. Lihat detail hasil quiz

**Expected Result:**
- Menampilkan detail hasil quiz siswa:
  - Soal dan jawaban siswa
  - Jawaban benar
  - Skor per soal
  - Total skor
  - Waktu pengerjaan

---

### 5.7. Leaderboard Management
**ID Test:** ADMIN-023  
**Deskripsi:** Menampilkan leaderboard admin  
**Test Steps:**
1. Login sebagai admin
2. Akses halaman admin leaderboard (`/admin/leaderboard`)

**Expected Result:**
- Menampilkan leaderboard semua kelas
- Dapat filter berdasarkan kelas
- Menampilkan ranking siswa dengan poin tertinggi

---

## 6. PENGUJIAN API ENDPOINTS

### 6.1. Authentication API
**ID Test:** API-001  
**Deskripsi:** API Login berhasil  
**Test Steps:**
1. Panggil API `POST /api/login`
2. Kirim data:
```json
{
  "email": "siswa@example.com",
  "password": "password123"
}
```

**Expected Result:**
- Status code: 200
- Response berisi token dan data user
- Token dapat digunakan untuk request berikutnya

**Expected Response:**
```json
{
  "success": true,
  "message": "Login berhasil.",
  "data": {
    "token": "abc123...",
    "user": {
      "user_id": 1,
      "user_name": "John Doe",
      "email": "user@example.com",
      "role": "siswa",
      "class_id": 1,
      "class_name": "Kelas X",
      "profile_picture": "http://..."
    }
  }
}
```

---

**ID Test:** API-002  
**Deskripsi:** API Login gagal dengan kredensial salah  
**Test Steps:**
1. Panggil API `POST /api/login`
2. Kirim data dengan password salah

**Expected Result:**
- Status code: 401 atau 400
- Response berisi pesan error
- Tidak ada token dikembalikan

---

**ID Test:** API-003  
**Deskripsi:** API Register berhasil  
**Test Steps:**
1. Panggil API `POST /api/register`
2. Kirim data:
```json
{
  "user_name": "Siswa Baru",
  "email": "siswabaru@example.com",
  "password": "password123",
  "password_confirmation": "password123",
  "class_id": 1
}
```

**Expected Result:**
- Status code: 201
- Response berisi token dan data user
- User baru terdaftar di database

---

**ID Test:** API-004  
**Deskripsi:** API Logout berhasil  
**Precondition:** Sudah login dan memiliki token  
**Test Steps:**
1. Login melalui API dan dapatkan token
2. Panggil API `POST /api/logout`
3. Kirim header: `Authorization: Bearer {token}`

**Expected Result:**
- Status code: 200
- Token dihapus/dinonaktifkan
- Tidak dapat menggunakan token lagi untuk request

---

**ID Test:** API-005  
**Deskripsi:** API Get User Info (me)  
**Precondition:** Sudah login dan memiliki token  
**Test Steps:**
1. Login melalui API dan dapatkan token
2. Panggil API `GET /api/me`
3. Kirim header: `Authorization: Bearer {token}`

**Expected Result:**
- Status code: 200
- Response berisi data user lengkap

---

### 6.2. Profile API
**ID Test:** API-006  
**Deskripsi:** API Get Profile  
**Precondition:** Sudah login  
**Test Steps:**
1. Login melalui API
2. Panggil API `GET /api/profile`
3. Kirim header: `Authorization: Bearer {token}`

**Expected Result:**
- Status code: 200
- Response berisi data profil user

---

**ID Test:** API-007  
**Deskripsi:** API Update Profile  
**Test Steps:**
1. Login melalui API
2. Panggil API `PUT /api/profile`
3. Kirim header: `Authorization: Bearer {token}`
4. Kirim data:
```json
{
  "user_name": "Nama Baru",
  "email": "emailbaru@example.com"
}
```

**Expected Result:**
- Status code: 200
- Profil berhasil diupdate
- Response berisi data profil terbaru

---

**ID Test:** API-008  
**Deskripsi:** API Update Profile Picture  
**Test Steps:**
1. Login melalui API
2. Panggil API `POST /api/profile/picture`
3. Kirim header: `Authorization: Bearer {token}`
4. Kirim file gambar sebagai multipart/form-data

**Expected Result:**
- Status code: 200
- Foto profil berhasil diunggah
- Response berisi URL foto profil baru

---

**ID Test:** API-009  
**Deskripsi:** API Delete Profile Picture  
**Precondition:** User memiliki foto profil  
**Test Steps:**
1. Login melalui API
2. Panggil API `DELETE /api/profile/picture`
3. Kirim header: `Authorization: Bearer {token}`

**Expected Result:**
- Status code: 200
- Foto profil terhapus
- Response berisi konfirmasi

---

### 6.3. Dashboard API
**ID Test:** API-010  
**Deskripsi:** API Get Dashboard  
**Test Steps:**
1. Login melalui API
2. Panggil API `GET /api/dashboard`
3. Kirim header: `Authorization: Bearer {token}`

**Expected Result:**
- Status code: 200
- Response berisi data dashboard lengkap:
  - Data user
  - Statistik
  - Daftar video

---

**ID Test:** API-011  
**Deskripsi:** API Get Dashboard Progress  
**Test Steps:**
1. Login melalui API
2. Panggil API `GET /api/dashboard/progress`
3. Kirim header: `Authorization: Bearer {token}`

**Expected Result:**
- Status code: 200
- Response berisi data progress saja

---

### 6.4. Video API
**ID Test:** API-012  
**Deskripsi:** API Get Videos List  
**Test Steps:**
1. Login melalui API
2. Panggil API `GET /api/videos`
3. Kirim header: `Authorization: Bearer {token}`

**Expected Result:**
- Status code: 200
- Response berisi daftar video untuk kelas user

---

**ID Test:** API-013  
**Deskripsi:** API Get Video Detail  
**Test Steps:**
1. Login melalui API
2. Panggil API `GET /api/videos/{id}`
3. Kirim header: `Authorization: Bearer {token}`

**Expected Result:**
- Status code: 200
- Response berisi detail video lengkap

---

**ID Test:** API-014  
**Deskripsi:** API Update Video Progress  
**Test Steps:**
1. Login melalui API
2. Panggil API `POST /api/videos/{id}/progress`
3. Kirim header: `Authorization: Bearer {token}`
4. Kirim data:
```json
{
  "progress": 75
}
```

**Expected Result:**
- Status code: 200
- Progress tersimpan
- Response berisi data progress terbaru

---

### 6.5. Quiz API
**ID Test:** API-015  
**Deskripsi:** API Get Quizzes List  
**Test Steps:**
1. Login melalui API
2. Panggil API `GET /api/quizzes`
3. Kirim header: `Authorization: Bearer {token}`

**Expected Result:**
- Status code: 200
- Response berisi daftar quiz untuk kelas user

---

**ID Test:** API-016  
**Deskripsi:** API Get Quiz Detail  
**Test Steps:**
1. Login melalui API
2. Panggil API `GET /api/quizzes/{id}`
3. Kirim header: `Authorization: Bearer {token}`

**Expected Result:**
- Status code: 200
- Response berisi detail quiz dan soal-soalnya

---

**ID Test:** API-017  
**Deskripsi:** API Submit Quiz  
**Test Steps:**
1. Login melalui API
2. Mulai quiz melalui API
3. Panggil API `POST /api/quizzes/{id}/submit`
4. Kirim header: `Authorization: Bearer {token}`
5. Kirim data:
```json
{
  "answers": {
    "1": "A",
    "2": "B",
    "3": "C"
  }
}
```

**Expected Result:**
- Status code: 200
- Quiz berhasil di-submit
- Response berisi hasil quiz (skor, grade, poin)

---

**ID Test:** API-018  
**Deskripsi:** API Get Quiz Time  
**Test Steps:**
1. Login melalui API
2. Mulai quiz melalui API
3. Panggil API `GET /api/quizzes/{id}/time`
4. Kirim header: `Authorization: Bearer {token}`

**Expected Result:**
- Status code: 200
- Response berisi waktu tersisa untuk quiz

---

**ID Test:** API-019  
**Deskripsi:** API Get Leaderboard  
**Test Steps:**
1. Login melalui API
2. Panggil API `GET /api/leaderboard`
3. Kirim header: `Authorization: Bearer {token}`

**Expected Result:**
- Status code: 200
- Response berisi leaderboard untuk kelas user

---

### 6.6. API Error Handling
**ID Test:** API-020  
**Deskripsi:** API mengembalikan error 401 untuk request tanpa token  
**Test Steps:**
1. Panggil API protected tanpa header Authorization
2. Contoh: `GET /api/dashboard`

**Expected Result:**
- Status code: 401
- Response berisi pesan error "Unauthorized"

---

**ID Test:** API-021  
**Deskripsi:** API mengembalikan error 401 untuk request dengan token tidak valid  
**Test Steps:**
1. Panggil API protected dengan token tidak valid
2. Kirim header: `Authorization: Bearer invalid_token`

**Expected Result:**
- Status code: 401
- Response berisi pesan error "Token tidak valid"

---

**ID Test:** API-022  
**Deskripsi:** API mengembalikan error 404 untuk resource tidak ditemukan  
**Test Steps:**
1. Login melalui API
2. Panggil API `GET /api/videos/99999` (ID tidak ada)
3. Kirim header: `Authorization: Bearer {token}`

**Expected Result:**
- Status code: 404
- Response berisi pesan error "Video tidak ditemukan"

---

**ID Test:** API-023  
**Deskripsi:** API mengembalikan error 422 untuk validasi gagal  
**Test Steps:**
1. Panggil API `POST /api/register`
2. Kirim data dengan field yang tidak valid (contoh: email tidak valid)

**Expected Result:**
- Status code: 422
- Response berisi pesan validasi untuk setiap field yang error

---

**ID Test:** API-024  
**Deskripsi:** API mengembalikan error 400 untuk bad request  
**Test Steps:**
1. Panggil API dengan format request yang salah
2. Contoh: kirim JSON yang tidak valid

**Expected Result:**
- Status code: 400
- Response berisi pesan error "Bad Request"

---

## 7. PENGUJIAN INTEGRASI

### 7.1. Integration: Video & Progress Tracking
**ID Test:** INT-001  
**Deskripsi:** Progress video terupdate di dashboard setelah menonton  
**Test Steps:**
1. Login sebagai siswa
2. Akses dashboard dan catat progress awal
3. Tonton video hingga 50%
4. Kembali ke dashboard

**Expected Result:**
- Progress video terupdate di dashboard
- Statistik dashboard terupdate
- Progress keseluruhan meningkat

---

### 7.2. Integration: Quiz & Points & Leaderboard
**ID Test:** INT-002  
**Deskripsi:** Poin dari quiz terupdate di leaderboard  
**Test Steps:**
1. Login sebagai siswa
2. Catat poin awal dan posisi di leaderboard
3. Selesaikan quiz dan dapatkan poin
4. Akses leaderboard

**Expected Result:**
- Poin pengguna meningkat
- Posisi di leaderboard berubah (jika poin meningkat)
- Leaderboard menampilkan poin terbaru

---

### 7.3. Integration: Admin & Video & Student Access
**ID Test:** INT-003  
**Deskripsi:** Video yang ditambahkan admin dapat diakses siswa  
**Test Steps:**
1. Login sebagai admin
2. Tambahkan video baru untuk kelas X
3. Logout admin
4. Login sebagai siswa kelas X
5. Akses halaman video pembelajaran

**Expected Result:**
- Video baru muncul di daftar video siswa kelas X
- Video dapat diputar oleh siswa kelas X
- Siswa kelas lain tidak dapat melihat video tersebut

---

### 7.4. Integration: Quiz Creation & Student Access
**ID Test:** INT-004  
**Deskripsi:** Quiz yang dibuat admin dapat diakses siswa  
**Test Steps:**
1. Login sebagai admin
2. Buat quiz baru untuk kelas X
3. Logout admin
4. Login sebagai siswa kelas X
5. Akses halaman quiz

**Expected Result:**
- Quiz baru muncul di daftar quiz siswa kelas X
- Quiz dapat dikerjakan oleh siswa kelas X
- Siswa kelas lain tidak dapat melihat quiz tersebut

---

### 7.5. Integration: Profile Update & Dashboard Display
**ID Test:** INT-005  
**Deskripsi:** Perubahan profil terlihat di dashboard  
**Test Steps:**
1. Login sebagai siswa
2. Update profil (ubah nama atau foto)
3. Akses dashboard

**Expected Result:**
- Dashboard menampilkan nama baru
- Dashboard menampilkan foto profil baru
- Perubahan konsisten di semua halaman

---

## 8. PENGUJIAN SISTEM END-TO-END

### 8.1. End-to-End: Student Journey
**ID Test:** E2E-001  
**Deskripsi:** Skenario lengkap perjalanan siswa dari registrasi hingga menyelesaikan pembelajaran  
**Test Steps:**
1. **Registrasi:**
   - Buka halaman register
   - Daftar sebagai siswa baru
   - Verifikasi email (jika diperlukan)

2. **Login:**
   - Login dengan kredensial baru
   - Akses dashboard

3. **Menonton Video:**
   - Akses halaman video pembelajaran
   - Pilih dan tonton beberapa video
   - Verifikasi progress terupdate

4. **Mengerjakan Quiz:**
   - Akses halaman quiz
   - Pilih dan kerjakan quiz
   - Lihat hasil quiz
   - Verifikasi poin bertambah

5. **Melihat Leaderboard:**
   - Akses leaderboard
   - Verifikasi posisi sesuai poin

6. **Update Profil:**
   - Akses profil
   - Update nama dan foto profil
   - Verifikasi perubahan terlihat di dashboard

**Expected Result:**
- Semua langkah berjalan dengan lancar
- Data konsisten di seluruh sistem
- Tidak ada error atau bug yang menghalangi

---

### 8.2. End-to-End: Admin Journey
**ID Test:** E2E-002  
**Deskripsi:** Skenario lengkap perjalanan admin mengelola sistem  
**Test Steps:**
1. **Login Admin:**
   - Login sebagai admin
   - Akses dashboard admin

2. **Mengelola Video:**
   - Tambah video baru
   - Edit video yang ada
   - Nonaktifkan video
   - Verifikasi perubahan terlihat oleh siswa

3. **Mengelola Quiz:**
   - Buat quiz baru dengan beberapa soal
   - Edit quiz yang ada
   - Verifikasi quiz dapat diakses siswa

4. **Melihat Analytics:**
   - Akses halaman analytics
   - Lihat statistik siswa
   - Export data analytics

5. **Mengelola Siswa:**
   - Lihat daftar siswa
   - Lihat detail progress siswa
   - Export data siswa

**Expected Result:**
- Semua fitur admin berfungsi dengan baik
- Perubahan admin langsung terlihat oleh siswa
- Data analytics akurat

---

### 8.3. End-to-End: Mobile App Integration
**ID Test:** E2E-003  
**Deskripsi:** Skenario lengkap penggunaan aplikasi mobile melalui API  
**Test Steps:**
1. **Login melalui API:**
   - Panggil API login
   - Simpan token

2. **Mengakses Dashboard:**
   - Panggil API dashboard dengan token
   - Tampilkan data di aplikasi mobile

3. **Menonton Video:**
   - Panggil API untuk mendapatkan daftar video
   - Pilih video dan putar
   - Update progress melalui API

4. **Mengerjakan Quiz:**
   - Panggil API untuk mendapatkan daftar quiz
   - Mulai quiz melalui API
   - Submit jawaban melalui API
   - Tampilkan hasil

5. **Melihat Leaderboard:**
   - Panggil API leaderboard
   - Tampilkan ranking di aplikasi mobile

**Expected Result:**
- Semua API berfungsi dengan baik
- Data konsisten dengan web application
- Aplikasi mobile dapat mengakses semua fitur

---

### 8.4. End-to-End: Concurrent Users
**ID Test:** E2E-004  
**Deskripsi:** Sistem dapat menangani beberapa pengguna secara bersamaan  
**Test Steps:**
1. Login sebagai beberapa siswa berbeda secara bersamaan
2. Setiap siswa menonton video berbeda
3. Setiap siswa mengerjakan quiz berbeda
4. Semua siswa mengakses leaderboard secara bersamaan

**Expected Result:**
- Sistem tidak crash atau error
- Data setiap pengguna terpisah dengan benar
- Leaderboard menampilkan data semua pengguna dengan benar
- Tidak ada race condition atau data corruption

---

### 8.5. End-to-End: Data Consistency
**ID Test:** E2E-005  
**Deskripsi:** Data konsisten di seluruh sistem  
**Test Steps:**
1. Login sebagai siswa
2. Tonton video hingga 50%
3. Akses dashboard melalui web
4. Akses dashboard melalui API
5. Login dari perangkat berbeda

**Expected Result:**
- Progress sama di semua tempat
- Data konsisten antara web dan API
- Data konsisten di semua perangkat

---

## 9. PENGUJIAN NON-FUNGSIONAL

### 9.1. Performance Testing
**ID Test:** PERF-001  
**Deskripsi:** Halaman dashboard load dalam waktu wajar  
**Test Steps:**
1. Login sebagai siswa
2. Akses halaman dashboard
3. Ukur waktu loading

**Expected Result:**
- Halaman load dalam waktu < 3 detik
- Tidak ada lag atau freeze

---

**ID Test:** PERF-002  
**Deskripsi:** Video player dapat memutar video tanpa buffering berlebihan  
**Test Steps:**
1. Login sebagai siswa
2. Putar video pembelajaran
3. Perhatikan kualitas streaming

**Expected Result:**
- Video dapat diputar dengan lancar
- Buffering minimal
- Kualitas video baik

---

**ID Test:** PERF-003  
**Deskripsi:** API response time cepat  
**Test Steps:**
1. Login melalui API
2. Panggil beberapa API endpoint
3. Ukur response time setiap endpoint

**Expected Result:**
- Response time < 1 detik untuk kebanyakan endpoint
- Tidak ada timeout

---

### 9.2. Security Testing
**ID Test:** SEC-001  
**Deskripsi:** Password ter-hash dengan aman  
**Test Steps:**
1. Daftar sebagai pengguna baru
2. Cek database langsung
3. Verifikasi password tidak tersimpan dalam plain text

**Expected Result:**
- Password ter-hash menggunakan algoritma yang aman (bcrypt)
- Tidak dapat melihat password asli dari database

---

**ID Test:** SEC-002  
**Deskripsi:** SQL Injection tidak mungkin  
**Test Steps:**
1. Coba input SQL injection di form login
2. Contoh: `' OR '1'='1`
3. Submit form

**Expected Result:**
- Input di-sanitize dengan benar
- Tidak ada SQL injection yang berhasil
- Sistem tetap aman

---

**ID Test:** SEC-003  
**Deskripsi:** XSS (Cross-Site Scripting) tidak mungkin  
**Test Steps:**
1. Login sebagai admin
2. Coba input script di form tambah video
3. Contoh: `<script>alert('XSS')</script>`
4. Simpan dan lihat hasil

**Expected Result:**
- Script di-escape dengan benar
- Tidak ada script yang dieksekusi
- Input ditampilkan sebagai teks biasa

---

**ID Test:** SEC-004  
**Deskripsi:** CSRF protection aktif  
**Test Steps:**
1. Login sebagai admin
2. Coba submit form tanpa CSRF token
3. Atau dengan CSRF token yang tidak valid

**Expected Result:**
- Request ditolak
- Menampilkan error CSRF token mismatch
- Tidak ada data yang tersimpan

---

**ID Test:** SEC-005  
**Deskripsi:** File upload hanya menerima format yang diizinkan  
**Test Steps:**
1. Login sebagai admin
2. Coba upload file dengan ekstensi tidak valid (contoh: .exe, .php)
3. Coba upload file dengan ukuran terlalu besar

**Expected Result:**
- File ditolak
- Menampilkan pesan error format tidak didukung atau ukuran terlalu besar
- File tidak tersimpan di server

---

### 9.3. Usability Testing
**ID Test:** USAB-001  
**Deskripsi:** Interface mudah digunakan  
**Test Steps:**
1. Berikan sistem kepada pengguna baru
2. Minta mereka melakukan beberapa tugas:
   - Login
   - Menonton video
   - Mengerjakan quiz
   - Melihat leaderboard

**Expected Result:**
- Pengguna dapat menyelesaikan tugas tanpa bantuan
- Interface intuitif dan mudah dipahami
- Tidak ada kebingungan

---

**ID Test:** USAB-002  
**Deskripsi:** Responsive design untuk mobile  
**Test Steps:**
1. Akses sistem dari perangkat mobile
2. Uji berbagai halaman
3. Uji berbagai fitur

**Expected Result:**
- Layout menyesuaikan dengan ukuran layar
- Semua fitur dapat diakses dari mobile
- Tidak ada elemen yang terpotong atau tidak terlihat

---

### 9.4. Compatibility Testing
**ID Test:** COMP-001  
**Deskripsi:** Sistem kompatibel dengan berbagai browser  
**Test Steps:**
1. Uji sistem di berbagai browser:
   - Chrome
   - Firefox
   - Safari
   - Edge
2. Uji berbagai fitur di setiap browser

**Expected Result:**
- Semua browser dapat mengakses sistem
- Fitur berfungsi dengan baik di semua browser
- Tidak ada perbedaan signifikan di tampilan

---

## 10. TEST DATA & ENVIRONMENT

### 10.1. Test Data Requirements
- **Users:**
  - Minimal 1 admin user
  - Minimal 5 siswa dari kelas berbeda
  - Minimal 1 siswa dengan progress lengkap
  - Minimal 1 siswa baru tanpa progress

- **Videos:**
  - Minimal 10 video dengan kategori berbeda
  - Minimal 3 video per tingkat kesulitan
  - Minimal 1 video tidak aktif
  - Video dengan durasi berbeda

- **Quizzes:**
  - Minimal 5 quiz dengan tingkat kesulitan berbeda
  - Minimal 1 quiz per kelas
  - Quiz dengan jumlah soal berbeda
  - Quiz dengan waktu berbeda

- **Progress Data:**
  - Progress video untuk beberapa siswa
  - Hasil quiz untuk beberapa siswa
  - Poin untuk beberapa siswa

### 10.2. Test Environment
- **Development Environment:**
  - Database: MySQL/PostgreSQL
  - Server: Laravel development server atau Apache/Nginx
  - PHP version: sesuai requirement Laravel

- **Testing Tools:**
  - Browser: Chrome, Firefox, Safari, Edge
  - API Testing: Postman, Insomnia, atau curl
  - Mobile Testing: Browser mobile atau emulator

---

## 11. TEST EXECUTION PLAN

### 11.1. Test Priority
1. **High Priority:**
   - Authentication & Authorization
   - Core functionality (Video, Quiz, Dashboard)
   - Data integrity

2. **Medium Priority:**
   - Admin features
   - API endpoints
   - Integration testing

3. **Low Priority:**
   - Non-functional testing
   - Edge cases
   - Usability testing

### 11.2. Test Schedule
1. **Week 1:** Unit Testing & Integration Testing
2. **Week 2:** System Testing & Functional Testing
3. **Week 3:** API Testing & Security Testing
4. **Week 4:** Performance Testing & Usability Testing

### 11.3. Test Reporting
- Setiap test case harus didokumentasikan dengan:
  - Test ID
  - Status (Pass/Fail/Blocked)
  - Screenshot (jika fail)
  - Error message (jika fail)
  - Date & Tester name

---

## 12. BUG TRACKING

### 12.1. Bug Severity Levels
1. **Critical:** Sistem tidak dapat digunakan, data hilang, security breach
2. **High:** Fitur utama tidak berfungsi, data tidak konsisten
3. **Medium:** Fitur sekunder tidak berfungsi, UI/UX issue
4. **Low:** Typo, minor UI issue, enhancement

### 12.2. Bug Report Template
```
Bug ID: BUG-001
Title: [Judul bug]
Severity: [Critical/High/Medium/Low]
Test Case ID: [ID test case yang terkait]
Steps to Reproduce:
1. [Langkah 1]
2. [Langkah 2]
3. [Langkah 3]

Expected Result: [Hasil yang diharapkan]
Actual Result: [Hasil yang terjadi]
Screenshot: [Jika ada]
Environment: [Browser, OS, dll]
```

---

## CATATAN PENTING

1. **Test Coverage:** Pastikan semua fitur utama tercakup dalam test scenarios
2. **Regression Testing:** Setelah bug fix, lakukan regression testing untuk memastikan tidak ada bug baru
3. **Automated Testing:** Pertimbangkan untuk membuat automated tests untuk test cases yang sering diulang
4. **Test Data Management:** Pastikan test data terpisah dari production data
5. **Documentation:** Dokumentasikan semua test results dan bugs yang ditemukan

---

**Dokumen ini dibuat untuk:** Platform Pembelajaran SMK  
**Versi:** 1.0  
**Tanggal:** 2024  
**Status:** Draft untuk Review

