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
    }
    public function index()
    {
        redirect('http://' . $_SERVER['SERVER_ADDR'] . ":" . $_SERVER['SERVER_PORT'] . '/hrms/promo-nesco/placement/');
    }
}
