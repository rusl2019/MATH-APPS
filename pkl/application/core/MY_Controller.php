<?php
(defined('BASEPATH')) or exit('No direct script access allowed');

/* load the MX_Controller class */
require APPPATH . 'third_party/MX/Controller.php';

class MY_Controller extends MX_Controller
{
    protected array $data;

    public function __construct()
    {
        parent::__construct();

        // Middleware: Check if user is authenticated
        if (!$this->session->userdata('is_logged_in')) {
            redirect('auth');
        }
    }

    protected function render(string $view): void
    {
        $roles = $this->session->userdata('roles');

        $this->data['role_access'] = $this->get_role_access($roles);
        $this->data['contents'] = $this->load->view($view, $this->data, TRUE);
        $this->load->view('template', $this->data);
    }

    protected function check_permission(string $module_name, string $action): bool
    {
        $this->load->model('Permission/Permission_model');

        $roles = $this->session->userdata('roles');
        $permission_data = $this->Permission_model->get_permission_by_module($roles, $module_name, $action);
        return in_array(true, $permission_data);
    }

    private function get_role_access(array $roles): array
    {
        $this->load->model('Permission/Permission_model');

        $role_access = [];
        foreach ($roles as $role) {
            $role_access[] = $this->Permission_model->get_all_permission_by_role($role);
        }
        $result = [];
        foreach ($role_access[0] as $key => $value) {
            $merged = [
                'create' => false,
                'read' => false,
                'update' => false,
                'delete' => false,
            ];
            foreach ($role_access as $array) {
                if (isset($array[$key])) {
                    $merged['create'] = $merged['create'] || $array[$key]['create'];
                    $merged['read'] = $merged['read'] || $array[$key]['read'];
                    $merged['update'] = $merged['update'] || $array[$key]['update'];
                    $merged['delete'] = $merged['delete'] || $array[$key]['delete'];
                }
            }
            $result[$key] = $merged;
        }

        return $result;
    }
}
