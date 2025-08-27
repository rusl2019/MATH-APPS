<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Switch_user extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('is_logged_in')) {
            redirect('auth');
        }
        if (!$this->session->userdata('is_admin')) {
            redirect('dashboard');
        }

        $this->load->model('Switch_user_model');
    }

    public function index(): void
    {
        $data['title'] = 'Switch User';
        $this->load->view('switch_user', $data);
    }

    public function change_user(): void
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('user_type', 'User Type', 'required');
        $this->form_validation->set_rules('user_id', 'User', 'required');

        if ($this->form_validation->run() === false) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('switch_user');
            return;
        }

        $user_type = $this->input->post('user_type', true);
        $user_id = $this->input->post('user_id', true);

        $session_data = $this->Switch_user_model->get_user_detail($user_type, $user_id);

        $this->session->set_userdata($session_data);
        redirect('dashboard');
    }

    public function get_users_by_type(): void
    {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        $search = $this->input->post('q', true);
        $user_type = $this->input->post('user_type', true);
        $page = $this->input->post('page', true);

        $users = $this->Switch_user_model->get_user($user_type, $search, $page);

        if (!isset($users['items'])) {
            $users['items'] = [];
        }

        echo json_encode($users);
    }
}
