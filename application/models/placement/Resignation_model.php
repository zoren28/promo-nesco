<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Resignation_model extends CI_Model
{
    public $db2 = '';
    public $date = '';
    public $datetime = '';
    public $loginId = '';
    public $hr = 'nesco';
    public $column_order = array();
    public $search_column = array();
    public $order = array();

    function __construct()
    {
        parent::__construct();
        $this->load->library('nativesession');

        $this->date = date('Y-m-d');
        $this->datetime = date('Y-m-d H:i:s');
        $this->loginId = $_SESSION['emp_id'];
        $this->db2 = $this->load->database('timekeeping', TRUE);

        $this->db->query('SET SESSION sql_mode = ""');

        $this->column_order = array('employee3.name', 'employee3.added_by', 'employee3.date_updated', 'termination.date', 'termination.remarks');
        $this->search_column = array('employee3.name', 'employee3.added_by', 'employee3.date_updated', 'termination.date', 'termination.remarks'); //set column field database for datatable searchable 
        $this->order = array('employee3.name' => 'ASC'); // default order 
    }

    public function resignation_list()
    {
        $this->make_query();
        if (@$_POST["length"] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_filtered_data()
    {
        $this->make_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function get_all_data()
    {
        $this->db->from('employee3');
        $this->db->join('users', 'users.emp_id = employee3.emp_id');
        if ($this->hr ==  'nesco') {
            $this->db->where('emp_type', 'Promo-NESCO');
        } else {
            $this->db->like('emp_type', 'Promo', 'after');
        }
        return $this->db->count_all_results();
    }

    private function make_query()
    {
        $this->db->from('employee3');
        $this->db->join('termination', 'termination.emp_id = employee3.emp_id');
        if ($this->hr ==  'nesco') {
            $this->db->where('emp_type', 'Promo-NESCO');
        } else {
            $this->db->like('emp_type', 'Promo', 'after');
        }

        $i = 0;
        foreach ($this->search_column as $item) // loop column 
        {
            if ($_POST['search']['value']) // if datatable send POST for search
            {

                if ($i === 0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($this->search_column) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }

        if (isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    public function show_termination($termination_no)
    {
        $query = $this->db->get_where('termination', array('termination_no' => $termination_no));
        return $query->row();
    }

    public function submit_resignation_letter($data)
    {
        $update = array(
            'resignation_letter' => $data['destination'],
            'date_updated' => $this->datetime
        );
        $this->db->where('termination_no', $data['termination_no']);
        return $this->db->update('termination', $update);
    }

    public function upload_clearance($emp_id, $clearances)
    {
        foreach ($clearances as $clearance) {
            $this->db->set($clearance['clearance'], $clearance['path']);
        }
        $this->db->where('emp_id', $emp_id);
        return $this->db->update('promo_record');
    }

    public function store_termination($data)
    {
        $insert = array(
            'emp_id' => $data['emp_id'],
            'date' => date('Y-m-d', strtotime($data['dateEffective'])),
            'remarks' => $data['remarks'],
            'resignation_letter' => $data['resignation_path'],
            'added_by' => $this->loginId,
            'date_updated' => $this->date
        );
        return $this->db->insert('termination', $insert);
    }

    public function update_employee3($data)
    {
        $update = array(
            'current_status' => $data['rt_status'],
            'remarks' => $data['remarks']
        );
        $this->db->where('emp_id', $data['emp_id']);
        return $this->db->update('employee3', $update);
    }

    public function inactive_user($emp_id)
    {
        $update = array(
            'user_status' => 'inactive'
        );
        $this->db->where('emp_id', $emp_id);
        return $this->db->update('users', $update);
    }
}
