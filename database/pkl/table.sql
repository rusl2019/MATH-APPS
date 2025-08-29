-- Adminer 5.3.0 MariaDB 11.8.2-MariaDB-ubu2404 dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `pkl_applications`;
CREATE TABLE `pkl_applications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` varchar(20) NOT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `lecturer_id` varchar(20) DEFAULT NULL,
  `place_id` int(11) DEFAULT NULL,
  `addressed_to` varchar(255) DEFAULT NULL,
  `equivalent_activity` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `status` enum('draft','submitted','approved_dosen','approved_kps','approved_kadep','recommendation_uploaded','approved_dekan','rejected','accepted_instansi','rejected_instansi','ongoing','finished') DEFAULT 'draft',
  `submission_date` date DEFAULT NULL,
  `activity_period_start` date DEFAULT NULL,
  `activity_period_end` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`),
  KEY `lecturer_id` (`lecturer_id`),
  KEY `place_id` (`place_id`),
  CONSTRAINT `fk_applications_lecturer` FOREIGN KEY (`lecturer_id`) REFERENCES `apps_lecturers` (`id`),
  CONSTRAINT `fk_applications_place` FOREIGN KEY (`place_id`) REFERENCES `pkl_places` (`id`),
  CONSTRAINT `fk_applications_student` FOREIGN KEY (`student_id`) REFERENCES `apps_students` (`id`)
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
  `doc_type` enum('portofolio','proposal','form_pengajuan','surat_pengantar','sk_pembimbing','logbook','sertifikat','laporan','seminar','berita_acara','recommendation_letter') NOT NULL,
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
  `name` varchar(255) NOT NULL,
  `address` text DEFAULT NULL,
  `contact_person` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


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


-- 2025-08-29 10:23:33 UTC
