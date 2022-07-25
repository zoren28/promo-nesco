<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Employee extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('nativesession');

        if (!isset($_SESSION['emp_id'])) {

            redirect('http://' . $_SERVER['SERVER_ADDR'] . ":" . $_SERVER['SERVER_PORT'] . '/hrms/nesco');
        }

        $this->load->model('placement/employee_model');
        $this->load->model('placement/dashboard_model');
        $this->load->library('upload');
    }

    public function find_hr_staff()
    {
        $val = "";
        $fetch = $this->input->post(NULL, TRUE);

        $query = $this->employee_model->find_hr_staff($fetch);
        if ($query->num_rows() > 0) {

            $info = $query->result_array();
            foreach ($info as $n) {

                $empId = $n['emp_id'];
                $name  = ucwords(strtolower($n['name']));

                if ($val != $empId) {
                    echo "<a href = \"javascript:void(0)\" onclick='getEmpId(\"$name\")'>" . $name . "</a></br>";
                } else {
                    echo 'No Result Found';
                }
            }
        } else {

            echo 'No Result Found';
        }
    }

    public function find_active_promo()
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
                    <a href="javascript:void(0);" onclick="getEmpId('<?= $emp['emp_id'] . ' * ' . $emp['name']  ?>')"><?= $emp['emp_id'] . ' * ' . $emp['name']  ?></a></br>
                <?php
                } else {
                    echo 'No Result Found';
                }
            }
        } else {

            echo 'No Result Found';
        }
    }

    public function fetch_employee_masterfile()
    {
        $fetch_data = $this->input->post(NULL, TRUE);
        $employees = $this->employee_model->employee_list($fetch_data);

        $data = array();
        foreach ($employees as $row) {

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
            $sub_array[] = '<span style="display:none">' . $row->record_no . '</span><a href="profile/' . $row->emp_id . '" target="_blank">' . htmlentities(ucwords(strtolower($row->name))) . '</a>';
            $sub_array[] = $row->promo_company;
            $sub_array[] = $storeName;
            $sub_array[] = $row->promo_department;
            $sub_array[] = $row->position;
            $sub_array[] = $row->promo_type;
            $sub_array[] = '<label class="btn btn-xs btn-block btn-success">' . $row->current_status . '</label>';
            $data[] = $sub_array;
        }

        echo json_encode(array("data" => $data));
    }

    public function fetch_assigned_department($id)
    {
        echo "<option value=''> --Select-- </option>";
        $departments = $this->employee_model->assigned_departments($id);
        foreach ($departments as $dept) {

            echo "<option value='" . $dept->dept_name . "'>" . $dept->dept_name . "</option>";
        }
    }

    public function searchThis()
    {
        $_SESSION['searchThis'] = $this->input->get('searchThis', TRUE);
    }

    public function search_employee()
    {
        $searchThis = $this->input->get('searchThis', TRUE);
        $data['request'] = "search_employee";
        $data['fetch'] = $this->employee_model->search_employee(trim($searchThis));

        $this->load->view('body/placement/modal_response', $data);
    }

    public function search_applicant()
    {
        $fetch_data = $this->input->get(NULL, TRUE);
        $data['request'] = "search_applicant";
        $data['fetch'] = $this->employee_model->search_applicant($fetch_data);

        $this->load->view('body/placement/modal_response', $data);
    }

    public function employee_information_details($code = "basicinfo")
    {
        $data['request'] = $code;
        $data['empId']  = $this->input->get('empId', TRUE);

        $this->load->view('body/placement/employee_information_details', $data);
    }

    public function update_basicinfo()
    {
        $fetch_data = $this->input->post(NULL, TRUE);

        if ($fetch_data['suffix'] != "") {
            $suffix = " " . $fetch_data['suffix'] . ",";
        } else {
            $suffix = "";
        }
        if ($fetch_data['mname'] != "") {
            $mname = " " . $fetch_data['mname'];
        } else {
            $mname = "";
        }

        $properName = ucwords(strtolower($fetch_data['lname'])) . ", " . ucwords(strtolower($fetch_data['fname'])) . "" . ucwords(strtolower($suffix)) . "" . ucwords(strtolower($mname));

        $updatebasicinfo = $this->employee_model->update_basicinfo($fetch_data);
        if ($updatebasicinfo) {

            $row = $this->employee_model->employee_name($fetch_data['empId']);
            $this->employee_model->update_name($fetch_data['empId'], $properName);
            $this->employee_model->logs($_SESSION['emp_id'], $_SESSION['username'], date("Y-m-d"), date("H:i:s"), 'Updating the Basic Information of ' . $row['name']);
            die("success");
        } else {

            die("failure");
        }
    }

    public function update_family()
    {
        $fetch_data = $this->input->post(NULL, TRUE);

        $updatefamily = $this->employee_model->update_family($fetch_data);
        if ($updatefamily) {

            $row = $this->employee_model->employee_name($fetch_data['empId']);
            $this->employee_model->logs($_SESSION['emp_id'], $_SESSION['username'], date("Y-m-d"), date("H:i:s"), 'Updating the Family Information of ' . $row['name']);
            die("success");
        } else {

            die("failure");
        }
    }

    public function changeProfilePic()
    {

        $data['fetch'] = $this->input->post(NULL, TRUE);
        $data['request'] = "changeProfilePic";

        $this->load->view('body/placement/modal_response', $data);
    }

    public function getProfilePic()
    {

        $empId = $this->input->post('empId', TRUE);
        $photo = $this->employee_model->get_applicant_info($empId)->photo;
        die($photo);
    }

    public function uploadProfilePic()
    {

        $fetch_data = $this->input->post(NULL, TRUE);
        $empId = $fetch_data['empId'];

        if (!empty($_FILES['profile']['name'])) {

            $photo = $this->employee_model->get_applicant_info($empId)->photo;
            unlink($photo);

            $image      = addslashes(file_get_contents($_FILES['profile']['tmp_name']));
            $image_name = addslashes($_FILES['profile']['name']);
            $array  = explode(".", $image_name);

            $filename   = $empId . "=" . date('Y-m-d') . "=" . 'Profile' . "=" . date('H-i-s-A') . "." . end($array);
            $destination_path   = "../images/users/" . $filename;

            if (move_uploaded_file($_FILES['profile']['tmp_name'], $destination_path)) {

                $profile = $this->employee_model->update_photo($empId, $destination_path);
                if ($profile) {

                    die("success");
                }
            }
        }
    }

    public function find_mothers_name()
    {
        $str = $this->input->post('str', TRUE);

        $mother_names = $this->employee_model->find_mothers_name($str);
        foreach ($mother_names as $row) {

            $empId  = $row['emp_id'];
            $name   = ucwords(strtolower($row['name']));

            if ($empId != "") {

                echo "<a class = \"nameFind\" href = \"#\" onclick='get_spouseId(\"$empId*$name\")'>" . $name . "</a></br>";
            } else {

                echo "<a class = \"afont\" href = \"#\">No Result Found</a>";
            }
        }
    }

    public function view_birthCert()
    {
        $childId = $this->input->post('childId', TRUE);
        echo $this->employee_model->fetch_birthCert($childId)['birth_cert'];
    }

    public function get_age()
    {
        $bday = $this->input->post('bday', TRUE);

        $dob = strtotime($bday);
        $now = date('Y-m-d');
        $tdate = strtotime($now);
        $age = 0;
        while ($tdate >= $dob = strtotime('+1 year', $dob)) {

            $age++;
        }

        echo $age;
    }

    public function update_birthCertForm()
    {
        $data['fetch'] = $this->input->post(NULL, TRUE);
        $data['request'] = "update_birthCertForm";

        $this->load->view('body/placement/modal_response', $data);
    }

    public function updateScannedNSO()
    {
        $childId = $this->input->post('childId', TRUE);
        $destination_path = "";
        if (!empty($_FILES['NSO']['name'])) {

            $birthCert = $this->employee_model->fetch_birthCert($childId)['birth_cert'];
            if ($birthCert != "") {

                unlink($birthCert);
            }

            $image      = addslashes(file_get_contents($_FILES['NSO']['tmp_name']));
            $image_name = addslashes($_FILES['NSO']['name']);
            $array  = explode(".", $image_name);

            $filename   = $childId . "=" . date('Y-m-d') . "=" . 'NSO' . "=" . date('H-i-s-A') . "." . end($array);
            $destination_path   = "../document/live_birth/" . $filename;

            if (move_uploaded_file($_FILES['NSO']['tmp_name'], $destination_path)) {

                $birth_cert = $this->employee_model->update_birthCert($childId, $destination_path);
                if ($birth_cert) {

                    die("success");
                } else {

                    die("failure");
                }
            } else {

                die("failure");
            }
        }
    }

    public function add_children_info()
    {
        $data['fetch'] = $this->input->post(NULL, TRUE);
        $data['request'] = "add_children_info";

        $this->load->view("body/placement/modal_response", $data);
    }

    public function delete_children_info()
    {
        $childId = $this->input->post('childId', TRUE);
        $birth_cert = $this->employee_model->fetch_birthCert($childId)['birth_cert'];
        if ($birth_cert != "") {

            unlink($birth_cert);
        }

        $delete = $this->employee_model->delete_children_info($childId);
        if ($delete) {

            die("success");
        } else {

            die("failure");
        }
    }

    public function upload_birthCertForm()
    {
        $data['request'] = "upload_birthCertForm";

        $this->load->view('body/placement/modal_response', $data);
    }

    public function submit_children_info()
    {
        $fetch = $this->input->post(NULL, TRUE);

        for ($i = 0; $i < count($fetch['deleted']); $i++) {

            if ($fetch['deleted'][$i] == "") {

                $data = array(
                    'spouseId'  => $fetch['spouseId'],
                    'fname'     => ucwords(strtolower($fetch['fname1'][$i])),
                    'mname'     => ucwords(strtolower($fetch['mname1'][$i])),
                    'lname'     => ucwords(strtolower($fetch['lname1'][$i])),
                    'bday'      => date("Y-m-d", strtotime($fetch['bday1'][$i])),
                    'gender'    => $fetch['gender1'][$i],
                    'deceased'  => $fetch['deceased1'][$i],
                );

                $insert = $this->employee_model->insert_children_info($data);
            }
        }

        die("success");
    }

    public function update_children_info()
    {
        $fetch = $this->input->post(NULL, TRUE);

        $update_spouse = $this->employee_model->update_spouse_info($fetch['spouseId'], $fetch['empId'], $fetch['spouse_empId'], ucwords(strtolower($fetch['spouse_name'])));
        if ($update_spouse) {

            for ($i = 0; $i < count($fetch['childId']); $i++) {

                $data = array(
                    'childId'   => $fetch['childId'][$i],
                    'fname'     => ucwords(strtolower($fetch['fname'][$i])),
                    'mname'     => ucwords(strtolower($fetch['mname'][$i])),
                    'lname'     => ucwords(strtolower($fetch['lname'][$i])),
                    'bday'      => date("Y-m-d", strtotime($fetch['bday'][$i])),
                    'gender'    => $fetch['gender'][$i],
                    'deceased'  => $fetch['deceased'][$i],
                );

                $update_children = $this->employee_model->update_children_info($data);
            }

            die("success");
        } else {

            die("failure");
        }
    }

    public function submit_spouse_children()
    {
        $fetch = $this->input->post(NULL, TRUE);

        $spouseId = $this->employee_model->last_spouseId()['spouse_id'];
        $insert_spouse = $this->employee_model->insert_spouse_info($spouseId, $fetch['empId'], $fetch['spouse_empId'], ucwords(strtolower($fetch['spouse_name'])));
        if ($insert_spouse) {

            for ($i = 0; $i < count($fetch['deleted']); $i++) {

                if ($fetch['deleted'][$i] == "") {

                    $data = array(
                        'spouseId'  => $spouseId,
                        'fname'     => ucwords(strtolower($fetch['fname1'][$i])),
                        'mname'     => ucwords(strtolower($fetch['mname1'][$i])),
                        'lname'     => ucwords(strtolower($fetch['lname1'][$i])),
                        'bday'      => date("Y-m-d", strtotime($fetch['bday1'][$i])),
                        'gender'    => $fetch['gender1'][$i],
                        'deceased'  => $fetch['deceased1'][$i],
                    );

                    $insert = $this->employee_model->insert_children_info($data);
                }
            }

            die("success");
        } else {

            die("failure");
        }
    }

    public function update_contact()
    {
        $fetch_data = $this->input->post(NULL, TRUE);

        $updatecontact = $this->employee_model->update_contact($fetch_data);
        if ($updatecontact) {

            $row = $this->employee_model->employee_name($fetch_data['empId']);
            $this->employee_model->logs($_SESSION['emp_id'], $_SESSION['username'], date("Y-m-d"), date("H:i:s"), 'Updating the Contact Information of ' . $row['name']);
            die("success");
        } else {

            die("failure");
        }
    }

    public function update_educ()
    {
        $fetch_data = $this->input->post(NULL, TRUE);

        $updateeduc = $this->employee_model->update_educ($fetch_data);
        if ($updateeduc) {

            $row = $this->employee_model->employee_name($fetch_data['empId']);
            $this->employee_model->logs($_SESSION['emp_id'], $_SESSION['username'], date("Y-m-d"), date("H:i:s"), 'Updating the Educational Information of ' . $row['name']);
            die("success");
        } else {

            die("failure");
        }
    }

    public function seminar_form()
    {
        $data['fetch'] = $this->input->post(NULL, TRUE);
        $data['request'] = "seminar_form";

        $this->load->view('body/placement/modal_response', $data);
    }

    public function submitSeminar()
    {
        $fetch_data = $this->input->post(NULL, TRUE);
        $empId = $fetch_data['appId'];
        $no = $fetch_data['no'];

        $destination_path = "";
        if (!empty($_FILES['semCertificate']['name'])) {

            $image      = addslashes(file_get_contents($_FILES['semCertificate']['tmp_name']));
            $image_name = addslashes($_FILES['semCertificate']['name']);
            $array  = explode(".", $image_name);

            $filename   = $empId . "=" . date('Y-m-d') . "=" . 'Seminar-Certificate' . "=" . date('H-i-s-A') . "." . end($array);
            $destination_path   = "../document/seminar_certificate/" . $filename;

            move_uploaded_file($_FILES['semCertificate']['tmp_name'], $destination_path);
        }

        if ($no == "") {

            $seminar = $this->employee_model->insert_seminar_info($fetch_data, $destination_path);
            if ($seminar) {

                $row = $this->employee_model->employee_name($empId);
                $this->employee_model->logs($_SESSION['emp_id'], $_SESSION['username'], date("Y-m-d"), date("H:i:s"), "Adding New Seminar/Eligibility/Training Information of " . $row['name']);
                die("success||Added");
            } else {

                die("failure");
            }
        } else {

            $est_cert = $this->employee_model->seminar_cert($fetch_data)['sem_certificate'];
            if ($est_cert != '') {

                unlink($est_cert);
            }


            $seminar = $this->employee_model->update_seminar_info($fetch_data, $destination_path);
            if ($seminar) {

                $row = $this->employee_model->employee_name($empId);
                $this->employee_model->logs($_SESSION['emp_id'], $_SESSION['username'], date("Y-m-d"), date("H:i:s"), "Updated Seminar/Eligibility/Training Information of " . $row['name']);
                die("success||Updated");
            } else {

                die("failure");
            }
        }
    }

    public function seminarCertificate()
    {
        $fetch_data = $this->input->post(NULL, TRUE);

        $row =  $this->employee_model->seminar_cert($fetch_data);
        echo $row['sem_certificate'];
    }

    public function character_ref_form()
    {
        $data['fetch'] = $this->input->post(NULL, TRUE);
        $data['request'] = "character_ref_form";

        $this->load->view('body/placement/modal_response', $data);
    }

    public function submit_character_ref()
    {
        $fetch_data = $this->input->post(NULL, TRUE);
        $no = $fetch_data['no'];
        $empId = $fetch_data['empId'];

        if ($no == "") {

            $charRef = $this->employee_model->insert_character_ref($fetch_data);

            if ($charRef) {

                $row = $this->employee_model->employee_name($empId);
                $this->employee_model->logs($_SESSION['emp_id'], $_SESSION['username'], date("Y-m-d"), date("H:i:s"), "Adding new Character References Details of " . $row['name']);
                die("success||Added");
            }
        } else {

            $charRef = $this->employee_model->update_character_ref($fetch_data);

            if ($charRef) {
                $row = $this->employee_model->employee_name($empId);
                $this->employee_model->logs($_SESSION['emp_id'], $_SESSION['username'], date("Y-m-d"), date("H:i:s"), "Updating the Character References Details of " . $row['name']);
                die("success||Updated");
            }
        }
    }

    public function update_skills()
    {
        $fetch_data = $this->input->post(NULL, TRUE);

        $updateskills = $this->employee_model->update_skills($fetch_data);
        if ($updateskills) {

            $row = $this->employee_model->employee_name($fetch_data['empId']);
            $this->employee_model->logs($_SESSION['emp_id'], $_SESSION['username'], date("Y-m-d"), date("H:i:s"), 'Updating the Skills Information of ' . $row['name']);
            die("success");
        }
    }

    public function appraisal_details()
    {
        $data['fetch'] = $this->input->post(NULL, TRUE);
        $data['request'] = "appraisal_details";

        $this->load->view('body/placement/modal_response', $data);
    }

    public function examDetails()
    {
        $data['fetch'] = $this->input->post(NULL, TRUE);
        $data['request'] = "examDetails";

        $this->load->view('body/placement/modal_response', $data);
    }

    public function appHistDetails()
    {
        $data['fetch'] = $this->input->post(NULL, TRUE);
        $data['request'] = "appHistDetails";

        $this->load->view('body/placement/modal_response', $data);
    }

    public function interviewDetails()
    {
        $data['fetch'] = $this->input->post(NULL, TRUE);
        $data['request'] = "interviewDetails";

        $this->load->view('body/placement/modal_response', $data);
    }

    public function update_apphis()
    {
        $fetch_data = $this->input->post(NULL, TRUE);
        $empId = $fetch_data['empId'];

        $checkApp = "SELECT COUNT(no) AS exist FROM application_details where app_id = '" . $empId . "'";
        if ($this->employee_model->return_row_array($checkApp)['exist'] > 0) {

            $act = "Updating the Application History Information of";
            $appHis = $this->employee_model->update_application_history($fetch_data);
        } else {

            $act = "Adding the Application History of";
            $appHis = $this->employee_model->insert_application_history($fetch_data);
        }

        if ($appHis) {

            $row = $this->employee_model->employee_name($empId);
            $this->employee_model->logs($_SESSION['emp_id'], $_SESSION['username'], date("Y-m-d"), date("H:i:s"), $act . ' ' . $row['name']);
            die("success");
        } else {

            die("failure");
        }
    }

    public function addContract()
    {
        $data['fetch'] = $this->input->post(NULL, TRUE);
        $data['request'] = "addContract";

        $this->load->view('body/placement/modal_response', $data);
    }

    public function contractDetails()
    {
        $data['fetch'] = $this->input->post(NULL, TRUE);
        $data['request'] = "contractDetails";

        $this->load->view('body/placement/modal_response', $data);
    }

    public function promoContractDetails()
    {
        $data['fetch'] = $this->input->post(NULL, TRUE);
        $data['request'] = "promoContractDetails";

        $this->load->view('body/placement/modal_response', $data);
    }

    public function get_file()
    {
        $data['fetch'] = $this->input->post(NULL, TRUE);
        $data['request'] = "get_file";

        $this->load->view('body/placement/modal_response', $data);
    }

    public function promoFile()
    {
        $data['fetch'] = $this->input->post(NULL, TRUE);
        $data['request'] = "promoFile";

        $this->load->view('body/placement/modal_response', $data);
    }

    public function editContractDetails()
    {
        $data['fetch'] = $this->input->post(NULL, TRUE);
        $data['request'] = "editContractDetails";

        $this->load->view('body/placement/modal_response', $data);
    }

    public function editPromoContractDetails()
    {
        $data['fetch'] = $this->input->post(NULL, TRUE);
        $data['request'] = "editPromoContractDetails";

        $this->load->view('body/placement/modal_response', $data);
    }

    public function locate_business_unit()
    {
        $fetch_data = $this->input->post(NULL, TRUE);
        $data['request'] = "locate_business_unit";
        $data['fetch'] = $this->employee_model->locate_business_unit($fetch_data);

        $this->load->view('body/placement/modal_response', $data);
    }

    public function locate_department()
    {
        $fetch_data = $this->input->post(NULL, TRUE);
        $data['request'] = "locate_department";
        $data['fetch'] = $this->employee_model->locate_department($fetch_data);

        $this->load->view('body/placement/modal_response', $data);
    }

    public function locate_section()
    {
        $fetch_data = $this->input->post(NULL, TRUE);
        $data['request'] = "locate_section";
        $data['fetch'] = $this->employee_model->locate_section($fetch_data);

        $this->load->view('body/placement/modal_response', $data);
    }

    public function locate_sub_section()
    {
        $fetch_data = $this->input->post(NULL, TRUE);
        $data['request'] = "locate_sub_section";
        $data['fetch'] = $this->employee_model->locate_sub_section($fetch_data);

        $this->load->view('body/placement/modal_response', $data);
    }

    public function locate_unit()
    {
        $fetch_data = $this->input->post(NULL, TRUE);
        $data['request'] = "locate_unit";
        $data['fetch'] = $this->employee_model->locate_unit($fetch_data);

        $this->load->view('body/placement/modal_response', $data);
    }

    public function position_level()
    {
        $position = $this->input->get('position', TRUE);
        $level = $this->employee_model->position_level($position);

        echo $level->lvlno;
    }

    public function updateContractDetails()
    {
        $fetch_data = $this->input->post(NULL, TRUE);

        $updEmp = $this->employee_model->update_contract_details($fetch_data);
        if ($updEmp) {

            $row = $this->employee_model->employee_name($fetch_data['empId']);
            $this->employee_model->logs($_SESSION['emp_id'], $_SESSION['username'], date("Y-m-d"), date("H:i:s"), 'Updated the current contract history of ' . $row['name']);
            die("success");
        }

        die('error');
    }

    public function uploadScannedFileForm()
    {
        $data['fetch'] = $this->input->post(NULL, TRUE);
        $data['request'] = "uploadScannedFileForm";

        $this->load->view('body/placement/modal_response', $data);
    }

    public function uploadPromoScannedFileForm()
    {
        $data['fetch'] = $this->input->post(NULL, TRUE);
        $data['request'] = "uploadPromoScannedFileForm";

        $this->load->view('body/placement/modal_response', $data);
    }

    public function uploadScannedFile()
    {
        $fetch_data = $this->input->post(NULL, TRUE);

        $contract = $fetch_data['contract'];
        $empId = $fetch_data['empId'];
        $recordNo = $fetch_data['recordNo'];
        $table = "";

        if ($contract == "current") :

            $table = "employee3";
        else :

            $table = "employmentrecord_";
        endif;

        $epasFlag = "";
        $contractFlag = "";
        $clearanceFlag = "";

        $destination_path = "";
        if (!empty($_FILES['clearance']['name'])) {

            $image      = addslashes(file_get_contents($_FILES['clearance']['tmp_name']));
            $image_name = addslashes($_FILES['clearance']['name']);
            $array  = explode(".", $image_name);

            $filename   = $empId . "=" . date('Y-m-d') . "=" . 'clearance' . "=" . date('H-i-s-A') . "." . end($array);
            $destination_path   = "../document/clearance/" . $filename;

            if (move_uploaded_file($_FILES['clearance']['tmp_name'], $destination_path)) {

                $this->employee_model->upload_scanned_file($table, 'clearance', $destination_path, $empId, $recordNo);
                $clearanceFlag = "true";
            }
        }

        $destination_path = "";
        if (!empty($_FILES['contract']['name'])) {

            $image      = addslashes(file_get_contents($_FILES['contract']['tmp_name']));
            $image_name = addslashes($_FILES['contract']['name']);
            $array  = explode(".", $image_name);

            $filename   = $empId . "=" . date('Y-m-d') . "=" . 'contract' . "=" . date('H-i-s-A') . "." . end($array);
            $destination_path   = "../document/contract/" . $filename;

            if (move_uploaded_file($_FILES['contract']['tmp_name'], $destination_path)) {

                $contract = $this->employee_model->upload_scanned_file($table, 'contract', $destination_path, $empId, $recordNo);
                $contractFlag = "true";
            }
        }

        $destination_path = "";
        if (!empty($_FILES['epas']['name'])) {

            $image      = addslashes(file_get_contents($_FILES['epas']['tmp_name']));
            $image_name = addslashes($_FILES['epas']['name']);
            $array  = explode(".", $image_name);

            $filename   = $empId . "=" . date('Y-m-d') . "=" . 'epas' . "=" . date('H-i-s-A') . "." . end($array);
            $destination_path   = "../document/epas/" . $filename;

            if (move_uploaded_file($_FILES['epas']['tmp_name'], $destination_path)) {

                $this->employee_model->upload_scanned_file($table, 'epas_code', $destination_path, $empId, $recordNo);
                $epasFlag = "true";
            }
        }

        $message = "";
        $name = $this->employee_model->employee_name($fetch_data['empId'])['name'];

        if ($contractFlag == 'true' && $clearanceFlag == 'true' && $epasFlag == 'true') {

            $activity = "Uploaded the scanned Contract, Clearance and EPAS of " . $name . " Record No." . $recordNo;
            $this->employee_model->logs($_SESSION['emp_id'], $_SESSION['username'], date("Y-m-d"), date("H:i:s"), $activity);
            $message = "Contract, Clearance and Scanned EPAS are Successfully Uploaded!";
        } else if ($contractFlag == 'true' && $clearanceFlag == 'true') {

            $activity = "Uploaded the scanned Contract and Clearance of " . $name . " Record No." . $recordNo;
            $this->employee_model->logs($_SESSION['emp_id'], $_SESSION['username'], date("Y-m-d"), date("H:i:s"), $activity);
            $message = "Contract and Clearance are Successfully Uploaded!";
        } else if ($contractFlag == 'true' && $epasFlag == 'true') {

            $activity = "Uploaded the scanned Contract and EPAS of " . $name . " Record No." . $recordNo;
            $this->employee_model->logs($_SESSION['emp_id'], $_SESSION['username'], date("Y-m-d"), date("H:i:s"), $activity);
            $message = "Contract and Scanned EPAS are Successfully Uploaded!";
        } else if ($epasFlag == 'true' && $clearanceFlag == 'true') {

            $activity = "Uploaded the scanned Clearance and EPAS of " . $name . " Record No." . $recordNo;
            $this->employee_model->logs($_SESSION['emp_id'], $_SESSION['username'], date("Y-m-d"), date("H:i:s"), $activity);
            $message = "Clearance and Scanned EPAS are Successfully Uploaded!";
        } else if ($contractFlag == 'true') {

            $activity = "Uploaded the scanned Contract of " . $name . " Record No." . $recordNo;
            $this->employee_model->logs($_SESSION['emp_id'], $_SESSION['username'], date("Y-m-d"), date("H:i:s"), $activity);
            $message = "Contract Successfully Uploaded!";
        } else if ($clearanceFlag == 'true') {

            $activity = "Uploaded the scanned Clearance (override) " . $name . " Record No." . $recordNo;
            $this->employee_model->logs($_SESSION['emp_id'], $_SESSION['username'], date("Y-m-d"), date("H:i:s"), $activity);
            $message = "Clearance Successfully Uploaded!";
        } else if ($epasFlag == 'true') {

            $activity = "Uploaded the scanned EPAS of " . $name . " Record No." . $recordNo;
            $this->employee_model->logs($_SESSION['emp_id'], $_SESSION['username'], date("Y-m-d"), date("H:i:s"), $activity);
            $message = "Scanned EPAS Successfully Uploaded!";
        }

        die("success||" . $message);
    }

    public function uploadPromoScannedFile()
    {
        $fetch_data = $this->input->post(NULL, TRUE);

        $contract = $fetch_data['contracta'];
        $empId = $fetch_data['empId'];
        $recordNo = $fetch_data['recordNo'];
        $table = "";

        if ($contract == "current") :

            $table = "promo_record";
        else :

            $table = "promo_history_record";
        endif;

        $epasFlag = "";
        $contractFlag = "";
        $clearanceFlag = "";

        foreach ($fetch_data['clearance'] as $key => $value) {

            $destination_path = "";
            if (!empty($_FILES[$value]['name'])) {

                $image        = addslashes(file_get_contents($_FILES[$value]['tmp_name']));
                $image_name    = addslashes($_FILES[$value]['name']);
                $array     = explode(".", $image_name);

                $filename     = $empId . "=" . date('Y-m-d') . "=" . $value . "=" . date('H-i-s-A') . "." . end($array);
                $destination_path    = "../document/clearance/" . $filename;

                if (move_uploaded_file($_FILES[$value]['tmp_name'], $destination_path)) {

                    $this->employee_model->upload_scanned_file($table, $value, $destination_path, $empId, $recordNo);
                    $clearanceFlag = "true";
                }
            }
        }

        foreach ($fetch_data['contract'] as $key => $value) {

            $destination_path = "";
            if (!empty($_FILES[$value]['name'])) {

                $image        = addslashes(file_get_contents($_FILES[$value]['tmp_name']));
                $image_name    = addslashes($_FILES[$value]['name']);
                $array     = explode(".", $image_name);

                $filename     = $empId . "=" . date('Y-m-d') . "=" . $value . "=" . date('H-i-s-A') . "." . end($array);
                $destination_path    = "../document/contract/" . $filename;

                if (move_uploaded_file($_FILES[$value]['tmp_name'], $destination_path)) {

                    $this->employee_model->upload_scanned_file($table, $value, $destination_path, $empId, $recordNo);
                    $contractFlag = "true";
                }
            }
        }

        foreach ($fetch_data['epas'] as $key => $value) {

            $destination_path = "";
            if (!empty($_FILES[$value]['name'])) {

                $image        = addslashes(file_get_contents($_FILES[$value]['tmp_name']));
                $image_name    = addslashes($_FILES[$value]['name']);
                $array     = explode(".", $image_name);

                $filename     = $empId . "=" . date('Y-m-d') . "=" . $value . "=" . date('H-i-s-A') . "." . end($array);
                $destination_path    = "../document/epas/" . $filename;

                if (move_uploaded_file($_FILES[$value]['tmp_name'], $destination_path)) {

                    $this->employee_model->upload_scanned_file($table, $value, $destination_path, $empId, $recordNo);
                    $epasFlag = "true";
                }
            }
        }

        $message = "";
        $name = $this->employee_model->employee_name($fetch_data['empId'])['name'];

        if ($contractFlag == 'true' && $clearanceFlag == 'true' && $epasFlag == 'true') {

            $activity = "Uploaded the scanned Contract, Clearance and EPAS of " . $name . " Record No." . $recordNo;
            $this->employee_model->logs($_SESSION['emp_id'], $_SESSION['username'], date("Y-m-d"), date("H:i:s"), $activity);
            $message = "Contract, Clearance and Scanned EPAS are Successfully Uploaded!";
        } else if ($contractFlag == 'true' && $clearanceFlag == 'true') {

            $activity = "Uploaded the scanned Contract and Clearance of " . $name . " Record No." . $recordNo;
            $this->employee_model->logs($_SESSION['emp_id'], $_SESSION['username'], date("Y-m-d"), date("H:i:s"), $activity);
            $message = "Contract and Clearance are Successfully Uploaded!";
        } else if ($contractFlag == 'true' && $epasFlag == 'true') {

            $activity = "Uploaded the scanned Contract and EPAS of " . $name . " Record No." . $recordNo;
            $this->employee_model->logs($_SESSION['emp_id'], $_SESSION['username'], date("Y-m-d"), date("H:i:s"), $activity);
            $message = "Contract and Scanned EPAS are Successfully Uploaded!";
        } else if ($epasFlag == 'true' && $clearanceFlag == 'true') {

            $activity = "Uploaded the scanned Clearance and EPAS of " . $name . " Record No." . $recordNo;
            $this->employee_model->logs($_SESSION['emp_id'], $_SESSION['username'], date("Y-m-d"), date("H:i:s"), $activity);
            $message = "Clearance and Scanned EPAS are Successfully Uploaded!";
        } else if ($contractFlag == 'true') {

            $activity = "Uploaded the scanned Contract of " . $name . " Record No." . $recordNo;
            $this->employee_model->logs($_SESSION['emp_id'], $_SESSION['username'], date("Y-m-d"), date("H:i:s"), $activity);
            $message = "Contract Successfully Uploaded!";
        } else if ($clearanceFlag == 'true') {

            $activity = "Uploaded the scanned Clearance (override) " . $name . " Record No." . $recordNo;
            $this->employee_model->logs($_SESSION['emp_id'], $_SESSION['username'], date("Y-m-d"), date("H:i:s"), $activity);
            $message = "Clearance Successfully Uploaded!";
        } else if ($epasFlag == 'true') {

            $activity = "Uploaded the scanned EPAS of " . $name . " Record No." . $recordNo;
            $this->employee_model->logs($_SESSION['emp_id'], $_SESSION['username'], date("Y-m-d"), date("H:i:s"), $activity);
            $message = "Scanned EPAS Successfully Uploaded!";
        }

        die("success||" . $message);
    }

    public function select_agency()
    {
        $agency_code = $this->input->get('agency', TRUE);

        echo "<option value=''> --Select-- </option>";
        $agencies = $this->employee_model->agency_list();
        foreach ($agencies as $agency) {

            if ($agency->agency_code == $agency_code) {

                echo '<option value="' . $agency->agency_code . '" selected>' . $agency->agency_name . '</option>';
            } else {

                echo '<option value="' . $agency->agency_code . '">' . $agency->agency_name . '</option>';
            }
        }
    }

    public function select_department()
    {
        $data = $this->input->get(NULL, TRUE);

        $bunit_ids = array();
        foreach ($data['stores'] as $key => $value) {

            $s = explode('/', $value);
            $bunit_ids[] = $s[0];
        }

        echo "<option value=''> --Select-- </option>";
        $departments = $this->employee_model->select_departments($bunit_ids);
        foreach ($departments as $dept) {

            if ($dept->dept_name == $data['department']) {

                echo '<option value="' . $dept->dept_name . '" selected>' . $dept->dept_name . '</option>';
            } else {
                echo '<option value="' . $dept->dept_name . '">' . $dept->dept_name . '</option>';
            }
        }
    }

    public function select_cutoff()
    {
        $statCut = $this->input->get('statCut', TRUE);
        $cutoffs = $this->employee_model->cutoff_list();
        foreach ($cutoffs as $co) {

            $endFC = ($co->endFC != '') ? $co->endFC : 'last';
            if ($statCut == $co->statCut) {

                echo '<option value="' . $co->statCut . '" selected>' . $co->startFC . '-' . $endFC . ' / ' . $co->startSC . '-' . $co->endSC . '</option>';
            } else {

                echo '<option value="' . $co->statCut . '">' . $co->startFC . '-' . $endFC . ' / ' . $co->startSC . '-' . $co->endSC . '</option>';
            }
        }
    }

    public function select_position()
    {
        $position = $this->input->get('position', TRUE);

        echo '<option value=""> --Select-- </position>';
        $positions = $this->employee_model->list_of_positions();
        foreach ($positions as $pos) {

            if (strtolower($pos['position_title']) == strtolower($position)) {

                echo '<option value="' . $pos['position_title'] . '" selected>' . $pos['position_title'] . '</option>';
            } else {

                echo '<option value="' . $pos['position_title'] . '">' . $pos['position_title'] . '</option>';
            }
        }
    }

    public function select_position_level()
    {
        $position = $this->input->get('position', TRUE);

        $pos = $this->employee_model->position_level($position);
        echo json_encode(array('level' => $pos->level, 'level_no' => $pos->lvlno));
    }

    public function select_employee_type()
    {
        $emp_type = $this->input->get('empType', TRUE);

        echo '<option value=""> --Select-- </position>';
        $emp_types = $this->employee_model->emp_type();
        foreach ($emp_types as $emp) {

            if ($emp->emp_type == $emp_type) {

                echo '<option value="' . $emp->emp_type . '" selected>' . $emp->emp_type . '</option>';
            } else {

                echo '<option value="' . $emp->emp_type . '">' . $emp->emp_type . '</option>';
            }
        }
    }

    public function select_company()
    {
        $fetch = $this->input->get(NULL, TRUE);
        if ($fetch['agency_code'] == 0) {

            echo '<option value=""> --Select Company-- </option>';
            $companies = $this->employee_model->company_list();
            foreach ($companies as $company) {
                ?>
                <option value="<?= $company->pc_code ?>" <?php if ($fetch['promo_company'] == $company->pc_name) echo "selected=''"; ?>><?= $company->pc_name ?>
                </option>
                <?php
            }
        } else {

            echo '<option value=""> --Select Company-- </option>';
            $companies = $this->employee_model->company_list_under_agency($fetch['agency_code']);
            foreach ($companies as $company) {

                $supplier = $this->employee_model->getcompanyCodeBycompanyName($company->company_name);
                if (!empty($supplier)) {
                ?>
                    <option value="<?= $supplier->pc_code ?>" <?php if ($fetch['promo_company'] == $company->company_name) echo "selected=''"; ?>><?= $company->company_name ?></option>
            <?php
                }
            }
        }
    }

    public function select_product()
    {
        $company_code = $this->input->get('company_code', TRUE);
        $company = $this->employee_model->get_company_name($company_code);
        $products = $this->employee_model->promo_company_products($company->pc_name);
        foreach ($products as $product) {
            ?>
            <option value="<?= $product->product ?>"><?= $product->product ?></option>
        <?php
        }
    }

    public function load_products()
    {
        $data = $this->input->get(NULL, TRUE);

        $company = $this->employee_model->get_company_name($data['company']);
        $emp_products = explode("|", $data['product']);

        ?>
        <select name="product_select[]" class="form-control select2" multiple="multiple">

            <?php
            $products = $this->employee_model->locate_promo_products($company->pc_name);
            foreach ($products as $product) {

                if (in_array($product->product, $emp_products)) {

                    echo '<option value="' . $product->product . '" selected>' . $product->product . '</option>';
                } else {

                    echo '<option value="' . $product->product . '">' . $product->product . '</option>';
                }
            }
            ?>
        </select>
        <script type="text/javascript">
            $('.select2').select2();
            $("span.select2").css("width", "100%");
        </script>
    <?php
    }

    public function select_promo_products()
    {
        $data = $this->input->get(NULL, TRUE);

        $company = $this->employee_model->get_company_name($data['company']);

    ?>
        <select name="product_select[]" class="form-control select2" multiple="multiple">

            <?php
            $products = $this->employee_model->locate_promo_products($company->pc_name);
            foreach ($products as $product) {

                echo '<option value="' . $product->product . '">' . $product->product . '</option>';
            }
            ?>
        </select>
        <script type="text/javascript">
            $('.select2').select2();
            $("span.select2").css("width", "100%");
        </script>
        <?php
    }

    public function select_business_unit()
    {
        $promo_type = $this->input->get('promo_type', TRUE);
        if ($promo_type == 'ROVING') {
        ?>
            <table class="table table-bordered">
                <tr>
                    <th colspan="2"><i class="text-red">*</i> SELECT STORE</th>
                </tr>
                <?php

                $ctr = 0;
                $bUs = $this->dashboard_model->businessUnit_list();
                foreach ($bUs as $bu) {

                    $ctr++;
                ?>
                    <tr>
                        <td><input type="checkbox" id="check_<?= $ctr; ?>" name="<?= $bu->bunit_field ?>" value="<?= $bu->bunit_id . '/' . $bu->bunit_field ?>" onclick="locateDeptRoving()" /></td>
                        <td><?= $bu->bunit_name ?></td>
                    </tr>
                <?php
                }
                ?>
                <input type="hidden" name="counter" value="<?= $ctr; ?>">
            </table>
        <?php

        } else {
        ?>
            <table class="table table-bordered">
                <tr>
                    <th colspan="2"><i class="text-red">*</i> SELECT STORE</th>
                </tr>
                <?php

                $ctr = 0;
                $bUs = $this->dashboard_model->businessUnit_list();
                foreach ($bUs as $bu) {

                    $ctr++;
                ?>
                    <tr>
                        <td><input type="radio" name="station" id="radio_<?= $ctr; ?>" value="<?= $bu->bunit_id . '/' . $bu->bunit_field ?>" onclick="locateDeptStation(this.value)" /></td>
                        <td><?= $bu->bunit_name ?></td>
                    </tr>
                <?php
                }
                ?>
                <input type="hidden" name="counter" value="<?= $ctr; ?>">
            </table>
        <?php
        }
    }

    public function locate_promo_department()
    {
        $store_ids = $this->input->post('storeId', TRUE);
        $condition = '';
        $i = 0;
        foreach ($store_ids as $id) {

            $bunit_id = explode('/', $id);
            if ($i == 0) {

                $condition .= "AND (bunit_id = '" . $bunit_id[0] . "'";
            } else {

                $condition .= " OR bunit_id = '" . $bunit_id[0] . "'";
            }

            $i++;
        }

        if ($condition != "") {
            $condition .= ")";
        }

        echo '<option value=""> --Select Department-- </option>';
        $sql = "SELECT dept_name FROM locate_promo_department WHERE status = 'active' $condition GROUP BY dept_name ORDER BY dept_name ASC";
        $result = $this->employee_model->return_result_array($sql);
        foreach ($result as $res) {
        ?>
            <option value="<?= $res['dept_name'] ?>"><?= $res['dept_name'] ?></option>
        <?php
        }
    }

    public function select_vendor()
    {
        $department = $this->input->get('department', TRUE);
        if ($department == "EASY FIX") {
            $department = 'FIXRITE';
        }

        echo '<option value=""> --Select Vendor-- </option>';
        $vendors = $this->employee_model->locate_vendor($department);
        foreach ($vendors as $vendor) {
        ?>
            <option value="<?= $vendor->vendor_code ?>"><?= $vendor->vendor_name ?></option>
        <?php
        }
    }

    public function load_vendor()
    {
        $data = $this->input->get(NULL, TRUE);
        if ($data['department'] == "EASY FIX") {
            $data['department'] = 'FIXRITE';
        }

        echo '<option value=""> --Select Vendor-- </option>';
        $vendors = $this->employee_model->locate_vendor($data['department']);
        foreach ($vendors as $vendor) {

            if ($vendor->vendor_code == $data['vendor']) {

                echo '<option value="' . $vendor->verdor_name . '" selected>' . $vendor->verdor_name . '</option>';
            } else {

                echo '<option value="' . $vendor->verdor_name . '">' . $vendor->verdor_name . '</option>';
            }
        }
    }

    public function contract_duration()
    {
        $fetch_data = $this->input->get(NULL, TRUE);
        $dF =  new DateTime($fetch_data['dF']);
        $dT =  new DateTime($fetch_data['dT']);

        $newDF = strtotime($fetch_data['dF']);
        $newDT = strtotime($fetch_data['dT']);

        if ($newDF > $newDT) {

            echo json_encode(['message' => 'EOCdate must be greater than or equal to startdate!']);
        } else {

            $interval = $dT->diff($dF);
            $duration = $interval->format('%a') + 1;

            if ($duration >= 32) {
                $duration = $interval->format('%m');
            } else {
                $duration = "$duration day(s)";
            }

            echo json_encode(['message' => 'success', 'duration' => $duration]);
        }
    }

    public function updatePromoContract()
    {
        $request = $this->input->post(NULL, TRUE);
        $name = $this->employee_model->employee_name($request['empId']);

        $this->db->trans_start();

        $this->employee_model->update_employment_contract($request);
        $this->employee_model->empty_store_value($request);
        $this->employee_model->update_promo_details($request);
        $this->employee_model->update_promo_products($request);
        $this->employee_model->update_promo_cutoff($request);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            echo json_encode(['message' => 'Opps! Something went wrong.']);
            // generate an error... or use the log_message() function to log your error
        } else {

            echo json_encode(['message' => 'success']);
        }
    }

    public function addEmploymentHist()
    {
        $fetch_data = $this->input->post(NULL, TRUE);
        $no     = $fetch_data['no'];
        $empId  = $fetch_data['empId'];

        $sql = "SELECT * FROM application_employment_history WHERE no = '$no'";
        $row = $this->employee_model->return_row_array($sql);

        ?>
        <input type="hidden" name="empId" value="<?php echo $empId; ?>">
        <input type="hidden" name="no" value="<?php echo $no; ?>">
        <div class="form-group"> <i class="text-red">*</i>
            <label>Company</label>
            <input type="text" value="<?php echo $row['company']; ?>" name="company" class="form-control" onkeyup="inputField(this.name)">
        </div>
        <div class="form-group">
            <label>Address</label>
            <input type="text" value="<?php echo $row['address']; ?>" name="address" class="form-control" onkeyup="inputField(this.name)">
        </div>
        <div class="form-group"> <i class="text-red">*</i>
            <label>Position</label>
            <input type="text" value="<?php echo $row['position']; ?>" name="position" class="form-control" onkeyup="inputField(this.name)">
        </div>
        <div class="form-group">
            <label>Employment Certificate</label> <?php if (!empty($row['emp_certificate'])) : echo "<i class='text-red'> - Already Uploaded</i>";
                                                    endif; ?>
            <input type="file" name="certificate" class="form-control">
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Date Start</label>
                    <input type="text" value="<?php echo $row['yr_start']; ?>" name="startdate" class="form-control" onkeyup="inputField(this.name)">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Date End</label>
                    <input type="text" value="<?php echo $row['yr_ends']; ?>" name="eocdate" class="form-control" onkeyup="inputField(this.name)">
                </div>
            </div>
        </div>
<?php
    }

    public function submitEmploymentHist()
    {
        $fetch_data = $this->input->post(NULL, TRUE);

        $no = $fetch_data['no'];
        $empId = $fetch_data['empId'];

        $destination_path = "";
        if (!empty($_FILES['certificate']['name'])) {

            $image      = addslashes(file_get_contents($_FILES['certificate']['tmp_name']));
            $image_name = addslashes($_FILES['certificate']['name']);
            $array  = explode(".", $image_name);

            $filename   = $empId . "=" . date('Y-m-d') . "=" . 'Employment-Certificate' . "=" . date('H-i-s-A') . "." . end($array);
            $destination_path   = "../document/employment_certificate/" . $filename;

            move_uploaded_file($_FILES['certificate']['tmp_name'], $destination_path);
        }

        if ($no == "") {

            $employment = $this->employee_model->insert_application_employment_history($fetch_data, $destination_path);
            if ($employment) {

                $row = $this->employee_model->employee_name($fetch_data['empId']);
                $this->employee_model->logs($_SESSION['emp_id'], $_SESSION['username'], date("Y-m-d"), date("H:i:s"), 'Adding new Employment History of ' . $row['name']);;
                die("success||Added");
            }
        } else {

            $employment = $this->employee_model->update_application_employment_history($fetch_data, $destination_path);
            if ($employment) {

                $row = $this->employee_model->employee_name($fetch_data['empId']);
                $this->employee_model->logs($_SESSION['emp_id'], $_SESSION['username'], date("Y-m-d"), date("H:i:s"), 'Updated Employment History of ' . $row['name']);;
                die("success||Updated");
            }
        }
    }

    public function employmentCertificate()
    {
        $fetch_data = $this->input->post(NULL, TRUE);

        $row =  $this->employee_model->employment_certificate($fetch_data);
        echo $row['emp_certificate'];
    }

    public function viewJobTrans()
    {
        $data['fetch'] = $this->input->post(NULL, TRUE);
        $data['request'] = "viewJobTrans";

        $this->load->view('body/placement/modal_response', $data);
    }

    public function addBlacklist()
    {
        $data['fetch'] = $this->input->post(NULL, TRUE);
        $data['request'] = "addBlacklist";

        $this->load->view('body/placement/modal_response', $data);
    }

    public function submitBlacklist()
    {
        $fetch_data = $this->input->post(NULL, TRUE);

        if ($fetch_data['no'] == "") {

            $blacklist = $this->employee_model->insert_blacklist($fetch_data);
            $update_status = $this->employee_model->update_current_status($fetch_data['empId']);

            if ($blacklist && $update_status) {

                die("success||Added!");
            }
        } else {

            $blacklist = $this->employee_model->update_blacklist($fetch_data);
            $update_status = $this->employee_model->update_current_status($fetch_data['empId']);

            if ($blacklist && $update_status) {

                die("success||Updated!");
            }
        }
    }

    public function update_benefits()
    {
        $fetch_data = $this->input->post(NULL, TRUE);

        $pagibigrtn = $_POST['pagibigrtn'];
        $recordedby = $_SESSION['username'] . "/" . $_SESSION['emp_id'];

        $check = "SELECT * FROM applicant_otherdetails where app_id = '" . $_POST['empId'] . "'";
        if ($this->employee_model->return_num_rows($check) > 0) {
            $act = "Updating the Benefits Info of";
            $updatebenefits = $this->employee_model->update_applicant_otherdetails($fetch_data);
        } else {

            $act = "Adding the Benefits Info of";
            $updatebenefits = $this->employee_model->insert_applicant_otherdetails($fetch_data);
        }

        if ($updatebenefits) {

            $row = $this->employee_model->employee_name($fetch_data['empId']);
            $this->employee_model->logs($_SESSION['emp_id'], $_SESSION['username'], date("Y-m-d"), date("H:i:s"), $act . " " . $row['name']);
            die("success");
        }
    }

    public function view201File()
    {
        $data['fetch'] = $this->input->post(NULL, TRUE);
        $data['request'] = "view201File";

        $this->load->view('body/placement/modal_response', $data);
    }

    public function upload201Files()
    {
        $data['fetch'] = $this->input->post(NULL, TRUE);
        $data['request'] = "upload201Files";

        $this->load->view('body/placement/modal_response', $data);
    }

    public function upload201File()
    {
        $fetch_data = $this->input->post(NULL, TRUE);
        $empId  = $fetch_data['empId'];
        $no     = $fetch_data['sel201File'];

        $sql = "SELECT 201_name, tableName, requirementName, path, empField FROM `201document` WHERE no = '$no'";
        $row = $this->employee_model->return_row_array($sql);

        $reqName    = $row['requirementName'];
        $tableName  = $row['tableName'];
        $empField   = $row['empField'];
        $path       = $row['path'];
        $req        = explode("/", $row['path']);
        $filename   = end($req);

        $destination_path = "";

        if (!empty($_FILES['file_upload']['name'])) {

            $file = $_FILES['file_upload']['name'];
            for ($i = 0; $i < count($file); $i++) {

                $query = "SELECT 
                                $empField 
                            FROM 
                                `$tableName`
                            WHERE 
                                $empField = '" . $empId . "' AND requirement_name = '" . $reqName . "'
                        ";
                $num = $this->employee_model->return_num_rows($query) + 1;

                $image = $file[$i];
                $array = explode(".", $image);

                $destination_path   = $path . "/" . $empId . "=" . $num . "=" . date('Y-m-d') . "=" . $reqName . "=" . date('H-i-s-A') . "." . end($array);

                if (move_uploaded_file($_FILES['file_upload']['tmp_name'][$i], $destination_path)) {

                    $document = $this->employee_model->insert_201_document($empField, $empId, $reqName, $destination_path, $tableName);

                    if ($document) {

                        $row = $this->employee_model->employee_name($fetch_data['empId']);
                        $this->employee_model->logs($_SESSION['emp_id'], $_SESSION['username'], date("Y-m-d"), date("H:i:s"), "Uploaded the 201 file [ " . $reqName . " ] of " . $row['name']);
                    }
                }
            }

            die("success");
        }
    }

    public function removeSubordinates()
    {
        $fetch_data = $this->input->post(NULL, TRUE);

        $supervisor = $this->employee_model->remove_supervisor($fetch_data);
        if ($supervisor) {
            die("success");
        }
    }

    public function addSupervisor()
    {
        $data['fetch'] = $this->input->post(NULL, TRUE);
        $data['request'] = "addSupervisor";

        $this->load->view('body/placement/modal_response', $data);
    }

    public function selectSupervisor()
    {
        $data['fetch'] = $this->input->post(NULL, TRUE);
        $data['request'] = "selectSupervisor";

        $this->load->view('body/placement/modal_response', $data);
    }

    public function saveSupervisor()
    {
        $fetch_data = $this->input->post(NULL, TRUE);
        $empId = $fetch_data['empId'];
        $supIds = explode("*", $fetch_data['newCHK']);

        for ($i = 0; $i < sizeof($supIds) - 1; $i++) {

            $this->employee_model->insert_leveling_subordinates($supIds[$i], $empId);
        }
        die("success");
    }

    public function update_remarks()
    {
        $fetch_data = $this->input->post(NULL, TRUE);

        $chk = "SELECT * FROM remarks where emp_id = '" . $_POST['empId'] . "'";
        if ($this->employee_model->return_num_rows($chk) > 0) {

            $remarks = $this->employee_model->update_remarks($fetch_data);
        } else {

            $remarks = $this->employee_model->insert_remarks($fetch_data);
        }

        if ($remarks) {

            $row = $this->employee_model->employee_name($fetch_data['empId']);
            $this->employee_model->logs($_SESSION['emp_id'], $_SESSION['username'], date("Y-m-d"), date("H:i:s"), "Saving remarks of " . $row['name']);
            die("success");
        }
    }

    public function resetPass()
    {
        $fetch_data = $this->input->post(NULL, TRUE);

        $password = $this->employee_model->reset_password($fetch_data);
        if ($password) {

            die("Password Successfully Resetted");
        }
    }

    public function activateAccount()
    {
        $fetch_data = $this->input->post(NULL, TRUE);

        $account = $this->employee_model->activate_account($fetch_data);
        if ($account) {

            die("Successfully Activated the User Account");
        }
    }

    public function deactivateAccount()
    {
        $fetch_data = $this->input->post(NULL, TRUE);

        $account = $this->employee_model->deactivate_account($fetch_data);
        if ($account) {

            die("Successfully Deactivated the User Account");
        }
    }

    public function deleteAccount()
    {
        $fetch_data = $this->input->post(NULL, TRUE);

        $account = $this->employee_model->delete_account($fetch_data);
        if ($account) {

            die("User Account Successfully Deleted");
        }
    }

    public function addUserAccount()
    {
        $data['fetch'] = $this->input->post(NULL, TRUE);
        $data['request'] = "addUserAccount";

        $this->load->view('body/placement/modal_response', $data);
    }

    public function submitAccount()
    {
        $fetch_data = $this->input->post(NULL, TRUE);
        $username = $fetch_data['username'];

        $check = "SELECT user_no FROM `users` WHERE username = '$username'";
        $chkNum = $this->employee_model->return_num_rows($check);

        if ($chkNum > 0) {
            die("exist");
        }

        $account = $this->employee_model->insert_users($fetch_data);
        if ($account) {
            die("success");
        }
    }

    public function load_business_unit()
    {
        $data['promo_type'] = $this->input->get('promo_type', TRUE);
        $data['request'] = 'load_business_unit';

        $this->load->view('body/placement/modal_response', $data);
    }

    public function load_department()
    {
        $data = $this->input->get(NULL, TRUE);

        $bunit_ids = array();
        foreach ($data['stores'] as $key => $value) {

            $s = explode('/', $value);
            $bunit_ids[] = $s[0];
        }

        echo "<option value=''> --Select-- </option>";
        $departments = $this->employee_model->select_departments($bunit_ids);
        foreach ($departments as $dept) {

            echo '<option value="' . $dept->dept_name . '">' . $dept->dept_name . '</option>';
        }
    }

    public function load_promo_business_unit()
    {
        $data['fetch_data'] = $this->input->get(NULL, TRUE);
        $data['request'] = 'load_promo_business_unit';

        $this->load->view('body/placement/modal_response', $data);
    }

    public function load_promo_intro()
    {
        $data['empId'] = $this->input->get('empId', TRUE);
        $data['request'] = 'load_promo_intro';

        $this->load->view('body/placement/modal_response', $data);
    }

    public function select_promo_type()
    {
        $promo_type = $this->input->get('promoType', TRUE);
        $promo_types = array('STATION', 'ROVING');

        foreach ($promo_types as $key => $value) {
            if ($promo_type == $value) {

                echo '<option value="' . $value . '" selected>' . $value . '</option>';
            } else {

                echo '<option value="' . $value . '">' . $value . '</option>';
            }
        }
    }
}
