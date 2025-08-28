<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        if (!$this->check_permission('dashboard', 'read')) {
            echo 'Access Denied!!!';
            $this->output->set_status_header(403);
            $this->output->set_output('Access Denied');
            exit;
        }
    }

    public function index(): void
    {
        $this->data['title'] = 'Dashboard';
        $this->render('dashboard');
    }
}
