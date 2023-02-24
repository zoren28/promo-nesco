<section class="content-header">
    <h1>
        Setup
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Setup</a></li>
        <li class="active">Department List</li>
    </ol>
</section>
<section class="content">

    <!-- Default box -->
    <div class="box box-primary">
        <div class="box-header with-border">
            <div class="pull-right">
                <button type="button" id="add-department" class="btn btn-primary btn-sm">
                    Add Department
                </button>
            </div>
        </div>

        <div class="box-body">
            <table id="dt-departments" class="table table-striped table-hover" width="100%">
                <thead>
                    <tr>
                        <th width="30%">Business Unit</th>
                        <th>Department</th>
                    </tr>
                </thead>
                <tbody>
                    <?php

                    foreach ($designations as $index => $bu) {

                        echo "<tr>
                            <td><b>{$bu['bunit']}</b></td>
                            <td></td>
                        </tr>";

                        foreach ($bu['depts'] as $key => $dept) {

                            echo "<tr>
                                <td></td>
                                <td><b><i>{$dept->dept_name}<i></b></td>
                            </tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->
</section>

<div id="add-department" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-light-blue color-palette">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Add Department</h4>
            </div>
            <form id="add-department" autocomplete="off">
                <div class="modal-body">
                    <div class="add-department"></div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="submit-btn" class="btn btn-primary">Submit</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>