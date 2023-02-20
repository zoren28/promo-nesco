
<div class="wrapper" style='padding:20px; '>
		<div class="col-sm-12" style="padding-right:5px">
			<div class="panel panel-default">
				<div class="wrapper" style='padding:10px; color:white; background-color:#3399cc;'><i class="fa fa-th-large"> <u> NEW EMPLOYEE</u> </i> 
				</div>
				<div class="panel-body">
					<?php
					$result_applicants = $this->initial_model->new_employee_applicants();
					?>
						<table id="tableViewEmp" class="table table-bordered">
							<thead>
								<th>App-Id</th>
								<th>Name</th>
								<th>Position</th>
								<th>Date Applied</th>
								<th>Action</th>
							</thead>
							<tbody>
								<?php 
								foreach ($result_applicants as $i) { ?>
									<tr>
										<td><?=$i['app_id']?></td>
										<td><?= $i['lastname'].", ".$i['firstname']." ".$i['middlename']." ".$i['suffix'] ?></td>
										<td><?= $i['position'] ?></td>
										<td><?= $i['date_time'] ?></td>
										<td>
											<?php 
												if($i['status'] == "new employee") {
											?>
												<button id="<?=$i['app_code']."|".$i['app_id'] ?>" type="button" class="btn btn-primary btn-sm deploy">Deploy?</button>
											<?php 
												}
												else 
												{ 
											?>
												<button type="button" class="btn btn-success btn-sm" disabled>Deployed</button>	
											<?php 
												} 
											?>
										</td>
									</tr><?php } ?>
							</tbody>
						</table>
				</div>
			</div>
		</div>
	</div>


    