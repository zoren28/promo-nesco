<style type="text/css">
    .search-results {

        box-shadow: 5px 5px 5px #ccc;
        margin-top: 1px;
        margin-left: 0px;
        background-color: #F1F1F1;
        width: 85%;
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
        <li class="active">View Employee</li>
    </ol>
</section>

<section class="content">

    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Blacklisted Employees</h3>
        </div>
        <div class="box-body">
            <table id="blacklists" class="table table-striped table-hover" width="100%">
                <thead>
                    <tr>
                        <th>Emp.ID</th>
                        <th>Name</th>
                        <th>ReportedBy</th>
                        <th>BlacklistDate</th>
                        <th>Reason</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</section>

<!-- Modal -->
<div class="modal fade" id="update_blacklist_form">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="update_blacklist" action="" action="post">
                <div class="modal-header bg-light-blue color-palette">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Edit Blacklisted Employee</h4>
                </div>
                <div class="modal-body update_blacklist_form">

                </div>
                <div class="modal-footer">
                    <button type="submit" id="update_blacklisted" class="btn btn-primary"><i class="fa fa-hand-pointer-o"></i> Update</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>