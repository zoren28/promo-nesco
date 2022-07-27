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
        $this->load->model('placement/dashboard_model');
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

    public function check_rt_status()
    {
        $data['data'] = $this->input->post(NULL, TRUE);
        $data['request'] = 'check_rt_status';

        $this->load->view('body/placement/modal_response', $data);
    }

    public function store_rt()
    {
        $data = $this->input->post(NULL, TRUE);
        $id = explode('*', $data['employee']);
        $emp_id = trim(current($id));
        $data['emp_id'] = $emp_id;

        $clearances = array();
        foreach ($data['clearances'] as $value) {

            $destination_path = "";
            if (isset($_FILES[$value]['name'])) {

                $image_name   = addslashes($_FILES[$value]['name']);
                $array     = explode(".", $image_name);

                $filename     = $emp_id . "=" . date('Y-m-d') . "=" . $value . "=" . date('H-i-s-A') . "." . end($array);
                $destination_path    = "../document/clearance/" . $filename;
                if (move_uploaded_file($_FILES[$value]['tmp_name'], $destination_path)) {

                    $clearances[] = array(
                        'clearance' => $value,
                        'path' => $destination_path
                    );
                }
            }
        }

        $update = $this->resignation_model->upload_clearance($emp_id, $clearances);
        if ($update) {

            $resignation_path = '';
            if ($data['rt_status'] == 'Resigned') {

                $image_name    = addslashes($_FILES['resignation']['name']);
                $array     = explode(".", $image_name);

                $filename     = $emp_id . "=" . date('Y-m-d') . "=" . 'Resignation-Letter' . "=" . date('H-i-s-A') . "." . end($array);
                $resignation_path    = "../document/resignation/" . $filename;

                move_uploaded_file($_FILES['resignation']['tmp_name'], $resignation_path);
            }

            $data['resignation_path'] = $resignation_path;

            $store = $this->resignation_model->store_termination($data);
            if ($store) {
                $record = $this->resignation_model->update_employee3($data);
                $user = $this->resignation_model->inactive_user($emp_id);
                if ($record && $user) {
                    echo json_encode(array('status' => 'success', 'url' => $this->base_url . '/hrms/promo-nesco/placement/page/menu/resignation-termination/resignation-termination-list'));
                }
            }
        }
    }

    public function find_all_promo()
    {
        $str = $this->input->post('str', TRUE);
        $val = "";

        $query = $this->employee_model->find_all_promo($str);
        if ($query->num_rows() > 0) {

            $info = $query->result_array();
            foreach ($info as $emp) {

                $empId = $emp['emp_id'];
                $name  = ucwords(strtolower($emp['name']));

                if ($val != $empId) {
?>
                    <a href="javascript:void(0);" onclick="empDetails('<?= $emp['emp_id'] . ' * ' . $emp['name']  ?>')"><?= $emp['emp_id'] . ' * ' . $emp['name']  ?></a></br>
<?php
                } else {
                    echo 'No Result Found';
                }
            }
        } else {

            echo 'No Result Found';
        }
    }

    public function show_employee($emp_id)
    {
        $emp = $this->employee_model->employee_info($emp_id);
        if ($emp) {
            echo json_encode(array('status' => $emp->current_status));
        }
    }
}
