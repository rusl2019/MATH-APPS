<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Migration to update PKL schema to align with 5-stage process
 */
class Migration_Update_pkl_schema extends CI_Migration
{
    public function up()
    {
        // Update pkl_applications table
        $this->db->query("ALTER TABLE `pkl_applications` 
            MODIFY COLUMN `status` ENUM('draft','submitted','approved_dosen','approved_kps','approved_kadep','recommendation_uploaded','rejected','rejected_instansi','ongoing','field_work_completed','seminar_requested','seminar_approved','seminar_scheduled','seminar_completed','revision','revision_submitted','revision_approved','finished') DEFAULT 'draft'");
        
        // Add seminar columns to pkl_applications
        $this->db->query("ALTER TABLE `pkl_applications` 
            ADD COLUMN `seminar_date` DATETIME DEFAULT NULL AFTER `activity_period_end`,
            ADD COLUMN `seminar_location` VARCHAR(255) DEFAULT NULL AFTER `seminar_date`");
        
        // Update pkl_assessments table
        $this->db->query("ALTER TABLE `pkl_assessments` 
            MODIFY COLUMN `assessor_type` ENUM('lapangan','dosen','dosen_pembimbing') NOT NULL");
        
        // Update pkl_documents table
        $this->db->query("ALTER TABLE `pkl_documents` 
            MODIFY COLUMN `doc_type` ENUM('portofolio','proposal','lembar_konsultasi','recommendation_letter','acceptance_letter','rejection_letter','logbook','sertifikat','laporan_pkl_draft','laporan_pkl_revised','seminar','berita_acara','berita_acara_seminar','lembar_pengesahan','form_b1','form_b2','form_b3','form_b4','form_b5','form_c1','form_d1','form_d3','form_d4','form_e1') NOT NULL");
        
        // Update pkl_seminars table
        $this->db->query("ALTER TABLE `pkl_seminars` 
            MODIFY COLUMN `schedule_date` DATETIME NOT NULL");
        
        // Update pkl_workflow table
        $this->db->query("ALTER TABLE `pkl_workflow` 
            MODIFY COLUMN `status` ENUM('pending','approved','rejected','done','submitted','scheduled','revision_started','completed') DEFAULT 'pending'");
    }

    public function down()
    {
        // Revert pkl_workflow table
        $this->db->query("ALTER TABLE `pkl_workflow` 
            MODIFY COLUMN `status` ENUM('pending','approved','rejected','done') DEFAULT 'pending'");
        
        // Revert pkl_seminars table
        $this->db->query("ALTER TABLE `pkl_seminars` 
            MODIFY COLUMN `schedule_date` DATE NOT NULL");
        
        // Revert pkl_documents table
        $this->db->query("ALTER TABLE `pkl_documents` 
            MODIFY COLUMN `doc_type` ENUM('portofolio','proposal','lembar_konsultasi','recommendation_letter','acceptance_letter','rejection_letter','logbook','sertifikat','laporan','seminar','berita_acara') NOT NULL");
        
        // Revert pkl_assessments table
        $this->db->query("ALTER TABLE `pkl_assessments` 
            MODIFY COLUMN `assessor_type` ENUM('lapangan','dosen') NOT NULL");
        
        // Remove seminar columns from pkl_applications
        $this->db->query("ALTER TABLE `pkl_applications` 
            DROP COLUMN `seminar_date`,
            DROP COLUMN `seminar_location`");
        
        // Revert pkl_applications table
        $this->db->query("ALTER TABLE `pkl_applications` 
            MODIFY COLUMN `status` ENUM('draft','submitted','approved_dosen','approved_kps','approved_kadep','recommendation_uploaded','rejected','ongoing','finished') DEFAULT 'draft'");
    }
}