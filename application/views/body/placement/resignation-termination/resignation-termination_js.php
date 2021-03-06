<script>
    $(document).ready(function() {

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
</script>