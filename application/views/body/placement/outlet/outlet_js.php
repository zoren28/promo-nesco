<script>
    $(document).ready(function() {

        $('form#dataOutlet').submit(function(e) {

            e.preventDefault();
            var formData = $(this).serialize();

            var effective_on = $("input[name = 'effective_on']").val();
            var current_store = $("input[name = 'current_store']").val();
            var current = current_store.split(',');

            var store = [];
            var loop = $("[name = 'counter']").val();

            for (var i = 1; i <= loop; i++) {

                if ($("input#field" + i).is(':checked')) {

                    store.push($("input#field" + i).val());
                }
            }

            if (store.length === 0 || effective_on === "" || current.length === store.length) {

                if (store.length === 0 || current.length === store.length) {

                    errDup("Please Select Business Unit!");
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

                                if (effective_on == "") {

                                    $("input[name = 'effective_on']").css("border-color", "#dd4b39");
                                }
                            }

                        }
                    });
                }
            } else {

                formData = formData + '&' + $.param({
                    'store': store
                })

                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('add_new_outlet') ?>",
                    data: formData,
                    success: function(data) {

                        let response = JSON.parse(data);
                        if (response.message == "success") {

                            $.alert.open({
                                type: 'warning',
                                title: 'Info',
                                icon: 'confirm',
                                cancel: false,
                                content: "<b>New Outlet Successfully Added!</b> <br><br> Please inform the promo to proceed to the newly assigned store for futher instructions.",
                                buttons: {
                                    OK: 'Yes'
                                },

                                callback: function(button) {
                                    if (button == 'OK') {

                                        location.reload();
                                    }

                                }
                            });
                        } else {

                            console.log(response.message);
                        }
                    }
                });
            }

        });

        $('form#dataTransferOutlet').submit(function(e) {

            e.preventDefault();

            var formData = $(this).serialize();
            var effective_on = $("input[name = 'effective_on']").val();
            var loop = $("input[name = 'loop']").val();

            if (loop == 0 || effective_on == "") {

                if (loop == 0) {

                    errDup("Please Select Business Unit!");
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

                                if (effective_on == "") {

                                    $("input[name = 'effective_on']").css("border-color", "#dd4b39");
                                }
                            }

                        }
                    });
                }
            } else {

                var store = [];
                $("input[name = 'transfer_stores[]']:checked").each(function() {

                    store.push(this.value);
                });

                formData = formData + '&' + $.param({
                    'store': store
                })

                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('transfer_outlet') ?>",
                    data: formData,
                    success: function(data) {

                        let response = JSON.parse(data);
                        if (response.message == "success") {

                            $.alert.open({
                                type: 'warning',
                                title: 'Info',
                                icon: 'confirm',
                                cancel: false,
                                content: "<b>Successfully Transfer Outlet!</b> <br><br> Please inform the promo to proceed to the newly assigned store for futher instructions.",
                                buttons: {
                                    OK: 'Yes'
                                },

                                callback: function(button) {
                                    if (button == 'OK') {

                                        location.reload();
                                    }

                                }
                            });
                        } else {

                            console.log(response.message);
                        }
                    }
                });
            }

        });

        var dataTable = $("table#outlet_history_table").DataTable({

            "destroy": true,
            "ajax": {
                url: "<?php echo site_url('change_outlet_histories'); ?>"
            },
            "order": [],
            "columnDefs": [{
                "targets": [0, 3, 4],
                "orderable": false,
            }, ],
        });

        $("form#dataUploadClearance").submit(function(e) {

            e.preventDefault();

            var emp_id = $("input[name = 'emp_id']").val();
            var record_no = $("input[name = 'record_no']").val();
            var clearances = [];

            var formData = new FormData(this);

            $("input[name = 'clearances[]']").each(function() {
                clearances.push(this.value)
            });

            var loop = $("input[name = 'clearances[]']").length;
            var chkNum = 0;
            for (let i = 1; i <= loop; i++) {

                var file = $("input.clearance_" + i).val();
                if (file != "") {

                    chkNum++;
                }
            }

            if (chkNum < loop) {

                $.alert.open({
                    type: 'warning',
                    cancel: false,
                    content: "Please Upload Clearance Needed!",
                    buttons: {
                        OK: 'Ok'
                    },

                    callback: function(button) {
                        if (button == 'OK') {

                            for (var i = 1; i <= loop; i++) {

                                var file = $(".clearance_" + i).val();
                                if (file == "") {

                                    $("input.clearance_" + i).css("border-color", "#dd4b39");
                                }
                            }
                        }
                    }
                });
            } else {

                $.ajax({
                    url: "<?php echo site_url('uploadClearance'); ?>",
                    type: 'POST',
                    data: formData,
                    success: function(data) {

                        let response = JSON.parse(data);
                        if (response.message == "success") {

                            $.alert.open({
                                type: 'warning',
                                title: 'Info',
                                icon: 'confirm',
                                cancel: false,
                                content: "Clearance Successfully Uploaded!",
                                buttons: {
                                    OK: 'Yes'
                                },

                                callback: function(button) {
                                    if (button == 'OK') {

                                        $("div#transfer_details").modal({
                                            backdrop: 'static',
                                            keyboard: false,
                                            show: true
                                        });

                                        $.ajax({
                                            url: "<?php echo site_url('transfer_details_form'); ?>",
                                            data: {
                                                record_no,
                                                emp_id,
                                                clearances
                                            },
                                            success: function(data) {

                                                $("div.transfer_details").html(data);
                                            }
                                        });
                                    }

                                }
                            });
                        } else {

                            console.log(response.message);
                        }
                    },
                    async: false,
                    cache: false,
                    contentType: false,
                    processData: false
                });
            }
        });

        $("form#dataUploadClearanceToRemoveOutlet").submit(function(e) {

            e.preventDefault();
            let formData = new FormData(this);
            let clearances = [];

            $("input[name = 'clearances[]']").each(function() {
                clearances.push(this.value)
            });

            var loop = $("input[name = 'clearances[]']").length;
            var chkNum = 0;
            for (let i = 1; i <= loop; i++) {

                var file = $("input.clearance_" + i).val();
                if (file != "") {

                    chkNum++;
                }
            }

            if (chkNum < loop) {

                $.alert.open({
                    type: 'warning',
                    cancel: false,
                    content: "Please Upload Clearance Needed!",
                    buttons: {
                        OK: 'Ok'
                    },

                    callback: function(button) {
                        if (button == 'OK') {

                            for (var i = 1; i <= loop; i++) {

                                var file = $(".clearance_" + i).val();
                                if (file == "") {

                                    $("input.clearance_" + i).css("border-color", "#dd4b39");
                                }
                            }
                        }
                    }
                });
            } else {

                $.ajax({
                    url: "<?php echo site_url('remove_outlet'); ?>",
                    type: 'POST',
                    data: formData,
                    success: function(data) {

                        let response = JSON.parse(data);
                        if (response.message == "success") {

                            $.alert.open({
                                type: 'warning',
                                title: 'Info',
                                icon: 'confirm',
                                content: 'Successfully Remove the Outlet!',

                                callback: function() {

                                    location.reload();
                                }
                            });
                        } else {

                            console.log(response.message);
                        }
                    },
                    async: false,
                    cache: false,
                    contentType: false,
                    processData: false
                });
            }
        });
    });

    function nameSearch(key) {

        var str = key.trim();
        var action = $("input[name = 'action']").val();
        if (str == '') {

            $(".search-results").hide();
        } else {
            var promo_type = '';
            if (action == 'remove_outlet') {

                promo_type = 'ROVING';
            } else {

                promo_type = 'all';
            }

            $.ajax({
                type: "POST",
                url: "<?php echo site_url('find_active_promo'); ?>",
                data: {
                    str: str,
                    promo_type: promo_type
                },
                success: function(data) {

                    $(".search-results").show().html(data);
                }
            });
        }
    }

    function getEmpId(response) {

        let res = response.split('*');
        let action = $("input[name = 'action']").val();

        $(".search-results").hide();
        $("input[name='employee']").val(response);
        $("input[name = 'employee']").css("border-color", "#ccc");

        if (action == 'add_outlet') {

            $.ajax({
                url: "<?php echo site_url('promo_details'); ?>",
                data: {
                    empId: res[0].trim()
                },
                success: function(data) {

                    $("div.promo-details").html(data);

                    $("div#loading-gif").show();
                    $.ajax({
                        url: "<?php echo site_url('add_outlet_form'); ?>",
                        data: {
                            empId: res[0].trim()
                        },
                        success: function(data) {

                            $("div#loading-gif").hide();
                            $("div.outlet-form").html(data);
                        }
                    });
                }
            });

        } else if (action == 'transfer_outlet') {

            $.ajax({
                url: "<?php echo site_url('promo_details'); ?>",
                data: {
                    empId: res[0].trim()
                },
                success: function(data) {

                    $("div.promo-details").html(data);

                    $("div#loading-gif").show();
                    $.ajax({
                        url: "<?php echo site_url('transfer_outlet_form'); ?>",
                        data: {
                            empId: res[0].trim()
                        },
                        success: function(data) {

                            $("div#loading-gif").hide();
                            $("div.outlet-form").html(data);
                        }
                    });
                }
            });

        } else {

            $.ajax({
                url: "<?php echo site_url('promo_details'); ?>",
                data: {
                    empId: res[0].trim()
                },
                success: function(data) {

                    $("div.promo-details").html(data);

                    $("div#loading-gif").show();
                    $.ajax({
                        url: "<?php echo site_url('remove_outlet_form'); ?>",
                        data: {
                            empId: res[0].trim()
                        },
                        success: function(data) {

                            $("div#loading-gif").hide();
                            $("div.outlet-form").html(data);
                        }
                    });
                }
            });
        }
    }

    function inputField(name) {

        $("[name = '" + name + "']").css("border-color", "#d2d6de");
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

    function storeChoice(id) {

        var checkedNum = $('input[name="stores[]"]:checked').length;
        if (!checkedNum) {

            $("button.transfer_btn").prop("disabled", true);
        } else {

            $("button.transfer_btn").prop("disabled", false);
        }

        if ($("input#store-" + id).is(':checked')) {

            $("span.transfer_" + id).css({
                "color": "red",
                "font-style": "italic"
            });

        } else {

            $("span.transfer_" + id).css({
                "color": "black",
                "font-style": "normal"
            });
        }
    }

    function proceedTo(record_no, emp_id) {

        var stores = [];
        let bUs = [];
        let current_store = $("input[name = 'current_store']").val();
        $('input[name="stores[]"]:checked').each(function() {

            stores.push($(this).val());
        });

        $("input[name = 'bUs[]']").each(function() {

            bUs.push($(this).val());
        })

        $("div#store_clearance").modal({
            backdrop: 'static',
            keyboard: false,
            show: true
        });

        $.ajax({
            url: "<?php echo site_url('store_clearance_form'); ?>",
            data: {
                current_store,
                record_no,
                emp_id,
                stores,
                bUs
            },
            success: function(data) {

                $(".store_clearance").html(data);
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

    function selectStore(ctr) {

        var loop = $("input[name = 'loop']").val();
        if ($("input.store-" + ctr).is(":checked")) {

            loop++;
        } else {

            loop--;
        }

        $("input[name = 'loop']").val(loop);
        if (loop == 0) {
            $("button.transfer_now_btn").prop('disabled', true);
        } else {
            $("button.transfer_now_btn").prop('disabled', false);
        }
    }

    function removeThisStore(id) {

        var checkedNum = $('input[name="stores[]"]:checked').length;
        var storeNum = $('input[name="stores[]"]').length;

        if ($("input#store-" + id).is(':checked')) {

            $("span.remove_" + id).css({
                "color": "red",
                "font-style": "italic"
            });

        } else {

            $("span.remove_" + id).css({
                "color": "black",
                "font-style": "normal"
            });
        }

        if ((storeNum === 1 || storeNum > 1) && checkedNum === 0) {

            $("button.remove_btn").prop("disabled", true);

        } else if (storeNum > 1 && checkedNum === storeNum) {

            if ($("input#store-" + id).is(':checked')) {

                $("span.remove_" + id).css({
                    "color": "black",
                    "font-style": "normal"
                });

                $("input#store-" + id).prop("checked", false);
            }

            $("button.remove_btn").prop("disabled", false);

        } else {

            $("button.remove_btn").prop("disabled", false);
        }
    }
</script>