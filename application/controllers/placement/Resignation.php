<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Resignation extends CI_Controller
{
    public $base_url = '';

    function __construct()
    {
        parent::__construct();
        $this->load->library('nativesession');

        if (!isset($_SESSION['emp_id'])) {

            redirect('http://' . $_SERVER['SERVER_ADDR'] . ":" . $_SERVER['SERVER_PORT'] . '/hrms/nesco');
        }

        if (isset($_SERVER['SERVER_PORT'])) {
            $this->base_url = 'http://' . $_SERVER['SERVER_ADDR'] . ":" . $_SERVER['SERVER_PORT'];
        } else {

            $this->base_url = 'http://' . $_SERVER['SERVER_ADDR'];
        }

        $this->load->model('placement/resignation_model');
        $this->load->model('placement/employee_model');
    }

    public function resignation_list()
    {
        $fetch_data = $this->resignation_model->resignation_list();
        $data = array();
        foreach ($fetch_data as $row) {

            $letter = "";
            if (!empty($row['resignation_letter'])) {

                $letter = '<button id="view_' . $row['emp_id'] . '_' . $row['termination_no'] . '" class="btn btn-primary btn-sm btn-block action"><i class="fa fa-file-image-o"></i> &nbsp;View</button>';
            } else {

                $letter = '<button id="upload_' . $row['emp_id'] . '_' . $row['termination_no'] . '" class="btn btn-warning btn-sm btn-block action"><i class="fa fa-upload"></i> &nbsp;Upload</button>';
            }

            $sub_array = array();
            $sub_array[] = '<a href="' . base_url('placement/page/menu/employee/profile/' . $row['emp_id']) . '" target="_blank">' . $row['name'] . '</a>';
            $sub_array[] = date("m/d/Y", strtotime($row['date']));
            $sub_array[] = $this->employee_model->employee_info($row['emp_id'])->name;
            $sub_array[] = date("m/d/Y", strtotime($row['date_updated']));
            $sub_array[] = $row['remarks'];
            $sub_array[] = $letter;
            $data[] = $sub_array;
        }
        $output = array(
            "draw"                      =>     intval($_POST["draw"]),
            "recordsTotal"              =>     $this->resignation_model->get_all_data(),
            "recordsFiltered"           =>     $this->resignation_model->get_filtered_data(),
            "data"                      =>     $data
        );
        echo json_encode($output);
    }

    public function show_resignation_letter()
    {
        $termination_no = $this->input->get('termination_no', NULL);
        $letter = $this->resignation_model->show_termination($termination_no)->resignation_letter;
        if ($letter) {
            echo json_encode(array('status' => 'success', 'image' => $this->base_url . '/hrms/promo/' . $letter));
        } else {
            echo json_encode(array('status' => 'failure'));
        }
    }

    public function upload_resignation_letter()
    {
        $data['data'] = $this->input->post(NULL, TRUE);
        $data['request'] = 'upload_resignation_letter';

        $this->load->view('body/placement/modal_response', $data);
    }

    public function submit_resignation_letter()
    {
        $data = $this->input->post(NULL, TRUE);
        $destination_path = "";
        if (isset($_FILES['resignation']['name'])) {
            $image_name   = addslashes($_FILES['resignation']['name']);
            $array        = explode(".", $image_name);

            $filename     = $data['emp_id'] . "=" . date('Y-m-d') . "=" . 'Resignation-Letter' . "=" . date('H-i-s-A') . "." . end($array);
            $destination_path  = "../document/resignation/" . $filename;
            $data['destination'] = $destination_path;

            if (move_uploaded_file($_FILES['resignation']['tmp_name'], $destination_path)) {

                $update = $this->resignation_model->submit_resignation_letter($data);
                if ($update) {
                    echo json_encode(array('status' => 'success'));
                } else {
                    echo json_encode(array('status' => 'failure'));
                }
            } else {
                echo json_encode(array('status' => 'failure'));
            }
        }
    }
}
