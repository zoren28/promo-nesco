<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Initial extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('nativesession');

        if (!isset($_SESSION['emp_id'])) {

            redirect('http://' . $_SERVER['SERVER_ADDR'] . ":" . $_SERVER['SERVER_PORT'] . '/hrms/nesco');
        }
		$this->load->model('recruitment/initial_model');
		$this->load->model('placement/employee_model');
		$this->load->model('placement/contract_model');
	}
	
	public function check_applicant_duplicate_or_blacklist() {

		$fetch_data = $this->input->post(NULL, TRUE);
		$data['request'] = "applicant_duplicate_or_blacklist";
		
		if($fetch_data['gender'] == 'female' && $fetch_data['civilstatus'] != 'single')
		{
			
			//$data['blacklist_suggest'] = $this->initial_model->check_applicant_blacklist_suggest($fetch_data);
			$data['blacklist'] = $this->initial_model->check_applicant_blacklist($fetch_data);
			$data['duplicate'] = $this->initial_model->check_duplicate_applicant($fetch_data);
			$data['duplicate_MI'] = $this->initial_model->check_duplicate_MI_applicant($fetch_data);
		}
		else
		{
			//$data['blacklist_suggest'] = $this->initial_model->check_applicant_blacklist_suggest($fetch_data);
			$data['blacklist'] = $this->initial_model->check_applicant_blacklist($fetch_data);
			$data['duplicate'] = $this->initial_model->check_duplicate_applicant($fetch_data);
			$data['duplicate_MI'] = [];
			
		}
		
		$this->load->view('body/recruitment/function_query',$data);
	}
	
	public function show_intro_check()
    {
		$data['stores'] = $this->input->get('check', TRUE);
        $data['request'] = 'show_intro';
        $this->load->view('body/recruitment/function_query',$data);	
    }
	
	// get applicant data recorded on database and compare it on the value to be recorded
	public function proceed_record_applicants() 
	{
		$fetch_data 			= $this->input->post(NULL, TRUE);
		
		$applicant_data = explode("|",$fetch_data['id']);
		$data['checkduplicate'] = $this->initial_model->check_applcant_duplicate($applicant_data[1],$applicant_data[2],$applicant_data[3],$applicant_data[4]);
		
		if(!empty($data['checkduplicate']))
		{
			$data['request'] = "applicant_old_record";
			$data['check_record'] = $this->initial_model->record_applicant_info($fetch_data);
			$data['refference']	= $this->initial_model->get_refference($data['checkduplicate']['app_id']);
			$data['seminar_eligibility'] = $this->initial_model->get_seminar_and_eligibility($data['checkduplicate']['app_id']);
			$data['employment_history'] = $this->initial_model->get_employment_history($data['checkduplicate']['app_id']);
		}
		else
		{
			$data['check_record'] 	= $this->initial_model->record_applicant_info($fetch_data);
			$data['request'] 		= "applicant_new_record";
		}
		
		$this->load->view('body/recruitment/function_query',$data);
	}
	
	public function view_exam_setup()  
	{
		$fetch_data 			= $this->input->post(NULL, TRUE);
		$data['request'] 		= "applicant_examination_view";
		$data['view_exam'] 		= $this->initial_model->applicant_examinee($fetch_data);
		$data['exam_pos'] 		= $this->initial_model->applicant_position_apply($data['view_exam']['appcode']);
		$data['exam_cat']		= $this->initial_model->applicant_exam_cat($fetch_data);
		$data['exam_score']		= $this->initial_model->application_exam_score($data['exam_cat']['exam_cat']);
		$this->load->view('body/recruitment/function_query',$data);
	}
	
	public function tag_applicant_interview()  
	{
		$fetch_data 	= 	$this->input->post(NULL, TRUE);
		$explode_val	= 	explode("|",$fetch_data['id']);
		$fetch_data['app_status']	=	'for interview';
		$fetch_data['appcode']  	= 	$explode_val[1];
		$fetch_data['app_id']  	= 	$explode_val[0];
		$fetch_data['app_history_tag'] 	=	6;
		
		$position = $this->initial_model->applicant_position_apply($fetch_data['appcode']);
		$fetch_data['position'] = $position['position'];
		
		$updateStat = $this->initial_model->applicant_status($fetch_data);
		$appHistory = $this->initial_model->save_application_history($fetch_data);

		if($updateStat)
		{ 
			echo json_encode(array('status'=> 1, 'message' => "Applicant successfully tag for interview!")); 
		}
		else
		{ 
			echo json_encode(array('status'=> 0, 'message' => "Error Found!")); 
		} 	
	}
	
	public function save_initial_interview()
	{
		$fetch_data = $this->input->post(NULL, TRUE);
		$fetch_data['app_history_tag'] = 7;
		$applicantCode = $this->initial_model->get_application_code($fetch_data['appid']);
		$fetch_data['app_code'] = $applicantCode['appcode'];
		
		$initial = $this->initial_model->save_initial_interview($fetch_data);
		$this->initial_model->save_application_history($fetch_data);
		
		if($initial == 1)
		{ 
			echo json_encode(array('status'=> 1, 'message' => "Applicant Initial Interview Done!")); 
		}
		else
		{ 
			echo json_encode(array('status'=> 0, 'message' => "Error Found!")); 
		}
	}
	public function save_final_completion()
	{
		
		$fetch_data = $this->input->post(NULL, TRUE);
		$temp 		= 	'jpg';
		$target_dir	=	"../document/final_requirements/";
		$fetch_data['app_history_tag'] = 10;

		$dataposition['position'] = $this->initial_model->applicant_position_apply($fetch_data['appcode']);

		if($dataposition['position']['position'] == "Merchandiser Seasonal" || $dataposition['position']['position'] == "Promodiser Seasonal")
		{
			$files = array('police_clearance',
							'recommend_letter',
							'otherDoc');

			$this->db->trans_start();
			foreach($files as $file => $value) 
			{
				if(isset($_FILES[$value])) 
				{
					if($value == 'police_clearance')
					{
						$file_name 		= "Police Clearance";
						$target_folder 	= $target_dir."police_clearance/";
						$filename 		= $value."_".$fetch_data['appid']."_".date("Y-m-dhis").".".$temp;
						$location 		= $target_folder.$filename;

						if(move_uploaded_file($_FILES[$value]["tmp_name"],$target_folder.''.$filename))
						{
							$requirement_info = $this->initial_model->insert_finalreq_info($value,$location,$fetch_data);
						}	
					}
					else if($value == 'recommend_letter')
					{
						$file_name 		= "Recommendation Letter";
						$target_folder 	= $target_dir."recommendation_letter/";
						$filename 		= $value."_".$fetch_data['appid']."_".date("Y-m-dhis").".".$temp;
						$location 		= $target_folder.$filename;

						if(move_uploaded_file($_FILES[$value]["tmp_name"],$target_folder.''.$filename))
						{
							$requirement_info = $this->initial_model->insert_finalreq_info($value,$location,$fetch_data);
						}	
					}
					else if($value == 'otherDoc')
					{					
						for($i=0; $i< count($_FILES[$value]['name']); $i++)
						{
							$file_name 		= "Other Document";
							$target_folder 	= $target_dir."others/";
							$filename 		= $fetch_data['documentName'][$i]."_".$i."_".$fetch_data['appid']."_".date("Y-m-dhis").".".$temp;
							$location 		= $target_folder.$filename;
							$fetch_data['doc'] = $fetch_data['documentName'][$i];
							
							if(move_uploaded_file($_FILES[$value]["tmp_name"][$i],$target_folder.''.$filename))
							{
								$requirement_info = $this->initial_model->insert_finalreq_info($value,$location,$fetch_data);
							}	
						}		
					}	
				}
			}

			$this->initial_model->insertRemarks($fetch_data);
			$fetch_data['app_status'] = "for hiring";
			$this->initial_model->applicant_status($fetch_data);
			
			$this->initial_model->save_application_history($fetch_data);
			
			$this->db->trans_complete();
			if ($this->db->trans_status() === TRUE)
			{ 
				echo json_encode(array('status'=> 1, 'message' => "Applicant Proceed to For Hiring"));				
			}
			else
			{ 
				echo json_encode(array('status'=> 0, 'message' => "Error Found!")); 
			}
		}
		else
		{                    
			$files = array('birthcertificate',
							'police_clearance',
							'fingerprint',
							'sss',
							'cedula',
							'parentconsent',
							'medical',
							'house_skecth',
							'background_investagation',
							'drugtest',
							'recommend_letter',
							'marriage',
							'otherDoc');
			
			$maxsize    = 2097152;
			$acceptable = array(
				'image/jpeg',
				'image/jpg',
				'image/png'
			);

			$errors = array();

			foreach($files as $file => $value) 
			{				
				if(isset($_FILES[$value])) 
				{
					if($value == 'birthcertificate' || $value == 'otherDoc')
					{
						
						for($i=0; $i< count($_FILES[$value]['name']); $i++)
						{
							$filesize 	= 	$_FILES[$value]['size'][$i];
							$filetype	=	$_FILES[$value]['type'][$i];
							
							if($filesize >= $maxsize) 
							{
									$errors[] = strtoupper($value)." file is too large. File must be less than 2 megabytes.";
							}
							else
							{
								if((!in_array($filetype, $acceptable)) && (!empty($filetype))) 
								{
									$errors[] = strtoupper($value)." file is invalid file type. Only PDF, JPG, GIF and PNG types are accepted.";
								}
							}
						}	
					}	
				}	
			}
			// checking the filesize does not exceed 2MB
			if(count($errors))
			{	
				echo json_encode(array('status'=> 0, 'message' => $errors));	
			}
			else
			{
				foreach($files as $file => $value) 
				{				
					if(isset($_FILES[$value])) 
					{
						if($value == 'birthcertificate' || $value == 'otherDoc')
						{
							
							for($i=0; $i< count($_FILES[$value]['name']); $i++)
							{
								if($value == 'birthcertificate')
								{
									$file_name 		= "Birth Certificate";
									$target_folder 	= $target_dir."birth_certificate/";
									$filename 		= $value."_".$i."_".$fetch_data['appid']."_".date("Y-m-d his").".".$temp;
									$location 		= $target_folder.$filename;
								}
								else
								{
									$file_name 		= "Other Document";
									$target_folder 	= $target_dir."others/";
									$filename 		= $fetch_data['documentName'][$i]."_".$i."_".$fetch_data['appid']."_".date("Y-m-d his").".".$temp;
									$location 		= $target_folder.$filename;
									$fetch_data['doc'] = $fetch_data['documentName'][$i];
								}
								
								if(move_uploaded_file($_FILES[$value]["tmp_name"][$i],$target_folder.''.$filename))
								{
									$requirement_info = $this->initial_model->insert_finalreq_info($value,$location,$fetch_data);
								}
							}	
						}
						else
						{
							$upload_checking = $this->initial_model->check_upload_finalcompletion($value,$fetch_data);
						}		
					}	
				}
				
				$this->initial_model->updateBloodtype($fetch_data);
				$this->initial_model->update_or_insert_cedula_benifits_numbers($fetch_data);
				$this->initial_model->insertRemarks($fetch_data);
				$this->initial_model->insertBenifits($fetch_data);
				$fetch_data['app_status'] = "for hiring";
				$this->initial_model->applicant_status($fetch_data);
				$this->initial_model->save_application_history($fetch_data);

				echo json_encode(array('status'=> 1, 'message' => "Final Requirements uploaded successfully!"));
			}
			
		}	
	}
	
	public function get_duration()
	{
		$fetch_data = $this->input->post(NULL, TRUE);
		
		$dF =  new DateTime($fetch_data['strtDate']);
		$dT =  new DateTime($fetch_data['endDate']);

		$newDF = date('Y-m-d', strtotime($fetch_data['strtDate']));
		$newDT = date('Y-m-d', strtotime($fetch_data['endDate']));

		$interval = $dT->diff($dF);
		$days_duration = $interval->format('%a') + 1;

		if ($days_duration >= 32) 
		{
			$days_duration = $interval->format('%m');
		} 
		else 
		{
			$days_duration = "$days_duration day(s)";
		}
		
		if($newDF > $newDT)
		{
			echo json_encode(array('status'=> 1, 'message' => "EOCdate must be greater than or equal to startdate!"));	
		}
		else
		{
			echo json_encode(array('status'=> 0, 'duration' => $days_duration));	
		}
			
	}
	
	public function company_select()
	{
		$fetch_data = $this->input->post(NULL, TRUE);
		$data['agency_result'] = $this->initial_model->check_agency($fetch_data['agency_code']);
		$data['request'] = "company_list";
		$this->load->view('body/recruitment/function_query', $data);
	}
	
	public function selproduct()
	{
		$fetch_data = $this->input->post(NULL, TRUE);
		$data['product'] = $this->initial_model->check_product($fetch_data['product']);
		$data['request'] = "product";
		$this->load->view('body/recruitment/function_query', $data);
	}
	
	public function locate_vendor()
	{
		$fetch_data = $this->input->post(NULL, TRUE);
		$data['vendor'] = $this->initial_model->check_vendor($fetch_data['deptval']);
		$data['request'] = "vendor";
		$this->load->view('body/recruitment/function_query', $data);
	}
	
	public function hiring_setup()
	{
		$fetch_data = $this->input->post(NULL, TRUE);
		$explode_val = explode("|",$fetch_data['id']);
		$fetch_data['id'] = $explode_val[1];
		$data['hired'] = $this->initial_model->applicant_examinee($fetch_data);
		$data['apposition'] = $this->initial_model->applicant_position_apply($data['hired']['appcode']);
		$data['request'] = "hiring_setup";
		$this->load->view('body/recruitment/function_query', $data);
	}
	
	public function final_completion()
	{
		$fetch_data = $this->input->post(NULL, TRUE);
		$explode_val = explode("|",$fetch_data['id']);
		$fetch_data['id'] = $explode_val[1];
		$data['finale'] = $this->initial_model->applicant_examinee($fetch_data);
		$data['check_record'] 	= $this->initial_model->record_applicant_info($fetch_data);
		$data_position['getposition'] = $this->initial_model->applicant_position_apply($data['finale']['appcode']);
		
		if($data_position['getposition']['position'] == "Merchandiser Seasonal" || $data_position['getposition']['position'] == "Promodiser Seasonal")
		{
			$data['request'] = "final_completion_seassonal";	
		}
		else
		{
			$data['request'] = "final_completion";	
		}
		$this->load->view('body/recruitment/function_query', $data);
	}
	
	public function hire_applicant()
	{
		$fetch_data = $this->input->post(NULL, TRUE);
		$fetch_data['app_status'] = "new employee";
		$fetch_data['agency'] = 29;
		$fetch_data['app_history_tag'] = 11;

		$this->db->trans_start();
		$dataCount = $this->initial_model->check_employee_existince($fetch_data['appid']);
		if($dataCount > 0)
		{
			$oldData = $this->initial_model->employee_oldData($fetch_data['appid']);
			$this->initial_model->employmentRecord($oldData,$fetch_data);
			$this->initial_model->applicant_status($fetch_data);
		}
		else
		{
			$this->initial_model->employment_New_Record($fetch_data);
			$this->initial_model->applicant_status($fetch_data);
		}
		
		$this->initial_model->save_application_history($fetch_data);

		$this->db->trans_complete();
		if ($this->db->trans_status() === TRUE)
		{ 
			echo json_encode(array('status'=> 1, 'message' => "Applicant’s Contract Details have been saved. Print Intro letter?"));
		}
		else
		{ 
			echo json_encode(array('status'=> 2, 'message' => "Error Found!")); 
		}	 
	}
	
	public function final_interview()
	{
		$fetch_data = $this->input->post(NULL, TRUE);
		$fetch_data['app_status'] = $this->initial_model->get_interview_result($fetch_data);
		$fetch_data['app_history_tag'] = 9;
		$applicantCode = $this->initial_model->get_application_code($fetch_data['appid']);
		$fetch_data['app_code'] = $applicantCode['appcode'];
		
		if(!empty($fetch_data['grade']))
		{
			$this->db->trans_start();

			$this->initial_model->applicant_status($fetch_data);
		 	$this->initial_model->save_application_history($fetch_data);

			$this->db->trans_complete();
			if ($this->db->trans_status() === TRUE)
			{ 
				if($fetch_data['interview_stat'] == 'passed')
				{
					echo json_encode(array('status'=> 1, 'message' => "Applicant Final Interview Done... Proceed to Final Completion"));
				}
				else
				{
					echo json_encode(array('status'=> 1, 'message' => "Applicant Failed Interview! Applicant is now on hold for future reference "));
				}
				
			}
			else
			{ 
				echo json_encode(array('status'=> 2, 'message' => "Error Found!")); 
			}	 
		}
		else
		{
			echo json_encode(array('status'=> 0, 'message' => "Sorry! Can't Proceed, No Grades Yet!")); 
		}
	}
	
	// check interview details
	public function check_interview()
	{
		$fetch_data = $this->input->post(NULL, TRUE);
		$explode_val = explode("|",$fetch_data['id']);
		$fetch_data['id'] = $explode_val[1];
		
		$data['interview_remarks'] = $this->initial_model->get_Initial_interview_remarks($explode_val[1]);
		$data['applicant_examinee'] = $this->initial_model->applicant_examinee($fetch_data);
		$data['interviewer_list'] = $this->initial_model->get_Initial_interviewer_list($explode_val[1]);
		$data['request'] = "check_interview_detail";
		$this->load->view('body/recruitment/function_query', $data);

	}
	// selecting an final interviewer
	public function setup_interviewee()
	{
		$fetch_data = $this->input->post(NULL, TRUE);
		$fetch_data['app_history_tag'] = 8;
		$applicantCode = $this->initial_model->get_application_code($fetch_data['appid']);
		$fetch_data['app_code'] = $applicantCode['appcode'];
		$setup = $this->initial_model->save_setUp_interview($fetch_data);
		$apphistory = $this->initial_model->save_application_history($fetch_data);

		if ($setup == 1)
		{ 
			echo json_encode(array('status'=> 1, 'message' => "Applicant Set-up Interview Done!")); 
		}
		else
		{ 
			echo json_encode(array('status'=> 0, 'message' => "Error Found!")); 
		}
	}
	// modal for setup interview
	public function setup_interview()
	{
		$fetch_data = $this->input->post(NULL, TRUE);
		$explode_val = explode("|",$fetch_data['id']);
		$fetch_data['id'] = $explode_val[1];
		$data['setup_interview'] = $this->initial_model->applicant_examinee($fetch_data);
		$data['request'] = "setup_interviewer";
		$this->load->view('body/recruitment/function_query', $data);
	}
	
	public function save_transfer_process()
	{
		$fetch_data = $this->input->post(NULL, TRUE); 
		$fetch_data['app_history_tag'] = 13;
		$updateChange = $this->initial_model->update_status_and_change_position($fetch_data);
		$this->initial_model->save_application_history($fetch_data);
		if($updateChange)
		{
			echo json_encode(array('status'=> 1, 'message' => "Applicant successfully transfer!")); 
		}
		else
		{
			echo json_encode(array('status'=> 0, 'message' => "Oops, something went wrong. Please try again."));
		}
	}

	public function transfer_app()
	{
		$fetch_data = $this->input->post(NULL, TRUE);
		$exp = explode("|",$fetch_data['id']);
		$fetch_data['id'] = $exp[1];
		$data['position'] = $this->initial_model->applicant_position_apply($exp[0]);
		$data['applicant_info'] = $this->initial_model->applicant_info($fetch_data);
		$data['request'] = "transfer_applicant";
		$this->load->view('body/recruitment/function_query', $data);
	}
	
	// deploye applicant/new employee
	public function deploy_applicant()
	{
		$fetch_data = $this->input->post(NULL, TRUE);
		$exploded_val = explode("|",$fetch_data['id']);
		$fetch_data['app_status'] = "deployed";
		$fetch_data['appcode'] = $exploded_val[0];
		$fetch_data['appid'] = $exploded_val[1];
		$fetch_data['app_history_tag'] = 12;
		
		$appDeploy = $this->initial_model->applicant_status($fetch_data);
		$this->initial_model->save_application_history($fetch_data);

		if($appDeploy)
		{
			echo json_encode(array('status'=> "success", 'message' => "Applicant successfully deployed!")); 
		}
		else
		{
			echo json_encode(array('status'=> "error", 'message' => "Oops, something went wrong. Please try again."));
		}
	}
	
	public function initial_interview()  
	{
		$fetch_data = $this->input->post(NULL, TRUE);
		$explode_val = explode("|",$fetch_data['id']);
		$fetch_data['id'] = $explode_val[1];
		$data['request'] = "applicant_initial_interview";
		$data['initial_interview'] = $this->initial_model->applicant_examinee($fetch_data);
		$this->load->view('body/recruitment/function_query', $data);
	}
	
	public function tag_applicant_transfer()  
	{
		$fetch_data 				= $this->input->post(NULL, TRUE);
		$explode_val				= explode("|",$fetch_data['id']);
		$fetch_data['app_status']  	= 	'failed exam';
		$fetch_data['appcode']  	= 	$explode_val[1];
		
		$this->db->trans_start();
		$data['tag_transfer'] 		= $this->initial_model->applicant_status($fetch_data);
		$this->db->trans_complete();
		if ($this->db->trans_status() === TRUE)
		{ 
			echo json_encode(array('status'=> 1, 'message' => "Applicant successfully tag for transfer!")); 
		}
		else
		{ 
			echo json_encode(array('status'=> 0, 'message' => "Error Found!")); 
		}
	}
	
	public function applicant_examination_setup()  
	{
		$fetch_data 			= $this->input->post(NULL, TRUE);
		$data['examinee'] 		= $this->initial_model->applicant_examinee($fetch_data);
		$data['examinee_app'] 	= $this->initial_model->applicant_position_apply($data['examinee']['appcode']);
		$data['request'] 		= "applicant_examination";
		$this->load->view('body/recruitment/function_query',$data);
	}
	
	public function save_examination()
	{
		$fetch_data = 	$this->input->post(NULL, TRUE);
		$fetch_data['app_history_tag'] = 5;

		if($fetch_data['exam_stat'] == 'passed')
		{
			$fetch_data['app_status'] = 'exam passed';
		}
		else if($fetch_data['exam_stat'] == 'failed')
		{
			$fetch_data['app_status'] = 'exam failed';
		}
		else if($fetch_data['exam_stat'] == 'assessment')
		{
			$fetch_data['app_status'] = 'assessment';
		}

		$ret_exam = $this->initial_model->save_exam_scores($fetch_data);
		if($ret_exam == 1)
		{
			$ret_exam_stat = $this->initial_model->exam_stats($fetch_data);
			if($ret_exam_stat == 1)
			{
				$ret_app_status = $this->initial_model->applicant_status($fetch_data);
				$this->initial_model->save_application_history($fetch_data);
			}
		}

		if($ret_app_status == 1)
		{
			echo json_encode(array('status'=> 1, 'message' => "Applicant successfully save examination!")); 
		}
		else
		{
			echo json_encode(array('status'=> 0, 'message' => "Error Found!")); 
		}
	}
	
	public function setup_examination()
	{
		$fetch_data 				= 	$this->input->post(NULL, TRUE);
		$fetch_data['app_status']  	= 	'for exam';
		
		$this->db->trans_start();
		$this->initial_model->setup_examination_info_append($fetch_data);
		// $this->initial_model->setup_textfile($fetch_data); // create text file for examination
		$this->initial_model->applicant_status($fetch_data);
		$this->initial_model->history_info_append($fetch_data);
		$this->db->trans_complete();
		
		if ($this->db->trans_status() === TRUE)
		{ 
			echo json_encode(array('status'=> 1, 'message' => "Applicant successfully set-up examination!")); 
		}
		else
		{ 
			echo json_encode(array('status'=> 0, 'message' => "Error Found!")); 
		}
	}
	

	public function interview_grade()
	{
		$fetch_data = $this->input->post(NULL, TRUE);
		
		$fetch_data['app_history_tag'] = 4;
		
		if($fetch_data['num_rate'] < 85)
		{ 
			$fetch_data['interview_stat'] = 'failed'; 
		}
		else
		{ 
			$fetch_data['interview_stat'] = 'passed';
		}
		
		if($fetch_data['num_rate'] > 0)
		{
			
			$save_grader = $this->initial_model->save_interview_grade($fetch_data);
			
			if($save_grader == 1)
			{
				$save_rate = $this->initial_model->save_interview_rate($fetch_data);
				if($save_rate)
				{
					$upDetail = $this->initial_model->update_interview_detail($fetch_data);
					if($upDetail)
					{
						$save_history = $this->initial_model->save_application_history($fetch_data);
						if($save_history)
						{
							$save_manual = $this->initial_model->save_interview_manual($fetch_data);
							if($save_manual)
							{
								echo json_encode(array('status'=> 1, 'message' => "Applicant evaluation form completed!")); 
							}
							else
							{
								echo json_encode(array('status'=> 0, 'message' => "Error on saving!")); 
							}
						}	
					}	
				}
			}
		}
			
	}

	public function applicant_information()
	{
		$fetch_data = $this->input->post(NULL, TRUE);
		
		$flag_seminar = 0;
		$flag_history = 0;
		$temp 				= 	'jpg';
		$target_seminar		=	"../document/seminar_certificate/";
		$target_employment	=	"../document/employment_certificate/";
		
		$explode_Id = explode("|",$this->initial_model->get_appId()); // getting the new applicant HRMS ID
		$fetch_data['appId'] = $explode_Id[1];
		$fetch_data['id'] = $explode_Id[0];

		$fetch_data['app_history_tag'] = 2;

		if($fetch_data['procedure'] == "UPDATE")
		{
			$fileId = $fetch_data['hrmsId'];
		}
		else
		{
			$fileId = $fetch_data['appId'];
		}

		$saveAppInfo = $this->initial_model->save_applicant_info($fetch_data); // applicant information like basic info, family background
		$saveAppCharacter = $this->initial_model->save_applicant_character_ref($fetch_data); // character refference

		// saving application seminar/training/eligibility
		for($i= 0 ; $i< count($fetch_data['seminar_name']);$i++)
		{
			if(isset($_FILES['seminar_certificate']['name'][$i])) 
			{
				$filesize 		= 	$_FILES['seminar_certificate']['size'][$i];
				$filename		=	$_FILES['seminar_certificate']['name'][$i];
				$filetype		=	$_FILES['seminar_certificate']['type'][$i];
				$seminar_cert 	= 	"seminar_certificate_".$i."_".$fileId."_".date("Y-m-d His").".".$temp;
				$location 		= 	$target_seminar.$seminar_cert;
				
				$fetch_data['location'] = $location;
				
				if(move_uploaded_file($_FILES["seminar_certificate"]["tmp_name"][$i], $target_seminar.$seminar_cert)) // uploading seminar certificate
				{
					$this->initial_model->save_applicant_seminar_training_eligibility($fetch_data, $i); // saving seminar info
				}		
			}	
		}
			
		for($z= 0; $z< count($fetch_data['company_name']);$z++)
		{
			if(isset($_FILES['certificate']['name'][$z])) 
			{
				$f_size 		= 	$_FILES['certificate']['size'][$z];
				$f_name			=	$_FILES['certificate']['name'][$z];
				$f_type			=	$_FILES['certificate']['type'][$z];
				$f_cert 		= 	"certificate".$z."_".$fileId."_".date("Y-m-d His").".".$temp;
				$location 		= 	$target_employment.$f_cert;
				
				$fetch_data['location'] = $location;
				
				if(move_uploaded_file($_FILES["certificate"]["tmp_name"][$z], $target_employment.$f_cert)) // uploading employment certificate
				{
					echo $this->initial_model->save_applicant_employment_history($fetch_data, $z); // saving employment info
				}
			}	
		}

		$updateStat = $this->initial_model->update_applicant_status($fetch_data); // updating the applicant status
		$up_app_history = $this->initial_model->save_application_history($fetch_data); // saving application history
		
		if($saveAppInfo == 1)
		{
			echo json_encode(array('status'=> 1, 'message' => "Applicant successfully recorded. Proceed & set-up examination."));	
		}
		else
		{
			echo json_encode(array('status'=> 0, 'message' => "Error Found!"));	
		}
	}
	
	public function upload_initial() 
	{
		$fetch_data = $this->input->post(NULL, TRUE);
		$fetch_data['app_history_tag'] = 1; // upload requirements tag for application history
		
		
		if($fetch_data['position'] == "Merchandiser Seasonal" || $fetch_data['position'] == "Promodiser Seasonal")
		{
			$check_initial = $this->initial_model->insert_initial_applicant_info($fetch_data);
			$fetch_data['appcode'] = $check_initial;
			$app_history = $this->initial_model->save_application_history($fetch_data); // saving application history
			
			if($check_initial)
			{
				echo json_encode(array('status'=> 1, 'message' => "Seassonal Applicant succesfully added to Record List!"));
			}	
		}
		else
		{
			$files 		= 	array('resume', 'application', 'transcript');
			$err 		= 	0;
			$errors 	= 	[];
			$temp 		= 	'jpg';
			$target_dir	=	"../document/initial_requirements/";
			
			foreach($files as $file => $value) 
			{
				if(isset($_FILES[$value])) 
				{
					
					$maximsize    = 8000000;
					$maxsize    = 2097152;
					$acceptable = array(
						'image/jpeg',
						'image/jpg',
						'image/png'
					);
					
					for($i=0; $i< count($_FILES[$value]['name']); $i++)
					{
						$filesize 	= 	$_FILES[$value]['size'][$i];
						$filetype	=	$_FILES[$value]['type'][$i];
						
						if($filesize >= $maxsize) 
						{
							$errors[] = ucfirst($value)." file too large. File must be less than 2 megabytes.";
						}
						else
						{
							if((!in_array($filetype, $acceptable)) && (!empty($filetype))) 
							{
								$errors[] =  ucfirst($value)." file is invalid file type. Only PDF, JPG, GIF and PNG types are accepted.";
							}
						}	
					}	
				}
			}
			
			if(count($errors))
			{	
				echo json_encode(array('status'=> 0, 'message' => $errors));
				die();
			}
			else
			{
				$check_initial = $this->initial_model->insert_initial_applicant_info($fetch_data);
				$fetch_data['appcode'] = $check_initial;
				$resume_flag = 0;
				$application_flag = 0;
				$transcript_upload = 0;

				if($fetch_data['updt_or_appnd'] == 'INSERT')
				{ 
					$appcode = $check_initial; 
				}
				else
				{ 
					$appcode = $check_initial['app_code']; 
				} 
				
				foreach($files as $file => $value) 
				{
					if(isset($_FILES[$value])) 
					{
						$errors     = array();
						$maxsize    = 2097152;
						$acceptable = array(
							'image/jpeg',
							'image/jpg',
							'image/png'
						);
						
						if($value == 'resume')
						{
							for($i=0; $i< count($_FILES[$value]['name']); $i++)
							{
								$filesize 	= 	$_FILES['resume']['size'][$i];
								$filename	=	$_FILES['resume']['name'][$i];
								$filetype	=	$_FILES['resume']['type'][$i];
								$resume 	= 	"resumebiodata_".$i."_".$appcode."_".date("Y-m-d").".".$temp;
								$location 	= 	$target_dir."resume/".$resume;
								
								if(move_uploaded_file($_FILES["resume"]["tmp_name"][$i], $target_dir."resume/".$resume))
								{
									$resume_upload = $this->initial_model->insert_uploaded_info($value,$location,$appcode);
									if($resume_upload)
									{$resume_flag = 1;}
								}
							}	
						}
						elseif($value == 'application')
						{
							for($x=0; $x< count($_FILES[$value]['name']); $x++)
							{
								$filesize 		= 	$_FILES['application']['size'][$x];
								$filename		=	$_FILES['application']['name'][$x];
								$filetype		=	$_FILES['application']['type'][$x];
								$application 	= 	"application_".$x."_".$appcode."_".date("Y-m-d").".".$temp;
								$location 		= 	$target_dir."application_letter/".$application;
								if(move_uploaded_file($_FILES["application"]["tmp_name"][$x], $target_dir."application_letter/".$application))
								{
									$application_upload = $this->initial_model->insert_uploaded_info($value,$location,$appcode);
									if($application_upload)
									{$application_flag = 1;}
								}
							}
						}
						elseif($value == 'transcript')
						{
							for($n=0; $n< count($_FILES[$value]['name']); $n++)
							{
								$filesize 		= 	$_FILES['transcript']['size'][$n];
								$filename		=	$_FILES['transcript']['name'][$n];
								$filetype		=	$_FILES['transcript']['type'][$n];
								$transcript 	= 	"transcript_".$n."_".$appcode."_".date("Y-m-d").".".$temp;
								$location 		= 	$target_dir."tor/".$transcript;
								
								if(move_uploaded_file($_FILES["transcript"]["tmp_name"][$n], $target_dir."tor/".$transcript))
								{
									$transcript_upload = $this->initial_model->insert_uploaded_info($value,$location,$appcode);
									if($transcript_upload)
									{$transcript_flag= 1;}	
								} 
							}		
						}
					} 
				}
				$app_history = $this->initial_model->save_application_history($fetch_data); // saving application history

				if($transcript_flag == 1 && $application_flag == 1 && $resume_flag == 1)
				{
					echo json_encode(array('status'=> 1, 'message' => "Files Succesfully Uploaded, Applicant proceeded to Record list."));
				}	
			}	
		}
	}
	
	public function append_character_ref() {
		
		$data['request'] = 'append_character_ref';
		$this->load->view('body/recruitment/function_query', $data);
	}

	public function append_character_ref_old() 
	{	
		$data['request'] = 'append_character_ref_old';
		$this->load->view('body/recruitment/function_query', $data);
	}

	public function append_otherDocs() 
	{	
		$data['request'] = 'append_otherDocs';
		$this->load->view('body/recruitment/function_query', $data);
	}

	public function append_other_Docs() 
	{	
		$data['request'] = 'append_other_Docs';
		$this->load->view('body/recruitment/function_query', $data);
	}

	public function input_grades()
	{
		$data['request'] = 'input_grades';
		$this->load->view('body/recruitment/function_query', $data);
	}
	
}