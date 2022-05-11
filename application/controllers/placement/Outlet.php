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

    public function transfer_outlet_form()
    {
        $emp_id = $this->input->get('empId', TRUE);
        $details = $this->outlet_model->employee_details($emp_id);

        $data['details'] = $details;
        $data['request'] = "transfer_outlet_form";

        $this->load->view('body/placement/modal_response', $data);
    }

    public function store_clearance_form()
    {
        $data['fetch'] = $this->input->get(NULL, TRUE);
        $data['request'] = "store_clearance_form";

        $this->load->view('body/placement/modal_response', $data);
    }

    public function uploadClearance()
    {
        $fetch = $this->input->post(NULL, TRUE);

        $clearanceFlag = "";
        foreach ($fetch['clearances'] as $key => $value) {

            $destination_path = "";
            if (!empty($_FILES[$value]['name'])) {

                $image        = addslashes(file_get_contents($_FILES[$value]['tmp_name']));
                $image_name   = addslashes($_FILES[$value]['name']);
                $array     = explode(".", $image_name);

                $filename     = $fetch['emp_id'] . "=" . date('Y-m-d') . "=" . $value . "=" . date('H-i-s-A') . "." . end($array);
                $destination_path    = "../document/clearance/" . $filename;

                if (move_uploaded_file($_FILES[$value]['tmp_name'], $destination_path)) {

                    $this->employee_model->upload_scanned_file('promo_record', $value, $destination_path, $fetch['emp_id'], $fetch['record_no']);
                    $clearanceFlag = "true";
                }
            }
        }

        $name = $this->employee_model->employee_name($fetch['emp_id'])['name'];

        if ($clearanceFlag == 'true') {

            $activity = "Uploaded the scanned Clearance for Transfer Outlet of " . $name . " Record No." . $fetch['record_no'];
            $this->employee_model->logs($_SESSION['emp_id'], $_SESSION['username'], date("Y-m-d"), date("H:i:s"), $activity);
            echo json_encode(['message' => 'success']);
        } else {

            echo json_encode(['message' => 'Opps! Something went wrong.']);
        }
    }

    public function transfer_details_form()
    {
        $data['details'] = $this->input->get(NULL, TRUE);
        $data['request'] = "transfer_details_form";

        $this->load->view('body/placement/modal_response', $data);
    }

    public function transfer_outlet()
    {
        $fetch = $this->input->post(NULL, TRUE);

        $this->db->trans_start();

        $this->contract_model->update_employment_history($fetch['emp_id'], $fetch['effective_on'], $fetch['remarks'], $fetch['store']);
        $this->contract_model->change_outlet_record($fetch['emp_id'], $fetch);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            echo json_encode(['message' => 'Opps! Something went wrong.']);
            // generate an error... or use the log_message() function to log your error
        } else {

            echo json_encode(['message' => 'success']);
        }
    }

    public function remove_outlet_form()
    {
        $emp_id = $this->input->get('empId', TRUE);
        $details = $this->outlet_model->employee_details($emp_id);

        $data['details'] = $details;
        $data['request'] = "remove_outlet_form";

        $this->load->view('body/placement/modal_response', $data);
    }

    public function remove_outlet()
    {
        $fetch = $this->input->post(NULL, TRUE);

        $stores = array();
        foreach ($fetch['bUs'] as $key => $value) {
            $bU = explode('/', $value);

            if (!in_array(end($bU), $fetch['fields'])) {
                $stores[] = $value;
            }
        }

        $fetch['store'] = $stores;
        $fetch['effective_on'] = date('Y-m-d');

        $clearanceFlag = "";
        foreach ($fetch['clearances'] as $key => $value) {

            $destination_path = "";
            if (!empty($_FILES[$value]['name'])) {

                $image_name   = addslashes($_FILES[$value]['name']);
                $array     = explode(".", $image_name);

                $filename     = $fetch['emp_id'] . "=" . date('Y-m-d') . "=" . $value . "=" . date('H-i-s-A') . "." . end($array);
                $destination_path    = "../document/clearance/" . $filename;

                if (move_uploaded_file($_FILES[$value]['tmp_name'], $destination_path)) {

                    $this->employee_model->upload_scanned_file('promo_record', $value, $destination_path, $fetch['emp_id'], $fetch['record_no']);
                    $clearanceFlag = "true";
                }
            }
        }

        $name = $this->employee_model->employee_name($fetch['emp_id'])['name'];

        if ($clearanceFlag == 'true') {

            $activity = "Uploaded the scanned Clearance for Remove Outlet of " . $name . " Record No." . $fetch['record_no'];
            $this->employee_model->logs($_SESSION['emp_id'], $_SESSION['username'], date("Y-m-d"), date("H:i:s"), $activity);

            $this->db->trans_start();

            $this->contract_model->update_employment_history($fetch['emp_id'], date('Y-m-d'), '', $stores);
            $this->contract_model->change_outlet_record($fetch['emp_id'], $fetch);

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                echo json_encode(['message' => 'Opps! Something went wrong.']);
                // generate an error... or use the log_message() function to log your error
            } else {

                echo json_encode(['message' => 'success']);
            }
        } else {

            echo json_encode(['message' => 'Opps! Something went wrong.']);
        }
    }
}
