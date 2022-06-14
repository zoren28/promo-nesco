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
	
	public function get_appId()
	{
		$year 			= 	date('Y');
		
		$query = $this->db->from('applicant')
							->where('year',$year)
							->order_by('id', 'desc')
							->get();
				$r =  $query->row_array();
				
		if(is_array($r))
		{
			$yr		= $r['year'];
			$id    	= $r['id'] + 1;
				 			
			 if($id =="")
				{ 
					$id = "1"; 
				}
			 else
				{ 
					$id = $r['id'] + 1; 
				}	
		}
		if(@$yr != $year)
		{ 
			$id = "1"; 
		}
				
		return $id."|".sprintf("%'.05d-" . date('Y'), $id);
	}
	public function save_applicant_info($fetch_data)
	{
		$year 			= 	date('Y');
		$contactNuber 	= 	$fetch_data['contact1'].",".$fetch_data['contact2'];
		
		
		$town_explode = explode(",",$fetch_data['address']);
		
		
		$data = array(
				'app_id'					=> $fetch_data['appId'],
				'id'						=> $fetch_data['id'],
				'year'						=> $year,
				'lastname'					=> trim(ucfirst($fetch_data['lastname'])),
				'firstname'					=> trim(ucfirst($fetch_data['firstname'])),
				'middlename'				=> trim(ucfirst($fetch_data['middlename'])),
				'birthdate'					=> trim(date('Y-m-d',strtotime($fetch_data['birthdate']))),
				'home_address'				=> $fetch_data['address'],
				'city_address'				=> $fetch_data['city_address'],
				'province'					=> trim(ucfirst($town_explode[2])),
				'town'						=> trim(ucfirst($town_explode[1])),
				'brgy'						=> trim(ucfirst($town_explode[0])),
				'religion'					=> $fetch_data['religion'],
				'civilstatus'				=> trim(ucfirst($fetch_data['civilstatus'])),
				'spouse'					=> $fetch_data['spouse'],
				'noofSiblings'				=> $fetch_data['no_of_siblings'],
				'siblingOrder'				=> $fetch_data['sibling_order'],
				'gender'					=> $fetch_data['gender'],
				'school'					=> $fetch_data['school'],
				'attainment'				=> $fetch_data['education'],
				'course'					=> $fetch_data['course'],
				'contactno'					=> $contactNuber,
				'telno'						=> $fetch_data['telephone_number'],
				'email'						=> $fetch_data['email_add'],
				'facebookAcct'				=> $fetch_data['facebook'],
				'twitterAcct'				=> $fetch_data['twitter'],
				'citizenship'				=> $fetch_data['citizenship'],
				'bloodtype'					=> '',
				'weight'					=> $fetch_data['weight'],
				'height'					=> $fetch_data['height'],
				'contact_person'			=> $fetch_data['contact_person'],
				'contact_person_address' 	=> $fetch_data['contact_person_address'],
				'contact_person_number'		=> $fetch_data['contact_person_number'],
				'mother'					=> $fetch_data['mother'],
				'father'					=> $fetch_data['father'],
				'guardian'					=> $fetch_data['guardian'],
				'hobbies'					=> $fetch_data['hobbies'],
				'specialSkills'				=> $fetch_data['special_skill'],
				'photo'						=> '',
				'suffix'					=> $fetch_data['suffix'],
				'appcode'					=> $fetch_data['application_code'],
				'source_app_vacant'			=> $fetch_data['vacancy_source']
			); 
			
			$this->db->insert('applicant', $data);
	}
	
	public function save_exam_scores($fetch_data)
	{
		if($fetch_data['exam_stat'] == 'passed')
		{
			$stat_value = 'exam passed';
		}
		else if($fetch_data['exam_stat'] == 'failed')
		{
			$stat_value = 'exam failed';
		}
		else if($fetch_data['exam_stat'] == 'assessment')
		{
			$stat_value = 'assessment';
		}
		
		// query for getting the exam category
		$query = $this->db->select('no,exam_cat')
							->from('application_exams2take')
							->where("app_id = '".$fetch_data['appid']."'")
							->order_by('no', 'DESC')
							->get();
        $resultN = $query->row_array();
		
		// loop for saving the exam scores
		for($i= 0 ; $i< count($fetch_data['examType']);$i++)
		{
			if(count($fetch_data['examType']) > 0)
			{
				$data = array(
										'record_no'		=> '',
										'exam_ref'		=> $resultN['no']."/".$fetch_data['appid'],
										'exam_type'		=> $fetch_data['examType'][$i],
										'exam_score'	=> $fetch_data['inputscore'][$i],
										'exam_code'		=> 'manual'
									);
									
				$this->db->insert('application_examdetails', $data);
			}
		}
		// set data for updating
		$data_1 = array('stats' => 'done','result' 	=> $fetch_data['exam_stat']);				
		$data_2 = array('status' => $stat_value);	
		//query for updating exam status // application_exams2take table
		$this->db->where('app_id', $fetch_data['appid']);
		$this->db->update('application_exams2take', $data_1); 
		//query for updating applicants status // applicants table
		$this->db->where('app_code', $fetch_data['appcode']);
		$this->db->update('applicants', $data_2); 
		
	}
	public function setup_examination_info_append($fetch_data)
	{
		$data = array(
							'app_id'		=> $fetch_data['appid'],
							'exam_cat'		=> $fetch_data['exam_type'],
							'stats'			=> '',
							'result'		=> ''
						);				
		$this->db->insert('application_exams2take', $data);
	}
	
	public function save_initial_interview($fetch_data)
	{
		$data = array(
						'interviewee_id'		=> $fetch_data['appid'],
						'interviewee_level'		=> '',
						'interviewer_id'		=> $_SESSION['emp_id'],
						'interview_status'		=> 'passed',
						'interviewer_remarks'	=> $fetch_data['initialRemark'],
						'date_interviewed'		=> date("Y-m-d"),
						'group'					=> '0',
						'chosen'				=> '1',
						'for_promo'				=> ''
						);
		
		$this->db->insert('application_interview_details', $data);
		$id = $this->db->insert_id();
		$int_code = date('m')."-".date('d')."-".$id."-".date('Y');
		
		//update interview code
		$this->db->set('interview_code', $int_code);
		$this->db->where(array('id' => $id));
		$this->db->update('application_interview_details');
	}
	
	public function history_info_append($fetch_data)
	{
		$description = "exam setup done, ".$fetch_data['exam_type']." exam";
		
		$data = array(
							'app_id'		=> $fetch_data['appid'],
							'date_time'		=> date("Y-m-d"),
							'description'	=> $description,
							'position'		=> $fetch_data['applying'],
							'phase'			=> 'Examination',
							'status'		=> 'completed',
						);
		$this->db->insert('application_history', $data);
	}
	
	public function setup_textfile($fetch_data)
	{
		$que = $this->db->get_where('applicant', array('app_id' => $fetch_data['appid']));
		$rw = $que->row_array();

		$file = fopen("../document/examfiles/".$rw['app_id'].".txt","w");
		fwrite($file,$rw['app_id']."%".$rw['lastname']."%".$rw['firstname']."%".$rw['middlename']."%".$rw['suffix']."%".$rw['gender']."%".$rw['school']."%".$rw['attainment']."%".$rw['course']."%".ucwords(strtolower($fetch_data['applying']))."%".$fetch_data['attainment']."%");
		fclose($file);
	}
	
	public function applicant_status($fetch_data)
	{
		$this->db->set('status', $fetch_data['app_status']);
		$this->db->where(array('app_code' => $fetch_data['appcode']));
		$this->db->update('applicants'); 
	}
	public function update_applicant_status($fetch_data)
	{
		$data_condition = array(
									'lastname'		=> trim(ucfirst($fetch_data['lastname'])),
									'firstname'		=> trim(ucfirst($fetch_data['firstname'])),
									'middlename'	=> trim(ucfirst($fetch_data['middlename'])),
									'suffix' 		=> trim(ucfirst($fetch_data['suffix']))
									);
		
		$this->db->set('status', "initialreq completed");
		$this->db->where($data_condition);
		$this->db->update('applicants');
	}
	public function save_applicant_character_ref($fetch_data)
	{
		for($i= 0 ; $i< count($fetch_data['character_name']);$i++)
		{
			if(count($fetch_data['character_name']) > 0)
			{
				$data = array(
										'app_id'		=> $fetch_data['appId'],
										'name'			=> $fetch_data['character_name'][$i],
										'position'		=> $fetch_data['character_position'][$i],
										'contactno'		=> $fetch_data['character_contact'][$i],
										'company'		=> $fetch_data['character_address'][$i]
									);
									
				$this->db->insert('application_character_ref', $data);
			}
		} 
	}
	
	public function save_applicant_seminar_training_eligibility($fetch_data, $i)
	{
		$data = array(
							'app_id'			=> $fetch_data['appId'],
							'name'				=> $fetch_data['seminar_name'][$i],
							'dates'				=> $fetch_data['seminar_location'][$i],
							'location'			=> $fetch_data['seminar_year'][$i],
							'sem_certificate'	=> $fetch_data['location']
						);
		$this->db->insert('application_seminarsandeligibility', $data);
	}
	
	public function save_applicant_employment_history($fetch_data, $z)
	{
		$data = array(
							'app_id'			=> $fetch_data['appId'],
							'company'			=> $fetch_data['company_name'][$z],
							'position'			=> $fetch_data['position'][$z],
							'yr_start'			=> $fetch_data['year_start'][$z],
							'yr_ends'			=> $fetch_data['year_end'][$z],
							'address'			=> $fetch_data['company_address'][$z],
							'emp_certificate'	=> $fetch_data['location']
						);
		$this->db->insert('application_employment_history', $data);
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
	
	public function examtype()
    {
        $query = $this->db->select('DISTINCT(exam_code)')
							->from('application_examtypes')
							->get();
        return $query->result_array();
    }
	
	public function examtype_codename($data)
    {
		$query = $this->db->select('DISTINCT(exam_codename)')
							->where("exam_code = '$data'")
							->from('application_examtypes')
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
		$query = $this->db->select('applicants.status,applicant.app_id,applicants.lastname,applicants.firstname,applicants.middlename,applicants.position,applicants.date_time,applicants.suffix,applicants.app_code')
							->from('applicants')
							->join('applicant', 'applicants.app_code = applicant.appcode')
							->where("(applicants.status = 'for exam' OR applicants.status = 'exam passed' OR applicants.status = 'exam failed' OR applicants.status = 'initialreq completed' OR applicants.status = 'assessment') AND (applicants.tagged_to = 'nesco')")
							->order_by('app_code', 'ASC')
							->get();
        return $query->result_array();
    }
	
	public function applicants_for_interview()
    {
		$query = $this->db->select('applicants.app_code, applicant.app_id, applicants.lastname, applicants.middlename, applicants.firstname, applicants.suffix, applicants.date_time, applicants.position')
							->from('applicants')
							->join('applicant', 'applicants.app_code = applicant.appcode')
							->where("(applicants.status = 'for interview' OR applicants.status = 'interview failed') AND (applicants.tagged_to = 'nesco')")
							->order_by('app_code', 'ASC')
							->get();
        return $query->result_array();
    }
	
	
	public function applicants_interview($data)
	{
		$que = $this->db->select('count(id) as val')
					->get_where('application_interview_details', array('interviewee_id' => $data));
		return $que->row_array()['val'];	
	}
	
	public function applicants_interview_level($data)
	{
		//$condition = ;
		
		$que = $this->db->select('count(id) as val')
					->where("interviewee_id = '$data' AND interviewee_level != 0")
					->get('application_interview_details');
		return $que->row_array()['val'];		
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
	
	public function application_exam_score($data)
	{
		$que = $this->db->get_where('application_examtypes', array('exam_code' => $data));
		return $que->result_array();
	}
	public function applicant_exam_cat($fetch_data)
	{
		$que = $this->db->select('exam_cat')
					->get_where('application_exams2take', array('app_id' => $fetch_data['id']));
		return $que->row_array();	
	}
	
	public function applicant_examinee($fetch_data)
	{
		$que = $this->db->get_where('applicant', array('app_id' => $fetch_data['id']));
		return $que->row_array();	
	}
	
	public function applicant_position_apply($data)
	{
		$que = $this->db->get_where('applicants', array('app_code' => $data));
		return $que->row_array();	
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