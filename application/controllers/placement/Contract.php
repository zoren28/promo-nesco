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
}
