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

    /**
     * Display student's PKL applications
     */
    public function index()
    {
        $student_id = $this->session->userdata('id');
        $this->data['title'] = 'Daftar Pengajuan PKL Saya';
        $this->data['applications'] = $this->app->get_by_student($student_id);
        $this->data['student_detail'] = $this->app->get_student($student_id);
        $this->data['documents'] = $this->app->get_documents_by_student($student_id);
        $this->render('applications_index');
    }

    /**
     * Create a new PKL application or re-apply from a rejected one
     */
    public function create($source_app_id = null)
    {
        $student_id = $this->session->userdata('id');
        $this->data['form_data'] = null;

        // Handle re-application from a rejected application
        if ($source_app_id && !$this->handle_reapplication($source_app_id, $student_id)) {
            return;
        }

        // Validate semester and submission limits
        $active_semester = $this->app->get_active_semester();
        if (
            !$this->validate_semester($active_semester) ||
            !$this->validate_submission_limit($student_id, $active_semester)
        ) {
            return;
        }

        // Set form validation rules
        $this->set_application_validation_rules();

        if ($this->form_validation->run() === false) {
            $this->load_form_data($student_id, $active_semester->id);
            $this->render('applications_form');
        } else {
            $application_id = $this->process_application_submission($student_id);

            if ($application_id) {
                $this->process_documents($application_id);
                $this->session->set_flashdata('success', 'Pengajuan PKL berhasil disimpan!');
            } else {
                $this->session->set_flashdata('error', 'Gagal menyimpan pengajuan PKL.');
            }

            redirect('pkl/applications');
        }
    }

    /**
     * Display PKL implementation and logbook
     */
    public function pelaksanaan($id)
    {
        $student_id = $this->session->userdata('id');

        if (!$this->validate_application_access($id, $student_id, 'ongoing')) {
            return;
        }

        $application = $this->app->get_application_by_id($id);
        $this->data['title'] = 'Pelaksanaan PKL & Logbook';
        $this->data['application'] = $application;
        $this->data['logs'] = $this->app->get_logs_by_application($id);
        $this->render('applications_pelaksanaan');
    }

    /**
     * Add a new logbook entry
     */
    public function add_log($id)
    {
        $student_id = $this->session->userdata('id');

        if (!$this->validate_application_access($id, $student_id, 'ongoing')) {
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

    /**
     * Display applications for approval based on user roles
     */
    public function approvals()
    {
        $roles = $this->session->userdata('role_names');
        $this->data['title'] = 'Daftar Pengajuan PKL untuk Approval';
        $this->data['applications'] = $this->app->get_applications_for_role($roles);
        $this->render('applications_approvals');
    }

    /**
     * Approve a PKL application
     */
    public function approve($id)
    {
        $roles = $this->session->userdata('role_names');
        $user_id = $this->session->userdata('id');

        // Determine role and update status accordingly
        $role_data = $this->get_role_approval_data($roles);
        if (!$role_data) {
            show_error('Tidak diizinkan');
            return;
        }

        $this->app->update_status($id, $role_data['status']);
        $this->app->insert_workflow([
            'application_id' => $id,
            'step_name' => $role_data['step_name'],
            'actor_id' => $user_id,
            'role' => $role_data['role'],
            'status' => 'approved',
            'remarks' => $role_data['remarks'],
        ]);

        $this->session->set_flashdata('success', 'Pengajuan PKL berhasil disetujui.');
        redirect('pkl/applications/approvals');
    }

    /**
     * Reject a PKL application
     */
    public function reject($id)
    {
        $role = $this->session->userdata('role');
        $user_id = $this->session->userdata('id');
        $remarks = $this->input->post('remarks') ?? 'Ditolak';

        $this->app->update_status($id, 'rejected');
        $this->app->insert_workflow([
            'application_id' => $id,
            'step_name' => strtoupper($role) . ' Approval',
            'actor_id' => $user_id,
            'role' => $role,
            'status' => 'rejected',
            'remarks' => $remarks,
        ]);

        $this->session->set_flashdata('error', 'Pengajuan PKL ditolak.');
        redirect('pkl/applications/approvals');
    }

    /**
     * Display all PKL applications (admin view)
     */
    public function all_applications()
    {
        $this->data['title'] = 'Semua Pengajuan PKL';
        $this->data['applications'] = $this->app->get_all_applications();
        $this->render('applications_all');
    }

    /**
     * Upload recommendation letter for approved applications
     */
    public function upload_recommendation($id)
    {
        // Validate application status
        $application = $this->db->get_where('pkl_applications', ['id' => $id])->row();
        if (!$application || $application->status !== 'approved_kadep') {
            $this->session->set_flashdata('error', 'Pengajuan tidak valid atau belum disetujui oleh Ketua Departemen.');
            redirect('pkl/applications/all_applications');
            return;
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
                $user_id = $this->session->userdata('id');

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
                    'actor_id' => $user_id,
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

    /**
     * Report decision from the institution
     */
    public function report_decision($id)
    {
        $student_id = $this->session->userdata('id');

        // Validate application ownership and status
        $application = $this->app->get_application_by_id($id);
        if (!$this->validate_decision_report($application, $student_id)) {
            return;
        }

        $this->data['title'] = 'Lapor Keputusan Instansi';
        $this->data['application'] = $application;

        // Handle form submission
        if ($this->input->method() === 'post') {
            $decision = $this->input->post('decision');

            if (!$this->validate_decision_submission($decision)) {
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

                // Save document
                $this->app->insert_document([
                    'application_id' => $id,
                    'doc_type' => $doc_type,
                    'file_path' => 'uploads/pkl/' . $file['file_name'],
                    'status' => 'submitted',
                ]);

                // Process decision
                if ($decision === 'accepted') {
                    $this->process_accepted_decision($id);
                } else {
                    $this->process_rejected_decision($id);
                }

                redirect('pkl/applications');
            } else {
                $this->session->set_flashdata('error', 'Gagal mengunggah surat: ' . $this->upload->display_errors());
                redirect('pkl/applications/report_decision/' . $id);
            }
        } else {
            // Display the form
            $this->render('applications_report_decision');
        }
    }

    /**
     * Report PKL completion
     */
    public function finish_pkl($id)
    {
        $student_id = $this->session->userdata('id');

        // Validate that the student can access this page
        if (!$this->validate_application_access($id, $student_id, 'ongoing')) {
            return;
        }

        $this->data['title'] = 'Form Penyelesaian PKL';
        $this->data['application_id'] = $id;

        // Set validation rules for all 7 criteria
        $criteria = ['pengetahuan', 'keterampilan', 'inisiatif', 'tanggung_jawab', 'kerjasama_tim', 'kehadiran', 'laporan'];
        foreach ($criteria as $criterion) {
            $this->form_validation->set_rules($criterion, ucfirst(str_replace('_', ' ', $criterion)), 'required|numeric|less_than_equal_to[100]|greater_than_equal_to[0]');
        }

        if ($this->form_validation->run() === false) {
            // If validation fails or it's the first visit, show the form
            $this->render('applications_finish_form');
        } else {
            // Process the form submission
            $user_id = $this->session->userdata('id');

            // 1. Handle Certificate Upload
            $certificate_path = $this->_do_upload('certificate_file', 'sertifikat_' . $id);
            if (!$certificate_path) {
                redirect('pkl/applications/finish_pkl/' . $id);
                return;
            }

            // 2. Handle Evaluation Form Upload
            $evaluation_path = $this->_do_upload('evaluation_file', 'penilaian_lapangan_' . $id);
            if (!$evaluation_path) {
                redirect('pkl/applications/finish_pkl/' . $id);
                return;
            }

            // 3. Insert documents to DB
            $this->app->insert_document([
                'application_id' => $id,
                'doc_type' => 'sertifikat',
                'file_path' => $certificate_path,
                'status' => 'submitted',
            ]);
            $this->app->insert_document([
                'application_id' => $id,
                'doc_type' => 'berita_acara', // Using 'berita_acara' for field evaluation form
                'file_path' => $evaluation_path,
                'status' => 'submitted',
            ]);

            // 4. Insert scores into pkl_assessments
            $assessments = [];
            foreach ($criteria as $criterion) {
                $assessments[] = [
                    'application_id' => $id,
                    'assessor_type' => 'lapangan',
                    'form_type' => $criterion,
                    'score' => $this->input->post($criterion),
                    'remarks' => 'Nilai dari pembimbing lapangan.',
                ];
            }
            $this->app->insert_assessments($assessments);

            // 5. Update application status
            $this->app->update_application($id, [
                'status' => 'field_work_completed'
            ]);

            // 6. Log workflow
            $this->app->insert_workflow([
                'application_id' => $id,
                'step_name' => 'Penyelesaian PKL Lapangan',
                'actor_id' => $user_id,
                'role' => 'mahasiswa',
                'status' => 'done',
                'remarks' => 'Mahasiswa melaporkan bahwa kegiatan PKL di lapangan telah selesai. Nilai dari pembimbing lapangan telah diinput.',
            ]);

            $this->session->set_flashdata('success', 'Selamat, Anda telah menyelesaikan PKL di lapangan! Status PKL Anda telah diperbarui. Silakan lanjutkan ke tahap seminar.');
            redirect('pkl/applications');
        }
    }

    // PRIVATE HELPER METHODS

    /**
     * Handle a file upload
     */
    private function _do_upload($field_name, $file_name_prefix)
    {
        $config = [
            'upload_path' => './uploads/pkl/',
            'allowed_types' => 'pdf',
            'max_size' => 2048,
            'file_name' => $file_name_prefix . '_' . time(),
        ];

        // Load and initialize upload library for each upload
        $this->load->library('upload');
        $this->upload->initialize($config);

        if ($this->upload->do_upload($field_name)) {
            $file_data = $this->upload->data();
            return 'uploads/pkl/' . $file_data['file_name'];
        } else {
            $this->session->set_flashdata('error', 'Gagal mengunggah file (' . $field_name . '): ' . $this->upload->display_errors('', ''));
            return false;
        }
    }

    /**
     * Handle reapplication logic
     */
    private function handle_reapplication($source_app_id, $student_id)
    {
        $old_app = $this->app->get_application_by_id($source_app_id);

        // Security check: ensure the student owns the application and it was rejected
        if ($old_app && $old_app->student_id == $student_id && in_array($old_app->status, ['rejected', 'rejected_instansi'])) {
            $this->data['form_data'] = $old_app;
            return true;
        } else {
            $this->session->set_flashdata('error', 'Pengajuan yang ingin Anda ajukan ulang tidak valid.');
            redirect('pkl/applications');
            return false;
        }
    }

    /**
     * Validate active semester
     */
    private function validate_semester($active_semester)
    {
        if (!$active_semester) {
            $this->session->set_flashdata('error', 'Saat ini tidak ada semester aktif yang ditetapkan oleh admin. Pendaftaran ditutup.');
            redirect('pkl/applications');
            return false;
        }
        return true;
    }

    /**
     * Validate submission limit
     */
    private function validate_submission_limit($student_id, $active_semester)
    {
        $submission_count = $this->app->count_applications_by_semester($student_id, $active_semester->id);
        if ($submission_count >= 3) {
            $this->session->set_flashdata('error', 'Anda telah mencapai batas maksimal 3 kali pengajuan untuk semester ini.');
            redirect('pkl/applications');
            return false;
        }
        return true;
    }

    /**
     * Set form validation rules for application
     */
    private function set_application_validation_rules()
    {
        $this->form_validation->set_rules('title', 'Judul PKL', 'required');
        $this->form_validation->set_rules('type', 'Jenis Kegiatan', 'required');
        $this->form_validation->set_rules('lecturer_id', 'Dosen Pembimbing', 'required');
        $this->form_validation->set_rules('place_id', 'Instansi', 'required');
        $this->form_validation->set_rules('phone_number', 'Nomor Telepon', 'required');
        $this->form_validation->set_rules('addressed_to', 'Surat Ditujukan Kepada', 'required');
        $this->form_validation->set_rules('activity_period_start', 'Tanggal Mulai', 'required');
        $this->form_validation->set_rules('activity_period_end', 'Tanggal Selesai', 'required');
        $this->form_validation->set_rules('semester_id', 'Semester', 'required');
    }

    /**
     * Load form data for application creation
     */
    private function load_form_data($student_id, $active_semester_id)
    {
        $this->data['title'] = 'Form Pengajuan PKL';
        $this->data['student_detail'] = $this->app->get_student($student_id);
        $this->data['lecturers'] = $this->app->get_lecturers();
        $this->data['places'] = $this->app->get_places();
        $this->data['semesters'] = $this->app->get_all_semesters();
        $this->data['active_semester_id'] = $active_semester_id;
    }

    /**
     * Process application submission
     */
    private function process_application_submission($student_id)
    {
        $insert = [
            'student_id' => $student_id,
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

        return $this->app->insert_application($insert);
    }

    /**
     * Process uploaded documents
     */
    private function process_documents($application_id)
    {
        $this->_upload_document($application_id, 'portfolio_file', 'portofolio');
        $this->_upload_document($application_id, 'proposal_file', 'proposal');
        $this->_upload_document($application_id, 'consultation_file', 'lembar_konsultasi');
    }

    /**
     * Upload a single document
     */
    private function _upload_document($application_id, $field_name, $doc_type)
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
                $user_id = $this->session->userdata('id');

                $this->app->insert_workflow([
                    'application_id' => $application_id,
                    'step_name' => 'Pengajuan PKL',
                    'actor_id' => $user_id,
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

    /**
     * Validate application access for a student
     */
    private function validate_application_access($application_id, $student_id, $required_status)
    {
        $application = $this->app->get_application_by_id($application_id);

        if (!$application || $application->student_id != $student_id || $application->status !== $required_status) {
            $this->session->set_flashdata('error', 'Halaman tidak valid atau Anda tidak diizinkan.');
            redirect('pkl/applications');
            return false;
        }

        return true;
    }

    /**
     * Get role-specific approval data
     */
    private function get_role_approval_data($roles)
    {
        if (in_array('head study program', $roles)) {
            return [
                'status' => 'approved_kps',
                'role' => 'kps',
                'step_name' => 'KPS APPROVAL',
                'remarks' => 'Disetujui oleh KPS',
            ];
        } elseif (in_array('head department', $roles)) {
            return [
                'status' => 'approved_kadep',
                'role' => 'kadep',
                'step_name' => 'KADep APPROVAL',
                'remarks' => 'Disetujui oleh Ketua Departemen',
            ];
        } elseif (in_array('lecturer', $roles)) {
            return [
                'status' => 'approved_dosen',
                'role' => 'dosen',
                'step_name' => 'DOSEN APPROVAL',
                'remarks' => 'Disetujui oleh Dosen',
            ];
        }

        return false;
    }

    /**
     * Validate decision report
     */
    private function validate_decision_report($application, $student_id)
    {
        if (!$application || $application->student_id != $student_id) {
            show_error('Anda tidak diizinkan untuk melakukan aksi ini.', 403);
            return false;
        }

        if ($application->status !== 'recommendation_uploaded') {
            $this->session->set_flashdata('error', 'Aksi ini belum dapat dilakukan.');
            redirect('pkl/applications');
            return false;
        }

        return true;
    }

    /**
     * Validate decision submission
     */
    private function validate_decision_submission($decision)
    {
        if (!$decision) {
            $this->session->set_flashdata('error', 'Pilih salah satu keputusan.');
            return false;
        }

        if (empty($_FILES['response_letter']['name'])) {
            $this->session->set_flashdata('error', 'Surat balasan dari instansi wajib diunggah.');
            return false;
        }

        return true;
    }

    /**
     * Process accepted decision
     */
    private function process_accepted_decision($application_id)
    {
        $user_id = $this->session->userdata('id');

        $this->app->update_status($application_id, 'ongoing');
        $this->app->insert_workflow([
            'application_id' => $application_id,
            'step_name' => 'Penerimaan Instansi & Mulai PKL',
            'actor_id' => $user_id,
            'role' => 'mahasiswa',
            'status' => 'accepted',
            'remarks' => 'Mahasiswa melaporkan bahwa pengajuan diterima oleh instansi dan kegiatan PKL telah dimulai.',
        ]);

        $this->session->set_flashdata('success', 'Status penerimaan oleh instansi berhasil dilaporkan. Status PKL Anda telah diperbarui menjadi \'Sedang Berlangsung\'.');
    }

    /**
     * Process rejected decision
     */
    private function process_rejected_decision($application_id)
    {
        $user_id = $this->session->userdata('id');

        $this->app->update_status($application_id, 'rejected_instansi');
        $this->app->insert_workflow([
            'application_id' => $application_id,
            'step_name' => 'Penolakan Instansi',
            'actor_id' => $user_id,
            'role' => 'mahasiswa',
            'status' => 'rejected',
            'remarks' => 'Mahasiswa melaporkan bahwa pengajuan ditolak oleh instansi.',
        ]);

        $this->session->set_flashdata('success', 'Status penolakan oleh instansi berhasil dilaporkan.');
    }
}