<script>
	$(document).ready(function() {
		//$("#birthdate").mask("99-99-9999");

		$("form#proceed_upload").submit(function(e) {

			e.preventDefault();

			var formData = new FormData(this);

			$.ajax({
				url: "<?php echo site_url('upload_initial'); ?>",
				type: 'POST',
				data: formData,
				dataType: 'json',
				success: function(response) {
					$("div#upload_success_proceed_record").modal({
						backdrop: 'static',
						keyboard: false,
						show: true
					});

					if (response.status === 0) {
						let err_message = '';

						err_message += '<ul>';
						for (var i = 0; i < response.message.length; i++) {
							err_message += '<li>' + response.message[i] + '</li>';
						}

						err_message += '</ul>';

						$("div.upload_success_proceed_record").html(err_message);
						$("button#record").prop('disabled', true);
					} else {
						$("div.upload_success_proceed_record").html(response.message);
						$("button#record").prop('disabled', false);
					}
				},
				async: false,
				cache: false,
				contentType: false,
				processData: false
			});
		});

		$("button#inputGrade").click(function() {
			var code = $("[name='appcode']").val();
			var id = $("[name='appid']").val();
			var interviewer = $("[name='interviewer']").val();
			var interview_code = $("[name='interview_code']").val();

			//$('#interviewdetails_modal').modal('hide');
			$("div#interview_grade").modal({
				backdrop: 'static',
				keyboard: false,
				show: true
			});

			$.ajax({
				url: "<?php echo site_url('input_grades'); ?>",
				type: 'POST',
				success: function(response) {
					$("div.grade_display").html(response);
					$("[name='interview_code']").val(interview_code);
					$("[name='app_id']").val(id);
					$("[name='interviewer']").val(interviewer);
					$("[name='appcode']").val(code);
				}
			});
		});

		$("button#sheet").click(function() {
			var code = $("[name='appcode']").val();
			var id = $("[name='appid']").val();
			window.open("http://172.16.43.134:81/hrms/report/interviewsheet.php?code=" + code + "&emp=" + id);
		});

		$("button#update_applicant").click(function() {

			var procedure = $("[name='procedure']").val();
			var gender = $("[name='gender']").val();
			var civilstatus = $("[name='civilstatus']").val();
			var firstname = $("[name='firstname']").val();
			var middlename = $("[name='middlename']").val();
			var lastname = $("[name='lastname']").val();
			var suffix = $("[name='suffix']").val();

			//alert(procedure);

			if (procedure == "UPDATE") {
				var_dup1 = $("input[name='duplicate[]']:checked").map(function() {
					return this.value;
				}).get();
				var_dup2 = $("input[name='duplicate_MI[]']:checked").map(function() {
					return this.value;
				}).get();

				var n = var_dup1.length + var_dup2.length

				if (n > 1) {
					$("div#small_alert").modal({
						backdrop: 'static',
						keyboard: false,
						show: true
					});

					$("div.small_alert_display").html("Can't proceed, please select one name only to be updated!");
				} else {
					let name1 = $(`span#${var_dup1[0]}`).text();
					let name2 = $(`span#${var_dup2[0]}`).text();

					//alert(name1+" "+name2);

					if (name1 == "" && name2 == "") {
						$("div#small_alert").modal({
							backdrop: 'static',
							keyboard: false,
							show: true
						});

						$("div.small_alert_display").html("Please check atleast one Name on checkbox!!");
					} else {
						var res1 = name1.split('|');
						var res2 = name2.split('|');

						if (name1 != "") {
							if (gender == "female" && civilstatus == "married") {
								$("[name='updt_or_appnd']").val('UPDATE');
								$("[name='hidden_code']").val(res2[0]);
								$("[name='hidden_gender']").val(gender);
								$("[name='hidden_civil_status']").val(civilstatus);
								$("[name='hidden_firstname']").val(firstname);
								$("[name='hidden_middlename']").val(middlename);
								$("[name='hidden_lastname']").val(lastname);
								$("[name='hidden_suffix']").val(suffix);
							} else {
								$("[name='updt_or_appnd']").val('UPDATE');
								$("[name='hidden_code']").val(res1[0]);
								$("[name='hidden_gender']").val(gender);
								$("[name='hidden_civil_status']").val(civilstatus);
								$("[name='hidden_firstname']").val(res1[2]);
								$("[name='hidden_middlename']").val(res1[3]);
								$("[name='hidden_lastname']").val(res1[1]);
								$("[name='hidden_suffix']").val(suffix);
							}
						} else if (name2 != "") {
							if (gender == "female" && civilstatus == "married") {
								$("[name='updt_or_appnd']").val('UPDATE');
								$("[name='hidden_code']").val(res2[0]);
								$("[name='hidden_gender']").val(gender);
								$("[name='hidden_civil_status']").val(civilstatus);
								$("[name='hidden_firstname']").val(firstname);
								$("[name='hidden_middlename']").val(middlename);
								$("[name='hidden_lastname']").val(lastname);
								$("[name='hidden_suffix']").val(suffix);
							} else {
								$("[name='updt_or_appnd']").val('UPDATE');
								$("[name='hidden_code']").val(res2[0]);
								$("[name='hidden_gender']").val(gender);
								$("[name='hidden_civil_status']").val(civilstatus);
								$("[name='hidden_firstname']").val(res2[2]);
								$("[name='hidden_middlename']").val(res2[3]);
								$("[name='hidden_lastname']").val(res2[1]);
								$("[name='hidden_suffix']").val(suffix);
							}
						}

						$("button#proceed_Record").prop('disabled', true);
						$("button#upload_save").prop('disabled', false);
						$("div#browse_blacklist").modal('hide');
					}
				}
			} else if (procedure == "BLACKLIST") {
				var_dup1 = $("input[name='duplicate[]']:checked").map(function() {
					return this.value;
				}).get();
				var_dup2 = $("input[name='duplicate_MI[]']:checked").map(function() {
					return this.value;
				}).get()

				var n = var_dup1.length + var_dup2.length

				//alert(n);

				if (n > 0) {
					if (n > 1) {
						$("div#small_alert").modal({
							backdrop: 'static',
							keyboard: false,
							show: true
						});

						$("div.small_alert_display").html("Can't proceed, please select one name only to be updated!");
					} else {
						let name1 = $(`span#${var_dup1[0]}`).text();
						let name2 = $(`span#${var_dup2[0]}`).text();

						alert(name1 + " " + name2);

						if (name1 == "" && name2 == "") {
							$("div#small_alert").modal({
								backdrop: 'static',
								keyboard: false,
								show: true
							});

							$("div.small_alert_display").html("Please check atleast one Name on checkbox!");
						} else {
							var res1 = name1.split('|');
							var res2 = name2.split('|');
							if (name1 != "") {
								$("[name='updt_or_appnd']").val('UPDATE');
								$("[name='hidden_code']").val(res1[0]);
								$("[name='hidden_gender']").val(gender);
								$("[name='hidden_civil_status']").val(civilstatus);
								$("[name='hidden_firstname']").val(res1[2]);
								$("[name='hidden_middlename']").val(res1[3]);
								$("[name='hidden_lastname']").val(res1[1]);
								$("[name='hidden_suffix']").val(suffix);
							} else if (name2 != "") {
								$("[name='updt_or_appnd']").val('UPDATE');
								$("[name='hidden_code']").val(res2[0]);
								$("[name='hidden_gender']").val(gender);
								$("[name='hidden_civil_status']").val(civilstatus);
								$("[name='hidden_firstname']").val(res2[2]);
								$("[name='hidden_middlename']").val(res2[3]);
								$("[name='hidden_lastname']").val(res2[1]);
								$("[name='hidden_suffix']").val(suffix);
							}

							$(':button[type="submit"]').prop('disabled', false);
							$("div#browse_blacklist").modal('hide');
						}
					}
				} else {
					$("div#small_alert").modal({
						backdrop: 'static',
						keyboard: false,
						show: true
					});

					$("div.small_alert_display").html("Can't Proceed, Applicant is Blacklisted");
				}
			} else if (procedure == "INSERT") {

				$("[name='updt_or_appnd']").val('INSERT');
				$("[name='hidden_code']").val('');
				$("[name='hidden_gender']").val(gender);
				$("[name='hidden_civil_status']").val(civilstatus);
				$("[name='hidden_firstname']").val(firstname);
				$("[name='hidden_middlename']").val(middlename);
				$("[name='hidden_lastname']").val(lastname);
				$("[name='hidden_suffix']").val(suffix);

				//$("button#proceed_Record").prop('disabled', true);
				$("button#proceed_Record").prop('disabled', true);
				$("button#upload_save").prop('disabled', false);
				$("div#browse_blacklist").modal('hide');
			}
		});

		$("button#proceed_Record").click(function() {
			var gender = $("[name='gender']").val();
			var civilstatus = $("[name='civilstatus']").val();
			var firstname = $("[name='firstname']").val();
			var middlename = $("[name='middlename']").val();
			var lastname = $("[name='lastname']").val();
			var suffix = $("[name='suffix']").val();

			var formData = {
				gender,
				civilstatus,
				firstname,
				middlename,
				lastname,
				suffix
			}

			if (gender == 'female' && civilstatus != 'single') {
				if (gender != '' && civilstatus != '' && middlename != '' && firstname != '' && lastname != '') {
					$.ajax({
						type: "POST",
						url: "<?php echo site_url('check_applicant_duplicate_or_blacklist'); ?>",
						data: formData,
						success: function(response) {
							$("div#browse_blacklist").modal({
								backdrop: 'static',
								keyboard: false,
								show: true
							});

							$("div.dbrowse_blacklist").html(response);
							//alert($("[name='procedure']").val());	
						}
					});
				} else {
					$("div#browse_blacklist").modal({
						backdrop: 'static',
						keyboard: false,
						show: true
					});

					$("div.dbrowse_blacklist").html("Please fill the middlename input box!");
					//$("input[name='middlename']")[0].focus();
				}
			} else {
				if (gender != '' && civilstatus != '' && firstname != '' && lastname != '') {

					$.ajax({
						type: "POST",
						url: "<?php echo site_url('check_applicant_duplicate_or_blacklist'); ?>",
						data: formData,
						success: function(response) {


							$("div#browse_blacklist").modal({
								backdrop: 'static',
								keyboard: false,
								show: true
							});
							$("button#update_applicant").prop('disabled', false);
							$("div.dbrowse_blacklist").html(response);
						}
					});
				} else {
					$("div#browse_blacklist").modal({
						backdrop: 'static',
						keyboard: false,
						show: true
					});

					$("button#update_applicant").prop('disabled', true);
					$("div.dbrowse_blacklist").html("Please fill the required box!");
				}
			}
		});

		$("[name='ten_selection']").change(function() {

			alert('try');
		});

		$("[name='position']").change(function() {

			var value = $(this).val()
			if (value == 'Merchandiser Seasonal' || value == 'Promodiser Seasonal') {
				$('#resume').prop('disabled', true);
				$('#application').prop('disabled', true);
				$('#transcript').prop('disabled', true);
			} else {
				$('#resume').prop('disabled', false);
				$('#application').prop('disabled', false);
				$('#transcript').prop('disabled', false);
			}
		});

		$("button#reloadpage").click(function() {
			setTimeout(function() {
				location.reload();
			}, 500);
		});

		$('#final_completion_modal').on('hidden.bs.modal', function(e) {
			$("body").addClass("modal-open");
		})

		$('#success_info').on('hidden.bs.modal', function(e) {
			$("body").addClass("modal-open");
		})

		$("button#record").click(function() {
			setTimeout(function() {
				window.location = "<?php echo base_url('recruitment/page/menu/initial/record'); ?>";
			}, 500);
		});

		const tableViewEmp_interview = $('#tableViewEmp_interview').DataTable();

		// event on clicking initial_interview applicant
		$('#tableViewEmp_interview').on('click', 'button.initial_interview', function() {
			var id = this.id;

			if (!$(this).parents('tr').hasClass('selected')) {
				tableViewEmp_interview.$('tr.selected').removeClass('selected');
				$(this).parents('tr').addClass('selected');
			}

			$.ajax({
				url: "<?php echo site_url('initial_interview'); ?>",
				type: 'POST',
				data: {
					id
				},
				success: function(response) {
					$("div#initial_interview_modal").modal({
						backdrop: 'static',
						keyboard: false,
						show: true
					});

					$("div.initial_interview_display").html(response);
				}
			});
		});


		$('#tableViewEmp').on('click', 'button.transferHold', function() {
			var id = this.id;

			if (!$(this).parents('tr').hasClass('selected')) {
				tableViewEmp.$('tr.selected').removeClass('selected');
				$(this).parents('tr').addClass('selected');
			}

			$.ajax({
				url: "<?php echo site_url('transfer_app'); ?>",
				type: 'POST',
				data: {
					id
				},
				success: function(response) {
					$("div#save_transfer").modal({
						backdrop: 'static',
						keyboard: false,
						show: true
					});

					$("div.transfer_display").html(response);
					//alert(response);		
				}
			});
		});

		$('#tableViewEmp').on('click', 'button.deploy', function() {
			var id = this.id;

			if (!$(this).parents('tr').hasClass('selected')) {
				tableViewEmp.$('tr.selected').removeClass('selected');
				$(this).parents('tr').addClass('selected');
			}
			Swal.fire({
				title: 'Are you sure you want to Update?',
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes, Update!'
			}).then((result) => {
				if (result.isConfirmed) {
					$.ajax({
						url: "<?php echo site_url('deploy_applicant'); ?>",
						type: 'POST',
						data: {
							id
						},
						success: function(response) {
							//alert(response);
							response = JSON.parse(response);
							Swal.fire({
								icon: response.status,
								text: response.message
							}).then((result) => {
								if (result.isConfirmed) {
									location.reload();
								}
							});
						}
					});
				}
			})
		});

		$('#tableViewEmp_interview').on('click', 'button.setup_interview', function() {
			var id = this.id;

			if (!$(this).parents('tr').hasClass('selected')) {
				tableViewEmp_interview.$('tr.selected').removeClass('selected');
				$(this).parents('tr').addClass('selected');
			}

			$.ajax({
				url: "<?php echo site_url('setup_interview'); ?>",
				type: 'POST',
				data: {
					id
				},
				success: function(response) {

					$("div#setup_interview_modal").modal({
						backdrop: 'static',
						keyboard: false,
						show: true
					});

					$("div.setup_interview_display").html(response);
				}
			});
		});

		$('#tableViewEmp_interview').on('click', 'button.check_interview', function() {
			var id = this.id;

			if (!$(this).parents('tr').hasClass('selected')) {
				tableViewEmp_interview.$('tr.selected').removeClass('selected');
				$(this).parents('tr').addClass('selected');
			}

			$.ajax({
				url: "<?php echo site_url('check_interview'); ?>",
				type: 'POST',
				data: {
					id
				},
				success: function(response) {


					$("div#interviewdetails_modal").modal({
						backdrop: 'static',
						keyboard: false,
						show: true
					});

					$("div.interviewdetails_display").html(response);
				}
			});
		});

		$('#ty').on('click', 'button.interview_sheets', function() {
			alert("try? test");
		});
		const tableViewEmp = $('#tableViewEmp').DataTable();

		// event on clicking record applicant
		$('#tableViewEmp').on('click', 'button.record', function() {
			var id = this.id;

			if (!$(this).parents('tr').hasClass('selected')) {
				tableViewEmp.$('tr.selected').removeClass('selected');
				$(this).parents('tr').addClass('selected');
			}

			$.ajax({
				url: "<?php echo site_url('proceed_record_applicants'); ?>",
				type: 'POST',
				data: {
					id
				},
				success: function(response) {
					$("div#record_applicants").modal({
						backdrop: 'static',
						keyboard: false,
						show: true
					});
					$("div.record_applicants").html(response);
				}
			});
		});

		const tableViewEmp_one = $('#tableViewEmp_one').DataTable();

		// event on clicking set-up examination applicant
		$('#tableViewEmp_one').on('click', 'button.setup_exam', function() {
			var id = this.id;

			if (!$(this).parents('tr').hasClass('selected')) {
				tableViewEmp_one.$('tr.selected').removeClass('selected');
				$(this).parents('tr').addClass('selected');
			}

			$.ajax({
				url: "<?php echo site_url('applicant_examination_setup'); ?>",
				type: 'POST',
				data: {
					id
				},
				success: function(response) {

					$("div#applicantsetup").modal({
						backdrop: 'static',
						keyboard: false,
						show: true
					});
					$("div.applicantsetup").html(response);
				}
			});
		});

		// event on clicking view detail examination applicant
		$('#tableViewEmp_one').on('click', 'button.view_detail', function() {
			var id = this.id;

			if (!$(this).parents('tr').hasClass('selected')) {
				tableViewEmp_one.$('tr.selected').removeClass('selected');
				$(this).parents('tr').addClass('selected');
			}

			$.ajax({
				url: "<?php echo site_url('view_exam_setup'); ?>",
				type: 'POST',
				data: {
					id
				},
				success: function(response) {
					//alert(response);
					$("div#view_examination").modal({
						backdrop: 'static',
						keyboard: false,
						show: true
					});
					$("div.view_examination").html(response);
				}
			});
		});

		// tag for interview button
		$('#tableViewEmp_one').on('click', 'button.tag_interview', function() {
			var id = this.id;

			if (!$(this).parents('tr').hasClass('selected')) {
				tableViewEmp_one.$('tr.selected').removeClass('selected');
				$(this).parents('tr').addClass('selected');
			}
			$.ajax({
				url: "<?php echo site_url('tag_applicant_interview'); ?>",
				type: 'POST',
				data: {
					id
				},
				success: function(response) {
					response = JSON.parse(response);

					$("div#info_exam").modal({
						backdrop: 'static',
						keyboard: false,
						show: true
					});
					//$("div.info_exam_msg").html(response);

					if (response.status === 1) {
						$("div.info_exam_msg").html(response.message);
					}

				}
			});
		});

		// tag for transfer button
		$('#tableViewEmp_one').on('click', 'button.tag_transfer', function() {
			var id = this.id;

			if (!$(this).parents('tr').hasClass('selected')) {
				tableViewEmp_one.$('tr.selected').removeClass('selected');
				$(this).parents('tr').addClass('selected');
			}
			$.ajax({
				url: "<?php echo site_url('tag_applicant_transfer'); ?>",
				type: 'POST',
				data: {
					id
				},
				success: function(response) {
					response = JSON.parse(response);

					$("div#info_exam").modal({
						backdrop: 'static',
						keyboard: false,
						show: true
					});

					if (response.status === 1) {
						$("div.info_exam_msg").html(response.message);
					}
				}
			});
		});

		// tag for transfer button
		$('#tableViewEmp').on('click', 'button.upload_final', function() {
			var id = this.id;

			if (!$(this).parents('tr').hasClass('selected')) {
				tableViewEmp.$('tr.selected').removeClass('selected');
				$(this).parents('tr').addClass('selected');
			}
			$.ajax({
				url: "<?php echo site_url('final_completion'); ?>",
				type: 'POST',
				data: {
					id
				},
				success: function(response) {

					$("div#final_modal").modal({
						backdrop: 'static',
						keyboard: false,
						show: true
					});

					$("div.final_display").html(response);
				}
			});
		});

		$('#tableViewEmp').on('click', 'button.hiring', function() {
			var id = this.id;

			if (!$(this).parents('tr').hasClass('selected')) {
				tableViewEmp.$('tr.selected').removeClass('selected');
				$(this).parents('tr').addClass('selected');
			}
			$.ajax({
				url: "<?php echo site_url('hiring_setup'); ?>",
				type: 'POST',
				data: {
					id
				},
				success: function(response) {

					$("div#hired_applicant").modal({
						backdrop: 'static',
						keyboard: false,
						show: true
					});

					$("div.hired_display").html(response);
				}
			});
		});


		$("form#save_transfer_process").submit(function(e) {

			e.preventDefault();

			var formData = new FormData(this);

			console.log(formData);

			Swal.fire({
				icon: 'info',
				title: 'Are you sure want to transfer?',
				showDenyButton: true,
				confirmButtonText: 'Yes',
				denyButtonText: `No`,
			}).then((result) => {
				if (result.isConfirmed) {
					$.ajax({
						url: "<?php echo site_url('save_transfer_process'); ?>",
						type: 'POST',
						data: formData,
						success: function(response) {
							response = JSON.parse(response);
							if (response.status == 1) {
								Swal.fire({
									icon: 'success',
									text: response.message,
									icon: 'success',
									confirmButtonColor: '#3085d6',
									confirmButtonText: 'Okay'
								}).then((result) => {
									if (result.isConfirmed) {
										location.reload();
									}
								})
							} else {
								Swal.fire({
									icon: 'success',
									text: response.message,
									icon: 'success',
									confirmButtonColor: '#3085d6',
									confirmButtonText: 'Okay'
								}).then((result) => {
									if (result.isConfirmed) {
										location.reload();
									}
								})
							}

						},
						async: false,
						cache: false,
						contentType: false,
						processData: false
					});
				}
			})
		});

		$("form#setup_interviewee").submit(function(e) {

			e.preventDefault();

			var formData = new FormData(this);

			console.log(formData);
			$.ajax({
				url: "<?php echo site_url('setup_interviewee'); ?>",
				type: 'POST',
				data: formData,
				success: function(response) {

					$("div#interview_modal").modal({
						backdrop: 'static',
						keyboard: false,
						show: true
					});

					response = JSON.parse(response);

					if (response.status === 1) {
						$("div.interview_display").html(response.message);
					}
				},
				async: false,
				cache: false,
				contentType: false,
				processData: false
			});
		});

		$("form#save_final_completion").submit(function(e) {

			e.preventDefault();

			var formData = new FormData(this);

			console.log(formData);
			$.ajax({
				url: "<?php echo site_url('save_final_completion'); ?>",
				type: 'POST',
				data: formData,

				success: function(response) {
					//alert(response);
					response = JSON.parse(response);

					if (response.status === 0) {
						$("div#final_completion_modal").modal({
							backdrop: 'static',
							keyboard: false,
							show: true
						});
					} else {
						$("div#final_completion_modal_success_response").modal({
							backdrop: 'static',
							keyboard: false,
							show: true
						});
					}

					if (response.status === 0) {
						let err_message = '';

						err_message += '<ul>';
						for (var i = 0; i < response.message.length; i++) {
							err_message += '<li>' + response.message[i] + '</li>';
						}

						err_message += '</ul>';
						$("div.final_completion_display").html(err_message);
					} else {
						$("div.final_completion_response").html(response.message);
					}
				},
				async: false,
				cache: false,
				contentType: false,
				processData: false
			});
		});

		$("form#save_initial_interview").submit(function(e) {

			e.preventDefault();

			var formData = new FormData(this);

			console.log(formData);
			$.ajax({
				url: "<?php echo site_url('save_initial_interview'); ?>",
				type: 'POST',
				data: formData,
				success: function(response) {

					//alert(response);
					$("div#interview_modal").modal({
						backdrop: 'static',
						keyboard: false,
						show: true
					});

					response = JSON.parse(response);

					if (response.status === 1) {
						$("div.interview_display").html(response.message);
					}
				},
				async: false,
				cache: false,
				contentType: false,
				processData: false
			});
		});

		$("form#setup_examination").submit(function(e) {

			e.preventDefault();

			var formData = new FormData(this);

			console.log(formData);
			$.ajax({
				url: "<?php echo site_url('setup_examination'); ?>",
				type: 'POST',
				data: formData,
				success: function(response) {

					$("div#applicantsetup_success").modal({
						backdrop: 'static',
						keyboard: false,
						show: true
					});

					response = JSON.parse(response);

					if (response.status === 1) {
						$("div.applicantsetup_success").html(response.message);
					}

					//location.reload();;
				},
				async: false,
				cache: false,
				contentType: false,
				processData: false
			});
		});

		// save exam result form 
		$("form#save_examination").submit(function(e) {

			e.preventDefault();

			var formData = new FormData(this);

			console.log(formData);
			$.ajax({
				url: "<?php echo site_url('save_examination'); ?>",
				type: 'POST',
				data: formData,
				success: function(response) {

					$("div#info_exam").modal({
						backdrop: 'static',
						keyboard: false,
						show: true
					});

					response = JSON.parse(response);

					if (response.status === 1) {
						$("div.info_exam_msg").html(response.message);
					}
				},
				async: false,
				cache: false,
				contentType: false,
				processData: false
			});
		});

		$("form#final_interview").submit(function(e) {

			e.preventDefault();

			var formData = new FormData(this);

			console.log(formData);
			$.ajax({
				url: "<?php echo site_url('final_interview'); ?>",
				type: 'POST',
				data: formData,
				success: function(response) {

					response = JSON.parse(response);

					$("div#interview_modal").modal({
						backdrop: 'static',
						keyboard: false,
						show: true
					});

					if (response.status === 1) {
						$("div.interview_display").html(response.message);
					} else {
						$("div.interview_display").html(response.message);
					}

				},
				async: false,
				cache: false,
				contentType: false,
				processData: false
			});
		});


		// for hiring // hired applicants
		$("form#hire_applicant").submit(function(e) {

			e.preventDefault();
			var formData = new FormData(this);
			console.log(formData);

			emp = $("input[name = 'appid']").val();
			strtDate = $("input[name = 'startDate']").val();
			eocDate = $("input[name = 'endDate']").val();
			company = $("select[name = 'company']").val();
			name = $("input[name = 'hidden_name']").val();
			join_emp = emp + "|" + strtDate + "|" + eocDate + "|" + name + "|" + company;
			promo_type = $("select[name = 'promotype']").val();
			let stores = $("input[name='check[]']:checked").map(function() {
				return this.value;
			}).get();
			var flag = 0;

			if (promo_type == "ROVING") {
				if (stores.length < 2) {
					alert("Employee type is ROVING, Please select two or more Store.");
					$("input[name='check[]']")[0].focus();
				} else {
					flag = 1;
				}
			} else {
				if (stores.length === 0) {
					alert("Employee type is STATION, Please select a Store.");
					$("input[name='check[]']")[0].focus();
				} else {
					flag = 1;
				}
			}
			if (flag == 1) {
				$.ajax({
					url: "<?php echo site_url('hire_applicant'); ?>",
					type: 'POST',
					data: formData,
					success: function(response) {

						response = JSON.parse(response);

						// $("div#pro_success").modal({
						// 	backdrop: 'static',
						// 	keyboard: false,
						// 	show: true
						// });

						if (response.status === 1) {

							Swal.fire({
								icon: 'info',
								title: response.message,
								showDenyButton: true,
								showCancelButton: true,
								confirmButtonText: 'Okay',
								denyButtonText: `Not now!`,
							}).then((result) => {
								if (result.isConfirmed) {
									window.open("http://172.16.43.134:81/hrms/report/new_intro.php?val=" + stores + "&emp=" + join_emp);
									location.reload();
								} else if (result.isDenied) {
									window.location = "<?php echo base_url('recruitment/page/menu/initial/deploy'); ?>";
									location.reload();
								}
							})
						}

					},
					async: false,
					cache: false,
					contentType: false,
					processData: false
				});
			}
		});

		$("form#interview_grade").submit(function(e) {

			e.preventDefault();

			var formData = $(this).serialize();
			//var formData = new FormData(this);

			$("div#grade_modal_info").modal({
				backdrop: 'static',
				keyboard: false,
				show: true
			});

			$.ajax({
				url: "<?php echo site_url('interview_grade'); ?>",
				type: 'POST',
				data: formData,
				success: function(response) {

					//alert(response);
					response = JSON.parse(response)

					//$("div.grade_modal_info_display").html(response);
					$("div.grade_modal_info_display").html(response.message);
					//alert(response.message);
				},
				// async: false,
				// cache: false,
				// contentType: false,
				// processData: false
			});
		});

		$("form#applicant_information").submit(function(e) {

			e.preventDefault();

			var formData = new FormData(this);

			console.log(formData);

			$.ajax({
				url: "<?php echo site_url('applicant_information'); ?>",
				type: 'POST',
				data: formData,
				success: function(response) {
					response = JSON.parse(response);

					Swal.fire({
						icon: 'info',
						title: response.message,
						showDenyButton: true,
						confirmButtonText: 'Okay',
					}).then((result) => {
						/* Read more about isConfirmed, isDenied below */
						if (result.isConfirmed) {
							location.reload();
						}
					})
				},
				async: false,
				cache: false,
				contentType: false,
				processData: false
			});
		});

	})

	function locate_vendor(deptval) {
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('locate_vendor'); ?>",
			data: {
				deptval: deptval
			},
			success: function(response) {
				//alert(response);
				$("select[name = 'vendor']").html(response);
			}
		});
	}

	function selectProduct(product) {
		$.ajax({
			url: "<?php echo site_url('select_product'); ?>?company_code=" + product,
			success: function(response) {
				//alert(response);
				$("select[name = 'product[]']").html(response);
			}
		});
	}

	function check_status(val) {
		let status = ['Widowed', 'Married'];

		if (status.includes(val)) {
			$("span.spouse-is-required").html('*');
			$("i.spouse-is-required").text('( Required )');
			$("input#spouse").attr('required', '');
		} else {
			$("span.spouse-is-required").html('');
			$("i.spouse-is-required").text('( Required if the applicant is Married/Widowed. )');
			$("input#spouse").removeAttr('required');
		}
	}

	function intro_letter() {
		let check = [];
		$("input[name = 'check[]']:checked").each(function() {

			check.push($(this).val())
		})

		$.ajax({
			type: "GET",
			url: "<?= site_url('show_intro_check') ?>",
			data: {
				check
			},
			success: function(data) {
				$("#intro_letter").html('').append(data);
			}
		});
	}

	function getDuration() {
		var strtDate = $("input[name = 'startDate']").val();
		var endDate = $("input[name = 'endDate']").val();

		//alert(strtDate+""+endDate)

		$.ajax({
			type: "POST",
			url: "<?php echo site_url('get_duration'); ?>",
			data: {
				strtDate: strtDate,
				endDate: endDate
			},
			success: function(response) {


				response = JSON.parse(response);

				if (response.status == 0) {
					$("input[name = 'duration_display']").val(response.duration);
				} else {
					alert(response.message)
				}
			}
		});
	}
	// newly added module add agency
	function select_agency(agency_code) {
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('company_select'); ?>",
			data: {
				agency_code: agency_code
			},
			success: function(response) {
				$("select[name = 'company']").html(response);
			}
		});
	}

	function validateForm(imgid) {

		var img = $("#" + imgid).val();
		var res = '';
		var i = img.length - 1;
		while (img[i] != ".") {
			res = img[i] + res;
			i--;
		}

		//checks the file format
		if (res != "PNG" && res != "jpg" && res != "JPG" && res != "png") {
			$("#" + imgid).val("");
			errDup('Invalid File Format. Take note on the allowed file!');
			return 1;
		}

		//checks the filesize- should not be greater than 2MB
		var uploadedFile = document.getElementById(imgid);
		var fileSize = uploadedFile.files[0].size < 1024 * 1024 * 2;
		if (fileSize == false) {
			$("#" + imgid).val("");
			errDup('The size of the file exceeds 2MB!')
			return 1;
		}
	}

	function getTotal() {
		let grade_total = 0;
		let selected = $("select.selection").each(function() {
			grade_total += parseFloat($(this).val());
		});

		console.log(grade_total);

		if (grade_total < 70) {
			var desc_rate = "VERY UNSATISFACTORY";
		} else if (grade_total > 69 && grade_total < 85) {
			var desc_rate = "UNSATISFACTORY";
		} else if (grade_total > 84 && grade_total < 90) {
			var desc_rate = "SATISFACTORY";
		} else if (grade_total > 89 && grade_total < 100) {
			var desc_rate = "VERY SATISFACTORY";
		} else if (grade_total == 100) {
			var desc_rate = "EXCELLENT";
		}
		$("input[name = 'num_rate']").val(grade_total);
		$("input[name = 'desc_rate']").val(desc_rate);
	}

	function getV(val) {
		//var selectV = $("input[name = 'promoV']").val();

		if (val == 'ROVING') {
			document.getElementById('rovingDiv').style.display = '';
			document.getElementById('stationDiv').style.display = 'none';
			$("#stationDiv *").attr({
				disabled: "true",
				checked: false
			});
			$("#rovingDiv *").removeAttr("disabled");
		} else {
			document.getElementById('rovingDiv').style.display = 'none';
			document.getElementById('stationDiv').style.display = '';
			$("#rovingDiv *").attr({
				disabled: "true",
				checked: false
			});
			$("#stationDiv *").removeAttr("disabled");
		}
	}

	/* function dupcheck()
	{
		var_duplicate =$("input[name='duplicate[]']:checked").map(function() { return this.value; }).get();
		if(var_duplicate.length > 1)
		{
			$("div#small_alert").modal({
				backdrop: 'static',
				keyboard: false,
				show: true
			});
						
			$("div.small_alert_display").html("Please select one name only to be updated..");
		}
		else
		{
			
		}
	} */
</script>