<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Study_program extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Study_program_model');
    }

    public function index(): void
    {
        $this->data['title'] = 'Program Studi';
        $this->data['page'] = 'study_program';
        $this->render('study_program');
    }

    public function create(): void
    {
        if (!$this->input->is_ajax_request()) {
            show_error('Access Denied', 403);
        }

        $data['lecturers'] = $this->Study_program_model->get_all_lecturers();
        $data['title'] = 'Program Studi';

        $content = $this->load->view('study_program_create', $data, true);

        $this->output->set_content_type('application/json')->set_output(json_encode($content));
    }

    public function store(): void
    {
        if (!$this->input->is_ajax_request()) {
            show_error('Access Denied', 403);
        }

        $result = $this->Study_program_model->create() ? 'success' : 'error';
        $this->output->set_content_type('application/json')->set_output(json_encode(['status' => $result]));
    }

    public function show(): void
    {
        if (!$this->input->is_ajax_request()) {
            show_error('Access Denied', 403);
        }

        $id = $this->input->post('id', true);
        $data = $this->Study_program_model->get_data_detail($id);
        $data['title'] = 'Program Studi';

        $content = $this->load->view('study_program_show', $data, true);
        $this->output->set_content_type('application/json')->set_output(json_encode($content));
    }

    public function edit(): void
    {
        if (!$this->input->is_ajax_request()) {
            show_error('Access Denied', 403);
        }

        $id = $this->input->post('id', true);
        $data = $this->Study_program_model->get_data_detail($id);
        $data['lecturers'] = $this->Study_program_model->get_all_lecturers();
        $data['title'] = 'Program Studi';

        $content = $this->load->view('study_program_edit', $data, true);
        $this->output->set_content_type('application/json')->set_output(json_encode($content));
    }

    public function update(): void
    {
        if (!$this->input->is_ajax_request()) {
            show_error('Access Denied', 403);
        }

        $result = $this->Study_program_model->update() ? 'success' : 'error';
        $this->output->set_content_type('application/json')->set_output(json_encode(['status' => $result]));
    }

    public function delete(): void
    {
        if (!$this->input->is_ajax_request()) {
            show_error('Access Denied', 403);
        }

        $result = $this->Study_program_model->delete() ? 'success' : 'error';
        $this->output->set_content_type('application/json')->set_output(json_encode(['status' => $result]));
    }

    public function get_data(): void
    {
        if (!$this->input->is_ajax_request()) {
            show_error('Access Denied', 403);
        }

        $data = $this->Study_program_model->get_data();
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
}
