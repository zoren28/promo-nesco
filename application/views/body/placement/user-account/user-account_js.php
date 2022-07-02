<script>
    $(document).ready(function() {

        $("input[name = 'employee']").keyup(function() {

            let key = $(this).val();
            var str = key.trim();
            if (str == '') {

                $(".search-results").hide();
            } else {

                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('find_active_promo'); ?>",
                    data: {
                        str: str,
                        promo_type: 'all'
                    },
                    success: function(data) {

                        $(".search-results").show().html(data);
                    }
                });
            }
        });

        $("input[name = 'hr']").keyup(function() {

            let key = $(this).val();
            var str = key.trim();
            if (str == '') {

                $(".search-results").hide();
            } else {

                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('find_active_hr_staff'); ?>",
                    data: {
                        str
                    },
                    success: function(data) {

                        $(".search-results").show().html(data);
                    }
                });
            }
        });

        $("form#add-user-account").submit(function(e) {

            e.preventDefault();
            let formData = $(this).serialize();
            let emp_id = $("input[name = 'emp_id']").val();
            let username = $("input[name = 'username']").val();
            let password = $("input[name = 'password']").val();

            if (emp_id == "" || username == "" || password == "") {

                $.alert.open({
                    type: 'warning',
                    cancel: false,
                    content: "Please Fill-up Required Fields!",
                    buttons: {
                        OK: 'Ok'
                    },

                    callback: function(button) {
                        if (button == 'OK') {

                            if (emp_id == "") {

                                $("input[name = 'employee']").css("border-color", "#dd4b39");
                            }

                            if (username == "") {

                                $("input[name = 'username']").css("border-color", "#dd4b39");
                            }

                            if (password == "") {

                                $("input[name = 'password']").css("border-color", "#dd4b39");
                            }
                        }

                    }
                });
            } else {

                $.ajax({
                    type: "POST",
                    url: "<?= site_url('create_user_account') ?>",
                    data: formData,
                    success: function(data) {

                        let response = JSON.parse(data);
                        if (response.status == "success") {

                            $.alert.open({
                                type: 'warning',
                                title: 'Info',
                                icon: 'confirm',
                                cancel: false,
                                content: "Promo Account Successfully Saved",
                                buttons: {
                                    OK: 'Yes'
                                },

                                callback: function(button) {
                                    if (button == 'OK') {

                                        location.reload();
                                    }

                                }
                            });

                        } else if (response.status == "exist") {

                            errDup("Username Already Exist");
                        } else if (response.status == "exist account") {

                            errDup("Employee had already an account!");
                        } else {

                            console.log(data);
                        }
                    }
                });
            }
        });

        $("form#add-hr-account").submit(function(e) {

            e.preventDefault();
            let formData = $(this).serialize();
            let [emp_id, name] = $("input[name = 'hr']").val().split('*');
            let usertype = $("select[name = 'usertype']").val();

            if (emp_id.trim() == '' || usertype == '') {

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

                                $("input[name = 'hr']").css("border-color", "#dd4b39");
                            }

                            if (usertype == "") {

                                $("select[name = 'usertype']").css("border-color", "#dd4b39");
                            }
                        }

                    }
                });

            } else {

                $.ajax({
                    type: "POST",
                    url: "<?= site_url('create_hr_account') ?>",
                    data: formData,
                    success: function(data) {

                        response = JSON.parse(data);
                        if (response.status == "success") {

                            $.alert.open({
                                type: 'warning',
                                title: 'Info',
                                icon: 'confirm',
                                cancel: false,
                                content: "Promo Incharge Successfully Saved",
                                buttons: {
                                    OK: 'Yes'
                                },

                                callback: function(button) {
                                    if (button == 'OK') {

                                        location.reload();
                                    }

                                }
                            });

                        } else if (response.status == "exist") {

                            errDup("Promo Incharge Already Exist");
                        } else {

                            console.log(data);
                        }
                    }
                });
            }
        });

        let managePromo = $('table#manage-promo').DataTable({
            destroy: true,
            processing: true,
            serverSide: true,
            order: [],
            ajax: {
                url: "<?= site_url('promo_account_list') ?>", // json datasource
                type: "POST", // method  ,by default get
                error: function() { // error handling
                    $("table#manage-promo").append('<tbody><tr><th colspan="6">No data found in the server</th></tr></tbody>');
                    $("div#manage-promo_processing").css("display", "none");
                }
            }
        });

        let manageHr = $('table#manage-promo-incharge').DataTable({
            destroy: true,
            stateSave: true,
            processing: true,
            serverSide: true,
            order: [],
            ajax: {
                url: "<?= site_url('hr_account_list') ?>", // json datasource
                type: "POST", // method  ,by default get
                error: function() { // error handling
                    $("table#manage-promo-incharge").append('<tbody><tr><th colspan="5">No data found in the server</th></tr></tbody>');
                    $("div#manage-promo-incharge_processing").css("display", "none");
                }
            }
        });
    });

    function getEmpId(response) {

        let [emp_id, name] = response.split('*');
        $("input[name = 'emp_id']").val(emp_id.trim());
        $("input[name = 'username']").val(emp_id.trim());

        $("input[name='employee']").val(response);
        $("input[name = 'employee']").css("border-color", "#ccc");
        $("input[name = 'username']").css("border-color", "#ccc");
        $(".search-results").hide();
    }

    function getHR(response) {

        let [emp_id, name] = response.split('*');
        $("input[name = 'emp_id']").val(emp_id.trim());

        $("input[name='hr']").val(response);
        $("input[name = 'hr']").css("border-color", "#ccc");
        $("div.search-results").hide();
    }

    function inputField(name) {

        $("[name = '" + name + "']").css("border-color", "#ccc");
    }

    function defaultPassword() {

        let password = "Hrms2014";
        $("[name= 'password']").val(password);
        $("[name= 'password']").prop("readonly", true);
        $("[name= 'password']").css("border-color", "#ccc");
    }

    function userAction(userNo, request) {

        var messej = "";

        if (request == 'resetPass') {
            messej = 'Are you sure to reset password for this User Account?';
        } else if (request == 'activateAccount') {
            messej = 'Are you sure to activate this User Account?';
        } else if (request == 'deactivateAccount') {
            messej = 'Are you sure to deactivate this User Account?';
        } else {
            messej = 'Are you really sure to delete this User Account?';
        }

        $.alert.open({
            type: 'warning',
            cancel: false,
            content: messej,
            buttons: {
                OK: 'Yes',
                NO: 'Not now'
            },

            callback: function(button) {
                if (button == 'OK') {

                    $.ajax({
                        type: "POST",
                        url: "<?= site_url() ?>" + request,
                        data: {
                            userNo: userNo
                        },
                        success: function(data) {

                            $.alert.open({
                                type: 'warning',
                                title: 'Info',
                                icon: 'confirm',
                                cancel: false,
                                content: data,
                                buttons: {
                                    OK: 'Yes'
                                },

                                callback: function(button) {
                                    if (button == 'OK') {

                                        location.reload();
                                    }
                                }
                            });
                        }
                    });
                }

            }
        });
    }

    function userType(emp_id, type) {

        $.ajax({
            type: "POST",
            url: "<?= site_url('update_hr_account') ?>",
            data: {
                emp_id,
                type
            },
            success: function(data) {

                response = JSON.parse(data);
                if (response.status == "success") {

                    succSaveReload("User Type Successfully Updated");
                } else {

                    alert(data);
                }
            }
        });
    }

    function userStatus(emp_id, status) {

        $.ajax({
            type: "POST",
            url: "<?= site_url('update_hr_status') ?>",
            data: {
                emp_id,
                status
            },
            success: function(data) {

                response = JSON.parse(data);
                if (response.status == "success") {

                    succSaveReload("User Status Successfully Updated");
                } else {

                    alert(data);
                }
            }
        });
    }
</script>