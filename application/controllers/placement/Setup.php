<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Setup extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('nativesession');

        if (!isset($_SESSION['emp_id'])) {

            redirect('http://' . $_SERVER['SERVER_ADDR'] . ":" . $_SERVER['SERVER_PORT'] . '/hrms/nesco');
        }

        $this->load->model('placement/setup_model');
        $this->load->model('placement/employee_model');
        $this->load->model('placement/dashboard_model');
    }

    public function add_department_form()
    {
        $business_units = $this->dashboard_model->businessUnit_list();
        $data = [
            'request' => 'add_department_form',
            'business_units' => $business_units
        ];
        $this->load->view('body/placement/modal_response', $data);
    }

    public function create_department()
    {
        $this->load->library('form_validation');
        $request = $this->input->post(NULL, TRUE);

        $config = [
            [
                'field' => 'bunit_id',
                'label' => 'Business Unit',
                'rules' => 'required'
            ],
            [
                'field' => 'dept_name',
                'label' => 'Department',
                'rules' => 'required'
            ]
        ];
        $this->form_validation->set_rules($config);
        if ($this->form_validation->run() === FALSE) {

            $errors = $this->form_validation->error_array();
            echo json_encode([
                'status' => 406,
                'errors' => $errors
            ]);
        } else {

            $exist = $this->setup_model->is_department_exist($request);
            if ($exist) {

                echo json_encode([
                    'status' => 406,
                    'errors' => [
                        'dept_name' => 'Department already exist'
                    ]
                ]);
                die();
            } else {

                $request['dept_name'] = strtoupper($request['dept_name']);
                $this->setup_model->create_department($request);
            }

            echo json_encode([
                'status' => 200,
                'message' => 'Department successfully saved'
            ]);
        }
    }

    public function company_list()
    {
        $companies = $this->setup_model->company_list('');
        $data = array();
        foreach ($companies as $company) {

            $id = $company->pc_code;
            if (isset($_SERVER['SERVER_PORT'])) {
                $base_url = 'http://' . $_SERVER['SERVER_ADDR'] . ":" . $_SERVER['SERVER_PORT'];
            } else {

                $base_url = 'http://' . $_SERVER['SERVER_ADDR'];
            }

            $action =  '<a href="javascript:void(0);" id="update_' . $id . '" title="click to update company" class="action"><i class="glyphicon glyphicon-pencil"></i> &nbsp;</a>';
            if ($company->status == '1') {

                $action .= '<a href="javascript:void(0)" id="deactivate_' . $id . '" title="click to deactivate company" class="action"><img src="' . $base_url . '/hrms/images/icons/icon-close-circled-20.png" height="17" width="17"></a>';
            } else {

                $action .= '<a href="javascript:void(0)" id="activate_' . $id . '" title="click to activate company" class="action"><img src="' . $base_url . '/hrms/images/icons/icn_active.gif" height="17" width="17"></a>';
            }

            if ($_SESSION['emp_id'] == "06359-2013") {
                $action .= ' <a href="javascript:void(0);" id="delete_' . $id . '" title="click to delete company" class="action"><i class="glyphicon glyphicon-trash text-red"></i></a>';
            }

            $sub_array = array();
            $sub_array[] = $company->pc_name;
            $sub_array[] = $action;
            $data[] = $sub_array;
        }

        echo json_encode(array("data" => $data));
    }

    public function delete_company()
    {
        $pc_code = $this->input->post('id', TRUE);
        $delete = $this->setup_model->delete_company($pc_code);
        if ($delete) {
            echo json_encode(array('status' => 'success'));
        } else {
            echo json_encode(array('status' => 'failure'));
        }
    }

    public function update_company_status()
    {
        $data = $this->input->post(NULL, TRUE);
        $update = $this->setup_model->update_company_status($data);
        if ($update) {
            echo json_encode(array('status' => 'success'));
        } else {
            echo json_encode(array('status' => 'failure'));
        }
    }

    public function show_company()
    {
        $pc_code = $this->input->get('id', TRUE);

        $data['company'] = $this->setup_model->show_company($pc_code);
        $data['request'] = 'show_company';
        $this->load->view('body/placement/modal_response', $data);
    }

    public function update_company()
    {
        $data = $this->input->post(NULL, TRUE);

        $exist = $this->setup_model->check_company($data['company'], $data['id']);
        if ($exist) {
            echo json_encode(array('status' => 'exist'));
        } else {
            $update = $this->setup_model->update_company($data);
            if ($update) {
                echo json_encode(array('status' => 'success'));
            } else {
                echo json_encode(array('status' => 'failure'));
            }
        }
    }

    public function store_company()
    {
        $company = $this->input->post('company', TRUE);

        $exist = $this->setup_model->check_company($company, '');
        if ($exist) {
            echo json_encode(array('status' => 'exist'));
        } else {
            $store = $this->setup_model->store_company($company);
            if ($store) {
                echo json_encode(array('status' => 'success'));
            } else {
                echo json_encode(array('status' => 'failure'));
            }
        }
    }

    public function agency_list()
    {
        $agencies = $this->setup_model->agency_list('');
        $data = array();
        foreach ($agencies as $agency) {

            $id = $agency->agency_code;
            if (isset($_SERVER['SERVER_PORT'])) {
                $base_url = 'http://' . $_SERVER['SERVER_ADDR'] . ":" . $_SERVER['SERVER_PORT'];
            } else {

                $base_url = 'http://' . $_SERVER['SERVER_ADDR'];
            }

            $action =  '<a href="javascript:void(0);" id="update_' . $id . '" title="click to update agency" class="action"><i class="glyphicon glyphicon-pencil"></i> &nbsp;</a>';
            if ($agency->status == '1') {

                $action .= '<a href="javascript:void(0)" id="deactivate_' . $id . '" title="click to deactivate agency" class="action"><img src="' . $base_url . '/hrms/images/icons/icon-close-circled-20.png" height="17" width="17"></a>';
            } else {

                $action .= '<a href="javascript:void(0)" id="activate_' . $id . '" title="click to activate agency" class="action"><img src="' . $base_url . '/hrms/images/icons/icn_active.gif" height="17" width="17"></a>';
            }

            if ($_SESSION['emp_id'] == "06359-2013") {
                $action .= ' <a href="javascript:void(0);" id="delete_' . $id . '" title="click to delete agency" class="action"><i class="glyphicon glyphicon-trash text-red"></i></a>';
            }

            $sub_array = array();
            $sub_array[] = $agency->agency_name;
            $sub_array[] = $action;
            $data[] = $sub_array;
        }

        echo json_encode(array("data" => $data));
    }

    public function delete_agency()
    {
        $id = $this->input->post('agency_code', TRUE);
        $delete = $this->setup_model->delete_agency($id);
        if ($delete) {
            echo json_encode(array('status' => 'success'));
        } else {
            echo json_encode(array('status' => 'failure'));
        }
    }

    public function agency_status()
    {
        $data = $this->input->post(NULL, TRUE);
        $update = $this->setup_model->update_agency_status($data);
        if ($update) {
            echo json_encode(array('status' => 'success'));
        } else {
            echo json_encode(array('status' => 'failure'));
        }
    }

    public function show_agency()
    {
        $agency_code = $this->input->get('agency_code', TRUE);

        $data['agency'] = $this->setup_model->show_agency($agency_code);
        $data['request'] = 'show_agency';
        $this->load->view('body/placement/modal_response', $data);
    }

    public function update_agency()
    {
        $data = $this->input->post(NULL, TRUE);

        $exist = $this->setup_model->check_agency($data['agency'], $data['agency_code']);
        if ($exist) {
            echo json_encode(array('status' => 'exist'));
        } else {
            $update = $this->setup_model->update_agency($data);
            if ($update) {
                echo json_encode(array('status' => 'success'));
            } else {
                echo json_encode(array('status' => 'failure'));
            }
        }
    }

    public function store_agency()
    {
        $agency = $this->input->post('agency', TRUE);

        $exist = $this->setup_model->check_agency($agency, '');
        if ($exist) {
            echo json_encode(array('status' => 'exist'));
        } else {
            $store = $this->setup_model->store_agency($agency);
            if ($store) {
                echo json_encode(array('status' => 'success'));
            } else {
                echo json_encode(array('status' => 'failure'));
            }
        }
    }

    public function companies_for_agency()
    {
        $agencies = $this->setup_model->agency_list(1);
        $data = array();
        foreach ($agencies as $agency) {

            $companies = $this->setup_model->company_under_agency($agency->agency_code);
            foreach ($companies as $company) {

                $sub_array = array();
                $sub_array[] = $agency->agency_name;
                $sub_array[] = $company->company_name;
                $sub_array[] = '<i id="delete_' . $company->company_code . '" title="Untag Company" class="fa fa-lg fa-trash text-danger action"></i>';
                $data[] = $sub_array;
            }
        }
        echo json_encode(array("data" => $data));
    }

    public function untag_company_agency()
    {
        $company_code = $this->input->post('company_code', TRUE);
        $delete = $this->setup_model->untag_company_agency($company_code);
        if ($delete) {
            echo json_encode(array('status' => 'success'));
        } else {
            echo json_encode(array('status' => 'failure'));
        }
    }

    public function choose_agency()
    {
        $data['agencies'] = $this->setup_model->agency_list(1);
        $data['request'] = 'choose_agency';

        $this->load->view('body/placement/modal_response', $data);
    }

    function tag_company_agency()
    {
        $data['agency_code'] = $this->input->get('agency_code', TRUE);
        $data['request'] = 'tag_company_agency';

        $this->load->view('body/placement/modal_response', $data);
    }

    public function store_promo_locate_company()
    {
        $data = $this->input->post(NULL, TRUE);

        $this->setup_model->delete_promo_locate_company($data['agency']);
        foreach ($data['companies'] as $company) {
            $this->setup_model->store_promo_locate_company($data['agency'], $company);
        }

        echo json_encode(array('status' => 'success'));
    }

    public function product_list()
    {
        $products = $this->setup_model->product_list('');
        $data = array();
        foreach ($products as $product) {

            if (isset($_SERVER['SERVER_PORT'])) {
                $base_url = 'http://' . $_SERVER['SERVER_ADDR'] . ":" . $_SERVER['SERVER_PORT'];
            } else {

                $base_url = 'http://' . $_SERVER['SERVER_ADDR'];
            }

            $action =  '<a href="javascript:void(0);" id="update_' . $product->id . '" title="click to update product" class="action"><i class="glyphicon glyphicon-pencil"></i> &nbsp;</a>';
            if ($product->status == '1') {

                $action .= '<a href="javascript:void(0)" id="deactivate_' . $product->id . '" title="click to deactivate product" class="action"><img src="' . $base_url . '/hrms/images/icons/icon-close-circled-20.png" height="17" width="17"></a>';
            } else {

                $action .= '<a href="javascript:void(0)" id="activate_' . $product->id . '" title="click to activate product" class="action"><img src="' . $base_url . '/hrms/images/icons/icn_active.gif" height="17" width="17"></a>';
            }

            if ($_SESSION['emp_id'] == "06359-2013") {
                $action .= ' <a href="javascript:void(0);" id="delete_' . $product->id . '" title="click to delete product" class="action"><i class="glyphicon glyphicon-trash text-red"></i></a>';
            }

            $sub_array = array();
            $sub_array[] = $product->product;
            $sub_array[] = $action;
            $data[] = $sub_array;
        }
        echo json_encode(array("data" => $data));
    }

    public function delete_product()
    {
        $id = $this->input->post('id', TRUE);
        $delete = $this->setup_model->delete_product($id);
        if ($delete) {
            echo json_encode(array('status' => 'success'));
        } else {
            echo json_encode(array('status' => 'failure'));
        }
    }

    public function product_status()
    {
        $data = $this->input->post(NULL, TRUE);

        $update = $this->setup_model->update_product_status($data);
        if ($update) {
            echo json_encode(array('status' => 'success'));
        } else {
            echo json_encode(array('status' => 'failure'));
        }
    }

    public function show_product()
    {
        $id = $this->input->get('id', TRUE);
        $data['product'] = $this->setup_model->show_product($id);
        $data['request'] = 'show_product';

        $this->load->view('body/placement/modal_response', $data);
    }

    public function update_product()
    {
        $data = $this->input->post(NULL, TRUE);

        $exist = $this->setup_model->check_product($data['company'], $data['id']);
        if ($exist) {
            echo json_encode(array('status' => 'exist'));
        } else {
            $update = $this->setup_model->update_product($data);
            if ($update) {
                echo json_encode(array('status' => 'success'));
            } else {
                echo json_encode(array('status' => 'failure'));
            }
        }
    }

    public function store_product()
    {
        $product = $this->input->post('product', TRUE);

        $exist = $this->setup_model->check_product($product, '');
        if ($exist) {
            echo json_encode(array('status' => 'exist'));
        } else {
            $store = $this->setup_model->store_product($product);
            if ($store) {
                echo json_encode(array('status' => 'success'));
            } else {
                echo json_encode(array('status' => 'failure'));
            }
        }
    }

    public function products_under_company()
    {
        $products = $this->setup_model->products_under_company();
        $data = array();
        foreach ($products as $product) {

            $sub_array = array();
            $sub_array[] = $product->company;
            $sub_array[] = $product->product;
            $sub_array[] = '<i id="delete_' . $product->id . '" title="Untag Product Under Company" class="fa fa-lg fa-trash text-danger action"></i>';
            $data[] = $sub_array;
        }
        echo json_encode(array("data" => $data));
    }

    public function untag_product_company()
    {
        $id = $this->input->post('id', TRUE);

        $delete = $this->setup_model->untag_product_company($id);
        if ($delete) {
            echo json_encode(array('status' => 'success'));
        } else {
            echo json_encode(array('status' => 'failure'));
        }
    }

    public function choose_company()
    {
        $data['companies'] = $this->setup_model->company_list(1);
        $data['request'] = 'choose_company';

        $this->load->view('body/placement/modal_response', $data);
    }

    public function tag_product_company()
    {
        $data['company'] = $this->input->get('company', TRUE);
        $data['request'] = 'tag_product_company';

        $this->load->view('body/placement/modal_response', $data);
    }

    public function store_promo_company_products()
    {
        $data = $this->input->post(NULL, TRUE);

        $this->setup_model->delete_promo_company_products($data['company']);
        foreach ($data['products'] as $product) {
            $this->setup_model->store_promo_company_products($data['company'], $product);
        }

        echo json_encode(array('status' => 'success'));
    }

    public function find_active_supervisor()
    {
        $val = "";
        $str = $this->input->post('str', TRUE);

        $query = $this->setup_model->find_active_supervisor($str);
        if ($query->num_rows() > 0) {

            $info = $query->result_array();
            foreach ($info as $emp) {

                $empId = $emp['emp_id'];
                $name  = ucwords(strtolower($emp['name']));

                if ($val != $empId) {
?>
                    <a href="javascript:void(0);" onclick="getEmpId('<?= $emp['emp_id'] . ' * ' . $emp['name']  ?>')"><?= $emp['emp_id'] . ' * ' . $emp['name']  ?></a></br>
<?php
                } else {
                    echo 'No Result Found';
                }
            }
        } else {

            echo 'No Result Found';
        }
    }

    public function supervisor_details()
    {
        $emp_id = $this->input->get('emp_id', TRUE);
        $data['supervisor'] = $this->setup_model->show_supervisor($emp_id);
        $data['request'] = 'supervisor_details';

        $this->load->view('body/placement/modal_response', $data);
    }

    public function list_of_subordinates()
    {
        $emp_id = $this->input->get('emp_id', TRUE);
        $data['subordinates'] = $this->setup_model->list_of_subordinates($emp_id);
        $data['request'] = 'list_of_subordinates';

        $this->load->view('body/placement/modal_response', $data);
    }

    public function remove_subordinates()
    {
        $subordinates = $this->input->post('subordinates', TRUE);
        $delete = $this->setup_model->remove_subordinates($subordinates);
        if ($delete) {
            echo json_encode(array('status' => 'success'));
        } else {
            echo json_encode(array('status' => 'failure'));
        }
    }

    public function employee_list()
    {
        $request = $this->input->post(NULL, TRUE);
        $data['employees'] = $this->setup_model->employee_list($request);
        $data['rater'] = $request['rater'];
        $data['request'] = 'employee_list';

        $this->load->view('body/placement/modal_response', $data);
    }

    public function store_subordinates()
    {
        $data = $this->input->post(NULL, TRUE);
        $store = $this->setup_model->store_leveling_subordinates($data);
        if ($store) {
            echo json_encode(array('status' => 'success'));
        } else {
            echo json_encode(array('status' => 'failure'));
        }
    }

    public function business_unit_list()
    {
        $business_units = $this->setup_model->businessUnit_list();
        $data = array();
        foreach ($business_units as $bU) {

            if (isset($_SERVER['SERVER_PORT'])) {
                $base_url = 'http://' . $_SERVER['SERVER_ADDR'] . ":" . $_SERVER['SERVER_PORT'];
            } else {

                $base_url = 'http://' . $_SERVER['SERVER_ADDR'];
            }

            $action =  '<a href="javascript:void(0);" id="edit_' . $bU->bunit_id . '" title="click to update business unit" class="action"><i class="glyphicon glyphicon-pencil"></i></a>';
            $options = ['active', 'inactive'];

            $status = '
                <select id="status-' . $bU->bunit_id . '" class="status">';
            foreach ($options as $option) {
                if ($option == $bU->status) {
                    $status .= '<option value="' . $option . '" selected>' . $option . '</option>';
                } else {
                    $status .= '<option value="' . $option . '">' . $option . '</option>';
                }
            }
            $status .= '
                </select>
            ';

            $tk_status = '
                <select id="tk_status-' . $bU->bunit_id . '" class="status">';
            foreach ($options as $option) {
                if ($option == $bU->tk_status) {
                    $tk_status .= '<option value="' . $option . '" selected>' . $option . '</option>';
                } else {
                    $tk_status .= '<option value="' . $option . '">' . $option . '</option>';
                }
            }
            $tk_status .= '
                </select>
            ';

            $appraisal_status = '
                <select id="appraisal_status-' . $bU->bunit_id . '" class="status">';
            foreach ($options as $option) {
                if ($option == $bU->appraisal_status) {
                    $appraisal_status .= '<option value="' . $option . '" selected>' . $option . '</option>';
                } else {
                    $appraisal_status .= '<option value="' . $option . '">' . $option . '</option>';
                }
            }
            $appraisal_status .= '
                </select>
            ';

            $sub_array = array();
            $sub_array[] = $bU->bunit_name;
            $sub_array[] = $bU->bunit_acronym;
            $sub_array[] = $bU->bunit_field;
            $sub_array[] = $status;
            $sub_array[] = $tk_status;
            $sub_array[] = $appraisal_status;
            $sub_array[] = $bU->hrd_location;
            $sub_array[] = $action;
            $data[] = $sub_array;
        }
        echo json_encode(array("data" => $data));
    }

    public function update_business_unit_status()
    {
        $data = $this->input->post(NULL, TRUE);
        $update = $this->setup_model->update_business_unit_status($data);
        if ($update) {
            echo json_encode(array('status' => 'success'));
        } else {
            echo json_encode(array('status' => 'failure'));
        }
    }

    public function show_business_unit($bunit_id)
    {
        $bU = $this->setup_model->show_business_unit($bunit_id);
        $data['bU'] = $bU;
        $data['request'] = 'show_business_unit';

        $this->load->view('body/placement/modal_response', $data);
    }

    public function add_businessUnit_form()
    {
        $data['request'] = 'add_business_unit';
        $this->load->view('body/placement/modal_response', $data);
    }

    public function store_promo_business_unit()
    {
        $data = $this->input->post(NULL, TRUE);
        $fields_values = array(
            'bunit_epascode' => 'epascode',
            'bunit_contract' => 'contract',
            'bunit_permit' => 'permit',
            'bunit_clearance' => 'clearance',
            'bunit_intro' => 'intro',
            'bunit_dutySched' => 'sched',
            'bunit_dutyDays' => 'days',
            'bunit_specialSched' => 'special_sched',
            'bunit_specialDays' => 'special_days'
        );

        // add business unit details in locate_promo_business_unit
        $store_bu = $this->setup_model->store_promo_business_unit($data, $fields_values);
        if ($store_bu) {

            // add fields in promo_record table
            $store_promo = $this->setup_model->add_fields_promo($data['bunit_field']);

            // add fields in promo_history_record table
            $store_promo_hist = $this->setup_model->add_fields_promo_hist($data['bunit_field']);

            if ($store_promo && $store_promo_hist) {
                echo json_encode(array('status' => 'success'));
            } else {
                echo json_encode(array('status' => 'failure'));
            }
        } else {
            echo json_encode(array('status' => 'failure'));
        }
    }

    public function update_promo_business_unit()
    {
        $data = $this->input->post(NULL, TRUE);

        $update = $this->setup_model->update_promo_business_unit($data);
        if ($update) {
            echo json_encode(array('status' => 'success'));
        } else {
            echo json_encode(array('status' => 'failure'));
        }
    }
}
