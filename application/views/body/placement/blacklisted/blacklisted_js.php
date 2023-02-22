<script>
    $(document).ready(function() {

        var dataTable = $("#blacklists").DataTable({

            "destroy": true,
            "processing": true,
            "serverSide": true,
            "ajax": {
                url: "<?php echo site_url('fetch_blacklisted'); ?>",
                type: "POST"
            },
            "order": [],
            "columnDefs": [{
                "targets": [0, 2, 3, 4, 5],
                "orderable": false,
            }, ],
        });

        $('#blacklists').on('click', 'button.record', function() {

            var id = this.id;
            if (!$(this).parents('tr').hasClass('selected')) {
                dataTable.$('tr.selected').removeClass('selected');
                $(this).parents('tr').addClass('selected');
            }

            $("div#update_blacklist_form").modal({
                backdrop: 'static',
                keyboard: false
            });

            $("div#update_blacklist_form").modal("show");
            $("div.update_blacklist_form").html('');
            $("button.update_blacklisted").prop('disabled', false);

            $.ajax({
                type: "POST",
                url: "<?php echo site_url('update_blacklist_form'); ?>",
                data: {
                    id: id
                },
                success: function(result) {

                    $("div.update_blacklist_form").html(result);
                }
            });

        });

        $("button#browse").click(function(e) {

            e.preventDefault();

            $("div#browse_employee").modal({
                backdrop: 'static',
                keyboard: false
            });

            $("div#browse_employee").modal("show");

            $.ajax({
                type: "POST",
                url: "<?php echo site_url('candidate_for_blacklisted'); ?>",
                success: function(response) {

                    $("div.browse_employee").html(response);
                }
            });
        });

        $("form#blacklist_form").submit(function(e) {

            e.preventDefault();
            var formData = $(this).serialize();

            var reason = $("textarea[name = 'reason']").val().trim();
            var dateBlacklisted = $("input[name = 'dateBlacklisted']").val().trim();
            var reportedBy = $("input[name = 'reportedBy']").val().trim();

            if (reason == "" || dateBlacklisted == "" || reportedBy == "") {

                errDup("Please fill out this required field.");

            } else {

                $.ajax({
                    url: "<?php echo site_url('add_blacklist'); ?>",
                    type: 'POST',
                    data: formData,
                    success: function(response) {

                        response = response.trim();

                        if (response == "success") {

                            $.alert.open({
                                type: 'warning',
                                title: 'Info',
                                icon: 'confirm',
                                cancel: false,
                                content: "Blacklist Information Successfully Saved!",

                                callback: function(button) {

                                    location.reload();
                                }
                            });
                        } else {

                            errDup("There is an error in saving blacklist form.");
                        }
                    }

                });
            }
        });

        $("button#reset_fields").click(function(e) {

            e.preventDefault();
            location.reload(true);
        });

        $("form#update_blacklist").submit(function(e) {

            e.preventDefault();
            var formData = $(this).serialize();

            var reason = $("textarea[name = 'reason']").val().trim();
            var dateBlacklisted = $("input[name = 'dateBlacklisted']").val().trim();
            var reportedBy = $("input[name = 'reportedBy']").val().trim();

            if (reason == "" || dateBlacklisted == "" || reportedBy == "") {

                errDup("Please fill out this required field.");

            } else {

                $("button.update_blacklisted").prop('disabled', true);
                $.ajax({
                    url: "<?php echo site_url('update_blacklist'); ?>",
                    type: 'POST',
                    data: formData,
                    success: function(response) {

                        response = response.trim();

                        if (response == "success") {

                            $.alert.open({
                                type: 'warning',
                                title: 'Info',
                                icon: 'confirm',
                                cancel: false,
                                content: "Blacklist Information Successfully Updated!",

                                callback: function(button) {

                                    $("div#update_blacklist_form").modal("hide");

                                    window.location.reload();
                                }
                            });
                        } else {

                            errDup("There is an error in saving blacklist form.");
                        }
                    }
                });
            }
        });

        $("button#reset_fields").click(function(e) {

            e.preventDefault();
            resetFields();
        });

        if ("<?php echo @$empId ?>" != "") {

            enabledFields();
        }

    })

    function enabledFields() {

        $("textarea[name = 'reason']").prop("disabled", false);
        $("input[name = 'dateBlacklisted']").prop("disabled", false);
        $("input[name = 'birthday']").prop("disabled", false);
        $("input[name = 'reportedBy']").prop("disabled", false);
        $("input[name = 'address']").prop("disabled", false);
        $("button.button_opt").prop("disabled", false);
    }

    function resetFields() {

        // disabled
        $("textarea[name = 'reason']").prop("disabled", true);
        $("input[name = 'dateBlacklisted']").prop("disabled", true);
        $("input[name = 'birthday']").prop("disabled", true);
        $("input[name = 'reportedBy']").prop("disabled", true);
        $("input[name = 'address']").prop("disabled", true);
        $("button.button_opt").prop("disabled", true);

        // empty fields
        $("textarea[name = 'reason']").val("");
        $("input[name = 'dateBlacklisted']").val("");
        $("input[name = 'birthday']").val("");
        $("input[name = 'reportedBy']").val("");
        $("input[name = 'address']").val("");
        $("input[name = 'employee']").val("");
    }

    function getEmpId(supId) {

        $("input[name='reportedBy']").val(supId);
        $("div.search-results").hide();
    }

    function nameSearch(str) {

        var str = str.trim();
        $(".search-results").hide();

        if (str == '') {

            $(".search-results").hide();
        } else {

            $.ajax({
                type: "POST",
                url: "<?php echo site_url('find_hr_staff'); ?>",
                data: {
                    str: str
                },
                success: function(data) {

                    $(".search-results").show().html(data);
                }
            });
        }
    }
</script>