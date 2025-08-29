<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Applications_model extends CI_Model
{
    public function get_by_student($student_id): array
    {
        return $this->db->select('a.*, l.name as lecturer_name, p.name as place_name, p.address as place_address')
            ->from('pkl_applications a')
            ->join('apps_lecturers l', 'a.lecturer_id = l.id', 'left')
            ->join('pkl_places p', 'a.place_id = p.id', 'left')
            ->where('a.student_id', $student_id)
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

    public function get_workflow(int $application_id): array
    {
        return $this->db->select('w.*, u.name as actor_name')
            ->from('pkl_workflow w')
            ->join('apps_lecturers u', 'w.actor_id = u.id', 'left') // dosen/lecturer
            ->where('w.application_id', $application_id)
            ->order_by('w.action_date', 'asc')
            ->get()->result();
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
        $app_id = $this->db->select('id')
            ->from('pkl_applications')
            ->where('student_id', $student_id)
            ->get()->row();
        if (!$app_id) {
            return [];
        }
        $app_id = $app_id->id;
        return $this->db->get_where('pkl_documents', ['application_id' => $app_id])->result();
    }
}
