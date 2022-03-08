<script>
    $(document).ready(function() {

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
</script>