<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Resignation_model extends CI_Model
{
    public $db2 = '';
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
        $this->db2 = $this->load->database('timekeeping', TRUE);

        $this->db->query('SET SESSION sql_mode = ""');

        $this->column_order = array('employee3.name', 'employee3.added_by', 'employee3.date_updated', 'termination.date', 'termination.remarks');
        $this->search_column = array('employee3.name', 'employee3.added_by', 'employee3.date_updated', 'termination.date', 'termination.remarks'); //set column field database for datatable searchable 
        $this->order = array('employee3.name' => 'ASC'); // default order 
    }

    public function resignation_list()
    {
        $this->make_query();
        if (@$_POST["length"] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_filtered_data()
    {
        $this->make_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function get_all_data()
    {
        $this->db->from('employee3');
        $this->db->join('users', 'users.emp_id = employee3.emp_id');
        if ($this->hr ==  'nesco') {
            $this->db->where('emp_type', 'Promo-NESCO');
        } else {
            $this->db->like('emp_type', 'Promo', 'after');
        }
        return $this->db->count_all_results();
    }

    private function make_query()
    {
        $this->db->from('employee3');
        $this->db->join('termination', 'termination.emp_id = employee3.emp_id');
        if ($this->hr ==  'nesco') {
            $this->db->where('emp_type', 'Promo-NESCO');
        } else {
            $this->db->like('emp_type', 'Promo', 'after');
        }

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

    public function show_termination($termination_no)
    {
        $query = $this->db->get_where('termination', array('termination_no' => $termination_no));
        return $query->row();
    }

    public function submit_resignation_letter($data)
    {
        $update = array(
            'resignation_letter' => $data['destination'],
            'date_updated' => $this->datetime
        );
        $this->db->where('termination_no', $data['termination_no']);
        return $this->db->update('termination', $update);
    }

    public function upload_clearance($emp_id, $clearances)
    {
        foreach ($clearances as $clearance) {
            $this->db->set($clearance['clearance'], $clearance['path']);
        }
        $this->db->where('emp_id', $emp_id);
        return $this->db->update('promo_record');
    }

    public function store_termination($data)
    {
        $insert = array(
            'emp_id' => $data['emp_id'],
            'date' => date('Y-m-d', strtotime($data['dateEffective'])),
            'remarks' => $data['remarks'],
            'resignation_letter' => $data['resignation_path'],
            'added_by' => $this->loginId,
            'date_updated' => $this->date
        );
        return $this->db->insert('termination', $insert);
    }

    public function update_employee3($data)
    {
        $update = array(
            'current_status' => $data['rt_status'],
            'remarks' => $data['remarks']
        );
        $this->db->where('emp_id', $data['emp_id']);
        return $this->db->update('employee3', $update);
    }

    public function inactive_user($emp_id)
    {
        $update = array(
            'user_status' => 'inactive'
        );
        $this->db->where('emp_id', $emp_id);
        return $this->db->update('users', $update);
    }

    public function show_resignation_status($ratee, $status, $rater)
    {
        return $this->db->from('tag_for_resignation')
            ->where(array('ratee_id' => $ratee, 'tag_stat' => $status, 'rater_id' => $rater))
            ->count_all_results();
    }

    public function check_promo_epas($emp_id, $record_no, $epas)
    {
        $this->db->from('promo_record');
        $this->db->where('record_no', $record_no);
        $this->db->where('emp_id', $emp_id);
        foreach ($epas as $key => $field) {
            if ($key == 0) {
                $this->db->where($field, '');
            } else {
                $this->db->or_where($field, '');
            }
        }
        return $this->db->count_all_results();
    }

    public function store_tag_for_resignation($data)
    {
        $insert = array(
            'ratee_id' => $data['emp_id'],
            'rater_id' => $data['rater'],
            'added_by' => $this->loginId,
            'date_added' => $this->date,
            'tag_stat' => 'Pending'
        );
        return $this->db->insert('tag_for_resignation', $insert);
    }

    public function delete_tag_for_resignation($data)
    {
        return $this->db->delete('tag_for_resignation', array('ratee_id' => $data['emp_id'], 'rater_id' => $data['rater']));
    }

    public function get_scpr_id($data)
    {
        $scpr_id = '';
        $query = $this->db->select('scpr_id')
            ->get_where('secure_clearance_promo', array('emp_id' => $data['emp_id'], 'promo_type' => $data['promo_type'], 'status' => 'Pending'));

        if ($query->num_rows() > 0) {
            $scpr_id = $query->row_array()['scpr_id'];
        }

        return $scpr_id;
    }

    public function show_secure_clearance_promo($scpr_id)
    {
        $query = $this->db->get_where('secure_clearance_promo', array('scpr_id' => $scpr_id));
        return $query->row();
    }

    public function check_secure_clearance_details($emp_id, $store)
    {
        return $this->db->from('secure_clearance_promo_details')
            ->where(array('emp_id' => $emp_id, 'store' => $store, 'clearance_status' => 'Pending'))
            ->count_all_results();
    }

    public function check_upload_clearance_details($emp_id, $store, $status)
    {
        $scpr_id = $this->fetch_scpr_id($emp_id);
        $this->db->from('secure_clearance_promo_details')
            ->where(array('emp_id' => $emp_id, 'store' => $store, 'scpr_id' => $scpr_id));

        if ($status) {
            $this->db->where('clearance_status', $status);
        }

        return $this->db->count_all_results();
    }

    public function fetch_scpr_id($emp_id)
    {
        $query = $this->db->select_max('scpr_id')
            ->get_where('secure_clearance_promo', array('emp_id' => $emp_id));
        $scpr = $query->row();
        return $scpr->scpr_id;
    }

    public function store_secure_clearance_promo($data)
    {
        $insert = array(
            'emp_id' => $data['emp_id'],
            'promo_type' => $data['promo_type'],
            'reason' => $data['reason'],
            'date_added' => $this->datetime,
            'added_by' => $this->loginId,
            'status' => 'Pending',
        );

        $this->db->insert('secure_clearance_promo', $insert);
        return $this->db->insert_id();
    }

    public function update_employee_status($data)
    {
        $update = array(
            'current_status' => $data['status'],
            'sub_status' => $data['sub_status']
        );

        $this->db->where('emp_id', $data['emp_id']);
        $this->db->update('employee3', $update);
    }

    function store_secure_clearance_promo_details($data)
    {
        if (!isset($data['date_of_resignation'])) {
            $date_effectivity = $this->date;
        } else {
            $date_effectivity = date('Y-m-d', strtotime($data['date_of_resignation']));
        }

        $insert = array(
            'scpr_id' => $data['scpr_id'],
            'emp_id' => $data['emp_id'],
            'store' => $data['store'],
            'date_activefor_resign' => $data['dateforactiveresign'],
            'date_secure' => $this->date,
            'date_effectivity' => $date_effectivity,
            'date_uncleared' => $data['dateuncleared'],
            'resignation_letter' => $data['resignation_path'],
            'added_by' => $this->loginId,
            'clearance_status' => 'Pending'
        );

        $this->db->insert('secure_clearance_promo_details', $insert);
        return $this->db->insert_id();
    }

    public function store_secure_clearance_deceased($data, $scpr_id)
    {
        $insert = array(
            'sc_id' => $scpr_id,
            'emp_id' => $data['emp_id'],
            'claimant' => $data['claimant'],
            'relation' => $data['relation'],
            'dateofdeath' => date('Y-m-d', strtotime($data['date_of_death'])),
            'causeofdeath' => $data['cause_of_death'],
            'authorization_letter' => $data['authorization_path']
        );

        $this->db->insert('secure_clearance_deceased', $insert);
    }

    public function employees_ratee($emp_id)
    {
        $query = $this->db->select('ratee')
            ->get_where('leveling_subordinates', array('subordinates_rater' => $emp_id));
        return $query->result();
    }

    public function browse_epas($record_no, $emp_id, $store)
    {
        $query = $this->db->get_where('appraisal_details', array('record_no' => $record_no, 'emp_id' => $emp_id, 'store' => $store));
        return $query->row();
    }

    public function secure_clearance_promo($data)
    {
        if (isset($data['emp_id'])) {
            $this->db->where('emp_id', $data['emp_id']);
        }

        if (isset($data['status'])) {
            $this->db->where('status', $data['status']);
        }

        if (isset($data['promo_type'])) {
            $this->db->where('promo_type', $data['promo_type']);
        }

        $query = $this->db->get('secure_clearance_promo');
        return $query->row();
    }

    public function secure_clearance_promo_details($data)
    {
        if (isset($data['emp_id'])) {
            $this->db->where('emp_id', $data['emp_id']);
        }

        if (isset($data['scpr_id'])) {
            $this->db->where('scpr_id', $data['scpr_id']);
        }

        if (isset($data['store'])) {
            $this->db->where('store', $data['store']);
        }

        if (isset($data['clearance_status'])) {
            $this->db->where('clearance_status', $data['clearance_status']);
        }

        $query = $this->db->get('secure_clearance_promo_details');
        return $query->row();
    }

    public function update_secure_clearance_promo_details($data)
    {
        $update = array(
            'date_cleared' => $this->date,
            'clearance_status' => 'Completed'
        );

        $this->db->where(array('emp_id' => $data['emp_id'], 'clearance_status' => 'Pending', 'store' => $data['store']));
        return $this->db->update('secure_clearance_promo_details', $update);
    }

    public function promo_locate_business_unit($data)
    {
        if (isset($data['bunit_id'])) {
            $this->db->where('bunit', $data['bunit_id']);
        }

        if (isset($data['bunit_name'])) {
            $this->db->where('bunit_name', $data['bunit_name']);
        }

        if (isset($data['bunit_field'])) {
            $this->db->where('bunit_field', $data['bunit_field']);
        }

        $query = $this->db->get('locate_promo_business_unit');
        return $query->row();
    }

    public function update_promo_record($data)
    {
        $update = array(
            $data['bunit_clearance'] => $data['clearance_path']
        );

        $this->db->where('emp_id', $data['emp_id']);
        $this->db->update('promo_record', $update);
    }

    public function update_secure_clearance_promo($emp_id)
    {
        $this->db->set('status', 'Completed');
        $this->db->where(array('emp_id' => $emp_id, 'status' => 'Pending'));
        $this->db->update('secure_clearance_promo');
    }

    public function store_secure_clearance_reprint($data)
    {
        $insert = array(
            'sc_id' => $data['scpr_id'],
            'reason' => $data['reason'],
            'date' => $this->datetime,
            'generatedby' => $this->loginId
        );
        return $this->db->insert('secure_clearance_reprint', $insert);
    }

    public function fetch_secured_clearance_promo()
    {
        $query = $this->db->order_by('scpr_id', 'DESC')
            ->get('secure_clearance_promo');
        return $query->result();
    }

    public function show_secured_clerance_details($scpr_id)
    {
        $query = $this->db->get_where('secure_clearance_promo_details', array('scpr_id' => $scpr_id));
        return $query->result();
    }

    public function fetch_authorization_letter($emp_id)
    {
        $query = $this->db->get_where('secure_clearance_deceased', array('emp_id' => $emp_id));
        $auth = $query->row();
        $letter = '';
        if (!empty($auth)) {

            $letter = $auth->authorization_letter;
        }

        return $letter;
    }
}
