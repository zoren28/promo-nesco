<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Page_model extends CI_Model
{
    public $usertype = '';

    function __construct()
    {
        parent::__construct();
        $this->load->library('nativesession');
        $this->usertype = $this->get_usertype();
    }

    private function get_usertype()
    {
        $query = $this->db->select('usertype')
            ->get_where('promo_user', array('emp_id' => $_SESSION['emp_id'], 'user_status' => 'active'));
        return $query->row()->usertype;
    }

    public function user_info($emp_id)
    {

        $query = $this->db->select('photo, name, position')
            ->from('applicant')
            ->join('employee3', 'applicant.app_id = employee3.emp_id')
            ->where('employee3.emp_id', $emp_id)
            ->get();
        return $query->row_array();
    }

    public function incharge_menu()
    {
        $this->db->select('id, menu, route, icon, has_submenu, promo1, promo2, nesco');
        $query = $this->db->get_where('promo_placement_menu', array($this->usertype => true, 'status' => true));
        return $query->result_array();
    }

    public function incharge_submenu($id)
    {

        $this->db->select('id, sub_menu, route, promo1, promo2, nesco');
        $query = $this->db->get_where('promo_placement_submenu', array($this->usertype => true, 'status' => true, 'menu_id' => $id));
        return $query->result_array();
    }
}
