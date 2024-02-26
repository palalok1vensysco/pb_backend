<div class="col-lg-12">
    <section class="panel">
        <header class="panel-heading text-white bg-dark">
            Send EMAIL to Users
        </header>
        <div class="panel-body">
            <form id="bulk_push" method="" action=""  role="form">
                <div class="form-group">
                    <label for="exampleInputEmail1">Type of user</label>
                    <select name="user_type"class="form-control m-bot15 bulk_user_type ">
                        <option value="all">All</option>
                        <?php
                        if ($this->input->get('email') != "") {
                            $email = urldecode($this->input->get('email'));
                            echo '<option selected value="' . $email . '">' . $email . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <!--
                <div class="form-group">
                    <label for="exampleInputEmail1">Device Type</label>
                    <select name="device_type" class="device_type form-control m-bot15 ">
                        <option value="">ALL</option>
                        <option value="1">ANDROID</option>
                        <option value="2">IOS</option>
                    </select>
                </div>
    -->
                <div class="form-group">
                    <label for="exampleInputEmail1">Subject</label>
                    <input class="form-control bulk_user_subject" name="subject">
                </div>

                <div class="form-group">
                    <label for="exampleInputEmail1">Choose Template</label>
                    <select onchange="set_template();" name="template" class="form-control m-bot15">
                        <option value="">--Choose template -- </option>
                        <option value="CUSTUM">Custum Template</option>
                        <?php
                        $template = $this->db->where(array('type' => "open"))->get('mailer')->result_array();
                        foreach ($template as $t) {
                            echo '<option value="' . $t['id'] . '">' . $t['template_name'] . '</option>';
                        }

                        foreach ($template as $t) {
                            echo '<textarea  style="display:none" id="tarea_' . $t['id'] . '" >' . $t['template_html'] . '</textarea>';
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="exampleInputPassword1">Type message</label>
                    <textarea class="form-control bulk_user_message " name="message"></textarea>
                </div>
                <button class="btn btn-info bulk_button hide" type="submit">Submit</button>
                <div id="show_socket_state" class="bold col-md-12"> <i class="fa fa-spinner fa-spin bold " aria-hidden="true"></i>  Please Wait while we connecting you to server. </div>
            </form>

        </div>
    </section>
</div>




<?php
$assetsurl = AUTH_ASSETS.'assets/ckeditor/ckeditor.js';
//$socketjs = base_url().'web_socket/examples/js/socket.js';
//$socketurl = 'ws://'.WEB_SOCKET_IP.':2000';
$adminurl = AUTH_PANEL_URL;
$socketjs = AUTH_ASSETS . 'new/socket.js';
$socket_ip = $_SERVER['SERVER_NAME'] . ':1337';
$custum_js = <<<EOD



                    
                    <script type="text/javascript" src="$assetsurl"></script>
                      <script>
                        CKEDITOR.replace('message');
                      </script>
                      <script src="$socketjs"></script>
                      <script type="text/javascript" language="javascript" >

                            function set_template(){
                                var id = $('select[name=template]').val();

                                CKEDITOR.instances['message'].setData($('#tarea_'+id).val());

                            }

            //              var socket=$.websocket('');
                            var socket = io('$socket_ip',{"rejectUnauthorized": true});

                            $('#bulk_push').submit(function() {
                            var users_type = $('.bulk_user_type').val();
                            var users_message = CKEDITOR.instances['message'].getData();// $('.bulk_user_message').val();
                            var users_subject = $('input[name=subject]').val();

                            if (users_subject == "") {
                                show_toast('error', 'Please type subject for email.', "Subject Error");
                                return false;
                            }

                            if (users_message == "") {
                                show_toast('error', 'Please type message for users.', "Subject Error");
                                return false;
                            }
                            let device_type = $(".device_type").val();

                            if (users_message) {
//                                $('.bulk_button').hide();
                                json_var = {};
                                json_var.users_type = users_type;
                                json_var.device_type = device_type;
                                json_var.users_message = users_message;
                                json_var.users_subject = users_subject;

                                //console.log(json_var);
                                socket.emit('bulk_mail', json_var);
                            }
                            $('.bulk_mail').val('');
                            return false;
                        });

                        socket.on('bulk_mail', function (msg) {
                            $('#show_socket_state').html('</br>' + msg);
                            console.log(msg);
                        });
                        socket.on('connect', function (user) {
                            $('.bulk_button').removeClass('hide');
                            $('#show_socket_state').html('<i class="fa fa-check" aria-hidden="true"></i> You are connected to server.');
                            console.log('web_socket connected');
                        });

                        socket.on('disconnect', function (user) {
                            $('.bulk_button').addClass('hide');
                            $('#show_socket_state').html('<i class="fa fa-spinner fa-spin bold " aria-hidden="true"></i>  Please Wait while we connecting you to server. ');
                            console.log('web_socket disconnected');
                        });
</script>



EOD;

  echo modules::run('auth_panel/template/add_custum_js',$custum_js );
?>
