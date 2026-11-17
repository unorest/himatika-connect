-- ==============================================================
-- DATABASE EXPORT: HIMATIKA CONNECT / HIMATIKA HUB
-- Generated at: 2026-11-16 12:15:23
-- Target DBMS: MySQL 8.0+ / MariaDB 10.4+
-- Charset: utf8mb4 / utf8mb4_unicode_ci
-- ==============================================================

SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+07:00";

-- --------------------------------------------------------
-- Table structure for: hmt_divisions
-- --------------------------------------------------------
DROP TABLE IF EXISTS `hmt_divisions`;
CREATE TABLE `hmt_divisions` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `slug` varchar(120) NOT NULL,
  `description` text DEFAULT NULL,
  `color` varchar(7) NOT NULL DEFAULT '#3B82F6',
  `icon` varchar(50) DEFAULT NULL,
  `head_user_id` int(10) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `head_user_id` (`head_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Dumping data for table: hmt_divisions
-- --------------------------------------------------------
INSERT INTO `hmt_divisions` (`id`, `name`, `slug`, `description`, `color`, `icon`, `head_user_id`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Divisi Stupport', 'stupport', 'Bertanggung jawab atas operasional, sarana prasarana, logistik perlengkapan, serta dukungan teknis kesekretariatan himpunan.', '#10B981', 'life-ring', 6, 1, '2026-11-16 12:15:23', '2026-11-16 12:15:23', NULL),
(2, 'Divisi PDD', 'pdd', 'Publikasi, Dekorasi, & Dokumentasi — mengelola media sosial, branding visual, liputan dokumentasi kegiatan, dan dekorasi acara.', '#F59E0B', 'camera-retro', 10, 1, '2026-11-16 12:15:23', '2026-11-16 12:15:23', NULL),
(3, 'Divisi PSDM', 'pdsm', 'Pengembangan Daya Saing Mahasiswa — bertanggung jawab atas kaderisasi, pelatihan kepemimpinan, dan peningkatan prestasi akademik anggota.', '#8B5CF6', 'users-gear', 13, 1, '2026-11-16 12:15:23', '2026-11-16 12:15:23', NULL),
(4, 'Divisi Intellogy', 'intellogy', 'Intelligence & Technology — mengawal inovasi riset teknologi informasi, pengembangan perangkat lunak, dan kompetisi saintifik.', '#06B6D4', 'microchip', 18, 1, '2026-11-16 12:15:23', '2026-11-16 12:15:23', NULL);

-- --------------------------------------------------------
-- Table structure for: hmt_users
-- --------------------------------------------------------
DROP TABLE IF EXISTS `hmt_users`;
CREATE TABLE `hmt_users` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `nim` varchar(20) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `role` enum('admin','kadiv','staff') NOT NULL DEFAULT 'staff',
  `division_id` int(10) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `last_login` datetime DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `nim` (`nim`),
  UNIQUE KEY `email` (`email`),
  KEY `division_id` (`division_id`),
  CONSTRAINT `fk_users_division` FOREIGN KEY (`division_id`) REFERENCES `hmt_divisions` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Dumping data for table: hmt_users (21 Anggota Resmi HIMATIKA)
-- --------------------------------------------------------
INSERT INTO `hmt_users` (`id`, `name`, `nim`, `email`, `password`, `avatar`, `role`, `division_id`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Rabiah Aladawiyah', '240401000000', 'rabiah@himatika.org', 'y$x0WzAgx8Ou/yx4vt/yhLFex9VHK/LseqTAUd4feC7Y4RgB6YDeUvC', NULL, 'admin', NULL, 1, '2026-11-17 02:26:00', '2026-11-17 02:26:00', NULL),
(2, 'Samuel Binsar Aditya Simamora', '240401000000', 'samuel@himatika.org', 'y$x0WzAgx8Ou/yx4vt/yhLFex9VHK/LseqTAUd4feC7Y4RgB6YDeUvC', NULL, 'admin', NULL, 1, '2026-11-17 02:26:00', '2026-11-17 02:26:00', NULL),
(3, 'Vina Lestari', '240401000000', 'vina@himatika.org', 'y$x0WzAgx8Ou/yx4vt/yhLFex9VHK/LseqTAUd4feC7Y4RgB6YDeUvC', NULL, 'staff', NULL, 1, '2026-11-17 02:26:00', '2026-11-17 02:26:00', NULL),
(4, 'Nadin Nadilah', '260401071001', 'nadin@himatika.org', 'y$x0WzAgx8Ou/yx4vt/yhLFex9VHK/LseqTAUd4feC7Y4RgB6YDeUvC', NULL, 'staff', NULL, 1, '2026-11-17 02:26:00', '2026-11-17 02:26:00', NULL),
(5, 'Dian Avila', '250401010377', 'dian@himatika.org', 'y$x0WzAgx8Ou/yx4vt/yhLFex9VHK/LseqTAUd4feC7Y4RgB6YDeUvC', NULL, 'staff', NULL, 1, '2026-11-17 02:26:00', '2026-11-17 02:26:00', NULL),
(6, 'M. Rendy Amril', '240401010288', 'rendy@himatika.org', 'y$x0WzAgx8Ou/yx4vt/yhLFex9VHK/LseqTAUd4feC7Y4RgB6YDeUvC', NULL, 'kadiv', 1, 1, '2026-11-17 02:26:00', '2026-11-17 02:26:00', NULL),
(7, 'Hikmah Indriyani', '250401010395', 'hikmah@himatika.org', 'y$x0WzAgx8Ou/yx4vt/yhLFex9VHK/LseqTAUd4feC7Y4RgB6YDeUvC', NULL, 'staff', 1, 1, '2026-11-17 02:26:00', '2026-11-17 02:26:00', NULL),
(8, 'M. Naufal Adzim Ramadhan', '260401630042', 'naufal@himatika.org', 'y$x0WzAgx8Ou/yx4vt/yhLFex9VHK/LseqTAUd4feC7Y4RgB6YDeUvC', NULL, 'staff', 1, 1, '2026-11-17 02:26:00', '2026-11-17 02:26:00', NULL),
(9, 'Linawati', '260401010228', 'linawati@himatika.org', 'y$x0WzAgx8Ou/yx4vt/yhLFex9VHK/LseqTAUd4feC7Y4RgB6YDeUvC', NULL, 'staff', 1, 1, '2026-11-17 02:26:00', '2026-11-17 02:26:00', NULL),
(10, 'Intan Puji Hirdian', '250401010210', 'intan@himatika.org', 'y$x0WzAgx8Ou/yx4vt/yhLFex9VHK/LseqTAUd4feC7Y4RgB6YDeUvC', NULL, 'kadiv', 2, 1, '2026-11-17 02:26:00', '2026-11-17 02:26:00', NULL),
(11, 'Ratu Bilqis Mantika', '240401020203', 'balqis@himatika.org', 'y$x0WzAgx8Ou/yx4vt/yhLFex9VHK/LseqTAUd4feC7Y4RgB6YDeUvC', NULL, 'staff', 2, 1, '2026-11-17 02:26:00', '2026-11-17 02:26:00', NULL),
(12, 'Triyas Apritantina', '260401010208', 'triyas@himatika.org', 'y$x0WzAgx8Ou/yx4vt/yhLFex9VHK/LseqTAUd4feC7Y4RgB6YDeUvC', NULL, 'staff', 2, 1, '2026-11-17 02:26:00', '2026-11-17 02:26:00', NULL),
(13, 'Cut Evelyn Anwar', '250401010442', 'cut.evelyn@himatika.org', 'y$x0WzAgx8Ou/yx4vt/yhLFex9VHK/LseqTAUd4feC7Y4RgB6YDeUvC', NULL, 'kadiv', 3, 1, '2026-11-17 02:26:00', '2026-11-17 02:26:00', NULL),
(14, 'Arsita Rahmawati Dewi', '240401010318', 'arsita@himatika.org', 'y$x0WzAgx8Ou/yx4vt/yhLFex9VHK/LseqTAUd4feC7Y4RgB6YDeUvC', NULL, 'staff', 3, 1, '2026-11-17 02:26:00', '2026-11-17 02:26:00', NULL),
(15, 'M. Dzaky Ghaly Ramdhani', '260401010316', 'dzaky@himatika.org', 'y$x0WzAgx8Ou/yx4vt/yhLFex9VHK/LseqTAUd4feC7Y4RgB6YDeUvC', NULL, 'staff', 3, 1, '2026-11-17 02:26:00', '2026-11-17 02:26:00', NULL),
(16, 'Johan Sudanta Hla', '260401630027', 'johan@himatika.org', 'y$x0WzAgx8Ou/yx4vt/yhLFex9VHK/LseqTAUd4feC7Y4RgB6YDeUvC', NULL, 'staff', 3, 1, '2026-11-17 02:26:00', '2026-11-17 02:26:00', NULL),
(17, 'Risma Diah Wulandari', '260401010315', 'risma@himatika.org', 'y$x0WzAgx8Ou/yx4vt/yhLFex9VHK/LseqTAUd4feC7Y4RgB6YDeUvC', NULL, 'staff', 3, 1, '2026-11-17 02:26:00', '2026-11-17 02:26:00', NULL),
(18, 'Raden Dimas Taufik Rahmat', '250401010460', 'dimas@himatika.org', 'y$x0WzAgx8Ou/yx4vt/yhLFex9VHK/LseqTAUd4feC7Y4RgB6YDeUvC', NULL, 'kadiv', 4, 1, '2026-11-17 02:26:00', '2026-11-17 02:26:00', NULL),
(19, 'Fajar Mauludin', '260401010227', 'fajar@himatika.org', 'y$x0WzAgx8Ou/yx4vt/yhLFex9VHK/LseqTAUd4feC7Y4RgB6YDeUvC', NULL, 'staff', 4, 1, '2026-11-17 02:26:00', '2026-11-17 02:26:00', NULL),
(20, 'Mochammad Wahyu R', '260401630016', 'wahyu@himatika.org', 'y$x0WzAgx8Ou/yx4vt/yhLFex9VHK/LseqTAUd4feC7Y4RgB6YDeUvC', NULL, 'staff', 4, 1, '2026-11-17 02:26:00', '2026-11-17 02:26:00', NULL),
(21, 'Chandra Handika', '260401010340', 'chandra@himatika.org', 'y$x0WzAgx8Ou/yx4vt/yhLFex9VHK/LseqTAUd4feC7Y4RgB6YDeUvC', NULL, 'staff', 4, 1, '2026-11-17 02:26:00', '2026-11-17 02:26:00', NULL);

-- Add foreign key back to divisions for head_user_id
ALTER TABLE `hmt_divisions`
  ADD CONSTRAINT `fk_divisions_head_user` FOREIGN KEY (`head_user_id`) REFERENCES `hmt_users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

-- --------------------------------------------------------
-- Table structure for: hmt_categories
-- --------------------------------------------------------
DROP TABLE IF EXISTS `hmt_categories`;
CREATE TABLE `hmt_categories` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `slug` varchar(120) NOT NULL,
  `type` enum('blog','forum','event') NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `color` varchar(7) NOT NULL DEFAULT '#6B7280',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Dumping data for table: hmt_categories
-- --------------------------------------------------------
INSERT INTO `hmt_categories` (`id`, `name`, `slug`, `type`, `description`, `color`, `created_at`, `updated_at`) VALUES
(1, 'Kegiatan & Event', 'kegiatan-event', 'blog', 'Liputan kegiatan, webinar, dan perayaan di lingkungan HIMATIKA.', '#2563EB', '2026-11-16 12:15:23', '2026-11-16 12:15:23'),
(2, 'Prestasi Mahasiswa', 'prestasi-mahasiswa', 'blog', 'Kabar prestasi membanggakan dari sivitas mahasiswa.', '#EAB308', '2026-11-16 12:15:23', '2026-11-16 12:15:23'),
(3, 'Tech & Tutorial', 'tech-tutorial', 'blog', 'Artikel edukatif dan wawasan perkembangan teknologi terkini.', '#0D9488', '2026-11-16 12:15:23', '2026-11-16 12:15:23'),
(4, 'Informasi Akademik', 'informasi-akademik', 'blog', 'Pengumuman resmi kurikulum, KRS, dan jadwal ujian.', '#9333EA', '2026-11-16 12:15:23', '2026-11-16 12:15:23'),
(5, 'Birokrasi & Jurusan', 'birokrasi-jurusan', 'forum', 'Pertanyaan seputar administrasi, surat menyurat, dan fakultas.', '#DC2626', '2026-11-16 12:15:23', '2026-11-16 12:15:23'),
(6, 'Mata Kuliah & Dosen', 'mata-kuliah-dosen', 'forum', 'Diskusi seputar silabus, tugas, dan rekomendasi peminatan.', '#F97316', '2026-11-16 12:15:23', '2026-11-16 12:15:23'),
(7, 'Fasilitas & Lab', 'fasilitas-lab', 'forum', 'Aspirasi terkait sarana lab komputer, Wi-Fi, dan ruang himpunan.', '#4F46E5', '2026-11-16 12:15:23', '2026-11-16 12:15:23'),
(8, 'Kegiatan Himpunan', 'kegiatan-himpunan', 'forum', 'Saran, masukan, dan kritik konstruktif untuk program kerja pengurus.', '#059669', '2026-11-16 12:15:23', '2026-11-16 12:15:23'),
(9, 'Seminar & Talkshow', 'seminar-talkshow', 'event', 'Acara seminar wawasan teknologi dan karir industri.', '#2563EB', '2026-11-16 12:15:23', '2026-11-16 12:15:23'),
(10, 'Workshop & Pelatihan', 'workshop-pelatihan', 'event', 'Pelatihan teknis hands-on dan coding bootcamp intensif.', '#10B981', '2026-11-16 12:15:23', '2026-11-16 12:15:23'),
(11, 'Lomba & Kompetisi', 'lomba-kompetisi', 'event', 'Ajang kompetisi hackathon, data science, dan olimpiade komputasi.', '#F59E0B', '2026-11-16 12:15:23', '2026-11-16 12:15:23'),
(12, 'Sosial & Pengabdian', 'sosial-pengabdian', 'event', 'Program bakti sosial mahasiswa untuk masyarakat.', '#EC4899', '2026-11-16 12:15:23', '2026-11-16 12:15:23');

-- --------------------------------------------------------
-- Table structure for: hmt_posts
-- --------------------------------------------------------
DROP TABLE IF EXISTS `hmt_posts`;
CREATE TABLE `hmt_posts` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(300) NOT NULL,
  `body` longtext NOT NULL,
  `excerpt` text DEFAULT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `author_id` int(10) UNSIGNED NOT NULL,
  `category_id` int(10) UNSIGNED DEFAULT NULL,
  `status` enum('draft','published','archived') NOT NULL DEFAULT 'draft',
  `read_time` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `views` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `published_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `author_id` (`author_id`),
  KEY `category_id` (`category_id`),
  KEY `status` (`status`),
  CONSTRAINT `fk_posts_author` FOREIGN KEY (`author_id`) REFERENCES `hmt_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_posts_category` FOREIGN KEY (`category_id`) REFERENCES `hmt_categories` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Dumping data for table: hmt_posts
-- --------------------------------------------------------
INSERT INTO `hmt_posts` (`id`, `title`, `slug`, `body`, `excerpt`, `thumbnail`, `author_id`, `category_id`, `status`, `read_time`, `views`, `published_at`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Pelantikan Pengurus HIMATIKA Periode 2026/2027: Siap Berinovasi!', 'pelantikan-pengurus-himatika-periode-2026-2027', '<p>Selamat datang di kepengurusan baru HIMATIKA Connect! Melalui pelantikan ini, segenap pengurus berkomitmen menghadirkan transformasi digital, transparansi aspirasi, dan peningkatan kualitas akademik seluruh mahasiswa Informatika.</p><p>Mari berkolaborasi bersama membangun himpunan yang inklusif dan progresif.</p>', 'Pelantikan resmi pengurus baru HIMATIKA Connect dengan semangat sinergi dan inovasi digital.', NULL, 10, 1, 'published', 3, 120, '2026-11-16 12:15:23', '2026-11-16 12:15:23', '2026-11-16 12:15:23', NULL),
(2, 'Tips Sukses Menghadapi Sidang Skripsi & Tugas Akhir Bidang Komputasi', 'tips-sukses-menghadapi-sidang-skripsi-tugas-akhir', '<p>Menghadapi tugas akhir bukan sekadar menulis kode, tetapi bagaimana menyusun metodologi riset, pengujian algoritma, serta komunikasi hasil analisis secara sistematis di hadapan dewan penguji.</p><p>Simak panduan komprehensif mulai dari penyusunan proposal hingga gladi resik presentasi.</p>', 'Panduan esensial bagi mahasiswa tingkat akhir dalam menyusun laporan dan presentasi sidang skripsi.', NULL, 1, 3, 'published', 5, 245, '2026-11-16 12:15:23', '2026-11-16 12:15:23', '2026-11-16 12:15:23', NULL);

-- --------------------------------------------------------
-- Table structure for: hmt_post_tags
-- --------------------------------------------------------
DROP TABLE IF EXISTS `hmt_post_tags`;
CREATE TABLE `hmt_post_tags` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `slug` varchar(60) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for: hmt_post_tag_pivot
-- --------------------------------------------------------
DROP TABLE IF EXISTS `hmt_post_tag_pivot`;
CREATE TABLE `hmt_post_tag_pivot` (
  `post_id` int(10) UNSIGNED NOT NULL,
  `tag_id` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`post_id`,`tag_id`),
  KEY `tag_id` (`tag_id`),
  CONSTRAINT `fk_pivot_post` FOREIGN KEY (`post_id`) REFERENCES `hmt_posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_pivot_tag` FOREIGN KEY (`tag_id`) REFERENCES `hmt_post_tags` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for: hmt_events
-- --------------------------------------------------------
DROP TABLE IF EXISTS `hmt_events`;
CREATE TABLE `hmt_events` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(300) NOT NULL,
  `description` text DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `start_datetime` datetime NOT NULL,
  `end_datetime` datetime NOT NULL,
  `category_id` int(10) UNSIGNED DEFAULT NULL,
  `division_id` int(10) UNSIGNED DEFAULT NULL,
  `color` varchar(7) DEFAULT NULL,
  `is_public` tinyint(1) NOT NULL DEFAULT 1,
  `allow_registration` tinyint(1) NOT NULL DEFAULT 0,
  `max_participants` int(10) UNSIGNED DEFAULT NULL,
  `registration_deadline` datetime DEFAULT NULL,
  `created_by` int(10) UNSIGNED NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `category_id` (`category_id`),
  KEY `division_id` (`division_id`),
  KEY `created_by` (`created_by`),
  KEY `start_datetime` (`start_datetime`),
  CONSTRAINT `fk_events_category` FOREIGN KEY (`category_id`) REFERENCES `hmt_categories` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_events_division` FOREIGN KEY (`division_id`) REFERENCES `hmt_divisions` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_events_creator` FOREIGN KEY (`created_by`) REFERENCES `hmt_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Dumping data for table: hmt_events
-- --------------------------------------------------------
INSERT INTO `hmt_events` (`id`, `title`, `slug`, `description`, `location`, `start_datetime`, `end_datetime`, `category_id`, `division_id`, `color`, `is_public`, `allow_registration`, `max_participants`, `registration_deadline`, `created_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'HIMATIKA Hackfest 2026: AI & Web Innovation Challenge', 'himatika-hackfest-2026', 'Kompetisi hackathon 24 jam untuk mengembangkan solusi berbasis web dan kecerdasan buatan dalam memecahkan masalah civitas kampus.', 'Auditorium Lab Komputer Gedung B & Hybrid Zoom', DATE_ADD(NOW(), INTERVAL 10 DAY), DATE_ADD(NOW(), INTERVAL 11 DAY), 11, 4, '#2563EB', 1, 1, 100, DATE_ADD(NOW(), INTERVAL 8 DAY), 18, '2026-11-16 12:15:23', '2026-11-16 12:15:23', NULL),
(2, 'Workshop Cloud Computing & DevOps Essentials', 'workshop-cloud-computing-devops-essentials', 'Hands-on workshop pengenalan Docker containerization, CI/CD pipelines, dan deployment cloud modern.', 'Lab Komputer 3', DATE_ADD(NOW(), INTERVAL 18 DAY), DATE_ADD(NOW(), INTERVAL 18 DAY), 10, 4, '#10B981', 1, 1, 40, DATE_ADD(NOW(), INTERVAL 16 DAY), 18, '2026-11-16 12:15:23', '2026-11-16 12:15:23', NULL);

-- --------------------------------------------------------
-- Table structure for: hmt_galleries
-- --------------------------------------------------------
DROP TABLE IF EXISTS `hmt_galleries`;
CREATE TABLE `hmt_galleries` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `post_id` int(10) UNSIGNED DEFAULT NULL,
  `event_id` int(10) UNSIGNED DEFAULT NULL,
  `file_path` varchar(255) NOT NULL,
  `caption` varchar(255) DEFAULT NULL,
  `sort_order` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `post_id` (`post_id`),
  KEY `event_id` (`event_id`),
  CONSTRAINT `fk_galleries_post` FOREIGN KEY (`post_id`) REFERENCES `hmt_posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_galleries_event` FOREIGN KEY (`event_id`) REFERENCES `hmt_events` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for: hmt_event_registrations
-- --------------------------------------------------------
DROP TABLE IF EXISTS `hmt_event_registrations`;
CREATE TABLE `hmt_event_registrations` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `event_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `nim` varchar(20) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `status` enum('pending','confirmed','cancelled') NOT NULL DEFAULT 'pending',
  `notes` text DEFAULT NULL,
  `registered_at` datetime NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `event_id` (`event_id`),
  KEY `status` (`status`),
  CONSTRAINT `fk_registrations_event` FOREIGN KEY (`event_id`) REFERENCES `hmt_events` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for: hmt_forum_questions
-- --------------------------------------------------------
DROP TABLE IF EXISTS `hmt_forum_questions`;
CREATE TABLE `hmt_forum_questions` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `author_name` varchar(100) NOT NULL,
  `author_nim` varchar(20) DEFAULT NULL,
  `author_email` varchar(150) DEFAULT NULL,
  `is_anonymous` tinyint(1) NOT NULL DEFAULT 0,
  `category_id` int(10) UNSIGNED DEFAULT NULL,
  `status` enum('pending','answered','rejected') NOT NULL DEFAULT 'pending',
  `upvotes` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `ip_address` varchar(45) DEFAULT NULL,
  `is_pinned` tinyint(1) NOT NULL DEFAULT 0,
  `moderated_by` int(10) UNSIGNED DEFAULT NULL,
  `moderated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  KEY `status` (`status`),
  KEY `moderated_by` (`moderated_by`),
  CONSTRAINT `fk_questions_category` FOREIGN KEY (`category_id`) REFERENCES `hmt_categories` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_questions_moderator` FOREIGN KEY (`moderated_by`) REFERENCES `hmt_users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Dumping data for table: hmt_forum_questions
-- --------------------------------------------------------
INSERT INTO `hmt_forum_questions` (`id`, `title`, `body`, `author_name`, `author_nim`, `author_email`, `is_anonymous`, `category_id`, `status`, `upvotes`, `ip_address`, `is_pinned`, `moderated_by`, `moderated_at`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Bagaimana alur pengajuan surat pengantar kerja praktik (KP) tahun ini?', 'Halo pengurus, apakah permohonan surat izin KP ke prodi sudah bisa lewat sistem online penuh atau masih harus tanda tangan basah di sekretariat jurusan?', 'Ilham Pratama', '23010199', 'ilham@student.ac.id', 0, 5, 'answered', 12, '127.0.0.1', 1, 1, '2026-11-16 12:15:23', '2026-11-16 12:15:23', '2026-11-16 12:15:23', NULL),
(2, 'Usulan penambahan stopkontak dan colokan listrik di area Lab Software', 'Saat kuliah praktikum siang, banyak mahasiswa yang kehabisan colokan untuk charger laptop. Mohon bantuannya untuk diajukan ke bagian sarpras kampus.', 'Anonim Mahasiswa', NULL, NULL, 1, 7, 'pending', 8, '127.0.0.1', 0, NULL, NULL, '2026-11-16 12:15:23', '2026-11-16 12:15:23', NULL);

-- --------------------------------------------------------
-- Table structure for: hmt_forum_answers
-- --------------------------------------------------------
DROP TABLE IF EXISTS `hmt_forum_answers`;
CREATE TABLE `hmt_forum_answers` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `question_id` int(10) UNSIGNED NOT NULL,
  `body` text NOT NULL,
  `answered_by` int(10) UNSIGNED NOT NULL,
  `is_official` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `question_id` (`question_id`),
  KEY `answered_by` (`answered_by`),
  CONSTRAINT `fk_answers_question` FOREIGN KEY (`question_id`) REFERENCES `hmt_forum_questions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_answers_user` FOREIGN KEY (`answered_by`) REFERENCES `hmt_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Dumping data for table: hmt_forum_answers
-- --------------------------------------------------------
INSERT INTO `hmt_forum_answers` (`id`, `question_id`, `body`, `answered_by`, `is_official`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'Halo Ilham! Untuk periode 2026, pengajuan surat pengantar KP sudah 100% digital melalui portal fakultas menu Layanan Akademik > Persuratan KP. Silakan unduh template dokumen yang telah disediakan di website himpunan bagian arsip.', 1, 1, '2026-11-16 12:15:23', '2026-11-16 12:15:23', NULL);

-- --------------------------------------------------------
-- Table structure for: hmt_forum_upvotes
-- --------------------------------------------------------
DROP TABLE IF EXISTS `hmt_forum_upvotes`;
CREATE TABLE `hmt_forum_upvotes` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `question_id` int(10) UNSIGNED NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `session_id` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `question_id_ip_address` (`question_id`,`ip_address`),
  KEY `question_id` (`question_id`),
  CONSTRAINT `fk_upvotes_question` FOREIGN KEY (`question_id`) REFERENCES `hmt_forum_questions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for: hmt_work_programs
-- --------------------------------------------------------
DROP TABLE IF EXISTS `hmt_work_programs`;
CREATE TABLE `hmt_work_programs` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `division_id` int(10) UNSIGNED NOT NULL,
  `period_year` year(4) NOT NULL,
  `period_term` tinyint(1) NOT NULL DEFAULT 1,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `status` enum('planning','active','completed','cancelled') NOT NULL DEFAULT 'planning',
  `progress` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `created_by` int(10) UNSIGNED NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `division_id` (`division_id`),
  KEY `created_by` (`created_by`),
  KEY `status` (`status`),
  CONSTRAINT `fk_work_programs_division` FOREIGN KEY (`division_id`) REFERENCES `hmt_divisions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_work_programs_creator` FOREIGN KEY (`created_by`) REFERENCES `hmt_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- --------------------------------------------------------
-- Dumping data for table: hmt_work_programs
-- --------------------------------------------------------
INSERT INTO `hmt_work_programs` (`id`, `title`, `description`, `division_id`, `period_year`, `period_term`, `start_date`, `end_date`, `status`, `progress`, `created_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Inventarisasi & Pemeliharaan Logistik Himpunan', 'Pengadaan aset inventaris sekretariat, pemeliharaan perangkat, dan rekap sarana prasarana.', 1, YEAR(NOW()), 1, CURDATE() - INTERVAL 15 DAY, CURDATE() + INTERVAL 45 DAY, 'active', 33, 6, '2026-11-16 14:53:27', '2026-11-16 14:53:27', NULL),
(2, 'Publikasi Konten Branding & Dokumentasi Acara', 'Desain visual media sosial, pengelolaan feed Instagram, dan liputan dokumentasi kegiatan himpunan.', 2, YEAR(NOW()), 1, CURDATE() - INTERVAL 10 DAY, CURDATE() + INTERVAL 60 DAY, 'active', 33, 10, '2026-11-16 14:53:27', '2026-11-16 14:53:27', NULL),
(3, 'Kaderisasi & Pelatihan Kepemimpinan Mahasiswa (LKMM)', 'Program pembinaan soft skills, upgrading internal pengurus, dan workshop leadership.', 3, YEAR(NOW()), 1, CURDATE() - INTERVAL 5 DAY, CURDATE() + INTERVAL 50 DAY, 'active', 33, 13, '2026-11-16 14:53:27', '2026-11-16 14:53:27', NULL),
(4, 'Pengembangan Website HIMATIKA Hub', 'Membangun portal informasi terintegrasi dan dashboard monitoring proker untuk kepengurusan HIMATIKA.', 4, YEAR(NOW()), 1, CURDATE() - INTERVAL 10 DAY, CURDATE() + INTERVAL 30 DAY, 'active', 33, 18, '2026-11-16 14:53:27', '2026-11-16 14:53:27', NULL);

-- --------------------------------------------------------
-- Table structure for: hmt_tasks
-- --------------------------------------------------------
DROP TABLE IF EXISTS `hmt_tasks`;
CREATE TABLE `hmt_tasks` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `work_program_id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('todo','in_progress','review','done') NOT NULL DEFAULT 'todo',
  `priority` enum('low','medium','high','critical') NOT NULL DEFAULT 'medium',
  `assigned_to` int(10) UNSIGNED DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `sort_order` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_by` int(10) UNSIGNED NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `work_program_id` (`work_program_id`),
  KEY `assigned_to` (`assigned_to`),
  KEY `created_by` (`created_by`),
  KEY `status` (`status`),
  CONSTRAINT `fk_tasks_program` FOREIGN KEY (`work_program_id`) REFERENCES `hmt_work_programs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_tasks_assignee` FOREIGN KEY (`assigned_to`) REFERENCES `hmt_users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_tasks_creator` FOREIGN KEY (`created_by`) REFERENCES `hmt_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- --------------------------------------------------------
-- Dumping data for table: hmt_tasks
-- --------------------------------------------------------
INSERT INTO `hmt_tasks` (`id`, `work_program_id`, `title`, `description`, `status`, `priority`, `assigned_to`, `due_date`, `sort_order`, `created_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
-- Proker 1: Divisi Stupport
(1, 1, 'Pembuatan SOP Peminjaman Barang & Aset Himatika', 'Menyusun alur peminjaman logistik sekretariat untuk kegiatan kemahasiswaan.', 'done', 'medium', 7, CURDATE() - INTERVAL 5 DAY, 1, 6, '2026-11-16 14:53:27', '2026-11-16 14:53:27', NULL),
(2, 1, 'Audit & Stock Opname Inventaris Ruang Himpunan', 'Pengecekan fisik proyektor, kabel roll, printer, dan sound system sekretariat.', 'in_progress', 'high', 6, CURDATE() + INTERVAL 3 DAY, 2, 6, '2026-11-16 14:53:27', '2026-11-16 14:53:27', NULL),
(3, 1, 'Pengadaan ATK & Kelengkapan Administrasi Triwulan', 'Pembelian kertas, tinta stempel, dan map arsip persuratan himpunan.', 'todo', 'medium', 8, CURDATE() + INTERVAL 10 DAY, 3, 6, '2026-11-16 14:53:27', '2026-11-16 14:53:27', NULL),

-- Proker 2: Divisi PDD
(4, 2, 'Perancangan Template Feed & Story Instagram Resmi', 'Menyusun panduan color palette, typography, dan asset grafis HIMATIKA.', 'done', 'high', 10, CURDATE() - INTERVAL 2 DAY, 1, 10, '2026-11-16 14:53:27', '2026-11-16 14:53:27', NULL),
(5, 2, 'Liputan Dokumentasi & Rilis Foto Pelantikan Pengurus', 'Editing foto kegiatan pelantikan untuk publikasi website dan sorotan IG.', 'in_progress', 'high', 11, CURDATE() + INTERVAL 2 DAY, 2, 10, '2026-11-16 14:53:27', '2026-11-16 14:53:27', NULL),
(6, 2, 'Penyusunan Kalender Konten Edukasi Teknologi Mingguan', 'Riset topik artikel info-grafis tech tips untuk mahasiswa.', 'todo', 'medium', 12, CURDATE() + INTERVAL 8 DAY, 3, 10, '2026-11-16 14:53:27', '2026-11-16 14:53:27', NULL),

-- Proker 3: Divisi PSDM
(7, 3, 'Evaluasi Kuesioner Need Assessment Anggota Baru', 'Menganalisis minat bakat mahasiswa angkatan baru untuk pemetaan divisi.', 'done', 'medium', 14, CURDATE() - INTERVAL 1 DAY, 1, 13, '2026-11-16 14:53:27', '2026-11-16 14:53:27', NULL),
(8, 3, 'Penyusunan Kurikulum & Silabus Materi LKMM Tingkat Dasar', 'Menyusun materi manajemen waktu, etika organisasi, dan problem solving.', 'in_progress', 'critical', 13, CURDATE() + INTERVAL 4 DAY, 2, 13, '2026-11-16 14:53:27', '2026-11-16 14:53:27', NULL),
(9, 3, 'Pendataan Peserta & Koordinasi Pemateri Alumni', 'Menghubungi pembicara seminar leadership dan menyiapkan surat undangan.', 'todo', 'high', 15, CURDATE() + INTERVAL 9 DAY, 3, 13, '2026-11-16 14:53:27', '2026-11-16 14:53:27', NULL),

-- Proker 4: Divisi Intellogy
(10, 4, 'Perancangan Skema Database (ERD) & Migrasi', 'Menyusun skema 18 tabel dan menyiapkan migrasi CodeIgniter 4.', 'done', 'high', 20, CURDATE() + INTERVAL 1 DAY, 1, 18, '2026-11-16 14:53:27', '2026-11-16 14:53:27', NULL),
(11, 4, 'Pembuatan Halaman Landing Page Publik & Formulir Q&A', 'Implementasi UI modern, responsif, dan interaktif untuk umum.', 'in_progress', 'high', 19, CURDATE() + INTERVAL 5 DAY, 2, 18, '2026-11-16 14:53:27', '2026-11-16 14:53:27', NULL),
(12, 4, 'Integrasi Notifikasi Webhook Discord/WhatsApp', 'Mengirim notifikasi pertanyaan aspirasi baru ke channel koordinasi himpunan.', 'todo', 'medium', 21, CURDATE() + INTERVAL 12 DAY, 3, 18, '2026-11-16 14:53:27', '2026-11-16 14:53:27', NULL);

-- --------------------------------------------------------
-- Table structure for: hmt_task_progress_logs
-- --------------------------------------------------------
DROP TABLE IF EXISTS `hmt_task_progress_logs`;
CREATE TABLE `hmt_task_progress_logs` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `task_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `body` text NOT NULL,
  `status_snapshot` enum('todo','in_progress','review','done') NOT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `logged_date` date NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `task_id` (`task_id`),
  KEY `user_id` (`user_id`),
  KEY `logged_date` (`logged_date`),
  CONSTRAINT `fk_logs_task` FOREIGN KEY (`task_id`) REFERENCES `hmt_tasks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_logs_user` FOREIGN KEY (`user_id`) REFERENCES `hmt_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for: hmt_schedules
-- --------------------------------------------------------
DROP TABLE IF EXISTS `hmt_schedules`;
CREATE TABLE `hmt_schedules` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `division_id` int(10) UNSIGNED DEFAULT NULL,
  `start_datetime` datetime NOT NULL,
  `end_datetime` datetime NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `color` varchar(7) DEFAULT NULL,
  `is_recurring` tinyint(1) NOT NULL DEFAULT 0,
  `recurrence_rule` varchar(255) DEFAULT NULL,
  `created_by` int(10) UNSIGNED NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `division_id` (`division_id`),
  KEY `created_by` (`created_by`),
  KEY `start_datetime` (`start_datetime`),
  CONSTRAINT `fk_schedules_division` FOREIGN KEY (`division_id`) REFERENCES `hmt_divisions` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_schedules_creator` FOREIGN KEY (`created_by`) REFERENCES `hmt_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Dumping data for table: hmt_schedules
-- --------------------------------------------------------
INSERT INTO `hmt_schedules` (`id`, `title`, `description`, `division_id`, `start_datetime`, `end_datetime`, `location`, `color`, `is_recurring`, `created_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Rapat Pleno Bulanan Evaluasi Kinerja Tengah Periode', 'Membahas evaluasi proker setiap divisi, kendala operasional sekretariat, dan penyelarasan timeline menuju perayaan Dies Natalis.', NULL, DATE_ADD(NOW(), INTERVAL 2 DAY), DATE_ADD(NOW(), INTERVAL 2 DAY) + INTERVAL 2 HOUR, 'Auditorium Gd. B & Hybrid Zoom', '#1D4ED8', 1, 1, '2026-11-16 12:15:23', '2026-11-16 12:15:23', NULL),
(2, 'Rapat Koordinasi Teknis Proker HIMATIKA Hub & Hackfest', 'Sinkronisasi progress deployment server dan modul form pendaftaran peserta lomba hackathon.', 4, DATE_ADD(NOW(), INTERVAL 4 DAY), DATE_ADD(NOW(), INTERVAL 4 DAY) + INTERVAL 2 HOUR, 'Lab Komputer 2', '#0891B2', 0, 18, '2026-11-16 12:15:23', '2026-11-16 12:15:23', NULL),
(3, 'Batas Waktu (Deadline) Pengumpulan Draft LPJ Triwulan 1', 'Seluruh bendahara divisi wajib mengumpulkan rekapitulasi nota dan laporan pertanggungjawaban fisik ke Bendahara Umum.', 1, DATE_ADD(NOW(), INTERVAL 7 DAY), DATE_ADD(NOW(), INTERVAL 7 DAY), 'Google Drive Sekretariat BPH', '#DC2626', 0, 1, '2026-11-16 12:15:23', '2026-11-16 12:15:23', NULL);

-- --------------------------------------------------------
-- Table structure for: hmt_schedule_attendees
-- --------------------------------------------------------
DROP TABLE IF EXISTS `hmt_schedule_attendees`;
CREATE TABLE `hmt_schedule_attendees` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `schedule_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `rsvp` enum('pending','accepted','declined') NOT NULL DEFAULT 'pending',
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `schedule_user` (`schedule_id`,`user_id`),
  KEY `schedule_id` (`schedule_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `fk_attendees_schedule` FOREIGN KEY (`schedule_id`) REFERENCES `hmt_schedules` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_attendees_user` FOREIGN KEY (`user_id`) REFERENCES `hmt_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Dumping data for table: hmt_schedule_attendees
-- --------------------------------------------------------
INSERT INTO `hmt_schedule_attendees` (`schedule_id`, `user_id`, `rsvp`, `created_at`) VALUES
(1, 1, 'accepted', '2026-11-16 12:15:23'),
(1, 2, 'accepted', '2026-11-16 12:15:23'),
(1, 3, 'pending', '2026-11-16 12:15:23'),
(1, 4, 'pending', '2026-11-16 12:15:23'),
(1, 5, 'pending', '2026-11-16 12:15:23'),
(1, 6, 'pending', '2026-11-16 12:15:23'),
(1, 10, 'pending', '2026-11-16 12:15:23'),
(1, 13, 'pending', '2026-11-16 12:15:23'),
(1, 18, 'pending', '2026-11-16 12:15:23'),
(2, 18, 'accepted', '2026-11-16 12:15:23'),
(2, 19, 'pending', '2026-11-16 12:15:23'),
(2, 20, 'pending', '2026-11-16 12:15:23'),
(2, 21, 'pending', '2026-11-16 12:15:23');

-- --------------------------------------------------------
-- Table structure for: hmt_notifications
-- --------------------------------------------------------
DROP TABLE IF EXISTS `hmt_notifications`;
CREATE TABLE `hmt_notifications` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(10) UNSIGNED NOT NULL,
  `type` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `body` text DEFAULT NULL,
  `url` varchar(500) DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `notifiable_type` varchar(100) DEFAULT NULL,
  `notifiable_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `is_read` (`is_read`),
  KEY `notifiable` (`notifiable_type`,`notifiable_id`),
  CONSTRAINT `fk_notifications_user` FOREIGN KEY (`user_id`) REFERENCES `hmt_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for: hmt_certificates
-- --------------------------------------------------------
DROP TABLE IF EXISTS `hmt_certificates`;
CREATE TABLE `hmt_certificates` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `certificate_no` varchar(64) NOT NULL,
  `verification_code` varchar(32) NOT NULL,
  `event_id` int(11) UNSIGNED DEFAULT NULL,
  `event_name` varchar(255) NOT NULL,
  `participant_name` varchar(255) NOT NULL,
  `identifier` varchar(50) DEFAULT NULL COMMENT 'NIM / NIP / No. Identitas',
  `email` varchar(150) NOT NULL,
  `institution` varchar(150) DEFAULT NULL,
  `role` varchar(50) NOT NULL DEFAULT 'Peserta',
  `rating` tinyint(1) DEFAULT NULL,
  `feedback` text DEFAULT NULL,
  `suggestions` text DEFAULT NULL,
  `download_count` int(11) NOT NULL DEFAULT 0,
  `last_downloaded_at` datetime DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `certificate_no` (`certificate_no`),
  UNIQUE KEY `verification_code` (`verification_code`),
  KEY `event_id` (`event_id`),
  KEY `email` (`email`),
  CONSTRAINT `fk_certificates_event` FOREIGN KEY (`event_id`) REFERENCES `hmt_events` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Dumping sample data for table: hmt_certificates
-- --------------------------------------------------------
INSERT INTO `hmt_certificates` (`id`, `certificate_no`, `verification_code`, `event_id`, `event_name`, `participant_name`, `identifier`, `email`, `institution`, `role`, `rating`, `feedback`, `suggestions`, `download_count`, `last_downloaded_at`, `ip_address`, `user_agent`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'CERT-HMT-2026-0001', 'HMT7F2A9B', 1, 'HIMATIKA Hackfest 2026: AI & Web Innovation Challenge', 'Budi Santoso', '22010099', 'budi.santoso@gmail.com', 'Universitas Teknologi', 'Peserta', 5, 'Acara sangat berbobot dan materi praktis!', 'Sediakan lebih banyak snack dan durasi sesi Q&A.', 1, '2026-11-16 12:15:23', '127.0.0.1', 'Mozilla/5.0 Demo Browser', '2026-11-16 12:15:23', '2026-11-16 12:15:23', NULL);

SET FOREIGN_KEY_CHECKS = 1;
COMMIT;