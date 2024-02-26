<?php //pre($api_all); die;   ?>
<!DOCTYPE html>
<html>
    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo $title; ?></title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.6 -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bootstrap/css/bootstrap.min.css">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
        <!-- Ionicons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/AdminLTE.min.css">
        <!-- AdminLTE Skins. Choose a skin from the css/skins
             folder instead of downloading all of them to reduce the load. -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/skins/_all-skins.min.css">
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        
        <style>
            .txtarea{ display:none;}
            </style>
        </head>
        <body>
    <?php
    $this->load->view('segments/header');
    $this->load->view('segments/sidebar');
    ?>
    <style>
        .no-hover:hover{
            cursor:default;
        }
    </style>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" style="z-index: 1">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1><?php echo $title; ?></h1>
            <?php if(isset($this->session->userdata['profile'])) { ?>
            <div class="breadcrumb"><a title="Create new API" href="<?php echo base_url('index.php/api_doc/Api/create'); ?>" class="btn btn-success"> + </a></div><br>  
            <?php } ?>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <!-- /.box-header -->
                        <div class="box-body">
                            <table id="example2" class="table table-bordered table-hover">
                                <thead>
                                    <tr class="hello" >
                                        <th>#</th>
                                        <th>API name</th>
<!--                                        <th>Category</th>-->
                                        <th>Url</th>
                                        <?php if(!isset($opt_for_dlt)){ ?>
                                        <th><center>Action</center></th>
                                        <?php } if(isset($this->session->userdata['profile'])) { ?>
                                        <th></th>
                                        <?php } ?>
                                </tr>
                                </thead>

                                <tbody class="menu_change">
                                    <?php $i= 0; if (!empty($api_all)) {  
                                        foreach ($api_all as $list) {
                                            $i++;
                                            ?>
                                            <tr class="record even gradeA">
                                                <td><?php echo $i; ?></td>
                                                <td><?php echo ucfirst($list['name']); ?></td>
<!--                                                <td><?php //echo ucfirst($list['controller']); ?></td>-->
                                                <td style="overflow:hidden"><?php echo $list['url']; ?></td>
                                                <?php if(!isset($opt_for_dlt)){ ?>
                                                <td style="width:20%; text-align: center;">
                                                    <a href="<?php echo site_url('api_doc/Api/doc/').$list['id']; ?>" class="btn btn-sm btn-success userid"><i class="fa fa-eye"></i> View</a>
                                                        <?php if(isset($this->session->userdata['profile'])) { ?>
                                                    <a href="<?php echo site_url('api_doc/Api/create/').$list['id']; ?>" class="btn btn-sm btn-warning"><i class="fa fa-edit "></i> Edit</a> 
                                                    <a href="<?php echo site_url('api_doc/Api/copy/').$list['id']; ?>" class="btn btn-sm btn-primary"><i class="fa fa-copy "></i> Copy</a> 
                                                    <?php } ?>
                                                </td>
                                                
                                                <?php } if(isset($opt_for_dlt)){ if(isset($this->session->userdata['profile'])) { ?>
                                                <td>
                                                    <a href="<?php echo site_url('api_doc/Api/delete_api_perma/').$list['id']; ?>" class="btn btn-sm btn-warning"><i class="fa fa-trash "></i> Delete</a> 
                                                    <a href="<?php echo site_url('api_doc/Api/change_status/').$list['id']; ?>" class="btn btn-sm btn-primary"><i class="fa fa-check-circle "></i> Restore</a> 
                                                </td>    
                                                <?php }}  else {  ?>
                                                <?php if(isset($this->session->userdata['profile'])) { ?>
                                                <td><a href="<?php echo site_url('api_doc/Api/change_status/').$list['id']; ?>" class="btn btn-sm btn-danger"><i class="fa fa-trash "></i></a> </td>
                                                <?php } }  ?>
                                                
                                            </tr>
                                            
                                        <?php }} ?>
                                </tbody>
                            </table>

                            <center><div id="text-center" ></div></center>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </section>                
    </div>
    <!-- /.row -->
    <div style=" width: 100%; " id="menu_pop_up"></div>
    <!-- /.content-wrapper -->

<?php $this->load->view('segments/footer'); ?>
    <script src="<?php echo base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/datatables/dataTables.bootstrap.min.js"></script>
    <script>
                                                        $(document).ready(function () {
                                                            $('#example2').DataTable({
                                                                //"scrollX": true
                                                                // serverSide: true,
                                                                //ajax: '/data-source'
                                                            });
                                                        });
    </script>

    <div id="getmodal">
    </div>
</body>
<style>
    .tble-width{
        width:200px !important;
        height:20px !important;
        overflow:hidden !important;
    }
</style>



<script>
    $(document).ready(function () {
        $(".prop_status").click(function () {

            if (confirm("Are you sure ?") == true) {


                var id = $(this).attr("data-id");
                var eid = btoa(id);
                $.ajax({
                    url: "<?php echo base_url('index.php/admin_panel/') ?>Properties/change_status_ajax",
                    method: 'GET',
                    data: {
                        id: eid
                    },
                    success: function (response) {  //alert(response); 
                        $("#status_" + id).empty();
                        $("#status_" + id).html(response);
                    }
                });

            }
        });
    });

    $(document).ready(function () {
        $(".sold_status").click(function () {


            if (confirm("Are you sure ?") == true) {
                var id = $(this).attr("id");
                var eid = btoa(id);
                $.ajax({
                    url: "<?php echo base_url('index.php/admin_panel/') ?>Properties/change_sold_status_ajax",
                    method: 'GET',
                    data: {
                        id: eid
                    },
                    success: function (response) { //alert(response); 
                        $("#" + id).empty();
                        $("#" + id).html(response);
                    }

                });
            }


        });
    });
</script>
</html>
<!--////////////////////////////////////
//                                    //
//      Modified by: Shashank Mishra //
//      Created On : 20 FEB 2021      //
//                                    //
/////////////////////////////////////-->