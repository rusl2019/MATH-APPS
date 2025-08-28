<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Module extends MY_Controller
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

        $this->load->model('Module_model');
    }

    public function index(): void
    {
        $this->data['title'] = 'Modul';
        $this->data['page'] = 'module';
        $this->render('module');
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

        $data['title'] = 'Modul';

        $content = $this->load->view('module_create', $data, true);

        $this->output->set_content_type('application/json')->set_output(json_encode($content));
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

        $result = $this->Module_model->create() ? 'success' : 'error';
        $this->output->set_content_type('application/json')->set_output(json_encode(['status' => $result]));
    }

    public function show(): void
    {
        if (!$this->input->is_ajax_request()) {
            show_error('Access Denied', 403);
        }

        $id = $this->input->post('id', true);
        $data = $this->Module_model->get_data_detail($id);
        $data['title'] = 'Modul';

        $content = $this->load->view('module_show', $data, true);
        $this->output->set_content_type('application/json')->set_output(json_encode($content));
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
        $data = $this->Module_model->get_data_detail($id);
        $data['title'] = 'Modul';

        $content = $this->load->view('module_edit', $data, true);
        $this->output->set_content_type('application/json')->set_output(json_encode($content));
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

        $result = $this->Module_model->update() ? 'success' : 'error';
        $this->output->set_content_type('application/json')->set_output(json_encode(['status' => $result]));
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

        $result = $this->Module_model->delete() ? 'success' : 'error';
        $this->output->set_content_type('application/json')->set_output(json_encode(['status' => $result]));
    }

    public function get_data(): void
    {
        if (!$this->input->is_ajax_request()) {
            show_error('Access Denied', 403);
        }

        $data = $this->Module_model->get_data();
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
}
