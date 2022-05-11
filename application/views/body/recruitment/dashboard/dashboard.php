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
        
        <div class="col-md-4">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <!--h3 class="box-title"><i class="fa fa-tasks"></i> Dashboard</h3-->
                </div>
                <div class="box-body">
                    <ul class="list-group">
                        <li class="list-group-item">
                            <span class="badge bg-red" id="examcount_applicant"><?= $result_applicants = $this->initial_model->examcount_applicant();?></span><i class="fa fa-pencil" style="margin-right: 5px;"></i> For Examination
                        </li>
                        <li class="list-group-item">
                            <span class="badge bg-red" id="interviewcount_applicant"><?= $result_applicants = $this->initial_model->interviewcount_applicant();?></span><i class="fa fa-users" style="margin-right: 5px;"></i> For Interview
                        </li>
                        <li class="list-group-item">
                            <span class="badge bg-red" id="hiringcount_applicant"><?= $result_applicants = $this->initial_model->hiringcount_applicant();?></span><i class="fa fa-male" style="margin-right: 5px;"></i>&nbsp;&nbsp; For Hiring
                        </li>
                        
                    </ul>
                </div>
            </div>
            <!-- /.box -->
        </div>
		
		<div class="col-md-8">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <!--h3 class="box-title"><i class="fa fa-bar-chart"></i> Number of Employee per Business Unit</h3-->
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-7">

                            <canvas id="pieChart" style="height:250px;"></canvas>
                            
                        </div>
                        <div class="col-md-5 table-responsive">
                            <table class="table table-bordered" style="font-size: 11px;">
                                <tr>
                                    <!--th>Business Unit</th-->
                                    <!--th>Count</th-->
                                    <?php

                                    /* $c = 0;
                                    $result = $this->dashboard_model->businessUnit_list();
                                    foreach ($result as $row) {

                                        $count = $this->dashboard_model->count_per_bu($row->bunit_field); */
                                    ?>
                                <tr>
                                    
                                </tr> 
								</tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
		
    </div>
</section>