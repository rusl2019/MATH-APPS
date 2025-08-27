<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Permission_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_permission_by_module(array $roles, string $module_name, string $action): array
    {
        $result = [];
        foreach ($roles as $role_id) {
            $this->db->select('apps_role_access.can_' . $action);
            $this->db->from('apps_role_access');
            $this->db->join('apps_modules', 'apps_modules.id = apps_role_access.module_id', 'left');
            $this->db->where('apps_role_access.role_id', $role_id);
            $this->db->where('apps_modules.name', $module_name);
            $query = $this->db->get()->row_array();
            if ($query) {
                $result[] = (bool) $query['can_' . $action];
            } else {
                $result[] = false;
            }
        }
        return $result;
    }

    public function get_all_permission_by_role(int $role_id): array
    {
        $this->db->select('*');
        $this->db->from('apps_role_access');
        $this->db->join('apps_modules', 'apps_modules.id = apps_role_access.module_id', 'left');
        $this->db->where('role_id', $role_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $permissions = [];
            foreach ($result as $row) {
                $permissions[$row['name']] = [
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
