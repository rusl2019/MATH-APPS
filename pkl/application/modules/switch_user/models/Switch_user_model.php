<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Switch_user_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_user(string $user_type, string $search, int $page = 1): array
    {
        $limit = 30;
        $page = max(1, intval($page));
        $offset = ($page - 1) * $limit;

        if ($user_type) {
            $this->db->where('type', $user_type);
        }

        $this->db->select('id, name, type');
        $subquery = "(SELECT id, name, 'students' as type FROM apps_students
                     UNION ALL
                     SELECT id, name, 'lecturers' as type FROM apps_lecturers
                     UNION ALL
                     SELECT id, name, 'staffs' as type FROM apps_staffs)";
        $this->db->from("$subquery as users");

        if ($search) {
            $this->db->like('name', $search);
            $this->db->or_like('id', $search);
        }

        $count_query = clone $this->db;
        $total_count = $count_query->count_all_results();

        $this->db->limit($limit, $offset);
        $query = $this->db->get();

        return [
            'items' => $query->num_rows() > 0 ? $query->result_array() : [],
            'total_count' => $total_count
        ];
    }

    public function get_user_detail(string $user_type, string $user_id): array
    {
        $user = $this->db->get_where('apps_' . $user_type, ['id' => $user_id])->row();
        $user_roles = $this->db->select('role_id')->get_where('apps_user_roles', ['user_id' => $user->id])->result_array();
        $roles = [];
        $role_names = [];
        foreach ($user_roles as $role) {
            $roles[] = $role['role_id'];
            $role_names[] = $this->db->get_where('apps_roles', ['id' => $role['role_id']])->row()->name;
        }
        $data = [
            'user_type' => $user_type,
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'roles' => $roles,
            'role_names' => $role_names,
            'is_head_study_program' => in_array('5', $roles),
        ];
        return $data;
    }
}
