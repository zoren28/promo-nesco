<?php

class Logout extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('nativesession');

        if (!isset($_SESSION['emp_id'])) {

            redirect('http://' . $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . '/hrms/nesco');
        }
    }

    public function index()
    {
        $this->nativesession->sess_destroy();

        redirect('http://' . $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . '/hrms/nesco');
    }
}
