 <?php
defined('BASEPATH') or exit('No direct script access allowed');

class Initial_model extends CI_Model
{
    public $usertype = '';
	public $db2 = '';
    function __construct()
    {
        parent::__construct();
        $this->load->library('nativesession');
		$this->db2 = $this->load->database('timekeeping', TRUE);
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
	
	public function insert_finalreq_info($fileV,$fileType,$fetch_data)
	{
		if($fileV == "police_clearance")
		{
			$requiName = "Police Clearance";
		}
		else if($fileV == "fingerprint")
		{
			$requiName = "Fingerprint";
		}
		else if($fileV == "sss")
		{
			$requiName = "SSS";
		}
		else if($fileV == "cedula")
		{
			$requiName = "Cedula";
		}
		else if($fileV == "parentconsent")
		{
			$requiName = "Parent Consent";
		}
		else if($fileV == "medical")
		{
			$requiName = "Medical Certificate";
		}
		else if($fileV == "house_skecth")
		{
			$requiName = "House Sketch";
		}
		else if($fileV == "background_investagation")
		{
			$requiName = "Background Investagation";
		}
		else if($fileV == "drugtest")
		{
			$requiName = "Drug Test";
		}
		else if($fileV == "recommend_letter")
		{
			$requiName = "Recommendation Letter";
		}
		else if($fileV == "marriage")
		{
			$requiName = "Marriage Certificate";
		}
		else if($fileV == 'birthcertificate')
		{
			$requiName = "Birth Certificate";
		}
		else if($fileV == 'otherDoc')
		{
			$requiName = $fetch_data['doc'];
		}
			
		$data = array(
				'filename' 				=> $fileType,
				'app_id'				=> $fetch_data['appid'],
				'requirement_name' 		=> $requiName,
				'receiving_staff'		=> $_SESSION['emp_id'],
				'date_time'				=> date("Y-m-d"),
				'requirement_status'	=> "passed"
			);
		
		if($fileV != 'otherDoc')
		{
			$this->db->insert('application_finalreq', $data); 
			return $this->db->insert_id(); 
		}
		else
		{
			$this->db->insert('application_otherreq', $data); 
			return $this->db->insert_id();
		}
		
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
	
	public function check_upload_finalcompletion($value,$fetch_data)
	{
		$temp 		= 	'jpg';
		$target_dir	=	"../document/final_requirements/";
				
		$maxsize    = 2097152;
		$acceptable = array(
			'image/jpeg',
			'image/jpg',
			'image/png'
		); 
		
		if($value == 'birthcertificate' || $value == 'otherDoc')
		{
			
			for($i=0; $i< count($_FILES[$value]['name']); $i++)
			{
				$filesize 	= 	$_FILES[$value]['size'][$i];
				$filetype	=	$_FILES[$value]['type'][$i];
				
				if($value == 'birthcertificate')
				{
					$file_name 		= "Birth Certificate";
					$target_folder 	= $target_dir."birth_certificate/";
					$filename 		= $value."_".$i."_".$fetch_data['appid']."_".date("Y-m-d").".".$temp;
					$location 		= $target_folder.$filename;
				}
				else
				{
					$file_name 		= "Other Document";
					$target_folder 	= $target_dir."others/";
					$filename 		= $fetch_data['documentName'][$i]."_".$i."_".$fetch_data['appid']."_".date("Y-m-d").".".$temp;
					$location 		= $target_folder.$filename;
					$fetch_data['doc'] = $fetch_data['documentName'][$i];
				}
				
				if($filesize >= $maxsize) 
				{
					echo $file_name." file too large. File must be less than 2 megabytes.";
				}
				else
				{
					if((!in_array($filetype, $acceptable)) && (!empty($filetype))) 
					{
						$file_name." file is invalid file type. Only PDF, JPG, GIF and PNG types are accepted.";
					}
					else
					{
						if(move_uploaded_file($_FILES[$value]["tmp_name"][$i],$target_folder.''.$filename))
						{
							$finalreq = $this->initial_model->insert_finalreq_info($value,$location,$fetch_data);
						}
					}
				}
			}	
		}
		else
		{
			$filesize 	= 	$_FILES[$value]['size'];
			$filetype	=	$_FILES[$value]['type'];
			//-------------------------------------------------//
			if($value == "police_clearance")
			{
				$file_name 		= "Police Clearance";
				$target_folder 	= $target_dir."police_clearance/";
				$filename 		= $value."_".$fetch_data['appid']."_".date("Y-m-d").".".$temp;
				$location 		= $target_folder.$filename;
			}
			else if($value == "fingerprint")
			{
				$file_name 		= "Fingerprint";
				$target_folder 	= $target_dir."fingerprint/";
				$filename 		= $value."_".$fetch_data['appid']."_".date("Y-m-d").".".$temp;
				$location 		= $target_folder.$filename;
			}
			else if($value == "sss")
			{
				$file_name 		= "SSS";
				$target_folder 	= $target_dir."sss/";
				$filename 		= $value."_".$fetch_data['appid']."_".date("Y-m-d").".".$temp;
				$location 		= $target_folder.$filename;
			}
			else if($value == "cedula")
			{
				$file_name 		= "Cedula";
				$target_folder 	= $target_dir."cedula/";
				$filename 		= $value."_".$fetch_data['appid']."_".date("Y-m-d").".".$temp;
				$location 		= $target_folder.$filename;
			}
			else if($value == "parentconsent")
			{
				$file_name 		= "Parent Consent";
				$target_folder 	= $target_dir."parent_consent/";
				$filename 		= $value."_".$fetch_data['appid']."_".date("Y-m-d").".".$temp;
				$location 		= $target_folder.$filename;
			}
			else if($value == "medical")
			{
				$file_name 		= "Medical Certificate";
				$target_folder 	= $target_dir."medical_certificate/";
				$filename 		= $value."_".$fetch_data['appid']."_".date("Y-m-d").".".$temp;
				$location 		= $target_folder.$filename;
			}
			else if($value == "house_skecth")
			{
				$file_name 		= "House Sketch";
				$target_folder 	= $target_dir."sketch/";
				$filename 		= $value."_".$fetch_data['appid']."_".date("Y-m-d").".".$temp;
				$location 		= $target_folder.$filename;
			}
			else if($value == "background_investagation")
			{
				$file_name 		= "Background Investagation";
				$target_folder 	= $target_dir."bi/";
				$filename 		= $value."_".$fetch_data['appid']."_".date("Y-m-d").".".$temp;
				$location 		= $target_folder.$filename;
			}
			else if($value == "drugtest")
			{
				$file_name 		= "Drug Test";
				$target_folder 	= $target_dir."drug_test/";
				$filename 		= $value."_".$fetch_data['appid']."_".date("Y-m-d").".".$temp;
				$location 		= $target_folder.$filename;
			}
			else if($value == "recommend_letter")
			{
				$file_name 		= "Recommendation Letter";
				$target_folder 	= $target_dir."recommendation_letter/";
				$filename 		= $value."_".$fetch_data['appid']."_".date("Y-m-d").".".$temp;
				$location 		= $target_folder.$filename;
			}
			else if($value == "marriage")
			{
				$file_name 		= "Marriage Certificate";
				$target_folder 	= $target_dir."marriage_certificate/";
				$filename 		= $value."_".$fetch_data['appid']."_".date("Y-m-d").".".$temp;
				$location 		= $target_folder.$filename;
			}
			//-------------------------------------------------//
			if($filesize >= $maxsize) 
			{
				echo $file_name." file too large. File must be less than 2 megabytes.";
			}
			else
			{
				if((!in_array($filetype, $acceptable)) && (!empty($filetype))) 
				{
					$value." file is invalid file type. Only PDF, JPG, GIF and PNG types are accepted.";
				}
				else
				{
					if(move_uploaded_file($_FILES[$value]["tmp_name"],$target_folder.''.$filename))
					{
						$finalreq = $this->initial_model->insert_finalreq_info($value,$location,$fetch_data);
					}
				}
			}
		}
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
	
	public function save_setUp_interview($fetch_data)
	{
		$data = array(
						'interviewee_id'		=> $fetch_data['appid'],
						'interviewee_level'		=> '1',
						'interviewer_id'		=> $fetch_data['interviewer'],
						'interview_status'		=> '',
						'interviewer_remarks'	=> '',
						'date_interviewed'		=> '',
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
	
	public function updateBloodtype($fetch_data)
	{
		$this->db->set('bloodtype', $fetch_data['bloodtype']);
		$this->db->where(array('app_id' => $fetch_data['appid']));
		$this->db->update('applicant');
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
	
	public function get_interview_result($fetch_data)
	{
		if($fetch_data['interview_stat'] == 'passed')
		{
			return "for final completion";
		}
		else if($fetch_data['interview_stat'] == 'failed')
		{
			return "interview failed";
		}
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
	
	public function interviewer_list()
    {
        $query = $this->db->from('interviewer_list')
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
	
	public function getGrade($data)
	{
		$que = $this->db->select('num_rate,desc_rate')
					->get_where('application_interview_totalrates', array('interview_code' => $data));
		return $que->row_array();
	}
	public function getName($data)
	{
		$que = $this->db->select('name')
					->get_where('employee3', array('emp_id' => $data));
		return $que->row_array()['name'];
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
	
	public function transfer_applicants()
    {
		$query = $this->db->from('applicants')
							->where("status = 'interview failed' AND tagged_to = 'nesco'")
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
							->where("(applicants.status = 'for interview') AND (applicants.tagged_to = 'nesco')")
							->order_by('app_code', 'ASC')
							->get();
        return $query->result_array();
    }
	
	public function insertRemarks($fetch_data)
	{
		$data = array(
						'app_id' 	=> $fetch_data['appid'],
						'date' 		=> date("Y-m-d"),
						'remarks' 	=> $fetch_data['finalremarks']
					);
		$this->db->insert('application_finalreq_remarks', $data);
	}
	public function insertBenifits($fetch_data)
	{
		$data = array(
						'emp_id' 		=> $fetch_data['appid'],
						'philhealth' 	=> 	$fetch_data['philhealth'],
						'sssno'			=>	$fetch_data['sss_id'],
						'pagibig' 		=> 	$fetch_data['pagibig_mid']
					);
		$this->db->insert('benefits', $data);
	}
	public function update_or_insert_cedula_benifits_numbers($fetch_data)
	{
		$dataIn = array(
						'app_id' 			=> 	$fetch_data['appid'],
						'sss_no'			=>	$fetch_data['sss_id'],
						'card_no'			=>	$fetch_data['id_card'],
						'cedula_no'			=>	$fetch_data['ctc_no'],
						'cedula_date'		=>	$fetch_data['issued_on_ctc'],
						'cedula_place'		=>	$fetch_data['issued_at_ctc'],
						'recordedby'		=> 	$_SESSION['emp_id'],
						'pagibig_tracking' 	=> 	$fetch_data['pagibig_track'],
						'pagibig' 			=> 	$fetch_data['pagibig_mid'],
						'philhealth' 		=> 	$fetch_data['philhealth']
					);
		
		$dataUp = array(
							'sss_no'			=>	$fetch_data['sss_id'],
							'cedula_no'			=>	$fetch_data['ctc_no'],
							'cedula_date'		=>	$fetch_data['issued_on_ctc'],
							'cedula_place'		=>	$fetch_data['issued_at_ctc'],
							'recordedby'		=> 	$_SESSION['emp_id'],
							'pagibig_tracking' 	=> 	$fetch_data['pagibig_track']
					);			
					
		$query = $this->db->from('applicant_otherdetails')
							->where('app_id', $fetch_data['appid'])
							->get();
        if($query->num_rows())
		{
			$this->db->where('app_id', $fetch_data['appid']);				
			$this->db->update('applicant_otherdetails', $dataUp);
		}
		else
		{
			$this->db->insert('applicant_otherdetails', $dataIn);
		}
	}
	public function applicants_interview($data)
	{
		$que = $this->db->select('count(id) as val')
					->get_where('application_interview_details', array('interviewee_id' => $data));
		return $que->row_array()['val'];	
	}
	
	public function get_Initial_interviewer_list($data)
	{
		$query = $this->db->from('application_interview_details')
							->where("interviewee_id = '$data' AND interviewee_level = 1")
							->order_by('id', 'DESC')
							->get();
        return $query->row_array();
	}
	
	public function get_Initial_interview_remarks($data)
	{
		//$query = $this->db->select('interviewer_remarks')
		$query = $this->db->from('application_interview_details')
							->where("interviewee_id = '$data' AND interviewee_level = ''")
							->order_by('id', 'DESC')
							->get();
        return $query->row_array();
	}
	public function applicants_interview_level($data)
	{
		$que = $this->db->select('count(id) as val')
					->where("interviewee_id = '$data' AND interviewee_level != 0")
					->get('application_interview_details');
		return $que->row_array()['val'];		
	}
	
	public function applicants_for_finalcompletion()
    {
		$query = $this->db->select('applicants.app_code, applicant.app_id, applicants.lastname, applicants.middlename, applicants.firstname, applicants.suffix, applicants.date_time, applicants.position')
							->from('applicants')
							->join('applicant', 'applicants.app_code = applicant.appcode')
							->where("(applicants.status = 'for final completion') AND (applicants.tagged_to = 'nesco')")
							->order_by('app_code', 'ASC')
							->get();
        return $query->result_array();
    }
	
	public function company()
	{
		$query = $this->db->from('locate_promo_company')
							->order_by('pc_name', 'ASC')
							->get();
			return $query->result_array();	
	}
	public function check_agency($data)
	{
		if($data == '0')
		{
			$query = $this->db->from('locate_promo_company')
							->where("status = '1'")
							->order_by('pc_name', 'ASC')
							->get();
			return $query->result_array();	
		}
		else
		{
			$query = $this->db2->from('promo_locate_company')
							->where("agency_code = $data")
							->order_by('company_name', 'ASC')
							->get();
			return $query->result_array();
		}
	}
	
	public function agency()
	{
		$query = $this->db2->from('promo_locate_agency')
							->where("status = '1'")
							->order_by('agency_name', 'ASC')
							->get();
        return $query->result_array();
	}
	
	public function applicants_for_hiring()
    {
		$query = $this->db->from('applicants')
							->join('applicant', 'applicants.app_code = applicant.appcode')
							->where("applicants.status = 'for hiring' AND applicants.tagged_to = 'nesco'")
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