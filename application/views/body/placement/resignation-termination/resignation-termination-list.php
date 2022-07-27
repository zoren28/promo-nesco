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
        Resignation/Termination
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Resignation/Termination</a></li>
        <li class="active">Resignation/Termination List</li>
    </ol>
</section>

<section class="content">

    <!-- Default box -->
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Resignation/Termination List</h3>
        </div>

        <div class="box-body">
            <table id="dt-resignation-list" class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Effectivity</th>
                        <th>AddedBy</th>
                        <th>DateUpdated</th>
                        <th>Remarks</th>
                        <th>Letter</th>
                    </tr>
                </thead>
            </table>
        </div>

        <!-- /.box-body -->
    </div>
    <!-- /.box -->

</section>

<div id="view-resignation-letter" class="modal fade">
    <div class="modal-dialog" style="width: 50%;">
        <div class="modal-content">
            <div class="modal-header bg-light-blue color-palette">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Resignation Letter</h4>
            </div>
            <div class="modal-body">
                <div class="view-resignation-letter"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div id="upload-resignation-letter" class="modal fade">
    <div class="modal-dialog" style="width: 50%;">
        <form id="upload-resignation-letter" action="" method="post" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header bg-light-blue color-palette">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Upload Resignation Letter</h4>
                </div>
                <div class="modal-body">
                    <div class="upload-resignation-letter"></div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Upload</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </form>
    </div>
    <!-- /.modal-dialog -->
</div>