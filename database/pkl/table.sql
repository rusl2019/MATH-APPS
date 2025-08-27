-- Adminer 5.3.0 MariaDB 11.6.2-MariaDB-ubu2404 dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `pkl_institutions`;
CREATE TABLE `pkl_institutions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  `address` text NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `is_deleted` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `pkl_institutions` (`id`, `name`, `address`, `status`, `is_deleted`, `created_at`, `updated_at`) VALUES
(1,	'PT Jasa Raharja Kota Malang',	'Jl. Dr. Cipto No. 8, Kec. Klojen Kota Malang, Jawa Timur',	'active',	0,	'2025-08-09 20:02:17',	NULL),
(2,	'Kantor Kelurahan Tiru Lor',	'Bolowono, Desa Tiru Lor, Kec. Gurah Kediri',	'active',	0,	'2025-08-09 20:02:17',	NULL),
(3,	'PT. Wasa Mitra Engineering',	'Jl. Raya Raya Roomo No. 388 Manyar, Gresik Jawa Timur 61151',	'active',	0,	'2025-08-09 20:02:17',	NULL),
(4,	'Dinas Pertanian Kota Serang',	'Jl. Jend. Sudirman No.15, Penancangan, Kec. Serang, Kota Serang, Banten 42124',	'active',	0,	'2025-08-09 20:02:17',	NULL),
(5,	'PT Ajiwana Tangguh Nusantara',	'Jl. Pantura No. 53, RT.004/RW.005, Dusun IV, Kaliori, Kec.Kalibagor, Kabupaten Banyumas, Jawa tengah',	'active',	0,	'2025-08-09 20:02:17',	NULL),
(6,	'Sekolah Dasar Negeri Sukorejo 1',	'Jl. Manggar No. 57, Sukorejo Blitar Jawa Timur',	'active',	0,	'2025-08-09 20:02:17',	NULL),
(7,	'DPRD Kota Samarinda',	'Jl. Basuki Rahmat, Pelabuhan, Samarinda Kalimantan Timur',	'active',	0,	'2025-08-09 20:02:17',	NULL),
(8,	'Badan Pusat Statistik (BPS) Kabupaten Malang',	'Jl. Jatirejoyoso No. 1A Dawukan, Jatirejoyoso Kepanjen',	'active',	0,	'2025-08-09 20:02:17',	NULL),
(9,	'Kantor Pelayanan Kekayaan Negara',	'Jl. S. Supriadi No. 157, Bandungrejosari, Kec. Sukun, Kota Malang, Jawa Timur',	'active',	0,	'2025-08-09 20:02:17',	NULL),
(10,	'Sekretariat Badan Keuangan dan Aset Daerah Kabupaten Malang',	'Jalan Agus Salim No. 7 Kidul Dalem Klojen Malang',	'active',	0,	'2025-08-09 20:02:17',	NULL),
(11,	'PT Ambhara Duta Shanti',	'Menara Kartika Chandra, It 1/014 Jl. Jendral Gatot Subroto Kav. 18-20, Karet Semanggi, Setia Budi, RT.8/RW.2, Karet Semanggi, Kota Jakarta Selatan, Daerah Khusus Ibukota Jakarta',	'active',	0,	'2025-08-09 20:02:17',	NULL),
(12,	'BPS Kota Jakarta Utara',	'Jl. Berdikari No. 1. Kelurahan Rawa Badak Utara, kecamatan Koja Kota Administrasi Utara',	'active',	0,	'2025-08-09 20:02:17',	NULL),
(13,	'PT Dua Empat Tujuh',	'Segitiga Emas Business Park Jalan Profesor Doktor Satrio No Kav.6 Unit 4 &5 Kuningan, Setiabudi Jakarta Selatan',	'active',	0,	'2025-08-09 20:02:17',	NULL),
(14,	'Badan Pusat Statistik RI',	'Jl. Dr. Sutomo No 6-8, Ps. Baru, Kec. Sawah Besar, Jakarta Pusat, DKI Jakarta',	'active',	0,	'2025-08-09 20:02:17',	NULL),
(15,	'Dinas Komunikasi dan Informatika Kota Malang',	'Jl. Mayjend Sungkono, Arjowinangun Kedungkandang Kota Malang',	'active',	0,	'2025-08-09 20:02:17',	NULL),
(16,	'Bank Jatim Kantor Cabang Kota Malang',	'Jl. Jaksa Agung Suprapto 26-28 Kec. Klojen Kota Malang',	'active',	0,	'2025-08-09 20:02:17',	NULL),
(17,	'PT. Pegadaian (Persero) Kota Malang',	'Jl. Ade Irma Suryani No.2 Kauman Klojen Malang',	'active',	0,	'2025-08-09 20:02:17',	NULL),
(18,	'BPS Kabupaten Mesuji',	'Jl. Raden Intan No. 02, Desa Mukti Karya, Kec. Panca Jaya Mesuji Lampung',	'active',	0,	'2025-08-09 20:02:17',	NULL),
(19,	'BPJS Kesehatan Kantor Cabang Tulungagung',	'Jl. Yos. Sudarso No.90, Karangwaru, Kec. Tulungagung Jawa Timur',	'active',	0,	'2025-08-09 20:02:17',	NULL),
(20,	'Bank BRI KC Tarakan',	'Jl. Yos Sudarso No. 86 Selumit Pantai Tarakan tengah Kalimantan Utara',	'active',	0,	'2025-08-09 20:02:17',	NULL),
(21,	'Badan Pusat Statistik Kabupaten Pasuruan',	'Jl. Sultan Agung No. 42, Purutrejo kec. Purworejo Pasuruan',	'active',	0,	'2025-08-09 20:02:17',	NULL),
(22,	'Bank Rakyat Indonesia Cabang Kawi Malang',	'Jl. Kawi No 20-22, Kauman kec. Klojen, Kota Malang',	'active',	0,	'2025-08-09 20:02:17',	NULL),
(23,	'BPJS Ketenagakerjaan Pasuruan',	'Jl. Ir. H. Juanda No. 77, Bugul Kidul Kota Pasuruan',	'active',	0,	'2025-08-09 20:02:17',	NULL),
(24,	'Badan Pengelolaan Pendapatan Daerah (BAPPENDA) Kabupaten Bogor',	'Jl. Tegar Beriman No.1, Pekansari, Kec.Cibinong Kab. Bogor Jawa Barat',	'active',	0,	'2025-08-09 20:02:17',	NULL),
(25,	'Badan Pusat Statistik Kabupaten Malang',	'Jl. Jatirejoyoso No.1A Dawukan Jatirejoyoso Kepanjen Malang',	'active',	0,	'2025-08-09 20:02:17',	NULL),
(26,	'Kantor Badan Pengelolaan Keuangan dan Aset Daerah (BPKAD) Kabupaten Nganjuk',	'Jl. Basuki Rahmat No.01 Mangundikaran Nganjuk',	'active',	0,	'2025-08-09 20:02:17',	NULL),
(27,	'BPS Kabupaten Banyuwangi',	'Jl. KH. Agus Salim No. 87 Mojopanggung Banyuwangi',	'active',	0,	'2025-08-09 20:02:17',	NULL),
(28,	'BSI KCP Soekarno Hatta Malang',	'Jl. Soekarno Hatta, Ruko Taman Niaga B15, B16, B17 dan S12 Kel. Jatimulyo Lowokwaru Malang',	'active',	0,	'2025-08-09 20:02:17',	NULL),
(29,	'PT. Anara Trisakti Medika',	'Jl. Pondok Raya Duren Sawit, Jakarta Timur',	'active',	0,	'2025-08-09 20:02:17',	NULL),
(30,	'Badan Pusat Statistik (BPS) Kota Jakarta Timur',	'Jl. Cipinang Baru Raya No. 214 Cipinang, Pulogadung Jakarta Timur',	'active',	0,	'2025-08-09 20:02:17',	NULL),
(31,	'OJK Purwokerto',	'Jl. Jend. Gatot Subroto No. 46, Purwokerto Sokanegara Banyumas',	'active',	0,	'2025-08-09 20:02:17',	NULL),
(32,	'Badan Pusat Statistik (BPS) Kota Malang',	'Jl. Janti Barat No. 47, Bandungrejosari, Kec Sukun, Kota Malang',	'active',	0,	'2025-08-09 20:02:17',	NULL),
(33,	'Dinas Komunikasi da Informatika Kota Malang',	'Jl. Mayjend Sungkono, Arjowinangun Kedungkandang Kota Malang',	'active',	0,	'2025-08-09 20:02:17',	NULL),
(34,	'PT. Asuransi Tugu Pratama Indonesia',	'Jl. H.R Rasuna Sa’id Kav. C8-9 Jakarta Selatan',	'active',	0,	'2025-08-09 20:02:17',	NULL),
(35,	'BPS Jakarta Timur',	'Jl. Cipinang Baru Raya Bo. 14 RT.02 RW.18 Cipinang Kec. Pulogadung Jakarta Timur',	'active',	0,	'2025-08-09 20:02:17',	NULL),
(36,	'Badan Pusat Statistik Kabupaten Tuban',	'Jl. Raya Manunggal No. 8 Sukolilo, Panyuran Tuban',	'active',	0,	'2025-08-09 20:02:17',	NULL),
(37,	'PT Toyota Astra Financial Services',	'Astra Biz Center Jl BSD Raya Utama No 22, Tangerang, Banten 15331,',	'active',	0,	'2025-08-09 20:02:17',	NULL),
(38,	'BPS Provinsi Bali',	'Jl. Raya Puputan No. 1 Renon, Denpasar Selatan, Kota Denpasar, Bali',	'active',	0,	'2025-08-09 20:02:17',	NULL),
(39,	'Badan Pusat Statistik Provinsi Jawa Barat',	'Jl. PHH. Mustofa No. 34 Bandung, Jawa Barat',	'active',	0,	'2025-08-09 20:02:17',	NULL),
(40,	'Kantor Pengawasan dan Pelayanan Bea dan Cukai TMP Belawan Medan',	'Jl. Anggada II No. 1, Kota Belawan, Medan',	'active',	0,	'2025-08-09 20:02:17',	NULL),
(41,	'PT. Jasa Raharja Cabang Bali',	'Jl. Hayam Wuruk No.202, Panjer, Denpasar Selatan, Kota Denpasar, Bal',	'active',	0,	'2025-08-09 20:02:17',	NULL),
(42,	'PT. Jasa Raharja Malang',	'Jl. Dr. Cipto No. 8, Kec. Klojen, Kota Malang, Jawa Timur',	'active',	0,	'2025-08-09 20:02:17',	NULL),
(43,	'PT. Taspen Kota Malang',	'Jl. Raden Intan, Kec. Blimbing, Kota Malang, Jawa Timur',	'active',	0,	'2025-08-09 20:02:17',	NULL),
(44,	'BPJS Ketenagakerjaan Kota Malang',	'Jl. Dr. Sutomo No. 1, RW.01, Klojen Kota Malang',	'active',	0,	'2025-08-09 20:02:17',	NULL),
(45,	'PT. Jasa Raharja Cabang Utama Jawa Timur',	'Jalan Diponegoro No.98, RT.002/RW.15, DR. Soetomo, Kec. Tegalsari, Kota Surabaya, Jawa Timur',	'active',	0,	'2025-08-09 20:02:17',	NULL),
(46,	'Bank Syariah Indonesia KCP Kalianda Lampung',	'Jl. Raden Intan No. 255 E-F-G Kalianda Lampung Selatan',	'active',	0,	'2025-08-09 20:02:17',	NULL),
(47,	'BPJS Kesehatan Cabang Denpasar',	'Jl. Panjaitan No. 6, Panjer Denpasar Selatan, Kota Denpasar Bali',	'active',	0,	'2025-08-09 20:02:17',	NULL),
(48,	'MPM Insurance',	'Jl. Panjang No. 5 RT.11/RW.10 Kebonjeruk Jakarta Barat',	'active',	0,	'2025-08-09 20:02:17',	NULL),
(49,	'BPJS Ketenagakerjaan Kabupaten Gresik',	'Jl. DR. Wahidin Sudiro Husodo No. 121A, Kebomas, Ngipik, Kec. Gresik, Kabupaten Gresik Jawa Timur',	'active',	0,	'2025-08-09 20:02:17',	NULL),
(50,	'PT. BPR Rakyat Kawan',	'Jl. Raya Jetis No. 105, Kec.Dau , Malang',	'active',	0,	'2025-08-09 20:02:17',	NULL),
(51,	'Biro Kepegawaian DPR RI',	'Jl. Gatot Subroto No. 1 RT.01/RW03 Senayan, Kecamatan Tanah Abang Kota Jakarta Pusat, DKI',	'active',	0,	'2025-08-09 20:02:17',	NULL),
(52,	'KPU Kota Malang',	'Jl. Bantaran No. 6 Malang',	'active',	0,	'2025-08-09 20:02:17',	NULL),
(53,	'Phintraco Sekuritas',	'Jl. Dr. Ide Anak Agung Gde Agung Kav. E3.2 No.1 Mega Kuningan Jakarta',	'active',	0,	'2025-08-09 20:02:17',	NULL),
(54,	'Badan Pusat Statistik Republik Indonesia',	'Jalan Dr. Sutomo No. 6-8, Ps. Baru, Kecamatan Sawah Besar',	'active',	0,	'2025-08-09 20:02:17',	NULL),
(55,	'Badan Pusat Statistik (BPS) Kabupaten Kutai Timur',	'Pusat Perkantoran Sekretariat Daerah Kabupaten Kutai Timur, Bukit Pelangi Sangatta, Kabupaten Kutai Timur',	'active',	0,	'2025-08-09 20:02:17',	NULL),
(56,	'Jasa Raharja Pekanbaru',	'Jl. Jend. Sudirman No. 285, Pekanbaru',	'active',	0,	'2025-08-09 20:02:17',	NULL),
(57,	'Badan Pusat Statistik Kabupaten Banyuwangi',	'Jl. K.H Agus Salim No. 87 Banyuwangi, Jawa Timur',	'active',	0,	'2025-08-09 20:02:17',	NULL),
(58,	'BPJS Ketenagakerjaan Cabang Kota Surakarta',	'di Jl. Bhayangkara Nomor 30, Panularan Laweyan',	'active',	0,	'2025-08-09 20:02:17',	NULL),
(59,	'Bank BTN KCP Kota Malang',	'Jl. Ade Irma Suryani No. 2, Kauman, Kota Malang',	'active',	0,	'2025-08-09 20:02:17',	NULL),
(60,	'Bank BNI KCP Kota Malang',	'Jalan Veteran No. 16, Ketawanggede, Kecamatan Lowokwaru, Kota Malang',	'active',	0,	'2025-08-09 20:02:17',	NULL);

DROP TABLE IF EXISTS `pkl_registrations`;
CREATE TABLE `pkl_registrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `student_id` varchar(20) NOT NULL,
  `student_phone_number` varchar(25) NOT NULL,
  `study_program_id` int(11) NOT NULL,
  `lecturer_id` varchar(20) NOT NULL,
  `addressed_to` varchar(100) NOT NULL,
  `institution_id` int(11) NOT NULL,
  `equivalent_activity` varchar(255) DEFAULT NULL,
  `activity_period_start` date NOT NULL,
  `activity_period_end` date NOT NULL,
  `portfolio_file` varchar(255) DEFAULT NULL,
  `proposal_file` varchar(255) DEFAULT NULL,
  `consultation_file` varchar(255) DEFAULT NULL,
  `status` enum('draft','awaiting_upload','accepted','completed','rejected') NOT NULL DEFAULT 'draft',
  `rejection_reason` text DEFAULT NULL,
  `rejection_file` varchar(255) DEFAULT NULL,
  `completed_file` varchar(255) DEFAULT NULL,
  `recommendation_letter_file` varchar(255) DEFAULT NULL,
  `is_deleted` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`),
  KEY `study_program_id` (`study_program_id`),
  KEY `lecturer_id` (`lecturer_id`),
  KEY `institution_id` (`institution_id`),
  CONSTRAINT `fk_pkl_registrations_institution` FOREIGN KEY (`institution_id`) REFERENCES `pkl_institutions` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_pkl_registrations_lecturer` FOREIGN KEY (`lecturer_id`) REFERENCES `lecturers` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_pkl_registrations_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_pkl_registrations_study_program` FOREIGN KEY (`study_program_id`) REFERENCES `study_programs` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- 2025-08-10 04:35:49 UTC
