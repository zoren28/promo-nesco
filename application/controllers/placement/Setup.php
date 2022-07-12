<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Setup extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('nativesession');

        if (!isset($_SESSION['emp_id'])) {

            redirect('http://' . $_SERVER['SERVER_ADDR'] . ":" . $_SERVER['SERVER_PORT'] . '/hrms/nesco');
        }

        $this->load->model('placement/setup_model');
    }

    public function company_list()
    {
        $companies = $this->setup_model->company_list();
        $data = array();
        foreach ($companies as $company) {

            $id = $company->pc_code;
            if (isset($_SERVER['SERVER_PORT'])) {
                $base_url = 'http://' . $_SERVER['SERVER_ADDR'] . ":" . $_SERVER['SERVER_PORT'];
            } else {

                $base_url = 'http://' . $_SERVER['SERVER_ADDR'];
            }

            $action =  '<a href="javascript:void(0);" id="update_' . $id . '" title="click to update company" class="update_company"><i class="glyphicon glyphicon-pencil"></i> &nbsp;</a>';
            if ($company->status == '1') {

                $action .= '<a href="javascript:void(0)" id="deactivate_' . $id . '" title="click to deactivate company" class="action"><img src="' . $base_url . '/hrms/images/icons/icon-close-circled-20.png" height="17" width="17"></a>';
            } else {

                $action .= '<a href="javascript:void(0)" id="activate_' . $id . '" title="click to activate company" class="action"><img src="' . $base_url . '/hrms/images/icons/icn_active.gif" height="17" width="17"></a>';
            }

            if ($_SESSION['emp_id'] == "06359-2013") {
                $action .= ' <a href="javascript:void(0);" id="delete_' . $id . '" title="click to delete company" class="action"><i class="glyphicon glyphicon-trash text-red"></i></a>';
            }

            $sub_array = array();
            $sub_array[] = $company->pc_name;
            $sub_array[] = $action;
            $data[] = $sub_array;
        }

        echo json_encode(array("data" => $data));
    }

    public function delete_company()
    {
        $pc_code = $this->input->post('id', TRUE);
        $delete = $this->setup_model->delete_company($pc_code);
        if ($delete) {
            echo json_encode(array('status' => 'success'));
        } else {
            echo json_encode(array('status' => 'failure'));
        }
    }

    public function update_company_status()
    {
        $data = $this->input->post(NULL, TRUE);
        $update = $this->setup_model->update_company_status($data);
        if ($update) {
            echo json_encode(array('status' => 'success'));
        } else {
            echo json_encode(array('status' => 'failure'));
        }
    }

    public function show_company()
    {
        $pc_code = $this->input->get('id', TRUE);

        $data['company'] = $this->setup_model->show_company($pc_code);
        $data['request'] = 'show_company';
        $this->load->view('body/placement/modal_response', $data);
    }

    public function update_company()
    {
        $data = $this->input->post(NULL, TRUE);

        $exist = $this->setup_model->check_company($data['company']);
        if ($exist) {
            echo json_encode(array('status' => 'exist'));
        } else {
            $update = $this->setup_model->update_company($data);
            if ($update) {
                echo json_encode(array('status' => 'success'));
            } else {
                echo json_encode(array('status' => 'failure'));
            }
        }
    }

    public function store_company()
    {
        $company = $this->input->post('company', TRUE);

        $exist = $this->setup_model->check_company($company);
        if ($exist) {
            echo json_encode(array('status' => 'exist'));
        } else {
            $store = $this->setup_model->store_company($company);
            if ($store) {
                echo json_encode(array('status' => 'success'));
            } else {
                echo json_encode(array('status' => 'failure'));
            }
        }
    }
}
