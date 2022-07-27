<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Account_model extends CI_Model
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

        $this->column_order = array('employee3.name', 'users.username', 'users.usertype', 'users.user_status', 'users.login');
        $this->search_column = array('employee3.name', 'users.username', 'users.usertype', 'users.user_status', 'users.login'); //set column field database for datatable searchable 
        $this->order = array('employee3.name' => 'ASC'); // default order 
    }

    public function check_user($emp_id)
    {
        return $this->db->from('users')
            ->where(array('emp_id' => $emp_id, 'usertype' => 'employee'))
            ->count_all_results();
    }

    public function check_usernamme($username)
    {
        return $this->db->from('users')
            ->where('username', $username)
            ->count_all_results();
    }

    public function create_user_account($data)
    {
        $insert = array(
            'emp_id' => $data['emp_id'],
            'username' => $data['username'],
            'password' => md5($data['password']),
            'usertype' => $data['usertype'],
            'user_status' => 'active',
            'login' => 'no',
            'date_created' => $this->datetime,
            'user_id' => '4'
        );

        return $this->db->insert('users', $insert);
    }

    public function find_active_hr_staff($str)
    {
        $this->db->query('SET SESSION sql_mode = ""');

        // ONLY_FULL_GROUP_BY
        $this->db->query('SET SESSION sql_mode =
                              REPLACE(REPLACE(REPLACE(
                              @@sql_mode,
                              "ONLY_FULL_GROUP_BY,", ""),
                              ",ONLY_FULL_GROUP_BY", ""),
                              "ONLY_FULL_GROUP_BY", "")');

        $this->db->select('employee3.emp_id, name')
            ->from('employee3')
            ->join('users', 'users.emp_id = employee3.emp_id')
            ->group_start()
            ->like('name', $str)
            ->or_where('employee3.emp_id', $str)
            ->group_end()
            ->where_in('usertype', array('administrator', 'placement1', 'placement2', 'placement3', 'placement4', 'nesco'))
            ->where('current_status', 'Active')
            ->where('user_status', 'active')
            ->group_by('users.emp_id')
            ->order_by('name', 'ASC')
            ->limit(10);
        return $this->db->get();
    }

    public function check_promo_user($emp_id)
    {
        return $this->db->from('promo_user')
            ->where('emp_id', $emp_id)
            ->count_all_results();
    }

    public function create_hr_account($data)
    {
        $insert = array(
            'emp_id' => $data['emp_id'],
            'usertype' => $data['usertype'],
            'user_status' => 'active',
            'date_created' => $this->datetime
        );
        return $this->db->insert('promo_user', $insert);
    }

    public function get_promo_account_list($data)
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
        $this->db->join('users', 'users.emp_id = employee3.emp_id');
        if ($this->hr ==  'nesco') {
            $this->db->where('emp_type', 'Promo-NESCO');
        } else {
            $this->db->like('emp_type', 'Promo', 'after');
        }
        $this->db->where('users.usertype', 'employee');
        return $this->db->count_all_results();
    }

    private function make_query($data)
    {
        $this->db->from('employee3');
        $this->db->join('users', 'users.emp_id = employee3.emp_id');
        if ($this->hr ==  'nesco') {
            $this->db->where('emp_type', 'Promo-NESCO');
        } else {
            $this->db->like('emp_type', 'Promo', 'after');
        }
        $this->db->where('users.usertype', 'employee');

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

    public function update_hr_account($data)
    {
        $update = array(
            'usertype' => $data['type'],
            'date_updated' => $this->datetime
        );

        $this->db->where('emp_id', $data['emp_id']);
        return $this->db->update('promo_user', $update);
    }

    public function update_hr_status($data)
    {
        $update = array(
            'user_status' => $data['status'],
            'date_updated' => $this->datetime
        );

        $this->db->where('emp_id', $data['emp_id']);
        return $this->db->update('promo_user', $update);
    }

    public function update_user_access($data)
    {
        $update = array(
            $data['field'] => $data['value']
        );

        $this->db->where('id', $data['id']);
        return $this->db->update($data['table'], $update);
    }
}
