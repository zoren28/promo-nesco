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
		//$this->load->model('placement/page_model');
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
}