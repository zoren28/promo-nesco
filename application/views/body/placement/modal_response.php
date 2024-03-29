<?php

$admin_users = ['06359-2013', '01476-2015'];

if ($request == "update_blacklist_form") {

    $info = explode("_", $fetch_data['id']);
    $blacklist_no = end($info);

    $fetch = $this->blacklist_model->get_blacklist_info($blacklist_no);

    if (!empty($fetch['app_id'])) {

        $employee = $fetch['app_id'] . " * " . $fetch['name'];
    } else {

        $employee = $fetch['name'];
    }

    $dateBlacklisted = "";
    $birthday = "";
    $address = "";
    $reportedBy = "";
    if (!empty($fetch['date_blacklisted'])) {

        $dateBlacklisted = date("m/d/Y", strtotime($fetch['date_blacklisted']));
    }

    if (!empty($fetch['bday'])) {

        $birthday = date("m/d/Y", strtotime($fetch['bday']));
    } ?>

    <input type="hidden" name="blacklistNo" value="<?php echo $blacklist_no; ?>">
    <input type="hidden" name="appId" value="<?php echo $fetch['app_id']; ?>">
    <input type="hidden" name="appName" value="<?php echo $fetch['name']; ?>">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label>Employee</label>
                <input type="text" class="form-control" name="employee" value="<?php echo $employee; ?>" readonly="">
            </div>
            <div class="form-group"> <span class="text-red">*</span>
                <label>Reason</label>
                <textarea name="reason" class="form-control" rows="4" required=""><?php echo $fetch['reason']; ?></textarea>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group"><span class="text-red">*</span>
                <label>Date Blacklisted</label>
                <div class="input-group date">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </div>
                    <input name="dateBlacklisted" class="form-control datepicker" type="text" required="" style="position: relative; z-index: 100000;" data-inputmask='"mask": "99/99/9999"' data-mask value="<?php echo $dateBlacklisted; ?>">
                </div>
            </div>
            <div class="form-group">
                <label>Birthday</label>
                <div class="input-group date">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </div>
                    <input name="birthday" class="form-control datepicker" type="text" style="position: relative; z-index: 100000;" data-inputmask='"mask": "99/99/9999"' data-mask value="<?php echo $birthday; ?>">
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group"><span class="text-red">*</span>
                <label>Reported By</label>
                <div class="input-group">
                    <input class="form-control" name="reportedBy" required="" autocomplete="off" type="text" onkeyup="nameSearch(this.value)" value="<?php echo $fetch['reportedby']; ?>">
                    <div class="input-group-addon">
                        <i class="fa fa-user-secret"></i>
                    </div>
                </div>
                <div class="search-results" style="display: none;"></div>
            </div>
            <div class="form-group">
                <label>Address</label>
                <input class="form-control" name="address" type="text" value="<?php echo $fetch['address']; ?>">
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function() {

            $(".datepicker").datepicker({

                changeYear: true,
                changeMonth: true
            });

            $("[data-mask]").inputmask();
        });
    </script>
<?php
} else if ($request == "candidate_for_blacklisted") {

?>
    <i class="text-danger">Note :</i>
    <ol>
        <li>You are advised to search the lastname first to find out if the one being searched is blacklisted. </li>
        <li>if no results found, that indicates that the one being search is not an applicant nor an employee. </li>
    </ol>
    <input type="hidden" name="blackSign" value="0">
    <div class="row">
        <div class="col-md-12">
            <form action="" id="browseNames" method="post" class="form-inline">
                <div class="form-group">
                    <label>Search </label>
                </div>
                <div class="form-group">
                    <input class="form-control mx-2" name="lastname" placeholder="Lastname" type="text" required="" autocomplete="off">
                </div>
                <div class="form-group">
                    <input class="form-control mx-2" name="firstname" placeholder="Firstname" type="text" autocomplete="off">
                </div>
                <button type="submit" class="btn btn-primary mx-2" id="browseNames"><i class="fa fa-search"></i> Search</button>
            </form>
        </div>
    </div>
    <div id="nonApp" style="display: none;">
        <hr>
        <i class="text-red">
            No Results Found! Kindly fill up the textbox below to blacklist non-applicant or non-employee.
        </i>
        <form id="choose_blacklist" action="" method="post">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Lastname <span class="text-danger">*</span></label>
                        <input type="text" name="lname" class="form-control" placeholder="Lastname" required="">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Firstname <span class="text-danger">*</span></label>
                        <input type="text" name="fname" class="form-control" placeholder="Firstname" required="">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Middlename </label>
                        <input type="text" name="mname" class="form-control" placeholder="Middlename">
                    </div>
                </div>
                <div class="col-md-3">
                    <label>&nbsp;</label><br>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-hand-pointer-o"></i> Choose to Blacklist</button>
                </div>
            </div>
        </form>
    </div>
    <div id="resultBrowse"></div>
    <div class="loading-gif"></div>
    <script type="text/javascript">
        $("form#choose_blacklist").submit(function(e) {
            e.preventDefault();

            var lastname = $("input[name = 'lname']").val().trim();
            var firstname = $("input[name = 'fname']").val().trim();
            var middlename = $("input[name = 'mname']").val().trim();

            if (lastname == "" || firstname == "") {

                errDup("Please fill out required field.");
            } else {

                var fullname = lastname + ", " + firstname + " " + middlename;
                $("[name = 'employee']").val(fullname);
                $("[name = 'appName']").val(fullname);

                $("#browse_employee").modal("hide");
                enabledFields();
            }
        });

        $("form#browseNames").submit(function(e) {
            e.preventDefault();

            var lastname = $("input[name = 'lastname']").val();
            var firstname = $("input[name = 'firstname']").val();
            var formData = $(this).serialize();

            if (lastname == "" && firstname == "") {

                errDup("Please indicate either the employee lastname or firstname to be searched.");
            } else {

                $("div.loading-gif").html('<center><img src="<?php echo base_url('assets/images/gif/loader_seq.gif'); ?>"><center>');
                $('#resultBrowse').html('');

                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('browseNames'); ?>",
                    data: formData,
                    success: function(data) {

                        data = data.trim();
                        if (data == "No Result Found") {

                            $("#nonApp").show();
                            $("div.loading-gif").html('');
                        } else {

                            $("#nonApp").hide();
                            $("div.loading-gif").html('');
                            $("#resultBrowse").html(data);
                        }
                    }
                });
            }
        });
    </script>
    <?php
} else if ($request == "browse_names") {

    $lastname = trim($fetch_data['lastname']);
    $firstname = trim($fetch_data['firstname']);

    $fullname = "$lastname, $firstname";
    $fullname2 = "$lastname,$firstname";

    $condition = "";
    if (!empty($lastname) && !empty($firstname)) {
        $condition = "lastname = '$lastname' AND firstname LIKE '%$firstname%'";
    } else {

        if (!empty($lastname)) {
            $condition = "lastname = '$lastname'";
        } else {
            $condition = "firstname LIKE '%$firstname%'";
        }
    }

    $query = $this->blacklist_model->browse_name1($condition);
    $query2 = $this->blacklist_model->browse_name2($fullname, $fullname2);

    $queryNum = $query->num_rows();
    $queryNum2 = $query2->num_rows();

    if ($queryNum == 0 && $queryNum2 == 0) {

        die("No Result Found");
    } else { ?>
        <br>
        <div class="row">
            <div class="col-md-7">
                <label>Applicant/Employee</label>
                <table class="table table-hover table-stripped table-sm">
                    <?php

                    $infos = $query->result_array();
                    foreach ($infos as $fetch) {
                        $fullname = "";
                        if (!empty($fetch['suffix'])) {

                            $fullname = utf8_encode($fetch['lastname'] . ", " . $fetch['firstname'] . " " . $fetch['suffix'] . ", " . $fetch['middlename']);
                        } else {

                            $fullname = utf8_encode($fetch['lastname'] . ", " . $fetch['firstname'] . " " . $fetch['middlename']);
                        }

                        $query3 = $this->blacklist_model->current_status($fetch['app_id']);
                        $fetchNum3 = $query3->num_rows();
                        $option = "";

                        if ($fetchNum3 > 0) {

                            $fetch3 = $query3->row_array();
                            switch ($fetch3['current_status']) {
                                case 'Active':
                                    $label = "success";
                                    break;
                                case 'Resigned':
                                    $label = "warning";
                                    break;
                                case 'End of Contract':
                                    $label = "warning";
                                    break;
                                default:
                                    $label = "danger";
                                    $option = "disabled=''";
                                    break;
                            }

                            $currentStat = $fetch3['current_status'];
                        } else {

                            $query4 = $this->blacklist_model->applicant_status($fetch['appCode']);
                            $fetch4 = $query4->row_array();

                            $label = "primary";
                            $currentStat = $fetch4['status'];
                            if (empty($currentStat)) {
                                $currentStat = "N/A";
                            }
                        }

                        echo "
                            <tr>
                                <td><input type='hidden' id='appName_" . $fetch['app_id'] . "' value='" . ucwords(strtolower($fullname)) . "'>" . ucwords(strtolower($fullname)) . "</td>
                                <td><span class='btn btn-block btn-sm btn-$label'>$currentStat</span></td>
                                <td><button class='btn btn-sm btn-secondary choose' $option id='choose_" . $fetch['app_id'] . "'><i class='fa fa-hand-pointer-o'></i> Choose</button></td>
                            </tr>";
                    }
                    ?>
                </table>
            </div>
            <div class="col-md-5">
                <label>Blacklisted</label>
                <table class="table table-hover table-stripped">
                    <?php

                    $infos = $query2->result_array();
                    foreach ($infos as $fetch2) {

                        echo "<tr>
                                <td>" . utf8_encode($fetch2['name']) . "</td>
                                <td><span class='btn btn-sm btn-block btn-danger'>blacklisted</span></td>
                            </tr>";
                    }
                    ?>
                </table>
            </div>
        </div>
        <script type="text/javascript">
            $("button.choose").click(function() {

                var id = this.id.split("_");
                var app_id = id[1].trim();

                var fullname = $("#appName_" + app_id).val();
                $("input[name = 'employee']").val(app_id + " * " + fullname);
                $("input[name = 'appName']").val(fullname);
                $("input[name = 'appId']").val(app_id);

                $("#browse_employee").modal("hide");
                enabledFields();

            });
        </script>
    <?php
    }
} else if ($request == 'due_contract_xls') {

    $filename = "Due of Contract Report";
    header("Content-Type: application/vnd.ms-excel");
    header("Content-disposition: attachment; filename=" . $filename . ".xls");

    ?>
    <center>
        <h3>Due of Contract Report as of <?php echo date("F d, Y"); ?></h3>
    </center>
    <table class='table table-bordered' border='1'>
        <tr>
            <th>No</th>
            <th>Emp. ID</th>
            <th>Name</th>
            <th>Agency</th>
            <th>Company</th>
            <th>Business Unit</th>
            <th>Department</th>
            <th>Position</th>
            <th>Promo Type</th>
            <th>Startdate</th>
            <th>Eocdate</th>
        </tr>
        <?php

        $num = 1;

        $result = $this->dashboard_model->due_contract();
        $fetch_data = $result->result();

        foreach ($fetch_data as $row) {

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

            $agency_name = '';
            if ($row->agency_code != 0) {

                $agency_name = $this->employee_model->agency_name($row->agency_code);
            }

            echo "
								<tr>
									<td>" . $num . "</td>
									<td>" . $row->emp_id . "</td>
									<td>" . ucwords(strtolower($row->name)) . "</td>
									<td>" . $agency_name . "</td>
									<td>" . $row->promo_company . "</td>
									<td>" . $storeName . "</td>
									<td>" . $row->promo_department . "</td>
									<td>" . $row->position . "</td>
									<td>" . $row->promo_type . "</td>
									<td>" . date('m/d/Y', strtotime($row->startdate)) . "</td>
									<td>" . date('m/d/Y', strtotime($row->eocdate)) . "</td>
								</tr>
							";
            $num++;
        }
        ?>
    </table>
<?php
} else if ($request == 'view_stat_BU') {

    $field = $fetch['field'];
    $bunit_name = $fetch['bunit_name'];

?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="btn-group pull-right">
                <button onclick="generate_stat_BU('<?php echo $field; ?>')" class="btn btn-success btn-md">Generate Report</button>
            </div>
            <h4><?php echo $bunit_name; ?></h4>
        </div>
        <div class="panel-body">

            <table id="statistics" class="table table-bordered table-hover" width="100%">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Employee Name</th>
                        <th>Department</th>
                        <th>Position</th>
                        <th>Promo Type</th>
                        <th>Contract Type</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <script type="text/javascript">
        var dataTable = $('#statistics').DataTable({

            "destroy": true,
            "order": [],
            "ajax": {
                url: "<?php echo site_url('load_stat_BU'); ?>",
                type: "get",
                data: {
                    field: '<?php echo $field; ?>'
                },
            },
            "columns": [{
                    "width": "5%"
                },
                {
                    "width": "18%"
                },
                {
                    "width": "18%"
                },
                {
                    "width": "18%"
                },
                {
                    "width": "18%"
                },
                {
                    "width": "18%"
                },
            ]
        });
    </script>
<?php
} else if ($request == 'statistics_xls') {

    $filename = "Statistic Summary Report";
    header("Content-Type: application/vnd.ms-excel");
    header("Content-disposition: attachment; filename=" . $filename . ".xls");

?>
    <center>
        <h3>Statistics Summary Report as of <?php echo date("F d, Y"); ?></h3>
    </center>

    <table class='table table-bordered' border='1'>
        <tr>
            <th>No</th>
            <th>Emp. ID</th>
            <th>Name</th>
            <th>Agency</th>
            <th>Company</th>
            <th>Business Unit</th>
            <th>Department</th>
            <th>Position</th>
            <th>Promo Type</th>
            <th>Contract Type</th>
            <th>Startdate</th>
            <th>Eocdate</th>
        </tr>
        <?php

        $num = 1;
        foreach ($statistics as $row) {

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

            $eocdate = "";
            if ($row->eocdate != "0000-00-00" && $row->eocdate != "1970-01-01" && $row->eocdate != "0001-01-01" && $row->eocdate != "0001-11-30") {

                $eocdate = date('m/d/Y', strtotime($row->eocdate));
            }

            $agency_name = '';
            if ($row->agency_code != 0) {

                $agency_name = $this->employee_model->agency_name($row->agency_code);
            }

            echo "
								<tr>
									<td>" . $num . "</td>
									<td>" . $row->emp_id . "</td>
									<td>" . ucwords(strtolower($row->name)) . "</td>
									<td>" . $agency_name . "</td>
									<td>" . $row->promo_company . "</td>
									<td>" . $storeName . "</td>
									<td>" . $row->promo_department . "</td>
									<td>" . $row->position . "</td>
									<td>" . $row->promo_type . "</td>
									<td>" . $row->type . "</td>
									<td>" . date('m/d/Y', strtotime($row->startdate)) . "</td>
									<td>" . $eocdate . "</td>
								</tr>
							";
            $num++;
        }
        ?>
    </table>
<?php
} else if ($request == 'view_stat_dept') {

    $field = $fetch['field'];
    $bunit_name = $fetch['bunit_name'];
    $dept = $fetch['dept'];

?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="btn-group pull-right">
                <button onclick="generate_stat_dept('<?php echo $field; ?>', '<?php echo $dept; ?>')" class="btn btn-success btn-md">Generate Report</button>
            </div>
            <h4><?php echo $bunit_name; ?></h4>
        </div>
        <div class="panel-body">

            <table id="statistics" class="table table-bordered table-hover" width="100%">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Employee Name</th>
                        <th>Department</th>
                        <th>Position</th>
                        <th>Promo Type</th>
                        <th>Contract Type</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <script type="text/javascript">
        var dataTable = $('#statistics').DataTable({

            "destroy": true,
            "order": [],
            "ajax": {
                url: "<?php echo site_url('load_stat_dept'); ?>",
                type: "get",
                data: {
                    field: '<?php echo $field; ?>',
                    dept: '<?php echo $dept; ?>'
                },
            },
            "columns": [{
                    "width": "5%"
                },
                {
                    "width": "18%"
                },
                {
                    "width": "18%"
                },
                {
                    "width": "18%"
                },
                {
                    "width": "18%"
                },
                {
                    "width": "18%"
                },
            ]
        });
    </script>
<?php
} else if ($request == 'search_employee') {
?>
    <table id="search_employeeTable" class="table table-hover table-condensed" width="100%">
        <thead style="display: none;">
            <tr>
                <th></th>
            </tr>
        </thead>
        <tbody>

            <?php

            $counter = 0;
            foreach ($fetch as $row) {
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
            } ?>
        </tbody>
    </table>
    <script type="text/javascript">
        $('#search_employeeTable').DataTable({

            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": false,
            "info": true,
            "autoWidth": false
        });
    </script>
<?php
} else if ($request == 'search_applicant') {
?>
    <table id="search_applicantTable" class="table table-hover table-condensed" width="100%">
        <thead style="display: none;">
            <tr>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php

            $counter = 0;
            foreach ($fetch as $row) {

                $counter++;
                $photo = $row->photo;
                if (empty($photo)) {
                    $photo = '../images/users/icon-user-default.png';
                }

                $fullname = "";
                if (!empty($row->suffix)) {

                    $fullname = $row->lastname . ", " . $row->firstname . " " . $row->suffix . ", " . $row->middlename;
                } else {

                    $fullname = $row->lastname . ", " . $row->firstname . " " . $row->middlename;
                }

                $birthdate = "";
                if ($row->birthdate != "") {

                    $birthdate = date("F d, Y", strtotime($row->birthdate));
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

                                echo "<p><span class='text-red'>[" . $counter . "]</span> <strong>" . $row->app_id . " <a href='" . base_url('placement/page/menu/employee/profile/' . $row->app_id) . "' target='_blank'>" . ucwords(strtolower($fullname)) . "</a></strong> &nbsp;";
                                echo "<br><strong>Civil Status: </strong><i>" . $row->civilstatus . "</i> &nbsp;<strong>Birthdate: </strong><i>" . $birthdate . "</i> &nbsp;<strong>Home Address: </strong><i>" . $row->home_address . "</i></p>";
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
    <script type="text/javascript">
        $('#search_applicantTable').DataTable({

            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": false,
            "info": true,
            "autoWidth": false
        });
    </script>
<?php
} else if ($request == 'changeProfilePic') {

    $empId = $fetch['empId'];

?>
    <small><i><span class="text-red">Note:</span> Acceptable file format are [ jpg, png, gif ] and file size should not be greater than 1MB.</i></small><br><br>
    <input type="hidden" name="empId" value="<?php echo $empId; ?>">

    <img id="photoprofile" class="img-circle center profilePhoto"><br>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <center><input type='button' id='profile_change' style='display:none;' class='btn btn-primary btn-md btn-block' value='Change Photo?' onclick='changePhoto("Photo","profile","profile_change")'>
                <input type='file' name='profile' id='profile' class='btn btn-default btn-block' onchange='readURL(this,"profile");'>
                <input type='button' name='clearprofile' id='clearprofile' style='display:none' class='btn btn-warning btn-block' value='Clear' onclick="clears('profile','photoprofile','clearprofile')">
            </center>
        </div>
    </div>

    <script type="text/javascript">
        var empId = $("[name = 'empId']").val();

        $('#photoprofile').removeAttr('src');
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('getProfilePic'); ?>",
            data: {
                empId: empId
            },
            success: function(data) {

                if (data != '') {
                    document.getElementById("photoprofile").src = "<?php echo 'http://' . $_SERVER['SERVER_ADDR'] . ":" . $_SERVER['SERVER_PORT'] . '/hrms/promo-nesco/'; ?>" + data;
                    $('#profile').hide();
                    $("#profile_change").show();
                } else {
                    $("#profile_change").hide();
                    $('#profile').show();
                }
            }
        });
    </script>
<?php

} else if ($request == "update_birthCertForm") {

    $childId = $this->input->post('childId', TRUE); ?>

    <input type="hidden" name="childId" value="<?php echo $childId; ?>">
    <div class="row">
        <div class="col-md-12">
            <img id="photoNSO" class='preview_birthCert img-responsive' /><br>
            <input type='file' name='NSO' id='NSO' class='btn btn-default' onchange='readURL(this,"NSO");'> <br>
            <input type='button' name='clearNSO' id='clearNSO' style='display:none' class='btn btn-warning' value='Clear' onclick="clears('NSO','photoNSO','clearNSO')">
            <input type='button' id='NSO_change' style='display:none;' class='btn btn-primary btn-sm' value='Change NSO?' onclick='changePhoto("NSO","NSO","NSO_change")'>
        </div>
    </div>
    <script type="text/javascript">
        $('#NSO').val('');
        $('#clearNSO').hide();
        var childId = $("input[name = 'childId']").val();

        $('#photoNSO').removeAttr('src');
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('view_birthCert'); ?>",
            data: {
                childId: childId
            },
            success: function(data) {

                if (data != '') {
                    document.getElementById("photoNSO").src = "<?php echo 'http://' . $_SERVER['SERVER_ADDR'] . ":" . $_SERVER['SERVER_PORT'] . '/hrms/promo-nesco/'; ?>" + data;
                    $('#NSO').hide();
                    $('#NSO_change').show();
                } else {
                    $('#NSO_change').hide();
                    $('#NSO').show();
                }
            }
        });
    </script>
    <?php

} else if ($request == "add_children_info") {

    $counter = $fetch['counter'];
    for ($i = 0; $i < $fetch['no_of_child']; $i++) {

        $counter++;
    ?>
        <tr id="tr_<?php echo $counter; ?>">

            <input type="hidden" name="deleted[]" class="deleted_<?php echo $counter; ?>" value="">
            <input type="hidden" name="deceased1[]" class="deceasedChild_<?php echo $counter; ?>" value="">
            <td><input type="text" name="fname1[]" class="fname_<?php echo $counter; ?>" onkeyup="fname(<?php echo $counter; ?>)"></td>
            <td><input type="text" name="mname1[]"></td>
            <td><input type="text" name="lname1[]" class="lname_<?php echo $counter; ?>" onkeyup="lname(<?php echo $counter; ?>)"></td>
            <td><input type="text" name="bday1[]" class="bday_<?php echo $counter; ?> datepicker" onchange="get_age(this.value, '<?php echo $counter; ?>')" data-inputmask='"mask": "99/99/9999"' data-mask=""></td>
            <td><input type="text" class="updAge_<?php echo $counter; ?>" disabled=""></td>
            <td>
                <select name="gender1[]" class="gender_<?php echo $counter; ?>" onchange="gender(<?php echo $counter; ?>)">
                    <option value=""> -- Select -- </option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </td>
            <td><input type="checkbox" id="deceasedChild_<?php echo $counter; ?>" value="deceased" onchange="deceasedChild(<?php echo $counter; ?>)"></td>
            <td><a href="javascript:del_child(<?php echo $counter; ?>)"><i class="fa fa-trash text-red"></i></a></td>
        </tr>
        <script type="text/javascript">
            $('.datepicker').datepicker({
                inline: true,
                changeYear: true,
                changeMonth: true
            });

            $("[data-mask]").inputmask();
        </script>
    <?php
    }

    echo "|" . $counter;
} else if ($request == "seminar_form") {

    $row = $this->employee_model->seminar_info($fetch['no']);
    $name = $row['name'];
    $dates = $row['dates'];
    $location = $row['location'];
    $sem_certificate = $row['sem_certificate'];

    ?>
    <input type="hidden" name="appId" value="<?php echo $fetch['empId']; ?>">
    <input type="hidden" name="no" value="<?php echo $fetch['no']; ?>">
    <div class="form-group"> <i class="text-red">*</i>
        <label>Name</label>
        <input type="text" name="semName" value="<?php echo $name; ?>" class="form-control" onkeyup="inputField(this.name)" autocomplete="off">
    </div>
    <div class="form-group"> <i class="text-red">*</i>
        <label>Location</label>
        <input type="text" name="semLocation" value="<?php echo $location; ?>" class="form-control" onkeyup="inputField(this.name)" autocomplete="off">
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Date</label>
                <input type="text" name="semDate" value="<?php echo $dates; ?>" class="form-control" onchange="inputField(this.name)">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Certificate</label> <?php if (!empty($sem_certificate)) : echo "<i class='text-red'> - Already Uploaded</i>";
                                            endif; ?>
                <input type="file" name="semCertificate" class="form-control" onchange="inputField(this.name)">
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $('.datepicker').datepicker({
            inline: true,
            changeYear: true,
            changeMonth: true
        });
    </script>
<?php
} else if ($request == "character_ref_form") {

    $row = $this->employee_model->character_ref_info($fetch);

    $name = $row['name'];
    $position = $row['position'];
    $contactno = $row['contactno'];
    $company = $row['company'];
?>
    <input type="hidden" name="no" value="<?php echo $fetch['no']; ?>">
    <input type="hidden" name="appId" value="<?php echo $fetch['empId']; ?>">
    <div class="form-group"> <i class="text-red">*</i>
        <label>Name</label>
        <input type="text" name="charName" value="<?php echo $name; ?>" class="form-control" onkeyup="inputField(this.name)" autocomplete="off">
    </div>
    <div class="form-group"> <i class="text-red">*</i>
        <label>Company / Location</label>
        <input type="text" name="charCompanyLocation" value="<?php echo $company; ?>" class="form-control" onkeyup="inputField(this.name)" autocomplete="off">
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group"> <i class="text-red">*</i>
                <label>Position</label>
                <input type="text" name="charPosition" value="<?php echo $position; ?>" class="form-control" onkeyup="inputField(this.name)">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Contact Number</label>
                <input type="text" name="charContact" class="form-control" value="<?php echo $contactno; ?>" onkeyup="inputField(this.name)">
            </div>
        </div>
    </div>
<?php
} else if ($request == "appraisal_details") {

    $detailsId = $fetch['detailsId'];

    $r = $this->employee_model->appraisal_info($detailsId);

    $numrate = $r['numrate'];
    $ratercomment = $r['ratercomment'];
    $rateecomment = $r['rateecomment'];

    switch ($r['descrate']) {
        case "E":
            $descrate = "Excellent";
            break;
        case "VS":
            $descrate = "Very Satisfactory";
            break;
        case "S":
            $descrate = "Satisfactory";
            break;
        case "US":
            $descrate = "Unsatisfactory";
            break;
        case "VU":
            $descrate = "Very Unsatisfactory";
            break;
    }

    $query = $this->employee_model->appraisal_rate($detailsId);

?>
    <div class="table-height">
        <table class="table table-bordered" width="100%">
            <thead>
                <tr>
                    <th colspan="3">GUIDE QUESTIONS</th>
                    <th>RATE</th>
                </tr>
            </thead>
            <tbody>
                <?php

                foreach ($query as $row) {

                    echo "
                                <tr>
                                    <td colspan='3'>" . $row['q_no'] . ". " . $row['title'] . "<br><small>" . $row['description'] . "</small></td>
                                    <th>" . $row['rate'] . "</th>
                                </tr>
                            ";
                }

                echo "
                            <tr>
                                <th width='20%'>Descriptive Rating</th>
                                <td widtd='45%'>" . $descrate . "</th>
                                <th width=''>Numerical Rating</th>
                                <th width=''>" . $numrate . "</th>
                            </tr>
                            <tr>
                                <th>Rater's Comment</th>
                                <td colspan='3'>
                                    <textarea class='form-control' readonly=''>" . $ratercomment . "</textarea>
                                </td>
                            </tr>
                            <tr>
                                <th>Ratee's Comment</th>
                                <td colspan='3'>
                                    <textarea class='form-control' readonly=''>" . $rateecomment . "</textarea>
                                </td>
                            </tr>
                        ";
                ?>
            </tbody>
        </table>
    </div>
<?php
} else if ($request == "examDetails") {

    $examVal = explode("|", $fetch['examVal']);
    $empId         = $examVal[0];
    $examcode     = $examVal[1];
    $query1     = "SELECT exam_codename FROM application_examtypes WHERE exam_code='$examcode'";
    $rw1         = $this->employee_model->return_row_array($query1);
    $codename     = $rw1['exam_codename'];
    $query3     = "SELECT * FROM application_examtypes WHERE exam_code='$examcode'";

    echo "
    <h4>$codename</h4>
    <table class='table'>
        <thead>
            <tr>
                <th>Exam Type</th>
                <th>Score</th>
                <th>Norm</th>
            </tr>
        </thead>
        <tbody>
        ";

    $overall = 0;
    $result3 = $this->employee_model->return_result_array($query3);
    foreach ($result3 as $r3) {

        $extype = $r3['exam_type'];

        if ($extype == "EXB") {
            $overall = 28;
        } elseif ($extype == "ACCP-A" || $extype == "ACCP-B") {
            $overall = 10;
        } elseif ($extype == "AIT-A") {
            $overall = 60;
        } elseif ($extype == "AIT-B") {
            $overall = 50;
        } elseif ($extype == "FIT") {
            $overall = 12;
        } elseif ($extype == "NTA" || $extype == "VAT") {
            $overall = 25;
        } elseif ($extype == "STAR" || $extype == "SACHS") {
            $overall = 0;
        } else {
            $overall = 0;
        }

        $query2 = "SELECT * FROM application_examdetails WHERE exam_ref LIKE '%$empId' AND exam_type='$extype'";
        $retctr = 0;

        $result2 = $this->employee_model->return_result_array($query2);
        foreach ($result2 as $r2) {

            $retctr++;
            $exscore = $r2['exam_score'] . " / " . $overall;

            $query4 = "SELECT * FROM application_exam_norms WHERE n_type = '$extype'";
            $num4 = $this->employee_model->return_num_rows($query4);

            $norm = "N/A";
            if ($num4 > 0) {

                $result4 = $this->employee_model->return_result_array($query4);
                foreach ($result4 as $row4) {

                    if (intval($exscore) >= $row4['n_low'] && intval($exscore) <= $row4['n_high']) {

                        $norm = $row4['n_desc'];
                    }
                }
            }

            echo "              
                    <tr>
                          <td>" . $extype;
            if ($retctr > 1) {
                echo " - $retctr(retake)";
            }
            echo "</td>
                          <td>$exscore</td>
                          <td>" . $norm . "</td>
                    </tr>
                    ";
        }
    }

    $query5 = "SELECT result FROM application_exams2take WHERE app_id='$empId' AND exam_cat='$examcode'";
    $row5 = $this->employee_model->return_row_array($query5);

    echo "		
            <tr>
                <td>
                    <label>Exam result: &ensp;&ensp;</label>";
    $result5 = $row5['result'];
    if ($result5 == "passed") {
        echo "<label class='label label-success'>Passed</label>";
    } else if ($result5 == "assessment") {
        echo "<label class='label label-information'>For Assessment</label>";
    } else if ($result5 == "failed") {
        echo "<label class='label label-danger'>Failed</label>";
    }
    echo "  
                </td>
                <td></td>
                <td align='center'></td>
              </tr>
        </tbody>
    </table>";
} else if ($request == "appHistDetails") {

    $empId = $fetch['empId'];
    $sql = "SELECT * from application_history where app_id = '$empId' ORDER BY no DESC";
    $result = $this->employee_model->return_result_array($sql);

?>
    <div class="table-height">
        <table class="table table-striped" width="96%">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Date Accomplished</th>
                    <th>Description</th>
                    <th>Applying For</th>
                    <th>Status</th>
                    <th>Phases / Process</th>
                </tr>
            </thead>
            <tbody>
                <?php

                $result_num = $this->employee_model->return_num_rows($sql) + 1;
                foreach ($result as $row) {

                    $result_num--;
                    echo "
                                <tr>
                                    <td>" . $result_num . "</td>
                                    <td>" . date("M. d, Y", strtotime($row['date_time'])) . "</td>
                                    <td>" . $row['description'] . "</td>
                                    <td>" . $row['position'] . "</td>
                                    <td>" . $row['status'] . "</td>
                                    <td>" . $row['phase'] . "</td>
                                </tr>
                                ";
                }
                ?>
            </tbody>
        </table>
    </div>
<?php
} else if ($request == "interviewDetails") {

    $empId = $fetch['empId'];

    echo "
        <div class='table-height'>
        <table class='table table-bordered' width='100%'>";

    $query = "SELECT distinct(application_interview_details_history.group) FROM application_interview_details_history WHERE interviewee_id = '$empId' ORDER BY application_interview_details_history.group DESC";

    if ($this->employee_model->return_num_rows($query) > 0) {

        //if kung naay interview history
        $sql = "SELECT distinct(application_interview_details_history.group) FROM application_interview_details_history WHERE interviewee_id = '$empId' ORDER BY application_interview_details_history.group DESC";
    } else {

        //else kung walay interview history
        $sql = "SELECT distinct(application_interview_details.group) FROM application_interview_details WHERE interviewee_id ='$empId' ORDER BY application_interview_details.group DESC";
    }

    if ($this->employee_model->return_num_rows($sql) > 0) {
        $result = $this->employee_model->return_result_array($sql);
        foreach ($result as $row) {

            $sqls = "SELECT * FROM application_interview_details_history WHERE interviewee_id = '$empId' and application_interview_details_history.group= '" . $row['group'] . "' ORDER BY interviewee_level ASC";
            if ($this->employee_model->return_num_rows($sqls) > 0) {

                echo "
                      <tr bgcolor='#CCCCCC'>
                        <th colspan='4'>Date Interviewed - ";

                $sqly = "SELECT distinct(date_interviewed) FROM application_interview_details_history WHERE application_interview_details_history.group = '" . $row['group'] . "'";
                $rowy = $this->employee_model->return_row_array($sqly);

                $date_interviewed = "";
                if ($rowy['date_interviewed'] != "" && $rowy['date_interviewed'] != "0000-00-00") {

                    $date_interviewed = date('F d, Y', strtotime($rowy['date_interviewed']));
                }
                echo $date_interviewed;

                echo "</th>
                    </tr>
                    <tr>    			
                        <th>Interview Code</th>
                        <th>Interviewer</th>
                        <th>Status</th>
                        <th>Remarks</th>
                    </tr>";

                $result2 = $this->employee_model->return_result_array($sqls);
                foreach ($result2 as $rows) {

                    $go = $rows['interviewee_id'] . "/" . $rows['interviewee_level'] . "/" . $rows['interview_code'];
                    echo
                    "<tr>      			
                                  <td>" . $rows['interview_code'] . "</td>
                                  <td>&nbsp;&nbsp;";

                    $emp = "SELECT distinct(name) FROM employee3 WHERE emp_id= '" . $rows['interviewer_id'] . "'";
                    if ($this->employee_model->return_num_rows($emp) > 0) {

                        $em = $this->employee_model->return_row_array($emp);
                        echo $em['name'];
                    } else {

                        $sql2 = "SELECT name,position FROM users4owner WHERE user_id = '" . $rows['interviewer_id'] . "'";
                        $tab = $this->employee_model->return_row_array($sql2);
                        echo $tab['name'];
                    }
                    echo
                    "</td>
                                  <td>&nbsp;&nbsp;" . $rows['interview_status'] . "</td>
                                  <td><p align='justify' style='padding:10px'>" . nl2br(trim($rows['interviewer_remarks'])) . "</p></td>
                            </tr>";
                }
            }
        }
    } else {
        echo "
                  <tr bgcolor='#CCCCCC'>
                    <th colspan='4'>Date Interviewed</th>
                  </tr>
                <tr>    			
                    <th>Interview Code</th>
                    <th>Interviewer</th>
                    <th>Status</th>
                    <th>Remarks</th>
                </tr>";
    }
    echo "</table></div>";
} else if ($request == "contractDetails") {

    $contract = $fetch['contract'];
    $recordNo = $fetch['recordNo'];
    $empId     = $fetch['empId'];

    if ($contract == "current") :

        $table = "employee3";
        $posDesc = "position_desc";
    else :

        $table = "employmentrecord_";
        $posDesc = "pos_desc";
    endif;

    $sql = "SELECT record_no, startdate, eocdate, emp_type, current_status, company_code, bunit_code, dept_code, section_code, sub_section_code, unit_code, positionlevel, position, $posDesc, lodging, comments, contract, permit, epas_code, clearance, remarks 
                            FROM `$table` 
                                WHERE record_no = '$recordNo' AND emp_id = '$empId'";
    $row = $this->employee_model->return_row_array($sql);

    if ($row['startdate'] == "0000-00-00") : $startdate = '';
    else : $startdate = date("m/d/Y", strtotime($row['startdate']));
    endif;
    if ($row['eocdate'] == "0000-00-00") : $eocdate = '';
    else : $eocdate = date("m/d/Y", strtotime($row['eocdate']));
    endif;

    $sql2 = "SELECT date_hired FROM application_details WHERE app_id = '" . $empId . "'";
    $dateHired = $this->employee_model->return_row_array($sql2)['date_hired'];

?>
    <div class="table-height">
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <td width="20%">Position</td>
                    <th width="30%"><?php echo $row['position']; ?></th>
                    <td width="20%">Current Status</td>
                    <th width="30%"><?php echo $row['current_status']; ?></th>
                </tr>
                <tr>
                    <td>Position Level</td>
                    <th><?php echo $row['positionlevel']; ?></th>
                    <td>Startdate</td>
                    <th><?php echo $startdate; ?></th>
                </tr>
                <tr>
                    <td>Position Description</td>
                    <th><?php echo $row[$posDesc]; ?></th>
                    <td>EOCdate</td>
                    <th><?php echo $eocdate; ?></th>
                </tr>
                <tr>
                    <td>Company</td>
                    <th><?php echo $this->employee_model->asc_company_name($row['company_code'])['company']; ?></th>
                    <td>Lodging</td>
                    <th><?php echo $row['lodging']; ?></th>
                </tr>
                <tr>
                    <td>Business Unit</td>
                    <th><?php echo $this->employee_model->get_businessunit_name($row['company_code'], $row['bunit_code'])['business_unit']; ?></th>
                    <td>Remarks</td>
                    <th><?php echo $row['remarks']; ?></th>
                </tr>
                <tr>
                    <td>Department</td>
                    <th><?php echo $this->employee_model->get_department_name($row['company_code'], $row['bunit_code'], $row['dept_code'])['dept_name']; ?></th>
                    <td>Comments</td>
                    <th><?php echo $row['comments']; ?></th>
                </tr>
                <tr>
                    <td>Section</td>
                    <th><?php echo $this->employee_model->get_section_name($row['company_code'], $row['bunit_code'], $row['dept_code'], $row['section_code'])['section_name']; ?></th>
                    <td>Clearance</td>
                    <th>
                        <?php

                        if ($row['clearance'] != "") {
                            echo "<button class='btn btn-primary btn-sm btn-flat btn-block' onclick='viewFile(\"get_file\",\"$table\",\"clearance\",\"$empId\",\"$recordNo\")'>View Clearance</button>";
                        }
                        ?>
                    </th>
                </tr>
                <tr>
                    <td>Sub Section</td>
                    <th><?php echo $this->employee_model->get_sub_section_name($row['company_code'], $row['bunit_code'], $row['dept_code'], $row['section_code'], $row['sub_section_code'])['sub_section_name']; ?></th>
                    <td>Epas</td>
                    <th>
                        <?php

                        $epas = "SELECT details_id, numrate, descrate FROM `appraisal_details` WHERE emp_id ='$empId' AND record_no = '$recordNo' AND store = ''";
                        $epasNum = $this->employee_model->return_num_rows($epas);
                        $e = $this->employee_model->return_row_array($epas);

                        if ($epasNum == 1) : echo "<button class='btn btn-primary btn-sm btn-flat btn-block' onclick='previewAppraisalDetails(\"$e[details_id]\")'>" . $e['numrate'] . " - " . $e['descrate'] . " &nbsp;[ View Epas ]</button>";
                        endif;
                        ?>
                    </th>
                </tr>
                <tr>
                    <td>Unit</td>
                    <th><?php echo $this->employee_model->get_unit_name($row['company_code'], $row['bunit_code'], $row['dept_code'], $row['section_code'], $row['sub_section_code'], $row['unit_code'])['unit_name']; ?></th>
                    <td>Contract</td>
                    <th>
                        <?php

                        if ($row['contract'] != "") {
                            echo "<button class='btn btn-primary btn-sm btn-flat btn-block' onclick='viewFile(\"get_file\",\"$table\",\"contract\",\"$empId\",\"$recordNo\")'>View Contract</button>";
                        }
                        ?>
                    </th>
                </tr>
                <tr>
                    <td>Employee Type</td>
                    <th><?php echo $row['emp_type']; ?></th>
                    <td>Date Regular</td>
                    <th>
                        <?php

                        if ($row['emp_type'] == 'Regular') {
                            echo date('m/d/Y', strtotime($startdate));
                        }
                        ?>
                    </th>
                </tr>
                <tr>
                    <td>Record No</td>
                    <th><?php echo $row['record_no']; ?></th>
                    <td>Date Hired</td>
                    <th>
                        <?php

                        echo date('m/d/Y', strtotime($dateHired));
                        ?>
                    </th>
                </tr>
            </tbody>
        </table>
    </div>
<?php
} else if ($request == "get_file") {

    $sql = "SELECT " . $fetch['field'] . " FROM " . $fetch['table'] . " WHERE emp_id = '" . $fetch['empId'] . "' AND record_no = '" . $fetch['recordNo'] . "'";
    $row  = $this->employee_model->return_row_array($sql);

    die($row[$fetch['field']]);
} else if ($request == "promoFile") {

    $sql = "SELECT " . $fetch['field'] . " FROM " . $fetch['table'] . " WHERE emp_id = '" . $fetch['empId'] . "' AND record_no = '" . $fetch['recordNo'] . "'";
    $row  = $this->employee_model->return_row_array($sql);

    die($row[$fetch['field']]);
} else if ($request == "editContractDetails") {

    $contract = $fetch['contract'];
    $recordNo = $fetch['recordNo'];
    $empId = $fetch['empId'];

    if ($contract == "current") :

        $table = "employee3";
        $posDesc = "position_desc";
    else :

        $table = "employmentrecord_";
        $posDesc = "pos_desc";
    endif;

    $sql = "SELECT record_no, startdate, eocdate, emp_type, current_status, company_code, bunit_code, dept_code, section_code, sub_section_code, unit_code, positionlevel, position, $posDesc, lodging, comments, contract, permit, epas_code, clearance, remarks 
                            FROM `$table` 
                                WHERE record_no = '$recordNo' AND emp_id = '$empId'";
    $row = $this->employee_model->return_row_array($sql);

    if ($row['startdate'] == "0000-00-00") : $startdate = '';
    else : $startdate = date("m/d/Y", strtotime($row['startdate']));
    endif;
    if ($row['eocdate'] == "0000-00-00" || $row['eocdate'] == "1970-01-01") : $eocdate = '';
    else : $eocdate = date("m/d/Y", strtotime($row['eocdate']));
    endif;

?>
    <input type="hidden" name="contract" value="<?php echo $contract; ?>">
    <input type="hidden" name="empId" value="<?php echo $empId; ?>">
    <input type="hidden" name="recordNo" value="<?php echo $recordNo; ?>">
    <div class="row">
        <div class="col-md-4">
            <div class="form-group"> <i class="text-red">*</i>
                <label>Company</label>
                <select name="company" class="form-control" onchange="getBusinessUnit(this.value)">
                    <option value=""> --Select Company-- </option>
                    <?php

                    $result = $this->employee_model->ae_company_list();
                    foreach ($result as $res) { ?>

                        <option value="<?php echo $res['company_code']; ?>" <?php if ($row['company_code'] == $res['company_code']) : echo "selected=''";
                                                                            endif; ?>><?php echo $res['company']; ?></option> <?php
                                                                                                                            }
                                                                                                                                ?>
                </select>
            </div>
            <div class="form-group"> <i class="text-red">*</i>
                <label>Business Unit</label>
                <select name="businessUnit" class="form-control" onchange="getDepartment(this.value)">
                    <option value=""> --Select Business Unit-- </option>
                    <?php

                    $sql = "SELECT * FROM locate_business_unit WHERE company_code = '" . $row['company_code'] . "' ORDER BY business_unit ASC";
                    $result = $this->employee_model->return_result_array($sql);
                    foreach ($result as $res) { ?>

                        <option value="<?php echo $res['company_code'] . '/' . $res['bunit_code']; ?>" <?php if ($row['bunit_code'] == $res['bunit_code']) : echo "selected=''";
                                                                                                        endif; ?>><?php echo $res['business_unit']; ?></option> <?php
                                                                                                                                                            }
                                                                                                                                                                ?>
                </select>
            </div>
            <div class="form-group"> <i class="text-red">*</i>
                <label>Department</label>
                <select name="department" class="form-control" onchange="getSection(this.value)">
                    <option value=""> --Select Department-- </option>
                    <?php

                    $sql = "SELECT * FROM locate_department WHERE company_code = '" . $row['company_code'] . "' AND bunit_code = '" . $row['bunit_code'] . "' ORDER BY dept_name ASC";
                    $result = $this->employee_model->return_result_array($sql);
                    foreach ($result as $res) { ?>

                        <option value="<?php echo $res['company_code'] . '/' . $res['bunit_code'] . '/' . $res['dept_code']; ?>" <?php if ($row['dept_code'] == $res['dept_code']) : echo "selected=''";
                                                                                                                                    endif; ?>><?php echo $res['dept_name']; ?></option> <?php
                                                                                                                                                                                    }
                                                                                                                                                                                        ?>
                </select>
            </div>
            <div class="form-group">
                <label>Section</label>
                <select name="section" class="form-control" onchange="getSubSection(this.value)">
                    <option value=""> --Select Section-- </option>
                    <?php

                    $sql = "SELECT * FROM locate_section WHERE company_code = '" . $row['company_code'] . "' AND bunit_code = '" . $row['bunit_code'] . "' AND dept_code = '" . $row['dept_code'] . "' ORDER BY section_name ASC";
                    $result = $this->employee_model->return_result_array($sql);
                    foreach ($result as $res) { ?>

                        <option value="<?php echo $res['company_code'] . '/' . $res['bunit_code'] . '/' . $res['dept_code'] . '/' . $res['section_code']; ?>" <?php if ($row['section_code'] == $res['section_code']) : echo "selected=''";
                                                                                                                                                                endif; ?>><?php echo $res['section_name']; ?></option> <?php
                                                                                                                                                                                                                    }
                                                                                                                                                                                                                        ?>
                </select>
            </div>
            <div class="form-group">
                <label>Sub-section</label>
                <select name="subSection" class="form-control" onchange="getUnit(this.value)">
                    <option value=""> --Select Sub-section-- </option>
                    <?php

                    $sql = "SELECT * FROM locate_sub_section WHERE company_code = '" . $row['company_code'] . "' AND bunit_code = '" . $row['bunit_code'] . "' AND dept_code = '" . $row['dept_code'] . "' AND section_code = '" . $row['section_code'] . "' ORDER BY sub_section_name ASC";
                    $result = $this->employee_model->return_result_array($sql);
                    foreach ($result as $res) { ?>

                        <option value="<?php echo $res['company_code'] . '/' . $res['bunit_code'] . '/' . $res['dept_code'] . '/' . $res['section_code'] . '/' . $res['sub_section_code']; ?>" <?php if ($row['sub_section_code'] == $res['sub_section_code']) : echo "selected=''";
                                                                                                                                                                                                endif; ?>><?php echo $res['sub_section_name']; ?></option> <?php
                                                                                                                                                                                                                                                        }
                                                                                                                                                                                                                                                            ?>
                </select>
            </div>
            <div class="form-group">
                <label>Unit</label>
                <select name="unit" class="form-control">
                    <option value=""> --Select Unit-- </option>
                    <?php

                    $sql = "SELECT * FROM locate_unit WHERE company_code = '" . $row['company_code'] . "' AND bunit_code = '" . $row['bunit_code'] . "' AND dept_code = '" . $row['dept_code'] . "' AND section_code = '" . $row['section_code'] . "' AND sub_section_code = '" . $row['sub_section_code'] . "' ORDER BY unit_name ASC";
                    $result = $this->employee_model->return_result_array($sql);
                    foreach ($result as $res) { ?>

                        <option value="<?php echo $res['company_code'] . '/' . $res['bunit_code'] . '/' . $res['dept_code'] . '/' . $res['section_code'] . '/' . $res['sub_section_code'] . '/' . $res['unit_code']; ?>" <?php if ($row['unit_code'] == $res['unit_code']) : echo "selected=''";
                                                                                                                                                                                                                            endif; ?>><?php echo $res['unit_name']; ?></option> <?php
                                                                                                                                                                                                                                                                            }
                                                                                                                                                                                                                                                                                ?>
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group"> <i class="text-red">*</i>
                <label>Startdate</label>
                <div class="input-group">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </div>
                    <input type="text" name="startdate" value="<?php echo $startdate; ?>" onkeyup="inputField(this.name)" class="form-control datepicker" placeholder="mm/dd/yyyy" style="position: relative; z-index: 100000;" data-inputmask='"mask": "99/99/9999"' data-mask>
                </div>
            </div>
            <div class="form-group"> <?php if ($contract == "previous") : echo "<i class='text-red'>*</i>";
                                        endif; ?>
                <label>EOCdate</label>
                <div class="input-group">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </div>
                    <input type="text" name="eocdate" value="<?php echo $eocdate; ?>" onkeyup="inputField(this.name)" class="form-control datepicker" placeholder="mm/dd/yyyy" style="position: relative; z-index: 100000;" data-inputmask='"mask": "99/99/9999"' data-mask>
                </div>
            </div>
            <div class="form-group"> <i class="text-red">*</i>
                <label>Position</label>
                <select name="position" class="form-control" onchange="position_level(this.value)">
                    <option value=""> --Select-- </option>
                    <?php

                    $positions = $this->employee_model->list_of_positions();
                    foreach ($positions as $pos) { ?>

                        <option value="<?php echo $pos['position_title']; ?>" <?php if ($row['position'] == $pos['position_title']) : echo "selected=''";
                                                                                endif; ?>><?php echo $pos['position_title']; ?></option> <?php
                                                                                                                                        }
                                                                                                                                            ?>
                </select>
            </div>
            <div class="form-group"> <i class="text-red">*</i>
                <label>Employee Type</label>
                <select name="empType" class="form-control" onchange="inputField(this.name)">
                    <option value=""> --Select-- </option>
                    <?php

                    $emp_types = $this->employee_model->employee_type();
                    foreach ($emp_types as $r) { ?>

                        <option value="<?php echo $r['emp_type']; ?>" <?php if ($row['emp_type'] == $r['emp_type']) : echo "selected=''";
                                                                        endif; ?>><?php echo $r['emp_type']; ?></option> <?php
                                                                                                                        }
                                                                                                                            ?>
                </select>
            </div>
            <div class="form-group"> <i class="text-red">*</i>
                <label>Current Status</label>
                <select name="current_status" class="form-control" <?php if (($row['current_status'] == 'blacklisted' || $row['current_status'] == "Blacklisted") && $this->employee_model->loginId != "06359-2013") : echo 'disabled=""';
                                                                    endif; ?> onchange="inputField(this.name)">
                    <option value=""> --Select-- </option>
                    <option <?php if ($row['current_status'] == 'Active') : ?>selected<?php endif; ?>>Active</option>
                    <option <?php if ($row['current_status'] == 'End of Contract') : ?>selected<?php endif; ?>>End of Contract</option>
                    <option <?php if ($row['current_status'] == 'Resigned') : ?>selected<?php endif; ?>>Resigned</option>
                    <option <?php if ($row['current_status'] == 'For Promotion') : ?>selected<?php endif; ?>>For Promotion</option>
                    <option <?php if (($row['current_status'] == 'blacklisted' || $row['current_status'] == "Blacklisted") && $this->employee_model->loginId != "06359-2013") : ?>selected<?php endif; ?>>blacklisted</option>
                    <?php if ($this->employee_model->loginId == "06359-2013") { ?>
                        <option <?php if ($row['current_status'] == 'blacklisted' || $row['current_status'] == "Blacklisted") : ?>selected<?php endif; ?>>blacklisted</option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <label>Position Level</label>
                <input type="hidden" name="posLevel" class="posLevel" value="<?= $row['positionlevel'] ?>">
                <input type="text" class="form-control posLevel" value="<?= $row['positionlevel'] ?>" readonly>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>Lodging</label>
                <select name="lodging" class="form-control">
                    <option value=""> --Select-- </option>
                    <option value="Stay-in" <?php if ($row['lodging'] == "Stay-in") : echo "selected=''";
                                            endif; ?>>Stay-in</option>
                    <option value="Stay-out" <?php if ($row['lodging'] == "Stay-out") : echo "selected=''";
                                                endif; ?>>Stay-out</option>
                </select>
            </div>
            <div class="form-group">
                <label>Position Description</label>
                <textarea name="posDesc" class="form-control" rows="5"><?php echo $row[$posDesc]; ?></textarea>
            </div>
            <div class="form-group">
                <label>Remarks</label>
                <textarea name="remarks" class="form-control" rows="5"><?php echo $row['remarks']; ?></textarea>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $('.datepicker').datepicker({
            inline: true,
            changeYear: true,
            changeMonth: true
        });

        $("[data-mask]").inputmask();
    </script>
    <?php
} else if ($request == "locate_business_unit") {

    echo '<option value = ""> Select Business Unit </option>';
    foreach ($fetch as $row) {
    ?>

        <option value="<?php echo $row['company_code'] . '/' . $row['bunit_code']; ?>"><?php echo $row['business_unit']; ?></option>
    <?php
    }
} else if ($request == "locate_department") {

    echo '<option value = ""> Select Department </option>';
    foreach ($fetch as $row) {
    ?>

        <option value="<?php echo $row['company_code'] . '/' . $row['bunit_code'] . '/' . $row['dept_code']; ?>"><?php echo $row['dept_name']; ?></option>
    <?php
    }
} else if ($request == "locate_section") {

    echo '<option value = ""> Select Section </option>';
    foreach ($fetch as $row) {
    ?>

        <option value="<?php echo $row['company_code'] . '/' . $row['bunit_code'] . '/' . $row['dept_code'] . '/' . $row['section_code']; ?>"><?php echo $row['section_name']; ?></option>
    <?php
    }
} else if ($request == "locate_sub_section") {

    echo '<option value = ""> Select Sub-section </option>';
    foreach ($fetch as $row) {
    ?>

        <option value="<?php echo $row['company_code'] . '/' . $row['bunit_code'] . '/' . $row['dept_code'] . '/' . $row['section_code'] . '/' . $row['sub_section_code']; ?>"><?php echo $row['sub_section_name']; ?></option>
    <?php
    }
} else if ($request == "locate_unit") {

    echo '<option value = ""> Select Unit </option>';
    foreach ($fetch as $row) {
    ?>

        <option value="<?php echo $row['company_code'] . '/' . $row['bunit_code'] . '/' . $row['dept_code'] . '/' . $row['section_code'] . '/' . $row['sub_section_code'] . '/' . $row['unit_code']; ?>"><?php echo $row['unit_name']; ?></option>
    <?php
    }
} else if ($request == "uploadScannedFileForm") {

    $contract = $fetch['contract'];
    $recordNo = $fetch['recordNo'];
    $empId = $fetch['empId'];

    if ($contract == "current") :

        $table = "employee3";
    else :

        $table = "employmentrecord_";
    endif;

    ?>
    <input type="hidden" name="contract" value="<?php echo $contract; ?>">
    <input type="hidden" name="empId" value="<?php echo $empId; ?>">
    <input type="hidden" name="recordNo" value="<?php echo $recordNo; ?>">

    <div class="row">
        <div class="col-md-4">
            <b>Clearance</b><br>
            <img id="photoclearance" class='preview img-responsive' /><br>
            <input type='file' name='clearance' id='clearance' class='btn btn-default' onchange='readURL(this,"clearance");'>
            <input type='button' name='clearclearance' id='clearclearance' style='display:none' class='btn btn-default' value='Clear' onclick="clears('clearance','photoclearance','clearclearance')">
            <input type='button' id='clearance_change' style='display:none;' class='btn btn-primary btn-sm' value='Change Clearance?' onclick='changePhoto("Clearance","clearance","clearance_change")'>
        </div>
        <div class="col-md-4">
            <b>Contract</b><br>
            <img id="photocontract" class='preview img-responsive' /><br>
            <input type='file' name='contract' id='contract' class='btn btn-default' onchange='readURL(this,"contract");'>
            <input type='button' name='clearcontract' id='clearcontract' style='display:none' class='btn btn-default' value='Clear' onclick="clears('contract','photocontract','clearcontract')">
            <input type='button' id='contract_change' style='display:none;' class='btn btn-primary btn-sm' value='Change Contract?' onclick='changePhoto("Contract","contract","contract_change")'>
        </div>
        <div class="col-md-4">
            <b>EOC Appraisal</b><br>
            <img id="photoepas" class='preview img-responsive' /><br>
            <input type='file' name='epas' id='epas' class='btn btn-default' onchange='readURL(this,"epas");'>
            <input type='button' name='clearepas' id='clearepas' style='display:none' class='btn btn-default' value='Clear' onclick="clears('epas','photoepas','clearepas')">
            <input type='button' id='epas_change' style='display:none;' class='btn btn-primary btn-sm' value='Change EOC Appraisal?' onclick='changePhoto("EOC Appraisal","epas","epas_change")'>
        </div>
    </div>
    <script type="text/javascript">
        var contract = $("input[name = 'contract']").val();
        var empId = $("input[name = 'empId']").val();
        var recordNo = $("input[name = 'recordNo']").val();

        if (contract == "current") {

            var table = "employee3";
        } else {

            var table = "employmentrecord_";
        }

        $('#epas').val('');
        $('#clerance').val('');
        $('#contract').val('');
        $('#clearepas').hide();
        $('#clearclerance').hide();
        $('#clearcontract').hide();

        $('#photoclearance').removeAttr('src');
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('get_file'); ?>",
            data: {
                table: table,
                recordNo: recordNo,
                empId: empId,
                field: "clearance"
            },
            success: function(data) {

                if (data != '') {
                    document.getElementById("photoclearance").src = "<?php echo 'http://' . $_SERVER['SERVER_ADDR'] . ":" . $_SERVER['SERVER_PORT'] . '/hrms/promo-nesco/'; ?>" + data;
                    $('#clearance').hide();
                    $('#clearance_change').show();
                } else {
                    $('#clearance_change').hide();
                    $('#clearance').show();
                }
            }
        });

        $('#photoepas').removeAttr('src');
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('get_file'); ?>",
            data: {
                table: table,
                recordNo: recordNo,
                empId: empId,
                field: "epas_code"
            },
            success: function(data) {

                if (data != 0) {

                    if (data > 0) {

                        var alternative = '<?php echo 'http://' . $_SERVER['SERVER_ADDR'] . ":" . $_SERVER['SERVER_PORT'] . '/hrms/images/epas_msg.jpg'; ?>';
                        document.getElementById("photoepas").src = alternative;
                        $('#epas').hide();

                    } else {

                        document.getElementById("photoepas").src = "<?php echo 'http://' . $_SERVER['SERVER_ADDR'] . ":" . $_SERVER['SERVER_PORT'] . '/hrms/promo-nesco/'; ?>" + data;
                        $('#epas').hide();
                        $('#epas_change').show();
                    }
                } else {
                    $('#epas').show();
                }
            }
        });

        $('#photocontract').removeAttr('src');
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('get_file'); ?>",
            data: {
                table: table,
                recordNo: recordNo,
                empId: empId,
                field: "contract"
            },
            success: function(data) {

                if (data != '') {
                    document.getElementById("photocontract").src = "<?php echo 'http://' . $_SERVER['SERVER_ADDR'] . ":" . $_SERVER['SERVER_PORT'] . '/hrms/promo-nesco/'; ?>" + data;
                    $('#contract').hide();
                    $('#contract_change').show();
                } else {
                    $('#contract_change').hide();
                    $('#contract').show();
                }
            }
        });
    </script>
    <?php
} else if ($request == "uploadPromoScannedFileForm") {

    $contract = $fetch['contract'];
    $recordNo = $fetch['recordNo'];
    $empId = $fetch['empId'];

    $bUs = $this->dashboard_model->businessUnit_list();
    foreach ($bUs as $bu) {

        $hasBU = $this->dashboard_model->promo_has_store($contract, $empId, $recordNo, $bu->bunit_field);
        if ($hasBU > 0) {
    ?>
            <input type="hidden" name="epas[]" value="<?= $bu->bunit_epascode ?>">
            <input type="hidden" name="clearance[]" value="<?= $bu->bunit_clearance ?>">
            <input type="hidden" name="contract[]" value="<?= $bu->bunit_contract ?>">

            <div class="row">
                <div class="col-md-4">
                    <b><?= "Clearance ($bu->bunit_name)" ?></b><br>
                    <img id="photo<?= $bu->bunit_clearance ?>" class='preview img-responsive' /><br>
                    <input type='file' name='<?= $bu->bunit_clearance ?>' id='<?= $bu->bunit_clearance ?>' class='btn btn-default' onchange='readURL(this,"<?= $bu->bunit_clearance ?>")'>
                    <input type='button' name='clear<?= $bu->bunit_clearance ?>' id='clear<?= $bu->bunit_clearance ?>' style='display:none' class='btn btn-default' value='Clear' onclick="clears('<?= $bu->bunit_clearance ?>','photo<?= $bu->bunit_clearance ?>','clear<?= $bu->bunit_clearance ?>')">
                    <input type='button' id='<?= $bu->bunit_clearance ?>_change' style='display:none' class='btn btn-primary btn-sm' value='Change Clearance?' onclick='changePhoto("Clearance","<?= $bu->bunit_clearance ?>","<?= $bu->bunit_clearance; ?>_change")'>
                </div>
                <div class="col-md-4">
                    <b><?= "Contract ($bu->bunit_name)" ?></b><br>
                    <img id="photo<?= $bu->bunit_contract ?>" class='preview img-responsive' /><br>
                    <input type='file' name='<?= $bu->bunit_contract ?>' id='<?= $bu->bunit_contract ?>' class='btn btn-default' onchange='readURL(this,"<?= $bu->bunit_contract ?>")'>
                    <input type='button' name='clear<?= $bu->bunit_contract ?>' id='clear<?= $bu->bunit_contract ?>' style='display:none' class='btn btn-default' value='Clear' onclick="clears('<?= $bu->bunit_contract ?>','photo<?= $bu->bunit_contract ?>','clear<?= $bu->bunit_contract ?>')">
                    <input type='button' id='<?= $bu->bunit_contract ?>_change' style='display:none;' class='btn btn-primary btn-sm' value='Change Contract?' onclick='changePhoto("Contract","<?= $bu->bunit_contract ?>","<?= $bu->bunit_contract ?>_change")'>
                </div>
                <div class="col-md-4">
                    <b><?= "EOC Appraisal ($bu->bunit_name)" ?></b><br>
                    <img id="photo<?= $bu->bunit_epascode ?>" class='preview img-responsive' /><br>
                    <input type='file' name='<?= $bu->bunit_epascode ?>' id='<?= $bu->bunit_epascode ?>' class='btn btn-default' onchange='readURL(this,"<?= $bu->bunit_epascode ?>")'>
                    <input type='button' name='clear<?= $bu->bunit_epascode ?>' id='clear<?= $bu->bunit_epascode ?>' class='btn btn-default' value='Clear' style='display:none' onclick="clears('<?= $bu->bunit_epascode ?>','photo<?= $bu->bunit_epascode ?>','clear<?= $bu->bunit_epascode ?>')">
                    <input type='button' id='<?= $bu->bunit_epascode ?>_change' style='display:none;' class='btn btn-primary btn-sm' value='Change Epas?' onclick='changePhoto("Epas","<?= $bu->bunit_epascode ?>","<?= $bu->bunit_epascode ?>_change")'>
                </div>
            </div><br>
    <?php
        }
    }
    ?>
    <input type="hidden" name="contracta" value="<?= $contract ?>">
    <input type="hidden" name="empId" value="<?= $empId ?>">
    <input type="hidden" name="recordNo" value="<?= $recordNo ?>">
    <script>
        var contracta = $("[name = 'contracta']").val();
        var empId = $("[name = 'empId']").val();
        var recordNo = $("[name = 'recordNo']").val();

        if (contracta == 'current') {

            var table = 'promo_record';
        } else {

            var table = 'promo_history_record';
        }

        var epascode = document.getElementsByName('epas[]');
        var contracts = document.getElementsByName('contract[]');
        var clearances = document.getElementsByName('clearance[]');

        for (var i = 0; i < epascode.length; i++) {

            $('input#' + epascode[i].value).val('');
            $('input#' + clearances[i].value).val('');
            $('input#' + contracts[i].value).val('');
            $('input#clear' + epascode[i].value).hide();
            $('input#clear' + clearances[i].value).hide();
            $('input#clear' + contracts[i].value).hide();

            (function(i) {

                $('input#photo' + epascode[i].value).removeAttr('src');
                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('promoFile'); ?>",
                    data: {
                        table: table,
                        field: epascode[i].value,
                        empId: empId,
                        recordNo: recordNo
                    },
                    success: function(data) {

                        if (data != 0) {

                            if (data > 0) {

                                document.getElementById("photo" + epascode[i].value).src = "<?= base_url('assets/images/epas_msg.jpg') ?>";
                                $('input#' + epascode[i].value).hide();

                            } else {
                                document.getElementById("photo" + epascode[i].value).src = "<?php echo 'http://' . $_SERVER['SERVER_ADDR'] . ":" . $_SERVER['SERVER_PORT'] . '/hrms/promo-nesco/'; ?>" + data;
                                $('input#' + epascode[i].value).hide();
                                $('input#' + epascode[i].value + "_change").show();
                            }
                        } else {
                            $('input#' + epascode[i].value).show();
                        }
                    }
                });

                $('input#photo' + contracts[i].value).removeAttr('src');
                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('promoFile'); ?>",
                    data: {
                        table: table,
                        field: contracts[i].value,
                        empId: empId,
                        recordNo: recordNo
                    },
                    success: function(data) {

                        if (data != '') {
                            document.getElementById("photo" + contracts[i].value).src = "<?php echo 'http://' . $_SERVER['SERVER_ADDR'] . ":" . $_SERVER['SERVER_PORT'] . '/hrms/promo-nesco/'; ?>" + data;
                            $('input#' + contracts[i].value).hide();
                            $('input#' + contracts[i].value + "_change").show();
                        } else {
                            $('input#' + contracts[i].value + "_change").hide();
                            $('input#' + contracts[i].value).show();
                        }
                    }
                });

                $('input#photo' + clearances[i].value).removeAttr('src');
                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('promoFile'); ?>",
                    data: {
                        table: table,
                        field: clearances[i].value,
                        empId: empId,
                        recordNo: recordNo
                    },
                    success: function(data) {

                        if (data != '') {
                            document.getElementById("photo" + clearances[i].value).src = "<?php echo 'http://' . $_SERVER['SERVER_ADDR'] . ":" . $_SERVER['SERVER_PORT'] . '/hrms/promo-nesco/'; ?>" + data;
                            $('input#' + clearances[i].value).hide();
                            $('input#' + clearances[i].value + "_change").show();
                        } else {
                            $('input#' + clearances[i].value + "_change").hide();
                            $('input#' + clearances[i].value).show();
                        }
                    }
                });

            })(i);
        }
    </script>
<?php
} else if ($request == "promoContractDetails") {

    $contract = $fetch['contract'];
    $recordNo = $fetch['recordNo'];
    $empId     = $fetch['empId'];

    if ($contract == "current") :

        $table1 = "employee3";
        $table2 = "promo_record";
    else :

        $table1 = "employmentrecord_";
        $table2 = "promo_history_record";
    endif;

    $sql = "SELECT startdate, eocdate, current_status, position, remarks, promo_company, promo_department, promo_type, type 
                            FROM `$table1` INNER JOIN `$table2`
                                ON $table1.record_no = $table2.record_no AND $table1.emp_id = $table2.emp_id
                                    WHERE $table1.record_no = '$recordNo' AND $table1.emp_id = '$empId'";
    $row = $this->employee_model->return_row_array($sql);

    if ($row['startdate'] == "0000-00-00") : $startdate = '';
    else : $startdate = date("m/d/Y", strtotime($row['startdate']));
    endif;
    if ($row['eocdate'] == "0000-00-00") : $eocdate = '';
    else : $eocdate = date("m/d/Y", strtotime($row['eocdate']));
    endif;

?>
    <div class="table-height">
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <td width="28%">Employee ID</td>
                    <th width="22%"><?php echo $empId; ?></th>
                    <td width="28%">Record No.</td>
                    <th width="22%"><?php echo $recordNo; ?></th>
                </tr>
                <tr>
                    <td>Position</td>
                    <th><?php echo $row['position']; ?></th>
                    <td>Current Status</td>
                    <th><?php echo $row['current_status']; ?></th>
                </tr>
                <tr>
                    <td>Promo Type</td>
                    <th><?php echo $row['promo_type']; ?></th>
                    <td>Contract Type</td>
                    <th><?php echo $row['type']; ?></th>
                </tr>
                <tr>
                    <td>Startdate</td>
                    <th><?php echo $startdate; ?></td>
                    <td>EOCdate</td>
                    <th><?php echo $eocdate; ?></th>
                </tr>
                <tr>
                    <td>Company</td>
                    <th><?php echo $row['promo_company']; ?></th>
                    <td>Department</td>
                    <th><?php echo $row['promo_department']; ?></th>
                </tr>
                <?php

                $bUs = $this->dashboard_model->businessUnit_list();
                foreach ($bUs as $bu) {

                    $hasECCI = $this->dashboard_model->promo_has_ecci($table2, $recordNo, $empId, $bu->bunit_field, $bu->bunit_epascode, $bu->bunit_intro);
                    if ($hasECCI->num_rows() > 0) {

                        $promo_details = $hasECCI->row_array();
                        // query epascode

                        $displayEpas = '';
                        $epas_sql = "SELECT details_id, numrate, descrate FROM `appraisal_details` WHERE emp_id ='$empId' AND record_no = '$recordNo' AND store = '" . $bu->bunit_name . "'";
                        $epasNum = $this->employee_model->return_num_rows($epas_sql);
                        if ($epasNum > 0) {

                            $epas = $this->employee_model->return_row_array($epas_sql);
                            $displayEpas = "<button class='btn btn-primary btn-sm btn-flat btn-block' onclick='view_appraisal_details(\"$epas[details_id]\")'>" . $epas['numrate'] . " - " . $epas['descrate'] . " &nbsp;[ View Epas ]</button>";
                        } else {

                            if (!empty($promo_details[$bu->bunit_epascode]) && strpos($promo_details[$bu->bunit_epascode], '../document/') !== false) : $displayEpas = "<button class='btn btn-primary btn-sm btn-flat btn-block' onclick='viewFile(\"promoFile\",\"$table2\",\"$bu->bunit_intro\",\"$empId\",\"$recordNo\")'>View Intro</button>";
                            else : $displayEpas = "";
                            endif;
                        }

                        if (!empty($promo_details[$bu->bunit_intro]) && strpos($promo_details[$bu->bunit_intro], '../document/') !== false) : $displayIntro = "<button class='btn btn-primary btn-sm btn-flat btn-block' onclick='viewFile(\"promoFile\",\"$table2\",\"$bu->bunit_intro\",\"$empId\",\"$recordNo\")'>View Intro</button>";
                        else : $displayIntro = "";
                        endif;

                        echo "
                                <tr>
                                    <td> EPAS - " . ucwords(strtolower($bu->bunit_name)) . "</td>
                                    <td>" . $displayEpas . "</td>
                                    <td> Intro - " . ucwords(strtolower($bu->bunit_name)) . "</td>
                                    <td>" . $displayIntro . "</td>
                                </tr>
                            ";
                    }
                }

                $bUs = $this->dashboard_model->businessUnit_list();
                foreach ($bUs as $bu) {

                    $hasECCI = $this->dashboard_model->promo_has_ecci($table2, $recordNo, $empId, $bu->bunit_field, $bu->bunit_clearance, $bu->bunit_contract);
                    if ($hasECCI->num_rows() > 0) {

                        $promo_details = $hasECCI->row_array();
                        if (!empty($promo_details[$bu->bunit_clearance]) && strpos($promo_details[$bu->bunit_clearance], '../document/') !== false) : $displayClearance = "<button class='btn btn-primary btn-sm btn-flat btn-block' onclick='viewFile(\"promoFile\",\"$table2\",\"$bu->bunit_clearance\",\"$empId\",\"$recordNo\")'>View Clearance</button>";
                        else : $displayClearance = "";
                        endif;
                        if (!empty($promo_details[$bu->bunit_contract]) && strpos($promo_details[$bu->bunit_contract], '../document/') !== false) : $displayContract = "<button class='btn btn-primary btn-sm btn-flat btn-block' onclick='viewFile(\"promoFile\",\"$table2\",\"$bu->bunit_contract\",\"$empId\",\"$recordNo\")'>View Contract</button>";
                        else : $displayContract = "";
                        endif;

                        echo "
                                <tr>
                                    <td> Clearance - " . ucwords(strtolower($bu->bunit_name)) . "</td>
                                    <td>" . $displayClearance . "</td>
                                    <td> Contract - " . ucwords(strtolower($bu->bunit_name)) . "</td>
                                    <td>" . $displayContract . "</td>
                                </tr>
                            ";
                    }
                }

                ?>
                <tr>
                    <td>Remarks</td>
                    <td colspan="3">
                        <textarea class="form-control" readonly=""><?php echo $row['remarks']; ?></textarea>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
<?php
} else if ($request == 'editPromoContractDetails') {

    $contract = $fetch['contract'];
    $recordNo = $fetch['recordNo'];
    $empId = $fetch['empId'];

    if ($contract == "current") :

        $table1 = "employee3";
        $table2 = "promo_record";
    else :

        $table1 = "employmentrecord_";
        $table2 = "promo_history_record";
    endif;

    $sql = "SELECT startdate, eocdate, current_status, position, emp_type, remarks, agency_code, promo_company, promo_department, vendor_code, promo_type, type 
                            FROM `$table1` 
                                LEFT JOIN `$table2`
                                    ON $table1.record_no = $table2.record_no AND $table1.emp_id = $table2.emp_id
                                        WHERE $table1.record_no = '$recordNo' AND $table1.emp_id = '$empId'";
    $row = $this->employee_model->return_row_array($sql);

    if ($row['startdate'] == "0000-00-00") : $startdate = '';
    else : $startdate = date("m/d/Y", strtotime($row['startdate']));
    endif;
    if ($row['eocdate'] == "0000-00-00" || $row['eocdate'] == "1970-01-01") : $eocdate = '';
    else : $eocdate = date("m/d/Y", strtotime($row['eocdate']));
    endif;

    $promoType       = $row['promo_type'];
    $agency_code     = $row['agency_code'];
    $department      = $row['promo_department'];
    $vendor_code     = $row['vendor_code'];

    $condition = "";
    if ($promoType == 'STATION') {

        $bUs = $this->dashboard_model->businessUnit_list();
        foreach ($bUs as $bu) {

            $hasBU = $this->dashboard_model->promo_has_store($contract, $empId, $recordNo, $bu->bunit_field);
            if ($hasBU > 0) {

                $condition = "AND bunit_id = '$bu->bunit_id'";
            }
        }
    } else {

        $ctr = 0;
        $bUs = $this->dashboard_model->businessUnit_list();
        foreach ($bUs as $bu) {

            $hasBU = $this->dashboard_model->promo_has_store($contract, $empId, $recordNo, $bu->bunit_field);
            if ($hasBU > 0) {

                $ctr++;
                if ($ctr == 1) {

                    $condition .= "AND (bunit_id = '$bu->bunit_id'";
                } else {

                    $condition .= " OR bunit_id = '$bu->bunit_id'";
                }
            }
        }

        if ($condition != "") {

            $condition .= ")";
        }
    }

    $emp_products = array();
    $products = $this->employee_model->promo_products($recordNo, $empId);
    foreach ($products as $product => $value) {

        array_push($emp_products, $value->product);
    }

    $cutoff = $this->employee_model->promo_cutoff($recordNo, $empId);

?>
    <style type="text/css">
        .datepicker {
            z-index: 9999 !important
        }
    </style>
    <input type="hidden" name="contract" value="<?php echo $contract; ?>">
    <input type="hidden" name="empId" value="<?php echo $empId; ?>">
    <input type="hidden" name="recordNo" value="<?php echo $recordNo; ?>">
    <input type="hidden" name="company_name" value="<?php echo $row['promo_company']; ?>">
    <div class="row">
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Agency</label>
                        <select name="agency" class="form-control" onchange="select_company(this.value)">
                            <option value=""> --Select Agency-- </option>
                            <?php

                            $result = $this->employee_model->agency_list();

                            foreach ($result as $res) {
                            ?>
                                <option value="<?= $res->agency_code ?>" <?php if ($row['agency_code'] == $res->agency_code) echo "selected=''"; ?>>
                                    <?= $res->agency_name ?>
                                </option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group"> <i class="text-red">*</i>
                        <label>Company</label>
                        <select name="company" class="form-control" onchange="select_product(this.value)" required>
                            <option value=""> --Select Company-- </option>
                            <?php

                            if ($row['agency_code'] != 0) {

                                $result = $this->employee_model->company_list_under_agency($row['agency_code']);
                                foreach ($result as $res) {

                                    $company_name = $res->company_name;
                                    $company = $this->employee_model->getcompanyCodeBycompanyName($company_name);
                                    if (!empty($company)) {
                            ?>
                                        <option value="<?= $company->pc_code ?>" <?php if ($row['promo_company'] == $company_name) echo "selected = ''"; ?>><?= $company_name ?></option>
                                    <?php
                                    }
                                    ?>
                                <?php
                                }
                            } else {

                                $result = $this->employee_model->company_list();
                                foreach ($result as $res) {
                                ?>

                                    <option value="<?= $res->pc_code ?>" <?php
                                                                            if ($row['promo_company'] == $res->pc_name) echo "selected=''"; ?>>
                                        <?= $res->pc_name; ?>
                                    </option>
                            <?php
                                }
                            }

                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group"> <i class="text-red">*</i>
                        <label>Promo Type</label>
                        <select name="promo_type" class="form-control" onchange="select_business_unit(this.value)" required>
                            <option value=""> --Select-- </option>
                            <option value="STATION" <?php if (strtolower($row['promo_type']) == "station") echo "selected=''"; ?>>STATION</option>
                            <option value="ROVING" <?php if (strtolower($row['promo_type']) == "roving") echo "selected=''"; ?>>ROVING</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="store">
                            <?php if ($row['promo_type'] == "ROVING") { ?>
                                <table class="table table-bordered">
                                    <tr>
                                        <th colspan="2"><i class="text-red">*</i> Business Unit</th>
                                    </tr>
                                    <?php

                                    $ctr = 0;
                                    $bUs = $this->dashboard_model->businessUnit_list();
                                    foreach ($bUs as $bu) {

                                        $ctr++;
                                        $hasBU = $this->dashboard_model->promo_has_store($contract, $empId, $recordNo, $bu->bunit_field);

                                    ?>
                                        <tr>
                                            <td><input type="checkbox" id="check_<?= $ctr; ?>" name="<?= $bu->bunit_field ?>" value="<?= $bu->bunit_id . '/' . $bu->bunit_field ?>" <?php if ($hasBU > 0) {
                                                                                                                                                                                        echo "checked=''";
                                                                                                                                                                                    } ?> onclick="locateDeptRoving()" /></td>
                                            <td><?= $bu->bunit_name ?></td>
                                        </tr>
                                    <?php
                                    }

                                    ?>
                                    <input type="hidden" name="counter" value="<?= $ctr; ?>">
                                </table>
                            <?php
                            } else { ?>

                                <table class="table table-bordered">
                                    <tr>
                                        <th colspan="2"><i class="text-red">*</i> Business Unit</th>
                                    </tr>
                                    <?php

                                    $ctr = 0;
                                    $bUs = $this->dashboard_model->businessUnit_list();
                                    foreach ($bUs as $bu) {

                                        $ctr++;
                                        $hasBU = $this->dashboard_model->promo_has_store($contract, $empId, $recordNo, $bu->bunit_field);

                                    ?>
                                        <tr>
                                            <td><input type="radio" name="station" id="radio_<?= $ctr; ?>" value="<?= $bu->bunit_id . '/' . $bu->bunit_field ?>" <?php if ($hasBU > 0) {
                                                                                                                                                                        echo "checked=''";
                                                                                                                                                                    } ?> onclick="locateDeptStation(this.value)" /></td>
                                            <td><?= $bu->bunit_name ?></td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                    <input type="hidden" name="counter" value="<?= $ctr; ?>">
                                </table>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group"> <i class="text-red">*</i>
                        <label>Department</label>
                        <select name="department" class="form-control" onchange="select_vendor(this.value)" required>
                            <option value=""> --Select-- </option>
                            <?php

                            $sql = "SELECT dept_name FROM locate_promo_department WHERE status = 'active' $condition GROUP BY dept_name ORDER BY dept_name ASC";
                            $result = $this->employee_model->return_result_array($sql);
                            foreach ($result as $res) {

                            ?>
                                <option value="<?= $res['dept_name'] ?>" <?php if ($row['promo_department'] == $res['dept_name']) echo "selected=''"; ?>><?= $res['dept_name'] ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Vendor Name</label>
                        <select name="vendor" class="form-control">
                            <option value=""> --Select-- </option>
                            <?php

                            if ($department == "EASY FIX") {
                                $department = 'FIXRITE';
                            }

                            $vendors = $this->employee_model->locate_vendor($row['promo_department']);
                            foreach ($vendors as $vendor) {
                            ?>
                                <option value="<?= $vendor->vendor_code ?>" <?php if ($row['vendor_code'] == $vendor->vendor_code) echo "selected=''"; ?>><?= $vendor->vendor_name ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Product</label>
                        <select name="product[]" class="form-control select2" multiple="multiple">
                            <?php
                            $products = $this->employee_model->locate_promo_products($row['promo_company']);
                            foreach ($products as $product) {
                            ?>
                                <option value="<?= $product->product ?>" <?php if (in_array($product->product, $emp_products)) echo "selected=''"; ?>><?= $product->product ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group"><i class="text-red">*</i>
                        <label>Startdate</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="text" value="<?php echo $startdate; ?>" name="startdate" class="form-control datepicker" onchange="checkStartdate()" required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group"> <i class="text-red">*</i>
                        <label>EOCdate</label>
                        <div class="input-group">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="text" value="<?php echo $eocdate; ?>" name="eocdate" class="form-control datepicker" onchange="durationContract(this.value)" required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group"> <i class="text-red">*</i>
                        <label>Position</label>
                        <input type="hidden" name="duration">
                        <input type="hidden" name="position_level" value="0">
                        <select class="form-control" name="position" style="width: 100%;" onchange="positionLevel(this.value)" required>
                            <option value=""> --Select-- </option>
                            <?php

                            $positions = $this->employee_model->list_of_positions();
                            foreach ($positions as $position) {
                            ?>
                                <option value="<?= $position['position_title'] ?>" <?php if (strtolower($row['position']) == strtolower($position['position_title'])) echo "selected=''"; ?>><?= $position['position_title'] ?></option>
                            <?php
                            }
                            ?> ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group"> <i class="text-red">*</i>
                        <label>Employee Type</label>
                        <select name="empType" class="form-control" onchange="inputField(this.name)" required>
                            <option value=""> --Select-- </option>
                            <?php

                            $emp_types = $this->employee_model->emp_type();
                            foreach ($emp_types as $emp_type) {
                            ?>
                                <option value="<?= $emp_type->emp_type ?>" <?php if ($row['emp_type'] == $emp_type->emp_type) echo "selected=''"; ?>><?= $emp_type->emp_type ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Contract Type</label>
                        <select name="contractType" class="form-control">
                            <option value="Contractual" <?php if ($row['type'] == "Contractual") : echo "selected=''";
                                                        endif; ?>>Contractual</option>
                            <option value="Seasonal" <?php if ($row['type'] == "Seasonal") : echo "selected=''";
                                                        endif; ?>>Seasonal</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group"> <i class="text-red">*</i>
                        <label>Current Status</label>
                        <select name="current_status" class="form-control" onchange="inputField(this.name)" required>
                            <option value="Active" <?php if ($row['current_status'] == "Active") echo "selected=''"; ?>>Active</option>
                            <option value="End of Contract" <?php if ($row['current_status'] == "End of Contract") echo "selected=''"; ?>>End of Contract</option>
                            <option value="Resigned" <?php if ($row['current_status'] == "Resigned") echo "selected=''"; ?>>Resigned</option>
                            <?php if (in_array($this->employee_model->loginId, $admin_users)) : ?>
                                <option value="blacklisted" <?php if ($row['current_status'] == "blacklisted") echo "selected=''"; ?>>blacklisted</option>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group"> <i class="text-red">*</i>
                        <label for="cutoff">Cut-off</label>
                        <select name="cutoff" id="cutoff" class="form-control">
                            <option value=""> --Select-- </option>
                            <?php

                            $cutoffs = $this->employee_model->cutoff_list();
                            foreach ($cutoffs as $co) {

                                $endFC = ($co->endFC != '') ? $co->endFC : 'last';
                                if (@$cutoff->statCut == $co->statCut) {

                                    echo '<option value="' . $co->statCut . '" selected>' . $co->startFC . '-' . $endFC . ' / ' . $co->startSC . '-' . $co->endSC . '</option>';
                                } else {

                                    echo '<option value="' . $co->statCut . '">' . $co->startFC . '-' . $endFC . ' / ' . $co->startSC . '-' . $co->endSC . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Remarks</label>
                        <textarea name="remarks" class="form-control" rows="6"><?= $row['remarks']; ?></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $('.datepicker').datepicker({
            inline: true,
            changeYear: true,
            changeMonth: true
        });

        $('.select2').select2();
        $('.select2').on('select2:open', function(e) {
            const evt = "scroll.select2";
            $(e.target).parents().off(evt);
            $(window).off(evt);
        });
        $("span.select2").css("width", "100%");
    </script>
<?php
} else if ($request == "viewJobTrans") {

    $jobTransfer = $fetch['jobTransfer'];

    $filename     = "SELECT * FROM employee_transfer_details WHERE transfer_no = '$jobTransfer'";
    $file         = $this->employee_model->return_row_array($filename)['file'];

?>

    <body>
        <center>
            <?php

            echo '<embed src="http://' . $_SERVER['SERVER_ADDR'] . ":" . $_SERVER['SERVER_PORT'] . '/hrms/promo-nesco/' . $file . '" width="85%" height="500"></embed>';
            ?>
        </center>
    </body>
<?php
} else if ($request == "addBlacklist") {

    $no = $fetch['no'];
    $empId = $fetch['empId'];
    $name = $this->employee_model->employee_name($empId)['name'];

    $sql = "SELECT * FROM `blacklist` WHERE blacklist_no = '$no'";
    $row = $this->employee_model->return_row_array($sql);

    if ($row['date_blacklisted'] == '0000-00-00' || $row['date_blacklisted'] == '' || $row['bday'] == "1970-01-01") {

        $datebl = '';
    } else {

        $datebl = date("m/d/Y", strtotime($row['date_blacklisted']));
    }

    if ($row['date_added'] == '0000-00-00' || $row['date_added'] == '') {

        $dateadded = '';
    } else {

        $dateadded = date("m/d/Y", strtotime($row['date_added']));
    }

    if ($row['bday'] == "0000-00-00" || $row['bday'] == "" || $row['bday'] == "1970-01-01") {

        $bday = "";
    } else {

        $bday = date("m/d/Y", strtotime($row['bday']));
    }

?>
    <style type="text/css">
        .search-results {

            box-shadow: 5px 5px 5px #ccc;
            margin-top: 1px;
            margin-left: 0px;
            background-color: #F1F1F1;
            width: 85%;
            border-radius: 3px 3px 3px 3px;
            font-size: 18x;
            padding: 8px 10px;
            display: block;
            position: absolute;
            z-index: 9999;
            max-height: 300px;
            overflow-y: scroll;
            overflow: auto;
        }
    </style>
    <input type="hidden" name="empId" value="<?php echo $empId; ?>">
    <input type="hidden" name="empName" value="<?php echo $name; ?>">
    <input type="hidden" name="no" value="<?php echo $no; ?>">
    <div class="form-group">
        <label>Employee</label>
        <div class="input-group">
            <input class="form-control" value="<?php echo $empId . ' * ' . $name; ?>" name="employee" disabled="" type="text">
            <span class="input-group-addon"><i class="fa fa-user"></i></span>
        </div>
    </div>
    <div class="form-group"> <i class="text-red">*</i>
        <label>Reason</label>
        <textarea name="reason" class="form-control" rows="4" onkeyup="inputField(this.name)"><?php echo $row['reason']; ?></textarea>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group"> <i class="text-red">*</i>
                <label>Date Blacklisted</label>
                <div class="input-group date">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </div>
                    <input type="text" name="dateBlacklisted" class="form-control pull-right datepicker" style="position: relative; z-index: 100000;" data-inputmask="'alias': 'mm/dd/yyyy'" data-mask onchange="inputField(this.name)" value="<?php echo $datebl; ?>" placeholder="mm/dd/yyy">
                </div>
            </div>
            <div class="form-group">
                <label>Birthday</label>
                <div class="input-group date">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </div>
                    <input type="text" name="birthday" value="<?php echo $bday; ?>" class="form-control pull-right datepicker" style="position: relative; z-index: 100000;" data-inputmask="'alias': 'mm/dd/yyyy'" data-mask placeholder="mm/dd/yyy">
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group"> <i class="text-red">*</i>
                <label>Reported By</label>
                <div class="input-group">
                    <input class="form-control" type="text" name="reportedBy" value="<?php echo $row['reportedby']; ?>" onkeyup="nameSearch(this.value)" autocomplete="off">
                    <span class="input-group-addon"><i class="fa fa-child"></i></span>
                </div>
                <div class="search-results" style="display: none;"></div>
            </div>
            <div class="form-group">
                <label>Address</label>
                <input type="text" class="form-control" name="address" value="<?php echo $row['address']; ?>">
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $('.datepicker').datepicker({
            inline: true,
            changeYear: true,
            changeMonth: true
        });

        $("[data-mask]").inputmask();
    </script>
<?php
} else if ($request == "view201File") {

    $empId  = $fetch['empId'];
    $no     = $fetch['no'];

    $start  = 0;
    $limit  = 1;
    if (!empty($fetch['page'])) {

        $id = $fetch['page'];
        $start = ($id - 1) * $limit;
    } else {

        $id = 1;
    }

    $doc = "SELECT tableName, empField, table_condition FROM 201document WHERE no = '$no'";
    $d   = $this->employee_model->return_row_array($doc);

    $tableName  = $d['tableName'];
    $empField   = $d['empField'];
    $table_condition = $d['table_condition'];

    $sql = "SELECT * FROM $tableName
                            WHERE 
                                $empField = '" . $empId . "' $table_condition
                            LIMIT 
                                $start, $limit
                        ";
    $result = $this->employee_model->return_result_array($sql);

    $rows = $this->employee_model->return_num_rows(
        "SELECT * FROM $tableName
                            WHERE
                                $empField = '" . $empId . "' $table_condition"
    );
    $total = ceil($rows / $limit); ?>

    <div class="col-md-2" style="position:absolute;top:2px; right:1px;">
        <div class="form-horizontal">
            <div class="form-group">
                <label class="col-md-3">page</label>
                <div class="col-md-9">
                    <select name="page" class="form-control" onchange="pagi('<?php echo $no; ?>',this.value)">
                        <?php

                        for ($i = 1; $i <= $total; $i++) { ?>

                            <option value="<?php echo $i; ?>" <?php if ($id == $i) : echo "selected=''";
                                                                endif; ?>><?php echo $i; ?></option> <?php
                                                                                                    }
                                                                                                        ?>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <?php

    foreach ($result as $row) {

        if ($no == 27) {

            $date_time = $row['date_updated'];
            $receiving_staff = $row['added_by'];
            $filename = $row['resignation_letter'];
        } else {

            $date_time = $row['date_time'];
            $receiving_staff = $row['receiving_staff'];
            $filename = $row['filename'];
        }
    ?>

        <div class="row">
            <div class="col-md-10">
                <span><i>Date Uploaded :</i><strong><?php echo date('F d, Y', strtotime($date_time)); ?></strong></span>
                <span><i>Uploaded By : </i><strong><?php echo $this->employee_model->employee_name($receiving_staff)['name']; ?></strong></span>
            </div>
        </div><br>
        <div class="row">
            <div class="col-md-12">
                <center><img src="<?php echo 'http://' . $_SERVER['SERVER_ADDR'] . ":" . $_SERVER['SERVER_PORT'] . '/hrms/promo-nesco/' . $filename; ?>" width="100%"></center>
            </div>
        </div>
    <?php
    }
} else if ($request == "upload201Files") {

    $empId = $fetch['empId'];

    ?>
    <i class="text-red">Allowed File : jpg, jpeg, png only</i><br>
    <input type="hidden" name="empId" value="<?php echo $empId; ?>">
    <div class="form-group">
        <label>201 File</label>
        <select name="sel201File" class="form-control" onchange="inputField(this.name)">
            <option value=""> --Select-- </option>
            <?php

            $qu = "SELECT no, 201_name FROM 201document WHERE promo = 'yes' ORDER BY 201_name ASC";
            $result = $this->employee_model->return_result_array($qu);
            foreach ($result as $rq) {

                if ($rq['no'] != 27) {

                    echo "<option value='" . $rq['no'] . "'>" . $rq['201_name'] . "</option>";
                }
            }
            ?>
        </select>
    </div>
    <div class="form-group">
        <label>Browse File</label>
        <input type="file" name="file_upload[]" multiple class="form-control" onchange="validateFile()">
    </div>
<?php
} else if ($request == "addSupervisor") {

    $empId = $fetch['empId'];

?>
    <style type="text/css">
        .table-height {

            overflow: auto;
            max-height: 450px;
        }
    </style>
    <input type="hidden" name="empId" value="<?php echo $empId; ?>">
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label>Company</label>
                <select class="form-control" name="company" onchange="loadBusinessUnit(this.value)">
                    <option value=""> --Select Company-- </option>
                    <?php

                    $result = $this->employee_model->ae_company_list();
                    foreach ($result as $res) { ?>

                        <option value="<?php echo $res['company_code']; ?>"><?php echo $res['company']; ?></option> <?php
                                                                                                                }
                                                                                                                    ?>
                </select>
            </div>
            <div class="form-group">
                <label>Business Unit</label>
                <select class="form-control" name="businessUnit" onchange="loadDepartment(this.value)">
                    <option value=""> --Select Business Unit-- </option>
                </select>
            </div>
            <div class="form-group">
                <label>Department</label>
                <select class="form-control" name="department" onchange="loadSection(this.value)">
                    <option value=""> --Select Department-- </option>
                </select>
            </div>
            <div class="form-group">
                <label>Section</label>
                <select class="form-control" name="section" onchange="loadSubSection(this.value)">
                    <option value=""> --Select Section-- </option>
                </select>
            </div>
            <div class="form-group">
                <label>Sub-section</label>
                <select class="form-control" name="subSection" onchange="loadUnit(this.value)">
                    <option value=""> --Select Sub-section-- </option>
                </select>
            </div>
            <div class="form-group">
                <label>Unit</label>
                <select class="form-control" name="unit">
                    <option value=""> --Select Unit-- </option>
                </select>
            </div>
        </div>
        <div class="col-md-8">
            <div class="supervisor table-height">
                <div class="loading-gif"></div>
            </div>
        </div>
    </div>
<?php
} else if ($request == "selectSupervisor") {

    $id = explode("/", $fetch['id']);
    $loc = $fetch['loc'];
    $empId = $fetch['empId'];

    $where = "";
    if ($loc == "cc") {
        $where = "AND company_code = '" . $fetch['id'] . "'";
    } else if ($loc == "bc") {
        $where = "AND company_code = '$id[0]' and bunit_code = '$id[1]'";
    } else if ($loc == "dc") {
        $where = "AND company_code = '$id[0]' and bunit_code = '$id[1]' and dept_code = '$id[2]'";
    } else if ($loc == "sc") {
        $where = "AND company_code = '$id[0]' and bunit_code = '$id[1]' and dept_code = '$id[2]' and section_code = '$id[3]'";
    } else if ($loc == "ssc") {
        $where = "AND company_code = '$id[0]' and bunit_code = '$id[1]' and dept_code = '$id[2]' and section_code = '$id[3]' and sub_section_code = '$id[4]'";
    } else if ($loc == "uc") {
        $where = "AND company_code = '$id[0]' and bunit_code = '$id[1]' and dept_code = '$id[2]' and section_code = '$id[3]' and sub_section_code = '$id[4]' and unit_code = '$id[5]'";
    }

    $sql = "SELECT emp_id, name, position, current_status FROM `employee3`, `leveling_subordinates` WHERE employee3.emp_id = leveling_subordinates.ratee AND current_status = 'Active' AND emp_id != '" . $empId . "' $where GROUP BY ratee ORDER BY name ASC";
    $result = $this->employee_model->return_result_array($sql);
?>

    <table id="example2" class="table table-striped table-hover table3">
        <thead>
            <tr>
                <th></th>
                <th>Emp.ID</th>
                <th>Name</th>
                <th>Position</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php

            foreach ($result as $row) {

                $supId = $row['emp_id'];

                if ($row['current_status'] == "Active") {

                    $class = "btn btn-success btn-xs";
                } else {

                    $class = "btn btn-warning btn-xs";
                }

                echo "
                        <tr id='" . $supId . "'>
                            <span style='display:none;'><input type='checkbox' name='chkempId[]' class='chkId_$supId' value='$supId'></span>
                            <td><input type='checkbox' class='chkIdC_$supId' onclick='chkIdC(\"$supId\")'></td>
                            <td><a href='" . base_url('placement/page/menu/employee/profile/' . $row['emp_id']) . "' target='_blank'>$supId</td>
                            <td>" . ucwords(strtolower($row['name'])) . "</td>
                            <td>" . $row['position'] . "</td>
                            <td><span class='$class btn-block'>" . $row['current_status'] . "</span></td>
                        </tr>
                    ";
            }
            ?>
        </tbody>
    </table>
    <script type="text/javascript">
        $(".table3").DataTable({

            "order": [
                [2, 'asc']
            ],
            "paging": false
        });

        $('#example2 tbody').on('click', 'tr', function() {

            var id = this.id;
            if ($("input.chkIdC_" + id).is(':checked')) {

                $(this).addClass('selected');
            } else {
                $(this).removeClass('selected');
            }
        });
    </script>
<?php
} else if ($request == 'username_xls') {

    $filename = "Username Report";
    header("Content-Type: application/vnd.ms-excel");
    header("Content-disposition: attachment; filename=" . $filename . ".xls");

?>
    <center>
        <h3>Username Report as of <?php echo date("F d, Y"); ?></h3>
    </center>
    <table class='table table-bordered' border='1'>
        <tr>
            <th>No</th>
            <th>Emp. ID</th>
            <th>Name</th>
            <th>Username</th>
            <th>Agency</th>
            <th>Company</th>
            <th>Business Unit</th>
            <th>Department</th>
            <th>Position</th>
            <th>Promo Type</th>
        </tr>
        <?php

        $num = 1;
        foreach ($usernames as $row) {

            $ctr = 0;
            $storeName = '';
            $bUs = $this->dashboard_model->businessUnit_list();
            foreach ($bUs as $bu) {

                $hasBU = $this->dashboard_model->promo_has_bu($row->emp_id, $bu->bunit_field);
                if ($hasBU > 0) {

                    $ctr++;

                    if ($ctr == 1) {

                        $storeName = $bu->bunit_name;
                    } else {

                        $storeName .= ", " . $bu->bunit_name;
                    }
                }
            }

            $username = '';
            $user = $this->report_model->get_username($row->emp_id);
            if (!empty($user)) {

                $username = $user->username;
            }

            $agency_name = '';
            if ($row->agency_code != 0) {

                $agency_name = $this->employee_model->agency_name($row->agency_code);
            }

            echo "
        						<tr>
        							<td>" . $num . "</td>
        							<td>" . $row->emp_id . "</td>
        							<td>" . utf8_decode(ucwords(strtolower($row->name))) . "</td>
        							<td>" . $username . "</td>
        							<td>" . $agency_name . "</td>
        							<td>" . $row->promo_company . "</td>
        							<td>" . $storeName . "</td>
        							<td>" . $row->promo_department . "</td>
        							<td>" . $row->position . "</td>
        							<td>" . $row->promo_type . "</td>
        						</tr>
        					";
            $num++;
        }
        ?>
    </table>
<?php
} else if ($request == 'qbe_report') {

    $filename = $fetch['filename'];
    header("Content-Type: application/vnd.ms-excel");
    header("Content-disposition: attachment; filename=" . $filename . ".xls");
?>
    <i>Date Generated : <?= date('F d, Y') ?></i><br>
    <i>Generated Thru : HRMS - Promo-NESCO</i><br>
    <i>Generated by : <?= $_SESSION['name'] ?></i><br>
    <i>Report Title : <?= ucwords(strtolower($fetch['report_title'])) ?></i><br><br><br>

    <table class='table table-bordered' border='1'>
        <tr>
            <th>No</th>
            <th>Employee ID</th>
            <th>FirstName</th>
            <th>MiddleName</th>
            <th>LastName</th>
            <th>Suffix</th>
            <th>Agency</th>
            <th>Company</th>
            <th>Business Unit</th>
            <th>Department</th>
            <th>Product</th>
            <th>Position</th>
            <th>Promo Type</th>
            <th>Contract Type</th>
            <th>Startdate</th>
            <th>Eocdate</th>
            <th>Duration from Company</th>
            <?php

            if (!empty($fetch['fields'])) {

                foreach ($fetch['fields'] as $key => $value) {
                    $field = '';
                    if ($value == 'birthdate') {
                        $field = 'Birth Day';
                    } else if ($value == 'contactno') {
                        $field = 'Contact Number';
                    } else if ($value == 'civilstatus') {
                        $field = 'Civil Status';
                    } else {

                        $field = ucwords(strtolower(str_replace('_', ' ', $value)));
                    }
                    echo '<th>' . $field . '</th>';
                }

                if (!empty($fetch['agecb'])) {

                    echo '<th>Age</th>';
                }
            }

            ?>
        </tr>
        <?php

        $no = 1;
        $results = $this->report_model->fetch_qbe_results($fetch);
        foreach ($results as $row) {

            $ctr = 0;
            $storeName = '';
            $bUs = $this->dashboard_model->businessUnit_list();
            foreach ($bUs as $bu) {

                $hasBU = $this->dashboard_model->promo_has_bu($row['emp_id'], $bu->bunit_field);
                if ($hasBU > 0) {

                    $ctr++;

                    if ($ctr == 1) {

                        $storeName = $bu->bunit_name;
                    } else {

                        $storeName .= ", " . $bu->bunit_name;
                    }
                }
            }

            $company_duration = '';
            if (!empty($row['company_duration'])) {

                $date = explode('-', $row['company_duration']);
                if (checkdate($date[1], $date[2], $date[0])) {

                    $company_duration = date('m/d/Y', strtotime($row['company_duration']));
                } else {

                    $company_duration = '';
                }
            }

            $agency_name = '';
            if ($row['agency_code'] != 0) {

                $agency_name = $this->employee_model->agency_name($row['agency_code']);
            }

            if (!empty($fetch['product'])) {

                $products = $this->employee_model->select_promo_products($row['record_no'], $row['emp_id'], $fetch['product']);
            } else {

                $products = $this->employee_model->promo_products($row['record_no'], $row['emp_id']);
            }

            $ctrProd = 0;
            $pp = '';
            foreach ($products as $product) {

                if ($ctrProd == 0) {

                    $pp = $product->product;
                } else {

                    $pp .= ", " . $product->product;
                }
                $ctrProd++;
            }

            if (!empty($fetch['product']) && $pp != '') {

                echo '
                    <tr>
                        <td>' . $no . '</td>
                        <td>' . $row['emp_id'] . '</td>
                        <td>' . utf8_decode(ucwords(strtolower($row['firstname']))) . '</td>
                        <td>' . utf8_decode(ucwords(strtolower($row['middlename']))) . '</td>
                        <td>' . utf8_decode(ucwords(strtolower($row['lastname']))) . '</td>
                        <td>' . ucwords(strtolower($row['suffix'])) . '</td>
                        <td>' . $agency_name . '</td>
                        <td>' . $row['promo_company'] . '</td>
                        <td>' . $storeName . '</td>
                        <td>' . $row['promo_department'] . '</td>
                        <td>' . $pp . '</td>
                        <td>' . $row['position'] . '</td>
                        <td>' . $row['promo_type'] . '</td>
                        <td>' . $row['type'] . '</td>
                        <td>' . date('m/d/Y', strtotime($row['startdate'])) . '</td>
                        <td>' . date('m/d/Y', strtotime($row['eocdate'])) . '</td>
                        <td>' . $company_duration . '</td>';

                if (!empty($fetch['fields'])) {

                    foreach ($fetch['fields'] as $key => $value) {

                        echo '<td>' . $row[$value] . '</td>';
                    }
                }

                if (!empty($fetch['agecb'])) {

                    $birthday = date_create($row['birthdate']);
                    $date_today = date_create(date("Y-m-d"));
                    $interval = date_diff($birthday, $date_today);
                    $age = $interval->format("%y");

                    echo '<td>' . $age . '</td>';
                }
                echo '
                    </tr>
                ';
            } else if (empty($fetch['product'])) {

                echo '
                    <tr>
                        <td>' . $no . '</td>
                        <td>' . $row['emp_id'] . '</td>
                        <td>' . utf8_decode(ucwords(strtolower($row['firstname']))) . '</td>
                        <td>' . utf8_decode(ucwords(strtolower($row['middlename']))) . '</td>
                        <td>' . utf8_decode(ucwords(strtolower($row['lastname']))) . '</td>
                        <td>' . ucwords(strtolower($row['suffix'])) . '</td>
                        <td>' . $agency_name . '</td>
                        <td>' . $row['promo_company'] . '</td>
                        <td>' . $storeName . '</td>
                        <td>' . $row['promo_department'] . '</td>
                        <td>' . $pp . '</td>
                        <td>' . $row['position'] . '</td>
                        <td>' . $row['promo_type'] . '</td>
                        <td>' . $row['type'] . '</td>
                        <td>' . date('m/d/Y', strtotime($row['startdate'])) . '</td>
                        <td>' . date('m/d/Y', strtotime($row['eocdate'])) . '</td>
                        <td>' . $company_duration . '</td>';

                if (!empty($fetch['fields'])) {

                    foreach ($fetch['fields'] as $key => $value) {

                        echo '<td>' . $row[$value] . '</td>';
                    }
                }

                if (!empty($fetch['agecb'])) {

                    $birthday = date_create($row['birthdate']);
                    $date_today = date_create(date("Y-m-d"));
                    $interval = date_diff($birthday, $date_today);
                    $age = $interval->format("%y");

                    echo '<td>' . $age . '</td>';
                }
                echo '
                    </tr>
                ';
            }

            $no++;
        }
        ?>
    </table>
<?php
} else if ($request == 'termination_of_contract_xls') {

    $filename = "Termination of Contract";
    header("Content-Type: application/vnd.ms-excel");
    header("Content-disposition: attachment; filename=" . $filename . ".xls");

?>
    <center>
        <h3>End of Contract List for <?= date('F Y', strtotime($fetch['month'])) ?></h3>
    </center>

    <table class='table table-bordered' border='1'>
        <tr>
            <th>No</th>
            <th>Employee ID</th>
            <th>Name</th>
            <th>Agency</th>
            <th>Company</th>
            <th>Business Unit</th>
            <th>Department</th>
            <th>Position</th>
            <th>Promo Type</th>
            <th>Contract Type</th>
            <th>Startdate</th>
            <th>EocDate</th>
            <th>Duration from Company</th>
        </tr>
        <?php

        $no = 1;
        $result = $this->report_model->naend_of_contract_list($fetch);
        foreach ($result as $row) {

            $ctr = 0;
            $storeName = '';
            $bUs = $this->dashboard_model->businessUnit_list();
            foreach ($bUs as $bu) {

                $hasBU = $this->dashboard_model->promo_has_bu($row->emp_id, $bu->bunit_field);
                if ($hasBU > 0) {

                    $ctr++;

                    if ($ctr == 1) {

                        $storeName = $bu->bunit_name;
                    } else {

                        $storeName .= ", " . $bu->bunit_name;
                    }
                }
            }

            $company_duration = '';
            if (!empty($row->company_duration)) {

                $date = explode('-', $row->company_duration);
                if (checkdate($date[1], $date[2], $date[0])) {

                    $company_duration = date('m/d/Y', strtotime($row->company_duration));
                } else {

                    $company_duration = '';
                }
            }

            $agency_name = '';
            if ($row->agency_code != 0) {

                $agency_name = $this->employee_model->agency_name($row->agency_code);
            }

            echo '
                <tr>
                    <td>' . $no . '</td>
                    <td>' . $row->emp_id . '</td>
                    <td>' . utf8_decode(ucwords(strtolower($row->name))) . '</td>
                    <td>' . $agency_name . '</td>
                    <td>' . $row->promo_company . '</td>
                    <td>' . $storeName . '</td>
                    <td>' . $row->promo_department . '</td>
                    <td>' . $row->position . '</td>
                    <td>' . $row->promo_type . '</td>
                    <td>' . $row->type . '</td>
                    <td>' . date('m/d/Y', strtotime($row->startdate)) . '</td>
                    <td>' . date('m/d/Y', strtotime($row->eocdate)) . '</td>
                    <td>' . $company_duration . '</td>
                </tr>
            ';

            $no++;
        }
        ?>
    </table>
<?php
} else if ($request == 'termination_list') {
?>
    <input type="hidden" name="company" value="<?= $fetch['company'] ?>">
    <input type="hidden" name="business_unit" value="<?= $fetch['business_unit'] ?>">
    <input type="hidden" name="department" value="<?= $fetch['department'] ?>">
    <input type="hidden" name="month" value="<?= $fetch['month'] ?>">
    <table id="termination_list" class="table table-bordered table-hover" width="100%">
        <thead>
            <tr>
                <td>
                    <center><input type="checkbox" class="select-all"></center>
                </td>
                <td>Emp.Id</td>
                <td>Name</td>
                <td>Agency</td>
                <td>Company</td>
                <td>BusinessUnit</td>
                <td>Department</td>
                <td>Position</td>
                <td>Startdate</td>
                <td>EOCdate</td>
            </tr>
        </thead>
    </table>
    <script>
        var company = $("input[name = 'company']").val();
        var business_unit = $("input[name = 'business_unit']").val();
        var department = $("input[name = 'department']").val();
        var month = $("input[name = 'month']").val();

        var maEOC_list = $('#termination_list').DataTable({

            "destroy": true,
            "order": [
                [2, "asc"]
            ],
            "ajax": {
                url: "<?php echo site_url('eoc_employees'); ?>",
                type: "get",
                data: {
                    company,
                    business_unit,
                    department,
                    month
                },
            },
            "columnDefs": [{
                    "targets": [1],
                    "orderable": false
                },
                {
                    orderable: false,
                    className: 'select-checkbox',
                    targets: 0
                }
            ],
            select: {
                style: 'multi',
                selector: 'td:first-child'
            },
        });

        $('.select-all').change(function() {
            if (this.checked) {
                ;
                maEOC_list.rows().select();
            } else {
                maEOC_list.rows().deselect();
            }
        });
    </script>
<?php
} else if ($request == 'promo_details') {

    $ctr = 0;
    $storeName = '';
    $bUs = $this->dashboard_model->businessUnit_list();
    foreach ($bUs as $bu) {

        $hasBU = $this->dashboard_model->promo_has_bu($employee->emp_id, $bu->bunit_field);
        if ($hasBU > 0) {

            $ctr++;
            echo '<input type="hidden" name="bUs[]" value="' . $bu->bunit_id . '/' . $bu->bunit_field . '">';
            if ($ctr == 1) {

                $storeName = $bu->bunit_name;
            } else {

                $storeName .= ", " . $bu->bunit_name;
            }
        }
    }

    $agency_name = '';
    if ($employee->agency_code != 0) {

        $agency_name = $this->employee_model->agency_name($employee->agency_code);
    }
?>
    <input type="hidden" name="current_store" value="<?= $storeName ?>">
    <table class="table">
        <tr>
            <th width="25%">Agency</th>
            <td>:</td>
            <td><?= $agency_name ?></td>
        </tr>
        <tr>
            <th>Company</th>
            <td>:</td>
            <td><?= $employee->promo_company ?></td>
        </tr>
        <tr>
            <th>Business Unit</th>
            <td>:</td>
            <td><?= $storeName ?></td>
        </tr>
        <tr>
            <th>Department</th>
            <td>:</td>
            <td><?= $employee->promo_department ?></td>
        </tr>
        <tr>
            <th>Promo Type</th>
            <td>:</td>
            <td><?= $employee->promo_type ?></td>
        </tr>
        <tr>
            <th>Contract Type</th>
            <td>:</td>
            <td><?= $employee->type ?></td>
        </tr>
        <tr>
            <th>Position</th>
            <td>:</td>
            <td><?= $employee->position ?></td>
        </tr>
        <tr>
            <th>Startdate</th>
            <td>:</td>
            <td><?= date('m/d/Y', strtotime($employee->startdate)) ?></td>
        </tr>
        <tr>
            <th>EOCdate</th>
            <td>:</td>
            <td><?= date('m/d/Y', strtotime($employee->eocdate)) ?></td>
        </tr>
    </table>
<?php
} else if ($request == 'add_outlet_form') {
?>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4>Current Outlet</h4>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-bordered">
                        <tr>
                            <th colspan="2"><span class="text-red">*</span> Business Unit</th>
                        </tr>
                        <?php

                        $ctr = 1;
                        $bUs = $this->dashboard_model->businessUnit_list();
                        foreach ($bUs as $bu) {

                            $hasBU = $this->dashboard_model->promo_has_bu($emp_id, $bu->bunit_field);
                            if ($hasBU > 0) {

                                $attributes = "checked= '' disabled= ''";
                            } else {

                                $attributes = '';
                            }

                            echo '
                                        <tr>
                                            <td><input type="checkbox" name="' . $bu->bunit_field . '" id="field' . $ctr . '" value="' . $bu->bunit_id . '/' . $bu->bunit_field . '" ' . $attributes . '></td>
                                            <td>' . $bu->bunit_name . '</td>
                                        </tr>
                                    ';

                            $ctr++;
                        }

                        ?>
                        <input type="hidden" name="counter" value="<?= $ctr ?>">
                    </table>
                </div>
            </div>
            <div class="form-group"> <span class="text-red">*</span>
                <label for="effective_on">Effective On</label>
                <input type="text" name="effective_on" class="form-control datepicker" value="<?= date('m/d/Y') ?>" onchange="inputField(this.name)" required>
            </div>
            <div class="form-group">
                <label>Remarks</label> <i class="">(optional)</i>
                <textarea name="remarks" class="form-control" rows="4"></textarea>
            </div>
        </div>
        <div class="panel-footer">
            <button type="submit" class="btn btn-primary"><i class="fa fa-bank"></i>&nbsp; Add Outlet</button>
        </div>
    </div>
    <script type="text/javascript">
        $('.datepicker').datepicker({
            inline: true,
            changeYear: true,
            changeMonth: true
        });
    </script>
<?php
} else if ($request == 'transfer_outlet_form') {
?>

    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-bordered">
                        <tr>
                            <th>Business Unit</th>
                            <th style="text-align: center;">Rate</th>
                            <th style="text-align: center;">Transfer</th>
                        </tr>
                        <?php

                        $ctr = 1;
                        $bUs = $this->dashboard_model->businessUnit_list();
                        foreach ($bUs as $bu) {

                            echo '
                                            <tr>
                                                <td>' . $bu->bunit_name . '</td>
                            ';

                            $hasBU = $this->dashboard_model->promo_has_bu($details->emp_id, $bu->bunit_field);
                            if ($hasBU > 0) {

                                $rate = $this->outlet_model->appraisal_details($details->record_no, $details->emp_id, $bu->bunit_name);
                                if ($rate->num_rows() > 0) {
                                    $appraisal = $rate->row();

                                    if ($appraisal->raterSO == 1 && $appraisal->rateeSO == 1) {

                                        $rate = "yes";
                                        $attributes = "btn btn-success btn-xs btn-flat";
                                    } else {

                                        $rate = "no";
                                        $attributes = "btn btn-warning btn-xs btn-flat";
                                    }

                                    if ($appraisal->numrate == 100) {
                                        $grade = 'pass';
                                        $label = "btn btn-success btn-flat btn-xs";
                                    } else if ($appraisal->numrate >= 90 && $appraisal->numrate <= 99.99) {
                                        $grade = 'pass';
                                        $label = "btn btn-primary btn-flat btn-xs";
                                    } else if ($appraisal->numrate >= 85 && $appraisal->numrate <= 89.99) {
                                        $grade = 'pass';
                                        $label = "btn btn-info btn-flat btn-xs";
                                    } else if ($appraisal->numrate >= 70 && $appraisal->numrate <= 84.99) {
                                        $grade = 'failed';
                                        $label = "btn btn-danger btn-flat btn-xs";
                                    } else if ($appraisal->numrate >= 0 && $appraisal->numrate <= 69.99) {
                                        $grade = 'failed';
                                        $label = "btn btn-danger btn-flat btn-xs";
                                    } else {
                                        $grage = 'failed';
                                        $label = "label label-danger";
                                    }

                                    echo '
                                        <td style="text-align: center;">
                                            <button class="' . $label . '" onclick="view_appraisal_details(' . $appraisal->details_id . ')">' . $appraisal->numrate . '</button>
                                            <span class="' . $attributes . '">' . $rate . '</span>
                                        </td>
                                    ';

                                    if ($rate == 'yes' && $grade == 'pass') {

                                        echo '
                                            <td style="text-align: center;">
                                                <input type="checkbox" name="stores[]" id="store-' . $ctr . '" value="' . $bu->bunit_id . '/' . $bu->bunit_field . '" onclick="storeChoice(' . $ctr . ')"> <span class="transfer_' . $ctr . '">Transfer</span>
                                            </td>
                                        ';
                                    } else {

                                        echo '
                                            <td></td>
                                        ';
                                    }
                                } else {

                                    echo '
                                        <td></td>
                                        <td></td>
                                    ';
                                }
                            } else {

                                echo '
                                    <td></td>
                                    <td></td>
                                ';
                            }

                            echo '</tr>';

                            $ctr++;
                        }

                        ?>
                        <input type="hidden" name="counter" value="<?= $ctr ?>">
                    </table>
                </div>
            </div>
        </div>
        <div class="panel-footer">
            <button class="btn btn-primary transfer_btn" disabled onclick="proceedTo('<?= $details->record_no ?>', '<?= $details->emp_id ?>')">Proceed to Transfer Outlet</button>
        </div>
    </div>
    <?php
} else if ($request == 'store_clearance_form') {

    $loop = 1;
    echo '<div class="row">';
    foreach ($fetch['stores'] as $key => $value) {

        $field = explode('/', $value);
        $bu = $this->outlet_model->business_unit_details(end($field));
    ?>
        <input type="hidden" name="clearances[]" value="<?= $bu->bunit_clearance ?>">
        <input type="hidden" name="fields[]" value="<?= $bu->bunit_field ?>">
        <div class="col-md-4" style="margin-top: 30px;">
            <b>Clearance (<?= $bu->bunit_name ?>)</b></br>
            <img id="photo<?= $bu->bunit_clearance ?>" class='preview img-responsive' /></br>
            <input type='file' name='<?= $bu->bunit_clearance ?>' id='<?= $bu->bunit_clearance ?>' class='btn btn-default clearance_<?= $loop ?>' onchange='readURL(this,"<?= $bu->bunit_clearance ?>");'>
            <input type='button' name='clear<?= $bu->bunit_clearance ?>' id='clear<?= $bu->bunit_clearance ?>' style='display:none' class='btn btn-default' value='Clear' onclick="clears('<?= $bu->bunit_clearance ?>','photo<?= $bu->bunit_clearance ?>','clear<?= $bu->bunit_clearance ?>')">
            <input type='button' id='<?= $bu->bunit_clearance ?>_change' style='display:none;' class='btn btn-primary btn-sm' value='Change Clearance?' onclick='changePhoto("Clearance","<?= $bu->bunit_clearance ?>","<?= $bu->bunit_clearance ?>_change")'>
        </div>
    <?php
        $loop++;
    }
    echo '</div>';

    foreach ($fetch['bUs'] as $key => $value) {
        echo '<input type="hidden" name="bUs[]" value="' . $value . '">';
    }

    echo '<input type="hidden" name="record_no" value="' . $fetch['record_no'] . '">';
    echo '<input type="hidden" name="emp_id" value="' . $fetch['emp_id'] . '">';
    echo '<input type="hidden" name="current_store" value="' . $fetch['current_store'] . '">';
} else if ($request == 'transfer_details_form') {

    ?>
    <input type="hidden" name="emp_id" value="<?= $details['emp_id'] ?>">
    <input type="hidden" name="record_no" value="<?= $details['record_no'] ?>">
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>Previous Outlet</h4>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered">
                                <tr>
                                    <th colspan="2">Business Unit</th>
                                </tr>
                                <?php

                                $ctr = 1;
                                $storeName = '';
                                $bUs = $this->dashboard_model->businessUnit_list();
                                foreach ($bUs as $bu) {

                                    $checked = '';
                                    $hasBU = $this->dashboard_model->promo_has_bu($details['emp_id'], $bu->bunit_field);
                                    if ($hasBU > 0) {

                                        if ($ctr == 1) {

                                            $storeName = $bu->bunit_name;
                                        } else {

                                            $storeName .= ", " . $bu->bunit_name;
                                        }
                                        $checked = "checked=''";
                                    }
                                    echo '
                                        <tr>
                                            <td><input type="checkbox" name="' . $bu->bunit_field . '" value="' . $bu->bunit_id . '/' . $bu->bunit_field . '" disabled ' . $checked . '></td>
                                            <td>' . $bu->bunit_name . '</td>
                                        </tr>
                                    ';

                                    $ctr++;
                                }
                                ?>
                            </table>
                        </div>
                    </div>
                    <input type="hidden" name="current_store" value="<?= $storeName ?>">
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>Current Outlet</h4>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered">
                                <tr>
                                    <th colspan="2"><span class="text-red">*</span> Business Unit</th>
                                </tr>
                                <?php

                                $ctr = 1;
                                $bUs = $this->dashboard_model->businessUnit_list();
                                foreach ($bUs as $bu) {

                                    $checked = '';
                                    $disabled = '';

                                    $hasBU = $this->dashboard_model->promo_has_bu($details['emp_id'], $bu->bunit_field);
                                    if ($hasBU > 0) {

                                        if (in_array($bu->bunit_clearance, $details['clearances'])) {

                                            $checked = '';
                                        } else {

                                            $checked = "checked=''";
                                        }

                                        $disabled = "disabled=''";
                                    }
                                    echo '
                                        <tr>
                                            <td><input type="checkbox" id="store-' . $ctr . '" class="store-' . $ctr . '" name="transfer_stores[]" value="' . $bu->bunit_id . '/' . $bu->bunit_field . '" ' . $disabled . ' ' . $checked . ' onclick="selectStore(' . $ctr . ')"></td>
                                            <td>' . $bu->bunit_name . '</td>
                                        </tr>
                                    ';
                                    $ctr++;
                                }
                                ?>
                            </table>
                            <input type="hidden" name="loop" value="0">
                        </div>
                    </div>
                    <div class="form-group"> <span class="text-red">*</span>
                        <label for="effective_on">Effective On</label>
                        <input type="text" name="effective_on" class="form-control datepicker" value="<?= date('m/d/Y') ?>" onchange="inputField(this.name)" required>
                    </div>
                    <div class="form-group">
                        <label>Remarks</label> <i class="">(optional)</i>
                        <textarea name="remarks" class="form-control" rows="4"></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $('.datepicker').datepicker({
            inline: true,
            changeYear: true,
            changeMonth: true
        });
    </script>
<?php
} else if ($request == 'remove_outlet_form') {
?>

    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-bordered">
                        <tr>
                            <th>Business Unit</th>
                            <th style="text-align: center;">Rate</th>
                            <th style="text-align: center;">Transfer</th>
                        </tr>
                        <?php

                        $ctr = 1;
                        $bUs = $this->dashboard_model->businessUnit_list();
                        foreach ($bUs as $bu) {

                            echo '
                                                <tr>
                                                    <td>' . $bu->bunit_name . '</td>
                                ';

                            $hasBU = $this->dashboard_model->promo_has_bu($details->emp_id, $bu->bunit_field);
                            if ($hasBU > 0) {

                                $rate = $this->outlet_model->appraisal_details($details->record_no, $details->emp_id, $bu->bunit_name);
                                if ($rate->num_rows() > 0) {
                                    $appraisal = $rate->row();

                                    if ($appraisal->raterSO == 1 && $appraisal->rateeSO == 1) {

                                        $rate = "yes";
                                        $attributes = "btn btn-success btn-xs btn-flat";
                                    } else {

                                        $rate = "no";
                                        $attributes = "btn btn-warning btn-xs btn-flat";
                                    }

                                    if ($appraisal->numrate == 100) {
                                        $grade = 'pass';
                                        $label = "btn btn-success btn-flat btn-xs";
                                    } else if ($appraisal->numrate >= 90 && $appraisal->numrate <= 99.99) {
                                        $grade = 'pass';
                                        $label = "btn btn-primary btn-flat btn-xs";
                                    } else if ($appraisal->numrate >= 85 && $appraisal->numrate <= 89.99) {
                                        $grade = 'pass';
                                        $label = "btn btn-info btn-flat btn-xs";
                                    } else if ($appraisal->numrate >= 70 && $appraisal->numrate <= 84.99) {
                                        $grade = 'failed';
                                        $label = "btn btn-danger btn-flat btn-xs";
                                    } else if ($appraisal->numrate >= 0 && $appraisal->numrate <= 69.99) {
                                        $grade = 'failed';
                                        $label = "btn btn-danger btn-flat btn-xs";
                                    } else {
                                        $grage = 'failed';
                                        $label = "label label-danger";
                                    }

                                    echo '
                                            <td style="text-align: center;">
                                                <button class="' . $label . '" onclick="view_appraisal_details(' . $appraisal->details_id . ')">' . $appraisal->numrate . '</button>
                                                <span class="' . $attributes . '">' . $rate . '</span>
                                            </td>
                                        ';

                                    if ($rate == 'yes' && $grade == 'pass') {

                                        echo '
                                                <td style="text-align: center;">
                                                    <input type="checkbox" name="stores[]" id="store-' . $ctr . '" value="' . $bu->bunit_id . '/' . $bu->bunit_field . '" onclick="removeThisStore(' . $ctr . ')"> <span class="remove_' . $ctr . '">Remove</span>
                                                </td>
                                            ';
                                    } else {

                                        echo '
                                                <td></td>
                                            ';
                                    }
                                } else {

                                    echo '
                                            <td></td>
                                            <td></td>
                                        ';
                                }
                            } else {

                                echo '
                                        <td></td>
                                        <td></td>
                                    ';
                            }

                            echo '</tr>';

                            $ctr++;
                        }

                        ?>
                        <input type="hidden" name="counter" value="<?= $ctr ?>">
                    </table>
                </div>
            </div>
        </div>
        <div class="panel-footer">
            <button class="btn btn-primary remove_btn" disabled onclick="proceedTo('<?= $details->record_no ?>', '<?= $details->emp_id ?>')">Proceed to Remove Outlet</button>
        </div>
    </div>
<?php
} else if ($request == 'extend_contract') {
?>
    <div class="form-group"> <i class="text-red">*</i>
        <label>Search Promo</label>
        <div class="input-group">
            <input class="form-control" name="employee" onkeyup="search_name(this.value)" autocomplete="off" type="text">
            <span class="input-group-addon"><i class="fa fa-user"></i></span>
        </div>
        <div class="search-results" style="display: none;"></div>
    </div>
    <?php
} else if ($request == 'load_business_unit') {

    if ($promo_type == 'ROVING') {
    ?>
        <table class="table table-bordered">
            <tr>
                <th colspan="2"> Business Unit</th>
            </tr>
            <?php

            $bUs = $this->dashboard_model->businessUnit_list();
            foreach ($bUs as $bu) {

                echo '
                        <tr>
                            <td>
                                <input type="checkbox" class="checkedEnable" name="stores[]" value="' . $bu->bunit_id . '/' . $bu->bunit_field . '" onchange="load_department()">
                            </td>
                            <td>' . $bu->bunit_name . '</td>
                        </tr>
                    ';
            }

            ?>
        </table>
    <?php
    } else {

    ?>
        <table class="table table-bordered">
            <tr>
                <th colspan="2"> Business Unit</th>
            </tr>
            <?php

            $bUs = $this->dashboard_model->businessUnit_list();
            foreach ($bUs as $bu) {

                echo '
                        <tr>
                            <td>
                                <input type="radio" class="checkedEnable" name="stores[]" value="' . $bu->bunit_id . '/' . $bu->bunit_field . '" onchange="load_department()">
                            </td>
                            <td>' . $bu->bunit_name . '</td>
                        </tr>
                    ';
            }

            ?>
        </table>
    <?php
    }
} else if ($request == 'show_intro') {

    if (is_array($stores)) {

        foreach ($stores as $key => $value) {

            $s = explode('/', $value);
            $bunit_id = $s[0];

            $bu = $this->contract_model->show_bu_details($bunit_id);
            echo '
                <tr>
                    <td><i class="text-red">*</i> ' . $bu->bunit_name . '</td>
                    <td>
                        <input type="hidden" name="bunit_intro[]" value="' . $bu->bunit_intro . '">
                        <input type="file" name="' . $bu->bunit_intro . '" id="' . $bu->bunit_intro . '" class="form-control" required onchange="validateForm(this.id)">
                    </td>
                </tr>
            ';
        }
    }
} else if ($request == 'load_promo_business_unit') {

    if ($fetch_data['promoType'] == 'ROVING') {
    ?>
        <table class="table table-bordered">
            <tr>
                <th colspan="2"> Business Unit</th>
            </tr>
            <?php

            $bUs = $this->dashboard_model->businessUnit_list();
            foreach ($bUs as $bu) {

                $hasBU = $this->dashboard_model->promo_has_bu($fetch_data['empId'], $bu->bunit_field);
                if ($hasBU > 0) {

                    echo '
                        <tr>
                            <td>
                                <input type="checkbox" class="checkedEnable" name="stores[]" value="' . $bu->bunit_id . '/' . $bu->bunit_field . '" disabled checked onchange="load_department()">
                            </td>
                            <td>' . $bu->bunit_name . '</td>
                        </tr>
                    ';
                } else {

                    echo '
                        <tr>
                            <td>
                                <input type="checkbox" class="checkedEnable" name="stores[]" value="' . $bu->bunit_id . '/' . $bu->bunit_field . '" disabled onchange="load_department()">
                            </td>
                            <td>' . $bu->bunit_name . '</td>
                        </tr>
                    ';
                }
            }

            ?>
        </table>
    <?php
    } else {

    ?>
        <table class="table table-bordered">
            <tr>
                <th colspan="2"> Business Unit</th>
            </tr>
            <?php

            $bUs = $this->dashboard_model->businessUnit_list();
            foreach ($bUs as $bu) {

                $hasBU = $this->dashboard_model->promo_has_bu($fetch_data['empId'], $bu->bunit_field);
                if ($hasBU > 0) {

                    echo '
                        <tr>
                            <td>
                                <input type="radio" class="checkedEnable" name="stores[]" value="' . $bu->bunit_id . '/' . $bu->bunit_field . '" disabled checked onchange="load_department()">
                            </td>
                            <td>' . $bu->bunit_name . '</td>
                        </tr>
                    ';
                } else {

                    echo '
                        <tr>
                            <td>
                                <input type="radio" class="checkedEnable" name="stores[]" value="' . $bu->bunit_id . '/' . $bu->bunit_field . '" disabled onchange="load_department()">
                            </td>
                            <td>' . $bu->bunit_name . '</td>
                        </tr>
                    ';
                }
            }

            ?>
        </table>
    <?php
    }
} else if ($request == 'load_promo_intro') {

    $counter = 0;
    $bUs = $this->dashboard_model->businessUnit_list();
    foreach ($bUs as $bu) {

        $hasBU = $this->dashboard_model->promo_has_bu($empId, $bu->bunit_field);
        if ($hasBU > 0) {

            echo '
                <tr>
                    <td><i class="text-red">*</i> ' . $bu->bunit_name . '</td>
                    <td>
                        <input type="hidden" name="bunit_intro[]" value="' . $bu->bunit_intro . '">
                        <input type="file" name="' . $bu->bunit_intro . '" id="' . $bu->bunit_intro . '" class="form-control" required onchange="validateForm(this.id)">
                    </td>
                </tr>
            ';
        }
        $counter++;
    }
} else if ($request == 'print_contract_permit') {

    $fullname = $this->employee_model->employee_name($emp_id)['name'];
    ?>
    <p style="font-size: 15px;">
        Contract of Employment of <code><?php echo $fullname; ?></code> was successfully added.<br>
        Please Proceed on <code>Printing of Contract</code> and <code>Permit-to-Work</code>. &nbsp;Thank You!
    </p>
    <br><br>
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-6">
                <button class="btn btn-primary btn-block" onclick="printPermit('<?= $emp_id; ?>')"> Permit-To-Work </button>
            </div>
            <div class="col-md-6">
                <button class="btn btn-primary btn-block" onclick="printContract('<?= $emp_id; ?>')"> Contract of Employment </button>
            </div>
        </div>
    </div>
<?php
} else if ($request == 'print_permit_renewal') {

    $emp = $this->employee_model->employee_info($emp_id);
    $cO = $this->contract_model->get_promo_cutoff($emp->record_no, $emp->emp_id);

    if ($cO->endFC == "") {
        $endFC = "last";
    } else {
        $endFC = $cO->endFC;
    }

    $cut_off = $cO->startFC . ' - ' . $endFC . ' / ' . $cO->startSC . ' - ' . $cO->endSC;

    $duty_days = ($emp->promo_type == 'STATION') ? 'DAILY' : '';
?>
    <link href="<?= base_url('assets/plugins/autoSuggest/css/jquery-ui.css') ?>" rel="stylesheet">
    <style type="text/css">
        .ui-autocomplete {
            padding: 0;
            list-style: none;
            background-color: #fff;
            width: 218px;
            border: 1px solid #B0BECA;
            max-height: 350px;
            overflow-x: hidden;
        }

        .ui-autocomplete .ui-menu-item {
            border-top: 1px solid #B0BECA;
            display: block;
            padding: 4px 6px;
            color: #353D44;
            cursor: pointer;
        }

        .ui-autocomplete .ui-menu-item:first-child {
            border-top: none;
        }

        .ui-autocomplete .ui-menu-item.ui-state-focus {
            background-color: #D5E5F4;
            color: #161A1C;
        }

        .ui-autocomplete {
            z-index: 9999;
        }
    </style>
    <input type="hidden" name="empId" value="<?= $emp_id; ?>">
    <input type="hidden" name="record_no" value="<?= $emp->record_no; ?>">
    <input type="hidden" name="permit" value="current">
    <div class="form-group"> <i class="text-red">*</i>
        <label>Search Promo</label>
        <div class="input-group">
            <input class="form-control" name="employee" disabled="" value="<?= $emp_id . ' * ' . $emp->name; ?>" autocomplete="off" type="text">
            <span class="input-group-addon"><i class="fa fa-user"></i></span>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group"> <i class="text-red">*</i>
                <label>Business Unit</label>
                <select name="storeName" class="form-control" required onchange="inputField(this.name)">
                    <option value=""> --Select-- </option>
                    <?php
                    $bUs = $this->dashboard_model->businessUnit_list();
                    foreach ($bUs as $bu) {

                        $hasBU = $this->dashboard_model->promo_has_bu($emp_id, $bu->bunit_field);
                        if ($hasBU > 0) {
                            echo '<option value="' . $bu->bunit_name . '|' . $bu->bunit_permit . '|' . $bu->bunit_dutySched . '|' . $bu->bunit_dutyDays . '|' . $bu->bunit_specialSched . '|' . $bu->bunit_specialDays . '">' . $bu->bunit_name . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="form-group"> <i class="text-red">*</i>
                <label>Duty Schedule</label>
                <select name="dutySched" class="form-control selects2 dutySched" required onchange="inputDutySched()">
                    <option value=""> --Select-- </option>
                    <?php

                    $shift_codes = $this->contract_model->get_shiftcodes();
                    foreach ($shift_codes as $sc) {

                        $shiftCode  = $sc['shiftCode'];
                        $In1        = $sc['1stIn'];
                        $Out1       = $sc['1stOut'];
                        $In2        = $sc['2ndIn'];
                        $Out2       = $sc['2ndOut'];

                        if ($In2 == "") {

                            echo "<option value = '$shiftCode'>$shiftCode = $In1-$Out1 </option>";
                        } else {

                            echo "<option value = '$shiftCode'>$shiftCode = $In1-$Out1, $In2-$Out2</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label>Special Schedule</label>
                <select name="specialSched" class="form-control selects2" onchange="inputSpecialDays(this.value)">
                    <option value=""> --Select-- </option>
                    <?php

                    $shift_codes = $this->contract_model->get_shiftcodes();
                    foreach ($shift_codes as $sc) {

                        $shiftCode  = $sc['shiftCode'];
                        $In1        = $sc['1stIn'];
                        $Out1       = $sc['1stOut'];
                        $In2        = $sc['2ndIn'];
                        $Out2       = $sc['2ndOut'];

                        if ($In2 == "") {

                            echo "<option value = '$shiftCode'>$shiftCode = $In1-$Out1 </option>";
                        } else {

                            echo "<option value = '$shiftCode'>$shiftCode = $In1-$Out1, $In2-$Out2</option>";
                        }
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group"> <i class="text-red">*</i>
                <label>Day Off</label>
                <select name="dayOff" class="form-control" required onchange="inputField(this.name)">
                    <option value=""> --Select-- </option>
                    <?php

                    $days = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday', 'No Day Off');
                    foreach ($days as $key => $value) {

                        echo '<option value="' . $value . '">' . $value . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="form-group"> <i class="text-red">*</i>
                <label>Duty Days</label>
                <input type="text" name="dutyDays" class="form-control" value="<?= $duty_days ?>" onkeyup="inputField(this.name)" style="text-transform: uppercase;" required>
            </div>
            <div class="form-group">
                <label>Special Days</label>
                <input type="text" name="specialDays" class="form-control" disabled="" onkeyup="inputField(this.name)" style="text-transform: uppercase;">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <label>Cut-off</label>
            <input type="hidden" name="cutOff" value="<?= $cO->statCut . '|' . $cut_off ?>">
            <input type="text" class="form-control" value="<?= $cut_off ?>" readonly>
        </div>
    </div>
    <script src="<?= base_url('assets/plugins/autoSuggest/js/jquery-ui.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/autoSuggest/js/jquery.select-to-autocomplete.js') ?>"></script>
    <script type="text/javascript">
        $(function() {
            $('select.selects2').selectToAutocomplete();
        });
    </script>
<?php

} else if ($request == 'print_contract_renewal') {

    $emp = $this->employee_model->employee_info($emp_id);

    $witness = $this->contract_model->show_employment_witness($emp->record_no, $emp_id);
    $chNo       = $witness->contract_header_no;
    $contractDate = ($witness->date_generated == '' || $witness->date_generated == '0000-00-00') ? date("m/d/Y") : date("m/d/Y", strtotime($witness->date_generated));
    $w1         = $witness->witness1;
    $w2         = $witness->witness2;
    $issuedon   = date("m/d/Y", strtotime($witness->issuedon));
    $issuedat   = $witness->issuedat;

    $cs = $this->contract_model->show_applicant_otherdetails($emp_id);
    $cedulaNo       = $cs->cedula_no;
    $sssNum         = $cs->sss_no;
    $cedula_date    = date("m/d/Y", strtotime($cs->cedula_date));
    $cedula_place   = $cs->cedula_place;
?>
    <style>
        .issued {
            display: none;
        }
    </style>
    <input type="hidden" name="empId" value="<?php echo $emp_id; ?>">
    <input type="hidden" name="contract_recordNo" value="<?php echo $emp->record_no; ?>">

    <div class="row">
        <div class="col-md-6">
            <div class="form-group"> <i class="text-red">*</i>
                <label>Witness(1)</label>
                <input type="text" name="witness1Renewal" class="form-control" placeholder="Firstname Lastname" required style="text-transform: uppercase;" value="<?= $w1 ?>" onkeyup="search_witness('witness1', this.value)">
                <div class="witness1Renewal" style="display: none;"></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group"> <i class="text-red">*</i>
                <label>Witness(2)</label>
                <input type="text" name="witness2Renewal" class="form-control" placeholder="Firstname Lastname" required style="text-transform: uppercase;" value="<?= $w2 ?>" onkeyup="search_witness('witness2', this.value)">
                <div class="witness2Renewal" style="display: none;"></div>
            </div>
        </div>
    </div>
    <div class="form-group"> <i class="text-red">*</i>
        <label>Contract Header</label>
        <select name="contractHeader" class="form-control" required onchange="inputField(this.name)">
            <option value=""> --Select-- </option>
            <?php

            $headers = $this->contract_model->contract_header_list();
            foreach ($headers as $header) {
                if ($header->ccode_no == $chNo) {

                    echo '<option value="' . $header->ccode_no . '" selected> ' . $header->company . ' ----- ' . $header->address . '</option>';
                } else {

                    echo '<option value="' . $header->ccode_no . '"> ' . $header->company . ' ----- ' . $header->address . '</option>';
                }
            }
            ?>
        </select>
    </div>
    <div class="form-group"> <i class="text-red">*</i>
        <label>Please choose either to use Cedula (CTC No.) or SSS No.</label>
        <div style="margin-left:40px;">
            <p>
            <div class="row">
                <div class="col-md-4">
                    <input type='radio' name='clear' required style="border-color: red;" id="clear1" value='Cedula' onclick="sssctc('ctc')"> Cedula (CTC No.)
                </div>
                <div class="col-md-8">
                    <input type='text' name='cedula' required value="<?= $cedulaNo; ?>" class="form-control issued" onkeyup="inputField(this.name)" data-inputmask='"mask": "CCI9999 99999999"' data-mask>
                </div>
            </div>
            </p>
            <p>
            <div class="row">
                <div class="col-md-4">
                    <input type='radio' name='clear' required id="clear2" value='SSS' onclick="sssctc('sss')"> SSS No.
                </div>
                <div class="col-md-8">
                    <input type='text' name='sss' required value="<?= $sssNum; ?>" class="form-control issued" onkeyup="inputField(this.name)" data-inputmask='"mask": "99-9999999-9"' data-mask>
                </div>
            </div>
            </p>
            <p>
            <div class="row">
                <div class="col-md-6 issuedOn issued">
                    <div class="form-group"> <i class="text-red">*</i>
                        <label>Issued On</label>
                        <div class="input-group date">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <?php
                            if (!empty($issuedon)) {
                                $issued_on = $issuedon;
                            } else if (!empty($cedula_date)) {
                                $issued_on = $cedula_date;
                            } else {
                                $issued_on = date('m/d/Y');
                            }
                            ?>
                            <input type="text" name="issuedOn" id="datepicker" class="form-control pull-right datepicker" data-inputmask="'alias': 'mm/dd/yyyy'" data-mask="" required value="<?= $issued_on ?>" onchange="inputField(this.name)">
                        </div>
                    </div>
                </div>
                <div class="col-md-6 issuedAt issued">
                    <div class="form-group"> <i class="text-red">*</i>
                        <label>Issued At</label>
                        <?php
                        if (!empty($issuedat)) {
                            $issued_at = $issuedat;
                        } else if (!empty($cedula_place)) {
                            $issued_at = $cedula_place;
                        } else {
                            $issued_at = date('m/d/Y');
                        }
                        ?>
                        <input type="text" name="issuedAt" class="form-control" value="<?= $issued_at ?>" required onkeyup="inputField(this.name)">
                    </div>
                </div>
            </div>
            </p>
        </div>
    </div>
    <div class="form-group"> <i class="text-red">*</i>
        <label>Date of Signing the Contract</label>
        <div class="input-group date">
            <div class="input-group-addon">
                <i class="fa fa-calendar"></i>
            </div>
            <input type="text" name="contractDate" id="datepicker" class="form-control pull-right datepicker" data-inputmask="'alias': 'mm/dd/yyyy'" data-mask="" required value="<?= $contractDate ?>" onchange="inputField(this.name)">
        </div>
    </div>
    <script type="text/javascript">
        //Date picker
        $(".datepicker").datepicker({

            inline: true,
            changeYear: true,
            changeMonth: true
        });

        // Input mask
        $("[data-mask]").inputmask();
    </script>
<?php
} else if ($request == 'print_current_permit') {
?>
    <div class="form-group"> <i class="text-red">*</i>
        <label>Search Promo</label>
        <div class="input-group">
            <input class="form-control" name="employee" onkeyup="searchDiserPermit(this.value)" autocomplete="off" type="text">
            <span class="input-group-addon"><i class="fa fa-user"></i></span>
        </div>
        <div class="search-results" style="display: none;"></div>
    </div>
    <div class="row current-permit-form">
        <div class="col-md-6">
            <div class="form-group"> <i class="text-red">*</i>
                <label>Business Unit</label>
                <select name="storeName" class="form-control" required disabled onchange="inputField(this.name)"></select>
            </div>
            <div class="form-group"> <i class="text-red">*</i>
                <label>Duty Schedule</label>
                <select name="dutySched" class="form-control selects2 dutySched" required disabled onchange="inputDutySched()">
                    <option value=""> --Select-- </option>
                    <?php

                    $shift_codes = $this->contract_model->get_shiftcodes();
                    foreach ($shift_codes as $sc) {

                        $shiftCode  = $sc['shiftCode'];
                        $In1        = $sc['1stIn'];
                        $Out1       = $sc['1stOut'];
                        $In2        = $sc['2ndIn'];
                        $Out2       = $sc['2ndOut'];

                        if ($In2 == "") {

                            echo "<option value = '$shiftCode'>$shiftCode = $In1-$Out1 </option>";
                        } else {

                            echo "<option value = '$shiftCode'>$shiftCode = $In1-$Out1, $In2-$Out2</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label>Special Schedule</label>
                <select name="specialSched" class="form-control selects2" disabled onchange="inputSpecialDays(this.value)">
                    <option value=""> --Select-- </option>
                    <?php

                    $shift_codes = $this->contract_model->get_shiftcodes();
                    foreach ($shift_codes as $sc) {

                        $shiftCode  = $sc['shiftCode'];
                        $In1        = $sc['1stIn'];
                        $Out1       = $sc['1stOut'];
                        $In2        = $sc['2ndIn'];
                        $Out2       = $sc['2ndOut'];

                        if ($In2 == "") {

                            echo "<option value = '$shiftCode'>$shiftCode = $In1-$Out1 </option>";
                        } else {

                            echo "<option value = '$shiftCode'>$shiftCode = $In1-$Out1, $In2-$Out2</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label>Cut-off</label>
                <select name="cutOff" class="form-control" disabled>
                    <option value=""> --Select-- </option>
                    <?php

                    $cutoffs = $this->employee_model->cutoff_list();
                    foreach ($cutoffs as $co) {

                        $endFC = ($co->endFC != '') ? $co->endFC : 'last';
                        if ($co->startFC != '') {

                            $cut_off = $co->startFC . '-' . $endFC . ' / ' . $co->startSC . '-' . $co->endSC;
                        } else {

                            $cut_off = '';
                        }

                        echo '<option value="' . $co->statCut . '">' . $cut_off . '</option>';
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group"> <i class="text-red">*</i>
                <label>Day Off</label>
                <select name="dayOff" class="form-control" required disabled onchange="inputField(this.name)">
                    <option value=""> --Select-- </option>
                    <?php

                    $days = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday', 'No Day Off');
                    foreach ($days as $key => $value) {

                        echo '<option value="' . $value . '">' . $value . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="form-group"> <i class="text-red">*</i>
                <label>Duty Days</label>
                <input type="text" name="dutyDays" class="form-control" onkeyup="inputField(this.name)" style="text-transform: uppercase;" required disabled>
            </div>
            <div class="form-group">
                <label>Special Days</label>
                <input type="text" name="specialDays" class="form-control" disabled="" disabled onkeyup="inputField(this.name)" style="text-transform: uppercase;">
            </div>
        </div>
    </div>
<?php
} else if ($request == 'current_permit_form') {

    $emp = $this->employee_model->employee_info($empId);
    $cO = $this->contract_model->get_promo_cutoff($emp->record_no, $emp->emp_id);

    if ($cO->endFC == "") {
        $endFC = "last";
    } else {
        $endFC = $cO->endFC;
    }

    $cut_off = $cO->startFC . ' - ' . $endFC . ' / ' . $cO->startSC . ' - ' . $cO->endSC;

?>
    <link href="<?= base_url('assets/plugins/autoSuggest/css/jquery-ui.css') ?>" rel="stylesheet">
    <style type="text/css">
        .ui-autocomplete {
            padding: 0;
            list-style: none;
            background-color: #fff;
            width: 218px;
            border: 1px solid #B0BECA;
            max-height: 350px;
            overflow-x: hidden;
        }

        .ui-autocomplete .ui-menu-item {
            border-top: 1px solid #B0BECA;
            display: block;
            padding: 4px 6px;
            color: #353D44;
            cursor: pointer;
        }

        .ui-autocomplete .ui-menu-item:first-child {
            border-top: none;
        }

        .ui-autocomplete .ui-menu-item.ui-state-focus {
            background-color: #D5E5F4;
            color: #161A1C;
        }

        .ui-autocomplete {
            z-index: 9999;
        }
    </style>
    <input type="hidden" name="empId" value="<?= $empId; ?>">
    <input type="hidden" name="record_no" value="<?= $emp->record_no; ?>">
    <div class="col-md-6">
        <div class="form-group"> <i class="text-red">*</i>
            <label>Business Unit</label>
            <select name="storeName" class="form-control" required onchange="inputField(this.name)">
                <?php

                echo '<option value=""> --Select-- </option>';
                $bUs = $this->dashboard_model->businessUnit_list();
                foreach ($bUs as $bu) {

                    $hasBU = $this->dashboard_model->promo_has_bu($empId, $bu->bunit_field);
                    if ($hasBU > 0) {
                        echo '<option value="' . $bu->bunit_name . '|' . $bu->bunit_permit . '|' . $bu->bunit_dutySched . '|' . $bu->bunit_dutyDays . '|' . $bu->bunit_specialSched . '|' . $bu->bunit_specialDays . '">' . $bu->bunit_name . '</option>';
                    }
                }
                ?>
            </select>
        </div>
        <div class="form-group"> <i class="text-red">*</i>
            <label>Duty Schedule</label>
            <select name="dutySched" class="form-control selects2 dutySched" required onchange="inputDutySched()">
                <option value=""> --Select-- </option>
                <?php

                $shift_codes = $this->contract_model->get_shiftcodes();
                foreach ($shift_codes as $sc) {

                    $shiftCode  = $sc['shiftCode'];
                    $In1        = $sc['1stIn'];
                    $Out1       = $sc['1stOut'];
                    $In2        = $sc['2ndIn'];
                    $Out2       = $sc['2ndOut'];

                    if ($In2 == "") {

                        echo "<option value = '$shiftCode'>$shiftCode = $In1-$Out1 </option>";
                    } else {

                        echo "<option value = '$shiftCode'>$shiftCode = $In1-$Out1, $In2-$Out2</option>";
                    }
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label>Special Schedule</label>
            <select name="specialSched" class="form-control selects2" onchange="inputSpecialDays(this.value)">
                <option value=""> --Select-- </option>
                <?php

                $shift_codes = $this->contract_model->get_shiftcodes();
                foreach ($shift_codes as $sc) {

                    $shiftCode  = $sc['shiftCode'];
                    $In1        = $sc['1stIn'];
                    $Out1       = $sc['1stOut'];
                    $In2        = $sc['2ndIn'];
                    $Out2       = $sc['2ndOut'];

                    if ($In2 == "") {

                        echo "<option value = '$shiftCode'>$shiftCode = $In1-$Out1 </option>";
                    } else {

                        echo "<option value = '$shiftCode'>$shiftCode = $In1-$Out1, $In2-$Out2</option>";
                    }
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label>Cut-off</label>
            <input type="hidden" name="cutOff" value="<?= $cO->statCut . '|' . $cut_off ?>">
            <input type="text" class="form-control" value="<?= $cut_off ?>" readonly>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group"> <i class="text-red">*</i>
            <label>Day Off</label>
            <select name="dayOff" class="form-control" required onchange="inputField(this.name)">
                <option value=""> --Select-- </option>
                <?php

                $days = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday', 'No Day Off');
                foreach ($days as $key => $value) {

                    echo '<option value="' . $value . '">' . $value . '</option>';
                }
                ?>
            </select>
        </div>
        <div class="form-group"> <i class="text-red">*</i>
            <label>Duty Days</label>
            <input type="text" name="dutyDays" class="form-control" value="<?php echo ($emp->promo_type == 'STATION') ? 'DAILY' : '' ?>" onkeyup="inputField(this.name)" style="text-transform: uppercase;" required>
        </div>
        <div class="form-group">
            <label>Special Days</label>
            <input type="text" name="specialDays" class="form-control" disabled="" disabled onkeyup="inputField(this.name)" style="text-transform: uppercase;">
        </div>
    </div>
    <script src="<?= base_url('assets/plugins/autoSuggest/js/jquery-ui.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/autoSuggest/js/jquery.select-to-autocomplete.js') ?>"></script>
    <script type="text/javascript">
        $(function() {
            $('select.selects2').selectToAutocomplete();
        });
    </script>
<?php

} else if ($request == 'print_previous_permit') {
?>
    <div class="form-group"> <i class="text-red">*</i>
        <label>Search Promo</label>
        <div class="input-group">
            <input class="form-control" name="employee" onkeyup="searchDiserPermit(this.value)" autocomplete="off" type="text">
            <span class="input-group-addon"><i class="fa fa-user"></i></span>
        </div>
        <div class="search-results" style="display: none;"></div>
    </div>
    <div class="previous-contract">

    </div>
<?php

} else if ($request == 'display_previous_contract') {

?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>Previous Permit</strong>
        </div>
        <div class="panel-body size-emp">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Startdate</th>
                        <th>EOCdate</th>
                        <th>
                            <center>Action</center>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (count($contracts) > 0) {

                        foreach ($contracts as $contract) {

                            $record_no = $contract->record_no;
                            $emp_id = $contract->emp_id;
                            echo "
                                <tr>
                                    <td>" . date('M. d, Y', strtotime($contract->startdate)) . "</td>
                                    <td>" . date('M. d, Y', strtotime($contract->eocdate)) . "</td>
                                    <td align='center'><button class='btn btn-primary btn-block btn-sm' onclick='printPreviousPermit(\"$emp_id\",\"$record_no\")'>Print Permit</button></td>
                                </tr>
                            ";
                        }
                    } else {

                        echo '
                            <tr>
                                <td colspan = "2">No Previous Contract</td>
                            </tr>
                        ';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
<?php

} else if ($request == 'display_previous_permit') {

    $empId = $data['emp_id'];
    $emp = $this->contract_model->show_previous_contract($data);
    $sched = $this->contract_model->get_promo_cutoff($emp->record_no, $emp->emp_id);
    $statCut = (!empty($sched)) ? $statCut = $sched->statCut : '';

?>
    <link href="<?= base_url('assets/plugins/autoSuggest/css/jquery-ui.css') ?>" rel="stylesheet">
    <style type="text/css">
        .ui-autocomplete {
            padding: 0;
            list-style: none;
            background-color: #fff;
            width: 218px;
            border: 1px solid #B0BECA;
            max-height: 350px;
            overflow-x: hidden;
        }

        .ui-autocomplete .ui-menu-item {
            border-top: 1px solid #B0BECA;
            display: block;
            padding: 4px 6px;
            color: #353D44;
            cursor: pointer;
        }

        .ui-autocomplete .ui-menu-item:first-child {
            border-top: none;
        }

        .ui-autocomplete .ui-menu-item.ui-state-focus {
            background-color: #D5E5F4;
            color: #161A1C;
        }

        .ui-autocomplete {
            z-index: 9999;
        }
    </style>
    <input type="hidden" name="empId" value="<?= $empId; ?>">
    <input type="hidden" name="record_no" value="<?= $emp->record_no; ?>">
    <div class="col-md-12">
        <div class="form-group"> <i class="text-red">*</i>
            <label>Search Promo</label>
            <div class="input-group">
                <input class="form-control" name="employee" type="text" disabled value="<?= $empId . '*' . $emp->names; ?>">
                <span class="input-group-addon"><i class="fa fa-user"></i></span>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group"> <i class="text-red">*</i>
            <label>Business Unit</label>
            <select name="storeName" class="form-control" required onchange="inputField(this.name)">
                <?php

                echo '<option value=""> --Select-- </option>';
                $bUs = $this->dashboard_model->businessUnit_list();
                foreach ($bUs as $bu) {

                    $hasBU = $this->dashboard_model->promo_has_bu($empId, $bu->bunit_field);
                    if ($hasBU > 0) {
                        echo '<option value="' . $bu->bunit_name . '|' . $bu->bunit_permit . '|' . $bu->bunit_dutySched . '|' . $bu->bunit_dutyDays . '|' . $bu->bunit_specialSched . '|' . $bu->bunit_specialDays . '">' . $bu->bunit_name . '</option>';
                    }
                }
                ?>
            </select>
        </div>
        <div class="form-group"> <i class="text-red">*</i>
            <label>Duty Schedule</label>
            <select name="dutySched" class="form-control selects2 dutySched" required onchange="inputDutySched()">
                <option value=""> --Select-- </option>
                <?php

                $shift_codes = $this->contract_model->get_shiftcodes();
                foreach ($shift_codes as $sc) {

                    $shiftCode  = $sc['shiftCode'];
                    $In1        = $sc['1stIn'];
                    $Out1       = $sc['1stOut'];
                    $In2        = $sc['2ndIn'];
                    $Out2       = $sc['2ndOut'];

                    if ($In2 == "") {

                        echo "<option value = '$shiftCode'>$shiftCode = $In1-$Out1 </option>";
                    } else {

                        echo "<option value = '$shiftCode'>$shiftCode = $In1-$Out1, $In2-$Out2</option>";
                    }
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label>Special Schedule</label>
            <select name="specialSched" class="form-control selects2" onchange="inputSpecialDays(this.value)">
                <option value=""> --Select-- </option>
                <?php

                $shift_codes = $this->contract_model->get_shiftcodes();
                foreach ($shift_codes as $sc) {

                    $shiftCode  = $sc['shiftCode'];
                    $In1        = $sc['1stIn'];
                    $Out1       = $sc['1stOut'];
                    $In2        = $sc['2ndIn'];
                    $Out2       = $sc['2ndOut'];

                    if ($In2 == "") {

                        echo "<option value = '$shiftCode'>$shiftCode = $In1-$Out1 </option>";
                    } else {

                        echo "<option value = '$shiftCode'>$shiftCode = $In1-$Out1, $In2-$Out2</option>";
                    }
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label>Cut-off</label>
            <select name="cutOff" class="form-control">
                <option value=""> --Select-- </option>
                <?php
                $cutoffs = $this->employee_model->cutoff_list();
                foreach ($cutoffs as $co) {

                    $endFC = ($co->endFC != '') ? $co->endFC : 'last';
                    $cut_off = $co->startFC . '-' . $endFC . ' / ' . $co->startSC . '-' . $co->endSC;

                    if ($sched->statCut == $co->statCut) {

                        echo '<option value="' . $co->statCut . '|' . $cut_off . '" selected>' . $cut_off . '</option>';
                    } else {

                        echo '<option value="' . $co->statCut . '|' . $cut_off . '">' . $cut_off . '</option>';
                    }
                }
                ?>
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group"> <i class="text-red">*</i>
            <label>Day Off</label>
            <select name="dayOff" class="form-control" required onchange="inputField(this.name)">
                <option value=""> --Select-- </option>
                <?php

                $days = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday', 'No Day Off');
                foreach ($days as $key => $value) {

                    echo '<option value="' . $value . '">' . $value . '</option>';
                }
                ?>
            </select>
        </div>
        <div class="form-group"> <i class="text-red">*</i>
            <label>Duty Days</label>
            <input type="text" name="dutyDays" class="form-control" value="<?php echo ($emp->promo_type == 'STATION') ? 'DAILY' : '' ?>" onkeyup="inputField(this.name)" style="text-transform: uppercase;" required>
        </div>
        <div class="form-group">
            <label>Special Days</label>
            <input type="text" name="specialDays" class="form-control" disabled="" disabled onkeyup="inputField(this.name)" style="text-transform: uppercase;">
        </div>
    </div>
    <script src="<?= base_url('assets/plugins/autoSuggest/js/jquery-ui.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/autoSuggest/js/jquery.select-to-autocomplete.js') ?>"></script>
    <script type="text/javascript">
        $(function() {
            $('select.selects2').selectToAutocomplete();
        });
    </script>
<?php

} else if ($request == 'transfer_rate_form') {

    $contracts = $this->contract_model->show_previous_contracts($emp_id);
    $emp = $this->employee_model->employee_info($emp_id);

?>
    <input type="hidden" name="emp_id" value="<?php echo $emp_id; ?>">
    <input type="hidden" name="record_no" value="<?php echo $emp->record_no; ?>">
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>Previous Rate(s)</strong>
        </div>
        <div class="panel-body">
            <div class="size-emp">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Store</th>
                            <th>Rate</th>
                            <th>Rater</th>
                            <th>Startdate</th>
                            <th>Eocdate</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                        $loop = 1;
                        if (count($contracts) > 0) {

                            foreach ($contracts as $contract) {

                                $appraisals = $this->contract_model->get_appraisal_details($contract->record_no, $contract->emp_id);
                                if ($appraisals->num_rows() > 0) {

                                    $numrates = $appraisals->result();
                                    foreach ($numrates as $numrate) {

                                        if ($loop == 1) {

                                            echo '
                                            <tr>
                                                <td>
                                                    <input type="checkbox" name="appraisal[]" value="' . $numrate->details_id . '|' . $contract->record_no . '|' . $numrate->store . '" onchange="transferRate()">
                                                </td>
                                                <td>' . $numrate->store . '</td>
                                                <td>' . $numrate->numrate . '</td>
                                                <td>' . ucwords(strtolower($this->employee_model->employee_name($numrate->rater)['name'])) . '</td>
                                                <td>' . date('m/d/Y', strtotime($contract->startdate)) . '</td>
                                                <td>' . date('m/d/Y', strtotime($contract->eocdate)) . '</td>
                                            </tr>
                                        ';
                                        } else {

                                            echo '
                                            <tr>
                                                <td>
                                                    <input type="checkbox" value="' . $numrate->details_id . '" disabled>
                                                </td>
                                                <td>' . $numrate->store . '</td>
                                                <td>' . $numrate->numrate . '</td>
                                                <td>' . ucwords(strtolower($this->employee_model->employee_name($numrate->rater)['name'])) . '</td>
                                                <td>' . date('m/d/Y', strtotime($contract->startdate)) . '</td>
                                                <td>' . date('m/d/Y', strtotime($contract->eocdate)) . '</td>
                                            </tr>
                                        ';
                                        }
                                    }
                                } else {

                                    if ($loop > 1)
                                        continue;

                                    echo '
                                    <tr>
                                        <td colspan="6" style="text-align:center;">No Records Found!</td>
                                    </tr>
                                ';
                                }

                                $loop++;
                            }
                        } else {

                            echo '
                                <tr>
                                    <td colspan="6" style="text-align:center;">No Records Found!</td>
                                </tr>
                            ';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="panel-footer">
            <button type="submit" class="btn btn-primary btn-sm transfer-rate" disabled><i class="fa fa-reply-all"></i> Transfer Rate</button>
            <span class="loadingSave"></span>
        </div>
    </div>
    <?php
} else if ($request == 'upload_clearance_renewal') {

    $emp = $this->employee_model->employee_info($emp_id);
    $bUs = $this->dashboard_model->businessUnit_list();
    foreach ($bUs as $bu) {

        $hasBU = $this->dashboard_model->promo_has_bu($emp_id, $bu->bunit_field);
        if ($hasBU > 0) {
    ?>
            <input type="hidden" name="clearances[]" value="<?= $bu->bunit_clearance ?>">
            <div class="row">
                <div class="col-md-12">
                    <b><?= "Clearance ($bu->bunit_name)" ?></b><br>
                    <img id="photo<?= $bu->bunit_clearance ?>" class='preview img-responsive' /><br>
                    <input type='file' name='<?= $bu->bunit_clearance ?>' id='<?= $bu->bunit_clearance ?>' class='btn btn-default' required onchange='readURL(this,"<?= $bu->bunit_clearance ?>")'>
                    <input type='button' name='clear<?= $bu->bunit_clearance ?>' id='clear<?= $bu->bunit_clearance ?>' style='display:none' class='btn btn-default' value='Clear' onclick="clears('<?= $bu->bunit_clearance ?>','photo<?= $bu->bunit_clearance ?>','clear<?= $bu->bunit_clearance ?>')">
                    <input type='button' id='<?= $bu->bunit_clearance ?>_change' style='display:none' class='btn btn-primary btn-sm' value='Change Clearance?' onclick='changePhoto("Clearance","<?= $bu->bunit_clearance ?>","<?= $bu->bunit_clearance; ?>_change")'>
                </div>
            </div><br>
    <?php
        }
    }
    ?>
    <input type="hidden" name="emp_id" id="emp_id" value="<?php echo $emp_id; ?>">
    <input type="hidden" name="record_no" id="record_no" value="<?php echo $emp->record_no; ?>">
<?php
} else if ($request == 'show_company') {

?>
    <div class="form-group">
        <label>Company</label>
        <div class="input-group">
            <input type="hidden" name="company_code" value="<?= $company->pc_code ?>">
            <input class="form-control" type="text" id="edit-company" name="company" value="<?= $company->pc_name ?>" style="text-transform: uppercase; border-color: rgb(204, 204, 204);" onkeyup="inputField(this.name)" required>
            <span class="input-group-addon"><i class="fa fa-bank"></i></span>
        </div>
    </div>
<?php
} else if ($request == 'show_agency') {
?>
    <div class="form-group">
        <label>Agency</label>
        <div class="input-group">
            <input type="hidden" name="agency_code" value="<?= $agency->agency_code ?>">
            <input class="form-control" type="text" id="edit-agency" name="agency" value="<?= $agency->agency_name ?>" style="text-transform: uppercase; border-color: rgb(204, 204, 204);" onkeyup="inputField(this.name)" required>
            <span class="input-group-addon"><i class="fa fa-bank"></i></span>
        </div>
    </div>
<?php
} else if ($request == 'choose_agency') {
?>
    <div class="form-group">
        <label>Agency</label>
        <select name="agency" class="form-control" onchange="company_list(this.value)">
            <option value="">Select Agency</option>
            <?php
            foreach ($agencies as $agency) {

                echo '<option value="' . $agency->agency_code . '">' . $agency->agency_name . '</option>';
            }
            ?>
        </select>
    </div>
<?php
} else if ($request == 'tag_company_agency') {
?>
    <div class="table-responsive">
        <table id="dt_companies" class="table table-bordered table-hover" width="100%">
            <thead>
                <tr>
                    <th>Company</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $companies = $this->setup_model->company_list(1);
                foreach ($companies as $company) {

                    $exist = $this->setup_model->check_company_agency($agency_code, $company->pc_name);
                    if ($exist) {

                        $action =  '<input type="checkbox" name="companies[]" value="' . $company->pc_name . '" checked="">';
                    } else {

                        $action =  '<input type="checkbox" name="companies[]" value="' . $company->pc_name . '">';
                    }

                    echo '
                        <tr>
                            <td>' . $company->pc_name . '</td>
                            <td>' . $action . '</td>
                        </tr>
                    ';
                }
                ?>
            </tbody>
        </table>
    </div>
    <script type="text/javascript">
        $("table#dt_companies").DataTable({
            "destroy": true,
            "scrollY": "200px",
            "scrollCollapse": true,
            "paging": false
        });
    </script>
<?php
} else if ($request == 'show_product') {
?>
    <div class="form-group">
        <label>Product</label>
        <div class="input-group">
            <input type="hidden" name="id" value="<?= $product->id ?>">
            <input class="form-control" type="text" id="edit-product" name="product" value="<?= $product->product ?>" style="text-transform: uppercase; border-color: rgb(204, 204, 204);" onkeyup="inputField(this.name)" required>
            <span class="input-group-addon"><i class="fa fa-bank"></i></span>
        </div>
    </div>
<?php
} else if ($request == 'choose_company') {
?>
    <div class="form-group">
        <label>Company</label>
        <select name="company" class="form-control" onchange="product_list(this.value)">
            <option value="">Select Company</option>
            <?php
            foreach ($companies as $company) {

                echo '<option value="' . $company->pc_name . '">' . $company->pc_name . '</option>';
            }
            ?>
        </select>
    </div>
<?php
} else if ($request == 'tag_product_company') {

?>
    <div class="table-responsive">
        <table id="dt_products" class="table table-bordered table-hover" width="100%">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $products = $this->setup_model->product_list(1);
                foreach ($products as $product) {

                    $exist = $this->setup_model->check_product_company($company, $product->product);
                    if ($exist) {

                        $action =  '<input type="checkbox" name="products[]" value="' . $product->product . '" checked="">';
                    } else {

                        $action =  '<input type="checkbox" name="products[]" value="' . $product->product . '">';
                    }

                    echo '
                        <tr>
                            <td>' . $product->product . '</td>
                            <td>' . $action . '</td>
                        </tr>
                    ';
                }
                ?>
            </tbody>
        </table>
    </div>
    <script type="text/javascript">
        $("table#dt_products").DataTable({
            "destroy": true,
            "scrollY": "200px",
            "scrollCollapse": true,
            "paging": false
        });
    </script>
<?php
} else if ($request == 'supervisor_details') {
?>
    <table class="table">
        <tr>
            <td>Company</td>
            <td>:</td>
            <td><?= ucwords(strtolower($this->employee_model->asc_company_name($supervisor->company_code)['company'])); ?></td>
        </tr>
        <tr>
            <td>Business Unit</td>
            <td>:</td>
            <td><?= ucwords(strtolower($this->employee_model->get_businessunit_name($supervisor->company_code, $supervisor->bunit_code)['business_unit'])); ?></td>
        </tr>
        <tr>
            <td>Department</td>
            <td>:</td>
            <td><?= ucwords(strtolower($this->employee_model->get_department_name($supervisor->company_code, $supervisor->bunit_code, $supervisor->dept_code)['dept_name'])); ?></td>
        </tr>
        <tr>
            <td>Section</td>
            <td>:</td>
            <td><?= ucwords(strtolower($this->employee_model->get_section_name($supervisor->company_code, $supervisor->bunit_code, $supervisor->dept_code, $supervisor->section_code)['section_name'])); ?></td>
        </tr>
        <tr>
            <td>Position</td>
            <td>:</td>
            <td><?= ucwords(strtolower($supervisor->position)); ?></td>
        </tr>
        <tr>
            <td>Position Level</td>
            <td>:</td>
            <td><?= ucwords(strtolower($supervisor->poslevel)); ?></td>
        </tr>
        <tr>
            <td>Employee Type</td>
            <td>:</td>
            <td><?= ucwords(strtolower($supervisor->emp_type)); ?></td>
        </tr>
    </table>
<?php
} else if ($request == 'list_of_subordinates') {
?>
    <div class="panel panel-default">
        <div class="panel-heading">S U B O R D I N A T E S
            <span style="float:right"><a href="javascript:void(0)" class="btn btn-primary btn-sm" onclick="addSubordinates()"><span class="fa fa-street-view"></span> Add Subordinates </a> | <a href="javascript:void(0)" class="btn btn-warning btn-sm" onclick="remove_sub()"><span class="fa fa-remove"></span> Remove</a></span>
        </div>
        <div class="panel-body">
            <table id="dt-subordinates" class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th></th>
                        <th>Emp.ID</th>
                        <th>Name</th>
                        <th>Position</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php

                    foreach ($subordinates as $subordinate) {

                        $ratee = $subordinate->emp_id;
                        $id = $subordinate->record_no;

                        if ($subordinate->current_status == "Active") {

                            $class = "btn btn-success btn-xs btn-flat";
                        } else if ($subordinate->current_status == 'blacklisted') {

                            $class = "btn btn-danger btn-xs btn-flat";
                        } else {

                            $class = "btn btn-warning btn-xs btn-flat";
                        }

                        echo '
                            <tr>
                                <td>
                                    <input type="checkbox" name="subordinates[]" value="' . $id . '">
                                </td>
                                <td><a href="' . base_url('placement/page/menu/employee/profile') . '/' . $ratee . '" target="_blank">' . $ratee . '</a></td>
                                <td>' . ucwords(strtolower($subordinate->name)) . '</td>
                                <td>' . $subordinate->position . '</td>
                                <td><span class="' . $class . ' btn-block">' . ucfirst(strtolower($subordinate->current_status)) . '</span></td>
                            </tr>
                        ';
                    }

                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <script type="text/javascript">
        $(function() {
            $("table#dt-subordinates").DataTable({
                "destroy": true,
                "order": [
                    [2, 'asc']
                ],
                scrollY: '320px',
                scrollCollapse: true,
                paging: false
            });
        });
    </script>
<?php
} else if ($request == 'employee_list') {
?>
    <table id="dt-employees" class="table table-striped table-hover">
        <thead>
            <tr>
                <th></th>
                <th>Emp.ID</th>
                <th>Name</th>
                <th>Position</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php

            foreach ($employees as $employee) {

                if ($employee->current_status == 'Active') {
                    $class = "btn btn-success btn-xs";
                } else {
                    $class = "btn btn-warning btn-xs";
                }

                $exist = $this->setup_model->check_subordinates($rater, $employee->emp_id);
                if ($exist == 0) {
                    echo '
                        <tr>
                            <td>
                                <input type="checkbox" name="employees[]" value="' . $employee->emp_id . '">
                            </td>
                            <td>' . $employee->emp_id . '</td>
                            <td><a href="' . base_url('placement/page/menu/employee/profile') . '/' . $employee->emp_id . '" target="_blank">' . ucwords(strtolower($employee->name)) . '</a></td>
                            <td>' . $employee->position . '</td>
                            <td><span class="' . $class . ' btn-block">' . $employee->current_status . '</span></td>
                        </tr>
                    ';
                }
            }
            ?>
        </tbody>
    </table>
    <script type="text/javascript">
        $(function() {
            $("table#dt-employees").DataTable({
                "destroy": true,
                "order": [
                    [2, 'asc']
                ],
                scrollY: '300px',
                scrollCollapse: true,
                paging: false
            });
        });
    </script>
<?php
} else if ($request == 'upload_resignation_letter') {

    echo '
        <input type="hidden" name="emp_id" value="' . $data['emp_id'] . '">
        <input type="hidden" name="termination_no" value="' . $data['termination_no'] . '">
    ';
?>
    <div class="row">
        <div class="col-md-12">
            <b>Resignation Letter</b><br>
            <img id="photoresignation" class='preview img-responsive' /><br>
            <input type='file' name='resignation' id='resignation' class='btn btn-default' required onchange='readURL(this,"resignation")'>
            <input type='button' name='clearresignation' id='clearresignation' style='display:none' class='btn btn-default' value='Clear' onclick="clears('resignation','photoresignation','clearresignation')">
        </div>
    </div><br>
    <?php
} else if ($request == 'check_rt_status') {

    $bUs = $this->dashboard_model->businessUnit_list();
    foreach ($bUs as $bu) {

        $hasBU = $this->dashboard_model->promo_has_bu($data['emp_id'], $bu->bunit_field);
        if ($hasBU > 0) {
    ?>
            <div class="form-group">
                <label>Clearance (<?= $bu->bunit_name ?>)</label> <i class="text-red">*</i>
                <input type="hidden" name="clearances[]" value="<?= $bu->bunit_clearance ?>">
                <input type="file" name="<?= $bu->bunit_clearance ?>" class="btn btn-default btn-flat clearances" required>
            </div>
        <?php
        }
    }

    if ($data['rt_status'] == 'Resigned') {
        ?>
        <div class="form-group">
            <label>Resignation Letter</label> <i class="text-red">*</i>
            <input type="file" name="resignation" class="btn btn-default btn-flat" required>
        </div>
    <?php
    }
} else if ($request == 'resignation/list_of_subordinates') {
    ?>
    <div class="panel panel-default">
        <div class="panel-heading">S U B O R D I N A T E S</div>
        <div class="panel-body">
            <table id="dt-employees" class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Emp.ID</th>
                        <th>Name</th>
                        <th>EmpType</th>
                        <th>Position</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($subordinates as $subordinate) {

                        $epas = array();
                        $bUs = $this->dashboard_model->businessUnit_list();
                        foreach ($bUs as $bu) {

                            $hasBU = $this->dashboard_model->promo_has_bu($subordinate->emp_id, $bu->bunit_field);
                            if ($hasBU > 0) {
                                $epas[] = $bu->bunit_epascode;
                            }
                        }

                        $no_epas = $this->resignation_model->check_promo_epas($subordinate->emp_id, $subordinate->emp_recordno, $epas);
                        if ($no_epas > 0) {

                    ?>
                            <tr class="<?php if ($this->resignation_model->show_resignation_status($subordinate->emp_id, 'Pending', $rater)) echo 'info';
                                        else if ($this->resignation_model->show_resignation_status($subordinate->emp_id, 'Done', $rater)) echo 'success';  ?>">
                        <?php
                            echo '  <td>' . $subordinate->emp_id . '</td>
                                    <td><a href="' . base_url('placement/page/menu/employee/profile') . '/' . $subordinate->emp_id . '" target="_blank">' . ucwords(strtolower($subordinate->name)) . '</a></td>
                                    <td>' . $subordinate->emp_type . '</td>
                                    <td>' . $subordinate->position . '</td>
                                    <td>';

                            if ($this->resignation_model->show_resignation_status($subordinate->emp_id, 'Pending', $rater) > 0) {
                                echo '
                                    <a href="javascript:void(0);" id="untag_' . $subordinate->emp_id . '_' . $rater . '" class="text-danger action" data-toggle="tooltip" data-placement="top" title="Untag for Resignation">
                                        <i class="fa fa-remove"></i>
                                    </a>
                                ';
                            } else if ($this->resignation_model->show_resignation_status($subordinate->emp_id, 'Done', $rater) > 0) {
                                echo '<i data-toggle="tooltip" data-placement="top" title="EPAS done" class="fa  fa-thumbs-o-up"></i>';
                            } else {
                                echo '
                                    <a href="javascript:void(0);" id="tag_' . $subordinate->emp_id . '_' . $rater . '" class="action" data-toggle="tooltip" data-placement="top" title="Click to Tag for Resignation">
                                        <i class="fa fa-tag"></i>
                                    </a>
                                ';
                            }

                            echo '   </td>
                                </tr>
                            ';
                        }
                    }
                        ?>
                </tbody>
            </table>
        </div>
    </div>
    <script type="text/javascript">
        $(function() {
            let dt_employees = $("table#dt-employees").DataTable({
                "destroy": true,
                "order": [
                    [1, 'asc']
                ]
            });

            $('table#dt-employees').on('click', 'a.action', function() {

                let [action, emp_id, rater] = this.id.split("_");

                if (!$(this).parents('tr').hasClass('selected')) {
                    dt_employees.$('tr.selected').removeClass('selected');
                    $(this).parents('tr').addClass('selected');
                }

                $.alert.open({
                    type: 'warning',
                    cancel: false,
                    content: "Are you sure you want to " + action + " for resignation?",
                    buttons: {
                        OK: 'Ok'
                    },

                    callback: function(button) {
                        if (button == 'OK') {

                            $.ajax({
                                type: "POST",
                                url: "<?= site_url('update_resignation_status') ?>",
                                data: {
                                    action,
                                    emp_id,
                                    rater
                                },
                                success: function(data) {

                                    let response = JSON.parse(data);
                                    if (response.status == "success") {

                                        $.alert.open({
                                            type: 'warning',
                                            title: 'Info',
                                            icon: 'confirm',
                                            cancel: false,
                                            content: "Successfully " + action + " for resignation.",
                                            buttons: {
                                                OK: 'Yes'
                                            },

                                            callback: function(button) {
                                                if (button == 'OK') {

                                                    list_of_subordinates(rater);
                                                }

                                            }
                                        });
                                    } else {

                                        console.log(data);
                                    }
                                }
                            });
                        }
                    }
                });
            });
        });
    </script>
<?php
} else if ($request == 'show_business_unit') {
?>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="">Business Unit</label> <span class="text-red">*</span>
                <input type="hidden" name="bunit_id" value="<?= $bU->bunit_id ?>">
                <input type="text" name="bunit_name" class="form-control" style="text-transform: uppercase;" required value="<?= $bU->bunit_name ?>">
            </div>
            <div class="form-group">
                <label for="">Column Field</label> <span class="text-red">*</span>
                <input type="text" name="bunit_field" class="form-control" value="<?= $bU->bunit_field ?>" readonly>
            </div>
        </div>
        <div class=" col-md-6">
            <div class="form-group">
                <label for="">Acronym</label> <span class="text-red">*</span>
                <input type="text" name="bunit_acronym" class="form-control" style="text-transform: uppercase;" required value="<?= $bU->bunit_acronym ?>">
            </div>
            <div class="form-group">
                <label for="">Location</label> <span class="text-red">*</span>
                <select name="hrd_location" id="" class="form-control" required>
                    <?php
                    $locations = array('asc', 'cebo');
                    foreach ($locations as $location) {
                        if ($location == $bU->hrd_location) {
                            echo '<option value="' . $location . '" selected>' . $location . '</option>';
                        } else {
                            echo '<option value="' . $location . '">' . $location . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>
<?php
} else if ($request == 'add_business_unit') {

?>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="">Business Unit</label> <span class="text-red">*</span>
                <input type="text" name="bunit_name" class="form-control" style="text-transform: uppercase;" required>
            </div>
            <div class="form-group">
                <label for="">Column Field</label> <span class="text-red">*</span>
                <input type="text" name="bunit_field" class="form-control" required>
            </div>
        </div>
        <div class=" col-md-6">
            <div class="form-group">
                <label for="">Acronym</label> <span class="text-red">*</span>
                <input type="text" name="bunit_acronym" class="form-control" style="text-transform: uppercase;" required>
            </div>
            <div class="form-group">
                <label for="">Location</label> <span class="text-red">*</span>
                <select name="hrd_location" id="" class="form-control" required>
                    <?php
                    $locations = array('asc', 'cebo');
                    foreach ($locations as $location) {
                        echo '<option value="' . $location . '">' . $location . '</option>';
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>
<?php
} else if ($request == 'secure_clearance') {

?>
    <div class="panel panel-default">
        <div class="panel-heading">
            SECURE CLEARANCE
        </div>
        <div class="panel-body">
            <form id="secure-clearance" autocomplete="off">
                <input type="hidden" name="process" value="secure-clearance">
                <div class="form-group">
                    <label><span class="text-red">*</span> Search Promo</label>
                    <div class="input-group">
                        <input class="form-control" type="text" name="employee" onkeyup="nameSearch(this.value)">
                        <span class="input-group-addon"><i class="fa fa-user"></i></span>
                    </div>
                    <div class="search-results" style="display: none;"></div>
                </div>
                <div class="promo-details"></div>
                <div class="clearance-form" style="display: none;">
                    <div class="form-group">
                        <label for=""><span class="text-red">*</span> Reason for asking clearance</label>
                        <select class="form-control" name='reason' required onchange='getRL(this.value)'>
                            <option value=''> - Please Choose - </option>
                            <option value="V-Resigned"> VOLUNTARY RESIGNATION FROM EMPLOYMENT WITH THE COMPANY </option>
                            <option value="Ad-Resigned"> ADVISED TO RESIGNED FROM EMPLOYMENT WITH THE COMPANY </option>
                            <option value="Termination"> TERMINATION OF CONTRACT FROM THE COMPANY </option>
                            <option value="Deceased"> DECEASED </option>
                        </select>
                    </div>
                    <div class="reason-based"></div>
                    <button type="submit" id="secure-clearance-btn" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        $(function() {

            $("form#secure-clearance").submit(function(e) {

                e.preventDefault();
                let formData = new FormData(this);
                $("button#secure-clearance-btn").prop('disabled', true);
                $("button#secure-clearance-btn").text('Please Wait...');

                let reason = $("select[name = 'reason']").val();
                let is_date_required = '';
                if (reason == 'Deceased') {
                    is_date_required = $("input[name = 'date_of_death']").val();
                } else {
                    is_date_required = $("input[name = 'date_of_resignation']").val();
                }

                if (!is_date_required) {


                    $("button#secure-clearance-btn").prop('disabled', false);
                    $("button#secure-clearance-btn").text('Submit');
                    $.alert.open({
                        type: 'warning',
                        cancel: false,
                        content: "Please Fill-up Required Fields!",
                        buttons: {
                            OK: 'Ok'
                        },
                        callback: function(button) {
                            if (button == 'OK') {

                                if (reason == 'Deceased') {
                                    $("input[name = 'date_of_death']").css("border-color", "#dd4b39");
                                } else {
                                    $("input[name = 'date_of_resignation']").css("border-color", "#dd4b39");
                                }
                            }
                        }
                    });
                } else {

                    $.ajax({
                        type: "POST",
                        url: "<?= site_url('store_secure_clearance'); ?>",
                        data: formData,
                        success: function(data) {

                            let response = JSON.parse(data);
                            if (response.status == 'success') {

                                $.alert.open({
                                    type: 'warning',
                                    title: 'Info',
                                    icon: 'confirm',
                                    cancel: false,
                                    content: "Clearance Processing is successfull! <br> Do you want to print clearance now or later?",
                                    buttons: {
                                        OK: 'Yes',
                                        NO: 'Not now'
                                    },
                                    callback: function(button) {
                                        if (button == 'OK') {

                                            if (reason == 'Deceased') {
                                                $.alert.open({
                                                    type: 'warning',
                                                    cancel: false,
                                                    content: 'Generating Clearance...',
                                                    buttons: {
                                                        OK: 'Yes',
                                                    },
                                                    callback: function(button) {
                                                        if (button == 'OK') {

                                                            clearanceProcess('secure_clearance');
                                                            window.open(response.base_url + '/hrms/report/deceased_clearance.php?empid=' + response.emp_id);
                                                        }
                                                    }
                                                });
                                            } else {
                                                $.alert.open({
                                                    type: 'warning',
                                                    cancel: false,
                                                    content: 'Generating Clearance...',
                                                    buttons: {
                                                        OK: 'Yes',
                                                    },
                                                    callback: function(button) {
                                                        if (button == 'OK') {

                                                            clearanceProcess('secure_clearance');
                                                            window.open(response.base_url + '/hrms/report/promo_clearance.php?empid=' + response.emp_id + '&scprdetailsid=' + response.scdetails_id);
                                                        }
                                                    }
                                                });
                                            }

                                        } else {
                                            clearanceProcess('secure_clearance');
                                        }
                                    }
                                });
                            } else {
                                console.log(data);
                            }
                        },
                        async: false,
                        cache: false,
                        contentType: false,
                        processData: false
                    });
                }
            });
        });
    </script>
<?php
} else if ($request == 'promo_details_clearance') {

    $scpr_id = $this->resignation_model->fetch_scpr_id($emp_id);

?>
    <input type="hidden" name="scpr_id" value="<?= $scpr_id ?>">
    <div class="form-group">
        <label for=""><span class="text-red">*</span> Promo Type</label>
        <input type="text" name="promo_type" class="form-control" value="<?= $emp_details->promo_type ?>" readonly>
    </div>
    <div class="form-group">
        <label for=""><span class="text-red">*</span> Store</label>
        <select name="store" class="form-control" required>
            <option value=""></option>
            <?php
            $bUs = $this->dashboard_model->businessUnit_list();
            foreach ($bUs as $bu) {

                $hasBU = $this->dashboard_model->promo_has_bu($emp_id, $bu->bunit_field);
                if ($hasBU > 0) {

                    if ($process == 'secure-clearance') {

                        $exist = $this->resignation_model->check_secure_clearance_details($emp_id, $bu->bunit_name);
                        if ($exist) {
                            echo '<option value="' . $bu->bunit_name . '" disabled>' . $bu->bunit_name . ' - DONE </option>';
                        } else {
                            echo '<option value="' . $bu->bunit_name . '">' . $bu->bunit_name . '</option>';
                        }
                    } else if ($process == 'upload-clearance') {

                        $exist = $this->resignation_model->check_upload_clearance_details($emp_id, $bu->bunit_name, 'Completed');
                        if ($exist) {
                            echo '<option value="' . $bu->bunit_name . '" disabled>' . $bu->bunit_name . ' - Completed </option>';
                        } else {

                            $exist = $this->resignation_model->check_upload_clearance_details($emp_id, $bu->bunit_name, '');
                            if ($exist) {
                                echo '<option value="' . $bu->bunit_name . '">' . $bu->bunit_name . '</option>';
                            } else {
                                echo '<option value="' . $bu->bunit_name . '" disabled>' . $bu->bunit_name . ' - Not Secured Yet </option>';
                            }
                        }
                    } else {

                        $exist = $this->resignation_model->check_upload_clearance_details($emp_id, $bu->bunit_name, 'Pending');
                        if ($exist) {
                            echo '<option value="' . $bu->bunit_name . '">' . $bu->bunit_name . '</option>';
                        } else {
                            echo '<option value="' . $bu->bunit_name . '" disabled>' . $bu->bunit_name . ' - Not Secured Yet/ Already Cleared </option>';
                        }
                    }
                }
            }
            ?>
        </select>
    </div>
    <?php
} else if ($request == 'get_rb_form') {

    if ($reason == 'Deceased') {
    ?>
        <div class="form-group">
            <label for=""><span class="text-red">*</span> Date of Death</label>
            <input type="text" name="date_of_death" class="form-control datepicker" placeholder="mm/dd/yyyy" data-inputmask="'alias': 'mm/dd/yyyy'" data-mask="" required onchange="inputField(this.name)">
        </div>
        <div class="form-group">
            <label for=""><span class="text-red">*</span> Name of Claimant</label>
            <input type="text" name="claimant" class="form-control" required>
        </div>
        <div class="form-group">
            <label for=""><span class="text-red">*</span> Relation to the deceased employee</label>
            <select name="relation" class="form-control" required>
                <option value=""> --Choose Relationship-- </option>
                <?php
                $relationships = array('Father', 'Mother', 'Spouse', 'Son', 'Daughter', 'Sister/Brother');
                foreach ($relationships as $relationship) {

                    echo '<option value="' . $relationship . '">' . $relationship . '</option>';
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for=""><span class="text-red">*</span> Cause of Death</label>
            <input type="text" name="cause_of_death" class="form-control" required>
        </div>
        <div class="form-group">
            <label for=""><span class="text-red">*</span> Required Document (Scanned Death Certificate)</label>
            <input type="file" name="resignation_letter" class="form-control" required accept="image/*">
        </div>
        <div class="form-group">
            <label for=""><span class="text-red">*</span> Required Document (Scanned Authorization Letter)</label>
            <input type="file" name="authorization_letter" class="form-control" required accept="image/*">
        </div>
    <?php
    } else if ($reason == 'Termination' || $reason == 'Remove-BU') {
    ?>
        <div class="form-group">
            <label for=""><span class="text-red">*</span> EOC Date</label>
            <input type="text" name="date_of_resignation" class="form-control datepicker" placeholder="mm/dd/yyyy" data-inputmask="'alias': 'mm/dd/yyyy'" data-mask="" required onchange="inputField(this.name)">
        </div>
    <?php
    } else {
    ?>
        <div class="form-group">
            <label for=""><span class="text-red">*</span> Date of Resignation</label>
            <input type="text" name="date_of_resignation" class="form-control datepicker" placeholder="mm/dd/yyyy" data-inputmask="'alias': 'mm/dd/yyyy'" data-mask="" required onchange="inputField(this.name)">
        </div>
        <div class="form-group">
            <label for=""><span class="text-red">*</span> Required Document (Scanned Resignation Letter)</label>
            <input type="file" name="resignation_letter" class="form-control" required accept="image/*">
        </div>
    <?php
    }
    ?>
    <script type="text/javascript">
        $('.datepicker').datepicker({
            inline: true,
            changeYear: true,
            changeMonth: true
        });

        $("[data-mask]").inputmask();
    </script>
<?php
} else if ($request == 'upload_clearance') {
?>
    <div class="panel panel-default">
        <div class="panel-heading">
            UPLOAD CLEARANCE & CHANGE STATUS
        </div>
        <div class="panel-body">
            <form id="upload-clearance" autocomplete="off">
                <input type="hidden" name="process" value="upload-clearance">
                <div class="form-group">
                    <label><span class="text-red">*</span> Search Promo</label>
                    <div class="input-group">
                        <input class="form-control" type="text" name="employee" onkeyup="nameSearch(this.value)">
                        <span class="input-group-addon"><i class="fa fa-user"></i></span>
                    </div>
                    <div class="search-results" style="display: none;"></div>
                </div>
                <div class="promo-details"></div>
                <div class="clearance-form" style="display: none;">
                    <div class="form-group">
                        <button type="button" class="btn btn-primary btn-xs" onclick="browseEpas()">Browse EPAS</button>
                    </div>
                    <div class="show-epas" style="display:none;">
                        <div class="form-group">
                            <label for=""><span class="text-red">*</span> EPAS</label>
                            <input type="text" name="epas" class="form-control input-form">
                        </div>
                        <div class="form-group">
                            <label for=""><span class="text-red">*</span> Succeeding Status</label>
                            <input type="text" name="status" class="form-control input-form">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for=""><span class="text-red">*</span> Remarks</label>
                        <textarea name="remarks" class="form-control disabled-form" cols="30" rows="2" disabled required></textarea>
                    </div>
                    <div class="form-group">
                        <label for=""><span class="text-red">*</span> Signed Clearance (Scanned)</label>
                        <input type="file" name="clearance" class="form-control disabled-form" disabled required accept="image/*">
                    </div>
                    <div class="reason-based"></div>
                    <button type="submit" id="upload-clearance-btn" class="btn btn-primary disabled-form" disabled>Submit</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        $(function() {

            $("form#upload-clearance").submit(function(e) {

                e.preventDefault();
                let formData = new FormData(this);

                $.alert.open({
                    type: 'warning',
                    title: 'Info',
                    icon: 'confirm',
                    cancel: false,
                    content: "Are you sure to submit signed clearace?",
                    buttons: {
                        OK: 'Yes',
                        NO: 'Not now'
                    },
                    callback: function(button) {
                        if (button == 'OK') {

                            $.ajax({
                                type: "POST",
                                url: "<?= site_url('store_upload_clearance'); ?>",
                                data: formData,
                                success: function(data) {

                                    let response = JSON.parse(data);
                                    if (response.status == 'success') {

                                        $.alert.open({
                                            type: 'warning',
                                            cancel: false,
                                            content: 'Employee Successfully Cleared',
                                            buttons: {
                                                OK: 'Yes',
                                            },
                                            callback: function(button) {
                                                if (button == 'OK') {

                                                    clearanceProcess('upload_clearance');
                                                }
                                            }
                                        });
                                    } else {
                                        console.log(data);
                                    }
                                },
                                async: false,
                                cache: false,
                                contentType: false,
                                processData: false
                            });
                        }
                    }
                });
            });
        });
    </script>
<?php
} else if ($request == 'reprint_clearance') {

?>
    <div class="panel panel-default">
        <div class="panel-heading">
            UPLOAD CLEARANCE & CHANGE STATUS
        </div>
        <div class="panel-body">
            <form id="reprint-clearance" autocomplete="off">
                <input type="hidden" name="process" value="reprint-clearance">
                <div class="form-group">
                    <label><span class="text-red">*</span> Search Promo</label>
                    <div class="input-group">
                        <input class="form-control" type="text" name="employee" onkeyup="nameSearch(this.value)">
                        <span class="input-group-addon"><i class="fa fa-user"></i></span>
                    </div>
                    <div class="search-results" style="display: none;"></div>
                </div>
                <div class="promo-details"></div>
                <div class="clearance-form" style="display: none;">
                    <div class="form-group">
                        <label for=""><span class="text-red">*</span> Reason for Clearance Reprint</label>
                        <textarea name="reprint_reason" class="form-control" rows="3"></textarea>
                    </div>
                    <button type="button" id="req-reprint" class="btn btn-primary" onclick="req_reprint_clearance()">Submit</button>
                    <button type="submit" id="view-clearance" class="btn btn-primary" disabled>View Clearance</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        $(function() {
            $("form#reprint-clearance").submit(function(e) {

                e.preventDefault();
                let [emp_id, name] = $("input[name = 'employee']").val().split('*');
                let store = $("select[name = 'store']").val();

                $.ajax({
                    type: "POST",
                    url: "<?= site_url('reprint_details') ?>",
                    data: {
                        emp_id: emp_id.trim(),
                        store: store
                    },
                    success: function(data) {

                        let response = JSON.parse(data);
                        if (response.status == 'success') {

                            print_clearance(response.reason, emp_id.trim(), response.scdetails_id, response.base_url)
                        } else {
                            console.log(data);
                        }
                    }
                });
            });
        });
    </script>
<?php
} else if ($request == 'list_secured_clerance') {

?>
    <div class="panel panel-default">
        <div class="panel-heading">
            EMPLOYEE LIST
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <input type="hidden" name="process" value="list-secured-clerance">
                <table id="dt-secured-clearance" class="table table-bordered table-hover" width="100%">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Secure</th>
                            <th>Effective</th>
                            <th>Status</th>
                            <th>Type</th>
                            <th>Reason</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <script>
        $(function() {

            let dt_secured_clearance = $("table#dt-secured-clearance").DataTable({
                "destroy": true,
                "ajax": {
                    url: "<?php echo site_url('fetch_secured_clearance'); ?>",
                    type: "POST"
                },
                "order": [],
                "columnDefs": [{
                    "targets": [6],
                    "orderable": false,
                    "className": "text-center",
                }],
            });

            $('table#dt-secured-clearance').on('click', 'button.action', function() {

                let [action, id] = this.id.split("_");

                if (!$(this).parents('tr').hasClass('selected')) {
                    dt_secured_clearance.$('tr.selected').removeClass('selected');
                    $(this).parents('tr').addClass('selected');
                }

                $("div#view-clerance").modal({
                    backdrop: 'static',
                    keyboard: false,
                    show: true
                });

                $("div.view-clerance").html("");
                $.ajax({
                    type: "GET",
                    url: "<?= site_url('show_secured_clerance_details') ?>",
                    data: {
                        id
                    },
                    success: function(data) {

                        $("div.view-clerance").html(data);
                    }
                });

            });
        });
    </script>
<?php
} else if ($request == 'show_secured_clerance_details') {
?>
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>Store</th>
                <th>EPAS</th>
                <th>Date Secure</th>
                <th>Date Effectivity</th>
                <th>Date Cleared</th>
                <th>Clearance Status</th>
                <th>
                    <center>Doc.</center>
                </th>
                <th>Added By</th>
                <th>
                    <center>Action</center>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($details as $row) {

                $sc = $this->resignation_model->secure_clearance_promo(['scpr_id' => $row->scpr_id]);
                $date_cleared = '';
                if ($row->date_cleared != '0000-00-00' && $row->date_cleared != '') {
                    $date_cleared = date('m/d/Y', strtotime($row->date_cleared));
                }

                $generated_clerance = '';
                if ($row->generated_clearance == '') {

                    $generated_clerance = '
                        <a href="javascript:void(0)" onclick=print_clearance("' . trim($sc->reason) . '","' . $row->emp_id . '","' . $row->scdetails_id . '","' . $base_url . '")><i class="fa fa-print"></i></a>
                    ';
                }

                $letter = '';
                if ($row->resignation_letter != '') {

                    $letter = '<button class="btn btn-sm btn-primary btn-block" onclick=view_letter("' . $base_url . '/hrms/promo/' . $row->resignation_letter . '")>View</button>';
                }

                $deceased = $this->resignation_model->fetch_authorization_letter($row->emp_id);
                if ($deceased) {

                    $letter = '<button class="btn btn-sm btn-primary btn-block" onclick=view_letter("' . $base_url . '/hrms/promo/' . $deceased . '")>View</button>';
                }

                echo '
                    <tr>
                        <td>' . $row->store . '</td>
                        <td></td>
                        <td>' . date('m/d/Y', strtotime($row->date_secure)) . '</td>
                        <td>' . date('m/d/Y', strtotime($row->date_effectivity)) . '</td>
                        <td>' . $date_cleared . '</td>
                        <td>' . $row->clearance_status . '</td>
                        <td>' . $letter . '</td>
                        <td>' . ucwords(strtolower($this->employee_model->employee_info($row->added_by)->name)) . '</td>
                        <td align="center">' . $generated_clerance . '</td>
                    </tr>
                ';
            }
            ?>
        </tbody>
    </table>
<?php
} else if ($request == 'add_department_form') {
?>
    <div class="form-group">
        <label for="bunit-name">Business Unit</label>
        <select name="bunit_id" id="bunit-name" class="form-control">
            <option value=""> -Select- </option>
            <?php
            foreach ($business_units as $bu) {
                echo "<option value='{$bu->bunit_id}'>{$bu->bunit_name}</option>";
            }
            ?>
        </select>
        <span class="bunit_id-error error-message text-danger"></span>
    </div>
    <div class="form-group">
        <label for="dept_name">Department</label>
        <input type="text" name="dept_name" id="dept_name" class="form-control" style="text-transform:uppercase">
        <span class="dept_name-error error-message text-danger"></span>
    </div>
<?php
}
