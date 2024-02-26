<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel"> </div>
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
            <!-- <li class="header">MAIN NAVIGATION</li> -->
            <li class="treeview">
                <a href="<?php echo base_url(); ?>index.php/api_doc/Api">
                    <i class="fa fa-dashboard"></i><span>Dashboard</span> <span class="pull-right-container"></span>
                </a>
            </li> 
            
           
           <?php
           foreach($controllers as $controller) { ?>
            
            <li class="treeview">
                <a href="<?php //echo base_url(''); ?>">
                    <i class="fa fa-bars"></i> <span><?php echo ucfirst($controller[0]['controller']); ?></span> <span class="pull-right-container"></span>
                </a>
                <ul class="treeview-menu">
                    <?php foreach ($controller as $api) {?>
                    <li>
                        <a href="<?php echo base_url('index.php/api_doc/Api/doc/').$api['id']; ?>"><i class="fa fa-arrow-circle-right"></i><?php echo ucfirst($api['name'])?></a>
                    </li>
                    <?php } ?>
                </ul>
            </li>
           
           <?php } ?>
           <li class="treeview">
                <a href="<?php echo base_url(); ?>index.php/api_doc/Api/api_list">
                    <i class="fa fa-list-ul"></i><span>All API List</span> <span class="pull-right-container"></span>
                </a>
            </li> <?php //pre($this->session->userdata)?>
            <?php if(isset($this->session->userdata['profile'])) { ?>
            <li class="treeview">
                <a href="<?php echo base_url(); ?>index.php/api_doc/Api/deleted_api_list">
                    <i class="fa fa-trash"></i><span>Deleted API</span> <span class="pull-right-container"></span>
                </a>
            </li> 
            <?php } ?>

        </ul>
    </section>
    <!-- /.sidebar -->
</aside>
