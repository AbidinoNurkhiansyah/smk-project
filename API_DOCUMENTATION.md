# RESTful API Documentation

API ini dibuat untuk digunakan dengan aplikasi Expo (React Native).

## Base URL
```
http://your-domain.com/api
```

## Authentication

API menggunakan token-based authentication. Setelah login, Anda akan menerima token yang harus dikirim di header setiap request:

```
Authorization: Bearer {token}
```

## Endpoints

### Authentication

#### POST /api/login
Login user dan mendapatkan token.

**Request Body:**
```json
{
  "email": "user@example.com",
  "password": "password123"
}
```

**Response:**
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

#### POST /api/register
Mendaftar user baru.

**Request Body:**
```json
{
  "user_name": "John Doe",
  "email": "user@example.com",
  "password": "password123",
  "password_confirmation": "password123",
  "class_id": 1
}
```

#### POST /api/logout
Logout user (menghapus token).

**Headers:**
```
Authorization: Bearer {token}
```

#### GET /api/me
Mendapatkan informasi user yang sedang login.

**Headers:**
```
Authorization: Bearer {token}
```

#### POST /api/forgot-password
Request reset password.

**Request Body:**
```json
{
  "email": "user@example.com"
}
```

#### POST /api/reset-password
Reset password dengan token.

**Request Body:**
```json
{
  "token": "reset_token",
  "email": "user@example.com",
  "password": "newpassword123",
  "password_confirmation": "newpassword123"
}
```

### Profile

#### GET /api/profile
Mendapatkan profil user.

**Headers:**
```
Authorization: Bearer {token}
```

#### PUT /api/profile
Update profil user.

**Headers:**
```
Authorization: Bearer {token}
```

**Request Body:**
```json
{
  "user_name": "John Doe",
  "email": "newemail@example.com",
  "current_password": "oldpassword",
  "new_password": "newpassword123",
  "new_password_confirmation": "newpassword123"
}
```

#### POST /api/profile/picture
Update foto profil.

**Headers:**
```
Authorization: Bearer {token}
Content-Type: multipart/form-data
```

**Request Body:**
```
profile_picture: (file)
```

#### DELETE /api/profile/picture
Hapus foto profil.

**Headers:**
```
Authorization: Bearer {token}
```

### Dashboard

#### GET /api/dashboard
Mendapatkan data dashboard lengkap.

**Headers:**
```
Authorization: Bearer {token}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "user": {
      "name": "John Doe",
      "class": "Kelas X",
      "profile_picture": "http://..."
    },
    "statistics": {
      "total_videos": 10,
      "completed_videos": 5,
      "in_progress_videos": 2,
      "not_started_videos": 3,
      "total_watch_time": 120,
      "overall_progress": 50,
      "user_points": 150
    },
    "videos": [...]
  }
}
```

#### GET /api/dashboard/progress
Mendapatkan data progress saja.

**Headers:**
```
Authorization: Bearer {token}
```

### Videos

#### GET /api/videos
Mendapatkan daftar semua video untuk kelas user.

**Headers:**
```
Authorization: Bearer {token}
```

#### GET /api/videos/{id}
Mendapatkan detail video.

**Headers:**
```
Authorization: Bearer {token}
```

#### POST /api/videos/{id}/progress
Update progress video.

**Headers:**
```
Authorization: Bearer {token}
```

**Request Body:**
```json
{
  "progress": 75
}
```

**Response:**
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

### Quizzes/Games

#### GET /api/quizzes
Mendapatkan daftar quiz yang tersedia.

**Headers:**
```
Authorization: Bearer {token}
```

#### GET /api/quizzes/{id}
Mendapatkan detail quiz dan soal-soalnya.

**Headers:**
```
Authorization: Bearer {token}
```

**Response:**
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
    "questions": [
      {
        "id": 1,
        "question": "Pertanyaan?",
        "image": "http://...",
        "points": 10,
        "order_number": 1,
        "options": [
          {"label": "A", "text": "Opsi A"},
          {"label": "B", "text": "Opsi B"}
        ]
      }
    ]
  }
}
```

#### POST /api/quizzes/{id}/submit
Submit jawaban quiz.

**Headers:**
```
Authorization: Bearer {token}
```

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

**Response:**
```json
{
  "success": true,
  "message": "Quiz berhasil disubmit!",
  "data": {
    "total_score": 30,
    "correct_answers": 8,
    "total_questions": 10,
    "percentage": 80,
    "grade": "B",
    "points_earned": 30,
    "is_first_attempt": true
  }
}
```

#### GET /api/quizzes/{id}/time
Mendapatkan waktu tersisa untuk quiz.

**Headers:**
```
Authorization: Bearer {token}
```

#### GET /api/leaderboard
Mendapatkan leaderboard untuk kelas user.

**Headers:**
```
Authorization: Bearer {token}
```

## Error Responses

Semua error akan mengembalikan format berikut:

```json
{
  "success": false,
  "message": "Pesan error"
}
```

**Status Codes:**
- `200` - Success
- `201` - Created
- `400` - Bad Request
- `401` - Unauthorized
- `404` - Not Found
- `422` - Validation Error

## Setup

1. Jalankan migration untuk menambahkan kolom `api_token`:
```bash
php artisan migrate
```

2. Pastikan CORS sudah dikonfigurasi dengan benar di `bootstrap/app.php`

3. Untuk production, pastikan untuk:
   - Mengubah CORS origin dari `*` ke domain spesifik
   - Menggunakan HTTPS
   - Menyimpan token dengan aman di aplikasi Expo

## Contoh Penggunaan di Expo

```javascript
import axios from 'axios';

const API_BASE_URL = 'http://your-domain.com/api';

// Login
const login = async (email, password) => {
  const response = await axios.post(`${API_BASE_URL}/login`, {
    email,
    password
  });
  
  // Simpan token
  await AsyncStorage.setItem('token', response.data.data.token);
  return response.data;
};

// Get Dashboard
const getDashboard = async () => {
  const token = await AsyncStorage.getItem('token');
  const response = await axios.get(`${API_BASE_URL}/dashboard`, {
    headers: {
      'Authorization': `Bearer ${token}`
    }
  });
  return response.data;
};
```

