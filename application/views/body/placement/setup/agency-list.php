<section class="content-header">
    <h1>
        Setup
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Setup</a></li>
        <li class="active">Agency List</li>
    </ol>
</section>
<section class="content">

    <!-- Default box -->
    <div class="box box-primary">
        <div class="box-header with-border">
            <div class="pull-right">
                <button type="button" id="add-agency" class="btn btn-primary btn-sm">
                    Add Agency
                </button>
            </div>
        </div>

        <div class="box-body">
            <table id="dt_agency" class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Agency Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->
</section>

<div id="show-agency" class="modal fade">
    <div class="modal-dialog" style="width: 37%;">
        <div class="modal-content">
            <div class="modal-header bg-light-blue color-palette">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Update Agency</h4>
            </div>
            <form id="update-agency">
                <div class="modal-body">
                    <div class="show-agency"></div>
                </div>
                <div class="modal-footer">
                    <span class="loadingSave"></span>
                    <button type="submit" class="btn btn-primary">Update</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div id="add-agency" class="modal fade">
    <div class="modal-dialog" style="width: 37%;">
        <div class="modal-content">
            <div class="modal-header bg-light-blue color-palette">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Add Agency</h4>
            </div>
            <form id="add-agency">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Agency</label>
                        <div class="input-group">
                            <input class="form-control" type="text" name="agency" style="text-transform:uppercase" autocomplete="off" onkeyup="inputField(this.name)">
                            <span class="input-group-addon"><i class="fa fa-bank"></i></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>