<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Seminar extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Applications_model', 'app');
        $this->load->model('Seminar_model', 'seminar');
        $this->load->library('form_validation');
    }

    /**
     * Seminar main page for student
     */
    public function index($application_id)
    {
        $student_id = $this->session->userdata('id');
        $application = $this->app->get_application_by_id($application_id);

        if (!$application || $application->student_id != $student_id) {
            $this->session->set_flashdata('error', 'Aplikasi tidak ditemukan.');
            redirect('internship/applications');
            return;
        }

        $allowed_statuses = ['field_work_completed', 'seminar_requested', 'report_rejected', 'seminar_approved', 'seminar_scheduled', 'seminar_completed', 'revision', 'revision_submitted', 'revision_approved', 'finished'];
        if (!in_array($application->status, $allowed_statuses)) {
            $this->session->set_flashdata('error', 'Aplikasi Anda belum atau tidak dalam tahap seminar.');
            redirect('internship/applications');
            return;
        }

        if ($application->status === 'revision') {
            $this->data['revision_notes'] = $this->seminar->get_revision_notes($application_id);
        }

        $this->data['title'] = 'Proses Seminar & Laporan PKL';
        $this->data['application'] = $application;
        $this->data['seminar_docs'] = $this->seminar->get_seminar_documents($application_id);
        $this->data['workflow'] = $this->app->get_workflow_by_application($application_id);
        $this->data['assessments'] = $this->app->get_assessments_by_application($application_id);
        $this->data['documents'] = $this->app->get_documents_by_application($application_id);
        $this->data['lembar_konsultasi'] = $this->seminar->get_lembar_konsultasi($application_id);
        $this->data['recap_scores'] = $this->_calculate_final_score($application_id);
        $this->render('seminar_index');
    }

    /**
     * Student uploads PKL report to request seminar
     */
    public function upload_report($application_id)
    {
        $student_id = $this->session->userdata('id');
        $application = $this->app->get_application_by_id($application_id);

        // Security and status check
        if (!$application || $application->student_id != $student_id || !in_array($application->status, ['field_work_completed', 'report_rejected'])) {
            $this->session->set_flashdata('error', 'Aksi tidak diizinkan.');
            redirect('internship/seminar/index/' . $application_id);
            return;
        }

        $config = [
            'upload_path' => './uploads/pkl/',
            'allowed_types' => 'pdf',
            'max_size' => 5120, // 5MB
            'file_name' => 'laporan_pkl_draft_' . $application_id . '_' . time(),
        ];

        $this->load->library('upload', $config);

        if ($this->upload->do_upload('report_file')) {
            $file = $this->upload->data();

            // Insert document
            $this->app->insert_document([
                'application_id' => $application_id,
                'doc_type' => 'laporan_pkl_draft',
                'file_path' => 'uploads/pkl/' . $file['file_name'],
                'status' => 'submitted',
            ]);

            // Update application status
            $this->app->update_status($application_id, 'seminar_requested');

            // Log workflow
            $this->app->insert_workflow([
                'application_id' => $application_id,
                'step_name' => 'Pengajuan Seminar',
                'actor_id' => $student_id,
                'role' => 'mahasiswa',
                'status' => 'submitted',
                'remarks' => 'Mahasiswa mengunggah draft laporan dan mengajukan seminar.',
            ]);

            $this->session->set_flashdata('success', 'Draft laporan berhasil diunggah. Menunggu persetujuan dosen pembimbing untuk seminar.');
        } else {
            $this->session->set_flashdata('error', 'Gagal mengunggah laporan: ' . $this->upload->display_errors());
        }

        redirect('internship/seminar/index/' . $application_id);
    }

    /**
     * Main page for lecturer to manage seminar (schedule, assess)
     */
    public function manage($application_id)
    {
        $user_id = $this->session->userdata('id');
        $roles = $this->session->userdata('role_names');

        if (!in_array('lecturer', $roles) && !in_array('admin', $roles)) {
            show_error('Tidak diizinkan');
            return;
        }

        $application = $this->app->get_application_by_id($application_id);

        // Security check
        if (!in_array('admin', $roles) && $application->lecturer_id != $user_id) {
            show_error('Anda bukan pembimbing untuk aplikasi ini.');
            return;
        }

        $this->data['title'] = 'Kelola Seminar PKL';
        $this->data['application'] = $application;
        $this->data['student'] = $this->app->get_student($application->student_id);
        $this->data['documents'] = $this->app->get_documents_by_application($application_id);
        $this->data['workflow'] = $this->app->get_workflow_by_application($application_id);
        $this->data['daily_logs'] = $this->app->get_logs_by_application($application_id);
        $this->data['weekly_logs'] = $this->app->get_weekly_logbooks($application_id);
        $this->data['lembar_konsultasi'] = $this->seminar->get_lembar_konsultasi($application_id);

        // NEW: Get guidance assessment scores
        $all_assessments = $this->app->get_assessments_by_application($application_id);
        $guidance_scores = [];
        $guidance_criteria = [
            'bimbingan_proses', 'bimbingan_disiplin', 'bimbingan_topik',
            'bimbingan_relevansi', 'bimbingan_pembahasan', 'bimbingan_tata_tulis'
        ];
        foreach ($all_assessments as $assessment) {
            if (in_array($assessment->form_type, $guidance_criteria)) {
                $guidance_scores[$assessment->form_type] = $assessment->score;
            }
        }
        $this->data['guidance_scores'] = $guidance_scores;
        $this->data['recap_scores'] = $this->_calculate_final_score($application_id);

        $this->render('seminar_manage');
    }

    /**
     * Save seminar schedule
     */
    public function save_schedule($application_id)
    {
        $user_id = $this->session->userdata('id');
        $roles = $this->session->userdata('role_names');

        if (!in_array('lecturer', $roles) && !in_array('admin', $roles)) {
            show_error('Tidak diizinkan');
            return;
        }

        $application = $this->app->get_application_by_id($application_id);

        // Security check
        if (!in_array('admin', $roles) && $application->lecturer_id != $user_id) {
            show_error('Anda bukan pembimbing untuk aplikasi ini.');
            return;
        }

        $this->form_validation->set_rules('seminar_date', 'Tanggal Seminar', 'required');
        $this->form_validation->set_rules('seminar_location', 'Lokasi Seminar', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('internship/seminar/manage/' . $application_id);
            return;
        }

        $data = [
            'seminar_date' => $this->input->post('seminar_date'),
            'seminar_location' => $this->input->post('seminar_location'),
            'status' => 'seminar_scheduled'
        ];

        $this->app->update_application($application_id, $data);

        $this->app->insert_workflow([
            'application_id' => $application_id,
            'step_name' => 'Penjadwalan Seminar',
            'actor_id' => $this->session->userdata('id'),
            'role' => 'dosen',
            'status' => 'scheduled',
            'remarks' => 'Seminar dijadwalkan pada ' . $data['seminar_date'] . ' di ' . $data['seminar_location'],
        ]);

        $this->session->set_flashdata('success', 'Jadwal seminar berhasil disimpan.');
        redirect('internship/seminar/manage/' . $application_id);
    }

    /**
     * Save guidance assessment from lecturer
     */
    public function save_guidance_assessment($application_id)
    {
        $user_id = $this->session->userdata('id');
        $roles = $this->session->userdata('role_names');
        if (!in_array('lecturer', $roles) && !in_array('admin', $roles)) {
            show_error('Tidak diizinkan');
            return;
        }

        $application = $this->app->get_application_by_id($application_id);
        if (!in_array('admin', $roles) && $application->lecturer_id != $user_id) {
            show_error('Anda bukan pembimbing untuk aplikasi ini.');
            return;
        }

        $criteria = [
            'bimbingan_proses', 'bimbingan_disiplin', 'bimbingan_topik',
            'bimbingan_relevansi', 'bimbingan_pembahasan', 'bimbingan_tata_tulis'
        ];

        foreach ($criteria as $criterion) {
            $this->form_validation->set_rules($criterion, ucfirst(str_replace('_', ' ', $criterion)), 'required|numeric|less_than_equal_to[100]|greater_than_equal_to[0]');
        }

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('internship/seminar/manage/' . $application_id);
            return;
        }

        // Delete existing scores to prevent duplicates, making the action idempotent
        $this->seminar->delete_guidance_assessment($application_id);

        $assessments = [];
        foreach ($criteria as $criterion) {
            $assessments[] = [
                'application_id' => $application_id,
                'assessor_type' => 'dosen_pembimbing',
                'form_type' => $criterion,
                'score' => $this->input->post($criterion),
                'remarks' => 'Penilaian Proses Pembimbingan (Form B-5)',
            ];
        }

        if ($this->app->insert_assessments($assessments)) {
            $this->session->set_flashdata('success', 'Penilaian proses pembimbingan berhasil disimpan.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menyimpan penilaian.');
        }

        redirect('internship/seminar/manage/' . $application_id);
    }

    /**
     * Save seminar assessment from lecturer
     */
    public function save_assessment($application_id)
    {
        $user_id = $this->session->userdata('id');
        $roles = $this->session->userdata('role_names');

        if (!in_array('lecturer', $roles) && !in_array('admin', $roles)) {
            show_error('Tidak diizinkan');
            return;
        }

        $application = $this->app->get_application_by_id($application_id);

        // Security check
        if (!in_array('admin', $roles) && $application->lecturer_id != $user_id) {
            show_error('Anda bukan pembimbing untuk aplikasi ini.');
            return;
        }

        // New criteria validation
        $this->form_validation->set_rules('kualitas_presentasi', 'Kualitas Presentasi', 'required|numeric|less_than_equal_to[100]|greater_than_equal_to[0]');
        $this->form_validation->set_rules('kualitas_diskusi', 'Kualitas Diskusi', 'required|numeric|less_than_equal_to[100]|greater_than_equal_to[0]');
        $this->form_validation->set_rules('penguasaan_materi', 'Penguasaan Materi', 'required|numeric|less_than_equal_to[100]|greater_than_equal_to[0]');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('internship/seminar/manage/' . $application_id);
            return;
        }

        // 2. Insert scores
        $assessments = [
            [
                'application_id' => $application_id,
                'assessor_type' => 'dosen_pembimbing',
                'form_type' => 'seminar_kualitas_presentasi',
                'score' => $this->input->post('kualitas_presentasi'),
            ],
            [
                'application_id' => $application_id,
                'assessor_type' => 'dosen_pembimbing',
                'form_type' => 'seminar_kualitas_diskusi',
                'score' => $this->input->post('kualitas_diskusi'),
            ],
            [
                'application_id' => $application_id,
                'assessor_type' => 'dosen_pembimbing',
                'form_type' => 'seminar_penguasaan_materi',
                'score' => $this->input->post('penguasaan_materi'),
            ]
        ];
        $this->app->insert_assessments($assessments);

        // 3. Update status
        $this->app->update_status($application_id, 'seminar_completed');

        // 4. Log workflow
        $this->app->insert_workflow([
            'application_id' => $application_id,
            'step_name' => 'Penilaian Seminar',
            'actor_id' => $this->session->userdata('id'),
            'role' => 'dosen',
            'status' => 'done',
            'remarks' => 'Dosen telah memberikan penilaian seminar.',
        ]);

        $this->session->set_flashdata('success', 'Penilaian seminar berhasil disimpan.');
        redirect('internship/seminar/manage/' . $application_id);
    }

    /**
     * Private file upload helper
     */
    private function _do_upload($field_name, $file_name_prefix)
    {
        $config = [
            'upload_path' => './uploads/pkl/',
            'allowed_types' => 'pdf',
            'max_size' => 2048,
            'file_name' => $file_name_prefix . '_' . time(),
        ];

        $this->load->library('upload', $config);
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
     * Start revision process or approve directly
     */
    public function start_revision($application_id)
    {
        $user_id = $this->session->userdata('id');
        $roles = $this->session->userdata('role_names');

        if (!in_array('lecturer', $roles) && !in_array('admin', $roles)) {
            show_error('Tidak diizinkan');
            return;
        }

        $application = $this->app->get_application_by_id($application_id);

        // Security check
        if (!in_array('admin', $roles) && $application->lecturer_id != $user_id) {
            show_error('Anda bukan pembimbing untuk aplikasi ini.');
            return;
        }

        $action = $this->input->post('action');
        $remarks = $this->input->post('remarks');

        if ($action === 'start_revision') {
            $this->app->update_status($application_id, 'revision');
            $this->app->insert_workflow([
                'application_id' => $application_id,
                'step_name' => 'Mulai Revisi',
                'actor_id' => $user_id,
                'role' => 'dosen',
                'status' => 'revision_started',
                'remarks' => 'Dosen memulai tahap revisi dengan catatan: ' . ($remarks ?: 'Tidak ada catatan khusus.'),
            ]);
            $this->session->set_flashdata('success', 'Tahap revisi telah dimulai.');
        } elseif ($action === 'no_revision') {
            $this->app->update_status($application_id, 'finished');
            $this->app->insert_workflow([
                'application_id' => $application_id,
                'step_name' => 'Penyelesaian PKL',
                'actor_id' => $user_id,
                'role' => 'dosen',
                'status' => 'completed',
                'remarks' => 'Laporan disetujui tanpa revisi. PKL Selesai.',
            ]);
            $this->session->set_flashdata('success', 'Laporan akhir disetujui tanpa revisi. Proses PKL telah selesai.');
        }

        redirect('internship/seminar/manage/' . $application_id);
    }

    public function upload_revision($application_id)
    {
        $student_id = $this->session->userdata('id');
        $application = $this->app->get_application_by_id($application_id);

        // Security check
        if (!$application || $application->student_id != $student_id) {
            show_error('Anda tidak diizinkan untuk melakukan aksi ini.', 403);
            return;
        }

        $file_path = $this->_do_upload('revision_file', 'laporan_revisi_' . $application_id);
        if ($file_path) {
            $this->app->insert_document([
                'application_id' => $application_id,
                'doc_type' => 'laporan_pkl_revised',
                'file_path' => $file_path,
            ]);

            $this->app->update_status($application_id, 'revision_submitted');

            $this->app->insert_workflow([
                'application_id' => $application_id,
                'step_name' => 'Pengumpulan Revisi',
                'actor_id' => $student_id,
                'role' => 'mahasiswa',
                'status' => 'submitted',
                'remarks' => 'Mahasiswa mengunggah laporan hasil revisi.',
            ]);

            $this->session->set_flashdata('success', 'Laporan revisi berhasil diunggah. Menunggu persetujuan akhir dari dosen.');
        }
        redirect('internship/seminar/index/' . $application_id);
    }

    public function approve_final_report($application_id)
    {
        $user_id = $this->session->userdata('id');
        $roles = $this->session->userdata('role_names');

        if (!in_array('lecturer', $roles) && !in_array('admin', $roles)) {
            show_error('Tidak diizinkan');
            return;
        }

        $application = $this->app->get_application_by_id($application_id);

        // Security check
        if (!in_array('admin', $roles) && $application->lecturer_id != $user_id) {
            show_error('Anda bukan pembimbing untuk aplikasi ini.');
            return;
        }

        $this->app->update_status($application_id, 'finished');
        $this->app->insert_workflow([
            'application_id' => $application_id,
            'step_name' => 'Penyelesaian PKL',
            'actor_id' => $user_id,
            'role' => 'dosen',
            'status' => 'completed',
            'remarks' => 'Dosen menyetujui laporan akhir hasil revisi. PKL Selesai.',
        ]);

        $this->session->set_flashdata('success', 'Laporan akhir telah disetujui. Proses PKL mahasiswa telah selesai.');
        redirect('internship/seminar/manage/' . $application_id);
    }

    /**
     * Lecturer approves a report for seminar with lembar konsultasi validation
     */
    public function approve($application_id)
    {
        $user_id = $this->session->userdata('id');
        $roles = $this->session->userdata('role_names');

        if (!in_array('lecturer', $roles)) {
            show_error('Tidak diizinkan');
            return;
        }

        // Additional check: make sure this lecturer is the assigned one
        $application = $this->app->get_application_by_id($application_id);
        if ($application->lecturer_id != $user_id) {
            show_error('Tidak diizinkan');
            return;
        }

        // Check if there are any lembar konsultasi entries
        $lembar_konsultasi = $this->seminar->get_lembar_konsultasi($application_id);
        if (empty($lembar_konsultasi)) {
            $this->session->set_flashdata('error', 'Tidak dapat menyetujui seminar. Minimal harus ada satu lembar konsultasi.');
            redirect('internship/seminar/manage/' . $application_id);
            return;
        }

        // Update status
        $this->app->update_status($application_id, 'seminar_approved');

        // Log workflow
        $this->app->insert_workflow([
            'application_id' => $application_id,
            'step_name' => 'Persetujuan Seminar',
            'actor_id' => $user_id,
            'role' => 'dosen',
            'status' => 'approved',
            'remarks' => 'Dosen menyetujui laporan untuk seminar.',
        ]);

        $this->session->set_flashdata('success', 'Laporan PKL disetujui untuk seminar.');
        redirect('internship/seminar/manage/' . $application_id);
    }

    /**
     * Lecturer rejects a report
     */
    public function reject_report($application_id)
    {
        $user_id = $this->session->userdata('id');
        $roles = $this->session->userdata('role_names');
        $remarks = $this->input->post('remarks') ?? 'Ditolak';

        if (!in_array('lecturer', $roles)) {
            show_error('Tidak diizinkan');
            return;
        }

        // Additional check: make sure this lecturer is the assigned one
        $application = $this->app->get_application_by_id($application_id);
        if ($application->lecturer_id != $user_id) {
            show_error('Tidak diizinkan');
            return;
        }

        // Update status
        $this->app->update_status($application_id, 'report_rejected');

        // Log workflow
        $this->app->insert_workflow([
            'application_id' => $application_id,
            'step_name' => 'Persetujuan Seminar',
            'actor_id' => $user_id,
            'role' => 'dosen',
            'status' => 'rejected',
            'remarks' => $remarks,
        ]);

        $this->session->set_flashdata('error', 'Laporan PKL ditolak.');
        redirect('internship/seminar/manage/' . $application_id);
    }

    /**
     * Add a new lembar konsultasi entry
     */
    public function add_lembar_konsultasi($application_id)
    {
        $user_id = $this->session->userdata('id');
        $roles = $this->session->userdata('role_names');

        // Check if user is lecturer or student
        $is_lecturer = in_array('lecturer', $roles) || in_array('admin', $roles);
        $is_student = !$is_lecturer;

        $application = $this->app->get_application_by_id($application_id);

        // Security check
        if ($is_student && $application->student_id != $user_id) {
            show_error('Anda tidak diizinkan untuk melakukan aksi ini.', 403);
            return;
        }

        if ($is_lecturer && !in_array('admin', $roles) && $application->lecturer_id != $user_id) {
            show_error('Anda tidak diizinkan untuk melakukan aksi ini.', 403);
            return;
        }

        // Set validation rules
        $this->form_validation->set_rules('date', 'Tanggal', 'required');
        $this->form_validation->set_rules('material', 'Materi', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
        } else {
            $data = [
                'application_id' => $application_id,
                'date' => $this->input->post('date'),
                'material' => $this->input->post('material'),
                'notes' => $this->input->post('notes')
            ];

            if ($this->seminar->insert_lembar_konsultasi($data)) {
                $this->session->set_flashdata('success', 'Lembar konsultasi berhasil ditambahkan.');
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan lembar konsultasi.');
            }
        }

        // Redirect based on user role
        if ($is_lecturer) {
            redirect('internship/seminar/manage/' . $application_id);
        } else {
            redirect('internship/seminar/index/' . $application_id);
        }
    }

    /**
     * Edit a lembar konsultasi entry
     */
    public function edit_lembar_konsultasi($id)
    {
        $user_id = $this->session->userdata('id');
        $roles = $this->session->userdata('role_names');

        // Check if user is lecturer or student
        $is_lecturer = in_array('lecturer', $roles) || in_array('admin', $roles);

        $lembar_konsultasi = $this->seminar->get_lembar_konsultasi_by_id($id);
        if (!$lembar_konsultasi) {
            show_error('Lembar konsultasi tidak ditemukan.');
            return;
        }

        $application = $this->app->get_application_by_id($lembar_konsultasi->application_id);

        // Security check
        if (!$is_lecturer && $application->student_id != $user_id) {
            show_error('Anda tidak diizinkan untuk melakukan aksi ini.', 403);
            return;
        }

        if ($is_lecturer && !in_array('admin', $roles) && $application->lecturer_id != $user_id) {
            show_error('Anda tidak diizinkan untuk melakukan aksi ini.', 403);
            return;
        }

        // Set validation rules
        $this->form_validation->set_rules('date', 'Tanggal', 'required');
        $this->form_validation->set_rules('material', 'Materi', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
        } else {
            $data = [
                'date' => $this->input->post('date'),
                'material' => $this->input->post('material'),
                'notes' => $this->input->post('notes')
            ];

            if ($this->seminar->update_lembar_konsultasi($id, $data)) {
                $this->session->set_flashdata('success', 'Lembar konsultasi berhasil diperbarui.');
            } else {
                $this->session->set_flashdata('error', 'Gagal memperbarui lembar konsultasi.');
            }
        }

        // Redirect based on user role
        if ($is_lecturer) {
            redirect('internship/seminar/manage/' . $lembar_konsultasi->application_id);
        } else {
            redirect('internship/seminar/index/' . $lembar_konsultasi->application_id);
        }
    }

    /**
     * Delete a lembar konsultasi entry
     */
    public function delete_lembar_konsultasi($id)
    {
        $user_id = $this->session->userdata('id');
        $roles = $this->session->userdata('role_names');

        // Check if user is lecturer or student
        $is_lecturer = in_array('lecturer', $roles) || in_array('admin', $roles);

        $lembar_konsultasi = $this->seminar->get_lembar_konsultasi_by_id($id);
        if (!$lembar_konsultasi) {
            show_error('Lembar konsultasi tidak ditemukan.');
            return;
        }

        $application = $this->app->get_application_by_id($lembar_konsultasi->application_id);

        // Security check
        if (!$is_lecturer && $application->student_id != $user_id) {
            show_error('Anda tidak diizinkan untuk melakukan aksi ini.', 403);
            return;
        }

        if ($is_lecturer && !in_array('admin', $roles) && $application->lecturer_id != $user_id) {
            show_error('Anda tidak diizinkan untuk melakukan aksi ini.', 403);
            return;
        }

        if ($this->seminar->delete_lembar_konsultasi($id)) {
            $this->session->set_flashdata('success', 'Lembar konsultasi berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus lembar konsultasi.');
        }

        // Redirect based on user role
        if ($is_lecturer) {
            redirect('internship/seminar/manage/' . $lembar_konsultasi->application_id);
        } else {
            redirect('internship/seminar/index/' . $lembar_konsultasi->application_id);
        }
    }

    /**
     * Calculate final score based on all assessments.
     * @return array
     */
    private function _calculate_final_score($application_id)
    {
        $all_assessments = $this->app->get_assessments_by_application($application_id);

        $lapangan_scores = [];
        $bimbingan_scores = [];
        $seminar_scores = [];

        $bimbingan_criteria = ['bimbingan_proses', 'bimbingan_disiplin', 'bimbingan_topik', 'bimbingan_relevansi', 'bimbingan_pembahasan', 'bimbingan_tata_tulis'];
        $seminar_criteria = ['seminar_kualitas_presentasi', 'seminar_kualitas_diskusi', 'seminar_penguasaan_materi'];

        foreach ($all_assessments as $assessment) {
            if ($assessment->assessor_type === 'lapangan') {
                $lapangan_scores[] = $assessment->score;
            } elseif (in_array($assessment->form_type, $bimbingan_criteria)) {
                $bimbingan_scores[] = $assessment->score;
            } elseif (in_array($assessment->form_type, $seminar_criteria)) {
                $seminar_scores[] = $assessment->score;
            }
        }

        $avg_lapangan = !empty($lapangan_scores) ? array_sum($lapangan_scores) / count($lapangan_scores) : 0;
        $avg_bimbingan = !empty($bimbingan_scores) ? array_sum($bimbingan_scores) / count($bimbingan_scores) : 0;
        $avg_seminar = !empty($seminar_scores) ? array_sum($seminar_scores) / count($seminar_scores) : 0;

        $is_complete = ($avg_lapangan > 0 && $avg_bimbingan > 0 && $avg_seminar > 0);

        $final_score = ($avg_lapangan * 0.25) + ($avg_bimbingan * 0.25) + ($avg_seminar * 0.50);

        return [
            'avg_lapangan' => $avg_lapangan,
            'avg_bimbingan' => $avg_bimbingan,
            'avg_seminar' => $avg_seminar,
            'final_score' => $final_score,
            'is_complete' => $is_complete
        ];
    }
}
