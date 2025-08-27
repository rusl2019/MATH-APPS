<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Role_model extends CI_Model
{
    private $table_name;

    public function __construct()
    {
        parent::__construct();
        $this->table_name = 'apps_roles';
    }

    public function create(): bool
    {
        $modules = $this->get_all_modules();

        $data = [
            'name' => $this->input->post('name', true),
            'description' => $this->input->post('description', true),
        ];

        if ($this->db->insert($this->table_name, $data)) {
            $id = $this->db->insert_id();

            $can_create = $this->input->post('can_create') ?: [];
            $can_read = $this->input->post('can_read') ?: [];
            $can_update = $this->input->post('can_update') ?: [];
            $can_delete = $this->input->post('can_delete') ?: [];

            $permissions = [];
            foreach ($modules as $module) {
                $module_id = $module['id'];
                $permissions[] = [
                    'role_id' => $id,
                    'module_id' => $module_id,
                    'can_create' => in_array($module_id, $can_create) ? 1 : 0,
                    'can_read' => in_array($module_id, $can_read) ? 1 : 0,
                    'can_update' => in_array($module_id, $can_update) ? 1 : 0,
                    'can_delete' => in_array($module_id, $can_delete) ? 1 : 0
                ];
            }
            return $this->update_permissions($id, $permissions);
        }

        return FALSE;
    }

    public function update(): bool
    {
        $modules = $this->get_all_modules();

        $id = $this->input->post('id', true);
        $data = [
            'name' => $this->input->post('name', true),
            'description' => $this->input->post('description', true),
        ];

        if ($this->db->update($this->table_name, $data, ['id' => $id])) {
            $can_create = $this->input->post('can_create') ?: [];
            $can_read = $this->input->post('can_read') ?: [];
            $can_update = $this->input->post('can_update') ?: [];
            $can_delete = $this->input->post('can_delete') ?: [];

            $permissions = [];
            foreach ($modules as $module) {
                $module_id = $module['id'];
                $permissions[] = [
                    'role_id' => $id,
                    'module_id' => $module_id,
                    'can_create' => in_array($module_id, $can_create) ? 1 : 0,
                    'can_read' => in_array($module_id, $can_read) ? 1 : 0,
                    'can_update' => in_array($module_id, $can_update) ? 1 : 0,
                    'can_delete' => in_array($module_id, $can_delete) ? 1 : 0
                ];
            }

            return $this->update_permissions($id, $permissions);
        }

        return FALSE;
    }

    public function delete(): bool
    {
        $id = $this->input->post('id', true);
        if (!$this->db->update($this->table_name, ['deleted_at' => date('Y-m-d H:i:s')], ['id' => $id])) {
            return FALSE;
        }

        if (!$this->db->delete('apps_role_access', ['role_id' => $id])) {
            return FALSE;
        }
        return TRUE;
    }

    public function update_permissions(int $id, array $permissions): bool
    {
        return $this->db->delete('apps_role_access', ['role_id' => $id]) && $this->db->insert_batch('apps_role_access', $permissions);
    }

    public function get_data(): array
    {
        $this->load->library('DataTables');

        $column = [
            "{$this->table_name}.name",
            "{$this->table_name}.description",
            "{$this->table_name}.id",
        ];
        $filter = [];
        $where = [
            "{$this->table_name}.deleted_at" => null
        ];
        $joins = [];
        $search = [
            "{$this->table_name}.name",
            "{$this->table_name}.description",
        ];
        $order_columns = [
            "{$this->table_name}.name",
            "{$this->table_name}.description",
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
            return $query->row_array();
        }
        return [];
    }

    public function get_all_modules(): array
    {
        $query = $this->db->get_where('apps_modules', ['deleted_at' => null]);
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return [];
    }

    public function get_permission_by_role(int $role_id): array
    {
        $query = $this->db->get_where('apps_role_access', ['role_id' => $role_id]);
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $permissions = [];
            foreach ($result as $row) {
                $permissions[$row['module_id']] = [
                    'create' => (bool) $row['can_create'],
                    'read' => (bool) $row['can_read'],
                    'update' => (bool) $row['can_update'],
                    'delete' => (bool) $row['can_delete']
                ];
            }
            return $permissions;
        }
        return [];
    }
}
