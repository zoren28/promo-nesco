<?php

$name = '';
$name_withId = '';
if ($empId != "") {

    $name_withId = "$empId * " . $this->employee_model->employee_name($empId)['name'];
    $name = $this->employee_model->employee_name($empId)['name'];
}
?>
<style type="text/css">
    .search-results {

        box-shadow: 5px 5px 5px #ccc;
        margin-top: 1px;
        margin-left: 0px;
        background-color: #F1F1F1;
        width: 83%;
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

<section class="content-header">
    <h1>
        Blacklisted
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Blacklisted</a></li>
        <li class="active">Add New Entry</li>
    </ol>
</section>

<section class="content">
    <!-- Default box -->
    <div class="row">
        <div class="col-md-8">
            <form id="blacklist_form" action="" method="post">

                <input type="hidden" name="appName" value="<?php echo $name; ?>">
                <input type="hidden" name="appId" value="<?php echo $empId; ?>">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Add New Blacklist Entry</h3>
                    </div>

                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Employee</label> <i class="text-red">*</i>
                                    <div class="input-group">
                                        <input class="form-control" type="text" name="employee" value="<?php echo $name_withId; ?>" disabled="">
                                        <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-primary" id="browse">Browse</button>
                                    <i>Click Browse</i>
                                </div>
                                <div class="form-group">
                                    <label>Reason</label> <i class="text-red">*</i>
                                    <textarea name="reason" class="form-control inputForm" rows="4" disabled="" required=""></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Date Blacklisted</label> <i class="text-red">*</i>
                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" name="dateBlacklisted" class="form-control inputForm pull-right datepicker" data-inputmask='"mask": "99/99/9999"' data-mask disabled="" required="">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Birthday</label>
                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" name="birthday" class="form-control inputForm pull-right datepicker" data-inputmask='"mask": "99/99/9999"' data-mask disabled="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Reported By</label> <i class="text-red">*</i>
                                    <div class="input-group">
                                        <input class="form-control inputForm" type="text" name="reportedBy" autocomplete="off" disabled="" onkeyup="nameSearch(this.value)" required="">
                                        <span class="input-group-addon"><i class="fa fa-user-secret"></i> </span>
                                    </div>
                                    <div class="search-results" style="display: none;"></div>
                                </div>
                                <div class="form-group">
                                    <label>Address</label>
                                    <input type="text" class="form-control inputForm" name="address" disabled="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <div class="pull-right">
                            <button type="submit" class="btn btn-primary button_opt" disabled=""><i class="fa fa-hand-pointer-o"></i> Submit</button>
                            <button type="button" id="reset_fields" class="btn btn-default button_opt" disabled=""> Cancel</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Modal -->
<div class="modal fade" id="browse_employee">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light-blue color-palette">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Add Blacklisted Employee</h4>
            </div>
            <div class="modal-body browse_employee">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>