<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Setup_model extends CI_Model
{
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

    public function check_company($pc_name)
    {
        return $this->db->from('locate_promo_company')
            ->where('pc_name', $pc_name)
            ->count_all_results();
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
}
