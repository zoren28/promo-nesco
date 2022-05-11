	<div class="wrapper" style='padding:20px; '>
		<div class="col-sm-12" style="padding-right:5px">
			<div class="panel panel-default">		
				<div class="panel-body">
					<?php
					$result_applicants = $this->initial_model->applicants_for_exam();
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
										<td><button id="<?= $i['app_code'] ?>" type="button" class="btn btn-primary btn-sm record">Set-Up Examination</button></td>
									</tr><?php } ?>
							</tbody>
						</table>
				</div>
			</div>
		</div>
	</div>

    