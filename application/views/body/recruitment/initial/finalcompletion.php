	<section class="content-header" style="padding-left:40px;">
		<h5>
			<u style="color:#3399cc;">Final Completion</u> ( Application process for compiling all the required documents )
		</h5>
	</section>
	<div class="wrapper" style='padding:20px; '>
		<div class="col-sm-12" style="padding-right:5px">
			<div class="panel panel-default">		
				<div class="wrapper"> 
				</div>
				<div class="panel-body">
					<?php
					$result_applicants = $this->initial_model->applicants_for_finalcompletion();
					?>
						<table id="tableViewEmp" class="table table-bordered">
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
										<td><?= $i['app_id'] ?></td>
										<td><?= $i['lastname'].", ".$i['firstname']." ".$i['middlename']." ".$i['suffix'] ?></td>
										<td><?= $i['position'] ?></td>
										<td><?= $i['date_time'] ?></td>
										<td><button id="<?= $i['app_code']."|".$i['app_id'] ?>" type="button" class="btn btn-primary btn-sm upload_final">Upload</button></td>
									</tr><?php } ?>
							</tbody>
						</table>
				</div>
			</div>
		</div>
	</div>
    
	
	<div class="modal fade" id="final_modal" >
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header bg-light-blue color-palette">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span></button>
					<h5 class="modal-title">Upload Final Requirement</h5>
				</div>
					<form id='save_final_completion' method='post' enctype="multipart/form-data">
						<div class="modal-body final_display" style='font-size=10px;'>		
						</div>
						
						<div class="modal-footer">
							<button type="button" class="btn" data-dismiss="modal">Close</button>
							<button type="submit" class="btn btn-primary">Submit</button>
						</div>
					</form>
			</div>
		</div>
	</div>
	
	<div class="modal fade" id="final_completion_modal" >
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header bg-light-blue color-palette">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span></button>
					<h5 class="modal-title">Final Completion Info</h5>
				</div>
				<div class="modal-body final_completion_display" style='font-size=10px;'>		
				</div>
				<div class="modal-footer">
					<button type="button" class="btn" data-dismiss="modal">Close</button>
					<!--button type="button" class="btn" data-dismiss="modal" id='reloadpage'>Close</button-->
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="final_completion_modal_success_response" >
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header bg-light-blue color-palette">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span></button>
					<h5 class="modal-title">Final Completion Info</h5>
				</div>
				<div class="modal-body final_completion_response" style='font-size=10px;'>		
				</div>
				<div class="modal-footer">
					<button type="button" class="btn" data-dismiss="modal" id='reloadpage'>Close</button>
				</div>
			</div>
		</div>
	</div>