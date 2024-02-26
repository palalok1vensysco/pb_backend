<?php
$app_id = (defined("APP_ID") ? APP_ID : "0"); 

$sql = "SELECT count(`id`) as total  FROM `users` where app_id = ".$app_id;
$total_users = $this->db->query($sql)->row()->total; 
//print_r($this->db->last_query()); die;
$sql = "SELECT count(`id`) as total  FROM `application_manager` where status = 1";
$total_clients = $this->db->query($sql)->row()->total;
//print_r($this->db->last_query()); die;
$sql = "SELECT count(`id`) as total  FROM `course_topic_file_meta_master` where  category=1 and app_id = ".$app_id;
$total_videos = $this->db->query($sql)->row()->total;

$sql = "SELECT count(`id`) as total  FROM `course_topic_file_meta_master` where  category=2 and app_id = ".$app_id;
$total_webseries = $this->db->query($sql)->row()->total;

$sql = "SELECT count(`id`) as total  FROM `course_topic_file_meta_master` where  category=3 and app_id = ".$app_id;
$total_tv_serials = $this->db->query($sql)->row()->total;

$sql = "SELECT count(`id`) as total  FROM `course_topic_file_meta_master` where  category=4 and app_id = ".$app_id;
$total_songs = $this->db->query($sql)->row()->total;

$sql = "SELECT count(`id`) as total  FROM `premium_transaction_record` where app_id = ".$app_id;
$total_tran = $this->db->query($sql)->row()->total;
// $sql = "SELECT count(`id`) as total  FROM `songs` where status=0 and app_id = ".$app_id;
// $total_songs = ($this->db->query($sql)->row()->total);

$sql = "SELECT count(`id`) as total  FROM `artists` where status=0 and app_id = ".$app_id;
$total_guru = $this->db->query($sql)->row()->total;

// $sql = "SELECT count(`id`) as total  FROM `premium_season` where status=0 and app_id = ".$app_id;
// $total_webseries = $this->db->query($sql)->row()->total;

// $sql = "SELECT count(`id`) as total  FROM `tv_serial` where status=0 and app_id = ".$app_id;
// $total_tv_serials = $this->db->query($sql)->row()->total;

// $sql = "SELECT count(`id`) as total  FROM `news` where status=0 ";
// $total_news = $this->db->query($sql)->row()->total;
?>
 
<div class="col-md-12">
  <!-- <h2 class="text-center">WELCOME TO <span style="color:#ff9700;"><?=CONFIG_PROJECT_GLOBAL_NAME?></span>  <span style="color:#FF6C60;"><?=CONFIG_PROJECT_SUBDOMAIN_NAME?> </span> </h2>
  <br> -->
</div>
  <div class=" state-overview">
    <button type="button" title="Fetch Latest Data" class="btn btn-primary btn-xs pull-right btn-sm redis_txn_clear" style="margin-top: -49px;"><i class="fa fa-refresh" aria-hidden="true"></i></button>
        <?php 
             if (!empty($GLOBALS['app'])) {
            ?>
                <div class="col-lg-3 col-sm-6">
                      <section class="panel">
                          <div class="symbol terques">
                              <i class="fa fa-users"></i>
                          </div>
                          <div class="value">
                              <h1 class="count"><?php echo $total_clients; ?></h1>
                             <!--  <p>Client section</p> -->
                              <a href="<?php echo AUTH_PANEL_URL . 'admin/application'; ?>"><span class="bold">Total Clients</span></a>
                          </div>
                      </section>
                  </div>
                  <div class="col-lg-3 col-sm-6">
                      <section class="panel">
                          <div class="symbol terques">
                              <i class="fa fa-users"></i>
                          </div>
                          <div class="value">
                              <h1 class="count"><?php echo $total_tran; ?></h1>
                              <!-- <p>Transcation Section</p> -->
                              <a href="<?php echo AUTH_PANEL_URL . 'course_transactions/index?status=complete'; ?>"><span class="bold">Total Transaction</span></a>
                          </div>
                      </section>
                  </div>
                  <div class="col-lg-3 col-sm-6">
                      <section class="panel">
                          <div class="symbol terques">
                              <i class="fa fa-users"></i>
                          </div>
                          <div class="value">
                              <h1 class="count"><?php echo $total_users; ?></h1>
                              <!-- <p>Total Users</p> -->
                              <a href="<?php echo AUTH_PANEL_URL . 'web_user/all_user_list?user=all'; ?>"><span class="bold">Total Users</span></a>
                          </div>
                      </section>
                  </div>
                  <div class="col-lg-3 col-sm-6">
                      <section class="panel">
                          <div class="symbol red">
                              <i class="fa fa-video-camera"></i>
                          </div>
                          <div class="value">
                              <h1 class=" count2"><?php echo $total_videos; ?></h1>
                             <!--  <p>Total Movies</p> -->
                              <a href="<?php echo AUTH_PANEL_URL . 'movies/movies/movies_list'; ?>"><span class="bold">Total Movies</span></a>
                          </div>
                      </section>
                  </div>
                   <div class="col-lg-3 col-sm-6">
                      <section class="panel">
                          <div class="symbol"  >
                              <i class="fa fa-youtube-play"></i>
                          </div>
                          <div class="value">
                              <h1 class=" count2"><?php echo $total_webseries; ?></h1>
                              <!-- <p>Total Web Series</p> -->
                              <a href="<?php echo AUTH_PANEL_URL . 'videos/premium_video/add_season'; ?>"><span class="bold">Total Web Series</span></a>
                          </div>
                      </section>
                  </div>
                   <div class="col-lg-3 col-sm-6">
                      <section class="panel">
                          <div class="symbol"  >
                              <i class="fa fa-youtube-play"></i>
                          </div>
                          <div class="value">
                              <h1 class=" count2"><?php echo $total_tv_serials; ?></h1>
                             <!--  <p>Total TV Serials</p> -->
                              <a href="<?php echo AUTH_PANEL_URL . 'videos/premium_tv_serials/add_tv_serial'; ?>"><span class="bold">Total TV Serials</span></a>
                          </div>
                      </section>
                  </div>
                  <div class="col-lg-3 col-sm-6">
                      <section class="panel">
                          <div class="symbol ">
                              <i class="fa fa-music"></i>
                          </div>
                          <div class="value">
                              <h1 class=" count3"><?php echo $total_songs; ?></h1>
                              <!-- <p>Total Videos</p> -->
                              <a href="<?php echo AUTH_PANEL_URL . 'file_manager/library/add_video'; ?>"><span class="bold">Total Videos</span></a>
                          </div>
                      </section>
                  </div>
                  <div class="col-lg-3 col-sm-6">
                      <section class="panel">
                          <div class="symbol">
                              <i class="fa fa-user"></i>
                          </div>
                          <div class="value">
                              <h1 class=" count4"><?php echo $total_guru; ?></h1>
                              <!-- <p>Total Artist</p> -->
                              <a href="<?php echo AUTH_PANEL_URL . 'Artist/artist/artist_list'; ?>"><span class="bold">Total Artist</span></a>
                          </div>
                      </section>
                  </div>
                  <!-- <div class="col-lg-3 col-sm-6">
                    <section class="panel">
                        <div class="symbol" >
                            <i class="fa fa-edit"></i>
                        </div>
                        <div class="value">
                            <h1 class=" count4"><?php echo $total_news; ?></h1>
                         
                            <a href="<?php echo AUTH_PANEL_URL . 'news/news/news_list'; ?>"><span class="bold">Total News</span></a>
                        </div>
                    </section>
                  </div> -->
                <?php } else { ?>
                  <div class="col-lg-3 col-sm-6">
                      <section class="panel">
                          <div class="symbol terques">
                              <i class="fa fa-users"></i>
                          </div>
                          <div class="value">
                              <h1 class="count"><?php echo $total_users; ?></h1>
                              <p>Total Users</p>
                          </div>
                      </section>
                  </div>
                  <div class="col-lg-3 col-sm-6">
                      <section class="panel">
                          <div class="symbol red">
                              <i class="fa fa-video-camera"></i>
                          </div>
                          <div class="value">
                              <h1 class=" count2"><?php echo $total_videos; ?></h1>
                              <p>Total Movies</p>
                          </div>
                      </section>
                  </div>
                   <div class="col-lg-3 col-sm-6">
                      <section class="panel">
                          <div class="symbol"  >
                              <i class="fa fa-youtube-play"></i>
                          </div>
                          <div class="value">
                              <h1 class=" count2"><?php echo $total_webseries; ?></h1>
                              <p>Total Web Series</p>
                          </div>
                      </section>
                  </div>
                   <div class="col-lg-3 col-sm-6">
                      <section class="panel">
                          <div class="symbol"  >
                              <i class="fa fa-youtube-play"></i>
                          </div>
                          <div class="value">
                              <h1 class=" count2"><?php echo $total_tv_serials; ?></h1>
                              <p>Total TV Serials</p>
                          </div>
                      </section>
                  </div>
                  <div class="col-lg-3 col-sm-6">
                      <section class="panel">
                          <div class="symbol ">
                              <i class="fa fa-music"></i>
                          </div>
                          <div class="value">
                              <h1 class=" count3"><?php echo $total_songs; ?></h1>
                              <p>Total Videos</p>
                          </div>
                      </section>
                  </div>
                  <div class="col-lg-3 col-sm-6">
                      <section class="panel">
                          <div class="symbol">
                              <i class="fa fa-user"></i>
                          </div>
                          <div class="value">
                              <h1 class=" count4"><?php echo $total_guru; ?></h1>
                              <p>Total Artist</p>
                          </div>
                      </section>
                  </div>
                   <div class="col-lg-3 col-sm-6">
                      <section class="panel">
                          <div class="symbol terques">
                              <i class="fa fa-users"></i>
                          </div>
                          <div class="value">
                              <h1 class="count"><?php echo round($total_tran,0); ?></h1>
                              <!-- <p>Transcation Section</p> -->
                              <a href="<?php echo AUTH_PANEL_URL . 'course_transactions/index?status=complete'; ?>"><span class="bold">Total Transaction</span></a>
                          </div>
                      </section>
                  </div>
                  <!-- <div class="col-lg-3 col-sm-6">
                    <section class="panel">
                        <div class="symbol" >
                            <i class="fa fa-edit"></i>
                        </div>
                        <div class="value">
                            <h1 class=" count4"><?php echo $total_news; ?></h1>
                            <p>Total News</p>
                        </div>
                    </section>
                  </div> -->
              </div>
            <?php } ?>
  <div class="clearfix"></div>

  <script type="text/javascript">
    function load_dashboard() {
        jQuery.ajax({
            url: "<?= AUTH_PANEL_URL ?>admin/ajax_dashboard_detail",
            method: 'POST',
            dataType: 'json',
            success: function (data) {
                if (data) {
                    //users
                    // $('.total_users').html(data.total_student);
                    // $('.total_disable').html(data.total_disable_users);
                    // $('.total_experts').html(data.total_experts.total);
                    // $('.total_paid_course_users').html(data.total_paid_course_users);
                    // $('.get_total_free_course_users').html(data.get_total_free_course_users);

                    // //clients
                    //  $('.total_clients').html(data.total_clients.total);
                    //  $('.total_live_clients').html(data.total_live_clients.total);
                    //  $('.total_dev_clients').html(data.total_dev_clients.total);
                    // //course
                    // $('.total_courses').html(data.course_count_details.total_course);
                    // $('.total_unpublished_courses').html(data.course_count_details.total_unpublished);
                    // $('.total_published_courses').html(data.course_count_details.total_published);
                    // $('.total_purchased_course').html(data.course_count_details.total_paid_course);
                    // $('.total_free_course').html(data.course_count_details.total_free_course);

                    // //Transaction
                    // $('.total_paid_amount').html(parseFloat(data.total_paid_amount.total).toLocaleString('en-IN'));
                    // $('.total_free_amount').html(parseFloat(data.total_free_amount.total).toLocaleString('en-IN'));
                    // $('.total_transaction').html(parseFloat(data.total_transaction.total).toLocaleString('en-IN'));
                    // //test
                    // $('.total_test').html(data.video_count_details.total_test);
                    // $('.total_quiz').html(data.video_count_details.total_quiz);
                    // $('.total_published_test').html(data.video_count_details.total_published_test);
                    // $('.total_unpublished_test').html(data.video_count_details.total_unpublished_test);

                    // //Quiz
                    // $('.total_quiz').html(data.video_count_details.total_quiz);
                    // $('.total_published_quiz').html(data.video_count_details.total_published_quiz);
                    // $('.total_unpublished_quiz').html(data.video_count_details.total_unpublished_quiz);

                    // //file management
                    // $('.total_videos').html(data.video_count_details.total_videos);
                    // $('.total_pdfs').html(data.video_count_details.total_pdfs);
                    // $('.total_images').html(data.video_count_details.total_images);

                    // //users data without appid
                    // $('.total_users_data').html(data.user_count_details.total_users);
                    // $('.total_disable_users_data').html(data.user_count_details.total_disable_users_data);
                    // $('.total_paid_users').html(data.user_count_details.total_paid_users);
                    // $('.total_unpaid_users').html(data.user_count_details.total_unpaid_users);

                }
            },
        });
    }

    $( document ).ready(function() {
      $("body").on("click", ".redis_txn_clear", function () { 
          $.ajax({
              url: "<?= AUTH_PANEL_URL ?>course_product/course_transactions/redis_txn_clear",
              data: {},
              method: 'POST',
              success: function (data) {
                  let arr = ['total_users', 'total_experts', 'total_paid_course_users','get_total_free_course_users', 'total_courses', 'total_published_courses','total_unpublished_courses','total_free_course','total_unpublished_test',
                      'total_paid_amount', 'total_purchased_course', 'total_videos', 'total_pdfs', 'total_test',
                      'total_quiz', 'total_published_test', 'total_published_quiz','total_disable'];
                  $.each(arr, function (index, val) {
                      $("." + val).html('<i class="fa fa-spin fa-spinner"></i>');
                  });
                  load_dashboard();
              }
          });
      });
    });
    load_dashboard();
  </script>