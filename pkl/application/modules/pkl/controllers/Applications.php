<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Applications extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Applications_model', 'app');
        $this->load->library('form_validation');
    }

    // Daftar pengajuan mahasiswa
    public function index()
    {
        $student_id = $this->session->userdata('id');
        $this->data['title'] = 'Daftar Pengajuan PKL Saya';
        $this->data['applications'] = $this->app->get_by_student($student_id);
        $this->render('applications_index');
    }

    // Form pengajuan baru
    public function create()
    {
        $this->form_validation->set_rules('title', 'Judul PKL', 'required');
        $this->form_validation->set_rules('type', 'Jenis Kegiatan', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->data['title'] = 'Form Pengajuan PKL';
            $this->data['lecturers'] = $this->app->get_lecturers();
            $this->data['places']    = $this->app->get_places();
            $this->render('applications_form');
        } else {
            $insert = [
                'student_id' => $this->session->userdata('id'),
                'lecturer_id' => $this->input->post('lecturer_id'),
                'place_id'    => $this->input->post('place_id'),
                'title'       => $this->input->post('title'),
                'type'        => $this->input->post('type'),
                'status'      => 'submitted',
                'submission_date' => date('Y-m-d')
            ];
            $application_id = $this->app->insert_application($insert);

            // upload dokumen
            $this->_upload_document($application_id, 'portofolio', 'portofolio');
            $this->_upload_document($application_id, 'proposal', 'proposal');

            $this->session->set_flashdata('success', 'Pengajuan PKL berhasil disimpan!');
            redirect('pkl/applications');
        }
    }

    private function _upload_document($application_id, $field_name, $doc_type)
    {
        if (!empty($_FILES[$field_name]['name'])) {
            $config['upload_path']   = './uploads/pkl/';
            $config['allowed_types'] = 'pdf|doc|docx';
            $config['max_size']      = 2048;
            $config['file_name']     = $doc_type . '_' . time();

            $this->load->library('upload', $config);

            if ($this->upload->do_upload($field_name)) {
                $file = $this->upload->data();
                $this->app->insert_document([
                    'application_id' => $application_id,
                    'doc_type'       => $doc_type,
                    'file_path'      => 'uploads/pkl/' . $file['file_name'],
                    'status'         => 'submitted'
                ]);
            }
        }
    }

    // List aplikasi yang menunggu approval sesuai role
    public function approvals()
    {
        $role = $this->session->userdata('role'); // misal: dosen, kps, kadep, dekan
        $this->data['title'] = 'Daftar Pengajuan PKL untuk Approval';
        $this->data['applications'] = $this->app->get_applications_for_role($role);
        $this->render('applications_approvals');
    }

    // Action approve/reject
    public function approve($id)
    {
        $role = $this->session->userdata('role');

        // $status_map = [
        //     'dosen' => 'approved_dosen',
        //     'kps'   => 'approved_kps',
        //     'kadep' => 'approved_kadep',
        //     'dekan' => 'approved_dekan',
        // ];

        // if (!isset($status_map[$role])) show_error('Tidak diizinkan');

        // $this->app->update_status($id, $status_map[$role]);
        $this->app->update_status($id, 'approved_dosen');

        $this->app->insert_workflow([
            'application_id' => $id,
            'step_name'      => strtoupper($role) . ' Approval',
            'actor_id'       => $this->session->userdata('id'),
            'role'           => $role,
            'status'         => 'approved',
            'remarks'        => 'Disetujui oleh ' . ucfirst($role)
        ]);

        $this->session->set_flashdata('success', 'Pengajuan PKL berhasil disetujui.');
        redirect('pkl/applications/approvals');
    }

    public function reject($id)
    {
        $role = $this->session->userdata('role');

        $this->app->update_status($id, 'rejected');

        $this->app->insert_workflow([
            'application_id' => $id,
            'step_name'      => strtoupper($role) . ' Approval',
            'actor_id'       => $this->session->userdata('id'),
            'role'           => $role,
            'status'         => 'rejected',
            'remarks'        => $this->input->post('remarks') ?? 'Ditolak'
        ]);

        $this->session->set_flashdata('error', 'Pengajuan PKL ditolak.');
        redirect('pkl/applications/approvals');
    }

    // Tracking approval untuk mahasiswa
    public function tracking($id)
    {
        $student_id = $this->session->userdata('id');

        // pastikan mahasiswa hanya bisa lihat pengajuan miliknya
        $application = $this->db->get_where('pkl_applications', [
            'id' => $id,
            'student_id' => $student_id
        ])->row();

        if (!$application) show_error('Data tidak ditemukan atau tidak berhak mengakses.');

        $this->data['title'] = 'Tracking Pengajuan PKL';
        $this->data['application'] = $application;
        $this->data['workflow'] = $this->app->get_workflow($id);
        $this->render('applications_tracking');
    }
}
