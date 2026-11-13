-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 13, 2026 at 04:42 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `haki_sentra_psu`
--

-- --------------------------------------------------------

--
-- Table structure for table `app_settings`
--

CREATE TABLE `app_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(100) NOT NULL,
  `value` longtext DEFAULT NULL,
  `group` varchar(50) NOT NULL DEFAULT 'general',
  `type` varchar(30) NOT NULL DEFAULT 'text',
  `label` varchar(150) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `app_settings`
--

INSERT INTO `app_settings` (`id`, `key`, `value`, `group`, `type`, `label`, `description`, `created_at`, `updated_at`) VALUES
(1, 'app_name', 'SIM-HKIPUB', 'branding', 'text', 'Nama Singkat Aplikasi', 'Nama singkatan aplikasi yang tampil pada header sidebar dan footer.', '2026-11-12 19:37:17', '2026-11-12 19:37:17'),
(2, 'app_full_name', 'Sistem Informasi Manajemen Kekayaan Intelektual & Publikasi Ilmiah', 'branding', 'text', 'Nama Lengkap Sistem', 'Nama lengkap aplikasi / portal informasi.', '2026-11-12 19:37:17', '2026-11-12 19:37:17'),
(3, 'app_tagline', 'Melindungi Karya, Mewujudkan Inovasi Mandiri', 'branding', 'text', 'Tagline / Slogan Aplikasi', 'Motto atau slogan yang tampil di landing page dan portal publik.', '2026-11-12 19:37:17', '2026-11-12 19:37:17'),
(4, 'app_logo', NULL, 'branding', 'image', 'Logo Aplikasi', 'Logo institusi / aplikasi resmi (PNG, JPG, SVG, WebP).', '2026-11-12 19:37:17', '2026-11-12 19:37:17'),
(5, 'app_favicon', NULL, 'branding', 'image', 'Icon / Favicon Browser', 'Icon tab browser (ICO, PNG, SVG).', '2026-11-12 19:37:17', '2026-11-12 19:37:17'),
(6, 'institution_name', 'Universitas Panca Sakti', 'institution', 'text', 'Nama Perguruan Tinggi', 'Nama perguruan tinggi yang ditampilkan di dashboard dan sidebar.', '2026-11-12 19:37:17', '2026-11-12 19:37:17'),
(7, 'institution_legal_name', 'Universitas Panca Sakti Bekasi', 'institution', 'text', 'Nama Resmi Institusi (Lengkap)', 'Nama legal perguruan tinggi untuk kop surat, sertifikat, dan laporan resmi.', '2026-11-12 19:37:17', '2026-11-12 19:37:17'),
(8, 'institution_short_name', 'UPS Bekasi', 'institution', 'text', 'Singkatan Nama Kampus', 'Singkatan atau akronim institusi.', '2026-11-12 19:37:17', '2026-11-12 19:37:17'),
(9, 'institution_city', 'Bekasi', 'institution', 'text', 'Kota Institusi', 'Nama kota yang digunakan untuk titimangsa tanggal surat dan sertifikat (misal: Bekasi, 14 Oktober 2026).', '2026-11-12 19:37:17', '2026-11-12 19:37:17'),
(10, 'institution_address', 'Jl. Raya Hankam No. 54, Pondok Gede, Kota Bekasi, Jawa Barat', 'institution', 'textarea', 'Alamat Lengkap Kampus', 'Alamat sekretariat untuk kop surat, sertifikat, dan landing page.', '2026-11-12 19:37:17', '2026-11-12 19:37:17'),
(11, 'institution_postal_code', '17414', 'institution', 'text', 'Kode Pos', 'Kode pos domisili kampus.', '2026-11-12 19:37:17', '2026-11-12 19:37:17'),
(12, 'institution_phone', '(021) 84978888', 'institution', 'text', 'Nomor Telepon / WhatsApp', 'Nomor kontak resmi LPPM / Sentra HKI.', '2026-11-12 19:37:17', '2026-11-12 19:37:17'),
(13, 'institution_email', 'lppm@panca-sakti.ac.id', 'institution', 'text', 'Email Resmi Institusi', 'Alamat email korespondensi LPPM.', '2026-11-12 19:37:17', '2026-11-12 19:37:17'),
(14, 'institution_website', 'https://panca-sakti.ac.id', 'institution', 'text', 'Website Resmi Kampus', 'URL website resmi perguruan tinggi.', '2026-11-12 19:37:17', '2026-11-12 19:37:17'),
(15, 'lppm_name', 'Lembaga Penelitian dan Pengabdian kepada Masyarakat (LPPM)', 'organization', 'text', 'Nama Lembaga LPPM', 'Nama lengkap lembaga penelitian kampus.', '2026-11-12 19:37:17', '2026-11-12 19:37:17'),
(16, 'sentra_hki_name', 'Sentra Kekayaan Intelektual (Sentra HKI)', 'organization', 'text', 'Nama Unit Sentra HKI', 'Nama unit sentra kekayaan intelektual.', '2026-11-12 19:37:17', '2026-11-12 19:37:17'),
(17, 'lppm_building', 'Gedung LPPM Lantai 2, Kampus Universitas Panca Sakti Bekasi', 'organization', 'text', 'Lokasi Gedung / Ruang Layanan', 'Lokasi fisik ruang sentra HKI & LPPM.', '2026-11-12 19:37:17', '2026-11-12 19:37:17'),
(18, 'head_lppm_name', 'Dr. H. Ahmad Fauzi, M.T.', 'leader', 'text', 'Nama Lengkap Kepala LPPM', 'Nama lengkap pimpinan LPPM beserta gelar akademik.', '2026-11-12 19:37:17', '2026-11-12 19:37:17'),
(19, 'head_lppm_nidn', '0412057801', 'leader', 'text', 'NIDN / NIP Kepala LPPM', 'Nomor Induk Dosen Nasional atau NIP Kepala LPPM.', '2026-11-12 19:37:17', '2026-11-12 19:37:17'),
(20, 'head_lppm_title', 'Ketua Lembaga Penelitian dan Pengabdian kepada Masyarakat (LPPM)', 'leader', 'text', 'Sebutan Jabatan Kepala LPPM', 'Sebutan resmi jabatan pimpinan di atas tanda tangan surat.', '2026-11-12 19:37:17', '2026-11-12 19:37:17'),
(21, 'head_lppm_signature', NULL, 'leader', 'image', 'Tanda Tangan & Stempel Resmi Digital', 'File gambar stempel & tanda tangan transparan (PNG) untuk dicantumkan pada sertifikat/surat keterangan.', '2026-11-12 19:37:17', '2026-11-12 19:37:17'),
(22, 'head_sentra_name', 'Dr. H. Ahmad Fauzi, M.T.', 'leader', 'text', 'Nama Ketua Sentra HKI', 'Nama ketua unit sentra HKI (bisa sama atau berbeda dengan Ketua LPPM).', '2026-11-12 19:37:17', '2026-11-12 19:37:17'),
(23, 'head_sentra_nidn', '0412057801', 'leader', 'text', 'NIDN / NIP Ketua Sentra HKI', 'NIDN / NIP Ketua Sentra HKI.', '2026-11-12 19:37:17', '2026-11-12 19:37:17'),
(24, 'head_sentra_title', 'Ketua Sentra HKI Universitas', 'leader', 'text', 'Sebutan Jabatan Ketua Sentra HKI', 'Sebutan jabatan ketua sentra HKI di landing page dan surat.', '2026-11-12 19:37:17', '2026-11-12 19:37:17');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--


-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `faculty_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `faculties`
--

CREATE TABLE `faculties` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` varchar(255) NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `incentive_requests`
--

CREATE TABLE `incentive_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `lecturer_id` bigint(20) UNSIGNED NOT NULL,
  `incentive_scheme_id` bigint(20) UNSIGNED NOT NULL,
  `claimable_type` varchar(255) NOT NULL,
  `claimable_id` bigint(20) UNSIGNED NOT NULL,
  `requested_amount` decimal(15,2) NOT NULL,
  `approved_amount` decimal(15,2) DEFAULT NULL,
  `bank_name` varchar(50) NOT NULL,
  `bank_account_number` varchar(50) NOT NULL,
  `bank_account_holder` varchar(100) NOT NULL,
  `statement_letter_file` varchar(255) DEFAULT NULL,
  `transfer_proof_file` varchar(255) DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'submitted',
  `disbursed_at` date DEFAULT NULL,
  `reviewed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `incentive_schemes`
--

CREATE TABLE `incentive_schemes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `category` varchar(50) NOT NULL,
  `tier` varchar(50) DEFAULT NULL,
  `amount` decimal(15,2) NOT NULL,
  `fiscal_year` year(4) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `information`
--

CREATE TABLE `information` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL DEFAULT 'panduan',
  `description` text DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `file_type` varchar(255) DEFAULT NULL,
  `file_size` varchar(255) DEFAULT NULL,
  `external_link` varchar(255) DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `badge_text` varchar(255) DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `intellectual_properties`
--

CREATE TABLE `intellectual_properties` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `title` text NOT NULL,
  `ipr_type` varchar(50) NOT NULL,
  `sub_type` varchar(100) DEFAULT NULL,
  `application_number` varchar(100) DEFAULT NULL,
  `application_date` date DEFAULT NULL,
  `registration_number` varchar(100) DEFAULT NULL,
  `grant_date` date DEFAULT NULL,
  `djki_status` varchar(50) NOT NULL DEFAULT 'draft',
  `rights_holder` varchar(255) NOT NULL DEFAULT 'Universitas',
  `certificate_file` varchar(255) DEFAULT NULL,
  `description_file` varchar(255) DEFAULT NULL,
  `verification_status` varchar(50) NOT NULL DEFAULT 'pending',
  `verified_by` bigint(20) UNSIGNED DEFAULT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `verification_note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ipr_inventors`
--

CREATE TABLE `ipr_inventors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `intellectual_property_id` bigint(20) UNSIGNED NOT NULL,
  `lecturer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `inventor_name` varchar(255) NOT NULL,
  `inventor_type` varchar(50) NOT NULL DEFAULT 'internal_lecturer',
  `role` varchar(50) NOT NULL DEFAULT 'inventor_anggota',
  `inventor_order` smallint(5) UNSIGNED NOT NULL DEFAULT 1,
  `contribution_percentage` decimal(5,2) DEFAULT NULL,
  `affiliation` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` smallint(5) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lecturers`
--

CREATE TABLE `lecturers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `department_id` bigint(20) UNSIGNED NOT NULL,
  `nidn` varchar(20) DEFAULT NULL,
  `nip` varchar(30) DEFAULT NULL,
  `front_title` varchar(30) DEFAULT NULL,
  `back_title` varchar(30) DEFAULT NULL,
  `academic_rank` varchar(50) DEFAULT NULL,
  `sinta_id` varchar(50) DEFAULT NULL,
  `scopus_id` varchar(50) DEFAULT NULL,
  `google_scholar_id` varchar(50) DEFAULT NULL,
  `orcid_id` varchar(50) DEFAULT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `sinta_score_overall` int(10) UNSIGNED DEFAULT 0,
  `sinta_score_3year` int(10) UNSIGNED DEFAULT 0,
  `google_scholar_citations` int(10) UNSIGNED DEFAULT 0,
  `google_scholar_h_index` int(10) UNSIGNED DEFAULT 0,
  `google_scholar_i10_index` int(10) UNSIGNED DEFAULT 0,
  `last_synced_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_10_09_000001_create_faculties_and_departments_tables', 1),
(5, '2026_10_09_000002_create_lecturers_table', 1),
(6, '2026_10_09_000003_create_publications_and_authors_tables', 1),
(7, '2026_10_09_000004_create_intellectual_properties_and_inventors_tables', 1),
(8, '2026_10_09_000005_create_incentive_schemes_and_requests_tables', 1),
(9, '2026_10_09_000006_create_verification_logs_table', 1),
(10, '2026_10_13_000007_add_photo_to_users_table', 1),
(11, '2026_10_13_000008_create_information_table', 1),
(12, '2026_10_13_000009_create_revision_comments_table', 1),
(13, '2026_10_13_000010_add_academic_metrics_to_lecturers_table', 1),
(14, '2026_10_13_000011_add_citation_count_to_publications_table', 1),
(15, '2026_10_14_000012_create_app_settings_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `publications`
--

CREATE TABLE `publications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `title` text NOT NULL,
  `publication_type` varchar(255) NOT NULL,
  `journal_name` varchar(255) NOT NULL,
  `doi` varchar(255) DEFAULT NULL,
  `issn_isbn` varchar(50) DEFAULT NULL,
  `volume` varchar(50) DEFAULT NULL,
  `issue` varchar(50) DEFAULT NULL,
  `pages` varchar(50) DEFAULT NULL,
  `publication_year` year(4) NOT NULL,
  `publication_date` date DEFAULT NULL,
  `url` text DEFAULT NULL,
  `indexation` varchar(50) DEFAULT NULL,
  `quartile` varchar(10) DEFAULT NULL,
  `sinta_grade` varchar(10) DEFAULT NULL,
  `citation_count` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `file_path` varchar(255) DEFAULT NULL,
  `loa_path` varchar(255) DEFAULT NULL,
  `verification_status` varchar(255) NOT NULL DEFAULT 'pending',
  `verified_by` bigint(20) UNSIGNED DEFAULT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `verification_note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `publication_authors`
--

CREATE TABLE `publication_authors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `publication_id` bigint(20) UNSIGNED NOT NULL,
  `lecturer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `author_name` varchar(255) NOT NULL,
  `author_type` varchar(255) NOT NULL DEFAULT 'internal_lecturer',
  `author_role` varchar(255) NOT NULL DEFAULT 'co_author',
  `author_order` smallint(5) UNSIGNED NOT NULL DEFAULT 1,
  `affiliation` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `revision_comments`
--

CREATE TABLE `revision_comments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `verifiable_type` varchar(255) NOT NULL,
  `verifiable_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `comment` text NOT NULL,
  `attachment_path` varchar(255) DEFAULT NULL,
  `attachment_name` varchar(255) DEFAULT NULL,
  `attachment_type` varchar(20) DEFAULT NULL,
  `attachment_size` varchar(30) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--


-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'dosen',
  `phone` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `phone`, `avatar`, `photo`, `is_active`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@kampus.ac.id', NULL, '$2y$12$JMAJ/bO3HbTIFJ4JlDZwpeEQFK4CWUPnjW3sMREOwueQv3tSV8wI6', 'admin_lppm', '081200000001', 'photos/1Do2ZA5y3kh1DnYojshq9BqBmnfebZwgrJch9ljs.png', 'photos/1Do2ZA5y3kh1DnYojshq9BqBmnfebZwgrJch9ljs.png', 1, NULL, '2026-10-11 22:21:56', '2026-11-12 19:40:35');

-- --------------------------------------------------------

--
-- Table structure for table `verification_logs`
--

CREATE TABLE `verification_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `verifiable_type` varchar(255) NOT NULL,
  `verifiable_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `status_before` varchar(50) NOT NULL,
  `status_after` varchar(50) NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `app_settings`
--
ALTER TABLE `app_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `app_settings_key_unique` (`key`),
  ADD KEY `app_settings_group_index` (`group`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `departments_code_unique` (`code`),
  ADD KEY `departments_faculty_id_foreign` (`faculty_id`);

--
-- Indexes for table `faculties`
--
ALTER TABLE `faculties`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `faculties_code_unique` (`code`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  ADD KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`);

--
-- Indexes for table `incentive_requests`
--
ALTER TABLE `incentive_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `incentive_requests_lecturer_id_foreign` (`lecturer_id`),
  ADD KEY `incentive_requests_incentive_scheme_id_foreign` (`incentive_scheme_id`),
  ADD KEY `incentive_requests_claimable_type_claimable_id_index` (`claimable_type`,`claimable_id`),
  ADD KEY `incentive_requests_reviewed_by_foreign` (`reviewed_by`),
  ADD KEY `incentive_requests_status_index` (`status`);

--
-- Indexes for table `incentive_schemes`
--
ALTER TABLE `incentive_schemes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `incentive_schemes_category_index` (`category`),
  ADD KEY `incentive_schemes_fiscal_year_index` (`fiscal_year`);

--
-- Indexes for table `information`
--
ALTER TABLE `information`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `information_slug_unique` (`slug`),
  ADD KEY `information_created_by_foreign` (`created_by`);

--
-- Indexes for table `intellectual_properties`
--
ALTER TABLE `intellectual_properties`
  ADD PRIMARY KEY (`id`),
  ADD KEY `intellectual_properties_user_id_foreign` (`user_id`),
  ADD KEY `intellectual_properties_verified_by_foreign` (`verified_by`),
  ADD KEY `intellectual_properties_ipr_type_index` (`ipr_type`),
  ADD KEY `intellectual_properties_application_number_index` (`application_number`),
  ADD KEY `intellectual_properties_registration_number_index` (`registration_number`),
  ADD KEY `intellectual_properties_djki_status_index` (`djki_status`),
  ADD KEY `intellectual_properties_verification_status_index` (`verification_status`);

--
-- Indexes for table `ipr_inventors`
--
ALTER TABLE `ipr_inventors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ipr_inventors_intellectual_property_id_foreign` (`intellectual_property_id`),
  ADD KEY `ipr_inventors_lecturer_id_foreign` (`lecturer_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lecturers`
--
ALTER TABLE `lecturers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `lecturers_nidn_unique` (`nidn`),
  ADD UNIQUE KEY `lecturers_nip_unique` (`nip`),
  ADD KEY `lecturers_user_id_foreign` (`user_id`),
  ADD KEY `lecturers_department_id_foreign` (`department_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `publications`
--
ALTER TABLE `publications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `publications_user_id_foreign` (`user_id`),
  ADD KEY `publications_verified_by_foreign` (`verified_by`),
  ADD KEY `publications_doi_index` (`doi`),
  ADD KEY `publications_publication_year_index` (`publication_year`),
  ADD KEY `publications_verification_status_index` (`verification_status`);

--
-- Indexes for table `publication_authors`
--
ALTER TABLE `publication_authors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `publication_authors_publication_id_foreign` (`publication_id`),
  ADD KEY `publication_authors_lecturer_id_foreign` (`lecturer_id`);

--
-- Indexes for table `revision_comments`
--
ALTER TABLE `revision_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `revision_comments_verifiable_type_verifiable_id_index` (`verifiable_type`,`verifiable_id`),
  ADD KEY `revision_comments_user_id_foreign` (`user_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `verification_logs`
--
ALTER TABLE `verification_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `verification_logs_verifiable_type_verifiable_id_index` (`verifiable_type`,`verifiable_id`),
  ADD KEY `verification_logs_user_id_foreign` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `app_settings`
--
ALTER TABLE `app_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `faculties`
--
ALTER TABLE `faculties`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `incentive_requests`
--
ALTER TABLE `incentive_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `incentive_schemes`
--
ALTER TABLE `incentive_schemes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `information`
--
ALTER TABLE `information`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `intellectual_properties`
--
ALTER TABLE `intellectual_properties`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ipr_inventors`
--
ALTER TABLE `ipr_inventors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lecturers`
--
ALTER TABLE `lecturers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `publications`
--
ALTER TABLE `publications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `publication_authors`
--
ALTER TABLE `publication_authors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `revision_comments`
--
ALTER TABLE `revision_comments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `verification_logs`
--
ALTER TABLE `verification_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `departments`
--
ALTER TABLE `departments`
  ADD CONSTRAINT `departments_faculty_id_foreign` FOREIGN KEY (`faculty_id`) REFERENCES `faculties` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `incentive_requests`
--
ALTER TABLE `incentive_requests`
  ADD CONSTRAINT `incentive_requests_incentive_scheme_id_foreign` FOREIGN KEY (`incentive_scheme_id`) REFERENCES `incentive_schemes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `incentive_requests_lecturer_id_foreign` FOREIGN KEY (`lecturer_id`) REFERENCES `lecturers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `incentive_requests_reviewed_by_foreign` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `information`
--
ALTER TABLE `information`
  ADD CONSTRAINT `information_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `intellectual_properties`
--
ALTER TABLE `intellectual_properties`
  ADD CONSTRAINT `intellectual_properties_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `intellectual_properties_verified_by_foreign` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `ipr_inventors`
--
ALTER TABLE `ipr_inventors`
  ADD CONSTRAINT `ipr_inventors_intellectual_property_id_foreign` FOREIGN KEY (`intellectual_property_id`) REFERENCES `intellectual_properties` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ipr_inventors_lecturer_id_foreign` FOREIGN KEY (`lecturer_id`) REFERENCES `lecturers` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `lecturers`
--
ALTER TABLE `lecturers`
  ADD CONSTRAINT `lecturers_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lecturers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `publications`
--
ALTER TABLE `publications`
  ADD CONSTRAINT `publications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `publications_verified_by_foreign` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `publication_authors`
--
ALTER TABLE `publication_authors`
  ADD CONSTRAINT `publication_authors_lecturer_id_foreign` FOREIGN KEY (`lecturer_id`) REFERENCES `lecturers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `publication_authors_publication_id_foreign` FOREIGN KEY (`publication_id`) REFERENCES `publications` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `revision_comments`
--
ALTER TABLE `revision_comments`
  ADD CONSTRAINT `revision_comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `verification_logs`
--
ALTER TABLE `verification_logs`
  ADD CONSTRAINT `verification_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
