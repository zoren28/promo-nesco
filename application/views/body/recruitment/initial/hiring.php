	<div class="wrapper" style='padding:20px; '>
		<div class="col-sm-12" style="padding-right:5px">
			<div class="panel panel-default">		
				<div class="panel-body">
					<?php
					$result_applicants = $this->initial_model->applicants_for_hiring();
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
										<td><?= $i['app_code'] ?></td>
										<td><?= $i['lastname'].", ".$i['firstname']." ".$i['middlename']." ".$i['suffix'] ?></td>
										<td><?= $i['position'] ?></td>
										<td><?= $i['date_time'] ?></td>
										<td><button id="<?=$i['app_code']."|".$i['app_id'] ?>" type="button" class="btn btn-primary btn-sm hiring">Hire Applicant?</button></td>
									</tr><?php } ?>
							</tbody>
						</table>
				</div>
			</div>
		</div>
	</div>
	
	
<div class="modal fade" id="hired_applicant">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header bg-light-blue color-palette">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span></button>
					<h5 class="modal-title">Hiring Information</h5>
				</div>
				<div class="modal-body hired_display" style='font-size=10px;'>
						
				</div>
				<div class="modal-footer">
					<button type="button" class="btn" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary">Submit</button>
				</div>
			</div>
		</div>
	</div>
    