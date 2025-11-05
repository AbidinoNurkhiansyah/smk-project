<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Mecha Learn</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .auth-container {
            display: flex;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            max-width: 1000px;
            width: 100%;
            min-height: 600px;
        }

        .left-panel {
            background: #C82020;
            flex: 1;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            padding: 2rem;
            overflow: hidden;
        }

        /* Orange Wave Curves - Pojok Kanan Atas dan Kanan Bawah */
        .left-panel::before {
            content: '';
            position: absolute;
            top: -20%;
            right: -10%;
            width: 120%;
            height: 140%;
            background: #FF6B35;
            border-radius: 50%;
            transform: rotate(-15deg);
            z-index: 1;
        }

        .left-panel::after {
            content: '';
            position: absolute;
            bottom: -10%;
            right: -5%;
            width: 80%;
            height: 60%;
            background: #FF6B35;
            border-radius: 50%;
            transform: rotate(10deg);
            z-index: 1;
        }

        .left-content {
            position: relative;
            z-index: 2;
            text-align: center;
        }

        .left-content h1 {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
            line-height: 1.3;
        }

        .left-content h1 br {
            display: block;
        }

        .logo {
            width: 150px;
            height: 150px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 2rem auto;
        }

        .logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            filter: drop-shadow(0 4px 8px rgba(0,0,0,0.3));
        }

        .right-panel {
            flex: 1.5;
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-title {
            font-size: 2rem;
            font-weight: bold;
            text-align: center;
            margin-bottom: 2rem;
            color: #333;
            position: relative;
        }

        .form-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: #333;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-control, .form-select {
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            padding: 15px 20px;
            font-size: 1rem;
            width: 100%;
            transition: all 0.3s ease;
            background: white;
        }

        .form-control:focus, .form-select:focus {
            border-color: #dc2626;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
            outline: none;
        }

        .form-control::placeholder {
            color: #9ca3af;
        }

        .form-select {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 12px center;
            background-repeat: no-repeat;
            background-size: 16px 12px;
            padding-right: 40px;
            color: #9ca3af;
        }

        .form-select option {
            color: #333;
        }

        .btn-primary {
            background: linear-gradient(90deg, #b91c1c 0%, #ef4444 100%);
            border: none;
            border-radius: 10px;
            padding: 15px;
            font-size: 1.1rem;
            font-weight: 600;
            color: white;
            width: 100%;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(220, 38, 38, 0.3);
        }

        .auth-link {
            text-align: center;
            margin-top: 1.5rem;
        }

        .auth-link p {
            color: #333;
            margin: 0;
        }

        .auth-link a {
            color: #dc2626;
            text-decoration: none;
            font-weight: 500;
        }

        .auth-link a:hover {
            color: #b91c1c;
        }

        .alert {
            border-radius: 10px;
            border: none;
            margin-bottom: 1rem;
        }

        @media (max-width: 768px) {
            .auth-container {
                flex-direction: column;
            }
            
            .left-panel {
                min-height: 200px;
            }
            
            .left-panel::before,
            .left-panel::after {
                display: none;
            }
        }
    </style>
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
            <h2 class="form-title">Daftar</h2>
            
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

            <form action="{{ route('register') }}" method="POST">
                @csrf
                
                <div class="form-group">
                    <input type="text" class="form-control" name="user_name" placeholder="Nama Lengkap" value="{{ old('user_name') }}" required>
                </div>

                <div class="form-group">
                    <input type="email" class="form-control" name="email" placeholder="Email" value="{{ old('email') }}" required>
                </div>

                <div class="form-group">
                    <select class="form-select" name="class_id" required>
                        <option value="">Pilih Kelas</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->class_id }}" {{ old('class_id') == $class->class_id ? 'selected' : '' }}>
                                {{ $class->class_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <input type="password" class="form-control" name="password" placeholder="Enter Your Password" required>
                </div>

                <button type="submit" class="btn btn-primary">
                    Daftar
                </button>
            </form>

            <div class="auth-link">
                <p>Sudah punya akun? <a href="{{ route('login') }}">Masuk</a></p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>