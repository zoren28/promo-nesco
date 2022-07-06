<section class="content-header">
    <h1>
        User Accounts
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">User Accounts</a></li>
        <li class="active">User Access</li>
    </ol>
</section>
<section class="content">

    <!-- Default box -->
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">User Access</h3>
        </div>

        <div class="box-body">
            <table id="" class="table table-striped table-hover table1">
                <thead>
                    <tr>
                        <th>Menu</th>
                        <th>Sub-menu</th>
                        <th>Admin</th>
                        <th>Promo Incharge</th>
                        <th>Encoder</th>
                        <th>NESCO Incharge</th>
                    </tr>
                </thead>
                <tbody>
                    <?php

                    $menus = $this->page_model->incharge_menu();
                    foreach ($menus as $menu) {

                        $menu_id = $menu['id'];
                        echo '
                            <tr>
                                <td>' . $menu['menu'] . '</td>
                                <td></td>
                                <td>
                                    <label class="btn btn-success btn-xs btn-block btn-flat">yes</label>
                                </td>';

                        $users = array('promo1', 'promo2', 'nesco');
                        $options = array(1 => 'yes', 0 => 'no');
                        foreach ($users as $key => $user) {

                            echo "<td>
                                <select onchange='userAccess(this.value, \"$user\", \"promo_placement_menu\", \"$menu_id\")'>";
                            foreach ($options as $key => $value) {
                                if ($key == $menu[$user]) {
                                    echo '<option value="' . $key . '" selected>' . $value . '</option>';
                                } else {
                                    echo '<option value="' . $key . '">' . $value . '</option>';
                                }
                            }
                            echo '</select>
                            </td>';
                        }
                        echo '</tr>';

                        $submenus = $this->page_model->incharge_submenu($menu['id']);
                        foreach ($submenus as $submenu) {

                            $submenu_id = $submenu['id'];
                            echo '
                                <tr>
                                    <td></td>
                                    <td>' . $submenu['sub_menu'] . '</td>
                                    <td>
                                        <label class="btn btn-success btn-xs btn-block btn-flat">yes</label>
                                    </td>';

                            $users = array('promo1', 'promo2', 'nesco');
                            $options = array(1 => 'yes', 0 => 'no');
                            foreach ($users as $key => $user) {

                                echo "<td>
                                <select onchange='userAccess(this, promo_placement_submenu, \"$submenu_id\")'>";
                                foreach ($options as $key => $value) {
                                    if ($key == $submenu[$user]) {
                                        echo '<option value="' . $value . '" selected>' . $value . '</option>';
                                    } else {
                                        echo '<option value="' . $value . '">' . $value . '</option>';
                                    }
                                }
                                echo '</select>
                            </td>';
                            }
                            echo '</tr>';
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>

    </div>
</section>