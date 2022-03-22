<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Employee_model extends CI_Model
{
    public $date = '';
    public $loginId = '';
    public $db2 = '';

    function __construct()
    {
        parent::__construct();
        $this->load->library('nativesession');

        $this->date = date('Y-m-d');
        $this->loginId = $_SESSION['emp_id'];
        $this->db2 = $this->load->database('timekeeping', TRUE);

        $this->db->query('SET SESSION sql_mode = ""');
    }

    public function return_result_array($sql)
    {
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function return_row_array($sql)
    {
        $query = $this->db->query($sql);
        return $query->row_array();
    }

    public function return_num_rows($sql)
    {
        $query = $this->db->query($sql);
        return $query->num_rows();
    }

    public function return_result($sql)
    {
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function company_list()
    {
        $query = $this->db->order_by('pc_name', 'ASC')
            ->get_where('locate_promo_company', array('status' => 1));
        return $query->result();
    }

    public function nesco_company_list()
    {
        $query = $this->db2->select('company_name')
            ->from('promo_locate_company')
            ->join('promo_locate_agency', 'promo_locate_agency.agency_code = promo_locate_company.agency_code')
            ->where('promo_locate_agency.agency_code', '29')
            ->get();
        return $query->result();
    }

    public function company_list_under_agency($agency_code)
    {
        $query = $this->db2->select('company_name')
            ->from('promo_locate_company')
            ->join('promo_locate_agency', 'promo_locate_agency.agency_code = promo_locate_company.agency_code')
            ->where('promo_locate_agency.agency_code', $agency_code)
            ->get();
        return $query->result();
    }

    public function getcompanyCodeBycompanyName($company_name)
    {
        $query = $this->db->select('pc_code')
            ->get_where('locate_promo_company', array('pc_name' => $company_name));
        return $query->row();
    }

    public function employee_name($emp_id)
    {
        $query = $this->db->select('name')
            ->get_where('employee3', array('emp_id' => $emp_id));
        return $query->row_array();
    }

    public function applicant_name($app_id)
    {
        $query = $this->db->select('firstname, lastname, middlename, suffix')
            ->get_where('applicant', array('app_id' => $app_id));
        $applicant = $query->row();

        $suffix = '';
        if (!empty($applicant->suffix)) {
            $suffix = ", $applicant->suffix";
        }

        if (!empty($applicant->middlename)) {

            $name = $applicant->firstname . ' ' . $applicant->middlename . ' ' . $applicant->lastname . '' . $suffix;
        } else {

            $name = $applicant->firstname . ' ' . $applicant->lastname . '' . $suffix;
        }

        return $name;
    }

    public function update_family($data)
    {
        if ($data['mother_bdate'] == "" || $data['mother_bdate'] == "00/00/0000") {
            $mother_bdate = "";
        } else {
            $mother_bdate = date("Y-m-d", strtotime($data['mother_bdate']));
        }
        if ($data['father_bdate'] == "" || $data['father_bdate'] == "00/00/0000") {
            $father_bdate = "";
        } else {
            $father_bdate = date("Y-m-d", strtotime($data['father_bdate']));
        }
        if ($data['spouse_bdate'] == "" || $data['spouse_bdate'] == "00/00/0000") {
            $spouse_bdate = "";
        } else {
            $spouse_bdate = date("Y-m-d", strtotime($data['spouse_bdate']));
        }

        $update = array(
            'mother'  => $data['mother'],
            'father' => $data['father'],
            'guardian' => $data['guardian'],
            'spouse' => $data['spouse'],
            'mother_bdate'  => $mother_bdate,
            'father_bdate' => $father_bdate,
            'spouse_bdate'    => $spouse_bdate,
            'mother_work' => $data['mother_work'],
            'father_work' => $data['father_work'],
            'spouse_work'    => $data['spouse_work']
        );

        $this->db->where('app_id', $data['empId']);
        return $this->db->update('applicant', $update);
    }

    public function find_hr_staff($fetch)
    {
        $this->db->query('SET SESSION sql_mode = ""');

        // ONLY_FULL_GROUP_BY
        $this->db->query('SET SESSION sql_mode =
                              REPLACE(REPLACE(REPLACE(
                              @@sql_mode,
                              "ONLY_FULL_GROUP_BY,", ""),
                              ",ONLY_FULL_GROUP_BY", ""),
                              "ONLY_FULL_GROUP_BY", "")');

        return $this->db->select('employee3.emp_id, name')
            ->from('employee3')
            ->join('users', 'employee3.emp_id = users.emp_id')
            ->group_start()
            ->where('usertype', 'administrator')
            ->or_where('usertype', 'placement1')
            ->or_where('usertype', 'placement2')
            ->or_where('usertype', 'placement3')
            ->or_where('usertype', 'placement4')
            ->or_where('usertype', 'nesco')
            ->or_where('usertype', 'franchise')
            ->group_end()
            ->group_start()
            ->like('name', $fetch['str'])
            ->or_where('employee3.emp_id', $fetch['str'])
            ->group_end()
            ->group_by('users.emp_id')
            ->order_by('name', 'ASC')
            ->limit(10)
            ->get();
    }

    public function employee_list($data)
    {
        if (!empty($data['company'])) {
            $company = $this->get_company_name($data['company'])->pc_name;
        }

        $where = array('emp_type' => 'Promo-NESCO', 'current_status' => 'Active');
        $this->db->select('employee3.record_no, employee3.emp_id, name, agency_code, promo_company, promo_department, al_tag, al_tal, icm, pm, abenson_tag, abenson_icm, cdc, berama, al_tub, colc, colm, alta_citta, bq, shoppers, promo_type, position, current_status')
            ->from('employee3')
            ->join('promo_record', 'promo_record.record_no = employee3.record_no')
            ->where($where);

        if (!empty($data['promo_type'])) {
            $this->db->where('promo_type', $data['promo_type']);
        }

        if (!empty($data['department'])) {
            $this->db->where('promo_department', $data['department']);
        }

        if (!empty($data['business_unit'])) {

            $field = explode('/', $data['business_unit']);
            $this->db->where($field[1], 'T');
        }

        if (!empty($data['company'])) {
            $this->db->where('promo_company', $company);
        }

        $this->db->order_by('name', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_company_name($pc_code)
    {
        $query = $this->db->select('pc_name')
            ->get_where('locate_promo_company', array('pc_code' => $pc_code));
        return $query->row();
    }

    public function assigned_departments($id)
    {
        $query = $this->db->select('dept_name')
            ->get_where('locate_promo_department', array('bunit_id' => $id));
        return $query->result();
    }

    public function search_employee($search)
    {
        $query = $this->db->select('employee3.record_no, employee3.emp_id, name, agency_code, promo_company, promo_department, al_tag, al_tal, icm, pm, abenson_tag, abenson_icm, cdc, berama, al_tub, colc, colm, alta_citta, bq, shoppers, promo_type, type, current_status, position, startdate, eocdate')
            ->from('employee3')
            ->join('promo_record', 'promo_record.record_no = employee3.record_no')
            ->where('emp_type', 'Promo-NESCO')
            ->group_start()
            ->like('name', $search)
            ->or_like('employee3.emp_id', $search)
            ->group_end()
            ->order_by('name', 'ASC')
            ->get();
        return $query->result();
    }

    public function get_applicant_info($app_id)
    {
        $query = $this->db->get_where('applicant', array('app_id' => $app_id));
        return $query->row();
    }

    public function update_photo($emp_id, $path)
    {
        $update = array(
            'photo'  => $path
        );

        $this->db->where('app_id', $emp_id);
        return $this->db->update('applicant', $update);
    }

    public function search_applicant($data)
    {
        $lname = trim($data['lname']);
        $fname = trim($data['fname']);

        $query = $this->db->select('app_id, lastname, firstname, middlename, birthdate, home_address, civilstatus, photo, suffix')
            ->from('applicant')
            ->where('lastname', $lname)
            ->like('firstname', $fname)
            ->get();
        return $query->result();
    }

    public function employee_info($emp_id)
    {
        $query = $this->db->select('employee3.record_no, employee3.emp_id, name, emp_no, emp_type, agency_code, promo_company, promo_department, vendor_code, al_tag, al_tal, icm, pm, abenson_tag, abenson_icm, cdc, berama, al_tub, colc, colm, alta_citta, bq, shoppers, promo_type, type, current_status, position, startdate, eocdate')
            ->from('employee3')
            ->join('promo_record', 'promo_record.record_no = employee3.record_no', 'left')
            ->where('emp_type', 'Promo-NESCO')
            ->where('employee3.emp_id', $emp_id)
            ->get();
        return $query->row();
    }

    public function check_blacklisted($app_id)
    {
        $query = $this->db->select('COUNT(blacklist_no) AS num')
            ->get_where('blacklist', array('app_id' => $app_id));
        return $query->row()->num;
    }

    public function applicant_status($app_id)
    {
        $query = $this->db->select('applicants.status')
            ->from('applicant')
            ->join('applicants', 'applicant.appcode = applicants.app_code')
            ->where('app_id', $app_id)
            ->get();
        return $query->row()->status;
    }

    public function user_login($emp_id)
    {
        $query = $this->db->select('login')
            ->from('users')
            ->where('emp_id', $emp_id)
            ->where('usertype', 'employee')
            ->get();
        return $query->row()->login;
    }

    public function date_hired($app_id)
    {
        $query = $this->db->select('date_hired')
            ->get_where('application_details', array('app_id' => $app_id));
        return $query->row()->date_hired;
    }

    public function agency_name($agency_code)
    {
        $query = $this->db2->select('agency_name')
            ->get_where('promo_locate_agency', array('agency_code' => $agency_code));
        return $query->row()->agency_name;
    }

    public function vendor_name($vendor_code)
    {
        $query = $this->db->select('vendor_name')
            ->get_where('promo_vendor_lists', array('vendor_code' => $vendor_code));
        return $query->row()->vendor_name;
    }

    public function select_all($tablename)
    {
        $query = $this->db->get($tablename);
        return $query->result();
    }

    public function update_basicinfo($data)
    {
        if ($data['datebirth'] == "" || $data['datebirth'] == "00/00/0000") {

            $birthdate = "";
        } else {

            $birthdate = date("Y-m-d", strtotime($data['datebirth']));
        }

        $update = array(
            'lastname'  => $data['lname'],
            'firstname' => $data['fname'],
            'middlename' => $data['mname'],
            'birthdate' => $birthdate,
            'religion'  => $data['religion'],
            'civilstatus' => $data['civilstatus'],
            'gender'    => $data['gender'],
            'citizenship' => $data['citizenship'],
            'bloodtype' => $data['bloodtype'],
            'weight'    => $data['weight'],
            'height'    => $data['height'],
            'suffix'    => $data['suffix']
        );

        $this->db->where('app_id', $data['empId']);
        return $this->db->update('applicant', $update);
    }

    public function update_name($emp_id, $name)
    {
        $this->db->set('name', $name);
        $this->db->where('emp_id', $emp_id);
        return $this->db->update('employee3');
    }

    public function logs($user, $username, $date, $time, $activity)
    {
        $data = array(
            'activity' => $activity,
            'date' => $date,
            'time' => $time,
            'user' => $user,
            'username' => $username
        );

        return $this->db->insert('logs', $data);
    }

    public function find_mothers_name($str)
    {
        $query = $this->db->select('emp_id, name')
            ->from('employee3')
            ->like('name', $str)
            ->order_by('name', 'ASC')
            ->limit(10)
            ->get();
        return $query->result_array();
    }

    public function spouse_info($empId)
    {
        $query = $this->db->select('spouseId, empId, spouse_empId, spouse')
            ->get_where('spouse_info', array('empId' => $empId));
        return $query;
    }

    public function asawa_info($empId)
    {
        $query = $this->db->select('spouse_empId')
            ->get_where('spouse_info', array('empId' => $empId, 'spouse_empId !=' => ''));
        return $query;
    }

    public function num_of_children($spouseId)
    {
        $query = $this->db->select('COUNT(childId) AS no_child')
            ->get_where('children_info', array('spouseId' => $spouseId));
        return $query->row_array();
    }

    public function children_info($spouseId, $empId)
    {
        $query = $this->db->select('childId, firstname, middlename, lastname, bday, gender, birth_cert, deceased')
            ->from('spouse_info')
            ->join('children_info', 'spouse_info.spouseId = children_info.spouseId')
            ->where('spouse_info.spouseId', $spouseId)
            ->where('empId', $empId)
            ->get();
        return $query->result_array();
    }

    public function fetch_birthCert($childId)
    {
        $query = $this->db->select('birth_cert')
            ->get_where('children_info', array('childId' => $childId));
        return $query->row_array();
    }

    public function update_birthCert($childId, $path)
    {
        $update = array(
            'birth_cert' => $path
        );

        $this->db->where('childId', $childId);
        return $this->db->update('children_info', $update);
    }

    public function delete_children_info($childId)
    {
        return $this->db->delete('children_info', array('childId' => $childId));
    }

    public function insert_children_info($data)
    {
        $insert = array(
            'spouseId'  => $data['spouseId'],
            'firstname' => $data['fname'],
            'middlename' => $data['mname'],
            'lastname'  => $data['lname'],
            'bday'      => $data['bday'],
            'gender'    => $data['gender'],
            'deceased'  => $data['deceased']
        );

        return $this->db->insert('children_info', $insert);
    }

    public function update_spouse_info($spouseId, $empId, $spouse_empId, $spouse)
    {
        $update = array(
            'empId' => $empId,
            'spouse_empId' => $spouse_empId,
            'spouse'  => $spouse,
            'dateUpdated'  => $this->date,
            'updatedBy'  => $this->loginId
        );

        $this->db->where('spouseId', $spouseId);
        return $this->db->update('spouse_info', $update);
    }

    public function last_spouseId()
    {
        $query = $this->db->select('MAX(spouseId) + 1 AS spouse_id')
            ->get('spouse_info');
        return $query->row_array();
    }

    public function update_children_info($data)
    {
        $update = array(
            'firstname' => $data['fname'],
            'middlename' => $data['mname'],
            'lastname'  => $data['lname'],
            'bday'      => $data['bday'],
            'gender'    => $data['gender'],
            'deceased'  => $data['deceased']
        );

        $this->db->where('childId', $data['childId']);
        return $this->db->update('children_info', $update);
    }

    function insert_spouse_info($spouseId, $empId, $spouse_empId, $spouse)
    {
        $insert = array(
            'spouseId' => $spouseId,
            'empId' => $empId,
            'spouse_empId' => $spouse_empId,
            'spouse'  => $spouse,
            'dateAdded'  => $this->date,
            'addedBy'  => $this->loginId
        );

        return $this->db->insert('spouse_info', $insert);
    }

    public function contactinfo($emp_id)
    {
        $this->db->select('home_address, city_address, contact_person, contact_person_address, contact_person_number, contactno, telno, email, facebookAcct, twitterAcct');
        $query = $this->db->get_where('applicant', array('app_id' => $emp_id));
        return $query->row_array();
    }

    public function brgy_town_prov()
    {

        $query = $this->db->select('brgy_name, town_name, prov_name')
            ->from('barangay')
            ->join('town', 'barangay.town_id = town.town_id')
            ->join('province', 'town.prov_id = province.prov_id')
            ->get();
        return $query->result_array();
    }

    public function update_contact($data)
    {
        $update = array(
            'home_address'  => $data['homeaddress'],
            'city_address' => $data['cityaddress'],
            'contact_person' => $data['contactperson'],
            'contact_person_address' => $data['contactpersonadd'],
            'contact_person_number'  => $data['contactpersonno'],
            'contactno' => $data['cellphone'],
            'telno'    => $data['telno'],
            'email' => $data['email'],
            'facebookAcct' => $data['fb'],
            'twitterAcct'    => $data['twitter']
        );

        $this->db->where('app_id', $data['empId']);
        return $this->db->update('applicant', $update);
    }

    public function educinfo($emp_id)
    {
        $this->db->select('attainment, school, course');
        $query = $this->db->get_where('applicant', array('app_id' => $emp_id));
        return $query->row_array();
    }

    public function attainment()
    {
        $this->db->select('attainment');
        $query = $this->db->get('attainment');
        return $query->result_array();
    }

    public function school_name()
    {
        $this->db->distinct('school_name');
        $query = $this->db->get('school');
        return $query->result_array();
    }

    public function update_educ($data)
    {
        $update = array(
            'attainment' => $data['attainment'],
            'school'    => $data['school'],
            'course'    => $data['course']
        );

        $this->db->where('app_id', $data['empId']);
        return $this->db->update('applicant', $update);
    }

    public function seminars_and_eligibility($emp_id)
    {
        $query = $this->db->get_where('application_seminarsandeligibility', array('app_id' => $emp_id));
        return $query->result_array();
    }

    public function seminar_info($no)
    {
        $query = $this->db->get_where('application_seminarsandeligibility', array('no' => $no));
        return $query->row_array();
    }

    public function insert_seminar_info($data, $path)
    {
        $insert = array(
            'app_id' => $data['appId'],
            'name' => $data['semName'],
            'dates' => $data['semDate'],
            'location' => $data['semLocation'],
            'sem_certificate' => $path
        );

        return $this->db->insert('application_seminarsandeligibility', $insert);
    }

    public function update_seminar_info($data, $path)
    {
        if (!empty($path)) {
            $update = array(
                'name' => $data['semName'],
                'dates' => $data['semDate'],
                'location' => $data['semLocation'],
                'sem_certificate' => $path
            );
        } else {

            $update = array(
                'name' => $data['semName'],
                'dates' => $data['semDate'],
                'location' => $data['semLocation']
            );
        }

        $this->db->where('no', $data['no']);
        $this->db->where('app_id', $data['appId']);
        return $this->db->update('application_seminarsandeligibility', $update);
    }

    public function seminar_cert($data)
    {
        $this->db->select('sem_certificate');
        $query = $this->db->get_where('application_seminarsandeligibility', array('no' => $data['no']));
        return $query->row_array();
    }

    public function character_ref($emp_id)
    {
        $query = $this->db->get_where('application_character_ref', array('app_id' => $emp_id));
        return $query->result_array();
    }

    public function character_ref_info($data)
    {
        $query = $this->db->get_where('application_character_ref', array('no' => $data['no'], 'app_id' => $data['empId']));
        return $query->row_array();
    }

    public function insert_character_ref($data)
    {
        $insert = array(
            'app_id'    => $data['empId'],
            'name'      => $data['charName'],
            'position'  => $data['charPosition'],
            'contactno' => $data['charContact'],
            'company'   => $data['charCompanyLocation']
        );

        return $this->db->insert('application_character_ref', $insert);
    }

    public function update_character_ref($data)
    {
        $update = array(
            'name' => $data['charName'],
            'position' => $data['charPosition'],
            'contactno' => $data['charContact'],
            'company' => $data['charCompanyLocation']
        );

        $this->db->where('no', $data['no']);
        $this->db->where('app_id', $data['empId']);
        return $this->db->update('application_character_ref', $update);
    }

    public function skills_info($emp_id)
    {
        $this->db->select('hobbies, specialSkills');
        $query = $this->db->get_where('applicant', array('app_id' => $emp_id));
        return $query->row_array();
    }

    public function update_skills($data)
    {
        $update = array(
            'hobbies' => $data['hobbies'],
            'specialSkills' => $data['skills']
        );

        $this->db->where('app_id', $data['empId']);
        return $this->db->update('applicant', $update);
    }

    public function appraisal_info($details_id)
    {
        $this->db->select('numrate, descrate, ratercomment, rateecomment, ratingdate, code');
        $query = $this->db->get_where('appraisal_details', array('details_id' => $details_id));
        return $query->row_array();
    }

    public function appraisal_rate($details_id)
    {
        $query = $this->db->select('q_no, title, description, rate')
            ->from('appraisal')
            ->join('appraisal_answer', 'appraisal.appraisal_id = appraisal_answer.appraisal_id')
            ->where('details_id', $details_id)
            ->get();
        return $query->result_array();
    }

    public function application_info($emp_id)
    {
        $this->db->select('date_brief, date_hired, date_applied, aeregular, exam_results, position_applied, date_examined');
        $query = $this->db->get_where('application_details', array('app_id' => $emp_id));
        return $query->row_array();
    }

    public function list_of_positions()
    {
        $query = $this->db->select('position_title')
            ->from('position_leveling')
            ->order_by('position_title', 'ASC')
            ->get();
        return $query->result_array();
    }

    public function update_application_history($data)
    {
        if ($data['dateApplied'] == '') {
            $dateApplied = '';
        } else {
            $dateApplied = date('Y-m-d', strtotime($data['dateApplied']));
        }
        if ($data['dateHired'] == '') {
            $dateHired = '';
        } else {
            $dateHired     = date('Y-m-d', strtotime($data['dateHired']));
        }
        if ($data['dateBrief'] == '') {
            $dateBrief = '';
        } else {
            $dateBrief     = date('Y-m-d', strtotime($data['dateBrief']));
        }
        if ($data['dateExamined'] == '') {
            $dateExamined = '';
        } else {
            $dateExamined      = date('Y-m-d', strtotime($data['dateExamined']));
        }

        $update = array(
            'date_applied' => $dateApplied,
            'date_brief' => $dateBrief,
            'date_hired' => $dateHired,
            'aeregular' => $data['aeRegular'],
            'date_examined' => $dateExamined,
            'position_applied' => $data['posApplied'],
            'exam_results' => $data['examResult']
        );

        $this->db->where('app_id', $data['empId']);
        return $this->db->update('application_details', $update);
    }

    public function insert_application_history($data)
    {
        if ($data['dateApplied'] == '') {
            $dateApplied = '';
        } else {
            $dateApplied = date('Y-m-d', strtotime($data['dateApplied']));
        }
        if ($data['dateHired'] == '') {
            $dateHired = '';
        } else {
            $dateHired     = date('Y-m-d', strtotime($data['dateHired']));
        }
        if ($data['dateBrief'] == '') {
            $dateBrief = '';
        } else {
            $dateBrief     = date('Y-m-d', strtotime($data['dateBrief']));
        }
        if ($data['dateExamined'] == '') {
            $dateExamined = '';
        } else {
            $dateExamined      = date('Y-m-d', strtotime($data['dateExamined']));
        }

        $insert = array(
            'app_id' => $data['empId'],
            'position_applied' => $data['posApplied'],
            'date_applied' => $dateApplied,
            'exam_results' => $data['examResult'],
            'date_examined' => $dateExamined,
            'date_brief' => $dateBrief,
            'date_hired' => $dateHired,
            'aeregular' => $data['aeRegular']
        );

        return $this->db->insert('application_details', $insert);
    }

    public function promo_details($table, $emp_id, $record_no)
    {
        $query = $this->db->select('promo_company, promo_department')
            ->get_where($table, array('emp_id' => $emp_id, 'record_no' => $record_no));
        return $query->row();
    }

    // get company, business unit, department, section, sub-section and unit name
    public function asc_company_name($cc)
    {
        $this->db->select('company, acroname');
        $query = $this->db->get_where('locate_company', array('company_code' => $cc));
        return $query->row_array();
    }

    public function get_businessunit_name($cc, $bc)
    {
        $this->db->select('business_unit, acroname');
        $array = array('company_code' => $cc, 'bunit_code' => $bc);
        $query = $this->db->get_where('locate_business_unit', $array);
        return $query->row_array();
    }

    public function get_department_name($cc, $bc, $dc)
    {
        $this->db->select('dept_name, acroname');
        $array = array('company_code' => $cc, 'bunit_code' => $bc, 'dept_code' => $dc);
        $query = $this->db->get_where('locate_department', $array);
        return $query->row_array();
    }

    public function get_section_name($cc, $bc, $dc, $sc)
    {
        $this->db->select('section_name');
        $query = $this->db->get_where('locate_section', array('company_code' => $cc, 'bunit_code' => $bc, 'dept_code' => $dc, 'section_code' => $sc));
        return $query->row_array();
    }

    public function get_sub_section_name($cc, $bc, $dc, $sc, $ssc)
    {
        $this->db->select('sub_section_name');
        $query = $this->db->get_where('locate_sub_section', array('company_code' => $cc, 'bunit_code' => $bc, 'dept_code' => $dc, 'section_code' => $sc, 'sub_section_code' => $ssc));
        return $query->row_array();
    }

    public function get_unit_name($cc, $bc, $dc, $sc, $ssc, $uc)
    {
        $this->db->select('unit_name');
        $query = $this->db->get_where('locate_unit', array('company_code' => $cc, 'bunit_code' => $bc, 'dept_code' => $dc, 'section_code' => $sc, 'sub_section_code' => $ssc, 'unit_code' => $uc));
        return $query->row_array();
    }

    public function ae_company_list()
    {
        $query = $this->db->select('company_code, company, acroname')
            ->where('status', 'active')
            ->order_by('company', 'ASC')
            ->get('locate_company');
        return $query->result_array();
    }

    public function employee_type()
    {
        $query = $this->db->select('emp_type')
            ->from('employee_type')
            ->order_by('emp_type', 'ASC')
            ->get();
        return $query->result_array();
    }

    public function locate_business_unit($fetch)
    {
        $query = $this->db->select('business_unit, company_code, bunit_code')
            ->from('locate_business_unit')
            ->where('company_code', $fetch['id'])
            ->get();
        return $query->result_array();
    }

    public function locate_department($fetch)
    {
        $id = explode("/", $fetch['id']);
        $this->db->select('dept_name, company_code, bunit_code, dept_code');
        $query = $this->db->get_where('locate_department', array('company_code' => $id[0], 'bunit_code' => $id[1]));
        return $query->result_array();
    }

    public function locate_section($fetch)
    {
        $id = explode("/", $fetch['id']);
        $this->db->select('section_name, company_code, bunit_code, dept_code, section_code');
        $query = $this->db->get_where('locate_section', array('company_code' => $id[0], 'bunit_code' => $id[1], 'dept_code' => $id[2]));
        return $query->result_array();
    }

    public function locate_sub_section($fetch)
    {
        $id = explode("/", $fetch['id']);
        $this->db->select('sub_section_name, company_code, bunit_code, dept_code, section_code, sub_section_code');
        $query = $this->db->get_where('locate_sub_section', array('company_code' => $id[0], 'bunit_code' => $id[1], 'dept_code' => $id[2], 'section_code' => $id[3]));
        return $query->result_array();
    }

    public function locate_unit($fetch)
    {
        $id = explode("/", $fetch['id']);
        $this->db->select('unit_name, company_code, bunit_code, dept_code, section_code, sub_section_code, unit_code');
        $query = $this->db->get_where('locate_unit', array('company_code' => $id[0], 'bunit_code' => $id[1], 'dept_code' => $id[2], 'section_code' => $id[3], 'sub_section_code' => $id[4]));
        return $query->result_array();
    }

    public function position_level($position)
    {
        $query = $this->db->select('lvlno')
            ->get_where('position_leveling', array('position_title' => $position));
        return $query->row();
    }

    function update_contract_details($data)
    {

        if ($data['contract'] == "current") :

            $table = "employee3";
            $updatedBy = "updated_by";
            $postDesc  = "position_desc";
        else :

            $table = "employmentrecord_";
            $updatedBy = "updatedby";
            $postDesc  = "pos_desc";
        endif;

        if ($data['startdate'] == "00/00/0000" || $data['startdate'] == "") : $startdate = '0000-00-00';
        else : $startdate = date("Y-m-d", strtotime($data['startdate']));
        endif;
        if ($data['eocdate'] == "00/00/0000" || $data['eocdate'] == "") : $eocdate = '0000-00-00';
        else : $eocdate = date("Y-m-d", strtotime($data['eocdate']));
        endif;

        $businessUnit   = explode("/", $data['businessUnit']);
        $department     = explode("/", $data['department']);
        @$section       = explode("/", $data['section']);
        @$subSection    = explode("/", $data['subSection']);
        @$unit          = explode("/", $data['unit']);

        $update = array(
            'startdate'     => $startdate,
            'eocdate'       => $eocdate,
            'emp_type'      => $data['empType'],
            'current_status' => $data['current_status'],
            'company_code'  => $data['company'],
            'bunit_code'    => end($businessUnit),
            'dept_code'     => end($department),
            'section_code'  => end($section),
            'sub_section_code' => end($subSection),
            'unit_code'     => end($unit),
            'positionlevel' => $data['posLevel'],
            'position'      => $data['position'],
            $postDesc       => $data['posDesc'],
            'lodging'       => $data['lodging'],
            'date_updated'  => $this->date,
            $updatedBy      => $this->loginId,
            'remarks'       => $data['remarks']
        );

        $this->db->where('record_no', $data['recordNo']);
        $this->db->where('emp_id', $data['empId']);
        return $this->db->update($table, $update);
    }

    public function upload_scanned_file($table, $field, $path, $emp_id, $record_no)
    {
        $update = array(
            $field  => $path
        );

        $this->db->where('emp_id', $emp_id);
        $this->db->where('record_no', $record_no);
        return $this->db->update($table, $update);
    }

    public function promo_products($record_no, $emp_id)
    {
        $query = $this->db->select('product')
            ->get_where('promo_products', array('record_no' => $record_no, 'emp_id' => $emp_id));
        return $query->result();
    }

    public function select_promo_products($record_no, $emp_id, $product)
    {
        $query = $this->db->select('product')
            ->get_where('promo_products', array('record_no' => $record_no, 'emp_id' => $emp_id, 'product' => $product));
        return $query->result();
    }

    public function agency_list()
    {
        $query = $this->db2->order_by('agency_name', 'ASC')
            ->get_where('promo_locate_agency', array('status' => 1));
        return $query->result();
    }

    public function locate_vendor($department)
    {
        $query = $this->db->select('vendor_code, vendor_name')
            ->order_by('vendor_name', 'ASC')
            ->get_where('promo_vendor_lists', array('department' => $department, 'vendor_name !=' => ''));
        return $query->result();
    }

    public function locate_promo_products($company)
    {
        $query = $this->db->select('product')
            ->get_where('promo_company_products', array('company' => $company));
        return $query->result();
    }

    public function promo_product_list()
    {
        $query = $this->db->select('product')
            ->get_where('locate_promo_product', array('status' => 1));
        return $query->result();
    }

    public function emp_type()
    {
        $query = $this->db->select('emp_type')
            ->order_by('emp_type', 'ASC')
            ->get('employee_type');
        return $query->result();
    }

    public function promo_company_products($company_name)
    {
        $query = $this->db->select('product')
            ->order_by('product', 'ASC')
            ->get_where('promo_company_products', array('company' => $company_name));
        return $query->result();
    }

    public function update_employment_contract($data)
    {
        if ($data['contract'] == "current") :

            $table = "employee3";
            $updated_by = 'updated_by';
        else :

            $table = "employmentrecord_";
            $updated_by = 'updatedby';
        endif;

        if ($data['startdate'] == "0000-00-00") : $startdate = '';
        else : $startdate = date("Y-m-d", strtotime($data['startdate']));
        endif;
        if ($data['eocdate'] == "0000-00-00") : $eocdate = '';
        else : $eocdate = date("Y-m-d", strtotime($data['eocdate']));
        endif;

        $update = array(
            'startdate'     => $startdate,
            'eocdate'       => $eocdate,
            'emp_type'      => $data['empType'],
            'duration'      => $data['duration'],
            'current_status' => $data['current_status'],
            'positionlevel' => $data['position_level'],
            'position'      => $data['position'],
            'date_updated'  => $this->date,
            $updated_by     => $this->loginId,
            'remarks'       => $data['remarks']
        );

        $this->db->where(array('record_no' => $data['recordNo'], 'emp_id' => $data['empId']));
        $this->db->update($table, $update);
    }

    public function empty_store_value($data)
    {
        if ($data['contract'] == "current") :

            $table = "promo_record";
        else :

            $table = "promo_history_record";
        endif;

        $bUs = $this->dashboard_model->businessUnit_list();
        foreach ($bUs as $bu) {

            $this->db->set($bu->bunit_field, '');
        }

        $this->db->where(array('record_no' => $data['recordNo'], 'emp_id' => $data['empId']));
        $this->db->update($table);
    }

    public function update_promo_details($data)
    {
        $company_name = $this->get_company_name($data['company'])->pc_name;
        if ($data['contract'] == "current") :

            $table = "promo_record";
        else :

            $table = "promo_history_record";
        endif;

        foreach ($data['store'] as $key => $value) {

            $store = explode('/', $value);
            $this->db->set($store[1], 'T');
        }

        $this->db->set('agency_code', $data['agency']);
        $this->db->set('promo_company', $company_name);
        $this->db->set('promo_department', $data['department']);
        $this->db->set('vendor_code', $data['vendor']);
        $this->db->set('promo_type', $data['promo_type']);
        $this->db->set('type', $data['contractType']);

        $this->db->where(array('record_no' => $data['recordNo'], 'emp_id' => $data['empId']));
        $this->db->update($table);
    }

    public function insert_application_employment_history($data, $path)
    {
        $insert = array(
            'app_id'        => $data['empId'],
            'company'       => $data['company'],
            'position'      => $data['position'],
            'yr_start'      => $data['startdate'],
            'yr_ends'       => $data['eocdate'],
            'address'       => $data['address'],
            'emp_certificate' => $path
        );

        return $this->db->insert('application_employment_history', $insert);
    }

    public function update_application_employment_history($data, $path)
    {
        if (!empty($path)) {

            $update = array(
                'company'       => $data['company'],
                'position'      => $data['position'],
                'yr_start'      => $data['startdate'],
                'yr_ends'       => $data['eocdate'],
                'address'       => $data['address'],
                'emp_certificate' => $path
            );
        } else {

            $update = array(
                'company'       => $data['company'],
                'position'      => $data['position'],
                'yr_start'      => $data['startdate'],
                'yr_ends'       => $data['eocdate'],
                'address'       => $data['address']
            );
        }

        $this->db->where('no', $data['no']);
        $this->db->where('app_id', $data['empId']);
        return $this->db->update('application_employment_history', $update);
    }

    public function employment_certificate($data)
    {
        $this->db->select('emp_certificate');
        $query = $this->db->get_where('application_employment_history', array('no' => $data['no']));
        return $query->row_array();
    }

    public function insert_blacklist($data)
    {
        if ($data['birthday'] == "") {

            $birthday = "";
        } else {

            $birthday = date("Y-m-d", strtotime($data['birthday']));
        }

        $insert = array(
            'app_id'        => $data['empId'],
            'name'          => $data['empName'],
            'date_blacklisted' => date("Y-m-d", strtotime($data['dateBlacklisted'])),
            'date_added'    => $this->date,
            'reportedby'    => $data['reportedBy'],
            'reason'        => $data['reason'],
            'status'        => 'blacklisted',
            'staff'         => $this->loginId,
            'bday'          =>  $birthday,
            'address'       => $data['address']
        );

        return $this->db->insert('blacklist', $insert);
    }

    public function update_current_status($empId)
    {
        $update = array(
            'current_status'  => 'blacklisted'
        );

        $this->db->where('emp_id', $empId);
        return $this->db->update('employee3', $update);
    }

    public function update_blacklist($data)
    {
        if ($data['birthday'] == "") {

            $birthday = "";
        } else {

            $birthday = date("Y-m-d", strtotime($data['birthday']));
        }

        $update = array(
            'date_blacklisted' => date("Y-m-d", strtotime($data['dateBlacklisted'])),
            'reportedby'    => $data['reportedBy'],
            'reason'        => $data['reason'],
            'staff'         => $this->loginId,
            'bday'          =>  $birthday,
            'address'       => $data['address']
        );

        $this->db->where('blacklist_no', $data['no']);
        $this->db->where('app_id', $data['empId']);
        return $this->db->update('blacklist', $update);
    }

    public function update_applicant_otherdetails($data)
    {
        $recordedby = $_SESSION['username'] . "/" . $_SESSION['emp_id'];

        $update = array(
            'philhealth'    => $data['ph'],
            'sss_no'        => $data['sss'],
            'pagibig'       => $data['pagibig'],
            'pagibig_tracking' => $data['pagibigrtn'],
            'tin_no'        => $data['tinno'],
            'recordedby'    => $recordedby
        );

        $this->db->where('app_id', $data['empId']);
        return $this->db->update('applicant_otherdetails', $update);
    }

    public function insert_applicant_otherdetails($data)
    {
        $recordedby = $_SESSION['username'] . "/" . $_SESSION['emp_id'];

        $insert = array(
            'app_id'        => $data['empId'],
            'philhealth'    => $data['ph'],
            'sss_no'        => $data['sss'],
            'pagibig'       => $data['pagibig'],
            'pagibig_tracking' => $data['pagibigrtn'],
            'tin_no'        => $data['tinno'],
            'recordedby'    => $recordedby
        );

        return $this->db->insert('applicant_otherdetails', $insert);
    }

    public function insert_201_document($field, $emp_id, $req_name, $path, $table_name)
    {
        $insert = array(
            $field              => $emp_id,
            'requirement_name'  => $req_name,
            'filename'          => $path,
            'date_time'         => $this->date,
            'requirement_status' => 'passed',
            'receiving_staff'   => $this->loginId
        );

        return $this->db->insert($table_name, $insert);
    }

    public function remove_supervisor($data)
    {
        return $this->db->delete('leveling_subordinates', array('record_no' => $data['recordNo']));
    }

    public function insert_leveling_subordinates($supervisor, $subordinates)
    {
        $insert = array(
            'ratee'             => $supervisor,
            'subordinates_rater' => $subordinates
        );

        return $this->db->insert('leveling_subordinates', $insert);
    }

    public function update_remarks($data)
    {
        $update = array(
            'remarks'    => $data['remarks']
        );

        $this->db->where('emp_id', $data['empId']);
        return $this->db->update('remarks', $update);
    }

    public function insert_remarks($data)
    {
        $insert = array(
            'emp_id'    => $data['empId'],
            'remarks'   => $data['remarks']
        );

        return $this->db->insert('remarks', $insert);
    }

    public function reset_password($data)
    {
        $password = md5("Hrms2014");

        $update = array(
            'password'    => $password
        );

        $this->db->where('user_no', $data['userNo']);
        return $this->db->update('users', $update);
    }

    public function activate_account($data)
    {
        $update = array(
            'user_status'    => 'active'
        );

        $this->db->where('user_no', $data['userNo']);
        return $this->db->update('users', $update);
    }

    public function deactivate_account($data)
    {
        $update = array(
            'user_status'    => 'inactive'
        );

        $this->db->where('user_no', $data['userNo']);
        return $this->db->update('users', $update);
    }

    public function delete_account($data)
    {
        return $this->db->delete('users', array('user_no' => $data['userNo']));
    }

    public function insert_users($data)
    {
        $password = md5($_POST['password']);

        $insert = array(
            'emp_id'        => $data['empId'],
            'username'      => $data['username'],
            'password'      => $password,
            'usertype'      => $data['usertype'],
            'user_status'   => 'active',
            'login'         => 'no',
            'date_created'  => date("Y-m-d H:i:s"),
            'user_id'       => 4
        );

        return $this->db->insert('users', $insert);
    }

    public function months()
    {
        return array(
            '01' => 'January',
            '02' => 'February',
            '03' => 'March',
            '04' => 'April',
            '05' => 'May',
            '06' => 'June',
            '07' => 'July',
            '08' => 'August',
            '09' => 'September',
            '10' => 'October',
            '11' => 'November',
            '12' => 'December'
        );
    }

    public function field_names()
    {
        return array(
            'home_address' => 'Home Address',
            'gender' => 'Gender',
            'birthdate' => 'Birth Day',
            'religion' => 'Religion',
            'civilstatus' => 'Civil Status',
            'school' => 'School',
            'attainment' => 'Attainment',
            'course' => 'Course',
            'contactno' => 'Contact Number',
            'mother' => 'Mother',
            'father' => 'Father',
            'height' => 'Height',
            'weight' => 'Weight',
            'bloodtype' => 'Bloodtype',
            'startdate' => 'Startdate',
            'eocdate' => 'Eocdate',
            'current_status' => 'Current Status'
        );
    }
}
