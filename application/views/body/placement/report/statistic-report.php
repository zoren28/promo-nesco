<section class="content-header">
    <h1>
        Statistics <small>Active status only.</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Report</a></li>
        <li class="active">Statistic Report</li>
    </ol>
</section>
<section class="content">

    <div class="row">
        <div class="col-md-12">

            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Statistic Report</h3>
                </div>
                <div class="box-body">

                    <table class="table table-bordered table-stripped table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th>Business Unit</th>
                                <th>Department</th>
                                <th>
                                    <center>Total</center>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php

                            $result = $this->dashboard_model->businessUnit_list();
                            foreach ($result as $row) {

                                $count = $this->dashboard_model->count_per_bu($row->bunit_field);
                            ?>
                                <tr>
                                    <td><?php echo $row->bunit_name; ?></td>
                                    <td></td>
                                    <td align="center"><a href="#" onclick="view_stat_BU('<?= $row->bunit_field; ?>', '<?= $row->bunit_name; ?>')"><small class="badge bg-green"><?php echo $count; ?></small></a></td>
                                </tr>
                                <?php
                                $department = $this->employee_model->assigned_departments($row->bunit_id);
                                foreach ($department as $dept) {

                                    $countDept = $this->dashboard_model->count_per_dept($row->bunit_field, $dept->dept_name);
                                ?>
                                    <tr>
                                        <td></td>
                                        <td><?php echo $dept->dept_name; ?></td>
                                        <td align="center"><a href="#" onclick="view_stat_dept('<?= $row->bunit_field; ?>', '<?= $row->bunit_name; ?>', '<?= $dept->dept_name; ?>')"><?= $countDept ?></a></td>
                                    </tr>
                            <?php
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ./Modal -->
<div id="viewStatistics" class="modal fade">
    <div class="modal-dialog" style="width: 85%">
        <div class="modal-content">
            <div class="modal-header bg-light-blue color-palette">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Statistics Summary</h4>
            </div>
            <div class="modal-body">
                <div class="viewStatistics"></div>
            </div>
        </div>
    </div>
</div>