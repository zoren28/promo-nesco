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
        
        <div class="col-md-4" style='padding-left:8px; padding-right:8px;'>
            <div class="box box-primary">
                <div class="box-header with-border">
                    <!--h3 class="box-title"><i class="fa fa-tasks"></i> Dashboard</h3-->
                </div>
                <div class="box-body">
                    <ul class="list-group">
                    <li class="list-group-item">
                            <span class="badge bg-red" id="record_count_applicant"><?= $result_applicants = $this->initial_model->record_count_applicant();?></span><i class="fa fa-pencil-square-o" style="margin-right: 5px;"></i> For Record
                        </li>
                        <li class="list-group-item">
                            <span class="badge bg-red" id="examcount_applicant"><?= $result_applicants = $this->initial_model->examcount_applicant();?></span><i class="fa fa-pencil-square-o" style="margin-right: 5px;"></i> For Examination
                        </li>
                        <li class="list-group-item">
                            <span class="badge bg-red" id="interviewcount_applicant"><?= $result_applicants = $this->initial_model->interviewcount_applicant();?></span><i class="fa fa-pencil-square-o" style="margin-right: 5px;"></i> For Interview
                        </li>
                        <li class="list-group-item">
                            <span class="badge bg-red" id="finalcompletion_count_applicant"><?= $result_applicants = $this->initial_model->finalcompletion_count_applicant();?></span><i class="fa fa-pencil-square-o" style="margin-right: 5px;"></i>&nbsp;&nbsp; For Final Completion
                        </li>
                        <li class="list-group-item">
                            <span class="badge bg-red" id="hiringcount_applicant"><?= $result_applicants = $this->initial_model->hiringcount_applicant();?></span><i class="fa fa-pencil-square-o" style="margin-right: 5px;"></i>&nbsp;&nbsp; For Hiring
                        </li>
                        <li class="list-group-item">
                            <span class="badge bg-red" id="deploy_count_applicant"><?= $result_applicants = $this->initial_model->deploy_count_applicant();?></span><i class="fa fa-pencil-square-o" style="margin-right: 5px;"></i>&nbsp;&nbsp; For Deployment
                        </li>
                        <li class="list-group-item">
                            <span class="badge bg-red" id="hold_count_applicant"><?= $result_applicants = $this->initial_model->hold_count_applicant();?></span><i class="fa fa-pencil-square-o" style="margin-right: 5px;"></i>&nbsp;&nbsp; Hold Applicants
                        </li>

                        <a href="<?php echo base_url('recruitment/page/menu/initial/deploy'); ?>"><li class="list-group-item" style='color:red;'>
                            
                                <span class="badge bg-red" id="new_employee"><?= $result_applicants = $this->initial_model->newEmp_count_applicant();?></span><i class="fa fa-pencil-square-o" style="margin-right: 5px;"></i>&nbsp;&nbsp; New Employee
                            
                        </li></a>
                    </ul>
                </div>
            </div>
            <!-- /.box -->
        </div>
		
        <input type='hidden' value='<?= $result_applicants = $this->initial_model->record_count_applicant();?>' id='record'>
        <input type='hidden' value='<?= $result_applicants = $this->initial_model->examcount_applicant();?>' id='exam'>
        <input type='hidden' value='<?= $result_applicants = $this->initial_model->interviewcount_applicant();?>' id='interview'>
        <input type='hidden' value='<?= $result_applicants = $this->initial_model->finalcompletion_count_applicant();?>' id='finalcompletion'>
        <input type='hidden' value='<?= $result_applicants = $this->initial_model->hiringcount_applicant();?>' id='hiring'>
        <input type='hidden' value='<?= $result_applicants = $this->initial_model->deploy_count_applicant();?>' id='deploy'>
        <input type='hidden' value='<?= $result_applicants = $this->initial_model->hold_count_applicant();?>' id='hold'>
        <input type='hidden' value='<?= $result_applicants = $this->initial_model->newEmp_count_applicant();?>' id='new_emp'>

		<div class="col-md-8" style='padding-left:8px; padding-right:8px;'>
            <div class="box box-primary">
                <div class="box-header with-border">
                </div>
                <div class="box-body">
                        <div class="col-md-12">
                        <canvas id="m" width="100%" height="50%"></canvas>
                        </div>
                       
                        
				</div>
            </div>
        </div>
		
    </div>
</section>

<!-- $total_app_offense 	= $cnt_tard+$cnt_negli+$cnt_awol+$cnt_aow+$cnt_insu;
		
$tardi_percentage 	= ($cnt_tard/$total_app_offense)*100;
$negli_percentage	= ($cnt_negli/$total_app_offense)*100;
$aow_percentage		= ($cnt_aow/$total_app_offense)*100;
$awol_percentage	= ($cnt_awol/$total_app_offense)*100;
$insu_percentage	= ($cnt_insu/$total_app_offense)*100; -->

<script src="<?php echo base_url('assets/plugins/chartjs/Chart.2.9.4.js'); ?>"></script>

<script>
   
    var m1 = document.getElementById('record').value
    var m2 = document.getElementById('exam').value
    var m3 = document.getElementById('interview').value
    var m4 = document.getElementById('finalcompletion').value
    var m5 = document.getElementById('hiring').value
    var m6 = document.getElementById('deploy').value
    var m7 = document.getElementById('hold').value
    var m8 = document.getElementById('new_emp').value
    
   
    var xValues = ["RECORD", "EXAMINATION", "INTERVIEW", "FINAL COMPLETION", "HIRING", "DEPLOYMENT", "ON HOLD", "NEW EMPLOYEE"];
    var yValues = [m1, m2, m3, m4, m5, m6, m7, m8];
    var barColors = ["#3399cc", "#3399cc","#3399cc","#3399cc","#3399cc","#3399cc","#3399cc","#3399cc"];

    new Chart("m", {
    type: "bar",
    
    data: {
        labels: xValues,
        datasets: [{
        backgroundColor: barColors,
        data: yValues
        }]
    },
    options: {
        legend: {display: false},
        title: {
        display: true,
        text: "PROMO NESCO APPLICATION DATA"
        }
    }
    });
</script>

