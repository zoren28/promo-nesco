<section class="content-header">
    <h1>
        Report
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Report</a></li>
        <li class="active">Username</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-8">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">Username</h3>
                </div>
                <div class="box-body">
                    <!-- <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Agency</label>
                                <select name="agency" class="form-control select2" onchange="select_company(this.value)">
                                    <option value=""> All Agency </option>
                                </select>
                            </div>
                        </div>
                    </div> -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Company</label>
                                <select name="company" class="form-control select2">
                                    <option value=""> All Company </option>
                                    <?php

                                    $companies = $this->employee_model->nesco_company_list();
                                    foreach ($companies as $company) {

                                        $supplier = $this->employee_model->getcompanyCodeBycompanyName($company->company_name);
                                        if (!empty($supplier)) {
                                    ?>
                                            <option value="<?= $supplier->pc_code ?>"><?= $company->company_name ?></option>
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
                            <div class="form-group">
                                <label>Business Unit</label>
                                <select name="business_unit" class="form-control" onchange="select_department(this.value)">
                                    <option value=""> All Business Unit </option>
                                    <?php

                                    $business_units = $this->dashboard_model->businessUnit_list();
                                    foreach ($business_units as $bu) {
                                    ?>
                                        <option value="<?= $bu->bunit_id . '/' . $bu->bunit_field ?>"><?= $bu->bunit_name ?></option>
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
                                <label>Department</label>
                                <select name="department" class="form-control">
                                    <option value=""> All Department </option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <?php
                    $link = "http://" . $_SERVER['SERVER_ADDR'] . ":" . $_SERVER['SERVER_PORT'] . "/hrms/promo-nesco/";
                    ?>
                    <button type="button" class="btn btn-primary" onclick="genUsernameReport()"> Generate in Excel <img src="<?= $link ?>../images/icons/excel-xls-icon.png"></button>
                </div>
            </div>
        </div>
    </div>
</section>