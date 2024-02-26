<?php
$display = !empty($data_id)?"block":"none";
$notf_type =['2'=>'Enter Course ID','5'=>'Enter Image URL','6'=>'Enter Web URL'];
$dis_text = (isset($res_data['notification_type']) && $res_data['notification_type']!='1')?"block":"none";
$query_data = $this->input->get('q') ? json_decode(base64_decode($this->input->get('q')), true) : array();
if (!$query_data) {
?>

    <section class="panel add_section" style="display:<?= $display ?>">
        <header class="panel-heading">
            Push Notification to Users
        </header>
        <div class="panel-body">
            <form id="bulk_push" method="" action="" role="form">
                <div class="form-group col-md-6" style="display:none">
                    <label for="exampleInputEmail1">Type of user </label>
                    <select name="user_type"class="form-control input-sm">
                        <option value="ALL" selected>ALL</option>
                        <!-- <option value="BATCH_WISE">Batch Wise</option> -->
                        <option value="COURSE_WISE">Course Wise</option>
                        <option value="PAID">Paid Users</option>
                        <option value="FREE">Free Users</option>
                        <option value="PURCHASED">Purchased</option>
                        <option value="UNPURCHASED">Non Purchased</option>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label>Select Device </label>
                    <div>
                        <select name="device_type" class=" device_type input-sm form-control ">
                            <option value="" <?= (isset($res_data['device_type']) && $res_data['device_type'] == "0") ? "selected" : ""; ?>>All</option>
                            <option value="1" <?= (isset($res_data['device_type']) && $res_data['device_type'] == "1") ? "selected" : ""; ?>>Android</option>
                            <option value="2" <?= (isset($res_data['device_type']) && $res_data['device_type'] == "2") ? "selected" : ""; ?>>iOS</option>
                        </select>
                    </div>
                </div>
                <div class="form-group col-md-6" style="display: none;">
                    <label></label>
                    <select data-tags="true" name="course_id[]" id="course_id" class="form-control input-xs select2-selection--multiple" multiple="multiple"></select>
                </div>
                <!-- <hr class="col-md-12"> -->
                <div class="form-group col-md-6">
                    <label>Notification Type </label>
                    <select class="form-control input-sm" name="notification_type">
                        <option value="1" <?= (isset($res_data['notification_type']) && $res_data['notification_type'] == "1") ? "selected" : ""; ?>>General</option>
                        <option value="2" <?= (isset($res_data['notification_type']) && $res_data['notification_type'] == "2") ? "selected" : ""; ?>>Course Detail</option>
                        <option value="5" <?= (isset($res_data['notification_type']) && $res_data['notification_type'] == "5") ? "selected" : ""; ?>>Image</option>
                        <option value="6" <?= (isset($res_data['notification_type']) && $res_data['notification_type'] == "6") ? "selected" : ""; ?>>URL</option>
                    </select>
                </div>

                 <div class="form-group col-md-6">
                    <label>Notification Sending Type </label>
                    <select class="form-control input-sm" name="n_s_type">
                        <option value="1">Instant</option>
                        <option value="2" <?= isset($res_data)?"selected":""; ?>>Scheduler</option>
                    </select>
                </div>
                 <div class="form-group col-md-6 " id="s_time" style="display:<?=$display ;?>">
                    <label>Choose Schedule Time </label>
                    <input type="text" name="s_time" class="s_time form-control" autocomplete="off" value="<?= isset($res_data['schedule_time'])? date('Y-m-d h:i',$res_data['schedule_time']):"" ?>"   placeholder="Enter Schedule Time">
                </div>
                <div class="form-group col-md-6" style=" display:<?= $dis_text ;?>">
                    <label><?= $notf_type[$res_data['notification_type']]?></label>
                    <input type="text"  class="form-control input-sm" value="<?= isset($res_data['notification_text'])? $res_data['notification_text'] :"" ; ?>"  name="notification_text">
                </div>
                <hr class="col-md-12">
                <div class="form-group col-md-12">
                    <label>Title </label>
                    <input type="text" value="<?= isset($res_data['title'])? $res_data['title'] :"" ; ?>" class="form-control input-sm" name="title" placeholder="Enter title">
                </div>
                <div class="form-group col-md-12">
                    <label>Message </label>
                    <textarea class="form-control" name="custom_message"><?= isset($res_data['message'])? $res_data['message'] :"" ; ?></textarea>
                </div>
                <div class="form-group col-md-12">
                    <button class="btn btn-info  btn-xs hide bulk_button" type="button">Submit</button>
                    <button class="btn btn-warning btn-xs hide cancel_btn" type="reset" onclick="$('.add_section').hide('slow');">Cancel</button>
                    <div class="form-group course_status"></div>
                    <div id="show_socket_state" class="bold col-md-12"> <i class="fa fa-spinner fa-spin bold " aria-hidden="true"></i> Please Wait while we connecting you to server. </div>
                </div>
            </form>

        </div>
    </section>
<?php
} else {
?>
    <section class="panel">
        <header class="panel-heading">
            Push Notification to <?= $query_data['name']; ?>
        </header>
        <div class="panel-body">
            <form method="POST" action="" role="form">
                <input type="hidden" value="<?= isset($query_data['device_type']) ? $query_data['device_type'] : ''; ?>" name="device_type">
                <input type="hidden" value="<?= isset($query_data['device_token']) ? $query_data['device_token'] : ''; ?>" name="device_token">
                <input type="hidden" value="<?= ($query_data) ? $query_data['id'] : ''; ?>" name="user_id">
                <div class="form-group col-md-12">
                    <label>Notification Type </label>
                    <select class="form-control input-sm" name="notification_type">
                        <option value="1">General</option>
                        <option value="2">Course Detail</option>
                        <option value="5">Image</option>
                        <option value="6">URL</option>
                    </select>
                </div>

                <div class="form-group col-md-6">
                    <label>Title </label>
                    <input type="text" class="form-control input-sm" name="title">
                </div>
                <div class="form-group col-md-6" style="display: none;">
                    <label for="">Extra (Paste url link for notification type URL) </label>
                    <input type="text" class="form-control" name="notification_text">
                </div>
                <div class="form-group col-md-12">
                    <label>Message </label>
                    <textarea class="form-control" name="custom_message"></textarea>
                </div>
                <div class="form-group col-md-12">
                    <button class="btn btn-info" type="submit">Submit</button>
                </div>
            </form>

        </div>
    </section>
<?php } ?>
<div class="col-sm-12 no-padding">
    <section class="panel">
        <header class="panel-heading bg-dark text-white">
            PUSH SENT LIST(s)
            <button type="button" class="btn-xs btn pull-right display_color dropdown_ttgl text-white" onclick="$('.add_section').show('slow')">Send Push</button>
        </header>
        <div class="panel-body">
            <div class="adv-table">
                <table class="display table table-bordered table-striped" id="push-history-grid">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Send by</th>
                            <th>Device</th>
                            <th>Message</th>
                            <th>Type</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </section>
</div>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Message</h5>
                <!--                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>-->
            </div>
            <div class="modal-body">
                <form method="post" action="<?= AUTH_PANEL_URL . "bulk_messenger/push_notification/edit_message" ?>">
                    <input type="hidden" name="message_id">
                    <div class="form-group">
                        <label for="message-text" class="col-form-label">Message:</label>
                        <textarea class="form-control" name="message-text" id="message-text"></textarea>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-xs" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary btn-xs save_message">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" charset="utf8" src="<?= AUTH_ASSETS ?>js/jquery.dataTables.min.js"></script>
<script src="<?= AUTH_ASSETS ?>new/socket.js"></script>
<script type="text/javascript" src="<?= AUTH_ASSETS ?>assets/ckeditor/ckeditor.js">
</script>
<script type="text/javascript" language="javascript">
    var socket = io('<?= WEB_SOCKET_IP ?>', {
        reconnectionDelay: 5000,
        reconnectionAttempts: 3
    });

    $('.bulk_button').click(function() {
        var users_type = $('select[name=user_type]').val();
        // var c_ids = $("#course_id").val();
        var title = $("input[name=title]").val();
        var device_type = $('.device_type').val();
        var notification_type = $('select[name=notification_type]').val();
        var notification_text = $('input[name=notification_text]').val();
        var send_no_type = $('select[name=n_s_type]').val();
        var shedule_time = $('input[name=s_time]').val();
        var message = CKEDITOR.instances['custom_message'].getData();
        if (notification_text === "" && (notification_type == 6 || notification_type == 5)) {
            show_toast('error', 'Please provide valid detail.', 'Warning!');
            return false;
        } else if ((notification_text === "" || isNaN(notification_text)) && notification_type == 2) {
            show_toast('error', 'Enter Valid Course ID.', 'Warning!');
            return false;
        }
        if (title === "") {
            show_toast('error', 'Please type Title for users.', 'Warning!');
            return false;
        }
        if (message === "") {
            show_toast('error', 'Please type message for users.', 'Warning!');
            return false;
        }

        var json_var = {
            users_type: users_type,
            // course_ids: c_ids,
            title: title,
            message: message,
            device_type: device_type,
            notification_type: notification_type,
            notification_text: notification_text,
            admin_id: "<?= $this->session->userdata("active_backend_user_id") ?>",

        };
        $('.bulk_button,.cancel_btn').addClass("hide");
        if (send_no_type == 2) { 

        var date_time = "<?= $data_id ?>";
        if(date_time!=""){
            $data = $(".s_time ").val();
            var date = new Date($data);
            var schedule_time = date.getTime() / 1000; 
            var curr_time = Math.floor(Date.now() / 1000)+600;
            if( schedule_time< curr_time){
                show_toast('error', "You Cann't edit notification before 10 minutes schedule time", 'Alert!');
                return false;
            }
           }

            delete json_var.users_type;
            delete json_var.admin_id;
            json_var.schedule_time = shedule_time;
            json_var.user_type = users_type;
            json_var.notification_type = notification_type;
            jQuery.ajax({
                url: "<?= AUTH_PANEL_URL . 'bulk_messenger/push_notification/notification_scheduler' ?>",
                method: 'POST',
                dataType: 'json',
                data: {
                    data: json_var
                },
                success: function(data) {
                    console.log(data.status);

                    if (data.status == "true") {
                        $('#push-history-grid').DataTable().ajax.reload();
                        $(".add_section").hide();
                        show_toast("success", data.message, data.title);
                    } else {
                        show_toast("warning", data.message, data.title);
                    }

                }
            });

        } else {
            // if (c_ids.length === 0) {
            //     delete json_var.course_ids;
            //     socket.emit('notification', json_var);
            // } else {
                socket.emit('bulk_notification', json_var);
            // }
        }

    });
    socket.on('notification', function(response) {
        var data = JSON.parse(response);
        $('.bulk_button,.cancel_btn').addClass('hide');
        $('#show_socket_state').html(data.message);
    });
    socket.on('connect', function(user) {
        $('.bulk_button,.cancel_btn').removeClass('hide');
        $('#show_socket_state').html('<i class="fa fa-check" aria-hidden="true"></i> You are connected to server.');
        console.log('web_socket connected');
    });
    socket.on('disconnect', function(user) {
        $('.bulk_button,.cancel_btn').addClass('hide');
        $('#show_socket_state').html('<i class="fa fa-spinner fa-spin bold " aria-hidden="true"></i>  Please Wait while we connecting you to server. ');
        console.log('web_socket disconnected');
    });
</script>
<script type="text/javascript" charset="utf8">
    var table = 'push-history-grid';
    var dataTable = jQuery("#" + table).DataTable({
        "pageLength": 50,
        "serverSide": true,
        "order": [
            [0, "desc"]
        ],
        "aoColumnDefs": [{
            "bSortable": false,
            "aTargets": [1, 2, 4]
        }, ],
        "ajax": {
            url: "<?= AUTH_PANEL_URL ?>bulk_messenger/push_notification/ajax_push_messages", // json datasource
            type: "post", // method  , by default get
            error: function() { // error handling
                jQuery("." + table + "-error").html("");
                jQuery("#" + table + "_processing").css("display", "none");
            }
        }
    });
    jQuery("#" + table + "_filter").css("display", "none");
    // bind_table_search(dataTable, table, 'keyup');
    let is_batch_enabled = false;

    function get_course_select2_url() {
        if (is_batch_enabled == true)
            return "<?= AUTH_PANEL_URL ?>course_product/course_transactions/course_search?filter=yes&type=batch";
        else
            return "<?= AUTH_PANEL_URL ?>course_product/course_transactions/course_search?filter=yes";
    }

    //   $("#push-history-grid").on("dblclick",'tbody tr td:eq(3)',function(){
    //       var selector = $(this);
    //       $("#exampleModal").modal('show');
    //   });
    CKEDITOR.replace("message-text");
    $(document).on('click', '.edit_message', function() {
        var selector = $(this);
        var message = selector.parent().parent().find('td:eq(3)').html();
        var id = selector.parent().parent().find('td:eq(0)').html();
        $("#exampleModal").modal('show');
        CKEDITOR.instances['message-text'].setData(message)
        $("#exampleModal").find('input[name="message_id"]').val(id);
        console.log(id);
    });
    $(".save_message").click(function() {
        var selector = $(this);
        var id = selector.parent().parent().find('input[name="message_id"]').val();
        var message = CKEDITOR.instances['message-text'].getData();
        jQuery.ajax({
            url: "<?= AUTH_PANEL_URL . 'bulk_messenger/push_notification/edit_message' ?>",
            method: 'POST',
            dataType: 'json',
            data: {
                id: id,
                message: message
            },
            success: function(data) {
                if (data.status == true) {
                    $('#push-history-grid').DataTable().ajax.reload();
                    $("#exampleModal").modal('hide');
                    show_toast("success", data.message, data.title);
                } else {
                    show_toast("warning", data.message, data.title);
                }

            }
        });
    });
    $('#course_id').select2({
        placeholder: 'Select an Course',
        theme: "classic",
        width: 'resolve',
        ajax: {
            url: function() {
                return get_course_select2_url();
            },
            dataType: 'json',
            delay: 1500,
            processResults: function(data) {
                return {
                    results: data
                };
            }
        }
    });
    $("select[name=user_type]").change(function() {
        $("#course_id").parent().hide();
        $("#course_id").val('').trigger('change');
        is_batch_enabled = false;
        switch ($(this).val()) {
            case "BATCH_WISE":
                is_batch_enabled = true;
                $("#course_id").parent().show();
                $("#course_id").siblings("label").text("Target batches or all batches");
                break;
            case "COURSE_WISE":
                $("#course_id").parent().show();
                $("#course_id").siblings("label").text("Target courses or all courses");
                break;
        }
    });
    $('select[name=notification_type]').change(function() {
        let target = $("input[name=notification_text]").parent();
        target.find("input").val("");
        target.find("input").removeClass("number");
        target.hide();
        switch ($(this).val()) {
            case "2":
                target.show();
                target.find("input").addClass("number");
                target.find("label").text("Enter Course ID");
                break;
            case "5":
                target.show();
                target.find("label").text("Enter Image URL");
                break;
            case "6":
                target.show();
                target.find("label").text("Enter Web URL");
                break;
        }
        target.find("input").attr("placeholder", target.find("label").text());
    });
    CKEDITOR.replace("custom_message");


    $("select[name=n_s_type]").change(function() {
        if ($(this).val() == 2) {
            $("#s_time").show();
        } else {
            $("#s_time").hide();
        }

    });

    $(".s_time").focus(function(){
        $(this).datetimepicker({
            format: 'YYYY-MM-DD HH:mm',    
            inline: true,
            sideBySide: true,
            minDate: moment(),
        });
       }); 
      
    // $(document).mouseup(function(e) {

    //     $('.datetimepicker-minutes').hide();

    // });
    
</script>