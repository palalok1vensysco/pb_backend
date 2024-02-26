<style>
    .panel-heading {
    background: #e9e9e9 none repeat scroll 0 0;
}
</style>
<?php //echo '<pre>'; print_r($video); die;?>
<aside class="profile-info col-lg-12">
      <section class="panel">
           <header class="panel-heading custom-panel-heading">
            VIDEO DETAILS
            <a href="<?= base_url('admin-panel/video-list'); ?>"><button class="pull-right btn btn-info btn-xs bold">Back to video list</button></a>
         </header>
          <div class="panel-body custom-panel-body">
            <div class="bio-row ">
                <p><span>Video ID </span>: <?php echo $video['id']; ?></p>
            </div>
            <div class="bio-row">
                <p><span>Video Title </span>: <?php echo $video['video_title']; ?></p>
            </div>
            <div class="bio-row">
                <p><span>Author</span>: <?php echo $video['author_name']; ?></p>
            </div>
            <div class="bio-row">
<!--                <p><span>Creation Date</span>: <?//php echo date("d-m-Y", ($video['creation_time'] / 1000) ); ?></p>-->
                 <p><span>Publish Date</span>: <?php echo date("d-m-Y h:i:s A",$video['published_date']/1000); ?></p>
            </div>
            <div class="bio-row">
                <p><span>Video Description </span>: <?php echo $video['video_desc']; ?></p>
            </div>
             

            <br>
            <section id="main-content">
              <section class="wrapper">
              <!-- page start-->
                <section class="panel">
                   <div class="panel-body">
                     <ul class="grid cs-style-3">
                        <li>
                            <figure>
                                <img class="img-thumbnail" style="width:100%; height:200px;" src="<?php echo $video['thumbnail_url']; ?>" alt="img04">
                                <figcaption>
                                    <h3>Video Thumbnail</h3>
                                    <a class="fancybox" rel="group" href="<?php echo $video['thumbnail_url']; ?>">Take a look</a>
                                </figcaption>
                            </figure>
                        </li>
                       </ul>
                     </div>
                   </section>
                 </section>
                </section>
            <div class="col-md-8 pull-right">
              <div class="col-md-3 pull-right">
                   <button onclick="$(this).addClass('disabled');fire_push();" class="btn btn-info btn-xs col-md-12">Send Notification</button>
              </div>
             <!-- <div id="show_socket_state" class="col-md-12"></div>-->
              <div id="show_socket_state" class="bold col-md-12"> <i class="fa fa-spinner fa-spin bold " aria-hidden="true"></i>  Please Wait while we connecting you to server. </div>
               <div id="show_socket_msg" class="col-md-12"></div>
            </div>
          </div>
      </section>
     </aside>
 
<?php
$adminurl = AUTH_PANEL_URL;
$video_title=$video['video_title'];
$post_id=$video['id'];
$post_type='video';
$image_url=$video['thumbnail_url'];
$admin_id = $this->session->userdata("active_backend_user_id");


$socketjs = AUTH_ASSETS . 'socket/socket.js';
$socket_ip = $_SERVER['SERVER_NAME'] . ':1449';
$custum_js = <<<EOD

        <script src="$socketjs"></script>
        <script type="text/javascript" language="javascript" > 
            /***
             *       _____               _          _     __  __                _       
             *      / ____|             | |        | |   |  \/  |              (_)      
             *     | (___    ___    ___ | | __ ___ | |_  | \  / |  __ _   __ _  _   ___ 
             *      \___ \  / _ \  / __|| |/ // _ \| __| | |\/| | / _` | / _` || | / __|
             *      ____) || (_) || (__ |   <|  __/| |_  | |  | || (_| || (_| || || (__ 
             *     |_____/  \___/  \___||_|\_\ \___| \__| |_|  |_| \__,_| \__, | |_| \___|
             *                                                            __/ |         
             *                                                           |___/          
             */
        var socket = io('$socket_ip');
        
            function fire_push(){
                json_var = {};
                json_var.users_message =  "$video_title ";
                json_var.notification_type = 'open';
                json_var.post_id = "$post_id";
                json_var.post_type = "$post_type";
                json_var.notification_text = "$image_url";
                json_var.admin_id= "$admin_id";
                json_var.users_type = 'ALL';
                console.log(json_var);
                socket.emit('notification', json_var);
                return false;
            }     
             socket.on('notification', function(msg){
                $('#show_socket_msg').text('Notification send successfully');
            });
      
           socket.on('connect', function (user) { //alert('1');
                        $('#show_socket_state').html('<i class="fa fa-check" aria-hidden="true"></i> You are connected to server.');
                        //console.log('web_socket connected');
                    });

                    socket.on('disconnect', function (user) { //alert('2');
                        $('#show_socket_state').html('<i class="fa fa-spinner fa-spin bold " aria-hidden="true"></i>  Please Wait while we connecting you to server. ');
                       // console.log('web_socket disconnected');
                    });
        </script>    

      

EOD;

   echo modules::run('auth_panel/template/add_custum_js',$custum_js );