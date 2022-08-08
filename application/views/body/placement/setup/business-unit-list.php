<style>
    table.dataTable tbody tr.selected,
    table.dataTable tbody th.selected,
    table.dataTable tbody td.selected {
        color: #333;
    }
</style>
<section class="content-header">
    <h1>
        Setup
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Setup</a></li>
        <li class="active">Business Unit List</li>
    </ol>
</section>
<section class="content">

    <!-- Default box -->
    <div class="box box-primary">
        <div class="box-header with-border">
            <div class="pull-right">
                <button type="button" id="add-business-unit" class="btn btn-primary btn-sm">
                    Add Business Unit
                </button>
            </div>
        </div>

        <div class="box-body">
            <table id="dt-business-unit" class="table table-striped table-hover" width="100%">
                <thead>
                    <tr>
                        <th>Business Unit</th>
                        <th>Acronym</th>
                        <th>Column Field</th>
                        <th>Status</th>
                        <th>TK Status</th>
                        <th>Appraisal Status</th>
                        <th>Location</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->
</section>

<div id="add-business-unit" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light-blue color-palette">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Add Business Unit</h4>
            </div>
            <form id="add-business-unit" autocomplete="off">
                <div class="modal-body">
                    <div class="add-business-unit"></div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="add-btn" class="btn btn-primary">Submit</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div id="show-business-unit" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light-blue color-palette">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Update Business Unit</h4>
            </div>
            <form id="update-business-unit" autocomplete="off">
                <div class="modal-body">
                    <div class="show-business-unit"></div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="update-btn" class="btn btn-primary">Update</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>