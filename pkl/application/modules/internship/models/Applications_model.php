<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Applications_model extends CI_Model
{
    /**
     * Get applications by student ID
     */
    public function get_by_student($student_id)
    {
        return $this->db->select('a.*, l.name as lecturer_name, p.name as place_name, p.address as place_address, sem.name as semester_name')
            ->from('pkl_applications a')
            ->join('apps_lecturers l', 'a.lecturer_id = l.id', 'left')
            ->join('pkl_places p', 'a.place_id = p.id', 'left')
            ->join('pkl_semesters sem', 'a.semester_id = sem.id', 'left')
            ->where('a.student_id', $student_id)
            ->order_by('a.id', 'DESC')
            ->get()
            ->result();
    }

    /**
     * Insert a new application
     */
    public function insert_application($data)
    {
        $this->db->insert('pkl_applications', $data);
        return $this->db->insert_id();
    }

    /**
     * Insert a document
     */
    public function insert_document($data)
    {
        $this->db->insert('pkl_documents', $data);
    }

    /**
     * Get all lecturers
     */
    public function get_lecturers()
    {
        return $this->db->get('apps_lecturers')->result();
    }

    /**
     * Get all places
     */
    public function get_places()
    {
        return $this->db->get('pkl_places')->result();
    }

    /**
     * Get all semesters
     */
    public function get_all_semesters()
    {
        return $this->db->get('pkl_semesters')->result();
    }

    /**
     * Get active semester
     */
    public function get_active_semester()
    {
        return $this->db->get_where('pkl_semesters', ['is_active' => 1])->row();
    }

    /**
     * Get application by ID
     */
    public function get_application_by_id($id)
    {
        return $this->db->select('a.*, l.name as lecturer_name, p.name as place_name, p.address as place_address, sem.name as semester_name')
            ->from('pkl_applications a')
            ->join('apps_lecturers l', 'a.lecturer_id = l.id', 'left')
            ->join('pkl_places p', 'a.place_id = p.id', 'left')
            ->join('pkl_semesters sem', 'a.semester_id = sem.id', 'left')
            ->where('a.id', $id)
            ->get()
            ->row();
    }

    /**
     * Count applications by semester for a student
     */
    public function count_applications_by_semester($student_id, $semester_id)
    {
        return $this->db->from('pkl_applications')
            ->where('student_id', $student_id)
            ->where('semester_id', $semester_id)
            ->count_all_results();
    }

    /**
     * Get logs by application ID
     */
    public function get_logs_by_application($application_id)
    {
        return $this->db->from('pkl_logs')
            ->where('application_id', $application_id)
            ->order_by('date', 'DESC')
            ->get()
            ->result();
    }

    /**
     * Insert a log entry
     */
    public function insert_log($data)
    {
        return $this->db->insert('pkl_logs', $data);
    }

    /**
     * Insert batch assessments
     */
    public function insert_assessments($data)
    {
        return $this->db->insert_batch('pkl_assessments', $data);
    }

    /**
     * Update application status
     */
    public function update_status($application_id, $status)
    {
        $this->db->where('id', $application_id)
            ->update('pkl_applications', ['status' => $status]);
    }

    /**
     * Update an application
     */
    public function update_application($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('pkl_applications', $data);
    }

    /**
     * Insert workflow entry
     */
    public function insert_workflow($data)
    {
        $this->db->insert('pkl_workflow', $data);
    }

    /**
     * Get applications for approval based on user roles
     */
    public function get_applications_for_role($roles)
    {
        $id_kps = $this->get_study_program_kps();
        $user_id = $this->session->userdata('id');

        $this->db->select('a.*, s.name as student_name, s.id as student_nim, sp.name as study_program_name, p.name as place_name, p.address as place_address, l.name as lecturer_name')
            ->from('pkl_applications a')
            ->join('apps_students s', 'a.student_id = s.id')
            ->join('apps_study_programs sp', 's.study_program_id = sp.id', 'left')
            ->join('pkl_places p', 'a.place_id = p.id', 'left')
            ->join('apps_lecturers l', 'a.lecturer_id = l.id', 'left');

        if (in_array('head study program', $roles)) {
            $this->db->where('a.status', 'approved_dosen');
            if (!empty($id_kps)) {
                $this->db->where('s.study_program_id', $id_kps[0]->id);
            }
        } elseif (in_array('head department', $roles)) {
            $this->db->where('a.status', 'approved_kps');
        } elseif (in_array('lecturer', $roles)) {
            $statuses = ['submitted', 'seminar_requested', 'seminar_approved', 'seminar_scheduled', 'seminar_completed', 'report_rejected', 'revision_submitted'];
            $this->db->where_in('a.status', $statuses);
            $this->db->where('a.lecturer_id', $user_id);
        }

        return $this->db->get()->result();
    }

    /**
     * Get study program KPS
     */
    public function get_study_program_kps()
    {
        $user_id = $this->session->userdata('id');
        return $this->db->get_where('apps_study_programs', ['lecturer_id' => $user_id])->result();
    }

    /**
     * Get student details
     */
    public function get_student($student_id)
    {
        return $this->db->select('s.*, sp.name as study_program')
            ->from('apps_students s')
            ->join('apps_study_programs sp', 's.study_program_id = sp.id', 'left')
            ->where('s.id', $student_id)
            ->get()
            ->row();
    }

    /**
     * Get documents by student
     */
    public function get_documents_by_student($student_id)
    {
        // Get all application IDs for the student
        $app_ids_query = $this->db->select('id')
            ->from('pkl_applications')
            ->where('student_id', $student_id)
            ->get();

        $app_ids = array_column($app_ids_query->result_array(), 'id');

        if (empty($app_ids)) {
            return [];
        }

        // Fetch all documents linked to those application IDs
        return $this->db->where_in('application_id', $app_ids)
            ->get('pkl_documents')
            ->result();
    }

    /**
     * Get all applications (admin view)
     */
    public function get_all_applications()
    {
        return $this->db->select('a.*, s.name as student_name, s.id as student_nim, sp.name as study_program_name, p.name as place_name, p.address as place_address, l.name as lecturer_name')
            ->from('pkl_applications a')
            ->join('apps_students s', 'a.student_id = s.id')
            ->join('apps_study_programs sp', 's.study_program_id = sp.id', 'left')
            ->join('pkl_places p', 'a.place_id = p.id', 'left')
            ->join('apps_lecturers l', 'a.lecturer_id = l.id', 'left')
            ->get()
            ->result();
    }

    /**
     * Get documents by application ID
     */
    public function get_documents_by_application($application_id)
    {
        return $this->db->where('application_id', $application_id)
            ->get('pkl_documents')
            ->result();
    }

    /**
     * Get document by application ID and document type
     */
    public function get_document_by_type($application_id, $doc_type)
    {
        return $this->db->where('application_id', $application_id)
            ->where('doc_type', $doc_type)
            ->get('pkl_documents')
            ->row();
    }

    /**
     * Get assessments by application ID
     */
    public function get_assessments_by_application($application_id)
    {
        return $this->db->where('application_id', $application_id)
            ->get('pkl_assessments')
            ->result();
    }

    /**
     * Get workflow by application ID
     */
    public function get_workflow_by_application($application_id)
    {
        return $this->db->where('application_id', $application_id)
            ->order_by('action_date', 'ASC')
            ->get('pkl_workflow')
            ->result();
    }

    /**
     * Get logs by application ID and date range
     */
    public function get_logs_by_date_range($application_id, $start_date, $end_date)
    {
        return $this->db->from('pkl_logs')
            ->where('application_id', $application_id)
            ->where('date >=', $start_date)
            ->where('date <=', $end_date)
            ->order_by('date', 'ASC')
            ->get()
            ->result();
    }

    /**
     * Get all weekly logbook submissions for an application
     */
    public function get_weekly_logbooks($application_id)
    {
        return $this->db->from('pkl_logbook_weekly')
            ->where('application_id', $application_id)
            ->order_by('week_number', 'ASC')
            ->get()
            ->result();
    }

    /**
     * Insert a new weekly logbook submission
     */
    public function insert_weekly_logbook($data)
    {
        return $this->db->insert('pkl_logbook_weekly', $data);
    }

    /**
     * Get supervised internship applications based on user role
     */
    public function get_supervised_internships_by_user_id($user_id)
    {
        return $this->db->select('a.*, s.name as student_name, l.name as lecturer_name, p.name as place_name')
            ->from('pkl_applications a')
            ->join('apps_students s', 'a.student_id = s.id')
            ->join('apps_lecturers l', 'a.lecturer_id = l.id', 'left')
            ->join('pkl_places p', 'a.place_id = p.id', 'left')
            ->where('a.lecturer_id', $user_id)
            ->order_by('a.submission_date', 'DESC')->get()->result();
    }

    /**
     * Get all lembar konsultasi entries for an application
     */
    public function get_lembar_konsultasi($application_id)
    {
        return $this->db->where('application_id', $application_id)
            ->order_by('date', 'ASC')
            ->get('pkl_lembar_konsultasi')
            ->result();
    }
}
