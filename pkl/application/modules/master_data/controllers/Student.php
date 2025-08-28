<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Student extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        if (!$this->check_permission('master_data', 'read')) {
            echo 'Access Denied!!!';
            $this->output->set_status_header(403);
            $this->output->set_output('Access Denied');
            exit;
        }

        $this->load->model('Student_model');
    }

    public function index(): void
    {
        $this->data['title'] = 'Mahasiswa';
        $this->data['page'] = 'student';
        $this->render('student');
    }

    public function create(): void
    {
        if (!$this->input->is_ajax_request()) {
            show_error('Access Denied', 403);
        }

        if (!$this->check_permission('master_data', 'create')) {
            $this->output->set_content_type('application/json');
            $this->output->set_output(json_encode(['status' => 'access_denied']));
            return;
        }

        $data['study_programs'] = $this->Student_model->get_all_study_programs();
        $data['roles'] = $this->Student_model->get_all_roles();
        $data['title'] = 'Mahasiswa';

        $content = $this->load->view('student_create', $data, true);

        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($content));
    }

    public function store(): void
    {
        if (!$this->input->is_ajax_request()) {
            show_error('Access Denied', 403);
        }

        if (!$this->check_permission('master_data', 'create')) {
            $this->output->set_content_type('application/json');
            $this->output->set_output(json_encode(['status' => 'access_denied']));
            return;
        }

        $result = $this->Student_model->create() && $this->Student_model->create_roles() ? 'success' : 'error';

        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode(['status' => $result]));
    }

    public function show(): void
    {
        if (!$this->input->is_ajax_request()) {
            show_error('Access Denied', 403);
        }

        $id = $this->input->post('id', true);

        $data = $this->Student_model->get_data_detail($id);
        $data['roles'] = $this->Student_model->get_all_roles();
        $data['title'] = 'Mahasiswa';

        $content = $this->load->view('student_show', $data, true);

        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($content));
    }

    public function edit(): void
    {
        if (!$this->input->is_ajax_request()) {
            show_error('Access Denied', 403);
        }

        if (!$this->check_permission('master_data', 'update')) {
            $this->output->set_content_type('application/json');
            $this->output->set_output(json_encode(['status' => 'access_denied']));
            return;
        }

        $id = $this->input->post('id', true);

        $data = $this->Student_model->get_data_detail($id);
        $data['roles'] = $this->Student_model->get_all_roles();
        $data['study_programs'] = $this->Student_model->get_all_study_programs();
        $data['title'] = 'Mahasiswa';

        $content = $this->load->view('student_edit', $data, true);

        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($content));
    }

    public function update(): void
    {
        if (!$this->input->is_ajax_request()) {
            show_error('Access Denied', 403);
        }

        if (!$this->check_permission('master_data', 'update')) {
            $this->output->set_content_type('application/json');
            $this->output->set_output(json_encode(['status' => 'access_denied']));
            return;
        }

        $result = $this->Student_model->update() && $this->Student_model->create_roles() ? 'success' : 'error';

        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode(['status' => $result]));
    }

    public function delete(): void
    {
        if (!$this->input->is_ajax_request()) {
            show_error('Access Denied', 403);
        }

        if (!$this->check_permission('master_data', 'delete')) {
            $this->output->set_content_type('application/json');
            $this->output->set_output(json_encode(['status' => 'access_denied']));
            return;
        }

        $result = $this->Student_model->delete() ? 'success' : 'error';

        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode(['status' => $result]));
    }

    public function get_data(): void
    {
        if (!$this->input->is_ajax_request()) {
            show_error('Access Denied', 403);
        }

        $data = $this->Student_model->get_data();

        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($data));
    }
}
