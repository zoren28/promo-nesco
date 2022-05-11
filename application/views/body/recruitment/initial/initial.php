	<div class="wrapper" style='padding:20px; '>
		<div class="col-sm-6" style="padding-right:5px">
			<div class="panel panel-default">
				<div class="panel-heading">
				CHECK INITIAL REQUIREMENTS
				</div>
				
				<div class="panel-body">
					<div class="form-group"> <span class="text-red">*</span>
						 <label for="usr">GENDER <i style='color:red; font-size:11px;'> ( Required )</i></label>
						  <select class="form-control" name="gender" required>
							<option value=''>Select Gender</option>
							<option value='male'>Male</option>
							<option value='female'>Female</option>
						  </select>
						</div>
					<div class="form-group"><span class="text-red">*</span>
						
						 <label for="sel_civilstatus">CIVIL STATUS  <i style='color:red; font-size:11px;'> ( Required )</i></label>
						  <select class="form-control" name="civilstatus" required>
							<option value=''>Select Civil Status</option>
							<option value='single'>Single</option>
							<option value='married'>Married</option>
							<option value='widowed'>Widowed</option>
							<option value='separated'>Separated</option>
							<option value='divorced'>Divorced</option>
						  </select>
						
					</div>
					<div class="form-group"><span class="text-red">*</span>
						<label for="pwd">FIRSTNAME  <i style='color:red; font-size:11px;'> ( Required )</i></label>
						<input type="text" class="form-control" name="firstname" autocomplete="off">
					</div>
					<div class="form-group">
						<label for="pwd">MIDDLENAME</label>
						<input type="text" class="form-control" name="middlename" autocomplete="off">
					</div>
					<div class="form-group"><span class="text-red">*</span>
						<label for="pwd">LASTNAME  <i style='color:red; font-size:11px;'> ( Required )</i></label>
						<input type="text" class="form-control" name="lastname" autocomplete="off">
					</div>
					<div class="form-group">
						<label for="pwd">SUFFIXES</label>
						  <select class="form-control" name="suffix">
							<option value=''>Select Suffixes</option>
							<option value='Jr'>JR</option>
							<option value='Sr'>SR</option>
							<option value='I'>I</option>
							<option value='II'>II</option>
							<option value='III'>III</option>
							<option value='IV'>IV</option>
							<option value='V'>V</option>
							<option value='VI'>VI</option>
							<option value='VII'>VII</option>
							<option value='VIII'>VIII</option>
							<option value='IX'>IX</option>
							<option value='X'>X</option>
							<option value='Jr II'>JR II</option>
						  </select>	
					</div>
					<div class="form-group">
						<label for="btn"></label>
						<button type="submit" class="btn btn-success btn-lg mb-1" style='width:100%;' id="proceed_Record">Proceed</button>
					</div>
					
				</div>
				
				
			</div>
		</div>
		<div class="col-sm-6" style="padding-left:5px">
			<div class="panel panel-default">
				<div class="panel-heading">
				UPLOAD REQUIREMENTS
				</div>
				
				<form id='proceed_upload' method='post' enctype="multipart/form-data" >
				
					<input type="text" name='updt_or_appnd' class="form-control" id="updt_or_appnd" readonly="readonly">
					<input type="hidden" name='hidden_gender' class="form-control" id="hidden_gender" readonly="readonly">
					<input type="hidden" name='hidden_civil_status' class="form-control" id="hidden_civil_status" readonly="readonly">
					<input type="hidden" name='hidden_firstname' class="form-control" id="hidden_firstname" readonly="readonly">
					<input type="hidden" name='hidden_middlename' class="form-control" id="hidden_middlename" readonly="readonly">
					<input type="hidden" name='hidden_lastname' class="form-control" id="hidden_lastname" readonly="readonly">
					<input type="hidden" name='hidden_suffix' class="form-control" id="hidden_suffix" readonly="readonly">
					
					<div class="panel-body">
						<div class="form-group"><span class="text-red">*</span>
							<label for="position">POSITION</label>
								<select class="form-control" name="position" id='position' required>
									<option value=''>Select Position</option>
									<?php

									$result = $this->initial_model->position();
									foreach ($result as $res) 
									{ ?>
										<option value="<?php echo $res['position_title']; ?>"><?php echo $res['position_title']; ?></option> 
									<?php
									}?>
								</select>
								
								
						</div>
						
						<div class="form-group">
							<label class="form-label" >RESUME</label>
							<input type="file" name='resume[]' class="form-control" id="resume" multiple="" required>
						</div>
						<div class="form-group">
							<label for="pwd">APPLICATION</label>
							<input type="file" name='application[]' class="form-control" id="application" multiple="" required>
						</div>
						<div class="form-group">
							<label for="pwd">TOR</label>
							<input type="file" name='transcript[]' class="form-control" id="transcript" multiple="" required>
						</div>
						
						<div class="form-group">
							<button type="submit" id='upload_save'class="btn btn-success btn-lg mb-1" style='width:100%;' disabled >Upload</button>
						</div>
					</div>
				</form>
				
			</div>
		</div>  
	</div>
	
	
	<div class="modal fade" id="browse_blacklist">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header bg-light-blue color-palette">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span></button>
					<h5 class="modal-title">Checking for Application History</h5>
				</div>
				<div class="modal-body browse_blacklist" style='font-size=10px;'>
						
				</div>
				<div class="modal-footer">
					<button type="button" class="btn" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
	
	
	<div class="modal fade" id="upload_success_proceed_record">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header bg-light-blue color-palette">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span></button>
					<h5 class="modal-title">Uploading Information</h5>
				</div>
				<div class="modal-body upload_success_proceed_record" style='font-size=10px;'>
						
				</div>
				<div class="modal-footer">
					<button type="button" class="btn" id='record'>Proceed</button>
				</div>
			</div>
		</div>
	</div>
