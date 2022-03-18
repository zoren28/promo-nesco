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

    public function termination_of_contract_xls()
    {
        $data['fetch'] = $this->input->get(NULL, TRUE);
        $data['request'] = "termination_of_contract_xls";

        $this->load->view('body/placement/modal_response', $data);
    }

    public function termination_of_contract_pdf()
    {
        $fetch = $this->input->get(NULL, TRUE);

        // create new PDF document
        $this->load->library('Pdf');
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Zoren Ormido');
        $pdf->SetTitle('Termination Report');
        $pdf->SetSubject('End of Contract');
        $pdf->SetKeywords('termination, end of contract, merchandiser, promodiser, diser');

        // remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(15, 15, 15);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, 15);

        // set some language-dependent strings (optional)
        if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
            require_once(dirname(__FILE__) . '/lang/eng.php');
            $pdf->setLanguageArray($l);
        }

        // set font
        $pdf->SetFont('times', 'B', 12);

        // add a page
        $pdf->AddPage('P', 'Letter');

        $result = $this->report_model->naend_of_contract_list($fetch);
        foreach ($result as $row) {

            $bUs = $this->dashboard_model->businessUnit_list();
            foreach ($bUs as $bu) {

                $hasBU = $this->dashboard_model->promo_has_bu($row->emp_id, $bu->bunit_field);
                if ($hasBU > 0) {

                    // Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=0, $link='', $stretch=0, $ignore_min_height=false, $calign='T', $valign='M')
                    $pdf->Cell(0, 0, 'NOTICE OF TERMINATION', 0, 0, 'C', 0, '', 0);
                    $pdf->SetFont('times', '', 12);

                    $pdf->Ln(5);
                    $pdf->Cell(0, 0, 'For Promodiser - Merchandiser', 0, 0, 'C');

                    $pdf->Ln(5);
                    $pdf->Cell(0, 0, 'Assigned at ' . $bu->bunit_name, 0, 0, 'C');

                    $pdf->Ln(10);
                    $pdf->SetFont('times', 'B', 12);
                    $pdf->SetX(145);
                    $pdf->Cell(0, 0, 'Date:');
                    $pdf->SetX(157);
                    $pdf->SetFont('times', 'BU', 12);
                    $pdf->Cell(0, 0, date('F d, Y'));

                    $pdf->Ln(10);
                    $pdf->SetFont('times', 'B', 12);
                    $pdf->Cell(0, 8, 'To', 0, 0, 'L');
                    $pdf->SetX(53);
                    $pdf->Cell(0, 8, ':');
                    $pdf->SetFont('times', 'BU', 12);
                    $pdf->SetX(58);
                    $pdf->Cell(0, 8, ucwords(strtolower($row->name)));

                    $pdf->Ln(5);
                    $pdf->SetFont('times', 'B', 12);
                    $pdf->Cell(0, 8, 'Company/Agency');
                    $pdf->SetX(53);
                    $pdf->Cell(0, 8, ':');
                    $pdf->SetX(58);
                    $pdf->Cell(0, 8, $row->promo_department . ' - ' . $row->promo_company);

                    $pdf->Ln(10);
                    $pdf->SetFont('times', '', 12);
                    $pdf->SetX(50);
                    $pdf->Cell(0, 8, 'Please be reminded that according to the Introductory Letter we received from your', 0, 0, 'J');
                    $pdf->Ln(5);
                    $pdf->Cell(0, 8, 'company/agency, your assignment on this establishment will expire on');
                    $pdf->Ln(5);
                    $pdf->SetFont('times', 'BU', 12);
                    $pdf->Cell(15, 8, date('l', strtotime($row->eocdate)) . ' ' . date('F d, Y', strtotime($row->eocdate)));

                    $pdf->Ln(10);
                    $pdf->SetFont('times', '', 12);
                    $pdf->SetX(50);
                    $pdf->Cell(0, 8, 'In connection with this you are advised to yield all company properties under your', 0, 0, 'J');
                    $pdf->Ln(6);
                    // MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0)    
                    $pdf->MultiCell(0, 12, 'care and seek clearance before you leave the company premises of ' . $bu->bunit_name . ' at the close of business hours on such day.', 0, 'L');

                    $pdf->Ln(4);
                    $pdf->SetX(50);
                    $pdf->Cell(0, 8, 'Thank you and good luck!');

                    $pdf->Ln(18);
                    $pdf->SetFont('times', 'B', 12);
                    $pdf->Cell(63, 8, 'MS. MARIA NORA A. PAHANG', 0);
                    $pdf->Ln(6);
                    $pdf->Cell(63, 8, 'HRD Manager', 0, 0, 'C');

                    $pdf->Ln(30);
                }
            }
        }

        //Close and output PDF document
        $pdf->Output('termination.pdf', 'I');
    }
}
