<?php
if($request == "applicant_duplicate_or_blacklist")
{
	if(!empty($fetch['blacklist']))
	{
		echo json_encode(array('status'=> 1, 'proceed'=>0, 'message' => "Sorry! We can't proceed!  ".$fetch['blacklist'][0]['name']." has a blacklist history!"));
	}
	elseif(!empty($fetch['duplicate']))
	{
		$name = $fetch['duplicate'][0]['firstname']." ".$fetch['duplicate'][0]['middlename']." ".$fetch['duplicate'][0]['lastname'];
		echo json_encode(array('status'=> 1, 'proceed'=>1, 'message' => "Duplicate found "." '' ".$name." '' "." please update application history!"));	
	}
	else
	{
		echo json_encode(array('status'=> 0, 'proceed'=>1, 'message' => "You can proceed and Add applicant's data/initial requirements.."));
	}	
}
else if($request == "applicant_record")
{
	?>
		<div class="wrapper" style='padding:5px; '>
			<div class="col-sm-6">
				<div class="panel panel-default">
					
					<div class="box box-primary">
					<h4 style='padding-left:20px;'>BASIC INFORMATION</h4>
						<div class="panel-body">
							<div class="form-group"> <span class="text-red">*</span>
								 <label for="firstname">Firstname <i style='color:red; font-size:11px;'> ( Required )</i></label>
								 <input type="text" value='<?=$check_record['applicants']['firstname']?>' name='firstname' class="form-control" id="firstname" readonly required>
							</div>
							<div class="form-group">
								 <label for="suffix">Suffix <i style='color:red; font-size:11px;'></i></label>
								 <input type="text" value='<?=$check_record['applicants']['suffix']?>' name='suffix' class="form-control" id="suffix" readonly>
							</div>
							<div class="form-group">
								 <label for="middlename">Middlename <i style='color:red; font-size:11px;'></i></label>
								 <input type="text" value='<?=$check_record['applicants']['middlename']?>' name='middlename' class="form-control" id="middlename" readonly>
							</div>
							<div class="form-group"> <span class="text-red">*</span>
								 <label for="lastname">Lastname <i style='color:red; font-size:11px;'> ( Required )</i></label>
								 <input type="text" value='<?=$check_record['applicants']['lastname']?>' name='lastname' class="form-control" id="lastname" readonly required>
							</div>
							<div class="form-group"> <span class="text-red">*</span>
								 <label for="birthdate">Date of Birth <i style='color:red; font-size:11px;'> ( Required )</i></label>
								 <input type="text" name='birthdate' class="form-control datepicker inputForm" data-inputmask="'alias': 'mm/dd/yyyy'" data-mask="" id="birthdate" required>
							</div>
							<div class="form-group"> <span class="text-red">*</span>
								 <label for="gender">Gender <i style='color:red; font-size:11px;'> ( Required )</i></label>
								 <select class="form-control" name="gender" required>
										<option value='<?=$check_record['new_details']['gender']?>'><?=$check_record['new_details']['gender']?></option>
										<?php 
											if($check_record['new_details']['gender'] == "Male")
												{ echo "<option value='Female'>Female</option>"; }
											else
												{ echo "<option value='Male'>Male</option>"; }
										?>
								  </select>	
							</div>
							<div class="form-group"> <span class="text-red">*</span>
								 <label for="civilstatus">Civil Status <i style='color:red; font-size:11px;'> ( Required )</i></label>
								 <select class="form-control" name="civilstatus" required onchange="check_status(this.value)">
									<option value='<?=$check_record['new_details']['civilstatus']?>'><?=$check_record['new_details']['civilstatus']?></option>
									<?php if($check_record['new_details']['civilstatus'] == "Single")
										{ 
											echo "<option value='Married'>Married</option>"; 
											echo "<option value='Widowed'>Widowed</option>"; 
											echo "<option value='Separated'>Separated</option>";
											echo "<option value='Divorced'>Divorced</option>";
										}
									else if($check_record['new_details']['civilstatus'] == "Married")	
										{ 
											echo "<option value='Single'>Single</option>"; 
											echo "<option value='Widowed'>Widowed</option>"; 
											echo "<option value='Separated'>Separated</option>";
											echo "<option value='Divorced'>Divorced</option>"; 
										}
									else if($check_record['new_details']['civilstatus'] == "Widowed")	
										{ 
											echo "<option value='Single'>Single</option>";
											echo "<option value='Married'>Married</option>";  
											echo "<option value='Separated'>Separated</option>";
											echo "<option value='Divorced'>Divorced</option>"; 
										}
									else if($check_record['new_details']['civilstatus'] == "Separated")
										{ 
											echo "<option value='Single'>Single</option>";
											echo "<option value='Married'>Married</option>"; 
											echo "<option value='Widowed'>Widowed</option>";
											echo "<option value='Divorced'>Divorced</option>";  
										}
									else if($check_record['new_details']['civilstatus'] == "Divorced")
										{ 
											echo "<option value='Single'>Single</option>";
											echo "<option value='Married'>Married</option>"; 
											echo "<option value='Widowed'>Widowed</option>"; 
											echo "<option value='Separated'>Separated</option>"; 
										}
									?>
								  </select>	
							</div>
							<div class="form-group"> <span class="text-red">*</span>
								 <label for="citizenship">Citizenship <i style='color:red; font-size:11px;'> ( Required )</i></label>
								 <select class="form-control" name="citizenship" required>
									<option value=''>Select Citizenship</option>
									<option value='Filipino'>Filipino</option>
								  </select>	
							</div>
							<div class="form-group"> <span class="text-red">*</span>
								 <label for="religion">Religion <i style='color:red; font-size:11px;'> ( Required )</i></label>
								 <select class="form-control" name="religion" required>
									<option value=''>Select Religion</option>
									<option value='Roman Catholic'>Roman Catholic</option>
									<option value='Pentecostal'>Pentecostal</option>
									<option value='Jehovas Witness'>Jehovas Witness</option>
									<option value='Seventh Day Adventist'>Seventh Day Adventist</option>
									<option value='Iglesia Ni Cristo'>Iglesia Ni Cristo</option>
									<option value='Muslim'>Muslim</option>
									<option value='Born Again Christian'>Born Again Christian</option>
									<option value='Christian'>Christian</option>
								  </select>
							</div>
							<div class="form-group">
								 <label for="weight">Weight</label>
								 <input type="text" name='weight' class="form-control" id="weight">
							</div>
							<div class="form-group">
								 <label for="height">Height</label>
								 <input type="text" name='height' class="form-control" id="height">
							</div>
						</div>
					</div>
				</div>
				<div class="panel panel-default">
					<div class="box box-primary">
						<h4 style='padding-left:20px;'>FAMILY BACKGROUND</h4>
						<div class="panel-body">
							<div class="form-group"> <span class="text-red">*</span>
								 <label for="mother">Mother <i style='color:red; font-size:11px;'> ( Required )</i></label>
								 <input type="text" name='mother' class="form-control" id="mother" autocomplete='off' required>
							</div>
							<div class="form-group"> <span class="text-red">*</span>
								<label for="father">Father <i style='color:red; font-size:11px;'>( Required )</i></label>
								<input type="text" name='father' class="form-control" id="father" autocomplete='off' required>	
							</div>
							<div class="form-group">
								 <label for="guardian">Guardian <i style='color:red; font-size:11px;' class="father-is-required"></i></label>
								 <input type="text" name='guardian' class="form-control" id="guardian" autocomplete='off'>
							</div>
							<div class="form-group">
								 
								 <?php if(in_array($check_record['new_details']['gender'], array('Married', 'Widowed')))
								 {
									 ?>
									 <span class="text-red spouse-is-required">*</span>
									 <label for="spouse">Spouse <i style='color:red; font-size:11px;' class="spouse-is-required"> ( Required )</i></label>
									 <input type="text" name='spouse' class="form-control" id="spouse" autocomplete='off' required>
									 <?php
								 } else {
									 
									 ?>
									 <span class="text-red spouse-is-required"></span>
									 <label for="spouse">Spouse <i style='color:red; font-size:11px;' class="spouse-is-required">( Required if the applicant is Married/Widowed. )</i></label>
									 <input type="text" name='spouse' class="form-control" id="spouse" autocomplete='off'>
									 <?php
								 }?>
								 
							</div>
							<div class="form-group">
								 <label for="no_of_siblings">No of Siblings</label>
								 <input type="number" name='no_of_siblings' class="form-control" id="no_of_siblings">
							</div>
						</div>	
					</div>	
				</div>	
			</div>
			
			<div class="col-sm-6" style="padding-left:5px">
				<div class="panel panel-default">
					<div class="box box-primary">
						<h4 style='padding-left:20px;'>CONTACT INFORMATION</h4>
						<div class="panel-body">
							<div class="form-group"> <span class="text-red">*</span>
								 <label for="address">Address <i style='color:red; font-size:11px;'> ( Required )</i></label>
								 <input list="address" name="address" autocomplete="off" class="form-control" required>
									<datalist id="address">
										<?php foreach ($check_record['town_brgy'] as $i) { echo "<option value='" . $i['brgy_name'] . ", " . $i['town_name'] . ", " . $i['prov_name'] . "'>" . $i['brgy_name'] . ", " . $i['town_name'] . ", " . $i['prov_name'] . "</option>"; } ?>
									</datalist>	
							</div>
							<div class="form-group">
								 <label for="city_address">City Address <i style='color:red; font-size:11px;'></i></label>
								 <input list="city_address" name="city_address" autocomplete="off" class="form-control">
									<datalist id="city_address">
										<?php foreach ($check_record['town_brgy'] as $i) { echo "<option value='" . $i['brgy_name'] . ", " . $i['town_name'] . ", " . $i['prov_name'] . "'>" . $i['brgy_name'] . ", " . $i['town_name'] . ", " . $i['prov_name'] . "</option>"; } ?>
									</datalist>
							</div>
							<div class="form-group"> <span class="text-red">*</span>
								 <label for="cel_number">Cellphone Number <i style='color:red; font-size:11px;'> ( Required )</i></label>
								 <input type="text" data-inputmask='"mask": "+639999999999"' data-mask name='cel_number' class="form-control" id="cel_number" required>
							</div>
							<div class="form-group">
								 <label for="telephone_number">Telephone Number</label>
								 <input type="text" data-inputmask='"mask": "(999) 999-9999"' data-mask name='telephone_number' class="form-control" id="telephone_number">
							</div>
							<div class="form-group">
								 <label for="contact_person">Contact Person</label>
								 <input type="text" name='contact_person' class="form-control" id="contact_person">
							</div>
							<div class="form-group">
								 <label for="contact_person_address">Contact Person Address</label>
								 <input list="contact_person_address" name="contact_person_address" autocomplete="off" class="form-control">
									<datalist id="contact_person_address">
										<?php foreach ($check_record['town_brgy'] as $i) { echo "<option value='" . $i['brgy_name'] . ", " . $i['town_name'] . ", " . $i['prov_name'] . "'>" . $i['brgy_name'] . ", " . $i['town_name'] . ", " . $i['prov_name'] . "</option>"; } ?>
									</datalist>
							</div>
							<div class="form-group">
								 <label for="contact_person_number">Contact Person Number </label>
								 <input type="text" data-inputmask='"mask": "+639999999999"' data-mask name='contact_person_number' class="form-control" id="contact_person_number">
							</div>
							<div class="form-group">
								 <label for="email_address">Email Address</label>
								 <input type="text" name='email_address' class="form-control" id="email_address">
							</div>
							<div class="form-group">
								 <label for="facebook">Facebook Account</label>
								 <input type="text" name='facebook' class="form-control" id="facebook">
							</div>
							<div class="form-group">
								 <label for="twitter">Twitter Account</i></label>
								 <input type="text" name='twitter' class="form-control" id="twitter">
							</div>
						</div>	
					</div>
				</div>
				<div class="panel panel-default">
					<div class="box box-primary" style='margin:0px'>
						<h4 style='padding-left:20px;'>EDUCATIONAL BACKGROUND</h4>
						<div class="panel-body">
							<div class="form-group"> <span class="text-red">*</span>
								 <label for="education">Educational Attainment <i style='color:red; font-size:11px;'> ( Required )</i></label>
								 <input list="education" name="education" autocomplete="off" class="form-control">
									<datalist id="education">
										<?php $result_education = $this->initial_model->attainment(); foreach ($result_education as $i)  { ?> 
											<option value='<?=$i['attainment']?>'><?=$i['attainment']?></option>
										<?php } ?>
									</datalist>
								 
							</div>
							<div class="form-group"> <span class="text-red">*</span>
								 <label for="school">School <i style='color:red; font-size:11px;'> ( Required )</i></label>
								 <input list="school" name="school" autocomplete="off" class="form-control">
									<datalist id="school">
										<?php $result_education = $this->initial_model->school(); foreach ($result_education as $i)  { ?> 
											<option value='<?=$i['school_name']?>'><?=$i['school_name']?></option>
										<?php } ?>
									</datalist>
							</div>
							<div class="form-group"> <span class="text-red">*</span>
								 <label for="course">Details / Course <i style='color:red; font-size:11px;'> ( Required )</i></label>
								 <input list="course" name="course" autocomplete="off" class="form-control">
									<datalist id="course">
										<?php $result_education = $this->initial_model->course(); foreach ($result_education as $i)  { ?> 
											<option value='<?=$i['course_name']?>'><?=$i['course_name']?></option>
										<?php } ?>
									</datalist>
							</div>
						</div>
							<h4 style='padding-left:20px;'>SPECIAL SKILLS / TALENTS</h4>
							<div class="panel-body">
								<div class="form-group">
									 <label for="hobbies">Hobbies</label>
									 <input type="text" name='hobbies' class="form-control" id="hobbies">
								</div>
								<div class="form-group">
									 <label for="special_skill">Special skills / Talents <i style='color:red; font-size:11px;'></i></label>
									 <input type="text" name='special_skill' class="form-control" id="special_skill">
								</div>
							</div>	
					</div>	
				</div>	
			</div>  
		</div>
		<div class="wrapper" style='padding:5px;'>
			<div class="col-sm-12" style="padding-left:5px">
				<div class="panel panel-default">
					<div class="box box-primary">
						<h4 style='padding-left:20px;'>ELIGIBILITY / SEMINARS / TRAININGS</h4>
						<div class="panel-body">
							<div class="form-group">
								<table  id="seminar" class="order-list1">
								<thead>
									<th style='text-align:center; width:30%'>Title / Name</th>
									<th style='text-align:center; width:30%'>Location (Seminar / Training)</th>
									<th style='text-align:center;width:10%'>Year</th>
									<th style='text-align:center; width:20%'>Certificate</th>
									
								</thead>
								<tbody>
										<tr>
										  
										  <td><input name="seminar_name[]" type="text" class="form-control"></td>
										  <td><input name="seminar_location[]"  type="text" class="form-control"></td>
										  <td><input name="seminar_year[]" type="text" class="form-control"></td>
										  <td><input name="seminar_certificate[]" id='seminarcert' onchange='validate(this.id)' type="file" class="form-control"></td>
										  <td><a class="deleteRow"></a></td>
										</tr>		
								</tbody>
								</table>
								<input type="button" id="addrowsem" value="Add Row">
							</div>
						</div>
					</div>
					<div class="box box-primary">	
						<h4 style='padding-left:20px;'>CHARACTER REFERENCES</h4>
						<div class="panel-body">
							<div class="form-group">
								<table  id="character_ref" class="character_ref-list">
								<thead>
									<th style='text-align:center; width:25%'>Name of Reference</th>
									<th style='text-align:center; width:25%'>Position / Designation</th>
									<th style='text-align:center; width:15%'>Contact Number</th>
									<th style='text-align:center; width:25%'>Company / Location / Address</th>	
								</thead>
								<tbody>
										<tr>
											<td><input type="text" name='character_name[]' class="form-control" id="company_name"></td>
											<td><input type="text" name='character_position[]' class="form-control" id="position"></td>
											<td><input type="text" name='character_contact[]' class="form-control" id="year_start"></td>
											<td><input type="text" name='character_address[]' class="form-control" id="year_end"></td>
											<td><a class="delete_row_character"></a></td>
										</tr>
								</tbody>
								</table>
								<input type="button" id="add_row_character" value="Add Row">
							</div>
						</div>	
					</div>
					<div class="box box-primary">	
						<h4 style='padding-left:20px;'>EMPLOYMENT HISTORY ( Outside Company )</h4>
						<div class="panel-body">
							<div class="form-group">
								<table  id="comp_history" class="company_order-list">
								<thead>
									<th style='text-align:center; width:20%'>Company Name (Outside Alturas Group)</th>
									<th style='text-align:center; width:20%'>Position</th>
									<th style='text-align:center; width:10%'>Year Started</th>
									<th style='text-align:center; width:10%'>Year Ended</th>
									<th style='text-align:center; width:20%'>Company / Location / Address</th>
									<th style='text-align:center; width:10%'>Certificate</th>
									
								</thead>
								<tbody>
										<tr>
											<td><input type="text" name='company_name[]' class="form-control" id="company_name"></td>
											<td><input type="text" name='position[]' class="form-control" id="position"></td>
											<td><input type="text" name='year_start[]' class="form-control" id="year_start"></td>
											<td><input type="text" name='year_end[]' class="form-control" id="year_end"></td>
											<td><input type="text" name='company_address[]' class="form-control" id="company_address"></td>
											<td><input type="file" name='certificate[]' class="form-control" id="certificate"></td>
											<td><a class="delete_row_history"></a></td>
										</tr>
								</tbody>
								</table>
								<input type="button" id="add_row_history" value="Add Row">
							</div>
						</div>	
					</div>
				</div>
				
				<div class="panel panel-default">
					<div class="box box-primary">	
						<h4 style='padding-left:20px;'>APPLICATION DETAILS</h4>
						<div class="panel-body">
							<div class="form-group">
								<div class="form-group">
									 <label for="vacancy_source">Vacancy Source <i style='color:red; font-size:11px;'></i></label>
									 <select name='vacancy_source' class="form-control" id="vacancy_source" style='width:50%;'>
										<option value="">SELECT SOURCE</option>
										<option value="Fliers/ Tabloid">Fliers/Tabloid</option>
										<option value="Radio">Radio</option>
										<option value="Newspaper">Newspaper</option>
										<option value="Internal Refferal">Internal Refferal</option>
										<option value="Job Fair">Job Fair</option>
									</select>
								</div>
								<div class="form-group">
									 <label for="date_applied">Date Applied <i style='color:red; font-size:11px;'></i></label>
									 <input type="text" value='<?=$check_record['applicants']['date_time']?>' name='date_applied' class="form-control" id="date_applied" style='width:50%;' readonly>
								</div>
								<div class="form-group">
									 <label for="position_applied">Position Applied <i style='color:red; font-size:11px;'></i></label>
									 <input type="text" value='<?=$check_record['applicants']['position']?>' name='position_applied' class="form-control" id="position_applied" style='width:50%;' readonly>
								</div>
								<div class="form-group">
									 <label for="applicant_status">Application Status <i style='color:red; font-size:11px;'></i></label>
									 <input type="text" name='applicant_status' value="Initial Completion" class="form-control" id="applicant_status" style='width:50%;' readonly>
								</div>
								<div class="form-group">
									<input type="text" value='<?=$check_record['applicants']['app_code']?>' name='application_code' class="form-control" style='width:10%' readonly>
								</div>
								
							</div>
						</div>
					</div>
				</div>	
			</div>
		</div>
		
		
		
		
		<script type="text/javascript">
		var counter1 = 0;
		$("#addrowsem").on("click", function () 
		{
			var counter1 = $('#seminar tr').length - 2;
			var newRow = $("<tr>");
			var cols = "";
			cols += '<td><input type="text" name="seminar_name[]" class="form-control" ></td>';
			cols += '<td><input type="text" name="seminar_location[]" class="form-control" ></td>';
			cols += '<td><input type="text" name="seminar_year[]" id="seminar' + counter1 + '" onchange="check(this)" class="form-control" ></td>';
			cols += '<td><input name="seminar_certificate[]" id="seminarcert['+counter1+']" onchange="validate(this.id)" type="file" class="form-control" ></td>';
			cols += '<td><input type="button" class="ibtnDelsem" value="Delete" class="form-control" ></td>';
			newRow.append(cols);
			$("table.order-list1").append(newRow);
			counter1++;
		});
		
		$("table.order-list1").on("click", ".ibtnDelsem", function (event) 
		{
			$(this).closest("tr").remove();
			counter1--;
			$('#addrowsem').prop('disabled', false).prop('value', "Add row");
		});
	
		// company history
		var cntr = 0;
		$("#add_row_history").on("click", function () 
		{
			var cntr = $('#comp_history tr').length - 2;
			var newRow = $("<tr>");
			var cols = "";
			cols += '<td><input type="text" name="company_name[]" class="form-control" ></td>';
			cols += '<td><input type="text" name="position[]" class="form-control" ></td>';
			cols += '<td><input type="text" name="year_start[]" id="comp_history' + cntr + '" onchange="check(this)" class="form-control" ></td>';
			cols += '<td><input type="text" name="year_end[]" id="comp_history' + cntr + '" onchange="check(this)" class="form-control" ></td>';
			cols += '<td><input type="text" name="company_address[]" class="form-control" ></td>';
			cols += '<td><input name="certificate[]" id="certificate['+cntr+']" onchange="validate(this.id)" type="file" class="form-control" ></td>';
			cols += '<td><input type="button" class="ibtnDelsem_history" value="Delete" class="form-control" ></td>';
			newRow.append(cols);
			$("table.company_order-list").append(newRow);
			cntr++;
		});
		
		$("table.company_order-list").on("click", ".ibtnDelsem_history", function (event) 
		{
			$(this).closest("tr").remove();
			cntr--;
			$('#add_row_history').prop('disabled', false).prop('value', "Add row");
		});
		
		
		// company history
		var countr = 0;
		$("#add_row_character").on("click", function () 
		{
			var countr = $('#character_ref tr').length - 2;
			var newRow = $("<tr>");
			var cols = "";
			cols += '<td><input type="text" name="character_name[]" class="form-control" ></td>';
			cols += '<td><input type="text" name="character_position[]" class="form-control" ></td>';
			cols += '<td><input type="text" name="character_contact[]" id="character_ref' + countr + '" onchange="check(this)" class="form-control" ></td>';
			cols += '<td><input type="text" name="character_address[]" id="character_ref' + countr + '" onchange="check(this)" class="form-control" ></td>';
			cols += '<td><input type="button" class="ibtnDelsem_character" value="Delete" class="form-control" ></td>';
			newRow.append(cols);
			$("table.character_ref-list").append(newRow);
			countr++;
		});
		
		$("table.character_ref-list").on("click", ".ibtnDelsem_character", function (event) 
		{
			$(this).closest("tr").remove();
			countr--;
			$('#add_row_character').prop('disabled', false).prop('value', "Add row");
		});
		
		$('.datepicker').datepicker({
            inline: true,
            changeYear: true,
            changeMonth: true
        });

        $("[data-mask]").inputmask();
		</script>
	<?php
}

?>


