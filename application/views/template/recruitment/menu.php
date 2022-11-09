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
				<a href="<?php echo 'http://' . $_SERVER['SERVER_ADDR'] . ":" . $_SERVER['SERVER_PORT'] . '/hrms/promo-nesco/placement/'; ?>">
					<i class="fa fa-arrow-left"></i> <span>Placement</span>
				</a>
			</li>
			
			<li class="treeview">
				<a href="<?php echo base_url('recruitment/page/menu/initial/initial'); ?>">
					<i class="fa fa-arrow-right"></i> <span>Initial Completion</span>
					<!--i class="fa fa-angle-left pull-right"></i-->
				</a>
				
				
			</li>
			
			<li class="treeview">
				 <a href="<?php echo base_url('recruitment/page/menu/initial/record'); ?>">
					<i class="fa fa-arrow-right"></i> <span>Record Applicants</span>
				</a>
			</li>
			
			
			<li class="treeview">
				<a href="<?php echo base_url('recruitment/page/menu/initial/examination'); ?>">
					<i class="fa fa-arrow-right"></i> <span>Examination</span>
				</a>
			</li>
			
			<li class="treeview">
				<a href="<?php echo base_url('recruitment/page/menu/initial/interview'); ?>">
					<i class="fa fa-arrow-right"></i> <span>Interview</span>
				</a>
			</li>
			
			<li class="treeview">
				<a href="<?php echo base_url('recruitment/page/menu/initial/finalcompletion'); ?>">
					<i class="fa fa-arrow-right"></i> <span>Final Completion</span>
				</a>
			</li>
			
			<li class="treeview">
				<a href="<?php echo base_url('recruitment/page/menu/initial/hiring'); ?>">
					<i class="fa fa-arrow-right"></i> <span>Hiring</span>
				</a>
			</li>
			
			<li class="treeview">
				<a href="<?php echo base_url('recruitment/page/menu/initial/deploy'); ?>">
					<i class="fa fa-arrow-right"></i> <span>Deploy</span>
				</a>
			</li>
			
			<!--li class="treeview">
				<a href="<?php //echo base_url('recruitment/page/menu/initial/transfer'); ?>">
					<i class="fa fa-arrow-right"></i> <span>Transfer</span>
				</a>
			</li>
			
			<li class="treeview">
				<a href="<?php //echo base_url('recruitment/page/menu/initial/transfer'); ?>">
					<i class="fa fa-arrow-right"></i> <span>Transfer</span>
				</a>
			</li-->
			
			<li class="treeview">
				<a href="<?php echo base_url('recruitment/page/menu/initial/hold'); ?>">
					<i class="fa fa-arrow-right"></i> <span>Hold Applicants</span>
				</a>
			</li>
			
		</ul>
    </section>
    <!-- /.sidebar -->
</aside>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->