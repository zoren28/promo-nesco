<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Contract_model extends CI_Model
{
    public $tk;
    public $tk_talibon;
    public $tk_tubigon;
    public $date;
    public $hrd_location;

    function __construct()
    {
        parent::__construct();
        $this->load->library('nativesession');

        $this->tk = $this->load->database('timekeeping', TRUE);
        $this->tk_talibon = $this->load->database('talibon', TRUE);
        $this->tk_tubigon = $this->load->database('tubigon', TRUE);

        $this->date = date('Y-m-d');
        $this->hrd_location = 'asc';
    }

    public function update_employment_history($emp_id, $startdate, $remarks, $stores)
    {
        // fetch employee3
        $query = $this->db->get_where('employee3', array('emp_id' => $emp_id));
        foreach ($query->result() as $row) {

            $fields1 = array();
            foreach ($row as $field => $value) {

                // echo $field . '== ' . $value . '<br>';
                $fields = array('record_no', 'tag_as', 'date_added', 'added_by', 'Details', 'DepCode', 'Branch', 'Branchcode', 'tag_request');
                if (!in_array($field, $fields)) {

                    if ($field == 'name') {

                        $field = 'names';
                    } else if ($field == 'position_desc') {

                        $field = 'pos_desc';
                    } else if ($field == 'updated_by') {

                        $field = 'updatedby';
                    }

                    $fields1[$field] = $value;
                }
            }

            // insert the data to employmentrecord_ table and get its record_no
            $this->db->insert('employmentrecord_', $fields1);
            $previous_record_no = $this->db->insert_id();

            // update appraisal details table
            $this->db->set('record_no', $previous_record_no)
                ->where(array('record_no' => $row->record_no, 'emp_id' => $emp_id))
                ->update('appraisal_details');

            // update employment_witness table
            $this->db->set('rec_no', $previous_record_no)
                ->where(array('rec_no' => $row->record_no, 'emp_id' => $emp_id))
                ->update('employment_witness');

            // fetch promo record
            $query = $this->db->get_where('promo_record', array('record_no' => $row->record_no, 'emp_id' => $emp_id));
            $row2 = $query->row();

            // insert the data to promo_history_table table
            $fields2 = array();
            foreach ($row2 as $field => $value) {

                $fields = array('promo_id');
                if (!in_array($field, $fields)) {

                    if ($field == 'record_no') {

                        $fields2[$field] = $previous_record_no;
                    } else {

                        $fields2[$field] = $value;
                    }
                }
            }
            $this->db->insert('promo_history_record', $fields2);

            // delete employee3
            $this->db->delete('employee3', array('record_no' => $row->record_no, 'emp_id' => $emp_id));

            // delete promo_record
            $this->db->delete('promo_record', array('record_no' => $row->record_no, 'emp_id' => $emp_id));

            // insert employee3

            $dF =  date_create(date('Y-m-d', strtotime($startdate)));
            $dT =  date_create($row->eocdate);

            $interval = date_diff($dF, $dT);
            $duration = $interval->format('%a') + 1;

            if ($duration >= 31) {
                $duration = $interval->format('%m');
            } else {
                $duration = "$duration day(s)";
            }


            $data = array(
                'emp_id'    => $emp_id,
                'emp_no'    => $row->emp_no,
                'emp_pins'  => $row->emp_pins,
                'barcodeId' => $row->barcodeId,
                'bioMetricId'   => $row->bioMetricId,
                'payroll_no'    => $row->payroll_no,
                'name'      => $row->name,
                'startdate' => date('Y-m-d', strtotime($startdate)),
                'eocdate'   => $row->eocdate,
                'emp_type'  => $row->emp_type,
                'current_status' => $row->current_status,
                'position'      => $row->position,
                'positionlevel' => $row->positionlevel,
                'comments'  => $row->comments,
                'remarks'   => $remarks,
                'date_added'    => $this->employee_model->date,
                'added_by'  => $this->employee_model->loginId,
                'duration'  => $duration
            );

            $this->db->insert('employee3', $data);
            $current_record_no = $this->db->insert_id();

            if (count($stores) > 1) {

                $promo_type = 'ROVING';
            } else {

                $promo_type = 'STATION';
            }

            foreach ($stores as $key => $value) {

                $bunit_field = explode('/', $value);
                $this->db->set(end($bunit_field), 'T');
            }

            $this->db->set('record_no', $current_record_no);
            $this->db->set('emp_id', $emp_id);
            $this->db->set('agency_code', $row2->agency_code);
            $this->db->set('promo_company', $row2->promo_company);
            $this->db->set('promo_department', $row2->promo_department);
            $this->db->set('vendor_code', $row2->vendor_code);
            $this->db->set('company_duration', $row2->company_duration);
            $this->db->set('promo_type', $promo_type);
            $this->db->set('type', $row2->type);
            $this->db->set('hr_location', $row2->hr_location);
            $this->db->insert('promo_record');

            // update promo_products table
            $this->db->set('record_no', $previous_record_no)
                ->where(array('record_no' => $row->record_no, 'emp_id' => $emp_id))
                ->update('promo_products');

            // insert promo_products table
            $query = $this->db->get_where('promo_products', array('record_no' => $previous_record_no, 'emp_id' => $emp_id));
            foreach ($query->result() as $product) {

                $data = array(
                    'record_no' => $current_record_no,
                    'emp_id' => $emp_id,
                    'product' => $product->product
                );

                $this->db->insert('promo_products', $data);
            }

            $corporate = '';
            $talibon = '';
            $tubigon = '';
            $bUs = $this->businessUnit_list();
            foreach ($bUs as $bu) {

                $hasBU = $this->promo_has_bu($emp_id, $bu->bunit_field);
                if ($hasBU > 0) {

                    if ($bu->bunit_field == 'al_tal') {
                        $talibon = 'true';
                    } else if ($bu->bunit_field == 'al_tub') {
                        $tubigon = 'true';
                    } else {
                        $corporate = 'true';
                    }
                }
            }

            $cutoff = $this->select_promo_cutoff($emp_id);
            $this->update_promo_cutoff('corporate', $cutoff, $emp_id, $current_record_no, $previous_record_no);
            $this->insert_promo_cutoff('corporate', $cutoff, $emp_id, $current_record_no);

            /* if ($talibon == 'true') {

                $this->update_promo_cutoff('talibon', $cutoff, $emp_id, $current_record_no, $previous_record_no);
                $this->insert_promo_cutoff('talibon', $cutoff, $emp_id, $current_record_no);
            }

            if ($tubigon == 'true') {

                $this->update_promo_cutoff('tubigon', $cutoff, $emp_id, $current_record_no, $previous_record_no);
                $this->insert_promo_cutoff('tubigon', $cutoff, $emp_id, $current_record_no);
            } */
        }
    }

    public function select_promo_cutoff($empId)
    {
        $query = $this->tk->select('statCut')
            ->from('promo_sched_emp')
            ->order_by('peId', 'DESC')
            ->limit(1)
            ->get();
        return $query->row()->statCut;
    }

    public function update_promo_cutoff($server, $statCut, $emp_id, $record_no, $previous_record_no)
    {
        $where = array('recordNo' => $record_no, 'empId' => $emp_id);

        if ($server == 'talibon') {

            $this->tk_talibon->set('recordNo', $previous_record_no)
                ->set('statCut', $statCut)
                ->where($where)
                ->update('promo_sched_emp');
        } else if ($server == 'tubigon') {

            $this->tk_tubigon->set('recordNo', $previous_record_no)
                ->set('statCut', $statCut)
                ->where($where)
                ->update('promo_sched_emp');
        } else {

            $this->tk->set('recordNo', $previous_record_no)
                ->set('statCut', $statCut)
                ->where($where)
                ->update('promo_sched_emp');
        }
    }

    public function insert_promo_cutoff($server, $statCut, $empId, $recordNo)
    {
        $insert = array(
            'statCut' => $statCut,
            'recordNo' => $recordNo,
            'empId' => $empId,
            'date_setup' => $this->date
        );

        if ($server == 'talibon') {

            $this->tk_talibon->insert('promo_sched_emp', $insert);
        } else if ($server == 'tubigon') {

            $this->tk_tubigon->insert('promo_sched_emp', $insert);
        } else {

            $this->tk->insert('promo_sched_emp', $insert);
        }
    }

    public function businessUnit_list()
    {
        $query = $this->db->select('bunit_id, bunit_name, bunit_field, bunit_acronym, bunit_epascode, bunit_clearance, bunit_contract, bunit_intro')
            ->get_where('locate_promo_business_unit', array('status' => 'active', 'hrd_location' => $this->hrd_location));
        return $query->result();
    }

    function promo_has_bu($emp_id, $field)
    {
        $query = $this->db->select('COUNT(promo_id) AS exist')
            ->get_where('promo_record', array('emp_id' => $emp_id, $field => 'T'));
        return $query->row()->exist;
    }

    public function change_outlet_record($emp_id, $data)
    {
        $ctr = 1;
        $storeName = '';
        foreach ($data['store'] as $key => $value) {

            $bunit_field = explode('/', $value);

            $store = $this->fetch_business_unit_field(end($bunit_field));
            if ($ctr == 1) {

                $storeName = $store->bunit_name;
            } else {

                $storeName .= ", " . $store->bunit_name;
            }

            $ctr++;
        }

        $data = array(
            'emp_id' => $emp_id,
            'changefrom' => $data['current_store'],
            'changeto' => $storeName,
            'effectiveon' => date('Y-m-d', strtotime($data['effective_on']))
        );

        $this->db->insert('change_outlet_record', $data);
    }

    public function fetch_business_unit_field($field)
    {
        $query = $this->db->select('bunit_name')
            ->get_where('locate_promo_business_unit', array('bunit_field' => $field));
        return $query->row();
    }
}
