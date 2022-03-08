<script>
    $(document).ready(function() {

        var company = $("[name = 'company']").val();
        var business_unit = $("[name = 'business_unit']").val();
        var department = $("[name = 'department']").val();
        var promo_type = $("[name = 'promo_type']").val();

        $(".select2").select2();

        var dataTable = $("#employee_masterfile_table").DataTable({

            "destroy": true,
            "ajax": {
                url: "<?php echo site_url('fetch_employee_masterfile'); ?>",
                type: "POST",
                data: {
                    company: company,
                    business_unit: business_unit,
                    department: department,
                    promo_type: promo_type
                },
            },
            "order": [],
            "columnDefs": [{
                "targets": [1, 2, 3, 4, 5, 6],
                "orderable": false,
            }, ],
        });

        $("button#filter").click(function() {

            $("div#filter_employee").modal({
                backdrop: 'static',
                keyboard: false
            });

            $("div#filter_employee").modal("show");
        });

        $("form#filter_employee").submit(function(e) {

            e.preventDefault();

            $("div#filter_employee").modal("hide");
            let company = $("[name = 'company']").val();
            let business_unit = $("[name = 'business_unit']").val();
            let department = $("[name = 'department']").val();
            let promo_type = $("[name = 'promo_type']").val();

            let filter = btoa(`${company}|_${business_unit}|_${department}|_${promo_type}`);
            window.location = "<?php echo base_url('placement/page/menu/employee/masterfile/') ?>" + filter;
        });

        $("input[name = 'searchThis']").keypress(function(e) {
            console.log('asd')
            var code = e.keyCode || e.which;
            if (code == 13) {

                var searchThis = $("input[name = 'searchThis']").val();
                $(".loading_process").html('<center><img src="<?php echo base_url('assets/images/gif/loader_seq.gif'); ?>"></center>');
                $("div.search_employee").hide().html('');
                $.ajax({
                    type: "GET",
                    url: "<?php echo site_url('employee/search_employee'); ?>",
                    data: {
                        searchThis
                    },
                    success: function(response) {

                        $(".loading_process").html('');
                        $("div.search_employee").show().html(response);
                    }
                });
            }
        });

        $("form#data_searchApplicant").submit(function(e) {

            e.preventDefault();

            var lname = $("input[name = 'lastnameApp']").val().trim();
            var fname = $("input[name = 'firstnameApp']").val().trim();

            if (lname == "") {

                errDup("Please fill out required field.");
            } else {

                $(".loading_process").html('<img src="<?php echo base_url('assets/images/gif/loading.gif'); ?>" width="20" height="20"> Please Wait...');
                $.ajax({
                    type: "GET",
                    url: "<?php echo site_url('search_applicant'); ?>",
                    data: {
                        lname,
                        fname
                    },
                    success: function(response) {

                        $(".loading_process").html('<i class="fa fa-search"></i> Search');
                        $("div.search_applicant").html(response);
                    }
                });
            }
        });

        if ("<?php echo $title; ?>" == "employee" && "<?php echo $page; ?>" == "profile") {

            getdefault('basic_info');
        }

        $("form#dataProfilePic").submit(function(e) {

            e.preventDefault();
            var formData = new FormData(this);

            $.ajax({
                url: "<?php echo site_url('uploadProfilePic'); ?>",
                type: 'POST',
                data: formData,
                success: function(data) {

                    response = data.trim();
                    if (response == "success") {

                        $.alert.open({
                            type: 'warning',
                            title: 'Info',
                            icon: 'confirm',
                            cancel: false,
                            content: "Photo Successfully Updated!",
                            buttons: {
                                OK: 'Yes'
                            },

                            callback: function(button) {
                                if (button == 'OK') {

                                    $("#profilePic").modal("hide");
                                    location.reload();
                                }

                            }
                        });
                    } else {

                        alert(response);
                    }
                },
                async: false,
                cache: false,
                contentType: false,
                processData: false
            });
        });

        $("form#data_birthCert").submit(function(e) {

            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                url: "<?php echo site_url('updateScannedNSO'); ?>",
                type: 'POST',
                data: formData,
                success: function(data) {

                    response = data.trim();
                    if (response == "success") {

                        $.alert.open({
                            type: 'warning',
                            title: 'Info',
                            icon: 'confirm',
                            cancel: false,
                            content: "Birth Certificate Successfully Updated!",
                            buttons: {
                                OK: 'Yes'
                            },

                            callback: function(button) {
                                if (button == 'OK') {

                                    $("#update_birthCert").modal("hide");
                                    getdefault('family');
                                }

                            }
                        });
                    } else {

                        alert(response);
                    }
                },
                async: false,
                cache: false,
                contentType: false,
                processData: false
            });
        });

        $("form#dataSeminar").submit(function(e) {

            e.preventDefault();
            var formData = new FormData(this);

            var semName = $("input[name = 'semName']").val();
            var semLocation = $("input[name = 'semLocation']").val();

            if (semName == "" || semLocation == "") {

                $.alert.open({
                    type: 'warning',
                    cancel: false,
                    content: "Please Fill-up Required Fields!",
                    buttons: {
                        OK: 'Ok'
                    },

                    callback: function(button) {
                        if (button == 'OK') {

                            if (semName == "") {

                                $("input[name = 'semName']").css("border-color", "rgb(221, 75, 57)");
                            }

                            if (semLocation == "") {

                                $("input[name = 'semLocation']").css("border-color", "rgb(221, 75, 57)");
                            }
                        }
                    }
                });
            } else {

                $("button#submit_seminar_btn").prop("disabled", true);

                $.ajax({
                    url: "<?php echo site_url('submitSeminar'); ?>",
                    type: 'POST',
                    data: formData,
                    success: function(data) {

                        response = data.split("||");
                        if (response[0].trim() == "success") {

                            $.alert.open({
                                type: 'warning',
                                title: 'Info',
                                icon: 'confirm',
                                cancel: false,
                                content: "Seminar/Eligibility/Training Information Successfully " + response[1].trim(),
                                buttons: {
                                    OK: 'Yes'
                                },

                                callback: function(button) {
                                    if (button == 'OK') {

                                        $("#seminar_form").modal("hide");
                                        getdefault('seminar');
                                    }

                                }
                            });
                        } else {

                            alert(response);
                        }
                    },
                    async: false,
                    cache: false,
                    contentType: false,
                    processData: false
                });
            }
        });

        $("form#dataUploadScannedFile").submit(function(e) {

            e.preventDefault();
            var formData = new FormData(this);

            $.ajax({
                url: "<?php echo site_url('uploadScannedFile'); ?>",
                type: 'POST',
                data: formData,
                success: function(data) {

                    response = data.split("||");
                    if (response[0].trim() == "success") {

                        $.alert.open({
                            type: 'warning',
                            title: 'Info',
                            icon: 'confirm',
                            cancel: false,
                            content: response[1].trim(),
                            buttons: {
                                OK: 'Yes'
                            },

                            callback: function(button) {

                                if (button == 'OK') {

                                    $("#uploadScannedFile").modal("hide");
                                    getdefault('employment');
                                }

                            }
                        });
                    } else {

                        alert(response);
                    }
                },
                async: false,
                cache: false,
                contentType: false,
                processData: false
            });
        });
    });

    function changeProfilePic() {

        $("#profilePic").modal({
            backdrop: 'static',
            keyboard: false
        });

        $("#profilePic").modal("show");
        var empId = $("[name = 'empId']").val();

        $.ajax({
            type: "POST",
            url: "<?php echo site_url('changeProfilePic'); ?>",
            data: {
                empId: empId
            },
            success: function(data) {

                $(".profilePic").html(data);
            }
        });
    }

    function uploadScannedFile(contract, recordNo, empId) {

        $("#uploadScannedFile").modal({
            backdrop: 'static',
            keyboard: false
        });

        $("#uploadScannedFile").modal("show");
        $(".uploadScannedFile").html("");

        $.ajax({
            type: "POST",
            url: "<?php echo site_url('uploadScannedFileForm'); ?>",
            data: {
                contract: contract,
                recordNo: recordNo,
                empId: empId
            },
            success: function(data) {

                $(".uploadScannedFile").html(data);
            }
        });
    }

    function readURL(input, upload) {

        $('#clear' + upload).show();
        var res = validateForm(upload);

        if (res != 1) {

            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#photo' + upload).attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        } else {
            $('#clear' + upload).hide();
            $('#photo' + upload).removeAttr('src');
        }
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

    function clears(file, preview, clrbtn) {

        $("#" + file).val("");
        $('#' + preview).removeAttr('src');
        $('#' + clrbtn).hide();
    }

    function changePhoto(file, photoid, change) {

        $.alert.open({
            type: 'warning',
            cancel: false,
            content: "You are attempting to change the uploaded " + file + ", <br> Click OK to proceed.",
            buttons: {
                OK: 'Ok',
                NO: 'Not now'
            },

            callback: function(button) {
                if (button == 'OK') {

                    $('#' + change).hide();
                    $('#' + photoid).show();
                }

            }
        });
    }

    function viewProfile() {

        var empId = $("[name = 'empId']").val();
        window.open("<?php echo 'http://' . $_SERVER['SERVER_ADDR'] . ":" . $_SERVER['SERVER_PORT'] . '/hrms/administrator/personnel_info_pdf.php?app_id='; ?>" + empId);
    }

    function getdefault(code) {

        var empId = $("[name = 'empId']").val();

        $.ajax({
            type: "GET",
            url: "<?php echo site_url('employee_information_details'); ?>/" + code,
            data: {
                empId
            },
            success: function(data) {

                $("#details").html(data);
            }
        });
    }

    function edit_basicinfo() {

        $("#edit-basicinfo").hide();
        $("#update_basicinfo").show();
        $(".inputForm").prop("disabled", false);
    }

    function edit_family() {

        $("#edit-family").hide();
        $("#update_family").show();
        $(".inputForm").prop("disabled", false);
    }

    function edit_contact() {

        $("#edit-contact").hide();
        $("#update_contact").show();
        $(".inputForm").prop("disabled", false);
    }

    function edit_educ() {

        $("#edit-educ").hide();
        $("#update_educ").show();
        $(".inputForm").prop("disabled", false);
    }

    function edit_skills() {

        $("#edit-skills").hide();
        $("#update_skills").show();
        $(".inputForm").prop("disabled", false);
    }

    function edit_apphis() {

        $("#edit-apphis").hide();
        $("#update_apphis").show();
        $(".inputForm").prop("disabled", false);
    }

    function edit_benefits() {

        $(".inputForm").prop("disabled", false);
        $("#edit-benefits").hide();
        $("#update_benefits").show();
    }

    function edit_remarks() {

        $(".inputForm").prop("disabled", false);
        $("#edit-remarks").hide();
        $("#update_remarks").show();
    }

    function update(code) {

        var empId = $("[name = 'empId']").val();

        $.alert.open({
            type: 'warning',
            cancel: false,
            content: "Are you sure to save the new update?",
            buttons: {
                OK: 'Yes',
                NO: 'Not now'
            },

            callback: function(button) {
                if (button == 'OK') {

                    if (code == "update_basicinfo") {

                        var fname = $("input[name = 'fname']").val();
                        var mname = $("input[name = 'mname']").val();
                        var lname = $("input[name = 'lname']").val();
                        var suffix = $("input[name = 'suffix']").val();
                        var datebirth = $("input[name = 'datebirth']").val();
                        var citizenship = $("input[name = 'citizenship']").val();
                        var gender = $("select[name = 'gender']").val();
                        var civilstatus = $("select[name = 'civilstatus']").val();
                        var religion = $("input[name = 'religion']").val();
                        var bloodtype = $("select[name = 'bloodtype']").val();
                        var weight = $("input[name = 'weight']").val();
                        var height = $("input[name = 'height']").val();

                        $.ajax({
                            type: "POST",
                            url: "<?php echo site_url(); ?>/" + code,
                            data: {
                                empId: empId,
                                fname: fname,
                                mname: mname,
                                lname: lname,
                                suffix: suffix,
                                datebirth: datebirth,
                                citizenship: citizenship,
                                gender: gender,
                                civilstatus: civilstatus,
                                religion: religion,
                                bloodtype: bloodtype,
                                weight: weight,
                                height: height
                            },
                            success: function(data) {

                                if (data.trim() == "success") {

                                    succSave("Updating Sucessful!");
                                } else {

                                    alert(data);
                                }

                                getdefault('basic_info');
                            }
                        });
                    } else if (code == "update_family") {

                        var mother = $("input[name = 'mother']").val();
                        var father = $("input[name = 'father']").val();
                        var guardian = $("input[name = 'guardian']").val();
                        var spouse = $("input[name = 'spouse']").val();
                        // newly added field
                        var mother_bdate = $("input[name = 'mother_bdate']").val();
                        var father_bdate = $("input[name = 'father_bdate']").val();
                        var spouse_bdate = $("input[name = 'spouse_bdate']").val();
                        var mother_work = $("input[name = 'mo_work']").val();
                        var father_work = $("input[name = 'fa_work']").val();
                        var spouse_work = $("input[name = 'sp_work']").val();

                        if ($("input[name = 'mo_work']").is(':checked')) {
                            mother_work = mother_work;
                        } else {
                            mother_work = "";
                        }
                        if ($("input[name = 'fa_work']").is(':checked')) {
                            father_work = father_work;
                        } else {
                            father_work = "";
                        }
                        if ($("input[name = 'sp_work']").is(':checked')) {
                            spouse_work = spouse_work;
                        } else {
                            spouse_work = "";
                        }

                        $.ajax({
                            type: "POST",
                            url: "<?php echo site_url(); ?>" + code,
                            data: {
                                empId: empId,
                                mother: mother,
                                father: father,
                                guardian: guardian,
                                spouse: spouse,
                                mother_bdate: mother_bdate,
                                father_bdate: father_bdate,
                                spouse_bdate: spouse_bdate,
                                mother_work: mother_work,
                                father_work: father_work,
                                spouse_work: spouse_work
                            },
                            success: function(data) {

                                if (data.trim() == "success") {

                                    succSave("Updating Sucessful!");
                                } else {

                                    alert(data);
                                }

                                getdefault('family');
                            }
                        });
                    } else if (code == "update_contact") {

                        var homeaddress = $("input[name = 'homeaddress']").val();
                        var cityaddress = $("input[name = 'cityaddress']").val();
                        var contactperson = $("input[name = 'contactperson']").val();
                        var contactpersonadd = $("input[name = 'contactpersonadd']").val();
                        var contactpersonno = $("input[name = 'contactpersonno']").val();
                        var cellphone = $("input[name = 'cellphone']").val();
                        var telno = $("input[name = 'telno']").val();
                        var email = $("input[name = 'email']").val();
                        var fb = $("input[name = 'fb']").val();
                        var twitter = $("input[name = 'twitter']").val();

                        $.ajax({
                            type: "POST",
                            url: "<?php echo site_url(); ?>" + code,
                            data: {
                                empId: empId,
                                homeaddress: homeaddress,
                                cityaddress: cityaddress,
                                contactperson: contactperson,
                                contactpersonadd: contactpersonadd,
                                contactpersonno: contactpersonno,
                                cellphone: cellphone,
                                telno: telno,
                                email: email,
                                fb: fb,
                                twitter: twitter
                            },
                            success: function(data) {

                                if (data.trim() == "success") {

                                    succSave("Updating Sucessful!");
                                } else {

                                    alert(data);
                                }

                                getdefault('contact');
                            }
                        });
                    } else if (code == "update_educ") {

                        var attainment = $("[name = 'attainment']").val();
                        var school = $("[name = 'school']").val();
                        var course = $("[name = 'course']").val();

                        $.ajax({
                            type: "POST",
                            url: "<?php echo site_url(); ?>" + code,
                            data: {
                                empId: empId,
                                attainment: attainment,
                                school: school,
                                course: course
                            },
                            success: function(data) {

                                if (data.trim() == "success") {

                                    succSave("Updating Sucessful!");
                                } else {

                                    alert(data);
                                }

                                getdefault('educ');
                            }
                        });
                    } else if (code == "update_skills") {

                        var hobbies = $("[name = 'hobbies']").val();
                        var skills = $("[name = 'skills']").val();

                        $.ajax({
                            type: "POST",
                            url: "<?php echo site_url(); ?>" + code,
                            data: {
                                empId: empId,
                                hobbies: hobbies,
                                skills: skills
                            },
                            success: function(data) {

                                if (data.trim() == "success") {

                                    succSave("Updating Sucessful!");
                                } else {

                                    alert(data);
                                }

                                getdefault('skills');
                            }
                        });
                    } else if (code == "update_apphis") {

                        var posApplied = $("[name = 'posApplied']").val();
                        var dateApplied = $("[name = 'dateApplied']").val();
                        var dateExamined = $("[name = 'dateExamined']").val();
                        var examResult = $("[name = 'examResult']").val();
                        var dateBrief = $("[name = 'dateBrief']").val();
                        var dateHired = $("[name = 'dateHired']").val();
                        var aeRegular = $("[name = 'aeRegular']").val();

                        $.ajax({
                            type: "POST",
                            url: "<?php echo site_url(); ?>" + code,
                            data: {
                                empId: empId,
                                posApplied: posApplied,
                                dateApplied: dateApplied,
                                dateExamined: dateExamined,
                                examResult: examResult,
                                dateBrief: dateBrief,
                                dateHired: dateHired,
                                aeRegular: aeRegular
                            },
                            success: function(data) {

                                if (data.trim() == "success") {

                                    succSave("Updating Sucessful!");
                                } else {

                                    alert(data);
                                }

                                getdefault('application');
                            }
                        });
                    } else if (code == "update_benefits") {

                        var ph = $("input[name = 'ph']").val();
                        var sss = $("input[name = 'sss']").val();
                        var pagibig = $("input[name = 'pagibig']").val();
                        var pagibigrtn = $("input[name = 'pagibigrtn']").val();
                        var tinno = $("input[name = 'tinno']").val();

                        $.ajax({
                            type: "POST",
                            url: "<?php echo site_url(); ?>" + code,
                            data: {
                                empId: empId,
                                ph: ph,
                                sss: sss,
                                pagibig: pagibig,
                                pagibigrtn: pagibigrtn,
                                tinno: tinno
                            },
                            success: function(data) {

                                if (data.trim() == "success") {

                                    succSave("Updating Sucessful!");
                                } else {

                                    alert(data);
                                }

                                getdefault('benefits');
                            }
                        });
                    } else if (code == "update_remarks") {

                        var remarks = $("textarea[name = 'remarks']").val();

                        $.ajax({
                            type: "POST",
                            url: "<?php echo site_url(); ?>" + code,
                            data: {
                                empId: empId,
                                remarks: remarks
                            },
                            success: function(data) {

                                if (data.trim() == "success") {

                                    succSave("Updating Sucessful!");
                                } else {

                                    alert(data);
                                }

                                getdefault('remarks');
                            }
                        });
                    }
                }
            }
        });
    }

    function fetchAssignedDept(field) {
        if (field != '') {

            let id = field.split('/');
            $.ajax({
                type: "GET",
                url: "<?php echo site_url('fetch_assigned_department'); ?>/" + id[0],
                success: function(data) {

                    $("[name = 'department']").html(data);
                }
            });
        } else {

            $("[name = 'department']").html('<option value=""> Select Department </option>');
        }
    }

    function delete_child(childId) {

        $.alert.open({
            type: 'warning',
            cancel: false,
            content: "Are you sure you want to delete this child information?",
            buttons: {
                OK: 'Yes',
                NO: 'Not now'
            },

            callback: function(button) {
                if (button == 'OK') {

                    $.ajax({
                        type: "POST",
                        url: "<?php echo site_url('delete_children_info'); ?>",
                        data: {
                            childId: childId
                        },
                        success: function(data) {

                            var response = data.trim();
                            if (response == "success") {

                                $.alert.open({
                                    type: 'warning',
                                    title: 'Info',
                                    icon: 'confirm',
                                    cancel: false,
                                    content: "The child information has been deleted.",
                                    buttons: {
                                        OK: 'Yes'
                                    },

                                    callback: function(button) {
                                        if (button == 'OK') {

                                            getdefault('family');
                                        }
                                    }
                                });

                            } else {

                                console.log(data);
                            }
                        }
                    });
                }
            }
        });
    }

    function submit_added_children() {

        var formData = $("form#data_childrenInfo").serialize();

        var counter = 1;
        var i = 0;
        var if_empty = "";
        var fname = $("input[name = 'fname1[]']");
        var lname = $("input[name = 'lname1[]']");
        var bday = $("input[name = 'bday1[]']");
        var gender = $("select[name = 'gender1[]']");

        $('input[name="deleted[]"]').each(function() {

            if ($(this).val() == "") {

                if (fname[i].value == "" || lname[i].value == "" || bday[i].value == "" || gender[i].value == "") {

                    if (fname[i].value == "") {

                        $("input.fname_" + counter).css("border-color", "rgb(221, 75, 57)");
                    }

                    if (lname[i].value == "") {

                        $("input.lname_" + counter).css("border-color", "rgb(221, 75, 57)");
                    }

                    if (bday[i].value == "") {

                        $("input.bday_" + counter).css("border-color", "rgb(221, 75, 57)");
                    }

                    if (gender[i].value == "") {

                        $("select.gender_" + counter).css("border-color", "rgb(221, 75, 57)");
                    }

                    if_empty = "true";
                }

            }

            counter++;
            i++;
        });

        if (if_empty == "true") {

            $.alert.open({
                type: 'warning',
                cancel: false,
                content: "Please fill-up required fields!",
                buttons: {
                    OK: 'OK'
                },
            });

        } else {

            $.ajax({
                type: "POST",
                url: "<?php echo site_url('submit_children_info'); ?>",
                data: formData,
                success: function(data) {

                    var response = data.trim();
                    if (response == "success") {

                        $.alert.open({
                            type: 'warning',
                            title: 'Info',
                            icon: 'confirm',
                            cancel: false,
                            content: "Child/Children has been added.",
                            buttons: {
                                OK: 'Yes'
                            },

                            callback: function(button) {
                                if (button == 'OK') {

                                    getdefault('family');
                                }
                            }
                        });
                    } else {

                        console.log(data);
                    }
                }
            });
        }
    }

    function del_child(no) {

        $("tr#tr_" + no).css({
            "background-color": "#d3d6ff"
        });
        $("tr#tr_" + no).fadeOut();
        $("input.deleted_" + no).val("deleted");

        var deleted = "";
        var temp_deleted = "";
        $('input[name="deleted[]"]').each(function() {

            if ($(this).val() == "deleted") {

                $deleted = "true";
            } else {

                temp_deleted = "false";
            }
        });

        if (temp_deleted == "") {

            $("tr.children_info").fadeOut();
            $("button#uac_btn").prop("disabled", true);
        }
    }

    function fname(id) {

        $("input.fname_" + id).removeAttr("style");
    }

    function lname(id) {

        $("input.lname_" + id).removeAttr("style");
    }

    function gender(id) {

        $("select.gender_" + id).removeAttr("style");
    }

    function submit_updated_children() {

        var formData = $("form#data_birthCert_details").serialize();

        var i = 0;
        var if_empty = "";
        var fname = $("input[name = 'fname[]']");
        var lname = $("input[name = 'lname[]']");
        var bday = $("input[name = 'bday[]']");
        var gender = $("select[name = 'gender[]']");

        $('input[name="childId[]"]').each(function() {

            var counter = $(this).val();

            if (fname[i].value == "" || lname[i].value == "" || bday[i].value == "" || gender[i].value == "") {

                if (fname[i].value == "") {

                    $("input.fname_" + counter).css("border-color", "rgb(221, 75, 57)");
                }

                if (lname[i].value == "") {

                    $("input.lname_" + counter).css("border-color", "rgb(221, 75, 57)");
                }

                if (bday[i].value == "") {

                    $("input.bday_" + counter).css("border-color", "rgb(221, 75, 57)");
                }

                if (gender[i].value == "") {

                    $("select.gender_" + counter).css("border-color", "rgb(221, 75, 57)");
                }

                if_empty = "true";
            }

            i++;
        });

        if (if_empty == "true") {

            $.alert.open({
                type: 'warning',
                cancel: false,
                content: "Please fill-up required fields!",
                buttons: {
                    OK: 'OK'
                },
            });

        } else {

            $.ajax({
                type: "POST",
                url: "<?php echo site_url('update_children_info'); ?>",
                data: formData,
                success: function(data) {

                    var response = data.trim();
                    if (response == "success") {

                        $.alert.open({
                            type: 'warning',
                            title: 'Info',
                            icon: 'confirm',
                            cancel: false,
                            content: "Child/Children has been updated.",
                            buttons: {
                                OK: 'Yes'
                            },

                            callback: function(button) {
                                if (button == 'OK') {

                                    getdefault('family');
                                }
                            }
                        });
                    } else {

                        console.log(data);
                    }
                }
            });
        }
    }

    function submit_spouse_children() {

        var formData = $("form#spouse_children_info").serialize();

        var counter = 1;
        var i = 0;
        var if_empty = "";
        var fname = $("input[name = 'fname1[]']");
        var lname = $("input[name = 'lname1[]']");
        var bday = $("input[name = 'bday1[]']");
        var gender = $("select[name = 'gender1[]']");
        var spouse_name = $("input[name = 'spouse_name']").val();

        $('input[name="deleted[]"]').each(function() {

            if ($(this).val() == "") {

                if (fname[i].value == "" || lname[i].value == "" || bday[i].value == "" || gender[i].value == "") {

                    if (fname[i].value == "") {

                        $("input.fname_" + counter).css("border-color", "rgb(221, 75, 57)");
                    }

                    if (lname[i].value == "") {

                        $("input.lname_" + counter).css("border-color", "rgb(221, 75, 57)");
                    }

                    if (bday[i].value == "") {

                        $("input.bday_" + counter).css("border-color", "rgb(221, 75, 57)");
                    }

                    if (gender[i].value == "") {

                        $("select.gender_" + counter).css("border-color", "rgb(221, 75, 57)");
                    }

                    if_empty = "true";
                }

                counter++;
                i++;
            }
        });

        if (if_empty == "true") {

            $.alert.open({
                type: 'warning',
                cancel: false,
                content: "Please fill-up required fields!",
                buttons: {
                    OK: 'OK'
                },
            });

        } else {

            $.ajax({
                type: "POST",
                url: "<?php echo site_url('submit_spouse_children'); ?>",
                data: formData,
                success: function(data) {

                    var response = data.trim();
                    if (response == "success") {

                        $.alert.open({
                            type: 'warning',
                            title: 'Info',
                            icon: 'confirm',
                            cancel: false,
                            content: "Child/Children has been added.",
                            buttons: {
                                OK: 'Yes'
                            },

                            callback: function(button) {
                                if (button == 'OK') {

                                    getdefault('family');
                                }
                            }
                        });
                    } else {

                        console.log(data);
                    }
                }
            });
        }
    }

    function deceased(name) {

        if ($("[name = '" + name + "']").is(':checked')) {
            console.log('checked');
            var family_member = "";
            var family = $("input.family_" + name).val().trim();

            if (family == "") {

                if (name == "mo_work") {
                    family_member = "Mother";
                } else if (name == "fa_work") {
                    family_member = "Father";
                } else {
                    family_member = "Spouse";
                }

                $.alert.open({

                    type: 'warning',
                    content: "Please fill-up name of the " + family_member + " first.",

                    callback: function() {

                        $("[name = '" + name + "']").prop("checked", false);
                        $("input.family_" + name).css("border-color", "rgb(221, 75, 57)");
                    }
                });
            } else {

                $("#text_" + name).addClass("text-red");
            }


        } else {

            $("#text_" + name).removeClass("text-red");
        }
    }

    function deceasedChild(childId) {

        if ($(`#deceasedChild_${childId}`).is(':checked')) {

            $(`.deceasedChild_${childId}`).val('deceased');
        } else {

            $(`.deceasedChild_${childId}`).val('');
        }
    }

    function mother_name(key) {

        var str = key.trim();
        if (str == '') {

            $("div.search-results").hide();
            $("input[name = 'spouse_empId']").val('');
            $("input[name = 'spouse_name']").val('');
        } else {

            $("input[name = 'mother_name']").removeAttr("style");
            $("input[name = 'spouse_empId']").val("");
            $("input[name = 'spouse_name']").val("");
            $.ajax({
                type: "POST",
                url: "<?php echo site_url('find_mothers_name'); ?>",
                data: {
                    str: str
                },
                success: function(data) {

                    var response = data.trim();
                    if (response) {

                        $("div.search-results").show().html(response);
                    } else {

                        $("div.search-results").hide();
                        $("input[name = 'spouse_empId']").val('');
                        $("input[name = 'spouse_name']").val(str);
                    }
                }
            });
        }
    }

    function get_spouseId(mother) {

        var str = mother.split("*");
        var spouse_empId = str[0].trim();
        var mothers_name = str[1].trim();

        $("input[name = 'spouse_empId']").val(spouse_empId);
        $("input[name = 'mother_name']").val(mothers_name);
        $("input[name = 'spouse_name']").val(mothers_name);

        $("div.search-results").hide();
    }

    function edit_children() {

        $(".option1").hide();
        $(".option_viewBC").hide();
        $(".option2").show();
        $(".update_children").prop("disabled", false);
    }

    function cancel_children() {

        $(".option1").show();
        $(".option2").hide();
        $(".option3").hide();
        $(".update_children").prop("disabled", true);

        getdefault('family');
    }

    function view_birthCert(childId) {

        $("div#view_birthCert").modal({
            backdrop: 'static',
            keyboard: false
        });

        $("div#view_birthCert").modal("show");

        $.ajax({
            type: "POST",
            url: "<?php echo site_url('view_birthCert'); ?>",
            data: {
                childId: childId
            },
            success: function(response) {

                $("div.view_birthCert").html('<center><img class="preview_birthCert img-responsive" src="<?php echo 'http://' . $_SERVER['SERVER_ADDR'] . ":" . $_SERVER['SERVER_PORT'] . '/hrms/promo-nesco/'; ?>' + response + '" alt="Birth Cert."></center>');
            }
        });
    }

    function update_birthCert(childId) {

        $("div#update_birthCert").modal({
            backdrop: 'static',
            keyboard: false
        });

        $("div#update_birthCert").modal("show");

        $.ajax({
            type: "POST",
            url: "<?php echo site_url('update_birthCertForm'); ?>",
            data: {
                childId: childId
            },
            success: function(response) {

                $("div.update_birthCert").html(response);
            }
        });
    }

    function get_age(bday, childId) {

        $("input.bday_" + childId).removeAttr("style");

        $.ajax({
            type: "POST",
            url: "<?php echo site_url('get_age'); ?>",
            data: {
                bday: bday
            },
            success: function(response) {

                $("input.updAge_" + childId).val(response);
            }
        });
    }

    function add_children() {

        $(".option1").hide();
        $(".option3").show();
    }

    function isNumberKey(evt) {
        var charCode = (evt.which) ? evt.which : evt.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;
        return true;
    }

    function add_children_info() {

        var no_of_child = $("input[name = 'number_child']").val();
        var counter = $("[name = 'counter']").val();

        if (no_of_child == "") {

            $.alert.open({
                type: 'warning',
                cancel: false,
                content: "Please input number of children first!",
                buttons: {
                    OK: 'Ok'
                },

                callback: function(button) {
                    if (button == 'OK') {

                        $("input[name = 'number_child']").focus();
                    }
                }
            });

        } else {

            if (no_of_child > 10) {

                $.alert.open({
                    type: 'warning',
                    cancel: false,
                    content: "Maximum number to be inputed is 10!",
                    buttons: {
                        OK: 'Ok'
                    },

                    callback: function(button) {
                        if (button == 'OK') {

                            $("input[name = 'number_child']").val("").focus();
                        }
                    }
                });

            } else {

                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('add_children_info'); ?>",
                    data: {
                        no_of_child: no_of_child,
                        counter: counter
                    },
                    success: function(data) {

                        data = data.split("|");

                        $("input[name = 'number_child']").val("");
                        $("button#uac_btn").prop("disabled", false);
                        $("tr.children_info").show();

                        $('tbody#my_table2').append(data[0]);
                        $("input[name = 'counter']").val(data[1]);
                    }
                });
            }
        }
    }

    function inputField(name) {

        $("[name = '" + name + "']").css("border-color", "#d2d6de");
    }

    function add_seminar(no) {

        $("#seminar_form").modal({
            backdrop: 'static',
            keyboard: false
        });

        $("#seminar_form").modal("show");
        var empId = $("[name = 'empId']").val();
        $("button#submit_seminar_btn").prop("disabled", false);

        $.ajax({
            type: "POST",
            url: "<?php echo site_url('seminar_form'); ?>",
            data: {
                empId: empId,
                no: no
            },
            success: function(data) {

                $(".seminar_form").html(data);
            }
        });
    }

    function viewSeminarCert(no) {

        $("#viewSeminar").modal({
            backdrop: 'static',
            keyboard: false
        });

        $("#viewSeminar").modal("show");

        $.ajax({
            type: "POST",
            url: "<?php echo site_url('seminarCertificate'); ?>",
            data: {
                no: no
            },
            success: function(data) {

                data = data.trim();
                $(".viewSeminar").html('<center><img class="img-responsive" src="<?php echo 'http://' . $_SERVER['SERVER_ADDR'] . ":" . $_SERVER['SERVER_PORT'] . '/hrms/promo-nesco/'; ?>' + data + '" alt="Photo"></center>');
            }
        });
    }

    function add_charref(no) {

        $("#addCharRef").modal({
            backdrop: 'static',
            keyboard: false
        });

        $("#addCharRef").modal("show");
        var empId = $("[name = 'empId']").val();
        $("button#submitCharRef").prop("disabled", false);

        $.ajax({
            type: "POST",
            url: "<?php echo site_url('character_ref_form'); ?>",
            data: {
                empId: empId,
                no: no
            },
            success: function(data) {

                $(".addCharRef").html(data);
            }
        });
    }

    function submit_character_ref() {

        var no = $("input[name = 'no']").val();
        var empId = $("input[name = 'appId']").val();
        var charName = $("input[name = 'charName']").val();
        var charCompanyLocation = $("input[name = 'charCompanyLocation']").val();
        var charPosition = $("input[name = 'charPosition']").val();
        var charContact = $("input[name = 'charContact']").val();

        if (charName == "" || charCompanyLocation == "" || charPosition == "") {

            $.alert.open({
                type: 'warning',
                cancel: false,
                content: "Please Fill-up Required Fields!",
                buttons: {
                    OK: 'Ok'
                },

                callback: function(button) {
                    if (button == 'OK') {

                        if (charName == "") {

                            $("[name = 'charName']").css("border-color", "border-color", "rgb(221, 75, 57)");
                        }

                        if (charCompanyLocation == "") {

                            $("[name = 'charCompanyLocation']").css("border-color", "border-color", "rgb(221, 75, 57)");
                        }

                        if (charPosition == "") {

                            $("[name = 'charPosition']").css("border-color", "border-color", "rgb(221, 75, 57)");
                        }
                    }
                }
            });

        } else {

            $("#submitCharRef").prop("disabled", true);

            $.ajax({
                type: "POST",
                url: "<?php echo site_url('submit_character_ref'); ?>",
                data: {
                    empId: empId,
                    no: no,
                    charName: charName,
                    charCompanyLocation: charCompanyLocation,
                    charPosition: charPosition,
                    charContact: charContact
                },
                success: function(data) {

                    response = data.split("||");
                    if (response[0].trim() == "success") {

                        $.alert.open({
                            type: 'warning',
                            title: 'Info',
                            icon: 'confirm',
                            cancel: false,
                            content: "Character Reference Details Successfully " + response[1].trim(),
                            buttons: {
                                OK: 'Yes'
                            },

                            callback: function(button) {
                                if (button == 'OK') {

                                    $("#addCharRef").modal("hide");
                                    getdefault('charref');
                                }

                            }
                        });
                    } else {

                        alert(response);
                    }
                }
            });
        }
    }

    function view_appraisal_details(detailsId) {

        $("#appraisal_form").modal({
            backdrop: 'static',
            keyboard: false
        });

        $("#appraisal_form").modal("show");

        $.ajax({
            type: "POST",
            url: "<?php echo site_url('appraisal_details'); ?>",
            data: {
                detailsId: detailsId
            },
            success: function(data) {

                $(".appraisal_form").html(data);
            }
        });
    }

    function viewExam(examVal) {

        $("#examDetails").modal({
            backdrop: 'static',
            keyboard: false
        });

        $("#examDetails").modal("show");

        $.ajax({
            type: "POST",
            url: "<?php echo site_url('examDetails'); ?>",
            data: {
                examVal: examVal
            },
            success: function(data) {

                $(".examDetails").html(data);
            }
        });
    }

    function viewAppDetails(empId) {

        $("#appHistDetails").modal({
            backdrop: 'static',
            keyboard: false
        });

        $("#appHistDetails").modal("show");

        $.ajax({
            type: "POST",
            url: "<?php echo site_url('appHistDetails'); ?>",
            data: {
                empId: empId
            },
            success: function(data) {

                $(".appHistDetails").html(data);
            }
        });
    }

    function viewInterview(empId) {

        $("#interviewDetails").modal({
            backdrop: 'static',
            keyboard: false
        });

        $("#interviewDetails").modal("show");

        $.ajax({
            type: "POST",
            url: "<?php echo site_url('interviewDetails'); ?>",
            data: {
                empId: empId
            },
            success: function(data) {

                $(".interviewDetails").html(data);
            }
        });
    }

    function add_contract() {

        $("#addContractDetails").modal({
            backdrop: 'static',
            keyboard: false
        });

        $("#addContractDetails").modal("show");
        var empId = $("[name = 'empId']").val();

        $.ajax({
            type: "POST",
            url: "<?php echo site_url('addContract'); ?>",
            data: {
                empId: empId
            },
            success: function(data) {

                $(".addContractDetails").html(data);
            }
        });
    }

    function viewDetails(contract, recordNo, empId) {

        $("#contractDetails").modal({
            backdrop: 'static',
            keyboard: false
        });

        $("#contractDetails").modal("show");
        $("#loading-gif").show();

        $.ajax({
            type: "POST",
            url: "<?php echo site_url('contractDetails'); ?>",
            data: {
                contract: contract,
                recordNo: recordNo,
                empId: empId
            },
            success: function(data) {

                $("#loading-gif").hide();
                $(".contractDetails").html(data);
            }
        });
    }

    function viewPromoDetails(contract, recordNo, empId) {

        $("#contractDetails").modal({
            backdrop: 'static',
            keyboard: false
        });

        $("#contractDetails").modal("show");
        $("#loading-gif").show();

        $.ajax({
            type: "POST",
            url: "<?php echo site_url('promoContractDetails'); ?>",
            data: {
                contract: contract,
                recordNo: recordNo,
                empId: empId
            },
            success: function(data) {

                $("#loading-gif").hide();
                $(".contractDetails").html(data);
            }
        });
    }

    function previewAppraisalDetails(detailsId) {

        $("#appraisal_form").modal({
            backdrop: 'static',
            keyboard: false
        });

        $("#appraisal_form").modal("show");

        $.ajax({
            type: "POST",
            url: "<?php echo site_url('appraisal_details'); ?>",
            data: {
                detailsId: detailsId
            },
            success: function(data) {

                $(".appraisal_form").html(data);
            }
        });
    }

    function viewFile(file, table, field, empId, recordNo) {

        $("#viewFile").modal({
            backdrop: 'static',
            keyboard: false
        });

        $("#viewFile").modal("show");
        $(".viewFile").html("");

        $.ajax({
            type: "POST",
            url: "<?php echo site_url(); ?>" + file,
            data: {
                table: table,
                field: field,
                empId: empId,
                recordNo: recordNo
            },
            success: function(data) {

                data = data.trim();
                $(".viewFile").html('<center><img class="img-responsive" src="<?php echo 'http://' . $_SERVER['SERVER_ADDR'] . ":" . $_SERVER['SERVER_PORT'] . '/hrms/promo-nesco/'; ?>' + data + '" alt="Photo"></center>');
            }
        });
    }

    function updateDetails(contract, recordNo, empId) {

        $("#updateContractDetails").modal({
            backdrop: 'static',
            keyboard: false
        });

        $("#updateContractDetails").modal("show");

        $.ajax({
            type: "POST",
            url: "<?php echo site_url('editContractDetails'); ?>",
            data: {
                contract: contract,
                recordNo: recordNo,
                empId: empId
            },
            success: function(data) {

                $(".updateContractDetails").html(data);
            }
        });
    }

    function updatePromoDetails(contract, recordNo, empId) {

        $("#updatePromoContractDetails").modal({
            backdrop: 'static',
            keyboard: false
        });

        $("#updatePromoContractDetails").modal("show");

        $.ajax({
            type: "POST",
            url: "<?php echo site_url('editPromoContractDetails'); ?>",
            data: {
                contract: contract,
                recordNo: recordNo,
                empId: empId
            },
            success: function(data) {

                $(".updatePromoContractDetails").html(data);
            }
        });
    }

    function getBusinessUnit(id) {

        $.ajax({
            type: "POST",
            url: "<?php echo site_url('locate_business_unit'); ?>",
            data: {
                id: id
            },
            success: function(response) {

                $("select[name = 'businessUnit']").html(response);
                $("select[name = 'department']").html('<option value=""> Select Department </option>');
                $("select[name = 'section']").html('<option value=""> Select Section </option>');
                $("select[name = 'subSection']").html('<option value=""> Select Sub-Section </option>');
                $("select[name = 'unit']").html('<option value=""> Select Unit </option>');
            }
        });
    }

    function getDepartment(id) {

        $.ajax({
            type: "POST",
            url: "<?php echo site_url('locate_department'); ?>",
            data: {
                id: id
            },
            success: function(response) {

                $("select[name = 'department']").html(response);
                $("select[name = 'section']").html('<option value=""> Select Section </option>');
                $("select[name = 'subSection']").html('<option value=""> Select Sub-Section </option>');
                $("select[name = 'unit']").html('<option value=""> Select Unit </option>');
            }
        });
    }

    function getSection(id) {

        $.ajax({
            type: "POST",
            url: "<?php echo site_url('locate_section'); ?>",
            data: {
                id: id
            },
            success: function(response) {

                $("select[name = 'section']").html(response);
                $("select[name = 'subSection']").html('<option value=""> Select Sub-Section </option>');
                $("select[name = 'unit']").html('<option value=""> Select Unit </option>');
            }
        });
    }

    function getSubSection(id) {

        $.ajax({
            type: "POST",
            url: "<?php echo site_url('locate_sub_section'); ?>",
            data: {
                id: id
            },
            success: function(response) {

                $("select[name = 'subSection']").html(response);
                $("select[name = 'unit']").html('<option value=""> Select Unit </option>');
            }
        });
    }

    function getUnit(id) {

        $.ajax({
            type: "POST",
            url: "<?php echo site_url('locate_unit'); ?>",
            data: {
                id: id
            },
            success: function(response) {

                $("select[name = 'unit']").html(response);
            }
        });
    }

    function position_level(position) {

        if (position != '') {

            $("[name = 'position']").css("border-color", "#d2d6de");
            $.ajax({
                type: "GET",
                url: "<?php echo site_url('position_level'); ?>",
                data: {
                    position
                },
                success: function(response) {
                    $("input.posLevel").val(response);
                }
            });

        } else {

            $("[name = 'position']").css("border-color", "rgb(221, 75, 57)");
        }
    }

    function updateContractDetails() {

        var contract = $("input[name = 'contract']").val();
        var empId = $("input[name = 'empId']").val();
        var recordNo = $("input[name = 'recordNo']").val();

        var company = $("select[name = 'company']").val();
        var businessUnit = $("select[name = 'businessUnit']").val();
        var department = $("select[name = 'department']").val();
        var section = $("select[name = 'section']").val();
        var subSection = $("select[name = 'subSection']").val();
        var unit = $("select[name = 'unit']").val();

        var startdate = $("input[name = 'startdate']").val();
        var eocdate = $("input[name = 'eocdate']").val();
        var position = $("select[name = 'position']").val();
        var empType = $("select[name = 'empType']").val();
        var current_status = $("select[name = 'current_status']").val();
        var posLevel = $("input[name = 'posLevel']").val();

        var lodging = $("select[name = 'lodging']").val();
        var posDesc = $("textarea[name = 'posDesc']").val();
        var remarks = $("textarea[name = 'remarks']").val();

        if (company == "" || businessUnit == "" || startdate == "" || (contract != "current" && eocdate == "") || position == "" || empType == "" || current_status == "") {

            $.alert.open({
                type: 'warning',
                cancel: false,
                content: "Please Fill-up Required Fields!",
                buttons: {
                    OK: 'Ok'
                },

                callback: function(button) {
                    if (button == 'OK') {

                        if (company == "") {

                            $("[name = 'company']").css("border-color", "rgb(221, 75, 57)");
                        }

                        if (businessUnit == "") {

                            $("[name = 'businessUnit']").css("border-color", "rgb(221, 75, 57)");
                        }

                        /*if (department == "") {

                            $("[name = 'department']").css("border-color","rgb(221, 75, 57)");
                        }*/

                        if (startdate == "") {

                            $("[name = 'startdate']").css("border-color", "rgb(221, 75, 57)");
                        }

                        if (contract != "current" && eocdate == "") {

                            $("[name = 'eocdate']").css("border-color", "rgb(221, 75, 57)");
                        }

                        if (position == "") {

                            $("[name = 'position']").css("border-color", "rgb(221, 75, 57)");
                        }

                        if (empType == "") {

                            $("[name = 'empType']").css("border-color", "rgb(221, 75, 57)");
                        }

                        if (current_status == "") {

                            $("[name = 'current_status']").css("border-color", "rgb(221, 75, 57)");
                        }
                    }
                }
            });
        } else {

            $.ajax({
                type: "POST",
                url: "<?php echo site_url('updateContractDetails'); ?>",
                data: {
                    contract: contract,
                    empId: empId,
                    recordNo: recordNo,
                    company: company,
                    businessUnit: businessUnit,
                    department: department,
                    section: section,
                    subSection: subSection,
                    unit: unit,
                    startdate: startdate,
                    eocdate: eocdate,
                    position: position,
                    empType: empType,
                    current_status: current_status,
                    posLevel: posLevel,
                    lodging: lodging,
                    posDesc: posDesc,
                    remarks: remarks
                },
                success: function(data) {

                    data = data.trim();
                    if (data == "success") {

                        $.alert.open({
                            type: 'warning',
                            title: 'Info',
                            icon: 'confirm',
                            cancel: false,
                            content: "Contract History Successfully Updated!",
                            buttons: {
                                OK: 'Yes'
                            },

                            callback: function(button) {
                                if (button == 'OK') {

                                    $("#updateContractDetails").modal("hide");
                                    getdefault('employment');
                                }

                            }
                        });
                    } else {

                        alert(data);
                    }
                }
            });
        }
    }
</script>