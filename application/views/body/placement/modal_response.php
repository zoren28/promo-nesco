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
                            <input type='hidden' id='appName_" . $fetch['app_id'] . "' value='" . utf8_decode(ucwords(strtolower($fullname))) . "'>
                            <tr>
                                <td>" . utf8_decode(ucwords(strtolower($fullname))) . "</td>
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

            echo "
								<tr>
									<td>" . $num . "</td>
									<td>" . $row->emp_id . "</td>
									<td>" . ucwords(strtolower(utf8_decode($row->name))) . "</td>
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

            echo "
								<tr>
									<td>" . $num . "</td>
									<td>" . $row->emp_id . "</td>
									<td>" . ucwords(strtolower(utf8_decode($row->name))) . "</td>
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
                        if ($this->employee_model->return_num_rows($epas_sql) > 0) {

                            $epas = $this->employee_model->return_row_array($epas_sql);

                            if ($epasNum == 1) : $displayEpas = "<button class='btn btn-primary btn-sm btn-flat btn-block' onclick='viewAppraisalDetails(\"$epas[details_id]\")'>" . $epas['numrate'] . " - " . $epas['descrate'] . " &nbsp;[ View Epas ]</button>";
                            else :
                                if (!empty($promo_details[$bu->bunit_epascode]) && strpos($promo_details[$bu->bunit_epascode], '../document/') !== false) : $displayEpas = "<button class='btn btn-primary btn-sm btn-flat btn-block' onclick='viewFile(\"promoFile\",\"$table2\",\"$bu->bunit_intro\",\"$empId\",\"$recordNo\")'>View Intro</button>";
                                else : $displayEpas = "";
                                endif;
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
                                INNER JOIN `$table2`
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

        array_push($emp_products, $value);
    }

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

                            foreach ($result as $res) { ?>

                                <option value="<?= $res->agency_code ?>" <?php
                                                                            if ($row['agency_code'] == $res->agency_code) : echo "selected=''";
                                                                            endif; ?>>
                                    <?=
                                    $res->agency_name
                                    ?>
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

                                    $company = $this->employee_model->getcompanyCodeBycompanyName($res->company_name);
                                    if (!empty($company)) {
                            ?>

                                        <option value="<?= $company->pc_code ?>" <?php if ($row['promo_company'] == $res->company_name) echo "selected = ''"; ?>><?= $res->company_name ?></option>
                                    <?php
                                    }
                                    ?>
                                <?php
                                }
                            } else {

                                $result = $this->employee_model->company_list();
                                foreach ($result as $res) {
                                ?>

                                    <option value="<?= $res->pc_name ?>" <?php
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
                            <option value="STATION" <?php if ($row['promo_type'] == "STATION") echo "selected=''"; ?>>STATION</option>
                            <option value="ROVING" <?php if ($row['promo_type'] == "ROVING") echo "selected=''"; ?>>ROVING</option>
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
                        <select name="product" class="form-control select2" multiple="multiple">
                            <option value=""> --Select-- </option>
                            <?php
                            $products = $this->employee_model->locate_promo_products($row['promo_company']);
                            foreach ($products as $product) {
                            ?>
                                <option value="<?= $product->product ?>" <?php if (in_array($product->product, $emp_products)) echo "selected=''"; ?>><?= $product->product ?></option>
                            <?php
                            }
                            ?> ?>
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
                            <input type="text" value="<?php echo $startdate; ?>" name="startdate" class="form-control datepicker" onchange="startdate()" required>
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
                        <label>Current Status <?= in_array($this->employee_model->loginId, $admin_users); ?></label>
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
        $("span.select2").css("width", "100%");
    </script>
<?php
}
?>