<section class="content-header">
    <h1>
        Dashboard
        <small>Control panel</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
    </ol>
</section>

<!-- Main content -->
<input type="hidden" name="dashboard" value="comeOut">
<section class="content">
    <div class="row">
        <div class="col-md-8">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-bar-chart"></i> Number of Employee per Business Unit</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-7">

                            <canvas id="pieChart" style="height:250px;"></canvas>
                            <button class="btn btn-primary btn-sm pull-right" onclick="viewDetails()">View Details ...</button>
                        </div>
                        <div class="col-md-5 table-responsive">
                            <table class="table table-bordered" style="font-size: 11px;">
                                <tr>
                                    <th>Business Unit</th>
                                    <th>Count</th>
                                    <?php

                                    $c = 0;
                                    $result = $this->dashboard_model->businessUnit_list();
                                    foreach ($result as $row) {

                                        $count = $this->dashboard_model->count_per_bu($row->bunit_field);
                                    ?>
                                <tr>
                                    <td><span class="glyphicon glyphicon-stop" style="color: <?php echo $pieColor[$c]; ?>"></span>&nbsp; <?= $row->bunit_name ?></td>
                                    <td><a href="#"><?php echo $count; ?></a></td>
                                </tr> <?php
                                        $c++;
                                    }
                                        ?>
                            </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.box -->
        </div>
        <div class="col-md-4">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-tasks"></i> Dashboard</h3>
                </div>
                <div class="box-body">
                    <ul class="list-group">
                        <li class="list-group-item">
                            <span class="badge bg-red" id="new_employee">0</span><i class="fa fa-user-plus" style="margin-right: 5px;"></i> New Employee
                        </li>
                        <li class="list-group-item">
                            <span class="badge bg-red" id="birthday_today">0</span><i class="fa fa-birthday-cake" style="margin-right: 5px;"></i> Birthday Today
                        </li>
                        <li class="list-group-item">
                            <span class="badge bg-red" id="active_employee">0</span><i class="fa fa-male" style="margin-right: 5px;"></i>&nbsp;&nbsp; Active Employee
                        </li>
                        <li class="list-group-item">
                            <span class="badge bg-red" id="eoc_today">0</span><i class="fa fa-thumb-tack" style="margin-right: 5px;"></i>&nbsp;&nbsp; End of Contract Today
                        </li>
                        <li class="list-group-item">
                            <span class="badge bg-red" id="due_contract">0</span><i class="fa fa-male" style="margin-right: 5px;"></i>&nbsp;&nbsp; Due of Contracts
                        </li>
                    </ul>
                </div>
            </div>
            <!-- /.box -->
        </div>
    </div>
</section>