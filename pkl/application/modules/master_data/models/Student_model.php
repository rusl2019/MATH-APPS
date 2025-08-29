<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Student_model extends CI_Model
{
    private $table_name;

    public function __construct()
    {
        parent::__construct();
        $this->table_name = 'apps_students';
    }

    public function create(): bool
    {
        $data = [
            'id' => $this->input->post('id', true),
            'name' => $this->input->post('name', true),
            'email' => $this->input->post('email', true),
            'study_program_id' => $this->input->post('study_program_id', true),
        ];

        return $this->db->insert($this->table_name, $data);
    }

    public function read(int $id): array
    {
        $query = $this->db->get_where($this->table_name, ['id' => $id]);
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
        return [];
    }

    public function update(): bool
    {
        $id = $this->input->post('id', true);
        $data = [
            'name' => $this->input->post('name', true),
            'email' => $this->input->post('email', true),
            'study_program_id' => $this->input->post('study_program_id', true),
        ];

        return $this->db->update($this->table_name, $data, ['id' => $id]);
    }

    public function delete(): bool
    {
        $id = $this->input->post('id', true);

        if (!$this->delete_roles($id)) {
            return false;
        }

        return $this->db->update($this->table_name, ['deleted_at' => date('Y-m-d H:i:s')], ['id' => $id]);
    }

    public function create_roles(): bool
    {
        $id = $this->input->post('id', true);
        $roles = $this->input->post('roles', true);

        if (!$this->delete_roles($id)) {
            return false;
        }
        if ($roles !== null) {
            foreach ($roles as $role) {
                if (!$this->db->insert('apps_user_roles', ['user_id' => $id, 'role_id' => $role])) {
                    return false;
                }
            }
        }
        return true;
    }

    public function delete_roles(string $id): bool
    {
        return $this->db->delete('apps_user_roles', ['user_id' => $id]);
    }

    public function get_data(): array
    {
        $this->load->library('DataTables');

        $column = [
            "{$this->table_name}.id",
            "{$this->table_name}.name",
            "{$this->table_name}.email",
            'apps_study_programs.name as study_program',
        ];
        $filter = [];
        $where = [
            "{$this->table_name}.deleted_at" => null
        ];
        $joins = [
            ['apps_study_programs', "apps_study_programs.id = {$this->table_name}.study_program_id", 'left']
        ];
        $search = [
            "{$this->table_name}.id",
            "{$this->table_name}.name",
            "{$this->table_name}.email",
            'apps_study_programs.name',
        ];
        $order_columns = [
            "{$this->table_name}.id",
            "{$this->table_name}.name",
            "{$this->table_name}.email",
            'apps_study_programs.name',
        ];
        $or_where = [];

        return $this
            ->datatables
            ->get_data($this->table_name, $column, $filter, $where, $joins, $search, $order_columns, $or_where);
    }

    public function get_data_detail(int $id): array
    {
        $this->db->select("{$this->table_name}.*, apps_study_programs.name as study_program");
        $this->db->from($this->table_name);
        $this->db->join('apps_study_programs', "apps_study_programs.id = {$this->table_name}.study_program_id", 'left');
        $this->db->where("{$this->table_name}.id", $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $data = $query->row_array();
            $roles = $this->db->select('role_id')->get_where('apps_user_roles', ['user_id' => $id])->result_array();
            $data['role_ids'] = array_column($roles, 'role_id');
            return $data;
        }
        return [];
    }

    public function get_all_roles(): array
    {
        $query = $this->db->get('apps_roles');
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return [];
    }

    public function get_all_study_programs(): array
    {
        $query = $this->db->get('apps_study_programs');
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return [];
    }
}
