<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="sidebar-form">
            <div class="input-group">
                <input name="searchEmployee" class="form-control" id="searchEmployee" placeholder="Search..." type="text">
                <span class="input-group-btn">
                    <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                    </button>
                </span>
            </div>
        </div>
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
            <li class="treeview">
                <a href="<?php echo base_url('recruitment'); ?>">
                    <i class="fa fa-arrow-right"></i> <span>Recruitment</span>
                </a>
            </li>
            <li class="treeview">
                <a href="<?php echo 'http://' . $_SERVER['SERVER_ADDR'] . ":" . $_SERVER['SERVER_PORT'] . '/hrms/nesco'; ?>">
                    <i class="glyphicon glyphicon-arrow-left"></i> <span>Back</span>
                </a>
            </li>
            <li class="treeview <?php if ($title == 'dashboard') : echo 'active';
                                endif; ?>">
                <a href="<?php echo base_url(); ?>">
                    <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                </a>
            </li>
            <?php

            $menus = $this->page_model->incharge_menu();
            foreach ($menus as $menu) {

                if ($menu['has_submenu'] == 0) { ?>

                    <li class="treeview <?php if ($title == $menu['route']) : echo 'active';
                                        endif; ?>">
                        <a href="<?php echo base_url('pages/menu/' . $menu['route'] . '/' . $menu['route']); ?>">
                            <i class="<?php echo $menu['icon']; ?>"></i> <span><?php echo $menu['menu']; ?></span>
                        </a>
                    </li>
                <?php

                } else { ?>

                    <li class="treeview <?php if ($title == $menu['route']) : echo 'active';
                                        endif; ?>">
                        <a href="#">
                            <i class="<?php echo $menu['icon']; ?> <?php if ($title == $menu['route']) : echo '';
                                                                    endif; ?>"></i>
                            <span><?php echo $menu['menu']; ?></span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <?php

                        $submenus = $this->page_model->incharge_submenu($menu['id']);
                        ?>
                        <ul class="treeview-menu <?php if ($page == $submenus[0]['route']) : echo 'active';
                                                    endif; ?>">
                            <?php

                            foreach ($submenus as $submenu) { ?>

                                <li class="">
                                    <a href="<?php echo base_url('placement/page/menu/' . $menu['route'] . '/' . $submenu['route']); ?>">
                                        <i class="fa fa-circle-o <?php if ($page == $submenu['route']) : echo 'text-aqua';
                                                                    endif; ?>"></i> <?php echo $submenu['sub_menu']; ?></a>
                                </li><?php
                                    }
                                        ?>
                        </ul>
                    </li><?php
                        }
                    }
                            ?>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->