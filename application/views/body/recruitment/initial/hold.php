	<div class="wrapper" style='padding:20px; '>
		<div class="col-sm-12" style="padding-right:5px">
			<div class="panel panel-default">
				<div class="wrapper" style='padding:10px; color:white; background-color:#3399cc;'><i class="fa fa-th-large"> <u> HOLD APPLICANTS</u> </i> 
				</div>		
				<div class="panel-body">
					<?php
					$result_applicants = $this->initial_model->hold_applicants();
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
								<?php 
								foreach ($result_applicants as $i) { ?>
									<tr>
										<td><?= $i['app_id'] ?></td>
										<td><?= $i['lastname'].", ".$i['firstname']." ".$i['middlename']." ".$i['suffix'] ?></td>
										<td><?= $i['position'] ?></td>
										<td><?=$i['date_time'] ?></td>
										<td><button id="<?= $i['app_code']."|".$i['app_id'] ?>" type="button" class="btn btn-primary btn-sm transferHold">Transfer</button></td>
									</tr><?php } ?>
							</tbody>
						</table>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="save_transfer" >
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header bg-light-blue color-palette">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span></button>
					<h5 class="modal-title">Transfer Applicant Info <i style='color:black;'> ( Administrator Side ) </i></h5>
				</div>
					<form id='save_transfer_process' method='post' enctype="multipart/form-data">
						<div class="modal-body transfer_display" style='font-size=10px;'>		
						</div>
						
						<div class="modal-footer">
							<button type="button" class="btn" data-dismiss="modal">Close</button>
							<button type="submit" class="btn btn-primary">Submit</button>
						</div>
					</form>
			</div>
		</div>
	</div>
    