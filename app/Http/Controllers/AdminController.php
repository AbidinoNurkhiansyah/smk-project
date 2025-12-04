<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        // Get selected class filter
        $selectedClass = $request->get('class_id', 'all');
        
        // Get all classes for filter dropdown
        $classes = DB::table('classes')->get();
        
        // Build base queries
        $studentsQuery = DB::table('users')->where('role', 'siswa');
        $videosQuery = DB::table('videos');
        $quizzesQuery = DB::table('teacher_quizzes')->where('is_active', true);
        
        // Apply class filter if not 'all'
        if ($selectedClass !== 'all') {
            $studentsQuery->where('class_id', $selectedClass);
            $videosQuery->where('class_id', $selectedClass);
            $quizzesQuery->where('class_id', $selectedClass);
        }
        
        // Get statistics
        $totalStudents = $studentsQuery->count();
        $totalVideos = $videosQuery->count();
        $totalQuizzes = $quizzesQuery->count();
        $totalClasses = DB::table('classes')->count();

        // Get recent activities with class filter
        $recentVideos = $videosQuery->orderBy('video_id', 'desc')->limit(5)->get();

        $recentProgress = DB::table('video_progress')
            ->join('users', 'video_progress.user_id', '=', 'users.user_id')
            ->join('videos', 'video_progress.video_id', '=', 'videos.video_id')
            ->join('classes', 'users.class_id', '=', 'classes.class_id')
            ->select('video_progress.*', 'users.user_name', 'videos.judul', 'classes.class_name')
            ->when($selectedClass !== 'all', function($query) use ($selectedClass) {
                return $query->where('users.class_id', $selectedClass);
            })
            ->orderBy('video_progress.progress', 'desc')
            ->limit(10)
            ->get();

        // Get class-specific statistics
        $classStats = [];
        foreach ($classes as $class) {
            $classStats[] = [
                'class_id' => $class->class_id,
                'class_name' => $class->class_name,
                'student_count' => DB::table('users')->where('role', 'siswa')->where('class_id', $class->class_id)->count(),
                'video_count' => DB::table('videos')->where('class_id', $class->class_id)->count(),
                'quiz_count' => DB::table('teacher_quizzes')->where('class_id', $class->class_id)->where('is_active', true)->count()
            ];
        }

        // Get performance data for bar chart (average quiz scores per class)
        // Only calculate from students who actually completed quizzes
        // Calculate average score per class: (total correct answers / total questions answered) * 100
        // Only count if there are actual quiz answers in the database
        $performanceData = DB::table('classes')
            ->leftJoin('users', 'classes.class_id', '=', 'users.class_id')
            ->leftJoin('teacher_quiz_answers', function($join) {
                $join->on('users.user_id', '=', 'teacher_quiz_answers.user_id')
                     ->where('users.role', '=', 'siswa')
                     ->whereNotNull('teacher_quiz_answers.answered_at');
            })
            ->select(
                'classes.class_id',
                'classes.class_name',
                DB::raw('COALESCE(
                    CASE 
                        WHEN COUNT(teacher_quiz_answers.id) > 0 THEN
                            (SUM(CASE WHEN teacher_quiz_answers.is_correct = 1 THEN 1 ELSE 0 END) * 100.0 / 
                             NULLIF(COUNT(teacher_quiz_answers.id), 0))
                        ELSE 0
                    END,
                    0
                ) as avg_score')
            )
            ->groupBy('classes.class_id', 'classes.class_name')
            ->orderBy('classes.class_name')
            ->get();

        // Get clustering data for donut chart
        $clusteringData = $this->getClusteringDataForDashboard($selectedClass);

        return view('admin.dashboard', compact(
            'totalStudents',
            'totalVideos',
            'totalQuizzes',
            'totalClasses',
            'recentVideos',
            'recentProgress',
            'classes',
            'selectedClass',
            'classStats',
            'performanceData',
            'clusteringData'
        ));
    }

    public function videos(Request $request)
    {
        $selectedClass = $request->get('class_id', 'all');
        $classes = DB::table('classes')->get();
        
        $videosQuery = DB::table('videos')
            ->join('classes', 'videos.class_id', '=', 'classes.class_id')
            ->select('videos.*', 'classes.class_name');
            
        if ($selectedClass !== 'all') {
            $videosQuery->where('videos.class_id', $selectedClass);
        }
        
        $videos = $videosQuery->orderBy('videos.video_id', 'desc')->get();

        return view('admin.videos', compact('videos', 'classes', 'selectedClass'));
    }

    public function addVideo(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'video_url' => 'required|url',
            'class_id' => 'required',
            'duration' => 'required|string|regex:/^[0-9]+(\.[0-9]{1,2})?$/'
        ]);

        // Convert duration from menit.detik format to minutes (decimal)
        // Example: 5.26 = 5 menit 26 detik = 5 + (26/60) = 5.433 menit
        $durationInput = $request->duration;
        $durationInMinutes = $this->convertDurationToMinutes($durationInput);

        // Get next video_id
        $nextVideoId = DB::table('videos')->max('video_id') + 1;
        
        // If "all" is selected, insert video for all classes
        if ($request->class_id === 'all') {
            $classes = DB::table('classes')->get();
            foreach ($classes as $class) {
                DB::table('videos')->insert([
                    'video_id' => $nextVideoId,
                    'judul' => $request->judul,
                    'description' => $request->deskripsi,
                    'video_url' => $request->video_url,
                    'class_id' => $class->class_id,
                    'duration' => $durationInMinutes
                ]);
                $nextVideoId++;
            }
            return redirect()->route('admin.videos')->with('success', 'Video berhasil ditambahkan ke semua kelas!');
        } else {
            // Validate class_id exists
            $request->validate([
                'class_id' => 'integer|exists:classes,class_id'
            ]);
        
        DB::table('videos')->insert([
            'video_id' => $nextVideoId,
            'judul' => $request->judul,
            'description' => $request->deskripsi,
            'video_url' => $request->video_url,
            'class_id' => $request->class_id,
            'duration' => $durationInMinutes
        ]);

        return redirect()->route('admin.videos')->with('success', 'Video berhasil ditambahkan!');
        }
    }

    public function editVideo($id)
    {
        $video = DB::table('videos')->where('video_id', $id)->first();
        
        if (!$video) {
            return redirect()->route('admin.videos')->with('error', 'Video tidak ditemukan!');
        }

        $classes = DB::table('classes')->get();
        
        // Convert duration from decimal minutes to menit.detik format for display
        if ($video->duration) {
            $video->duration_display = $this->convertMinutesToDurationFormat($video->duration);
        } else {
            $video->duration_display = '';
        }

        return view('admin.edit-video', compact('video', 'classes'));
    }

    public function updateVideo(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'video_url' => 'required|url',
            'class_id' => 'required|integer|exists:classes,class_id',
            'duration' => 'required|string|regex:/^[0-9]+(\.[0-9]{1,2})?$/'
        ]);

        // Convert duration from menit.detik format to minutes (decimal)
        $durationInput = $request->duration;
        $durationInMinutes = $this->convertDurationToMinutes($durationInput);

        DB::table('videos')
            ->where('video_id', $id)
            ->update([
                'judul' => $request->judul,
                'description' => $request->deskripsi,
                'video_url' => $request->video_url,
                'class_id' => $request->class_id,
                'duration' => $durationInMinutes
            ]);

        return redirect()->route('admin.videos')->with('success', 'Video berhasil diperbarui!');
    }

    public function deleteVideo($id)
    {
        // Hapus data terkait terlebih dahulu
        DB::table('video_progress')->where('video_id', $id)->delete();
        
        // Hapus video
        DB::table('videos')->where('video_id', $id)->delete();
        
        return redirect()->route('admin.videos')->with('success', 'Video berhasil dihapus!');
    }

    public function getVideoDuration(Request $request)
    {
        $request->validate([
            'video_url' => 'required|url'
        ]);

        $videoUrl = $request->video_url;
        
        // Check if it's YouTube URL
        $youtubeVideoId = $this->extractYouTubeVideoId($videoUrl);
        if ($youtubeVideoId) {
            return $this->getYouTubeDuration($youtubeVideoId);
        }
        
        // Check if it's Google Drive URL
        $driveFileId = $this->extractGoogleDriveFileId($videoUrl);
        if ($driveFileId) {
            return $this->getGoogleDriveDuration($driveFileId, $videoUrl);
        }
        
        // URL tidak dikenali
        return response()->json([
            'success' => false,
            'message' => 'URL tidak valid. Hanya mendukung YouTube dan Google Drive.'
        ], 400);
    }

    private function getYouTubeDuration($videoId)
    {
        // Get YouTube Data API key from config (optional)
        $apiKey = config('services.youtube.api_key');
        
        if (!$apiKey) {
            return response()->json([
                'success' => false,
                'message' => 'YouTube Data API key tidak dikonfigurasi. Silakan masukkan durasi secara manual.',
                'video_id' => $videoId
            ], 200);
        }

        try {
            // Fetch video details from YouTube Data API v3
            $apiUrl = "https://www.googleapis.com/youtube/v3/videos?id={$videoId}&part=contentDetails&key={$apiKey}";
            $response = file_get_contents($apiUrl);
            $data = json_decode($response, true);

            if (!isset($data['items'][0]['contentDetails']['duration'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat mengambil durasi video'
                ], 400);
            }

            // Parse ISO 8601 duration (PT1H2M10S format)
            $duration = $data['items'][0]['contentDetails']['duration'];
            $minutes = $this->parseISO8601Duration($duration);

            return response()->json([
                'success' => true,
                'duration' => $minutes,
                'formatted' => $this->formatDuration($minutes),
                'source' => 'youtube'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil durasi: ' . $e->getMessage()
            ], 500);
        }
    }

    private function getGoogleDriveDuration($fileId, $originalUrl)
    {
        try {
            // Convert sharing URL to direct view URL
            $viewUrl = "https://drive.google.com/file/d/{$fileId}/view";
            
            // Use cURL for better control and error handling
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $viewUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 15);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                'Accept-Language: en-US,en;q=0.9',
            ]);
            
            $html = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);
            
            if ($error) {
                throw new \Exception('cURL Error: ' . $error);
            }
            
            if ($httpCode !== 200 || !$html) {
                throw new \Exception('Tidak dapat mengakses halaman Google Drive. HTTP Code: ' . $httpCode);
            }
            
            // Try multiple patterns to extract duration
            $patterns = [
                // Pattern 1: Direct duration in JSON
                '/"duration"\s*:\s*(\d+)/',
                // Pattern 2: Duration in videoMetadata
                '/\["videoMetadata"[^}]*"duration"\s*:\s*(\d+)/',
                // Pattern 3: Duration with quotes
                '/duration["\']?\s*:\s*["\']?(\d+)/',
                // Pattern 4: Duration in seconds (more common)
                '/"durationSeconds"\s*:\s*(\d+)/',
                // Pattern 5: Duration in milliseconds
                '/"duration"\s*:\s*(\d+)\s*,\s*"durationMillis"/',
                // Pattern 6: In videoInfo
                '/"videoInfo"[^}]*"duration"\s*:\s*(\d+)/',
                // Pattern 7: Try finding any number after "duration"
                '/duration[^0-9]*(\d+)/',
            ];
            
            foreach ($patterns as $pattern) {
                if (preg_match($pattern, $html, $matches)) {
                    $seconds = (int)$matches[1];
                    
                    // If the number seems too large, it might be milliseconds
                    if ($seconds > 86400) { // More than 24 hours, probably milliseconds
                        $seconds = round($seconds / 1000);
                    }
                    
                    $minutes = round($seconds / 60, 1);
                    
                    if ($minutes > 0) {
                        return response()->json([
                            'success' => true,
                            'duration' => $minutes,
                            'formatted' => $this->formatDuration($minutes),
                            'source' => 'google_drive',
                            'debug' => 'Found using pattern: ' . $pattern
                        ]);
                    }
                }
            }
            
            // Try to find duration in embedded JSON data
            if (preg_match('/var\s+_initData\s*=\s*(\[.*?\]);/s', $html, $matches)) {
                $jsonData = json_decode($matches[1], true);
                if ($jsonData && is_array($jsonData)) {
                    $duration = $this->findDurationInArray($jsonData);
                    if ($duration) {
                        $minutes = round($duration / 60, 1);
                        return response()->json([
                            'success' => true,
                            'duration' => $minutes,
                            'formatted' => $this->formatDuration($minutes),
                            'source' => 'google_drive'
                        ]);
                    }
                }
            }
            
            // If we can't extract duration, return helpful message
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat mengambil durasi dari Google Drive. Silakan masukkan durasi secara manual (dalam menit).',
                'file_id' => $fileId,
                'note' => 'Pastikan file video di Google Drive sudah di-share dengan akses "Anyone with the link"',
                'debug' => 'HTTP Code: ' . $httpCode . ', HTML length: ' . strlen($html)
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil durasi dari Google Drive: ' . $e->getMessage() . '. Silakan masukkan durasi secara manual.'
            ], 200);
        }
    }

    private function findDurationInArray($array)
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                if (isset($value['duration']) && is_numeric($value['duration'])) {
                    return (int)$value['duration'];
                }
                if (isset($value['durationSeconds']) && is_numeric($value['durationSeconds'])) {
                    return (int)$value['durationSeconds'];
                }
                $result = $this->findDurationInArray($value);
                if ($result) {
                    return $result;
                }
            } elseif ($key === 'duration' && is_numeric($value)) {
                return (int)$value;
            }
        }
        return null;
    }

    private function extractYouTubeVideoId($url)
    {
        $pattern = '/^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=)([^#&?]*).*/';
        preg_match($pattern, $url, $matches);
        return isset($matches[2]) && strlen($matches[2]) === 11 ? $matches[2] : null;
    }

    private function extractGoogleDriveFileId($url)
    {
        // Pattern untuk berbagai format Google Drive URL:
        // https://drive.google.com/file/d/FILE_ID/view
        // https://drive.google.com/open?id=FILE_ID
        // https://drive.google.com/uc?id=FILE_ID
        // https://docs.google.com/file/d/FILE_ID
        
        // Pattern 1: /file/d/FILE_ID
        if (preg_match('/\/file\/d\/([a-zA-Z0-9_-]+)/', $url, $matches)) {
            return $matches[1];
        }
        
        // Pattern 2: ?id=FILE_ID
        if (preg_match('/[?&]id=([a-zA-Z0-9_-]+)/', $url, $matches)) {
            return $matches[1];
        }
        
        // Pattern 3: /uc?id=FILE_ID
        if (preg_match('/\/uc\?id=([a-zA-Z0-9_-]+)/', $url, $matches)) {
            return $matches[1];
        }
        
        // Pattern 4: /open?id=FILE_ID
        if (preg_match('/\/open\?id=([a-zA-Z0-9_-]+)/', $url, $matches)) {
            return $matches[1];
        }
        
        return null;
    }

    private function parseISO8601Duration($duration)
    {
        // Parse PT1H2M10S format to minutes
        preg_match('/PT(?:(\d+)H)?(?:(\d+)M)?(?:(\d+)S)?/', $duration, $matches);
        
        $hours = isset($matches[1]) ? (int)$matches[1] : 0;
        $minutes = isset($matches[2]) ? (int)$matches[2] : 0;
        $seconds = isset($matches[3]) ? (int)$matches[3] : 0;
        
        $totalMinutes = ($hours * 60) + $minutes + ($seconds / 60);
        
        return round($totalMinutes, 1);
    }

    private function formatDuration($minutes)
    {
        $hours = floor($minutes / 60);
        $mins = floor($minutes % 60);
        $secs = round(($minutes % 1) * 60);
        
        if ($hours > 0) {
            return sprintf('%d:%02d:%02d', $hours, $mins, $secs);
        }
        
        return sprintf('%d:%02d', $mins, $secs);
    }

    /**
     * Convert duration from menit.detik format to decimal minutes
     * Example: 5.26 = 5 menit 26 detik = 5 + (26/60) = 5.433 menit
     */
    private function convertDurationToMinutes($durationInput)
    {
        // Remove any whitespace
        $durationInput = trim($durationInput);
        
        // Check if input contains a dot
        if (strpos($durationInput, '.') !== false) {
            $parts = explode('.', $durationInput);
            $minutes = (int)$parts[0];
            $seconds = isset($parts[1]) ? (int)$parts[1] : 0;
            
            // Convert seconds to decimal minutes
            $decimalMinutes = $seconds / 60;
            
            return $minutes + $decimalMinutes;
        } else {
            // No dot, just minutes
            return (float)$durationInput;
        }
    }

    /**
     * Convert decimal minutes back to menit.detik format for display
     * Example: 5.433 menit = 5 menit 26 detik = 5.26
     */
    private function convertMinutesToDurationFormat($decimalMinutes)
    {
        $minutes = floor($decimalMinutes);
        $seconds = round(($decimalMinutes - $minutes) * 60);
        
        // If seconds is 60, add to minutes
        if ($seconds >= 60) {
            $minutes += 1;
            $seconds = 0;
        }
        
        if ($seconds > 0) {
            return sprintf('%d.%02d', $minutes, $seconds);
        }
        
        return (string)$minutes;
    }


    public function leaderboard(Request $request)
    {
        $selectedClass = $request->get('class_id', 'all');
        $classes = DB::table('classes')->get();
        
        $leaderboardQuery = DB::table('users')
            ->join('classes', 'users.class_id', '=', 'classes.class_id')
            ->join('points', 'users.user_id', '=', 'points.user_id')
            ->select('users.user_id', 'users.user_name', 'classes.class_name', 'points.total_point')
            ->where('users.role', 'siswa');
            
        if ($selectedClass !== 'all') {
            $leaderboardQuery->where('users.class_id', $selectedClass);
        }
        
        $leaderboard = $leaderboardQuery->orderBy('points.total_point', 'desc')->get();

        // Add ranking
        $leaderboard = $leaderboard->map(function ($user, $index) {
            $user->ranking = $index + 1;
            return $user;
        });

        return view('admin.leaderboard', compact('leaderboard', 'classes', 'selectedClass'));
    }



    public function updateGame(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|string',
            'points' => 'required|integer|min:1',
            'difficulty' => 'required|string'
        ]);

        DB::table('challenges')
            ->where('challenge_id', $id)
            ->update([
                'question' => $request->title,
                'type' => $request->type,
                'difficulty' => $request->difficulty,
                'point' => $request->points
            ]);

        // Update options
        if ($request->has('options')) {
            // Delete existing options
            DB::table('challenge_options')->where('challenge_id', $id)->delete();
            
            // Insert new options
            $optionLabels = ['A', 'B', 'C', 'D', 'E', 'F'];
            $nextOptionId = DB::table('challenge_options')->max('option_id') + 1;
            
            foreach ($request->options as $index => $option) {
                if (!empty($option['text'])) {
                    DB::table('challenge_options')->insert([
                        'option_id' => $nextOptionId,
                        'challenge_id' => $id,
                        'option_label' => $optionLabels[$index] ?? 'A',
                        'option_text' => $option['text']
                    ]);
                    $nextOptionId++;
                }
            }
        }

        return redirect()->route('admin.games')->with('success', 'Game berhasil diperbarui!');
    }

    public function deleteGame($id)
    {
        // Hapus data terkait terlebih dahulu
        DB::table('user_challenges')->where('challenge_id', $id)->delete();
        DB::table('challenge_options')->where('challenge_id', $id)->delete();
        
        // Hapus challenge
        DB::table('challenges')->where('challenge_id', $id)->delete();
        
        return redirect()->route('admin.games')->with('success', 'Game berhasil dihapus!');
    }

    public function gameOptions($id)
    {
        $game = DB::table('challenges')->where('challenge_id', $id)->first();
        $options = DB::table('challenge_options')->where('challenge_id', $id)->get();
        
        if (!$game) {
            return redirect()->route('admin.games')->with('error', 'Game tidak ditemukan!');
        }

        return view('admin.game-options', compact('game', 'options'));
    }

    public function addGameOption(Request $request, $id)
    {
        $request->validate([
            'option_text' => 'required|string|max:255',
            'is_correct' => 'nullable|boolean'
        ]);

        $nextOptionId = DB::table('challenge_options')->max('option_id') + 1;
        $optionLabels = ['A', 'B', 'C', 'D', 'E', 'F'];
        $existingOptions = DB::table('challenge_options')->where('challenge_id', $id)->count();
        
        DB::table('challenge_options')->insert([
            'option_id' => $nextOptionId,
            'challenge_id' => $id,
            'option_label' => $optionLabels[$existingOptions] ?? 'A',
            'option_text' => $request->option_text
        ]);

        return redirect()->route('admin.game-options', $id)->with('success', 'Opsi jawaban berhasil ditambahkan!');
    }

    public function updateGameOption(Request $request, $id, $optionId)
    {
        $request->validate([
            'option_text' => 'required|string|max:255'
        ]);

        DB::table('challenge_options')
            ->where('option_id', $optionId)
            ->where('challenge_id', $id)
            ->update([
                'option_text' => $request->option_text
            ]);

        return redirect()->route('admin.game-options', $id)->with('success', 'Opsi jawaban berhasil diperbarui!');
    }

    public function deleteGameOption($id, $optionId)
    {
        DB::table('challenge_options')
            ->where('option_id', $optionId)
            ->where('challenge_id', $id)
            ->delete();

        return redirect()->route('admin.game-options', $id)->with('success', 'Opsi jawaban berhasil dihapus!');
    }

    public function quizQuestions($id)
    {
        $game = DB::table('challenges')->where('challenge_id', $id)->first();
        $questions = DB::table('questions')
            ->where('quiz_id', $id)
            ->orderBy('question_number')
            ->get();
        
        if (!$game) {
            return redirect()->route('admin.games')->with('error', 'Game tidak ditemukan!');
        }

        return view('admin.quiz-questions', compact('game', 'questions'));
    }

    public function addQuestion(Request $request, $id)
    {
        $request->validate([
            'question_text' => 'required|string',
            'correct_answer' => 'required|string|in:A,B,C,D',
            'options' => 'required|array',
            'options.A' => 'required|string',
            'options.B' => 'required|string',
            'options.C' => 'required|string',
            'options.D' => 'required|string'
        ]);

        // Get next question number
        $nextQuestionNumber = DB::table('questions')->where('quiz_id', $id)->max('question_number') + 1;
        
        // Get next question_id
        $nextQuestionId = DB::table('questions')->max('question_id') + 1;
        
        // Insert question
        DB::table('questions')->insert([
            'question_id' => $nextQuestionId,
            'quiz_id' => $id,
            'question_text' => $request->question_text,
            'correct_answer' => $request->correct_answer,
            'question_number' => $nextQuestionNumber
        ]);

        // Insert options
        $nextOptionId = DB::table('question_options')->max('option_id') + 1;
        foreach ($request->options as $label => $text) {
            DB::table('question_options')->insert([
                'option_id' => $nextOptionId,
                'question_id' => $nextQuestionId,
                'option_label' => $label,
                'option_text' => $text
            ]);
            $nextOptionId++;
        }

        return redirect()->route('admin.quiz-questions', $id)->with('success', 'Soal berhasil ditambahkan!');
    }

    public function updateQuestion(Request $request, $id, $questionId)
    {
        $request->validate([
            'question_text' => 'required|string',
            'correct_answer' => 'required|string|in:A,B,C,D',
            'options' => 'required|array',
            'options.A' => 'required|string',
            'options.B' => 'required|string',
            'options.C' => 'required|string',
            'options.D' => 'required|string'
        ]);

        // Update question
        DB::table('questions')
            ->where('question_id', $questionId)
            ->where('quiz_id', $id)
            ->update([
                'question_text' => $request->question_text,
                'correct_answer' => $request->correct_answer
            ]);

        // Update options
        foreach ($request->options as $label => $text) {
            DB::table('question_options')
                ->where('question_id', $questionId)
                ->where('option_label', $label)
                ->update(['option_text' => $text]);
        }

        return redirect()->route('admin.quiz-questions', $id)->with('success', 'Soal berhasil diperbarui!');
    }

    public function deleteQuestion($id, $questionId)
    {
        // Delete question options first
        DB::table('question_options')->where('question_id', $questionId)->delete();
        
        // Delete question
        DB::table('questions')
            ->where('question_id', $questionId)
            ->where('quiz_id', $id)
            ->delete();

        return redirect()->route('admin.quiz-questions', $id)->with('success', 'Soal berhasil dihapus!');
    }

    public function students(Request $request)
    {
        $selectedClass = $request->get('class_id', 'all');
        $classes = DB::table('classes')->get();
        
        $studentsQuery = DB::table('users')
            ->join('classes', 'users.class_id', '=', 'classes.class_id')
            ->where('users.role', 'siswa')
            ->select('users.*', 'classes.class_name');
        
        if ($selectedClass !== 'all') {
            $studentsQuery->where('users.class_id', $selectedClass);
        }
        
        $students = $studentsQuery->orderBy('users.created_at', 'desc')->get();

        return view('admin.students', compact('students', 'classes', 'selectedClass'));
    }

    public function deleteStudent($id)
    {
        // Cek apakah siswa ada
        $student = DB::table('users')->where('user_id', $id)->where('role', 'siswa')->first();
        
        if (!$student) {
            return redirect()->route('admin.students')->with('error', 'Siswa tidak ditemukan!');
        }

        // Cek apakah user ini membuat quiz (created_by)
        $quizzesCreated = DB::table('teacher_quizzes')->where('created_by', $id)->get();
        
        if ($quizzesCreated->count() > 0) {
            // Jika user ini membuat quiz, ubah created_by menjadi admin/guru pertama yang ditemukan
            $adminUser = DB::table('users')
                ->whereIn('role', ['guru', 'kajur'])
                ->where('user_id', '!=', $id)
                ->first();
            
            if ($adminUser) {
                // Update created_by ke admin/guru lain
                DB::table('teacher_quizzes')
                    ->where('created_by', $id)
                    ->update(['created_by' => $adminUser->user_id]);
            } else {
                // Jika tidak ada admin lain, hapus quiz yang dibuat user ini
                foreach ($quizzesCreated as $quiz) {
                    // Hapus quiz options
                    $questionIds = DB::table('teacher_quiz_questions')
                        ->where('quiz_id', $quiz->id)
                        ->pluck('id');
                    
                    if ($questionIds->count() > 0) {
                        DB::table('teacher_quiz_options')
                            ->whereIn('question_id', $questionIds)
                            ->delete();
                        
                        // Hapus quiz answers
                        DB::table('teacher_quiz_answers')
                            ->where('quiz_id', $quiz->id)
                            ->delete();
                        
                        // Hapus quiz questions
                        DB::table('teacher_quiz_questions')
                            ->where('quiz_id', $quiz->id)
                            ->delete();
                    }
                    
                    // Hapus quiz
                    DB::table('teacher_quizzes')->where('id', $quiz->id)->delete();
                }
            }
        }

        // Hapus data terkait terlebih dahulu
        DB::table('user_challenges')->where('user_id', $id)->delete();
        DB::table('video_progress')->where('user_id', $id)->delete();
        DB::table('user_video_stats')->where('user_id', $id)->delete();
        DB::table('user_quiz_answers')->where('user_id', $id)->delete();
        DB::table('teacher_quiz_answers')->where('user_id', $id)->delete();
        
        // Hapus data leaderboard yang mereferensikan points
        DB::table('leaderboard')->where('user_id', $id)->delete();
        
        // Hapus points setelah leaderboard
        DB::table('points')->where('user_id', $id)->delete();
        
        // Hapus clustering jika ada
        DB::table('clustering')->where('user_id', $id)->delete();
        
        // Hapus user
        DB::table('users')->where('user_id', $id)->delete();
        
        return redirect()->route('admin.students')->with('success', 'Akun siswa berhasil dihapus!');
    }

    public function studentProgress($id)
    {
        $student = DB::table('users')
            ->join('classes', 'users.class_id', '=', 'classes.class_id')
            ->where('users.user_id', $id)
            ->select('users.*', 'classes.class_name')
            ->first();

        if (!$student) {
            return redirect()->route('admin.students')->with('error', 'Siswa tidak ditemukan!');
        }

        // Get video progress - only show videos that match student's class
        $videoProgress = DB::table('video_progress')
            ->join('videos', 'video_progress.video_id', '=', 'videos.video_id')
            ->where('video_progress.user_id', $id)
            ->where('videos.class_id', $student->class_id) // Filter by student's class
            ->select('video_progress.*', 'videos.judul')
            ->get();

        // Get game progress from challenges (old system) - only show challenges for student's class
        $challengeProgress = DB::table('user_challenges')
            ->join('challenges', 'user_challenges.challenge_id', '=', 'challenges.challenge_id')
            ->where('user_challenges.user_id', $id)
            ->where('challenges.class_id', $student->class_id) // Filter by student's class
            ->select('user_challenges.*', 'challenges.question', 'challenges.point')
            ->get();

        // Get quiz progress from teacher quizzes (new system) - only show quizzes for student's class
        $quizProgress = DB::table('teacher_quiz_answers')
            ->join('teacher_quizzes', 'teacher_quiz_answers.quiz_id', '=', 'teacher_quizzes.id')
            ->join('teacher_quiz_questions', 'teacher_quiz_answers.question_id', '=', 'teacher_quiz_questions.id')
            ->where('teacher_quiz_answers.user_id', $id)
            ->where('teacher_quizzes.class_id', $student->class_id) // Filter by student's class
            ->select(
                'teacher_quiz_answers.*',
                'teacher_quizzes.quiz_title as quiz_title',
                'teacher_quiz_questions.question',
                'teacher_quiz_questions.points as point'
            )
            ->get();

        // Group quiz answers by quiz_id to get quiz-level data - only show quizzes for student's class
        $quizResults = DB::table('teacher_quiz_answers')
            ->join('teacher_quizzes', 'teacher_quiz_answers.quiz_id', '=', 'teacher_quizzes.id')
            ->where('teacher_quiz_answers.user_id', $id)
            ->where('teacher_quizzes.class_id', $student->class_id) // Filter by student's class
            ->select(
                'teacher_quiz_answers.quiz_id',
                'teacher_quizzes.quiz_title as quiz_title',
                DB::raw('SUM(CASE WHEN teacher_quiz_answers.is_correct = 1 THEN teacher_quiz_questions.points ELSE 0 END) as total_score'),
                DB::raw('COUNT(*) as total_questions'),
                DB::raw('SUM(CASE WHEN teacher_quiz_answers.is_correct = 1 THEN 1 ELSE 0 END) as correct_answers'),
                DB::raw('MAX(teacher_quiz_answers.answered_at) as answered_at')
            )
            ->join('teacher_quiz_questions', 'teacher_quiz_answers.question_id', '=', 'teacher_quiz_questions.id')
            ->groupBy('teacher_quiz_answers.quiz_id', 'teacher_quizzes.quiz_title')
            ->get();

        // Combine challenge progress and quiz results
        $gameProgress = collect();
        
        // Add challenge progress
        foreach ($challengeProgress as $challenge) {
            $gameProgress->push((object)[
                'type' => 'challenge',
                'question' => $challenge->question,
                'point' => $challenge->point,
                'is_correct' => $challenge->is_correct,
                'score' => $challenge->is_correct ? $challenge->point : 0,
                'answered_at' => $challenge->answered_at ?? null
            ]);
        }

        // Add quiz results
        foreach ($quizResults as $quiz) {
            $percentage = $quiz->total_questions > 0 ? round(($quiz->correct_answers / $quiz->total_questions) * 100, 2) : 0;
            $gameProgress->push((object)[
                'type' => 'quiz',
                'question' => $quiz->quiz_title,
                'point' => $quiz->total_score,
                'is_correct' => $percentage >= 60, // Consider passed if >= 60%
                'score' => $percentage,
                'answered_at' => $quiz->answered_at
            ]);
        }

        // Sort by answered_at (most recent first)
        $gameProgress = $gameProgress->sortByDesc('answered_at')->values();

        // Get points
        $points = DB::table('points')->where('user_id', $id)->first();

        // Calculate statistics - only count videos for student's class
        $totalVideos = DB::table('videos')->where('class_id', $student->class_id)->count();
        $completedVideos = $videoProgress->where('progress', 100)->count();
        $totalGames = DB::table('challenges')->where('class_id', $student->class_id)->count() + DB::table('teacher_quizzes')->where('is_active', true)->where('class_id', $student->class_id)->count();
        $completedGames = $challengeProgress->where('is_completed', true)->count() + $quizResults->count();

        return view('admin.student-progress', compact(
            'student',
            'videoProgress',
            'gameProgress',
            'points',
            'totalVideos',
            'completedVideos',
            'totalGames',
            'completedGames'
        ));
    }

    public function analytics(Request $request)
    {
        // Get selected class filter
        $selectedClass = $request->get('class_id', 'all');
        
        // Get all classes for filter dropdown
        $classes = DB::table('classes')->orderBy('class_name')->get();

        // Get student clustering data
        // First get video stats (with class filter if needed)
        // Calculate progress based on videos that belong to student's class
        $videoStatsQuery = DB::table('users')
            ->leftJoin('video_progress', function($join) {
                $join->on('users.user_id', '=', 'video_progress.user_id')
                     ->leftJoin('videos', 'video_progress.video_id', '=', 'videos.video_id')
                     ->whereColumn('videos.class_id', 'users.class_id');
            })
            ->where('users.role', 'siswa');
            
        if ($selectedClass !== 'all') {
            $videoStatsQuery->where('users.class_id', $selectedClass);
        }
        
        // Better approach: Calculate per user with subquery
        $videoStats = DB::table('users')
            ->where('users.role', 'siswa')
            ->when($selectedClass !== 'all', function($query) use ($selectedClass) {
                return $query->where('users.class_id', $selectedClass);
            })
            ->select(
                'users.user_id',
                DB::raw('COALESCE((
                    SELECT COUNT(DISTINCT vp.video_id)
                    FROM video_progress vp
                    INNER JOIN videos v ON vp.video_id = v.video_id
                    WHERE vp.user_id = users.user_id 
                    AND v.class_id = users.class_id
                ), 0) as total_videos_watched'),
                DB::raw('COALESCE((
                    SELECT AVG(vp.progress)
                    FROM video_progress vp
                    INNER JOIN videos v ON vp.video_id = v.video_id
                    WHERE vp.user_id = users.user_id 
                    AND v.class_id = users.class_id
                ), 0) as avg_video_progress')
            )
            ->get()
            ->keyBy('user_id');

        // Get quiz stats (with class filter if needed)
        $quizStatsQuery = DB::table('users')
            ->leftJoin('teacher_quiz_answers', 'users.user_id', '=', 'teacher_quiz_answers.user_id')
            ->where('users.role', 'siswa');
            
        if ($selectedClass !== 'all') {
            $quizStatsQuery->where('users.class_id', $selectedClass);
        }
        
        $quizStats = $quizStatsQuery
            ->select(
                'users.user_id',
                DB::raw('COUNT(DISTINCT teacher_quiz_answers.quiz_id) as total_quizzes_taken'),
                DB::raw('COALESCE(SUM(CASE WHEN teacher_quiz_answers.is_correct = 1 THEN 1 ELSE 0 END), 0) as total_correct_answers'),
                DB::raw('COALESCE(COUNT(teacher_quiz_answers.id), 0) as total_quiz_answers')
            )
            ->groupBy('users.user_id')
            ->get()
            ->keyBy('user_id');

        // Get points
        $pointsData = DB::table('points')
            ->select('user_id', 'total_point')
            ->get()
            ->keyBy('user_id');

        // Combine all data
        $clusteringQuery = DB::table('users')
            ->join('classes', 'users.class_id', '=', 'classes.class_id')
            ->where('users.role', 'siswa')
            ->select(
                'users.user_id',
                'users.user_name',
                'users.email',
                'classes.class_id',
                'classes.class_name'
            );

        // Apply class filter
        if ($selectedClass !== 'all') {
            $clusteringQuery->where('users.class_id', $selectedClass);
        }

        $students = $clusteringQuery->get();

        // Merge with stats
        $students = $students->map(function($student) use ($videoStats, $quizStats, $pointsData) {
            $videoStat = $videoStats->get($student->user_id);
            $quizStat = $quizStats->get($student->user_id);
            $pointData = $pointsData->get($student->user_id);

            return (object) [
                'user_id' => $student->user_id,
                'user_name' => $student->user_name,
                'email' => $student->email,
                'class_id' => $student->class_id,
                'class_name' => $student->class_name,
                'total_videos_watched' => $videoStat->total_videos_watched ?? 0,
                'avg_video_progress' => $videoStat->avg_video_progress ?? 0,
                'total_quizzes_taken' => $quizStat->total_quizzes_taken ?? 0,
                'total_correct_answers' => $quizStat->total_correct_answers ?? 0,
                'total_quiz_answers' => $quizStat->total_quiz_answers ?? 0,
                'total_points' => $pointData->total_point ?? 0
            ];
        });

        // Calculate clustering scores and assign clusters
        $clusteredStudents = $students->map(function($student) {
            // Get total videos available for this student's class
            $totalVideosInClass = DB::table('videos')
                ->where('class_id', $student->class_id)
                ->count();
            
            // Calculate activity score (0-100)
            // Video watched score (max 30 points) - based on percentage of videos watched
            $videoWatchPercentage = $totalVideosInClass > 0 
                ? ($student->total_videos_watched / $totalVideosInClass) * 100 
                : 0;
            $videoScore = min($videoWatchPercentage * 0.3, 30);
            
            // Video progress score (max 30 points) - average progress of watched videos
            $videoProgressScore = min($student->avg_video_progress * 0.3, 30);
            
            // Quiz taken score (max 20 points)
            $quizScore = min(($student->total_quizzes_taken / 5) * 20, 20);
            
            // Quiz accuracy score (max 20 points)
            $totalQuizAnswers = $student->total_quiz_answers ?? 0;
            $accuracyScore = $totalQuizAnswers > 0 
                ? min(($student->total_correct_answers / $totalQuizAnswers) * 20, 20) 
                : 0;
            
            $activityScore = $videoScore + $videoProgressScore + $quizScore + $accuracyScore;
            
            // Determine cluster (threshold: 50 points)
            $cluster = $activityScore >= 50 ? 'rajin' : 'butuh bimbingan';
            
            return [
                'user_id' => $student->user_id,
                'user_name' => $student->user_name,
                'email' => $student->email,
                'class_id' => $student->class_id,
                'class_name' => $student->class_name,
                'total_videos_watched' => $student->total_videos_watched,
                'total_videos_in_class' => $totalVideosInClass,
                'avg_video_progress' => round($student->avg_video_progress, 2),
                'total_quizzes_taken' => $student->total_quizzes_taken,
                'total_correct_answers' => $student->total_correct_answers,
                'total_quiz_answers' => $student->total_quiz_answers ?? 0,
                'total_points' => $student->total_points,
                'activity_score' => round($activityScore, 2),
                'cluster' => $cluster
            ];
        });

        // Group by cluster
        $clusteringData = [
            'rajin' => $clusteredStudents->where('cluster', 'rajin')->values(),
            'butuh_bimbingan' => $clusteredStudents->where('cluster', 'butuh bimbingan')->values()
        ];

        // Statistics
        $clusteringStats = [
            'total_students' => $clusteredStudents->count(),
            'rajin_count' => $clusteringData['rajin']->count(),
            'butuh_bimbingan_count' => $clusteringData['butuh_bimbingan']->count(),
            'rajin_percentage' => $clusteredStudents->count() > 0 
                ? round(($clusteringData['rajin']->count() / $clusteredStudents->count()) * 100, 2) 
                : 0
        ];

        return view('admin.analytics', compact(
            'clusteringData',
            'clusteringStats',
            'classes',
            'selectedClass'
        ));
    }

    // API endpoint for real-time progress data
    public function getClassProgress()
    {
        $classProgress = DB::table('classes')
            ->select(
                'classes.class_id',
                'classes.class_name',
                DB::raw('COALESCE(
                    (SELECT AVG(vp.progress) 
                     FROM video_progress vp 
                     INNER JOIN users u ON vp.user_id = u.user_id 
                     INNER JOIN videos v ON vp.video_id = v.video_id
                     WHERE u.class_id = classes.class_id 
                     AND u.role = "siswa"
                     AND v.class_id = classes.class_id), 
                    0) as avg_progress')
            )
            ->orderBy('classes.class_name')
            ->get();

        return response()->json($classProgress);
    }

    private function getClusteringDataForDashboard($selectedClass = 'all')
    {
        // Use the same logic as analytics method for consistency
        // Get video stats
        $videoStats = DB::table('users')
            ->where('users.role', 'siswa')
            ->when($selectedClass !== 'all', function($query) use ($selectedClass) {
                return $query->where('users.class_id', $selectedClass);
            })
            ->select(
                'users.user_id',
                DB::raw('COALESCE((
                    SELECT COUNT(DISTINCT vp.video_id)
                    FROM video_progress vp
                    INNER JOIN videos v ON vp.video_id = v.video_id
                    WHERE vp.user_id = users.user_id 
                    AND v.class_id = users.class_id
                ), 0) as total_videos_watched'),
                DB::raw('COALESCE((
                    SELECT AVG(vp.progress)
                    FROM video_progress vp
                    INNER JOIN videos v ON vp.video_id = v.video_id
                    WHERE vp.user_id = users.user_id 
                    AND v.class_id = users.class_id
                ), 0) as avg_video_progress')
            )
            ->get()
            ->keyBy('user_id');

        // Get quiz stats
        $quizStats = DB::table('users')
            ->leftJoin('teacher_quiz_answers', 'users.user_id', '=', 'teacher_quiz_answers.user_id')
            ->where('users.role', 'siswa')
            ->when($selectedClass !== 'all', function($query) use ($selectedClass) {
                return $query->where('users.class_id', $selectedClass);
            })
            ->select(
                'users.user_id',
                DB::raw('COUNT(DISTINCT teacher_quiz_answers.quiz_id) as total_quizzes_taken'),
                DB::raw('COALESCE(SUM(CASE WHEN teacher_quiz_answers.is_correct = 1 THEN 1 ELSE 0 END), 0) as total_correct_answers'),
                DB::raw('COALESCE(COUNT(teacher_quiz_answers.id), 0) as total_quiz_answers')
            )
            ->groupBy('users.user_id')
            ->get()
            ->keyBy('user_id');

        // Get students
        $studentsQuery = DB::table('users')
            ->join('classes', 'users.class_id', '=', 'classes.class_id')
            ->where('users.role', 'siswa');
            
        if ($selectedClass !== 'all') {
            $studentsQuery->where('users.class_id', $selectedClass);
        }
        
        $students = $studentsQuery->get();
        
        // Calculate clustering for each student
        $rajinCount = 0;
        $butuhBimbinganCount = 0;
        
        foreach ($students as $student) {
            $videoStat = $videoStats->get($student->user_id);
            $quizStat = $quizStats->get($student->user_id);
            
            $totalVideosWatched = $videoStat->total_videos_watched ?? 0;
            $avgVideoProgress = $videoStat->avg_video_progress ?? 0;
            $totalQuizzesTaken = $quizStat->total_quizzes_taken ?? 0;
            $totalCorrectAnswers = $quizStat->total_correct_answers ?? 0;
            $totalQuizAnswers = $quizStat->total_quiz_answers ?? 0;
            
            // Get total videos in class
            $totalVideosInClass = DB::table('videos')
                ->where('class_id', $student->class_id)
                ->count();
            
            // Calculate activity score (same logic as analytics)
            $videoWatchPercentage = $totalVideosInClass > 0 
                ? ($totalVideosWatched / $totalVideosInClass) * 100 
                : 0;
            $videoScore = min($videoWatchPercentage * 0.3, 30);
            $videoProgressScore = min($avgVideoProgress * 0.3, 30);
            $quizScore = min(($totalQuizzesTaken / 5) * 20, 20);
            $accuracyScore = $totalQuizAnswers > 0 
                ? min(($totalCorrectAnswers / $totalQuizAnswers) * 20, 20) 
                : 0;
            
            $activityScore = $videoScore + $videoProgressScore + $quizScore + $accuracyScore;
            
            // Determine cluster (threshold: 50 points)
            // Only count if student has some activity (watched videos or took quizzes)
            if ($totalVideosWatched > 0 || $totalQuizAnswers > 0) {
                if ($activityScore >= 50) {
                    $rajinCount++;
                } else {
                    $butuhBimbinganCount++;
                }
            }
        }
        
        return [
            'rajin' => $rajinCount,
            'butuh_bimbingan' => $butuhBimbinganCount,
            'total' => $students->count()
        ];
    }
}
