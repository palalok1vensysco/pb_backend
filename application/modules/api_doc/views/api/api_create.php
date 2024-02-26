<?php
//pre($properties); die();
?>
<!DOCTYPE html>
<html>
    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo $pageTitle; ?></title>
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
        <body class="hold-transition skin-blue sidebar-mini">

            <div class="wrapper">
                <?php
                $this->load->view('segments/header');
                $this->load->view('segments/sidebar');
                ?>

                <!-- Content Wrapper. Contains page content -->
                <div class="content-wrapper">
                    <!-- Content Header (Page header) -->
                    <section class="content-header">
                        <h1><?php echo $title; ?></h1>
                    </section>
                    <!-- Main content -->
                    <section class="content">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="box box-body">
                                    <!-- /.box-header -->
                                    <form id="api_form" name="form1" method="post" enctype="multipart/form-data" >        
                                        <?php if (isset($api_detail)) { ?>
                                            <input type="hidden" value="<?php echo $api_detail['id'] ?>" name="id" id="id" >
                                        <?php } ?>
                                        <fieldset>
                                            <div class="setting col-md-6">
                                                <div class="price">
                                                    <label><h4>API Name</h4></label>
                                                    <input  type="text" id="name" name="name" maxlength="50" placeholder="Enter API name"  class="form-control" value="<?php if(isset($api_detail)){ echo $api_detail['name'];}?>" required>
                                                </div>
                                            </div> 
                                            <div class="setting col-md-6">
                                                <div class="price">
                                                    <label><h4>Controller Name</h4></label>
                                                    <input  type="text" id="controller" name="controller" maxlength="50" placeholder="Enter Category/controller"  class="form-control" value="<?php if(isset($api_detail)){ echo $api_detail['controller'];}?>" required>
                                                </div>
                                            </div> 
                                        </fieldset> 
                                        <fieldset>
                                            <div class="setting col-md-12">
                                                <div class="price">
                                                    <label><h4>API url</h4></label>
                                                    <input  type="text" id="url" name="url" maxlength="100" placeholder="Enter API url (starting from controller)"  class="form-control" value="<?php if(isset($api_detail)){ echo $api_detail['url'];}?>" required>
                                                </div>
                                            </div> 
                                        </fieldset> 
                                            
                                        <fieldset>
                                            <div class="setting col-md-6">
                                                <div>
                                                    <label><h4>Request parameter(s)</h4></label>
                                                    <textarea class="form-control" value="" name="request" id="request" rows="10" placeholder="Please type without brackets" required><?php if(isset($api_detail)){ echo $api_detail['request'];}?></textarea>
                                                </div>
                                            </div>                                        
                                            <div class="setting col-md-6">
                                                <div>
                                                    <label><h4>Response parameter(s)</h4></label>
                                                    <textarea class="form-control" value="" name="response" id="response" rows="10" placeholder="Please type without brackets" required><?php if(isset($api_detail)){ echo $api_detail['response'];}?></textarea>
                                                </div>
                                            </div>
                                        </fieldset> 

                                        <fieldset>
                                            <div class="setting col-md-12">
                                                <div>
                                                    <label><h4>Description</h4></label>
                                                    <textarea class="form-control" value="" name="description" id="description" rows="4" placeholder="(If any)"><?php if(isset($api_detail)){ echo $api_detail['description'];}?></textarea>
                                                </div>
                                            </div>
                                        </fieldset> 
                                        
                                       <br><br>
                                       <div class="col-md-12">
                                            <button id="submit12" type="submit" class="btn btn-primary" style="width:200px;" ><?php
                                                if (isset($api_detail)) {
                                                    echo "Save";
                                                } else {
                                                    ?>Submit<?php } ?></button>
                                        </div>
                                        <br><br>
                                    </form>
                                    <!-- /.box-body -->
                                </div>
                                <!-- /.box -->
                            </div>
                            <!-- /.col -->
                        </div>
                        <!-- /.row -->
                    </section>
                    <!-- /.content-wrapper -->
                </div>

                <?php
                $this->load->view('segments/footer');
//include('include/footer.php'); 
                ?>

                <!-- ./wrapper -->

            </div>

            <!-- MODEL START--->
            <div id="myModal" class="modal fade" role="dialog">
                <div class="modal-dialog">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-body">
                            <h4 class="modal-title" id="mydiv"></h4>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Ok</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- MODEL END --->

            
</body>
</html>
<!--////////////////////////////////////
//                                    //
//      Modified by: Harish Kumar     //
//      Created On: 24feb-2018        //
//                                    //
/////////////////////////////////////-->