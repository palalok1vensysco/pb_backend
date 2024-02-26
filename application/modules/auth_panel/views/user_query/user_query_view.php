<style>
    .site-min-height{
        min-height:100vh !important;
    }
    .card-footer{
        display: flow-root;
    }

#scrollrol_div_id::-webkit-scrollbar-track
{
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
    background-color: #888;
}

#scrollrol_div_id::-webkit-scrollbar
{
    width: 5px;
    background-color: #888;
}

#scrollrol_div_id::-webkit-scrollbar-thumb
{
    background-color: #f00;
    border-radius: 50px;
}

.scrollbar
{
    
    float: left;
   
    overflow-y: scroll; 
    margin-bottom: 25px;
}
.chat__main_div{padding: 10px;
               /* background: rgb(31,31,31);*/
                max-height: 400px;
                overflow-y: scroll;}

    .reality_chat_parent_right{text-align: right;
                         padding: 10px; 
                         position: relative;}

    .reality_chat_parent_left{text-align: left;
                         padding: 10px; 
                         position: relative;}

                .text__main_div_right{
                  background: #FF7676; 
                padding: 7px; 
                border-radius:6px; 
                display: inline-block; 
                line-break: anywhere; 
                color: #000;
                max-width: 70%
                }

            .text__main_div_left{background:#DADDDF;
                padding: 7px; 
                border-radius:6px; 
                display: inline-block; 
                line-break: anywhere; 
                color: #000;
                max-width: 70%;}
            .text__show{ 
                padding: 7px; 
                border-radius: 0px 0px 0px 6px; 
                text-align: left;}

                .corner_img{width: 17px; 
                            position: absolute; 
                            margin-left: 4px; 
                            z-index: 1; 
                             top: 10px;}

           .time__date{font-size: 12px; 
                        color: #121212; 
                       margin-left: 8px; 
                      font-weight: 400; 
                      position: relative;
                      top: 4px;}
                .time_date_div{ padding: 0px; 
                    border-radius: 0px 6px 6px 0px; 
                    text-align: right;
                 }

                

                        .name_photo_div{display: flex;}
                        .name_photo_div_right{display: flex;    justify-content: end;}

                        .userPhoto_left{border-radius: 50px;height: 30px;
                                    width: 30px;
                                    margin-right: 5px;}

                        .userPhoto_right{border-radius: 50px;height: 30px;
                                    width: 30px;
                                    margin-left: 5px;}

                        .w100{width: 100%}


                            .wd30{width: 30px;border-radius: 50px;}
                            .user_name_txt{    font-size: 15px;
                                                text-transform: uppercase;
                                                color: white;
                                                font-weight: 600;
                                                margin-top: 3px;
                                            }

                        .right_you{    justify-content: flex-end;}

.room-box p{
    display:flex;
    justify-content:space-between;
}

@media(max-width:1199px){
    .user-heading.round {
        margin-top: 50px;
    }
}

@media(max-width:767px){
    .profile-nav .user-heading{
        padding: 0px;
    }
    .user-heading.round {
        margin-top: 0px;
    }
}

@media(max-width:567px){
    .text__main_div_right{
        max-width:100%;
    }
}


</style>
<aside class="profile-nav col-lg-3 no-padding">
    <section class="panel">
        <div class="user-heading round">
            <a href="#" style="cursor: default">
                <?php if(isset($user_info['profile_picture']) && !empty($user_info['profile_picture'])){?>
                <img alt="" src="<?= $user_info['profile_picture'] ? $user_info['profile_picture'] : AMS_BUCKET_BASE . "course_file_meta/7745759user_default.png"; ?>">
            <?php }else{?>
                <img alt="" src="https://mvfplayerbucket.s3.ap-south-1.amazonaws.com/course_file_meta/4942445sample_profile.png">
            <?php }?>
            </a>
            <h1><?= $user_info['name']; ?></h1>
            <!-- <h1><?= $user_info['last_name']; ?></h1> -->
            <p><?= $user_info['email']; ?></p>
        </div>
    </section>

    <section class="card">
        <header class="card-header text-white bg-dark">
            Query Detail<span class="pull-right"><?= $query['id'] ?></span>
        </header>
        <div class="card-body">
            <div class="room-box">
                
                <p><span class="text-muted">Title :</span> <?= $query['title'] ?></p>
                <p><span class="text-muted">Description :</span> <?= $query['description'] ?></p>
                <p><span class="text-muted">Date :</span> <?= $query['time'] ? date("d-M-Y h:i A", $query['time']) : "N/A" ?></p>
                <p><span class="text-muted">Status :</span> <?= $query['close_date'] == 0 ? "Open" : "Closed" ?></p>
                <?php
                if ($query['close_date']) {
                    $closed_by = isset($query['closed_by']) ? $this->db->where("id", $query['closed_by'])->get("backend_user")->row_array() : array();
                    ?>
                    <p><span class="text-muted">Close Date :</span> <?= date("d-M-Y h:i A", $query['close_date']) ?></p>
                    <p><span class="text-muted">Closed By :</span> <?= $closed_by['username'] ?? "N/A" ?></p>
                    <?php
                } else {
                    ?>
                   <!--  <a class="btn btn-info btn-xs" style="width: 100%" onclick="return confirm('Are you sure want to close this query?')" href="<?= AUTH_PANEL_URL . "user_query/close_query?id=" . $query['id'] . "&query_id=" . $query['query_id'] ?>">Close</a> -->
                    <?php
                }
                ?>
            </div>
        </div>
    </section>
</aside>
<aside class="profile-info col-lg-9">
    <section class="panel">
        <div class="panel-body bio-graph-info">
            <h1>
                User Details
                <a href="<?= base_url('auth_panel/User_query/index'); ?>"><button class="pull-right btn btn-info btn-xs bold">Back to user query list</button></a>
            </h1>
            <div class="row">
                <div class="bio-row">
                    <p><span>Sr.No </span>: <?= $user_info['id']; ?></p>
                </div>
                <div class="bio-row">
                    <p><span>User Name </span>: <?= $user_info['name']; ?></p>
                </div> 
                <div class="bio-row">
                    <p><span>Email </span>: <?= $user_info['email']; ?></p>
                </div>
                <div class="bio-row">
                    <p><span>Mobile</span>: <?= $user_info['mobile']; ?></p>
                </div>
                <div class="bio-row">
                    <p><span>Date/Time of Registration </span>: <?= date("d-m-Y H:i:s", $user_info['created_at']) ?></p>
                </div>
               
            </div>
        </div>
    </section>
</aside>
<div class="col-lg-9 pull-right">
    <section class="panel">
        <div class="panel-body">
            <div class="card-body profile-activity">

                <!--start -->
                <div class="col-sm-12 m-auto chat__main_div scrollbar" id="scrollrol_div_id">
                    <?php
                foreach ($query_reply as $value) {
                    
                    if($value['backend_user_id'] == 0)
                    {
                        ?>
                  
                    <div class="reality_chat_parent_left">
                        <div class="name_photo_div"><img class="userPhoto_left" src="<?=$user_info['profile_picture']?>"><h6 class="userName"><?=$user_info['name']?></h6> </div>
                   <div class="text__main_div_left">
                      <div class="text__show"><?= $value['text'] ?></div>
                        <div class="time_date_div"><?= date("d-M-y h:i A", $value['create_date']) ?><b class="time__date"></b></div>
                   </div>
                </div>
<?php
}
else
{
    ?>

                  
                   <div class="reality_chat_parent_right">
                   <div class="name_photo_div_right"> <h6 class="userName"><?=$value['username']?></h6><img class="userPhoto_right" src="<?=$value['backend_image']?>" width="50px"></div>
                         <div class="text__main_div_right">
                            <div class="text__show"><?= $value['text'] ?></div>
                        <div class="time_date_div"><b class="time__date"><?= date("d-M-y h:i A", $value['create_date']) ?></b></div>
                 </div>
              </div>
               <?php 
}
           }
                ?>
            </div>


                <!-- End -->

            </div>
            <?php
            if (!$query['close_date']) {
                ?>
                <div class="clearfix"></div>
                <div class="card-footer">
                    <div class="chat-form">
                        <form method="post" action="<?= AUTH_PANEL_URL . "User_query/submit_query_detail" ?>">
                            <input value="<?= $query['id'] ?>" name="id" hidden="">
                            <input value="<?= $query['user_id'] ?>" name="user_id" hidden="">
                            <div class="input-cont ">
                                <input type="text" name="text" class="form-control col-lg-12" placeholder="Type a message here..." required>
                            </div>
                            <div class="form-group">
                                <div class="pull-right chat-features">
                                    <button class="btn btn-danger btn-xs dropdown_ttgl text-white">Send</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>      
                <?php
            }
            ?>
        </div>
    </section>
</div>
<!--                <li>
                    <a class="cmt-thumb" href="#">
                        <i class="fa fa-user fa-4x"></i>
                    </a>
                    <form action="<?php echo AUTH_PANEL_URL . 'user_query/user_query_admin_reply'; ?>" method="post">
                        <div class="cmt-form">
                            <textarea name="text" placeholder="Write a comment..." class="form-control submit_on_enter" required="required"></textarea>
                            <input type="hidden"  name="query_id" value="<?php echo $value['id']; ?>">
                            <input type="hidden"  name="backend_user_id" value="<?php echo $this->session->userdata('active_backend_user_id'); ?>">
                            <input type="hidden" name="queried_user_email" value="<?php echo $userQueryDetail[0]['email']; ?>">
                        </div>
                        <button class="btn btn-primary pull-right btn-xs bold" style="position: relative;right:1.6%" type="submit">Send</button>
                    </form>
                </li>
                <div class="clearfix"></div>------>