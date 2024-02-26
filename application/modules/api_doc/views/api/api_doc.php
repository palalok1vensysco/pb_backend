<?php
//pre($api_detail['request']); die();
$api_base_url = base_url('index.php/api_model/');
?>
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
            .same_print{width: 85%;
                        color: #ffe55b;
                        background-color: transparent !important;
                        border: none;
                        font-size: 15px;
            }
            .api-heading{
                color: #de8322;
                font-stretch: extra-expanded;
                font-family: monospace;
                font-weight: 400;

            }
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
                <section class="content">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="box box-body">
                                <!-- box-body -->
                                <?php if (!empty($api_detail)) { ?>
                                    <section>
                                        <div class="col-sm-12" style="border-bottom:solid thin white; margin-bottom:20px;">
                                            <div class="row">
                                                <center style="position:relative;"><h3 class="api-heading"><?php echo ucfirst($api_detail['name']); ?></h3></center>
                                                <?php if(isset($this->session->userdata['profile']) && $this->session->userdata['profile'] == 1) { ?>
                                                <a style="position:absolute; top: 5px; right:0" href="<?php echo site_url('api_doc/Api/create/') . $api_detail['id']; ?>" class="btn btn-success"><i class="fa fa-edit "></i> Edit</a> 
                                                <a style="position:absolute; top: 5px; left:0" href="<?php echo site_url('api_doc/Api/copy/').$api_detail['id']; ?>" class="btn btn-primary"><i class="fa fa-copy "></i> Copy</a> 
                                                <?php } ?>
                                             </div>
                                        </div>
                                    </section>
                                    <section>
                                        <center><div class="col-sm-offset-1 col-sm-10" style="background-color:white; border-top:solid 2px black; border-bottom:solid 2px black; color: black; padding: 15px 0; padding-left: 3%; margin-bottom: 10px; border-radius: 8px;">
                                            <div class="row">
                                                <h4><span><var><a style="color:black; font-size: 20px;" href="<?php echo $api_detail['url']; ?>" target="_blank"><?php echo $api_detail['url']; ?></a></var></span></h4>
                                            </div>
                                        </div></center>
                                    </section> 
                                    <section>
                                        <div class="col-sm-12" style="background-color:#540000; color: white; padding: 15px 0; padding-left: 3%;  margin-bottom: 10px; border-radius: 8px;" >
                                            <div class="row">
                                                <h4>REQUEST :</h4>
                                                <div class="col-sm-offset-1">{</div>
                                                <?php $request = implode(",<br>", explode(",", $api_detail['request'])); ?>
                                                <div style="padding-left:10%"><pre class="same_print"><?php echo $api_detail['request']; ?></pre></div>
                                                <div class="col-sm-offset-1">}</div>
                                            </div>
                                        </div>
                                    </section> 
                                    <section>
                                        <div class="col-sm-12" style="background-color:#130f0f; color: white; padding: 15px 0; padding-left: 3%; margin-bottom: 10px; border-radius: 8px;" >
                                            <div class="row">
                                                <h4>RESPONSE :</h4>
                                                <div class="col-sm-offset-1">{</div>
                                                <?php $response = implode(",<br>", explode(",", $api_detail['response'])); ?>
                                                <div style="padding-left:10%"><pre class="same_print"><?php echo $api_detail['response']; ?></pre></div>
                                                    <div class="col-sm-offset-1">}</div>
                                            </div>
                                        </div>
                                    </section>
                                    <?php if (!empty($api_detail['description'])) { ?>
                                        <section>
                                            <div class="col-sm-12" style="background-color:#213031; color: white; padding: 15px 0; padding-left: 3%; margin-bottom: 10px; border-radius: 8px;" >
                                                <div class="row">
                                                    <h5>DESCRIPTION :</h5>
                                                    <p><?php echo $api_detail['description']; ?></p>
                                                </div>
                                            </div>
                                        </section>
                                    <?php }
                                } else {
                                    echo "<h3>No detail founf for this API.</h3>";
                                } ?>
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
