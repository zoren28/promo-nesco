<section class="content-header">
    <h1>
        <?php echo ucwords(strtolower($title)); ?>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#"><?php echo ucwords(strtolower($title)); ?></a></li>
        <li class="active"><?php echo ucwords(strtolower($page)); ?></li>
    </ol>
</section>
<section class="content">

    <div class="box box-primary">
        <div class="box-header with-border">
            <button id="filter" class="btn btn-sm btn-primary"><i class="fa fa-filter"></i> Show Filter</button>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-md-1"><label readonly=""><span>LEGEND :</span></label></span>
                </div>
                <div class="col-md-11">
                    <?php
                    foreach ($activeBUs as $bu) { ?>

                        <span style="margin-right:25px;"><label readonly=""><span class="text-red"><?= $bu->bunit_acronym ?></span><span> - <?= $bu->bunit_name ?></span></label></span>
                    <?php
                    } ?>
                </div>
            </div>
            <br />
            <table id="employee_masterfile_table" class="table table-striped table-hover" width="100%">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Company</th>
                        <th>BusinessUnit</th>
                        <th>Department</th>
                        <th>Position</th>
                        <th>EmpType</th>
                        <th>Status</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</section>

<!-- Modal -->
<div class="modal fade" id="filter_employee">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form id="filter_employee" action="" method="post">
                <div class="modal-header bg-light-blue color-palette">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Filter Employee</h4>
                </div>
                <div class="modal-body filter_employee">
                    <?php

                    $filter = explode('|_', base64_decode($filters));
                    $bunit_id = [];
                    $bunit_value = '';
                    $dept_value = '';
                    $promo_value = '';

                    if (!empty($filter[1])) {
                        $bunit_id = explode('/', $filter[1]);
                        $bunit_value = $filter[1];
                    }

                    if (!empty($filter[2])) {
                        $dept_value = $filter[2];
                    }

                    if (!empty($filter[3])) {
                        $promo_value = $filter[3];
                    }

                    if ($filters != '') {
                    ?>
                        <div class="form-group">
                            <label>Company</label>
                            <select name="company" class="form-control select2" style="width: 100%;">
                                <option value=""> </option>
                                <?php
                                $companies = $this->employee_model->company_list();
                                foreach ($companies as $company) {
                                ?>
                                    <option value="<?= $company->pc_code ?>" <?php if ($company->pc_code == $filter[0]) {
                                                                                    echo 'selected';
                                                                                } ?>><?= $company->pc_name ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Business Unit</label>
                            <select name="business_unit" class="form-control" onchange="fetchAssignedDept(this.value)">
                                <option value=""> Select Business Unit </option>
                                <?php
                                $BUs = $this->dashboard_model->businessUnit_list();
                                foreach ($BUs as $bu) {
                                ?>
                                    <option value="<?= $bu->bunit_id . '/' . $bu->bunit_field ?>" <?php if ($bu->bunit_id . '/' . $bu->bunit_field == $bunit_value) {
                                                                                                        echo 'selected';
                                                                                                    } ?>><?= $bu->bunit_name ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Department</label>
                            <select name="department" id="" class="form-control">
                                <option value=""> Select Department </option>
                                <?php
                                $departments = $this->employee_model->assigned_departments($bunit_id[0]);
                                foreach ($departments as $dept) {
                                ?>
                                    <option value="<?= $dept->dept_name ?>" <?php if ($dept->dept_name == $dept_value) {
                                                                                echo 'selected';
                                                                            } ?>><?= $dept->dept_name ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Promo Type</label>
                            <select name="promo_type" class="form-control">
                                <option value=""> Select Promo Type </option>
                                <option value="STATION" <?php if ($promo_value == 'STATION') {
                                                            echo 'selected';
                                                        } ?>> STATION </option>
                                <option value="ROVING" <?php if ($promo_value == 'ROVING') {
                                                            echo 'selected';
                                                        } ?>> ROVING </option>
                            </select>
                        </div>
                    <?php
                    } else {
                    ?>
                        <div class="form-group">
                            <label>Company</label>
                            <select name="company" class="form-control select2" style="width: 100%;">
                                <option value=""> </option>
                                <?php
                                $companies = $this->employee_model->company_list();
                                foreach ($companies as $company) {

                                    echo '<option value="' . $company->pc_code . '">' . $company->pc_name . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Business Unit</label>
                            <select name="business_unit" class="form-control" onchange="fetchAssignedDept(this.value)">
                                <option value=""> Select Business Unit </option>
                                <?php
                                $BUs = $this->dashboard_model->businessUnit_list();
                                foreach ($BUs as $bu) {

                                    echo '<option value="' . $bu->bunit_id . '/' . $bu->bunit_field . '">' . $bu->bunit_name . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Department</label>
                            <select name="department" id="" class="form-control">
                                <option value=""> Select Department </option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Promo Type</label>
                            <select name="promo_type" class="form-control">
                                <option value=""> Select Promo Type </option>
                                <option value="STATION"> STATION </option>
                                <option value="ROVING"> ROVING </option>
                            </select>
                        </div>
                    <?php
                    }
                    ?>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-filter"></i> Filter</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>