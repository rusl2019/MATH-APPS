<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Module_model extends CI_Model
{
    private $table_name;

    public function __construct()
    {
        parent::__construct();
        $this->table_name = 'apps_modules';
    }

    public function create(): bool
    {
        $data = [
            'name' => $this->input->post('name', true),
            'description' => $this->input->post('description', true),
        ];
        return $this->db->insert($this->table_name, $data);
    }

    public function update(): bool
    {
        $id = $this->input->post('id', true);
        $data = [
            'name' => $this->input->post('name', true),
            'description' => $this->input->post('description', true),
        ];
        return $this->db->update($this->table_name, $data, ['id' => $id]);
    }

    public function delete(): bool
    {
        $id = $this->input->post('id', true);
        return $this->db->update($this->table_name, ['deleted_at' => date('Y-m-d H:i:s')], ['id' => $id]);
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
}
