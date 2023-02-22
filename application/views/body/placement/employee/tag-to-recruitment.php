<style type="text/css">
    .loader {
        background: url("<?= base_url('assets/images/loader.gif'); ?>");
        background-repeat: no-repeat;
        background-position: right;
    }
</style>

<section class="content-header">
    <h1>
        Promo
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Promo</a></li>
        <li class="active">Tag to Recruitment</li>
    </ol>
</section>

<section class="content">

    <!-- Default box -->
    <div class="row">
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Tag to Recruitment</h3>
                </div>
                <form id="tag-recruitment" autocomplete="off">
                    <div class="box-body">
                        <div class="form-group"> <i class="text-red">*</i>
                            <label>Search Applicant</label>
                            <div class="input-group">
                                <input class="form-control" type="text" name="applicant" placeholder="Lastname or Firstname or Emp. ID" autofocus>
                                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                            </div>
                            <span class="applicant-error error-message text-danger"></span>
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <input type="text" name="applicant_status" class="form-control" readonly>
                        </div>
                        <div class="form-group"> <i class="text-red">*</i>
                            <label>Recruitment Process</label>
                            <select name="status" class="form-control">
                                <option value=""> -- Select -- </option>
                                <?php

                                $processes = [
                                    'initial_completion' => 'Initial Completion',
                                    'initialreq completed' => 'For Examination',
                                    'for interview' => 'For Interview',
                                    'for training' => 'For Training',
                                    'for final completion' => 'For Final Completion',
                                    'for orientation' => 'For Orientation',
                                    'for hiring' => 'For Hiring',
                                    'deployed' => 'For Deployment'
                                ];

                                foreach ($processes as $key => $value) {
                                    echo "<option value='{$key}'>{$value}</option>";
                                }
                                ?>
                            </select>
                            <span class="status-error error-message text-danger"></span>
                        </div>
                        <div class="form-group"> <i class="text-red">*</i>
                            <label>Position</label>
                            <select name="position" class="form-control">
                                <option value=""> --Select-- </option>
                                <?php
                                foreach ($positions as $position) {
                                    echo "<option value='{$position['position_title']}'>{$position['position_title']}</option>";
                                }
                                ?>
                            </select>
                            <span class="position-error error-message text-danger"></span>
                        </div>
                        <div class="form-group">
                            <label>Tag To <i>(Optional)</i></label>
                            <select name="tagged_to" class="form-control">
                                <option value=""> -- Select -- </option>
                                <?php

                                $tags = [
                                    'nesco' => 'NESCO',
                                    'promo_nesco' => 'Promo-NESCO'
                                ];

                                foreach ($tags as $key => $value) {
                                    echo "<option value='{$key}'>{$value}</option>";
                                }
                                ?>
                            </select>
                            <span class="tagged_to-error error-message text-danger"></span>
                        </div>
                    </div>
                    <div class="box-footer">
                        <div class="pull-right">
                            <button type="submit" id="submit-btn" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</section>