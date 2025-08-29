<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Staff_model extends CI_Model
{
    private $table_name;

    public function __construct()
    {
        parent::__construct();
        $this->table_name = 'apps_staffs';
    }

    public function create(): bool
    {
        $data = [
            'id' => $this->input->post('id', true),
            'name' => $this->input->post('name', true),
            'email' => $this->input->post('email', true),
        ];
        return $this->db->insert($this->table_name, $data);
    }

    public function update(): bool
    {
        $id = $this->input->post('id', true);
        $data = [
            'name' => $this->input->post('name', true),
            'email' => $this->input->post('email', true),
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
        ];
        $filter = [];
        $where = [
            "{$this->table_name}.deleted_at" => null
        ];
        $joins = [];
        $search = [
            "{$this->table_name}.id",
            "{$this->table_name}.name",
            "{$this->table_name}.email",
        ];
        $order_columns = [
            "{$this->table_name}.id",
            "{$this->table_name}.name",
            "{$this->table_name}.email",
        ];
        $or_where = [];

        return $this
            ->datatables
            ->get_data($this->table_name, $column, $filter, $where, $joins, $search, $order_columns, $or_where);
    }

    public function get_data_detail(string $id): array
    {
        $query = $this->db->get_where($this->table_name, ['id' => $id]);
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
}
