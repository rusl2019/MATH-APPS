-- Adminer 5.3.0 MariaDB 11.8.2-MariaDB-ubu2404 dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `pkl_semesters`;
CREATE TABLE `pkl_semesters` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `is_active` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `pkl_semesters` (`name`, `is_active`) VALUES
('Ganjil 2025/2026', 1),
('Genap 2025/2026', 0);

DROP TABLE IF EXISTS `pkl_applications`;
CREATE TABLE `pkl_applications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` varchar(20) NOT NULL,
  `semester_id` int(11) NOT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `lecturer_id` varchar(20) DEFAULT NULL,
  `place_id` int(11) DEFAULT NULL,
  `addressed_to` varchar(255) DEFAULT NULL,
  `equivalent_activity` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `status` enum('draft','submitted','approved_dosen','approved_kps','approved_kadep','recommendation_uploaded','rejected','ongoing','finished') DEFAULT 'draft',
  `rejection_reason` text DEFAULT NULL,
  `reapplication_count` tinyint(1) DEFAULT 0,
  `submission_date` date DEFAULT NULL,
  `activity_period_start` date DEFAULT NULL,
  `activity_period_end` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`),
  KEY `lecturer_id` (`lecturer_id`),
  KEY `place_id` (`place_id`),
  KEY `semester_id` (`semester_id`),
  CONSTRAINT `fk_applications_lecturer` FOREIGN KEY (`lecturer_id`) REFERENCES `apps_lecturers` (`id`),
  CONSTRAINT `fk_applications_place` FOREIGN KEY (`place_id`) REFERENCES `pkl_places` (`id`),
  CONSTRAINT `fk_applications_student` FOREIGN KEY (`student_id`) REFERENCES `apps_students` (`id`),
  CONSTRAINT `fk_applications_semester` FOREIGN KEY (`semester_id`) REFERENCES `pkl_semesters` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


DROP TABLE IF EXISTS `pkl_assessments`;
CREATE TABLE `pkl_assessments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `application_id` int(11) NOT NULL,
  `assessor_type` enum('lapangan','dosen') NOT NULL,
  `form_type` varchar(50) NOT NULL,
  `score` decimal(5,2) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `application_id` (`application_id`),
  CONSTRAINT `fk_assessments_application` FOREIGN KEY (`application_id`) REFERENCES `pkl_applications` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


DROP TABLE IF EXISTS `pkl_documents`;
CREATE TABLE `pkl_documents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `application_id` int(11) NOT NULL,
  `doc_type` enum('portofolio','proposal','lembar_konsultasi','recommendation_letter','acceptance_letter','rejection_letter','logbook','sertifikat','laporan','seminar','berita_acara') NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `status` enum('draft','submitted','approved','rejected') DEFAULT 'draft',
  `uploaded_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `application_id` (`application_id`),
  CONSTRAINT `fk_documents_application` FOREIGN KEY (`application_id`) REFERENCES `pkl_applications` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


DROP TABLE IF EXISTS `pkl_logs`;
CREATE TABLE `pkl_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `application_id` int(11) NOT NULL,
  `log_date` date NOT NULL,
  `activity` text NOT NULL,
  `approved_by` varchar(20) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `application_id` (`application_id`),
  CONSTRAINT `fk_logs_application` FOREIGN KEY (`application_id`) REFERENCES `pkl_applications` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


DROP TABLE IF EXISTS `pkl_places`;
CREATE TABLE `pkl_places` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  `address` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `pkl_places` (`id`, `name`, `address`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1,	'PT Jasa Raharja Kota Malang',	'Jl. Dr. Cipto No. 8, Kec. Klojen Kota Malang, Jawa Timur',	'2025-08-09 20:02:17',	NULL,	NULL),
(2,	'Kantor Kelurahan Tiru Lor',	'Bolowono, Desa Tiru Lor, Kec. Gurah Kediri',	'2025-08-09 20:02:17',	NULL,	NULL),
(3,	'PT. Wasa Mitra Engineering',	'Jl. Raya Raya Roomo No. 388 Manyar, Gresik Jawa Timur 61151',	'2025-08-09 20:02:17',	NULL,	NULL),
(4,	'Dinas Pertanian Kota Serang',	'Jl. Jend. Sudirman No.15, Penancangan, Kec. Serang, Kota Serang, Banten 42124',	'2025-08-09 20:02:17',	NULL,	NULL),
(5,	'PT Ajiwana Tangguh Nusantara',	'Jl. Pantura No. 53, RT.004/RW.005, Dusun IV, Kaliori, Kec.Kalibagor, Kabupaten Banyumas, Jawa tengah',	'2025-08-09 20:02:17',	NULL,	NULL),
(6,	'Sekolah Dasar Negeri Sukorejo 1',	'Jl. Manggar No. 57, Sukorejo Blitar Jawa Timur',	'2025-08-09 20:02:17',	NULL,	NULL),
(7,	'DPRD Kota Samarinda',	'Jl. Basuki Rahmat, Pelabuhan, Samarinda Kalimantan Timur',	'2025-08-09 20:02:17',	NULL,	NULL),
(8,	'Badan Pusat Statistik (BPS) Kabupaten Malang',	'Jl. Jatirejoyoso No. 1A Dawukan, Jatirejoyoso Kepanjen',	'2025-08-09 20:02:17',	NULL,	NULL),
(9,	'Kantor Pelayanan Kekayaan Negara',	'Jl. S. Supriadi No. 157, Bandungrejosari, Kec. Sukun, Kota Malang, Jawa Timur',	'2025-08-09 20:02:17',	NULL,	NULL),
(10,	'Sekretariat Badan Keuangan dan Aset Daerah Kabupaten Malang',	'Jalan Agus Salim No. 7 Kidul Dalem Klojen Malang',	'2025-08-09 20:02:17',	NULL,	NULL),
(11,	'PT Ambhara Duta Shanti',	'Menara Kartika Chandra, It 1/014 Jl. Jendral Gatot Subroto Kav. 18-20, Karet Semanggi, Setia Budi, RT.8/RW.2, Karet Semanggi, Kota Jakarta Selatan, Daerah Khusus Ibukota Jakarta',	'2025-08-09 20:02:17',	NULL,	NULL),
(12,	'BPS Kota Jakarta Utara',	'Jl. Berdikari No. 1. Kelurahan Rawa Badak Utara, kecamatan Koja Kota Administrasi Utara',	'2025-08-09 20:02:17',	NULL,	NULL),
(13,	'PT Dua Empat Tujuh',	'Segitiga Emas Business Park Jalan Profesor Doktor Satrio No Kav.6 Unit 4 &5 Kuningan, Setiabudi Jakarta Selatan',	'2025-08-09 20:02:17',	NULL,	NULL),
(14,	'Badan Pusat Statistik RI',	'Jl. Dr. Sutomo No 6-8, Ps. Baru, Kec. Sawah Besar, Jakarta Pusat, DKI Jakarta',	'2025-08-09 20:02:17',	NULL,	NULL),
(15,	'Dinas Komunikasi dan Informatika Kota Malang',	'Jl. Mayjend Sungkono, Arjowinangun Kedungkandang Kota Malang',	'2025-08-09 20:02:17',	NULL,	NULL),
(16,	'Bank Jatim Kantor Cabang Kota Malang',	'Jl. Jaksa Agung Suprapto 26-28 Kec. Klojen Kota Malang',	'2025-08-09 20:02:17',	NULL,	NULL),
(17,	'PT. Pegadaian (Persero) Kota Malang',	'Jl. Ade Irma Suryani No.2 Kauman Klojen Malang',	'2025-08-09 20:02:17',	NULL,	NULL),
(18,	'BPS Kabupaten Mesuji',	'Jl. Raden Intan No. 02, Desa Mukti Karya, Kec. Panca Jaya Mesuji Lampung',	'2025-08-09 20:02:17',	NULL,	NULL),
(19,	'BPJS Kesehatan Kantor Cabang Tulungagung',	'Jl. Yos. Sudarso No.90, Karangwaru, Kec. Tulungagung Jawa Timur',	'2025-08-09 20:02:17',	NULL,	NULL),
(20,	'Bank BRI KC Tarakan',	'Jl. Yos Sudarso No. 86 Selumit Pantai Tarakan tengah Kalimantan Utara',	'2025-08-09 20:02:17',	NULL,	NULL),
(21,	'Badan Pusat Statistik Kabupaten Pasuruan',	'Jl. Sultan Agung No. 42, Purutrejo kec. Purworejo Pasuruan',	'2025-08-09 20:02:17',	NULL,	NULL),
(22,	'Bank Rakyat Indonesia Cabang Kawi Malang',	'Jl. Kawi No 20-22, Kauman kec. Klojen, Kota Malang',	'2025-08-09 20:02:17',	NULL,	NULL),
(23,	'BPJS Ketenagakerjaan Pasuruan',	'Jl. Ir. H. Juanda No. 77, Bugul Kidul Kota Pasuruan',	'2025-08-09 20:02:17',	NULL,	NULL),
(24,	'Badan Pengelolaan Pendapatan Daerah (BAPPENDA) Kabupaten Bogor',	'Jl. Tegar Beriman No.1, Pekansari, Kec.Cibinong Kab. Bogor Jawa Barat',	'2025-08-09 20:02:17',	NULL,	NULL),
(25,	'Badan Pusat Statistik Kabupaten Malang',	'Jl. Jatirejoyoso No.1A Dawukan Jatirejoyoso Kepanjen Malang',	'2025-08-09 20:02:17',	NULL,	NULL),
(26,	'Kantor Badan Pengelolaan Keuangan dan Aset Daerah (BPKAD) Kabupaten Nganjuk',	'Jl. Basuki Rahmat No.01 Mangundikaran Nganjuk',	'2025-08-09 20:02:17',	NULL,	NULL),
(27,	'BPS Kabupaten Banyuwangi',	'Jl. KH. Agus Salim No. 87 Mojopanggung Banyuwangi',	'2025-08-09 20:02:17',	NULL,	NULL),
(28,	'BSI KCP Soekarno Hatta Malang',	'Jl. Soekarno Hatta, Ruko Taman Niaga B15, B16, B17 dan S12 Kel. Jatimulyo Lowokwaru Malang',	'2025-08-09 20:02:17',	NULL,	NULL),
(29,	'PT. Anara Trisakti Medika',	'Jl. Pondok Raya Duren Sawit, Jakarta Timur',	'2025-08-09 20:02:17',	NULL,	NULL),
(30,	'Badan Pusat Statistik (BPS) Kota Jakarta Timur',	'Jl. Cipinang Baru Raya No. 214 Cipinang, Pulogadung Jakarta Timur',	'2025-08-09 20:02:17',	NULL,	NULL),
(31,	'OJK Purwokerto',	'Jl. Jend. Gatot Subroto No. 46, Purwokerto Sokanegara Banyumas',	'2025-08-09 20:02:17',	NULL,	NULL),
(32,	'Badan Pusat Statistik (BPS) Kota Malang',	'Jl. Janti Barat No. 47, Bandungrejosari, Kec Sukun, Kota Malang',	'2025-08-09 20:02:17',	NULL,	NULL),
(33,	'Dinas Komunikasi da Informatika Kota Malang',	'Jl. Mayjend Sungkono, Arjowinangun Kedungkandang Kota Malang',	'2025-08-09 20:02:17',	NULL,	NULL),
(34,	'PT. Asuransi Tugu Pratama Indonesia',	'Jl. H.R Rasuna Sa’id Kav. C8-9 Jakarta Selatan',	'2025-08-09 20:02:17',	NULL,	NULL),
(35,	'BPS Jakarta Timur',	'Jl. Cipinang Baru Raya Bo. 14 RT.02 RW.18 Cipinang Kec. Pulogadung Jakarta Timur',	'2025-08-09 20:02:17',	NULL,	NULL),
(36,	'Badan Pusat Statistik Kabupaten Tuban',	'Jl. Raya Manunggal No. 8 Sukolilo, Panyuran Tuban',	'2025-08-09 20:02:17',	NULL,	NULL),
(37,	'PT Toyota Astra Financial Services',	'Astra Biz Center Jl BSD Raya Utama No 22, Tangerang, Banten 15331,',	'2025-08-09 20:02:17',	NULL,	NULL),
(38,	'BPS Provinsi Bali',	'Jl. Raya Puputan No. 1 Renon, Denpasar Selatan, Kota Denpasar, Bali',	'2025-08-09 20:02:17',	NULL,	NULL),
(39,	'Badan Pusat Statistik Provinsi Jawa Barat',	'Jl. PHH. Mustofa No. 34 Bandung, Jawa Barat',	'2025-08-09 20:02:17',	NULL,	NULL),
(40,	'Kantor Pengawasan dan Pelayanan Bea dan Cukai TMP Belawan Medan',	'Jl. Anggada II No. 1, Kota Belawan, Medan',	'2025-08-09 20:02:17',	NULL,	NULL),
(41,	'PT. Jasa Raharja Cabang Bali',	'Jl. Hayam Wuruk No.202, Panjer, Denpasar Selatan, Kota Denpasar, Bal',	'2025-08-09 20:02:17',	NULL,	NULL),
(42,	'PT. Jasa Raharja Malang',	'Jl. Dr. Cipto No. 8, Kec. Klojen, Kota Malang, Jawa Timur',	'2025-08-09 20:02:17',	NULL,	NULL),
(43,	'PT. Taspen Kota Malang',	'Jl. Raden Intan, Kec. Blimbing, Kota Malang, Jawa Timur',	'2025-08-09 20:02:17',	NULL,	NULL),
(44,	'BPJS Ketenagakerjaan Kota Malang',	'Jl. Dr. Sutomo No. 1, RW.01, Klojen Kota Malang',	'2025-08-09 20:02:17',	NULL,	NULL),
(45,	'PT. Jasa Raharja Cabang Utama Jawa Timur',	'Jalan Diponegoro No.98, RT.002/RW.15, DR. Soetomo, Kec. Tegalsari, Kota Surabaya, Jawa Timur',	'2025-08-09 20:02:17',	NULL,	NULL),
(46,	'Bank Syariah Indonesia KCP Kalianda Lampung',	'Jl. Raden Intan No. 255 E-F-G Kalianda Lampung Selatan',	'2025-08-09 20:02:17',	NULL,	NULL),
(47,	'BPJS Kesehatan Cabang Denpasar',	'Jl. Panjaitan No. 6, Panjer Denpasar Selatan, Kota Denpasar Bali',	'2025-08-09 20:02:17',	NULL,	NULL),
(48,	'MPM Insurance',	'Jl. Panjang No. 5 RT.11/RW.10 Kebonjeruk Jakarta Barat',	'2025-08-09 20:02:17',	NULL,	NULL),
(49,	'BPJS Ketenagakerjaan Kabupaten Gresik',	'Jl. DR. Wahidin Sudiro Husodo No. 121A, Kebomas, Ngipik, Kec. Gresik, Kabupaten Gresik Jawa Timur',	'2025-08-09 20:02:17',	NULL,	NULL),
(50,	'PT. BPR Rakyat Kawan',	'Jl. Raya Jetis No. 105, Kec.Dau , Malang',	'2025-08-09 20:02:17',	NULL,	NULL),
(51,	'Biro Kepegawaian DPR RI',	'Jl. Gatot Subroto No. 1 RT.01/RW03 Senayan, Kecamatan Tanah Abang Kota Jakarta Pusat, DKI',	'2025-08-09 20:02:17',	NULL,	NULL),
(52,	'KPU Kota Malang',	'Jl. Bantaran No. 6 Malang',	'2025-08-09 20:02:17',	NULL,	NULL),
(53,	'Phintraco Sekuritas',	'Jl. Dr. Ide Anak Agung Gde Agung Kav. E3.2 No.1 Mega Kuningan Jakarta',	'2025-08-09 20:02:17',	NULL,	NULL),
(54,	'Badan Pusat Statistik Republik Indonesia',	'Jalan Dr. Sutomo No. 6-8, Ps. Baru, Kecamatan Sawah Besar',	'2025-08-09 20:02:17',	NULL,	NULL),
(55,	'Badan Pusat Statistik (BPS) Kabupaten Kutai Timur',	'Pusat Perkantoran Sekretariat Daerah Kabupaten Kutai Timur, Bukit Pelangi Sangatta, Kabupaten Kutai Timur',	'2025-08-09 20:02:17',	NULL,	NULL),
(56,	'Jasa Raharja Pekanbaru',	'Jl. Jend. Sudirman No. 285, Pekanbaru',	'2025-08-09 20:02:17',	NULL,	NULL),
(57,	'Badan Pusat Statistik Kabupaten Banyuwangi',	'Jl. K.H Agus Salim No. 87 Banyuwangi, Jawa Timur',	'2025-08-09 20:02:17',	NULL,	NULL),
(58,	'BPJS Ketenagakerjaan Cabang Kota Surakarta',	'di Jl. Bhayangkara Nomor 30, Panularan Laweyan',	'2025-08-09 20:02:17',	NULL,	NULL),
(59,	'Bank BTN KCP Kota Malang',	'Jl. Ade Irma Suryani No. 2, Kauman, Kota Malang',	'2025-08-09 20:02:17',	NULL,	NULL),
(60,	'Bank BNI KCP Kota Malang',	'Jalan Veteran No. 16, Ketawanggede, Kecamatan Lowokwaru, Kota Malang',	'2025-08-09 20:02:17',	NULL,	NULL);


DROP TABLE IF EXISTS `pkl_reports`;
CREATE TABLE `pkl_reports` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `application_id` int(11) NOT NULL,
  `version` int(11) NOT NULL DEFAULT 1,
  `file_path` varchar(255) NOT NULL,
  `status` enum('draft','submitted','approved_dosen','revisi','final') DEFAULT 'draft',
  `submission_date` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `application_id` (`application_id`),
  CONSTRAINT `fk_reports_application` FOREIGN KEY (`application_id`) REFERENCES `pkl_applications` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


DROP TABLE IF EXISTS `pkl_seminars`;
CREATE TABLE `pkl_seminars` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `application_id` int(11) NOT NULL,
  `schedule_date` date NOT NULL,
  `place` varchar(255) DEFAULT NULL,
  `status` enum('planned','done','revised') DEFAULT 'planned',
  `berita_acara_file` varchar(255) DEFAULT NULL,
  `nilai_file` varchar(255) DEFAULT NULL,
  `revisi_file` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `application_id` (`application_id`),
  CONSTRAINT `fk_seminars_application` FOREIGN KEY (`application_id`) REFERENCES `pkl_applications` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


DROP TABLE IF EXISTS `pkl_workflow`;
CREATE TABLE `pkl_workflow` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `application_id` int(11) NOT NULL,
  `step_name` varchar(100) NOT NULL,
  `actor_id` varchar(20) DEFAULT NULL,
  `role` varchar(50) DEFAULT NULL,
  `status` enum('pending','approved','rejected','done') DEFAULT 'pending',
  `action_date` timestamp NULL DEFAULT current_timestamp(),
  `remarks` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `application_id` (`application_id`),
  CONSTRAINT `fk_workflow_application` FOREIGN KEY (`application_id`) REFERENCES `pkl_applications` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;