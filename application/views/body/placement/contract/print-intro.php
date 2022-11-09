<style type="text/css">
    .search-results {

        box-shadow: 5px 5px 5px #ccc;
        margin-top: 1px;
        margin-left: 0px;
        background-color: #F1F1F1;
        width: 92%;
        border-radius: 3px 3px 3px 3px;
        font-size: 18x;
        padding: 8px 10px;
        display: block;
        position: absolute;
        z-index: 9999;
        max-height: 300px;
        overflow-y: scroll;
        overflow: auto;
    }
</style>
<section class="content-header">
    <h1>
        Intro
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Intro</a></li>
        <li class="active">Print Intro</li>
    </ol>
</section>

<section class="content">

    <!-- Default box -->
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Print Intro</h3>
                </div>

                <div class="box-body">

                    <div class="row">
                        <form id="generate_contract" autocomplete="off">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label>Search Promo</label>
                                    <input type="hidden" name="page" value="print-intro">
                                    <div class="input-group">
                                        <input class="form-control" type="text" name="employee" onkeyup="nameSearch(this.value)">
                                        <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                    </div>
                                    <div class="search-results" style="display: none;"></div>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="form-group" style="margin-top:23px;">
                                    <button type="button" id="print-intro" class="btn btn-primary">Print Intro</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>