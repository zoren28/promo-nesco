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

    public function username_list($data)
    {
        if (!empty($data['company'])) {
            $company_name = $this->employee_model->get_company_name($data['company'])->pc_name;
        }

        $this->db->select('employee3.record_no, employee3.emp_id, name, position, agency_code, promo_company, promo_department, promo_type')
            ->from('employee3')
            ->join('promo_record', 'promo_record.record_no = employee3.record_no AND promo_record.emp_id = employee3.emp_id')
            ->where('current_status', 'Active')
            ->where('emp_type', 'Promo-NESCO');

        if (!empty($data['store'])) {

            $field = explode('/', $data['store']);
            $this->db->where(end($field), 'T');
        }

        if (!empty($data['department'])) {

            $this->db->where('promo_department', $data['department']);
        }

        if (!empty($data['company'])) {
            $this->db->where('promo_company', $company_name);
        }

        $query = $this->db->get();
        return $query->result();
    }

    public function get_username($emp_id)
    {
        $query = $this->db->select('username')
            ->get_where('users', array('emp_id' => $emp_id, 'usertype' => 'employee'));
        return $query->row();
    }

    public function fetch_qbe_results($data)
    {
        $field = '';
        if (!empty($data['fields'])) {

            foreach ($data['fields'] as $key => $value) {

                $field .= ", $value";
            }
        }

        if (!empty($data['company'])) {
            $company_name = $this->employee_model->get_company_name($data['company'])->pc_name;
        }

        $fields = "employee3.record_no, employee3.emp_id, startdate, eocdate, position, firstname, middlename, lastname, suffix, birthdate, agency_code, promo_company, promo_department, company_duration, promo_type, type" . $field;
        $this->db->select($fields)
            ->from('employee3')
            ->join('applicant', 'applicant.app_id = employee3.emp_id')
            ->join('promo_record', 'promo_record.record_no = employee3.record_no AND promo_record.emp_id = employee3.emp_id')
            ->where('emp_type', 'Promo-NESCO');

        if (!empty($data['current_status'])) {
            $this->db->where('current_status', $data['current_status']);
        }

        if (!empty($data['date_asof'])) {
            $this->db->where('startdate <=', date('Y-m-d', strtotime($data['date_asof'])));
        }

        if (!empty($data['promo_type'])) {
            $this->db->where('promo_type', $data['promo_type']);
        }

        if (!empty($data['department'])) {
            $this->db->where('promo_department', $data['department']);
        }

        if (!empty($data['business_unit'])) {
            $bunit_field = explode('/', $data['business_unit']);
            $this->db->where(end($bunit_field), 'T');
        }

        if (!empty($data['company'])) {
            $this->db->where('promo_company', $company_name);
        }

        if (!empty($data['bloodtypetf'])) {
            $this->db->where('bloodtype', $data['bloodtypetf']);
        }

        if (!empty($data['weighttf'])) {
            $this->db->like('weight', $data['weighttf']);
        }

        if (!empty($data['heighttf'])) {
            $this->db->like('height', $data['heighttf']);
        }

        if (!empty($data['coursetf'])) {
            $this->db->like('course', $data['coursetf']);
        }

        if (!empty($data['attainmenttf'])) {
            $this->db->like('attainment', $data['attainmenttf']);
        }

        if (!empty($data['schooltf'])) {
            $this->db->like('school', $data['schooltf']);
        }

        if (!empty($data['civilstatustf'])) {
            $this->db->where('civilstatus', $data['civilstatustf']);
        }

        if (!empty($data['religiontf'])) {
            $this->db->like('religion', $data['religiontf']);
        }

        if (!empty($data['gendertf'])) {
            $this->db->where('gender', $data['gendertf']);
        }

        if (!empty($data['home_addresstf'])) {
            $this->db->like('home_address', $data['home_addresstf']);
        }

        if (!empty($data['nametf'])) {
            $this->db->like('name', $data['nametf']);
        }

        $query = $this->db->get();
        return $query->result_array();
    }

    public function naend_of_contract_list($data)
    {
        if (!empty($data['company'])) {
            $company_name = $this->employee_model->get_company_name($data['company'])->pc_name;
        }

        $this->db->select('employee3.record_no, employee3.emp_id, name, startdate, eocdate, position, agency_code, promo_company, promo_department, promo_type, type, company_duration')
            ->from('employee3')
            ->join('promo_record', 'promo_record.record_no = employee3.record_no AND promo_record.emp_id = employee3.emp_id')
            ->where(array('emp_type' => 'Promo-NESCO', 'current_status' => 'Active'));

        if (!empty($data['month'])) {
            $this->db->like('eocdate', $data['month']);
        }

        if (!empty($data['department'])) {
            $this->db->where('promo_department', $data['department']);
        }

        if (!empty($data['business_unit'])) {
            $field = explode('/', $data['business_unit']);
            $this->db->where(end($field), 'T');
        }

        if (!empty($data['company'])) {
            $this->db->where('promo_company', $company_name);
        }

        $query = $this->db->get();
        return $query->result();
    }

    public function mga_naend_of_contract($empIds)
    {
        $query = $this->db->select('employee3.record_no, employee3.emp_id, name, startdate, eocdate, position, agency_code, promo_company, promo_department, promo_type, type, company_duration')
            ->from('employee3')
            ->join('promo_record', 'promo_record.record_no = employee3.record_no AND promo_record.emp_id = employee3.emp_id')
            ->where(array('emp_type' => 'Promo-NESCO', 'current_status' => 'Active'))
            ->where_in('employee3.emp_id', $empIds)
            ->get();
        return $query->result();
    }
}
