<section class="content-header">
    <h1>
        Report
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Report</a></li>
        <li class="active">Query by Example</li>
    </ol>
</section>

<section class="content">
    <form action="<?php echo base_url('report/qbe_report') ?>" method="get" autocomplete="off" target="_blank">
        <div class="row">
            <div class="col-md-4">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Field Names</h3>
                    </div>
                    <div class="box-body">
                        <p><i><span class="text-red">Note:</span> Please click on the <code>checkbox beside the fieldnames</code> atleast one or as many as you can. (These fieldnames will be displayed in the report.)</i></p>
                        <hr>
                        <?php

                        $fields = $this->employee_model->field_names();
                        foreach ($fields as $field => $value) {
                        ?>
                            <input type="checkbox" name="fields[]" id="<?= $field ?>" value="<?= $field ?>" class="fields">
                            <span class="<?= $field ?>"><?= $value ?></span><br>
                        <?php
                        }
                        ?>
                        <input type="checkbox" name="agecb" id="age" class="fields" value="age"> <span class="age">Age</span><br>
                        <hr>
                        <p><i><span class="text-red">Take Note: </span>Employee ID, Firstname, Middlename, Lastname, Company, Business Unit, Department, Promo Type, Contract Type and Duration from Company are default column names.</i></p>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Query</h3>
                    </div>

                    <div class="box-body">
                        <p><i><span class="text-red">Note:</span> You are only allowed to select <code>five (5) conditions.</code>Once reached to 5 selection, unchecked the checkbox and select another one.</i></p>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <?php

                                $weights = $this->employee_model->select_all('weight');
                                $heights = $this->employee_model->select_all('height');
                                $religions = $this->employee_model->select_all('religion');
                                $attainments = $this->employee_model->select_all('attainment');
                                $schools = $this->employee_model->select_all('school');
                                $courses = $this->employee_model->select_all('course');
                                $bloodtypes = array('A', 'A+', 'A-', 'B', 'B+', 'B-', 'O', 'O+', 'O-', 'AB', 'AB+', 'AB-');
                                $civil_statuses = array('Single', 'Married', 'Widowed', 'Separated', 'Anulled', 'Divorced');

                                ?>
                                <h4>Conditions</h4>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <input type="checkbox" name="condition[]" id="namec" class="condition" value="name"> <span class="namec">Name</span> <i>(Lastname, Firstname or Lastname only or Firstname only)</i>
                                            <input type="text" name="nametf" class="form-control tf_namec" disabled required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <input type="checkbox" name="condition[]" id="home_addressc" class="condition" value="home_address"> <span class="home_addressc">Home Address</span>
                                            <input type="text" name="home_addresstf" class="form-control tf_home_addressc" disabled required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <input type="checkbox" name="condition[]" id="genderc" class="condition" value="gender"> <span class="genderc">Gender</span>
                                            <select name="gendertf" class="form-control tf_genderc" disabled required>
                                                <option value=""></option>
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <input type="checkbox" name="condition[]" id="religionc" class="condition" value="religion"> <span class="religionc">Religion</span>
                                            <input list="religions" type="text" name="religiontf" class="form-control tf_religionc" disabled required>
                                            <datalist id="religions">
                                                <?php

                                                foreach ($religions as $religion) {

                                                    echo '<option value="' . $religion->religion . '">';
                                                }
                                                ?>
                                            </datalist>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <input type="checkbox" name="condition[]" id="civilstatusc" class="condition" value="civilstatus"> <span class="civilstatusc">Civil Status</span>
                                            <select name="civilstatustf" class="form-control tf_civilstatusc" disabled required>
                                                <option value=""></option>
                                                <?php

                                                foreach ($civil_statuses as $key => $value) {

                                                    echo '<option value="' . $value . '">' . $value . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <input type="checkbox" name="condition[]" id="schoolc" class="condition" value="school"> <span class="schoolc">School</span>
                                            <input list="schools" type="text" name="schooltf" class="form-control tf_schoolc" required disabled>
                                            <datalist id="schools">
                                                <?php
                                                print_r($schools);
                                                foreach ($schools as $school) {
                                                    echo '<option value="' . $school->school_name . '">';
                                                }
                                                ?>
                                            </datalist>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <input type="checkbox" name="condition[]" id="attainmentc" class="condition" value="attainment"> <span class="attainmentc">Attainment</span>
                                            <input list="attainments" type="text" name="attainmenttf" class="form-control tf_attainmentc" required disabled>
                                            <datalist id="attainments">
                                                <?php

                                                foreach ($attainments as $attainment) {
                                                    echo '<option value="' . $attainment->attainment . '">';
                                                }
                                                ?>
                                            </datalist>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <input type="checkbox" name="condition[]" id="coursec" class="condition" value="course"> <span class="coursec">Course</span>
                                            <input list="courses" type="text" name="coursetf" class="form-control tf_coursec" disabled required>
                                            <datalist id="courses">
                                                <?php

                                                foreach ($courses as $course) {
                                                    echo '<option value="' . $course->course_name . '">';
                                                }
                                                ?>
                                            </datalist>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <input type="checkbox" name="condition[]" id="heightc" class="condition" value="height"> <span class="heightc">Height</span>
                                            <input list="heights" type="text" name="heighttf" class="form-control tf_heightc" disabled required>
                                            <datalist id="heights">
                                                <?php

                                                foreach ($heights as $height) {
                                                    echo '<option value="' . $height->feet . ' / ' . $height->cm . '">';
                                                }
                                                ?>
                                            </datalist>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <input type="checkbox" name="condition[]" id="weightc" class="condition" value="weight"> <span class="weightc">Weight</span>
                                            <input list="weights" type="text" name="weighttf" class="form-control tf_weightc" disabled required>
                                            <datalist id="weights">
                                                <?php

                                                foreach ($weights as $weight) {
                                                    echo '<option value="' . $weight->kilogram . ' / ' . $weight->pounds . '">';
                                                }
                                                ?>
                                            </datalist>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <input type="checkbox" name="condition[]" id="bloodtypec" class="condition" value="bloodtype"> <span class="bloodtypec">Blood Type</span>
                                            <select name="bloodtypetf" class="form-control tf_bloodtypec" disabled required>
                                                <option value=""></option>
                                                <?php

                                                foreach ($bloodtypes as $key => $value) {
                                                    echo '<option value="' . $value . '">' . $value . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h4>Filter</h4>
                                <!-- <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Agency</label>
                                        <select name="agency" class="form-control select2" onchange="select_company(this.value)">
                                            <option value=""> --Select Agency-- </option>
                                        </select>
                                    </div>
                                </div>
                            </div> -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Company</label>
                                            <select name="company" class="form-control select2" onchange="select_product(this.value)">
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
                                        <div class="form-group">
                                            <label>Product</label>
                                            <select name="product" class="form-control">
                                                <option value=""> All Product</option>
                                                <?php

                                                $products = $this->employee_model->promo_product_list();
                                                foreach ($products as $product) {
                                                    echo '<option value="' . $product->product . '">' . $product->product . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="promo_type">Promo Type</label>
                                            <select name="promo_type" id="promo_type" class="form-control">
                                                <option value=""> All Promo Type </option>
                                                <option value="STATION">Station</option>
                                                <option value="ROVING">Roving</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group"> <i class="text-red">*</i>
                                            <label for="current_status">Current Status</label>
                                            <select name="current_status" id="current_status" class="form-control" required>
                                                <option value=""> --Select Current Status-- </option>
                                                <?php

                                                $statuses = array('Active', 'End of Contract', 'Resigned', 'blacklisted');
                                                foreach ($statuses as $key => $value) {

                                                    echo '<option value="' . $value . '">' . $value . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group"> <i class="text-red">*</i>
                                            <label for="date_as_of">Date as of</label>
                                            <input type="text" name="date_asof" id="date_as_of" class="form-control datepicker" placeholder="mm/dd/yyyy" required="">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group"> <i class="text-red">*</i>
                                            <label for="report_title">Report Title</label>
                                            <input type="text" name="report_title" id="report_title" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group"> <i class="text-red">*</i>
                                            <label for="filename">Filename</label>
                                            <input type="text" name="filename" id="filename" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <input type="submit" name="submit" class="btn btn-primary btn-block" value="Submit" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</section>