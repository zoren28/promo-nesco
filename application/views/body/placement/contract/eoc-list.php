<style>
    .preview {

        background-image: url("http://<?php echo $_SERVER['SERVER_ADDR'] . ':' . $_SERVER['SERVER_PORT']; ?>/hrms/promo-nesco/../images/images.png");
        background-size: contain;
        width: 700px;
        height: 500px;
        border: 2px solid #BBD9EE;
    }
</style>
<section class="content-header">
    <h1>
        End of Contract List
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Contract</a></li>
        <li class="active">End of Contract List</li>
    </ol>
</section>
<section class="content">

    <!-- Default box -->
    <div class="box box-primary">
        <div class="box-header with-border">
            <div class="table-responsive">
                <table align="center" width="98%">
                    <tbody>
                        <tr>
                            <td>
                                <label><i class="glyphicon glyphicon-filter"></i> Filter</label> &nbsp;
                                <input type="hidden" name="page" value="eoc-list">
                                <select name="filterBU">
                                    <option value="">All Business Unit</option>
                                    <?php
                                    $bUs = $this->dashboard_model->epas_businessUnit_list();
                                    foreach ($bUs as $bU) {
                                        echo '
                                        <option value="' . $bU->bunit_field . '">' . $bU->bunit_name . '</option>
                                    ';
                                    }
                                    ?>
                                </select>

                                <select name="filterDate" class="filter-date">
                                    <option value="">All</option>
                                    <option value="today">Today</option>
                                    <option value="yesterday">Yesterday</option>
                                    <option value="last7days">Last 7days</option>
                                    <option value="last1month">Last 1 month</option>
                                </select>

                                <select name="filterMonth" class="filter-month-year">
                                    <?php

                                    $months = $this->employee_model->months();
                                    foreach ($months as $key => $value) {

                                        if (date('m') == $key) {

                                            echo '
                                            <option value="' . $key . '" selected>' . $value . '</option>
                                        ';
                                        } else {

                                            echo '
                                                <option value="' . $key . '">' . $value . '</option>
                                            ';
                                        }
                                    }
                                    ?>
                                </select>

                                <select name="filterYear" class="filter-month-year">
                                    <?php
                                    $year = date('Y', strtotime('-1 year'));
                                    for ($i = 0; $i < 3; $i++) {

                                        if (date('Y') == $year) {

                                            echo '
                                            <option value="' . $year . '" selected>' . $year . '</option>
                                        ';
                                        } else {

                                            echo '
                                            <option value="' . $year . '">' . $year . '</option>
                                        ';
                                        }
                                        $year++;
                                    }
                                    ?>
                                </select> &nbsp;

                                <button class="btn btn-primary btn-sm" id="genEOCList">Filter</button> &nbsp;
                                <a href="javascript:void(0)" class="btn btn-primary btn-sm" onclick="genReport()"> Generate in Excel</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="box-body">

            <table id="eoc-list" class="table table-striped table-hover" style="width:100%">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Startdate</th>
                        <th>EOCdate</th>
                        <?php
                        foreach ($bUs as $bU) {
                            echo '
                                <th>' . $bU->bunit_acronym . '</th>
                            ';
                        }
                        ?>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->

</section>

<!-- ./Modal -->
<div id="appraisal_form" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light-blue color-palette">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Performance Appraisal For Promodiser/Merchandiser</h4>
            </div>
            <div class="modal-body">
                <div class="appraisal_form"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- ./Modal -->
<div id="uploadClearance" class="modal fade">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header bg-light-blue color-palette">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Upload Clearance</h4>
            </div>
            <form action="" id="dataUploadClearance" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="uploadClearance"></div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary">Upload</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>