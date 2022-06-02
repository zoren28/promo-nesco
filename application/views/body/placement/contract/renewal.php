<style type="text/css">
    .search-results {

        box-shadow: 5px 5px 5px #ccc;
        margin-top: 1px;
        margin-left: 0px;
        background-color: #F1F1F1;
        width: 89%;
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

    .size-emp {

        max-height: 450px;
        overflow: auto;
    }
</style>

<section class="content-header">
    <h1>
        Contract
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Contract</a></li>
        <li class="active">Renewal</li>
    </ol>
</section>

<section class="content">

    <!-- Default box -->
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-body">

                    <p style='font-size:20px'>Online</p>
                    <p><button class='btn btn-primary extend-contract'>Extend of Contract</button></p>
                    <code>Extending of Contract</code> for all employee.
                    <hr>

                    <p><a href="<?= base_url('placement/page/menu/contract/eoc-list') ?>" class='btn btn-primary'>Online Process of EOC</a></p>
                    Listing the <code>End of Contract Employees</code> one month from today and one month after today. Proceed with the process of End of Contract.

                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>

</section>

<!-- ./Modal -->
<div id="extend" class="modal fade">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header bg-light-blue color-palette">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Renew Employee</h4>
            </div>
            <div class="modal-body">
                <div class="extend"></div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary proceed-extend">Proceed</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>