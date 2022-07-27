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
        Resignation/Termination
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Resignation/Termination</a></li>
        <li class="active">Add Resignation/Termination</li>
    </ol>
</section>

<section class="content">

    <!-- Default box -->
    <div class="row">
        <div class="col-md-7">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Add Resignation/Termination</h3>
                </div>
                <form id="data-rt" method="post" enctype="multipart/form-data" autocomplete="off">

                    <input type="hidden" name="clearanceName">
                    <div class="box-body">
                        <div class="form-group">
                            <label>Search Promo</label> <i class="text-red">*</i>
                            <div class="input-group">
                                <input class="form-control" type="text" name="employee" value="<?php if ($empId != "") : echo $empId . ' * ' . $emp_details->name;
                                                                                                endif; ?>">
                                <span class="input-group-addon"><i class="fa fa-male"></i></span>
                            </div>
                            <div class="search-results" style="display: none;"></div>
                        </div>
                        <div class="form-group">
                            <label>Date</label> <i class="text-red">*</i>
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" name="dateEffective" class="form-control datepicker rt-form" required disabled onchange="inputField(this.name)" value="<?php echo date('m/d/Y'); ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Remarks</label> <i class="text-red">*</i>
                            <textarea name="remarks" class="form-control rt-form" rows="3" required disabled onkeyup="inputField(this.name)"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Status</label> <i class="text-red">*</i>
                            <select name="rt_status" class="form-control rt-form" required disabled>
                                <option value=""> --Select-- </option>
                                <option value="End of Contract">End of Contract</option>
                                <option value="Resigned">Resigned</option>
                            </select>
                        </div>
                        <div class="uploadResignation"></div>
                    </div>
                    <div class="box-footer">
                        <div class="pull-right">
                            <input type="submit" class="btn btn-primary" value="Submit">
                        </div>
                    </div>
                </form>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>

</section>