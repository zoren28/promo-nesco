<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User_model extends CI_Model
{
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

        $this->db->query('SET SESSION sql_mode = ""');

        $this->column_order = array('employee3.name', 'promo_user.emp_id', 'promo_user.usertype', 'promo_user.user_status', 'promo_user.date_created', 'promo_user.date_updated');
        $this->search_column = array('employee3.name', 'promo_user.emp_id', 'promo_user.usertype', 'promo_user.user_status', 'promo_user.date_created', 'promo_user.date_updated'); //set column field database for datatable searchable 
        $this->order = array('employee3.name' => 'ASC'); // default order 
    }

    public function get_hr_account_list($data)
    {
        $this->make_query($data);
        if (@$_POST["length"] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_filtered_data($data)
    {
        $this->make_query($data);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function get_all_data($data)
    {
        $this->db->from('employee3');
        $this->db->join('promo_user', 'promo_user.emp_id = employee3.emp_id');
        $this->db->where('promo_user.usertype !=', 'administrator');
        return $this->db->count_all_results();
    }

    private function make_query($data)
    {
        $this->db->from('employee3');
        $this->db->join('promo_user', 'promo_user.emp_id = employee3.emp_id');
        $this->db->where('promo_user.usertype !=', 'administrator');

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
}
