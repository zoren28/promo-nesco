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

        max-height: 335px;
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
        <li class="active">Print Permit</li>
    </ol>
</section>

<section class="content">

    <!-- Default box -->
    <div class="row">
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Generate Permit</h3>
                </div>

                <div class="box-body">
                    <input type="hidden" name="permit" value="current">
                    <p><strong> Current Permit </strong></p>
                    <p><button class='btn btn-primary btn-sm current-permit'> Print Permit </button></p>
                    <i> Allows printing of permit of current contract. </i>
                    <hr>

                    <p><strong> Previous Permit </strong></p>
                    <p><button class='btn btn-primary btn-sm previous-permit'> Print Permit </button></td>
                    </p>
                    <i> Allows printing of permit from previous contract. </i>
                    <hr>
                </div>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
    </div>

</section>

<div id="current-permit" class="modal fade">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header bg-light-blue color-palette">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Print Permit</h4>
            </div>
            <div class="modal-body">
                <div class="current-permit"></div>
            </div>
            <div class="modal-footer">
                <span class="loadingSave"></span>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div id="current-permit" class="modal fade">
    <div class="modal-dialog" style="width: 50%">
        <div class="modal-content">
            <div class="modal-header bg-light-blue color-palette">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Print Permit</h4>
            </div>
            <form id="print-current-permit" autocomplete="off">
                <div class="modal-body">
                    <div class="current-permit"></div>
                </div>
                <div class="modal-footer">
                    <span class="loadingSave"></span>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-file-pdf-o"></i> &nbsp;Generate Permit</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>