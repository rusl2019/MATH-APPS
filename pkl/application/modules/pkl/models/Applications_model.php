<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Applications_model extends CI_Model
{
    public function get_by_student($student_id): array
    {
            return $this->db->select('a.*, l.name as lecturer_name, p.name as place_name, p.address as place_address, sem.name as semester_name')
        ->from('pkl_applications a')
        ->join('apps_lecturers l', 'a.lecturer_id = l.id', 'left')
        ->join('pkl_places p', 'a.place_id = p.id', 'left')
        ->join('pkl_semesters sem', 'a.semester_id = sem.id', 'left')
        ->where('a.student_id', $student_id)
        ->order_by('a.id', 'DESC')
        ->get()->result();
    }

    public function insert_application(array $data): int
    {
        $this->db->insert('pkl_applications', $data);
        return $this->db->insert_id();
    }

    public function insert_document(array $data): void
    {
        $this->db->insert('pkl_documents', $data);
    }

    public function get_lecturers(): array
    {
        return $this->db->get('apps_lecturers')->result();
    }

    public function get_places(): array
    {
        return $this->db->get('pkl_places')->result();
    }

    public function get_all_semesters(): array
    {
        return $this->db->get('pkl_semesters')->result();
    }

    public function get_active_semester()
    {
        return $this->db->get_where('pkl_semesters', ['is_active' => 1])->row();
    }

    public function get_application_by_id(int $id)
    {
        return $this->db->get_where('pkl_applications', ['id' => $id])->row();
    }

    public function count_applications_by_semester(string $student_id, int $semester_id): int
    {
        return $this->db->from('pkl_applications')
            ->where('student_id', $student_id)
            ->where('semester_id', $semester_id)
            ->count_all_results();
    }

    public function get_logs_by_application(int $application_id): array
    {
        return $this->db->from('pkl_logs')
            ->where('application_id', $application_id)
            ->order_by('log_date', 'DESC')
            ->get()->result();
    }

    public function insert_log(array $data): bool
    {
        return $this->db->insert('pkl_logs', $data);
    }

    public function update_status(int $application_id, string $status): void
    {
        $this->db->where('id', $application_id)
            ->update('pkl_applications', ['status' => $status]);
    }

    public function insert_workflow(array $data): void
    {
        $this->db->insert('pkl_workflow', $data);
    }

    public function get_applications_for_role(array $roles): array
    {
        $id_kps = $this->get_study_program_kps();

        $this->db->select('a.*, s.name as student_name, p.name as place_name, p.address as place_address, l.name as lecturer_name')
            ->from('pkl_applications a')
            ->join('apps_students s', 'a.student_id = s.id')
            ->join('pkl_places p', 'a.place_id = p.id', 'left')
            ->join('apps_lecturers l', 'a.lecturer_id = l.id', 'left');

        if (in_array('head study program', $roles)) {
            $this->db->where('a.status', 'approved_dosen');
            $this->db->where('s.study_program_id', $id_kps[0]->id);
        } elseif (in_array('head department', $roles)) {
            $this->db->where('a.status', 'approved_kps');
        } elseif (in_array('lecturer', $roles)) {
            $this->db->where('a.status', 'submitted');
            $this->db->where('a.lecturer_id', $this->session->userdata('id'));
        }

        return $this->db->get()->result();
    }

    public function get_study_program_kps(): array
    {
        return $this->db->get_where('apps_study_programs', ['lecturer_id' => $this->session->userdata('id')])->result();
    }

    public function get_student($student_id)
    {
        $this->db->select('s.*, sp.name as study_program');
        $this->db->from('apps_students s');
        $this->db->join('apps_study_programs sp', 's.study_program_id = sp.id', 'left');
        return $this->db->where('s.id', $student_id)
            ->get('apps_students')->row();
    }

    public function get_documents_by_student($student_id): array
    {
        // Get all application IDs for the student
        $app_ids_query = $this->db->select('id')->from('pkl_applications')->where('student_id', $student_id)->get();
        $app_ids = array_column($app_ids_query->result_array(), 'id');

        if (empty($app_ids)) {
            return [];
        }

        // Fetch all documents linked to those application IDs
        return $this->db->where_in('application_id', $app_ids)->get('pkl_documents')->result();
    }

    public function get_all_applications(): array
    {
        return $this->db->select('a.*, s.name as student_name, p.name as place_name, p.address as place_address, l.name as lecturer_name')
            ->from('pkl_applications a')
            ->join('apps_students s', 'a.student_id = s.id')
            ->join('pkl_places p', 'a.place_id = p.id', 'left')
            ->join('apps_lecturers l', 'a.lecturer_id = l.id', 'left')
            ->get()->result();
    }
}
