<section class="content-header">
    <h1>
        Report
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Report</a></li>
        <li class="active">Termination of Contract</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-8">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">Termination Report</h3>
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
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group"> <i class="text-red">*</i>
                                <label>Month</label>
                                <select name="month" class="form-control">
                                    <option value=""> --Select Month-- </option>
                                    <?php

                                    $months = $this->employee_model->months();
                                    foreach ($months as $month => $value) {
                                    ?>
                                        <option value="<?= date('Y') . '-' . $month  ?>"><?= $value ?></option>
                                    <?php
                                    }
                                    ?>
                                    <option value="<?= date('Y', strtotime('+1 years')) . '-01' ?>">January <?= date('Y', strtotime('+1 years')) ?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <?php
                    $link = "http://" . $_SERVER['SERVER_ADDR'] . ":" . $_SERVER['SERVER_PORT'] . "/hrms/promo-nesco/";
                    ?>
                    <button type="button" class="btn btn-primary" onclick="genReport('excel')"> Generate in Excel <img src="<?= $link ?>../images/icons/excel-xls-icon.png"></button>
                    <button type="button" class="btn btn-primary" onclick="genReport('pdf')"> Generate in PDF <img src="<?= $link ?>../images/icons/pdf-icon.png"></button>
                    <button type="button" class="btn btn-primary" onclick="genReport('list')"> Generate List <img src='<?= $link ?>../images/icons/txt-icon.png' /></button>
                </div>
            </div>
        </div>
    </div>

    <!-- ./Modal -->
    <div id="termination" class="modal fade">
        <div class="modal-dialog" style="width: 90%;">
            <div class="modal-content">
                <div class="modal-header bg-light-blue color-palette">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">List of End Contract for <span class="termination_date"></span></h4>
                </div>
                <div class="modal-body">
                    <div class="termination"></div>
                </div>
                <div class="modal-footer">
                    <button class="employee btn btn-primary" onclick="termination()">Generate Termination of Contract</button>
                    <button class="company btn btn-primary" onclick="termination_for_company()">Generate Termination of Contract for Company</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</section>