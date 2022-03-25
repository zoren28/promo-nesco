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

                            console.log(data.message);
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
    });

    function nameSearch(key) {

        var str = key.trim();
        if (str == '') {

            $(".search-results").hide();
        } else {

            $.ajax({
                type: "POST",
                url: "<?php echo site_url('find_active_promo'); ?>",
                data: {
                    str: str
                },
                success: function(data) {

                    $(".search-results").show().html(data);
                }
            });
        }
    }

    function getEmpId(response) {

        let res = response.split('*');

        $(".search-results").hide();
        $("input[name='employee']").val(response);
        $("input[name = 'employee']").css("border-color", "#ccc");

        $.ajax({
            type: "GET",
            url: "<?php echo site_url('promo_details'); ?>",
            data: {
                empId: res[0].trim()
            },
            success: function(data) {

                $("div.promo-details").html(data);

                $("div#loading-gif").show();
                $.ajax({
                    type: "GET",
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
    }

    function inputField(name) {

        $("[name = '" + name + "']").css("border-color", "#d2d6de");
    }
</script>