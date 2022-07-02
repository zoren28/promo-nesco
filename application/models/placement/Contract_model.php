<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Contract_model extends CI_Model
{
    public $tk;
    public $tk_talibon;
    public $tk_tubigon;
    public $date;
    public $loginId;
    public $hrd_location;
    public $column_order = array();
    public $search_column = array();
    public $order = array();

    function __construct()
    {
        parent::__construct();
        $this->load->library('nativesession');

        $this->tk = $this->load->database('timekeeping', TRUE);
        // $this->tk_talibon = $this->load->database('talibon', TRUE);
        // $this->tk_tubigon = $this->load->database('tubigon', TRUE);

        $this->date = date('Y-m-d');
        $this->loginId = $_SESSION['emp_id'];
        $this->hrd_location = 'asc';

        $this->column_order = array('record_no', 'emp_id', 'name', 'startdate', 'eocdate');
        $this->search_column = array('record_no', 'emp_id', 'name', 'startdate', 'eocdate'); //set column field database for datatable searchable 
        $this->order = array('name' => 'ASC'); // default order 
    }

    public function update_employment_history($emp_id, $startdate, $remarks, $stores)
    {
        // fetch employee3
        $query = $this->db->get_where('employee3', array('emp_id' => $emp_id));
        foreach ($query->result() as $row) {

            $fields1 = array();
            foreach ($row as $field => $value) {

                // echo $field . '== ' . $value . '<br>';
                $fields = array('record_no', 'tag_as', 'date_added', 'added_by', 'Details', 'DepCode', 'Branch', 'Branchcode', 'tag_request', 'sub_status');
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

    public function show_bu_details($id)
    {
        $query = $this->db->get_where('locate_promo_business_unit', array('bunit_id' => $id));
        return $query->row();
    }

    public function find_witness($data)
    {
        return $this->db->select("DISTINCT(" . $data['witness'] . ") as witness")
            ->from('employment_witness')
            ->like($data['witness'], $data['str'])
            ->or_where('emp_id', $data['str'])
            ->order_by($data['witness'], 'ASC')
            ->limit(10)
            ->get();
    }

    public function store_application_otherreq($emp_id, $intro, $path)
    {
        $insert = array(
            'app_id' => $emp_id,
            'requirement_name' => 'intro',
            'filename' => $path,
            'date_time' => $this->date,
            'requirement_status' => 'passed',
            'receiving_staff' => $this->employee_model->loginId
        );

        $this->db->insert('application_otherreq', $insert);
    }

    public function update_employment_contract($data, $intros_path)
    {
        $company_duration = '';
        if (isset($data['companyDuration'])) {
            $company_duration = date('Y-m-d', strtotime($data['companyDuration']));
        }

        $startdate   = date("Y-m-d", strtotime($data['startdate']));
        $eocdate     = date("Y-m-d", strtotime($data['eocdate']));
        $duration    = $data['duration'];
        $witness1    = $data['witness1'];
        $witness2    = $data['witness2'];
        $comments    = $data['comments'];
        $remarks     = $data['remarks'];

        if ($data['edited'] == "true") {

            $stores      = $data['stores'];
            $agency_code = $data['agency_select'];
            $pc_code     = $data['company_select'];
            $promo_type  = $data['promoType_select'];
            $department  = $data['department_select'];
            $vendor_code = $data['vendor_select'];

            if (isset($data['product_select'])) {
                $products  = $data['product_select'];
            } else {
                $products  = array();
            }

            $position    = $data['position_select'];
            $positionlevel = $data['level'];
            $emp_type    = $data['empType_select'];
            $contract_type = $data['contractType_select'];
            $statCut     = $data['cutoff_select'];
        } else {

            $stores      = $data['business_units'];
            $agency_code = $data['agency'];
            $pc_code     = $data['company'];
            $promo_type  = $data['promoType'];
            $department  = $data['department'];
            $vendor_code = $data['vendor'];
            $products    = explode("|", $data['product']);
            $position    = $data['position'];
            $positionlevel = $data['positionlevel'];
            $emp_type    = $data['empType'];
            $contract_type = $data['contractType'];
            $statCut     = $data['cutoff'];
        }

        $company_name = $this->employee_model->get_company_name($pc_code)->pc_name;

        // fetch employee3
        $query = $this->db->get_where('employee3', array('record_no' => $data['recordNo'], 'emp_id' => $data['empId']));
        $old_data = $query->row();

        $fields1 = array();
        foreach ($old_data as $field => $value) {

            // echo $field . '== ' . $value . '<br>';
            $fields = array('record_no', 'tag_as', 'date_added', 'added_by', 'Details', 'DepCode', 'Branch', 'Branchcode', 'tag_request', 'sub_status');
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
            ->where(array('record_no' => $old_data->record_no, 'emp_id' => $old_data->emp_id))
            ->update('appraisal_details');

        // fetch promo record
        $query = $this->db->get_where('promo_record', array('record_no' => $old_data->record_no, 'emp_id' => $old_data->emp_id));
        $old_promo_data = $query->row();

        // insert the data to promo_history_table table
        $fields2 = array();
        foreach ($old_promo_data as $field => $value) {

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
        $this->db->delete('employee3', array('record_no' => $old_data->record_no, 'emp_id' => $old_data->emp_id));

        // delete promo_record
        $this->db->delete('promo_record', array('record_no' => $old_data->record_no, 'emp_id' => $old_data->emp_id));

        $insert = array(
            'emp_id'    => $data['empId'],
            'emp_no'    => $old_data->emp_no,
            'emp_pins'  => $old_data->emp_pins,
            'barcodeId' => $old_data->barcodeId,
            'bioMetricId'   => $old_data->bioMetricId,
            'payroll_no'    => $old_data->payroll_no,
            'name'      => $old_data->name,
            'startdate' => $startdate,
            'eocdate'   => $eocdate,
            'emp_type'  => $emp_type,
            'current_status' => 'Active',
            'position'      => $position,
            'positionlevel' => $positionlevel,
            'poslevel' => $positionlevel,
            'comments'  => $comments,
            'remarks'   => $remarks,
            'date_added'    => $this->employee_model->date,
            'added_by'  => $this->employee_model->loginId,
            'duration'  => $duration
        );

        $this->db->insert('employee3', $insert);
        $record_no = $this->db->insert_id();

        // update employment_witness table
        $update = array(
            'rec_no' => $record_no,
            'witness1' => $witness1,
            'witness2' => $witness2
        );
        $this->db->where(array('rec_no' => $old_data->record_no, 'emp_id' => $old_data->emp_id))
            ->update('employment_witness', $update);

        foreach ($stores as $key => $value) {

            $bunit_field = explode('/', $value);
            $this->db->set(end($bunit_field), 'T');
        }

        foreach ($data['bunit_intro'] as $key => $value) {

            $this->db->set($value, $intros_path[$value]);
        }

        $this->db->set('record_no', $record_no);
        $this->db->set('emp_id', $data['empId']);
        $this->db->set('agency_code', $agency_code);
        $this->db->set('promo_company', $company_name);
        $this->db->set('promo_department', $department);
        $this->db->set('vendor_code', $vendor_code);
        $this->db->set('company_duration', $company_duration);
        $this->db->set('promo_type', $promo_type);
        $this->db->set('type', $contract_type);
        $this->db->set('hr_location', $this->hrd_location);
        $this->db->insert('promo_record');

        // update promo_products table
        $this->db->set('record_no', $previous_record_no)
            ->where(array('record_no' => $old_data->record_no, 'emp_id' => $data['empId']))
            ->update('promo_products');

        // insert promo_products table

        if (is_array($products)) {

            // $this->db->delete('promo_products', array('record_no' => $record_no, 'emp_id' => $data['empId']));
            foreach ($products as $key => $value) {

                $insert = array(
                    'record_no' => $record_no,
                    'emp_id' => $data['empId'],
                    'product' => $value
                );
                $this->db->insert('promo_products', $insert);
            }
        }

        $corporate = '';
        $talibon = '';
        $tubigon = '';
        $bUs = $this->businessUnit_list();
        foreach ($bUs as $bu) {

            $hasBU = $this->promo_has_bu($data['empId'], $bu->bunit_field);
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

        $this->update_promo_cutoff('corporate', $statCut, $data['empId'], $record_no, $previous_record_no);
        $this->insert_promo_cutoff('corporate', $statCut, $data['empId'], $record_no);

        /* if ($talibon == 'true') {

            $this->update_promo_cutoff('talibon', $statCut, $data['empId'], $record_no, $previous_record_no);
            $this->insert_promo_cutoff('talibon', $statCut, $data['empId'], $record_no);
        }

        if ($tubigon == 'true') {

            $this->update_promo_cutoff('tubigon', $statCut, $data['empId'], $record_no, $previous_record_no);
            $this->insert_promo_cutoff('tubigon', $statCut, $data['empId'], $record_no);
        } */

        return $record_no;
    }

    public function get_employee_details($emp_id)
    {
        $query = $this->db->select('record_no, name')
            ->get_where('employee3', array('emp_id' => $emp_id));
        return $query->row();
    }

    public function get_shiftcodes()
    {
        $query = $this->tk->from('shiftcodes')
            ->order_by('1stIn', 'ASC')
            ->order_by('1stOut', 'ASC')
            ->order_by('2ndIn', 'ASC')
            ->order_by('2ndOut', 'ASC')
            ->get();
        return $query->result_array();
    }

    public function get_promo_cutoff($record_no, $emp_id)
    {
        $query = $this->tk->select('promo_sched_emp.statCut, startFC, endFC, startSC, endSC')
            ->from('promo_sched_emp')
            ->join('promo_schedule', 'promo_schedule.statCut = promo_sched_emp.statCut')
            ->where(array('recordNo' => $record_no, 'empId' => $emp_id))
            ->get();
        return $query->row();
    }

    public function show_shiftcode($shiftcode)
    {
        $query = $this->tk->select('1stIn, 1stOut, 2ndIn, 2ndOut')
            ->get_where('shiftcodes', array('shiftCode' => $shiftcode));
        return $query->row_array();
    }

    public function edit_promo_record($data, $duty_sched, $special_sched)
    {
        $cO = explode('|', $data['cutOff']);
        $store = explode('|', $data['storeName']);

        $special_days = '';
        if ($data['specialSched'] != '') {

            $special_days = $data['specialDays'];
        }

        $update = array(
            $store[2] => $duty_sched,
            $store[3] => $data['dutyDays'],
            $store[4] => $special_sched,
            $store[5] => $special_days,
            'dayoff'  => $data['dayOff'],
            'cutoff'  => end($cO)
        );

        $this->db->where(array('record_no' => $data['record_no'], 'emp_id' => $data['empId']));
        return $this->db->update('promo_record', $update);
    }

    public function show_employment_witness($record_no, $emp_id)
    {
        $query = $this->db->get_where('employment_witness', array('rec_no' => $record_no, 'emp_id' => $emp_id));
        return $query->row();
    }

    public function show_applicant_otherdetails($app_id)
    {
        $query = $this->db->get_where('applicant_otherdetails', array('app_id' => $app_id));
        return $query->row();
    }

    public function contract_header_list()
    {
        $query = $this->db->order_by('company', 'ASC')
            ->get_where('contract_header', array('hr_location' => $this->hrd_location));
        return $query->result();
    }

    public function get_employment_witness($record_no, $emp_id)
    {
        $query = $this->db->select('COUNT(ew_no) AS witness')
            ->get('employment_witness', array('rec_no' => $record_no, 'emp_id' => $emp_id));
        return $query->row()->witness;
    }

    public function update_employment_witness($data)
    {
        $this->db->set('witness1', $data['witness1Renewal']);
        $this->db->set('witness2', $data['witness2Renewal']);
        $this->db->set('contract_header_no', $data['contractHeader']);

        if ($data['clear'] == 'Cedula') {

            $this->db->set('sss_ctc', $data['clear']);
            $this->db->set('sssno_ctcno', $data['cedula']);
            $this->db->set('issuedon', date('Y-m-d', strtotime($data['issuedOn'])));
            $this->db->set('issuedat', $data['issuedAt']);
        } else {

            $this->db->set('sss_ctc', $data['clear']);
            $this->db->set('sssno_ctcno', $data['sss']);
            $this->db->set('issuedat', $data['issuedAt']);
        }


        $this->db->set('date_generated', date("Y-m-d", strtotime($data['contractDate'])));
        $this->db->set('generated_by', $this->loginId);
        $this->db->where(array('rec_no' => $data['contract_recordNo'], 'emp_id' => $data['empId']));
        $this->db->update('employment_witness');
    }

    public function store_employment_witness($data)
    {
        $this->db->set('rec_no', $data['contract']);
        $this->db->set('emp_id', $data['witness1Renewal']);
        $this->db->set('witness1', $data['witness1Renewal']);
        $this->db->set('witness2', $data['witness2Renewal']);
        $this->db->set('contract_header_no', $data['contractHeader']);

        if ($data['clear'] == 'Cedula') {

            $this->db->set('sss_ctc', $data['clear']);
            $this->db->set('sssno_ctcno', $data['cedula']);
            $this->db->set('issuedon', date('Y-m-d', strtotime($data['issuedOn'])));
            $this->db->set('issuedat', $data['issuedAt']);
        } else {

            $this->db->set('sss_ctc', $data['clear']);
            $this->db->set('sssno_ctcno', $data['sss']);
            $this->db->set('issuedat', $data['issuedAt']);
        }

        $this->db->set('date_generated', date("Y-m-d", strtotime($data['contractDate'])));
        $this->db->set('generated_by', $this->loginId);
        $this->db->insert('employment_witness');
    }

    public function get_application_otherdetails($app_id)
    {
        $query = $this->db->select('COUNT(no) as other_details')
            ->get_where('applicant_otherdetails', array('app_id' => $app_id));
        return $query->row()->other_details;
    }

    public function update_applicant_otherdetails($data)
    {
        if ($data['clear'] == 'Cedula') {

            $this->db->set('cedula_no', $data['cedula']);
        } else {

            $this->db->set('sss_no', $data['sss']);
        }

        $this->db->set('cedula_date', date('Y-m-d', strtotime($data['issuedOn'])));
        $this->db->set('cedula_place', $data['issuedAt']);
        $this->db->set('recordedby', $this->loginId);
        $this->db->where('app_id', $data['empId']);
        $this->db->update('applicant_otherdetails');
    }

    public function store_applicant_otherdetails($data)
    {
        $this->db->set('app_id', $data['empId']);

        if ($data['clear'] == 'Cedula') {

            $this->db->set('cedula_no', $data['cedula']);
        } else {

            $this->db->set('sss_no', $data['sss']);
        }

        $this->db->set('cedula_date', date('Y-m-d', strtotime($data['issuedOn'])));
        $this->db->set('cedula_place', $data['issuedAt']);
        $this->db->set('recordedby', $this->loginId);
        $this->db->insert('applicant_otherdetails');
    }

    public function show_previous_contracts($emp_id)
    {
        $query = $this->db->select('employmentrecord_.record_no, employmentrecord_.emp_id, startdate, eocdate')
            ->from('employmentrecord_')
            ->join('promo_history_record', 'promo_history_record.record_no = employmentrecord_.record_no AND promo_history_record.emp_id = employmentrecord_.emp_id')
            ->where('employmentrecord_.emp_id', $emp_id)
            ->order_by('startdate', 'DESC')
            ->get();
        return $query->result();
    }

    public function show_previous_contract($data)
    {
        $query = $this->db->select('employmentrecord_.record_no, employmentrecord_.emp_id, names, promo_type')
            ->from('employmentrecord_')
            ->join('promo_history_record', 'promo_history_record.record_no = employmentrecord_.record_no AND promo_history_record.emp_id = employmentrecord_.emp_id')
            ->where(array('employmentrecord_.record_no' => $data['record_no'], 'employmentrecord_.emp_id' => $data['emp_id']))
            ->get();
        return $query->row();
    }

    public function get_appraisal_details($record_no, $emp_id)
    {
        return $this->db->select('details_id, rater, numrate, store')
            ->get_where('appraisal_details', array('record_no' => $record_no, 'emp_id' => $emp_id));
    }

    public function transfer_rate($data, $record_no)
    {
        $this->db->trans_start();

        $error = array();
        foreach ($data['appraisal'] as $key => $value) {

            $appraisal = explode('|', $value);

            // get bunit_epascode via store name
            $query = $this->db->select('bunit_epascode')
                ->get_where('locate_promo_business_unit', array('bunit_name' => $appraisal[2]));
            $epas = $query->row_array();

            $query = $this->db->select($epas['bunit_epascode'])
                ->get_where('promo_record', array('emp_id' => $data['emp_id']));
            $promo = $query->row();

            if ($promo->$epas['bunit_epascode'] != '' && $promo->$epas['bunit_epascode'] == '1') {

                // there is already epas_code assigned
                $error[] = 'exist';
            } else {

                // update promo_record table
                $this->db->set($epas['bunit_epascode'], '1');
                $this->db->where('emp_id', $data['emp_id']);
                $this->db->update('promo_record');

                // update promo_history_record table
                $this->db->set($epas['bunit_epascode'], '');
                $this->db->where(array('record_no' => $appraisal[1], 'emp_id' => $data['emp_id']));
                $this->db->update('promo_history_record');

                // update appraisal_details table
                $this->db->set('record_no', $record_no);
                $this->db->where('details_id', $appraisal[0]);
                $this->db->update('appraisal_details');
            }
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            # Something went wrong.
            $this->db->trans_rollback();
            return json_encode(array('status' => FALSE, 'message' => "Something's went wrong!"));
        } else {
            # Everything is Perfect. 
            # Committing data to the database.
            $this->db->trans_commit();

            if (count($data['appraisal']) === count($error)) {

                return json_encode(array('status' => TRUE, 'message' => 'There is already existed rate in the current contract. Please review!'));
            } else {

                return json_encode(array('status' => TRUE, 'message' => 'Rate was successfully transfered!'));
            }
        }
    }

    public function get_eoclist($data)
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
        $this->db->join('promo_record', 'promo_record.emp_id = employee3.emp_id AND promo_record.record_no = employee3.record_no');
        $this->db->where('emp_type', 'Promo-NESCO');

        if (!empty($data['bU'])) {
            $this->db->where($data['bU'], 'T');
        }

        if (!empty($data['date'])) {

            $date = date('Y-m-d');
            if ($data['date'] == 'yesterday') {

                $this->db->where('eocdate', date('Y-m-d', strtotime('-1 days')));
            } else if ($data['date'] == 'last7days') {

                $date_from = date('Y-m-d', strtotime('-7 days'));
                $this->db->where("eocdate BETWEEN '$date_from' AND '$date'");
            } else if ($data['date'] == 'last1month') {

                $date_from = date('Y-m-d', strtotime('-1 month'));
                $this->db->where("eocdate BETWEEN '$date_from' AND '$date'");
            } else {

                $this->db->where('eocdate', date('Y-m-d'));
            }
        } else {

            $eocdate = $data['year'] . '-' . $data['month'];
            $this->db->like('eocdate', $eocdate, 'after');
        }

        return $this->db->count_all_results();
    }

    private function make_query($data)
    {
        $this->db->from('employee3');
        $this->db->join('promo_record', 'promo_record.emp_id = employee3.emp_id AND promo_record.record_no = employee3.record_no');
        $this->db->where('emp_type', 'Promo-NESCO');

        if (!empty($data['bU'])) {
            $this->db->where($data['bU'], 'T');
        }

        if (!empty($data['date'])) {

            if ($data['date'] == 'yesterday') {

                $this->db->where('eocdate', date('Y-m-d', strtotime('-1 days')));
            } else if ($data['date'] == 'last7days') {

                $this->db->where('eocdate', date('Y-m-d', strtotime('-7 days')));
            } else if ($data['date'] == 'last1month') {

                $this->db->where('eocdate', date('Y-m-d', strtotime('-1 month')));
            } else {

                $this->db->where('eocdate', date('Y-m-d'));
            }
        } else {

            $eocdate = $data['year'] . '-' . $data['month'];
            $this->db->like('eocdate', $eocdate, 'after');
        }

        $i = 0;
        foreach ($this->search_column as $item) // loop column 
        {
            if ($_POST['search']['value']) // if datatable send POST for search
            {

                if ($i === 0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like('employee3.' . $item, $_POST['search']['value']);
                } else {
                    $this->db->or_like('employee3.' . $item, $_POST['search']['value']);
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

    public function check_appraisal($data, $record_no, $emp_id)
    {
        return $this->db->select($data->bunit_epascode)
            ->get_where('promo_record', array($data->bunit_field => 'T', 'record_no' => $record_no, 'emp_id' => $emp_id));
    }

    public function get_appraisal($store, $record_no, $emp_id)
    {
        $query = $this->db->select('details_id, numrate, descrate, raterSO, rateeSO')
            ->get_where('appraisal_details', array('record_no' => $record_no, 'emp_id' => $emp_id, 'store' => $store));
        return $query->row();
    }
}
