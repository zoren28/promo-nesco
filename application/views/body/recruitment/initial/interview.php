	<div class="wrapper" style='padding:20px; '>
		<div class="col-sm-12" style="padding-right:5px">
			<div class="panel panel-default">
				<div class="wrapper" style='padding:10px; color:white; background-color:#3399cc;'><i class="fa fa-th-large"> <u> INTERVIEW</u> </i> 
				</div>		
				<div class="panel-body">
					<?php
					$result_applicants = $this->initial_model->applicants_for_interview();
					?>
						<table id="tableViewEmp_interview" class="table table-bordered">
							<thead>
								<th>Code</th>
								<th>Name</th>
								<th>Position</th>
								<th>Date Applied</th>
								<th>Action</th>
							</thead>
							<tbody>
								<?php // $result_applicants->lastname //
								foreach ($result_applicants as $i) 
								{ 
									$result_interview = $this->initial_model->applicants_interview($i['app_id']);
									$result_interview_lvl = $this->initial_model->applicants_interview_level($i['app_id']);
									?>
									<tr>
										<td><?= $i['app_id'] ?></td>
										<td><?= $i['lastname'].", ".$i['firstname']." ".$i['middlename']." ".$i['suffix'] ?></td>
										<td><?= $i['position'] ?></td>
										<td><?= $i['date_time'] ?></td>
										<td>
											<?php 
											if($result_interview < 1) 
											{	
												?><button id="<?= $i['app_code']."|".$i['app_id'] ?>" type="button" class="btn btn-primary btn-sm initial_interview">
												<?php //echo  $result_interview." ".$result_interview_lvl?>
												Start Initial Interview
												</button><?php 
											}
											else 
											{ 
												if($result_interview_lvl > 0 && $result_interview > 1)
												{
												?><button id="<?= $i['app_code']."|".$i['app_id'] ?>" type="button" class="btn btn-warning btn-sm check_interview">
												<?php //echo  $result_interview." ".$result_interview_lvl?>
												Check Interview Details
												</button><?php 
												}
												else
												{
												?><button id="<?= $i['app_code']."|".$i['app_id'] ?>" type="button" class="btn btn-success btn-sm setup_interview">
												<?php //echo  $result_interview." ".$result_interview_lvl?>
												Setup Interview
												</button><?php 
												}
											}
										?></td>
									</tr><?php 
								} ?>
							</tbody>
						</table>
				</div>
			</div>
		</div>
	</div>
	
	
	<div class="modal fade" id="initial_interview_modal" >
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header bg-light-blue color-palette">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span></button>
					<h5 class="modal-title">Initial Interview</h5>
				</div>
					<form id='save_initial_interview' method='post' enctype="multipart/form-data">
						<div class="modal-body initial_interview_display" style='font-size=10px;'>		
						</div>
						<div class="modal-footer">
							<button type="button" class="btn" data-dismiss="modal">Close</button>
							<button type="submit" class="btn btn-primary">Submit</button>
						</div>
					</form>
			</div>
		</div>
	</div>
	
	<div class="modal fade" id="setup_interview_modal" >
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header bg-light-blue color-palette">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span></button>
					<h5 class="modal-title">Set-up Interview</h5>
				</div>
					<form id='setup_interviewee' method='post' enctype="multipart/form-data">
						<div class="modal-body setup_interview_display" style='font-size=10px;'>		
						</div>
						
						<div class="modal-footer">
							<button type="button" class="btn" data-dismiss="modal">Close</button>
							<button type="submit" class="btn btn-primary">Submit</button>
						</div>
					</form>
			</div>
		</div>
	</div>
	
    <div class="modal fade" id="interviewdetails_modal" >
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header bg-light-blue color-palette">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span></button>
					<h5 class="modal-title">Interview Details</h5>
				</div>
					<form id='final_interview' method='post' enctype="multipart/form-data">
						<div class="modal-body interviewdetails_display" style='font-size=10px;'>		
						</div>
						
						<div class="modal-footer">
							<button type="button" class="btn btn-default" id='inputGrade'>Input Grade</button>
							<button type="button" class="btn btn-default" id='sheet'>Print Interview Sheet</button>
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							<button type="submit" class="btn btn-primary">Submit</button>
						</div>
					</form>
			</div>
		</div>
	</div>
	
	
	<div class="modal fade" id="interview_modal" >
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header bg-light-blue color-palette">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span></button>
					<h5 class="modal-title">Interview Info</h5>
				</div>
				<div class="modal-body interview_display" style='font-size=10px;'>		
				</div>
				<div class="modal-footer">
					<button type="button" class="btn" data-dismiss="modal" id='reloadpage'>Close</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="interview_grade" >
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header bg-light-blue color-palette">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span></button>
					<h5 class="modal-title">Interview Grade</h5>
				</div>
				<form id='interview_grade' method='post' enctype="multipart/form-data">
					<div class="modal-body grade_display" style='font-size=10px;'>		
					</div>
					<div class="modal-footer">
						<button type="button" class="btn" data-dismiss="modal" >Close</button>
						<button type="submit" class="btn btn-primary">Submit</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div class="modal fade" id="grade_modal_info" >
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header bg-light-blue color-palette">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span></button>
					<h5 class="modal-title">Interview Info</h5>
				</div>
				<div class="modal-body grade_modal_info_display" style='font-size=10px;'>		
				</div>
				<div class="modal-footer">
					<button type="button" class="btn" data-dismiss="modal" id='reloadpage'>Close</button>
				</div>
			</div>
		</div>
	</div>
	