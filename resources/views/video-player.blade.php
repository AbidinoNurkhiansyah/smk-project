<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $video->judul }} - Mecha Learn</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: #f8f9fa;
            line-height: 1.6;
        }
        
        /* Navbar Styles */
        .navbar {
            background: linear-gradient(135deg, #c12727 0%, #ff9100 100%);
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            padding: 0.75rem 1rem;
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        
        .logo-area {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .logo-area img {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            object-fit: cover;
        }
        
        .logo-area h1 {
            color: white;
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0;
        }
        
        .nav-links {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .nav-links a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .nav-links a:hover {
            background: rgba(255,255,255,0.1);
            color: white;
        }
        
        .btn-logout {
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.3);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-logout:hover {
            background: rgba(255,255,255,0.2);
            color: white;
        }
        
        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
        }
        
        /* Main Content */
        .main-content {
            padding: 2rem 1rem;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .video-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            overflow: hidden;
            margin-bottom: 2rem;
        }
        
        .video-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1.5rem;
            text-align: center;
        }
        
        .video-header h1 {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .video-header p {
            opacity: 0.9;
            font-size: 1rem;
        }
        
        .video-wrapper {
            position: relative;
            width: 100%;
            height: 0;
            padding-bottom: 56.25%; /* 16:9 aspect ratio */
            background: #000;
        }
        
        .video-wrapper iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: none;
        }
        
        .video-info {
            padding: 1.5rem;
        }
        
        .video-description {
            font-size: 1rem;
            line-height: 1.6;
            color: #666;
            margin-bottom: 1.5rem;
        }
        
        .progress-section {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .progress-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }
        
        .progress-title {
            font-size: 1rem;
            font-weight: 600;
            color: #333;
            margin: 0;
        }
        
        .progress-percentage {
            font-size: 1rem;
            font-weight: 700;
            color: #667eea;
        }
        
        .progress-bar-container {
            width: 100%;
            height: 8px;
            background: #e9ecef;
            border-radius: 4px;
            overflow: hidden;
            margin-bottom: 0.5rem;
        }
        
        .progress-bar {
            height: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 4px;
            transition: width 0.3s ease;
        }
        
        .video-actions {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }
        
        .btn-action {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .btn-action:disabled {
            opacity: 0.5;
            cursor: not-allowed !important;
            pointer-events: none;
        }
        
        .btn-action:not(:disabled):hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        .btn-primary-action {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .btn-primary-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
            color: white;
        }
        
        .btn-secondary-action {
            background: #6c757d;
            color: white;
        }
        
        .btn-secondary-action:hover {
            background: #5a6268;
            transform: translateY(-2px);
            color: white;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .mobile-menu-btn {
                display: block;
            }
            
            .nav-links {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: linear-gradient(135deg, #c12727 0%, #ff9100 100%);
                flex-direction: column;
                padding: 1rem;
                box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            }
            
            .nav-links.active {
                display: flex;
            }
            
            .main-content {
                padding: 1rem;
            }
            
            .video-header h1 {
                font-size: 1.4rem;
            }
            
            .video-actions {
                flex-direction: column;
            }
            
            .btn-action {
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <header class="navbar d-flex flex-wrap justify-content-between align-items-center">
        <div class="logo-area d-flex align-items-center gap-2">
            <img src="{{ asset('image/logo.png') }}" alt="Logo">
            <h1 class="mb-0">Mecha Learn</h1>
        </div>
        <button class="mobile-menu-btn" onclick="toggleMobileMenu()">☰</button>
        <nav class="nav-links d-flex flex-wrap align-items-center">
            <a href="{{ route('dashboard') }}" class="nav-link">Beranda</a>
            <a href="{{ route('video.index') }}" class="nav-link">Video Pembelajaran</a>
            <a href="{{ route('kuis') }}" class="nav-link">Kuis</a>
            <a href="{{ url('/') }}" class="btn-logout">Keluar</a>
        </nav>
    </header>

    <main class="main-content">
        <div class="video-container">
            <div class="video-header">
                <h1>{{ $video->judul }}</h1>
                <p>{{ $userData->class_name }} • Teknik Sepeda Motor</p>
            </div>
            
            <div class="video-wrapper">
                @php
                    $isYouTube = false;
                    $youtubeVideoId = null;
                    if (preg_match('/^[a-zA-Z0-9_-]{11}$/', $embedUrl)) {
                        // It's a YouTube video ID
                        $isYouTube = true;
                        $youtubeVideoId = $embedUrl;
                    }
                @endphp
                
                @if($isYouTube)
                    <div id="videoPlayer"></div>
                @else
                    <iframe 
                        src="{{ $embedUrl }}" 
                        allowfullscreen
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        id="videoPlayer"
                        onerror="handleVideoError()">
                    </iframe>
                @endif
                <div id="videoError" style="display: none; text-align: center; padding: 2rem; background: #f8f9fa; border-radius: 10px;">
                    <i class="fas fa-exclamation-triangle" style="font-size: 3rem; color: #ffc107; margin-bottom: 1rem;"></i>
                    <h3 style="color: #333; margin-bottom: 0.5rem;">Video Tidak Dapat Diputar</h3>
                    <p style="color: #666; margin-bottom: 1.5rem;">
                        Video ini mungkin bersifat private atau tidak dapat diakses melalui embed.<br>
                        Silakan klik tombol di bawah untuk membuka video di tab baru.
                    </p>
                    <a href="{{ $video->video_url }}" target="_blank" class="btn-action btn-primary-action" style="text-decoration: none; display: inline-block;">
                        <i class="fas fa-external-link-alt"></i>
                        Buka Video di Tab Baru
                    </a>
                </div>
            </div>
            
            <div class="video-info">
                <div class="video-description">
                    {{ $video->description }}
                </div>
                
                <div class="progress-section">
                    <div class="progress-header">
                        <h3 class="progress-title">Progress Menonton</h3>
                        <span class="progress-percentage">{{ $progressPercentage }}%</span>
                    </div>
                    <div class="progress-bar-container">
                        <div class="progress-bar" style="width: {{ $progressPercentage }}%;"></div>
                    </div>
                </div>
                
                <div class="video-actions">
                    <a href="{{ route('video.index') }}" class="btn-action btn-secondary-action">
                        <i class="fas fa-arrow-left"></i>
                        Kembali ke Video
                    </a>
                    <button id="btnMarkCompleted" onclick="markAsCompleted()" class="btn-action btn-primary-action" 
                            @if($progressPercentage < 100) disabled style="opacity: 0.5; cursor: not-allowed;" @endif>
                        <i class="fas fa-check"></i>
                        Tandai Selesai
                    </button>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleMobileMenu() {
            const navLinks = document.querySelector('.nav-links');
            navLinks.classList.toggle('active');
        }

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const navLinks = document.querySelector('.nav-links');
            const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
            
            if (!navLinks.contains(event.target) && !mobileMenuBtn.contains(event.target)) {
                navLinks.classList.remove('active');
            }
        });

        // Close mobile menu when window is resized to desktop
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                document.querySelector('.nav-links').classList.remove('active');
            }
        });

        // Video progress tracking
        let progressUpdateInterval = null;
        let lastProgress = {{ $progressPercentage }};
        let videoStarted = false;
        let videoPaused = false;
        let lastVideoTime = 0;
        let totalWatchedTime = 0;
        let videoDuration = 0;
        let isYouTube = false;
        let isGoogleDrive = false;
        let youtubePlayer = null;

        // Check video source
        const embedUrl = '{{ $embedUrl }}';
        @php
            $isYouTubeJS = isset($isYouTube) && $isYouTube;
            $youtubeVideoIdJS = isset($youtubeVideoId) ? $youtubeVideoId : '';
            $isGoogleDriveJS = strpos($embedUrl, 'drive.google.com') !== false;
        @endphp
        
        @if($isYouTubeJS)
            isYouTube = true;
            const youtubeVideoId = '{{ $youtubeVideoIdJS }}';
        @endif
        
        @if($isGoogleDriveJS)
            isGoogleDrive = true;
        @endif

        // Load YouTube IFrame API if needed
        if (isYouTube) {
            const tag = document.createElement('script');
            tag.src = "https://www.youtube.com/iframe_api";
            const firstScriptTag = document.getElementsByTagName('script')[0];
            firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
        }

        // YouTube IFrame API callback
        function onYouTubeIframeAPIReady() {
            if (isYouTube && typeof youtubeVideoId !== 'undefined') {
                youtubePlayer = new YT.Player('videoPlayer', {
                    videoId: youtubeVideoId,
                    playerVars: {
                        'rel': 0,
                        'modestbranding': 1,
                        'playsinline': 1
                    },
                    events: {
                        'onReady': onPlayerReady,
                        'onStateChange': onPlayerStateChange
                    }
                });
            }
        }

        // Make onYouTubeIframeAPIReady global
        window.onYouTubeIframeAPIReady = onYouTubeIframeAPIReady;

        function onPlayerReady(event) {
            videoDuration = event.target.getDuration();
            console.log('Video duration:', videoDuration, 'seconds');
        }

        function onPlayerStateChange(event) {
            // YT.PlayerState: -1 (unstarted), 0 (ended), 1 (playing), 2 (paused), 3 (buffering), 5 (cued)
            const state = event.data;
            
            if (state === YT.PlayerState.PLAYING) {
                // Video started playing
                if (!videoStarted) {
                    videoStarted = true;
                    videoPaused = false;
                    console.log('Video started playing');
                    startProgressTracking();
                } else if (videoPaused) {
                    // Video resumed from pause
                    videoPaused = false;
                    console.log('Video resumed');
                    startProgressTracking();
                }
            } else if (state === YT.PlayerState.PAUSED) {
                // Video paused
                videoPaused = true;
                stopProgressTracking();
                console.log('Video paused');
            } else if (state === YT.PlayerState.ENDED) {
                // Video ended
                videoStarted = false;
                videoPaused = false;
                stopProgressTracking();
                // Force update to 100% when video ends
                lastProgress = 100;
                updateProgress(100);
                console.log('Video ended - Progress set to 100%');
            } else if (state === YT.PlayerState.BUFFERING) {
                // Video buffering - don't track progress
                console.log('Video buffering');
            }
        }

        // For Google Drive, use postMessage
        if (isGoogleDrive) {
            window.addEventListener('message', function(event) {
                if (event.origin !== 'https://drive.google.com') return;
                
                const data = event.data;
                if (data && typeof data === 'object') {
                    // Handle Google Drive video events
                    if (data.type === 'video-progress') {
                        if (!videoStarted && data.playing) {
                            videoStarted = true;
                            videoPaused = false;
                            startProgressTracking();
                        } else if (videoStarted && !data.playing) {
                            videoPaused = true;
                            stopProgressTracking();
                        }
                    }
                }
            });
        }

        function startProgressTracking() {
            if (progressUpdateInterval) {
                clearInterval(progressUpdateInterval);
            }

            progressUpdateInterval = setInterval(function() {
                if (!videoStarted || videoPaused) {
                    return;
                }

                let currentTime = 0;
                let duration = 0;

                if (isYouTube && youtubePlayer) {
                    try {
                        currentTime = youtubePlayer.getCurrentTime();
                        duration = youtubePlayer.getDuration();
                        
                        // Check if user skipped (jumped forward more than 10 seconds)
                        if (currentTime - lastVideoTime > 10) {
                            console.log('Video skipped - stopping progress tracking');
                            stopProgressTracking();
                            return;
                        }
                        
                        lastVideoTime = currentTime;
                        
                        if (duration > 0) {
                            const progress = Math.round((currentTime / duration) * 100);
                            updateProgress(progress);
                        }
                    } catch (e) {
                        console.error('Error getting YouTube video time:', e);
                    }
                } else if (isGoogleDrive) {
                    // For Google Drive, we can't directly access video time
                    // So we'll track based on time watched
                    totalWatchedTime += 1; // Increment by 1 second
                    if (videoDuration > 0) {
                        const progress = Math.round((totalWatchedTime / videoDuration) * 100);
                        updateProgress(Math.min(progress, 100));
                    }
                }
            }, 1000); // Update every second
        }

        function stopProgressTracking() {
            if (progressUpdateInterval) {
                clearInterval(progressUpdateInterval);
                progressUpdateInterval = null;
            }
        }

        function updateProgress(progress) {
            // Ensure progress is between 0 and 100
            progress = Math.max(0, Math.min(100, progress));
            
            // Only update if progress actually changed
            if (Math.abs(progress - lastProgress) >= 1) {
                lastProgress = progress;
                
                // Update UI
                const progressElement = document.querySelector('.progress-percentage');
                const progressBar = document.querySelector('.progress-bar');
                const btnMarkCompleted = document.getElementById('btnMarkCompleted');
                
                if (progressElement) {
                    progressElement.textContent = progress + '%';
                }
                if (progressBar) {
                    progressBar.style.width = progress + '%';
                }
                
                // Enable/disable "Tandai Selesai" button based on progress
                if (btnMarkCompleted) {
                    if (progress >= 100) {
                        // Video selesai - enable button
                        btnMarkCompleted.disabled = false;
                        btnMarkCompleted.style.opacity = '1';
                        btnMarkCompleted.style.cursor = 'pointer';
                        btnMarkCompleted.title = 'Klik untuk menandai video sebagai selesai';
                    } else {
                        // Video belum selesai - disable button
                        btnMarkCompleted.disabled = true;
                        btnMarkCompleted.style.opacity = '0.5';
                        btnMarkCompleted.style.cursor = 'not-allowed';
                        btnMarkCompleted.title = 'Video harus ditonton sampai selesai (100%) untuk bisa ditandai selesai';
                    }
                }
                
                // Send to server
                fetch('{{ route("video.progress") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        video_id: {{ $video->video_id }},
                        progress: progress
                    })
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Progress saved:', data);
                    if (data.success && progress >= 100) {
                        console.log('Video marked as completed in database');
                    }
                })
                .catch(error => {
                    console.error('Error updating progress:', error);
                });
            }
        }

        function markAsCompleted() {
            // Check if progress is already 100%
            if (lastProgress < 100) {
                alert('Video harus ditonton sampai selesai (100%) sebelum bisa ditandai selesai.\n\nProgress saat ini: ' + lastProgress + '%');
                return;
            }
            
            // Double check - ensure progress is 100%
            if (lastProgress >= 100) {
                stopProgressTracking();
                updateProgress(100);
                alert('Video berhasil ditandai sebagai selesai!');
                
                // Disable button after marking as completed
                const btnMarkCompleted = document.getElementById('btnMarkCompleted');
                if (btnMarkCompleted) {
                    btnMarkCompleted.disabled = true;
                    btnMarkCompleted.style.opacity = '0.7';
                    btnMarkCompleted.innerHTML = '<i class="fas fa-check-circle"></i> Sudah Selesai';
                }
            } else {
                alert('Video belum selesai ditonton. Progress saat ini: ' + lastProgress + '%');
            }
        }

        // Initialize when page loads
        document.addEventListener('DOMContentLoaded', function() {
            // Don't start tracking automatically - wait for video to play
            console.log('Video player loaded. Waiting for video to start...');
            
            // Initialize button state based on current progress
            const btnMarkCompleted = document.getElementById('btnMarkCompleted');
            if (btnMarkCompleted) {
                if (lastProgress >= 100) {
                    btnMarkCompleted.disabled = false;
                    btnMarkCompleted.style.opacity = '1';
                    btnMarkCompleted.style.cursor = 'pointer';
                } else {
                    btnMarkCompleted.disabled = true;
                    btnMarkCompleted.style.opacity = '0.5';
                    btnMarkCompleted.style.cursor = 'not-allowed';
                    btnMarkCompleted.title = 'Video harus ditonton sampai selesai (100%) untuk bisa ditandai selesai';
                }
            }
            
            // For non-YouTube videos, try to detect play events
            if (!isYouTube) {
                const iframe = document.getElementById('videoPlayer');
                if (iframe) {
                    // Try to detect when video starts (limited by cross-origin restrictions)
                    // We'll use a fallback: start tracking after user interacts with page
                    let userInteracted = false;
                    
                    document.addEventListener('click', function() {
                        if (!userInteracted) {
                            userInteracted = true;
                            // Small delay to allow video to start
                            setTimeout(function() {
                                if (!videoStarted) {
                                    videoStarted = true;
                                    videoPaused = false;
                                    startProgressTracking();
                                }
                            }, 2000);
                        }
                    }, { once: true });
                }
            }
        });

        // Clean up when page unloads
        window.addEventListener('beforeunload', function() {
            if (progressUpdateInterval) {
                clearInterval(progressUpdateInterval);
            }
        });

        // Handle video error
        function handleVideoError() {
            const iframe = document.getElementById('videoPlayer');
            const errorDiv = document.getElementById('videoError');
            
            // Check if iframe loaded successfully
            setTimeout(function() {
                try {
                    // Try to access iframe content (will fail if video is private)
                    iframe.contentWindow.document;
                } catch (e) {
                    // If we can't access, show error message
                    iframe.style.display = 'none';
                    if (errorDiv) {
                        errorDiv.style.display = 'block';
                    }
                }
            }, 2000);
        }

        // Listen for iframe load errors
        document.getElementById('videoPlayer').addEventListener('load', function() {
            // If iframe loads but shows error, check after a delay
            setTimeout(function() {
                const iframe = document.getElementById('videoPlayer');
                try {
                    // Try to check if video is actually playing
                    const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
                    const bodyText = iframeDoc.body.innerText || iframeDoc.body.textContent || '';
                    
                    // Check for common YouTube error messages
                    if (bodyText.includes('private') || 
                        bodyText.includes('unavailable') || 
                        bodyText.includes('Video tidak tersedia') ||
                        bodyText.includes('Video tidak dapat diputar')) {
                        iframe.style.display = 'none';
                        const errorDiv = document.getElementById('videoError');
                        if (errorDiv) {
                            errorDiv.style.display = 'block';
                        }
                    }
                } catch (e) {
                    // Cross-origin error is expected, but we can't verify
                    // So we'll show a fallback option
                    console.log('Cannot access iframe content (expected for cross-origin)');
                }
            }, 3000);
        });

        // Alternative: Show fallback button if video doesn't load
        window.addEventListener('load', function() {
            setTimeout(function() {
                const iframe = document.getElementById('videoPlayer');
                const errorDiv = document.getElementById('videoError');
                
                // Check if iframe is visible and has content
                if (iframe && iframe.offsetHeight === 0 && errorDiv) {
                    errorDiv.style.display = 'block';
                }
            }, 5000);
        });
    </script>
</body>
</html>
