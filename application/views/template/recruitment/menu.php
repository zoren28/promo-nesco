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
					<?php 
						if($page == 'initial') 
						{	echo "<i class='fa fa-arrow-right' style='color:#3399cc;'></i>
							<span style='color:#3399cc;'><span><u>Initial Completion 
							<img src='http://172.16.43.134:81/hrms//images/gear.gif' style='width:20px; hieght:20px;'></u></span></span>";	}
						else
						{	echo "<i class='fa fa-arrow-right'></i><span><span>Initial Completion</span></span>";	}
					?>   
					
				</a>
				
				
			</li>
			
			<li class="treeview">
				 <a href="<?php echo base_url('recruitment/page/menu/initial/record'); ?>">
					<?php 
						if($page == 'record') 
						{	echo "<i class='fa fa-arrow-right' style='color:#3399cc;'></i>
							<span style='color:#3399cc;'><u>Record Applicants 
							<img src='http://172.16.43.134:81/hrms//images/gear.gif' style='width:20px; hieght:20px;'>
							</u></span>";	}
						else
						{	echo "<i class='fa fa-arrow-right'></i><span>Record Applicants</span>";	}
					?>  
				</a>
			</li>
			
			
			<li class="treeview">
				<a href="<?php echo base_url('recruitment/page/menu/initial/examination'); ?>">
					<?php 
						if($page == 'examination') 
						{	echo "<i class='fa fa-arrow-right' style='color:#3399cc;'></i>
							<span style='color:#3399cc;'><u>Examination 
							<img src='http://172.16.43.134:81/hrms//images/gear.gif' style='width:20px; hieght:20px;'>
							</u></span>";	}
						else
						{	echo "<i class='fa fa-arrow-right'></i><span>Examination</span>";	}
					?>  
				</a>
			</li>
			
			<li class="treeview">
				<a href="<?php echo base_url('recruitment/page/menu/initial/interview'); ?>">
					<?php 
						if($page == 'interview') 
						{	echo "<i class='fa fa-arrow-right' style='color:#3399cc;'></i>
							<span style='color:#3399cc;'><u>Interview 
							<img src='http://172.16.43.134:81/hrms//images/gear.gif' style='width:20px; hieght:20px;'>
							</u></span>";	}
						else
						{	echo "<i class='fa fa-arrow-right'></i><span>Interview</span>";	}
					?> 
				</a>
			</li>
			
			<li class="treeview">
				<a href="<?php echo base_url('recruitment/page/menu/initial/finalcompletion'); ?>">
					<?php 
						if($page == 'finalcompletion') 
						{	echo "<i class='fa fa-arrow-right' style='color:#3399cc;'></i>
							<span style='color:#3399cc;'><u>Final Completion 
							<img src='http://172.16.43.134:81/hrms//images/gear.gif' style='width:20px; hieght:20px;'>
							</u></span>";	}
						else
						{	echo "<i class='fa fa-arrow-right'></i><span>Final Completion</span>";	}
					?>
				</a>
			</li>
			
			<li class="treeview">
				<a href="<?php echo base_url('recruitment/page/menu/initial/hiring'); ?>">
					<?php 
						if($page == 'hiring') 
						{	echo "<i class='fa fa-arrow-right' style='color:#3399cc;'></i>
							<span style='color:#3399cc;'><u>Hiring 
							<img src='http://172.16.43.134:81/hrms//images/gear.gif' style='width:20px; hieght:20px;'>
							</u></span>";	}
						else
						{	echo "<i class='fa fa-arrow-right'></i><span>Hiring</span>";	}
					?>
				</a>
			</li>
			

			<li class="treeview">
				<a href="<?php echo base_url('recruitment/page/menu/initial/new_employee'); ?>">
					<?php 
						
						if($page == 'new_employee') 
						{	echo "<i class='fa fa-arrow-right' style='color:#3399cc;'></i>
							<span style='color:#3399cc;'><u>New Employee
							<img src='http://172.16.43.134:81/hrms//images/gear.gif' style='width:20px; hieght:20px;'>
							</u></span>";	}
						else
						{	echo "<i class='fa fa-arrow-right'></i><span>New Employee</span>";	}
					?>
				</a>
			</li>


			<li class="treeview">
				<a href="<?php echo base_url('recruitment/page/menu/initial/deploy'); ?>">
					<?php 
						if($page == 'deploy') 
						{	echo "<i class='fa fa-arrow-right' style='color:#3399cc;'></i>
							<span style='color:#3399cc;'><u>Deployed 
							<img src='http://172.16.43.134:81/hrms//images/gear.gif' style='width:20px; hieght:20px;'>
							</u></span>";	}
						else
						{	echo "<i class='fa fa-arrow-right'></i><span>Deployed</span>";	}
					?>
				</a>
			</li>
			
			<!--li class="treeview">
				<a href="<?php //echo base_url('recruitment/page/menu/initial/transfer'); ?>">
					<i class="fa fa-arrow-right"></i> <span>Transfer</span>
				</a>
			</li-->
			
			<!--li class="treeview">
				<a href="<?php //0echo base_url('recruitment/page/menu/initial/transfer'); ?>">
					<i class="fa fa-arrow-right"></i> <span>Transfer</span>
					<ul class="treeview-menu">
							<li class="">
                                <a href="">
                                	<i class="fa fa-arrow-right"></i> 
								</a>
                            </li>
                    </ul>
				</a>
			</li-->
			
			<li class="treeview">
				<a href="<?php echo base_url('recruitment/page/menu/initial/hold'); ?>">
					<?php 
						if($page == 'hold') 
						{	echo "<i class='fa fa-arrow-right' style='color:#3399cc;'></i>
							<span style='color:#3399cc;'><u>Hold Applicants 
							<img src='http://172.16.43.134:81/hrms//images/gear.gif' style='width:20px; hieght:20px;'>
							</u></span>";	}
						else
						{	echo "<i class='fa fa-arrow-right'></i><span>Hold Applicants</span>";	}
					?> 
				</a>
			</li>
			
		</ul>
    </section>
    <!-- /.sidebar -->
</aside>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->