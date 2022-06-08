<style type="text/css">
    .witness1,
    .witness1Renewal {

        box-shadow: 5px 5px 5px #ccc;
        margin-top: 1px;
        margin-left: 0px;
        background-color: #F1F1F1;
        width: 94%;
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

    .witness2,
    .witness2Renewal {

        box-shadow: 5px 5px 5px #ccc;
        margin-top: 1px;
        margin-left: 0px;
        background-color: #F1F1F1;
        width: 94%;
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
<section class="content">

    <!-- Default box -->
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Renewal of Contract</h3>
                </div>
                <form id="dataProcessRenewal" method="post" enctype="multipart/form-data" autocomplete="off">
                    <div class="box-body">

                        <input type="hidden" name="empId" value="<?= $empId; ?>">
                        <input type="hidden" name="recordNo" value="<?= $emp_details->record_no; ?>">
                        <input type="hidden" name="edited" value="false">
                        <input type="hidden" class="renewContract" value="">

                        <h4><strong><?= "[$empId] " . ucwords(strtolower($emp_details->name)); ?></strong></h4>
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td width="26%"></td>
                                    <th width="37%">Previous Contract Details</th>
                                    <th width="37%">New Contract Details
                                        <a href="javascript:void(0)" onclick="edit_renew_control();" id="edit_new" class="btn btn-primary btn-sm pull-right">Edit</a>
                                        <a href="javascript:void(0)" style="display:none;" id="cancel_new" onclick="cancel_renew_control();" class="btn btn-danger btn-sm pull-right">Cancel</a>
                                    </th>
                                </tr>
                                <tr>
                                    <?php

                                    $agency_name = $this->employee_model->agency_name($emp_details->agency_code);
                                    ?>
                                    <td>Agency</td>
                                    <td><?= $agency_name ?></td>
                                    <td>
                                        <input name="agency" value="<?= $emp_details->agency_code ?>" type="hidden">
                                        <span class="inputLabel"><?= $agency_name ?></span>
                                        <select name="agency_select" class="form-control inputSelect" style="display:none;" onchange="select_agency(this.value)"></select>
                                    </td>
                                </tr>
                                <tr>
                                    <?php

                                    $com = $this->employee_model->getcompanyCodeBycompanyName($emp_details->promo_company);
                                    ?>
                                    <td>Company</td>
                                    <td><?= $emp_details->promo_company ?></td>
                                    <td>
                                        <input name="company" value="<?= $com->pc_code ?>" type="hidden">
                                        <input name="company_name" value="<?= $emp_details->promo_company ?>" type="hidden">
                                        <span class="inputLabel"><?= $emp_details->promo_company ?></span>
                                        <select name="company_select" class="form-control inputSelect" style="display:none;" onchange="select_product(this.value)"></select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Promo Type</td>
                                    <td><?= $emp_details->promo_type ?></td>
                                    <td>
                                        <input name="promoType" value="<?= $emp_details->promo_type ?>" type="hidden">
                                        <span class="inputLabel"><?= $emp_details->promo_type ?></span>
                                        <select name="promoType_select" class="form-control inputSelect" style="display:none;" onchange="select_business_unit(this.value)"></select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Business Unit</td>
                                    <td>
                                        <?php if ($emp_details->promo_type == "ROVING") { ?>
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th colspan="2"> Business Unit</th>
                                                </tr>
                                                <?php
                                                $bUs = $this->dashboard_model->businessUnit_list();
                                                foreach ($bUs as $bu) {

                                                    $hasBU = $this->dashboard_model->promo_has_bu($empId, $bu->bunit_field);
                                                    if ($hasBU > 0) {

                                                        echo '
                                                            <tr>
                                                                <td>
                                                                    <input type="hidden" name="business_units[]" value="' . $bu->bunit_id . '/' . $bu->bunit_field . '">
                                                                    <input type="checkbox" name="' . $bu->bunit_field . '" disabled checked>
                                                                </td>
                                                                <td>' . $bu->bunit_name . '</td>
                                                            </tr>
                                                        ';
                                                    } else {

                                                        echo '
                                                            <tr>
                                                                <td>
                                                                    <input type="checkbox" name="' . $bu->bunit_field . '" disabled>
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

                                                    $hasBU = $this->dashboard_model->promo_has_bu($empId, $bu->bunit_field);
                                                    if ($hasBU > 0) {

                                                        echo '
                                                            <tr>
                                                                <td>
                                                                    <input type="hidden" name="business_units[]" value="' . $bu->bunit_id . '/' . $bu->bunit_field . '">
                                                                    <input type="radio" name="stations" disabled checked>
                                                                </td>
                                                                <td>' . $bu->bunit_name . '</td>
                                                            </tr>
                                                        ';
                                                    } else {

                                                        echo '
                                                            <tr>
                                                                <td>
                                                                    <input type="radio" name="stations" disabled>
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
                                        ?>
                                    </td>
                                    <td>
                                        <div class="store">
                                            <?php if ($emp_details->promo_type == "ROVING") { ?>
                                                <table class="table table-bordered">
                                                    <tr>
                                                        <th colspan="2"> Business Unit</th>
                                                    </tr>
                                                    <?php

                                                    $bUs = $this->dashboard_model->businessUnit_list();
                                                    foreach ($bUs as $bu) {

                                                        $hasBU = $this->dashboard_model->promo_has_bu($empId, $bu->bunit_field);
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

                                                        $hasBU = $this->dashboard_model->promo_has_bu($empId, $bu->bunit_field);
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
                                            <?php } ?>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Department</td>
                                    <td><?= $emp_details->promo_department ?></td>
                                    <td>
                                        <input name="department" value="<?= $emp_details->promo_department ?>" type="hidden">
                                        <span class="inputLabel"><?= $emp_details->promo_department ?></span>
                                        <select name="department_select" class="form-control inputSelect" style="display:none;" onchange="vendor_list(this.value)"></select>
                                    </td>
                                </tr>
                                <tr>
                                    <?php

                                    $vendor_code = '';
                                    $vendor_name = '';
                                    if (!empty($emp_details->vendor_code)) {
                                        $vendor_code = $emp_details->vendor_code;
                                        $vendor_name = $this->employee_model->vendor_name($emp_details->vendor_code);
                                    }
                                    ?>
                                    <td>Vendor Name</td>
                                    <td><?= $vendor_name; ?></td>
                                    <td>
                                        <input name="vendor" value="<?= $vendor_code ?>" type="hidden">
                                        <span class="inputLabel"><?= $vendor_name; ?></span>
                                        <select name="vendor_select" class="form-control inputSelect select2" style="display:none;"></select>
                                    </td>
                                </tr>
                                <tr>
                                    <?php

                                    $emp_products = "";
                                    $products = "";
                                    $x = 0;
                                    $promo_products = $this->employee_model->promo_products($emp_details->record_no, $empId);
                                    foreach ($promo_products as $prod) {

                                        if ($x == 0) {
                                            $emp_products = $prod->product;
                                            $products = $prod->product;
                                        } else {

                                            $emp_products .= "|" . $prod->product;
                                            $products .= ", " . $prod->product;
                                        }
                                        $x++;
                                    }
                                    ?>
                                    <td>Product</td>
                                    <td><?= $products; ?></td>
                                    <td>
                                        <input name="product" value="<?= $emp_products; ?>" type="hidden">
                                        <span class="inputLabel"><?= $products; ?></span>
                                        <div class="inputSelect product_select" style="display:none;"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Position</td>
                                    <td><?= $emp_details->position ?></td>
                                    <td>
                                        <input name="position" value="<?= $emp_details->position ?>" type="hidden">
                                        <span class="inputLabel"><?= $emp_details->position ?></span>
                                        <select name="position_select" class="form-control inputSelect" style="display:none;" onchange="position_level(this.value)"></select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Position Level</td>
                                    <td><?= $emp_details->poslevel ?></td>
                                    <td>
                                        <input name="positionlevel" value="<?= $emp_details->poslevel ?>" type="hidden">
                                        <span class="inputLabel"><?= $emp_details->poslevel ?></span>
                                        <input type="text" name="positionlevel_select" readonly style="display:none;">
                                        <input type="text" name="level" class="form-control inputSelect" readonly="" style="display:none;">
                                    </td>
                                </tr>
                                <tr>
                                    <td>Employee Type</td>
                                    <td><?= $emp_details->emp_type ?></td>
                                    <td>
                                        <input name="empType" value="<?= $emp_details->emp_type ?>" type="hidden">
                                        <select name="empType_select" class="form-control">
                                            <?php
                                            $emp_types = array('Promo-NESCO', 'Promo');
                                            foreach ($emp_types as $key => $value) {

                                                if ($value == $emp_details->emp_type) {

                                                    echo '<option value="' . $value . '" selected>' . $value . '</option>';
                                                } else {

                                                    echo '<option value="' . $value . '">' . $value . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Contract Type</td>
                                    <td><?= $emp_details->type ?></td>
                                    <td>
                                        <input name="contractType" value="<?= $emp_details->type ?>" type="hidden">
                                        <select name="contractType_select" class="form-control inputSelect" onchange="input_company_duration(this.value)">
                                            <?php

                                            $contract_types = array('Contractual', 'Seasonal');
                                            foreach ($contract_types as $key => $value) {

                                                if ($value == $emp_details->type) {

                                                    echo '<option value="' . $value . '" selected>' . $value . '</option>';
                                                } else {

                                                    echo '<option value="' . $value . '">' . $value . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr class="companyDuration" <?php if ($emp_details->type == "Seasonal" || $emp_details->promo_department == "HOME AND FASHION" || $emp_details->promo_department == "FIXRITE" || $emp_details->promo_department == "EASY FIX") echo "";
                                                            else echo "style = 'display:none;'";
                                                            ?>>
                                    <td><i class="text-red">*</i> Duration from Company</td>
                                    <td><?php if ($emp_details->company_duration == '0000-00-00' || $emp_details->company_duration == '') : echo "";
                                        else : echo date("M. d, Y", strtotime($emp_details->company_duration));
                                        endif; ?>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <input type="text" name="companyDuration" class="form-control datepicker" placeholder="mm/dd/yyyy" data-inputmask="'alias': 'mm/dd/yyyy'" data-mask="" onchange="inputField(this.name)">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th colspan="3">INCLUSIVE DATES OF CONTRACT</th>
                                </tr>
                                <tr>
                                    <td><i class="text-red">*</i> Startdate</td>
                                    <td><?= date("M. d, Y", strtotime($emp_details->startdate)); ?></td>
                                    <td>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <input type="text" name="startdate" class="form-control datepicker" placeholder="mm/dd/yyyy" data-inputmask="'alias': 'mm/dd/yyyy'" data-mask="" required onchange="inputStartdate()">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><i class="text-red">*</i> EOCdate</td>
                                    <td><?= date("M. d, Y", strtotime($emp_details->eocdate)); ?></td>
                                    <td>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <input type="text" name="eocdate" class="form-control datepicker" placeholder="mm/dd/yyyy" data-inputmask="'alias': 'mm/dd/yyyy'" data-mask="" required onchange="durationContract(this.value)">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>No. of Month(s) to Work</td>
                                    <td><?= $emp_details->duration; ?></td>
                                    <td>
                                        <input type="hidden" name="duration" class="duration">
                                        <input type="text" class="form-control duration" disabled="">
                                    </td>
                                </tr>
                                <tr>
                                    <th colspan="3">CUT-OFF DETAILS</th>
                                </tr>
                                <tr>
                                    <?php

                                    $sc = $this->employee_model->promo_cutoff($emp_details->record_no, $empId);
                                    $co = $this->employee_model->cutoff_details($sc->statCut);
                                    $endFC = ($co->endFC != '') ? $co->endFC : 'last';
                                    if ($co->startFC != '') {

                                        $cut_off = $co->startFC . '-' . $endFC . ' / ' . $co->startSC . '-' . $co->endSC;
                                    } else {

                                        $cut_off = '';
                                    }
                                    ?>
                                    <td><span class="text-red">*</span> </span>Cut-off</td>
                                    <td><?= $cut_off; ?></td>
                                    <td>
                                        <input name="cutoff" value="<?= $sc->statCut ?>" type="hidden">
                                        <span class="inputLabel"><?= $cut_off ?></span>
                                        <select name="cutoff_select" class="form-control inputSelect" style="display:none;"></select>
                                    </td>
                                </tr>
                                <tr>
                                    <th colspan="3">INTRO &nbsp;<small class="text-red">Allowed File : jpg, jpeg, png only</small></th>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <table class="table table-bordered" style="margin-bottom:0px;">
                                            <tbody id="promoIntro">
                                                <?php

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

                                                ?>
                                            </tbody>
                                        </table>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <th colspan="3">SIGNED IN THE PRESENCE OF</th>
                                </tr>
                                <tr>
                                    <td colspan="3">

                                        <div class="emp_details">
                                            <div class="col-md-6">
                                                <label>Witness 1</label>
                                                <input type="text" name="witness1" class="form-control" placeholder="Firstname Lastname" onkeyup="search_witness('witness1', this.value)" style="text-transform: uppercase;" autocomplete="off">
                                                <div class="witness1" style="display: none;"></div>
                                            </div>
                                            <div class="col-md-6">
                                                <label>Witness 2</label>
                                                <input type="text" name="witness2" class="form-control" placeholder="Firstname Lastname" onkeyup="search_witness('witness2', this.value)" style="text-transform: uppercase;" autocomplete="off">
                                                <div class="witness2" style="display: none;"></div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th colspan="3">COMMENTS/REMARKS</th>
                                </tr>
                                <tr>
                                    <td colspan="3">

                                        <div class="emp_details">
                                            <div class="col-md-6">
                                                <label>Comments</label>
                                                <textarea name="comments" class="form-control"></textarea>
                                            </div>
                                            <div class="col-md-6">
                                                <label>Remarks</label>
                                                <textarea name="remarks" class="form-control"></textarea>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="box-footer">
                        <div class="pull-right">
                            <button class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>

</section>

<!-- ./Modal -->
<div id="printContractAndPermit" class="modal fade">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header bg-light-blue color-palette">
                <button type="button" class="close close-event" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Employment</h4>
            </div>
            <div class="modal-body">
                <div class="printContractAndPermit"></div>
            </div>
            <div class="modal-footer">
                <span class="loadingSave"></span>
                <button type="button" class="btn btn-default close-event">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div id="printPermit" class="modal fade">
    <div class="modal-dialog" style="width: 50%">
        <div class="modal-content">
            <div class="modal-header bg-light-blue color-palette">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Print Permit</h4>
            </div>
            <form id="generate_permit">
                <div class="modal-body">
                    <div class="printPermit"></div>
                </div>
                <div class="modal-footer">
                    <span class="loadingSave"></span>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-file-pdf-o"></i> &nbsp;Generate Permit</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div id="printContract" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light-blue color-palette">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Edit & Generate Contract</h4>
            </div>
            <form id="generate_contract">
                <div class="modal-body">
                    <div class="printContract"></div>
                </div>
                <div class="modal-footer">
                    <span class="loadingSave"></span>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-file-pdf-o"></i> &nbsp;Generate Contract</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>