<script>
    $(document).ready(function() {

        let page = $("input[name = 'page']").val();
        if (page == 'clearance-processing') {
            clearanceProcess('secure_clearance');
        }

        $("button.clearance-action").click(function() {

            $("button.clearance-action").removeClass('active');
            $(this).addClass('active');

            clearanceProcess(this.id);
        });

        let employee = $("input[name = 'employee']").val();
        if (employee) {
            $(".rt-form").prop('disabled', false);
        }

        $("input[name = 'employee']").keyup(function() {

            let str = $(this).val().trim();
            $(".search-results").hide();

            if (str == '') {

                $(".search-results").hide();
            } else {

                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('find_all_promo'); ?>",
                    data: {
                        str
                    },
                    success: function(data) {

                        if (data.trim() != "No Result Found") {

                            $(".search-results").show().html(data);
                        } else {

                            $(".search-results").hide();
                        }
                    }
                });
            }

        });

        let dt_resignation_list = $("table#dt-resignation-list").DataTable({
            "processing": true,
            "serverSide": true,
            "stateSave": true,
            "order": [],

            "ajax": {
                url: "<?= site_url('resignation_list') ?>", // json datasource
                type: "post", // method  , by default get
                error: function() { // error handling
                    $(".employee-grid-error").html("");
                    $("table#dt-resignation-list").append('<tbody><tr><th colspan="6">No data found in the server</th></tr></tbody>');
                    $("#dt-resignation-list_processing").css("display", "none");

                }
            }
        });

        $('table#dt-resignation-list').on('click', 'button.action', function() {

            let [action, emp_id, termination_no] = this.id.split("_");

            if (!$(this).parents('tr').hasClass('selected')) {
                dt_resignation_list.$('tr.selected').removeClass('selected');
                $(this).parents('tr').addClass('selected');
            }

            if (action == 'upload') {

                $("div#upload-resignation-letter").modal({
                    backdrop: 'static',
                    keyboard: false,
                    show: true
                });

                $("div.upload-resignation-letter").html("");
                $.ajax({
                    type: "POST",
                    url: "<?= site_url('upload_resignation_letter') ?>",
                    data: {
                        emp_id,
                        termination_no
                    },
                    success: function(data) {

                        $("div.upload-resignation-letter").html(data);
                    }
                });

            } else {

                $("div#view-resignation-letter").modal({
                    backdrop: 'static',
                    keyboard: false,
                    show: true
                });

                $("div.view-resignation-letter").html("");
                $.ajax({
                    type: "GET",
                    url: "<?= site_url('show_resignation_letter') ?>",
                    data: {
                        termination_no
                    },
                    success: function(data) {

                        let response = JSON.parse(data);
                        if (response.status == 'success') {

                            $("div.view-resignation-letter").html("<center><img class='img-responsive' src='" + response.image + "' alt='Photo'></center>");
                        } else {
                            console.log(data);
                        }
                    }
                });
            }
        });

        $("form#upload-resignation-letter").submit(function(e) {

            e.preventDefault();
            let formData = new FormData(this);
            let resignation = $("input[name = 'resignation']").val();
            if (resignation == "") {

                $.alert.open({
                    type: 'warning',
                    cancel: false,
                    content: "Please Fill-up Required Fields!",
                    buttons: {
                        OK: 'Ok'
                    },

                    callback: function(button) {
                        if (button == 'OK') {

                            if (resignation == "") {
                                $("input[name = 'resignation']").css("border-color", "#dd4b39");
                            }
                        }
                    }
                });

            } else {

                $.ajax({
                    url: "<?= site_url('submit_resignation_letter') ?>",
                    type: 'POST',
                    data: formData,
                    success: function(data) {

                        let response = JSON.parse(data);
                        if (response.status == "success") {

                            $.alert.open({
                                type: 'warning',
                                title: 'Info',
                                icon: 'confirm',
                                cancel: false,
                                content: "Resignation Letter Successfully Uploaded!",
                                buttons: {
                                    OK: 'Yes'
                                },
                                callback: function(button) {
                                    if (button == 'OK') {

                                        $("div#upload-resignation-letter").modal("hide");
                                        location.reload();
                                    }

                                }
                            });
                        } else {

                            console.log(data);
                        }
                    },
                    async: false,
                    cache: false,
                    contentType: false,
                    processData: false

                });
            }
        });

        $("select[name='rt_status']").change(function() {

            let rt_status = $(this).val();
            if (rt_status) {

                $(this).css("border-color", "#d2d6de");
                let [emp_id, name] = $("input[name='employee']").val().split("*");

                $.ajax({
                    type: "POST",
                    url: "<?= site_url('check_rt_status') ?>",
                    data: {
                        emp_id: emp_id.trim(),
                        rt_status: rt_status
                    },
                    success: function(data, status) {
                        if (status == 'success') {

                            $(".uploadResignation").html(data);
                        }
                    }
                });
            } else {

                $("div.uploadResignation").html('');
            }
        });

        $("form#data-rt").submit(function(e) {

            e.preventDefault();
            let formData = new FormData(this);

            let [emp_id, name] = $("input[name = 'employee']").val().split("*");
            let remarks = $("textarea[name = 'remarks']").val();
            let dateEffective = $("input[name = 'dateEffective']").val();
            let status = $("select[name = 'rt_status']").val();
            if (status) {

                let resignation = '';
                if (status == 'Resigned') {
                    resignation = $("input[name ='resignation']").val();
                }

                let checker = true;
                $("input.clearances").each(function() {

                    if (!$(this).val()) {
                        checker = false;
                    }
                });

                if ((status == 'Resigned' && resignation) && checker) {

                    $.ajax({
                        url: "<?= site_url('store_rt') ?>",
                        type: 'POST',
                        data: formData,
                        success: function(data) {

                            let response = JSON.parse(data);
                            if (response.status == "success") {

                                $.alert.open({
                                    type: 'warning',
                                    title: 'Info',
                                    icon: 'confirm',
                                    cancel: false,
                                    content: "Resignation/Termination Successfully Saved",
                                    buttons: {
                                        OK: 'Yes'
                                    },

                                    callback: function(button) {
                                        if (button == 'OK') {

                                            // location.reload();
                                            window.location.href = response.url;
                                        }
                                    }
                                });
                            } else {

                                console.log(data);
                            }
                        },
                        async: false,
                        cache: false,
                        contentType: false,
                        processData: false
                    });
                } else {

                    $.alert.open({
                        type: 'warning',
                        cancel: false,
                        content: "Please Fill-up Required Fields!",
                        buttons: {
                            OK: 'Ok'
                        },

                        callback: function(button) {
                            if (button == 'OK') {

                                if (checker === false) {

                                    $("input.clearances").each(function() {

                                        $(this).css("border-color", "#dd4b39");
                                    });
                                }

                                if (status == "Resigned" && resignation == '') {

                                    $("input[name = 'resignation']").css("border-color", "#dd4b39");
                                }
                            }
                        }
                    });
                }

            } else {

                if (emp_id.trim() == "" || remarks == "" || status == "" || dateEffective == "") {

                    $.alert.open({
                        type: 'warning',
                        cancel: false,
                        content: "Please Fill-up Required Fields!",
                        buttons: {
                            OK: 'Ok'
                        },

                        callback: function(button) {
                            if (button == 'OK') {

                                if (emp_id.trim() == "") {

                                    $("input[name = 'employee']").css("border-color", "#dd4b39");
                                }

                                if (remarks == "") {

                                    $("input[name = 'dateEffective']").css("border-color", "#dd4b39");
                                }

                                if (remarks == "") {

                                    $("textarea[name = 'remarks']").css("border-color", "#dd4b39");
                                }

                                $("select[name = 'rt_status']").css("border-color", "#dd4b39");
                            }

                        }
                    });
                }
            }
        });

        $("input[name = 'supervisor']").keyup(function(e) {

            let str = $(this).val().trim();
            $(".search-results").hide();

            if (str == '') {

                $(".search-results").hide();
            } else {

                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('find_active_supervisor'); ?>",
                    data: {
                        str
                    },
                    success: function(data) {

                        if (data.trim() != "No Result Found") {

                            $(".search-results").show().html(data);
                        } else {

                            $(".search-results").hide();
                        }
                    }
                });
            }
        });
    });

    function getEmpId(supervisor) {

        let [emp_id, name] = supervisor.split('*');

        $("input[name='supervisor']").val(supervisor);
        $("input[name = 'rater']").val(emp_id.trim());
        $(".search-results").hide();

        list_of_subordinates(emp_id.trim());
    }

    function list_of_subordinates(emp_id) {

        $("div.subordinates").html('');
        $("div#loading-gif").show();
        $.ajax({
            type: "GET",
            url: "<?= site_url('resignation/list_of_subordinates') ?>",
            data: {
                emp_id
            },
            success: function(data) {

                $("div#loading-gif").hide();
                $("div.subordinates").html(data);
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

    function inputField(name) {

        $("[name = '" + name + "']").css("border-color", "#d2d6de");
    }

    function empDetails(empId) {

        $("input[name='employee']").val(empId);
        $("div.search-results").hide();
        $("input[name = 'employee']").css("border-color", "#ccc");

        disabledFields();
        let [emp_id, name] = empId.trim().split("*");
        $.ajax({
            url: "<?= site_url('show_employee') ?>/" + emp_id.trim(),
            success: function(data) {

                let response = JSON.parse(data);
                if (response.status == "Active" || response.status == "End of Contract") {

                    $.alert.open({
                        type: 'warning',
                        title: 'Info',
                        icon: 'confirm',
                        cancel: false,
                        content: "Employee status is " + response.status,
                        buttons: {
                            OK: 'Yes'
                        },

                        callback: function(button) {
                            if (button == 'OK') {

                                $(".rt-form").prop('disabled', false);
                            }
                        }
                    });
                } else {

                    errDup("Employee status is " + response.status);
                }
            }
        });
    }

    function disabledFields() {
        $("input[name = 'dateEffective']").val('');
        $("textarea[name = 'remarks']").val('');
        $("select[name = 'rt_status']").val('');

        $("input[name = 'dateEffective']").prop('disabled', true);
        $("textarea[name = 'remarks']").prop('disabled', true);
        $("select[name = 'rt_status']").prop('disabled', true);

        $("div.uploadResignation").html('');
    }

    function clearanceProcess(process) {

        $("div.clearance-body").html('');
        $("div#loading-gif").show();
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('clearance_process'); ?>",
            data: {
                process
            },
            success: function(data) {

                $("div#loading-gif").hide();
                $("div.clearance-body").html(data);
            }
        });
    }

    function nameSearch(str) {

        str = str.trim();
        $(".search-results").hide();

        if (str == '') {

            $(".search-results").hide();
        } else {

            let process = $("input[name = 'process']").val();

            $.ajax({
                type: "POST",
                url: "<?php echo site_url('find_promo_for_clearance'); ?>",
                data: {
                    str,
                    process
                },
                success: function(data) {

                    if (data.trim() != "No Result Found") {

                        $("div.promo-details").html('');
                        $("div.clearance-form").hide();
                        $(".search-results").show().html(data);
                    } else {

                        $(".search-results").hide();
                    }
                }
            });
        }
    }

    function promoClearance(employee) {

        let [emp_id, name, promo_type] = employee.trim().split("*");
        $("input[name='employee']").val(`${emp_id} * ${name}`);
        $("div.search-results").hide();
        $("input[name = 'employee']").css("border-color", "#ccc");

        let process = $("input[name = 'process']").val();
        $("div.clearance-form").show();

        if (process == 'secure-clearance') {

            if (promo_type == 'ROVING') {
                $("select[name = 'reason']").append('<option value="Remove-BU">REMOVE BUSINESS UNIT</option>');
            }

            $.ajax({
                type: "POST",
                url: "<?php echo site_url('check_secure_clearance'); ?>",
                data: {
                    emp_id: emp_id.trim(),
                    promo_type: promo_type
                },
                success: function(data) {

                    let response = JSON.parse(data);
                    $("select[name = 'reason']").val(response.reason);
                },
                async: false
            });
        }

        $.ajax({
            type: "POST",
            url: "<?php echo site_url('promo_details_clearance'); ?>",
            data: {
                emp_id: emp_id.trim(),
                process: process
            },
            success: function(data) {

                $("div.promo-details").html(data);
            }
        });
    }

    function req_reprint_clearance() {

        let scpr_id = $("input[name = 'scpr_id']").val();
        let store = $("select[name = 'store']").val();
        let reason = $("textarea[name = 'reprint_reason']").val();

        if (scpr_id && reason) {

            $.ajax({
                type: "POST",
                url: "<?php echo site_url('record_reprint_clearance'); ?>",
                data: {
                    scpr_id,
                    reason
                },
                success: function(data) {

                    let response = JSON.parse(data);
                    if (response.status == 'success') {
                        $.alert.open({
                            type: 'warning',
                            title: 'Info',
                            icon: 'confirm',
                            cancel: false,
                            content: "You can now view the Clearance",
                            buttons: {
                                OK: 'OK'
                            },

                            callback: function(button) {
                                if (button == 'OK') {

                                    $("button#req-reprint").prop('disabled', true);
                                    $("button#view-clearance").prop('disabled', false);
                                }
                            }
                        });
                    } else {
                        console.log(data);
                    }
                }
            });
        } else {

            $.alert.open({
                type: 'warning',
                cancel: false,
                content: "Please Fill-up Required Fields!",
                buttons: {
                    OK: 'Ok'
                },

                callback: function(button) {
                    if (button == 'OK') {

                        if (store == "") {
                            $("select[name = 'store']").css("border-color", "#dd4b39");
                        }

                        if (reason == "") {
                            $("textarea[name = 'reprint_reason']").css("border-color", "#dd4b39");
                        }
                    }
                }
            });
        }
    }

    function print_clearance(reason, emp_id, scdetails_id, base_url) {

        if (reason == "Deceased") {
            $.alert.open({
                type: 'warning',
                title: 'Info',
                icon: 'confirm',
                cancel: false,
                content: "Generating Clearance...",
                buttons: {
                    OK: 'OK'
                },

                callback: function(button) {
                    if (button == 'OK') {

                        clearanceProcess('reprint_clearance');
                        window.open(base_url + '/hrms/report/deceased_clearance.php?empid=' + emp_id, 'new');
                    }
                }
            });
        } else {
            $.alert.open({
                type: 'warning',
                title: 'Info',
                icon: 'confirm',
                cancel: false,
                content: "Generating Clearance...",
                buttons: {
                    OK: 'OK'
                },

                callback: function(button) {
                    if (button == 'OK') {

                        clearanceProcess('reprint_clearance');
                        window.open(base_url + '/hrms/report/promo_clearance.php?empid=' + emp_id + "&scprdetailsid=" + scdetails_id, 'new');
                    }
                }
            });
        }
    }

    function browseEpas() {

        let store = $("select[name = 'store']").val();
        if (!store) {
            $("select[name = 'store']").focus();
            return;
        }
        let [emp_id, name] = $("input[name = 'employee']").val().split("*");;

        $.ajax({
            type: "POST",
            url: "<?= site_url('browse_epas'); ?>",
            data: {
                emp_id: emp_id.trim(),
                store: store
            },
            success: function(data) {

                let response = JSON.parse(data);
                if (response.secure == 'no') {

                    if (response.reason == 'Seasonal') {

                        $.alert.open({
                            type: 'warning',
                            cancel: false,
                            content: 'No EPAS for seasonal with 15days below contract',
                            buttons: {
                                OK: 'OK',
                            },
                            callback: function(button) {
                                if (button == 'OK') {

                                    $("div.show-epas").hide();
                                    $(".disabled-form").prop('disabled', false);
                                    $("input.input-form").val('');
                                }
                            }
                        });


                    } else {

                        $.alert.open({
                            type: 'warning',
                            cancel: false,
                            content: response.reason,
                            buttons: {
                                OK: 'OK',
                            },
                            callback: function(button) {
                                if (button == 'OK') {

                                    $("div.show-epas").hide();
                                    $(".disabled-form").prop('disabled', true);
                                    $("input.input-form").val('');
                                }
                            }
                        });
                    }
                } else {

                    $("div.show-epas").show();
                    $(".disabled-form").prop('disabled', false);
                    $("input.input-form").prop('readonly', true);
                    $("input.input-form").prop('required', true);
                    $("input[name = 'epas']").val(response.epas);
                    $("input[name = 'status']").val(response.status);
                }
            }
        });
    }

    function getRL(reason) {

        if (reason) {
            $.ajax({
                type: "POST",
                url: "<?php echo site_url('get_rb_form'); ?>",
                data: {
                    reason
                },
                success: function(data) {

                    $("div.reason-based").html(data);
                }
            });
        }
    }
</script>