<script>
    $(document).ready(function() 
	{
		//$("#birthdate").mask("99-99-9999");
		
		$("form#proceed_upload").submit(function(e) {

            e.preventDefault();
			
            var formData = new FormData(this);

			$.ajax({
				url: "<?php echo site_url('upload_initial'); ?>",
				type: 'POST',
				data:formData,
				success: function(response) {
					
					
					
					$("div#upload_success_proceed_record").modal({
						backdrop: 'static',
						keyboard: false,
						show: true
					}); 
					
					response = JSON.parse(response);
					if(response.status === 1)
					{
						$("div.upload_success_proceed_record").html(response.message);
					}
					
				},
				async: false,
				cache: false,
				contentType: false,
				processData: false
			});
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
			
			if(gender != '' && civilstatus != '' && firstname != '' && lastname != '')
			{
				//alert(gender+" "+civilstatus+" "+firstname+" "+middlename+" "+lastname+" "+suffix);
				
				$("div#browse_blacklist").modal({
						backdrop: 'static',
						keyboard: false,
						show: true
					});
				
				$.ajax({
				type: "POST",
				url: "<?php echo site_url('check_applicant_duplicate_or_blacklist'); ?>",
				data : formData,
				success: function(response) 
				{
					response = JSON.parse(response);
					console.log(response.message);
					
					$("div.browse_blacklist").html(response.message);
					
					if(response.proceed === 1 && response.status === 1)
					{
						$('#upload_save').prop('disabled', false);
						
						$("[name='updt_or_appnd']").val('UPDATE');
						$("[name='hidden_gender']").val(gender); 
						$("[name='hidden_civil_status']").val(civilstatus);
						$("[name='hidden_firstname']").val(firstname); 
						$("[name='hidden_middlename']").val(middlename); 
						$("[name='hidden_lastname']").val(lastname); 
						$("[name='hidden_suffix']").val(suffix); 
					}
					else if(response.proceed === 1 && response.status === 0)
					{
						$('#upload_save').prop('disabled', false);
						
						$("[name='updt_or_appnd']").val('INSERT');
						$("[name='hidden_gender']").val(gender); 
						$("[name='hidden_civil_status']").val(civilstatus);
						$("[name='hidden_firstname']").val(firstname); 
						$("[name='hidden_middlename']").val(middlename); 
						$("[name='hidden_lastname']").val(lastname); 
						$("[name='hidden_suffix']").val(suffix);
					}	
				}
				});	
			}
			else
			{
				$("div#browse_blacklist").modal({
						backdrop: 'static',
						keyboard: false,
						show: true
					});
					
				$("div.browse_blacklist").html("Please fill the required box!");
			}
		});
		
		
		$("[name='position']").change(function() {
			
			var value =$(this).val()
			if(value == 'Merchandiser Seasonal' || value == 'Promodiser Seasonal')
			{
				$('#resume').prop('disabled', true);
				$('#application').prop('disabled', true);
				$('#transcript').prop('disabled', true);
			}
			else
			{
				$('#resume').prop('disabled', false);
				$('#application').prop('disabled', false);
				$('#transcript').prop('disabled', false);
			}
		});
		
		
		$("button#reloadpage").click(function() {
			setTimeout(function(){
			location.reload();
			},500);
		});
		
		$("button#record").click(function() {
			setTimeout(function(){
            window.location = "<?php echo base_url('recruitment/page/menu/initial/record'); ?>";
            },500);
		});
		
		const tableViewEmp_interview = $('#tableViewEmp_interview').DataTable();
		
		// event on clicking record applicant
		$('#tableViewEmp_interview').on('click', 'button.initial_interview', function() 
		{
            var id = this.id;
			
            if (!$(this).parents('tr').hasClass('selected')) 
			{
                tableViewEmp_interview.$('tr.selected').removeClass('selected');
                $(this).parents('tr').addClass('selected');
            }
			
			$.ajax({
				url: "<?php echo site_url('initial_interview'); ?>",
				type: 'POST',
				data:{id},
				success: function(response)
				{
					$("div#initial_interview_modal").modal({
						backdrop: 'static',
						keyboard: false,
						show: true
					});	
					
					$("div.initial_interview_display").html(response);
				}
			});
		});
		
		$('#tableViewEmp_interview').on('click', 'button.setup_interview', function() 
		{
            var id = this.id;
			
            if (!$(this).parents('tr').hasClass('selected')) 
			{
                tableViewEmp_interview.$('tr.selected').removeClass('selected');
                $(this).parents('tr').addClass('selected');
            }
			
			$.ajax({
				url: "<?php echo site_url('setup_interview'); ?>",
				type: 'POST',
				data:{id},
				success: function(response)
				{
					
					$("div#setup_interview_modal").modal({
						backdrop: 'static',
						keyboard: false,
						show: true
					});	
					
					$("div.setup_interview_display").html(response);
				}
			});
		});
		
		$('#tableViewEmp_interview').on('click', 'button.check_interview', function() 
		{
            var id = this.id;
			
            if (!$(this).parents('tr').hasClass('selected')) 
			{
                tableViewEmp_interview.$('tr.selected').removeClass('selected');
                $(this).parents('tr').addClass('selected');
            }
			
			$.ajax({
				url: "<?php echo site_url('check_interview'); ?>",
				type: 'POST',
				data:{id},
				success: function(response)
				{
					
					$("div#interviewdetails_modal").modal({
						backdrop: 'static',
						keyboard: false,
						show: true
					});	
					
					$("div.interviewdetails_display").html(response);
				}
			});
		});
		
		
		const tableViewEmp = $('#tableViewEmp').DataTable();
		
		// event on clicking record applicant
		$('#tableViewEmp').on('click', 'button.record', function() 
		{
            var id = this.id;
			
            if (!$(this).parents('tr').hasClass('selected')) 
			{
                tableViewEmp.$('tr.selected').removeClass('selected');
                $(this).parents('tr').addClass('selected');
            }
			
			$.ajax({
				url: "<?php echo site_url('proceed_record_applicants'); ?>",
				type: 'POST',
				data:{id},
				success: function(response)
				{
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
		$('#tableViewEmp_one').on('click', 'button.setup_exam', function() 
		{
            var id = this.id;
			
            if (!$(this).parents('tr').hasClass('selected')) 
			{
                tableViewEmp_one.$('tr.selected').removeClass('selected');
                $(this).parents('tr').addClass('selected');
            }
			
			$.ajax({
				url: "<?php echo site_url('applicant_examination_setup'); ?>",
				type: 'POST',
				data:{id},
				success: function(response)
				{
					
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
		$('#tableViewEmp_one').on('click', 'button.view_detail', function() 
		{
            var id = this.id;
			
            if (!$(this).parents('tr').hasClass('selected')) 
			{
                tableViewEmp_one.$('tr.selected').removeClass('selected');
                $(this).parents('tr').addClass('selected');
            }
			
			$.ajax({
				url: "<?php echo site_url('view_exam_setup'); ?>",
				type: 'POST',
				data:{id},
				success: function(response)
				{
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
		$('#tableViewEmp_one').on('click', 'button.tag_interview', function() 
		{
            var id = this.id;
			
            if (!$(this).parents('tr').hasClass('selected')) 
			{
                tableViewEmp_one.$('tr.selected').removeClass('selected');
                $(this).parents('tr').addClass('selected');
            }
				$.ajax({
					url: "<?php echo site_url('tag_applicant_interview'); ?>",
					type: 'POST',
					data:{id},
					success: function(response)
					{
						response = JSON.parse(response);
						
						$("div#info_exam").modal({
							backdrop: 'static',
							keyboard: false,
							show: true
						});	
						
						if(response.status === 1)
						{
							$("div.info_exam_msg").html(response.message);
						}
						
					}
				});
		});
		
		// tag for transfer button
		$('#tableViewEmp_one').on('click', 'button.tag_transfer', function() 
		{
            var id = this.id;
			
            if (!$(this).parents('tr').hasClass('selected')) 
			{
                tableViewEmp_one.$('tr.selected').removeClass('selected');
                $(this).parents('tr').addClass('selected');
            }
				$.ajax({
					url: "<?php echo site_url('tag_applicant_transfer'); ?>",
					type: 'POST',
					data:{id},
					success: function(response)
					{
						response = JSON.parse(response);
						
						$("div#info_exam").modal({
							backdrop: 'static',
							keyboard: false,
							show: true
						});	
						
						if(response.status === 1)
						{
							$("div.info_exam_msg").html(response.message);
						}
					}
				});
		});
		
		// tag for transfer button
		$('#tableViewEmp').on('click', 'button.upload_final', function() 
		{
            var id = this.id;
			
            if (!$(this).parents('tr').hasClass('selected')) 
			{
                tableViewEmp.$('tr.selected').removeClass('selected');
                $(this).parents('tr').addClass('selected');
            }
				$.ajax({
					url: "<?php echo site_url('final_completion'); ?>",
					type: 'POST',
					data:{id},
					success: function(response)
					{
						
						$("div#final_modal").modal({
							backdrop: 'static',
							keyboard: false,
							show: true
						});	
						
						$("div.final_display").html(response);
					}
				});
		});
		
		$('#tableViewEmp').on('click', 'button.hiring', function() 
		{
            var id = this.id;
			
            if (!$(this).parents('tr').hasClass('selected')) 
			{
                tableViewEmp.$('tr.selected').removeClass('selected');
                $(this).parents('tr').addClass('selected');
            }
				$.ajax({
					url: "<?php echo site_url('hiring_setup'); ?>",
					type: 'POST',
					data:{id},
					success: function(response)
					{
						
						$("div#hired_applicant").modal({
							backdrop: 'static',
							keyboard: false,
							show: true
						});	
						
						$("div.hired_display").html(response);
					}
				});
		});
		
		$("form#setup_interviewee").submit(function(e) {

            e.preventDefault();
			
            var formData = new FormData(this);

			console.log(formData);
			$.ajax({
				url: "<?php echo site_url('setup_interviewee'); ?>",
				type: 'POST',
				data:formData,
				success: function(response) {
					
					$("div#interview_modal").modal({
						backdrop: 'static',
						keyboard: false,
						show: true
					});	
					
					response = JSON.parse(response);
					
					if(response.status === 1)
					{
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
				data:formData,
				
				success: function(response) {
					
					$("div#final_completion_modal").modal({
						backdrop: 'static',
						keyboard: false,
						show: true
					});	
					
					response = JSON.parse(response);
					
					if(response.status === 1)
					{
						$("div.final_completion_display").html(response.message);
					}
					
					//$("div.final_completion_display").html(response);
					
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
				data:formData,
				success: function(response) {
					
					//alert(response);
					$("div#interview_modal").modal({
						backdrop: 'static',
						keyboard: false,
						show: true
					});	
					
					response = JSON.parse(response);
					
					if(response.status === 1)
					{
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
				data:formData,
				success: function(response) {
					
					$("div#applicantsetup_success").modal({
						backdrop: 'static',
						keyboard: false,
						show: true
					});	
					
					response = JSON.parse(response);
					
					if(response.status === 1)
					{
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
				data:formData,
				success: function(response) {
					
					//alert(response);
					
					$("div#info_exam").modal({
						backdrop: 'static',
						keyboard: false,
						show: true
					});	
					
					$("div.info_exam").html(response);
					
					response = JSON.parse(response);
					
					if(response.status === 1)
					{
						$("div.info_exam").html(response.message);
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
				data:formData,
				success: function(response) {
					
					response = JSON.parse(response);
					
					$("div#interview_modal").modal({
						backdrop: 'static',
						keyboard: false,
						show: true
					});	
					
					if(response.status === 1)
					{
						$("div.interview_display").html(response.message);
					}
					else
					{
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
			
			promo_type = $("select[name = 'promotype']").val();
			let stores = $("input[name='check[]']:checked").map(function() { return this.value; }).get();
			
			if(stores.length != 0) // checking if store checkbox 
			{
				if(promo_type == "Roving")
				{
					if (stores.length == 1) 
					{
						alert("Employee type is ROVING, Please select two or more Store..");
						$("input[name='check[]']")[0].focus();	
					}
				}
				else
				{
					if (stores.length > 1 ) 
					{
						alert("Employee type is STATION, Please select one Store only..");
						$("input[name='check[]']")[0].focus();
					}
				}
			
				$.ajax({
					url: "<?php echo site_url('hire_applicant'); ?>",
					type: 'POST',
					data:formData,
					success: function(response) {
						
					alert(response);	
						
					},
					async: false,
					cache: false,
					contentType: false,
					processData: false
				});
			}
			else
			{
				alert("Please check atleast 1 for STATION 2 for ROVING");
				$("input[name='check[]']")[0].focus();
			}
        });
		
		
		$("form#applicant_information").submit(function(e) {

            e.preventDefault();
			
            var formData = new FormData(this);

			console.log(formData);
			$.ajax({
				url: "<?php echo site_url('applicant_information'); ?>",
				type: 'POST',
				data:formData,
				success: function(response) {
					
					response = JSON.parse(response);
					
					$("div#applicant_record_success").modal({
						backdrop: 'static',
						keyboard: false,
						show: true
					});	
					
					if(response.status === 1)
					{
						$("div.applicant_record_success").html(response.message);
					} 
					
				},
				async: false,
				cache: false,
				contentType: false,
				processData: false
			});
        });
		
	})
	
	function locate_vendor(deptval)
	{
		$.ajax({
            type : "POST",
			url: "<?php echo site_url('locate_vendor'); ?>",
            data : { deptval:deptval },
            success : function(response){
                //alert(response);
                $("select[name = 'vendor']").html(response);
            }
        });
	} 
	
	function selectProduct(product)
	{
		$.ajax({
			url: "<?php echo site_url('select_product'); ?>?company_code="+product,
            success : function(response){
                //alert(response);
                $("select[name = 'product[]']").html(response);
            }
        });
	} 
	
	function check_status(val)
	{
		let status = ['Widowed','Married'];
		
		if(status.includes(val))
		{
			$("span.spouse-is-required").html('*');
			$("i.spouse-is-required").text('( Required )');
			$("input#spouse").attr('required', '');
		}
		else
		{
			$("span.spouse-is-required").html('');
			$("i.spouse-is-required").text('( Required if the applicant is Married/Widowed. )');
			$("input#spouse").removeAttr('required');
		}
	}
	
	function intro_letter()
	{
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
	
	function getDuration()
	{	
      var strtDate = $("input[name = 'startDate']").val(); 
	  var endDate = $("input[name = 'endDate']").val(); 
	  
	  //alert(strtDate+""+endDate)
	  
	  $.ajax({
			type : "POST",
			url: "<?php echo site_url('get_duration'); ?>",
            data : 
				{ strtDate:strtDate, endDate:endDate },
			success : function(response){
             
				
				response = JSON.parse(response);
				
					if(response.status == 0)
					{
						$("input[name = 'duration_display']").val(response.duration);
					} 
					else
					{
						alert(response.message)
					}
            }
        });
	}
	// newly added module add agency
    function select_agency(agency_code) 
	{
		$.ajax({
            type : "POST",
			url: "<?php echo site_url('company_select'); ?>",
            data : { agency_code:agency_code },
            success : function(response)
			{
                $("select[name = 'company']").html(response);
            }
        });
    }
	
	function validateForm(imgid) 
	{
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
</script>