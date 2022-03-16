<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Report extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('nativesession');

        if (!isset($_SESSION['emp_id'])) {

            redirect('http://' . $_SERVER['SERVER_ADDR'] . ":" . $_SERVER['SERVER_PORT'] . '/hrms/nesco');
        }

        $this->load->model('placement/employee_model');
        $this->load->model('placement/report_model');
        $this->load->model('placement/dashboard_model');
    }

    public function view_stat_BU()
    {
        $data['fetch'] = $this->input->get(NULL, TRUE);
        $data['request'] = "view_stat_BU";

        $this->load->view('body/placement/modal_response', $data);
    }

    public function load_stat_BU()
    {
        $field = $this->input->get('field', TRUE);
        $result = $this->report_model->load_stat_BU($field);

        $no = 1;
        $data = array();
        foreach ($result as $row) {

            $sub_array = array();
            $sub_array[] = $no++ . ".";
            $sub_array[] = '<a href="' . base_url('placement/page/menu/employee/profile/' . $row->emp_id) . '" target="_blank">' . ucwords(strtolower($row->name)) . '</a>';
            $sub_array[] = $row->promo_department;
            $sub_array[] = $row->position;
            $sub_array[] = $row->promo_type;
            $sub_array[] = $row->type;
            $data[] = $sub_array;
        }

        echo json_encode(array("data" => $data));
    }

    public function stat_BU_xls($field)
    {
        $data['statistics'] = $this->report_model->load_stat_BU($field);
        $data['request'] = "statistics_xls";

        $this->load->view('body/placement/modal_response', $data);
    }

    public function view_stat_dept()
    {
        $data['fetch'] = $this->input->get(NULL, TRUE);
        $data['request'] = "view_stat_dept";

        $this->load->view('body/placement/modal_response', $data);
    }

    public function load_stat_dept()
    {
        $fetch_data = $this->input->get(NULL, TRUE);
        $result = $this->report_model->load_stat_dept($fetch_data);

        $no = 1;
        $data = array();
        foreach ($result as $row) {

            $sub_array = array();
            $sub_array[] = $no++ . ".";
            $sub_array[] = '<a href="' . base_url('placement/page/menu/employee/profile/' . $row->emp_id) . '" target="_blank">' . ucwords(strtolower($row->name)) . '</a>';
            $sub_array[] = $row->promo_department;
            $sub_array[] = $row->position;
            $sub_array[] = $row->promo_type;
            $sub_array[] = $row->type;
            $data[] = $sub_array;
        }

        echo json_encode(array("data" => $data));
    }

    public function stat_dept_xls($field, $dept)
    {
        $fetch_data = array('field' => $field, 'dept' => $dept);
        $data['statistics'] = $this->report_model->load_stat_dept($fetch_data);
        $data['request'] = "statistics_xls";

        $this->load->view('body/placement/modal_response', $data);
    }

    public function select_company_under_agency()
    {
        $agency_code = $this->input->get('agency_code', TRUE);
        $companies = $this->employee_model->company_list_under_agency($agency_code);
        echo '<option value=""> --Select Company-- </option>';
        foreach ($companies as $company) {

            $supplier = $this->employee_model->getcompanyCodeBycompanyName($company->company_name);
            if (!empty($supplier)) {
?>
                <option value="<?= $supplier->pc_code ?>"><?= $company->company_name ?></option>
<?php
            }
        }
    }

    public function username_xls()
    {
        $fetch = $this->input->get(NULL, TRUE);

        $data['usernames'] = $this->report_model->username_list($fetch);
        $data['request'] = "username_xls";

        $this->load->view('body/placement/modal_response', $data);
    }

    public function qbe_report()
    {
        $data['fetch'] = $this->input->get(NULL, TRUE);
        $data['request'] = "qbe_report";

        $this->load->view('body/placement/modal_response', $data);
    }
}
