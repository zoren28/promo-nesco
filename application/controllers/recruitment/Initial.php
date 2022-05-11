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
		$fetch_data 	= $this->input->post(NULL, TRUE);
		
		$data['check_record'] 	= $this->initial_model->record_applicant_info($fetch_data);
		
		$data['request'] = "applicant_record";
		
		$this->load->view('body/recruitment/function_query',$data);
	}
	
	public function applicant_information()
	{
		$fetch_data = $this->input->post(NULL, TRUE);
		print_r($fetch_data);
		//echo json_encode(array('status'=> 1, 'message' => "Resume/Transcript/Application successfully uploaded.."));
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
	
}