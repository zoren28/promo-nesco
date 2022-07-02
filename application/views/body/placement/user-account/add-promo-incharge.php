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
        User Accounts
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">User Accounts</a></li>
        <li class="active">Add Promo Incharge</li>
    </ol>
</section>

<section class="content">

    <!-- Default box -->
    <div class="row">
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Add Promo Incharge Account</h3>
                </div>
                <form id="add-hr-account" autocomplete="off">
                    <div class="box-body">
                        <input type="hidden" name="emp_id">
                        <div class="form-group">
                            <label>HR Staff</label> <i class="text-red">*</i>
                            <div class="input-group">
                                <input class="form-control" type="text" name="hr">
                                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                            </div>
                            <div class="search-results" style="display: none;"></div>
                        </div>
                        <div class="form-group">
                            <label>User Type</label> <i class="text-red">*</i>
                            <select name="usertype" class="form-control" onchange="inputField(this.name)">
                                <?php

                                $user_types = array(
                                    'promo1' => 'Promo Incharge',
                                    'promo2' => 'Encoder',
                                    'nesco' => 'Nesco Incharge'
                                );

                                echo '
                                    <option value=""></option>
                                ';
                                foreach ($user_types as $key => $value) {

                                    echo '
                                        <option value="' . $key . '">' . $value . '</option>
                                    ';
                                }

                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="box-footer">
                        <div class="pull-right">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-send"></i> Submit</button>
                        </div>
                    </div>
                </form>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>

</section>