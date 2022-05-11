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

    .preview {

        background-image: url("http://<?php echo $_SERVER['SERVER_ADDR'] . ':' . $_SERVER['SERVER_PORT']; ?>/hrms/promo-nesco/../images/images.png");
        background-size: contain;
        width: 300px;
        height: 370px;
        border: 2px solid #BBD9EE;
    }
</style>

<section class="content-header">
    <h1>
        Outlet
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Outlet</a></li>
        <li class="active">Remove Outlet</li>
    </ol>
</section>

<section class="content">

    <!-- Default box -->
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Remove Outlet</h3>
                </div>

                <div class="box-body">
                    <div class="row">
                        <div class="col-md-5">
                            <input type="hidden" name="action" value="remove_outlet">
                            <div class="form-group">
                                <label>Search Promo</label>
                                <div class="input-group">
                                    <input class="form-control" type="text" name="employee" onkeyup="nameSearch(this.value)" autocomplete="off">
                                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                </div>
                                <div class="search-results" style="display: none;"></div>
                            </div>
                            <div class="promo-details"></div>
                        </div>
                        <div class="col-md-7">
                            <div id="loading-gif" style="text-align: center; display:none;">
                                <img src="<?= base_url('assets/images/gif/loader_seq.gif'); ?>" alt="gif">
                            </div>
                            <div class="outlet-form"></div>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>

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
    <div id="store_clearance" class="modal fade">
        <div class="modal-dialog" style="width: 80%;">
            <div class="modal-content">
                <div class="modal-header bg-light-blue color-palette">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Upload Clearance</h4>
                </div>
                <form id="dataUploadClearanceToRemoveOutlet" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="store_clearance"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Upload</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</section>