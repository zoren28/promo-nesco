<script>
    $(document).ready(function() {

        var dt_company = $("table#dt-company").DataTable({
            "destroy": true,
            "stateSave": true,
            "ajax": {
                url: "<?= site_url('company_list') ?>"
            },
            "order": [],
            "columnDefs": [{
                "targets": [1],
                "orderable": false,
                "className": "text-center",
            }]
        });

        $('table#dt-company').on('click', 'a.action', function() {

            let [action, id] = this.id.split("_");

            if (!$(this).parents('tr').hasClass('selected')) {
                dt_company.$('tr.selected').removeClass('selected');
                $(this).parents('tr').addClass('selected');
            }

            var messej = "";
            if (action == 'delete') {
                messej = "Are you sure you want to delete this company?";
            } else {
                messej = `Are you sure you want to ${action} this company`;
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

                        if (action == 'delete') {

                            $.post("<?= site_url('delete_company') ?>", {
                                id
                            }, function(data, status) {

                                let response = JSON.parse(data);
                                if (response.status == "success") {

                                    $.alert.open({
                                        type: 'warning',
                                        title: 'Info',
                                        icon: 'confirm',
                                        cancel: false,
                                        content: "Company has been deleted.",
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
                        } else if (action == 'activate' || action == 'deactivate') {

                            $.post("<?= site_url('update_company_status') ?>", {
                                action,
                                id
                            }, function(data, status) {

                                var response = JSON.parse(data);
                                if (response.status == "success") {

                                    $.alert.open({
                                        type: 'warning',
                                        title: 'Info',
                                        icon: 'confirm',
                                        cancel: false,
                                        content: `Company has been ${action}.`,
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
                }
            });
        });

        $('table#dt-company').on('click', 'a.update_company', function() {

            let [action, id] = this.id.split("_");

            if (!$(this).parents('tr').hasClass('selected')) {
                dt_company.$('tr.selected').removeClass('selected');
                $(this).parents('tr').addClass('selected');
            }

            $("div#show-company").modal({
                backdrop: 'static',
                keyboard: false,
                show: true
            });

            $("div.show-company").html("");
            $.ajax({
                type: "GET",
                url: "<?= site_url('show_company') ?>",
                data: {
                    id
                },
                success: function(data) {

                    $("div.show-company").html(data);
                }
            });
        });

        $("form#update-company").submit(function(e) {

            e.preventDefault();

            let formData = $(this).serialize();
            let company = $("input[name = 'company']").val();
            if (company.trim() == '') {

                $.alert.open({
                    type: 'warning',
                    cancel: false,
                    content: "Please Fill-up Company Name!",
                    buttons: {
                        OK: 'Ok'
                    },

                    callback: function(button) {
                        if (button == 'OK') {

                            if (company.trim() == "") {

                                $("input[name = 'company']").css("border-color", "#dd4b39");
                            }
                        }
                    }
                });
            } else {

                $.ajax({
                    type: "POST",
                    url: "<?= site_url('update_company') ?>",
                    data: formData,
                    success: function(data) {

                        var response = JSON.parse(data);
                        if (response.status == "success") {

                            $.alert.open({
                                type: 'warning',
                                title: 'Info',
                                icon: 'confirm',
                                cancel: false,
                                content: "Company has been updated!",
                                buttons: {
                                    OK: 'Yes'
                                },

                                callback: function(button) {
                                    if (button == 'OK') {

                                        location.reload();
                                    }
                                }
                            });
                        } else if (response.status == 'exist') {

                            $.alert.open({
                                type: 'warning',
                                content: "Company is already exist"
                            });
                        } else {

                            console.log(data);
                        }
                    }
                });
            }
        });

        $("button#add-company").click(function() {

            $("div#add-company").modal({
                backdrop: 'static',
                keyboard: false,
                show: true
            });

            $("input[name = 'company']").val('');
        });

        $("form#add-company").submit(function(e) {

            e.preventDefault();
            let company = $("input[name = 'company']").val();
            if (company.trim() == "") {

                $.alert.open({
                    type: 'warning',
                    cancel: false,
                    content: "Please Fill-up Company Name!",
                    buttons: {
                        OK: 'Ok'
                    },

                    callback: function(button) {
                        if (button == 'OK') {

                            if (company.trim() == "") {

                                $("input[name = 'company']").css("border-color", "#dd4b39");
                            }
                        }
                    }
                });
            } else {

                $.ajax({
                    type: "POST",
                    url: "<?= site_url('store_company') ?>",
                    data: {
                        company
                    },
                    success: function(data) {

                        var response = JSON.parse(data);
                        if (response.status == "success") {

                            $.alert.open({
                                type: 'warning',
                                title: 'Info',
                                icon: 'confirm',
                                cancel: false,
                                content: "Company has been added!",
                                buttons: {
                                    OK: 'Yes'
                                },

                                callback: function(button) {
                                    if (button == 'OK') {

                                        location.reload();
                                    }
                                }
                            });
                        } else if (response.status == 'exist') {

                            $.alert.open({
                                type: 'warning',
                                content: "Company is already exist"
                            });
                        } else {

                            console.log(data);
                        }
                    }
                });
            }
        });
    });

    function inputField(name) {

        $("[name = '" + name + "']").css("border-color", "#d2d6de");
    }
</script>