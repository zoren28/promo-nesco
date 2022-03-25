<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Outlet extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('nativesession');

        if (!isset($_SESSION['emp_id'])) {

            redirect('http://' . $_SERVER['SERVER_ADDR'] . ":" . $_SERVER['SERVER_PORT'] . '/hrms/nesco');
        }

        $this->load->model('placement/outlet_model');
        $this->load->model('placement/contract_model');
        $this->load->model('placement/employee_model');
        $this->load->model('placement/dashboard_model');
    }

    public function promo_details()
    {
        $emp_id = $this->input->get('empId', TRUE);

        $data['employee'] = $this->employee_model->get_promo_details($emp_id);
        $data['request'] = "promo_details";

        $this->load->view('body/placement/modal_response', $data);
    }

    public function add_outlet_form()
    {
        $data['emp_id'] = $this->input->get('empId', TRUE);
        $data['request'] = "add_outlet_form";

        $this->load->view('body/placement/modal_response', $data);
    }

    public function add_new_outlet()
    {
        $fetch = $this->input->post(NULL, TRUE);
        $emp = explode('*', $fetch['employee']);

        $this->db->trans_start();

        $this->contract_model->update_employment_history(trim($emp[0]), $fetch['effective_on'], $fetch['remarks'], $fetch['store']);
        $this->contract_model->change_outlet_record(trim($emp[0]), $fetch);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            echo json_encode(['message' => 'Opps! Something went wrong.']);
            // generate an error... or use the log_message() function to log your error
        } else {

            echo json_encode(['message' => 'success']);
        }
    }

    public function change_outlet_histories()
    {
        $result = $this->outlet_model->change_outlet_histories();

        $data = array();
        $no = 0;
        foreach ($result as $row) {

            $no++;

            $sub_array = array();
            $sub_array[] = "$no.";
            $sub_array[] = '<a href="http://' . $_SERVER['SERVER_ADDR'] . ":" . $_SERVER['SERVER_PORT'] . '/hrms/promo-nesco/placement/page/menu/employee/profile/' . $row->emp_id . '" target="_blank">' . htmlentities(ucwords(strtolower($row->name))) . '</a>';
            $sub_array[] = date('m/d/Y', strtotime($row->effectiveon));
            $sub_array[] = $row->changefrom;
            $sub_array[] = $row->changeto;
            $data[] = $sub_array;
        }

        echo json_encode(array("data" => $data));
    }
}
