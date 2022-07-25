<style type="text/css">
    .search-results {
        box-shadow: 5px 5px 5px #ccc;
        margin-top: 1px;
        margin-left: 0px;
        background-color: #F1F1F1;
        width: 92%;
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
        Setup
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Setup</a></li>
        <li class="active">Setup Subordinates</li>
    </ol>
</section>

<section class="content">

    <!-- Default box -->
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Subordinates Setup</h3>
                </div>

                <div class="box-body">

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Supervisor</label>
                                <div class="input-group">
                                    <input class="form-control" type="text" name="supervisor" autocomplete="off">
                                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                </div>
                                <div class="search-results" style="display: none;"></div>
                            </div>
                            <div class="supervisor-details"></div>
                        </div>
                        <div class="col-md-8">
                            <div id="loading-gif" style="text-align: center; display:none;">
                                <img src="<?= base_url('assets/images/gif/loader_seq.gif'); ?>" alt="gif">
                            </div>
                            <div class="subordinates"></div>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>

</section>
<div id="add-subordinates" class="modal fade">
    <div class="modal-dialog" style="width: 80%;">
        <div class="modal-content">
            <div class="modal-header bg-light-blue color-palette">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Select Subordinate(s)</h4>
            </div>
            <form id="add-subordinates">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <input type="hidden" name="rater">
                            <div class="form-group">
                                <label>Business Unit</label>
                                <select name="store" class="form-control">
                                    <option value=""> --Select-- </option>
                                    <?php

                                    $bUs = $this->dashboard_model->businessUnit_list();
                                    foreach ($bUs as $bu) {

                                        echo '
                                            <option value="' . $bu->bunit_id . '/' . $bu->bunit_field . '">' . $bu->bunit_name . '</option>
                                        ';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Department</label>
                                <select name="department" class="form-control" disabled>
                                    <option value=""> --Select-- </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div id="load-gif" style="text-align: center; display:none;">
                                <img src="<?= base_url('assets/images/gif/loader_seq.gif'); ?>" alt="gif">
                            </div>
                            <div class="employee-list"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-street-view"></i> Submit</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>