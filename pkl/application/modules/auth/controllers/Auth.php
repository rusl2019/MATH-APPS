<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends MX_Controller
{
    private $_auth_instance;

    public function __construct()
    {
        parent::__construct();
        require_once 'simplesaml/lib/_autoload.php';
        $this->_auth_instance = new \SimpleSAML\Auth\Simple('appsmatematika-pkl');
        $this->load->model('auth_model');
    }

    public function index(): void
    {
        if ($this->session->has_userdata('is_logged_in')) {
            redirect('dashboard');
        }

        if (!$this->_auth_instance->isAuthenticated()) {
            $this->_auth_instance->requireAuth();
        }

        $user_attributes = $this->_auth_instance->getAttributes();

        if (!$this->auth_model->verify_user($user_attributes)) {
            show_error('User not registered in the system', 403);
        }

        $this->auth_model->set_user_session($user_attributes);

        redirect('dashboard');
    }

    public function logout(): void
    {
        $_SESSION = [];
        $this->_auth_instance->logout(base_url('auth'));
    }
}
