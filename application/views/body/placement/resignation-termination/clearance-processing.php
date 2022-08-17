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
</style>
<section class="content-header">
    <h1>
        Clearance Processing
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Resignation/Termination</a></li>
        <li class="active">Clearance Processing</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-body">
                    <div class="row">
                        <div class='col-md-2'>
                            <div class="list-group">
                                <button type="button" href="#" class="list-group-item clearance-action active" id="secure_clearance">
                                    <b> SECURE </b> Clearance
                                </button>
                                <button type="button" href="#" class="list-group-item clearance-action" id="upload_clearance">
                                    <b> UPLOAD </b>Clearance and <br>Change Status
                                </button>
                                <button type="button" href="#" class="list-group-item clearance-action" id="reprint_clearance">
                                    <b> REPRINT </b> Clearance
                                </button>
                                <button type="button" href="#" class="list-group-item clearance-action" id="list_secured_clerance">
                                    <b> LIST </b> of Employees <br>who secured clearance
                                </button>
                                <button type="button" href="#" class="list-group-item clearance-action" id="process_flow">
                                    <b> PROCESS </b> Flow
                                </button>
                            </div>
                        </div>
                        <div class='col-md-10'>
                            <input type="hidden" name="page" value="<?= $page ?>">
                            <div id="loading-gif" style="text-align: center; display:none;">
                                <img src="<?= base_url('assets/images/gif/loader_seq.gif'); ?>" alt="gif">
                            </div>
                            <div class="clearance-body"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>