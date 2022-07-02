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
        <li class="active">Add New User</li>
    </ol>
</section>

<section class="content">

    <!-- Default box -->
    <div class="row">
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Add New User Account</h3>
                </div>
                <form id="add-user-account" autocomplete="off">
                    <div class="box-body">
                        <input type="hidden" name="emp_id">
                        <div class="form-group">
                            <label>Employee</label> <i class="text-red">*</i>
                            <div class="input-group">
                                <input class="form-control" type="text" name="employee" required>
                                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                            </div>
                            <div class="search-results" style="display: none;"></div>
                        </div>
                        <div class="form-group">
                            <label>User Type</label>
                            <input type="hidden" name="usertype" value="employee">
                            <input type="text" class="form-control" value="Employee" disabled="">
                        </div>
                        <div class="form-group">
                            <label>Username</label> <i class="text-red">*</i>
                            <input type="text" name="username" class="form-control" required onkeyup="inputField(this.name)">
                        </div>
                        <hr>
                        <button type="button" class="btn btn-primary btn-sm" onclick="defaultPassword()">set default password</button> <i>Default password: Hrms2014</i>
                        <div class="form-group">
                            <label>Password</label> <i class="text-red">*</i>
                            <input type="password" name="password" class="form-control" required onkeyup="inputField(this.name)">
                        </div>
                    </div>
                    <div class="box-footer">
                        <div class="pull-right">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-send"></i> Submit</button>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </form>
            </div>
            <!-- /.box -->
        </div>
    </div>

</section>