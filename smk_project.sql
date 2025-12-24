-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 24, 2025 at 09:21 AM
-- Server version: 8.0.30
-- PHP Version: 8.2.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `smk_project`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `challenges`
--

CREATE TABLE `challenges` (
  `challenge_id` int NOT NULL,
  `class_id` int DEFAULT NULL,
  `object_id` int DEFAULT NULL,
  `question` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Quiz',
  `difficulty` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Mudah',
  `correct_answer` char(1) COLLATE utf8mb4_unicode_ci NOT NULL,
  `point` int NOT NULL DEFAULT '20',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `challenges`
--

INSERT INTO `challenges` (`challenge_id`, `class_id`, `object_id`, `question`, `type`, `difficulty`, `correct_answer`, `point`, `created_at`) VALUES
(101, 1, NULL, 'Apa fungsi dari karburator pada sepeda motor?', 'quiz', 'mudah', 'B', 10, '2025-10-22 04:56:40'),
(102, 1, NULL, 'Apa fungsi dari busi pada sepeda motor?', 'quiz', 'mudah', 'B', 10, '2025-10-22 04:56:40'),
(103, 1, NULL, 'Komponen berikut yang berfungsi menggerakkan klep adalah...', 'quiz', 'mudah', 'A', 10, '2025-10-22 04:56:40'),
(104, 1, NULL, 'Apa fungsi dari piston pada mesin sepeda motor?', 'quiz', 'mudah', 'C', 10, '2025-10-22 04:56:40'),
(105, 1, NULL, 'Sistem pendingin pada sepeda motor menggunakan...', 'quiz', 'mudah', 'A', 10, '2025-10-22 04:56:40'),
(106, 1, NULL, 'Apa fungsi dari filter udara pada sepeda motor?', 'quiz', 'mudah', 'B', 10, '2025-10-22 04:56:40'),
(107, 1, NULL, 'Komponen yang mengatur aliran bahan bakar ke karburator adalah...', 'quiz', 'mudah', 'C', 10, '2025-10-22 04:56:40'),
(108, 1, NULL, 'Apa fungsi dari rantai pada sepeda motor?', 'quiz', 'mudah', 'A', 10, '2025-10-22 04:56:40'),
(109, 1, NULL, 'Sistem pengereman pada sepeda motor terdiri dari...', 'quiz', 'mudah', 'B', 10, '2025-10-22 04:56:40'),
(110, 1, NULL, 'Apa fungsi dari aki pada sepeda motor?', 'quiz', 'mudah', 'C', 10, '2025-10-22 04:56:40'),
(111, 1, NULL, 'Komponen yang mengatur putaran mesin adalah...', 'quiz', 'mudah', 'A', 10, '2025-10-22 04:56:40'),
(112, 1, NULL, 'Apa fungsi dari knalpot pada sepeda motor?', 'quiz', 'mudah', 'B', 10, '2025-10-22 04:56:40'),
(113, 1, NULL, 'Sistem transmisi pada sepeda motor berfungsi untuk...', 'quiz', 'mudah', 'C', 10, '2025-10-22 04:56:40'),
(114, 1, NULL, 'Apa fungsi dari oli mesin pada sepeda motor?', 'quiz', 'mudah', 'A', 10, '2025-10-22 04:56:40'),
(115, 1, NULL, 'Komponen yang menghubungkan mesin dengan roda belakang adalah...', 'quiz', 'mudah', 'B', 10, '2025-10-22 04:56:40'),
(116, 1, NULL, 'kapan motor dibuat', 'quiz', 'mudah', 'D', 10, '2025-10-22 05:29:49');

-- --------------------------------------------------------

--
-- Table structure for table `challenge_options`
--

CREATE TABLE `challenge_options` (
  `option_id` int NOT NULL,
  `challenge_id` int NOT NULL,
  `option_label` char(1) COLLATE utf8mb4_unicode_ci NOT NULL,
  `option_text` text COLLATE utf8mb4_unicode_ci,
  `object_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `challenge_options`
--

INSERT INTO `challenge_options` (`option_id`, `challenge_id`, `option_label`, `option_text`, `object_id`) VALUES
(1001, 101, 'A', 'Mengatur suhu mesin', NULL),
(1002, 101, 'B', 'Mencampur udara dan bahan bakar', NULL),
(1003, 101, 'C', 'Mengatur tekanan oli', NULL),
(1004, 102, 'A', 'Mendinginkan mesin', NULL),
(1005, 102, 'B', 'Membakar campuran bahan bakar dan udara', NULL),
(1006, 102, 'C', 'Menyalurkan bahan bakar ke ruang bakar', NULL),
(1007, 103, 'A', 'Noken as (camshaft)', NULL),
(1008, 103, 'B', 'Piston', NULL),
(1009, 103, 'C', 'Karburator', NULL),
(1010, 104, 'A', 'Mengatur aliran bahan bakar', NULL),
(1011, 104, 'B', 'Mendinginkan mesin', NULL),
(1012, 104, 'C', 'Mengubah energi panas menjadi gerak', NULL),
(1013, 105, 'A', 'Udara', NULL),
(1014, 105, 'B', 'Air', NULL),
(1015, 105, 'C', 'Oli', NULL),
(1016, 106, 'A', 'Mengatur tekanan udara', NULL),
(1017, 106, 'B', 'Menyaring udara yang masuk ke mesin', NULL),
(1018, 106, 'C', 'Mengatur aliran bahan bakar', NULL),
(1019, 107, 'A', 'Filter udara', NULL),
(1020, 107, 'B', 'Busi', NULL),
(1021, 107, 'C', 'Kran bensin', NULL),
(1022, 108, 'A', 'Menyalurkan tenaga dari mesin ke roda', NULL),
(1023, 108, 'B', 'Mengatur kecepatan', NULL),
(1024, 108, 'C', 'Mendinginkan mesin', NULL),
(1025, 109, 'A', 'Rem depan saja', NULL),
(1026, 109, 'B', 'Rem depan dan belakang', NULL),
(1027, 109, 'C', 'Rem belakang saja', NULL),
(1028, 110, 'A', 'Mengatur kecepatan', NULL),
(1029, 110, 'B', 'Mendinginkan mesin', NULL),
(1030, 110, 'C', 'Menyimpan energi listrik', NULL),
(1031, 111, 'A', 'Gas', NULL),
(1032, 111, 'B', 'Kopling', NULL),
(1033, 111, 'C', 'Rantai', NULL),
(1034, 112, 'A', 'Mengatur kecepatan', NULL),
(1035, 112, 'B', 'Mengeluarkan gas buang', NULL),
(1036, 112, 'C', 'Mendinginkan mesin', NULL),
(1037, 113, 'A', 'Mengatur kecepatan', NULL),
(1038, 113, 'B', 'Mendinginkan mesin', NULL),
(1039, 113, 'C', 'Mengatur perbandingan gigi', NULL),
(1040, 114, 'A', 'Melumasi dan mendinginkan mesin', NULL),
(1041, 114, 'B', 'Mengatur kecepatan', NULL),
(1042, 114, 'C', 'Mengatur tekanan udara', NULL),
(1043, 115, 'A', 'Rantai', NULL),
(1044, 115, 'B', 'Rantai dan sprocket', NULL),
(1045, 115, 'C', 'Kopling', NULL),
(1046, 116, 'A', '2019', NULL),
(1047, 116, 'B', '2000', NULL),
(1048, 116, 'C', '1998', NULL),
(1049, 116, 'D', 'benar semua', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE `classes` (
  `class_id` int NOT NULL,
  `class_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `classes`
--

INSERT INTO `classes` (`class_id`, `class_name`, `created_at`, `updated_at`) VALUES
(1, 'TSM X', '2025-10-22 09:55:19', '2025-10-22 09:55:19'),
(2, 'TSM XI', '2025-10-22 09:55:19', '2025-10-22 09:55:19'),
(3, 'TSM XII', '2025-10-22 09:55:19', '2025-10-22 09:55:19');

-- --------------------------------------------------------

--
-- Table structure for table `clustering`
--

CREATE TABLE `clustering` (
  `clustering_id` int NOT NULL,
  `user_id` int NOT NULL,
  `class_id` int NOT NULL,
  `cluster_label` enum('rajin','butuh bimbingan') COLLATE utf8mb4_unicode_ci NOT NULL,
  `assigned_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leaderboard`
--

CREATE TABLE `leaderboard` (
  `leaderboard_id` int NOT NULL,
  `user_id` int NOT NULL,
  `point_id` int NOT NULL,
  `ranking` int NOT NULL,
  `class_id` int NOT NULL,
  `periode` enum('weekly','monthly','semester') COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2025_09_29_083809_create_classes_table', 1),
(2, '2025_09_29_083914_create_users_table', 1),
(3, '2025_09_29_084030_create_videos_table', 1),
(4, '2025_09_29_084151_create_video_progress_table', 1),
(5, '2025_09_29_084311_create_user_video_stats_table', 1),
(6, '2025_09_29_084422_create_points_table', 1),
(7, '2025_09_29_084516_create_leaderboard_table', 1),
(8, '2025_09_29_084545_create_tools_table', 1),
(9, '2025_09_29_084640_create_spareparts_table', 1),
(10, '2025_09_29_084751_create_objects_table', 1),
(11, '2025_09_29_084830_create_challenges_table', 1),
(12, '2025_09_29_084924_create_challenge_options_table', 1),
(13, '2025_09_29_085028_create_user_challenges_table', 1),
(14, '2025_09_29_085129_create_clustering_table', 1),
(15, '2025_10_01_025057_create_sessions_table', 1),
(16, '2025_10_20_043040_create_cache_table', 1),
(17, '2025_10_20_043052_create_jobs_table', 1),
(18, '2025_10_22_015834_add_type_and_difficulty_to_challenges_table', 1),
(19, '2025_10_22_021702_create_questions_table', 1),
(20, '2025_10_22_021717_create_question_options_table', 1),
(21, '2025_10_22_021751_create_user_quiz_answers_table', 1),
(22, '2025_10_22_041737_add_class_id_to_videos_table', 2),
(23, '2025_10_22_041742_add_class_id_to_challenges_table', 2),
(24, '2025_10_22_053837_create_quiz_settings_table', 3),
(26, '2025_10_22_154548_create_teacher_quizzes_table', 4),
(30, '2025_10_22_154821_create_teacher_quizzes_table_v2', 5),
(31, '2025_10_22_154847_create_teacher_quiz_questions_table_v2', 5),
(32, '2025_10_22_154904_create_teacher_quiz_options_table_v2', 5),
(33, '2025_11_12_061414_create_password_reset_tokens_table', 6),
(34, '2025_11_17_064318_add_image_to_teacher_quiz_questions_table', 7),
(35, '2025_11_18_043311_add_profile_picture_to_users_table', 8);

-- --------------------------------------------------------

--
-- Table structure for table `objects`
--

CREATE TABLE `objects` (
  `object_id` int NOT NULL,
  `sparepart_id` int DEFAULT NULL,
  `tool_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `points`
--

CREATE TABLE `points` (
  `point_id` int NOT NULL,
  `user_id` int NOT NULL,
  `total_point` int NOT NULL DEFAULT '0',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `points`
--

INSERT INTO `points` (`point_id`, `user_id`, `total_point`, `updated_at`) VALUES
(2, 999, 130, '2025-10-22 02:55:20'),
(3, 998, 0, '2025-10-22 02:55:20'),
(4, 1000, 0, '2025-12-02 04:47:25'),
(5, 1001, 0, '2025-12-03 23:54:13'),
(6, 1002, 0, '2025-12-04 00:19:30'),
(7, 1003, 80, '2025-12-04 02:35:56'),
(8, 1004, 0, '2025-12-10 04:14:31'),
(9, 1005, 60, '2025-12-11 05:37:45');

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `question_id` int NOT NULL,
  `quiz_id` int NOT NULL,
  `question_text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `correct_answer` varchar(1) COLLATE utf8mb4_unicode_ci NOT NULL,
  `question_number` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `question_options`
--

CREATE TABLE `question_options` (
  `option_id` int NOT NULL,
  `question_id` int NOT NULL,
  `option_label` varchar(1) COLLATE utf8mb4_unicode_ci NOT NULL,
  `option_text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quiz_settings`
--

CREATE TABLE `quiz_settings` (
  `id` bigint UNSIGNED NOT NULL,
  `class_id` int NOT NULL,
  `quiz_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Kuis Teknik Sepeda Motor',
  `quiz_description` text COLLATE utf8mb4_unicode_ci,
  `total_questions` int NOT NULL DEFAULT '15',
  `time_limit` int NOT NULL DEFAULT '20',
  `points_per_question` int NOT NULL DEFAULT '10',
  `difficulty` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'mudah',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `quiz_settings`
--

INSERT INTO `quiz_settings` (`id`, `class_id`, `quiz_title`, `quiz_description`, `total_questions`, `time_limit`, `points_per_question`, `difficulty`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'Kuis Teknik Sepeda Motor - Kelas X', 'Jawab soal-soal pilihan ganda dengan benar untuk mendapatkan poin!', 25, 30, 10, 'mudah', 1, '2025-10-22 12:53:25', '2025-10-22 12:53:25'),
(2, 2, 'Kuis Teknik Sepeda Motor - Kelas XI', 'Jawab soal-soal pilihan ganda dengan benar untuk mendapatkan poin!', 30, 35, 10, 'sedang', 1, '2025-10-22 12:53:25', '2025-10-22 12:53:25'),
(3, 3, 'Kuis Teknik Sepeda Motor - Kelas XII', 'Jawab soal-soal pilihan ganda dengan benar untuk mendapatkan poin!', 25, 30, 10, 'sulit', 1, '2025-10-22 12:53:25', '2025-10-22 12:53:25');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('hBu0ZcXNclZtep6sZefWle8iqiFgaWkHfhGcTmjE', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTo4OntzOjY6Il9mbGFzaCI7YToyOntzOjM6Im5ldyI7YTowOnt9czozOiJvbGQiO2E6MDp7fX1zOjY6Il90b2tlbiI7czo0MDoicFZaTU56eE1NSHZ2Z2dyQ2NBTVpYNFFzc09wM2JaYUtyTWJtUjFoMSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzQ6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi92aWRlb3MiO31zOjc6InVzZXJfaWQiO2k6OTk4O3M6OToidXNlcl9uYW1lIjtzOjE2OiJHdXJ1IFNNSyBQcm9qZWN0IjtzOjU6ImVtYWlsIjtzOjE5OiJndXJ1QHNta3Byb2plY3QuY29tIjtzOjQ6InJvbGUiO3M6NDoiZ3VydSI7czo4OiJjbGFzc19pZCI7aToxO30=', 1765952370);

-- --------------------------------------------------------

--
-- Table structure for table `spareparts`
--

CREATE TABLE `spareparts` (
  `sparepart_id` int NOT NULL,
  `sparepart_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sparepart_image` blob
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `teacher_quizzes`
--

CREATE TABLE `teacher_quizzes` (
  `id` bigint UNSIGNED NOT NULL,
  `class_id` int NOT NULL,
  `quiz_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quiz_description` text COLLATE utf8mb4_unicode_ci,
  `total_questions` int NOT NULL,
  `time_limit` int NOT NULL,
  `points_per_question` int NOT NULL DEFAULT '10',
  `difficulty` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'mudah',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_by` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `teacher_quizzes`
--

INSERT INTO `teacher_quizzes` (`id`, `class_id`, `quiz_title`, `quiz_description`, `total_questions`, `time_limit`, `points_per_question`, `difficulty`, `is_active`, `created_by`, `created_at`, `updated_at`) VALUES
(5, 1, 'latihan tsm 10 pertemuan 1', 'kerjakanlah', 1, 5, 1, 'mudah', 1, 999, '2025-12-04 17:22:57', '2025-12-04 17:22:57'),
(7, 2, 'latihan tsm 11 pertemuan 1', 'kerjakanlah', 2, 5, 1, 'sedang', 1, 999, '2025-12-04 17:33:23', '2025-12-04 17:33:23'),
(8, 2, 'latihan tsm 11 pertemuan 1', NULL, 1, 1, 1, 'mudah', 1, 1003, '2025-12-04 20:05:04', '2025-12-04 20:05:04');

-- --------------------------------------------------------

--
-- Table structure for table `teacher_quiz_answers`
--

CREATE TABLE `teacher_quiz_answers` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `quiz_id` bigint UNSIGNED NOT NULL,
  `question_id` bigint UNSIGNED NOT NULL,
  `user_answer` varchar(1) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_correct` tinyint(1) NOT NULL,
  `answered_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `teacher_quiz_answers`
--

INSERT INTO `teacher_quiz_answers` (`id`, `user_id`, `quiz_id`, `question_id`, `user_answer`, `is_correct`, `answered_at`, `created_at`, `updated_at`) VALUES
(1, 998, 1, 1, 'D', 0, '2025-10-23 19:38:51', '2025-10-23 19:38:51', '2025-10-23 19:38:51'),
(2, 998, 1, 2, 'C', 0, '2025-10-23 19:38:51', '2025-10-23 19:38:51', '2025-10-23 19:38:51'),
(3, 998, 1, 3, 'B', 0, '2025-10-23 19:38:51', '2025-10-23 19:38:51', '2025-10-23 19:38:51'),
(4, 999, 1, 1, 'D', 0, '2025-10-23 17:38:51', '2025-10-23 17:38:51', '2025-10-23 17:38:51'),
(5, 999, 1, 2, 'D', 1, '2025-10-23 17:38:51', '2025-10-23 17:38:51', '2025-10-23 17:38:51'),
(6, 999, 1, 3, 'C', 0, '2025-10-23 17:38:51', '2025-10-23 17:38:51', '2025-10-23 17:38:51'),
(14, 1003, 7, 11, 'A', 1, '2025-12-04 20:02:02', '2025-12-04 18:08:35', '2025-12-04 20:02:02'),
(15, 1003, 7, 12, 'A', 1, '2025-12-04 19:13:23', '2025-12-04 18:08:35', '2025-12-04 19:13:23'),
(16, 1003, 8, 13, 'A', 1, '2025-12-04 20:11:46', '2025-12-04 20:11:46', '2025-12-04 20:11:46'),
(17, 1005, 8, 13, 'A', 1, '2025-12-11 07:03:15', '2025-12-11 05:53:04', '2025-12-11 07:03:15');

-- --------------------------------------------------------

--
-- Table structure for table `teacher_quiz_options`
--

CREATE TABLE `teacher_quiz_options` (
  `id` bigint UNSIGNED NOT NULL,
  `question_id` bigint UNSIGNED NOT NULL,
  `option_label` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `option_text` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `teacher_quiz_options`
--

INSERT INTO `teacher_quiz_options` (`id`, `question_id`, `option_label`, `option_text`, `created_at`, `updated_at`) VALUES
(33, 9, 'A', 'etanol', '2025-12-04 17:22:57', '2025-12-04 17:22:57'),
(34, 9, 'B', 'etanol ron', '2025-12-04 17:22:57', '2025-12-04 17:22:57'),
(35, 9, 'C', 'pertamina', '2025-12-04 17:22:57', '2025-12-04 17:22:57'),
(36, 9, 'D', 'pertalite', '2025-12-04 17:22:57', '2025-12-04 17:22:57'),
(41, 11, 'A', 'etanol', '2025-12-04 17:33:23', '2025-12-04 17:33:23'),
(42, 11, 'B', 'etanol ron', '2025-12-04 17:33:23', '2025-12-04 17:33:23'),
(43, 11, 'C', 'pertamina', '2025-12-04 17:33:23', '2025-12-04 17:33:23'),
(44, 11, 'D', 'pertalite', '2025-12-04 17:33:23', '2025-12-04 17:33:23'),
(45, 12, 'A', 'Hitam', '2025-12-04 17:33:23', '2025-12-04 17:33:23'),
(46, 12, 'B', 'hijau', '2025-12-04 17:33:23', '2025-12-04 17:33:23'),
(47, 12, 'C', 'kuning', '2025-12-04 17:33:23', '2025-12-04 17:33:23'),
(48, 12, 'D', 'hijau', '2025-12-04 17:33:23', '2025-12-04 17:33:23'),
(49, 13, 'A', 'etanol', '2025-12-04 20:05:04', '2025-12-04 20:05:04'),
(50, 13, 'B', 'etanol ron', '2025-12-04 20:05:04', '2025-12-04 20:05:04'),
(51, 13, 'C', 'pertamina', '2025-12-04 20:05:04', '2025-12-04 20:05:04'),
(52, 13, 'D', 'pertalite', '2025-12-04 20:05:04', '2025-12-04 20:05:04');

-- --------------------------------------------------------

--
-- Table structure for table `teacher_quiz_questions`
--

CREATE TABLE `teacher_quiz_questions` (
  `id` bigint UNSIGNED NOT NULL,
  `quiz_id` bigint UNSIGNED NOT NULL,
  `question` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `correct_answer` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `points` int NOT NULL DEFAULT '10',
  `order_number` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `teacher_quiz_questions`
--

INSERT INTO `teacher_quiz_questions` (`id`, `quiz_id`, `question`, `image`, `correct_answer`, `points`, `order_number`, `created_at`, `updated_at`) VALUES
(9, 5, 'etanol', NULL, 'A', 1, 1, '2025-12-04 17:22:57', '2025-12-04 17:22:57'),
(11, 7, 'etanol', NULL, 'A', 10, 1, '2025-12-04 17:33:23', '2025-12-04 17:33:23'),
(12, 7, 'etanol', NULL, 'A', 10, 2, '2025-12-04 17:33:23', '2025-12-04 17:33:23'),
(13, 8, 'etanol', NULL, 'A', 10, 1, '2025-12-04 20:05:04', '2025-12-04 20:05:04');

-- --------------------------------------------------------

--
-- Table structure for table `tools`
--

CREATE TABLE `tools` (
  `tool_id` int NOT NULL,
  `tool_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tool_image` blob
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int NOT NULL,
  `user_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('siswa','guru','kajur') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'siswa',
  `class_id` int DEFAULT NULL,
  `profile_picture` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `user_name`, `email`, `password`, `role`, `class_id`, `profile_picture`, `created_at`) VALUES
(998, 'Guru SMK Project', 'guru@smkproject.com', '$2y$12$gmgE79h60ZyCTXE11kzlaeikXgnlkFOv/SfAnwnNtMAzx5Gezb3fm', 'guru', 1, NULL, '2025-10-22 09:55:20'),
(999, 'Admin SMK Project', 'admin@smkproject.com', '$2y$12$Od6M5UMm89tG7CkH7gzsdOcKnYmrWkbhZIqrUQ7SqBqaPPdeZ0Cce', 'kajur', 3, NULL, '2025-10-22 09:55:20'),
(1000, 'rega', 'Rega@gmail.com', '$2y$12$B0Z/orYDnuP79TLgJG2YVOfAvstCNoNf/ZP0KuYOGboq2.BzPcota', 'siswa', 1, NULL, '2025-12-02 19:47:25'),
(1001, 'rihan', 'rihan1@gmail.com', '$2y$12$5H5L9XBzoBJ0sGbZox310uF9jfGgQZy2EjGQ5zCWLdAEw2eM5g.k6', 'siswa', 1, NULL, '2025-12-04 14:54:13'),
(1002, 'faiq', 'faiq@gmail.com', '$2y$12$NBo3Ku/wXiXcOja6st4ZA.ibivyDxoJLkL3q.3u1mfBrJ4V0u//5W', 'siswa', 2, NULL, '2025-12-04 15:19:30'),
(1003, 'aldi', 'aldi@gmail.com', '$2y$12$RMwesyIScyhoUyUxKUGl4uv1C3YLeqPOlJEcjoEqHKr4suaR1N7P.', 'siswa', 2, NULL, '2025-12-04 17:35:56'),
(1004, 'Alvito', 'vito@gmail.com', '$2y$12$cKW3REKAgqVFsfek0.d3JuM2VIrW9f5Tnvv2wrefLm/0SP/qwLD4e', 'siswa', 3, NULL, '2025-12-10 04:14:31'),
(1005, 'Akiw Syahputra', 'akiw@gmail.com', '$2y$12$L.6JLNq336/dNp.dMCx0g.XdtJ.b42CcCza6.EmVxM.KCoGDCjhRG', 'siswa', 2, NULL, '2025-12-11 05:37:45');

-- --------------------------------------------------------

--
-- Table structure for table `user_challenges`
--

CREATE TABLE `user_challenges` (
  `user_challenges_id` int NOT NULL,
  `user_id` int NOT NULL,
  `challenge_id` int NOT NULL,
  `option_id` int NOT NULL,
  `is_correct` tinyint(1) DEFAULT NULL,
  `score` int NOT NULL DEFAULT '0',
  `answered_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_challenges`
--

INSERT INTO `user_challenges` (`user_challenges_id`, `user_id`, `challenge_id`, `option_id`, `is_correct`, `score`, `answered_at`) VALUES
(4, 999, 101, 1002, 1, 10, '2025-10-22 12:08:06'),
(5, 999, 102, 1006, 0, 0, '2025-10-22 12:08:06'),
(6, 999, 103, 1008, 0, 0, '2025-10-22 12:08:06'),
(7, 999, 104, 1011, 0, 0, '2025-10-22 12:08:06'),
(8, 999, 105, 1014, 0, 0, '2025-10-22 12:08:06'),
(9, 999, 106, 1016, 0, 0, '2025-10-22 12:08:06'),
(10, 999, 107, 1020, 0, 0, '2025-10-22 12:08:06'),
(11, 999, 108, 1022, 1, 10, '2025-10-22 12:08:06'),
(12, 999, 109, 1025, 0, 0, '2025-10-22 12:08:06'),
(13, 999, 110, 1029, 0, 0, '2025-10-22 12:08:06'),
(14, 999, 111, 1031, 1, 10, '2025-10-22 12:08:06'),
(15, 999, 112, 1035, 1, 10, '2025-10-22 12:08:06'),
(22, 999, 101, 1001, 0, 0, '2025-10-22 12:10:37'),
(23, 999, 102, 1006, 0, 0, '2025-10-22 12:10:37'),
(24, 999, 103, 1008, 0, 0, '2025-10-22 12:10:37'),
(25, 999, 104, 1010, 0, 0, '2025-10-22 12:10:37'),
(26, 999, 105, 1014, 0, 0, '2025-10-22 12:10:37'),
(27, 999, 106, 1017, 1, 10, '2025-10-22 12:10:37'),
(28, 999, 107, 1019, 0, 0, '2025-10-22 12:10:37'),
(29, 999, 108, 1023, 0, 0, '2025-10-22 12:10:37'),
(30, 999, 109, 1026, 1, 10, '2025-10-22 12:10:37'),
(31, 999, 110, 1029, 0, 0, '2025-10-22 12:10:37'),
(32, 999, 111, 1032, 0, 0, '2025-10-22 12:10:37'),
(33, 999, 112, 1035, 1, 10, '2025-10-22 12:10:37');

-- --------------------------------------------------------

--
-- Table structure for table `user_quiz_answers`
--

CREATE TABLE `user_quiz_answers` (
  `answer_id` int NOT NULL,
  `user_id` int NOT NULL,
  `quiz_id` int NOT NULL,
  `question_id` int NOT NULL,
  `user_answer` varchar(1) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_correct` tinyint(1) NOT NULL,
  `answered_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_video_stats`
--

CREATE TABLE `user_video_stats` (
  `stats_id` int NOT NULL,
  `user_id` int NOT NULL,
  `total_videos` int NOT NULL DEFAULT '0',
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `videos`
--

CREATE TABLE `videos` (
  `video_id` int NOT NULL,
  `class_id` int DEFAULT NULL,
  `judul` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `video_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `duration` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `videos`
--

INSERT INTO `videos` (`video_id`, `class_id`, `judul`, `video_url`, `description`, `duration`) VALUES
(1, 1, 'Modifikasi Motor', 'https://youtu.be/Ap4RjCYpex0?si=BqyYMKPYF5ISHQ_u', 'selesaikanlah', 6);

-- --------------------------------------------------------

--
-- Table structure for table `video_progress`
--

CREATE TABLE `video_progress` (
  `progress_id` int NOT NULL,
  `user_id` int NOT NULL,
  `video_id` int NOT NULL,
  `progress` tinyint NOT NULL,
  `is_completed` tinyint(1) NOT NULL DEFAULT '0',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `video_progress`
--

INSERT INTO `video_progress` (`progress_id`, `user_id`, `video_id`, `progress`, `is_completed`, `updated_at`) VALUES
(1, 1003, 1, 0, 0, '2025-12-04 02:35:56'),
(2, 1004, 1, 0, 0, '2025-12-10 04:14:31'),
(3, 1005, 1, 0, 0, '2025-12-11 05:37:45');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `challenges`
--
ALTER TABLE `challenges`
  ADD PRIMARY KEY (`challenge_id`),
  ADD KEY `challenges_object_id_foreign` (`object_id`),
  ADD KEY `challenges_class_id_foreign` (`class_id`);

--
-- Indexes for table `challenge_options`
--
ALTER TABLE `challenge_options`
  ADD PRIMARY KEY (`option_id`),
  ADD KEY `challenge_options_challenge_id_foreign` (`challenge_id`),
  ADD KEY `challenge_options_object_id_foreign` (`object_id`);

--
-- Indexes for table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`class_id`);

--
-- Indexes for table `clustering`
--
ALTER TABLE `clustering`
  ADD PRIMARY KEY (`clustering_id`),
  ADD KEY `clustering_user_id_foreign` (`user_id`),
  ADD KEY `clustering_class_id_foreign` (`class_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `leaderboard`
--
ALTER TABLE `leaderboard`
  ADD PRIMARY KEY (`leaderboard_id`),
  ADD KEY `leaderboard_user_id_foreign` (`user_id`),
  ADD KEY `leaderboard_point_id_foreign` (`point_id`),
  ADD KEY `leaderboard_class_id_foreign` (`class_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `objects`
--
ALTER TABLE `objects`
  ADD PRIMARY KEY (`object_id`),
  ADD KEY `objects_sparepart_id_foreign` (`sparepart_id`),
  ADD KEY `objects_tool_id_foreign` (`tool_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `points`
--
ALTER TABLE `points`
  ADD PRIMARY KEY (`point_id`),
  ADD KEY `points_user_id_foreign` (`user_id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`question_id`),
  ADD KEY `questions_quiz_id_foreign` (`quiz_id`);

--
-- Indexes for table `question_options`
--
ALTER TABLE `question_options`
  ADD PRIMARY KEY (`option_id`),
  ADD KEY `question_options_question_id_foreign` (`question_id`);

--
-- Indexes for table `quiz_settings`
--
ALTER TABLE `quiz_settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quiz_settings_class_id_foreign` (`class_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `spareparts`
--
ALTER TABLE `spareparts`
  ADD PRIMARY KEY (`sparepart_id`);

--
-- Indexes for table `teacher_quizzes`
--
ALTER TABLE `teacher_quizzes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `teacher_quizzes_class_id_foreign` (`class_id`),
  ADD KEY `teacher_quizzes_created_by_foreign` (`created_by`);

--
-- Indexes for table `teacher_quiz_answers`
--
ALTER TABLE `teacher_quiz_answers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `teacher_quiz_options`
--
ALTER TABLE `teacher_quiz_options`
  ADD PRIMARY KEY (`id`),
  ADD KEY `teacher_quiz_options_question_id_foreign` (`question_id`);

--
-- Indexes for table `teacher_quiz_questions`
--
ALTER TABLE `teacher_quiz_questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `teacher_quiz_questions_quiz_id_foreign` (`quiz_id`);

--
-- Indexes for table `tools`
--
ALTER TABLE `tools`
  ADD PRIMARY KEY (`tool_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_class_id_foreign` (`class_id`);

--
-- Indexes for table `user_challenges`
--
ALTER TABLE `user_challenges`
  ADD PRIMARY KEY (`user_challenges_id`),
  ADD KEY `user_challenges_user_id_foreign` (`user_id`),
  ADD KEY `user_challenges_challenge_id_foreign` (`challenge_id`),
  ADD KEY `user_challenges_option_id_foreign` (`option_id`);

--
-- Indexes for table `user_quiz_answers`
--
ALTER TABLE `user_quiz_answers`
  ADD PRIMARY KEY (`answer_id`),
  ADD KEY `user_quiz_answers_user_id_foreign` (`user_id`),
  ADD KEY `user_quiz_answers_quiz_id_foreign` (`quiz_id`),
  ADD KEY `user_quiz_answers_question_id_foreign` (`question_id`);

--
-- Indexes for table `user_video_stats`
--
ALTER TABLE `user_video_stats`
  ADD PRIMARY KEY (`stats_id`),
  ADD KEY `user_video_stats_user_id_foreign` (`user_id`);

--
-- Indexes for table `videos`
--
ALTER TABLE `videos`
  ADD PRIMARY KEY (`video_id`),
  ADD KEY `videos_class_id_foreign` (`class_id`);

--
-- Indexes for table `video_progress`
--
ALTER TABLE `video_progress`
  ADD PRIMARY KEY (`progress_id`),
  ADD KEY `video_progress_user_id_foreign` (`user_id`),
  ADD KEY `video_progress_video_id_foreign` (`video_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `quiz_settings`
--
ALTER TABLE `quiz_settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `teacher_quizzes`
--
ALTER TABLE `teacher_quizzes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `teacher_quiz_answers`
--
ALTER TABLE `teacher_quiz_answers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `teacher_quiz_options`
--
ALTER TABLE `teacher_quiz_options`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `teacher_quiz_questions`
--
ALTER TABLE `teacher_quiz_questions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `challenges`
--
ALTER TABLE `challenges`
  ADD CONSTRAINT `challenges_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`class_id`),
  ADD CONSTRAINT `challenges_object_id_foreign` FOREIGN KEY (`object_id`) REFERENCES `objects` (`object_id`);

--
-- Constraints for table `challenge_options`
--
ALTER TABLE `challenge_options`
  ADD CONSTRAINT `challenge_options_challenge_id_foreign` FOREIGN KEY (`challenge_id`) REFERENCES `challenges` (`challenge_id`),
  ADD CONSTRAINT `challenge_options_object_id_foreign` FOREIGN KEY (`object_id`) REFERENCES `objects` (`object_id`);

--
-- Constraints for table `clustering`
--
ALTER TABLE `clustering`
  ADD CONSTRAINT `clustering_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`class_id`),
  ADD CONSTRAINT `clustering_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `leaderboard`
--
ALTER TABLE `leaderboard`
  ADD CONSTRAINT `leaderboard_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`class_id`),
  ADD CONSTRAINT `leaderboard_point_id_foreign` FOREIGN KEY (`point_id`) REFERENCES `points` (`point_id`),
  ADD CONSTRAINT `leaderboard_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `objects`
--
ALTER TABLE `objects`
  ADD CONSTRAINT `objects_sparepart_id_foreign` FOREIGN KEY (`sparepart_id`) REFERENCES `spareparts` (`sparepart_id`),
  ADD CONSTRAINT `objects_tool_id_foreign` FOREIGN KEY (`tool_id`) REFERENCES `tools` (`tool_id`);

--
-- Constraints for table `points`
--
ALTER TABLE `points`
  ADD CONSTRAINT `points_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_quiz_id_foreign` FOREIGN KEY (`quiz_id`) REFERENCES `challenges` (`challenge_id`) ON DELETE CASCADE;

--
-- Constraints for table `question_options`
--
ALTER TABLE `question_options`
  ADD CONSTRAINT `question_options_question_id_foreign` FOREIGN KEY (`question_id`) REFERENCES `questions` (`question_id`) ON DELETE CASCADE;

--
-- Constraints for table `quiz_settings`
--
ALTER TABLE `quiz_settings`
  ADD CONSTRAINT `quiz_settings_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`class_id`);

--
-- Constraints for table `teacher_quizzes`
--
ALTER TABLE `teacher_quizzes`
  ADD CONSTRAINT `teacher_quizzes_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`class_id`),
  ADD CONSTRAINT `teacher_quizzes_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `teacher_quiz_options`
--
ALTER TABLE `teacher_quiz_options`
  ADD CONSTRAINT `teacher_quiz_options_question_id_foreign` FOREIGN KEY (`question_id`) REFERENCES `teacher_quiz_questions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `teacher_quiz_questions`
--
ALTER TABLE `teacher_quiz_questions`
  ADD CONSTRAINT `teacher_quiz_questions_quiz_id_foreign` FOREIGN KEY (`quiz_id`) REFERENCES `teacher_quizzes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`class_id`);

--
-- Constraints for table `user_challenges`
--
ALTER TABLE `user_challenges`
  ADD CONSTRAINT `user_challenges_challenge_id_foreign` FOREIGN KEY (`challenge_id`) REFERENCES `challenges` (`challenge_id`),
  ADD CONSTRAINT `user_challenges_option_id_foreign` FOREIGN KEY (`option_id`) REFERENCES `challenge_options` (`option_id`),
  ADD CONSTRAINT `user_challenges_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `user_quiz_answers`
--
ALTER TABLE `user_quiz_answers`
  ADD CONSTRAINT `user_quiz_answers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `user_video_stats`
--
ALTER TABLE `user_video_stats`
  ADD CONSTRAINT `user_video_stats_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `videos`
--
ALTER TABLE `videos`
  ADD CONSTRAINT `videos_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`class_id`);

--
-- Constraints for table `video_progress`
--
ALTER TABLE `video_progress`
  ADD CONSTRAINT `video_progress_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `video_progress_video_id_foreign` FOREIGN KEY (`video_id`) REFERENCES `videos` (`video_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
