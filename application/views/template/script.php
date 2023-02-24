</div>
<!-- /.content-wrapper -->

<footer class="main-footer">
    <div class="pull-right hidden-xs">
        <b>Version</b> 1.0.0
    </div>
    <strong>Copyright &copy; 2021-2022 <a href="#">Hrms Promo-NESCO</a>.</strong> All rights reserved.
</footer>

<!-- jQuery 3.3.1 -->
<script src="<?php echo base_url('assets/js/jquery.min.js'); ?>"></script>

<!-- Bootstrap 3.3.6 -->
<script src="<?php echo base_url('assets/bootstrap/js/bootstrap.min.js'); ?>"></script>

<!-- jQuery UI 1.12.1 -->
<script src="<?php echo base_url('assets/js/jquery-ui.min.js'); ?>"></script>

<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
    $.widget.bridge('uibutton', $.ui.button);
</script>

<!-- Select2 -->
<script src="<?php echo base_url('assets/plugins/select2/select2.full.min.js'); ?>"></script>

<!-- InputMask -->
<script src="<?php echo base_url('assets/plugins/input-mask/jquery.inputmask.js'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/input-mask/jquery.inputmask.date.extensions.js'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/input-mask/jquery.inputmask.extensions.js'); ?>"></script>

<!-- DataTables -->
<script src="<?php echo base_url('assets/plugins/DataTables/datatables.min.js'); ?>"></script>

<!-- ChartJS 1.0.1 -->
<script src="<?php echo base_url('assets/plugins/chartjs/Chart.min.js'); ?>"></script>
<!-- <script src="<?php echo base_url('assets/plugins/chartjs/dough.js'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/chartjs/chart_morris.js'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/chartjs/morris.js'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/chartjs/morris.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/chartjs/rafa.js'); ?>"></script> -->
<!-- AdminLTE App -->
<script src="<?php echo base_url('assets/dist/js/app.min.js'); ?>"></script>

<!-- AdminLTE for demo purposes -->
<script src="<?php echo base_url('assets/dist/js/demo.js'); ?>"></script>

<!-- Alert Messages -->
<script src="<?php echo base_url('assets/plugins/alert/js/alert.js'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/alert/js/doc.js'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/alert/message.js'); ?>"></script>
<script src="<?php echo base_url('assets/plugins/alert/js/sweetalert.js'); ?>"></script>



<script type="text/javascript">
    $(document).ready(function() {

        $(".datepicker").datepicker({

            changeYear: true,
            changeMonth: true
        });

        $("[data-mask]").inputmask();

        $("input[name = 'searchEmployee']").keypress(function(e) {

            var code = e.keyCode || e.which;
            if (code == 13) {

                var searchThis = $("input[name = 'searchEmployee']").val();
                $.ajax({
                    type: "GET",
                    url: "<?php echo site_url('searchThis'); ?>",
                    data: {
                        searchThis
                    },
                    success: function(data) {

                        window.location = "<?php echo base_url('placement/page/menu/employee/search-promo'); ?>";
                    }
                });
            }
        });
    });
</script>
</div>