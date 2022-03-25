<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Outlet_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->load->library('nativesession');
    }

    public function change_outlet_histories()
    {
        $query = $this->db->select('employee3.emp_id, name, changefrom, changeto, effectiveon')
            ->from('employee3')
            ->join('change_outlet_record', 'change_outlet_record.emp_id = employee3.emp_id')
            ->where('emp_type', 'Promo-NESCO')
            ->order_by('effectiveon DESC, change_no DESC')
            ->get();
        return $query->result();
    }
}
