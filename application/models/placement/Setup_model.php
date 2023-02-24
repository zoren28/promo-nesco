<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Setup_model extends CI_Model
{
    public $db2 = '';
    public $date = '';
    public $datetime = '';
    public $loginId = '';
    public $hr = 'nesco';

    function __construct()
    {
        parent::__construct();
        $this->load->library('nativesession');

        $this->date = date('Y-m-d');
        $this->datetime = date('Y-m-d H:i:s');
        $this->loginId = $_SESSION['emp_id'];
        $this->db2 = $this->load->database('timekeeping', TRUE);

        $this->db->query('SET SESSION sql_mode = ""');
    }

    public function is_department_exist($data)
    {
        foreach ($data as $key => $value) {
            $this->db->where($key, $value);
        }

        return $this->db->from('locate_promo_department')
            ->count_all_results();
    }

    public function create_department($data)
    {
        return $this->db->insert('locate_promo_department', $data);
    }

    public function company_list($filter)
    {
        if (!empty($filter)) {
            $this->db->where('status', $filter);
        }
        $query = $this->db->order_by('pc_name', 'ASC')
            ->get('locate_promo_company');
        return $query->result();
    }

    public function delete_company($pc_code)
    {
        return $this->db->delete('locate_promo_company', array('pc_code' => $pc_code));
    }

    public function update_company_status($data)
    {
        if ($data['action'] == 'activate') {
            $this->db->set('status', 1);
        } else {
            $this->db->set('status', 0);
        }

        $this->db->where('pc_code', $data['id']);
        return $this->db->update('locate_promo_company');
    }

    public function show_company($pc_code)
    {
        $query = $this->db->get_where('locate_promo_company', array('pc_code' => $pc_code));
        return $query->row();
    }

    public function check_company($pc_name, $pc_code)
    {
        $this->db->from('locate_promo_company');

        if (!empty($pc_code)) {
            $this->db->where('pc_code !=', $pc_code);
        }

        $this->db->where('pc_name', $pc_name);
        return $this->db->count_all_results();
    }

    public function update_company($data)
    {
        $update = array(
            'pc_name' => strtoupper($data['company'])
        );

        $this->db->where('pc_code', $data['company_code']);
        return $this->db->update('locate_promo_company', $update);
    }

    public function store_company($company)
    {
        $insert = array(
            'pc_name' => strtoupper($company),
            'created_at' => $this->datetime
        );

        return $this->db->insert('locate_promo_company', $insert);
    }

    public function agency_list($filter)
    {
        if (!empty($filter)) {
            $this->db2->where('status', $filter);
        }
        $query = $this->db2->order_by('agency_name', 'ASC')
            ->get('promo_locate_agency');
        return $query->result();
    }

    public function delete_agency($agency_code)
    {
        return $this->db2->delete('promo_locate_agency', array('agency_code' => $agency_code));
    }

    public function update_agency_status($data)
    {
        if ($data['action'] == 'activate') {
            $this->db2->set('status', 1);
        } else {
            $this->db2->set('status', 0);
        }

        $this->db2->where('agency_code', $data['agency_code']);
        return $this->db2->update('promo_locate_agency');
    }

    public function show_agency($agency_code)
    {
        $query = $this->db2->get_where('promo_locate_agency', array('agency_code' => $agency_code));
        return $query->row();
    }

    public function check_agency($agency_name, $agency_code)
    {
        $this->db2->from('promo_locate_agency');

        if (!empty($agency_code)) {
            $this->db2->where('agency_code !=', $agency_code);
        }

        $this->db2->where('agency_name', $agency_name);
        return $this->db2->count_all_results();
    }

    public function update_agency($data)
    {
        $update = array(
            'agency_name' => strtoupper($data['agency']),
            'updated_at' => $this->datetime
        );
        $this->db2->where('agency_code', $data['agency_code']);
        return $this->db2->update('promo_locate_agency', $update);
    }

    public function store_agency($agency)
    {
        $insert = array(
            'agency_name' => strtoupper($agency),
            'created_at' => $this->datetime
        );
        return $this->db2->insert('promo_locate_agency', $insert);
    }

    public function company_under_agency($agency_code)
    {
        $query = $this->db2->select('company_code, company_name')
            ->get_where('promo_locate_company', array('agency_code' => $agency_code));
        return $query->result();
    }

    public function untag_company_agency($company_code)
    {
        return $this->db2->delete('promo_locate_company', array('company_code' => $company_code));
    }

    public function check_company_agency($agency_code, $company_name)
    {
        return $this->db2->from('promo_locate_company')
            ->where(array('agency_code' => $agency_code, 'company_name' => $company_name))
            ->count_all_results();
    }

    public function delete_promo_locate_company($agency_code)
    {
        $this->db2->delete('promo_locate_company', array('agency_code' => $agency_code));
    }

    public function store_promo_locate_company($agency_code, $company)
    {
        $insert = array(
            'agency_code' => $agency_code,
            'company_name' => $company,
            'created_at' => $this->datetime
        );
        $this->db2->insert('promo_locate_company', $insert);
    }

    public function product_list($filter)
    {
        if (!empty($filter)) {
            $this->db->where('status', $filter);
        }
        $query = $this->db->order_by('product', 'ASC')
            ->get('locate_promo_product');
        return $query->result();
    }

    public function delete_product($id)
    {
        return $this->db->delete('locate_promo_product', array('id' => $id));
    }

    public function update_product_status($data)
    {
        if ($data['action'] == 'activate') {
            $this->db->set('status', 1);
        } else {
            $this->db->set('status', 0);
        }

        $this->db->where('id', $data['id']);
        return $this->db->update('locate_promo_product');
    }

    public function show_product($id)
    {
        $query = $this->db->get_where('locate_promo_product', array('id' => $id));
        return $query->row();
    }

    public function update_product($data)
    {
        $update = array(
            'product' => strtoupper($data['product']),
            'updated_at' => $this->datetime
        );
        $this->db->where('id', $data['id']);
        return $this->db->update('locate_promo_product', $update);
    }

    public function check_product($product, $id)
    {
        $this->db->from('locate_promo_product');

        if (!empty($id)) {
            $this->db->where('id !=', $id);
        }

        $this->db->where('product', $product);
        return $this->db->count_all_results();
    }

    public function store_product($product)
    {
        $insert = array(
            'product' => strtoupper($product),
            'created_at' => $this->datetime
        );
        return $this->db->insert('locate_promo_product', $insert);
    }

    public function products_under_company()
    {
        $query = $this->db->get('promo_company_products');
        return $query->result();
    }

    public function untag_product_company($id)
    {
        return $this->db->delete('promo_company_products', array('id' => $id));
    }

    public function check_product_company($company, $product)
    {
        return $this->db->from('promo_company_products')
            ->where(array('company' => $company, 'product' => $product))
            ->count_all_results();
    }

    public function delete_promo_company_products($company)
    {
        return $this->db->delete('promo_company_products', array('company' => $company));
    }

    public function store_promo_company_products($company, $product)
    {
        $insert = array(
            'company' => $company,
            'product' => $product,
            'created_at' => $this->datetime
        );
        return $this->db->insert('promo_company_products', $insert);
    }

    public function find_active_supervisor($str)
    {
        $this->db->query('SET SESSION sql_mode = ""');

        // ONLY_FULL_GROUP_BY
        $this->db->query('SET SESSION sql_mode =
                              REPLACE(REPLACE(REPLACE(
                              @@sql_mode,
                              "ONLY_FULL_GROUP_BY,", ""),
                              ",ONLY_FULL_GROUP_BY", ""),
                              "ONLY_FULL_GROUP_BY", "")');

        return $this->db->select('employee3.emp_id, name')
            ->from('employee3')
            ->join('users', 'employee3.emp_id = users.emp_id')
            ->where('usertype', 'supervisor')
            ->group_start()
            ->like('name', $str)
            ->or_where('employee3.emp_id', $str)
            ->group_end()
            ->group_by('users.emp_id')
            ->order_by('name', 'ASC')
            ->limit(10)
            ->get();
    }

    public function show_supervisor($emp_id)
    {
        $query = $this->db->get_where('employee3', array('emp_id' => $emp_id));
        return $query->row();
    }

    public function list_of_subordinates($rater)
    {
        $this->db->select('leveling_subordinates.record_no, employee3.record_no AS emp_recordno, emp_id, name, emp_type, current_status, position')
            ->from('leveling_subordinates')
            ->join('employee3', 'employee3.emp_id = leveling_subordinates.subordinates_rater')
            ->where('ratee', $rater);

        if ($this->hr == 'nesco') {
            $this->db->where('emp_type', 'Promo-NESCO');
        } else {
            $this->db->like('emp_type', 'Promo', 'after');
        }
        $query = $this->db->get();
        return $query->result();
    }

    public function remove_subordinates($subordinates)
    {
        $this->db->where_in('record_no', $subordinates);
        return $this->db->delete('leveling_subordinates');
    }

    public function employee_list($data)
    {
        $this->db->select('employee3.emp_id, name, current_status, position')
            ->from('employee3')
            ->join('promo_record', 'promo_record.record_no = employee3.record_no AND promo_record.emp_id = employee3.emp_id')
            ->where($data['field'], 'T')
            ->where('promo_department', $data['department'])
            ->where('current_status !=', 'blacklisted');

        if ($this->hr == 'nesco') {
            $this->db->where('emp_type', 'Promo-NESCO');
        } else {
            $this->db->like('emp_type', 'Promo', 'after');
        }

        $query = $this->db->get();
        return $query->result();
    }

    public function check_subordinates($rater, $ratee)
    {
        return $this->db->from('leveling_subordinates')
            ->where(array('ratee' => $rater, 'subordinates_rater' => $ratee))
            ->count_all_results();
    }

    public function store_leveling_subordinates($data)
    {
        $insert = array();
        foreach ($data['employees'] as $employee) {

            $subordinates = array(
                'ratee' => $data['rater'],
                'subordinates_rater' => $employee
            );

            $insert[] = $subordinates;
        }

        return $this->db->insert_batch('leveling_subordinates', $insert);
    }

    public function businessUnit_list()
    {
        $query = $this->db->get_where('locate_promo_business_unit');
        return $query->result();
    }

    public function update_business_unit_status($data)
    {
        $update = array(
            $data['column'] => $data['status']
        );
        $this->db->where('bunit_id', $data['bunit_id']);
        return $this->db->update('locate_promo_business_unit', $update);
    }

    public function show_business_unit($bunit_id)
    {
        $query = $this->db->get_where('locate_promo_business_unit', array('bunit_id' => $bunit_id));
        return $query->row();
    }

    public function check_field_value($field)
    {
        $count_table = $this->db->from('promo_record')
            ->where($field, 'T')
            ->count_all_results();

        if ($count_table == 0) {

            $count_table = $this->db->from('promo_history_record')
                ->where($field, 'T')
                ->count_all_results();
        }

        return $count_table;
    }

    public function store_promo_business_unit($data, $fields)
    {
        foreach ($fields as $key => $value) {

            $this->db->set($key, $data['bunit_field'] . '_' . $value);
        }
        $this->db->set('bunit_name', strtoupper($data['bunit_name']));
        $this->db->set('business_unit', strtoupper($data['bunit_name']));
        $this->db->set('bunit_field', $data['bunit_field']);
        $this->db->set('bunit_acronym', strtoupper($data['bunit_acronym']));
        $this->db->set('hrd_location', $data['hrd_location']);
        return $this->db->insert('locate_promo_business_unit');
    }

    public function add_fields_promo($field)
    {
        return $this->db->query("ALTER TABLE `promo_record`
        ADD COLUMN `" . $field . "` VARCHAR(1) NULL DEFAULT NULL AFTER `alta_citta`,
        ADD COLUMN `" . $field . "_epascode` VARCHAR(255) NULL DEFAULT NULL AFTER `alta_special_days`,
        ADD COLUMN `" . $field . "_contract` TEXT NULL DEFAULT NULL AFTER `" . $field . "_epascode`,
        ADD COLUMN `" . $field . "_permit` VARCHAR(255) NULL DEFAULT NULL AFTER `" . $field . "_contract`,
        ADD COLUMN `" . $field . "_clearance` TEXT NULL DEFAULT NULL AFTER `" . $field . "_permit`,
        ADD COLUMN `" . $field . "_intro` VARCHAR(255) NULL DEFAULT NULL AFTER `" . $field . "_clearance`,
        ADD COLUMN `" . $field . "_sched` VARCHAR(50) NULL DEFAULT NULL AFTER `" . $field . "_intro`,
        ADD COLUMN `" . $field . "_days` VARCHAR(50) NULL DEFAULT NULL AFTER `" . $field . "_sched`,
        ADD COLUMN `" . $field . "_special_sched` VARCHAR(50) NULL DEFAULT NULL AFTER `" . $field . "_days`,
        ADD COLUMN `" . $field . "_special_days` VARCHAR(50) NULL DEFAULT NULL AFTER `" . $field . "_special_sched`");
    }

    public function add_fields_promo_hist($field)
    {
        return $this->db->query("ALTER TABLE `promo_history_record`
        ADD COLUMN `" . $field . "` VARCHAR(1) NULL DEFAULT NULL AFTER `alta_citta`,
        ADD COLUMN `" . $field . "_epascode` VARCHAR(255) NULL DEFAULT NULL AFTER `alta_special_days`,
        ADD COLUMN `" . $field . "_contract` TEXT NULL DEFAULT NULL AFTER `" . $field . "_epascode`,
        ADD COLUMN `" . $field . "_permit` VARCHAR(255) NULL DEFAULT NULL AFTER `" . $field . "_contract`,
        ADD COLUMN `" . $field . "_clearance` TEXT NULL DEFAULT NULL AFTER `" . $field . "_permit`,
        ADD COLUMN `" . $field . "_intro` VARCHAR(255) NULL DEFAULT NULL AFTER `" . $field . "_clearance`,
        ADD COLUMN `" . $field . "_sched` VARCHAR(50) NULL DEFAULT NULL AFTER `" . $field . "_intro`,
        ADD COLUMN `" . $field . "_days` VARCHAR(50) NULL DEFAULT NULL AFTER `" . $field . "_sched`,
        ADD COLUMN `" . $field . "_special_sched` VARCHAR(50) NULL DEFAULT NULL AFTER `" . $field . "_days`,
        ADD COLUMN `" . $field . "_special_days` VARCHAR(50) NULL DEFAULT NULL AFTER `" . $field . "_special_sched`");
    }

    public function update_promo_business_unit($data)
    {
        $update = array(
            'bunit_name' => $data['bunit_name'],
            'bunit_acronym' => $data['bunit_acronym'],
            'hrd_location' => $data['hrd_location']
        );
        $this->db->where('bunit_id', $data['bunit_id']);
        return $this->db->update('locate_promo_business_unit', $update);
    }
}
