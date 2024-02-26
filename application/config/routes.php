<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/

/*routing for entire project start */
$route['default_controller'] = 'auth_panel/admin/index';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
/*routing for entire project end */

/*routing for web panel start */
$route['all'] = 'web/MVF/Dashboard';
$route['dashboard-details'] = 'web/MVF/Dashboard/dashboard';
$route['user-login'] = 'web/MVF/Login_register/MVF_login';
$route['user-signup'] = 'web/MVF/Login_register/MVF_register';
$route['forgot-password'] = 'web/MVF/Login_register/forgot_password';
$route['error-404'] = 'web/MVF/Dashboard/error';
$route['MVF-index'] = 'web/MVF/Dashboard/MVF_index';
$route['video-details'] = 'web/MVF/Dashboard/video_details';
$route['profile-details'] = 'web/MVF/Dashboard/profile_details';
$route['audio-details'] = 'web/MVF/Dashboard/audio_details';
$route['play-video'] = 'web/MVF/Dashboard/play_video';
$route['pdf-details'] = 'web/MVF/Dashboard/pdf_s';
$route['youtube'] = 'web/MVF/Dashboard/youtube';
$route['watchlist-details'] = 'web/MVF/Dashboard/watchlistDetails';
$route['test'] = 'web/MVF/Dashboard/test';
$route['subscriptions'] = 'web/MVF/Subscriptions';

$route['play-episode'] = 'web/MVF/Dashboard/play_episode';
$route['play-episode-test'] = 'web/MVF/Dashboard/play_episode_test';
$route['play-episode-test-youtube'] = 'web/MVF/Dashboard/play_episode_youtube_test';


$route['play-episode-hls'] = 'web/MVF/Dashboard/play_episode_hls';

$route['bhajan/(:num)'] = 'web/home/play_bhajan_by_id/$1';
$route['news/(:num)'] = 'web/home/view_news_by_id/$1';
$route['video/(:num)'] = 'web/home/play_video_by_id/$1';
$route['testing'] = 'web/home/testing';
$route['dashboard'] = 'web/MVF/Dashboard/index';
$route['afterplay'] = 'web/MVF/Dashboard/afterplay';
$route['details'] = 'web/MVF/Dashboard/video_details';
$route['watchlist'] = 'web/MVF/Dashboard/watchlist';
$route['profile'] = 'web/MVF/Profile/index';
$route['guruji'] = 'web/MVF/Dashboard/guruji';
$route['web-series'] = 'web/MVF/Dashboard/web_series';
$route['news'] = 'web/MVF/Dashboard/news';
$route['audio-songs'] = 'web/MVF/Songs/audio_songs';
$route['video-songs'] = 'web/MVF/Songs/video_songs';
$route['audio'] = 'web/MVF/Songs/play_song';
$route['songs-details'] = 'web/MVF/Songs/songs_details';
$route['play-song'] = 'web/MVF/Songs/play_video_song';
$route['tv-serials'] = 'web/MVF/Dashboard/tv_serial';
$route['view-all'] = 'web/MVF/Dashboard/view_all';
$route['contact-us'] = 'web/MVF/Dashboard/contact';
$route['terms-conditions'] = 'web/MVF/Dashboard/terms_conditions';


$route['reality-show'] = 'web/MVF/Reality_shows/reality';
$route['reality-details'] = 'web/MVF/Reality_shows/reality_details';
$route['reality-form'] = 'web/MVF/Reality_shows/reality_form';
$route['reality-details-dancing-singing'] = 'web/MVF/Reality_shows/reality_details_dancing_singing';
// $route['search'] = 'web/MVF/Dashboard/search_result';
$route['privacy-policy'] = 'web/MVF/Dashboard/privacy_policy';
$route['hls'] = 'web/MVF/Dashboard/hls';
$route['privacy-policy-mobile'] = 'web/MVF/Dashboard/privacy_policy_mobile';
$route['user-agreement-mobile'] = 'web/MVF/Dashboard/user_agree_mobile';
$route['about-us-mobile'] = 'web/MVF/Dashboard/about_us_mobile';
$route['refund-cancellations-policy'] = 'web/MVF/Dashboard/refund_cancellations_policy';
// $route['details'] = 'web/MVF/Dashboard/details';
$route['about-us'] = 'web/MVF/Dashboard/about_us';
$route['copy-right'] = 'web/MVF/Dashboard/copyrights';
$route['save_language/(:any)/(:num)'] = 'web/MVF/Dashboard/set_cookie/$1/$2';
$route['chnage-language/(:any)'] = 'web/MVF/Dashboard/change_language/$1';
$route['payu-post'] = 'web/MVF/Subscriptions/payupost';
$route['payu-success'] = 'web/MVF/Subscriptions/buy_course_payusucc';
$route['my-plan'] = 'web/MVF/Subscriptions/myplan';
$route['payu-failed'] = 'web/MVF/Subscriptions/buy_course_payusucc';
$route['detailssearch'] = 'web/MVF/Search';
$route['watchlist'] = 'web/MVF/Watchlist/get_watchlist';
$route['chat'] = 'web/MVF/chat/chat';
$route['user-agreement'] = 'web/MVF/Dashboard/user_agree';

$route['binding-terms'] = 'web/MVF/Dashboard/binding_terms';

/*Razorpay Payment*/

$route['razorpay'] = 'web/MVF/Subscriptions/buy_subscription';
$route['verify-payment'] = 'web/MVF/Subscriptions/razor_verify';

/*routing for web panel end*/ 

/*routing for api panel start */
$route['api-panel'] = 'api_doc/Admin';
$route['data_model/videos'] = 'data_model/videos/Video_control/home_page_videos';
//$route['auth_panel/premium-plan'] = 'auth_panel/videos/premium_video/premium_plan';
/*routing for api panel end*/

/*routing for admin panel start */
$route['admin-panel'] = 'auth_panel/login';
$route['admin-panel/dashboard'] = 'auth_panel/admin/index';
$route['admin-panel/otp-authentication'] = 'auth_panel/admin/otp_authentication';
$route['admin-panel/mobile-menu-type'] = 'auth_panel/mobile_menu/menu_type';
$route['admin-panel/mobile-menu'] = 'auth_panel/mobile_menu/mobile_menu';
$route['admin-panel/edit-mobile-menu/(:num)'] = 'auth_panel/mobile_menu/edit_mobile_menu/$1';
$route['admin-panel/tv-menu'] = 'auth_panel/tv_menu/tv_menu';
$route['admin-panel/edit-tv-menu/(:num)'] = 'auth_panel/tv_menu/edit_tv_menu/$1';
$route['admin-panel/menu-item'] = 'auth_panel/menu_item/menu_item';
$route['admin-panel/edit-menu-item/(:num)'] = 'auth_panel/menu_item/menu_item/$1';
$route['admin-panel/premium-section'] = 'auth_panel/menu_master/premium_section/premium_section';
$route['admin-panel/all-users'] = 'auth_panel/web_user/all_user_list';
$route['admin-panel/android-users'] = 'auth_panel/web_user/android_user_list';
$route['admin-panel/ios-users'] = 'auth_panel/web_user/ios_user_list';
$route['admin-panel/web-users'] = 'auth_panel/web_user/android_tv_user_list';
$route['admin-panel/view-user/(:num)'] = 'auth_panel/web_user/user_profile/$1';
$route['admin-panel/delete-user/(:num)'] = 'auth_panel/web_user/delete_users/$1';
$route['admin-panel/user-login-recoard'] = 'auth_panel/web_user/user_login_recoard';


$route['admin-panel/add-artist'] = 'auth_panel/Artist/artist/add_artist';
$route['admin-panel/edit-artist/(:num)'] = 'auth_panel/Artist/artist/edit_artist/$1';
$route['admin-panel/artist-list'] = 'auth_panel/Artist/artist/artist_list';

$route['admin-panel/add-reality-show'] = 'auth_panel/Reality_show/add_reality_show';
$route['admin-panel/edit-reality-show/(:num)'] = 'auth_panel/Reality_show/edit_reality_show/$1';
$route['admin-panel/reality-list'] = 'auth_panel/Reality_show/reality_list';
$route['admin-panel/candidate-list/(:num)'] = 'auth_panel/Reality_show/candidate_list/$1';
$route['admin-panel/candidate-profile/(:num)'] = 'auth_panel/Reality_show/candidate_profile/$1';



$route['admin-panel/add-judges'] = 'auth_panel/Reality_show/add_judges';
$route['admin-panel/edit-judge/(:num)'] = 'auth_panel/Reality_show/edit_judges/$1';
$route['admin-panel/list-judges/(:num)'] = 'auth_panel/Reality_show/list_judges/$l';

$route['admin-panel/add-guru-images'] = 'auth_panel/guru/guru/add_guru_images';
$route['admin-panel/edit-guru-images/(:num)'] = 'auth_panel/guru/guru/edit_guru_images/$1';
$route['admin-panel/video-add-category'] = 'auth_panel/videos/video_control/add_category';
$route['admin-panel/video-edit-category/(:num)'] = 'auth_panel/videos/video_control/edit_category/$1';
$route['admin-panel/video-category-list'] = 'auth_panel/videos/video_control/category_list';
$route['admin-panel/add-video'] = 'auth_panel/videos/video_control/add_video';
$route['admin-panel/view-video/(:num)'] = 'auth_panel/videos/video_control/view_video/$1';
$route['admin-panel/edit-video/(:num)'] = 'auth_panel/videos/video_control/edit_video/$1';
$route['admin-panel/video-list'] = 'auth_panel/videos/video_control/video_list';
$route['admin-panel/premium-add-category'] = 'auth_panel/videos/premium_video/add_category';
$route['admin-panel/premium-edit-category/(:num)'] = 'auth_panel/videos/premium_video/edit_category/$1';
$route['admin-panel/premium-add-author'] = 'auth_panel/videos/premium_video/add_premium_author';
$route['admin-panel/premium-edit-author/(:num)'] = 'auth_panel/videos/premium_video/edit_premium_author/$1';

$route['admin-panel/premium-add-season'] = 'auth_panel/videos/premium_video/add_season';
//$route['admin-panel/premium-add-episode/(:num)'] = 'auth_panel/videos/premium_video/add_season_episode/$1';
$route['admin-panel/premium-add-plan'] = 'auth_panel/videos/premium_video/premium_plan';
$route['admin-panel/premium-edit-plan/(:num)'] = 'auth_panel/videos/premium_video/edit_plan/$1';

$route['admin-panel/add-tv-serial'] = 'auth_panel/videos/premium_tv_serials/add_tv_serial';

$route['admin-panel/show-query'] = 'auth_panel/User_query/index';


$route['admin-panel/premium-add-coupon'] = 'auth_panel/coupon/add_coupon';
$route['admin-panel/premium-edit-coupon/(:num)'] = 'auth_panel/coupon/edit_coupon/$1';
$route['admin-panel/premium-add-promocode'] = 'auth_panel/promocode/add_promocode';
$route['admin-panel/premium-edit-promocode/(:num)'] = 'auth_panel/promocode/edit_promocode/$1';
$route['admin-panel/premium-vouchers/(:num)'] = 'auth_panel/promocode/view_vouchers/$1';
$route['admin-panel/web-advertisement'] = 'auth_panel/advertisement/advertisement/add_advertisement';
$route['admin-panel/edit-web-advertisement/(:num)'] = 'auth_panel/advertisement/advertisement/edit_advertisement/$1';
$route['admin-panel/app-advertisement'] = 'auth_panel/advertisement/advertisement/add_app_advertisement';
$route['admin-panel/edit-app-advertisement/(:num)'] = 'auth_panel/advertisement/advertisement/edit_app_advertisement/$1';
$route['admin-panel/advertisement-time-slot/(:num)'] = 'auth_panel/advertisement/advertisement/add_time_slot/$1';
$route['admin-panel/add-banner'] = 'auth_panel/banner/banner/add_banner';
$route['admin-panel/edit-banner/(:num)'] = 'auth_panel/banner/banner/edit_banner/$1';
$route['admin-panel/banner-list'] = 'auth_panel/banner/banner/banner_list';

$route['admin-panel/add-album'] = 'auth_panel/album/Albums/add_album';
$route['admin-panel/edit-album/(:num)'] = 'auth_panel/album/Albums/edit_album/$1';
$route['admin-panel/album-list'] = 'auth_panel/album/Albums/list_album';


$route['admin-panel/add-songs'] = 'auth_panel/songs/Songs/add_songs';
$route['admin-panel/songs-list'] = 'auth_panel/songs/Songs/songs_list';
$route['admin-panel/video-songs-list'] = 'auth_panel/songs/Songs/video_songs_list';

$route['admin-panel/add-news'] = 'auth_panel/news/news/add_news';
$route['admin-panel/news-list'] = 'auth_panel/news/news/news_list';

$route['admin-panel/add-backend-user'] = 'auth_panel/admin/create_backend_user';
$route['admin-panel/backend-user-list'] = 'auth_panel/admin/backend_user_list';
$route['admin-panel/role-management'] = 'auth_panel/admin/make_permission_group';
$route['admin-panel/version-control'] = 'auth_panel/version_control/version/versioning';
$route['admin-panel/configuration'] = 'auth_panel/configuration/configuration/device_limit';
$route['admin-panel/social-login'] = 'auth_panel/configuration/configuration/social_login';
//$route['admin-panel/all-transaction'] = 'auth_panel/course_transactions/index?status=all';
//$route['admin-panel/pending-transaction'] = 'auth_panel/course_transactions/index?status=pending';
//$route['admin-panel/complete-transaction'] = 'auth_panel/course_transactions/index?status=complete';
//$route['admin-panel/all-uploads'] = 'auth_panel/reports/all_uploads/index?type=all';
$route['admin-panel/send-email'] = 'auth_panel/support/send_email/index';
$route['admin-panel/add-channel'] = 'auth_panel/channel/Channel_control/add_channel';
$route['admin-panel/channel-list'] = 'auth_panel/channel/Channel_control/channel_list';
$route['admin-panel/reported-chat'] = 'auth_panel/chat/chat/reported_chat';
$route['admin-panel/contact-us'] = 'auth_panel/contact/contact/contact_us_list';
$route['admin-panel/view-contact-us/(:num)'] = 'auth_panel/contact/contact/view_contact_us/$1';
$route['admin-panel/file-library-add-image'] = 'auth_panel/library/library/add_image';
$route['admin-panel/file-library-edit-image/(:num)'] = 'auth_panel/library/library/edit_image_library/$1';
$route['admin-panel/push-notification'] = 'auth_panel/bulk_messenger/push_notification/send_push_notification';
$route['admin-panel/add-api'] = 'auth_panel/api_panel/add_api';
$route['admin-panel/edit-api/(:num)'] = 'auth_panel/api_panel/edit_api/$1';
$route['admin-panel/testinggg'] = 'auth_panel/Test/notify_user';
//$route['about-us'] = 'web/home/about_us';
//$route['privacy-policy'] = 'web/home/privacy_policy';
//$route['terms-conditions'] = 'web/home/terms_conditions'; 
//$route['contact-us'] = 'web/home/contact_us';
$route['admin-panel/add-category'] = 'auth_panel/category/category/add_category';
$route['admin-panel/category-list'] = 'auth_panel/category/category/category_list';
//$route['admin-panel/edit-category/(:num)'] = 'auth_panel/category/category/edit_category/$1';

$route['admin-panel/add-sub-category'] = 'auth_panel/sub_category/sub_category/add_sub_category';
$route['admin-panel/edit-sub-category/(:num)'] = 'auth_panel/sub_category/sub_category/edit_sub_category/$1';

$route['admin-panel/add-video'] = 'auth_panel/guruji/guruji_video/add_g_video';
$route['admin-panel/list-video'] = 'auth_panel/guruji/guruji_video/list_g_video';
$route['admin-panel/edit-guruji-video/(:num)'] = 'auth_panel/guruji/guruji_video/edit_g_video/$1';

$route['admin-panel/add-audio'] = 'auth_panel/guruji/guruji_video/add_g_audio';
$route['admin-panel/list-audio'] = 'auth_panel/guruji/guruji_video/list_g_audio';
$route['admin-panel/edit-guruji-audio/(:num)'] = 'auth_panel/guruji/guruji_video/edit_g_audio/$1';

$route['admin-panel/add-pdf'] = 'auth_panel/guruji/guruji_video/add_g_pdf';
$route['admin-panel/list-pdf'] = 'auth_panel/guruji/guruji_video/list_g_pdf';
$route['admin-panel/edit-guruji-pdf/(:num)'] = 'auth_panel/guruji/guruji_video/edit_g_pdf/$1';

$route['admin-panel/add-movies'] = 'auth_panel/movies/movies/add_movies';
$route['admin-panel/list-movies'] = 'auth_panel/movies/movies/movies_list';
$route['admin-panel/edit-movies/(:num)'] = 'auth_panel/movies/movies/edit_movie/$1';


$route['admin-panel/add-season'] = 'auth_panel/season/seasonController/add_season';
$route['admin-panel/season-list'] = 'auth_panel/season/season_list';



/*routing for admin panel end*/
