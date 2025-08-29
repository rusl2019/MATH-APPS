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

    public function index(): void
    {
        $student_id = $this->session->userdata('id');
        $this->data['title'] = 'Daftar Pengajuan PKL Saya';
        $this->data['applications'] = $this->app->get_by_student($student_id);
        $this->data['student_detail'] = $this->app->get_student($student_id);
        $this->data['documents'] = $this->app->get_documents_by_student($student_id);
        $this->render('applications_index');
    }

    public function create(): void
    {
        $this->form_validation->set_rules('title', 'Judul PKL', 'required');
        $this->form_validation->set_rules('type', 'Jenis Kegiatan', 'required');
        $this->form_validation->set_rules('lecturer_id', 'Dosen Pembimbing', 'required');
        $this->form_validation->set_rules('place_id', 'Instansi', 'required');
        $this->form_validation->set_rules('phone_number', 'Nomor Telepon', 'required');
        $this->form_validation->set_rules('addressed_to', 'Surat Ditujukan Kepada', 'required');
        $this->form_validation->set_rules('activity_period_start', 'Tanggal Mulai', 'required');
        $this->form_validation->set_rules('activity_period_end', 'Tanggal Selesai', 'required');

        if ($this->form_validation->run() === false) {
            $this->data['title'] = 'Form Pengajuan PKL';
            $this->data['student_detail'] = $this->app->get_student($this->session->userdata('id'));
            $this->data['lecturers'] = $this->app->get_lecturers();
            $this->data['places'] = $this->app->get_places();
            $this->render('applications_form');
        } else {
            $insert = [
                'student_id' => $this->session->userdata('id'),
                'lecturer_id' => $this->input->post('lecturer_id'),
                'place_id' => $this->input->post('place_id'),
                'phone_number' => $this->input->post('phone_number'),
                'addressed_to' => $this->input->post('addressed_to'),
                'equivalent_activity' => $this->input->post('equivalent_activity'),
                'title' => $this->input->post('title'),
                'type' => $this->input->post('type'),
                'status' => 'submitted',
                'submission_date' => date('Y-m-d'),
                'activity_period_start' => $this->input->post('activity_period_start'),
                'activity_period_end' => $this->input->post('activity_period_end'),
            ];
            $application_id = $this->app->insert_application($insert);

            $this->_upload_document($application_id, 'portfolio_file', 'portofolio');
            $this->_upload_document($application_id, 'proposal_file', 'proposal');
            $this->_upload_document($application_id, 'consultation_file', 'form_pengajuan');

            $this->session->set_flashdata('success', 'Pengajuan PKL berhasil disimpan!');
            redirect('pkl/applications');
        }
    }

    private function _upload_document(int $application_id, string $field_name, string $doc_type): void
    {
        if (!empty($_FILES[$field_name]['name'])) {
            $config = [
                'upload_path' => './uploads/pkl/',
                'allowed_types' => 'pdf|doc|docx',
                'max_size' => 2048,
                'file_name' => $doc_type . '_' . time(),
            ];

            $this->load->library('upload', $config);

            if ($this->upload->do_upload($field_name)) {
                $file = $this->upload->data();
                $this->app->insert_workflow([
                    'application_id' => $application_id,
                    'step_name' => 'Pengajuan PKL',
                    'actor_id' => $this->session->userdata('id'),
                    'role' => 'mahasiswa',
                    'status' => 'submitted',
                    'remarks' => 'Formulir pengajuan diajukan',
                ]);
                $this->app->insert_document([
                    'application_id' => $application_id,
                    'doc_type' => $doc_type,
                    'file_path' => 'uploads/pkl/' . $file['file_name'],
                ]);
            } else {
                $this->session->set_flashdata('error', 'Gagal mengunggah ' . $doc_type . ': ' . $this->upload->display_errors());
                redirect('pkl/applications/create');
            }
        }
    }

    public function approvals(): void
    {
        $roles = $this->session->userdata('role_names'); // misal: dosen, kps, kadep, dekan
        $this->data['title'] = 'Daftar Pengajuan PKL untuk Approval';
        $this->data['applications'] = $this->app->get_applications_for_role($roles);
        $this->render('applications_approvals');
    }

    public function approve(int $id): void
    {
        $roles = $this->session->userdata('role_names'); // misal: dosen, kps, kadep, dekan

        if (in_array('head study program', $roles)) {
            $role = 'kps';
            $this->app->update_status($id, 'approved_kps');
        } elseif (in_array('lecturer', $roles)) {
            $role = 'dosen';
            $this->app->update_status($id, 'approved_dosen');
        } else {
            show_error('Tidak diizinkan');
        }

        $this->app->insert_workflow([
            'application_id' => $id,
            'step_name' => strtoupper($role) . ' Approval',
            'actor_id' => $this->session->userdata('id'),
            'role' => $role,
            'status' => 'approved',
            'remarks' => 'Disetujui oleh ' . ucfirst($role),
        ]);

        $this->session->set_flashdata('success', 'Pengajuan PKL berhasil disetujui.');
        redirect('pkl/applications/approvals');
    }

    public function reject(int $id): void
    {
        $role = $this->session->userdata('role');

        $this->app->update_status($id, 'rejected');

        $this->app->insert_workflow([
            'application_id' => $id,
            'step_name' => strtoupper($role) . ' Approval',
            'actor_id' => $this->session->userdata('id'),
            'role' => $role,
            'status' => 'rejected',
            'remarks' => $this->input->post('remarks') ?? 'Ditolak',
        ]);

        $this->session->set_flashdata('error', 'Pengajuan PKL ditolak.');
        redirect('pkl/applications/approvals');
    }
}
