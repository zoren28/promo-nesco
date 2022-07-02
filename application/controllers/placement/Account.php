<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Account extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('nativesession');

        if (!isset($_SESSION['emp_id'])) {

            redirect('http://' . $_SERVER['SERVER_ADDR'] . ":" . $_SERVER['SERVER_PORT'] . '/hrms/nesco');
        }

        $this->load->model('placement/account_model');
        $this->load->model('placement/user_model');
        $this->load->model('placement/dashboard_model');
    }

    public function create_user_account()
    {
        $data = $this->input->post(NULL, TRUE);

        // check if the employee had already an account
        $user_chk = $this->account_model->check_user($data['emp_id']);
        if ($user_chk) {

            echo json_encode(array('status' => 'exist account'));
        } else {

            $username_chk = $this->account_model->check_usernamme($data['username']);
            if ($username_chk) {

                echo json_encode(array('status' => 'exist'));
            } else {

                $store = $this->account_model->create_user_account($data);
                if ($store) {
                    echo json_encode(array('status' => 'success'));
                }
            }
        }
    }

    public function find_active_hr_staff()
    {
        $val = "";
        $str = $this->input->post('str', TRUE);

        $query = $this->account_model->find_active_hr_staff($str);
        if ($query->num_rows() > 0) {

            $info = $query->result_array();
            foreach ($info as $emp) {

                $empId = $emp['emp_id'];
                $name  = ucwords(strtolower($emp['name']));

                if ($val != $empId) {
?>
                    <a href="javascript:void(0);" onclick="getHR('<?= $emp['emp_id'] . ' * ' . $emp['name']  ?>')"><?= $emp['emp_id'] . ' * ' . $emp['name']  ?></a></br>
<?php
                } else {
                    echo 'No Result Found';
                }
            }
        } else {

            echo 'No Result Found';
        }
    }

    public function create_hr_account()
    {
        $data = $this->input->post(NULL, TRUE);

        $user_chk = $this->account_model->check_promo_user($data['emp_id']);
        if ($user_chk) {

            echo json_encode(array('status' => 'exist'));
        } else {

            $hr = $this->account_model->create_hr_account($data);
            if ($hr) {

                echo json_encode(array('status' => 'success'));
            }
        }
    }

    public function promo_account_list()
    {
        $request = $this->input->post(NULL, TRUE);
        $fetch_data = $this->account_model->get_promo_account_list($request);
        $data = array();
        foreach ($fetch_data as $row) {

            if (isset($_SERVER['SERVER_PORT'])) {
                $base_url = 'http://' . $_SERVER['SERVER_ADDR'] . ":" . $_SERVER['SERVER_PORT'];
            } else {

                $base_url = 'http://' . $_SERVER['SERVER_ADDR'];
            }

            $user_no = $row['user_no'];
            if ($row['user_status'] == "active") {

                $status = "btn btn-success btn-xs btn-block btn-flat";
                $iconImage = "<a href='javascript:void(0)' title='click to deactivate account' onclick=userAction(\"$user_no\",'deactivateAccount')><img src='" . $base_url . "/hrms/images/icons/icon-close-circled-20.png' height='17' width='17'></a>";
            } else {

                $status = "btn btn-danger btn-xs btn-block btn-flat";
                $iconImage = "<a href='javascript:void(0)' title='click to activate account' onclick=userAction(\"$user_no\",'activateAccount')><img src='" . $base_url . "/hrms/images/icons/icn_active.gif' height='17' width='17'></a>";
            }

            if ($this->account_model->loginId == "06359-2013") {
                $trashImage = "<a href='javascript:void(0)' title='click to delete account' onclick=userAction(\"$user_no\",'deleteAccount')><img src='" . $base_url . "/hrms/images/icons/delete-icon.png' height='17' width='17'></a>";
            }

            $sub_array = array();
            $sub_array[] = '<a href="' . base_url('placement/page/menu/employee/profile/' . $row['emp_id']) . '" target="_blank">' . $row['name'] . '</a>';
            $sub_array[] = $row['username'];
            $sub_array[] = $row['usertype'];
            $sub_array[] = '<label class="' . $status . '">' . $row['user_status'] . '</label>';
            $sub_array[] = $row['login'];
            $sub_array[] = "<a href='javascript:void(0)' title='click to reset password' onclick=userAction(\"$user_no\",'resetPass')><img src='" . $base_url . "/hrms/images/icons/refresh.png' height='17' width='17'></a>&nbsp;$iconImage&nbsp;$trashImage";
            $data[] = $sub_array;
        }
        $output = array(
            "draw"                      =>     intval($_POST["draw"]),
            "recordsTotal"              =>     $this->account_model->get_all_data($request),
            "recordsFiltered"           =>     $this->account_model->get_filtered_data($request),
            "data"                      =>     $data
        );
        echo json_encode($output);
    }

    public function hr_account_list()
    {
        $request = $this->input->post(NULL, TRUE);
        $fetch_data = $this->user_model->get_hr_account_list($request);
        $data = array();
        foreach ($fetch_data as $row) {

            $emp_id = $row['emp_id'];

            $usertypes = array('promo1' => 'Promo Incharge', 'promo2' => 'Encoder', 'nesco' => 'NESCO Incharge');
            $usertype = "<select name='usertype' onchange='userType(\"$emp_id\", this.value)'>";
            foreach ($usertypes as $key => $value) {

                if ($key == $row['usertype']) {

                    $usertype .= '
                        <option value="' . $key . '" selected>' . $value . '</option>
                    ';
                } else {

                    $usertype .= '
                        <option value="' . $key . '">' . $value . '</option>
                    ';
                }
            }
            $usertype .= "</select>";

            $statuses = array('active', 'inactive');
            $status = "<select name='userStatus' onchange='userStatus(\"$emp_id\",this.value)'>";
            foreach ($statuses as $key => $value) {

                if ($value == $row['user_status']) {

                    $status .= '<option value="' . $value . '" selected>' . $value . '</option>';
                } else {

                    $status .= '<option value="' . $value . '">' . $value . '</option>';
                }
            }
            $status .= "</select>";

            $sub_array = array();
            $sub_array[] = '<a href="' . base_url('placement/page/menu/employee/profile/' . $row['emp_id']) . '" target="_blank">' . $row['name'] . '</a>';
            $sub_array[] = $usertype;
            $sub_array[] = $status;
            $sub_array[] = date("m/d/Y h:i A", strtotime($row['date_created']));
            $sub_array[] = (!empty($row['date_updated'])) ? date('m/d/Y h:i A', strtotime($row['date_updated'])) : '';
            $data[] = $sub_array;
        }
        $output = array(
            "draw"                      =>     intval($_POST["draw"]),
            "recordsTotal"              =>     $this->user_model->get_all_data($request),
            "recordsFiltered"           =>     $this->user_model->get_filtered_data($request),
            "data"                      =>     $data
        );
        echo json_encode($output);
    }

    public function update_hr_account()
    {
        $data = $this->input->post(NULL, TRUE);
        $user = $this->account_model->update_hr_account($data);
        if ($user) {

            echo json_encode(array('status' => 'success'));
        }
    }

    public function update_hr_status()
    {
        $data = $this->input->post(NULL, TRUE);
        $user = $this->account_model->update_hr_status($data);
        if ($user) {

            echo json_encode(array('status' => 'success'));
        }
    }
}
