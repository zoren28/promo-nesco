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

    .size-emp {

        max-height: 450px;
        overflow: auto;
    }

    .witness1Renewal {

        box-shadow: 5px 5px 5px #ccc;
        margin-top: 1px;
        margin-left: 0px;
        background-color: #F1F1F1;
        width: 90%;
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

    .witness2Renewal {

        box-shadow: 5px 5px 5px #ccc;
        margin-top: 1px;
        margin-left: 0px;
        background-color: #F1F1F1;
        width: 90%;
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
        Contract
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Contract</a></li>
        <li class="active">Transfer Rate</li>
    </ol>
</section>

<section class="content">

    <!-- Default box -->
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Transfer Rate</h3>
                </div>

                <div class="box-body">

                    <div class="row">
                        <form id="transfer-rate" autocomplete="off">
                            <div class="col-md-5">
                                <input type="hidden" name="page" value="transfer-rate">
                                <div class="form-group">
                                    <label>Search Promo</label>
                                    <div class="input-group">
                                        <input class="form-control" type="text" name="employee" onkeyup="nameSearch(this.value)">
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
                                <div class="transfer-rate"></div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>

</section>