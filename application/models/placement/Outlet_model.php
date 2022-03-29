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

    public function employee_details($emp_id)
    {
        $query = $this->db->select('record_no, emp_id')
            ->get_where('employee3', array('emp_id' => $emp_id));
        return $query->row();
    }

    public function appraisal_details($record_no, $emp_id, $store)
    {
        return $this->db->get_where('appraisal_details', array('record_no' => $record_no, 'emp_id' => $emp_id, 'store' => $store));
    }

    public function business_unit_details($bunit_field)
    {
        $query = $this->db->get_where('locate_promo_business_unit', array('bunit_field' => $bunit_field));
        return $query->row();
    }
}
