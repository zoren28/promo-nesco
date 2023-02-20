	<div class="wrapper" style='padding:20px; '>
		<div class="col-sm-12" style="padding-right:5px">
			<div class="panel panel-default">		
				<div class="wrapper" style='padding:10px; color:white; background-color:#3399cc;'><i class="fa fa-th-large"> <u> RECORD </u> </i> 
				</div>
					<div class="panel-body">
						<?php
						$result_applicants = $this->initial_model->record_applicants();
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
											<td><?= $i['app_code']?></td>
											<td><?= $i['lastname'].", ".$i['firstname']." ".$i['middlename']." ".$i['suffix'] ?></td>
											<td><?= $i['position'] ?></td>
											<td><?= $i['date_time'] ?></td>
											<td><button id="<?= $i['app_code']."|".$i['lastname']."|".$i['firstname']."|".$i['middlename']."|".$i['suffix'] ?>" type="button" class="btn btn-primary btn-sm record">Record</button></td>
										</tr><?php } ?>
								</tbody>
							</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	
<div class="modal fade" id="record_applicants" >
	<div class="modal-dialog modal-lg" style='width:90%;'>
		<div class="modal-content">
			<div class="modal-header bg-light-blue color-palette">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span></button>
				<h5 class="modal-title">Record Applicants Information</h5>
			</div>
			<form id='applicant_information' method='post' enctype="multipart/form-data">
				<div class="modal-body record_applicants" style='font-size=10px;'>
						
				</div>
				<div class="modal-footer">
					<button type="button" class="btn" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary">Submit</button>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="modal fade" id="applicant_record_success" >
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header bg-light-blue color-palette">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span></button>
				<h5 class="modal-title">Record Applicants Information</h5>
			</div>
			
				<div class="modal-body record_success" style='font-size=10px;'>
						
				</div>
				<div class="modal-footer">
					<button type="button" class="btn" data-dismiss="modal" id='reloadpage'>Close</button>
				</div>
		</div>
	</div>
</div>
    