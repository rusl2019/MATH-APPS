<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth_model extends CI_Model
{
    private $_allowed_tables;

    public function __construct()
    {
        parent::__construct();
        $this->_allowed_tables = ['apps_lecturers', 'apps_staffs', 'apps_students'];
    }

    public function set_user_session(array $user_attributes): void
    {
        $user_type = $this->session->userdata('user_type');
        $user_email = $user_attributes['email'][0];
        $user_data = $this->db->get_where($user_type, ['email' => $user_email])->row();
        $user_roles = $this->db->select('role_id')->get_where('apps_user_roles', ['user_id' => $user_data->id])->result_array();
        $roles = [];
        $role_names = [];
        foreach ($user_roles as $role) {
            $roles[] = $role['role_id'];
            $role_names[] = $this->db->get_where('apps_roles', ['id' => $role['role_id']])->row()->name;
        }
        $user_data = [
            'is_logged_in' => TRUE,
            'is_admin' => in_array('1', $roles),
            'id' => $user_data->id,
            'name' => $user_data->name,
            'email' => $user_data->email,
            'roles' => $roles,
            'role_names' => $role_names,
            'is_head_study_program' => in_array('5', $roles),
            'is_head_department' => in_array('6', $roles),
        ];
        $this->session->set_userdata($user_data);
    }

    public function verify_user(array $user_attributes): bool
    {
        if (!isset($user_attributes['email'][0])) {
            return FALSE;
        }

        $user_email = trim($user_attributes['email'][0]);

        foreach ($this->_allowed_tables as $table_name) {
            $is_user_exists = $this->_check_user_in_table($table_name, $user_email);

            if ($is_user_exists) {
                $this->session->set_userdata(['user_type' => $table_name]);
                return TRUE;
            }
        }

        return FALSE;
    }

    private function _check_user_in_table(string $table_name, string $email): bool
    {
        return (bool) $this->db->get_where($table_name, ['email' => $email])->row();
    }
}
