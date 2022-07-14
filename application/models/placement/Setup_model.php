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

    public function company_list()
    {
        $query = $this->db->from('locate_promo_company')
            ->order_by('pc_name', 'ASC')
            ->get();
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

    public function agency_list()
    {
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
}
