<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Page extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('nativesession');

        if (!isset($_SESSION['emp_id'])) {

            redirect('http://' . $_SERVER['SERVER_ADDR'] . ":" . $_SERVER['SERVER_PORT'] . '/hrms/nesco');
        }
		$this->load->model('recruitment/page_model');
		$this->load->model('recruitment/initial_model');
		
	}
	
	public function menu($menu = 'dashboard', $page = 'dashboard', $empId = '')
    {
        if (!file_exists(APPPATH . "views/body/recruitment/$menu/$page.php")) {
            // Whoops, we don't have a page for that!
            show_404();
		}
		
		$page = html_escape($page);
        $user_id = $this->nativesession->get('emp_id'); 
		
		$data['title']  = $menu;
        $data['page']  = $page;
		
		$user_id = $this->nativesession->get('emp_id');
		$data['user']  = $this->page_model->user_info($user_id);
	
		$this->load->view('template/header', $data);
		$this->load->view('template/recruitment/menu', $data);
		$this->load->view("body/recruitment/$menu/$page");
		
	
        $this->load->view('template/script');
        $this->load->view("body/recruitment/$menu/" . $menu . '_js', $data);
	}
	
	
	
	
}
