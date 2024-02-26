<?php //pre($this->session->userdata['backend_user']); die; ?>

<!DOCTYPE html>
<html>

    <!-- Header -->
    <?php
    $this->load->view('segments/header');
    $this->load->view('segments/sidebar');
    ?>

    <!-- //Left side column. contains the logo and sidebar -->


    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                <?php
                if (isset($pageTitle)) {
                    echo $pageTitle;
                }
                ?>
                <small></small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Dashboard</li>
            </ol>

            <?php if (!empty($this->session->flashdata('success_message'))) { ?>
                <div class="alert alert-success">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <?php echo $this->session->flashdata('success_message'); ?>
                </div>
            <?php } ?>

        </section>
        <div class="box-footer no-border">
            <div class="row">
                <?php if($this->session->userdata['backend_user']['user_type']!=2){?>
                <div class="col-lg-4 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-aqua"> 
                        <a class="small-box-footer" id="on_going_lift_link" href="<?php echo base_url('index.php/admin_panel/Users'); ?>"> 
                            <div class="inner"> 
                                <p>Total Users </p>
                                <h3 id="on_going_lift"><b id="total_user"><?php echo $users['total_users']; ?></b></h3>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-12"></div>
                                            <div class="col-md-12"></div>
                                            <div class="col-md-12"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-12"></div>

                                            <div class="col-md-12"></div>
                                            <div class="col-md-12"></div>

                                        </div>
                                    </div> 

                                </div>
                            </div>  
                        </a>
                    </div>
                </div><!-- ./col -->
                <div class="col-lg-4 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-red">
                        <a class="small-box-footer" id="successful_lift_link" href="<?php echo base_url('index.php/admin_panel/Users'); ?>"> 
                            <div class="inner">
                                <p>Blocked Users</p>
                                <h3 id="successful_lift"><b id='total_blocked'><?php echo $users['blocked_users']; ?></b></h3>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-12"></div>
                                            <div class="col-md-12"></div>
                                            <div class="col-md-12"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-12"></div>
                                            <div class="col-md-12"></div>
                                            <div class="col-md-12"></div>
                                        </div>
                                    </div> 
                                </div>

                            </div>  
                        </a> 
                    </div>
                </div>
                <div class="col-lg-4 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-green">
                        <a class="small-box-footer" id="unsuccessful_lift_link" href="<?php echo base_url('index.php/admin_panel/Users'); ?>"> 
                            <div class="inner">
                                <p>Active Users</p>
                                <h3 id="unsuccessful_lift"><b id='total_active'><?php echo $users['active_users']; ?></b></h3>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-12"></div>
                                            <div class="col-md-12"></div>
                                            <div class="col-md-12"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-12"></div>
                                            <div class="col-md-12"></div>
                                            <div class="col-md-12"></div>
                                        </div>
                                    </div> 
                                </div>

                            </div>  
                        </a>
                    </div>
                </div><!-- ./col -->
                
                <div class="col-lg-4 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-aqua"> 
                        <a class="small-box-footer" id="on_going_lift_link" href="<?php echo base_url('index.php/admin_panel/Users/service_providers'); ?>"> 
                            <div class="inner"> 
                                <p>Total Service Providers </p>
                                <h3 id="on_going_lift"><b id="total_user"><?php echo $provider['total_provider']; ?></b></h3>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-12"></div>
                                            <div class="col-md-12"></div>
                                            <div class="col-md-12"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-12"></div>

                                            <div class="col-md-12"></div>
                                            <div class="col-md-12"></div>

                                        </div>
                                    </div> 

                                </div>
                            </div>  
                        </a>
                    </div>
                </div><!-- ./col -->
                <div class="col-lg-4 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-red">
                        <a class="small-box-footer" id="successful_lift_link" href="<?php echo base_url('index.php/admin_panel/Users/service_providers'); ?>"> 
                            <div class="inner">
                                <p>Blocked Service Providers</p>
                                <h3 id="successful_lift"><b id='total_blocked'><?php echo $provider['blocked_provider']; ?></b></h3>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-12"></div>
                                            <div class="col-md-12"></div>
                                            <div class="col-md-12"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-12"></div>
                                            <div class="col-md-12"></div>
                                            <div class="col-md-12"></div>
                                        </div>
                                    </div> 
                                </div>

                            </div>  
                        </a> 
                    </div>
                </div>
                <div class="col-lg-4 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-green">
                        <a class="small-box-footer" id="unsuccessful_lift_link" href="<?php echo base_url('index.php/admin_panel/Users/service_providers'); ?>"> 
                            <div class="inner">
                                <p>Active Service Providers</p>
                                <h3 id="unsuccessful_lift"><b id='total_active'><?php echo $provider['active_provider']; ?></b></h3>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-12"></div>
                                            <div class="col-md-12"></div>
                                            <div class="col-md-12"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-12"></div>
                                            <div class="col-md-12"></div>
                                            <div class="col-md-12"></div>
                                        </div>
                                    </div> 
                                </div>

                            </div>  
                        </a>
                    </div>
                </div><!-- ./col -->
                
                <div class="col-lg-4 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-aqua"> 
                        <a class="small-box-footer" id="on_going_lift_link" href="<?php echo base_url('index.php/admin_panel/Agents'); ?>"> 
                            <div class="inner"> 
                                <p>Total Property Agents </p>
                                <h3 id="on_going_lift"><b id="total_user"><?php echo $property_agent['total_provider']; ?></b></h3>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-12"></div>
                                            <div class="col-md-12"></div>
                                            <div class="col-md-12"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-12"></div>

                                            <div class="col-md-12"></div>
                                            <div class="col-md-12"></div>

                                        </div>
                                    </div> 

                                </div>
                            </div>  
                        </a>
                    </div>
                </div><!-- ./col -->
                <div class="col-lg-4 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-red">
                        <a class="small-box-footer" id="successful_lift_link" href="<?php echo base_url('index.php/admin_panel/Agents'); ?>"> 
                            <div class="inner">
                                <p>Blocked Property Agents</p>
                                <h3 id="successful_lift"><b id='total_blocked'><?php echo $property_agent['blocked_provider']; ?></b></h3>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-12"></div>
                                            <div class="col-md-12"></div>
                                            <div class="col-md-12"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-12"></div>
                                            <div class="col-md-12"></div>
                                            <div class="col-md-12"></div>
                                        </div>
                                    </div> 
                                </div>

                            </div>  
                        </a> 
                    </div>
                </div>
                <div class="col-lg-4 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-green">
                        <a class="small-box-footer" id="unsuccessful_lift_link" href="<?php echo base_url('index.php/admin_panel/Agents'); ?>"> 
                            <div class="inner">
                                <p>Active Property Agents</p>
                                <h3 id="unsuccessful_lift"><b id='total_active'><?php echo $property_agent['active_provider']; ?></b></h3>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-12"></div>
                                            <div class="col-md-12"></div>
                                            <div class="col-md-12"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-12"></div>
                                            <div class="col-md-12"></div>
                                            <div class="col-md-12"></div>
                                        </div>
                                    </div> 
                                </div>

                            </div>  
                        </a>
                    </div>
                </div><!-- ./col -->
                <?php } ?>
                
                <div class="col-lg-4 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-aqua"> 
                        <a class="small-box-footer" id="on_going_lift_link" href="<?php echo base_url('index.php/admin_panel/Properties/pro_list'); ?>"> 
                            <div class="inner"> 
                                <p>Total Properties </p>
                                <h3 id="on_going_lift"><b id="total_user"><?php echo $property['total']; ?></b></h3>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-12"></div>
                                            <div class="col-md-12"></div>
                                            <div class="col-md-12"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-12"></div>

                                            <div class="col-md-12"></div>
                                            <div class="col-md-12"></div>

                                        </div>
                                    </div> 

                                </div>
                            </div>  
                        </a>
                    </div>
                </div><!-- ./col -->
                <div class="col-lg-4 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-red">
                        <a class="small-box-footer" id="successful_lift_link" href="<?php echo base_url('index.php/admin_panel/Properties/pro_list'); ?>"> 
                            <div class="inner">
                                <p>Pending Properties</p>
                                <h3 id="successful_lift"><b id='total_blocked'><?php echo $property['pending']; ?></b></h3>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-12"></div>
                                            <div class="col-md-12"></div>
                                            <div class="col-md-12"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-12"></div>
                                            <div class="col-md-12"></div>
                                            <div class="col-md-12"></div>
                                        </div>
                                    </div> 
                                </div>

                            </div>  
                        </a> 
                    </div>
                </div>
                <div class="col-lg-4 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-green">
                        <a class="small-box-footer" id="unsuccessful_lift_link" href="<?php echo base_url('index.php/admin_panel/Properties/pro_list'); ?>"> 
                            <div class="inner">
                                <p>Active Properties</p>
                                <h3 id="unsuccessful_lift"><b id='total_active'><?php echo $property['active']; ?></b></h3>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-12"></div>
                                            <div class="col-md-12"></div>
                                            <div class="col-md-12"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-12"></div>
                                            <div class="col-md-12"></div>
                                            <div class="col-md-12"></div>
                                        </div>
                                    </div> 
                                </div>

                            </div>  
                        </a>
                    </div>
                </div><!-- ./col -->
                
                <div class="col-lg-4 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-aqua"> 
                        <a class="small-box-footer" id="on_going_lift_link" href="<?php echo base_url('index.php/admin_panel/Properties/pro_list'); ?>"> 
                            <div class="inner"> 
                                <p>Buy Properties </p>
                                <h3 id="on_going_lift"><b id="total_user"><?php echo $sale_type['buy']; ?></b></h3>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-12"></div>
                                            <div class="col-md-12"></div>
                                            <div class="col-md-12"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-12"></div>

                                            <div class="col-md-12"></div>
                                            <div class="col-md-12"></div>

                                        </div>
                                    </div> 

                                </div>
                            </div>  
                        </a>
                    </div>
                </div><!-- ./col -->
                <div class="col-lg-4 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-red">
                        <a class="small-box-footer" id="successful_lift_link" href="<?php echo base_url('index.php/admin_panel/Properties/pro_list'); ?>"> 
                            <div class="inner">
                                <p>Rent Properties</p>
                                <h3 id="successful_lift"><b id='total_blocked'><?php echo $sale_type['rent']; ?></b></h3>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-12"></div>
                                            <div class="col-md-12"></div>
                                            <div class="col-md-12"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row">
                                            <div class="col-md-12"></div>
                                            <div class="col-md-12"></div>
                                            <div class="col-md-12"></div>
                                        </div>
                                    </div> 
                                </div>

                            </div>  
                        </a> 
                    </div>
                </div>
                

                <!-- ./col -->
            </div><!-- /.row -->


        </div>
    </div>
    <!-- Main content -->

    <!-- Small boxes (Stat box) -->

    <!-- /.row -->
    <!-- Main row -->

    <!-- /.content-wrapper -->

    <!-- Footer -->
    <?php $this->load->view('segments/footer'); // include('segments/footer.php'); ?>
    <!--// Footer -->
    <!-- Control Sidebar -->
    <?php //include('segments/controlSidebar.php');  ?>
    <!-- /.control-sidebar -->
</div>
</div>
<!-- ./wrapper -->

<!-- jQuery 2.2.0 -->
<?php $this->load->view('segments/jquery'); //include('segments/jquery.php'); ?>
<!-- //jQuery 2.2.0 -->

<script>
//    setInterval(function () {
//        get_total_user();
//    }, 300);
</script>
<script>
//    function get_total_user() {
//        $.ajax({
//            url: '<?php //echo base_url("index.php/admin_panel/admin/get_total_user") ?>',
//            data: '',
//            dataType: 'json',
//            type: 'post',
//            success: function (data) {
//                $('#total_user').html(data.total);
//                $('#total_blocked').html(data.blocked);
//                $('#total_nonblocked').html(data.nonblocked);
//                $('#total_active').html(data.active);
//                $('#total_inactive').html(data.inactive);
//            }
//        })
//    }
</script>
</body>
</html>
