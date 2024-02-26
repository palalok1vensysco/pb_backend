<!DOCTYPE html>
<?php $app_data = $this->session->userdata("active_app_data");
//print_r($app_data);
$project_name = CONFIG_PROJECT_NICK_NAME;
$primarycolor = '#ff9700';
$primarycolorhov = '#e35802';
$secondarycolor = "#2a3542";
$textprimarycolor = "#3a3b45";
$logo = base_url("auth_panel_assets/img/webapp/logo.jpg");
if (!empty($app_data)) {
    $logo = $app_data->app_logo;
    $project_name = $app_data->app_name;
    $primarycolor = $app_data->bg_color;
    $secondarycolor = $app_data->bgone_color;
    $textprimarycolor = $app_data->font_color;
}
//pre($_SESSION);die;

?><html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="shortcut icon" href="<?= base_url('auth_panel_assets/img/favicon.ico') ?>">

    <title><?= (!empty($app_data) && $app_data->app_logo != "") ? $app_data->app_name : CONFIG_PROJECT_NICK_NAME ?> </title>

    <link rel="stylesheet" type="text/css" href="<?php echo AUTH_ASSETS; ?>assets/bootstrap-wysihtml5/bootstrap-wysihtml5.css" />
    <!-- Bootstrap core CSS -->
    <link href="<?php echo AUTH_ASSETS; ?>css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo AUTH_ASSETS; ?>css/timedropper.css" rel="stylesheet">
    <link href="<?php echo AUTH_ASSETS; ?>css/bootstrap-reset.css" rel="stylesheet">
    <!-- <link rel="stylesheet" type="text/css" href="<?php //echo AUTH_ASSETS;  
                                                        ?>assets/bootstrap-datepicker/css/datepicker.css" /> -->

    <!--external css-->
    <link href="<?php echo AUTH_ASSETS; ?>assets/font-awesome/css/font-awesome.css" rel="stylesheet" />

    <link href="<?php echo AUTH_ASSETS; ?>assets/fancybox/source/jquery.fancybox.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="<?php echo AUTH_ASSETS; ?>css/gallery.css" />
    <!--right slidebar-->
    <link href="<?php echo AUTH_ASSETS ?>css/slidebars.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?php echo AUTH_ASSETS; ?>css/style.css" rel="stylesheet">
    <link href="<?php echo AUTH_ASSETS; ?>css/style-responsive.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet" />


    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="<?php echo AUTH_ASSETS; ?>assets/bootstrap-datepicker/css/datepicker.css">

    <!-- date time picker css -->
    <link rel="stylesheet" type="text/css" href="<?php echo AUTH_ASSETS; ?>assets/bootstrap-datetimepicker/css/datetimepicker.css" />

    <!-- file upload css -->
    <link rel="stylesheet" type="text/css" href="<?php echo AUTH_ASSETS; ?>assets/bootstrap-fileupload/bootstrap-fileupload.css" />


    <!-- Time picker css -->
    <link rel="stylesheet" type="text/css" href="<?php echo AUTH_ASSETS; ?>assets/bootstrap-timepicker/compiled/timepicker.css" />

    <!--toastr-->
    <link href="<?php echo AUTH_ASSETS; ?>assets/toastr-master/toastr.css" rel="stylesheet" type="text/css" />
    
    <!--custom css-->
    <link href="<?php echo AUTH_ASSETS; ?>css/pb-custom.css" rel="stylesheet" type="text/css" />


    <script src="<?php echo base_url('auth_panel_assets/js/jquery.js'); ?>"></script>

    

</head>

<body>
    <div id="overlay">
        <div>
            <section><span></span><br>
                <i class='fa fa-refresh fa-spin fa-5x'></i>
            </section>
        </div>
    </div>
    <section id="container">
        <!--header start-->
        <header class="header dark-bg">
            <div class="sidebar-toggle-box">
                <div data-original-title="Toggle Navigation" data-placement="right" class="fa fa-bars tooltips"></div>
            </div>
            <a href="<?php echo site_url('auth_panel/admin/index'); ?>" class="logo"><?php echo CONFIG_PROJECT_GLOBAL_NAME; ?></a>
            <!--logo end-->

            <div class="nav notify-row" id="top_menu" style="position: relative;">
                <?php
                if(1 == 0){
                $active_data = $this->session->get_userdata();
                if ($this->session->userdata('lang_id')) {
                    $this->db->select("id,title");
                    $this->db->order_by("title", "asc");
                    $this->db->where("status", 0);
                    $languages = $this->db->get("languages")->result_array();
                ?>
                    <div class="panel-body panel-select" style="position:absolute;">
                        <select class="form-control input-sm app_id  global_dropdown" id="select_admin">
                            <option value="">Global</option>

                            <?php
                            $lang_id = $this->session->userdata('temp_lang_id') ??   $this->session->userdata('lang_id');
                            foreach ($languages as $key => $language) {
                            ?>
                                <option value="<?= $language['id'] ?>" <?= $lang_id == $language['id'] ? "selected" : "" ?>><?= ucfirst(strtolower($language['title'])) ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                    <script type="text/javascript" language="javascript">
                        $(document).ready(function() {
                            $('.app_id').change(function() {
                                let val = $(this).val();
                                let text = $(this).children("option:selected").text();
                                var param = 1;
                                if (val == "") {
                                    param = 0;
                                }
                                $.ajax({
                                    url: "<?= AUTH_PANEL_URL ?>admin/temp_app_enter_exit/" + param,
                                    data: {
                                        id: val,
                                        text: text
                                    },
                                    dataType: 'json',
                                    method: 'POST',
                                    success: function(data) {
                                        if (data.data == 1) {
                                            window.location.reload();
                                        }
                                    }
                                });
                            });
                        });
                    </script>
                <?php } } ?>
            </div>
            <div class="top-nav ">
                <ul class="nav pull-right top-menu">
                    <li class="dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle dropdown_ttgl" href="#">
                            <?php
                            $user_data = $this->session->userdata('active_user_data');
                            if ($user_data->profile_picture != "") {
                                echo "<img class='img-thumbnail' width='30px' src='" . $user_data->profile_picture . "' />";
                            } else {
                                echo '<i class ="fa fa-user"></i>';
                            }
                            ?>
                            <span class="username account_btn">ACCOUNT</span>
                            <b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu extended logout">
                            <div class="log-arrow-up"></div>
                            <li>
                                <a class="" href="<?php echo site_url('auth_panel/profile/profile_edit'); ?>">
                                <i class=" fa fa-suitcase "></i>Profile</a>
                            </li>
                            <li>
                                <a class="" href="<?php echo site_url('auth_panel/login/logout'); ?>">
                                <i class="fa fa-key"></i> Log Out</a>
                            </li>
                            <li>
                                <a class="" href="#"><i class="fa fa-bell-o "></i>
                                    <?php
                                    echo $user_data->username . '</br>' . $user_data->email;
                                    ?>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <!-- user login dropdown end -->
                </ul>
            </div>
        </header>
        <!--header end-->
        <!--sidebar start-->
        <?php
        $this->load->view('SIDEBAR_SUPER_USER');
        ?>

        <!--sidebar end-->
        <!--main content start-->
        <section id="main-content">
            <section class="wrapper site-min-height">
                <div class="row">
                    <?php
                    if (!isset($breadcrum)) {
                        $breadcrum = array(str_replace('_', ' ', $page ?? $page_title) => '#');
                    }
                    echo breadcrumbs($breadcrum ?? array())
                    ?>
                    <div class="col-lg-12"> <?php echo isset($page_data) ? $page_data : ""; ?> </div>
                </div>
            </section>
        </section>
        <!--main content end-->

        <!--footer start-->
        <footer class="site-footer">
            <div class="text-center">
                <?= CONFIG_PROJECT_GLOBAL_NAME ?> BACKEND
                <a href="#" class="go-top">
                    <i class="fa fa-angle-up"></i>
                </a>
            </div>
        </footer>
        <!--footer end-->
    </section>
    <div role="dialog" id="myuploader" class="modal fade ">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                    <h4 class="modal-title file-modal-element-head"><strong>Upload Meta</strong> </h4>
                </div>
                <div class="modal-body">
                    <div class="panel-body">
                        <form role="form" method="post" id="image_upload_form_global" enctype="multipart/form-data">
                            <span class="error bold img_upload_form" id="image_url_error_global"></span>
                            <input type="hidden" name="backend_user_id" value="<?= $this->session->userdata("active_backend_user_id"); ?>">
                            <div class="form-group">
                                <input type="file" accept="" name="image_file" id="image_file_global" class="img_upload_form col-xs-3">
                                <button class="btn btn-info img_upload_form col-xs-4" type="submit" id="image_upload_btn">Upload</button>
                            </div>

                            <div class="form-group col-md-12 img_status" style="display: none;">
                                <label><strong>Uploaded Image</strong></label>
                                <input type="text" id="copy_input_box" name="copy_input_box" class="form-control input-xs" value="">
                            </div>

                        </form>
                        <div class="form-group col-md-4 img_status" style="display: none;">
                            <img id="img_src_global" src="" alt="" width="42" height="42">
                        </div>
                        <div class="form-group col-md-4 img_status" style="display: none;">
                            <button class="copy_url_global hide">Copy Url</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <!-- js placed at the end of the document so the pages load faster -->
    <script src="<?php echo AUTH_ASSETS; ?>js/jquery.js"></script>
    <script src="<?php echo AUTH_ASSETS; ?>js/popper.min.js"></script>
    <script src="<?php echo AUTH_ASSETS; ?>js/bootstrap.min.js"></script>
    <script src="<?php echo AUTH_ASSETS; ?>js/timedropper.js"></script>
    <script class="include" type="text/javascript" src="<?php echo AUTH_ASSETS; ?>js/jquery.dcjqaccordion.2.7.js"></script>
    <script src="<?php echo AUTH_ASSETS; ?>assets/fancybox/source/jquery.fancybox.js"></script>
    <script src="<?php echo AUTH_ASSETS; ?>js/jquery.scrollTo.min.js"></script>
    <script src="<?php echo AUTH_ASSETS; ?>js/slidebars.min.js"></script>
    <script src="<?php echo AUTH_ASSETS; ?>js/jquery.nicescroll.js" type="text/javascript"></script>
    <script src="<?php echo AUTH_ASSETS; ?>js/respond.min.js"></script>
    <script src="<?php echo AUTH_ASSETS; ?>js/modernizr.custom.js"></script>
    <script src="<?php echo AUTH_ASSETS; ?>js/toucheffects.js"></script>
    <!-- file upload button -->
    <script type="text/javascript" src="<?php echo AUTH_ASSETS; ?>assets/bootstrap-fileupload/bootstrap-fileupload.js"></script>
    <script src="https://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
    <!-- Date time  picker -->
    <script type="text/javascript" src="<?php echo AUTH_ASSETS; ?>assets/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"></script>

    <!--toastr-->
    <script src="<?php echo AUTH_ASSETS; ?>assets/toastr-master/toastr.js"></script>

    <!--common script for all pages-->
    <script src="<?php echo AUTH_ASSETS; ?>js/common-scripts.js"></script>

    <!--date picker-------->
    <script type="text/javascript" src="<?php echo AUTH_ASSETS; ?>assets/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
    <!--time picker-------->
    <script type="text/javascript" src="<?php echo AUTH_ASSETS; ?>assets/bootstrap-timepicker/js/bootstrap-timepicker.js"></script>

    <!--jquery knob for charts-------->
    <script type="text/javascript" src="<?php echo AUTH_ASSETS; ?>assets/jquery-knob/js/jquery.knob.js"></script>

    <script type="text/javascript" src="<?php echo AUTH_ASSETS; ?>assets/ckeditor/ckeditor.js"></script>
    <!-- <script src="//cdn.ckeditor.com/4.9.2/standard/ckeditor.js"></script> -->

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDD_TuINx3k8rZA9ZS9uZOo_xssoBOJLus"></script>

    <!---wyshtml5-->

    <script type="text/javascript" src="<?php echo AUTH_ASSETS; ?>assets/bootstrap-wysihtml5/wysihtml5-0.3.0.js"></script>
    <script type="text/javascript" src="<?php echo AUTH_ASSETS; ?>assets/bootstrap-wysihtml5/bootstrap-wysihtml5.js"></script>
    <script src="<?php echo AUTH_ASSETS; ?>js/select2.min.js"></script>
    <!--end-->
    <?php /* global ajax handler if authentication failure server will return a code and   it will catch that
         *   start here
         */
    ?>
    <script type="text/javascript">
        function overlay(text) {
            var overlay = $("#overlay");
            if (text == "") {
                overlay.css({
                    display: "none"
                });
            } else {
                overlay.find("div span").html(text);
                overlay.css({
                    display: "block"
                });
            }
        }
    </script>
    <script>
        $(document).ajaxComplete(function(event, xhr, settings) {
            if (xhr.draw) {
                alert("ALL current AJAX calls have completed");
            }
        });
    </script>


    <?php echo $javascript; ?>
    <script type="text/javascript">
        var i = -1;
        var toastCount = 0;
        var $toastlast;

        function show_toast(type, text, title) {
            var shortCutFunction = type;
            var msg = text;
            var toastIndex = toastCount++;

            toastr.options = {
                "closeButton": true,
                "debug": false,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "onclick": null,
                "showDuration": "3000",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "10000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            }
            if ($('#addBehaviorOnToastClick').prop('checked')) {
                toastr.options.onclick = function() {
                    alert('You can perform some custom action after a toast goes away');
                };
            }
            if (!msg) {
                msg = getMessage();
            }
            $("#toastrOptions").text("Command: toastr[" +
                shortCutFunction +
                "](\"" +
                msg +
                (title ? "\", \"" + title : '') +
                "\")\n\ntoastr.options = " +
                JSON.stringify(toastr.options, null, 2)
            );

            var $toast = toastr[shortCutFunction](msg, title); // Wire up an event handler to a button in the toast, if it exists
            $toastlast = $toast;
            if ($toast.find('#okBtn').length) {
                $toast.delegate('#okBtn', 'click', function() {
                    alert('you clicked me. i was toast #' + toastIndex + '. goodbye!');
                    $toast.remove();
                });
            }
            if ($toast.find('#surpriseBtn').length) {
                $toast.delegate('#surpriseBtn', 'click', function() {
                    alert('Surprise! you clicked me. i was toast #' + toastIndex + '. You could perform an action here.');
                });
            }
        }
        $('#clearlasttoast').click(function() {
            toastr.clear(getLastToast());
        });
        $('#cleartoasts').click(function() {
            toastr.clear();
        });
        <?php
        if ($page_toast_type != "" && $page_toast != "") {
        ?>
            $('#toast-container').css("width", "100%");
            show_toast(`<?php echo $page_toast_type; ?>`, `<?php echo $page_toast; ?>`, `<?php echo $page_toast_title; ?>`);
        <?php
        } elseif (isset($_SESSION['page_alert_box_type']) && isset($_SESSION['page_alert_box_title']) && isset($_SESSION['page_alert_box_message'])) {
        ?>
            $('#toast-container').css("width", "99%");
            show_toast(`<?php echo $_SESSION['page_alert_box_type']; ?>`, `<?php echo $_SESSION['page_alert_box_message']; ?>`, `<?php echo $_SESSION['page_alert_box_title']; ?>`);

        <?php
            unset($_SESSION['page_alert_box_type']);
            unset($_SESSION['page_alert_box_title']);
            unset($_SESSION['page_alert_box_message']);
        }
        ?>
    </script>
    <script src="<?php echo AUTH_ASSETS; ?>js/tasks.js" type="text/javascript"></script>
    <script>
        $(document).ready(function(e) {
            $('.img_status').hide();
            $("#image_upload_form_global").on('submit', (function(e) {
                var image_data = $('#image_file_global').val();
                if (image_data == '') {
                    e.preventDefault();
                    $("#image_url_error_global").text("Please select an image first");
                } else {
                    e.preventDefault();
                    $.ajax({
                        url: "<?php echo AUTH_PANEL_URL ?>" + "question_bank/add_image",
                        type: "POST",
                        data: new FormData(this),
                        cache: false,
                        dataType: 'json',
                        contentType: false,
                        processData: false,
                        success: function(data) {
                            var url = data.url;
                            $(".img_status").show();
                            $("#img_status_value_global").val(data.url);
                            $('#img_status_value_global').data('url', data.url);
                            $('#img_src_global').attr('src', data.url);
                            $("#copy_input_box").val(data.url);
                        },
                        error: function(data) {
                            console.log("error");
                            console.log(data);
                        }
                    });
                }
            }));

            $("body").on("click", ".copy_url_global", function(event) {
                var url = $('#copy_input_box').val();
                var tmpInput = $('<input>');
                tmpInput.val(url);
                $('body').append(tmpInput);
                tmpInput.select();
                document.execCommand('copy');
                tmpInput.remove();
                alert("Url copied paste it anywhere to use image url")
            });
        });


        function bind_table_search(table_object, table_target, event) {
            let input_search = null;
            $('#' + table_target + ' .' + (event == 'keyup' ? 'search-input-text' : 'search-input-select')).bind(event, function() {
                let selector = $(this);
                if (input_search !== null) {
                    clearTimeout(input_search);
                }
                input_search = setTimeout(function() {
                    table_object.columns(selector.attr('data-column')).search(selector.val()).draw();
                    input_search = null;
                }, 1000);
            });
        }

        function bind_table_searchs(table_object, table_target, event) {
            let input_search = null;
            $('#' + table_target + ' .' + (event == 'keyup' ? 'search-input-text' : 'search-input-select-test')).bind(event, function() {
                let selector = $(this);
                if (input_search !== null) {
                    clearTimeout(input_search);
                }
                input_search = setTimeout(function() {
                    table_object.columns(selector.attr('data-column')).search(selector.val()).draw();
                    input_search = null;
                }, 1000);
            });
        }

        $(function() {
            if($("#sortable").length){
                $("#sortable").sortable();
                $("#sortable").disableSelection();
            }
        });

        var test_behaviour = "";

        $(document).ajaxSend(function() {
            overlay("Please Wait.. Request Is In Processing.");
        }).ajaxStop(function() {
            overlay("");
        }).ajaxComplete(function(data, xhr, setting) {
            var data = jQuery.parseJSON(xhr.responseText);
            if (data.message != undefined) {
                if (data.message == "LOGOUT") {
                    show_toast("error", "Please Login Again.", "Session Expired!");
                    setTimeout(function() {
                        window.location.reload();
                    }, 500)
                } else if (data.message == "NOT_AUTHORIZE") {
                    show_toast("error", "Not Authorised To Access This Request", "Permission Error!");
                }
            }
            overlay("");
        }).ajaxError(function() {
            overlay("");
            show_toast("error", "Server Error", "Error!");
        });

        $.ajaxSetup({
            dataFilter: function(data, type) {
                //modify your data here
                let search = '<?= AMS_BUCKET_NAME . ".s3." . AMS_REGION . ".amazonaws.com" ?>';
                let replace = '<?= S3_CLOUDFRONT_DOMAIN ?>';
                let exist_count = (data.match(new RegExp(search, "g")) || []).length
                for (let i = 1; i <= exist_count; i++) {
                    data = data.replace(search, replace);
                }

                exist_count = (data.match(new RegExp('ut-production-efs.s3.ap-south-1.amazonaws.com', "g")) || []).length
                for (let i = 1; i <= exist_count; i++) {
                    data = data.replace("ut-production-efs.s3.ap-south-1.amazonaws.com", "s3-efs.utkarshapp.com");
                }
                return data;
            }
        });

        function handleConnectionChange(event) {
            if (event.type == "offline") {
                overlay("Please Wait.. Internet Is Not Available.");
            }
            if (event.type == "online") {
                overlay("");
            }
        }
        window.addEventListener('online', handleConnectionChange);
        window.addEventListener('offline', handleConnectionChange);

        $("form").submit(function() {
            //overlay("Please Wait.. Request Is In Processing.");
        });

        function searchsidebar(str) {
            if (str) {
                $(".sidebar-menu li").hide();
                $(".sidebar-menu li").each(function() {
                    if ($(this).text().toUpperCase().indexOf(str.toUpperCase()) != -1) {
                        $(this).show();
                        $(this).find("li").show();
                    }
                });
            } else {
                $(".sidebar-menu li").show();
            }
        }
        try {
            CKEDITOR.config.removeButtons = 'PasteFromWord,Underline,Strike,Superscript,Subscript,Save,Newpage,Preview,Print,Templates,Find,Replace,SelectAll,BidiLtr,BidiRtl,Font,FontSize,ShowBlocks,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,Language,Smiley,CreateDiv,About';
        } catch (e) {
            console.log("Please include ckeditor.js first");
        }

        $(document).on("keypress", ".number", function(e) {
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                return false;
            }
        });
    </script>
    <script>
        $(document).ready(function() {
        // Initialize select2
        if($("#select_admin").length){
            $("#select_admin").select2();
        }

    });
    </script>
</body>

</html>