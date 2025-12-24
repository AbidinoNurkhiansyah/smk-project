# Dokumentasi Testing Laravel

Dokumen ini menjelaskan cara menjalankan dan memahami test cases untuk aplikasi Platform Pembelajaran SMK.

## Struktur Testing

```
tests/
├── Feature/          # Feature Tests (Integration Tests)
│   ├── AuthTest.php          # Test untuk Authentication
│   ├── DashboardTest.php     # Test untuk Dashboard
│   ├── VideoTest.php         # Test untuk Video Pembelajaran
│   ├── QuizTest.php          # Test untuk Quiz/Game
│   ├── AdminTest.php         # Test untuk Admin Panel
│   └── ApiTest.php           # Test untuk API Endpoints
├── Unit/             # Unit Tests
└── TestCase.php      # Base Test Case Class
```

## Menjalankan Tests

### Menjalankan Semua Tests
```bash
php artisan test
# atau
vendor/bin/phpunit
```

### Menjalankan Test Suite Tertentu
```bash
# Feature Tests saja
php artisan test --testsuite=Feature

# Unit Tests saja
php artisan test --testsuite=Unit
```

### Menjalankan Test File Tertentu
```bash
php artisan test tests/Feature/AuthTest.php
```

### Menjalankan Test Method Tertentu
```bash
php artisan test --filter test_user_can_login_with_valid_credentials
```

### Menjalankan dengan Coverage
```bash
php artisan test --coverage
```

## Test Cases yang Tersedia

### 1. Authentication Tests (AuthTest.php)

**Test Cases:**
- ✅ User dapat melihat halaman login
- ✅ User dapat login dengan kredensial valid
- ✅ User tidak dapat login dengan password salah
- ✅ User tidak dapat login dengan email tidak terdaftar
- ✅ Validasi form login
- ✅ User dapat melihat halaman register
- ✅ User dapat register dengan data valid
- ✅ Registrasi gagal dengan email duplikat
- ✅ Validasi password tidak cocok
- ✅ Validasi panjang password minimum
- ✅ User dapat logout
- ✅ User dapat melihat profil
- ✅ User dapat update profil
- ✅ User dapat mengubah password
- ✅ Ubah password gagal dengan password lama salah
- ✅ User dapat request reset password
- ✅ Reset password dengan token valid
- ✅ Admin redirect ke admin dashboard setelah login
- ✅ Siswa redirect ke welcome setelah login
- ✅ Protected routes memerlukan autentikasi

### 2. Dashboard Tests (DashboardTest.php)

**Test Cases:**
- ✅ User dapat melihat dashboard
- ✅ Dashboard menampilkan informasi user
- ✅ Dashboard menampilkan statistik video
- ✅ Dashboard menampilkan progress 0 untuk user baru
- ✅ Dashboard API mengembalikan data progress
- ✅ Dapat update progress video via API
- ✅ Validasi progress video (max 100, min 0)
- ✅ Dashboard menghitung overall progress dengan benar

### 3. Video Tests (VideoTest.php)

**Test Cases:**
- ✅ User dapat melihat daftar video
- ✅ Daftar video hanya menampilkan video untuk kelas user
- ✅ User dapat melihat video player
- ✅ Video player menampilkan progress jika sudah ditonton
- ✅ User dapat update progress video
- ✅ Penyelesaian video memberikan poin
- ✅ Poin hanya diberikan sekali per video
- ✅ Validasi progress video
- ✅ Video tidak ditemukan redirect

### 4. Quiz Tests (QuizTest.php)

**Test Cases:**
- ✅ User dapat melihat daftar quiz
- ✅ Daftar quiz hanya menampilkan quiz aktif untuk kelas user
- ✅ User dapat memulai quiz
- ✅ User dapat submit quiz
- ✅ Submit quiz menghitung skor dengan benar
- ✅ Quiz memberikan poin pada attempt pertama
- ✅ Quiz tidak memberikan poin pada retake
- ✅ User dapat melihat leaderboard
- ✅ Leaderboard hanya menampilkan user dari kelas yang sama
- ✅ Validasi submit quiz memerlukan answers

### 5. Admin Tests (AdminTest.php)

**Test Cases:**
- ✅ Admin dapat melihat admin dashboard
- ✅ Siswa tidak dapat mengakses admin dashboard
- ✅ Admin dapat melihat daftar video
- ✅ Admin dapat menambah video
- ✅ Admin dapat edit video
- ✅ Admin dapat menghapus video
- ✅ Admin dapat melihat daftar siswa
- ✅ Admin dapat melihat progress siswa
- ✅ Admin dapat menghapus siswa
- ✅ Admin dapat melihat analytics
- ✅ Admin dapat melihat leaderboard
- ✅ Admin dapat melihat daftar quiz
- ✅ Admin dapat membuat quiz
- ✅ Admin dapat edit quiz
- ✅ Admin dapat menghapus quiz
- ✅ Admin dapat melihat quiz analytics

### 6. API Tests (ApiTest.php)

**Test Cases:**
- ✅ API status endpoint
- ✅ API login berhasil
- ✅ API login gagal dengan password salah
- ✅ API register berhasil
- ✅ API register validation
- ✅ API get user info (me)
- ✅ API get dashboard
- ✅ API get videos list
- ✅ API get video detail
- ✅ API update video progress
- ✅ API get quizzes list
- ✅ API get quiz detail
- ✅ API submit quiz
- ✅ API get leaderboard
- ✅ API logout
- ✅ Protected routes memerlukan autentikasi
- ✅ API mengembalikan 404 untuk resource tidak ditemukan

## Setup Database untuk Testing

Tests menggunakan SQLite in-memory database untuk kecepatan. Pastikan konfigurasi di `phpunit.xml` sudah benar:

```xml
<env name="DB_CONNECTION" value="sqlite"/>
<env name="DB_DATABASE" value=":memory:"/>
```

## Helper Methods di TestCase

Base `TestCase` class menyediakan beberapa helper methods:

### `createUser($attributes = [])`
Membuat user test dengan atribut default atau custom.

```php
$userId = $this->createUser([
    'email' => 'custom@example.com',
    'role' => 'siswa'
]);
```

### `createClass($attributes = [])`
Membuat class test.

```php
$this->createClass([
    'class_id' => 1,
    'class_name' => 'Kelas X'
]);
```

### `loginAsUser($userId = null)`
Login sebagai user tertentu atau membuat user baru.

```php
$this->loginAsUser();
// atau
$this->loginAsUser($userId);
```

### `loginAsAdmin()`
Login sebagai admin.

```php
$this->loginAsAdmin();
```

## Best Practices

1. **Gunakan RefreshDatabase Trait**
   - Setiap test akan reset database
   - Memastikan test independence

2. **Setup Data di setUp() atau Test Method**
   - Setup data yang diperlukan untuk test
   - Gunakan helper methods untuk konsistensi

3. **Test Satu Skenario per Test Method**
   - Setiap test method harus fokus pada satu skenario
   - Nama test method harus deskriptif

4. **Assertions yang Jelas**
   - Gunakan assertions yang spesifik
   - Test behavior, bukan implementation

5. **Clean Up**
   - RefreshDatabase trait sudah menangani cleanup
   - Tidak perlu manual cleanup di tearDown()

## Troubleshooting

### Error: "Class not found"
Pastikan autoload sudah di-update:
```bash
composer dump-autoload
```

### Error: "Database connection failed"
Pastikan konfigurasi database di `phpunit.xml` benar dan migrations sudah dijalankan.

### Error: "Session not found"
Pastikan menggunakan `loginAsUser()` atau `loginAsAdmin()` helper methods untuk setup session.

### Error: "Foreign key constraint fails"
Pastikan data dependencies dibuat dengan urutan yang benar (misalnya: class sebelum user).

## Menambahkan Test Baru

1. Buat test method baru di file test yang sesuai
2. Nama method harus dimulai dengan `test_`
3. Gunakan helper methods yang tersedia
4. Tambahkan assertions yang sesuai
5. Dokumentasikan test case di README ini

Contoh:
```php
public function test_new_feature_works()
{
    // Arrange
    $this->createClass();
    $userId = $this->createUser();
    $this->loginAsUser($userId);

    // Act
    $response = $this->get('/some-route');

    // Assert
    $response->assertStatus(200);
    $response->assertViewIs('some.view');
}
```

## Coverage Target

Target coverage untuk aplikasi ini:
- **Feature Tests**: Minimal 80% coverage untuk routes dan controllers
- **Unit Tests**: Minimal 70% coverage untuk models dan utilities

## Continuous Integration

Tests dapat dijalankan di CI/CD pipeline:

```yaml
# Contoh GitHub Actions
- name: Run Tests
  run: php artisan test
```

## Catatan Penting

1. Tests menggunakan SQLite in-memory database untuk kecepatan
2. Setiap test dijalankan dalam transaction yang di-rollback setelah test selesai
3. Session dan cache di-reset untuk setiap test
4. File uploads menggunakan Storage::fake() untuk testing

## Referensi

- [Laravel Testing Documentation](https://laravel.com/docs/testing)
- [PHPUnit Documentation](https://phpunit.de/documentation.html)
- [SKENARIO_TESTING.md](../SKENARIO_TESTING.md) - Dokumentasi skenario testing lengkap

