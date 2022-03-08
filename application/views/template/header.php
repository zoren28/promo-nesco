<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="shortcut icon" href="<?php echo base_url('assets/images/hrms.png'); ?>">
    <title><?php echo ucwords($title); ?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css'); ?>">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo base_url('assets/bootstrap/css/font-awesome.css'); ?>">

    <!-- Ionicons -->
    <link rel="stylesheet" href="<?php echo base_url('assets/bootstrap/css/ionicons.css'); ?>">

    <!-- Jquery UI -->
    <link rel="stylesheet" href="<?php echo base_url('assets/js/jquery-ui.min.css'); ?>">

    <!-- Select2 -->
    <link rel="stylesheet" href="<?php echo base_url('assets/plugins/select2/select2.min.css'); ?>">

    <!-- DataTables -->
    <link rel="stylesheet" href="<?php echo base_url('assets/plugins/DataTables/datatables.min.css'); ?>">

    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url('assets/dist/css/AdminLTE.min.css'); ?>">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
             folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="<?php echo base_url('assets/dist/css/skins/_all-skins.min.css'); ?>">

    <!-- Alert Messages -->
    <link href="<?php echo base_url('assets/plugins/alert/css/alert.css'); ?>" rel="stylesheet" />
    <link href="<?php echo base_url('assets/plugins/alert/themes/default/theme.css'); ?>" rel="stylesheet" />
</head>

<style type="text/css">
    .fieldReq {
        color: #f56954;
    }

    td.details-control {
        background: url('<?php echo base_url('assets/images/datatables/details_open.png'); ?>') no-repeat center center;
        cursor: pointer;
    }

    tr.shown td.details-control {
        background: url('<?php echo base_url('assets/images/datatables/details_close.png'); ?>') no-repeat center center;
    }
</style>
<?php

$userPhoto = $user['photo'];
?>

<body class="hold-transition skin-blue-light sidebar-mini">
    <div class="row" style="background-color:#333; color:#FFF; font-size:25px;height:37px; width:101.1%; padding-left: 25px;"> <img src="<?php echo base_url('assets/images/hrms.png'); ?>" width="23" height="23"> Human Resource Management System [ Promo-NESCO ]</div>
    <div class="row" style="background-color:#090; height:5px;width:101.1%">&nbsp;</div>
    <div class="wrapper">
        <header class="main-header">
            <!-- Logo -->
            <a href="<?php echo base_url(); ?>" class="logo">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                <span class="logo-mini"></span>
                <!-- logo for regular state and mobile devices -->
                <span class="logo-lg"><img src="<?php echo base_url('assets/images/logo/nesco.jpg'); ?>" height="35px;" width="200px;"></span>
            </a>

            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top">

                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                </a>
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <!-- User Account: style can be found in dropdown.less -->
                        <?php

                        /*$menus = $this->page_model->menus($_SESSION['emp_id']);
                                foreach ($menus as $menu) {
                                    
                                    echo "<li class='user-menu'> <a href='../".$menu['userAllow']."/'>".$menu['menu_name']."</a></li>";
                                }*/
                        ?>
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <img src="http://<?php echo $_SERVER['SERVER_NAME'] . ":" . $_SERVER['SERVER_PORT'] . '/hrms/franchise/' . $userPhoto; ?>" class="user-image" alt="User Image">
                                <span class="hidden-xs"><?php echo $_SESSION['username']; ?></span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- User image -->
                                <li class="user-header">
                                    <img src="http://<?php echo $_SERVER['SERVER_NAME'] . ":" . $_SERVER['SERVER_PORT'] . '/hrms/franchise/' . $userPhoto; ?>" class="img-circle" alt="User Image">
                                    <p> <?php echo $_SESSION['name'] . " <br> " . $_SESSION['position']; ?>

                                    </p>
                                </li>
                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <a href="<?php echo base_url('placement/page/menu/employee/change_account'); ?>" class="btn btn-default btn-flat">Change Account Details</a>
                                    </div>
                                    <div class="pull-right">
                                        <a href="<?php echo base_url('logout'); ?>" class="btn btn-default btn-flat">Log out</a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>