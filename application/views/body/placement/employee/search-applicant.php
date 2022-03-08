<section class="content-header">
    <h1>
        <?php echo ucwords(strtolower($title)); ?>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#"><?php echo ucwords(strtolower($title));; ?></a></li>
        <li class="active">Search Applicant</li>
    </ol>
</section>
<section class="content">

    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-body">
                    <p><i class="text-red">Note:</i> Lastname is required.</p>
                    <div class="row">
                        <div class="col-md-10">
                            <form id="data_searchApplicant" action="" method="post">
                                <div class="form-group">
                                    <label>Search Applicant</label>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <input type="text" name="lastnameApp" class="form-control" placeholder="Lastname" autocomplete="off" style="text-transform: uppercase;" required="">
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" name="firstnameApp" class="form-control" placeholder="Firstname" autocomplete="off" style="text-transform: uppercase;">
                                        </div>
                                        <div class="col-sm-2">
                                            <button type="submit" class="btn btn-primary loading_process"><i class="fa fa-search"></i> Search</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12 search_applicant">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>