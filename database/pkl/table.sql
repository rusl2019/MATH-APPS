-- ==========================================
--  TABEL TAMBAHAN UNTUK SOP PKL
-- ==========================================

-- 1. Tempat / Instansi PKL
DROP TABLE IF EXISTS `pkl_places`;
CREATE TABLE `pkl_places` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `address` TEXT DEFAULT NULL,
  `contact_person` VARCHAR(100) DEFAULT NULL,
  `email` VARCHAR(100) DEFAULT NULL,
  `phone` VARCHAR(50) DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP(),
  `updated_at` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 2. Pengajuan PKL (Formulir, Proposal, Portofolio)
DROP TABLE IF EXISTS `pkl_applications`;
CREATE TABLE `pkl_applications` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `student_id` VARCHAR(20) NOT NULL,           -- NIM mahasiswa
  `phone_number` VARCHAR(20) DEFAULT NULL,     -- Nomor telepon/WA
  `lecturer_id` VARCHAR(20) DEFAULT NULL,      -- Dosen pembimbing
  `place_id` INT(11) DEFAULT NULL,             -- Instansi PKL
  `addressed_to` VARCHAR(255) DEFAULT NULL,    -- Surat ditujukan kepada (jabatan)
  `equivalent_activity` VARCHAR(255) DEFAULT NULL, -- Kegiatan setara
  `title` VARCHAR(255) DEFAULT NULL,           -- Judul/tema PKL
  `type` VARCHAR(100) DEFAULT NULL,            -- Jenis kegiatan
  `status` ENUM(
    'draft',
    'submitted',
    'approved_dosen',
    'approved_kps',
    'approved_kadep',
    'approved_dekan',
    'rejected',
    'accepted_instansi',
    'rejected_instansi',
    'ongoing',
    'finished'
  ) DEFAULT 'draft',
  `submission_date` DATE DEFAULT NULL,
  `activity_period_start` DATE DEFAULT NULL,   -- Periode mulai
  `activity_period_end` DATE DEFAULT NULL,     -- Periode selesai
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP(),
  `updated_at` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(),
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`),
  KEY `lecturer_id` (`lecturer_id`),
  KEY `place_id` (`place_id`),
  CONSTRAINT `fk_applications_student` FOREIGN KEY (`student_id`) REFERENCES `apps_students` (`id`),
  CONSTRAINT `fk_applications_lecturer` FOREIGN KEY (`lecturer_id`) REFERENCES `apps_lecturers` (`id`),
  CONSTRAINT `fk_applications_place` FOREIGN KEY (`place_id`) REFERENCES `pkl_places` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 3. Dokumen (portofolio, proposal, surat, SK, laporan, sertifikat)
DROP TABLE IF EXISTS `pkl_documents`;
CREATE TABLE `pkl_documents` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `application_id` INT(11) NOT NULL,
  `doc_type` ENUM(
    'portofolio',
    'proposal',
    'form_pengajuan',
    'surat_pengantar',
    'sk_pembimbing',
    'logbook',
    'sertifikat',
    'laporan',
    'seminar',
    'berita_acara'
  ) NOT NULL,
  `file_path` VARCHAR(255) NOT NULL,
  `status` ENUM('draft','submitted','approved','rejected') DEFAULT 'draft',
  `uploaded_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP(),
  PRIMARY KEY (`id`),
  KEY `application_id` (`application_id`),
  CONSTRAINT `fk_documents_application` FOREIGN KEY (`application_id`) REFERENCES `pkl_applications` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 4. Log Book Harian / Mingguan
DROP TABLE IF EXISTS `pkl_logs`;
CREATE TABLE `pkl_logs` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `application_id` INT(11) NOT NULL,
  `log_date` DATE NOT NULL,
  `activity` TEXT NOT NULL,
  `approved_by` VARCHAR(20) DEFAULT NULL, -- dosen pembimbing / pembimbing lapangan
  `remarks` TEXT DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `application_id` (`application_id`),
  CONSTRAINT `fk_logs_application` FOREIGN KEY (`application_id`) REFERENCES `pkl_applications` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 5. Penilaian (Pembimbing Lapangan, Dosen, Seminar)
DROP TABLE IF EXISTS `pkl_assessments`;
CREATE TABLE `pkl_assessments` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `application_id` INT(11) NOT NULL,
  `assessor_type` ENUM('lapangan','dosen') NOT NULL,
  `form_type` VARCHAR(50) NOT NULL, -- B-2, B-5, D-1, dll
  `score` DECIMAL(5,2) DEFAULT NULL,
  `remarks` TEXT DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP(),
  PRIMARY KEY (`id`),
  KEY `application_id` (`application_id`),
  CONSTRAINT `fk_assessments_application` FOREIGN KEY (`application_id`) REFERENCES `pkl_applications` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 6. Seminar PKL
DROP TABLE IF EXISTS `pkl_seminars`;
CREATE TABLE `pkl_seminars` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `application_id` INT(11) NOT NULL,
  `schedule_date` DATE NOT NULL,
  `place` VARCHAR(255) DEFAULT NULL,
  `status` ENUM('planned','done','revised') DEFAULT 'planned',
  `berita_acara_file` VARCHAR(255) DEFAULT NULL,
  `nilai_file` VARCHAR(255) DEFAULT NULL,
  `revisi_file` VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `application_id` (`application_id`),
  CONSTRAINT `fk_seminars_application` FOREIGN KEY (`application_id`) REFERENCES `pkl_applications` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 7. Laporan PKL
DROP TABLE IF EXISTS `pkl_reports`;
CREATE TABLE `pkl_reports` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `application_id` INT(11) NOT NULL,
  `version` INT(11) NOT NULL DEFAULT 1,
  `file_path` VARCHAR(255) NOT NULL,
  `status` ENUM('draft','submitted','approved_dosen','revisi','final') DEFAULT 'draft',
  `submission_date` DATE DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `application_id` (`application_id`),
  CONSTRAINT `fk_reports_application` FOREIGN KEY (`application_id`) REFERENCES `pkl_applications` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 8. Workflow Tracking (opsional, untuk log status setiap tahap)
DROP TABLE IF EXISTS `pkl_workflow`;
CREATE TABLE `pkl_workflow` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `application_id` INT(11) NOT NULL,
  `step_name` VARCHAR(100) NOT NULL, -- tahap 1-18
  `actor_id` VARCHAR(20) DEFAULT NULL,
  `role` VARCHAR(50) DEFAULT NULL,
  `status` ENUM('pending','approved','rejected','done') DEFAULT 'pending',
  `action_date` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP(),
  `remarks` TEXT DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `application_id` (`application_id`),
  CONSTRAINT `fk_workflow_application` FOREIGN KEY (`application_id`) REFERENCES `pkl_applications` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
