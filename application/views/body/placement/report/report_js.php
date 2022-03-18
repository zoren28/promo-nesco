<script>
    $(document).ready(function() {

        $(".select2").select2();

        $("input.fields").change(function() {

            if ($("input#" + this.id).is(':checked')) {

                $("span." + this.id).css({
                    "color": "red",
                    "font-style": "italic"
                });
            } else {

                $("span." + this.id).css({
                    "color": "black",
                    "font-style": "normal"
                });
            }

        });

        $("input.condition").change(function() {

            var count = $("input[name = 'condition[]']:checked").length;
            if (count > 5) {

                $("input#" + this.id).prop('checked', false);

            } else {

                if ($("input#" + this.id).is(':checked')) {

                    $("span." + this.id).css({
                        "color": "red",
                        "font-style": "italic"
                    });
                    $(".tf_" + this.id).prop('disabled', false);
                } else {

                    $("span." + this.id).css({
                        "color": "black",
                        "font-style": "normal"
                    });

                    $(".tf_" + this.id).val('');
                    $(".tf_" + this.id).prop('disabled', true);
                }
            }
        });

        var dataTable = $("#due_of_contract").DataTable({

            "destroy": true,
            "ajax": {
                url: "<?php echo site_url('fetch_due_contract'); ?>",
            },
            "order": [],
            "columnDefs": [{
                "targets": [1, 2, 3, 4, 5, 6],
                "orderable": false,
            }, ],
        });

        $("button#generate_duecontract").click(function() {

            $.alert.open({
                type: 'warning',
                cancel: false,
                content: "Generate Report Now?",
                buttons: {
                    OK: 'Yes',
                    NO: 'Not now'
                },

                callback: function(button) {
                    if (button == 'OK') {

                        window.open("<?php echo base_url('placement/dashboard/due_contract_xls'); ?>");
                    }
                }
            });
        });
    });

    function view_stat_BU(field, bunit_name) {

        $("#viewStatistics").modal({
            backdrop: 'static',
            keyboard: false
        });

        $("#viewStatistics").modal("show");
        $(".viewStatistics").html('');

        $.ajax({
            type: "GET",
            url: "<?php echo site_url('view_stat_BU'); ?>",
            data: {
                field,
                bunit_name
            },
            success: function(data) {

                $(".viewStatistics").html(data);
            }
        });
    }

    function view_stat_dept(field, bunit_name, dept) {

        $("#viewStatistics").modal({
            backdrop: 'static',
            keyboard: false
        });

        $("#viewStatistics").modal("show");
        $(".viewStatistics").html('');

        $.ajax({
            type: "GET",
            url: "<?php echo site_url('view_stat_dept'); ?>",
            data: {
                field,
                bunit_name,
                dept
            },
            success: function(data) {

                $(".viewStatistics").html(data);
            }
        });
    }

    function generate_stat_BU(field) {

        $.alert.open({
            type: 'warning',
            cancel: false,
            content: "Generate Report Now?",
            buttons: {
                OK: 'Yes',
                NO: 'Not now'
            },

            callback: function(button) {
                if (button == 'OK') {

                    window.location = "<?php echo base_url('placement/report/stat_BU_xls/'); ?>" + field;
                }
            }
        });
    }

    function generate_stat_dept(field, dept) {

        $.alert.open({
            type: 'warning',
            cancel: false,
            content: "Generate Report Now?",
            buttons: {
                OK: 'Yes',
                NO: 'Not now'
            },

            callback: function(button) {
                if (button == 'OK') {

                    window.location = "<?php echo base_url('placement/report/stat_dept_xls/'); ?>" + field + '/' + dept;
                }
            }
        });
    }

    function select_company(agency_code) {

        $.ajax({
            type: "GET",
            url: "<?php echo site_url('select_company_under_agency'); ?>",
            data: {
                agency_code
            },
            success: function(data) {

                $("select[name = 'company']").html(data);
            }
        });
    }

    function select_department(id) {

        let storeId = [];
        storeId.push(id);

        if (id) {

            $.ajax({
                type: "POST",
                url: "<?php echo site_url('locate_promo_department'); ?>",
                data: {
                    storeId
                },
                success: function(data) {

                    $("select[name = 'department']").html(data);
                }
            });

        } else {

            $("select[name = 'department']").html('<option value=""> --Select Department-- </option>');
        }
    }

    function genReport(type) {

        var store = $("[name = 'business_unit']").val();
        var department = $("[name = 'department']").val();
        var company = $("[name = 'company']").val();
        var month = $("[name = 'month']").val();

        if (month == "") {

            $.alert.open({
                type: 'warning',
                cancel: false,
                content: "Please Fill-up Required Fields!",
                buttons: {
                    OK: 'Ok'
                },
                callback: function(button) {
                    if (button == 'OK') {

                        if (month == "") {

                            $("[name = 'month']").css("border-color", "#dd4b39");
                        }
                    }
                }
            });
        } else {

            $.alert.open({
                type: 'warning',
                cancel: false,
                content: "Generate report now?",
                buttons: {
                    OK: 'Yes',
                    NO: 'Not now'
                },

                callback: function(button) {
                    if (button == 'OK') {

                        if (type == "excel") {

                            window.location = "<?= base_url('placement/report/termination_of_contract_xls') ?>" + '?' + `store=${store}&department=${department}&company=${company}&month=${month}`;
                        } else if (type == "pdf") {

                            window.location = "<?= base_url('placement/report/termination_of_contract_pdf') ?>" + '?' + `store=${store}&department=${department}&company=${company}&month=${month}`;
                        } else if (type == "list") {

                            window.open("?p=terminationReportList&&module=Reports&&report=forStore&&store=" + store + "&&department=" + department + "&&company=" + company + "&&month=" + month);
                        } else {

                            window.open("?p=terminationReportList&&module=Reports&&report=forCompany&&store=" + store + "&&department=" + department + "&&company=" + company + "&&month=" + month);
                        }
                    }

                }
            });
        }
    }

    function genUsernameReport() {

        let store = $("[name = 'business_unit']").val();
        let department = $("[name = 'department']").val();
        let company = $("[name = 'company']").val();

        $.alert.open({
            type: 'warning',
            cancel: false,
            content: "Generate report now?",
            buttons: {
                OK: 'Yes',
                NO: 'Not now'
            },

            callback: function(button) {
                if (button == 'OK') {

                    window.location = "<?php echo base_url('placement/report/username_xls') ?>" + '?' + `store=${store}&department=${department}&company=${company}`;
                }
            }
        });
    }

    function select_product(company_code) {

        if (company_code) {
            $.ajax({
                type: "GET",
                url: "<?php echo site_url('select_product'); ?>",
                data: {
                    company_code
                },
                success: function(data) {

                    $("select[name = 'product']").html(data);
                }
            });
        } else {

            $.ajax({
                type: "GET",
                url: "<?php echo site_url('product_list'); ?>",
                success: function(data) {

                    $("select[name = 'product']").html(data);
                }
            });
        }
    }
</script>