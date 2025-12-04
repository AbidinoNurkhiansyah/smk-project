<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - SMK Project</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .profile-container {
            max-width: 600px;
            margin: 2rem auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .profile-header {
            background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }

        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: rgba(255,255,255,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 2rem;
            position: relative;
            overflow: hidden;
            border: 4px solid rgba(255,255,255,0.3);
        }

        .profile-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-avatar i {
            font-size: 3rem;
        }

        .profile-picture-upload {
            margin-bottom: 1.5rem;
        }

        .profile-picture-preview {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid rgba(255,255,255,0.3);
            margin: 0 auto 1rem;
            display: block;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .profile-picture-preview:hover {
            opacity: 0.8;
            transform: scale(1.05);
        }

        .profile-picture-input-wrapper {
            text-align: center;
        }

        .profile-picture-input-wrapper label {
            display: inline-block;
            padding: 0.5rem 1.5rem;
            background: rgba(255,255,255,0.2);
            color: white;
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        .profile-picture-input-wrapper label:hover {
            background: rgba(255,255,255,0.3);
            transform: translateY(-2px);
        }

        .profile-picture-input-wrapper input[type="file"] {
            display: none;
        }

        .profile-picture-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .profile-picture-modal.show {
            display: flex;
        }

        .profile-picture-modal-content {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            max-width: 400px;
            width: 90%;
            text-align: center;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        }

        .profile-picture-modal-content h4 {
            margin-bottom: 1.5rem;
            color: #333;
        }

        .profile-picture-modal-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-modal {
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-modal-upload {
            background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
            color: white;
        }

        .btn-modal-upload:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(220, 38, 38, 0.4);
        }

        .btn-modal-delete {
            background: #dc2626;
            color: white;
        }

        .btn-modal-delete:hover {
            background: #b91c1c;
            transform: translateY(-2px);
        }

        .btn-modal-cancel {
            background: #6c757d;
            color: white;
        }

        .btn-modal-cancel:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }

        .profile-picture-clickable {
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .profile-picture-clickable:hover {
            opacity: 0.8;
            transform: scale(1.05);
        }

        .profile-body {
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

        .user-info {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            border-left: 4px solid #dc2626;
        }

        .user-info h6 {
            margin: 0 0 0.5rem 0;
            color: #dc2626;
            font-weight: 600;
        }

        .user-info p {
            margin: 0;
            font-size: 14px;
            color: #6c757d;
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
    </style>
</head>
<body>
    <div class="container">
        <div class="profile-container">
            <div class="profile-header">
                <div class="profile-picture-upload">
                    @if($user->profile_picture)
                        <img src="{{ asset('storage/' . $user->profile_picture) }}?v={{ time() }}" alt="Foto Profil" class="profile-picture-preview profile-picture-clickable" id="profilePreview" onclick="showProfilePictureModal()" onerror="handleImageError(this)">
                        <div class="profile-avatar profile-picture-clickable" id="profilePreviewFallback" onclick="showProfilePictureModal()" style="display: none;">
                            <i class="fas fa-user-circle"></i>
                        </div>
                    @else
                        <div class="profile-avatar profile-picture-clickable" id="profilePreview" onclick="showProfilePictureModal()">
                            <i class="fas fa-user-circle"></i>
                        </div>
                    @endif
                </div>
                <h3>{{ $user->user_name }}</h3>
                <p>{{ $user->class_name }} • {{ ucfirst($user->role) }}</p>
            </div>

            <!-- Modal for Profile Picture Actions -->
            <div class="profile-picture-modal" id="profilePictureModal" onclick="closeProfilePictureModal(event)">
                <div class="profile-picture-modal-content" onclick="event.stopPropagation()">
                    <h4>
                        @if($user->profile_picture)
                            <i class="fas fa-image"></i> Foto Profil
                        @else
                            <i class="fas fa-camera"></i> Upload Foto Profil
                        @endif
                    </h4>
                    @if($user->profile_picture)
                        <div class="mb-3">
                            <img src="{{ asset('storage/' . $user->profile_picture) }}?v={{ time() }}" alt="Foto Profil" style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover; border: 4px solid #dc2626;" onerror="handleImageError(this)">
                        </div>
                        <div class="profile-picture-modal-actions">
                            <label for="profile_picture" class="btn-modal btn-modal-upload">
                                <i class="fas fa-camera"></i> Ganti Foto
                            </label>
                            <form action="{{ route('profile.picture.delete') }}" method="POST" id="deleteProfilePictureForm" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn-modal btn-modal-delete" onclick="deleteProfilePicture()">
                                    <i class="fas fa-trash"></i> Hapus Foto
                                </button>
                            </form>
                            <button type="button" class="btn-modal btn-modal-cancel" onclick="closeProfilePictureModal()">
                                <i class="fas fa-times"></i> Batal
                            </button>
                        </div>
                    @else
                        <p class="text-muted mb-3">Pilih foto untuk dijadikan foto profil Anda</p>
                        <div class="profile-picture-modal-actions">
                            <label for="profile_picture" class="btn-modal btn-modal-upload">
                                <i class="fas fa-camera"></i> Pilih Foto
                            </label>
                            <button type="button" class="btn-modal btn-modal-cancel" onclick="closeProfilePictureModal()">
                                <i class="fas fa-times"></i> Batal
                            </button>
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="profile-body">
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

                <!-- User Info -->
                <div class="user-info">
                    <h6><i class="fas fa-user-circle"></i> Informasi Akun</h6>
                    <p>Email: {{ $user->email }}<br>Kelas: {{ $user->class_name }}</p>
                </div>

                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Hidden file input for profile picture -->
                    <input type="file" class="form-control" id="profile_picture" name="profile_picture" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp" style="display: none;" onchange="previewProfilePicture(this)">
                    
                    <div class="mb-3">
                        <label for="user_name" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" id="user_name" name="user_name" value="{{ $user->user_name }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="current_password" class="form-label">Password Saat Ini</label>
                        <div class="password-input-wrapper">
                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                            <i class="fas fa-eye toggle-password" id="toggleCurrentPassword"></i>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="new_password" class="form-label">Password Baru (opsional)</label>
                        <div class="password-input-wrapper">
                            <input type="password" class="form-control" id="new_password" name="new_password">
                            <i class="fas fa-eye toggle-password" id="toggleNewPassword"></i>
                        </div>
                        <div class="form-text">Kosongkan jika tidak ingin mengubah password</div>
                    </div>

                    <div class="mb-3">
                        <label for="new_password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                        <div class="password-input-wrapper">
                            <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation">
                            <i class="fas fa-eye toggle-password" id="toggleNewPasswordConfirmation"></i>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('welcome') }}" class="btn btn-secondary btn-back">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary btn-save">
                            <i class="fas fa-save"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function handleImageError(img) {
            // Hide the broken image and show fallback
            img.style.display = 'none';
            var fallback = document.getElementById('profilePreviewFallback');
            if (fallback) {
                fallback.style.display = 'flex';
            }
        }

        function showProfilePictureModal() {
            document.getElementById('profilePictureModal').classList.add('show');
        }

        function closeProfilePictureModal(event) {
            if (!event || event.target.id === 'profilePictureModal') {
                document.getElementById('profilePictureModal').classList.remove('show');
            }
        }

        function previewProfilePicture(input) {
            var preview = document.getElementById('profilePreview');
            var uploadWrapper = preview.parentElement;
            
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                
                reader.onload = function(e) {
                    if (preview.tagName === 'IMG') {
                        preview.src = e.target.result;
                    } else {
                        var newImg = document.createElement('img');
                        newImg.src = e.target.result;
                        newImg.alt = 'Foto Profil';
                        newImg.className = 'profile-picture-preview profile-picture-clickable';
                        newImg.id = 'profilePreview';
                        newImg.style.width = '120px';
                        newImg.style.height = '120px';
                        newImg.style.borderRadius = '50%';
                        newImg.style.objectFit = 'cover';
                        newImg.style.border = '4px solid rgba(255,255,255,0.3)';
                        newImg.style.margin = '0 auto 1rem';
                        newImg.style.display = 'block';
                        newImg.style.cursor = 'pointer';
                        newImg.onclick = showProfilePictureModal;
                        
                        uploadWrapper.replaceChild(newImg, preview);
                    }
                    // Close modal after selecting file
                    closeProfilePictureModal();
                };
                
                reader.readAsDataURL(input.files[0]);
            }
        }

        function deleteProfilePicture() {
            if (confirm('Apakah Anda yakin ingin menghapus foto profil?')) {
                document.getElementById('deleteProfilePictureForm').submit();
            }
        }

        // Close modal when clicking outside
        document.addEventListener('click', function(event) {
            var modal = document.getElementById('profilePictureModal');
            if (event.target === modal) {
                closeProfilePictureModal(event);
            }
        });

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
