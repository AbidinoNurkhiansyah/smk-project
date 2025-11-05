<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Mecha Learn</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body>
    <div class="auth-container">
        <!-- Left Panel -->
        <div class="left-panel">
            <div class="left-content">
                <h1>Selamat Datang Di<br>Mecha Learn</h1>
                <div class="logo">
                    <img src="{{ asset('image/logo.png') }}" alt="Mecha Learn Logo">
                </div>
            </div>
        </div>

        <!-- Right Panel -->
        <div class="right-panel">
            <h2 class="form-title">Masuk</h2>
            
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    @foreach($errors->all() as $error)
                        {{ $error }}
                    @endforeach
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf
                
                <div class="form-group">
                    <input type="email" class="form-control" name="email" placeholder="Enter Your Email" value="{{ old('email') }}" required>
                </div>

                <div class="form-group">
                    <input type="password" class="form-control" name="password" placeholder="Enter Your Password" required>
                </div>

                <div class="forgot-password">
                    <a href="#">Lupa Password</a>
                </div>

                <button type="submit" class="btn btn-primary">
                    Masuk
                </button>
            </form>

            <div class="auth-link">
                <p>Belum punya akun? <a href="{{ route('register') }}">Daftar</a></p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>