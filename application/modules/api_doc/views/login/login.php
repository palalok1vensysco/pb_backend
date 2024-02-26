<?php defined('BASEPATH') OR exit('No direct script access allowed');

 //echo $AUTH_PANEL_URL; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Mosaddek">
    <meta name="keyword" content="FlatLab, Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
    <link rel="shortcut icon" href="<?=base_url('assets/logo_fav.png')?>">

    <title>PRASAR BHARTI ADMIN LOGIN</title>

    <!-- Bootstrap core CSS auth_panel_assets -->
    <link href="<?php echo base_url();?>auth_panel_assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url();?>auth_panel_assets/css/bootstrap-reset.css" rel="stylesheet">
    <!--external css-->
    <link href="<?php echo base_url();?>auth_panel_assets/assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <!-- Custom styles for this template -->
    <link href="<?php echo base_url();?>auth_panel_assets/css/style.css" rel="stylesheet">
    <link href="<?php echo base_url();?>auth_panel_assets/css/style-responsive.css" rel="stylesheet" />

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
    <!--[if lt IE 9]>
    <script src="<?php echo base_url();?>auth_panel_assets/js/html5shiv.js"></script>
    <script src="<?php echo base_url();?>auth_panel_assets/js/respond.min.js"></script>
    <![endif]-->
    <style>
/*    body {
        background: url(<?php echo base_url();?>auth_panel_assets/img/landing-users.png) ;
        webkit-animation: sidedownscroll 30s linear infinite;
        animation: sidedownscroll 30s linear infinite;
      }*/
      body{
            background: #ffffff;
            -webkit-animation: 30s linear 0s normal none infinite animate;
            -moz-animation: 30s linear 0s normal none infinite animate;
            -ms-animation: 30s linear 0s normal none infinite animate;
            -o-animation: 30s linear 0s normal none infinite animate;
            animation: 30s linear 0s normal none infinite animate;
         
          }  
         
        @-webkit-keyframes animate {
            from {background-position:0 0;}
            to {background-position: 1000px 0;}
        }
         
        @-moz-keyframes animate {
            from {background-position:0 0;}
            to {background-position: 1000px 0;}
        }
         
        @-ms-keyframes animate {
            from {background-position:0 0;}
            to {background-position: 1000px 0;}
        }
         
        @-o-keyframes animate {
            from {background-position:0 0;}
            to {background-position: 1000px 0;}
        }
         
        @keyframes animate {
            from {background-position:0 0;}
            to {background-position: 1000px 0;}
        }
    </style>
</head>

  <body class="login-body">
    <div class="container">
      <form class="form-signin" method="POST">
          <img src="https://d3t441od1ekxi5.cloudfront.net/53/admin_v1/test_management/question_bank/13511040_durdarsan_logo_white.png" alt="" class="" style="width:100%">
        <div class="login-wrap">
			<span class="error bold"><?php if(isset($error)){echo $error;}?></span>
            <input type="text" class="form-control" value="<?php echo set_value('username')?>" name="username" placeholder="Email" id="login_username">
			 <span class="error bold"><?php echo form_error('email');?></span>
            <input type="password" class="form-control" name="password" placeholder="Password" id="login_pwd">
			 <span class="error bold"><?php echo form_error('password');?></span>
            <label class="checkbox col-md-12">
                <!--<input type="checkbox" value="remember-me"> Remember me-->
                <span class="pull-right bold hide">
                    <a data-toggle="modal" href="#myModal"> Forgot Password?</a>
                </span>

            </label>
            <label class="checkbox col-md-12">

<!--                <span class="pull-right bold">
                    <a href="<?php echo site_url('auth_panel/registration/index');?>">Register as Instructor</a>
                </span>-->
            </label>
            <button class="btn btn-lg btn-login btn-block" type="submit">Sign in</button>
        </div>

      </form>

    </div>

<!-- ################### Forget password of admin pop up  model ################################-->

   <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                    <h4 class="modal-title">Forgot Password ?</h4>
                </div>
            <div class="modal-body">
                    <span id="validate_message"></span>
                    <p>Enter your e-mail address below to reset your password.</p>
                    <input type="text" class="form-control placeholder-no-fix" autocomplete="off" placeholder="Email" name="email" id="email">

                    <div id="change_password" class="hide">

                    </div>

            </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                  <button type="button" class="btn btn-success submit_form">Submit</button>
                </div>
            </div>
        </div>
    </div>


    <!-- js placed at the end of the document so the pages load faster -->
    <script src="<?php echo base_url();?>auth_panel_assets/js/jquery.js"></script>
    <script src="<?php echo base_url();?>auth_panel_assets/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.submit_form').click(function() {
                var post_type = $('#post_type').val();
                var data = '';
                if(post_type == 'change_pwd') {
                     data ={'email':$('#email').val(),'tokken':$('#tokken').val(),'new_pwd':$('#new_pwd').val(),'cnf_pwd':$('#cnf_pwd').val(),'post_type':$('#post_type').val()};

                } else {
                    data ={'email':$('#email').val()};
                }

                jQuery.ajax({
                        url: '<?php echo base_url('index.php/auth_panel/login/forget_password'); ?>',
                        method: 'POST',
                        dataType: 'json',
                        data: data,
                        success: function (data) {
                            if(data.status) {
                                if(data.post_type == '') {
                                $('#validate_message').css('color','green');
                                $('#validate_message').text(data.message);
                                $('#change_password').removeClass('hide');
                                $('#change_password').html('<p>Enter OTP</p><input autocomplete=off class="form-control placeholder-no-fix"id=tokken name=tokken placeholder="Enter OTP"><p>Enter new password</p><input autocomplete=off class="form-control placeholder-no-fix"id=new_pwd name=new_pwd placeholder="Enter New Password"><p>Enter confirm password</p><input autocomplete=off class="form-control placeholder-no-fix"id=cnf_pwd name=cnf_pwd placeholder="Enter Confirm Password"> <input autocomplete=off class="form-control placeholder-no-fix"id=post_type name=post_type placeholder=""type=hidden value=change_pwd>');
                                } else {
                                    $('#validate_message').css('color','green');
                                    $('#validate_message').html(data.message);
                                    $('#myModal input').val('');
                                }
                            } else {
                                $('#validate_message').css('color','red');
                                $('#validate_message').text(data.message);
                            }

                        }
                    });
            })
        })
    </script>


  </body>
</html>
