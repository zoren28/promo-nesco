<script>
    $(document).ready(function() {

        var dashboard = $("input[name = 'dashboard']").val();
        if (dashboard == "comeOut") {

            var pieChartCanvas = $("#pieChart").get(0).getContext("2d");
            var pieChart = new Chart(pieChartCanvas);
            var PieData = [

                <?php

                $c = 0;
                $result = $this->dashboard_model->businessUnit_list();
                foreach ($result as $row) { ?>

                    {
                        value: <?php echo $this->dashboard_model->count_per_bu($row->bunit_field); ?>,
                        color: "<?php echo $pieColor[$c]; ?>",
                        highlight: "<?php echo $pieColor[$c]; ?>",
                        label: "<?= $row->bunit_name; ?>"
                    },
                <?php

                    $c++;
                }
                ?>
            ];
            var pieOptions = {
                //Boolean - Whether we should show a stroke on each segment
                segmentShowStroke: true,
                //String - The colour of each segment stroke
                segmentStrokeColor: "#fff",
                //Number - The width of each segment stroke
                segmentStrokeWidth: 2,
                //Number - The percentage of the chart that we cut out of the middle
                percentageInnerCutout: 0, // This is 0 for Pie charts
                //Number - Amount of animation steps
                animationSteps: 100,
                //String - Animation easing effect
                animationEasing: "easeOutBounce",
                //Boolean - Whether we animate the rotation of the Doughnut
                animateRotate: true,
                //Boolean - Whether we animate scaling the Doughnut from the centre
                animateScale: false,
                //Boolean - whether to make the chart responsive to window resizing
                responsive: true,
                // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
                maintainAspectRatio: true,
                //String - A legend template
                legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<segments.length; i++){%><li><span style=\"background-color:<%=segments[i].fillColor%>\"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>"
            };
            //Create pie or douhnut chart
            // You can switch between pie and douhnut using the method below.
            pieChart.Doughnut(PieData, pieOptions);

            setTimeout(function() {

                $.ajax({
                    url: "<?php echo site_url('new_employee'); ?>",
                    success: function(data) {
                        console.log(data);
                        $("#new_employee").html(data);
                    }
                });

                $.ajax({
                    url: "<?php echo site_url('birthday_today'); ?>",
                    success: function(data) {

                        $("#birthday_today").html(data);
                    }
                });

                $.ajax({
                    url: "<?php echo site_url('active_employee'); ?>",
                    success: function(data) {

                        $("#active_employee").html(data);
                    }
                });

                $.ajax({
                    url: "<?php echo site_url('eoc_today'); ?>",
                    success: function(data) {

                        $("#eoc_today").html(data);
                    }
                });

                $.ajax({
                    url: "<?php echo site_url('due_contract'); ?>",
                    success: function(data) {

                        $("#due_contract").html(data);
                    }
                });
            });
        }

        var dataTable = $("#birthday_table").DataTable({

            "destroy": true,
            "ajax": {
                url: "<?php echo site_url('fetch_birthday_today'); ?>",
            },
            "order": [],
            "columnDefs": [{
                "targets": [1, 2, 3, 4, 5],
                "orderable": false,
            }, ],
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

    function viewDetails() {
        window.location = "<?php echo base_url('/placement/page/menu/report/statistic-report'); ?>";
    }
</script>