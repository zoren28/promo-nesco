<section class="content-header">
    <h1>
        Setup
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Setup</a></li>
        <li class="active">Company List</li>
    </ol>
</section>
<section class="content">

    <!-- Default box -->
    <div class="box box-primary">
        <div class="box-header with-border">
            <div class="pull-right">
                <button type="button" id="add-company" class="btn btn-primary btn-sm">
                    Add Company
                </button>
            </div>
        </div>
        <div class="box-body">
            <table id="dt-company" class="table table-striped table-hover" width="100%">
                <thead>
                    <tr>
                        <th>Company Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->

</section>

<div id="show-company" class="modal fade">
    <div class="modal-dialog" style="width: 37%;">
        <div class="modal-content">
            <div class="modal-header bg-light-blue color-palette">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Update Company</h4>
            </div>
            <form id="update-company" autocomplete="off">
                <div class="modal-body">
                    <div class="show-company"></div>
                </div>
                <div class="modal-footer">
                    <span class="loadingSave"></span>
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div id="add-company" class="modal fade">
    <div class="modal-dialog" style="width: 37%;">
        <div class="modal-content">
            <div class="modal-header bg-light-blue color-palette">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Add Company</h4>
            </div>
            <form id="add-company">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Company</label>
                        <div class="input-group">
                            <input class="form-control" type="text" name="company" style="text-transform:uppercase" autocomplete="off" onkeyup="inputField(this.name)">
                            <span class="input-group-addon"><i class="fa fa-bank"></i></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <span class="loadingSave"></span>
                    <button type="submit" class="btn btn-primary add_company">Submit</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>