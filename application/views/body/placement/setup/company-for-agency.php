<section class="content-header">
    <h1>
        Setup
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Setup</a></li>
        <li class="active">Company under Agency</li>
    </ol>
</section>
<section class="content">

    <!-- Default box -->
    <div class="box box-primary">
        <div class="box-header with-border">
            <div class="pull-right">
                <button type="button" id="setup_under_agency" class="btn btn-primary btn-sm">
                    Setup Company under Agency
                </button>
            </div>
        </div>

        <div class="box-body">
            <table id="dt-company-agency" class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Agency</th>
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

<div class="modal fade" id="setup_company_under_agency">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light-blue color-palette">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Setup Company Under Agency</h4>
            </div>
            <form id="company-under-agency">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 agencies"></div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12 companies"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary submit_company">Submit</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>