<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Contract extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('nativesession');

        if (!isset($_SESSION['emp_id'])) {

            redirect('http://' . $_SERVER['SERVER_ADDR'] . ":" . $_SERVER['SERVER_PORT'] . '/hrms/nesco');
        }

        $this->load->model('placement/contract_model');
        $this->load->model('placement/dashboard_model');
        $this->load->model('placement/employee_model');
    }

    public function extend_contract()
    {
        $data['request'] = 'extend_contract';

        $this->load->view('body/placement/modal_response', $data);
    }

    public function show_intro()
    {
        $data['stores'] = $this->input->get('stores', TRUE);
        $data['request'] = 'show_intro';

        $this->load->view('body/placement/modal_response', $data);
    }

    public function find_witness()
    {
        $val = "";
        $data = $this->input->post(NULL, TRUE);

        $query = $this->contract_model->find_witness($data);
        if ($query->num_rows() > 0) {

            $info = $query->result_array();
            foreach ($info as $emp) {

                $name  = ucwords(strtolower($emp['witness']));

                if ($val != $name) {
?>
                    <a href="javascript:void(0);" onclick="getWitness('<?= $name  ?>', '<?= $data['witness'] ?>')"><?= $name  ?></a></br>
                <?php
                } else {
                    echo 'No Result Found';
                }
            }
        } else {

            echo 'No Result Found';
        }
    }

    public function process_renewal()
    {
        $data = $this->input->post(NULL, TRUE);

        $intros_path = array();
        $errors     = array();
        $maxsize    = 2097152;
        $acceptable = array(
            'image/jpeg',
            'image/jpg',
            'image/png'
        );

        foreach ($data['bunit_intro'] as $key => $value) {

            if (isset($_FILES[$value]['name'])) {

                $filesize        =    $_FILES[$value]['size'];
                $filetype        =    $_FILES[$value]['type'];

                $image_name = addslashes($_FILES[$value]['name']);
                $array  = explode(".", $image_name);

                $filename   = $data['empId'] . "=" . date('Y-m-d') . "=" . $value . "=" . date('H-i-s-A') . "." . end($array);
                $destination_path   = "../document/final_requirements/others/" . $filename;

                if ($filesize >= $maxsize || ((!in_array($filetype, $acceptable)) && (!empty($filetype)))) {
                    if ($filesize >= $maxsize) {

                        $errors[] = 'File too large. File must be less than 2 megabytes.';
                    } else {

                        $errors[] = 'File is invalid file type. Only PDF, JPG, GIF and PNG types are accepted.';
                    }

                    die(json_encode(array('status' => 'failure', 'errors' => $errors)));
                } else {

                    if (move_uploaded_file($_FILES[$value]['tmp_name'], $destination_path)) {

                        $intros_path[$value] = $destination_path;
                        $this->contract_model->store_application_otherreq($data['empId'], $value, $destination_path);
                    } else {

                        die(json_encode(array('status' => 'failure')));
                    }
                }
            } else {

                die(json_encode(array('status' => 'failure')));
            }
        }

        $this->db->trans_start();

        $record_no = $this->contract_model->update_employment_contract($data, $intros_path);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            echo json_encode(['message' => 'Opps! Something went wrong.']);
            // generate an error... or use the log_message() function to log your error
        } else {

            $row = $this->employee_model->employee_name($data['empId']);
            $this->employee_model->logs($_SESSION['emp_id'], $_SESSION['username'], date("Y-m-d"), date("H:i:s"), 'Added a new Contract of Employment of ' . $row['name'] . 'Record No:' . $record_no);

            echo json_encode(array('status' => 'success', 'message' => 'Added a new Contract of Employment of ' . $row['name']));
        }
    }

    public function print_contract_permit($emp_id)
    {
        $data['emp_id'] = $emp_id;
        $data['request'] = 'print_contract_permit';

        $this->load->view('body/placement/modal_response', $data);
    }

    public function print_permit_renewal($emp_id)
    {
        $data['emp_id'] = $emp_id;
        $data['request'] = 'print_permit_renewal';

        $this->load->view('body/placement/modal_response', $data);
    }

    public function store_duty_details()
    {
        $data = $this->input->post(NULL, TRUE);

        $sc = $this->contract_model->show_shiftcode($data['dutySched']);
        $In1     = $sc['1stIn'];
        $Out1    = $sc['1stOut'];
        $In2     = $sc['2ndIn'];
        $Out2    = $sc['2ndOut'];

        if ($In2 == "") {

            $duty_sched = "$In1-$Out1";
        } else {

            $duty_sched = "$In1-$Out1, $In2-$Out2";
        }

        $special_sched = '';
        if ($data['specialSched'] != '') {

            $sc = $this->contract_model->show_shiftcode($data['specialSched']);
            $In1     = $sc['1stIn'];
            $Out1    = $sc['1stOut'];
            $In2     = $sc['2ndIn'];
            $Out2    = $sc['2ndOut'];

            if ($In2 == "") {

                $special_sched = "$In1-$Out1";
            } else {

                $special_sched = "$In1-$Out1, $In2-$Out2";
            }
        }

        $update = $this->contract_model->edit_promo_record($data, $duty_sched, $special_sched);
        if ($update)
            echo json_encode(array('status' => 'success'));
    }

    public function print_contract_renewal($emp_id)
    {
        $data['emp_id'] = $emp_id;
        $data['request'] = 'print_contract_renewal';

        $this->load->view('body/placement/modal_response', $data);
    }

    public function store_witness_otherdetails()
    {
        $data = $this->input->post(NULL, TRUE);

        $this->db->trans_start();

        // update or insert employment witness
        $witness = $this->contract_model->get_employment_witness($data['contract_recordNo'], $data['empId']);
        if ($witness > 0) {

            $this->contract_model->update_employment_witness($data);
        } else {

            $this->contract_model->store_employment_witness($data);
        }

        // update or insert applicant other details
        $other_details = $this->contract_model->get_application_otherdetails($data['empId']);
        if ($other_details) {

            $this->contract_model->update_applicant_otherdetails($data);
        } else {

            $this->contract_model->store_applicant_otherdetails($data);
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            echo json_encode(array('status' => 'failure', 'message' => 'Opps! Something went wrong.'));
            // generate an error... or use the log_message() function to log your error
        } else {

            echo json_encode(array('status' => 'success', 'message' => 'Contract Successfully Saved'));
        }
    }

    public function find_iextend_promo()
    {
        $val = "";
        $fetch = $this->input->post(NULL, TRUE);

        $query = $this->employee_model->find_active_promo($fetch);
        if ($query->num_rows() > 0) {

            $info = $query->result_array();
            foreach ($info as $emp) {

                $empId = $emp['emp_id'];
                $name  = ucwords(strtolower($emp['name']));

                if ($val != $empId) {
                ?>
                    <a href="javascript:void(0);" onclick="getExtendEmpId('<?= $emp['emp_id'] . ' * ' . $emp['name']  ?>')"><?= $emp['emp_id'] . ' * ' . $emp['name']  ?></a></br>
                <?php
                } else {
                    echo 'No Result Found';
                }
            }
        } else {

            echo 'No Result Found';
        }
    }

    public function print_current_permit()
    {
        $data['request'] = 'print_current_permit';

        $this->load->view('body/placement/modal_response', $data);
    }

    public function find_iprintpermit_promo()
    {
        $val = "";
        $fetch = $this->input->post(NULL, TRUE);

        $query = $this->employee_model->find_active_promo($fetch);
        if ($query->num_rows() > 0) {

            $info = $query->result_array();
            foreach ($info as $emp) {

                $empId = $emp['emp_id'];
                $name  = ucwords(strtolower($emp['name']));

                if ($val != $empId) {
                ?>
                    <a href="javascript:void(0);" onclick="getIprintPermit('<?= $emp['emp_id'] . ' * ' . $name  ?>')"><?= $emp['emp_id'] . ' * ' . $name  ?></a></br>
<?php
                } else {
                    echo 'No Result Found';
                }
            }
        } else {

            echo 'No Result Found';
        }
    }

    public function current_permit_form($empId)
    {
        $data['empId'] = $empId;
        $data['request'] = 'current_permit_form';

        $this->load->view('body/placement/modal_response', $data);
    }

    public function print_previous_permit()
    {
        $data['request'] = 'print_previous_permit';

        $this->load->view('body/placement/modal_response', $data);
    }

    public function display_previous_contract($empId)
    {
        $data['contracts'] = $this->contract_model->show_previous_contracts($empId);
        $data['request'] = 'display_previous_contract';

        $this->load->view('body/placement/modal_response', $data);
    }

    public function display_previous_permit()
    {
        $data['data'] = $this->input->get(NULL, TRUE);
        $data['request'] = 'display_previous_permit';

        $this->load->view('body/placement/modal_response', $data);
    }

    public function transfer_rate_form($emp_id)
    {
        $data['emp_id'] = $emp_id;
        $data['request'] = 'transfer_rate_form';

        $this->load->view('body/placement/modal_response', $data);
    }

    public function transfer_rate()
    {
        $data = $this->input->post(NULL, TRUE);
        $emp = $this->employee_model->employee_info($data['emp_id']);

        $transfer = $this->contract_model->transfer_rate($data, $emp->record_no);
        echo $transfer;
    }

    public function eoc_list()
    {
        $request = $this->input->post(NULL, TRUE);
        $fetch_data = $this->contract_model->get_eoclist($request);
        $data = array();
        foreach ($fetch_data as $row) {

            $sub_array = array();
            $sub_array[] = '<a href="' . base_url('placement/page/menu/employee/profile/' . $row['emp_id']) . '" target="_blank">' . $row['name'] . '</a>';
            $sub_array[] = date("m/d/Y", strtotime($row['startdate']));
            $sub_array[] = date("m/d/Y", strtotime($row['eocdate']));

            $bUs = $this->dashboard_model->epas_businessUnit_list();
            $grado = array();
            $comment = array();
            foreach ($bUs as $bU) {

                $rate = '';
                $appraisal = $this->contract_model->check_appraisal($bU, $row['record_no'], $row['emp_id']);
                if ($appraisal->num_rows() > 0) {

                    $epas = $this->contract_model->get_appraisal($bU->bunit_name, $row['record_no'], $row['emp_id']);
                    if (!empty($epas)) {

                        if ($epas->raterSO == 1 && $epas->rateeSO == 1) {

                            $rate = "yes";
                            $label = "label label-success";
                        } else {

                            $rate = "no";
                            $label = "label label-warning";
                        }

                        if ($epas->numrate == 100) {
                            $label2 = "label label-success";
                        } else if ($epas->numrate >= 90 && $epas->numrate <= 99.99) {
                            $label2 = "label label-primary";
                        } else if ($epas->numrate >= 85 && $epas->numrate <= 89.99) {
                            $label2 = "label label-info";
                        } else if ($epas->numrate >= 70 && $epas->numrate <= 84.99) {
                            $label2 = "label label-danger";
                        } else if ($epas->numrate >= 0 && $epas->numrate <= 69.99) {
                            $label2 = "label label-danger";
                        } else {
                            $label2 = "label label-danger";
                        }

                        $grado[] = $epas->numrate;
                        $comment[] = $rate;

                        $sub_array[] = '<a href="javascript:void(0)" title="Click to view appraisal details" onclick="view_appraisal_details(' . $epas->details_id . ')"> <span class="' . $label2 . '">' . $epas->numrate . '</span> <span class="' . $label . '"> ' . $rate . '</span></a>';
                    } else {

                        $grado[] = '';
                        $comment[] = '';

                        $sub_array[] = '<span class="label label-default">none</span>';
                    }
                } else {

                    $sub_array[] = '';
                }
            }

            $action = $resign = $blacklist = 'no';
            foreach ($grado as $key => $value) {

                if ($value >= 85 && $comment[$key] == 'yes') {

                    $renew = 'yes';
                    continue;
                } else if ($value >= 85 && $comment[$key] == 'no') {

                    $resign = 'yes';
                    continue;
                } else {

                    $blacklist = 'yes';
                    break;
                }
            }

            if ($blacklist == 'yes') {

                $sub_array[] = '
                    <select id="' . $row['emp_id'] . '" onchange="proceedTo(this)">
                        <option value="">Proceed To</option>
                        <option value="blacklist">Blacklist</option>
                    </select>
                ';
            } else if ($resign == 'yes') {

                $sub_array[] = '
                    <select id="' . $row['emp_id'] . '" onchange="proceedTo(this)">
                        <option value="">Proceed To</option>
                        <option value="resign">Resign</option>
                        <option value="blacklist">Blacklist</option>
                    </select>
                ';
            } else {

                $sub_array[] = '
                    <select id="' . $row['emp_id'] . '" onchange="proceedTo(this)">
                        <option value="">Proceed To</option>
                        <option value="renewal">Renewal</option>
                        <option value="resign">Resign</option>
                    </select>
                ';
            }

            $data[] = $sub_array;
        }
        $output = array(
            "draw"                      =>     intval($_POST["draw"]),
            "recordsTotal"              =>     $this->contract_model->get_all_data($request),
            "recordsFiltered"           =>     $this->contract_model->get_filtered_data($request),
            "data"                      =>     $data
        );
        echo json_encode($output);
    }

    public function upload_clearance_renewal()
    {
        $data['emp_id'] = $this->input->get('emp_id', TRUE);
        $data['request'] = 'upload_clearance_renewal';

        $this->load->view('body/placement/modal_response', $data);
    }

    public function store_clearance_renewal()
    {
        $data = $this->input->post(NULL, TRUE);

        $clearanceFlag = "";
        foreach ($data['clearances'] as $key => $value) {

            $destination_path = "";
            if (!empty($_FILES[$value]['name'])) {

                $image        = addslashes(file_get_contents($_FILES[$value]['tmp_name']));
                $image_name   = addslashes($_FILES[$value]['name']);
                $array     = explode(".", $image_name);

                $filename     = $data['emp_id'] . "=" . date('Y-m-d') . "=" . $value . "=" . date('H-i-s-A') . "." . end($array);
                $destination_path    = "../document/clearance/" . $filename;

                if (move_uploaded_file($_FILES[$value]['tmp_name'], $destination_path)) {

                    $this->employee_model->upload_scanned_file('promo_record', $value, $destination_path, $data['emp_id'], $data['record_no']);
                    $clearanceFlag = "true";
                }
            }
        }

        $name = $this->employee_model->employee_name($data['emp_id'])['name'];

        if ($clearanceFlag == 'true') {

            $activity = "Uploaded the Scanned Clearance for Renewal of " . $name . " Record No." . $data['record_no'];
            $this->employee_model->logs($_SESSION['emp_id'], $_SESSION['username'], date("Y-m-d"), date("H:i:s"), $activity);
            echo json_encode(['message' => 'success']);
        } else {

            echo json_encode(['message' => 'Opps! Something went wrong.']);
        }
    }
}
