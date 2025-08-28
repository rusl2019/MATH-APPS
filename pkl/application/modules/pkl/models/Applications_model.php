<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Applications_model extends CI_Model
{
    public function get_by_student($student_id)
    {
        return $this->db->select('a.*, l.name as lecturer_name, p.name as place_name')
            ->from('pkl_applications a')
            ->join('apps_lecturers l', 'a.lecturer_id = l.id', 'left')
            ->join('pkl_places p', 'a.place_id = p.id', 'left')
            ->where('a.student_id', $student_id)
            ->get()->result();
    }

    public function insert_application($data)
    {
        $this->db->insert('pkl_applications', $data);
        return $this->db->insert_id();
    }

    public function insert_document($data)
    {
        $this->db->insert('pkl_documents', $data);
    }

    public function get_lecturers()
    {
        return $this->db->get('apps_lecturers')->result();
    }

    public function get_places()
    {
        return $this->db->get('pkl_places')->result();
    }

    public function update_status($application_id, $status)
    {
        $this->db->where('id', $application_id)
            ->update('pkl_applications', ['status' => $status]);
    }

    public function insert_workflow($data)
    {
        $this->db->insert('pkl_workflow', $data);
    }

    public function get_applications_for_role($role)
    {
        // ambil data sesuai role approval
        $this->db->select('a.*, s.name as student_name, p.name as place_name, l.name as lecturer_name')
            ->from('pkl_applications a')
            ->join('apps_students s', 'a.student_id = s.id')
            ->join('pkl_places p', 'a.place_id = p.id', 'left')
            ->join('apps_lecturers l', 'a.lecturer_id = l.id', 'left');

        switch ($role) {
            case 'dosen':
                $this->db->where('a.status', 'submitted');
                break;
            case 'kps':
                $this->db->where('a.status', 'approved_dosen');
                break;
            case 'kadep':
                $this->db->where('a.status', 'approved_kps');
                break;
            case 'dekan':
                $this->db->where('a.status', 'approved_kadep');
                break;
        }
        return $this->db->get()->result();
    }

    public function get_workflow($application_id)
    {
        return $this->db->select('w.*, u.name as actor_name')
            ->from('pkl_workflow w')
            ->join('apps_lecturers u', 'w.actor_id = u.id', 'left') // dosen/lecturer
            ->where('w.application_id', $application_id)
            ->order_by('w.action_date', 'asc')
            ->get()->result();
    }
}
