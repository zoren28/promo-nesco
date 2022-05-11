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
		
		
		$("button#record").click(function() {
			setTimeout(function(){
            window.location = "<?php echo base_url('recruitment/page/menu/initial/record'); ?>";
            },500);
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
				//alert(response);	
				$("div.record_applicants").html(response);
				}
			});
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
					
					alert(response);
				},
				async: false,
				cache: false,
				contentType: false,
				processData: false
			});
        });
		
	})
	
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
</script>