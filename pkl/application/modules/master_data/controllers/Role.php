<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Role extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Role_model');
    }

    public function index(): void
    {
        $this->data['title'] = 'Peran Pengguna';
        $this->data['page'] = 'role';
        $this->render('role');
    }

    public function create(): void
    {
        if (!$this->input->is_ajax_request()) {
            show_error('Access Denied', 403);
        }

        $data['modules'] = $this->Role_model->get_all_modules();
        $data['title'] = 'Peran Pengguna';

        $content = $this->load->view('role_create', $data, true);

        $this->output->set_content_type('application/json')->set_output(json_encode($content));
    }

    public function store(): void
    {
        if (!$this->input->is_ajax_request()) {
            show_error('Access Denied', 403);
        }

        $result = $this->Role_model->create() ? 'success' : 'error';
        $this->output->set_content_type('application/json')->set_output(json_encode(['status' => $result]));
    }

    public function show(): void
    {
        if (!$this->input->is_ajax_request()) {
            show_error('Access Denied', 403);
        }

        $id = $this->input->post('id', true);
        $data = $this->Role_model->get_data_detail($id);
        $data['modules'] = $this->Role_model->get_all_modules();
        $data['permissions'] = $this->Role_model->get_permission_by_role($id);
        $data['title'] = 'Peran Pengguna';

        $content = $this->load->view('role_show', $data, true);
        $this->output->set_content_type('application/json')->set_output(json_encode($content));
    }

    public function edit(): void
    {
        if (!$this->input->is_ajax_request()) {
            show_error('Access Denied', 403);
        }

        $id = $this->input->post('id', true);
        $data = $this->Role_model->get_data_detail($id);
        $data['modules'] = $this->Role_model->get_all_modules();
        $data['permissions'] = $this->Role_model->get_permission_by_role($id);
        $data['title'] = 'Peran Pengguna';

        $content = $this->load->view('role_edit', $data, true);
        $this->output->set_content_type('application/json')->set_output(json_encode($content));
    }

    public function update(): void
    {
        if (!$this->input->is_ajax_request()) {
            show_error('Access Denied', 403);
        }

        $result = $this->Role_model->update() ? 'success' : 'error';
        $this->output->set_content_type('application/json')->set_output(json_encode(['status' => $result]));
    }

    public function delete(): void
    {
        if (!$this->input->is_ajax_request()) {
            show_error('Access Denied', 403);
        }

        $result = $this->Role_model->delete() ? 'success' : 'error';
        $this->output->set_content_type('application/json')->set_output(json_encode(['status' => $result]));
    }

    public function get_data(): void
    {
        if (!$this->input->is_ajax_request()) {
            show_error('Access Denied', 403);
        }

        $data = $this->Role_model->get_data();
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
}
