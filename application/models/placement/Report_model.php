<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Report_model extends CI_Model
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

    public function load_stat_BU($field)
    {
        $where = array('emp_type' => 'Promo-NESCO', $field => 'T', 'current_status' => 'Active');
        $query = $this->db->select('employee3.record_no, employee3.emp_id, name, agency_code, promo_company, promo_department, al_tag, al_tal, icm, pm, abenson_tag, abenson_icm, cdc, berama, al_tub, colc, colm, alta_citta, bq, shoppers, promo_type, type, position, startdate, eocdate')
            ->from('employee3')
            ->join('promo_record', 'promo_record.record_no = employee3.record_no')
            ->where($where)
            ->order_by('name', 'ASC')
            ->get();
        return $query->result();
    }

    public function load_stat_dept($data)
    {
        $where = array('emp_type' => 'Promo-NESCO', $data['field'] => 'T', 'promo_department' => $data['dept'], 'current_status' => 'Active');
        $query = $this->db->select('employee3.record_no, employee3.emp_id, name, agency_code, promo_company, promo_department, al_tag, al_tal, icm, pm, abenson_tag, abenson_icm, cdc, berama, al_tub, colc, colm, alta_citta, bq, shoppers, promo_type, type, position, startdate, eocdate')
            ->from('employee3')
            ->join('promo_record', 'promo_record.record_no = employee3.record_no')
            ->where($where)
            ->order_by('name', 'ASC')
            ->get();
        return $query->result();
    }
}
