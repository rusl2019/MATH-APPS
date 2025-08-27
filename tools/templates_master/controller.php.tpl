<?php
defined('BASEPATH') or exit('No direct script access allowed');

class {{entity_name_capital}} extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('{{entity_name_capital}}_model');
    }

    public function index(): void
    {
        $this->data['title'] = '{{entity_name_capital}}';
        $this->data['page'] = '{{entity_name_lower}}';
        $this->render('{{entity_name_lower}}');
    }

    public function create(): void
    {
        if (!$this->input->is_ajax_request()) {
            show_error('Access Denied', 403);
        }

        // Data tambahan jika diperlukan, contoh:
        // $data['study_programs'] = $this->{{entity_name_capital}}_model->get_all_study_programs();
        $data['title'] = '{{entity_name_capital}}';

        $content = $this->load->view('{{entity_name_lower}}_create', $data, true);

        $this->output->set_content_type('application/json')->set_output(json_encode($content));
    }

    public function store(): void
    {
        if (!$this->input->is_ajax_request()) {
            show_error('Access Denied', 403);
        }

        $result = $this->{{entity_name_capital}}_model->create() ? 'success' : 'error';
        $this->output->set_content_type('application/json')->set_output(json_encode(['status' => $result]));
    }

    public function show(): void
    {
        if (!$this->input->is_ajax_request()) {
            show_error('Access Denied', 403);
        }

        $id = $this->input->post('id', true);
        $data = $this->{{entity_name_capital}}_model->get_data_detail($id);
        $data['title'] = '{{entity_name_capital}}';

        $content = $this->load->view('{{entity_name_lower}}_show', $data, true);
        $this->output->set_content_type('application/json')->set_output(json_encode($content));
    }

    public function edit(): void
    {
        if (!$this->input->is_ajax_request()) {
            show_error('Access Denied', 403);
        }

        $id = $this->input->post('id', true);
        $data = $this->{{entity_name_capital}}_model->get_data_detail($id);
        // Data tambahan jika diperlukan, contoh:
        // $data['study_programs'] = $this->{{entity_name_capital}}_model->get_all_study_programs();
        $data['title'] = '{{entity_name_capital}}';

        $content = $this->load->view('{{entity_name_lower}}_edit', $data, true);
        $this->output->set_content_type('application/json')->set_output(json_encode($content));
    }

    public function update(): void
    {
        if (!$this->input->is_ajax_request()) {
            show_error('Access Denied', 403);
        }

        $result = $this->{{entity_name_capital}}_model->update() ? 'success' : 'error';
        $this->output->set_content_type('application/json')->set_output(json_encode(['status' => $result]));
    }

    public function delete(): void
    {
        if (!$this->input->is_ajax_request()) {
            show_error('Access Denied', 403);
        }

        $result = $this->{{entity_name_capital}}_model->delete() ? 'success' : 'error';
        $this->output->set_content_type('application/json')->set_output(json_encode(['status' => $result]));
    }

    public function get_data(): void
    {
        if (!$this->input->is_ajax_request()) {
            show_error('Access Denied', 403);
        }

        $data = $this->{{entity_name_capital}}_model->get_data();
        $this->output->set_content_type('application/json')->set_output(json_encode($data));
    }
}