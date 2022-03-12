<?php

if ($request == 'basic_info') {

    $row = $this->employee_model->get_applicant_info($empId);
    $lastname    = $row->lastname;
    $firstname   = $row->firstname;
    $middlename  = $row->middlename;
    $suffix      = $row->suffix;
    $citizenship = $row->citizenship;
    $gender      = $row->gender;
    $cv          = $row->civilstatus;
    $religion    = $row->religion;
    $weight      = $row->weight;
    $height      = $row->height;
    $bloodtype   = $row->bloodtype;

    $datebirth = "";
    if ($row->birthdate != "0000-00-00" && $row->birthdate != "NULL" && $row->birthdate != "") {

        $datebirth    = date("m/d/Y", strtotime($row->birthdate));
    }

    $weight_fetch = $this->employee_model->select_all('weight');
    $height_fetch = $this->employee_model->select_all('height');
    $religion_fetch = $this->employee_model->select_all('religion');
    $bloodtype_array = array('A', 'A+', 'A-', 'B', 'B+', 'B-', 'O', 'O+', 'O-', 'AB', 'AB+', 'AB-');
    $cv_array         = array('Single', 'Married', 'Widowed', 'Separated', 'Anulled', 'Divorced');

?>
    <div class="modf">Basic Information
        <input name="edit" id="edit-basicinfo" value="edit" class="btn btn-primary btn-sm" onclick="edit_basicinfo()" type="button">
        <input class="btn btn-primary btn-sm" id="update_basicinfo" value="update" onclick="update(this.id)" style="display:none" type="button">
    </div>
    <table class="table table-bordered">
        <tbody>
            <tr>
                <td width="20%" align="right">Employee No</td>
                <td colspan="4">
                    <input name="" value="<?php echo $empId; ?>" readonly="" class="form-control" disabled="" type="text">
                </td>
            </tr>
            <tr>
                <td align="right">Firstname</td>
                <td><input name="fname" value="<?php echo $firstname; ?>" class="form-control inputForm" type="text"></td>
                <td align="right">Middlename</td>
                <td><input name="mname" value="<?php echo $middlename; ?>" class="form-control inputForm" type="text"></td>
            </tr>
            <tr>
                <td align="right">Lastname</td>
                <td><input name="lname" value="<?php echo $lastname; ?>" class="form-control inputForm" type="text"></td>
                <td align="right">Suffix</td>
                <td><input name="suffix" value="<?php echo $suffix; ?>" class="form-control inputForm" type="text"></td>
            </tr>
            <tr>
                <td align="right">Date of Birth</td>
                <td><input name="datebirth" value="<?php echo $datebirth; ?>" class="form-control inputForm datepicker" placeholder="mm/dd/yyyy" type="text" data-inputmask="'alias': 'mm/dd/yyyy'" data-mask></td>
                <td align="right">Citizenship</td>
                <td><input name="citizenship" value="<?php echo $citizenship ?>" class="form-control inputForm" type="text"></td>
            </tr>
            <tr>
                <td align="right">Gender</td>
                <td>
                    <select name="gender" class="form-control inputForm">
                        <option value=""> --Select-- </option>
                        <option value="Male" <?php if ($gender == "Male") : echo "selected=''";
                                                endif; ?>>Male</option>
                        <option value="Female" <?php if ($gender == "Female") : echo "selected=''";
                                                endif; ?>>Female</option>
                    </select>
                </td>
                <td align="right">Civil Status</td>
                <td>
                    <select name="civilstatus" class="form-control inputForm">
                        <option value=""></option>
                        <?php

                        for ($i = 0; $i < count($cv_array); $i++) {

                            if ($cv == $cv_array[$i]) {
                                echo "<option value='" . $cv_array[$i] . "' selected='selected' >" . $cv_array[$i] . "</option>";
                            } else {
                                echo "<option value='" . $cv_array[$i] . "'>" . $cv_array[$i] . "</option>";
                            }
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="right">Religion</td>
                <td>
                    <input list="religions" name="religion" class="form-control inputForm" autocomplete="off" value="<?php echo $religion; ?>">
                    <datalist id="religions">
                        <?php
                        foreach ($religion_fetch as $rrow) {

                            if ($rrow->religion == $religion) {
                                echo "<option value='" . $rrow->religion . "''>" . $rrow->religion . "</option>";
                            } else {
                                echo "<option value='" . $rrow->religion . "''>" . $rrow->religion . "</option>";
                            }
                        } ?>
                    </datalist>
                </td>
                <td align="right">Bloodtype</td>
                <td>
                    <select class="form-control inputForm" name="bloodtype">
                        <option value=""></option>
                        <?php

                        for ($i = 0; $i < count($bloodtype_array); $i++) {

                            if ($bloodtype == $bloodtype_array[$i]) {
                                echo "<option value='" . $bloodtype_array[$i] . "' selected='selected' >" . $bloodtype_array[$i] . "</option>";
                            } else {
                                echo "<option value='" . $bloodtype_array[$i] . "'>" . $bloodtype_array[$i] . "</option>";
                            }
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="right">Weight <i>in kilogram</i></td>
                <td>
                    <input list="weights" name="weight" class="form-control inputForm" autocomplete="off" value="<?php echo $weight; ?>">
                    <datalist id="weights">
                        <?php
                        foreach ($weight_fetch as $wrow) {

                            $we = $wrow->kilogram . " / " . $wrow->pounds;
                            echo "<option value=\"$we\">" . $we . "</option>";
                        } ?>
                    </datalist>
                </td>
                <td align="right">Height <i>in centimeter</i></td>
                <td>
                    <input list="heights" name="height" class="form-control inputForm" autocomplete="off" value="<?php echo $height; ?>">
                    <datalist id="heights">
                        <?php
                        foreach ($height_fetch as $hrow) {

                            $he = $hrow->cm . " / " . $hrow->feet;
                            echo "<option value=\"$he\">" . $he . "</option>";
                        } ?>
                    </datalist>
                </td>
            </tr>
        </tbody>
    </table>
    <script type="text/javascript">
        $('.datepicker').datepicker({
            inline: true,
            changeYear: true,
            changeMonth: true
        });

        $("[data-mask]").inputmask();
        $(".inputForm").prop("disabled", true);
    </script>
<?php
} else if ($request == "family") {

    $row = $this->employee_model->get_applicant_info($empId);

    $mother     = $row->mother;
    $father     = $row->father;
    $guardian     = $row->guardian;
    $noofsibling = $row->noofSiblings;
    $siblingordr = $row->siblingOrder;
    $spouse     = $row->spouse;
    $gender     = $row->gender;

    $mo_work = $mo_text = "";
    $fa_work = $fa_text = "";
    $sp_work = $sp_text = "";

    if ($row->mother_work != "") {
        if ($row->mother_work == "deceased") {
            $mo_work = "checked";
            $mo_text = "text-red";
        }
    }
    if ($row->father_work != "") {
        if ($row->father_work == "deceased") {
            $fa_work = "checked";
            $fa_text = "text-red";
        }
    }
    if ($row->spouse_work != "") {
        if ($row->spouse_work == "deceased") {
            $sp_work = "checked";
            $sp_text = "text-red";
        }
    }

    if ($row->mother_bdate == '0000-00-00') {
        $mother_bdate = "";
    } else {
        $mother_bdate = date("m/d/Y", strtotime($row->mother_bdate));
    }

    if ($row->father_bdate == '0000-00-00') {
        $father_bdate = "";
    } else {
        $father_bdate = date("m/d/Y", strtotime($row->father_bdate));
    }

    if ($row->spouse_bdate == '0000-00-00') {
        $spouse_bdate = "";
    } else {
        $spouse_bdate = date("m/d/Y", strtotime($row->spouse_bdate));
    }

?>
    <style type="text/css">
        .input-group .form-control {
            position: relative;
            z-index: 0;
            float: left;
            width: 100%;
            margin-bottom: 0;
        }

        .search-results {

            box-shadow: 5px 5px 5px #ccc;
            margin-top: 2px;
            margin-left: 0px;
            background-color: #F1F1F1;
            width: 84%;
            border-radius: 3px 3px 3px 3px;
            font-size: 14px;
            padding: 8px 10px;
            display: block;
            position: absolute;
            z-index: 9999;
            max-height: 300px;
            overflow-y: scroll;
            overflow: auto;
        }
    </style>
    <div class="modf">Family Background
        <input name="edit" id="edit-family" value="edit" class="btn btn-primary btn-sm" onclick="edit_family()" type="button">
        <input class="btn btn-primary btn-sm" id="update_family" value="update" onclick="update(this.id)" style="display:none" type="button">
    </div>
    <table class="table table-bordered" width="600">
        <tbody>
            <tr>
                <td align="right">Mother</td>
                <td><input name="mother" value="<?php echo $mother; ?>" class="form-control inputForm family_mo_work" type="text" onkeyup="inputField(this.name)"></td>
                <td>Birthdate</td>
                <td><input type="text" name="mother_bdate" class="form-control datepicker inputForm" placeholder="mm/dd/yyyy" value="<?php echo $mother_bdate; ?>" data-inputmask="'alias': 'mm/dd/yyyy'" data-mask></td>
                <td>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <input type="checkbox" name="mo_work" <?php echo $mo_work; ?> class="inputForm" onclick="deceased(this.name)" value="deceased">
                        </span>
                        <input type="text" id="text_mo_work" class="form-control <?php echo $mo_text; ?> inputForm" value="Deceased">
                    </div>
                </td>
            </tr>
            <tr>
                <td align="right">Father</td>
                <td><input name="father" value="<?php echo $father; ?>" class="form-control inputForm family_fa_work" type="text" onkeyup="inputField(this.name)"></td>
                <td>Birthdate</td>
                <td><input type="text" name="father_bdate" class="form-control datepicker inputForm" placeholder="mm/dd/yyyy" value="<?php echo $father_bdate; ?>" data-inputmask="'alias': 'mm/dd/yyyy'" data-mask></td>
                <td>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <input type="checkbox" name="fa_work" <?php echo $fa_work; ?> class="inputForm" onclick="deceased(this.name)" value="deceased">
                        </span>
                        <input type="text" id="text_fa_work" class="form-control <?php echo $fa_text; ?> inputForm" value="Deceased">
                    </div>
                </td>
            </tr>
            <tr>
                <td align="right">Spouse</td>
                <td><input name="spouse" value="<?php echo $spouse; ?>" class="form-control inputForm family_sp_work" type="text" onkeyup="inputField(this.name)"></td>
                <td>Birthdate</td>
                <td><input type="text" name="spouse_bdate" class="form-control datepicker inputForm" placeholder="mm/dd/yyyy" value="<?php echo $spouse_bdate; ?>" data-inputmask="'alias': 'mm/dd/yyyy'" data-mask></td>
                <td>
                    <div class="input-group">
                        <span class="input-group-addon">
                            <input type="checkbox" name="sp_work" <?php echo $sp_work; ?> class="inputForm" onclick="deceased(this.name)" value="deceased">
                        </span>
                        <input type="text" id="text_sp_work" class="form-control <?php echo $sp_text; ?> inputForm" value="Deceased">
                    </div>
                </td>
            </tr>
            <td align="right">Guardian</td>
            <td colspan="5"><input name="guardian" value="<?php echo $guardian; ?>" class="form-control inputForm" type="text"></td>
            <tr>
            </tr>
        </tbody>
    </table>
    <table class="table table-bordered" width="100%">
        <thead bgcolor="#EBEAEA">
            <tr>
                <th>Complete the details for Single Parent/Married only</th>
            </tr>
        </thead>
        <tbody>

            <?php

            $spouse_info  = $this->employee_model->spouse_info($empId);
            $spouse_num = $spouse_info->num_rows();
            $row = $spouse_info->row_array();

            $asawa_info  = $this->employee_model->asawa_info($empId);
            $asawa_num = $asawa_info->num_rows();
            $row2 = $asawa_info->row_array();

            if ($gender == "Male") {

                $parent = "mother";
            } else {

                $parent = "father";
            }

            if ($spouse_num != 0) {

                if ($asawa_num != 0) {

                    $spouse_name = $this->employee_model->employee_name($row2['spouse_empId'])['name'];
                } else {

                    $spouse_name = $row['spouse'];
                } ?>

                <tr>
                    <td>
                        <div class="form-group">
                            <label><?php echo ucwords($parent); ?> of the Child/Children <i class="text-red">(Please follow this format - Lastname, Firstname Middlename)</i></label>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-group" style="position: relative; z-index: 0;">
                                        <input type="text" name="mother_name" autocomplete="off" class="form-control update_children" onkeyup="mother_name(this.value)" placeholder="Lastname, Firstname Middlename" value="<?php echo $spouse_name; ?>" disabled="">
                                        <div class="input-group-addon">
                                            <i class="fa fa-user"></i>
                                        </div>
                                    </div>
                                    <div class="search-results" style="display: none;"></div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <form id="data_birthCert_details" action="" method="post">
                            <input type="hidden" name="spouseId" value="<?php echo $row['spouseId']; ?>">
                            <input type="hidden" name="empId" value="<?php echo $empId; ?>">
                            <input type="hidden" name="spouse_empId" value="<?php echo $row['spouse_empId']; ?>">
                            <input type="hidden" name="spouse_name" value="<?php echo $spouse_name; ?>">

                            <div class="row">
                                <div class="col-md-12" style="overflow-x: auto; width: 787px;">
                                    <table class="table" width="100%">
                                        <thead>
                                            <tr bgcolor="#EBEAEA">
                                                <th colspan="9">Child/Children</th>
                                            </tr>
                                            <tr>
                                                <th>Firstname</th>
                                                <th>Middlename</th>
                                                <th>Lastname</th>
                                                <th>Birthday</th>
                                                <th>Age</th>
                                                <th>Gender</th>
                                                <th>Deceased</th>
                                                <th>BirthCert.</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody id="myTable2">
                                            <?php

                                            $no_child = $this->employee_model->num_of_children($row['spouseId'])['no_child'];
                                            if ($no_child == 0) {

                                                echo "<tr>
                                                                    <td colspan='9' align='center'> No Child/Children </td>
                                                                </tr>";
                                            } else {

                                                $children = $this->employee_model->children_info($row['spouseId'], $empId);
                                                foreach ($children as $child) {

                                                    $dob = strtotime($child['bday']);
                                                    $now = date('Y-m-d');
                                                    $tdate = strtotime($now);
                                                    $age = 0;
                                                    while ($tdate >= $dob = strtotime('+1 year', $dob)) {

                                                        $age++;
                                                    }

                                                    $childId = $child['childId'];
                                                    $birthCert = $child['birth_cert'];

                                                    $disabled = "disabled=''";
                                                    if (trim($birthCert) != "") {

                                                        $disabled = '';
                                                    }

                                            ?>

                                                    <input type="hidden" name="childId[]" value="<?php echo $childId; ?>">
                                                    <input type="hidden" name="deceased[]" class="deceasedChild_<?php echo $childId; ?>" value="<?= $child['deceased'] ?>">

                                                    <tr id="tr_<?php echo $childId; ?>">
                                                        <td><input type="text" name="fname[]" class="fname_<?php echo $childId; ?> update_children" value="<?php echo $child['firstname']; ?>" disabled="" onkeyup="fname(<?php echo $childId; ?>)"></td>
                                                        <td><input type="text" name="mname[]" class="mname_<?php echo $childId; ?> update_children" value="<?php echo $child['middlename']; ?>" disabled=""></td>
                                                        <td><input type="text" name="lname[]" class="lname_<?php echo $childId; ?> update_children" value="<?php echo $child['lastname']; ?>" disabled="" onkeyup="lname(<?php echo $childId; ?>)"></td>
                                                        <td><input type="text" name="bday[]" class="datepicker bday_<?php echo $childId; ?> update_children" onchange="get_age(this.value, '<?php echo $childId; ?>')" value="<?php echo date("m/d/Y", strtotime($child['bday'])); ?>" disabled=""></td>
                                                        <td><input type="text" name="age[]" class="updAge_<?php echo $childId; ?>" value="<?php echo $age; ?>" disabled=""></td>
                                                        <td>
                                                            <select name="gender[]" disabled="" class="gender_<?php echo $childId; ?> update_children" onchange="gender(<?php echo $childId; ?>)">
                                                                <option value=""> -- Select -- </option>
                                                                <option value="Male" <?php if ($child['gender'] == 'Male') : echo 'selected';
                                                                                        endif; ?>>Male</option>
                                                                <option value="Female" <?php if ($child['gender'] == 'Female') : echo 'selected';
                                                                                        endif; ?>>Female</option>
                                                            </select>
                                                        </td>
                                                        <td><input type="checkbox" id="deceasedChild_<?php echo $childId; ?>" class="update_children" value="deceased" <?php if ($child['deceased'] == "deceased") : echo "checked=''";
                                                                                                                                                                        endif; ?> disabled="" onchange="deceasedChild(<?php echo $childId; ?>)"></td>
                                                        <td>
                                                            <button id="viewBC_<?php echo $childId; ?>" class="btn btn-primary btn-block option_viewBC" <?php echo $disabled; ?>><i class="fa fa-folder-open"></i>&nbsp; View BirthCert.</button>
                                                            <button id="childId_<?php echo $childId; ?>" class="btn btn-primary btn-block option2" style="display: none;"><i class="fa fa-upload"></i>&nbsp; Upload BirthCert.</button>
                                                        </td>
                                                        <td><a href="javascript:delete_child(<?php echo $childId; ?>)" class="option2" style="display: none;"><i class="fa fa-trash text-red"></i></a></td>
                                                    </tr> <?php
                                                        }
                                                    }
                                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </form>
                    </td>
                </tr>
                <tr class="option3" style="display: none;">
                    <td>
                        <div class="row">
                            <div class="col-md-7">
                                <div class="form-group">
                                    <label>Input Number of Children <i class="text-red">(Note: Maximum number to be inputed is 10)</i></label>
                                    <input type="number" name="number_child" class="form-control" onkeypress="return isNumberKey(event)">
                                    <input type="hidden" name="counter" value="0">
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <br>
                                    <button class="btn btn-primary" onclick="add_children_info()">Add</button>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>

                <tr class="children_info" style="display: none;">
                    <td>
                        <form id="data_childrenInfo">
                            <input type="hidden" name="spouseId" value="<?php echo $row['spouseId']; ?>">
                            <div class="row">
                                <div class="col-md-12" style="overflow-x: auto; width: 787px;">
                                    <table class="table" width="100%">
                                        <thead>
                                            <tr bgcolor="#EBEAEA">
                                                <th colspan="9">Child/Children</th>
                                            </tr>
                                            <tr>
                                                <th>Firstname</th>
                                                <th>Middlename</th>
                                                <th>Lastname</th>
                                                <th>Birthday</th>
                                                <th>Age</th>
                                                <th>Gender</th>
                                                <th>Deceased</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody id="my_table2">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </form>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span class="option1">
                            <button class="btn btn-warning" onclick="edit_children()"><i class="fa fa-pencil"></i>&nbsp; Edit</button>
                            <button class="btn btn-primary" onclick="add_children()"><i class="fa fa-child"></i>&nbsp; Add Child</button>
                        </span>
                        <span class="option2" style="display: none;">
                            <button class="btn btn-success" onclick="submit_updated_children()"><i class="fa fa-save"></i>&nbsp; Save</button>
                            <button class="btn btn-warning" onclick="cancel_children()"><i class="fa fa-power-off"></i>&nbsp; Cancel</button>
                        </span>
                        <span class="option3" style="display: none;">
                            <button class="btn btn-success" id="uac_btn" onclick="submit_added_children()" disabled=""><i class="fa fa-save"></i>&nbsp; Save</button>
                            <button class="btn btn-warning" onclick="cancel_children()"><i class="fa fa-power-off"></i>&nbsp; Cancel</button>
                        </span>
                    </td>
                </tr> <?php

                    } else { ?>

                <tr>
                    <td>
                        <div class="form-group">
                            <label><?php echo ucwords($parent); ?> of the Child/Children <i class="text-red">(Please follow this format - Lastname, Firstname Middlename)</i></label>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-group" style="position: relative; z-index: 0;">
                                        <input type="text" name="mother_name" autocomplete="off" class="form-control update_children" onkeyup="mother_name(this.value)" placeholder="Lastname, Firstname Middlename">
                                        <div class="input-group-addon">
                                            <i class="fa fa-user"></i>
                                        </div>
                                    </div>
                                    <div class="search-results" style="display: none;"></div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="row">
                            <div class="col-md-7">
                                <div class="form-group">
                                    <label>Input Number of Children <i class="text-red">(Note: Maximum number to be inputed is 10)</i></label>
                                    <input type="number" name="number_child" class="form-control" onkeypress="return isNumberKey(event)">
                                    <input type="hidden" name="counter" value="0">
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <br>
                                    <button class="btn btn-primary" onclick="add_children_info()">Add</button>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr class="children_info" style="display: none;">
                    <td>
                        <form id="spouse_children_info">
                            <input type="hidden" name="empId" value="<?php echo $empId; ?>">
                            <input type="hidden" name="spouse_name" value="">
                            <input type="hidden" name="spouse_empId" value="">

                            <div class="row">
                                <div class="col-md-12" style="overflow-x: auto; width: 787px;">
                                    <table class="table" width="100%">
                                        <thead>
                                            <tr bgcolor="#EBEAEA">
                                                <th colspan="9">Child/Children</th>
                                            </tr>
                                            <tr>
                                                <th>Firstname</th>
                                                <th>Middlename</th>
                                                <th>Lastname</th>
                                                <th>Birthday</th>
                                                <th>Age</th>
                                                <th>Gender</th>
                                                <th>Deceased</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody id="my_table2">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </form>
                    </td>
                </tr>
                <tr>
                    <td>
                        <button class="btn btn-success" id="uac_btn" onclick="submit_spouse_children()" disabled=""><i class="fa fa-save"></i>&nbsp; Save</button>
                        <button class="btn btn-warning" onclick="cancel_children()"><i class="fa fa-power-off"></i>&nbsp; Cancel</button>
                    </td>
                </tr> <?php
                    }
                        ?>
        </tbody>
    </table>
    <script type="text/javascript">
        $('.datepicker').datepicker({
            inline: true,
            changeYear: true,
            changeMonth: true
        });

        $("[data-mask]").inputmask();
        $(".inputForm").prop("disabled", true);

        $("button.option_viewBC").click(function(e) {

            e.preventDefault();
            var id = this.id.split("_");
            childId = id[1];

            $("div#view_birthCert").modal({
                backdrop: 'static',
                keyboard: false
            });

            $("div#view_birthCert").modal("show");

            $.ajax({
                type: "POST",
                url: "<?php echo site_url('view_birthCert'); ?>",
                data: {
                    childId: childId
                },
                success: function(response) {

                    $("div.view_birthCert").html('<center><img class="preview_birthCert img-responsive" src="<?php echo 'http://' . $_SERVER['SERVER_ADDR'] . ":" . $_SERVER['SERVER_PORT'] . '/hrms/promo-nesco/'; ?>' + response + '" alt="Birth Cert."></center>');
                }
            });
        });

        $("button.option2").click(function(e) {

            e.preventDefault();
            var id = this.id.split("_");
            childId = id[1];

            $("div#update_birthCert").modal({
                backdrop: 'static',
                keyboard: false
            });

            $("div#update_birthCert").modal("show");
            $.ajax({
                type: "POST",
                url: "<?php echo site_url('update_birthCertForm'); ?>",
                data: {
                    childId: childId
                },
                success: function(response) {

                    $("div.update_birthCert").html(response);
                }
            });
        });
    </script>
<?php
} else if ($request == "contact") {

    $row = $this->employee_model->contactinfo($empId);

    $homeaddress       = $row['home_address'];
    $cityaddress       = $row['city_address'];
    $contactperson     = $row['contact_person'];
    $contactpersonadd  = $row['contact_person_address'];
    $contactpersonno   = $row['contact_person_number'];
    $cellphone         = $row['contactno'];
    $telno             = $row['telno'];
    $email             = $row['email'];
    $fb                = $row['facebookAcct'];
    $twitter           = $row['twitterAcct'];

?>
    <div class="modf">Contact &amp; Address Information
        <input name="edit" id="edit-contact" value="edit" class="btn btn-primary btn-sm" onclick="edit_contact()" type="button">
        <input class="btn btn-primary btn-sm" id="update_contact" value="update" onclick="update(this.id)" style="display:none" type="button">
    </div>
    <table class="table table-bordered">
        <tbody>
            <tr>
                <td width="20%" align="right">Home Address</td>
                <td colspan="4">
                    <input list="homeadd" name="homeaddress" autocomplete="off" value="<?php echo $homeaddress; ?>" class="form-control inputForm">
                    <datalist id="homeadd">
                        <?php

                        $result = $this->employee_model->brgy_town_prov();
                        foreach ($result as $rs) {

                            echo "<option value='" . $rs['brgy_name'] . ", " . $rs['town_name'] . ", " . $rs['prov_name'] . "'>" . $rs['brgy_name'] . ", " . $rs['town_name'] . ", " . $rs['prov_name'] . "</option>";
                        }
                        ?>
                    </datalist>
                </td>
            </tr>
            <tr>
                <td align="right">City Address</td>
                <td colspan="4">
                    <input list="cityadd" name="cityaddress" autocomplete="off" value="<?php echo $cityaddress; ?>" class="form-control inputForm">
                    <datalist id="cityadd">
                        <?php

                        $result = $this->employee_model->brgy_town_prov();
                        foreach ($result as $rs) {

                            echo "<option value='" . $rs['brgy_name'] . ", " . $rs['town_name'] . ", " . $rs['prov_name'] . "'>" . $rs['brgy_name'] . ", " . $rs['town_name'] . ", " . $rs['prov_name'] . "</option>";
                        }
                        ?>
                    </datalist>
                </td>
            </tr>
            <tr>
                <td align="right">Contact Person</td>
                <td colspan="4"><input name="contactperson" id="contactperson" value="<?php echo $contactperson; ?>" class="form-control inputForm" type="text"></td>
            </tr>
            <tr>
                <td align="right">Contact Person Address</td>
                <td colspan="4">
                    <input list="contactpersonadd" name="contactpersonadd" autocomplete="off" value="<?php echo $contactpersonadd; ?>" class="form-control inputForm">
                    <datalist id="contactpersonadd">
                        <?php

                        $result = $this->employee_model->brgy_town_prov();
                        foreach ($result as $rs) {

                            echo "<option value='" . $rs['brgy_name'] . ", " . $rs['town_name'] . ", " . $rs['prov_name'] . "'>" . $rs['brgy_name'] . ", " . $rs['town_name'] . ", " . $rs['prov_name'] . "</option>";
                        }
                        ?>
                    </datalist>
                </td>
            </tr>
            <tr>
                <td align="right">Contact Person No.</td>
                <td><input name="contactpersonno" value="<?php echo $contactpersonno; ?>" data-inputmask='"mask": "+639999999999"' data-mask class="form-control inputForm" type="text"></td>
                <td align="right">Cellphone No</td>
                <td><input name="cellphone" value="<?php echo $cellphone; ?>" data-inputmask='"mask": "+639999999999"' data-mask class="form-control inputForm" type="text"></td>
            </tr>
            <tr>
                <td align="right">Telephone No.</td>
                <td><input name="telno" value="<?php echo $telno; ?>" class="form-control inputForm" type="text"></td>
                <td align="right">Email address</td>
                <td><input name="email" value="<?php echo $email; ?>" class="form-control inputForm" type="text"></td>
            </tr>
            <tr>
                <td align="right">Facebook</td>
                <td><input name="fb" value="<?php echo $fb; ?>" class="form-control inputForm" type="text"></td>
                <td align="right">Twitter</td>
                <td><input name="twitter" value="<?php echo $twitter; ?>" class="form-control inputForm" type="text"></td>
            </tr>
        </tbody>
    </table>
    <script type="text/javascript">
        $(".inputForm").prop("disabled", true);
        $("[data-mask]").inputmask();
    </script>
<?php
} else if ($request == "educ") {

    $row = $this->employee_model->educinfo($empId);

    $attainment = $row['attainment'];
    $school     = $row['school'];
    $course     = $row['course'];

?>
    <div class="modf">Educational Background
        <input name="edit" id="edit-educ" value="edit" class="btn btn-primary btn-sm" onclick="edit_educ()" type="button">
        <input class="btn btn-primary btn-sm" id="update_educ" value="update" onclick="update(this.id)" style="display:none" type="button">
    </div>
    <table class="table table-bordered">
        <tbody>
            <tr>
                <td width="20%" align="right">Educational Attainment</td>
                <td>
                    <select name="attainment" class="form-control inputForm" id="attainment">
                        <option></option>
                        <?php

                        $result = $this->employee_model->attainment();
                        foreach ($result as $rw) {

                            if ($attainment == $rw['attainment']) {
                                echo "<option value='" . $rw['attainment'] . "' selected='selected' >" . $rw['attainment'] . "</option>";
                            } else {
                                echo "<option value='" . $rw['attainment'] . "' >" . $rw['attainment'] . "</option>";
                            }
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td align="right">School</td>
                <td>
                    <input list="schools" name="school" autocomplete="off" value="<?php echo $school; ?>" class="form-control inputForm">
                    <datalist id="schools">
                        <?php

                        $result = $this->employee_model->school_name();
                        foreach ($result as $rows) {

                            if ($school == $rows['school_name']) {
                                echo "<option value='" . $rows['school_name'] . "'>" . $rows['school_name'] . "</option>";
                            } else {
                                echo "<option value='" . $rows['school_name'] . "'>" . $rows['school_name'] . "</option>";
                            }
                        }
                        ?>
                    </datalist>
                </td>
            </tr>
            <tr>
                <td align="right">Details / Course</td>
                <td>
                    <input list="courses" name="course" autocomplete="off" value="<?php echo $course; ?>" class="form-control inputForm">
                    <datalist id="courses">
                        <?php

                        $result = $this->employee_model->select_all('course');
                        foreach ($result as $rs) {

                            if ($course == $rs->course_name) {
                                echo "<option value='" . $rs->course_name . "'>" . $rs->course_name . "</option>";
                            } else {
                                echo "<option value='" . $rs->course_name . "'>" . $rs->course_name . "</option>";
                            }
                        }
                        ?>
                    </datalist>
                </td>
            </tr>
        </tbody>
    </table>
    <script type="text/javascript">
        $(".inputForm").prop("disabled", true);
    </script>
<?php
} else if ($request == "seminar") {

?>
    <div class="modf">Eligibility / Seminar / Training Information
        <button class="btn btn-primary btn-sm" id="add-seminar" onclick="add_seminar('')">Add</button>
    </div>
    <table class="table table-striped table-hover" width="100%">
        <thead>
            <tr>
                <th>Name</th>
                <th>Date</th>
                <th>Location</th>
                <th>Certificate</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php

            $result = $this->employee_model->seminars_and_eligibility($empId);
            foreach ($result as $rwss) {

                echo "  
                        <tr>		           
                            <td>" . $rwss['name'] . "</td>
                            <td>" . $rwss['dates'] . "</td>
                            <td>" . $rwss['location'] . "</td>
                            <td>";
                if (!empty($rwss['sem_certificate'])) {
                    echo "<button class='btn btn-primary btn-sm' onclick='viewSeminarCert(" . $rwss['no'] . ")'>view</button>";
                }
                echo "
                            </td>
                            <td><input type='button' class='btn btn-primary btn-sm' value='edit' id='edit-seminar' onclick='add_seminar(" . $rwss['no'] . ")'></td>
                        </tr>";
            }
            ?>
        </tbody>
    </table>
<?php
} else if ($request == "charref") {

?>
    <div class="modf">Character References
        <button class="btn btn-primary btn-sm" id="add_charref" onclick="add_charref('')">add</button>
    </div>
    <table class="table table-striped table-hover" width="100%">
        <thead>
            <tr>
                <th>Name</th>
                <th>Position</th>
                <th>Contact Number</th>
                <th>Company / Location</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php

            $result = $this->employee_model->character_ref($empId);
            foreach ($result as $rws) {

                echo " 
                            <tr>            
                                <td>" . $rws['name'] . "</td>
                                <td>" . $rws['position'] . "</td>
                                <td>" . $rws['contactno'] . "</td>
                                <td>" . $rws['company'] . "</td>
                                <td><button class='btn btn-primary btn-sm' id='edit-charref' onclick=add_charref('$rws[no]')>edit</button></td>
                            </tr>";
            }
            ?>
        </tbody>
    </table>
<?php
} else if ($request == "skills") {

    $row = $this->employee_model->skills_info($empId);

    $hobbies     = $row['hobbies'];
    $skills      = $row['specialSkills'];

?>
    <div class="modf">Skills &amp; Competencies
        <input name="edit" id="edit-skills" value="edit" class="btn btn-primary btn-sm" onclick="edit_skills()" type="button">
        <input class="btn btn-primary btn-sm" id="update_skills" value="update" onclick="update(this.id)" style="display:none" type="button">
    </div>
    <table class="table table-bordered" width="100%">
        <tbody>
            <tr>
                <td width="20%" align="right">Hobbies</td>
                <td><textarea name="hobbies" class="form-control inputForm" onkeyup="inputField(this.name)"><?php echo $hobbies; ?></textarea></td>
            </tr>
            <tr>
                <td align="right">Special skills / Talents</td>
                <td><textarea name="skills" class="form-control inputForm" onkeyup="inputField(this.name)"><?php echo $skills; ?></textarea></td>
            </tr>
        </tbody>
    </table>
    <script type="text/javascript">
        $(".inputForm").prop("disabled", true);
    </script>
<?php
} else if ($request == "eocapp") {

?>
    <div class="modf">EOC Appraisal History</div>
    <div class="table-height">
        <table class="table table-hover" width="100%">
            <thead>
                <tr>
                    <th>Startdate</th>
                    <th>EOCdate</th>
                    <th>Rater's Name</th>
                    <th>NumRate</th>
                    <th>DescRate</th>
                    <th>Store</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php

                $ctr = 0;
                $storeEpas = '';
                $bUs = $this->dashboard_model->businessUnit_list();
                foreach ($bUs as $bu) {

                    $hasBU = $this->dashboard_model->promo_has_bu($empId, $bu->bunit_field);
                    if ($hasBU > 0) {

                        $ctr++;

                        if ($ctr == 1) {

                            $storeEpas = "AND (" . $bu->bunit_epascode . " = '1'";
                        } else {

                            $storeEpas .= " OR " . $bu->bunit_epascode . " = '1'";
                        }
                    }
                }

                $storeEpas .= ")";

                // current contract
                $sql = "SELECT employee3.record_no, startdate, eocdate 
                            FROM employee3 
                                INNER JOIN promo_record 
                                ON employee3.record_no = promo_record.record_no AND employee3.emp_id = promo_record.emp_id
                                WHERE employee3.emp_id = '" . $empId . "' $storeEpas ORDER BY startdate DESC";
                $result = $this->employee_model->return_result_array($sql);
                foreach ($result as $row) {

                    $eocdate = "";
                    if ($row['eocdate'] != "0000-00-00" && $row['eocdate'] != "") {

                        $eocdate = date("m/d/Y", strtotime($row['eocdate']));
                    }

                    $appraisal = "SELECT details_id, rater, numrate, descrate, ratingdate, store FROM appraisal_details WHERE record_no = '" . $row['record_no'] . "' and emp_id = '" . $empId . "'";
                    $app = $this->employee_model->return_row_array($appraisal);
                ?>
                    <tr>
                        <td><?php echo date("m/d/Y", strtotime($row['startdate'])); ?></td>
                        <td><?php echo $eocdate; ?></td>
                        <td><a href="<?php echo 'http://172.16.161.34:8080/hrms/placement/employee_details.php?com=' . $app['rater']; ?>" target="_blank"><?php echo $this->employee_model->employee_name($app['rater'])['name']; ?></a></td>
                        <td><?php echo $app['numrate']; ?></td>
                        <td><?php echo $app['descrate']; ?></td>
                        <td><?php echo $app['store']; ?></td>
                        <td><button class="btn btn-sm btn-primary" onclick="view_appraisal_details(<?php echo $app['details_id']; ?>)"> View </button></td>
                    </tr>
                    <?php
                }

                // previous contract
                $sql = "SELECT employmentrecord_.record_no, startdate, eocdate 
                            FROM employmentrecord_ 
                                INNER JOIN promo_history_record
                                ON employmentrecord_.record_no = promo_history_record.record_no AND employmentrecord_.emp_id = promo_history_record.emp_id
                                WHERE employmentrecord_.emp_id = '" . $empId . "' ORDER BY startdate DESC";
                $result = $this->employee_model->return_result_array($sql);
                foreach ($result as $row) {

                    $ctr = 0;
                    $storeEpas = '';
                    $bUs = $this->dashboard_model->businessUnit_list();
                    foreach ($bUs as $bu) {

                        $hasBU = $this->dashboard_model->promo_has_history_bu($empId, $row['record_no'], $bu->bunit_field);
                        if ($hasBU > 0) {

                            $ctr++;

                            if ($ctr == 1) {

                                $storeEpas = "AND (" . $bu->bunit_epascode . " = '1'";
                            } else {

                                $storeEpas .= " OR " . $bu->bunit_epascode . " = '1'";
                            }
                        }
                    }

                    $storeEpas .= ")";

                    $eocdate = "";
                    if ($row['eocdate'] != "0000-00-00" && $row['eocdate'] != "") {

                        $eocdate = date("m/d/Y", strtotime($row['eocdate']));
                    }

                    $query = "SELECT COUNT(promo_id) AS exist FROM promo_history_record WHERE record_no = '" . $row['record_no'] . "' AND emp_id = '" . $empId . "' $storeEpas";
                    $response = $this->employee_model->return_row_array($query);
                    if ($response['exist'] > 0) {

                        $appraisal = "SELECT details_id, rater, numrate, descrate, ratingdate, store FROM appraisal_details WHERE record_no = '" . $row['record_no'] . "' and emp_id = '" . $empId . "'";
                        $appraisals = $this->employee_model->return_result_array($appraisal);

                        foreach ($appraisals as $app) {
                    ?>
                            <tr>
                                <td><?php echo date("m/d/Y", strtotime($row['startdate'])); ?></td>
                                <td><?php echo $eocdate; ?></td>
                                <td><a href="<?php echo 'http://172.16.161.34:8080/hrms/placement/employee_details.php?com=' . $app['rater']; ?>" target="_blank"><?php echo $this->employee_model->employee_name($app['rater'])['name']; ?></a></td>
                                <td><?php echo $app['numrate']; ?></td>
                                <td><?php echo $app['descrate']; ?></td>
                                <td><?php echo $app['store']; ?></td>
                                <td><button class="btn btn-sm btn-primary" onclick="view_appraisal_details(<?php echo $app['details_id']; ?>)"> View </button></td>
                            </tr>
                <?php
                        }
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
<?php
} else if ($request == "application") {

    $row = $this->employee_model->application_info($empId);

    if ($row['date_applied'] == '' || $row['date_applied'] == '0000-00-00') {
        $dateApplied = '';
    } else {
        $dateApplied = date('m/d/Y', strtotime($row['date_applied']));
    }
    if ($row['date_hired'] == '' || $row['date_hired'] == '0000-00-00') {
        $dateHired = '';
    } else {
        $dateHired      = date('m/d/Y', strtotime($row['date_hired']));
    }
    if ($row['date_brief'] == '' || $row['date_brief'] == '0000-00-00') {
        $dateBrief = '';
    } else {
        $dateBrief      = date('m/d/Y', strtotime($row['date_brief']));
    }
    if ($row['date_examined'] == '' || $row['date_examined'] == '0000-00-00') {
        $dateExamined = '';
    } else {
        $dateExamined      = date('m/d/Y', strtotime($row['date_examined']));
    }
    $examResult     = $row['exam_results'];
    $posApplied  = $row['position_applied'];
    $aeRegular   = $row['aeregular'];

?>
    <div class="modf">Application History
        <button name="edit" id="edit-apphis" class="btn btn-primary btn-sm" onclick="edit_apphis()">edit</button>
        <button class="btn btn-primary btn-sm" id="update_apphis" onclick="update(this.id)" style="display:none">update</button>
    </div>
    <table class="table table-bordered">
        <tbody>
            <tr>
                <td width="20%" align="right">Position Applied</td>
                <td width="30%">
                    <select name="posApplied" class="form-control select2 inputForm">
                        <option value=""> --Select-- </option>
                        <?php

                        $positions = $this->employee_model->list_of_positions();
                        foreach ($positions as $p) { ?>

                            <option value="<?php echo $p['position_title']; ?>" <?php if ($posApplied == $p['position_title']) : echo "selected=''";
                                                                                endif; ?>><?php echo $p['position_title']; ?></option><?php
                                                                                                                                    }
                                                                                                                                        ?>
                    </select>
                </td>
                <td width="20%" align="right">Date Applied</td>
                <td><input name="dateApplied" value="<?php echo $dateApplied; ?>" placeholder="mm/dd/yyyy" class="form-control datepicker inputForm" type="text"></td>
            </tr>
            <tr>
                <td align="right">Date of Exam</td>
                <td><input name="dateExamined" value="<?php echo $dateExamined; ?>" placeholder="mm/dd/yyyy" class="form-control datepicker inputForm" data-inputmask="'alias': 'mm/dd/yyyy'" data-mask="" type="text"></td>
                <td align="right">Exam Result</td>
                <td><input name="examResult" value="<?php echo $examResult; ?>" class="form-control inputForm" type="text"></td>
            </tr>
            <tr>
                <td align="right">Date Briefed</td>
                <td><input name="dateBrief" value="<?php echo $dateBrief; ?>" placeholder="mm/dd/yyyy" class="form-control datepicker inputForm" data-inputmask="'alias': 'mm/dd/yyyy'" data-mask="" type="text"></td>
                <td align="right">Date Hired</td>
                <td><input name="dateHired" value="<?php echo $dateHired; ?>" placeholder="mm/dd/yyyy" class="form-control datepicker inputForm" data-inputmask="'alias': 'mm/dd/yyyy'" data-mask="" type="text"></td>
            </tr>
            <tr>
                <td align="right">Recommended by (Alturas Employee)</td>
                <td colspan="3"><input name="aeRegular" value="<?php echo $aeRegular; ?>" class="form-control inputForm" type="text"></td>
            </tr>
        </tbody>
    </table>
    <table class="table table-striped" width="100%">
        <thead bgcolor="#f9f9f9">
            <tr>
                <th colspan="11" height="39">Examination History</th>
            </tr>
            <tr bgcolor="#ccc">
                <th width="174">No.</th>
                <th width="130">Examination&nbsp;Date</th>
                <th width="500">Applying&nbsp;For</th>
                <th width="345">Exam&nbsp;Code</th>
                <th width="345">Exam&nbsp;Details</th>
            </tr>
        </thead>
        <tbody>
            <?php

            $x = 0;
            $sql = "SELECT date_time, description, position FROM application_history WHERE app_id='" . $empId . "' AND phase='Examination' AND status='completed'";
            $sql_num = $this->employee_model->return_num_rows($sql);

            if ($sql_num > 0) {

                $results = $this->employee_model->return_result_array($sql);
                foreach ($results as $result) {

                    $x++;
                    $exstr = explode(",", $result['description']);
                    $excode = explode(" ", $exstr[1]);
                    $exam_val = $empId . "|" . $excode[0];
                    echo
                    "<tr>
                                    <td width='174'>" . $x . ".</td>
                                    <td width='419'>" . date("M. d, Y", strtotime($result['date_time'])) . "</td>
                                    <td width='307'>" . $result['position'] . "</td>
                                    <td width='419'>" . $excode[0] . "</td>
                                    <td width='345' align='center'><a href='#' onclick='viewExam(\"$exam_val\")'>view</a></td>                
                                </tr>";
                }
            }
            ?>
        </tbody>
    </table>
    <input class="btn btn-primary btn-sm" onclick="viewAppDetails('<?php echo $empId; ?>')" value="View Application Details" type="button">
    <input class="btn btn-primary btn-sm" onclick="viewInterview('<?php echo $empId; ?>')" value="View Interview Details" type="button">
    <script type="text/javascript">
        $('.datepicker').datepicker({
            inline: true,
            changeYear: true,
            changeMonth: true
        });

        $("[data-mask]").inputmask();
        $('.select2').select2();
        $(".inputForm").prop("disabled", true);
    </script>
<?php
} else if ($request == "employment") {

?>
    <div class="modf">Contract History
        <button id="add-contract" class="btn btn-primary btn-sm" onclick="add_contract()">add</button>
    </div>
    <p>
        <i class="text-red">Note:</i> There should <code>ONLY BE ONE CURRENT CONTRACT</code> and that should be the latest contract of the employee.<br>
        <span style="display:inline-block; width: 35px;"></span>When adding <code>PREVIOUS CONTRACT</code>, status should not be active.
    </p>

    <!-- ./current Contract -->
    <h4><span class="btn btn-success btn-xs">CURRENT CONTRACT</span></h4>
    <table class="table table-hover" width="100%">
        <thead>
            <tr>
                <th width="1%">No</th>
                <th>Position</th>
                <th>Company</th>
                <th>BusinessUnit</th>
                <th>Department</th>
                <th>Status</th>
                <th>Startdate</th>
                <th>EOCdate</th>
                <th width="9%">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php

            $no = 0;
            $sql = "SELECT record_no, emp_id, startdate, eocdate, emp_type, current_status, company_code, bunit_code, dept_code, section_code, position FROM employee3 WHERE emp_id = '$empId'";
            $result = $this->employee_model->return_result_array($sql);
            foreach ($result as $row) {

                if ($row['startdate'] == "0000-00-00" || $row['startdate'] == "1970-01-01") : $startdate = '';
                else : $startdate = date("m/d/Y", strtotime($row['startdate']));
                endif;
                if ($row['eocdate'] == "0000-00-00" || $row['eocdate'] == "1970-01-01" || $row['eocdate'] == "0001-11-30") : $eocdate = '';
                else : $eocdate = date("m/d/Y", strtotime($row['eocdate']));
                endif;
                $recordNo = $row['record_no'];

                $no++;
                if ($row['emp_type'] == 'Promo-NESCO') {

                    $promo_details = $this->employee_model->promo_details('promo_record', $empId, $recordNo);
                    $ctr = 0;
                    $storeName = '';
                    $bUs = $this->dashboard_model->businessUnit_list();
                    foreach ($bUs as $bu) {

                        $hasBU = $this->dashboard_model->promo_has_bu($empId, $bu->bunit_field);
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
                                <td>" . $no . ".</td>
                                <td>" . $row['position'] . "</td>
                                <td>" . $promo_details->promo_company . "</td>
                                <td>" . $storeName . "</td>
                                <td>" . $promo_details->promo_department . "</td>
                                <td>" . $row['current_status'] . "</td>
                                <td>" . $startdate . "</td>
                                <td>" . $eocdate . "</td>
                                <td>
                                    <a href='javascript:void(0)' onclick='viewPromoDetails(\"current\",\"$recordNo\",\"$empId\")' title='View Promo Information' class='text-success'><i class='glyphicon glyphicon-info-sign'></i></a>
                                    <a href='javascript:void(0)' onclick='updatePromoDetails(\"current\",\"$recordNo\",\"$empId\")' title='Edit Employment History' class='text-red'><i class='glyphicon glyphicon-pencil'></i></a>
                                    <a href='javascript:void(0)' onclick='uploadPromoScannedFile(\"current\",\"$recordNo\",\"$empId\")' title='Upload Scanned Contract'><i class='glyphicon glyphicon-upload'></i></a>
                                </td>
                            </tr>
                            ";
                } else {

                    if (trim($this->employee_model->asc_company_name($row2['company_code'])['acroname']) != "") : $companyName = $this->employee_model->asc_company_name($row2['company_code'])['acroname'];
                    else : $companyName = $this->employee_model->asc_company_name($row2['company_code'])['company'];
                    endif;

                    if (trim($this->employee_model->get_businessunit_name($row2['company_code'], $row2['bunit_code'])['acroname']) != "") : $bunitName = $this->employee_model->get_businessunit_name($row2['company_code'], $row2['bunit_code'])['acroname'];
                    else : $bunitName = $this->employee_model->get_businessunit_name($row2['company_code'], $row2['bunit_code'])['business_unit'];
                    endif;

                    if (trim($this->employee_model->get_department_name($row2['company_code'], $row2['bunit_code'], $row2['dept_code'])['acroname']) != "") : $deptName = $this->employee_model->get_department_name($row2['company_code'], $row2['bunit_code'], $row2['dept_code'])['acroname'];
                    else : $deptName = $this->employee_model->get_department_name($row2['company_code'], $row2['bunit_code'], $row2['dept_code'])['dept_name'];
                    endif;

                    echo "
                            <tr>
                                <td>" . $no . ".</td>
                                <td>" . $row['position'] . "</td>
                                <td>" . $companyName . "</td>
                                <td>" . $bunitName . "</td>
                                <td>" . $deptName . "</td>
                                <td>" . $row['current_status'] . "</td>
                                <td>" . $startdate . "</td>
                                <td>" . $eocdate . "</td>
                                <td>
                                    <a href='javascript:void(0)' onclick='viewDetails(\"current\",\"$recordNo\",\"$empId\")' title='View Promo Information' class='text-success'><i class='glyphicon glyphicon-info-sign'></i></a>
                                    <a href='javascript:void(0)' onclick='updateDetails(\"current\",\"$recordNo\",\"$empId\")' title='Edit Employment History' class='text-red'><i class='glyphicon glyphicon-pencil'></i></a>
                                    <a href='javascript:void(0)' onclick='uploadScannedFile(\"current\",\"$recordNo\",\"$empId\")' title='Upload Scanned Contract'><i class='glyphicon glyphicon-upload'></i></a>
                                </td>
                            </tr>
                            ";
                }
            }
            ?>
        </tbody>
    </table>

    <!-- ./previous Contract -->
    <h4><span class="btn btn-danger btn-xs">PREVIOUS CONTRACT</span></h4>
    <table class="table table-hover" width="100%">
        <thead>
            <tr>
                <th width="1%">No</th>
                <th>Position</th>
                <th>Company</th>
                <th>BusinessUnit</th>
                <th>Department</th>
                <th>Status</th>
                <th>Startdate</th>
                <th>EOCdate</th>
                <th width="9%">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php

            $no = 0;
            $sql2 = "SELECT record_no, emp_id, startdate, eocdate, emp_type, current_status, company_code, bunit_code, dept_code, section_code, position FROM employmentrecord_ WHERE emp_id = '$empId' ORDER BY startdate DESC";
            $result2 = $this->employee_model->return_result_array($sql2);
            foreach ($result2 as $row2) {

                $recordNo = $row2['record_no'];
                if ($row2['startdate'] == "0000-00-00" || $row2['startdate'] == "1970-01-01") : $startdate = '';
                else : $startdate = date("m/d/Y", strtotime($row2['startdate']));
                endif;
                if ($row2['eocdate'] == "0000-00-00" || $row2['eocdate'] == "1970-01-01" || $row2['eocdate'] == "0001-11-30") : $eocdate = '';
                else : $eocdate = date("m/d/Y", strtotime($row2['eocdate']));
                endif;

                $no++;

                if (strpos($row2['emp_type'], 'Promo') !== false) {

                    $promo_details = $this->employee_model->promo_details('promo_history_record', $empId, $recordNo);
                    $ctr = 0;
                    $storeName = '';
                    $bUs = $this->dashboard_model->businessUnit_list();
                    foreach ($bUs as $bu) {

                        $hasBU = $this->dashboard_model->promo_has_history_bu($empId, $recordNo, $bu->bunit_field);
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
                                <td>" . $no . ".</td>
                                <td>" . $row['position'] . "</td>
                                <td>" . $promo_details->promo_company . "</td>
                                <td>" . $storeName . "</td>
                                <td>" . $promo_details->promo_department . "</td>
                                <td>" . $row2['current_status'] . "</td>
                                <td>" . $startdate . "</td>
                                <td>" . $eocdate . "</td>
                                <td>
                                    <a href='javascript:void(0)' onclick='viewPromoDetails(\"previous\",\"$recordNo\",\"$empId\")' title='View Promo Information' class='text-success'><i class='glyphicon glyphicon-info-sign'></i></a>
                                    <a href='javascript:void(0)' onclick='updatePromoDetails(\"previous\",\"$recordNo\",\"$empId\")' title='Edit Employment History' class='text-red'><i class='glyphicon glyphicon-pencil'></i></a>
                                    <a href='javascript:void(0)' onclick='uploadPromoScannedFile(\"previous\",\"$recordNo\",\"$empId\")' title='Upload Scanned Contract'><i class='glyphicon glyphicon-upload'></i></a>
                                </td>
                            </tr>
                            ";
                } else {

                    if (trim($this->employee_model->asc_company_name($row2['company_code'])['acroname']) != "") : $companyName = $this->employee_model->asc_company_name($row2['company_code'])['acroname'];
                    else : $companyName = $this->employee_model->asc_company_name($row2['company_code'])['company'];
                    endif;

                    if (trim($this->employee_model->get_businessunit_name($row2['company_code'], $row2['bunit_code'])['acroname']) != "") : $bunitName = $this->employee_model->get_businessunit_name($row2['company_code'], $row2['bunit_code'])['acroname'];
                    else : $bunitName = $this->employee_model->get_businessunit_name($row2['company_code'], $row2['bunit_code'])['business_unit'];
                    endif;

                    if (trim($this->employee_model->get_department_name($row2['company_code'], $row2['bunit_code'], $row2['dept_code'])['acroname']) != "") : $deptName = $this->employee_model->get_department_name($row2['company_code'], $row2['bunit_code'], $row2['dept_code'])['acroname'];
                    else : $deptName = $this->employee_model->get_department_name($row2['company_code'], $row2['bunit_code'], $row2['dept_code'])['dept_name'];
                    endif;

                    echo "
                            <tr>
                                <td>" . $no . ".</td>
                                <td>" . $row2['position'] . "</td>
                                <td>" . $companyName . "</td>
                                <td>" . $bunitName . "</td>
                                <td>" . $deptName . "</td>
                                <td>" . $row2['current_status'] . "</td>
                                <td>" . $startdate . "</td>
                                <td>" . $eocdate . "</td>
                                <td>
                                    <a href='javascript:void(0)' onclick='viewDetails(\"previous\",\"$recordNo\",\"$empId\")' title='View Promo Information' class='text-success'><i class='glyphicon glyphicon-info-sign'></i></a>
                                    <a href='javascript:void(0)' onclick='updateDetails(\"previous\",\"$recordNo\",\"$empId\")' title='Edit Employment History' class='text-red'><i class='glyphicon glyphicon-pencil'></i></a>
                                    <a href='javascript:void(0)' onclick='uploadScannedFile(\"previous\",\"$recordNo\",\"$empId\")' title='Upload Scanned Contract'><i class='glyphicon glyphicon-upload'></i></a>
                                </td>
                            </tr>
                        ";
                }
            }

            ?>
        </tbody>
    </table>
<?php
} else if ($request == "history") {

    $sql = "SELECT * FROM application_employment_history WHERE app_id = '$empId'";
    $result = $this->employee_model->return_result_array($sql);

?>
    <div class="modf">Employment History
        <input class="btn btn-primary btn-sm" id="add-emphis" value="add" onclick="add_emphis('')" type="button">
    </div>
    <table class="table table-striped" width="100%">
        <thead>
            <tr>
                <th>No</th>
                <th>Company</th>
                <th>Position</th>
                <th>DateStart</th>
                <th>DateEnd</th>
                <th>Address/Location</th>
                <th>Certificate</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php

            $i = 0;
            foreach ($result as $row) {

                $i++;
                echo "
								<tr>
									<td>" . $i . ".</td>
									<td>" . $row['company'] . "</td>
									<td>" . $row['position'] . "</td>
									<td>" . $row['yr_start'] . "</td>
									<td>" . $row['yr_ends'] . "</td>
									<td>" . $row['address'] . "</td>
									<td>";

                if (!empty($row['emp_certificate'])) {

                    echo "<button class='btn btn-primary btn-sm' onclick=viewEmpCert('$row[no]')>view</button>";
                } else {

                    echo "none";
                }
                echo "
									</td>
									<td>
										<td><button class='btn btn-primary btn-sm' onclick=add_emphis('$row[no]')>edit</button></td>
									</td>
								</tr>
							";
            }
            ?>
        </tbody>
    </table>
<?php
} else if ($request == "transfer") {

    $sql = "SELECT * FROM employee_transfer_details WHERE emp_id = '$empId' ORDER BY transfer_no DESC";
    $result = $this->employee_model->return_result_array($sql);
?>
    <div class="modf">Job Transfer History</div>
    <table class="table table-striped" id="data">
        <thead>
            <tr>
                <th>No</th>
                <th>Effectivity</th>
                <th>TransferFrom</th>
                <th>TransferTo</th>
                <th>OldPosition</th>
                <th>NewPosition</th>
                <th>DirectSupervisor</th>
                <th>JobTrans</th>
            </tr>
        </thead>
        <tbody>
            <?php

            foreach ($result as $row) {

                $oldLoc = explode('-', $row['old_location']);
                $newLoc = explode('-', $row['new_location']);

                // previous department
                if ($this->employee_model->get_department_name(@$oldLoc[0], @$oldLoc[1], @$oldLoc[2])['acroname'] != "") {

                    $olddept = $this->employee_model->get_department_name(@$oldLoc[0], @$oldLoc[1], @$oldLoc[2])['acroname'];
                } else {

                    $olddept = $this->employee_model->get_department_name(@$oldLoc[0], @$oldLoc[1], @$oldLoc[2])['dept_name'];
                }

                // current department
                if ($this->employee_model->get_department_name(@$newLoc[0], @$newLoc[1], @$newLoc[2])['acroname'] != "") {

                    $dept = $this->employee_model->get_department_name(@$newLoc[0], @$newLoc[1], @$newLoc[2])['acroname'];
                } else {

                    $dept = $this->employee_model->get_department_name(@$newLoc[0], @$newLoc[1], @$newLoc[2])['dept_name'];
                }

                // business unit
                $businessUnit = $this->employee_model->get_businessunit_name(@$oldLoc[0], @$oldLoc[1])['acroname'];
                if (trim($businessUnit) != "") {

                    $businessUnit = $this->employee_model->get_businessunit_name(@$oldLoc[0], @$oldLoc[1])['business_unit'];
                } ?>
                <tr>
                    <td><?php echo $row['transfer_no']; ?></td>
                    <td><?php
                        if (strlen($row['effectiveon']) > 10 || strlen($row['effectiveon']) < 10) {
                            echo $row['effectiveon'];
                        } else {
                            echo date('m/d/Y', strtotime($row['effectiveon']));
                        } ?></td>
                    <td><?php echo $this->employee_model->asc_company_name(@$oldLoc[0])['acroname'] . "-" . $businessUnit . "-" . $olddept; ?></td>
                    <td><?php echo $this->employee_model->asc_company_name(@$newLoc[0])['acroname'] . "-" . $businessUnit . "-" . $dept; ?></td>
                    <td><?php echo $row['old_position']; ?></td>
                    <td><?php echo $row['position']; ?></td>
                    <td><?php echo $row['supervision']; ?></td>
                    <td><button class="btn btn-primary btn-sm" onclick="viewJobTrans(<?php echo $row['transfer_no']; ?>)">view</button></td>
                </tr> <?php
                    }
                        ?>
        </tbody>
    </table>
<?php
} else if ($request == "blacklist") {

    $sql = "SELECT blacklist_no, date_blacklisted, date_added, reportedby, reason, status FROM `blacklist` WHERE app_id = '$empId'";
    $blNum = $this->employee_model->return_row_array($sql);

?>
    <div class="modf">Blacklist History
        <button class="btn btn-primary btn-sm" id="add-blacklist" <?php if ($blNum > 0) : echo "disabled=''";
                                                                    endif; ?> onclick="add_blacklist('')">add</button>
    </div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>DateBlacklisted</th>
                <th>ReportedBy</th>
                <th>Reason</th>
                <th>DateAdded</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php

            $result = $this->employee_model->return_result_array($sql);
            foreach ($result as $row) {

                if ($row['date_blacklisted'] == '0000-00-00' || $row['date_blacklisted'] == '') {

                    $datebl = '';
                } else {

                    $datebl = date("m/d/Y", strtotime($row['date_blacklisted']));
                }

                if ($row['date_added'] == '0000-00-00' || $row['date_added'] == '') {

                    $dateadded = '';
                } else {

                    $dateadded = date("m/d/Y", strtotime($row['date_added']));
                }

                echo "
                            <tr>
                                <td>" . $datebl . "</td>
                                <td>" . $row['reportedby'] . "</td>
                                <td>" . $row['reason'] . "</td>
                                <td>" . $dateadded . "</td>
                                <td><label class='btn btn-xs btn-danger btn-block'>" . $row['status'] . "</label></td>
                                <td><button class='btn btn-sm btn-primary' onclick='add_blacklist(" . $row['blacklist_no'] . ")'>edit</button></td>
                            </tr>
                        ";
            }
            ?>
        </tbody>
    </table>
<?php
} else if ($request == "benefits") {

    $sql = "SELECT sss_no, pagibig_tracking, pagibig, philhealth, tin_no FROM `applicant_otherdetails` WHERE app_id = '$empId'";
    $row = $this->employee_model->return_row_array($sql);

?>
    <div class="modf">BENEFITS
        <input name="edit" id="edit-benefits" value="edit" class="btn btn-primary btn-sm" onclick="edit_benefits()" type="button">
        <input class="btn btn-primary btn-sm" id="update_benefits" value="update" style="display:none" onclick="update(this.id)" type="button">
    </div>
    <table class="table table-bordered" width="100%">
        <tbody>
            <tr>
                <td align="right" width="20%">Philhealth No.</td>
                <td><input name="ph" value="<?php echo $row['philhealth']; ?>" class="form-control inputForm" placeholder="00-000000000-0" type="text" data-inputmask='"mask": "99-999999999-9"' data-mask></td>
            </tr>
            <tr>
                <td align="right">SSS No.</td>
                <td><input name="sss" value="<?php echo $row['sss_no']; ?>" class="form-control inputForm" placeholder="00-0000000-0" type="text" data-inputmask='"mask": "99-9999999-9"' data-mask></td>
            </tr>
            <tr>
                <td align="right">Pag-ibig No.</td>
                <td><input name="pagibig" value="<?php echo $row['pagibig']; ?>" class="form-control inputForm" placeholder="0000-0000-0000" type="text" data-inputmask='"mask": "9999-9999-9999"' data-mask></td>
            </tr>
            <tr>
                <td align="right">Pag-ibig RTN</td>
                <td><input name="pagibigrtn" value="<?php echo $row['pagibig_tracking']; ?>" class="form-control inputForm" placeholder="0000-0000-0000" type="text" data-inputmask='"mask": "9999-9999-9999"' data-mask></td>
            </tr>
            <tr>
                <td align="right">TIN no.</td>
                <td><input name="tinno" value="<?php echo $row['tin_no']; ?>" class="form-control inputForm" placeholder="000-000-000-000" type="text" data-inputmask='"mask": "999-999-999-999"' data-mask></td>
            </tr>
        </tbody>
    </table>
    <script type="text/javascript">
        $(".inputForm").prop("disabled", true);
        $("[data-mask]").inputmask();
    </script>
<?php
} else if ($request == "201doc") {

    $doc = "SELECT no, 201_name, tableName, empField, table_condition FROM 201document WHERE promo = 'yes' ORDER BY 201_name ASC";
    $result = $this->employee_model->return_result_array($doc);

?>
    <div class="modf">201 Documents
        <input type="button" class="btn btn-sm btn-primary" value="upload" onclick="upload201Files()">
    </div>
    <div class="row">
        <?php

        $total = 0;
        foreach ($result as $row) {

            $no         = $row['no'];
            $title         = $row['201_name'];
            $tableName     = $row['tableName'];
            $empField     = $row['empField'];
            $table_condition = $row['table_condition'];

            $sql = "SELECT $empField FROM `$tableName` WHERE $empField = '$empId' $table_condition";
            $total = $this->employee_model->return_num_rows($sql);

        ?>
            <div class="col-md-3">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <center><span class="sm"><?php echo $title; ?></span></center>
                    </div>
                    <div class="panel-body">
                        <span class="label label-danger pull-right"><?php echo $total; ?></span>
                        <center>
                            <a href="javascript:void(0)" onclick="view201Files('<?php echo $no; ?>','<?php echo $title; ?>')" title="click to view"><img src="<?php echo base_url('assets/images/docs.png'); ?>" class="img"></a>
                        </center>
                    </div>
                </div>
            </div> <?php
                }
                    ?>
    </div>
<?php
} else if ($request == "pss") {

?>
    <div class="modf">Peer-Subordinate-Supervisor</div>
    <div>
        <h4>Supervisor <button class="btn btn-primary btn-sm" id="add-supervisor" onclick="add_supervisor()">add</button></h4>
    </div>
    <hr>
    <table class="table table-striped table-hover" width="100%">
        <thead>
            <tr>
                <th>No</th>
                <th>Name</th>
                <th>Position</th>
                <th>Status</th>
                <th>
                    <center>EmployeeType</center>
                </th>
                <th>
                    <center>Action</center>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php

            $sql = "SELECT leveling_subordinates.record_no, ratee, name, current_status, emp_type, position FROM leveling_subordinates, employee3 WHERE leveling_subordinates.ratee = employee3.emp_id AND subordinates_rater = '$empId' ORDER BY name ASC";
            $supervisors = $this->employee_model->return_result_array($sql);

            $no = 1;
            foreach ($supervisors as $row) {

                if ($row['current_status'] == "Active") {

                    $class = "btn btn-success btn-xs btn-block";
                } else if ($row['current_status'] == "End of Contract" || $row['current_status'] == "Resigned") {

                    $class = "btn btn-warning btn-xs btn-block";
                } else {

                    $class = "btn btn-danger btn-xs btn-block";
                }

                echo "
                            <tr id='remove_" . $row['record_no'] . "'>
                                <td>" . $no++ . ".</td>
                                <td><a href='http://" . $_SERVER['SERVER_ADDR'] . ':' . $_SERVER['SERVER_PORT'] . "/hrms/nesco/?p=employee&com=" . $row['ratee'] . "' target='_blank'>" . ucwords(strtolower($row['name'])) . "</a></td>
                                <td>" . $row['position'] . "</td>
                                <td><label class='$class'>" . $row['current_status'] . "</label></td>
                                <td align='center'>" . $row['emp_type'] . "</td>
                                <td align='center'><i class='glyphicon glyphicon-trash text-red' title='remove supervisor' onclick=removeSubordinates('" . $row['record_no'] . "','supervisor')></i>
                            </tr>
                        ";
            }
            ?>
        </tbody>
    </table>
    <br>
    <h4>Subordinates</h4>
    <hr>
    <table id="dt_subordinates" class="table table-striped table-hover" width="100%">
        <thead>
            <tr>
                <th>No</th>
                <th>Name</th>
                <th>Position</th>
                <th>Status</th>
                <th>
                    <center>Action</center>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php

            $sql = "SELECT leveling_subordinates.record_no, subordinates_rater, name, current_status, emp_type, position FROM leveling_subordinates, employee3 WHERE leveling_subordinates.subordinates_rater = employee3.emp_id AND ratee = '" . $empId . "' ORDER BY name ASC";
            $subordinates = $this->employee_model->return_result_array($sql);

            $no = 1;
            foreach ($subordinates as $row) {

                if ($row['current_status'] == "Active") {

                    $class = "btn btn-success btn-xs btn-block";
                } else if ($row['current_status'] == "End of Contract" || $row['current_status'] == "Resigned") {

                    $class = "btn btn-warning btn-xs btn-block";
                } else {

                    $class = "btn btn-danger btn-xs btn-block";
                }

                echo "
                            <tr id='remove_" . $row['record_no'] . "'>
                                <td>" . $no++ . ".</td>
                                <td><a href='../profile/" . $row['subordinates_rater'] . "' target='_blank'>" . ucwords(strtolower($row['name'])) . "</a></td>
                                <td>" . $row['position'] . "</td>
                                <td><label class='$class'>" . $row['current_status'] . "</label></td>
                                <td align='center'><i class='glyphicon glyphicon-trash text-red' title='remove subordinates' onclick=removeSubordinates('" . $row['record_no'] . "','subordinate')></i>
                            </tr>
                        ";
            }
            ?>
        </tbody>
    </table>
    <script type="text/javascript">
        $(function() {
            var dataTable = $("#dt_subordinates").DataTable({

                "order": [],
                "bLengthChange": false,
                "autoWidth": true,
                "paging": false,
                "scrollY": '46vh',
                "scrollCollapse": true,
                "columnDefs": [{
                    "targets": [0, 1, 2, 3, 4],
                    "orderable": false,
                }, ],
            });
        });
    </script>
<?php
} else if ($request == "remarks") {

    $sql = "SELECT remarks FROM remarks where emp_id = '$empId'";
    $row = $this->employee_model->return_row_array($sql);

?>
    <div class="modf">Remarks
        <input name="edit" id="edit-remarks" value="edit" class="btn btn-primary btn-sm" onclick="edit_remarks()" type="button">
        <input class="btn btn-primary btn-sm" id="update_remarks" value="update" style="display:none" onclick="update(this.id)" type="button">
    </div>
    <?php

    $checkifres = "SELECT * FROM `termination` WHERE emp_id = '$empId' order by date desc";
    $result = $this->employee_model->return_result_array($checkifres);
    if ($this->employee_model->return_num_rows($checkifres) > 0) {

        echo "<div class='alert alert-info' role='alert'>";

        foreach ($result as $rch) {

            echo "<i>" . $rch['remarks'] . " last " . date('M d, Y', strtotime($rch['date'])) . " added by " . $this->employee_model->employee_name($rch['added_by'])['name'] . " updated last " . date('M d, Y', strtotime($rch['date_updated'])) . ".</i><br>";
        }
        echo "</div>";
    }
    ?>
    <textarea name="remarks" rows="15" cols="150" class="form-control inputForm" id="remarks"><?php echo $row['remarks']; ?></textarea>
    <script type="text/javascript">
        $(".inputForm").prop("disabled", true);
    </script>
<?php
} else if ($request == "useraccount") {

    $sql = "SELECT user_no, username, usertype, user_status, login, date_created FROM users WHERE emp_id = '$empId'";
    $result = $this->employee_model->return_result_array($sql);
?>
    <div class="modf">User Account
        <input type="button" class="btn btn-sm btn-primary" <?php if ($this->employee_model->return_num_rows($sql) > 0) : echo "disabled=''";
                                                            endif; ?> value="add" onclick="addUserAccount()">
    </div>
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>UserNo.</th>
                <th>Username</th>
                <th>Usertype</th>
                <th>Status</th>
                <th>LogIn</th>
                <th>Date Created</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php

            foreach ($result as $row) {

                $trashImage = "";
                if ($row['user_status'] == "active") {

                    $userClass = "btn btn-success btn-xs btn-block btn-flat";
                } else {

                    $userClass = "btn btn-danger btn-xs btn-block btn-flat";
                }

                if ($row['user_status'] == "active") {

                    $iconImage = "<i class='glyphicon glyphicon-remove text-yellow' href='javascript:void(0);' title='click to deactivate account' onclick=userAction('$row[user_no]','deactivateAccount')></i>";
                } else {

                    $iconImage = "<i class='glyphicon glyphicon-ok text-green' href='javascript:void(0);' title='click to activate account' onclick=userAction('$row[user_no]','activateAccount')></i>";
                }

                if ($_SESSION['emp_id'] == "06359-2013") {
                    $trashImage = "<i class='glyphicon glyphicon-trash text-red' href='javascript:void(0);' title='click to delete account' onclick=userAction('$row[user_no]','deleteAccount')></i>";
                }

                echo "
                            <tr>
                                <td>" . $row['user_no'] . "</td>
                                <td>" . $row['username'] . "</td>
                                <td>" . $row['usertype'] . "</td>
                                <td><label class='$userClass'>" . $row['user_status'] . "</label></td>
                                <td>" . $row['login'] . "</td>
                                <td>" . date("M. d, Y", strtotime($row['date_created'])) . "</td>
                                <td>
                                    <i class='glyphicon glyphicon-repeat' href='javascript:void(0);' title='click to reset password' onclick=userAction('$row[user_no]','resetPass')></i>&nbsp;$iconImage&nbsp;$trashImage
                                </td>
                            </tr>
                        ";
            }
            ?>
        </tbody>
    </table>
<?php
}
