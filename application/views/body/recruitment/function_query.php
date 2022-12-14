<?php
	if($request == "applicant_duplicate_or_blacklist")
	{					
		//echo count($blacklist_suggest);
		//if(count($blacklist) == 0 && count($duplicate) == 0 && count($duplicate_MI) == 0 && count($blacklist_suggest) == 0)
		if(count($blacklist) == 0 && count($duplicate) == 0 && count($duplicate_MI) == 0)
		{
			?>
			<div class="row">
				<div class="col-sm-12">No Entry Found! You can proceed and Add applicant's data.</div>
			</div><input type='hidden' name='procedure' value='INSERT'>
			<?php
		}
		else
		{
			if(count($blacklist) > 0)
			{
				foreach ($blacklist as $i) 
				{ 
					?>
						<div class="row">
							<div class="col-sm-6"><?= $i['name']; ?>( <i style='color:red;'>Blacklisted</i> )</div>
							<div class="col-sm-6"><?= $i['reason']; ?></div>
						</div><input type='hidden' name='procedure' value='BLACKLIST'>
					<?php
				} 
			}
			/* else if(count($blacklist_suggest) > 0)
			{
				foreach ($blacklist_suggest as $i) 
				{ 
					?>
						<div class="row">
							<div class="col-sm-6"><?= $i['name']; ?>( <i style='color:red;'>Blacklisted?</i> )</div>
							<div class="col-sm-6"><?= $i['reason']; ?></div>
						</div><input type='hidden' name='procedure' value='BLACKLIST'>
					<?php
				} 
			} */
			else
			{
				foreach ($duplicate as $key => $n) 
				{
					?>
						<div class="row" style='padding-left:5px; border-bottom:1px solid grey;'>	
							<div class="col-sm-8" style='padding-top:5px; padding-bottom:5px;'>
								<span style='display:none;' id="<?= $key ?>"><?= $n['app_code']."|".$n['lastname']."|".$n['firstname']."|".$n['middlename'];?></span>
								<span><?= $n['app_code']." | ".$n['lastname'].", ".$n['firstname']." ".$n['middlename'];?>( <i style='color:red;'>Duplicate?</i> )</span>
							</div>
							<div class="col-sm-4" style='padding-top:5px; padding-bottom:5px;'>
								<input type="checkbox" name="duplicate[]" value="<?= $key; ?>">
							</div>	
						</div><input type='hidden' name='procedure' value='UPDATE'>
					<?php 				
				}
				
				foreach ($duplicate_MI as $keys => $n) 
				{ 
					?>
					<div class="row" style='padding-left:5px; border-bottom:1px solid grey;'>
						<div class="col-sm-8" style='padding-top:5px; padding-bottom:5px;'>
							<span style='display:none;' id="<?= $keys ?>"><?= $n['app_code']."|".$n['lastname']."|".$n['firstname']."|".$n['middlename'];?></span>
							<span><?= $n['app_code']." | ".$n['lastname'].", ".$n['firstname']." ".$n['middlename'];?> ( <i style='color:red;'>Duplicate?</i> )</span>
						</div>
						<div class="col-sm-4" style='padding-top:5px; padding-bottom:5px;'>
							<input type="checkbox" name="duplicate_MI[]" value="<?= $keys; ?>">
						</div>
					</div><input type='hidden' name='procedure' value='UPDATE'>	
					<?php 
				}
			}
		}
	}
	else if($request == "final_completion_seassonal")
	{
		?>
		<div class="panel-body">
			<div class="box box-primary">
				<h4 style='padding-left:0px; padding-right:0px;'><u>APPLICANT INFORMATION</u></h4>
				<div class="panel-body" style='padding-left:0px; padding-right:0px;'>
					<div class="col-sm-12" style='padding-left:0px; padding-right:0px;'>
						
						<div class="col-sm-6" style='padding-left:0px; padding-right:5px;'>
							<div class="form-group">
								 <label for="hrmsId">APPLICANT ID</label>
								 <input type="hidden" name='appcode' class="form-control" id="appcode"  value='<?=$finale['appcode']?>' readonly>
								 <input type="text" name='appid' class="form-control" id="appid"  value='<?=$finale['app_id']?>' readonly >
							</div>
						</div>
						
						<div class="col-sm-6" style='padding-left:5px; padding-right:0px;'>
							<div class="form-group">
								 <label for="hrmsId">APPLICANT NAME</label>
								 <input type="text" name='name' class="form-control" id="name"  value='<?=$finale['lastname'].", ".$finale['firstname']." ".$finale['middlename']." ".$finale['suffix']?>' readonly >
							</div>
						</div>
						
					</div>
				</div>	
			</div>
			
			<div class="box box-primary">
				<h4 style='padding-left:0px; padding-right:0px;'><u>FINAL REQUIREMENTS</u></h4>
				<div class="panel-body" style='padding-left:0px; padding-right:0px;'>
					<div class="col-sm-12" style='padding-left:0px; padding-right:0px;'>
						
						<div class="col-sm-6" style='padding-left:0px; padding-right:5px;'>
							<!-- <div class="form-group">
								<label for="hrmsId">BLOOD TYPE</label><span class="text-red spouse-is-required"> * ( Required )</span>
								<select class="form-control" name="bloodtype" required>
									<option value=''>Select Type</option>
									<option value='A+'>A+</option>
									<option value='A-'>A-</option>
									<option value='B+'>B+</option>
									<option value='B-'>B-</option>
									<option value='AB+'>AB+</option>
									<option value='AB-'>AB-</option>
									<option value='O+'>O+</option>
									<option value='O-'>O-</option>
								</select>
							</div> -->
							
							
							<!-- <div class="form-group">
								 <label for="hrmsId">BIRTH CERTIFICATE</label><span class="text-red spouse-is-required"> * ( Required )</span>
								 <input type="file" name='birthcertificate[]' class="form-control" id="birthcertificate" multiple="" required>
							</div> -->
							<div class="form-group">
								 <label for="hrmsId">POLICE CLEARANCE</label><span class="text-red spouse-is-required"> * ( Required )</span>
								 <input type="file" name='police_clearance' class="form-control" id="police_clearance" required>
							</div>
							<!-- <div class="form-group">
								 <label for="hrmsId">FINGERPRINT</label><span class="text-red spouse-is-required"> * ( Required )</span>
								 <input type="file" name='fingerprint' class="form-control" id="fingerprint" required>
							</div> -->
							<!-- <div class="form-group">
								 <label for="hrmsId">SSS</label><span class="text-red spouse-is-required"> * ( Required )</span>
								 <input type="file" name='sss' class="form-control" id="sss" required>
							</div> -->
							<!-- <div class="form-group">
								 <label for="hrmsId">CEDULA</label><span class="text-red spouse-is-required"> * ( Required )</span>
								 <input type="file" name='cedula' class="form-control" id="cedula" required>
							</div> -->
							<!-- <div class="form-group">
								 <label for="hrmsId">PARENT CONSENT</label><span class="text-red spouse-is-required"> * ( Required )</span>
								 <input type="file" name='parentconsent' class="form-control" id="parentconsent" required>
							</div> -->
							<!-- <div class="form-group">
								 <label for="hrmsId">MEDICAL CERTIFICATE</label><span class="text-red spouse-is-required"> * ( Required )</span>
								 <input type="file" name='medical' class="form-control" id="medical" required>
							</div> -->
							<!-- <div class="form-group">
								 <label for="hrmsId">HOUSE ADDRESS SKETCH</label><span class="text-red spouse-is-required"> * ( Required )</span>
								 <input type="file" name='house_skecth' class="form-control" id="house_skecth" required>
							</div> -->
							<!-- <div class="form-group">
								 <label for="hrmsId">PAG-IBIG TRACKING</label><span class="text-red spouse-is-required"> * ( Required )</span>
								 <input type="text" data-inputmask='"mask": "9999-9999-9999"' data-mask name='pagibig_track' class="form-control" id="pagibig_track" required>
							</div> -->
							<!-- <div class="form-group">
								 <label for="hrmsId">PAG-IBIG MID</label>
								 <input type="text" data-inputmask='"mask": "9999-9999-9999"' data-mask name='pagibig_mid' class="form-control" id="pagibig_mid" >
							</div> -->
							<div class="form-group">
								 <label for="hrmsId"><a href="#" id="addOtherDocs">* Add other documents</a></label>
								 <table  id="seminar" class="order-list1">
									
									<tbody>
											<tr>
											  <td><input name="documentName[]" type="text" class="form-control"></td>
											  <td><input name="otherDoc[]" id='otherDoc' onchange='validate(this.id)' type="file" class="form-control"></td>
											  <td><a class="deleteRow"></a></td>
											</tr>		
									</tbody>
									</table>
							</div>
						</div>
						
						<div class="col-sm-6" style='padding-left:5px; padding-right:0px;'>
							<!-- <div class="form-group">
								<label for="hrmsId">BACKGROUND INVESTIGATION</label><span class="text-red spouse-is-required"> * ( Required )</span>
								<input type="file" name='background_investagation' class="form-control" id="background_investagation" required>
							</div> -->
							<!-- <div class="form-group">
								<label for="hrmsId">DRUG TEST</label><span class="text-red spouse-is-required"> * ( Required )</span>
								<input type="file" name='drugtest' class="form-control" id="drugtest" required>
							</div> -->
							<div class="form-group">
								<label for="hrmsId">RECOMMENDATION LETTER</label><span class="text-red spouse-is-required"> * ( Required )</span>
								<input type="file" name='recommend_letter' class="form-control" id="recommend_letter" required>
							</div>
							<!-- <div class="form-group">
								<label for="hrmsId">MARRIAGE CERTIFICATE</label><span class="text-red spouse-is-required"> * ( Required )</span>
								<input type="file" name='marriage' class="form-control" id="marriage" required>
							</div> -->
							<!-- <div class="form-group">
								 <label for="hrmsId">SSS ID</label><span class="text-red spouse-is-required"> * ( Required )</span>
								 <input type="text" data-inputmask='"mask": "99-9999999-9"' data-mask name='sss_id' class="form-control" id="sss_id" required>
							</div> -->
							<!-- <div class="form-group">
								 <label for="hrmsId">ID CARD No</label>
								 <input type="text" data-inputmask='"mask": "99999999"' data-mask name='id_card' class="form-control" id="id_card" >
							</div> -->
							<!-- <div class="form-group">
								 <label for="hrmsId">CTC No</label><span class="text-red spouse-is-required"> * ( Required )</span>
								 <input type="text" data-inputmask='"mask": "CCI9999 99999999"' data-mask name='ctc_no' class="form-control" id="ctc_no" required>
							</div> -->
							<!-- <div class="form-group">
								 <label for="hrmsId">Issued On (CTC)</label><span class="text-red spouse-is-required"> * ( Required )</span>
								 <input type="text" data-inputmask='"mask": "9999-99-99"' data-mask name='issued_on_ctc' class="form-control" id="issued_on_ctc" placeholder='yyyy-mm-dd' required>
							</div> -->
							<!-- <div class="form-group">
								 <label for="hrmsId">Issued At (CTC)</label><span class="text-red spouse-is-required"> * ( Required )</span>
									<input list="issued_at_ctc" name="issued_at_ctc" autocomplete="off" class="form-control" required>
									<datalist id="issued_at_ctc">
									<?php foreach ($check_record['town_brgy'] as $i) { echo "<option value='" . $i['brgy_name'] . ", " . $i['town_name'] . ", " . $i['prov_name'] . "'>" . $i['brgy_name'] . ", " . $i['town_name'] . ", " . $i['prov_name'] . "</option>"; } ?>
									</datalist>
							</div> -->
							<!-- <div class="form-group">
								 <label for="hrmsId">Philhealth No</label>
								 <input type="text" data-inputmask='"mask": "99-999999999-9"' data-mask name='philhealth' class="form-control" id="philhealth">
							</div> -->
							<div class="form-group">
								<label for="hrmsId">Final Completion Remarks</label>
								<textarea name='finalremarks' class="form-control" id="finalremarks" style='resize:none; height:110px;'></textarea>
							</div>
						</div>
						
					</div>	
				</div>
			</div>
		</div>
		
		<script type="text/javascript">
		var counter1 = 0;
			
		$("#addOtherDocs").on("click", function () 
		{
			var counter1 = $('#documentName tr').length - 2;
			var newRow = $("<tr>");
			var cols = "";
			cols += '<td><input type="text" name="documentName[]" id="documentName' + counter1 + '" onchange="check(this)" class="form-control" ></td>';
			cols += '<td><input name="otherDoc[]" id="otherDoc['+counter1+']" onchange="validate(this.id)" type="file" class="form-control" ></td>';
			cols += '<td><input type="button" class="ibtnDelsem" value="Delete" class="form-control" ></td>';
			newRow.append(cols);
			$("table.order-list1").append(newRow);
			counter1++;
		});
		
		$("table.order-list1").on("click", ".ibtnDelsem", function (event) 
		{
			$(this).closest("tr").remove();
			counter1--;
			$('#addOtherDocs').prop('disabled', false).prop('value', "Add row");
		});
			
		$("[data-mask]").inputmask();
		
		</script>
		<?php
	}
	else if($request == "final_completion")
	{
		?>
		<div class="panel-body">
			<div class="box box-primary">
				<h4 style='padding-left:0px; padding-right:0px;'><u>APPLICANT INFORMATION</u></h4>
				<div class="panel-body" style='padding-left:0px; padding-right:0px;'>
					<div class="col-sm-12" style='padding-left:0px; padding-right:0px;'>
					<?php // computation of AGE
						$dateOfBirth = $finale['birthdate'];
						$today = date("Y-m-d");
						$diff = date_diff(date_create($dateOfBirth), date_create($today));
						$age_of_app = $diff->format('%y');
					?>
					<input type="text"  class="form-control"  value='<?=$finale['civilstatus']." and the AGE is ".$age_of_app;?>'>
					<div class="col-sm-6" style='padding-left:0px; padding-right:5px;'>
							<div class="form-group">
								 <label for="hrmsId">APPLICANT ID</label>
								 <input type="hidden" name='appcode' class="form-control" id="appcode"  value='<?=$finale['appcode']?>' readonly>
								 <input type="text" name='appid' class="form-control" id="appid"  value='<?=$finale['app_id']?>' readonly >
							</div>
						</div>
						
						<div class="col-sm-6" style='padding-left:5px; padding-right:0px;'>
							<div class="form-group">
								 <label for="hrmsId">APPLICANT NAME</label>
								 <input type="text" name='name' class="form-control" id="name"  value='<?=$finale['lastname'].", ".$finale['firstname']." ".$finale['middlename']." ".$finale['suffix']?>' readonly >
							</div>
						</div>
						
					</div>
				</div>	
			</div>
			
			<div class="box box-primary">
				<h4 style='padding-left:0px; padding-right:0px;'><u>FINAL REQUIREMENTS</u></h4>
				<div class="panel-body" style='padding-left:0px; padding-right:0px;'>
					<div class="col-sm-12" style='padding-left:0px; padding-right:0px;'>
						
						<div class="col-sm-6" style='padding-left:0px; padding-right:5px;'>
							<div class="form-group">
								<label for="hrmsId">BLOOD TYPE</label><span class="text-red spouse-is-required"> * ( Required )</span>
								<select class="form-control" name="bloodtype" required>
									<option value=''>Select Type</option>
									<option value='A+'>A+</option>
									<option value='A-'>A-</option>
									<option value='B+'>B+</option>
									<option value='B-'>B-</option>
									<option value='AB+'>AB+</option>
									<option value='AB-'>AB-</option>
									<option value='O+'>O+</option>
									<option value='O-'>O-</option>
								</select>
							</div>
							
							
							<div class="form-group">
								 <label for="hrmsId">BIRTH CERTIFICATE</label><span class="text-red spouse-is-required"> * ( Required )</span>
								 <input type="file" name='birthcertificate[]' class="form-control" id="birthcertificate" multiple="" required onchange="validateForm(this.id)">
							</div>
							<div class="form-group">
								 <label for="hrmsId">POLICE CLEARANCE</label><span class="text-red spouse-is-required"> * ( Required )</span>
								 <input type="file" name='police_clearance' class="form-control" id="police_clearance" required onchange="validateForm(this.id)">
							</div>
							<div class="form-group">
								 <label for="hrmsId">FINGERPRINT</label><span class="text-red spouse-is-required"> * ( Required )</span>
								 <input type="file" name='fingerprint' class="form-control" id="fingerprint" required onchange="validateForm(this.id)">
							</div>
							<div class="form-group">
								 <label for="hrmsId">SSS</label><span class="text-red spouse-is-required"> * ( Required )</span>
								 <input type="file" name='sss' class="form-control" id="sss" required onchange="validateForm(this.id)">
							</div>
							<div class="form-group">
								 <label for="hrmsId">CEDULA</label><span class="text-red spouse-is-required"> * ( Required )</span>
								 <input type="file" name='cedula' class="form-control" id="cedula" required onchange="validateForm(this.id)">
							</div>
							<div class="form-group">
								<?php // condition for AGE is below 18  
									if($age_of_app < 18) { ?> 
									<label for="hrmsId">PARENT CONSENT</label><span class="text-red spouse-is-required"> * ( Required )</span>
									<input type="file" name='parentconsent' class="form-control" id="parentconsent" required onchange="validateForm(this.id)">
								<?php } else { ?>
									<label for="hrmsId">PARENT CONSENT</label><span class="text-red spouse-is-required"></span>
									<input type="file" name='parentconsent' class="form-control" id="parentconsent">
								<?php } ?> 
							</div>
							<div class="form-group">
								 <label for="hrmsId">MEDICAL CERTIFICATE</label><span class="text-red spouse-is-required"> * ( Required )</span>
								 <input type="file" name='medical' class="form-control" id="medical" required onchange="validateForm(this.id)">
							</div>
							<div class="form-group">
								 <label for="hrmsId">HOUSE ADDRESS SKETCH</label><span class="text-red spouse-is-required"> * ( Required )</span>
								 <input type="file" name='house_skecth' class="form-control" id="house_skecth" required onchange="validateForm(this.id)">
							</div>
							<div class="form-group">
								 <label for="hrmsId">PAG-IBIG TRACKING</label><span class="text-red spouse-is-required"> * ( Required )</span>
								 <input type="text" data-inputmask='"mask": "9999-9999-9999"' data-mask name='pagibig_track' class="form-control" id="pagibig_track" required>
							</div>
							<div class="form-group">
								 <label for="hrmsId">PAG-IBIG MID</label>
								 <input type="text" data-inputmask='"mask": "9999-9999-9999"' data-mask name='pagibig_mid' class="form-control" id="pagibig_mid" >
							</div>
							<div class="form-group">
								 <label for="hrmsId"><a href="#" id="addOtherDocs">* Add other documents</a></label>
								 <table  id="seminar" class="order-list1">
									
									<tbody>
											<tr>
											  <td><input name="documentName[]" type="text" class="form-control"></td>
											  <td><input name="otherDoc[]" id='otherDoc' onchange='validate(this.id)' type="file" class="form-control"></td>
											  <td><a class="deleteRow"></a></td>
											</tr>		
									</tbody>
									</table>
							</div>
						</div>
						
						<div class="col-sm-6" style='padding-left:5px; padding-right:0px;'>
							<div class="form-group">
								<label for="hrmsId">BACKGROUND INVESTIGATION</label><span class="text-red spouse-is-required"> * ( Required )</span>
								<input type="file" name='background_investagation' class="form-control" id="background_investagation" required onchange="validateForm(this.id)">
							</div>
							<div class="form-group">
								<label for="hrmsId">DRUG TEST</label><span class="text-red spouse-is-required"> * ( Required )</span>
								<input type="file" name='drugtest' class="form-control" id="drugtest" required onchange="validateForm(this.id)">
							</div>
							<div class="form-group">
								<label for="hrmsId">RECOMMENDATION LETTER</label><span class="text-red spouse-is-required"> * ( Required )</span>
								<input type="file" name='recommend_letter' class="form-control" id="recommend_letter" required onchange="validateForm(this.id)">
							</div>
							<div class="form-group">
								<?php if(strtoupper($finale['civilstatus']) != "SINGLE") {?>
									<label for="hrmsId">MARRIAGE CERTIFICATE</label><span class="text-red spouse-is-required"> * ( Required )</span>
									<input type="file" name='marriage' class="form-control" id="marriage" required onchange="validateForm(this.id)">
								<?php } else { ?>
									<label for="hrmsId">MARRIAGE CERTIFICATE</label><span class="text-red spouse-is-required"></span>
									<input type="file" name='marriage' class="form-control" id="marriage" >
								<?php }?>
							</div>
							<div class="form-group">
								 <label for="hrmsId">SSS ID</label><span class="text-red spouse-is-required"> * ( Required )</span>
								 <input type="text" data-inputmask='"mask": "99-9999999-9"' data-mask name='sss_id' class="form-control" id="sss_id" required>
							</div>
							<div class="form-group">
								 <label for="hrmsId">ID CARD No</label>
								 <input type="text" data-inputmask='"mask": "99999999"' data-mask name='id_card' class="form-control" id="id_card" >
							</div>
							<div class="form-group">
								 <label for="hrmsId">CTC No</label><span class="text-red spouse-is-required"> * ( Required )</span>
								 <input type="text" data-inputmask='"mask": "CCI9999 99999999"' data-mask name='ctc_no' class="form-control" id="ctc_no" required>
							</div>
							<div class="form-group">
								 <label for="hrmsId">Issued On (CTC)</label><span class="text-red spouse-is-required"> * ( Required )</span>
								 <input type="text" data-inputmask='"mask": "9999-99-99"' data-mask name='issued_on_ctc' class="form-control" id="issued_on_ctc" placeholder='yyyy-mm-dd' required>
							</div>
							<div class="form-group">
								 <label for="hrmsId">Issued At (CTC)</label><span class="text-red spouse-is-required"> * ( Required )</span>
									<input list="issued_at_ctc" name="issued_at_ctc" autocomplete="off" class="form-control" required>
									<datalist id="issued_at_ctc">
									<?php foreach ($check_record['town_brgy'] as $i) { echo "<option value='" . $i['brgy_name'] . ", " . $i['town_name'] . ", " . $i['prov_name'] . "'>" . $i['brgy_name'] . ", " . $i['town_name'] . ", " . $i['prov_name'] . "</option>"; } ?>
									</datalist>
							</div>
							<div class="form-group">
								 <label for="hrmsId">Philhealth No</label>
								 <input type="text" data-inputmask='"mask": "99-999999999-9"' data-mask name='philhealth' class="form-control" id="philhealth">
							</div>
							<div class="form-group">
								<label for="hrmsId">Final Completion Remarks</label>
								<textarea name='finalremarks' class="form-control" id="finalremarks" style='resize:none; height:110px;'></textarea>
							</div>
						</div>
						
					</div>	
				</div>
			</div>
		</div>
		
		<script type="text/javascript">
		var counter1 = 0;
			
		$("#addOtherDocs").on("click", function () 
		{
			var counter1 = $('#documentName tr').length - 2;
			var newRow = $("<tr>");
			var cols = "";
			cols += '<td><input type="text" name="documentName[]" id="documentName' + counter1 + '" onchange="check(this)" class="form-control" ></td>';
			cols += '<td><input name="otherDoc[]" id="otherDoc['+counter1+']" onchange="validate(this.id)" type="file" class="form-control" ></td>';
			cols += '<td><input type="button" class="ibtnDelsem" value="Delete" class="form-control" ></td>';
			newRow.append(cols);
			$("table.order-list1").append(newRow);
			counter1++;
		});
		
		$("table.order-list1").on("click", ".ibtnDelsem", function (event) 
		{
			$(this).closest("tr").remove();
			counter1--;
			$('#addOtherDocs').prop('disabled', false).prop('value', "Add row");
		});
			
		$("[data-mask]").inputmask();
		
		</script>
		<?php
	}
	else if($request == "check_interview_detail")
	{
		?>
		<div class="panel-body">
			<div class="form-group">
				 <label for="hrmsId">APPLICANT ID</label>
				 <input type="hidden" name='appcode' class="form-control" id="appcode"  value='<?=$applicant_examinee['appcode']?>' readonly required>
				 <input type="text" name='appid' class="form-control" id="appid"  value='<?=$applicant_examinee['app_id']?>' readonly >
			</div>
			
			<div class="form-group">
				 <label for="hrmsId">APPLICANT NAME</label>
				 <input type="text" name='name' class="form-control" id="name"  value='<?=$applicant_examinee['lastname'].", ".$applicant_examinee['firstname']." ".$applicant_examinee['middlename']." ".$applicant_examinee['suffix']?>' readonly >
			</div>
			
			<div class="form-group">
				 <label for="hrmsId">Initial Remarks</label>
				 <textarea name='initialRemark' class="form-control" id="initialRemark"  style='resize:none; height:100px;' readonly><?=$interview_remarks['interviewer_remarks']?> </textarea>
			</div>
			
			<div class="form-group">
				<label for="hrmsId">FINAL INTERVIEWER</label>
				<table class='table table-bordered table-hover dataTable dtr-inline' id='ty'>
					<thead>
						<th style='width:60%; background-color:lightblue;'>INTERVIEWER</th>
						<th style='width:20%; background-color:lightblue;'>GRADE</th>
						<th style='width:20%; background-color:lightblue;'>ACTION</th>
					</thead>
					<tbody>
						<tr>
							<input type='hidden' name='grade' value='<?=$this->initial_model->getGrade($interviewer_list['interview_code'])['num_rate']?>'>
							<td style='background-color:lightblue;'><u><?=$this->initial_model->getName($interviewer_list['interviewer_id'])?></u></td>
							<td style='color:red; background-color:lightblue;'>
							<?=$this->initial_model->getGrade($interviewer_list['interview_code'])['num_rate']." - ".
							$this->initial_model->getGrade($interviewer_list['interview_code'])['desc_rate']?></td>
							<td><button type="button" class="btn btn-default" id='sheet'>Print Sheet</button><td>
						</tr>	
					</tbody>
				</table>
			</div>
			
			<div class="form-group">
				 <label for="religion">INTERVIEW STATUS</label>
				 <select class="form-control" name="interview_stat" required>
					<option value=''>Select Status</option>
					<option value='passed'>PASS</option>
					<option value='failed'>FAIL</option>
				  </select>
			</div>
								
		</div>
		<?php
	}
	else if($request == "applicant_initial_interview")
	{
		?>
		<div class="panel-body">
			<div class="form-group">
				 <label for="hrmsId">APPLICANT ID</label>
				 <input type="text" name='appid' class="form-control" id="appid"  value='<?=$initial_interview['app_id']?>' readonly required>
			</div>
			
			<div class="form-group">
				 <label for="hrmsId">APPLICANT NAME</label>
				 <input type="text" name='name' class="form-control" id="name"  value='<?=$initial_interview['lastname'].", ".$initial_interview['firstname']." ".$initial_interview['middlename']." ".$initial_interview['suffix']?>' readonly required>
			</div>
			
			<div class="form-group">
				 <label for="hrmsId">Initial Remarks</label>
				 <textarea name='initialRemark' class="form-control" id="initialRemark" style='resize:none; height:250px;' required> </textarea>
			</div>
		</div>
		<?php
	}
	
	else if($request == "hiring_setup")
	{
		
		?>
		<div class="panel-body">
			
			<div class="form-group">
				 <label for="hrmsId">APPLICANT NAME</label>
				 <input type="hidden" name='appcode' class="form-control" id="appcode"  value='<?=$hired['appcode']?>' readonly required>
				 <input type="hidden" name='position' class="form-control" id="position"  value='<?=$apposition['position']?>'>
				 <input type="hidden" name='appid' class="form-control" id="appid"  value='<?=$hired['app_id']?>'>
				 <input type="hidden" name='hidden_name' class="form-control" id="hidden_name"  value='<?=$hired['lastname'].", ".$hired['firstname']." ".$hired['middlename']." ".$hired['suffix']?>'>
				 <input type="text" name='name' class="form-control" id="name"  value='<?=$hired['app_id']." - ".$hired['lastname'].", ".$hired['firstname']." ".$hired['middlename']." ".$hired['suffix']?>' readonly>
			</div>
			
			<div class="form-group">
				 <label for="agency">AGENCY</label>
				 <select class="form-control" name="agency" onchange="select_agency(this.value)">
					<option value='0'>Select Agency</option>
					<?php 
						$resultype = $this->initial_model->agency(); 
						foreach ($resultype as $i)  
						{ ?>
							<option value='<?=$i['agency_code']?>'><?=$i['agency_name']?></option><?php 
						} ?>
				  </select>
				 
			</div>
			
			<div class="form-group">
				 <label for="company">COMPANY</label>
				 <select class="form-control" name="company" onchange="selectProduct(this.value)" required>
					<option value=''>Select</option>
					<?php 
						$r = $this->initial_model->company(); 
						foreach ($r as $i)  
						{ ?>
							<option value='<?=$i['pc_code']?>'><?=$i['pc_name']?></option><?php 
						} ?>
				  </select>
				 
			</div>
			
			<div class="form-group">
				 <label for="company">PROMO TYPE</label>
				 <select class="form-control" name="promotype" required>
					<option value=''>Select</option>
					<option value='Roving'>ROVING</option>
					<option value='Station'>STATION</option>
				  </select>
				 
			</div>
			
			<div class="form-group">
				 <label for="company">BUSINESS UNIT</label>
				 <?php $result = $this->initial_model->business_unit(); ?>
				 <table class="table table-bordered">
					<tr>
						<td colspan="2"><b>SELECT STORE</b></td>
					</tr>
					<?php
					
					foreach ($result as $i) { ?>
					<tr>
						<td>
							<input type="checkbox" name='check[]' value="<?php echo $i['bunit_id'].'/'.$i['bunit_field']; ?>">
							<!--input type="checkbox" name='check[]' value="<?php //echo $i['bunit_id'].'/'.$i['bunit_field']; ?>" onchange="intro_letter()"-->
						</td>
						<td><?= $i['bunit_name'] ?></td>
					</tr>
					<?php }?>
					
				</table>
			</div>
			
			<div class="form-group">
				 <label for="company">DEPARTMENT</label>
				 <select class="form-control" name="department" required onchange="locate_vendor(this.value)">
					<option value=''>Select</option>
					<?php 
						$r = $this->initial_model->department(); 
						foreach ($r as $i)  
						{ ?>
							<option value='<?=$i['dept_name']?>'><?=$i['dept_name']?></option><?php 
						} ?>
				  </select>
				 
			</div>
			
			<div class="form-group">
				<label for="company">VENDOR NAME</label>
				<select class="form-control" name="vendor">
					<option value=''>Select</option>	
				</select>
				 
			</div>
			
			<div class="form-group">
				<label for="company">PRODUCT</label>
				<select id="states" name="product[]" class="form-control select2" multiple="multiple" onchange="showSelected();">
					<option value="">Select</option>
				</select>

				<!--input type="text" id="productSelect" class="form-control" name='pro' autocomplete='off'-->
			</div>			
			
			<div class="form-group">
				<label for="company">EMPLOYEE TYPE</label>
				<select name="emptype" class="form-control">
					<option value="Promo-Nesco">Promo-Nesco</option>
				</select>	 
			</div>
			
			<div class="form-group">
				<label for="company">CONTRACT TYPE</label>
				<select name="contract" class="form-control" required>
					<option value="">Select</option>
					<option value="contractual">Contractual</option>
					<option value="seassonal">Seasonnal</option>
				</select>	 
			</div>
			
			<div class="form-group">
				<label for="company">INCLUSIVE DATES</label>
				<table class="table table-bordered">
					<tr>
						<td><input type="text" name="startDate" id="startDate" class="form-control datepicker" placeholder="Start Date" autocomplete='off' required></td>
						<td><input type="text" name="endDate" id='endDate' class="form-control datepicker" placeholder="End Date" onchange="getDuration()" autocomplete='off' required></td>
					</tr>
					
				</table>	 
			</div>
			
			<div class="form-group">
				<label for="company">DURATION</label>
				<input type="text" name="duration_display" class="form-control" autocomplete='off' required >	 
			</div>
			
			
			<div class="form-group">
				<div id='intro_letter'>
			</div>
			
			
			
			<div class="form-group">
				<label for="hrmsId">REMARKS/COMMENTS</label>
				<textarea name='remark_comment' class="form-control" style='resize:none; height:110px;'></textarea>
			</div>
			
		</div>
		
		<script>
		  $('#states').select2({
			 dropdownParent: $('#parent')
		  });
		  $('#states').on('select2:open', function (e) {
			const evt = "scroll.select2";
			$(e.target).parents().off(evt);
			$(window).off(evt);
		  });  
				  
		$('.datepicker').datepicker({
				inline: true,
				changeYear: true,
				changeMonth: true
			});
		
		$('.select2').select2();
		$("span.select2").css("width", "100%");
		</script>
		<?php
	}
	else if ($request == 'show_intro') 
	{
		
		?><div class="form-group">
		<label for="company">INTRO LETTER</label><br/><?php  
		
		if (is_array($stores)) 
		{

			foreach ($stores as $key => $value) 
			{
				$s = explode('/', $value);
				$bunit_id = $s[0];
				
				$bu = $this->contract_model->show_bu_details($bunit_id);
				?>
					<input type="hidden" name="bunit_intro[]" value="<?=$bu->bunit_intro?>">
					<label style='color:red;'><?=$bu->bunit_name?></b> <input type="file" name="<?=$bu->bunit_intro?>" id="<?=$bu->bunit_intro?>" class="form-control" required onchange="validateForm(this.id)">
				<?php  
			}
			?></div><?php  
		}
		
	}
	else if($request == "applicant_old_record")
	{
		$split_contact = explode(",",$checkduplicate['contactno']);
		?>
			<input type = 'text' value='UPDATE' name='procedure'>
			<input type = 'text' value="<?=$checkduplicate['app_id']?>" name='hrmsId'>
			
			<div class="wrapper" style='padding:5px; '>
				<div class="col-sm-6">
					<div class="panel panel-default">
						
						<div class="box box-primary">
						<h4 style='padding-left:20px;'>BASIC INFORMATION ( <i style='color:red;'>OLD APPLICANT</i> )</h4>
							<div class="panel-body">
								<div class="form-group"> <span class="text-red">*</span>
									 <label for="firstname">Firstname <i style='color:red; font-size:11px;'> ( Required )</i></label>
									 <input type="text" value='<?=$check_record['applicants']['firstname']?>' name='firstname' class="form-control" id="firstname"  required>
								</div>
								<div class="form-group">
									 <label for="suffix">Suffix <i style='color:red; font-size:11px;'></i></label>
									 <input type="text" value='<?=$check_record['applicants']['suffix']?>' name='suffix' class="form-control" id="suffix" >
								</div>
								<div class="form-group">
									 <label for="middlename">Middlename <i style='color:red; font-size:11px;'></i></label>
									 <input type="text" value='<?=$check_record['applicants']['middlename']?>' name='middlename' class="form-control" id="middlename" >
								</div>
								<div class="form-group"> <span class="text-red">*</span>
									 <label for="lastname">Lastname <i style='color:red; font-size:11px;'> ( Required )</i></label>
									 <input type="text" value='<?=$check_record['applicants']['lastname']?>' name='lastname' class="form-control" id="lastname" required>
								</div>
								<div class="form-group"> <span class="text-red">*</span>
									 <label for="birthdate">Date of Birth <i style='color:red; font-size:11px;'> ( Required )</i></label>
									 <input type="text" name='birthdate' class="form-control datepicker inputForm" id="birthdate" required value='<?=date("m-d-Y",strtotime($checkduplicate['birthdate'])) ?>'>
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
									 <option value='<?=$checkduplicate['citizenship'] ?>'><?=$checkduplicate['citizenship'] ?></option>
										
									  </select>	
								</div>
								<div class="form-group"> <span class="text-red">*</span>
									 <label for="religion">Religion <i style='color:red; font-size:11px;'> ( Required )</i></label>
									 <select class="form-control" name="religion" required>
									 <option value='<?=$checkduplicate['religion'] ?>'><?=$checkduplicate['religion'] ?></option>
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
									 
									 <?php $rslt_weight = $this->initial_model->weight();?>
									

									 <input list='weight' type="text" name='weight' class="form-control" value="<?=$checkduplicate['weight'] ?>">
									 <datalist id="weight">
											<?php foreach ($rslt_weight as $i) 
											{	
												echo "<option value='".$i['kilogram']."/".$i['pounds']."'>".$i['kilogram']."/".$i['pounds']."</option>"; 
											} ?>
									</datalist>
								</div>
								<div class="form-group">
									 <label for="height">Height</label>
									 
									 <?php $rslt_height = $this->initial_model->height();?>

									 <input  list="height" type="text" name='height' class="form-control" value="<?=$checkduplicate['height'] ?>">
									 <datalist id="height">
											<?php foreach ($rslt_height as $i)
												{
													echo '<option value="'.$i['feet']." | ".$i['cm'].'">'.$i['feet']." | ".$i['cm'].'</option>'; 
												} ?>
									</datalist>
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
									 <input type="text" name='mother' class="form-control" id="mother" autocomplete='off' required value='<?=$checkduplicate['mother'] ?>'>
								</div>
								<div class="form-group"> <span class="text-red">*</span>
									<label for="father">Father</label>
									<input type="text" name='father' class="form-control" id="father" autocomplete='off' value=<?=$checkduplicate['father'] ?>''>	
								</div>
								<div class="form-group">
									 <label for="guardian">Guardian <i style='color:red; font-size:11px;' class="father-is-required"></i></label>
									 <input type="text" name='guardian' class="form-control" id="guardian" autocomplete='off' value='<?=$checkduplicate['guardian'] ?>'>
								</div>
								<div class="form-group">
									 
									 <?php if(in_array($check_record['new_details']['gender'], array('Married', 'Widowed')))
									 {
										 ?>
										 <span class="text-red spouse-is-required">*</span>
										 <label for="spouse">Spouse <i style='color:red; font-size:11px;' class="spouse-is-required"> ( Required )</i></label>
										 <input type="text" name='spouse' class="form-control" id="spouse" autocomplete='off' required value="<?=$checkduplicate['spouse'] ?>">
										 <?php
									 } else {
										 
										 ?>
										 <span class="text-red spouse-is-required"></span>
										 <label for="spouse">Spouse <i style='color:red; font-size:11px;' class="spouse-is-required">( Required if the applicant is Married/Widowed. )</i></label>
										 <input type="text" name='spouse' class="form-control" id="spouse" autocomplete='off' value="<?=$checkduplicate['spouse'] ?>">
										 <?php
									 }?>
									 
								</div>
								<div class="form-group">
									<div  class="col-sm-6" style='padding:0px;'>
									<label for="no_of_siblings">No of Siblings</label>
									<input type="number" name='no_of_siblings' class="form-control" id="no_of_siblings" value='<?=$checkduplicate['noofSiblings']?>'>
									</div>
									<div class="col-sm-6" style='padding:0px;'>
										<label for="sibling_order">Sibling Order</label>
										<select class="form-control" name="sibling_order">
											<option value='<?=$checkduplicate['siblingOrder']?>'><?=$checkduplicate['siblingOrder']?></option>
											<option value='1st Child'>1st Child</option>
											<option value='2nd Child'>2nd Child</option>
											<option value='3rd Child'>3rd Child</option>
											<option value='4th Child'>4th Child</option>
											<option value='5th Child'>5th Child</option>
											<option value='6th Child'>6th Child</option>
											<option value='7th Child'>7th Child</option>
											<option value='8th Child'>8th Child</option>
											<option value='9th Child'>9th Child</option>
										</select>
									</div>
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
									 <input list="address" name="address" autocomplete="off" class="form-control" required value="<?=$checkduplicate['home_address']?>">
										<datalist id="address">
											<?php foreach ($check_record['town_brgy'] as $i) { echo "<option value='" . $i['brgy_name'] . ", " . $i['town_name'] . ", " . $i['prov_name'] . "'>" . $i['brgy_name'] . ", " . $i['town_name'] . ", " . $i['prov_name'] . "</option>"; } ?>
										</datalist>	
								</div>
								<div class="form-group">
									 <label for="city_address">City Address <i style='color:red; font-size:11px;'></i></label>
									 <input list="city_address" name="city_address" autocomplete="off" class="form-control" value="<?=$checkduplicate['city_address']?>">
										<datalist id="city_address">
											<?php foreach ($check_record['town_brgy'] as $i) { echo "<option value='" . $i['brgy_name'] . ", " . $i['town_name'] . ", " . $i['prov_name'] . "'>" . $i['brgy_name'] . ", " . $i['town_name'] . ", " . $i['prov_name'] . "</option>"; } ?>
										</datalist>
								</div>
								<div class="form-group">
									<div  class="col-sm-6" style='padding:0px;'><span class="text-red">*</span>
										<label for="contact1">Contact Number <i style='color:red; font-size:11px;'> ( Required )</i></label>
										<input type="text" data-inputmask='"mask": "+639999999999"' data-mask name='contact1' class="form-control" id="contact1" required value="<?=$split_contact[0]?>">	
									</div>
									<div class="col-sm-6" style='padding:0px;'>
										<label for="contact1" style='color:white;'>Contact Number</label>
										<input type="text" data-inputmask='"mask": "+639999999999"' data-mask name='contact2' class="form-control" id="contact2" value="<?=$split_contact[1] ?>">	
									</div>	 
								</div>
								<div class="col-sm-12" style='padding:5px;'>
										
								</div>	 
								<div class="form-group">
									 <label for="telephone_number">Telephone Number</label>
									 <input type="text" data-inputmask='"mask": "(999) 999-9999"' data-mask name='telephone_number' class="form-control" id="telephone_number" value="<?=$checkduplicate['telno']?>">
								</div>
								<div class="form-group"><span class="text-red">*</span>
									 <label for="contact_person">Contact Person</label><i style='color:red; font-size:11px;'> ( Required )</i>
									 <input type="text" name='contact_person' class="form-control" autocomplete="off" id="contact_person" required value="<?=$checkduplicate['contact_person']?>">
								</div>
								<div class="form-group"><span class="text-red">*</span>
									 <label for="contact_person_address">Contact Person Address</label><i style='color:red; font-size:11px;'> ( Required )</i>
									 <input list="contact_person_address" name="contact_person_address" autocomplete="off" class="form-control" required value="<?=$checkduplicate['contact_person_address']?>">
										<datalist id="contact_person_address">
											<?php foreach ($check_record['town_brgy'] as $i) { echo "<option value='" . $i['brgy_name'] . ", " . $i['town_name'] . ", " . $i['prov_name'] . "'>" . $i['brgy_name'] . ", " . $i['town_name'] . ", " . $i['prov_name'] . "</option>"; } ?>
										</datalist>
								</div>
								<div class="form-group"><span class="text-red">*</span>
									 <label for="contact_person_number">Contact Person Number </label><i style='color:red; font-size:11px;'> ( Required )</i>
									 <input type="text" data-inputmask='"mask": "+639999999999"' data-mask name='contact_person_number' class="form-control" id="contact_person_number" required value="<?=$checkduplicate['contact_person_number']?>">
								</div>
								<div class="form-group">
									 <label for="email_add">Email Address</label>
									 <input type="text" name='email_add' class="form-control" id="email_add" value="<?=$checkduplicate['email']?>">
								</div>
								<div class="form-group">
									 <label for="facebook">Facebook Account</label>
									 <input type="text" name='facebook' class="form-control" id="facebook" value="<?=$checkduplicate['facebookAcct']?>">
								</div>
								<div class="form-group">
									 <label for="twitter">Twitter Account</i></label>
									 <input type="text" name='twitter' class="form-control" id="twitter" value="<?=$checkduplicate['twitterAcct']?>">
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
									 <input list="education" name="education" autocomplete="off" class="form-control" required value="<?=$checkduplicate['attainment']?>">
										<datalist id="education">
											<?php $result_education = $this->initial_model->attainment(); foreach ($result_education as $i)  { ?> 
												<option value='<?=$i['attainment']?>'><?=$i['attainment']?></option>
											<?php } ?>
										</datalist>
									 
								</div>
								<div class="form-group"> <span class="text-red">*</span>
									 <label for="school">School <i style='color:red; font-size:11px;'> ( Required )</i></label>
									 <input list="school" name="school" autocomplete="off" class="form-control" required value="<?=$checkduplicate['school']?>">
										<datalist id="school">
											<?php $result_education = $this->initial_model->school(); foreach ($result_education as $i)  { ?> 
												<option value='<?=$i['school_name']?>'><?=$i['school_name']?></option>
											<?php } ?>
										</datalist>
								</div>
								<div class="form-group"> <span class="text-red">*</span>
									 <label for="course">Details / Course </label>
									 <input list="course" name="course" autocomplete="off" class="form-control" value="<?=$checkduplicate['course']?>">
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
										 <input type="text" name='hobbies' class="form-control" id="hobbies" value='<?=$checkduplicate['hobbies'] ?>'>
									</div>
									<div class="form-group">
										 <label for="special_skill">Special skills / Talents <i style='color:red; font-size:11px;'></i></label>
										 <input type="text" name='special_skill' class="form-control" id="special_skill" value='<?=$checkduplicate['specialSkills'] ?>'>
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
										<?php foreach( $seminar_eligibility as $list) {?>
											<tr>
												
												<td><input type="text" class="form-control" readonly value="<?=$list['name']?>"></td>
											  	<td><input type="text" class="form-control" readonly value="<?=$list['dates']?>"></td>
											  	<td><input type="text" class="form-control" readonly value="<?=$list['location']?>"></td>
											  	<td><input type="text" class="form-control" readonly value="<?=$list['sem_certificate']?>"></td>
											</tr>
										<?php } ?>

											<tr>
												<td><input name="seminar_name[]" type="text" class="form-control"></td>
											  	<td><input name="seminar_location[]"  type="text" class="form-control"></td>
											  	<td><input name="seminar_year[]" type="text" class="form-control"></td>
											  	<td><input name="seminar_certificate[]" id='seminarcert' onchange='validateForm(this.id)' type="file" class="form-control"></td>
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
									<table  id="character_ref" class="character_ref-list_old">
									<thead>
										<th style='text-align:center; width:25%'>Name of Reference</th>
										<th style='text-align:center; width:25%'>Position / Designation</th>
										<th style='text-align:center; width:15%'>Contact Number</th>
										<th style='text-align:center; width:25%'>Company / Location / Address</th>	
									</thead>
									<tbody>
											<?php foreach( $refference as $list) {?>
											<tr>
												<td><input type="text" class="form-control" value="<?=$list['name']?>" readonly></td>
												<td><input type="text" class="form-control" value="<?=$list['position']?>" readonly></td>
												<td><input type="text" class="form-control" value="<?=$list['contactno']?>" readonly></td>
												<td><input type="text" class="form-control" value="<?=$list['company']?>" readonly></td>
												
											</tr>
											<?php } ?>
											<tr>
												<td><input type="text" name='character_name[]' class="form-control" id="character_name" autocomplete="off"></td>
												<td><input type="text" name='character_position[]' class="form-control" id="character_position" autocomplete="off" ></td>
												<td><input type="text" name='character_contact[]' class="form-control" id="character_contact" data-inputmask='"mask": "+639999999999"' data-mask></td>
												<td><input type="text" name='character_address[]' class="form-control" id="character_address" autocomplete="off"></td>
												<td><a class="delete_row_character"></a></td>
											</tr>
									</tbody>
									</table>
									<input type="button" id="add_row_character_old" value="Add Row">
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
										<th style='text-align:center; width:8%'>Year Started</th>
										<th style='text-align:center; width:8%'>Year Ended</th>
										<th style='text-align:center; width:20%'>Company / Location / Address</th>
										<th style='text-align:center; width:20%'>Certificate</th>
										
									</thead>
									<tbody>
											<?php foreach($employment_history as $list){?>
											<tr>
												<td><input type="text" class="form-control" value="<?=$list['company']?>" readonly></td>
												<td><input type="text" class="form-control" value="<?=$list['position']?>" readonly></td>
												<td><input type="text" class="form-control" value="<?=$list['yr_start']?>" readonly></td>
												<td><input type="text" class="form-control" value="<?=$list['yr_ends']?>" readonly></td>
												<td><input type="text" class="form-control" value="<?=$list['address']?>" readonly></td>
												<td><input type="text" class="form-control" value="<?=$list['emp_certificate']?>" readonly></td>
												
											</tr>
											<?php } ?>
											<tr>
												<td><input type="text" name='company_name[]' class="form-control" id="company_name"></td>
												<td><input type="text" name='position[]' class="form-control" id="position"></td>
												<td><input type="text" name='year_start[]' class="form-control" id="year_start"></td>
												<td><input type="text" name='year_end[]' class="form-control" id="year_end"></td>
												<td><input type="text" name='company_address[]' class="form-control" id="company_address"></td>
												<td><input type="file" name='certificate[]' class="form-control" id="certificate" onchange='validateForm(this.id)'></td>
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
										 <select name='vacancy_source' class="form-control" id="vacancy_source" style='width:50%;' required>
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
										<input type="hidden" value='<?=$check_record['applicants']['app_code']?>' name='application_code' class="form-control" style='width:10%' readonly>
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
				cols += '<td><input name="seminar_certificate[]" id="seminarcert'+counter1+'" onchange="validateForm(this.id)" type="file" class="form-control" ></td>';
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
				cols += '<td><input name="certificate[]" id="certificate'+cntr+'" onchange="validateForm(this.id)" type="file" class="form-control" ></td>';
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
			
			$("#add_row_character_old").on("click", function () 
			{
				
				$.ajax({
					url: "<?php echo site_url('append_character_ref_old'); ?>",
					success: function(response)
					{
						$("table.character_ref-list_old").append(response);
					}
				});
				/*var countr = $('#character_ref tr').length - 2;
				var newRow = $("<tr>");
				var cols = "";
				cols += '<td><input type="text" name="character_name[]" class="form-control" ></td>';
				cols += '<td><input type="text" name="character_position[]" class="form-control"></td>';
				cols += '<td><input type="text" name="character_contact[]" id="character_ref' + countr + '" onchange="check(this)" class="form-control" ></td>';
				cols += '<td><input type="text" name="character_address[]" id="character_ref' + countr + '" onchange="check(this)" class="form-control" ></td>';
				cols += '<td><input type="button" class="ibtnDelsem_character" value="Delete" class="form-control" ></td>';
				newRow.append(cols);
				$("table.character_ref-list").append(newRow);
				countr++;*/
			});
			
			$("table.character_ref-list_old").on("click", ".ibtnDelsem_character", function (event) 
			{
				$(this).closest("tr").remove();
				countr--;
				$('#add_row_character_old').prop('disabled', false).prop('value', "Add row");
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
	else if($request == "applicant_new_record")
	{
		?>
			<input type = 'text' value='INSERT' name='procedure'>
			<div class="wrapper" style='padding:5px; '>
				<div class="col-sm-6">
					<div class="panel panel-default">
						
						<div class="box box-primary">
						<h4 style='padding-left:20px;'>BASIC INFORMATION ( <i style='color:red;'>NEW APPLICANT</i> )</h4>
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
									 
									 <?php $rslt_weight = $this->initial_model->weight();?>
									

									 <input list='weight' type="text" name='weight' class="form-control" >
									 <datalist id="weight">
											<?php foreach ($rslt_weight as $i) 
											{	
												echo "<option value='".$i['kilogram']."/".$i['pounds']."'>".$i['kilogram']."/".$i['pounds']."</option>"; 
											} ?>
									</datalist>
								</div>
								<div class="form-group">
									 <label for="height">Height</label>
									 
									 <?php $rslt_height = $this->initial_model->height();?>

									 <input  list="height" type="text" name='height' class="form-control">
									 <datalist id="height">
											<?php foreach ($rslt_height as $i)
												{
													echo '<option value="'.$i['feet']." | ".$i['cm'].'">'.$i['feet']." | ".$i['cm'].'</option>'; 
												} ?>
									</datalist>
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
									<div  class="col-sm-6" style='padding:0px;'>
									<label for="no_of_siblings">No of Siblings</label>
									<input type="number" name='no_of_siblings' class="form-control" id="no_of_siblings">
									</div>
									<div class="col-sm-6" style='padding:0px;'>
										<label for="sibling_order">Sibling Order</label>
										<select class="form-control" name="sibling_order">
											<option value=''>Select Sibling Order</option>
											<option value='1st Child'>1st Child</option>
											<option value='2nd Child'>2nd Child</option>
											<option value='3rd Child'>3rd Child</option>
											<option value='4th Child'>4th Child</option>
											<option value='5th Child'>5th Child</option>
											<option value='6th Child'>6th Child</option>
											<option value='7th Child'>7th Child</option>
											<option value='8th Child'>8th Child</option>
											<option value='9th Child'>9th Child</option>
										</select>
									</div>
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
								<div class="form-group">
									<div  class="col-sm-6" style='padding:0px;'><span class="text-red">*</span>
										<label for="contact1">Contact Number <i style='color:red; font-size:11px;'> ( Required )</i></label>
										<input type="text" data-inputmask='"mask": "+639999999999"' data-mask name='contact1' class="form-control" id="contact1" required>	
									</div>
									<div class="col-sm-6" style='padding:0px;'>
										<label for="contact1" style='color:white;'>Contact Number</label>
										<input type="text" data-inputmask='"mask": "+639999999999"' data-mask name='contact2' class="form-control" id="contact2">	
									</div>	 
								</div>
								<div class="col-sm-12" style='padding:5px;'>
										
								</div>	 
								<div class="form-group">
									 <label for="telephone_number">Telephone Number</label>
									 <input type="text" data-inputmask='"mask": "(999) 999-9999"' data-mask name='telephone_number' class="form-control" id="telephone_number">
								</div>
								<div class="form-group"><span class="text-red">*</span>
									 <label for="contact_person">Contact Person</label><i style='color:red; font-size:11px;'> ( Required )</i>
									 <input type="text" name='contact_person' class="form-control" autocomplete="off" id="contact_person" required>
								</div>
								<div class="form-group"><span class="text-red">*</span>
									 <label for="contact_person_address">Contact Person Address</label><i style='color:red; font-size:11px;'> ( Required )</i>
									 <input list="contact_person_address" name="contact_person_address" autocomplete="off" class="form-control" required>
										<datalist id="contact_person_address">
											<?php foreach ($check_record['town_brgy'] as $i) { echo "<option value='" . $i['brgy_name'] . ", " . $i['town_name'] . ", " . $i['prov_name'] . "'>" . $i['brgy_name'] . ", " . $i['town_name'] . ", " . $i['prov_name'] . "</option>"; } ?>
										</datalist>
								</div>
								<div class="form-group"><span class="text-red">*</span>
									 <label for="contact_person_number">Contact Person Number </label><i style='color:red; font-size:11px;'> ( Required )</i>
									 <input type="text" data-inputmask='"mask": "+639999999999"' data-mask name='contact_person_number' class="form-control" id="contact_person_number" required>
								</div>
								<div class="form-group">
									 <label for="email_add">Email Address</label>
									 <input type="text" name='email_add' class="form-control" id="email_add">
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
									 <input list="education" name="education" autocomplete="off" class="form-control" required>
										<datalist id="education">
											<?php $result_education = $this->initial_model->attainment(); foreach ($result_education as $i)  { ?> 
												<option value='<?=$i['attainment']?>'><?=$i['attainment']?></option>
											<?php } ?>
										</datalist>
									 
								</div>
								<div class="form-group"> <span class="text-red">*</span>
									 <label for="school">School <i style='color:red; font-size:11px;'> ( Required )</i></label>
									 <input list="school" name="school" autocomplete="off" class="form-control" required>
										<datalist id="school">
											<?php $result_education = $this->initial_model->school(); foreach ($result_education as $i)  { ?> 
												<option value='<?=$i['school_name']?>'><?=$i['school_name']?></option>
											<?php } ?>
										</datalist>
								</div>
								<div class="form-group"> <span class="text-red">*</span>
									 <label for="course">Details / Course </label>
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
											  <td><input name="seminar_certificate[]" id='seminarcert' onchange='validateForm(this.id)' type="file" class="form-control"></td>
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
												<td><input type="text" name='character_name[]' class="form-control" id="character_name" autocomplete="off"></td>
												<td><input type="text" name='character_position[]' class="form-control" id="character_position" autocomplete="off"></td>
												<td><input type="text" name='character_contact[]' class="form-control" id="character_contact" data-inputmask='"mask": "+639999999999"' data-mask></td>
												<td><input type="text" name='character_address[]' class="form-control" id="character_address" autocomplete="off"></td>
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
										<th style='text-align:center; width:8%'>Year Started</th>
										<th style='text-align:center; width:8%'>Year Ended</th>
										<th style='text-align:center; width:20%'>Company / Location / Address</th>
										<th style='text-align:center; width:18%'>Certificate</th>
										
									</thead>
									<tbody>
											<tr>
												<td><input type="text" name='company_name[]' class="form-control" id="company_name"></td>
												<td><input type="text" name='position[]' class="form-control" id="position"></td>
												<td><input type="text" name='year_start[]' class="form-control" id="year_start"></td>
												<td><input type="text" name='year_end[]' class="form-control" id="year_end"></td>
												<td><input type="text" name='company_address[]' class="form-control" id="company_address"></td>
												<td><input type="file" name='certificate[]' class="form-control" onchange='validateForm(this.id)' id="certificate"></td>
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
										 <select name='vacancy_source' class="form-control" id="vacancy_source" style='width:50%;' required>
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
										<input type="hidden" value='<?=$check_record['applicants']['app_code']?>' name='application_code' class="form-control" style='width:10%' readonly>
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
				cols += '<td><input name="seminar_certificate[]" id="seminarcert'+counter1+'" onchange="validateForm(this.id)" type="file" class="form-control" ></td>';
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
				cols += '<td><input name="certificate[]" id="certificate'+cntr+'" onchange="validateForm(this.id)" type="file" class="form-control" ></td>';
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
				
				$.ajax({
					url: "<?php echo site_url('append_character_ref'); ?>",
					success: function(response)
					{
						$("table.character_ref-list").append(response);
					}
				});
				/*var countr = $('#character_ref tr').length - 2;
				var newRow = $("<tr>");
				var cols = "";
				cols += '<td><input type="text" name="character_name[]" class="form-control" ></td>';
				cols += '<td><input type="text" name="character_position[]" class="form-control"></td>';
				cols += '<td><input type="text" name="character_contact[]" id="character_ref' + countr + '" onchange="check(this)" class="form-control" ></td>';
				cols += '<td><input type="text" name="character_address[]" id="character_ref' + countr + '" onchange="check(this)" class="form-control" ></td>';
				cols += '<td><input type="button" class="ibtnDelsem_character" value="Delete" class="form-control" ></td>';
				newRow.append(cols);
				$("table.character_ref-list").append(newRow);
				countr++;*/
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
	else if($request == "setup_interviewer")
	{
		?>
		<div class="panel-body">
			<div class="form-group">
				 <label for="hrmsId">APPLICANT ID</label>
				 <input type="text" name='appid' class="form-control" id="appid"  value='<?=$setup_interview['app_id']?>' readonly required>
			</div>	
			<div class="form-group">
				 <label for="hrmsId">APPLICANT NAME</label>
				 <input type="text" name='name' class="form-control" id="name"  value='<?=$setup_interview['lastname'].", ".$setup_interview['firstname']." ".$setup_interview['middlename']." ".$setup_interview['suffix']?>' readonly required>
			</div>
			<div class="form-group">
				<label for="hrmsId">INTERVIEWER</label>
				<select class="form-control" name="interviewer" required>
					<option value=''>Select Interviewer</option>
					<?php 
						$resultype = $this->initial_model->interviewer_list(); 
						foreach ($resultype as $i)  
						{ 
							?><option value='<?=$i['empId']?>'><?=$this->initial_model->getName($i['empId'])?></option><?php 
						} 	?>
				</select>	
			</div>
		</div>
	<?php
	}
	else if($request == "applicant_examination")
	{
	?>
		<div class="panel-body">
			<div class="form-group">
				 <label for="hrmsId">APPLICANT ID</label>
				 <input type="hidden" name='appcode' class="form-control" id="appcode"  value='<?=$examinee['appcode']?>' readonly required>
				 <input type="text" name='appid' class="form-control" id="appid"  value='<?=$examinee['app_id']?>' readonly required>
			</div>
			
			<div class="form-group">
				 <label for="hrmsId">APPLICANT NAME</label>
				 <input type="text" name='name' class="form-control" id="name"  value='<?=$examinee['lastname'].", ".$examinee['firstname']." ".$examinee['middlename']." ".$examinee['suffix']?>' readonly required>
			</div>
			
			<div class="form-group">
				 <label for="hrmsId">APPLICANT ATTAINMENT</label>
				 <input type="text" name='attainment' class="form-control" id="attainment"  value='<?=$examinee['attainment']?>' readonly required>
			</div>
			
			<div class="form-group">
				 <label for="hrmsId">APPLYING FOR</label>
				 <input type="text" name='applying' class="form-control" id="applying"  value='<?=$examinee_app['position']?>' readonly required>
			</div>
			
			<div class="form-group">
				<label for="hrmsId">EXAM CATEGORY</label>
				<select class="form-control" name="exam_type" required>
					<option value=''>Select Exam Type</option>
					<?php 
						$resultype = $this->initial_model->examtype(); 
						foreach ($resultype as $i)  
						{  
							$result_codename = $this->initial_model->examtype_codename($i['exam_code']);
							foreach ($result_codename as $z)
							{
								$n=$z['exam_codename'];
							}
							?>
							<option value='<?=$i['exam_code']?>'><?=$i['exam_code']." - ".$n?></option><?php 
						} ?>
				</select>
			</div>	
		</div>
	<?php
	}
	else if($request =='applicant_examination_view')
	{
		?>
		<div class="col-sm-12" style="padding-right:5px">
			<div class="col-sm-6" style="padding-right:5px">
				<div class="panel-body">
					<div class="form-group">
						 <label for="hrmsId">Applicant ID</label>
						 <input type="hidden" name='appcode' class="form-control" id="appcode"  value='<?=$view_exam['appcode']?>' readonly required>
						 <input type="text" name='appid' class="form-control" id="appid"  value='<?=$view_exam['app_id']?>' readonly required>
					</div>
					
					<div class="form-group">
						 <label for="hrmsId">Applicant Name</label>
						 <input type="text" name='name' class="form-control" id="name"  value='<?=$view_exam['lastname'].", ".$view_exam['firstname']." ".$view_exam['middlename']." ".$view_exam['suffix']?>' readonly required>
					</div>
					
					<div class="form-group">
						 <label for="hrmsId">Applicant Attainment</label>
						 <input type="text" name='attainment' class="form-control" id="attainment"  value='<?=$view_exam['attainment']?>' readonly required>
					</div>	
				</div>
			</div>
			
			<div class="col-sm-6" style="padding-right:5px">
				<div class="panel-body">
					<div class="form-group">
						 <label for="hrmsId">Applying for</label>
						 
						 <input type="text" name='applying' class="form-control" id="applying"  value='<?=$exam_pos['position']?>' readonly required>
					</div>
					
					<div class="form-group">
						 <label for="hrmsId">Exam Category</label>
						 <input type="text" name='exam_category' class="form-control" id="exam_category"  value='<?=$exam_cat['exam_cat']?>' readonly required>
					</div>
					
					<!--div class="form-group">
						 <label>Examinee Code</label>
						 <input type="text" class="form-control" value='Manual' readonly>
					</div-->	
				</div>
			</div>
			
			
			<div class="col-sm-12" style="padding-right:5px">
			<h5 class="modal-title"><label><u>List of Exam to Take</u></label></h5>
					
					<table id="tableViewEmp_one" class="table table-bordered">
						<thead>
							<th style='text-align:center;'>EXAM TYPES</th>
							<th>EXAM CODE</th>
							<th>SCORE</th>
						</thead>
						<tbody>
							<?php foreach ($exam_score as $score) { ?>
							<tr>
								<td><?=$score['exam_typename'];?></td>
								<td><?=$score['exam_type'];?></td>
								<td>
									<input type="hidden" name='examType[]'  value='<?=$score['exam_type'];?> 'class="form-control" id="examType" style='width:30%;'>
									<input type="text" name='inputscore[]' class="form-control" id="inputscore" required autocomplete='off'>
								</td>
							</tr>
							<?php } ?>
						</tbody>
					</table>

					<table id="table_one" class="table table-bordered">
						<tbody>
							<tr>
								
								<td style='text-align:center; width:30%;'></td>
								<td style='text-align:center; width:30%;'>EXAMINATION STATUS</td>
								<td style='text-align:center; width:40%;'>
									<select class="form-control" name="exam_stat" required>
									<option value=''>Select Status</option>
									<option value='passed'>Pass</option>
									<!--option value='assessment'>For Assessment</option-->
									<option value='failed'>Fail</option>
									</select>
								</td>
							</tr>
						</tbody>
					</table>	
			</div>
		</div>
	<?php
	}
	else if ($request == 'vendor') 
	{
		?><option value=""> --Select Vendor-- </option><?php
		foreach ($vendor as $i)  
		{ ?>
			<option value='<?=$i['vendor_code']?>'><?=$i['vendor_name']?></option><?php 
		} ?>
		<?php
	}
	else if ($request == 'product') 
	{
		?><option value="">Select Product</option><?php
		foreach ($product as $i)  
		{ ?>
			<option value='<?=$i['product']?>'><?=$i['product']?></option><?php 
		} ?>
		<?php
	}
	else if ($request == 'company_list') 
	{
		 
		?><option value=""> --Select-- </option><?php
		foreach ($agency_result as $i)  
		{ ?>
			<option value='<?=$i['agency_code']?>'><?=$i['company_name']?></option><?php 
		} ?>
		<?php	
	}
	else if ($request == 'append_character_ref')
	{
		?>
			<tr>

				<td><input type="text" name='character_name[]' class="form-control" id="character_name"></td>
				<td><input type="text" name='character_position[]' class="form-control" id="character_position"></td>
				<td><input type="text" name='character_contact[]' class="form-control" id="character_contact" data-inputmask='"mask": "+639999999999"' data-mask></td>
				<td><input type="text" name='character_address[]' class="form-control" id="character_address"></td>
				<td><input type="button" class="ibtnDelsem_character" value="Delete" class="form-control" ></td></td>
			</tr>
			<script>
				$(function() {
					
					$("[data-mask]").inputmask();
				});
			</script>
		<?php
	}
	else if ($request == 'append_character_ref_old')
	{
		?>
			<tr>
				<td><input type="text" name='character_name[]' class="form-control" id="character_name"></td>
				<td><input type="text" name='character_position[]' class="form-control" id="character_position"></td>
				<td><input type="text" name='character_contact[]' class="form-control" id="character_contact" data-inputmask='"mask": "+639999999999"' data-mask></td>
				<td><input type="text" name='character_address[]' class="form-control" id="character_address"></td>
				<td><input type="button" class="ibtnDelsem_character" value="Delete" class="form-control" ></td></td>
			</tr>
			<script>
				$(function() {
					
					$("[data-mask]").inputmask();
				});
			</script>
		<?php
	}

?>


