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

        $this->load->model('placement/page_model');
        $this->load->model('placement/dashboard_model');
        $this->load->model('placement/employee_model');
    }

    public function menu($menu = 'dashboard', $page = 'dashboard', $empId = '')
    {

        if (!file_exists(APPPATH . "views/body/placement/$menu/$page.php")) {
            // Whoops, we don't have a page for that!
            show_404();
        }

        $page = html_escape($page);
        $user_id = $this->nativesession->get('emp_id');

        $data['title']  = $menu;
        $data['page']  = $page;

        $activeBU = $this->dashboard_model->businessUnit_list();
        $data['activeBUs']  = $activeBU;

        if (($menu == "employee" && $page == "profile") || ($menu == "contract" && $page == "renewal") || ($menu == "blacklisted" && $page == "add-blacklisted") || ($menu == "termination" && $page == "resignation_termination")) {

            $data['empId']  = $empId;
        } else if ($menu == "employee" && $page == "masterfile") {

            $data['filters'] = $empId;
        } else {

            $data['searchThis'] = $empId;
        }

        $data['user']  = $this->page_model->user_info($user_id);
        $data['pieColor'] = array('#3c8dbc', '#0f4661', '#6db00a', '#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de', '#6db00a', '#6d0015', '#6648ae', '#cc8754', '#7a0a01', '#d42cc2');

        $this->load->view('template/header', $data);
        $this->load->view('template/menu', $data);
        $this->load->view("body/placement/$menu/$page");

        if ($menu == "employee" && $page == "profile") {

            $this->load->view('body/placement/employees_modal');
        }
        $this->load->view('template/script');
        $this->load->view("body/placement/$menu/" . $menu . '_js', $data);
    }
}