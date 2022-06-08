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
	}
	
	public function check_applicant_duplicate_or_blacklist() {

		$fetch_data = $this->input->post(NULL, TRUE);
        $data['request'] = "applicant_duplicate_or_blacklist";
        $data['fetch'] = $this->initial_model->check_applicant_duplicate_or_blacklist($fetch_data);
		$this->load->view('body/recruitment/function_query',$data);
	}
	
	public function proceed_record_applicants() 
	{
		$fetch_data 			= $this->input->post(NULL, TRUE);
		$data['check_record'] 	= $this->initial_model->record_applicant_info($fetch_data);
		$data['request'] 		= "applicant_record";
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
		$fetch_data 				= $this->input->post(NULL, TRUE);
		$explode_val				= explode("|",$fetch_data['id']);
		$fetch_data['app_status']  	= 	'for interview';
		$fetch_data['appcode']  	= 	$explode_val[1];
		//print_r($fetch_data);
		$this->db->trans_start();
		$data['tag_interview'] 		= $this->initial_model->applicant_status($fetch_data);
		$this->db->trans_complete();
		if ($this->db->trans_status() === TRUE)
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
		$this->db->trans_start();
		$this->initial_model->save_initial_interview($fetch_data);
		$this->db->trans_complete();
		if ($this->db->trans_status() === TRUE)
		{ 
			echo json_encode(array('status'=> 1, 'message' => "Applicant Initial Interview Done!")); 
		}
		else
		{ 
			echo json_encode(array('status'=> 0, 'message' => "Error Found!")); 
		}
	}
	
	// selecting an final interviewer
	public function setup_interviewee()
	{
		$fetch_data = $this->input->post(NULL, TRUE);
		print_r($fetch_data);
	}
	// modal for setup interview
	public function setup_interview()
	{
		$fetch_data = $this->input->post(NULL, TRUE);
		$data['request'] = "setup_interviewer";
		$this->load->view('body/recruitment/function_query', $data);
	}
	
	public function initial_interview()  
	{
		$fetch_data = $this->input->post(NULL, TRUE);
		$explode_val = explode("|",$fetch_data['id']);
		$fetch_data['id'] = $explode_val[1];
		$data['request'] = "applicant_initial_interview";
		//print_r($fetch_data);
		$data['initial_interview'] = $this->initial_model->applicant_examinee($fetch_data);
		$this->load->view('body/recruitment/function_query', $data);
	}
	
	public function tag_applicant_transfer()  
	{
		$fetch_data 				= $this->input->post(NULL, TRUE);
		$explode_val				= explode("|",$fetch_data['id']);
		$fetch_data['app_status']  	= 	'failed exam';
		$fetch_data['appcode']  	= 	$explode_val[1];
		//print_r($fetch_data);
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
		
		$this->db->trans_start();
		$this->initial_model->save_exam_scores($fetch_data);
		$this->db->trans_complete();
		if ($this->db->trans_status() === TRUE)
		{ 
			echo json_encode(array('status'=> 1, 'message' => "Applicant examination successfully save!")); 
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
	
	public function applicant_information()
	{
		$fetch_data = $this->input->post(NULL, TRUE);

		$temp 				= 	'jpg';
		$target_seminar		=	"../document/seminar_certificate/";
		$target_employment	=	"../document/employment_certificate/";
		
		$errors     = array();
		$maxsize    = 2097152;
		$acceptable = array(
			'image/jpeg',
			'image/jpg',
			'image/png'
		);
		
		$explode_Id = explode("|",$this->initial_model->get_appId());
		$fetch_data['appId'] = $explode_Id[1];
		$fetch_data['id'] = $explode_Id[0];
		
		$this->db->trans_start();
		$this->initial_model->save_applicant_info($fetch_data);
		$this->initial_model->save_applicant_character_ref($fetch_data);
		
		// saving application seminar/training/eligibility
		for($i= 0 ; $i< count($fetch_data['seminar_name']);$i++)
		{
			if(isset($_FILES['seminar_certificate']['name'][$i])) 
			{
				$filesize 		= 	$_FILES['seminar_certificate']['size'][$i];
				$filename		=	$_FILES['seminar_certificate']['name'][$i];
				$filetype		=	$_FILES['seminar_certificate']['type'][$i];
				$seminar_cert 	= 	"seminar_certificate_".$i."_".$fetch_data['appId']."_".date("Y-m-d").".".$temp;
				$location 		= 	$target_seminar.$seminar_cert;
				
				$fetch_data['location'] = $location;
				
				if($_FILES['seminar_certificate']['size'][$i] >= $maxsize) 
				{
					echo 'File too large. File must be less than 2 megabytes.';
				}
				else
				{
					if((!in_array($filetype, $acceptable)) && (!empty($filetype))) 
					{
						echo 'File is invalid file type. Only PDF, JPG, GIF and PNG types are accepted.';
					}
					else
					{
						if(move_uploaded_file($_FILES["seminar_certificate"]["tmp_name"][$i], $target_seminar.$seminar_cert))
						{
							$this->initial_model->save_applicant_seminar_training_eligibility($fetch_data, $i);
						}
					}	
				}
			}
			else
			{
				$fetch_data['location'] = "";
				$this->initial_model->save_applicant_seminar_training_eligibility($fetch_data, $i);
			}
		}
		
		// saving application history
		for($z= 0; $z< count($fetch_data['company_name']);$z++)
		{
			if(isset($_FILES['certificate']['name'][$z])) 
			{
				$f_size 		= 	$_FILES['certificate']['size'][$z];
				$f_name			=	$_FILES['certificate']['name'][$z];
				$f_type			=	$_FILES['certificate']['type'][$z];
				$f_cert 		= 	"certificate".$z."_".$fetch_data['appId']."_".date("Y-m-d").".".$temp;
				$location 		= 	$target_employment.$f_cert;
				
				$fetch_data['location'] = $location;
				
				if($f_size >= $maxsize ) 
				{
					echo 'test File too large. File must be less than 2 megabytes.';
				}
				else
				{
					if((!in_array($f_type, $acceptable)) && (!empty($f_type))) 
					{
						echo 'File is invalid file type. Only PDF, JPG, GIF and PNG types are accepted.';
					}
					else
					{
						if(move_uploaded_file($_FILES["certificate"]["tmp_name"][$z], $target_employment.$f_cert))
						{
							echo $this->initial_model->save_applicant_employment_history($fetch_data, $z);
						}
					}	
				}
			}	
		}
		
		$this->initial_model->update_applicant_status($fetch_data);
		$this->db->trans_complete();

		if ($this->db->trans_status() === TRUE)
		{
			echo json_encode(array('status'=> 1, 'message' => "Applicant successfully recorded. You can proceed and set-up examination!"));	
		}
		else
		{
			echo json_encode(array('status'=> 0, 'message' => "Error Found!"));	
		}
	}
	
	public function upload_initial() 
	{
		$fetch_data = $this->input->post(NULL, TRUE);
		
		$files 		= 	array('resume', 'application', 'transcript');
		$target_dir	=	"../document/initial_requirements/";
		
		$check_initial = $this->initial_model->insert_initial_applicant_info($fetch_data);
		
		if($fetch_data['updt_or_appnd'] == 'INSERT')
		{
			$appcode = $check_initial;
		}
		else
		{
			$appcode = $check_initial['app_code'];
		}
		
		$transcript_flag = 0;
		$resume_flag = 0;
		$application_flag = 0;
		
		foreach($files as $file => $value) 
		{
			$temp = 'jpg'; 
			
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
						
						if($filesize >= $maxsize || $filesize == 0) 
						{
							echo 'Resume file too large. File must be less than 2 megabytes.';
						}
						else
						{
							if((!in_array($filetype, $acceptable)) && (!empty($filetype))) 
							{
								echo 'Resume file is invalid file type. Only PDF, JPG, GIF and PNG types are accepted.';
							}
							else
							{
								if(move_uploaded_file($_FILES["resume"]["tmp_name"][$i], $target_dir."resume/".$resume))
								{
									$resume_upload = $this->initial_model->insert_uploaded_info($value,$location,$appcode);
									$resume_flag = 1;
								}
							}	
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
						
						if($filesize >= $maxsize || $filesize == 0) 
						{
							echo 'Application file too large. File must be less than 2 megabytes.';
						}
						else
						{
							if((!in_array($filetype, $acceptable)) && (!empty($filetype))) 
							{
								echo 'Application file is invalid file type. Only PDF, JPG, GIF and PNG types are accepted.';
							}
							else
							{
								if(move_uploaded_file($_FILES["application"]["tmp_name"][$x], $target_dir."application_letter/".$application))
								{
									$application_upload = $this->initial_model->insert_uploaded_info($value,$location,$appcode);
									$application_flag = 1;
								}
							}
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
						
						if($filesize >= $maxsize || $filesize == 0) 
						{
							echo 'TOR file too large. File must be less than 2 megabytes.';
						}
						else
						{
							if((!in_array($filetype, $acceptable)) && (!empty($filetype))) 
							{
								echo 'TOR file is invalid file type. Only PDF, JPG, GIF and PNG types are accepted.';
							}
							else
							{
								if(move_uploaded_file($_FILES["transcript"]["tmp_name"][$n], $target_dir."tor/".$transcript))
								{
									$transcript_upload = $this->initial_model->insert_uploaded_info($value,$location,$appcode);
									$transcript_flag = 1;
								}
							}	
						}	
					}
				}
				
			}	
		}
		
		if($transcript_flag == 1 && $resume_flag == 1 && $application_flag == 1)
		{
			echo json_encode(array('status'=> 1, 'message' => "Resume/Transcript/Application successfully uploaded.."));
		}
		else
		{
			echo json_encode(array('status'=> 0, 'message' => "Error file not uploaded.."));
		}
	}
	
	public function append_character_ref() {
		
		$data['request'] = 'append_character_ref';
		$this->load->view('body/recruitment/function_query', $data);
	}
	
}