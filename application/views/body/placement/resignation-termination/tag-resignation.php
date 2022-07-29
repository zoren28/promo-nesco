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
</style>

<section class="content-header">
    <h1>
        Resignation/Termination
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Resignation/Termination</a></li>
        <li class="active">Tag for Resignation</li>
    </ol>
</section>

<section class="content">

    <!-- Default box -->
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Tag for Resignation</h3>
                </div>

                <div class="box-body">

                    <div class="row">
                        <div class="col-md-4">
                            <!-- <h3>Supervisor Details</h3> -->
                            <div class="form-group">
                                <label>Supervisor</label>
                                <div class="input-group">
                                    <input class="form-control" type="text" name="supervisor" autocomplete="off">
                                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                </div>
                                <div class="search-results" style="display: none;"></div>
                            </div>
                            <div class="legend">
                                <div class="panel panel-default">
                                    <div class="panel-body">
                                        <label>Legend</label>
                                        <hr>
                                        <table>
                                            <tr>
                                                <td><a class="btn btn-primary btn-xs disabled">&nbsp;</a></td>
                                                <td> - </td>
                                                <td>Pending</td>
                                                <td width="10%"></td>
                                                <td><a class="btn btn-success btn-xs disabled">&nbsp;</a></td>
                                                <td> - </td>
                                                <td>Done</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div id="loading-gif" style="text-align: center; display:none;">
                                <img src="<?= base_url('assets/images/gif/loader_seq.gif'); ?>" alt="gif">
                            </div>
                            <div class="subordinates">

                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>

</section>