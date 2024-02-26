<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="Mosaddek">
        <meta name="keyword" content="FlatLab, Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
        <link rel="shortcut icon" href="<?= base_url('auth_panel_assets/img/favicon.ico') ?>">

        <title><?= CONFIG_PROJECT_NICK_NAME ?> ADMIN OTP Verification</title>

        <!-- Bootstrap core CSS auth_panel_assets -->
        <link href="<?php echo base_url(); ?>auth_panel_assets/css/bootstrap.min.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>auth_panel_assets/css/bootstrap-reset.css" rel="stylesheet">
        <!--external css-->
        <link href="<?php echo base_url(); ?>auth_panel_assets/assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
        <!-- Custom styles for this template -->
        <link href="<?php echo base_url(); ?>auth_panel_assets/css/style.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>auth_panel_assets/css/style-responsive.css" rel="stylesheet" />
        <style>
            .lock-wrapper img{
                left: 38%;
                width: 24%;
            }
            .logout{
                position: fixed;
                top: 15%;
                left: 7%;
            }
            .logout img{
                width: 60px;
            }
        </style>
        <script>
            var mobile = "<?= $this->session->userdata("active_user_data")->mobile ?>";
        </script>
    </head>

    <body class="lock-screen" onload="startTime()">

        <div class="lock-wrapper">
            <div class="logout">
                <a href="<?= AUTH_PANEL_URL . "login/logout" ?>">
                    <img src="<?= AUTH_ASSETS . "img/logout.png" ?>">
                </a>
            </div>
            <div id="time"></div>
            <div class="lock-box text-center">
                <img src="<?= filter_var($this->session->userdata("active_user_data")->profile_picture, FILTER_VALIDATE_URL) ? $this->session->userdata("active_user_data")->profile_picture : AUTH_ASSETS . "img/user_default.png" ?>" alt="lock avatar"/>
                <h1><?= $this->session->userdata("active_user_data")->username ?></h1>
                <span class="locked">You seems like that you have changed your device. Please verify your identity.</span>
                <form role="form" method="post" class="form-inline" action="">
                    <label class="mobile_verification">Confirm Your Mobile number end with- <b>******<?= substr($this->session->userdata("active_user_data")->mobile, -4) ?></b></label>
                    <div class="form-group col-lg-12">
                        <input type="text" autocomplete="off" name="mobile" placeholder="Enter Mobile Number" class="form-control mobile_verification lock-input">
                        <input type="text" autocomplete="off" name="otp" placeholder="Enter OTP" class="form-control lock-input otp_verification hide">
                        <button class="btn btn-lock" type="submit">
                            <i class="fa fa-arrow-right"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <script src="<?php echo base_url(); ?>auth_panel_assets/js/jquery.js"></script>
        <script src="<?php echo base_url(); ?>auth_panel_assets/js/sweetalert.min.js"></script>
        <script>
        function startTime() {
            var today = new Date();
            var h = today.getHours();
            var m = today.getMinutes();
            var s = today.getSeconds();
            // add a zero in front of numbers<10
            m = checkTime(m);
            s = checkTime(s);
            document.getElementById('time').innerHTML = h + ":" + m + ":" + s;
            t = setTimeout(function () {
                startTime()
            }, 500);
        }
        
        function checkTime(i) {
            if (i < 10) {
                i = "0" + i;
            }
            return i;
        }
        
        $("form").submit(function (ev) {
            
            ev.preventDefault();
            let form = $(this);
            if (form.find("input[name=mobile]").val() == "") {
                swal("Error!", "Please Enter Mobile Number!", "error");
                return false;
            }
            if (mobile != $(this).find("input").val()) {
                swal("Error!", "Please Enter Valid Mobile Number!", "error");
                return false;
            } else if ($(".otp_verification").hasClass("hide")) {
                $(".mobile_verification").addClass("hide");
                $(".otp_verification").removeClass("hide");
                $.ajax({
                    url: form.attr("action"),
                    type: "POST",
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    dataType: "json",
                    success: function (data) {
                        swal(data.title, data.message, data.type);
                    }, error: function (jqXHR, textStatus, errorThrown) {
                        show_toast("error", "Internal Error", "Invalid Server Response");
                    }
                });
                return false;
            }
            if (form.find("input[name=otp]").val().length != 6) {
                swal("Error!", "Please Enter Valid 6 Digit OTP!", "error");
                return false;
            }
            
            $.ajax({
                url: form.attr("action"),
                type: "POST",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                dataType: "json",
                success: function (data) {
                    swal(data.title, data.message, data.type);
                    if (data.data == 3) {
                        window.location.reload();
                    }
                }, error: function (jqXHR, textStatus, errorThrown) {
                    show_toast("error", "Internal Error", "Invalid Server Response");
                }
            });
        });
        </script>
    </body>
</html>
