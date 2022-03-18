<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('nativesession');

        if (!isset($_SESSION['emp_id'])) {

            redirect('http://' . $_SERVER['SERVER_ADDR'] . ":" . $_SERVER['SERVER_PORT'] . '/hrms/nesco');
        }

        $this->load->model('placement/dashboard_model');
        $this->load->model('placement/employee_model');
    }

    public function new_employee()
    {
        echo $this->dashboard_model->new_employee();
    }

    public function birthday_today()
    {
        $result = $this->dashboard_model->birthday_today();
        $count = $result->num_rows();
        if ($count > 0) {

            echo "<a href='" . base_url('placement/page/menu/dashboard/birthday_today') . "' style='color: #fff !important;'>" . $count . "</a>";
        } else {
            echo $count;
        }
    }

    public function active_employee()
    {
        $count = $this->dashboard_model->active_employee();
        if ($count > 0) {

            echo "<a href='" . base_url('placement/page/menu/employee/masterfile') . "' style='color: #fff !important;'>" . $count . "</a>";
        } else {
            echo $count;
        }
    }

    public function eoc_today()
    {
        $count = $this->dashboard_model->eoc_today();
        if ($count > 0) {

            echo "<a href='" . base_url('placement/page/menu/contract/masterfile') . "' style='color: #fff !important;'>" . $count . "</a>";
        } else {
            echo $count;
        }
    }

    public function due_contract()
    {
        $result = $this->dashboard_model->due_contract();
        $count = $result->num_rows();
        if ($count > 0) {

            echo "<a href='" . base_url('placement/page/menu/dashboard/due-contract') . "' style='color: #fff !important;'>" . $count . "</a>";
        } else {
            echo $count;
        }
    }

    public function fetch_birthday_today()
    {
        $result = $this->dashboard_model->birthday_today();
        $fetch_data = $result->result();

        $data = array();
        foreach ($fetch_data as $row) {

            $ctr = 0;
            $storeName = '';
            $bUs = $this->dashboard_model->businessUnit_list();
            foreach ($bUs as $bu) {

                $hasBU = $this->dashboard_model->promo_has_bu($row->emp_id, $bu->bunit_field);
                if ($hasBU > 0) {

                    $ctr++;

                    if ($ctr == 1) {

                        $storeName = $bu->bunit_acronym;
                    } else {

                        $storeName .= ", " . $bu->bunit_acronym;
                    }
                }
            }

            $sub_array = array();
            $sub_array[] = '<span style="display:none">' . $row->record_no . '</span><a href="' . base_url('placement/page/menu/employee/profile/' . $row->emp_id) . '" target="_blank">' . ucwords(strtolower($row->name)) . '</a>';
            $sub_array[] = $row->gender;
            $sub_array[] = date('m/d/Y', strtotime($row->birthdate));
            $sub_array[] = $storeName;
            $sub_array[] = $row->promo_department;
            $sub_array[] = $row->position;
            $data[] = $sub_array;
        }

        echo json_encode(array("data" => $data));
    }

    public function fetch_due_contract()
    {
        $result = $this->dashboard_model->due_contract();
        $fetch_data = $result->result();

        $data = array();
        foreach ($fetch_data as $row) {

            $ctr = 0;
            $storeName = '';
            $bUs = $this->dashboard_model->businessUnit_list();
            foreach ($bUs as $bu) {

                $hasBU = $this->dashboard_model->promo_has_bu($row->emp_id, $bu->bunit_field);
                if ($hasBU > 0) {

                    $ctr++;

                    if ($ctr == 1) {

                        $storeName = $bu->bunit_acronym;
                    } else {

                        $storeName .= ", " . $bu->bunit_acronym;
                    }
                }
            }

            $sub_array = array();
            $sub_array[] = '<span style="display:none">' . $row->record_no . '</span><a href="' . base_url('placement/page/menu/employee/profile/' . $row->emp_id) . '" target="_blank">' . ucwords(strtolower($row->name)) . '</a>';
            $sub_array[] = $row->promo_company;
            $sub_array[] = $storeName;
            $sub_array[] = $row->promo_department;
            $sub_array[] = $row->promo_type;
            $sub_array[] = date('m/d/Y', strtotime($row->startdate));
            $sub_array[] = date('m/d/Y', strtotime($row->eocdate));
            $data[] = $sub_array;
        }

        echo json_encode(array("data" => $data));
    }

    public function due_contract_xls()
    {
        $data['fetch'] = $this->input->post(NULL, TRUE);
        $data['request'] = "due_contract_xls";

        $this->load->view('body/placement/modal_response', $data);
    }
}
