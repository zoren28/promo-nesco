<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard_model extends CI_Model
{
    public $date = '';
    public $hrd_location = '';

    function __construct()
    {
        parent::__construct();
        $this->load->library('nativesession');

        $this->date = date('Y-m-d');
        $this->hrd_location = 'asc';
    }

    public function businessUnit_list()
    {
        $query = $this->db->select('bunit_id, bunit_name, bunit_field, bunit_acronym, bunit_epascode, bunit_permit, bunit_clearance, bunit_contract, bunit_intro, bunit_dutySched, bunit_dutyDays, bunit_specialSched, bunit_specialDays')
            ->get_where('locate_promo_business_unit', array('status' => 'active', 'hrd_location' => $this->hrd_location));
        return $query->result();
    }

    public function epas_businessUnit_list()
    {
        $query = $this->db->select('bunit_id, bunit_name, bunit_field, bunit_acronym, bunit_epascode, bunit_permit, bunit_clearance, bunit_contract, bunit_intro, bunit_dutySched, bunit_dutyDays, bunit_specialSched, bunit_specialDays')
            ->get_where('locate_promo_business_unit', array('appraisal_status' => 'active', 'hrd_location' => $this->hrd_location));
        return $query->result();
    }

    public function count_per_bu($field)
    {
        $where = array('emp_type' => 'Promo-NESCO', $field => 'T', 'current_status' => 'Active');
        $query = $this->db->select('COUNT(employee3.record_no) AS num')
            ->from('employee3')
            ->join('promo_record', 'promo_record.record_no = employee3.record_no')
            ->where($where)
            ->get();
        return $query->row()->num;
    }

    public function new_employee()
    {
        $where = array('emp_type' => 'Promo-NESCO', 'current_status' => 'Active', 'tag_as' => 'new');
        $query = $this->db->select('COUNT(record_no) AS num')
            ->get_where('employee3', $where);
        return $query->row()->num;
    }

    public function birthday_today()
    {
        return $this->db->select('employee3.record_no, employee3.emp_id, name, agency_code, promo_company, promo_department, al_tag, al_tal, icm, pm, abenson_tag, abenson_icm, cdc, berama, al_tub, colc, colm, alta_citta, bq, shoppers, position, birthdate, gender')
            ->from('employee3')
            ->join('applicant', 'employee3.emp_id = applicant.app_id')
            ->join('promo_record', 'employee3.record_no = promo_record.record_no')
            ->where(array('current_status' => 'Active', 'emp_type' => 'Promo-NESCO'))
            ->like('birthdate', date('m-d'), 'before')
            ->order_by('name', 'ASC')
            ->get();
    }

    public function active_employee()
    {
        $where = array('emp_type' => 'Promo-NESCO', 'current_status' => 'Active');
        $query = $this->db->select('COUNT(record_no) AS num')
            ->get_where('employee3', $where);
        return $query->row()->num;
    }

    public function eoc_today()
    {
        $where = array('emp_type' => 'Promo-NESCO', 'eocdate' => $this->date);
        $query = $this->db->select('COUNT(record_no) AS num')
            ->from('employee3')
            ->group_start()
            ->where('current_status', 'Active')
            ->or_where('current_status', 'End of Contract')
            ->group_end()
            ->where($where)
            ->get();
        return $query->row()->num;
    }

    public function due_contract()
    {
        return $this->db->select('employee3.record_no, employee3.emp_id, name, agency_code, promo_company, promo_department, al_tag, al_tal, icm, pm, abenson_tag, abenson_icm, cdc, berama, al_tub, colc, colm, alta_citta, bq, shoppers, promo_type, position, startdate, eocdate')
            ->from('employee3')
            ->join('promo_record', 'employee3.record_no = promo_record.record_no')
            ->where(array('current_status' => 'Active', 'emp_type' => 'Promo-NESCO'))
            ->group_start()
            ->where('eocdate !=', '0000-00-00')
            ->where('eocdate !=', '0001-11-30')
            ->where('eocdate <', $this->date)
            ->group_end()
            ->order_by('name', 'ASC')
            ->get();
    }

    function promo_has_bu($emp_id, $field)
    {
        $query = $this->db->select('COUNT(promo_id) AS exist')
            ->get_where('promo_record', array('emp_id' => $emp_id, $field => 'T'));
        return $query->row()->exist;
    }

    function promo_has_history_bu($emp_id, $record_no, $field)
    {
        $query = $this->db->select('COUNT(promo_id) AS exist')
            ->get_where('promo_history_record', array('record_no' => $record_no, 'emp_id' => $emp_id, $field => 'T'));
        return $query->row()->exist;
    }

    function promo_has_store($contract, $emp_id, $record_no, $field)
    {
        if ($contract == 'current') {

            $table = 'promo_record';
        } else {

            $table = 'promo_history_record';
        }

        $query = $this->db->select('COUNT(promo_id) AS exist')
            ->get_where($table, array('record_no' => $record_no, 'emp_id' => $emp_id, $field => 'T'));
        return $query->row()->exist;
    }

    function promo_has_ecci($table, $record_no, $emp_id, $field1, $field2, $field3)
    {
        return $query = $this->db->select('promo_id, ' . $field2 . ', ' . $field3)
            ->get_where($table, array('record_no' => $record_no, 'emp_id' => $emp_id, $field1 => 'T'));
    }

    public function count_per_dept($field, $dept)
    {
        $where = array('emp_type' => 'Promo-NESCO', $field => 'T', 'promo_department' => $dept, 'current_status' => 'Active');
        $query = $this->db->select('COUNT(employee3.record_no) AS num')
            ->from('employee3')
            ->join('promo_record', 'promo_record.record_no = employee3.record_no')
            ->where($where)
            ->get();
        return $query->row()->num;
    }
}
