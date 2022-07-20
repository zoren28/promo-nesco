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
        $data['agency_code'] = $this->input->get('agency_code');
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
}
