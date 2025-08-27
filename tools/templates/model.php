<?php
defined('BASEPATH') or exit('No direct script access allowed');

class {{module_name}}_model extends CI_Model
{
    private $table_name;

    public function __construct()
    {
        parent::__construct();
        $this->table_name = '{{table_name}}';
    }

    public function create(array $data): bool
    {
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

    public function update(int $id, array $data): bool
    {
        return $this->db->update($this->table_name, $data, ['id' => $id]);
    }

    public function delete(int $id): bool
    {
        return $this->db->update($this->table_name, ['deleted_at' => date('Y-m-d H:i:s')], ['id' => $id]);
    }
}
