<?php

$emp_info = $this->employee_model->employee_info($empId);
if (empty($emp_info)) {

    redirect('http://' . $_SERVER['SERVER_ADDR'] . ":" . $_SERVER['SERVER_PORT'] . '/hrms/promo-nesco/placement/page/menu/employee/search-promo');
}

$app_info = $this->employee_model->get_applicant_info($empId);

$photo     = $app_info->photo;
$name     = $app_info->lastname . ", " . $app_info->firstname;

if (trim($photo) == "") {

    $photo = '../images/users/icon-user-default.png';
}

// birthdate
$age = "";
$msgbd = "";
$birthdate = $app_info->birthdate;
if ($birthdate != "0000-00-00" && $birthdate != "NULL" && $birthdate != "") {

    $birthDate = date("m/d/Y", strtotime($birthdate));
    $birthDate = explode("/", $birthDate);
    $age = (date("md", date("U", mktime(0, 0, 0, $birthDate[0], $birthDate[1], $birthDate[2]))) > date("md")
        ? ((date("Y") - $birthDate[2]) - 1)
        : (date("Y") - $birthDate[2]));

    $md = explode('-', $birthdate);
    if ($md[1] . "-" . $md[2] == date('m-d')) : $msgbd = "<i class='text-red'>...has a birthday today!!!</i><br>";
    endif;
}


// if naay agency
$agency_name = '';
if ($emp_info->agency_code != 0) {

    $agency_name = $this->employee_model->agency_name($emp_info->agency_code);
}

// if naay vendor
$vendor_name = '';
if ($emp_info->vendor_code != '') {

    $vendor_name = $this->employee_model->vendor_name($emp_info->vendor_code) . '<br>';
}

$ctr = 0;
$storeName = '';
$bUs = $this->dashboard_model->businessUnit_list();
foreach ($bUs as $bu) {

    $hasBU = $this->dashboard_model->promo_has_bu($empId, $bu->bunit_field);
    if ($hasBU > 0) {

        $ctr++;

        if ($ctr == 1) {

            $storeName = $bu->bunit_name;
        } else {

            $storeName .= "<br>" . $bu->bunit_name;
        }
    }
}

// current status
$current_status = $emp_info->current_status;
switch ($current_status) {

    case 'Active':
        $statClass = "btn-success";
        break;

    case 'Resigned':
        $statClass = "btn-warning";
        break;

    case 'End of Contract':
        $statClass = "btn-warning";
        break;

    case 'blacklisted':
        $statClass = "btn-danger";
        break;

    default:

        $bk_num = $this->employee_model->check_blacklisted($empId);
        if ($bk_num > 0) {

            $statClass = "btn-danger";
            $current_status = "blacklisted";
        } else {

            $statClass = "btn-primary";

            $app_status = $this->employee_model->applicant_status($empId);
            if ($app_status != "") {

                $current_status = $app_status;
            } else {

                $current_status = "<i> N O N E </i>";
            }
        }

        break;
}

$userLogin = $this->employee_model->user_login($empId);

switch ($userLogin) {
    case 'yes':
        $userStatClass = "text-success";
        break;
    default:
        $userStatClass = "text-default";
        break;
}

// contract
$startdate = $eocdate = "";

if ($emp_info->startdate != "" && $emp_info->startdate != "0000-00-00" && $emp_info->startdate != "1970-01-01" && $emp_info->startdate != "0001-01-01" && $emp_info->startdate != "0001-11-30") {

    $startdate = date("m/d/Y", strtotime($emp_info->startdate));
}

if ($emp_info->eocdate != "" && $emp_info->eocdate != "0000-00-00" && $emp_info->eocdate != "1970-01-01" && $emp_info->eocdate != "0001-01-01" && $emp_info->eocdate != "0001-11-30") {

    $eocdate = date("m/d/Y", strtotime($emp_info->eocdate));
}

$contract = $startdate . " - " . $eocdate . " (<i>start-eoc</i>)<br>";

// date hired
$hired = $this->employee_model->date_hired($empId);
$date_hired = "";
if ($hired != "" && $hired != "0000-00-00"  && $hired != "1970-01-01"  && $hired != "0001-01-01"  && $hired != "0001-11-30") {

    $date_hired = date("m/d/Y", strtotime($hired)) . " (<i>date hired</i>)<br>";
}

?>
<style type="text/css">
    .center {
        display: block;
        margin-left: auto;
        margin-right: auto;
        width: 90%;
    }

    .img {
        width: 70px;
        height: 80px;
    }

    .modf {
        float: right;
        margin-top: -55px;
        font-size: 20px
    }

    .table-height {

        overflow: auto;
        max-height: 450px;
    }

    .preview {

        background-image: url("http://<?php echo $_SERVER['SERVER_ADDR'] . ':' . $_SERVER['SERVER_PORT']; ?>/hrms/promo-nesco/../images/images.png");
        background-size: contain;
        width: 300px;
        height: 370px;
        border: 2px solid #BBD9EE;
    }

    .preview_birthCert {

        background-image: url("http://<?php echo $_SERVER['SERVER_ADDR'] . ':' . $_SERVER['SERVER_PORT']; ?>/hrms/promo-nesco/../images/images.png");
        background-size: contain;
        width: 100%;
        height: 670px;
        border: 2px solid #BBD9EE;
    }

    .profilePhoto {

        background-image: url("http://<?php echo $_SERVER['SERVER_ADDR'] . ':' . $_SERVER['SERVER_PORT']; ?>/hrms/promo-nesco/../images/images.png");
        background-size: contain;
        width: 270px;
        height: 270px;
        border: 2px solid #BBD9EE;
    }
</style>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-3">

            <input type="hidden" name="empId" value="<?php echo $empId; ?>">
            <div class="box box-primary">
                <div class="box-body box-profile">
                    <a href="javascript:void(0)" onclick="changeProfilePic()"><img width="200" height="200" class="img-circle center" src="<?php echo 'http://' . $_SERVER['SERVER_ADDR'] . ":" . $_SERVER['SERVER_PORT'] . '/hrms/promo-nesco/' . $photo; ?>"></a>
                    <h3 class="profile-username text-center"><small><i class="fa fa-circle <?php echo $userStatClass; ?>"></i></small> <?php echo $name; ?></h3>
                    <p class="text-muted text-center"><?php echo $emp_info->position; ?></p>
                    <p class="text-center"><button class="btn btn-success btn-sm" onclick="changeProfilePic()">Change Photo</button> &nbsp; <button class="btn btn-success btn-sm" onclick="viewProfile()">View Full Profile</button></p>

                    <div style="padding:10px; font-style:italic">
                        <p><strong>
                                <?php

                                echo
                                $msgbd . "" .
                                    $empId . "<br>" .
                                    $emp_info->emp_no . "<br>" .
                                    $age . " years old <br><br>" .
                                    $agency_name . "<br>" .
                                    $emp_info->promo_company . "<br>" .
                                    $vendor_name . "<br>" .
                                    $storeName . "<br><br>" .
                                    $emp_info->promo_department . "<br>" .
                                    $emp_info->emp_type . "(" . $emp_info->type . ")<br>" .
                                    $date_hired . "" .
                                    $contract;
                                ?>
                            </strong></p>
                    </div>
                    <a href="#" class="btn btn-sm btn-block <?php echo $statClass; ?>"><b><?php echo $current_status; ?></b></a>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="box box-primary">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <select class="form-control" style="width:250px" id='classify' onchange="getdefault(this.value)">
                                <option value="basic_info">Basic Information</option>
                                <option value="family">Family Background</option>
                                <option value="contact">Contact & Address Information</option>
                                <option value="educ">Educational Background</option>
                                <option value="seminar">Eligibility/Seminars/Trainings</option>
                                <option value="charref">Character References</option>
                                <option value="skills">Skills and Competencies</option>
                                <option value="eocapp">EOC Appraisal</option>
                                <option value="application">Application History</option>
                                <option value="employment">Contract History</option>
                                <option value="history">Employment History</option>
                                <option value="transfer">Job Transfer History</option>
                                <option value="blacklist">Blacklist History</option>
                                <option value="benefits">Benefits</option>
                                <option value="201doc">201 Documents</option>
                                <option value="pss">Peer-Subordinate-Supervisor</option>
                                <option value="remarks">Remarks</option>
                                <option value="useraccount">User Account</option>
                            </select>
                        </div>
                        <div class="col-md-6 modf pull-right"></div>
                    </div><br>
                    <div class="row">
                        <div class="col-md-12" id="details"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>