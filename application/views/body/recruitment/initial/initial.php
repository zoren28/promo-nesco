	
	<section class="content-header" style="padding-left:40px;">
		<h5>
			<u style="color:#3399cc;">Initial Completion</u> ( Compiling applicants initial requirements ) 
		</h5>
	</section>
	<div class="wrapper" style='padding:20px; '>
		<div class="col-sm-6" style="padding-right:5px">
			<div class="panel panel-default">
				<div class="panel-heading">
				CHECK INITIAL APPLICANT DATA
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
						<input type="text" class="form-control" name="firstname" autocomplete="off" style="text-transform: capitalize;">
					</div>
					<div class="form-group">
						<label for="pwd">MIDDLENAME</label>
						<input type="text" class="form-control" name="middlename" autocomplete="off" style="text-transform: capitalize;">
					</div>
					<div class="form-group"><span class="text-red">*</span>
						<label for="pwd">LASTNAME  <i style='color:red; font-size:11px;'> ( Required )</i></label>
						<input type="text" class="form-control" name="lastname" autocomplete="off" style="text-transform: capitalize;">
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
						<button type="submit" class="btn btn-success btn-lg mb-1" style='width:100%;' id="proceed_Record">Check Applicant Record</button>
					</div>
					
				</div>
				
				
			</div>
		</div>
		<div class="col-sm-6" style="padding-left:5px">
			<div class="panel panel-default">
				<div class="panel-heading">
				UPLOAD REQUIREMENTS AND APPLICANT PERSONAL DATA
				</div>
				
				<form id='proceed_upload' method='post' enctype="multipart/form-data" >
					
					<div class="panel-body">
						<div class="row">	
							<!-- <div class="col-sm-3" style='padding-top:5px; padding-bottom:5px; text-align:right'>
								<label class="form-label" >PROCEDURE</label>	
							</div> -->
								<input type="hidden" name='updt_or_appnd' class="form-control" id="updt_or_appnd" >	
						</div>
						
						<div class="row">	
							<!-- <div class="col-sm-3" style='padding-top:5px; padding-bottom:5px; text-align:right'>
								<label class="form-label" >APP CODE</label>	
							</div> -->
								<input type="hidden" name='hidden_code' class="form-control" id="hidden_code" >	
						</div>
						
						<div class="row">	
							<div class="col-sm-3" style='padding-top:5px; padding-bottom:5px; text-align:right'>
								<label class="form-label" >GENDER</label>	
							</div>
							<div class="col-sm-9" style='padding-top:5px; padding-bottom:5px; padding-right:10%;'>
								<input type="text" name='hidden_gender' class="form-control" id="hidden_gender" readonly="readonly" style="text-transform: capitalize;">
							</div>	
						</div>
						
						<div class="row">	
							<div class="col-sm-3" style='padding-top:5px; padding-bottom:5px; text-align:right'>
								<label class="form-label" >CIVIL STATUS</label>	
							</div>
							<div class="col-sm-9" style='padding-top:5px; padding-bottom:5px; padding-right:10%;'>
								<input type="text" name='hidden_civil_status' class="form-control" id="hidden_civil_status" readonly="readonly" style="text-transform: capitalize;">
							</div>	
						</div>
						
						<div class="row" >	
							<div class="col-sm-3" style='padding-top:5px; padding-bottom:5px; text-align:right'>
								<label class="form-label" >FIRSTNAME</label>	
							</div>
							<div class="col-sm-9" style='padding-top:5px; padding-bottom:5px; padding-right:10%;'>
								<input type="text" name='hidden_firstname' class="form-control" id="hidden_firstname" readonly="readonly" style="text-transform: capitalize;">
							</div>	
						</div>
						
						<div class="row">	
							<div class="col-sm-3" style='padding-top:5px; padding-bottom:5px; text-align:right'>
								<label class="form-label" >MIDDLENAME</label>	
							</div>
							<div class="col-sm-9" style='padding-top:5px; padding-bottom:5px; padding-right:10%;'>
								<input type="text" name='hidden_middlename' class="form-control" id="hidden_middlename" readonly="readonly" style="text-transform: capitalize;">
							</div>	
						</div>
						
						<div class="row">	
							<div class="col-sm-3" style='padding-top:5px; padding-bottom:5px; text-align:right'>
								<label class="form-label" >LASTNAME</label>	
							</div>
							<div class="col-sm-9" style='padding-top:5px; padding-bottom:5px; padding-right:10%;'>
								<input type="text" name='hidden_lastname' class="form-control" id="hidden_lastname" readonly="readonly" style="text-transform: capitalize;">
							</div>	
						</div>
						
						<div class="row" >	
							<div class="col-sm-3" style='padding-top:5px; padding-bottom:5px; text-align:right'>
								<label class="form-label" >SUFFIX</label>	
							</div>
							<div class="col-sm-9" style='padding-top:5px; padding-bottom:5px; padding-right:10%;'>
									<input type="text" name='hidden_suffix' class="form-control" id="hidden_suffix" readonly="readonly">
							</div>	
						</div>
					
						
						<div class="row" >	
							<div class="col-sm-3" style='padding-top:5px; padding-bottom:5px; text-align:right'>
								<label class="form-label" >POSITION</label>
							</div>
							<div class="col-sm-9" style='padding-top:5px; padding-bottom:5px; padding-right:10%;'>
								<select class="form-control" name="position" id='position' required>
									<option value=''>Select Position</option>
									<?php

									$result = $this->initial_model->position();
									foreach ($result as $res) 
									{ 
										if($res['poslevel_no'] == '458' || $res['poslevel_no'] == '459' || $res['poslevel_no'] == '522' || $res['poslevel_no'] == '523' || $res['poslevel_no'] == '525')
										{
											?>
												<option value="<?php echo $res['position_title']; ?>"><?php echo $res['position_title']; ?></option> 
											<?php
										}
										
									}?>
								</select>		
							</div>	
						</div>
						
						<div class="form-group">
							<div class="row" >	
								<div class="col-sm-3" style='padding-top:5px; padding-bottom:5px; text-align:right'>
									<label class="form-label" >RESUME</label>	
								</div>
								<div class="col-sm-9" style='padding-top:5px; padding-bottom:5px; padding-right:10%;'>
									<input type="file" name='resume[]' class="form-control" id="resume" multiple="" required>
								</div>	
							</div>
						</div>
						
						<div class="form-group">
							<div class="row" >	
								<div class="col-sm-3" style='padding-top:5px; padding-bottom:5px; text-align:right'>
									<label for="pwd">APPLICATION</label>
								</div>
								<div class="col-sm-9" style='padding-top:5px; padding-bottom:5px; padding-right:10%;'>
									<input type="file" name='application[]' class="form-control" id="application" multiple="" required>
								</div>	
							</div>
						</div>
						
						<div class="form-group">
							<div class="row" >	
								<div class="col-sm-3" style='padding-top:5px; padding-bottom:5px; text-align:right'>
									<label for="pwd">TOR</label>
								</div>
								<div class="col-sm-9" style='padding-top:5px; padding-bottom:5px; padding-right:10%;'>
									<input type="file" name='transcript[]' class="form-control" id="transcript" multiple="" required>
								</div>	
							</div>
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
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header bg-light-blue color-palette">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span></button>
					<h5 class="modal-title">Checking for Application History</h5>
				</div>
				<div class="modal-body dbrowse_blacklist" style='font-size=10px;'>
						
				</div>
				<div class="modal-footer">
					<button type="button" class="btn" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary" id='update_applicant'>Proceed</button>
				</div>
			</div>
		</div>
	</div>
	
	
	<div class="modal fade" id="upload_success_proceed_record">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header bg-light-blue color-palette">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span></button>
					<h5 class="modal-title">Uploading Information</h5>
				</div>
				<div class="modal-body upload_success_proceed_record" style='font-size=10px;'>
						
				</div>
				<div class="modal-footer">
				
				<button type="button" class="btn btn-primary" id='record'>Proceed</button>
				</div>
			</div>
		</div>
	</div>

	
	<div class="modal fade" id="small_alert">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header bg-light-blue color-palette">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span></button>
					<h5 class="modal-title">Information</h5>
				</div>
				<div class="modal-body small_alert_display" style='font-size=10px;'>
						
				</div>
				<div class="modal-footer">
					<button type="button" class="btn" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
	
	<div class="modal fade" id="goProceed">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header bg-light-blue color-palette">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span></button>
					<h5 class="modal-title">Information</h5>
				</div>
				<div class="modal-body goProceed_display" style='font-size=10px;'>
						
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
					<button type="button" class="btn btn-primary" data-dismiss="modal" id='addproceed'>Proceed</button>
				</div>
			</div>
		</div>
	</div>
