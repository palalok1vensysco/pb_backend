<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$project_name=CONFIG_PROJECT_NICK_NAME;
$bg_color1=$bg_color='#ff9700';
$logo=base_url("auth_panel_assets/img/webapp/ott-videocrypt.png");
if(!empty($app_detail)){
    $logo=$app_detail['logo'];
    $project_name=$app_detail['title'];
    $bg_color=$app_detail['bg_color'];
    $bg_color1=$app_detail['bgone_color'];
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">
        <meta name="keyword" content="">
        <link rel="shortcut icon" href="<?=$logo ?>">

        <title><?= $project_name ?> ADMIN LOGIN</title>

        <!-- Bootstrap core CSS auth_panel_assets -->
        <link href="<?php echo base_url(); ?>auth_panel_assets/css/bootstrap.min.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>auth_panel_assets/css/bootstrap-reset.css" rel="stylesheet">
        <!--external css-->
        <link href="<?php echo base_url(); ?>auth_panel_assets/assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
        <!-- Custom styles for this template -->
        <link href="<?php echo base_url(); ?>auth_panel_assets/css/style.php" rel="stylesheet">
        <link href="<?php echo base_url(); ?>auth_panel_assets/css/style-responsive.css" rel="stylesheet" />
        <style type="text/css">
            

/*====newcss===*/

.loginBox {
    background: #ffffff;
    margin: 40px 10px 40px;
    position: relative;
    background: #fff;
    border-radius: 10px;
    border-radius: 20px;
}

.logo-left {
    border-right: 2px solid #ff9700;
    height: 552px;
    display: flex;
    justify-content: center;
    align-items: center;
}

.logo-new-tb {
    text-align: center;
}

.logo-new-tb img {
    width: 70%!important;
    text-align: center;
}

.logo-form-right .Login-title h1 {
    display: inline-block;
    color: #000!important;
    font-size: 32px;
    margin-bottom: 25px;
}

.logo-form-right {
    padding: 30px 107px 0px 107px;
    height: 552px;
}

.login-part-inner h4 {
    font-weight: bold;
    font-size: 12px;
    color: #666;
}

.login-part-inner .form-control {
    display: block;
    width: 100%;
    height: 38px;
    padding: 0.375rem 0.75rem!important;
    font-size: 1rem;
    font-weight: 400;
    line-height: 1.5!important;
    color: #495057;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
    transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
}

.footer {
    color: white;
    width: 100%;
    font-size: 11px;
    bottom: 5px;
    text-align: center;
}

.logo-newtop img {
    width: auto;
    height: 54px;
}
        </style>
        <style>
            body{
                background:<?php echo $bg_color;?>;
                #background-image: url(<?php echo base_url(); ?>auth_panel_assets/img/login_background.png);
                background-size: 100%;
            }
            .login-body {
              background-color: <?php echo $bg_color;?>;
            }
            .logo-left {
              border-right: 2px solid <?php echo $bg_color;?>;
            }

            .form-signin .btn-login {
              color: #fff !important;
              background-image: linear-gradient( 315deg , <?php echo $bg_color;?> 0%, <?php echo $bg_color1;?> 74%);
            }
            .btn{
                border: 1px solid <?php echo $bg_color;?>;
            }
            .btnRefresh{
                background-color:black;
                border:0;
                padding:7px 10px;
                color:#FFF;
                float:right;
            }
            .log_img{
                width: 140px;
                display: table;
                margin: auto;
            }



        </style>
        <!-- js placed at the end of the document so the pages load faster -->
        <script src="<?php echo base_url(); ?>auth_panel_assets/js/jquery.js"></script>
        <script src="<?php echo base_url(); ?>auth_panel_assets/js/bootstrap.min.js"></script>
    </head>

    <body class="login-body">
        <div class="container">
            <div class="row loginBox">
                <div class="col-md-6">
                    <div class="logo-left">
                         <div class="logo-new-tb"><a href="#">

                            <img src="<?= $logo;?>" alt="logo"></a></div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="logo-form-right"> 
                        <form class="form-signin" method="POST" action="<?php echo base_url('auth_panel/login/index?return='.$full_url); ?>">
                            <!-- <h2 class="form-signin-heading bold"><?= CONFIG_PROJECT_FULL_NAME ?> Classes Admin</h2> -->
                           
                            <div class="login-wrap">
                                    <div class="Login-title">
                                        <h1 style="font-weight:700">Login</h1>
                                    </div>

                                <div class="row">
                                    <div class="col-lg-12 login-part-inner">
                                    <h4>Account Email ID</h4>
                                        <input type="text" autocomplete="off" class="form-control" value="<?php echo set_value('email') ?>" name="email" placeholder="Email" id="login_username">
                                        <span class="error bold"><?php echo form_error('email'); ?></span>
                                    </div>

                                    <div class="col-lg-12 login-part-inner">
                                        <h4>Password</h4>
                                        <input type="password" autocomplete="off" class="form-control" name="password" placeholder="Password" id="login_pwd">
                                        <span class="error bold"><?php echo form_error('password'); ?></span>
                                    </div>

                                    <div class="col-lg-12 login-part-inner">
                                        <h4>Enter Captcha</h4>
                                         <input style="width: 50%;float: left;margin-bottom: 0px;" class="form-control" autocomplete="off" name="captcha" placeholder="Type here" type="text" />
                                        <div style="width: 45%;margin-left: 5%;margin-bottom: 6px;float: left;">
                                            <button type="button" class="btnRefresh" onClick="refreshCaptcha();"><i class="fa fa-spinner"></i></button>
                                            <img id="captcha_code" src="<?= base_url() . "auth_panel/login/captcha"; ?>" />
                                        </div>
                                        <span>*Enter the text as shown in image</span>
                                        <span class="error bold"><?= (isset($error)) ? $error : "" ?></span>
                                    </div>

                                    <div class="col-lg-12 login-part-inner">
                                         <button class="btn btn-lg btn-login btn-block" type="submit">Sign in</button>
                                    </div>

                                </div>
                                
                               
                              
                                
                              
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <footer>
        <p class="footer">Copyright ©<?=date('Y')." ".$project_name; ?></p>    
    </footer>
        <script>
            function refreshCaptcha() {
                $("#captcha_code").attr('src', $("#captcha_code").attr('src'));
            }
        </script>
</html>
