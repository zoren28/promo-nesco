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
            $sub_array[] = '<span style="display:none">' . $row->record_no . '</span><a href="profile/' . $row->emp_id . '" target="_blank">' . ucwords(strtolower($row->name)) . '</a>';
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
        echo "<option value=''> Select Department </option>";
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
}