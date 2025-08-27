<?php
defined('BASEPATH') or exit('No direct script access allowed');

class {{module_name}} extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('{{module_name}}_model');
    }

    public function index(): void
    {
        $this->data['title'] = '{{module_name}}';
        $this->render('{{module_view}}');
    }

    public function create(): void
    {
        $this->data['title'] = 'Create {{module_name}}';
        $this->render('{{module_view}}_create');
    }

    public function store(): void
    {
        $input = $this->input->post();
        $this->{{module_name}}_model->create($input);
        redirect('{{module_name}}');
    }

    public function show($id): void
    {
        $this->data['title'] = 'View {{module_name}}';
        $this->data['item'] = $this->{{module_name}}_model->read($id);
        $this->render('{{module_view}}_show');
    }

    public function edit($id): void
    {
        $this->data['title'] = 'Edit {{module_name}}';
        $this->data['item'] = $this->{{module_name}}_model->read($id);
        $this->render('{{module_view}}_edit');
    }

    public function update($id): void
    {
        $input = $this->input->post();
        $this->{{module_name}}_model->update($id, $input);
        redirect('{{module_name}}');
    }

    public function delete($id): void
    {
        $this->{{module_name}}_model->delete($id);
        redirect('{{module_name}}');
    }
}