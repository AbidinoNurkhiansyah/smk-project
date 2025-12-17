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

        @media (max-width: 768px) {
            .profile-container {
                margin: 1rem;
                border-radius: 10px;
            }

            .profile-header {
                padding: 1.5rem;
            }

            .profile-body {
                padding: 1.5rem;
            }

            .d-flex.gap-2 {
                flex-direction: column;
                width: 100%;
            }

            .d-flex.gap-2 .btn {
                width: 100%;
            }

            .user-info p {
                font-size: 0.9rem;
            }

            .user-info[style*="background: linear-gradient"] p {
                font-size: 1.25rem !important;
            }
        }

        @media (max-width: 480px) {
            .profile-container {
                margin: 0.5rem;
            }

            .profile-header {
                padding: 1rem;
            }

            .profile-body {
                padding: 1rem;
            }

            .user-info[style*="background: linear-gradient"] p {
                font-size: 1.1rem !important;
            }
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

                <!-- Points Display -->
                <div class="user-info" style="background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%); color: white; border-left: 4px solid #ff9100;">
                    <h6 style="color: white;"><i class="fas fa-trophy"></i> Total Poin</h6>
                    <p style="color: white; font-size: 1.5rem; font-weight: 700; margin: 0.5rem 0 0 0;">{{ number_format($totalPoints) }} Poin</p>
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

                    <div class="d-flex justify-content-between flex-wrap gap-2">
                        <a href="{{ route('welcome') }}" class="btn btn-secondary btn-back">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                        <div class="d-flex gap-2">
                            <a href="{{ route('password.change') }}" class="btn btn-warning" style="background: #ff9100; border: none; color: white;">
                                <i class="fas fa-key"></i> Ubah Password
                            </a>
                            <button type="submit" class="btn btn-primary btn-save">
                                <i class="fas fa-save"></i> Simpan Perubahan
                            </button>
                        </div>
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
            if (!input.files || !input.files[0]) {
                console.log('No file selected');
                return;
            }

            var file = input.files[0];
            console.log('File selected:', file.name, file.type, file.size);
            
            // Validate file type
            var validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
            if (!validTypes.includes(file.type)) {
                alert('Format file tidak didukung. Gunakan JPG, PNG, GIF, atau WEBP.');
                input.value = '';
                return;
            }

            // Validate file size (max 2MB)
            if (file.size > 2048 * 1024) {
                alert('Ukuran file terlalu besar. Maksimal 2MB.');
                input.value = '';
                return;
            }

            var reader = new FileReader();
            var preview = document.getElementById('profilePreview');
            var previewFallback = document.getElementById('profilePreviewFallback');
            var uploadWrapper = document.querySelector('.profile-picture-upload');
            
            if (!uploadWrapper) {
                console.error('Upload wrapper not found');
                return;
            }
            
            reader.onload = function(e) {
                var imageUrl = e.target.result;
                console.log('Image loaded, creating preview');
                
                // If preview exists and is an IMG element
                if (preview && preview.tagName === 'IMG') {
                    console.log('Updating existing image');
                    preview.src = imageUrl;
                    preview.style.display = 'block';
                    preview.style.visibility = 'visible';
                    preview.style.opacity = '1';
                    if (previewFallback) {
                        previewFallback.style.display = 'none';
                    }
                    
                    // Show success message
                    var existingMsg = uploadWrapper.querySelector('.alert-success');
                    if (existingMsg) {
                        existingMsg.remove();
                    }
                    var successMsg = document.createElement('div');
                    successMsg.className = 'alert alert-success';
                    successMsg.style.marginTop = '1rem';
                    successMsg.style.textAlign = 'center';
                    successMsg.innerHTML = '<i class="fas fa-check-circle"></i> Foto dipilih! Klik "Simpan Perubahan" untuk mengupload.';
                    uploadWrapper.appendChild(successMsg);
                    
                    // Remove message after 5 seconds
                    setTimeout(function() {
                        if (successMsg.parentNode) {
                            successMsg.parentNode.removeChild(successMsg);
                        }
                    }, 5000);
                } else {
                    console.log('Creating new image element');
                    // Remove existing preview elements
                    if (preview) {
                        if (preview.parentNode) {
                            preview.parentNode.removeChild(preview);
                        }
                    }
                    if (previewFallback) {
                        previewFallback.style.display = 'none';
                    }
                    
                    // Create new image element
                    var newImg = document.createElement('img');
                    newImg.src = imageUrl;
                    newImg.alt = 'Foto Profil';
                    newImg.className = 'profile-picture-preview profile-picture-clickable';
                    newImg.id = 'profilePreview';
                    newImg.onclick = showProfilePictureModal;
                    newImg.onerror = function() {
                        console.error('Error loading preview image');
                        handleImageError(this);
                    };
                    
                    // Ensure all styles are applied
                    newImg.style.width = '120px';
                    newImg.style.height = '120px';
                    newImg.style.borderRadius = '50%';
                    newImg.style.objectFit = 'cover';
                    newImg.style.border = '4px solid rgba(255,255,255,0.3)';
                    newImg.style.margin = '0 auto 1rem';
                    newImg.style.display = 'block';
                    newImg.style.cursor = 'pointer';
                    newImg.style.visibility = 'visible';
                    newImg.style.opacity = '1';
                    
                    // Clear upload wrapper first
                    uploadWrapper.innerHTML = '';
                    
                    // Append to upload wrapper
                    uploadWrapper.appendChild(newImg);
                    console.log('Preview image added to DOM');
                    
                    // Show success message
                    var successMsg = document.createElement('div');
                    successMsg.className = 'alert alert-success';
                    successMsg.style.marginTop = '1rem';
                    successMsg.style.textAlign = 'center';
                    successMsg.innerHTML = '<i class="fas fa-check-circle"></i> Foto dipilih! Klik "Simpan Perubahan" untuk mengupload.';
                    uploadWrapper.appendChild(successMsg);
                    
                    // Remove message after 5 seconds
                    setTimeout(function() {
                        if (successMsg.parentNode) {
                            successMsg.parentNode.removeChild(successMsg);
                        }
                    }, 5000);
                }
                
                // Close modal after a short delay
                setTimeout(function() {
                    closeProfilePictureModal();
                }, 300);
            };
            
            reader.onerror = function() {
                console.error('Error reading file');
                alert('Error membaca file. Silakan coba lagi.');
                input.value = '';
            };
            
            reader.readAsDataURL(file);
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

    </script>
</body>
</html>
