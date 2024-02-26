<div class="col-lg-6 add_section" id="push_main">
    <section class="panel">
        <header class="panel-heading">
            Complete Notification
        </header>
        <div class="panel-body">
            <h4><b>Target Audience</b></h4>
            <form id="bulk_push" method="" action=""  role="form">
                <div class="form-group col-md-6">
                    <label for="exampleInputEmail1">Target Users</label>
                    <select  name="user_type"class="form-control input-xs bulk_user_type">
                        <option value="ALL">ALL</option>
                        <option value="BATCHWISE" selected>Batch-Wise</option>
                        <option value="COURSEWISE" selected>Course-Wise</option>
                        <option value="PAID" selected>Paid Users</option>
                        <option value="FREE">Free Users</option> 
                        <option value="PURCHASED">Purchaised</option>
                        <option value="UNPURCHASED">Un-Purchaised</option>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label >Device Type</label>
                    <select name="device_type"class=" device_type input-xs form-control ">
                        <option value="">ALL</option>
                        <option value="1">ANDROID</option>
                        <option value="2">IOS</option>
                    </select>
                </div>
                <div class="form-group col-md-12" id="course_list" style="display:none;">
                    <label>Select Courses</label>
                    <select  name="course_id[]" class="form-control input-xs course_id select2-selection--multiple" multiple="multiple" required="">
                    </select>
                </div>
                <div class="form-group col-md-12" id="batch_list" style="display:none;">
                    <label>Select Batches</label>
                    <select   name="batch_id[]" class="form-control input-xs batch_id select2-selection--multiple" multiple="multiple" required="">
                    </select>
                </div>
                <div class="form-group col-md-12">
                    <label for="notification_type">Notification Type</label>
                    <select class="form-control input-xs notification_type " name = "notification_type" id="notification_type">
                        <option value="1" selected>General</option>
                        <option value="2">Course Detail</option>
                        <option value="5">Image</option>
                        <option value="6">URL</option>
                    </select>
                </div>
                <div class="form-group col-md-12" id="notification_type_text" >
                    <label for="">Extra (Paste url link for notification type URL )</label>
                    <input type="text" class="form-control notification_content input-xs" value="" accept="" name = "" id="">
                </div>
                <div class="clearfix"></div>

                <div class="form-group col-md-12">
                    <label>Title</label>
                    <input type="text" class="form-control input-xs" name="title">
                </div>
                <div class="form-group col-md-12">
                    <label>Message</label>
                    <textarea class="form-control input-xs" name="message"><?php
                        if ($this->input->get('text')) {/* echo base64_decode($this->input->get('text')); */
                        }
                        ?></textarea>
                    <span style="color: red"><?php echo ($this->session->flashdata('error') && $this->session->flashdata('error') == 'raw_error') ? form_error('message') : ''; ?></span>
                </div>
                <hr>
                <h4><b>Notification Payload</b></h4>
                <div class="form-group col-md-12">
                    <label>Short Message</label>
                    <textarea class="form-control input-xs" name="short_msg" placeholder="Type Short Message Here"></textarea>
                </div>
                <div class="form-group col-md-12">
                    <label>Long Message</label>
                    <textarea class="form-control input-xs" name="long_msg" placeholder="Type Long Message Here"></textarea>
                </div>
                <!--<input type="hidden" value="raw" name="notification_type">-->
                <div class="col-md-6">
                    <button class="btn btn-info bulk_button btn-xs " type="button" >Submit</button>
                    <button class="btn btn-warning btn-xs cancel_btn" type="reset" onclick="$('#push_main').hide('slow');" >Cancel</button>
                </div>
                <div class="form-group course_status"></div>
                <div id="show_socket_state" class="bold col-md-12"> <i class="fa fa-spinner fa-spin bold " aria-hidden="true"></i>  Please Wait while we connecting you to server. </div>
            </form>

        </div>
    </section>
</div>
<!-- All sent messages to user or dams/non dams user -->
<div class="col-sm-12 no-padding" style="display:none;">
    <section class="panel">
        <header class="panel-heading">
            TARGET LIST(s)
               <button type="button" class="btn btn-info btn-xs pull-right" onclick="$('.add_section').show('slow')">Send Push</button>
        </header>
        <div class="panel-body">
            <div class="adv-table">
                <table  class="display table table-bordered table-striped" id="target-grid">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Send by</th>
                            <th>Device</th>
                            <th>Message</th>
                            <th>Type</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </section>
</div>
<script>
<?php if ($this->input->get('q')) { ?>
        $('#push_main').toggleClass('col-lg-12', 'col-lg-6');
<?php } else { ?>
        $('#push_main').toggleClass('col-lg-6', 'col-lg-12');
<?php } ?>
</script>

<script type="text/javascript" charset="utf8" src="<?= AUTH_ASSETS ?>js/jquery.dataTables.min.js"></script>
<script src="<?= AUTH_ASSETS ?>new/socket.js"></script>
<script type="text/javascript" src="<?= AUTH_ASSETS ?>assets/ckeditor/ckeditor.js"></script>
<script type="text/javascript" language="javascript" >
    var c_ids = [];
    var c_index = 0;
    $(document).ready(function () {
        CKEDITOR.replace("message");
        CKEDITOR.replace("long_msg");
    });

    var socket = io('<?= WEB_SOCKET_IP ?>', {
        reconnectionDelay: 5000,
        reconnectionAttempts: 3
    });
    var json_var = {};
    
    $('.bulk_button').click(function () {
        var users_type = $('.bulk_user_type').val();
        var c_ids = $(".course_id").val();
        var b_ids = $(".batch_id").val();
        var title = $("input[name=title]").val();
        var device_type = $('.device_type').val();
        var notification_type = $('.notification_type').val();
        var notification_text = $('.notification_content').val();
        var message = CKEDITOR.instances['message'].getData();
        
        if(users_type == "BATCHWISE" && b_ids.length < 1){
            show_toast("error","Please Select Batches","Warning");
            return false;
        }
        if(users_type == "COURSEWISE" && c_ids.length < 1){
            show_toast("error","Please Select Courses","Warning");
            return false;
        }
        if (notification_text == "" && (notification_type == 6 || notification_type == 5)) {
            show_toast('error', 'Please provide valid detail.', 'Warning!');
            return false;
        } else if ((notification_text == "" || isNaN(notification_text)) && notification_type == 2) {
            show_toast('error', 'Enter Valid Course ID.', 'Warning!');
            return false;
        }
        if (message == "") {
            show_toast('error', 'Please type message for users.', 'Warning!');
            return false;
        }

        if (message) {
            $('.bulk_button,.cancel_btn').addClass("hide");
            json_var = {};
            json_var.users_type = users_type;
            switch(users_type){
                case "BATCHWISE":
                   json_var.batch_ids = b_ids;
                   break;
               case "COURSEWISE" :
                   json_var.course_ids = c_ids.length > 0 ? c_ids[c_index] : 0;
                   break;
            }
//            json_var.course_ids = c_ids.length > 0 ? c_ids[c_index] : 0;
            json_var.title = title;
            json_var.message = message;
            json_var.device_type = device_type;
            json_var.notification_type = notification_type;
            json_var.notification_text = notification_text;
            json_var.short_msg = $("input[name=short_msg]").val();
            json_var.long_msg = $("input[name=long_msg]").val();
            json_var.admin_id = "<?= $this->session->userdata("active_backend_user_id") ?>";
            console.log(JSON.stringify(json_var));
            
//            socket.emit('notification', json_var);
        }

        if (c_ids.length > 0) {
            ++c_index;
            let courses_list = "";
            $.each(c_ids, function (index, c_id) {
                courses_list += `<span class="badge badge-` + (c_index < c_ids.length ? 'success' : 'warning') + `" >` + ($(document).find("select[name='course_id[]']").find("option[value=" + c_id + "]").text()) + `<i class="fa fa-` + (c_index < c_ids.length ? 'check' : 'spin fa-spinner') + `" aria-hidden="true"></i></span><br>`;
            });
            $(".course_status").append(courses_list);
        }

    });

    socket.on('notification', function (response) {
        var data = JSON.parse(response);
        if (c_index < c_ids.length) {
            $('.bulk_button').click();
            if (c_index == 1) {
                $(window).bind("beforeunload", function (event) {
                    return "Task in in progress. Please wait till complete otherwise task will be cancelled.";
                });
            }
        } else if (c_index == c_ids.length) {
            window.location.reload();
//            $(window).unbind("beforeunload");
        }
        $('#show_socket_state').text(data.message);

    });

    socket.on('connect', function (user) {
        $('.bulk_button,.cancel_btn').removeClass('hide');
        $('#show_socket_state').html('<i class="fa fa-check" aria-hidden="true"></i> You are connected to server.');
        console.log('web_socket connected');
    });

    socket.on('disconnect', function (user) {
        $('.bulk_button,.cancel_btn').addClass('hide');
        $('#show_socket_state').html('<i class="fa fa-spinner fa-spin bold " aria-hidden="true"></i>  Please Wait while we connecting you to server. ');
        console.log('web_socket disconnected');
    });

</script>    
<script type="text/javascript" charset="utf8">
    
    $('#notification_type').change(function () {
        $('#notification_type_text').show();
        if ($(this).val() == 1 || $(this).val() == "") {
            $('#notification_type_text').hide();
        }

        if ($(this).val() == 2) {
            $('#notification_type_text').find("label").text("Extra (Enter Course ID)");
        } else {
            $('#notification_type_text').find("label").text("Extra (Paste url link for notification type URL)");
        }
    }).change();

    //show hide course and batch search
    $("body").on("change","select[name=user_type]",function(){
        var targetType = $(this).val();
        switch(targetType){
            case "BATCHWISE" :
                $("#batch_list").show("slow");
                $("#course_list").hide("slow");
                break;
            case "COURSEWISE" :
                console.log(targetType);
                $("#course_list").show("slow");
                $("#batch_list").hide("slow");
                break;
            default :
                $("#course_list").hide("slow");
                $("#batch_list").hide("slow");
                break;
       }
    });
    
    $(document).ready(function () {
        $('.course_id').select2({
            placeholder: 'Select Courses',
            theme: "classic",
            width: 'resolve',
//            allowClear: true,
            ajax: {
                url: "<?= AUTH_PANEL_URL ?>course_product/course_transactions/course_search?filter=yes",
                dataType: 'json',
                delay: 2000,
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });
        $('.batch_id').select2({
            placeholder: 'Select Batches',
            theme: "classic",
            width: 'resolve',
//            allowClear: true,
            ajax: {
                url: "<?= AUTH_PANEL_URL ?>batch/batch_search?filter=yes",
                dataType: 'json',
                delay: 2000,
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });
    });
    
    $('#notification_type_single').change(function () {
        $('#notification_type_text_single').show();
        if ($(this).val() == 1 || $(this).val() == "") {
            $('#notification_type_text_single').hide();
        }

        if ($(this).val() == 2) {
            $('#notification_type_text_single').find("label").text("Extra (Enter Course ID)");
        } else {
            $('#notification_type_text_single').find("label").text("Extra (Paste url link for notification type URL)");
        }
    }).change();
</script>       