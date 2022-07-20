<section class="content-header">
    <h1>
        Setup
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Setup</a></li>
        <li class="active">Product under Company</li>
    </ol>
</section>
<section class="content">

    <!-- Default box -->
    <div class="box box-primary">
        <div class="box-header with-border">
            <div class="pull-right">
                <button type="button" id="setup-product-company" class="btn btn-primary btn-sm">
                    Setup Product Under Company
                </button>
            </div>
        </div>

        <div class="box-body">
            <table id="dt-company-product" class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Company</th>
                        <th>Product</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->

</section>

<div class="modal fade" id="setup_product_under_company">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light-blue color-palette">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Setup Product Under Company</h4>
            </div>
            <form id="product-under-company">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 companies"></div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12 products"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>