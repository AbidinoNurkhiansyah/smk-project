<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Siswa - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h4><i class="fas fa-graduation-cap"></i> Admin Panel</h4>
        </div>
        <nav class="sidebar-nav">
            <a href="{{ route('admin.dashboard') }}" class="nav-link">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            <a href="{{ route('admin.videos') }}" class="nav-link">
                <i class="fas fa-video"></i> Kelola Video
            </a>
            <a href="{{ route('admin.teacher-quiz') }}" class="nav-link">
                <i class="fas fa-question-circle"></i> Kelola Quiz
            </a>
            <a href="{{ route('admin.students') }}" class="nav-link active">
                <i class="fas fa-users"></i> Data Siswa
            </a>
            <a href="{{ route('admin.analytics') }}" class="nav-link">
                <i class="fas fa-chart-bar"></i> Analitik
            </a>
            <a href="{{ route('admin.quiz-analytics') }}" class="nav-link">
                <i class="fas fa-chart-line"></i> Analitik Kuis
            </a>
            <a href="{{ route('admin.leaderboard') }}" class="nav-link">
                <i class="fas fa-trophy"></i> Leaderboard
            </a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <header class="header">
            <div class="header-left">
                <button class="sidebar-toggle">
                    <i class="fas fa-bars"></i>
                </button>
                <h2>Data Siswa</h2>
            </div>
            <div class="header-right">
                <div class="user-actions me-3">
                    <a href="{{ route('profile') }}" class="btn btn-outline-primary btn-sm me-2" title="Profil">
                        <i class="fas fa-user"></i>
                    </a>
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm" title="Logout" onclick="return confirm('Apakah Anda yakin ingin logout?')">
                            <i class="fas fa-sign-out-alt"></i>
                        </button>
                    </form>
                </div>
                <div class="search-box">
                    <input type="text" class="form-control" placeholder="Cari siswa..." id="searchInput">
                </div>
            </div>
        </header>

        <!-- Content -->
        <div class="content">
            <!-- Students Table -->
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-users"></i> Daftar Siswa</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="studentsTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Kelas</th>
                                    <th>Role</th>
                                    <th>Bergabung</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($students as $index => $student)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                                <div class="ms-2">
                                                    <strong>{{ $student->user_name }}</strong>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $student->email }}</td>
                                        <td>
                                            <span class="badge bg-primary">{{ $student->class_name }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-success">{{ ucfirst($student->role) }}</span>
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($student->created_at)->format('d M Y') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.student-progress', $student->user_id) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-chart-line"></i> Progress
                                                </a>
                                                <form method="POST" action="{{ route('admin.delete-student', $student->user_id) }}" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun siswa {{ $student->user_name }}? Tindakan ini tidak dapat dibatalkan!')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="fas fa-trash"></i> Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Belum ada siswa</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sidebar toggle
        document.querySelector('.sidebar-toggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('collapsed');
        });

        // Search functionality
        document.getElementById('searchInput').addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const table = document.getElementById('studentsTable');
            const rows = table.getElementsByTagName('tr');

            for (let i = 1; i < rows.length; i++) {
                const row = rows[i];
                const name = row.cells[1].textContent.toLowerCase();
                const email = row.cells[2].textContent.toLowerCase();
                const className = row.cells[3].textContent.toLowerCase();

                if (name.includes(searchTerm) || email.includes(searchTerm) || className.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });
    </script>
</body>
</html>
