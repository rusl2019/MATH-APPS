<?php
defined('BASEPATH') or exit('No direct script access allowed');

class DataTables
{
    protected string $table;
    protected array $columns;
    protected array $where;
    protected array $filter;
    protected array $joins;
    protected array $search;
    protected array $order_columns;  // New property to handle complex order columns
    protected array $or_where;

    public function __construct()
    {
        $this->table = '';
        $this->columns = [];
        $this->where = [];
        $this->filter = [];
        $this->joins = [];
        $this->search = [];
        $this->order_columns = [];  // Initialize the new property
        $this->or_where = [];

        $this->db = &get_instance()->db;
        $this->input = &get_instance()->input;
    }

    public function get_data(string $table, array $column, array $filter = [], array $where = [], array $joins = [], array $search = [], array $order_columns = [], array $or_where = []): array
    {
        $draw = $this->input->post('draw', true);

        $this->table = $table;
        $this->columns = $column;
        $this->filter = $filter;
        $this->where = $where;
        $this->joins = $joins;
        $this->search = $search;
        $this->order_columns = $order_columns ?? $column;  // Use provided order columns or default to regular columns
        $this->or_where = $or_where;

        if ($this->where) {
            $this->db->where($this->where);
        }

        if ($this->or_where) {
            $this->db->or_where($this->or_where);
        }

        $total_records = $this->db->count_all_results($this->table);
        $filtered_records = $this->_get_filtered_records();
        $data = $this->_get_records();

        return [
            'draw' => intval($draw),
            'recordsTotal' => $total_records,
            'recordsFiltered' => $filtered_records,
            'data' => $data
        ];
    }

    private function _setup_base_query(): void
    {
        $this->db->select($this->columns);
        $this->db->from($this->table);

        if ($this->where) {
            $this->db->where($this->where);
        }

        if ($this->filter) {
            foreach ($this->filter as $key => $value) {
                if ($value == '') {
                    unset($this->filter[$key]);
                }
            }
            foreach ($this->filter as $key => $value) {
                $this->db->where($key, $value);
            }
        }

        if ($this->joins) {
            foreach ($this->joins as $join) {
                $this->db->join($join[0], $join[1], $join[2]);
            }
        }
    }

    private function _get_filtered_records(): int
    {
        $search = $this->input->post('search', true)['value'] ?? '';

        $this->_setup_base_query();

        $this->db->group_start();
        $this->db->or_like($this->_get_search_fields($search));
        $this->db->group_end();
        return $this->db->get()->num_rows();
    }

    private function _get_records(): array
    {
        $start = $this->input->post('start', true);
        $length = $this->input->post('length', true);
        $search = $this->input->post('search', true)['value'] ?? '';
        $order = $this->input->post('order', true)[0] ?? null;

        if ($order) {
            $order_column = $this->order_columns[$order['column']] ?? '';
            $order_dir = $order['dir'];
        } else {
            $order_column = $this->order_columns[0] ?? '';
            $order_dir = 'asc';
        }

        $this->_setup_base_query();

        $this->db->group_start();
        $this->db->or_like($this->_get_search_fields($search));
        $this->db->group_end();
        $this->db->order_by($order_column, $order_dir, FALSE);
        $this->db->limit($length, $start);
        return $this->db->get()->result_array();
    }

    private function _get_search_fields(string $search): array
    {
        $search_fields = [];

        if ($this->search) {
            foreach ($this->search as $column) {
                $search_fields[$column] = $search;
            }
        } else {
            foreach ($this->columns as $column) {
                $search_fields[$column] = $search;
            }
        }

        return $search_fields;
    }
}
