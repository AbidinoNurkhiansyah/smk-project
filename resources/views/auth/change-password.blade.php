<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Password - SMK Project</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .password-container {
            max-width: 500px;
            margin: 2rem auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .password-header {
            background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }

        .password-header i {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .password-body {
            padding: 2rem;
        }

        .form-control {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 12px 15px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #dc2626;
            box-shadow: 0 0 0 0.2rem rgba(220, 38, 38, 0.25);
        }

        .btn-save {
            background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
            border: none;
            border-radius: 10px;
            padding: 12px 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(220, 38, 38, 0.4);
        }

        .btn-back {
            background: #6c757d;
            border: none;
            border-radius: 10px;
            padding: 12px 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-back:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }

        .alert {
            border-radius: 10px;
            border: none;
        }

        .password-input-wrapper {
            position: relative;
        }

        .password-input-wrapper .form-control {
            padding-right: 45px;
        }

        .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6c757d;
            transition: color 0.3s ease;
        }

        .toggle-password:hover {
            color: #dc2626;
        }

        .form-text {
            font-size: 0.85rem;
            color: #6c757d;
            margin-top: 0.5rem;
        }

        @media (max-width: 768px) {
            .password-container {
                margin: 1rem;
                border-radius: 10px;
            }

            .password-header {
                padding: 1.5rem;
            }

            .password-body {
                padding: 1.5rem;
            }

            .d-flex.justify-content-between {
                flex-direction: column;
                gap: 1rem;
            }

            .d-flex.justify-content-between .btn {
                width: 100%;
            }
        }

        @media (max-width: 480px) {
            .password-container {
                margin: 0.5rem;
            }

            .password-header {
                padding: 1rem;
            }

            .password-header i {
                font-size: 2rem;
            }

            .password-body {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="password-container">
            <div class="password-header">
                <i class="fas fa-key"></i>
                <h3>Ubah Password</h3>
                <p style="margin: 0; opacity: 0.9;">Ganti password akun Anda</p>
            </div>
            
            <div class="password-body">
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

                <form action="{{ route('password.change.update') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Password Saat Ini</label>
                        <div class="password-input-wrapper">
                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                            <i class="fas fa-eye toggle-password" id="toggleCurrentPassword"></i>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="new_password" class="form-label">Password Baru</label>
                        <div class="password-input-wrapper">
                            <input type="password" class="form-control" id="new_password" name="new_password" required minlength="8">
                            <i class="fas fa-eye toggle-password" id="toggleNewPassword"></i>
                        </div>
                        <div class="form-text">Minimal 8 karakter</div>
                    </div>

                    <div class="mb-3">
                        <label for="new_password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                        <div class="password-input-wrapper">
                            <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required minlength="8">
                            <i class="fas fa-eye toggle-password" id="toggleNewPasswordConfirmation"></i>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between flex-wrap gap-2">
                        <a href="{{ route('profile') }}" class="btn btn-secondary btn-back">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary btn-save">
                            <i class="fas fa-save"></i> Simpan Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle current password visibility
        document.getElementById('toggleCurrentPassword').addEventListener('click', function() {
            var passwordInput = document.getElementById('current_password');
            var toggleIcon = this;
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        });

        // Toggle new password visibility
        document.getElementById('toggleNewPassword').addEventListener('click', function() {
            var passwordInput = document.getElementById('new_password');
            var toggleIcon = this;
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        });

        // Toggle new password confirmation visibility
        document.getElementById('toggleNewPasswordConfirmation').addEventListener('click', function() {
            var passwordInput = document.getElementById('new_password_confirmation');
            var toggleIcon = this;
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        });
    </script>
</body>
</html>

