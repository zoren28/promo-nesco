<?php

if (!empty($_SESSION['searchThis'])) { ?>

    <script type="text/javascript">
        $(".search_employee").show();
    </script>
<?php

    $result = $this->employee_model->search_employee($_SESSION['searchThis']);
    $employee = $_SESSION['searchThis'];
    unset($_SESSION['searchThis']);
} else {

    $employee = "";
?>
    <style type="text/css">
        .search_employee {
            display: none;
        }
    </style>
<?php
}
?>

<section class="content-header">
    <h1>
        <?php echo ucwords(strtolower($title)); ?>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#"><?php echo ucwords(strtolower($title)); ?></a></li>
        <li class="active">Search Employee</li>
    </ol>
</section>
<section class="content">

    <!-- Default box -->
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Search Employee</label>
                                <div class="input-group">
                                    <input class="form-control searchThis" type="text" name="searchThis" value="<?php echo $employee; ?>" style="text-transform:uppercase" autocomplete="off">
                                    <span class="input-group-addon"><i class="fa fa-male"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="loading_process"></div>
                    <div class="row">
                        <div class="col-md-12 search_employee">

                            <table id="search_employeeTable" class="table table-hover table-condensed" width="100%">
                                <thead style="display: none;">
                                    <tr>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php

                                    $counter = 0;
                                    foreach ($result as $row) {
                                        $counter++;

                                        $info = $this->employee_model->get_applicant_info($row->emp_id);

                                        $photo = $info->photo;
                                        if (empty($photo)) {
                                            $photo = '../images/users/icon-user-default.png';
                                        }

                                        $ctr = 0;
                                        $storeName = '';
                                        $bUs = $this->dashboard_model->businessUnit_list();
                                        foreach ($bUs as $bu) {

                                            $hasBU = $this->dashboard_model->promo_has_bu($row->emp_id, $bu->bunit_field);
                                            if ($hasBU > 0) {

                                                $ctr++;

                                                if ($ctr == 1) {

                                                    $storeName = $bu->bunit_acronym;
                                                } else {

                                                    $storeName .= ", " . $bu->bunit_acronym;
                                                }
                                            }
                                        }

                                        $company_name = $row->promo_company;
                                        $dept_name = $row->promo_department;

                                        if (strtolower($row->current_status) == "active") {

                                            $classSpan = "label label-success";
                                        } else if (strtolower($row->current_status) == "blacklisted") {

                                            $classSpan = "label label-danger";
                                        } else {

                                            $classSpan = "label label-warning";
                                        }

                                    ?>
                                        <tr>
                                            <td>
                                                <div class="row">
                                                    <div class="col-md-1">
                                                        <img class="" src="<?php echo 'http://' . $_SERVER['SERVER_ADDR'] . ":" . $_SERVER['SERVER_PORT'] . '/hrms/promo-nesco/' . $photo; ?>" alt="" style="width: 100%; height: 100%;">
                                                    </div>
                                                    <div class="col-md-11">
                                                        <?php

                                                        echo "<p><span class='text-red'>[" . $counter . "]</span> <strong>" . $row->emp_id . " <a href='" . base_url('placement/page/menu/employee/profile/' . $row->emp_id) . "'>" . ucwords(strtolower($row->name)) . "</a></strong> &nbsp;<span class='" . $classSpan . "'>" . $row->current_status . "</span>";
                                                        echo "<span class='text-success'><br><strong>Company: </strong><i>" . $company_name . "</i> &nbsp;<strong>Business Unit: </strong><i>" . $storeName . "</i> &nbsp;<strong>Department: </strong><i>" . $dept_name . "</i></span>";
                                                        echo "<br><strong>Position: </strong><i>" . $row->position . "</i> <strong>Civil Status: </strong><i>" . $info->civilstatus . "</i> <strong>Birthdate: </strong><i>" . date('F d, Y', strtotime($info->birthdate)) . "</i> <strong>Home Address: </strong><i>" . ucwords(strtolower($info->home_address)) . "</i>";
                                                        ?>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>

                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>