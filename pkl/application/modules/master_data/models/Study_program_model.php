<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Study_program_model extends CI_Model
{
    private $table_name;

    public function __construct()
    {
        parent::__construct();
        $this->table_name = 'apps_study_programs';
    }

    public function create(): bool
    {
        $data = [
            'name' => $this->input->post('name', true),
            'lecturer_id' => $this->input->post('lecturer_id', true),
        ];
        return $this->db->insert($this->table_name, $data);
    }

    public function update(): bool
    {
        $id = $this->input->post('id', true);
        $data = [
            'name' => $this->input->post('name', true),
            'lecturer_id' => $this->input->post('lecturer_id', true),
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
            'apps_lecturers.name as lecturer_name',
            "{$this->table_name}.id",
        ];
        $filter = [];
        $where = [
            "{$this->table_name}.deleted_at" => null
        ];
        $joins = [
            ['apps_lecturers', "{$this->table_name}.lecturer_id = apps_lecturers.id", 'left']
        ];
        $search = [
            "{$this->table_name}.name",
            'apps_lecturers.name',
        ];
        $order_columns = [
            "{$this->table_name}.name",
            'apps_lecturers.name',
        ];
        $or_where = [];

        return $this
            ->datatables
            ->get_data($this->table_name, $column, $filter, $where, $joins, $search, $order_columns, $or_where);
    }

    public function get_data_detail(string $id): array
    {
        $this->db->select("{$this->table_name}.*, apps_lecturers.name as lecturer_name");
        $this->db->from($this->table_name);
        $this->db->join('apps_lecturers', "{$this->table_name}.lecturer_id = apps_lecturers.id", 'left');
        $this->db->where("{$this->table_name}.id", $id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
        return [];
    }

    public function get_all_lecturers(): array
    {
        $query = $this->db->get('apps_lecturers');
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return [];
    }
}
