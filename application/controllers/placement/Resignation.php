<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Resignation extends CI_Controller
{
    public $base_url = '';
    public $date = '';

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
        $this->load->model('placement/setup_model');
        $this->load->model('placement/dashboard_model');
        $this->load->model('placement/employee_model');
        $this->date = date('Y-m-d');
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

    public function list_of_subordinates()
    {
        $emp_id = $this->input->get('emp_id', TRUE);
        $data['rater'] = $emp_id;
        $data['subordinates'] = $this->setup_model->list_of_subordinates($emp_id);
        $data['request'] = 'resignation/list_of_subordinates';

        $this->load->view('body/placement/modal_response', $data);
    }

    public function update_resignation_status()
    {
        $data = $this->input->post(NULL, TRUE);
        if ($data['action'] == 'tag') {

            $action = $this->resignation_model->store_tag_for_resignation($data);
        } else {

            $action = $this->resignation_model->delete_tag_for_resignation($data);
        }

        if ($action) {
            echo json_encode(array('status' => 'success'));
        } else {
            echo json_encode(array('status' => 'failure'));
        }
    }

    public function clearance_process()
    {
        $process = $this->input->post('process', TRUE);
        $data['request'] = $process;

        $this->load->view('body/placement/modal_response', $data);
    }

    public function find_promo_for_clearance()
    {
        $str = $this->input->post('str', TRUE);
        $val = "";

        $query = $this->employee_model->find_promo_for_clearance($str);
        if ($query->num_rows() > 0) {

            $info = $query->result_array();
            foreach ($info as $emp) {

                $empId = $emp['emp_id'];
                $name  = ucwords(strtolower($emp['name']));

                if ($val != $empId) {
                ?>
                    <a href="javascript:void(0);" onclick="promoClearance('<?= $emp['emp_id'] . '*' . $emp['name'] . '*' . $emp['promo_type']  ?>')"><?= $emp['emp_id'] . ' * ' . $name  ?></a></br>
<?php
                } else {
                    echo 'No Result Found';
                }
            }
        } else {

            echo 'No Result Found';
        }
    }

    public function check_secure_clearance()
    {
        $data = $this->input->post(NULL, TRUE);
        $scpr_id = $this->resignation_model->get_scpr_id($data);
        if (!empty($scpr_id)) {

            $sc = $this->resignation_model->show_secure_clearance_promo($scpr_id);
            echo json_encode(array('reason' => $sc->reason));
        } else {
            echo json_encode(array('reason' => '', 'date_effectivity' => ''));
        }
    }

    public function promo_details_clearance()
    {
        $data['emp_id'] = $this->input->post('emp_id', TRUE);
        $data['emp_details'] = $this->employee_model->employee_info($data['emp_id']);
        $data['request'] = 'promo_details_clearance';

        $this->load->view('body/placement/modal_response', $data);
    }

    public function get_rb_form()
    {
        $data['reason'] = $this->input->post('reason', TRUE);
        $data['request'] = 'get_rb_form';

        $this->load->view('body/placement/modal_response', $data);
    }

    public function store_secure_clearance()
    {
        $data = $this->input->post(NULL, TRUE);
        $emp = explode('*', $data['employee']);
        $status = $data['reason'];
        $data['emp_id'] = trim(current($emp));

        $flag = 0;
        if ($this->resignation_model->check_secure_clearance_details(trim(current($emp)), $data['store']) > 0) {
            $flag = 0;
        } else {
            $flag = 1;
        }

        $substatus                 = "Uncleared";
        $dateforactiveresign       = "";
        $dateuncleared             = $this->date;

        if ($data['reason'] != 'Deceased') {

            if (strtotime($this->date) <= strtotime($data['date_of_resignation'])) {

                $status = "Active";
                switch ($data['reason']) {
                    case 'V-Resigned':
                        $substatus = "For Resignation";
                        break;
                    case 'Ad-Resigned':
                        $substatus = "For Resignation";
                        break;
                    case 'Termination':
                        $substatus = "For End of Contract";
                        break;
                    case 'Remove-BU':
                        $substatus = "For End of Contract";
                        break;
                }

                $dateforactiveresign     = $this->date;
                $dateuncleared           = "";
            } else {

                switch ($data['reason']) {
                    case 'V-Resigned':
                        $status = "V-Resigned";
                        break;
                    case 'Ad-Resigned':
                        $status = "Ad-Resigned";
                        break;
                    case 'Termination':
                        $status = "End of Contract";
                        break;
                    case 'Remove-BU':
                        $substatus = "End of Contract";
                        break;
                }

                $substatus                 = "Uncleared";
                $dateforactiveresign       = "";
                $dateuncleared             = $this->date;
            }
        }

        $data['status'] = $status;
        $data['sub_status'] = $substatus;
        $data['dateforactiveresign'] = $dateforactiveresign;
        $data['dateuncleared'] = $dateuncleared;

        if ($flag == 1) {

            $resignation_path = "";
            if (isset($_FILES['resignation_letter']['name'])) {
                $image_name   = addslashes($_FILES['resignation_letter']['name']);
                $array        = explode(".", $image_name);

                $filename     = $data['emp_id'] . "=" . date('Y-m-d') . "=" . 'Resignation-Letter' . "=" . date('H-i-s-A') . "." . end($array);
                $resignation_path  = "../document/resignation/" . $filename;

                move_uploaded_file($_FILES['resignation_letter']['tmp_name'], $resignation_path);
            }

            $authorization_path = "";
            if (isset($_FILES['authorization_letter']['name'])) {
                $image_name   = addslashes($_FILES['authorization_letter']['name']);
                $array        = explode(".", $image_name);

                $filename     = $data['emp_id'] . "=" . date('Y-m-d') . "=" . 'Authorization-Letter' . "=" . date('H-i-s-A') . "." . end($array);
                $authorization_path  = "../document/authorizationletter/" . $filename;

                move_uploaded_file($_FILES['authorization_letter']['tmp_name'], $authorization_path);
            }

            $data['resignation_path'] = $resignation_path;
            $data['authorization_path'] = $authorization_path;

            $scpr_id = $this->resignation_model->get_scpr_id($data);
            if (empty($scpr_id)) {

                $scpr_id = $this->resignation_model->store_secure_clearance_promo($data);

                // update current_status and sub_status of the employee
                $this->resignation_model->update_employee_status($data);

                // store secure_clearance_deceased if the employee is deceased
                if ($data['status'] == 'Deceased') {
                    $this->resignation_model->store_secure_clearance_deceased($data, $scpr_id);
                }

                if ($data['reason'] == 'V-Resigned' || $data['reason'] == 'Ad-Resigned') {

                    $raters = $this->resignation_model->employees_ratee($data['emp_id']);
                    foreach ($raters as $rater) {

                        $data['rater'] = $rater->ratee;
                        $this->resignation_model->store_tag_for_resignation($data);
                    }
                }
            }

            $data['scpr_id'] = $scpr_id;
            $scdetails_id = $this->resignation_model->store_secure_clearance_promo_details($data);
            if ($scdetails_id) {
                echo json_encode(array('status' => 'success', 'scdetails_id' => $scdetails_id, 'emp_id' => $data['emp_id'], 'base_url' => $this->base_url));
            } else {
                echo json_encode(array('status' => 'failure'));
            }
        }
    }
}
