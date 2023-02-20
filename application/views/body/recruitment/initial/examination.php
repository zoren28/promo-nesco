	<div class="wrapper" style='padding:20px; '>
		<div class="col-sm-12" style="padding-right:5px">
			<div class="panel panel-default">
				<div class="wrapper" style='padding:10px; color:white; background-color:#3399cc;'><i class="fa fa-th-large"> <u> EXAMINATION </u> </i> 
				</div>		
				<div class="panel-body">
					<?php
					$result_applicants = $this->initial_model->applicants_for_exam();
					?>
						<table id="tableViewEmp_one" class="table table-bordered">
							<thead>
								<th>Code</th>
								<th>Name</th>
								<th>Position</th>
								<th>Date Applied</th>
								<th>Action</th>
							</thead>
							<tbody>
								<?php // $result_applicants->lastname //
								foreach ($result_applicants as $i) { ?>
									<tr>
										<td><?= $i['app_id']?></td>
										<td><?= $i['lastname'].", ".$i['firstname']." ".$i['middlename']." ".$i['suffix'] ?></td>
										<td><?= $i['position'] ?></td>
										<td><?= $i['date_time'] ?></td>
										<td>
										<?php 
										if($i['status'] == 'initialreq completed') 
										{
											?><button id="<?= $i['app_id'] ?>" type="button" class="btn btn-primary btn-sm setup_exam">Set-Up Examination</button><?php 
										} 
										else if($i['status'] == 'for exam') 
										{
											?><button id="<?= $i['app_id'] ?>" type="button" class="btn btn-success btn-sm view_detail">View Exam Details</button><?php	
										} 
										else if($i['status'] == 'exam passed' || $i['status'] == 'assessment') 
										{
											?><button id="<?= $i['app_id']."|".$i['app_code'] ?>" type="button" class="btn btn-success btn-sm tag_interview">Tag to Interview</button><?php 
										} 
										else if($i['status'] == 'exam failed') 
										{
											// tag to transfer button change to HOLD for changes of concerns
											?><button id="<?= $i['app_id']."|".$i['app_code'] ?>" type="button" class="btn btn-danger btn-sm tag_transfer">Tag to Hold</button><?php 
										} 
										?></td>
									</tr><?php } ?>
							</tbody>
						</table>
				</div>
			</div>
		</div>
	</div>
	
	<div class="modal fade" id="applicantsetup" >
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header bg-light-blue color-palette">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span></button>
					<h5 class="modal-title">Set-Up Examination</h5>
				</div>
					<form id='setup_examination' method='post' enctype="multipart/form-data">
						<div class="modal-body applicantsetup" style='font-size=10px;'>		
						</div>
						<div class="modal-footer">
							<button type="button" class="btn" data-dismiss="modal">Close</button>
							<button type="submit" class="btn btn-primary">Submit</button>
						</div>
					</form>
			</div>
		</div>
	</div>
	
	<div class="modal fade" id="view_examination" >
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header bg-light-blue color-palette">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span></button>
					<h5 class="modal-title">Examination Details</h5>
				</div>
					<form id='save_examination' method='post' enctype="multipart/form-data">
						<div class="modal-body view_examination" style='font-size=10px;'>		
						</div>
						<div class="modal-footer">
							<button type="button" class="btn" data-dismiss="modal" id='reloadpage'>Close</button>
							<button type="submit" class="btn btn-primary">Submit</button>
						</div>
					</form>
			</div>
		</div>
	</div>
	
	<div class="modal fade" id="applicantsetup_success" >
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header bg-light-blue color-palette">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span></button>
					<h5 class="modal-title">Set-Up Examination</h5>
				</div>
				<div class="modal-body applicantsetup_success" style='font-size=10px;'>		
				</div>
				<div class="modal-footer">
					<button type="button" class="btn" data-dismiss="modal" id='reloadpage'>Close</button>
				</div>
			</div>
		</div>
	</div>

    
	<div class="modal fade" id="info_exam" >
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header bg-light-blue color-palette">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span></button>
					<h5 class="modal-title">Examination Info</h5>
				</div>
				<div class="modal-body info_exam_msg" style='font-size=10px;'>		
				</div>
				<div class="modal-footer">
					<button type="button" class="btn" data-dismiss="modal" id='reloadpage'>Close</button>
				</div>
			</div>
		</div>
	</div>