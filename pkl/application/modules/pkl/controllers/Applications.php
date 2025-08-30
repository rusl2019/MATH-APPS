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

    public function create($source_app_id = null): void
    {
        $student_id = $this->session->userdata('id');
        $this->data['form_data'] = null;

        // If it is a re-application, load previous data
        if ($source_app_id) {
            $old_app = $this->app->get_application_by_id($source_app_id);
            // Security check: ensure the student owns the application and it was rejected
            if ($old_app && $old_app->student_id == $student_id && in_array($old_app->status, ['rejected', 'rejected_instansi'])) {
                $this->data['form_data'] = $old_app;
            } else {
                $this->session->set_flashdata('error', 'Pengajuan yang ingin Anda ajukan ulang tidak valid.');
                redirect('pkl/applications');
                return;
            }
        }

        // Get active semester from database
        $active_semester = $this->app->get_active_semester();
        if (!$active_semester) {
            $this->session->set_flashdata('error', 'Saat ini tidak ada semester aktif yang ditetapkan oleh admin. Pendaftaran ditutup.');
            redirect('pkl/applications');
            return;
        }

        // Check submission limit for the active semester
        $submission_count = $this->app->count_applications_by_semester($student_id, $active_semester->id);
        if ($submission_count >= 3) {
            $this->session->set_flashdata('error', 'Anda telah mencapai batas maksimal 3 kali pengajuan untuk semester ini.');
            redirect('pkl/applications');
            return;
        }

        $this->form_validation->set_rules('title', 'Judul PKL', 'required');
        $this->form_validation->set_rules('type', 'Jenis Kegiatan', 'required');
        $this->form_validation->set_rules('lecturer_id', 'Dosen Pembimbing', 'required');
        $this->form_validation->set_rules('place_id', 'Instansi', 'required');
        $this->form_validation->set_rules('phone_number', 'Nomor Telepon', 'required');
        $this->form_validation->set_rules('addressed_to', 'Surat Ditujukan Kepada', 'required');
        $this->form_validation->set_rules('activity_period_start', 'Tanggal Mulai', 'required');
        $this->form_validation->set_rules('activity_period_end', 'Tanggal Selesai', 'required');
        $this->form_validation->set_rules('semester_id', 'Semester', 'required');

        if ($this->form_validation->run() === false) {
            $this->data['title'] = 'Form Pengajuan PKL';
            $this->data['student_detail'] = $this->app->get_student($this->session->userdata('id'));
            $this->data['lecturers'] = $this->app->get_lecturers();
            $this->data['places'] = $this->app->get_places();
            $this->data['semesters'] = $this->app->get_all_semesters();
            $this->data['active_semester_id'] = $active_semester->id;

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
                'semester_id' => $this->input->post('semester_id'),
            ];
            $application_id = $this->app->insert_application($insert);

            $this->_upload_document($application_id, 'portfolio_file', 'portofolio');
            $this->_upload_document($application_id, 'proposal_file', 'proposal');
            $this->_upload_document($application_id, 'consultation_file', 'form_pengajuan');

            $this->session->set_flashdata('success', 'Pengajuan PKL berhasil disimpan!');
            redirect('pkl/applications');
        }
    }

    public function pelaksanaan(int $id): void
    {
        $application = $this->app->get_application_by_id($id);
        $student_id = $this->session->userdata('id');

        // Security check
        if (!$application || $application->student_id != $student_id || $application->status !== 'ongoing') {
            $this->session->set_flashdata('error', 'Halaman tidak valid atau Anda tidak diizinkan.');
            redirect('pkl/applications');
            return;
        }

        $this->data['title'] = 'Pelaksanaan PKL & Logbook';
        $this->data['application'] = $application;
        $this->data['logs'] = $this->app->get_logs_by_application($id);
        $this->render('applications_pelaksanaan');
    }

    public function add_log(int $id): void
    {
        $application = $this->app->get_application_by_id($id);
        $student_id = $this->session->userdata('id');

        // Security check
        if (!$application || $application->student_id != $student_id || $application->status !== 'ongoing') {
            $this->session->set_flashdata('error', 'Aksi tidak diizinkan.');
            redirect('pkl/applications');
            return;
        }

        $this->form_validation->set_rules('log_date', 'Tanggal Kegiatan', 'required');
        $this->form_validation->set_rules('activity', 'Uraian Kegiatan', 'required');

        if ($this->form_validation->run() === false) {
            $this->session->set_flashdata('error', validation_errors());
        } else {
            $data = [
                'application_id' => $id,
                'log_date' => $this->input->post('log_date'),
                'activity' => $this->input->post('activity'),
            ];
            if ($this->app->insert_log($data)) {
                $this->session->set_flashdata('success', 'Logbook berhasil disimpan.');
            } else {
                $this->session->set_flashdata('error', 'Gagal menyimpan logbook.');
            }
        }
        redirect('pkl/applications/pelaksanaan/' . $id);
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
        } elseif (in_array('head department', $roles)) {
            $role = 'kadep';
            $this->app->update_status($id, 'approved_kadep');
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

    public function all_applications(): void
    {
        $this->data['title'] = 'Semua Pengajuan PKL';
        $this->data['applications'] = $this->app->get_all_applications();
        $this->render('applications_all');
    }

    public function upload_recommendation(int $id): void
    {
        // Check if application is in approved_kadep status
        $application = $this->db->get_where('pkl_applications', ['id' => $id])->row();
        if (!$application || $application->status !== 'approved_kadep') {
            $this->session->set_flashdata('error', 'Pengajuan tidak valid atau belum disetujui oleh Ketua Departemen.');
            redirect('pkl/applications/all_applications');
        }

        $this->data['title'] = 'Unggah Surat Rekomendasi';
        $this->data['application_id'] = $id;

        if ($this->input->method() === "post") {
            $config = [
                'upload_path' => './uploads/pkl/',
                'allowed_types' => 'pdf',
                'max_size' => 2048,
                'file_name' => 'recommendation_letter_' . time(),
            ];

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('recommendation_file')) {
                $file = $this->upload->data();

                // Insert document
                $this->app->insert_document([
                    'application_id' => $id,
                    'doc_type' => 'recommendation_letter',
                    'file_path' => 'uploads/pkl/' . $file['file_name'],
                    'status' => 'submitted',
                ]);

                // Update application status
                $this->app->update_status($id, 'recommendation_uploaded');

                // Log workflow
                $this->app->insert_workflow([
                    'application_id' => $id,
                    'step_name' => 'Upload Surat Rekomendasi',
                    'actor_id' => $this->session->userdata('id'),
                    'role' => 'admin',
                    'status' => 'done',
                    'remarks' => 'Surat rekomendasi diunggah oleh admin',
                ]);

                $this->session->set_flashdata('success', 'Surat rekomendasi berhasil diunggah.');
                redirect('pkl/applications/all_applications');
            } else {
                $this->session->set_flashdata('error', 'Gagal mengunggah surat rekomendasi: ' . $this->upload->display_errors());
                redirect('pkl/applications/upload_recommendation/' . $id);
            }
        }

        $this->render('applications_upload_recommendation');
    }

    public function report_decision(int $id): void
    {
        // 1. Get application and check ownership & status
        $application = $this->app->get_application_by_id($id);
        $student_id = $this->session->userdata('id');

        if (!$application || $application->student_id != $student_id) {
            show_error('Anda tidak diizinkan untuk melakukan aksi ini.', 403);
            return;
        }

        if ($application->status !== 'recommendation_uploaded') {
            $this->session->set_flashdata('error', 'Aksi ini belum dapat dilakukan.');
            redirect('pkl/applications');
            return;
        }

        $this->data['title'] = 'Lapor Keputusan Instansi';
        $this->data['application'] = $application;

        // Handle form submission
        if ($this->input->method() === 'post') {
            $decision = $this->input->post('decision');

            if (!$decision) {
                $this->session->set_flashdata('error', 'Pilih salah satu keputusan.');
                redirect('pkl/applications/report_decision/' . $id);
                return;
            }

            if (empty($_FILES['response_letter']['name'])) {
                $this->session->set_flashdata('error', 'Surat balasan dari instansi wajib diunggah.');
                redirect('pkl/applications/report_decision/' . $id);
                return;
            }

            $doc_type = ($decision === 'accepted') ? 'acceptance_letter' : 'rejection_letter';
            $config = [
                'upload_path' => './uploads/pkl/',
                'allowed_types' => 'pdf',
                'max_size' => 2048,
                'file_name' => $doc_type . '_' . $id . '_' . time(),
            ];
            $this->load->library('upload', $config);

            if ($this->upload->do_upload('response_letter')) {
                $file = $this->upload->data();
                $this->app->insert_document([
                    'application_id' => $id,
                    'doc_type' => $doc_type,
                    'file_path' => 'uploads/pkl/' . $file['file_name'],
                    'status' => 'submitted',
                ]);

                if ($decision === 'accepted') {
                    $new_status = 'ongoing';
                    $message = 'Status penerimaan oleh instansi berhasil dilaporkan. Status PKL Anda telah diperbarui menjadi \'Sedang Berlangsung\'.';
                    $step_name = 'Penerimaan Instansi & Mulai PKL';
                    $remarks = 'Mahasiswa melaporkan bahwa pengajuan diterima oleh instansi dan kegiatan PKL telah dimulai.';
                } else { // rejected
                    $new_status = 'rejected';
                    $message = 'Status penolakan oleh instansi berhasil dilaporkan.';
                    $step_name = 'Penolakan Instansi';
                    $remarks = 'Mahasiswa melaporkan bahwa pengajuan ditolak oleh instansi.';
                }

                $this->app->update_status($id, $new_status);
                $this->app->insert_workflow([
                    'application_id' => $id,
                    'step_name' => $step_name,
                    'actor_id' => $this->session->userdata('id'),
                    'role' => 'mahasiswa',
                    'status' => $decision,
                    'remarks' => $remarks,
                ]);

                $this->session->set_flashdata('success', $message);
                redirect('pkl/applications');
            } else {
                $this->session->set_flashdata('error', 'Gagal mengunggah surat: ' . $this->upload->display_errors());
                redirect('pkl/applications/report_decision/' . $id);
                return;
            }
        } else {
            // Display the form
            $this->render('applications_report_decision');
        }
    }
}
