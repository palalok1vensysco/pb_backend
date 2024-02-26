<aside>
    <div id="sidebar" class="nav-collapse ">
        <!-- sidebar menu start-->
        <ul class="sidebar-menu" id="nav-accordion">
            <!-- =========================== DASHBOARD MENU =================================-->
            <li>
                <a class="" href="<?= AUTH_PANEL_URL . 'admin/index'; ?>">
                    <i class="fa fa-dashboard"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <!--################################ MOBILE USER MENU ################################################-->
            <li class="sub-menu dcjq-parent-li ">
                <a href="javascript:;" class="">
                    <i class="fa fa-user"></i>
                    <span>User Management</span>
                    <span class="dcjq-icon"></span>
                </a>
                <ul class="sub">
                    <li class=""><a href="<?= AUTH_PANEL_URL . 'web_user/all_user_list?user=all'; ?>"><i class="fa fa-users"></i>All</a></li>
                    <li class="hide"><a href="<?= AUTH_PANEL_URL . 'web_user/all_user_list?user=android'; ?>"><i class="fa fa-android"></i>Android</a></li>
                    <li class="hide"><a href="<?= AUTH_PANEL_URL . 'web_user/all_user_list?user=ios'; ?>"><i class="fa fa-apple"></i>iOS</a></li>
                    <li class="hide"><a href="<?= AUTH_PANEL_URL . 'web_user/all_user_list?user=windows'; ?>"><i class="fa fa-windows"></i>Windows</a></li>
                    <li class="hide"><a href="<?= AUTH_PANEL_URL . "web_user/user_activation"; ?>"><i class="fa fa-user" aria-hidden="true"></i>
                            Generate Activation Key</a></li>
                </ul>

            </li>
            <!--################################ MOBILE USER MENU ################################################-->



            <li class="sub-menu">
                <a class="" href="javascript:;">
                    <i class="fa fa-sitemap "></i>
                    <span>Category Management</span>
                </a>
                <ul class="sub">
                    <li><a href="<?= AUTH_PANEL_URL . "category/category/add_category" ?>"><i class="fa fa-plus"></i><span>Add/List Category</span></a></li>
                    <li><a href="<?= AUTH_PANEL_URL . "sub_category/sub_category/add_sub_category" ?>"><i class="fa fa-plus"></i><span>Add Genres</span></a></li>
                    <li><a href="<?= AUTH_PANEL_URL . "category/category/map_category" ?>"><i class="fa fa-plus"></i><span>Map Category</span></a></li>


                </ul>

            </li>
            <!-- ########################### state management #####################################--
                        <li class="sub-menu">
                <a class="" href="javascript:;">
                    <i class="fa fa-sitemap "></i>
                    <span>State Management</span>
                </a>
                <ul class="sub">
                     <li><a href="<?= AUTH_PANEL_URL . "State/index" ?>"><i class="fa fa-plus"></i><span>Add state</span></a></li>
                       <li><a href="<?= AUTH_PANEL_URL . "district/Add_district/map_district" ?>"><i class="fa fa-plus"></i><span>Map state & District</span></a></li>
                    <li><a href="<?= AUTH_PANEL_URL . "district/Add_district/add_district" ?>"><i class="fa fa-plus"></i><span>Add district</span></a></li>
                  
                    
                </ul>

            </li>
             ########################### state management #####################################-->

            <!-- end  -->

            <li class="sub-menu ">
                <a class="" href="javascript:;">
                    <i class="fa fa-users "></i>
                    <span>Artist Management</span>
                </a>
                <ul class="sub">
                    <li><a href="<?= AUTH_PANEL_URL . "Artist/artist/add_artists_type" ?>"><i class="fa fa-user"></i><span>Add Artist Type</span></a></li>
                    <li><a href="<?= AUTH_PANEL_URL . "Artist/artist/add_artist" ?>"><i class="fa fa-user"></i><span>Add Artist</span></a></li>
                    <li><a href="<?= AUTH_PANEL_URL . "Artist/artist/artist_list" ?>"><i class="fa fa-list"></i><span>View Artist List</span></a></li>
                </ul>
            </li>

            <!--################################ Subscription Management ################################################-->
            <?php if (APP_ID != 10 && 1 == 0) { ?>
                <li class="sub-menu">
                    <a href="javascript:;">
                        <i class="fa fa-user"></i>
                        <span>Subscription Management</span>
                    </a>
                    <ul class="sub">
                        <li><a href="<?= AUTH_PANEL_URL . "videos/premium_video/premium_plan" ?>">
                                <i class="fa fa-rocket"></i>
                                <span>Subscription</span></a></li>
                    </ul>
                    <ul class="sub">
                        <li><a href="<?= AUTH_PANEL_URL . "videos/premium_video/country" ?>">
                                <i class="fa fa-list"></i>
                                <span>Country Price</span></a></li>
                    </ul>
                </li>
            <?php } ?>


            <li class="sub-menu hide">
                <a class="" href="javascript:;"><i class="fa fa-credit-card"></i><span>WebSeries Management</span></a>
                <ul class="sub">
                    <li class=""><a href="<?= AUTH_PANEL_URL . 'videos/premium_video/add_season'; ?>"><span><i class="fa fa-sitemap"></i>List Season</span></a></li>
                </ul>
            </li>

            <li class="sub-menu hide">
                <a class="" href="javascript:;"><i class="fa fa-sitemap"></i><span> TV Serials</span></a>
                <ul class="sub">
                    <li class=""><a href="<?= AUTH_PANEL_URL . 'videos/premium_tv_serials/add_tv_serial'; ?>"><span><i class="fa fa-sitemap"></i>List TV Serials</span></a></li>
                </ul>
            </li>

            <li class="sub-menu ">
                <a class="" href="javascript:;"><i class="fa fa-link"></i><span>Aggregator Management</span></a>
                <ul class="sub">
                    <li class=""><a href="<?= AUTH_PANEL_URL . 'aggregator/Aggregator/add_aggregator'; ?>">Add/View Aggregator</a></li>

                </ul>
            </li>


            <!-- #####################  File Management   ##########################  -->

            <li class="sub-menu">
                <a class="" href="javascript:;"><i class="fa fa-video-camera"></i><span>Content Management</span></a>
                <ul class="sub">
                    <li class=""><a href="<?= AUTH_PANEL_URL . 'contentManagement/ContentManagementController/add_content'; ?>">Add Content </a></li>
                    <li class=""><a href="<?= AUTH_PANEL_URL . 'contentManagement/SeasonController/list_content'; ?>">Content List</a></li>
                </ul>
            </li>


            <!-- ##########################  Coupon managemnet  ################################################-->
            <li>
                <a class="" href="<?= AUTH_PANEL_URL . 'master/banner_management'; ?>">
                    <i class="fa fa-picture-o"></i>
                    <span>Banner Management</span>
                </a>
            </li>
            <li><a href="<?= AUTH_PANEL_URL . 'page_management/PageManagementController/page_management'; ?>"><i class="fa fa-globe"></i> Website Pages</a></li>

            <!-- ######################## Indiaott code ####################################################### -->

            <!-- ######################## Indiaott code ####################################################### -->

            <!-- ######################## Indiaott code ####################################################### -->
            <li class="sub-menu">
                <a class="" href="javascript:;"><i class="fa fa-line-chart"></i><span>Trending Content</span></a>
                <ul class="sub">
                    <li class=""><a href="<?= AUTH_PANEL_URL . 'trending/add_trending'; ?>">Add Trending</a></li>                    
                </ul>
            </li>



            <!-- ##########################  Bundle managemnet  ################################################-->
            
                <li class="sub-menu dcjq-parent-li hide">
                    <a href="javascript:;" class="dcjq-parent">
                        <i class="fa fa-comments-o"></i>
                        <span>Help Support </span>
                        <span class="dcjq-icon"></span>
                    </a>
                    <ul class="sub">
                        <li><a href="<?= AUTH_PANEL_URL . "User_query/index"; ?>">User Query</a></li>
                    </ul>
                </li>
           



            <!-- ############################audio player  ############################################ -->
            <?php if (1 == 0) { ?>
                <li class="sub-menu">
                    <a class="" href="javascript:;"><i class="fa fa-briefcase"></i><span>Audio Management</span></a>
                    <ul class="sub">
                        <li class=""><a href="<?= AUTH_PANEL_URL . 'Audio/add_audio'; ?>">Add/View Audio</a></li>
                        <li class=""><a href="<?= AUTH_PANEL_URL . 'file_manager/library/add_audio'; ?>">Add/View Audio</a></li>
                    </ul>
                </li>

                <!-- ##################### Pay Per Video File Management   ##########################  -->

                <li class="sub-menu">
                    <a class="" href="javascript:;"><i class="fa fa-briefcase"></i><span>Pay Per Video</span></a>
                    <ul class="sub">
                        <li class="hide"><a href="<?= AUTH_PANEL_URL . 'file_manager/library/index'; ?>">Add/View pdf</a></li>
                        <li class=""><a href="<?= AUTH_PANEL_URL . 'file_manager_ppv/library/add_ppvideo'; ?>">Add/View video</a></li>
                        <li class=""><a href="<?= AUTH_PANEL_URL . 'file_manager/library/add_audio'; ?>">Add/View Audio</a></li>
                        <li class="hide"><a href="<?= AUTH_PANEL_URL . 'file_manager/library/add_image'; ?>">Add/View image </a></li>
                        <li class="hide"><a href="<?= AUTH_PANEL_URL . 'file_manager/library/add_concept'; ?>">Add/View Note </a></li>
                        <li class="hide"><a href="<?= AUTH_PANEL_URL . 'file_manager/library/add_link'; ?>">Add/View link </a></li>
                        <li class="hide"><a href="<?= AUTH_PANEL_URL . 'file_manager/library/add_extra_class'; ?>">Add/View Extra Class </a></li>
                    </ul>
                </li>
            <?php } ?>

            <!-- ##########################Backend Users ################################################-->

            <li class="sub-menu dcjq-parent-li ">
                <a href="javascript:;" class="dcjq-parent">
                    <i class="fa fa-user-plus"></i>
                    <span>Admin Management</span>
                    <span class="dcjq-icon"></span>
                </a>
                <ul class="sub">
                    <li class="sub-menu dcjq-parent-li">
                        <a href="javascript:;" class="dcjq-parent">
                            <span>Backend Users</span>
                            <span class="dcjq-icon"></span>
                        </a>
                        <ul class="sub">
                            <li class=""><a href="<?= AUTH_PANEL_URL . 'admin/create_backend_user'; ?>">Add New</a></li>
                            <li class=""><a href="<?= AUTH_PANEL_URL . 'admin/backend_user_list'; ?>">View List</a></li>
                        </ul>
                    </li>

                    <li class="sub-menu">
                        <a class="" href="<?= AUTH_PANEL_URL . 'user_loger/index'; ?>">
                            <span> Backend Activity Log</span>
                        </a>
                    </li>
                    <li class=""><a href="<?= AUTH_PANEL_URL . 'admin/make_permission_group'; ?>">Role management</a></li>

                </ul>
            </li>

            <!-- ###################   Live Chanel ############################ -->

            <li class="sub-menu">
                <a class="" href="javascript:;"><i class="fa fa-adjust"></i><span>Live Module (AWS)</span></a>
                <ul class="sub">
                    <!-- <li class="" ><a href="<?= AUTH_PANEL_URL . 'live_module/inputs/index'; ?>">Input Management</a></li> -->
                    <!-- <li class="" ><a href="<?= AUTH_PANEL_URL . 'live_module/media_package/index'; ?>">Media Package Management</a></li> -->
                    <li class=""><a href="<?= AUTH_PANEL_URL . 'live_module/channels/index'; ?>">Channel Management</a></li>
                    <!-- <li class="" ><a href="<?= AUTH_PANEL_URL . 'live_module/media_package/harvest_job'; ?>">Harvest Jobs</a></li> -->
                    <li class="hide"><a href="<?= AUTH_PANEL_URL . 'live_module/studio/index'; ?>">Studio Management</a></li>
                </ul>
            </li>

            <!-- ##########################  CMS  ################################################-->
            <li class="sub-menu dcjq-parent-li ">
                <a class="" href="javascript:;"><i class="fa fa-cogs"></i><span>Configuration</span></a>
                <ul class="sub">
                    <li class=""><a href="<?= AUTH_PANEL_URL . 'version_control/version/configuration'; ?>">Setting</a></li>
                    <li class=""><a href="<?= AUTH_PANEL_URL . 'version_control/version/versioning'; ?>">Version Control</a></li>
                    <!-- <li class=""><a href="<?= AUTH_PANEL_URL . 'version_control/version/app_configuration'; ?>">App Settings</a></li> -->

                </ul>
            </li>
            <!-- ##########################  CMS  ################################################-->

            <!-- ##########################  Report  ################################################-->

            <li class="sub-menu hide">
                <a class="" href="javascript:;">
                    <i class="fa fa-envelope"></i>
                    <span>Reports</span>
                </a>
                <ul class="sub">
                    <li><a href="<?= AUTH_PANEL_URL . 'course_transactions/index?status=all'; ?>">Transactions Report</a></li>
                    <li><a href="<?= AUTH_PANEL_URL . 'course_transactions/user_report'; ?>">User Report</a></li>
                    <li><a href="<?= AUTH_PANEL_URL . 'course_transactions/usage_report'; ?>">Usage Report</a></li>
                    <li><a href="<?= AUTH_PANEL_URL . 'course_transactions/content_analytics_report'; ?>">Content Analytics Report</a></li>
                    <li><a href="<?= AUTH_PANEL_URL . 'course_transactions/platform_usage_report'; ?>">Platform Usage Report</a></li>
                    <li><a href="<?= AUTH_PANEL_URL . 'course_transactions/ads_report'; ?>"> Ads Report</a></li>
                </ul>
            </li>

            


            <?php if (1 == 0) { ?>
                <li class="sub-menu hide">
                    <a class="" href="javascript:;">
                        <i class="fa fa-envelope"></i>
                        <span>Messenger</span>
                    </a>
                    <ul class="sub">
                        <li class=""><a href="<?= AUTH_PANEL_URL . 'mailer/send_email'; ?>">Email Template</a></li>
                        <li class=""><a href="<?= AUTH_PANEL_URL . 'mailer/addsmstype'; ?>">Sms management</a></li>
                        <!--<li class="" ><a href="<?= AUTH_PANEL_URL . 'bulk_messenger/bulk_email/send_bulk_email'; ?>">Email</a></li>-->
                        <!--<li class="" ><a href="<?= AUTH_PANEL_URL . 'bulk_messenger/push_notification/send_announcement'; ?>">Announcement</a></li>-->
                    </ul>
                </li>
            <?php } ?>

            <!-- ##########################  Report  ################################################-->
            <li class="sub-menu dcjq-parent-li ">
                <a href="" class="dcjq-parent"><i class="fa fa-bell"></i> Push Notification <span class="dcjq-icon"></span></a>
                <ul class="sub">
                    <li><a href="<?= AUTH_PANEL_URL . 'bulk_messenger/push_notification/send_push_notification'; ?>">Push Notification</a></li>
                    <li><a href="<?= AUTH_PANEL_URL . 'api_panel/add_api'; ?>">Api master</a></li>
                    <li><a href="<?= AUTH_PANEL_URL . 'bulk_messenger/push_notification/complete_notification'; ?>">Complete Notification</a></li>
                    <li class="" ><a href="<?= AUTH_PANEL_URL . 'bulk_messenger/push_notification/notification_scheduler'; ?>">Push Notification Scheduler</a></li>
                </ul>
            </li>

            <!-- =========================== FAST CHANNEL  MENU =================================-->
            <li class="hide">
                <a class="" href="<?= AUTH_PANEL_URL . 'Fast_channel/fast_channel_list'; ?>">
                    <i class="fa fa-dashboard"></i>
                    <span>Fast Channel</span>
                </a>
            </li>
            <!-- =========================== FAST CHANNEL  MENU =================================-->
            <li class="hide">
                <a class="" href="<?= AUTH_PANEL_URL . 'analytics/index'; ?>">
                    <i class="fa fa-dashboard"></i>
                    <span>Analytics</span>
                </a>
            </li>


            <!--  <li class="sub-menu dcjq-parent-li">
                <a href="" class="dcjq-parent"><i class="fa fa-user"></i> User Support <span class="dcjq-icon"></span></a>
                <ul class="sub" >
                     <li><a href="<?= AUTH_PANEL_URL . 'user_support/category_management'; ?>">Suggestion Section</a></li> 
                    <li><a href="<?= AUTH_PANEL_URL . 'user_support/feedback'; ?>">Feedback  Section</a></li>
                     <li><a href="<?= AUTH_PANEL_URL . 'user_support/suggestions'; ?>">Suggestion  Section</a></li>
                    <li class="" ><a href="<?= AUTH_PANEL_URL . 'user_support/complaint'; ?>">Complaint Section </a></li>  
                </ul>
            </li> -->
        </ul>
        <!-- sidebar menu end-->
    </div>
</aside>
<script>
    var selector = $("a[href='<?= FULL_URL ?>']");
    if (selector.parent().parent().hasClass("sub") == true) {
        selector.parent().addClass("active");
        selector = selector.parent().parent();
        selector.siblings("a").addClass("active");
        if (selector.parent().parent().hasClass("sub") == true) {
            selector.parent().parent().siblings("a").addClass("active");
        }
    } else {
        selector.addClass("active");
    }

    var permissions = JSON.parse(`<?= json_encode($GLOBALS['perm']) ?>`);
    $(".sidebar-menu").children().each(function() {
        let main_selector = $(this);
        main_selector.find("a").each(function() {
            let selector = $(this);
            let href = selector.attr("href");
            if (href == "javascript:void(0)" || href == "javascript:;" || href == "") {
                return;
            }
            let remove = true;
            $.each(permissions, function(index, perm_href) {
                if (remove == true && href.indexOf(perm_href.permission_perm) != -1) {
                    remove = false;
                }
            });

            if (remove == true) {
                if (selector.closest("li").siblings().length == 0) {
                    selector.parent("li").remove();
                } else {
                    selector.parent("li").remove();
                }
            }
        });

        if (main_selector.find("ul").html() != undefined) {
            if (main_selector.find("ul").children().length == 0) {
                main_selector.remove();
            } else if (main_selector.find("ul ul").children().length == 0) {
                main_selector.children().each(function() {
                    if (main_selector.find("ul").html() != undefined) {
                        if (main_selector.find("ul").children().length == 0) {
                            main_selector.remove();
                        } else if (main_selector.find("ul ul").children().length == 0) {
                            main_selector.find("li").each(function() {
                                let sub_li = $(this);
                                if (sub_li.find("ul").length == 0) {} else if (sub_li.find("ul").children().length == 0) {
                                    sub_li.remove();
                                }
                            });
                        }
                    }
                });
            }
        }
    });
</script>