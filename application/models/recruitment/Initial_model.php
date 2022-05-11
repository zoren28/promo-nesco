 <?php
defined('BASEPATH') or exit('No direct script access allowed');

class Initial_model extends CI_Model
{
    public $usertype = '';

    function __construct()
    {
        parent::__construct();
        $this->load->library('nativesession');
    }

	public function check_applicant_duplicate_or_blacklist($data)
	{
		$name	= 	$data['lastname'].", ".$data['firstname'];
		$name1	= 	$data['lastname'].",".$data['firstname'];
		
		$query = $this->db->select('firstname, middlename,lastname')
									->from('applicants')
									->where('firstname',$data['firstname'])
									->where('middlename',$data['middlename'])
									->where('lastname',$data['lastname'])
									->get();
		$duplicate = $query->result_array();
		
		$query = $this->db->select('name,reason')
									->from('blacklist')
									->like('name', $name)
									->or_like('name', $name1)
									->get();
		$blacklist = $query->result_array();

		return compact("duplicate","blacklist");
	}
	
	public function insert_uploaded_info($fileV,$fileType,$appcode)
	{
		if($fileV == 'resume')
		{
			$requiName = "Resume";
		}
		elseif($fileV == 'application')
		{
			$requiName = "Application Letter";
		}
		elseif($fileV == 'transcript')
		{
			$requiName = "Transcript of Record";
		}
		
		$data = array(
				'filename' 				=> $fileType,
				'app_code'				=> $appcode,
				'requirement_name' 		=> $requiName,
				'receiving_staff'		=> $_SESSION['emp_id'],
				'date_time'				=> date("Y-m-d"),
				'requirement_status'	=> "passed"
			);
			
		$this->db->insert('application_initialreq', $data); 
		return $this->db->insert_id(); 
	}
	
	public function insert_initial_applicant_info($fetch_data)
	{	
		$date_added = date("Y-m-d");

		$data = array(
				'firstname' 	=> $this->security->xss_clean(ucfirst($fetch_data['hidden_firstname'])),
				'middlename' 	=> $this->security->xss_clean(ucfirst($fetch_data['hidden_middlename'])),
				'lastname' 		=> $this->security->xss_clean(ucfirst($fetch_data['hidden_lastname'])),
				'suffix' 		=> $this->security->xss_clean(ucfirst($fetch_data['hidden_suffix'])),
				'position'		=> $this->security->xss_clean(ucfirst($fetch_data['position'])),
				'status'		=> $this->security->xss_clean("tagged"),
				'date_time'		=> $this->security->xss_clean($date_added),
				'entry_by'		=> $this->security->xss_clean($_SESSION['emp_id']),
				'tagged_to'		=> $this->security->xss_clean("nesco"),
				'locate'		=> $this->security->xss_clean("0/00000-0000"),
				'rizon'			=> $this->security->xss_clean(''),
				'franchise'		=> $this->security->xss_clean(''),
				'waiver'		=> $this->security->xss_clean(''),
				'prehire_eval'	=> $this->security->xss_clean(0),
				'hr_location'	=> $this->security->xss_clean('')
			);
			
			
			if($fetch_data['updt_or_appnd'] == 'INSERT')
			{
				$this->db->insert('applicants', $data); 
				$returnV = $this->db->insert_id();
				
				$data1 		= 	array(
									'app_code' 		=> $returnV,
									'middle_name' 	=> ucfirst($fetch_data['hidden_middlename']),
									'gender' 		=> ucfirst($fetch_data['hidden_gender']),
									'civilstatus' 	=> ucfirst($fetch_data['hidden_civil_status'])
								);
								
				$this->db->insert('application_newdetails', $data1);
				return $returnV; 
			}
			else
			{
				$update_data_cndtn = array(
												'lastname' 		=> $fetch_data['hidden_lastname'],
												'firstname' 	=> $fetch_data['hidden_firstname'],
												'middlename' 	=> $fetch_data['hidden_middlename'],
												'suffix' 		=> $fetch_data['hidden_suffix']
												);
				
				$this->db->where($update_data_cndtn);
				$this->db->update('applicants', $data); 
				
				$get_appCode = $this->db->select('app_code')
									->from('applicants')
									->where($update_data_cndtn)
									->get();
				$returnV = $get_appCode->row_array();
				
				$data2 		= 	array(
									'app_code' 		=> $returnV['app_code'],
									'middle_name' 	=> ucfirst($fetch_data['hidden_middlename']),
									'gender' 		=> ucfirst($fetch_data['hidden_gender']),
									'civilstatus' 	=> ucfirst($fetch_data['hidden_civil_status'])
								);
				
				$this->db->where('app_code', $returnV['app_code']);				
				$this->db->update('application_newdetails', $data2);
				return $returnV;	
			}	
	}
	
	public function position()
    {
        $query = $this->db->select('position_title,poslevel_no')
							->from('position_leveling')
							->order_by('position_title', 'ASC')
							->get();
        return $query->result_array();
    }
	
	public function record_applicants()
    {
		$query = $this->db->from('applicants')
							->where("status = 'tagged' AND tagged_to = 'nesco'")
							->order_by('app_code', 'ASC')
							->get();
        return $query->result_array();
    }
	
	public function applicants_for_exam()
    {
		$query = $this->db->from('applicants')
							->where("(status = 'for exam' OR status = 'exam passed' OR status = 'exam failed') AND (tagged_to = 'nesco')")
							->order_by('app_code', 'ASC')
							->get();
        return $query->result_array();
    }
	
	public function applicants_for_interview()
    {
		$query = $this->db->from('applicants')
							->where("(status = 'for interview' OR status = 'interview failed') AND (tagged_to = 'nesco')")
							->order_by('app_code', 'ASC')
							->get();
        return $query->result_array();
    }
	
	public function applicants_for_finalcompletion()
    {
		$query = $this->db->from('applicants')
							->where("status = 'for final completion' AND tagged_to = 'nesco'")
							->order_by('app_code', 'ASC')
							->get();
        return $query->result_array();
    }
	
	public function applicants_for_hiring()
    {
		$query = $this->db->from('applicants')
							->where("status = 'for hiring' AND tagged_to = 'nesco'")
							->order_by('app_code', 'ASC')
							->get();
        return $query->result_array();
    }
	// dashboard counting examinees 
	public function examcount_applicant()
    {
		$query = $this->db->from('applicants')
							->where("(status = 'for exam' OR status = 'exam passed' OR status = 'exam failed') AND (tagged_to = 'nesco')")
							->order_by('app_code', 'ASC')
							->get();
        return $query->num_rows();
    }
	// dashboard counting interviews
	public function interviewcount_applicant()
    {
		$query = $this->db->from('applicants')
							->where("status = 'for interview' AND tagged_to = 'nesco'")
							->order_by('app_code', 'ASC')
							->get();
        return $query->num_rows();
    }
	// dashboard counting hiring
	public function hiringcount_applicant()
    {
		$query = $this->db->from('applicants')
							->where("status = 'for hiring' AND tagged_to = 'nesco'")
							->order_by('app_code', 'ASC')
							->get();
        return $query->num_rows();
    }
	public function attainment()
	{
		$query = $this->db->from('attainment')
							->get();
        return $query->result_array();
	}
	public function school()
	{
		$query = $this->db->from('school')
							->get();
        return $query->result_array();
	}
	public function course()
	{
		$query = $this->db->from('course')
							->get();
        return $query->result_array();
	}
	
	// process for recording applicants
	public function record_applicant_info($fetch_data)
	{
		// get the gender and civil status
		$query1 = $this->db->from('application_newdetails')
							->where("app_code = '".$fetch_data['id']."'")
							->order_by('app_code', 'ASC')
							->get();
		$new_details = $query1->row_array();					
		// get applicant app code
		$query2 = $this->db->from('applicants')
							->where("app_code = '".$fetch_data['id']."'")
							->order_by('app_code', 'ASC')
							->get();
        $applicants = $query2->row_array();
		// get the barangay, town for selection
		$query3 = $this->db->from('barangay')
						->join('town', 'barangay.town_id = town.town_id')
						->join('province', 'town.prov_id = province.prov_id')
						->get();
		$town_brgy = $query3->result_array();
		
		return compact("town_brgy","new_details","applicants");
	}
	
}