<style>
    .apiselementlink {
        height: calc(100vh - 4rem);
        overflow-y: auto;
        position: sticky;
        top: 8rem;
    }
    .api-cat li{
        font-size:11px;
        padding:0px 5px;
    }
</style>
<div class="apiselementlink col-md-3 mx-0">
    <section class="panel">
        <header class="panel-heading">
            API's LIST
        </header>
        <div class="panel-body">
            <input type="text"  onkeyup="searchapi($(this).val())" class="form-control input-xs" placeholder="Keyword Search">
        </div>
        <div class="panel-body">
            <ul class="nav api-cat">
            </ul>
        </div>
    </section>


</div>
<div class="api_file_meta col-md-9 mx-0">
    <pre style="font-family: Verdana;">
Base URL (client) -:<?= base_url(); ?>index.php/data_model/


Policy URL -:<?= base_url(); ?>index.php/data_model/master_hit/policies
Terms URL -:<?= base_url(); ?>index.php/data_model/master_hit/terms
Refund Policy URL -:<?= base_url(); ?>index.php/data_model/master_hit/refund
About Us URL -:<?= base_url(); ?>index.php/data_model/master_hit/about
Contact Us URL -:<?= base_url(); ?>index.php/data_model/master_hit/contact
Calculator URL -:<?= base_url(); ?>index.php/data_model/test/mobile_calculator
License DRM URL -:<?= base_url(); ?>index.php/data_model/meta_distributer/on_request_drm_license

PLEASE SEND IN API HEADER To stop double session for user 
Lang:1 //1-English,2-Hindi
Version:1
Devicetype:1 //1-Android,2-iOS,3-Windows
Userid:1 //If you have
Jwt:sdfsdsfdsf


//API Codes For Caching
UT_CODE_1001: 11


################################  GET VERSION ########################
<h4>1) GET VERSION</h4>

	Method -:      Post 
	Service url -: version/get_version

	Input parameter -:
	##input_param_start## 	
            
	##input_param_end##
	Response -:

	True case 
	##output_code_start## 
	{
            "status": true,
            "message": "Version Info",
            "time": 1633440723,
            "data": {
                "id": "1",
                "version": "1",
                "min_version": "1",
                "force_update": "0",
                "app_url": "",
                "break_to": "0",
                "break_from": "0",
                "hide_tabs":0 //if 1 then hide paid tabs
            }
        }
	##output_code_end## 

	False case 
	##output_code_start## 
	{"status":true,"message":"Version not found","data":{}}
	##output_code_end##
DIVIDER-SEGMENT

################################  SEND VERIFICATION OTP ########################
<h4>2) SEND OTP ON MOBILE</h4>

	Method -:      Post 
	Service url -: users/send_verification_otp

	Input parameter -:
	##input_param_start## 	
            mobile:9582163098
            resend:0 //0-no,1-yes
            is_registration:1 //if registration then 1 else 0
            otp:592999 //if have otp then OTP else blank
	##input_param_end##
	Response -:

	True case 
	##output_code_start## 
	{"status":true,time": 1633440723,"message":"OTP Sent/Verified","data":[]}	
	##output_code_end## 

	False case 
	##output_code_start## 
	{"status":true,"message":"OTP Failed","data":[]}
	##output_code_end##
DIVIDER-SEGMENT

################################  GET STATES ########################
<h4>3) GET STATES</h4>

	Method -:      Post 
	Service url -: master_hit/get_states

	Input parameter -:
	##input_param_start## 	
            
	##input_param_end##
	Response -:

	True case 
	##output_code_start## 
	{
            "status": true,
            "message": "States Displayed",
            "time": 1633440723,
            "data": [
                {
                    "id": "1",
                    "name": "Andaman and Nicobar Islands"
                }
            ]
        }
	##output_code_end## 

	False case 
	##output_code_start## 
	{"status":false,"message":"Version not found","data":[]}
	##output_code_end##
DIVIDER-SEGMENT

################################  GET CITIES ########################
<h4>4) GET CITIES</h4>

	Method -:      Post 
	Service url -: master_hit/get_cities

	Input parameter -:
	##input_param_start## 	
            state_id:1
	##input_param_end##
	Response -:

	True case 
	##output_code_start## 
	{
            "status": true,
            "message": "Cities Displayed",
            "time": 1633440723,
            "data": [
                {
                    "id": "1",
                    "name": "Bombuflat"
                }
            ]
        }
	##output_code_end## 

	False case 
	##output_code_start## 
	{"status":false,"message":"Cities not found","data":[]}
	##output_code_end##
DIVIDER-SEGMENT

################################  REGISTRATION ########################
<h4>5) REGISTRATION</h4>

	Method -:      Post 
	Service url -: users/registration

	Input parameter -:
	##input_param_start## 	
            name:Mohit
            device_id:1
            email:mohit1@gmail.com
            is_social:0
            city:1
            state:1
            otp:870399 //optional if not have
            mobile:9716247619
            password:12345678
            device_token:12345678,
            location:{
                lat:""
                lng:""
                ip:""
                os_version:""
                device_model:""
                manufacturer:""
            }
	##input_param_end##
	Response -:

	True case 
	##output_code_start## 
        {
            "status": true,
            "message": "User authentication successful.",
            "time": 1633440723,
            "data": {
                "jwt": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6NTAzNjU4NywiZGV2aWNlX3R5cGUiOm51bGwsInZlcnNpb25fY29kZSI6IjEiLCJpYXQiOjE2MzM0NDA3MjMsImV4cCI6MTYzNTYwMDcyM30.FCms5PqW2_fgV7_gn6WeeTqT-qVvqfdwMEFdxRGcDFY"
            }
        }
	##output_code_end## 

	False case 
	##output_code_start## 
	{"status":true,"message":"OTP Failed","data":[]}
	##output_code_end##
DIVIDER-SEGMENT

################################  LOGIN AUTH ########################
<h4>6) LOGIN AUTH</h4>

	Method -:      Post 
	Service url -: users/login_auth

	Input parameter -:
	##input_param_start##
            device_id:1
            mobile:1111111111
            is_social:0
            password:12345678,
            location:{
                lat:""
                lng:""
                ip:""
                os_version:""
                device_model:""
                manufacturer:""
            }
	##input_param_end##
	Response -:

	True case 
	##output_code_start## 
        {
            "status": true,
            "message": "User authentication successful.",
            "time": 1633440723,
            "data": {
                "jwt": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6NTAzNjU4NywiZGV2aWNlX3R5cGUiOm51bGwsInZlcnNpb25fY29kZSI6IjEiLCJpYXQiOjE2MzM0NDA3MjMsImV4cCI6MTYzNTYwMDcyM30.FCms5PqW2_fgV7_gn6WeeTqT-qVvqfdwMEFdxRGcDFY"
            }
        }
	##output_code_end## 

	False case 
	##output_code_start## 
	{"status":true,"message":"Login Failed","data":[]}
	##output_code_end##
DIVIDER-SEGMENT

################################  GET MY PROFILE ########################
<h4>7) GET MY PROFILE</h4>

	Method -:      Post 
	Service url -: users/get_my_profile

	Input parameter -:
	##input_param_start##

	##input_param_end##
	Response -:

	True case 
	##output_code_start## 
        {
            "status": true,
            "message": "User profile.",
            "time": 1633440723,
            "data": {
                "id": "5036587",
                "name": "Mohit",
                "profile_picture": "",
                "email": "mohit1@gmail.com",
                "username": "",
                "c_code": "+91",
                "mobile": "9716247619",
                "device_id": "1",
                "creation_time": "1633440723",
                "status": "0",
                "state": "1",
                "city": "1",
                "lang": "0"
            }
        }
	##output_code_end## 

	False case 
	##output_code_start## 
	{"status":true,"message":"Login Failed","data":[]}
	##output_code_end##
DIVIDER-SEGMENT
################################  UPDATE PASSWORD ########################
<h4>8) UPDATE PASSWORD</h4>

	Method -:      Post 
	Service url -: users/update_password

	Input parameter -:
	##input_param_start##
            otp:450998 //if have
            mobile:9582163098
            password:12345678
	##input_param_end##
	Response -:

	True case 
	##output_code_start## 
        {"status":true,"time": 1633440723,"message":"Password updated.","data":[]}
	##output_code_end## 

	False case 
	##output_code_start## 
	{"status":false,"message":"Not able to update password.","data":[]}
	##output_code_end##
DIVIDER-SEGMENT

################################ GET MASTER HIT ########################
<h4>9) GET MASTER HIT</h4>

	Method -:      Post 
	Service url -: master_hit/content

	Input parameter -:
	##input_param_start##
            
	##input_param_end##
	Response -:

	True case 
	##output_code_start## 
        {
            "status": true,
            "message": "Master hit content",
            "time": 1633440723,
            "data": {
                "all_cat": [
                    {
                        "id": "1",
                        "name": "CBSE",
                        "parent_id": "0",
                        "master_type":"2"
                    }
                ],
                "master_cat": [
                    {
                        "id":"1",
                        "cat": "School"
                    }
                ],
                "course_type_master": [
                    {
                        "id": "1",
                        "name": "Online Class"
                    }
                ],
                "notification": {
                    "count": "66"
                }
            }
        }
	##output_code_end## 

	False case 
	##output_code_start## 
	{"status":false,"message":"Not able to update password.","data":[]}
	##output_code_end##
DIVIDER-SEGMENT

################################ GET DASHBOARD DATA ########################
<h4>10) GET DASHBOARD DATA</h4>

	Method -:      Post 
	Service url -: dashboard/dashboard/

	Input parameter -:
	##input_param_start##
         
	##input_param_end##
	Response -:

	True case 
	##output_code_start## 
    {
    "status": true,
    "message": "Data listed.",
    "data": {
        "content_list": [
            {
                "id": "1",
                "type_id": "1",
                "category_name": "Movies",
                "listing": [
                    {
                        "id": "153",
                        "type_id": "1",
                        "type": "Movies",
                        "title": "john carter",
                        "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/710541/1661848225167_425261771928072060_video_VOD.m3u8",
                        "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/710541/1661848225167_425261771928072060_video_VOD.m3u8",
                        "language": "",
                        "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/5456949153_a%20the%20p.jpg",
                        "app_id": "10",
                        "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/5287202153_a%20the%20l.jpg",
                        "description": "john carter\r\n            ",
                        "category_id": "1",
                        "genres_type": "3",
                        "genres_name": "3",
                        "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/710627/1661855514710_496246933437449700_video_VOD.m3u8",
                        "subscription": "0",
                        "is_free": "0",
                        "video_type": "7",
                        "url_type": "2",
                        "ppv_amount": "10",
                        "validity": "360",
                        "ppv_status": "0",
                        "is_purchased": "0",
                        "ppv_paid": "0"
                    },
                    {
                        "id": "152",
                        "type_id": "1",
                        "type": "Movies",
                        "title": "lage raho munna bhai",
                        "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/710541/1661848225167_425261771928072060_video_VOD.m3u8",
                        "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/710541/1661848225167_425261771928072060_video_VOD.m3u8",
                        "language": "",
                        "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/4197903152_lage%20raho%20munna%20bhai%20p.jpg",
                        "app_id": "10",
                        "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/723541152_lage%20raho%20munna%20bhai%20l.jpg",
                        "description": "An elderly man who’s been pushed around by a government official for far too long resorts to ‘Gandhigiri’ (Gandhiism) to achieve his means – he publicly shames the corrupt official by stripping off his clothes.     ",
                        "category_id": "1",
                        "genres_type": "1",
                        "genres_name": "1",
                        "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/90195/test/1656657382599_207102464443241500_video_VOD.m3u8",
                        "subscription": "1",
                        "is_free": "1",
                        "video_type": "7",
                        "url_type": "2",
                        "ppv_amount": "19",
                        "validity": "360",
                        "ppv_status": "1",
                        "is_purchased": "1",
                        "ppv_paid": "0"
                    },
                    {
                        "id": "151",
                        "type_id": "1",
                        "type": "Movies",
                        "title": "Bhotbhoti ",
                        "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
                        "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
                        "language": "",
                        "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/6442997151_banner-thumbnail-background-graphic-design-260nw-2050938644.webp",
                        "app_id": "10",
                        "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/604372151_vs-youtube-thumbnail-background-download-11626402992ahtdgvlgtc.jpg",
                        "description": "JJHKJHKSJ",
                        "category_id": "1",
                        "genres_type": "1",
                        "genres_name": "1",
                        "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141941/1659965493661_577564927403321900_video_VOD.m3u8",
                        "subscription": "1",
                        "is_free": "1",
                        "video_type": "7",
                        "url_type": "2",
                        "ppv_amount": "100",
                        "validity": "360",
                        "ppv_status": "1",
                        "is_purchased": "1",
                        "ppv_paid": "0"
                    },
                    {
                        "id": "145",
                        "type_id": "1",
                        "type": "Movies",
                        "title": "EARTH",
                        "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/90195/test/1656657382599_207102464443241500_video_VOD.m3u8",
                        "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/90195/test/1656657382599_207102464443241500_video_VOD.m3u8",
                        "language": "",
                        "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/1275318145_azhar%20l.jpg",
                        "app_id": "10",
                        "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/2776360145_AZHAR%20LL.jpg",
                        "description": "AZHAR               ",
                        "category_id": "1",
                        "genres_type": "1",
                        "genres_name": "1",
                        "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
                        "subscription": "1",
                        "is_free": "1",
                        "video_type": "7",
                        "url_type": "2",
                        "ppv_amount": "1",
                        "validity": "360",
                        "ppv_status": "1",
                        "is_purchased": "1",
                        "ppv_paid": "0"
                    },
                    {
                        "id": "143",
                        "type_id": "1",
                        "type": "Movies",
                        "title": "THE lostcity",
                        "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/710628/1661855558559_312360552746000300_video_VOD.m3u8",
                        "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/710628/1661855558559_312360552746000300_video_VOD.m3u8",
                        "language": "",
                        "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/1813070143_lostcity%20p.jpg",
                        "app_id": "10",
                        "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/7109785143_lostcity%20l.jpg",
                        "description": "The lost  City            ",
                        "category_id": "1",
                        "genres_type": "2",
                        "genres_name": "2",
                        "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/710541/1661848225167_425261771928072060_video_VOD.m3u8",
                        "subscription": "0",
                        "is_free": "0",
                        "video_type": "7",
                        "url_type": "2",
                        "ppv_amount": "11",
                        "validity": "360",
                        "ppv_status": "0",
                        "is_purchased": "0",
                        "ppv_paid": "0"
                    },
                    {
                        "id": "142",
                        "type_id": "1",
                        "type": "Movies",
                        "title": "M.S. Dhoni The Untold Story",
                        "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/710518/1661846582897_812646299440107800_video_VOD.m3u8",
                        "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/710518/1661846582897_812646299440107800_video_VOD.m3u8",
                        "language": "",
                        "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/7560087142_ms%20dhoni%20pp.jpg",
                        "app_id": "10",
                        "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/2311061142_dhoni%20l.jpg",
                        "description": "Indian captain  ",
                        "category_id": "1",
                        "genres_type": "3",
                        "genres_name": "3",
                        "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/710541/1661848225167_425261771928072060_video_VOD.m3u8",
                        "subscription": "0",
                        "is_free": "0",
                        "video_type": "7",
                        "url_type": "2",
                        "ppv_amount": "99",
                        "validity": "360",
                        "ppv_status": "0",
                        "is_purchased": "0",
                        "ppv_paid": "0"
                    },
                    {
                        "id": "139",
                        "type_id": "1",
                        "type": "Movies",
                        "title": "Van Helsing",
                        "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/710518/1661846582897_812646299440107800_video_VOD.m3u8",
                        "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/710518/1661846582897_812646299440107800_video_VOD.m3u8",
                        "language": "",
                        "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/2730208139_van.jpg",
                        "app_id": "10",
                        "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/3788632139_vanhel.jpg",
                        "description": "Tons of awesome van helsing dracula wallpapers to download for free.",
                        "category_id": "1",
                        "genres_type": "2",
                        "genres_name": "2",
                        "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/710518/1661846582897_812646299440107800_video_VOD.m3u8",
                        "subscription": "0",
                        "is_free": "0",
                        "video_type": "7",
                        "url_type": "2",
                        "ppv_amount": "1",
                        "validity": "360",
                        "ppv_status": "0",
                        "is_purchased": "0",
                        "ppv_paid": "0"
                    },
                    {
                        "id": "136",
                        "type_id": "1",
                        "type": "Movies",
                        "title": "Amityville",
                        "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/137185/1659073371526_253944938181943500_video_VOD.m3u8",
                        "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/137185/1659073371526_253944938181943500_video_VOD.m3u8",
                        "language": "",
                        "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/2306081136_amityville%20p.jpg",
                        "app_id": "10",
                        "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/5559047136_amityville%20l.jpg",
                        "description": "It has become one of the most famous paranormal photos of all time.",
                        "category_id": "1",
                        "genres_type": "2",
                        "genres_name": "2",
                        "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/137185/1659073371526_253944938181943500_video_VOD.m3u8",
                        "subscription": "0",
                        "is_free": "0",
                        "video_type": "7",
                        "url_type": "2",
                        "ppv_amount": "1",
                        "validity": "360",
                        "ppv_status": "0",
                        "is_purchased": "0",
                        "ppv_paid": "0"
                    },
                    {
                        "id": "135",
                        "type_id": "1",
                        "type": "Movies",
                        "title": "M.S. Dhoni The Untold Story",
                        "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/710518/1661846582897_812646299440107800_video_VOD.m3u8",
                        "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/710518/1661846582897_812646299440107800_video_VOD.m3u8",
                        "language": "",
                        "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/5509059135_ms%20dhoni%20p.jpg",
                        "app_id": "10",
                        "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/7388124135_dhoni%20l.jpg",
                        "description": "Our #Singles Squad is all set to sneak in girls for a dhamakedaar house party! But how will they escape Sanskari landlord, Mishra and Haraami Seniors? Find out in Episode 01 now!       ",
                        "category_id": "1",
                        "genres_type": "1",
                        "genres_name": "1",
                        "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/710518/1661846582897_812646299440107800_video_VOD.m3u8",
                        "subscription": "0",
                        "is_free": "0",
                        "video_type": "7",
                        "url_type": "2",
                        "ppv_amount": "1",
                        "validity": "360",
                        "ppv_status": "0",
                        "is_purchased": "0",
                        "ppv_paid": "0"
                    },
                    {
                        "id": "133",
                        "type_id": "1",
                        "type": "Movies",
                        "title": "Zudgement Day AZHAR",
                        "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/710518/1661846582897_812646299440107800_video_VOD.m3u8",
                        "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/710518/1661846582897_812646299440107800_video_VOD.m3u8",
                        "language": "",
                        "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/2003965133_azhar%20p.jpg",
                        "app_id": "10",
                        "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/874623133_AZHAR%20LL.jpg",
                        "description": "Azhar is a 2016 Indian Hindi biographical sports drama film directed by Tony D'Souza. The story and is inspired from the life of Indian cricketer and former ............     ",
                        "category_id": "1",
                        "genres_type": "1",
                        "genres_name": "1",
                        "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/710518/1661846582897_812646299440107800_video_VOD.m3u8",
                        "subscription": "0",
                        "is_free": "0",
                        "video_type": "7",
                        "url_type": "2",
                        "ppv_amount": "1",
                        "validity": "360",
                        "ppv_status": "0",
                        "is_purchased": "0",
                        "ppv_paid": "0"
                    }
                ]
            },
            {
                "id": "2",
                "type_id": "2",
                "category_name": "Web  Series",
                "listing": [
                    {
                        "id": "134",
                        "type_id": "2",
                        "type": "Web Series",
                        "title": "Singles | E01 - Ladkiyan Aur House Party",
                        "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/710624/1661855437464_371398478498118600_video_VOD.m3u8",
                        "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/710624/1661855437464_371398478498118600_video_VOD.m3u8",
                        "language": "",
                        "app_id": "10",
                        "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/2989368134_singles.........jpg",
                        "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/4028667134_singles...jpg",
                        "season_name_id": "0",
                        "description": "Our #Singles Squad is all set to sneak in girls for a dhamakedaar house party! But how will they escape Sanskari landlord, Mishra and Haraami Seniors? Find out in Episode 01 now!       ",
                        "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/710624/1661855437464_371398478498118600_video_VOD.m3u8",
                        "category_id": "2",
                        "subscription": "0",
                        "is_free": "0",
                        "video_type": "7",
                        "url_type": "2",
                        "ppv_amount": "2",
                        "validity": "360",
                        "ppv_status": "0",
                        "genres_type": "1",
                        "genres_name": "1",
                        "is_purchased": "0",
                        "ppv_paid": "0"
                    },
                    {
                        "id": "123",
                        "type_id": "2",
                        "type": "Web Series",
                        "title": "I Am Not Okay with This",
                        "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
                        "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
                        "language": "",
                        "app_id": "10",
                        "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/4369824123_dea%20p.jpg",
                        "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/512013123_death%20l.jpg",
                        "season_name_id": "0",
                        "description": "I Am Not Okay with This ",
                        "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
                        "category_id": "2",
                        "subscription": "0",
                        "is_free": "0",
                        "video_type": "7",
                        "url_type": "2",
                        "ppv_amount": "2",
                        "validity": "360",
                        "ppv_status": "0",
                        "genres_type": "2",
                        "genres_name": "2",
                        "is_purchased": "0",
                        "ppv_paid": "0"
                    },
                    {
                        "id": "122",
                        "type_id": "2",
                        "type": "Web Series",
                        "title": "Santa Clarita Diet ",
                        "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
                        "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
                        "language": "",
                        "app_id": "10",
                        "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/5443190122_sil%20p.jpg",
                        "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/2513707122_sl%201.jpg",
                        "season_name_id": "0",
                        "description": "Santa Clarita Diet  ",
                        "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
                        "category_id": "2",
                        "subscription": "0",
                        "is_free": "0",
                        "video_type": "7",
                        "url_type": "2",
                        "ppv_amount": "2",
                        "validity": "360",
                        "ppv_status": "0",
                        "genres_type": "2",
                        "genres_name": "2",
                        "is_purchased": "0",
                        "ppv_paid": "0"
                    },
                    {
                        "id": "121",
                        "type_id": "2",
                        "type": "Web Series",
                        "title": "Unbreakable Kimmy Schmidt",
                        "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141941/1659965493661_577564927403321900_video_VOD.m3u8",
                        "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141941/1659965493661_577564927403321900_video_VOD.m3u8",
                        "language": "",
                        "app_id": "10",
                        "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/6455232121_u%20p.jpg",
                        "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/1097375121_ul.jpg",
                        "season_name_id": "0",
                        "description": "Unbreakable Kimmy Schmidt ",
                        "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141941/1659965493661_577564927403321900_video_VOD.m3u8",
                        "category_id": "2",
                        "subscription": "0",
                        "is_free": "0",
                        "video_type": "7",
                        "url_type": "2",
                        "ppv_amount": "2",
                        "validity": "360",
                        "ppv_status": "0",
                        "genres_type": "2",
                        "genres_name": "2",
                        "is_purchased": "0",
                        "ppv_paid": "0"
                    },
                    {
                        "id": "120",
                        "type_id": "2",
                        "type": "Web Series",
                        "title": "sense 8",
                        "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141941/1659965493661_577564927403321900_video_VOD.m3u8",
                        "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141941/1659965493661_577564927403321900_video_VOD.m3u8",
                        "language": "",
                        "app_id": "10",
                        "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/1139340120_se%208p.jpg",
                        "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/6959210120_8l.jpg",
                        "season_name_id": "0",
                        "description": "Sense8 ",
                        "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141941/1659965493661_577564927403321900_video_VOD.m3u8",
                        "category_id": "2",
                        "subscription": "0",
                        "is_free": "0",
                        "video_type": "7",
                        "url_type": "2",
                        "ppv_amount": "2",
                        "validity": "360",
                        "ppv_status": "0",
                        "genres_type": "2",
                        "genres_name": "2",
                        "is_purchased": "0",
                        "ppv_paid": "0"
                    },
                    {
                        "id": "103",
                        "type_id": "2",
                        "type": "Web Series",
                        "title": "Asur: Welcome to Your Dark Side",
                        "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141941/1659965493661_577564927403321900_video_VOD.m3u8",
                        "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141941/1659965493661_577564927403321900_video_VOD.m3u8",
                        "language": "",
                        "app_id": "10",
                        "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/2523435103_asur%20p.jpg",
                        "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/5182299103_asur%20l.jpg",
                        "season_name_id": "0",
                        "description": "Asur: Welcome to Your Dark Side  ",
                        "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141941/1659965493661_577564927403321900_video_VOD.m3u8",
                        "category_id": "2",
                        "subscription": "1",
                        "is_free": "1",
                        "video_type": "7",
                        "url_type": "2",
                        "ppv_amount": "2",
                        "validity": "360",
                        "ppv_status": "1",
                        "genres_type": "3",
                        "genres_name": "3",
                        "is_purchased": "1",
                        "ppv_paid": "0"
                    },
                    {
                        "id": "102",
                        "type_id": "2",
                        "type": "Web Series",
                        "title": "Apharan",
                        "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141941/1659965493661_577564927403321900_video_VOD.m3u8",
                        "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141941/1659965493661_577564927403321900_video_VOD.m3u8",
                        "language": "",
                        "app_id": "10",
                        "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/4072151102_apharan%20p.jpg",
                        "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/6607118102_apha%20l.jpg",
                        "season_name_id": "0",
                        "description": "Apharan is a 2018 Indian Hindi-language action thriller web series directed and co-produced by Sidharth Sengupta for video on demand platform ALTBalaji. ",
                        "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141941/1659965493661_577564927403321900_video_VOD.m3u8",
                        "category_id": "2",
                        "subscription": "0",
                        "is_free": "0",
                        "video_type": "7",
                        "url_type": "2",
                        "ppv_amount": "2",
                        "validity": "360",
                        "ppv_status": "0",
                        "genres_type": "1",
                        "genres_name": "1",
                        "is_purchased": "0",
                        "ppv_paid": "0"
                    },
                    {
                        "id": "101",
                        "type_id": "2",
                        "type": "Web Series",
                        "title": "Breathe",
                        "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141722/test/1659946380107_854022091447815800_video_VOD.m3u8",
                        "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141722/test/1659946380107_854022091447815800_video_VOD.m3u8",
                        "language": "",
                        "app_id": "10",
                        "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/6938597101_bre%20p.jpg",
                        "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/7568606101_bre%20l.jpg",
                        "season_name_id": "0",
                        "description": "Breathe ",
                        "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141722/test/1659946380107_854022091447815800_video_VOD.m3u8",
                        "category_id": "2",
                        "subscription": "0",
                        "is_free": "0",
                        "video_type": "7",
                        "url_type": "2",
                        "ppv_amount": "2",
                        "validity": "360",
                        "ppv_status": "0",
                        "genres_type": "1",
                        "genres_name": "1",
                        "is_purchased": "0",
                        "ppv_paid": "0"
                    },
                    {
                        "id": "85",
                        "type_id": "2",
                        "type": "Web Series",
                        "title": "Maniritha",
                        "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141722/test/1659946380107_854022091447815800_video_VOD.m3u8",
                        "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141722/test/1659946380107_854022091447815800_video_VOD.m3u8",
                        "language": "",
                        "app_id": "10",
                        "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/461063485_download.jpg",
                        "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/630417385_thumbnail.png",
                        "season_name_id": "0",
                        "description": "This is web series content ",
                        "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141722/test/1659946380107_854022091447815800_video_VOD.m3u8",
                        "category_id": "2",
                        "subscription": "1",
                        "is_free": "1",
                        "video_type": "7",
                        "url_type": "2",
                        "ppv_amount": "0",
                        "validity": "",
                        "ppv_status": "0",
                        "genres_type": "3",
                        "genres_name": "3",
                        "is_purchased": "1",
                        "ppv_paid": "0"
                    },
                    {
                        "id": "79",
                        "type_id": "2",
                        "type": "Web Series",
                        "title": "Paatal Lok ",
                        "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141941/1659965493661_577564927403321900_video_VOD.m3u8",
                        "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141941/1659965493661_577564927403321900_video_VOD.m3u8",
                        "language": "",
                        "app_id": "10",
                        "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/448518179_patallok.jpg",
                        "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/480385779_patal%20lok%20l.jpg",
                        "season_name_id": "0",
                        "description": "Paatal Lok ",
                        "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141941/1659965493661_577564927403321900_video_VOD.m3u8",
                        "category_id": "2",
                        "subscription": "1",
                        "is_free": "1",
                        "video_type": "7",
                        "url_type": "2",
                        "ppv_amount": "0",
                        "validity": "",
                        "ppv_status": "0",
                        "genres_type": "1",
                        "genres_name": "1",
                        "is_purchased": "1",
                        "ppv_paid": "0"
                    }
                ]
            },
            {
                "id": "3",
                "type_id": "3",
                "category_name": "TV Serials",
                "listing": [
                    {
                        "id": "118",
                        "type_id": "3",
                        "type": "TV Serials",
                        "title": "Kuch Toh Hai Tere Mere Darmiyaan",
                        "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
                        "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
                        "language": "",
                        "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/5159541118_asur%20p.jpg",
                        "app_id": "10",
                        "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/1490172118_asur%20l.jpg",
                        "description": ". Pardes Mein Hai Mera Dil  ",
                        "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
                        "category_id": "3",
                        "subscription": "1",
                        "is_free": "1",
                        "video_type": "7",
                        "url_type": "2",
                        "ppv_amount": "3",
                        "validity": "360",
                        "ppv_status": "1",
                        "genres_type": "10",
                        "genres_name": "10",
                        "is_purchased": "1",
                        "ppv_paid": "0"
                    },
                    {
                        "id": "116",
                        "type_id": "3",
                        "type": "TV Serials",
                        "title": "Bhotbhoti Yeh Hai Mohabbatein ",
                        "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
                        "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
                        "language": "",
                        "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/4421093116_Yeh%20Hai%20Mohabbatein.jpg",
                        "app_id": "10",
                        "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/2420076116_Yeh%20Hai%20Mohabbatein%20l.jpg",
                        "description": "Yeh Hai Mohabbatein",
                        "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
                        "category_id": "3",
                        "subscription": "0",
                        "is_free": "0",
                        "video_type": "7",
                        "url_type": "2",
                        "ppv_amount": "3",
                        "validity": "360",
                        "ppv_status": "1",
                        "genres_type": "10",
                        "genres_name": "10",
                        "is_purchased": "0",
                        "ppv_paid": "0"
                    },
                    {
                        "id": "115",
                        "type_id": "3",
                        "type": "TV Serials",
                        "title": "Mahabharat",
                        "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
                        "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
                        "language": "",
                        "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/2835375115_maha%20bp.jpg",
                        "app_id": "10",
                        "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/5701125115_mahab%20l.jpg",
                        "description": "Mahabharat",
                        "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
                        "category_id": "3",
                        "subscription": "0",
                        "is_free": "0",
                        "video_type": "7",
                        "url_type": "2",
                        "ppv_amount": "3",
                        "validity": "360",
                        "ppv_status": "1",
                        "genres_type": "10",
                        "genres_name": "10",
                        "is_purchased": "0",
                        "ppv_paid": "0"
                    },
                    {
                        "id": "114",
                        "type_id": "3",
                        "type": "TV Serials",
                        "title": " Kahaani Ghar Ghar Kii ",
                        "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141941/1659965493661_577564927403321900_video_VOD.m3u8",
                        "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141941/1659965493661_577564927403321900_video_VOD.m3u8",
                        "language": "",
                        "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/6011406114_kahani%20p.jpg",
                        "app_id": "10",
                        "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/7328794114_khani%20ga%20l.jpg",
                        "description": " Kahaani Ghar Ghar Kii  ",
                        "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141941/1659965493661_577564927403321900_video_VOD.m3u8",
                        "category_id": "3",
                        "subscription": "0",
                        "is_free": "0",
                        "video_type": "7",
                        "url_type": "2",
                        "ppv_amount": "3",
                        "validity": "360",
                        "ppv_status": "0",
                        "genres_type": "1",
                        "genres_name": "1",
                        "is_purchased": "0",
                        "ppv_paid": "0"
                    },
                    {
                        "id": "110",
                        "type_id": "3",
                        "type": "TV Serials",
                        "title": "The kapil sharma show",
                        "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141941/1659965493661_577564927403321900_video_VOD.m3u8",
                        "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141941/1659965493661_577564927403321900_video_VOD.m3u8",
                        "language": "",
                        "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/3841186110_kppp.jpg",
                        "app_id": "10",
                        "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/4856007110_kp%20p.jpg",
                        "description": "kapil sharma show ",
                        "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141941/1659965493661_577564927403321900_video_VOD.m3u8",
                        "category_id": "3",
                        "subscription": "0",
                        "is_free": "0",
                        "video_type": "7",
                        "url_type": "2",
                        "ppv_amount": "3",
                        "validity": "360",
                        "ppv_status": "0",
                        "genres_type": "1",
                        "genres_name": "1",
                        "is_purchased": "0",
                        "ppv_paid": "0"
                    },
                    {
                        "id": "109",
                        "type_id": "3",
                        "type": "TV Serials",
                        "title": "Yeh Rishta Kya Kehlata Ha",
                        "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141941/1659965493661_577564927403321900_video_VOD.m3u8",
                        "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141941/1659965493661_577564927403321900_video_VOD.m3u8",
                        "language": "",
                        "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/2763234109_ye%20rista%20p.jpg",
                        "app_id": "10",
                        "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/2455352109_ye%20rista%20l.jpg",
                        "description": "Yeh Rishta Kya Kehlata Ha ",
                        "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141941/1659965493661_577564927403321900_video_VOD.m3u8",
                        "category_id": "3",
                        "subscription": "0",
                        "is_free": "0",
                        "video_type": "7",
                        "url_type": "2",
                        "ppv_amount": "3",
                        "validity": "360",
                        "ppv_status": "0",
                        "genres_type": "1",
                        "genres_name": "1",
                        "is_purchased": "0",
                        "ppv_paid": "0"
                    },
                    {
                        "id": "108",
                        "type_id": "3",
                        "type": "TV Serials",
                        "title": "Bhagya Lakhmi",
                        "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141941/1659965493661_577564927403321900_video_VOD.m3u8",
                        "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141941/1659965493661_577564927403321900_video_VOD.m3u8",
                        "language": "",
                        "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/6766878108_bhagya%20p.jpg",
                        "app_id": "10",
                        "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/2628340108_bhag%20l.jpg",
                        "description": "Bhagya Lakhmi",
                        "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141941/1659965493661_577564927403321900_video_VOD.m3u8",
                        "category_id": "3",
                        "subscription": "0",
                        "is_free": "0",
                        "video_type": "7",
                        "url_type": "2",
                        "ppv_amount": "3",
                        "validity": "360",
                        "ppv_status": "1",
                        "genres_type": "1",
                        "genres_name": "1",
                        "is_purchased": "0",
                        "ppv_paid": "0"
                    },
                    {
                        "id": "107",
                        "type_id": "3",
                        "type": "TV Serials",
                        "title": "Bhotbhoti ",
                        "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
                        "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
                        "language": "",
                        "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/2351235107_naag%20p.jpg",
                        "app_id": "10",
                        "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/2637402107_naag%20l.jpg",
                        "description": "Naagin",
                        "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
                        "category_id": "3",
                        "subscription": "0",
                        "is_free": "0",
                        "video_type": "7",
                        "url_type": "2",
                        "ppv_amount": "3",
                        "validity": "360",
                        "ppv_status": "1",
                        "genres_type": "1",
                        "genres_name": "1",
                        "is_purchased": "0",
                        "ppv_paid": "0"
                    },
                    {
                        "id": "106",
                        "type_id": "3",
                        "type": "TV Serials",
                        "title": "Bhotbhoti ",
                        "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
                        "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
                        "language": "",
                        "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/6624217106_sind%20p.jpg",
                        "app_id": "10",
                        "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/1998515106_sind%20l.jpg",
                        "description": "sindur ki kimat",
                        "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
                        "category_id": "3",
                        "subscription": "0",
                        "is_free": "0",
                        "video_type": "7",
                        "url_type": "2",
                        "ppv_amount": "3",
                        "validity": "360",
                        "ppv_status": "1",
                        "genres_type": "3",
                        "genres_name": "3",
                        "is_purchased": "0",
                        "ppv_paid": "0"
                    },
                    {
                        "id": "105",
                        "type_id": "3",
                        "type": "TV Serials",
                        "title": "Gunaho ka Devta",
                        "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
                        "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
                        "language": "",
                        "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/6538558105_gunhap.jpg",
                        "app_id": "10",
                        "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/852542105_gunaho%20l.jpg",
                        "description": "Gunaho ka Devta",
                        "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
                        "category_id": "3",
                        "subscription": "0",
                        "is_free": "0",
                        "video_type": "7",
                        "url_type": "2",
                        "ppv_amount": "3",
                        "validity": "360",
                        "ppv_status": "1",
                        "genres_type": "3",
                        "genres_name": "3",
                        "is_purchased": "0",
                        "ppv_paid": "0"
                    }
                ]
            },
            {
                "id": "4",
                "type_id": "4",
                "category_name": "Video",
                "listing": [
                    {
                        "id": "138",
                        "type_id": "4",
                        "type": "Video",
                        "title": "barish",
                        "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141722/test/1659946380107_854022091447815800_video_VOD.m3u8",
                        "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141722/test/1659946380107_854022091447815800_video_VOD.m3u8",
                        "language": "",
                        "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/1315822138_barish.jpg",
                        "app_id": "10",
                        "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/6622428138_barish%20l.jpg",
                        "description": "Barish song.... ",
                        "category_id": "4",
                        "genres_type": "1",
                        "genres_name": "1",
                        "movie_trailer_url": "",
                        "subscription": "0",
                        "is_free": "0",
                        "video_type": "7",
                        "url_type": "2",
                        "ppv_amount": "4",
                        "validity": "360",
                        "ppv_status": "0",
                        "is_purchased": "0",
                        "ppv_paid": "0"
                    },
                    {
                        "id": "129",
                        "type_id": "4",
                        "type": "Video",
                        "title": "MANN BHARYAA",
                        "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/137185/1659073371526_253944938181943500_video_VOD.m3u8",
                        "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/137185/1659073371526_253944938181943500_video_VOD.m3u8",
                        "language": "",
                        "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/2531865129_MANN.jpg",
                        "app_id": "10",
                        "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/1925784129_MANN%20L.jpg",
                        "description": "MANN BHARAYAA  ",
                        "category_id": "4",
                        "genres_type": "4",
                        "genres_name": "4",
                        "movie_trailer_url": "",
                        "subscription": "0",
                        "is_free": "0",
                        "video_type": "7",
                        "url_type": "2",
                        "ppv_amount": "4",
                        "validity": "360",
                        "ppv_status": "0",
                        "is_purchased": "0",
                        "ppv_paid": "0"
                    },
                    {
                        "id": "128",
                        "type_id": "4",
                        "type": "Video",
                        "title": "Hawa Banke",
                        "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
                        "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
                        "language": "",
                        "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/3455923128_hawa%20bnke%20p.jpg",
                        "app_id": "10",
                        "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/4772834128_hawa%20bnke%20l.jpg",
                        "description": "Boohey barian te nale kanda tapke \r\nTu aaja vi hawa banke.....",
                        "category_id": "4",
                        "genres_type": "4",
                        "genres_name": "4",
                        "movie_trailer_url": "",
                        "subscription": "0",
                        "is_free": "0",
                        "video_type": "7",
                        "url_type": "2",
                        "ppv_amount": "4",
                        "validity": "360",
                        "ppv_status": "0",
                        "is_purchased": "0",
                        "ppv_paid": "0"
                    },
                    {
                        "id": "127",
                        "type_id": "4",
                        "type": "Video",
                        "title": "Kale Je Libas",
                        "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/137185/1659073371526_253944938181943500_video_VOD.m3u8",
                        "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/137185/1659073371526_253944938181943500_video_VOD.m3u8",
                        "language": "",
                        "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/5954806127_libass%20p.jpg",
                        "app_id": "10",
                        "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/3652080127_libas%20l.jpg",
                        "description": "Kale Je Libaas Di Shukeenan Kudi\r\nDoor Door Jaawein Mere Kaale Rang Ton...",
                        "category_id": "4",
                        "genres_type": "2",
                        "genres_name": "2",
                        "movie_trailer_url": "",
                        "subscription": "0",
                        "is_free": "0",
                        "video_type": "7",
                        "url_type": "2",
                        "ppv_amount": "4",
                        "validity": "360",
                        "ppv_status": "0",
                        "is_purchased": "0",
                        "ppv_paid": "0"
                    },
                    {
                        "id": "126",
                        "type_id": "4",
                        "type": "Video",
                        "title": "testing",
                        "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141722/test/1659946380107_854022091447815800_video_VOD.m3u8",
                        "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141722/test/1659946380107_854022091447815800_video_VOD.m3u8",
                        "language": "",
                        "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/4621098126_temporary%20pyar%20p.jpg",
                        "app_id": "10",
                        "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/2939518126_temporary%20pyar%20l.jpg",
                        "description": "Rovengi mukkadran nu heere meriye\r\nJe heereyan de haar jeha yaar kho gaya...       ",
                        "category_id": "4",
                        "genres_type": "2",
                        "genres_name": "2",
                        "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141941/1659965493661_577564927403321900_video_VOD.m3u8",
                        "subscription": "0",
                        "is_free": "0",
                        "video_type": "7",
                        "url_type": "2",
                        "ppv_amount": "4",
                        "validity": "360",
                        "ppv_status": "0",
                        "is_purchased": "0",
                        "ppv_paid": "0"
                    },
                    {
                        "id": "125",
                        "type_id": "4",
                        "type": "Video",
                        "title": "Jannat Ve",
                        "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141941/1659965493661_577564927403321900_video_VOD.m3u8",
                        "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141941/1659965493661_577564927403321900_video_VOD.m3u8",
                        "language": "",
                        "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/2015572125_jannat%20p.jpg",
                        "app_id": "10",
                        "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/4362894125_jannat%20l.jpg",
                        "description": " Jannat Ve Song from the Jannat Ve album is voiced by famous singer Darshan Raval.\r\n   ",
                        "category_id": "4",
                        "genres_type": "1",
                        "genres_name": "1",
                        "movie_trailer_url": "",
                        "subscription": "1",
                        "is_free": "1",
                        "video_type": "7",
                        "url_type": "2",
                        "ppv_amount": "4",
                        "validity": "360",
                        "ppv_status": "1",
                        "is_purchased": "1",
                        "ppv_paid": "0"
                    },
                    {
                        "id": "124",
                        "type_id": "4",
                        "type": "Video",
                        "title": "VIDEO OF PATALLOK",
                        "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141941/1659965493661_577564927403321900_video_VOD.m3u8",
                        "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141941/1659965493661_577564927403321900_video_VOD.m3u8",
                        "language": "",
                        "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/759921124_patallok.jpg",
                        "app_id": "10",
                        "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/7289634124_patal%20lok%20l.jpg",
                        "description": "B praak  ",
                        "category_id": "4",
                        "genres_type": "1",
                        "genres_name": "1",
                        "movie_trailer_url": "",
                        "subscription": "0",
                        "is_free": "0",
                        "video_type": "7",
                        "url_type": "2",
                        "ppv_amount": "4",
                        "validity": "360",
                        "ppv_status": "0",
                        "is_purchased": "0",
                        "ppv_paid": "0"
                    },
                    {
                        "id": "119",
                        "type_id": "4",
                        "type": "Video",
                        "title": "Duniya",
                        "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141941/1659965493661_577564927403321900_video_VOD.m3u8",
                        "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141941/1659965493661_577564927403321900_video_VOD.m3u8",
                        "language": "",
                        "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/238095119_du%20p.jpg",
                        "app_id": "10",
                        "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/1405519119_du%20l.jpg",
                        "description": "Duniya\r\n ",
                        "category_id": "4",
                        "genres_type": "4",
                        "genres_name": "4",
                        "movie_trailer_url": "",
                        "subscription": "0",
                        "is_free": "0",
                        "video_type": "7",
                        "url_type": "2",
                        "ppv_amount": "4",
                        "validity": "360",
                        "ppv_status": "0",
                        "is_purchased": "0",
                        "ppv_paid": "0"
                    },
                    {
                        "id": "83",
                        "type_id": "4",
                        "type": "Video",
                        "title": "vod new",
                        "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/137185/1659073371526_253944938181943500_video_VOD.m3u8",
                        "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/137185/1659073371526_253944938181943500_video_VOD.m3u8",
                        "language": "",
                        "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/418495183_di%20p.jpg",
                        "app_id": "10",
                        "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/524578183_di%20l.jpg",
                        "description": "",
                        "category_id": "4",
                        "genres_type": "",
                        "genres_name": "",
                        "movie_trailer_url": "",
                        "subscription": "0",
                        "is_free": "0",
                        "video_type": "0",
                        "url_type": "2",
                        "ppv_amount": "0",
                        "validity": "365",
                        "ppv_status": "0",
                        "is_purchased": "0",
                        "ppv_paid": "0"
                    },
                    {
                        "id": "82",
                        "type_id": "4",
                        "type": "Video",
                        "title": "IShq nhi karte",
                        "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141941/1659965493661_577564927403321900_video_VOD.m3u8",
                        "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141941/1659965493661_577564927403321900_video_VOD.m3u8",
                        "language": "",
                        "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/649840482_ish%20p.jpg",
                        "app_id": "10",
                        "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/748348282_ish%20l.jpg",
                        "description": "Filhaal 2 Lyrics in English by B Praak featuring Akshay Kumar, Nupur Sanon, Ammy Virk is a latest Hindi song sung by B Praak. Music composed by B Praak and Filhaal 2 Lyrics written by Jaani. Filhaal 2 Video song is directed by Arvindr Khaira.        ",
                        "category_id": "4",
                        "genres_type": "1",
                        "genres_name": "1",
                        "movie_trailer_url": "",
                        "subscription": "0",
                        "is_free": "0",
                        "video_type": "7",
                        "url_type": "2",
                        "ppv_amount": "0",
                        "validity": "365",
                        "ppv_status": "0",
                        "is_purchased": "0",
                        "ppv_paid": "0"
                    }
                ]
            }
        ]
    },
    "is_ios_price": 1,
    "iOSAppURL": "app store url when ios app live",
    "error": []
}
	##output_code_end## 

	False case 
	##output_code_start## 
	{"status":false,"message":"No Data Found.","data":[]}
	##output_code_end##
DIVIDER-SEGMENT

################################ GET DASHBOARD DATA BASED ON TYPE ID ########################
<h4>11) GET DASHBOARD DATA BASED ON TYPE ID</h4>

	Method -:      Post 
	Service url -: dashboard/dashboard

	Input parameter -:
	##input_param_start##
            type_id:1
	##input_param_end##
	Response -:

	True case 
	##output_code_start## 
    {
    "status": true,
    "message": "Data listed.",
    "data": {
        "content_list": [
            {
                "cat_id": "1",
                "category_name": "HINDI",
                "type_id": "1",
                "listing": [
                    {
                        "id": "152",
                        "type_id": "1",
                        "type": "HINDI",
                        "title": "lage raho munna bhai",
                        "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/710541/1661848225167_425261771928072060_video_VOD.m3u8",
                        "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/710541/1661848225167_425261771928072060_video_VOD.m3u8",
                        "language": "",
                        "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/4197903152_lage%20raho%20munna%20bhai%20p.jpg",
                        "app_id": "10",
                        "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/723541152_lage%20raho%20munna%20bhai%20l.jpg",
                        "description": "An elderly man who’s been pushed around by a government official for far too long resorts to ‘Gandhigiri’ (Gandhiism) to achieve his means – he publicly shames the corrupt official by stripping off his clothes.     ",
                        "category_id": "1",
                        "genres_type": "1",
                        "genres_name": "1",
                        "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/90195/test/1656657382599_207102464443241500_video_VOD.m3u8",
                        "subscription": "1",
                        "is_free": "1",
                        "video_type": "7",
                        "url_type": "2",
                        "ppv_amount": "19",
                        "validity": "360",
                        "ppv_status": "1",
                        "is_purchased": "1",
                        "ppv_paid": "0"
                    },
                    {
                        "id": "151",
                        "type_id": "1",
                        "type": "HINDI",
                        "title": "Bhotbhoti ",
                        "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
                        "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
                        "language": "",
                        "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/6442997151_banner-thumbnail-background-graphic-design-260nw-2050938644.webp",
                        "app_id": "10",
                        "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/604372151_vs-youtube-thumbnail-background-download-11626402992ahtdgvlgtc.jpg",
                        "description": "JJHKJHKSJ",
                        "category_id": "1",
                        "genres_type": "1",
                        "genres_name": "1",
                        "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141941/1659965493661_577564927403321900_video_VOD.m3u8",
                        "subscription": "1",
                        "is_free": "1",
                        "video_type": "7",
                        "url_type": "2",
                        "ppv_amount": "100",
                        "validity": "360",
                        "ppv_status": "1",
                        "is_purchased": "1",
                        "ppv_paid": "0"
                    },
                    {
                        "id": "145",
                        "type_id": "1",
                        "type": "HINDI",
                        "title": "EARTH",
                        "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/90195/test/1656657382599_207102464443241500_video_VOD.m3u8",
                        "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/90195/test/1656657382599_207102464443241500_video_VOD.m3u8",
                        "language": "",
                        "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/1275318145_azhar%20l.jpg",
                        "app_id": "10",
                        "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/2776360145_AZHAR%20LL.jpg",
                        "description": "AZHAR               ",
                        "category_id": "1",
                        "genres_type": "1",
                        "genres_name": "1",
                        "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
                        "subscription": "1",
                        "is_free": "1",
                        "video_type": "7",
                        "url_type": "2",
                        "ppv_amount": "1",
                        "validity": "360",
                        "ppv_status": "1",
                        "is_purchased": "1",
                        "ppv_paid": "0"
                    },
                    {
                        "id": "135",
                        "type_id": "1",
                        "type": "HINDI",
                        "title": "M.S. Dhoni The Untold Story",
                        "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/710518/1661846582897_812646299440107800_video_VOD.m3u8",
                        "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/710518/1661846582897_812646299440107800_video_VOD.m3u8",
                        "language": "",
                        "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/5509059135_ms%20dhoni%20p.jpg",
                        "app_id": "10",
                        "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/7388124135_dhoni%20l.jpg",
                        "description": "Our #Singles Squad is all set to sneak in girls for a dhamakedaar house party! But how will they escape Sanskari landlord, Mishra and Haraami Seniors? Find out in Episode 01 now!       ",
                        "category_id": "1",
                        "genres_type": "1",
                        "genres_name": "1",
                        "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/710518/1661846582897_812646299440107800_video_VOD.m3u8",
                        "subscription": "0",
                        "is_free": "0",
                        "video_type": "7",
                        "url_type": "2",
                        "ppv_amount": "1",
                        "validity": "360",
                        "ppv_status": "0",
                        "is_purchased": "0",
                        "ppv_paid": "0"
                    },
                    {
                        "id": "133",
                        "type_id": "1",
                        "type": "HINDI",
                        "title": "Zudgement Day AZHAR",
                        "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/710518/1661846582897_812646299440107800_video_VOD.m3u8",
                        "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/710518/1661846582897_812646299440107800_video_VOD.m3u8",
                        "language": "",
                        "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/2003965133_azhar%20p.jpg",
                        "app_id": "10",
                        "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/874623133_AZHAR%20LL.jpg",
                        "description": "Azhar is a 2016 Indian Hindi biographical sports drama film directed by Tony D'Souza. The story and is inspired from the life of Indian cricketer and former ............     ",
                        "category_id": "1",
                        "genres_type": "1",
                        "genres_name": "1",
                        "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/710518/1661846582897_812646299440107800_video_VOD.m3u8",
                        "subscription": "0",
                        "is_free": "0",
                        "video_type": "7",
                        "url_type": "2",
                        "ppv_amount": "1",
                        "validity": "360",
                        "ppv_status": "0",
                        "is_purchased": "0",
                        "ppv_paid": "0"
                    },
                    {
                        "id": "87",
                        "type_id": "1",
                        "type": "HINDI",
                        "title": "new move 001",
                        "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/90195/test/1656657382599_207102464443241500_video_VOD.m3u8",
                        "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/90195/test/1656657382599_207102464443241500_video_VOD.m3u8",
                        "language": "",
                        "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/294163187_Robert%20Pattinson.jpg",
                        "app_id": "10",
                        "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/493545387_pexels-photo-1767434.webp",
                        "description": "hello only test ",
                        "category_id": "1",
                        "genres_type": "1",
                        "genres_name": "1",
                        "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/90195/test/1656657382599_207102464443241500_video_VOD.m3u8",
                        "subscription": "1",
                        "is_free": "1",
                        "video_type": "7",
                        "url_type": "2",
                        "ppv_amount": "0",
                        "validity": "",
                        "ppv_status": "0",
                        "is_purchased": "1",
                        "ppv_paid": "0"
                    },
                    {
                        "id": "59",
                        "type_id": "1",
                        "type": "HINDI",
                        "title": "Lagaan",
                        "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/137185/1659073371526_253944938181943500_video_VOD.m3u8",
                        "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/137185/1659073371526_253944938181943500_video_VOD.m3u8",
                        "language": "",
                        "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/65580559_lp.jpg",
                        "app_id": "10",
                        "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/405981159_ll.jpg",
                        "description": "Lagaan ",
                        "category_id": "1",
                        "genres_type": "1",
                        "genres_name": "1",
                        "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/137185/1659073371526_253944938181943500_video_VOD.m3u8",
                        "subscription": "1",
                        "is_free": "1",
                        "video_type": "7",
                        "url_type": "2",
                        "ppv_amount": "0",
                        "validity": "",
                        "ppv_status": "0",
                        "is_purchased": "1",
                        "ppv_paid": "0"
                    },
                    {
                        "id": "58",
                        "type_id": "1",
                        "type": "HINDI",
                        "title": "Bhotbhoti ",
                        "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
                        "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
                        "language": "",
                        "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/472175158_m3.jpg",
                        "app_id": "10",
                        "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/494903258_Cinema01.jpg",
                        "description": "141960_0_9295552800835144",
                        "category_id": "1",
                        "genres_type": "1",
                        "genres_name": "1",
                        "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
                        "subscription": "1",
                        "is_free": "1",
                        "video_type": "7",
                        "url_type": "2",
                        "ppv_amount": "0",
                        "validity": "",
                        "ppv_status": "0",
                        "is_purchased": "1",
                        "ppv_paid": "0"
                    },
                    {
                        "id": "57",
                        "type_id": "1",
                        "type": "HINDI",
                        "title": "Gangs of Wasseypur",
                        "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/137185/1659073371526_253944938181943500_video_VOD.m3u8",
                        "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/137185/1659073371526_253944938181943500_video_VOD.m3u8",
                        "language": "",
                        "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/73677557_gp.jpg",
                        "app_id": "10",
                        "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/730539057_g%20l.jpg",
                        "description": "Gangs of Wasseypur\r\n",
                        "category_id": "1",
                        "genres_type": "1",
                        "genres_name": "1",
                        "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/137185/1659073371526_253944938181943500_video_VOD.m3u8",
                        "subscription": "1",
                        "is_free": "1",
                        "video_type": "7",
                        "url_type": "2",
                        "ppv_amount": "0",
                        "validity": "",
                        "ppv_status": "0",
                        "is_purchased": "1",
                        "ppv_paid": "0"
                    },
                    {
                        "id": "56",
                        "type_id": "1",
                        "type": "HINDI",
                        "title": " Koshish",
                        "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/137185/1659073371526_253944938181943500_video_VOD.m3u8",
                        "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/137185/1659073371526_253944938181943500_video_VOD.m3u8",
                        "language": "",
                        "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/323879556_k%20p.jpg",
                        "app_id": "10",
                        "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/162669356_k%20l.jpg",
                        "description": " Koshish",
                        "category_id": "1",
                        "genres_type": "1",
                        "genres_name": "1",
                        "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/137185/1659073371526_253944938181943500_video_VOD.m3u8",
                        "subscription": "1",
                        "is_free": "1",
                        "video_type": "7",
                        "url_type": "2",
                        "ppv_amount": "0",
                        "validity": "",
                        "ppv_status": "0",
                        "is_purchased": "1",
                        "ppv_paid": "0"
                    }
                ]
            },
            {
                "cat_id": "2",
                "category_name": "Hollywood",
                "type_id": "1",
                "listing": [
                    {
                        "id": "143",
                        "type_id": "1",
                        "type": "Hollywood",
                        "title": "THE lostcity",
                        "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/710628/1661855558559_312360552746000300_video_VOD.m3u8",
                        "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/710628/1661855558559_312360552746000300_video_VOD.m3u8",
                        "language": "",
                        "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/1813070143_lostcity%20p.jpg",
                        "app_id": "10",
                        "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/7109785143_lostcity%20l.jpg",
                        "description": "The lost  City            ",
                        "category_id": "1",
                        "genres_type": "2",
                        "genres_name": "2",
                        "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/710541/1661848225167_425261771928072060_video_VOD.m3u8",
                        "subscription": "0",
                        "is_free": "0",
                        "video_type": "7",
                        "url_type": "2",
                        "ppv_amount": "11",
                        "validity": "360",
                        "ppv_status": "0",
                        "is_purchased": "0",
                        "ppv_paid": "0"
                    },
                    {
                        "id": "139",
                        "type_id": "1",
                        "type": "Hollywood",
                        "title": "Van Helsing",
                        "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/710518/1661846582897_812646299440107800_video_VOD.m3u8",
                        "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/710518/1661846582897_812646299440107800_video_VOD.m3u8",
                        "language": "",
                        "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/2730208139_van.jpg",
                        "app_id": "10",
                        "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/3788632139_vanhel.jpg",
                        "description": "Tons of awesome van helsing dracula wallpapers to download for free.",
                        "category_id": "1",
                        "genres_type": "2",
                        "genres_name": "2",
                        "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/710518/1661846582897_812646299440107800_video_VOD.m3u8",
                        "subscription": "0",
                        "is_free": "0",
                        "video_type": "7",
                        "url_type": "2",
                        "ppv_amount": "1",
                        "validity": "360",
                        "ppv_status": "0",
                        "is_purchased": "0",
                        "ppv_paid": "0"
                    },
                    {
                        "id": "136",
                        "type_id": "1",
                        "type": "Hollywood",
                        "title": "Amityville",
                        "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/137185/1659073371526_253944938181943500_video_VOD.m3u8",
                        "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/137185/1659073371526_253944938181943500_video_VOD.m3u8",
                        "language": "",
                        "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/2306081136_amityville%20p.jpg",
                        "app_id": "10",
                        "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/5559047136_amityville%20l.jpg",
                        "description": "It has become one of the most famous paranormal photos of all time.",
                        "category_id": "1",
                        "genres_type": "2",
                        "genres_name": "2",
                        "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/137185/1659073371526_253944938181943500_video_VOD.m3u8",
                        "subscription": "0",
                        "is_free": "0",
                        "video_type": "7",
                        "url_type": "2",
                        "ppv_amount": "1",
                        "validity": "360",
                        "ppv_status": "0",
                        "is_purchased": "0",
                        "ppv_paid": "0"
                    },
                    {
                        "id": "74",
                        "type_id": "1",
                        "type": "Hollywood",
                        "title": "The Game Of Thrones",
                        "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
                        "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
                        "language": "",
                        "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/349562974_game%20of%20thrones1.jpg",
                        "app_id": "10",
                        "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/257230474_game%20of%20thrones.jpg",
                        "description": "ICE Ghost    ",
                        "category_id": "1",
                        "genres_type": "2",
                        "genres_name": "2",
                        "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
                        "subscription": "1",
                        "is_free": "1",
                        "video_type": "7",
                        "url_type": "2",
                        "ppv_amount": "0",
                        "validity": "",
                        "ppv_status": "0",
                        "is_purchased": "1",
                        "ppv_paid": "0"
                    },
                    {
                        "id": "73",
                        "type_id": "1",
                        "type": "Hollywood",
                        "title": "SANDMAN",
                        "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
                        "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
                        "language": "",
                        "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/377591673_sandman%20T.jpg",
                        "app_id": "10",
                        "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/75221873_sndmn%20p.jpg",
                        "description": "SANDMAN     ",
                        "category_id": "1",
                        "genres_type": "2",
                        "genres_name": "2",
                        "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
                        "subscription": "1",
                        "is_free": "1",
                        "video_type": "7",
                        "url_type": "2",
                        "ppv_amount": "0",
                        "validity": "",
                        "ppv_status": "0",
                        "is_purchased": "1",
                        "ppv_paid": "0"
                    },
                    {
                        "id": "72",
                        "type_id": "1",
                        "type": "Hollywood",
                        "title": "PEAKY BLINDERS",
                        "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
                        "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
                        "language": "",
                        "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/521475572_Peaky%20blinders.jpg",
                        "app_id": "10",
                        "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/399768672_pe%20b%20l.jpg",
                        "description": "Peaky blinders   ",
                        "category_id": "1",
                        "genres_type": "2",
                        "genres_name": "2",
                        "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
                        "subscription": "1",
                        "is_free": "1",
                        "video_type": "7",
                        "url_type": "2",
                        "ppv_amount": "0",
                        "validity": "",
                        "ppv_status": "0",
                        "is_purchased": "1",
                        "ppv_paid": "0"
                    },
                    {
                        "id": "71",
                        "type_id": "1",
                        "type": "Hollywood",
                        "title": "GRUDGE ",
                        "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
                        "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
                        "language": "",
                        "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/544506571_the%20grudge%20t.jpg",
                        "app_id": "10",
                        "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/775958671_grudge%20l.jpg",
                        "description": "GRUDGE ",
                        "category_id": "1",
                        "genres_type": "2",
                        "genres_name": "2",
                        "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
                        "subscription": "1",
                        "is_free": "1",
                        "video_type": "7",
                        "url_type": "2",
                        "ppv_amount": "0",
                        "validity": "",
                        "ppv_status": "0",
                        "is_purchased": "1",
                        "ppv_paid": "0"
                    },
                    {
                        "id": "70",
                        "type_id": "1",
                        "type": "Hollywood",
                        "title": "DOCTOR WHO",
                        "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
                        "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
                        "language": "",
                        "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/690014970_D%20W%20P.jpg",
                        "app_id": "10",
                        "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/761830570_DW%20L.jpg",
                        "description": "Doctor Who  ",
                        "category_id": "1",
                        "genres_type": "2",
                        "genres_name": "2",
                        "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
                        "subscription": "1",
                        "is_free": "1",
                        "video_type": "7",
                        "url_type": "2",
                        "ppv_amount": "0",
                        "validity": "",
                        "ppv_status": "0",
                        "is_purchased": "1",
                        "ppv_paid": "0"
                    },
                    {
                        "id": "69",
                        "type_id": "1",
                        "type": "Hollywood",
                        "title": "The Outlaws ",
                        "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
                        "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
                        "language": "",
                        "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/425680969_O%20P.jpg",
                        "app_id": "10",
                        "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/453417369_O%20L.jpg",
                        "description": "The Outlaws  ",
                        "category_id": "1",
                        "genres_type": "2",
                        "genres_name": "2",
                        "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
                        "subscription": "1",
                        "is_free": "1",
                        "video_type": "7",
                        "url_type": "2",
                        "ppv_amount": "0",
                        "validity": "",
                        "ppv_status": "0",
                        "is_purchased": "1",
                        "ppv_paid": "0"
                    },
                    {
                        "id": "68",
                        "type_id": "1",
                        "type": "Hollywood",
                        "title": "TWILIGHT",
                        "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141941/1659965493661_577564927403321900_video_VOD.m3u8",
                        "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141941/1659965493661_577564927403321900_video_VOD.m3u8",
                        "language": "",
                        "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/533447168_TWILIGHT1.jpg",
                        "app_id": "10",
                        "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/372979568_t%20l.jpg",
                        "description": "TWILIGHT TWILIGHT ",
                        "category_id": "1",
                        "genres_type": "2",
                        "genres_name": "2",
                        "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141941/1659965493661_577564927403321900_video_VOD.m3u8",
                        "subscription": "1",
                        "is_free": "1",
                        "video_type": "7",
                        "url_type": "2",
                        "ppv_amount": "0",
                        "validity": "",
                        "ppv_status": "0",
                        "is_purchased": "1",
                        "ppv_paid": "0"
                    }
                ]
            },
            {
                "cat_id": "3",
                "category_name": "Hamar Bhojpuri",
                "type_id": "1",
                "listing": [
                    {
                        "id": "153",
                        "type_id": "1",
                        "type": "Hamar Bhojpuri",
                        "title": "john carter",
                        "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/710541/1661848225167_425261771928072060_video_VOD.m3u8",
                        "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/710541/1661848225167_425261771928072060_video_VOD.m3u8",
                        "language": "",
                        "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/5456949153_a%20the%20p.jpg",
                        "app_id": "10",
                        "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/5287202153_a%20the%20l.jpg",
                        "description": "john carter\r\n            ",
                        "category_id": "1",
                        "genres_type": "3",
                        "genres_name": "3",
                        "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/710627/1661855514710_496246933437449700_video_VOD.m3u8",
                        "subscription": "0",
                        "is_free": "0",
                        "video_type": "7",
                        "url_type": "2",
                        "ppv_amount": "10",
                        "validity": "360",
                        "ppv_status": "0",
                        "is_purchased": "0",
                        "ppv_paid": "0"
                    },
                    {
                        "id": "142",
                        "type_id": "1",
                        "type": "Hamar Bhojpuri",
                        "title": "M.S. Dhoni The Untold Story",
                        "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/710518/1661846582897_812646299440107800_video_VOD.m3u8",
                        "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/710518/1661846582897_812646299440107800_video_VOD.m3u8",
                        "language": "",
                        "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/7560087142_ms%20dhoni%20pp.jpg",
                        "app_id": "10",
                        "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/2311061142_dhoni%20l.jpg",
                        "description": "Indian captain  ",
                        "category_id": "1",
                        "genres_type": "3",
                        "genres_name": "3",
                        "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/710541/1661848225167_425261771928072060_video_VOD.m3u8",
                        "subscription": "0",
                        "is_free": "0",
                        "video_type": "7",
                        "url_type": "2",
                        "ppv_amount": "99",
                        "validity": "360",
                        "ppv_status": "0",
                        "is_purchased": "0",
                        "ppv_paid": "0"
                    },
                    {
                        "id": "60",
                        "type_id": "1",
                        "type": "Hamar Bhojpuri",
                        "title": "Hausala",
                        "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/137185/1659073371526_253944938181943500_video_VOD.m3u8",
                        "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/137185/1659073371526_253944938181943500_video_VOD.m3u8",
                        "language": "",
                        "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/42638160_ho%20p.jpg",
                        "app_id": "10",
                        "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/511515160_ho%20l.jpg",
                        "description": "Hausala",
                        "category_id": "1",
                        "genres_type": "3",
                        "genres_name": "3",
                        "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/137185/1659073371526_253944938181943500_video_VOD.m3u8",
                        "subscription": "1",
                        "is_free": "1",
                        "video_type": "7",
                        "url_type": "2",
                        "ppv_amount": "0",
                        "validity": "",
                        "ppv_status": "0",
                        "is_purchased": "1",
                        "ppv_paid": "0"
                    },
                    {
                        "id": "44",
                        "type_id": "1",
                        "type": "Hamar Bhojpuri",
                        "title": " Aaj Jeene Ki Tamanna Hai ",
                        "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/137185/1659073371526_253944938181943500_video_VOD.m3u8",
                        "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/137185/1659073371526_253944938181943500_video_VOD.m3u8",
                        "language": "",
                        "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/555618644_ah%20p.jpg",
                        "app_id": "10",
                        "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/212039244_aj%20ll.jpg",
                        "description": "Aaj Jeene Ki Tamanna Hai\r\n   ",
                        "category_id": "1",
                        "genres_type": "3",
                        "genres_name": "3",
                        "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/137185/1659073371526_253944938181943500_video_VOD.m3u8",
                        "subscription": "1",
                        "is_free": "1",
                        "video_type": "7",
                        "url_type": "2",
                        "ppv_amount": "10",
                        "validity": "",
                        "ppv_status": "0",
                        "is_purchased": "1",
                        "ppv_paid": "0"
                    },
                    {
                        "id": "43",
                        "type_id": "1",
                        "type": "Hamar Bhojpuri",
                        "title": "Bihari Babu",
                        "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/90195/test/1656657382599_207102464443241500_video_VOD.m3u8",
                        "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/90195/test/1656657382599_207102464443241500_video_VOD.m3u8",
                        "language": "",
                        "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/618731243_bi%20p.jpg",
                        "app_id": "10",
                        "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/250609643_bi%20l.jpg",
                        "description": "Bihari Babu",
                        "category_id": "1",
                        "genres_type": "3",
                        "genres_name": "3",
                        "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/90195/test/1656657382599_207102464443241500_video_VOD.m3u8",
                        "subscription": "1",
                        "is_free": "1",
                        "video_type": "7",
                        "url_type": "2",
                        "ppv_amount": "10",
                        "validity": "",
                        "ppv_status": "0",
                        "is_purchased": "1",
                        "ppv_paid": "0"
                    },
                    {
                        "id": "42",
                        "type_id": "1",
                        "type": "Hamar Bhojpuri",
                        "title": "Bhotbhoti ",
                        "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
                        "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
                        "language": "",
                        "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/127876042_bhp.jpg",
                        "app_id": "10",
                        "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/386725642_bh%20ll.jpg",
                        "description": "Bihari Babu",
                        "category_id": "1",
                        "genres_type": "3",
                        "genres_name": "3",
                        "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
                        "subscription": "1",
                        "is_free": "1",
                        "video_type": "7",
                        "url_type": "2",
                        "ppv_amount": "10",
                        "validity": "",
                        "ppv_status": "0",
                        "is_purchased": "1",
                        "ppv_paid": "0"
                    },
                    {
                        "id": "41",
                        "type_id": "1",
                        "type": "Hamar Bhojpuri",
                        "title": "Ganga Ghat",
                        "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141722/test/1659946380107_854022091447815800_video_VOD.m3u8",
                        "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141722/test/1659946380107_854022091447815800_video_VOD.m3u8",
                        "language": "",
                        "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/319863941_ga%20p.jpg",
                        "app_id": "10",
                        "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/309734141_ga%20ll.jpg",
                        "description": "Ganga Ghat\r\n",
                        "category_id": "1",
                        "genres_type": "3",
                        "genres_name": "3",
                        "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141722/test/1659946380107_854022091447815800_video_VOD.m3u8",
                        "subscription": "1",
                        "is_free": "1",
                        "video_type": "7",
                        "url_type": "2",
                        "ppv_amount": "10",
                        "validity": "",
                        "ppv_status": "0",
                        "is_purchased": "1",
                        "ppv_paid": "0"
                    },
                    {
                        "id": "40",
                        "type_id": "1",
                        "type": "Hamar Bhojpuri",
                        "title": " Fleabag ",
                        "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141941/1659965493661_577564927403321900_video_VOD.m3u8",
                        "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141941/1659965493661_577564927403321900_video_VOD.m3u8",
                        "language": "",
                        "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/45440340_Fleabag.jpg",
                        "app_id": "10",
                        "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/208096140_Fleabag%201.jpg",
                        "description": " Fleabag  ",
                        "category_id": "1",
                        "genres_type": "3",
                        "genres_name": "3",
                        "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141941/1659965493661_577564927403321900_video_VOD.m3u8",
                        "subscription": "1",
                        "is_free": "1",
                        "video_type": "7",
                        "url_type": "2",
                        "ppv_amount": "10",
                        "validity": "",
                        "ppv_status": "0",
                        "is_purchased": "1",
                        "ppv_paid": "0"
                    },
                    {
                        "id": "33",
                        "type_id": "1",
                        "type": "Hamar Bhojpuri",
                        "title": "Phoolwari",
                        "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
                        "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
                        "language": "",
                        "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/582488333_phu%20p.jpg",
                        "app_id": "10",
                        "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/198304433_pju%20l.jpg",
                        "description": "Phoolwari",
                        "category_id": "1",
                        "genres_type": "3",
                        "genres_name": "3",
                        "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
                        "subscription": "1",
                        "is_free": "1",
                        "video_type": "7",
                        "url_type": "2",
                        "ppv_amount": "10",
                        "validity": "",
                        "ppv_status": "0",
                        "is_purchased": "1",
                        "ppv_paid": "0"
                    },
                    {
                        "id": "30",
                        "type_id": "1",
                        "type": "Hamar Bhojpuri",
                        "title": "LOCKDOWN",
                        "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/90195/test/1656657382599_207102464443241500_video_VOD.m3u8",
                        "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/90195/test/1656657382599_207102464443241500_video_VOD.m3u8",
                        "language": "",
                        "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/655082430_l%20t.jpg",
                        "app_id": "10",
                        "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/233565830_l%20p.jpg",
                        "description": "lockdown  ",
                        "category_id": "1",
                        "genres_type": "3",
                        "genres_name": "3",
                        "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/90195/test/1656657382599_207102464443241500_video_VOD.m3u8",
                        "subscription": "1",
                        "is_free": "1",
                        "video_type": "7",
                        "url_type": "2",
                        "ppv_amount": "90",
                        "validity": "",
                        "ppv_status": "0",
                        "is_purchased": "1",
                        "ppv_paid": "0"
                    }
                ]
            },
            {
                "cat_id": "4",
                "category_name": "Kids Zone",
                "type_id": "1",
                "listing": [
                    {
                        "id": "100",
                        "type_id": "1",
                        "type": "Kids Zone",
                        "title": "Gully Boy",
                        "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141941/1659965493661_577564927403321900_video_VOD.m3u8",
                        "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141941/1659965493661_577564927403321900_video_VOD.m3u8",
                        "language": "",
                        "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/5311408100_gul%20p.jpg",
                        "app_id": "10",
                        "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/5848883100_gul%20l.jpg",
                        "description": "Gully Boy",
                        "category_id": "1",
                        "genres_type": "4",
                        "genres_name": "4",
                        "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141941/1659965493661_577564927403321900_video_VOD.m3u8",
                        "subscription": "0",
                        "is_free": "0",
                        "video_type": "7",
                        "url_type": "2",
                        "ppv_amount": "1",
                        "validity": "360",
                        "ppv_status": "1",
                        "is_purchased": "0",
                        "ppv_paid": "0"
                    },
                    {
                        "id": "99",
                        "type_id": "1",
                        "type": "Kids Zone",
                        "title": "Blue Umbrella",
                        "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
                        "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
                        "language": "",
                        "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/427428099_BL%20P.jpg",
                        "app_id": "10",
                        "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/669839299_BL%20L.jpg",
                        "description": "Blue Umbrella ",
                        "category_id": "1",
                        "genres_type": "4",
                        "genres_name": "4",
                        "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
                        "subscription": "0",
                        "is_free": "0",
                        "video_type": "7",
                        "url_type": "2",
                        "ppv_amount": "1",
                        "validity": "360",
                        "ppv_status": "0",
                        "is_purchased": "0",
                        "ppv_paid": "0"
                    },
                    {
                        "id": "64",
                        "type_id": "1",
                        "type": "Kids Zone",
                        "title": "THE RING",
                        "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141941/1659965493661_577564927403321900_video_VOD.m3u8",
                        "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141941/1659965493661_577564927403321900_video_VOD.m3u8",
                        "language": "",
                        "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/613077964_R%20P.jpg",
                        "app_id": "10",
                        "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/187155464_R%20L.jpg",
                        "description": "THE RING HORROR MOVIE\r\n  ",
                        "category_id": "1",
                        "genres_type": "4",
                        "genres_name": "4",
                        "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141941/1659965493661_577564927403321900_video_VOD.m3u8",
                        "subscription": "1",
                        "is_free": "1",
                        "video_type": "7",
                        "url_type": "2",
                        "ppv_amount": "0",
                        "validity": "",
                        "ppv_status": "0",
                        "is_purchased": "1",
                        "ppv_paid": "0"
                    }
                ]
            },
            {
                "cat_id": "27",
                "category_name": "old is gold",
                "type_id": "1",
                "listing": [
                    {
                        "id": "130",
                        "type_id": "1",
                        "type": "old is gold",
                        "title": "VEER",
                        "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/137185/1659073371526_253944938181943500_video_VOD.m3u8",
                        "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/137185/1659073371526_253944938181943500_video_VOD.m3u8",
                        "language": "",
                        "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/3745832130_veer%20p.jpg",
                        "app_id": "10",
                        "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/3989511130_veer%20l.jpg",
                        "description": "Story of a conventional, conservative small town villager and his son.  ",
                        "category_id": "1",
                        "genres_type": "27",
                        "genres_name": "27",
                        "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/137185/1659073371526_253944938181943500_video_VOD.m3u8",
                        "subscription": "0",
                        "is_free": "0",
                        "video_type": "7",
                        "url_type": "2",
                        "ppv_amount": "1",
                        "validity": "360",
                        "ppv_status": "2",
                        "is_purchased": "0",
                        "ppv_paid": "0"
                    },
                    {
                        "id": "98",
                        "type_id": "1",
                        "type": "old is gold",
                        "title": "Sooryavansham",
                        "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
                        "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
                        "language": "",
                        "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/25909698_sur%20p.jpg",
                        "app_id": "10",
                        "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/273867398_sur%20l.jpg",
                        "description": "Sooryavansham",
                        "category_id": "1",
                        "genres_type": "27",
                        "genres_name": "27",
                        "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
                        "subscription": "0",
                        "is_free": "0",
                        "video_type": "7",
                        "url_type": "2",
                        "ppv_amount": "1",
                        "validity": "360",
                        "ppv_status": "1",
                        "is_purchased": "0",
                        "ppv_paid": "0"
                    },
                    {
                        "id": "93",
                        "type_id": "1",
                        "type": "old is gold",
                        "title": "GOLD",
                        "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
                        "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
                        "language": "",
                        "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/521231193_gold%20t.jpg",
                        "app_id": "10",
                        "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/501094593_gold%20l.jpg",
                        "description": "Symbol of gold metal is Au.      ",
                        "category_id": "1",
                        "genres_type": "27",
                        "genres_name": "27",
                        "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
                        "subscription": "1",
                        "is_free": "1",
                        "video_type": "7",
                        "url_type": "2",
                        "ppv_amount": "1",
                        "validity": "360",
                        "ppv_status": "1",
                        "is_purchased": "1",
                        "ppv_paid": "0"
                    }
                ]
            },
            {
                "cat_id": "29",
                "category_name": "SIFI MOVIES",
                "type_id": "1",
                "listing": [
                    {
                        "id": "113",
                        "type_id": "1",
                        "type": "SIFI MOVIES",
                        "title": "nayak",
                        "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
                        "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
                        "language": "",
                        "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/6433022113_nayak%20p.jpg",
                        "app_id": "10",
                        "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/5172487113_nayak%20l.jpg",
                        "description": "NAYAK",
                        "category_id": "1",
                        "genres_type": "29",
                        "genres_name": "29",
                        "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
                        "subscription": "1",
                        "is_free": "1",
                        "video_type": "7",
                        "url_type": "2",
                        "ppv_amount": "1",
                        "validity": "360",
                        "ppv_status": "0",
                        "is_purchased": "1",
                        "ppv_paid": "0"
                    },
                    {
                        "id": "112",
                        "type_id": "1",
                        "type": "SIFI MOVIES",
                        "title": "udaan",
                        "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141941/1659965493661_577564927403321900_video_VOD.m3u8",
                        "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141941/1659965493661_577564927403321900_video_VOD.m3u8",
                        "language": "",
                        "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/2223910112_udan%20p.jpg",
                        "app_id": "10",
                        "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/3162314112_udaan%20l.jpg",
                        "description": "Udaan movie\r\n ",
                        "category_id": "1",
                        "genres_type": "29",
                        "genres_name": "29",
                        "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141941/1659965493661_577564927403321900_video_VOD.m3u8",
                        "subscription": "1",
                        "is_free": "1",
                        "video_type": "7",
                        "url_type": "2",
                        "ppv_amount": "1",
                        "validity": "360",
                        "ppv_status": "1",
                        "is_purchased": "1",
                        "ppv_paid": "0"
                    },
                    {
                        "id": "84",
                        "type_id": "1",
                        "type": "SIFI MOVIES",
                        "title": "LAGAAN 1",
                        "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141941/1659965493661_577564927403321900_video_VOD.m3u8",
                        "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141941/1659965493661_577564927403321900_video_VOD.m3u8",
                        "language": "",
                        "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/111814884_download.jpg",
                        "app_id": "10",
                        "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/361643084_thumbnail.png",
                        "description": "This is WIFI MOVIES       ",
                        "category_id": "1",
                        "genres_type": "29",
                        "genres_name": "29",
                        "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
                        "subscription": "1",
                        "is_free": "1",
                        "video_type": "7",
                        "url_type": "2",
                        "ppv_amount": "0",
                        "validity": "",
                        "ppv_status": "1",
                        "is_purchased": "1",
                        "ppv_paid": "0"
                    }
                ]
            }
        ]
    },
    "is_ios_price": 1,
    "iOSAppURL": "app store url when ios app live",
    "error": []
}
       
	##output_code_end## 

	False case 
	##output_code_start## 
	{"status":false,"message":"No Data Found.","data":[]}
	##output_code_end##
DIVIDER-SEGMENT

################################ GET DASHBOARD DETAIL ########################
<h4>12) GET DASHBOARD DETAIL</h4>

	Method -:      Post 
	Service url -: dashboard/dashboard/get_detail_page

	Input parameter -:
	##input_param_start##
                    id:133
                    type_id:1
	##input_param_end##
	Response -:

	True case 
	##output_code_start## 
    {
    "status": true,
    "message": "Movies detail listed.",
    "data": {
        "id": "133",
        "type_id": "1",
        "title": "Zudgement Day AZHAR",
        "description": "Azhar is a 2016 Indian Hindi biographical sports drama film directed by Tony D'Souza. The story and is inspired from the life of Indian cricketer and former ............     ",
        "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/710518/1661846582897_812646299440107800_video_VOD.m3u8",
        "video_file_tail": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/710518/1661846582897_812646299440107800_video_VOD.m3u8",
        "language": "",
        "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/2003965133_azhar%20p.jpg",
        "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/874623133_AZHAR%20LL.jpg",
        "subscription": "0",
        "video_type": "7",
        "is_free": "0",
        "ppv_amount": "1",
        "validity": "360",
        "ppv_status": "0",
        "is_bookmarked": 0,
        "is_purchased": "0",
        "ppv_paid": "0",
        "genres": "HINDI",
        "pause_time": "0",
        "artist": [
            {
                "id": "56",
                "name": "Starring Anupam Kher",
                "profile_image": "https://d23kfzlh2otf0u.cloudfront.net/0/admin_v1/artist/552768956_anu%201.jpg",
                "description": "Actor"
            },
            {
                "id": "57",
                "name": "Sushant Singh Rajput",
                "profile_image": "https://d23kfzlh2otf0u.cloudfront.net/0/admin_v1/artist/192121957_sushant.jpg",
                "description": "actor"
            },
            {
                "id": "58",
                "name": "Bhumika Chawla",
                "profile_image": "https://d23kfzlh2otf0u.cloudfront.net/0/admin_v1/artist/247630958_bhumika.jpg",
                "description": "Actress"
            }
        ],
        "releted": [
            {
                "id": "152",
                "type_id": "1",
                "type": "Movies",
                "title": "lage raho munna bhai",
                "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/710541/1661848225167_425261771928072060_video_VOD.m3u8",
                "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/710541/1661848225167_425261771928072060_video_VOD.m3u8",
                "language": "",
                "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/4197903152_lage%20raho%20munna%20bhai%20p.jpg",
                "app_id": "10",
                "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/723541152_lage%20raho%20munna%20bhai%20l.jpg",
                "description": "An elderly man who’s been pushed around by a government official for far too long resorts to ‘Gandhigiri’ (Gandhiism) to achieve his means – he publicly shames the corrupt official by stripping off his clothes.     ",
                "category_id": "1",
                "genres_type": "1",
                "genres_name": "1",
                "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/90195/test/1656657382599_207102464443241500_video_VOD.m3u8",
                "subscription": "1",
                "is_free": "1",
                "video_type": "7",
                "url_type": "2",
                "ppv_amount": "19",
                "validity": "360",
                "ppv_status": "1",
                "is_purchased": "1",
                "ppv_paid": "0",
                "main_id": "1"
            },
            {
                "id": "151",
                "type_id": "1",
                "type": "Movies",
                "title": "Bhotbhoti ",
                "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
                "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
                "language": "",
                "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/6442997151_banner-thumbnail-background-graphic-design-260nw-2050938644.webp",
                "app_id": "10",
                "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/604372151_vs-youtube-thumbnail-background-download-11626402992ahtdgvlgtc.jpg",
                "description": "JJHKJHKSJ",
                "category_id": "1",
                "genres_type": "1",
                "genres_name": "1",
                "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141941/1659965493661_577564927403321900_video_VOD.m3u8",
                "subscription": "1",
                "is_free": "1",
                "video_type": "7",
                "url_type": "2",
                "ppv_amount": "100",
                "validity": "360",
                "ppv_status": "1",
                "is_purchased": "1",
                "ppv_paid": "0",
                "main_id": "1"
            },
            {
                "id": "145",
                "type_id": "1",
                "type": "Movies",
                "title": "EARTH",
                "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/90195/test/1656657382599_207102464443241500_video_VOD.m3u8",
                "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/90195/test/1656657382599_207102464443241500_video_VOD.m3u8",
                "language": "",
                "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/1275318145_azhar%20l.jpg",
                "app_id": "10",
                "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/2776360145_AZHAR%20LL.jpg",
                "description": "AZHAR               ",
                "category_id": "1",
                "genres_type": "1",
                "genres_name": "1",
                "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
                "subscription": "1",
                "is_free": "1",
                "video_type": "7",
                "url_type": "2",
                "ppv_amount": "1",
                "validity": "360",
                "ppv_status": "1",
                "is_purchased": "1",
                "ppv_paid": "0",
                "main_id": "1"
            },
            {
                "id": "135",
                "type_id": "1",
                "type": "Movies",
                "title": "M.S. Dhoni The Untold Story",
                "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/710518/1661846582897_812646299440107800_video_VOD.m3u8",
                "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/710518/1661846582897_812646299440107800_video_VOD.m3u8",
                "language": "",
                "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/5509059135_ms%20dhoni%20p.jpg",
                "app_id": "10",
                "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/7388124135_dhoni%20l.jpg",
                "description": "Our #Singles Squad is all set to sneak in girls for a dhamakedaar house party! But how will they escape Sanskari landlord, Mishra and Haraami Seniors? Find out in Episode 01 now!       ",
                "category_id": "1",
                "genres_type": "1",
                "genres_name": "1",
                "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/710518/1661846582897_812646299440107800_video_VOD.m3u8",
                "subscription": "0",
                "is_free": "0",
                "video_type": "7",
                "url_type": "2",
                "ppv_amount": "1",
                "validity": "360",
                "ppv_status": "0",
                "is_purchased": "0",
                "ppv_paid": "0",
                "main_id": "1"
            },
            {
                "id": "87",
                "type_id": "1",
                "type": "Movies",
                "title": "new move 001",
                "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/90195/test/1656657382599_207102464443241500_video_VOD.m3u8",
                "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/90195/test/1656657382599_207102464443241500_video_VOD.m3u8",
                "language": "",
                "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/294163187_Robert%20Pattinson.jpg",
                "app_id": "10",
                "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/493545387_pexels-photo-1767434.webp",
                "description": "hello only test ",
                "category_id": "1",
                "genres_type": "1",
                "genres_name": "1",
                "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/90195/test/1656657382599_207102464443241500_video_VOD.m3u8",
                "subscription": "1",
                "is_free": "1",
                "video_type": "7",
                "url_type": "2",
                "ppv_amount": "0",
                "validity": "",
                "ppv_status": "0",
                "is_purchased": "1",
                "ppv_paid": "0",
                "main_id": "1"
            },
            {
                "id": "59",
                "type_id": "1",
                "type": "Movies",
                "title": "Lagaan",
                "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/137185/1659073371526_253944938181943500_video_VOD.m3u8",
                "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/137185/1659073371526_253944938181943500_video_VOD.m3u8",
                "language": "",
                "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/65580559_lp.jpg",
                "app_id": "10",
                "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/405981159_ll.jpg",
                "description": "Lagaan ",
                "category_id": "1",
                "genres_type": "1",
                "genres_name": "1",
                "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/137185/1659073371526_253944938181943500_video_VOD.m3u8",
                "subscription": "1",
                "is_free": "1",
                "video_type": "7",
                "url_type": "2",
                "ppv_amount": "0",
                "validity": "",
                "ppv_status": "0",
                "is_purchased": "1",
                "ppv_paid": "0",
                "main_id": "1"
            },
            {
                "id": "58",
                "type_id": "1",
                "type": "Movies",
                "title": "Bhotbhoti ",
                "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
                "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
                "language": "",
                "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/472175158_m3.jpg",
                "app_id": "10",
                "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/494903258_Cinema01.jpg",
                "description": "141960_0_9295552800835144",
                "category_id": "1",
                "genres_type": "1",
                "genres_name": "1",
                "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
                "subscription": "1",
                "is_free": "1",
                "video_type": "7",
                "url_type": "2",
                "ppv_amount": "0",
                "validity": "",
                "ppv_status": "0",
                "is_purchased": "1",
                "ppv_paid": "0",
                "main_id": "1"
            },
            {
                "id": "57",
                "type_id": "1",
                "type": "Movies",
                "title": "Gangs of Wasseypur",
                "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/137185/1659073371526_253944938181943500_video_VOD.m3u8",
                "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/137185/1659073371526_253944938181943500_video_VOD.m3u8",
                "language": "",
                "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/73677557_gp.jpg",
                "app_id": "10",
                "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/730539057_g%20l.jpg",
                "description": "Gangs of Wasseypur\r\n",
                "category_id": "1",
                "genres_type": "1",
                "genres_name": "1",
                "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/137185/1659073371526_253944938181943500_video_VOD.m3u8",
                "subscription": "1",
                "is_free": "1",
                "video_type": "7",
                "url_type": "2",
                "ppv_amount": "0",
                "validity": "",
                "ppv_status": "0",
                "is_purchased": "1",
                "ppv_paid": "0",
                "main_id": "1"
            },
            {
                "id": "56",
                "type_id": "1",
                "type": "Movies",
                "title": " Koshish",
                "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/137185/1659073371526_253944938181943500_video_VOD.m3u8",
                "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/137185/1659073371526_253944938181943500_video_VOD.m3u8",
                "language": "",
                "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/323879556_k%20p.jpg",
                "app_id": "10",
                "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/162669356_k%20l.jpg",
                "description": " Koshish",
                "category_id": "1",
                "genres_type": "1",
                "genres_name": "1",
                "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/137185/1659073371526_253944938181943500_video_VOD.m3u8",
                "subscription": "1",
                "is_free": "1",
                "video_type": "7",
                "url_type": "2",
                "ppv_amount": "0",
                "validity": "",
                "ppv_status": "0",
                "is_purchased": "1",
                "ppv_paid": "0",
                "main_id": "1"
            },
            {
                "id": "55",
                "type_id": "1",
                "type": "Movies",
                "title": " 3 Idiots",
                "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/137185/1659073371526_253944938181943500_video_VOD.m3u8",
                "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/137185/1659073371526_253944938181943500_video_VOD.m3u8",
                "language": "",
                "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/382461355_3%20p.jpg",
                "app_id": "10",
                "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/350631755_3%20l.jpg",
                "description": " 3 Idiots",
                "category_id": "1",
                "genres_type": "1",
                "genres_name": "1",
                "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/137185/1659073371526_253944938181943500_video_VOD.m3u8",
                "subscription": "1",
                "is_free": "1",
                "video_type": "7",
                "url_type": "2",
                "ppv_amount": "0",
                "validity": "",
                "ppv_status": "0",
                "is_purchased": "1",
                "ppv_paid": "0",
                "main_id": "1"
            },
            {
                "id": "54",
                "type_id": "1",
                "type": "Movies",
                "title": " Sardar Udham",
                "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/90195/test/1656657382599_207102464443241500_video_VOD.m3u8",
                "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/90195/test/1656657382599_207102464443241500_video_VOD.m3u8",
                "language": "",
                "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/91001654_sr%20p.jpg",
                "app_id": "10",
                "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/177613954_srl.jpg",
                "description": " Sardar Udham ",
                "category_id": "1",
                "genres_type": "1",
                "genres_name": "1",
                "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/90195/test/1656657382599_207102464443241500_video_VOD.m3u8",
                "subscription": "1",
                "is_free": "1",
                "video_type": "7",
                "url_type": "2",
                "ppv_amount": "0",
                "validity": "",
                "ppv_status": "1",
                "is_purchased": "1",
                "ppv_paid": "0",
                "main_id": "1"
            },
            {
                "id": "53",
                "type_id": "1",
                "type": "Movies",
                "title": " Swades",
                "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/137185/1659073371526_253944938181943500_video_VOD.m3u8",
                "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/137185/1659073371526_253944938181943500_video_VOD.m3u8",
                "language": "",
                "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/275933653_sw%20p.jpg",
                "app_id": "10",
                "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/683119353_sw%20l.jpg",
                "description": " Swades",
                "category_id": "1",
                "genres_type": "1",
                "genres_name": "1",
                "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/137185/1659073371526_253944938181943500_video_VOD.m3u8",
                "subscription": "1",
                "is_free": "1",
                "video_type": "7",
                "url_type": "2",
                "ppv_amount": "0",
                "validity": "",
                "ppv_status": "0",
                "is_purchased": "1",
                "ppv_paid": "0",
                "main_id": "1"
            },
            {
                "id": "52",
                "type_id": "1",
                "type": "Movies",
                "title": " Shershaah",
                "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141722/test/1659946380107_854022091447815800_video_VOD.m3u8",
                "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141722/test/1659946380107_854022091447815800_video_VOD.m3u8",
                "language": "",
                "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/765854552_sh%20p.jpg",
                "app_id": "10",
                "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/291687252_sh%20l.jpg",
                "description": " Shershaah",
                "category_id": "1",
                "genres_type": "1",
                "genres_name": "1",
                "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141722/test/1659946380107_854022091447815800_video_VOD.m3u8",
                "subscription": "1",
                "is_free": "1",
                "video_type": "7",
                "url_type": "2",
                "ppv_amount": "0",
                "validity": "",
                "ppv_status": "0",
                "is_purchased": "1",
                "ppv_paid": "0",
                "main_id": "1"
            },
            {
                "id": "51",
                "type_id": "1",
                "type": "Movies",
                "title": " Bhaag Milkha Bhaag",
                "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141722/test/1659946380107_854022091447815800_video_VOD.m3u8",
                "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141722/test/1659946380107_854022091447815800_video_VOD.m3u8",
                "language": "",
                "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/739318751_bh%20p.jpg",
                "app_id": "10",
                "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/538133551_bh%20l.jpg",
                "description": " Bhaag Milkha Bhaag ",
                "category_id": "1",
                "genres_type": "1",
                "genres_name": "1",
                "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141722/test/1659946380107_854022091447815800_video_VOD.m3u8",
                "subscription": "1",
                "is_free": "1",
                "video_type": "7",
                "url_type": "2",
                "ppv_amount": "0",
                "validity": "",
                "ppv_status": "0",
                "is_purchased": "1",
                "ppv_paid": "0",
                "main_id": "1"
            },
            {
                "id": "50",
                "type_id": "1",
                "type": "Movies",
                "title": " Dangal",
                "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/90195/test/1656657382599_207102464443241500_video_VOD.m3u8",
                "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/90195/test/1656657382599_207102464443241500_video_VOD.m3u8",
                "language": "",
                "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/261321250_d.jpg",
                "app_id": "10",
                "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/216824850_d%20l.jpg",
                "description": " Dangal",
                "category_id": "1",
                "genres_type": "1",
                "genres_name": "1",
                "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/90195/test/1656657382599_207102464443241500_video_VOD.m3u8",
                "subscription": "1",
                "is_free": "1",
                "video_type": "7",
                "url_type": "2",
                "ppv_amount": "0",
                "validity": "",
                "ppv_status": "0",
                "is_purchased": "1",
                "ppv_paid": "0",
                "main_id": "1"
            },
            {
                "id": "49",
                "type_id": "1",
                "type": "Movies",
                "title": " Udaan ",
                "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
                "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
                "language": "",
                "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/113894949_up.jpg",
                "app_id": "10",
                "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/691719449_u%20l.jpg",
                "description": " Udaan  ",
                "category_id": "1",
                "genres_type": "1",
                "genres_name": "1",
                "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
                "subscription": "1",
                "is_free": "1",
                "video_type": "7",
                "url_type": "2",
                "ppv_amount": "0",
                "validity": "",
                "ppv_status": "0",
                "is_purchased": "1",
                "ppv_paid": "0",
                "main_id": "1"
            },
            {
                "id": "48",
                "type_id": "1",
                "type": "Movies",
                "title": "Andhadhun",
                "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
                "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
                "language": "",
                "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/724264048_an%20p.jpg",
                "app_id": "10",
                "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/639502048_an%20l.jpg",
                "description": "Andhadhun",
                "category_id": "1",
                "genres_type": "1",
                "genres_name": "1",
                "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
                "subscription": "1",
                "is_free": "1",
                "video_type": "7",
                "url_type": "2",
                "ppv_amount": "10",
                "validity": "",
                "ppv_status": "0",
                "is_purchased": "1",
                "ppv_paid": "0",
                "main_id": "1"
            },
            {
                "id": "47",
                "type_id": "1",
                "type": "Movies",
                "title": "Jai Bhim",
                "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141722/test/1659946380107_854022091447815800_video_VOD.m3u8",
                "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141722/test/1659946380107_854022091447815800_video_VOD.m3u8",
                "language": "",
                "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/395239847_j%20p.jpg",
                "app_id": "10",
                "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/196953047_jl.jpg",
                "description": "Jai Bhim",
                "category_id": "1",
                "genres_type": "1",
                "genres_name": "1",
                "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141722/test/1659946380107_854022091447815800_video_VOD.m3u8",
                "subscription": "1",
                "is_free": "1",
                "video_type": "7",
                "url_type": "2",
                "ppv_amount": "10",
                "validity": "",
                "ppv_status": "0",
                "is_purchased": "1",
                "ppv_paid": "0",
                "main_id": "1"
            },
            {
                "id": "46",
                "type_id": "1",
                "type": "Movies",
                "title": "Zindagi Na Milegi Dobara ",
                "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/137185/1659073371526_253944938181943500_video_VOD.m3u8",
                "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/137185/1659073371526_253944938181943500_video_VOD.m3u8",
                "language": "",
                "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/425379146_zi%20l.jpg",
                "app_id": "10",
                "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/103713846_zi%20p.jpg",
                "description": "Zindagi Na Milegi Dobara \r\n",
                "category_id": "1",
                "genres_type": "1",
                "genres_name": "1",
                "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/137185/1659073371526_253944938181943500_video_VOD.m3u8",
                "subscription": "1",
                "is_free": "1",
                "video_type": "7",
                "url_type": "2",
                "ppv_amount": "10",
                "validity": "",
                "ppv_status": "0",
                "is_purchased": "1",
                "ppv_paid": "0",
                "main_id": "1"
            },
            {
                "id": "45",
                "type_id": "1",
                "type": "Movies",
                "title": ". Ek Ruka Hua Faisla",
                "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/90195/test/1656657382599_207102464443241500_video_VOD.m3u8",
                "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/90195/test/1656657382599_207102464443241500_video_VOD.m3u8",
                "language": "",
                "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/761579045_ek%20p.jpg",
                "app_id": "10",
                "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/720894745_ek%20l.jpg",
                "description": ". Ek Ruka Hua Faisla ",
                "category_id": "1",
                "genres_type": "1",
                "genres_name": "1",
                "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/90195/test/1656657382599_207102464443241500_video_VOD.m3u8",
                "subscription": "1",
                "is_free": "1",
                "video_type": "7",
                "url_type": "2",
                "ppv_amount": "10",
                "validity": "",
                "ppv_status": "0",
                "is_purchased": "1",
                "ppv_paid": "0",
                "main_id": "1"
            }
        ],
        "type": "Movies"
    },
    "is_ios_price": 1,
    "iOSAppURL": "app store url when ios app live",
    "error": []
}
       
	##output_code_end## 

	False case 
	##output_code_start## 
	{"status":false,"message":"No Data Found.","data":[]}
	##output_code_end##
DIVIDER-SEGMENT

################################ GET MASTER DATA ########################
<h4>13) GET MASTER DATA</h4>

	Method -:      Post 
	Service url -: dashboard/dashboard/get_master_data

	Input parameter -:
	##input_param_start##
                    type_id:1  //Category
                    page:1      //Pagination
                    cat_id:1    // Type of Genre_category
	##input_param_end##
	Response -:

	True case 
	##output_code_start## 
    {
    "status": true,
    "message": "Movies data listed.",
    "data": {
        "type": "Movies",
        "genre_name": "HINDI",
        "type_id": "1",
        "content_list": [
            {
                "id": "152",
                "type_id": "1",
                "type": "Movies_1",
                "title": "lage raho munna bhai",
                "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/710541/1661848225167_425261771928072060_video_VOD.m3u8",
                "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/710541/1661848225167_425261771928072060_video_VOD.m3u8",
                "language": "",
                "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/4197903152_lage%20raho%20munna%20bhai%20p.jpg",
                "app_id": "10",
                "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/723541152_lage%20raho%20munna%20bhai%20l.jpg",
                "description": "An elderly man who’s been pushed around by a government official for far too long resorts to ‘Gandhigiri’ (Gandhiism) to achieve his means – he publicly shames the corrupt official by stripping off his clothes.     ",
                "category_id": "1",
                "genres_type": "1",
                "genres_name": "1",
                "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/90195/test/1656657382599_207102464443241500_video_VOD.m3u8",
                "subscription": "1",
                "is_free": "1",
                "video_type": "7",
                "url_type": "2",
                "ppv_amount": "19",
                "validity": "360",
                "ppv_status": "1",
                "is_purchased": "1",
                "ppv_paid": "0",
                "index_value": 0
            },
            {
                "id": "151",
                "type_id": "1",
                "type": "Movies_1",
                "title": "Bhotbhoti ",
                "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
                "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
                "language": "",
                "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/6442997151_banner-thumbnail-background-graphic-design-260nw-2050938644.webp",
                "app_id": "10",
                "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/604372151_vs-youtube-thumbnail-background-download-11626402992ahtdgvlgtc.jpg",
                "description": "JJHKJHKSJ",
                "category_id": "1",
                "genres_type": "1",
                "genres_name": "1",
                "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141941/1659965493661_577564927403321900_video_VOD.m3u8",
                "subscription": "1",
                "is_free": "1",
                "video_type": "7",
                "url_type": "2",
                "ppv_amount": "100",
                "validity": "360",
                "ppv_status": "1",
                "is_purchased": "1",
                "ppv_paid": "0",
                "index_value": 1
            },
            {
                "id": "145",
                "type_id": "1",
                "type": "Movies_1",
                "title": "EARTH",
                "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/90195/test/1656657382599_207102464443241500_video_VOD.m3u8",
                "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/90195/test/1656657382599_207102464443241500_video_VOD.m3u8",
                "language": "",
                "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/1275318145_azhar%20l.jpg",
                "app_id": "10",
                "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/2776360145_AZHAR%20LL.jpg",
                "description": "AZHAR               ",
                "category_id": "1",
                "genres_type": "1",
                "genres_name": "1",
                "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
                "subscription": "1",
                "is_free": "1",
                "video_type": "7",
                "url_type": "2",
                "ppv_amount": "1",
                "validity": "360",
                "ppv_status": "1",
                "is_purchased": "1",
                "ppv_paid": "0",
                "index_value": 2
            },
            {
                "id": "135",
                "type_id": "1",
                "type": "Movies_1",
                "title": "M.S. Dhoni The Untold Story",
                "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/710518/1661846582897_812646299440107800_video_VOD.m3u8",
                "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/710518/1661846582897_812646299440107800_video_VOD.m3u8",
                "language": "",
                "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/5509059135_ms%20dhoni%20p.jpg",
                "app_id": "10",
                "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/7388124135_dhoni%20l.jpg",
                "description": "Our #Singles Squad is all set to sneak in girls for a dhamakedaar house party! But how will they escape Sanskari landlord, Mishra and Haraami Seniors? Find out in Episode 01 now!       ",
                "category_id": "1",
                "genres_type": "1",
                "genres_name": "1",
                "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/710518/1661846582897_812646299440107800_video_VOD.m3u8",
                "subscription": "0",
                "is_free": "0",
                "video_type": "7",
                "url_type": "2",
                "ppv_amount": "1",
                "validity": "360",
                "ppv_status": "0",
                "is_purchased": "0",
                "ppv_paid": "0",
                "index_value": 3
            },
            {
                "id": "133",
                "type_id": "1",
                "type": "Movies_1",
                "title": "Zudgement Day AZHAR",
                "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/710518/1661846582897_812646299440107800_video_VOD.m3u8",
                "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/710518/1661846582897_812646299440107800_video_VOD.m3u8",
                "language": "",
                "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/2003965133_azhar%20p.jpg",
                "app_id": "10",
                "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/874623133_AZHAR%20LL.jpg",
                "description": "Azhar is a 2016 Indian Hindi biographical sports drama film directed by Tony D'Souza. The story and is inspired from the life of Indian cricketer and former ............     ",
                "category_id": "1",
                "genres_type": "1",
                "genres_name": "1",
                "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/710518/1661846582897_812646299440107800_video_VOD.m3u8",
                "subscription": "0",
                "is_free": "0",
                "video_type": "7",
                "url_type": "2",
                "ppv_amount": "1",
                "validity": "360",
                "ppv_status": "0",
                "is_purchased": "0",
                "ppv_paid": "0",
                "index_value": 4
            },
            {
                "id": "87",
                "type_id": "1",
                "type": "Movies_1",
                "title": "new move 001",
                "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/90195/test/1656657382599_207102464443241500_video_VOD.m3u8",
                "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/90195/test/1656657382599_207102464443241500_video_VOD.m3u8",
                "language": "",
                "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/294163187_Robert%20Pattinson.jpg",
                "app_id": "10",
                "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/493545387_pexels-photo-1767434.webp",
                "description": "hello only test ",
                "category_id": "1",
                "genres_type": "1",
                "genres_name": "1",
                "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/90195/test/1656657382599_207102464443241500_video_VOD.m3u8",
                "subscription": "1",
                "is_free": "1",
                "video_type": "7",
                "url_type": "2",
                "ppv_amount": "0",
                "validity": "",
                "ppv_status": "0",
                "is_purchased": "1",
                "ppv_paid": "0",
                "index_value": 5
            },
            {
                "id": "59",
                "type_id": "1",
                "type": "Movies_1",
                "title": "Lagaan",
                "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/137185/1659073371526_253944938181943500_video_VOD.m3u8",
                "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/137185/1659073371526_253944938181943500_video_VOD.m3u8",
                "language": "",
                "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/65580559_lp.jpg",
                "app_id": "10",
                "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/405981159_ll.jpg",
                "description": "Lagaan ",
                "category_id": "1",
                "genres_type": "1",
                "genres_name": "1",
                "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/137185/1659073371526_253944938181943500_video_VOD.m3u8",
                "subscription": "1",
                "is_free": "1",
                "video_type": "7",
                "url_type": "2",
                "ppv_amount": "0",
                "validity": "",
                "ppv_status": "0",
                "is_purchased": "1",
                "ppv_paid": "0",
                "index_value": 6
            },
            {
                "id": "58",
                "type_id": "1",
                "type": "Movies_1",
                "title": "Bhotbhoti ",
                "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
                "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
                "language": "",
                "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/472175158_m3.jpg",
                "app_id": "10",
                "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/494903258_Cinema01.jpg",
                "description": "141960_0_9295552800835144",
                "category_id": "1",
                "genres_type": "1",
                "genres_name": "1",
                "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
                "subscription": "1",
                "is_free": "1",
                "video_type": "7",
                "url_type": "2",
                "ppv_amount": "0",
                "validity": "",
                "ppv_status": "0",
                "is_purchased": "1",
                "ppv_paid": "0",
                "index_value": 7
            },
            {
                "id": "57",
                "type_id": "1",
                "type": "Movies_1",
                "title": "Gangs of Wasseypur",
                "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/137185/1659073371526_253944938181943500_video_VOD.m3u8",
                "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/137185/1659073371526_253944938181943500_video_VOD.m3u8",
                "language": "",
                "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/73677557_gp.jpg",
                "app_id": "10",
                "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/730539057_g%20l.jpg",
                "description": "Gangs of Wasseypur\r\n",
                "category_id": "1",
                "genres_type": "1",
                "genres_name": "1",
                "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/137185/1659073371526_253944938181943500_video_VOD.m3u8",
                "subscription": "1",
                "is_free": "1",
                "video_type": "7",
                "url_type": "2",
                "ppv_amount": "0",
                "validity": "",
                "ppv_status": "0",
                "is_purchased": "1",
                "ppv_paid": "0",
                "index_value": 8
            },
            {
                "id": "56",
                "type_id": "1",
                "type": "Movies_1",
                "title": " Koshish",
                "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/137185/1659073371526_253944938181943500_video_VOD.m3u8",
                "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/137185/1659073371526_253944938181943500_video_VOD.m3u8",
                "language": "",
                "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/323879556_k%20p.jpg",
                "app_id": "10",
                "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/162669356_k%20l.jpg",
                "description": " Koshish",
                "category_id": "1",
                "genres_type": "1",
                "genres_name": "1",
                "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/137185/1659073371526_253944938181943500_video_VOD.m3u8",
                "subscription": "1",
                "is_free": "1",
                "video_type": "7",
                "url_type": "2",
                "ppv_amount": "0",
                "validity": "",
                "ppv_status": "0",
                "is_purchased": "1",
                "ppv_paid": "0",
                "index_value": 9
            },
            {
                "id": "55",
                "type_id": "1",
                "type": "Movies_1",
                "title": " 3 Idiots",
                "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/137185/1659073371526_253944938181943500_video_VOD.m3u8",
                "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/137185/1659073371526_253944938181943500_video_VOD.m3u8",
                "language": "",
                "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/382461355_3%20p.jpg",
                "app_id": "10",
                "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/350631755_3%20l.jpg",
                "description": " 3 Idiots",
                "category_id": "1",
                "genres_type": "1",
                "genres_name": "1",
                "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/137185/1659073371526_253944938181943500_video_VOD.m3u8",
                "subscription": "1",
                "is_free": "1",
                "video_type": "7",
                "url_type": "2",
                "ppv_amount": "0",
                "validity": "",
                "ppv_status": "0",
                "is_purchased": "1",
                "ppv_paid": "0",
                "index_value": 10
            },
            {
                "id": "54",
                "type_id": "1",
                "type": "Movies_1",
                "title": " Sardar Udham",
                "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/90195/test/1656657382599_207102464443241500_video_VOD.m3u8",
                "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/90195/test/1656657382599_207102464443241500_video_VOD.m3u8",
                "language": "",
                "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/91001654_sr%20p.jpg",
                "app_id": "10",
                "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/177613954_srl.jpg",
                "description": " Sardar Udham ",
                "category_id": "1",
                "genres_type": "1",
                "genres_name": "1",
                "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/90195/test/1656657382599_207102464443241500_video_VOD.m3u8",
                "subscription": "1",
                "is_free": "1",
                "video_type": "7",
                "url_type": "2",
                "ppv_amount": "0",
                "validity": "",
                "ppv_status": "1",
                "is_purchased": "1",
                "ppv_paid": "0",
                "index_value": 11
            },
            {
                "id": "53",
                "type_id": "1",
                "type": "Movies_1",
                "title": " Swades",
                "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/137185/1659073371526_253944938181943500_video_VOD.m3u8",
                "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/137185/1659073371526_253944938181943500_video_VOD.m3u8",
                "language": "",
                "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/275933653_sw%20p.jpg",
                "app_id": "10",
                "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/683119353_sw%20l.jpg",
                "description": " Swades",
                "category_id": "1",
                "genres_type": "1",
                "genres_name": "1",
                "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/137185/1659073371526_253944938181943500_video_VOD.m3u8",
                "subscription": "1",
                "is_free": "1",
                "video_type": "7",
                "url_type": "2",
                "ppv_amount": "0",
                "validity": "",
                "ppv_status": "0",
                "is_purchased": "1",
                "ppv_paid": "0",
                "index_value": 12
            },
            {
                "id": "52",
                "type_id": "1",
                "type": "Movies_1",
                "title": " Shershaah",
                "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141722/test/1659946380107_854022091447815800_video_VOD.m3u8",
                "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141722/test/1659946380107_854022091447815800_video_VOD.m3u8",
                "language": "",
                "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/765854552_sh%20p.jpg",
                "app_id": "10",
                "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/291687252_sh%20l.jpg",
                "description": " Shershaah",
                "category_id": "1",
                "genres_type": "1",
                "genres_name": "1",
                "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141722/test/1659946380107_854022091447815800_video_VOD.m3u8",
                "subscription": "1",
                "is_free": "1",
                "video_type": "7",
                "url_type": "2",
                "ppv_amount": "0",
                "validity": "",
                "ppv_status": "0",
                "is_purchased": "1",
                "ppv_paid": "0",
                "index_value": 13
            },
            {
                "id": "51",
                "type_id": "1",
                "type": "Movies_1",
                "title": " Bhaag Milkha Bhaag",
                "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141722/test/1659946380107_854022091447815800_video_VOD.m3u8",
                "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141722/test/1659946380107_854022091447815800_video_VOD.m3u8",
                "language": "",
                "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/739318751_bh%20p.jpg",
                "app_id": "10",
                "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/538133551_bh%20l.jpg",
                "description": " Bhaag Milkha Bhaag ",
                "category_id": "1",
                "genres_type": "1",
                "genres_name": "1",
                "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141722/test/1659946380107_854022091447815800_video_VOD.m3u8",
                "subscription": "1",
                "is_free": "1",
                "video_type": "7",
                "url_type": "2",
                "ppv_amount": "0",
                "validity": "",
                "ppv_status": "0",
                "is_purchased": "1",
                "ppv_paid": "0",
                "index_value": 14
            }
        ]
    },
    "is_ios_price": 1,
    "iOSAppURL": "app store url when ios app live",
    "error": []
}
	##output_code_end## 

	False case 
	##output_code_start## 
	{"status":false,"message":"Not data found.","data":[]}
	##output_code_end##
DIVIDER-SEGMENT

################################ POST ADD TO WATCHLIST ########################
<h4>14) POST ADD TO WATCHLIST</h4>

	Method -:      Post 
	Service url -: /dashboard/dashboard/add_to_wishlist

	Input parameter -:
	##input_param_start##
                    product_data:[{"main_id":1,"product_id":1,"type_id":1}]  //param json format
	##input_param_end##
	Response -:

	True case 
	##output_code_start## 
    {
    "status": true,
    "message": "Added to watchlist",
    "data": {
        "insert_id": 5,
        "counter": 1
    },
    "is_ios_price": 1,
    "iOSAppURL": "app store url when ios app live",
    "error": []
}
	##output_code_end## 

	False case 
	##output_code_start## 
	{"status":false,"message":"No data could not be Added.","data":[]}
	##output_code_end##
DIVIDER-SEGMENT

################################ GET WATCHLIST ########################
<h4>15) GET WATCHLIST</h4>

	Method -:      Post 
	Service url -: dashboard/dashboard/get_wishlist

	Input parameter -:
	##input_param_start##
           
	##input_param_end##
	Response -:

	True case 
	##output_code_start## 
    {
    "status": true,
    "message": "Watchlist data listed.",
    "data": [
        {
            "id": "151",
            "type_id": "1",
            "type": "Watchlist",
            "genres_type": "1",
            "title": "Bhotbhoti ",
            "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
            "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
            "language": "",
            "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/6442997151_banner-thumbnail-background-graphic-design-260nw-2050938644.webp",
            "app_id": "10",
            "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/604372151_vs-youtube-thumbnail-background-download-11626402992ahtdgvlgtc.jpg",
            "description": "JJHKJHKSJ",
            "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141941/1659965493661_577564927403321900_video_VOD.m3u8",
            "subscription": "1",
            "is_free": "1",
            "url_type": "2",
            "ppv_amount": "100",
            "validity": "360",
            "ppv_status": "1",
            "is_purchased": "1",
            "ppv_paid": "0",
            "main_id": "1",
            "index_value": 0
        },
        {
            "id": "145",
            "type_id": "1",
            "type": "Watchlist",
            "genres_type": "1",
            "title": "EARTH",
            "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/90195/test/1656657382599_207102464443241500_video_VOD.m3u8",
            "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/90195/test/1656657382599_207102464443241500_video_VOD.m3u8",
            "language": "",
            "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/1275318145_azhar%20l.jpg",
            "app_id": "10",
            "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/2776360145_AZHAR%20LL.jpg",
            "description": "AZHAR               ",
            "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
            "subscription": "1",
            "is_free": "1",
            "url_type": "2",
            "ppv_amount": "1",
            "validity": "360",
            "ppv_status": "1",
            "is_purchased": "1",
            "ppv_paid": "0",
            "main_id": "1",
            "index_value": 1
        },
        {
            "id": "129",
            "type_id": "4",
            "type": "Watchlist",
            "title": "MANN BHARYAA",
            "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/137185/1659073371526_253944938181943500_video_VOD.m3u8",
            "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/137185/1659073371526_253944938181943500_video_VOD.m3u8",
            "language": "",
            "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/2531865129_MANN.jpg",
            "app_id": "10",
            "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/1925784129_MANN%20L.jpg",
            "description": "MANN BHARAYAA  ",
            "movie_trailer_url": "",
            "subscription": "0",
            "is_free": "0",
            "url_type": "2",
            "ppv_amount": "4",
            "validity": "360",
            "ppv_status": "0",
            "is_purchased": "0",
            "ppv_paid": "0",
            "main_id": "4",
            "index_value": 2
        },
        {
            "id": "126",
            "type_id": "4",
            "type": "Watchlist",
            "title": "testing",
            "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141722/test/1659946380107_854022091447815800_video_VOD.m3u8",
            "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141722/test/1659946380107_854022091447815800_video_VOD.m3u8",
            "language": "",
            "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/4621098126_temporary%20pyar%20p.jpg",
            "app_id": "10",
            "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/2939518126_temporary%20pyar%20l.jpg",
            "description": "Rovengi mukkadran nu heere meriye\r\nJe heereyan de haar jeha yaar kho gaya...       ",
            "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141941/1659965493661_577564927403321900_video_VOD.m3u8",
            "subscription": "0",
            "is_free": "0",
            "url_type": "2",
            "ppv_amount": "4",
            "validity": "360",
            "ppv_status": "0",
            "is_purchased": "0",
            "ppv_paid": "0",
            "main_id": "4",
            "index_value": 3
        },
        {
            "id": "125",
            "type_id": "4",
            "type": "Watchlist",
            "title": "Jannat Ve",
            "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141941/1659965493661_577564927403321900_video_VOD.m3u8",
            "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141941/1659965493661_577564927403321900_video_VOD.m3u8",
            "language": "",
            "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/2015572125_jannat%20p.jpg",
            "app_id": "10",
            "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/4362894125_jannat%20l.jpg",
            "description": " Jannat Ve Song from the Jannat Ve album is voiced by famous singer Darshan Raval.\r\n   ",
            "movie_trailer_url": "",
            "subscription": "1",
            "is_free": "1",
            "url_type": "2",
            "ppv_amount": "4",
            "validity": "360",
            "ppv_status": "1",
            "is_purchased": "1",
            "ppv_paid": "0",
            "main_id": "4",
            "index_value": 4
        }
    ],
    "is_ios_price": 1,
    "iOSAppURL": "app store url when ios app live",
    "error": []
}
	##output_code_end## 

	False case 
	##output_code_start## 
	{"status":false,"message":"Empty Watchlist.","data":[]}
	##output_code_end##
DIVIDER-SEGMENT

################################ DELETE WATCHLIST EMEMENTS ########################
<h4>16) DELETE WATCHLIST EMEMENTS</h4>

	Method -:      Post 
	Service url -: dashboard/dashboard/delete_wishlist

	Input parameter -:
	##input_param_start##
                        main_id:1
                        product_id:23
                        type_id:1
                        type:1
	##input_param_end##
	Response -:

	True case 
	##output_code_start## 
    {
    "status": true,
    "message": "Watchlist updated",
    "data": [
        {
            "id": "151",
            "type_id": "1",
            "type": "Watchlist",
            "genres_type": "1",
            "title": "Bhotbhoti ",
            "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
            "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
            "language": "",
            "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/6442997151_banner-thumbnail-background-graphic-design-260nw-2050938644.webp",
            "app_id": "10",
            "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/604372151_vs-youtube-thumbnail-background-download-11626402992ahtdgvlgtc.jpg",
            "description": "JJHKJHKSJ",
            "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141941/1659965493661_577564927403321900_video_VOD.m3u8",
            "subscription": "1",
            "is_free": "1",
            "url_type": "2",
            "ppv_amount": "100",
            "validity": "360",
            "ppv_status": "1",
            "is_purchased": "1",
            "ppv_paid": "0",
            "main_id": "1",
            "index_value": 0
        },
        {
            "id": "145",
            "type_id": "1",
            "type": "Watchlist",
            "genres_type": "1",
            "title": "EARTH",
            "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/90195/test/1656657382599_207102464443241500_video_VOD.m3u8",
            "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/90195/test/1656657382599_207102464443241500_video_VOD.m3u8",
            "language": "",
            "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/1275318145_azhar%20l.jpg",
            "app_id": "10",
            "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/2776360145_AZHAR%20LL.jpg",
            "description": "AZHAR               ",
            "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141960/1659966534628_7003087914303352_video_VOD.m3u8",
            "subscription": "1",
            "is_free": "1",
            "url_type": "2",
            "ppv_amount": "1",
            "validity": "360",
            "ppv_status": "1",
            "is_purchased": "1",
            "ppv_paid": "0",
            "main_id": "1",
            "index_value": 1
        },
        {
            "id": "129",
            "type_id": "4",
            "type": "Watchlist",
            "title": "MANN BHARYAA",
            "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/137185/1659073371526_253944938181943500_video_VOD.m3u8",
            "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/137185/1659073371526_253944938181943500_video_VOD.m3u8",
            "language": "",
            "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/2531865129_MANN.jpg",
            "app_id": "10",
            "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/1925784129_MANN%20L.jpg",
            "description": "MANN BHARAYAA  ",
            "movie_trailer_url": "",
            "subscription": "0",
            "is_free": "0",
            "url_type": "2",
            "ppv_amount": "4",
            "validity": "360",
            "ppv_status": "0",
            "is_purchased": "0",
            "ppv_paid": "0",
            "main_id": "4",
            "index_value": 2
        },
        {
            "id": "126",
            "type_id": "4",
            "type": "Watchlist",
            "title": "testing",
            "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141722/test/1659946380107_854022091447815800_video_VOD.m3u8",
            "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141722/test/1659946380107_854022091447815800_video_VOD.m3u8",
            "language": "",
            "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/4621098126_temporary%20pyar%20p.jpg",
            "app_id": "10",
            "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/2939518126_temporary%20pyar%20l.jpg",
            "description": "Rovengi mukkadran nu heere meriye\r\nJe heereyan de haar jeha yaar kho gaya...       ",
            "movie_trailer_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141941/1659965493661_577564927403321900_video_VOD.m3u8",
            "subscription": "0",
            "is_free": "0",
            "url_type": "2",
            "ppv_amount": "4",
            "validity": "360",
            "ppv_status": "0",
            "is_purchased": "0",
            "ppv_paid": "0",
            "main_id": "4",
            "index_value": 3
        },
        {
            "id": "125",
            "type_id": "4",
            "type": "Watchlist",
            "title": "Jannat Ve",
            "movie_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141941/1659965493661_577564927403321900_video_VOD.m3u8",
            "file_url": "https://d23kfzlh2otf0u.cloudfront.net/file_library/videos/vod_non_drm_ios/141941/1659965493661_577564927403321900_video_VOD.m3u8",
            "language": "",
            "thumbnail_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/2015572125_jannat%20p.jpg",
            "app_id": "10",
            "movie_poster_url": "https://d23kfzlh2otf0u.cloudfront.net/10/admin_v1/file_manager/videos/4362894125_jannat%20l.jpg",
            "description": " Jannat Ve Song from the Jannat Ve album is voiced by famous singer Darshan Raval.\r\n   ",
            "movie_trailer_url": "",
            "subscription": "1",
            "is_free": "1",
            "url_type": "2",
            "ppv_amount": "4",
            "validity": "360",
            "ppv_status": "1",
            "is_purchased": "1",
            "ppv_paid": "0",
            "main_id": "4",
            "index_value": 4
        }
    ],
    "is_ios_price": 1,
    "iOSAppURL": "app store url when ios app live",
    "error": []
}
	##output_code_end## 

	False case 
	##output_code_start## 
	{"status":false,"message":"No Such Element Found.","data":[]}
	##output_code_end##
DIVIDER-SEGMENT

################################ POST INITALIZE TRANSACTION ########################
<h4>17) POST INITALIZE TRANSACTION</h4>

	Method -:      Post 
	Service url -: membership/f_payment

	Input parameter -:
	##input_param_start##
                type:1
                plan_price:99
                pay_via:1
                payment_mode:1
                movie_id:131
	##input_param_end##
	Response -:

	True case 
	##output_code_start## 
                    {
                    "status": true,
                    "message": "Payment initialized.",
                    "data": {
                        "pre_transaction_id": "order_KEBgYLGmezp8nm",
                        "CFREE_SECRET_KEY": "gfhg"
                    },
                    "is_ios_price": 1,
                    "iOSAppURL": "app store url when ios app live",
                    "error": []
                }
	##output_code_end## 

	False case 
	##output_code_start## 
	{"status":false,"message":"Transaction Initalization Failed.","data":[]}
	##output_code_end##
DIVIDER-SEGMENT

################################ POST COMPLETE TRANSACTION ########################
<h4>18) POST COMPLETE TRANSACTION</h4>

	Method -:      Post 
	Service url -: membership/f_payment

	Input parameter -:
	##input_param_start##
            type:2
            pre_transaction_id:order_KEBgYLGmezp8nm
            post_transaction_id:etert34445      //if you have this will be use in case of redirect from push notification
	##input_param_end##
	Response -:

	True case 
	##output_code_start## 
    {
    "status": true,
    "message": "Payment Completed. You can access this movie from My Library secion.",
    "data": [],
    "is_ios_price": 1,
    "iOSAppURL": "app store url when ios app live",
    "error": []
}
	##output_code_end## 

	False case 
	##output_code_start## 
	{"status":false,"message":"Transaction Failed.","data":[]}
	##output_code_end##
DIVIDER-SEGMENT

################################ GET COURSE COMBO ########################
<h4>19) GET COURSE COMBO</h4>

	Method -:      Post 
	Service url -: course/get_master_data

	Input parameter -:
	##input_param_start##
            tile_id:6027
            type:course_combo
            revert_api:0#0#0#0
            course_id:3478
	##input_param_end##
	Response -:

	True case 
	##output_code_start## 
        {
            "status": true,
            "message": "Courses Displayed",
            "time": 1633440723,
            "data": [
                {
                    "id": "3478",
                    "segment_information":"1 PDF,2 Video"
                    "title": "book1",
                    "cover_image": ""
                }
            ]
        }
	##output_code_end## 

	False case 
	##output_code_start## 
	{"status":false,"message":"Not data found.","data":[]}
	##output_code_end##
DIVIDER-SEGMENT

################################ GET PDF DATA L1 ########################
<h4>19) GET PDF DATA L1</h4>

	Method -:      Post 
	Service url -: course/get_master_data

	Input parameter -:
	##input_param_start##
            tile_id:20370
            type:pdf
            revert_api:0#0#0#0
            course_id:3478
            layer:1
            page:1
	##input_param_end##
	Response -:

	True case 
	##output_code_start## 
        {
            "status": true,
            "message": "Pdf data",
            "time": 1633440723,
            "data": {
                "layer": "1",
                "list": [
                    {
                        "id": "503",
                        "title": "Courses Chapters",
                        "image_icon": "",
                        "c_code": "",
                        "is_live": "0",
                        "total": "1"
                    }
                ]
            }
        }
	##output_code_end## 

	False case 
	##output_code_start## 
	{"status":false,"message":"Not Data.","data":[]}
	##output_code_end##
DIVIDER-SEGMENT

################################ GET PDF DATA L2 ########################
<h4>20) GET PDF DATA L2</h4>

	Method -:      Post 
	Service url -: course/get_master_data

	Input parameter -:
	##input_param_start##
            tile_id:20370
            type:pdf
            revert_api:0#0#0#0
            course_id:3478
            layer:2
            page:1
            subject_id:503
	##input_param_end##
	Response -:

	True case 
	##output_code_start## 
        {
            "status": true,
            "message": "Pdf data",
            "time": 1633440723,
            "data": {
                "layer": "2",
                "list": [
                    {
                        "id": "5915",
                        "title": "Ch- 09 बल तथा गति के नियम (NEW)",
                        "image_icon": "",
                        "c_code": "",
                        "is_live": "0",
                        "total": "1"
                    }
                ]
            }
        }
	##output_code_end## 

	False case 
	##output_code_start## 
	{"status":false,"message":"Not data found.","data":[]}
	##output_code_end##
DIVIDER-SEGMENT

################################ GET PDF DATA L3 ########################
<h4>21) GET PDF DATA L3</h4>

	Method -:      Post 
	Service url -: course/get_master_data

	Input parameter -:
	##input_param_start##
            tile_id:20370
            type:pdf
            revert_api:0#0#0#0
            course_id:3478
            layer:3
            page:1
            subject_id:503
            topic_id:5915

            file_id:1 //if you have this will be use in case of redirect from push notification
	##input_param_end##
	Response -:

	True case 
	##output_code_start## 
        {
            "status": true,
            "message": "Pdf data",
            "time": 1633440723,
            "data": {
                "layer": "3",
                "list": [
                    {
                        "id": "1109260",
                        "file_url": "",
                        "is_download": "0", //0-no,1-yes
                        "thumbnail_url": "",
                        "title": "Part- 06 पाठ्यपुस्तक के प्रश्नोत्तर",
                        "description": "Ch- 09 बल तथा गति के नियम (NEW)",
                        "video_type": "0",
                        "is_locked": "1",
                        "is_live": "0",
                        "is_chat_locked": "0",
                        "video_length": "0",
                        "chat_node": "",
                        "live_status": "0",
                        "open_in_app": "",
                        "playtime": "0"
                    }
                ]
            }
        }
	##output_code_end## 

	False case 
	##output_code_start## 
	{"status":false,"message":"Not data found.","data":[]}
	##output_code_end##
DIVIDER-SEGMENT

################################ GET LINK DATA L1 ########################
<h4>22) GET LINK DATA L1</h4>

	Method -:      Post 
	Service url -: course/get_master_data

	Input parameter -:
	##input_param_start##
            tile_id:20371
            type:link
            revert_api:0#0#0#0
            course_id:3478
            layer:1
            page:1
	##input_param_end##
	Response -:

	True case 
	##output_code_start## 
        {
            "status": true,
            "message": "Link data",
            "time": 1633440723,
            "data": {
                "layer": "1",
                "list": [
                    {
                        "id": "503",
                        "title": "Courses Chapters",
                        "image_icon": "",
                        "c_code": "",
                        "is_live": "0",
                        "total": "1"
                    }
                ]
            }
        }
	##output_code_end## 

	False case 
	##output_code_start## 
	{"status":false,"message":"Not Data.","data":[]}
	##output_code_end##
DIVIDER-SEGMENT

################################ GET LINK DATA L2 ########################
<h4>23) GET LINK DATA L2</h4>

	Method -:      Post 
	Service url -: course/get_master_data

	Input parameter -:
	##input_param_start##
            tile_id:20371
            type:link
            revert_api:0#0#0#0
            course_id:3478
            layer:2
            page:1
            subject_id:503
	##input_param_end##
	Response -:

	True case 
	##output_code_start## 
        {
            "status": true,
            "message": "Link data",
            "time": 1633440723,
            "data": {
                "layer": "2",
                "list": [
                    {
                        "id": "20768",
                        "title": "Youtube Live",
                        "image_icon": "",
                        "c_code": "",
                        "is_live": "0",
                        "total": "1"
                    }
                ]
            }
        }
	##output_code_end## 

	False case 
	##output_code_start## 
	{"status":false,"message":"Not data found.","data":[]}
	##output_code_end##
DIVIDER-SEGMENT

################################ GET LINK DATA L3 ########################
<h4>24) GET LINK DATA L3</h4>

	Method -:      Post 
	Service url -: course/get_master_data

	Input parameter -:
	##input_param_start##
            tile_id:20371
            type:link
            revert_api:0#0#0#0
            course_id:3478
            layer:3
            page:1
            subject_id:503
            topic_id:20768

            file_id:1 //if you have this will be use in case of redirect from push notification
	##input_param_end##
	Response -:

	True case 
	##output_code_start## 
        {
            "status": true,
            "message": "Link data",
            "time": 1633440723,
            "data": {
                "layer": "3",
                "list": [
                    {
                        "id": "1109353",
                        "file_url": "",
                        "is_download": "0", //0-no,1-yes
                        "thumbnail_url": "",
                        "title": "YouTube",
                        "description": "",
                        "video_type": "0",
                        "is_locked": "1",
                        "is_live": "0",
                        "is_chat_locked": "0",
                        "video_length": "0",
                        "chat_node": "",
                        "live_status": "0",
                        "open_in_app": "",
                        "playtime": "0"
                    }
                ]
            }
        }
	##output_code_end## 

	False case 
	##output_code_start## 
	{"status":false,"message":"Not data found.","data":[]}
	##output_code_end##
DIVIDER-SEGMENT

################################ GET TEST DATA L1 ########################
<h4>25) GET TEST DATA L1</h4>

	Method -:      Post 
	Service url -: course/get_master_data

	Input parameter -:
	##input_param_start##
            tile_id:20371
            type:test
            revert_api:0#0#0#0
            course_id:3478
            layer:1
            page:1
	##input_param_end##
	Response -:

	True case 
	##output_code_start## 
        {
            "status": true,
            "message": "Test data",
            "time": 1633440723,
            "data": {
                "layer": "1",
                "list": [
                    {
                        "id": "503",
                        "title": "Courses Chapters",
                        "image_icon": "",
                        "c_code": "",
                        "is_live": "0",
                        "total": "1"
                    }
                ]
            }
        }
	##output_code_end## 

	False case 
	##output_code_start## 
	{"status":false,"message":"Not Data.","data":[]}
	##output_code_end##
DIVIDER-SEGMENT

################################ GET TEST DATA L2 ########################
<h4>26) GET TEST DATA L2</h4>

	Method -:      Post 
	Service url -: course/get_master_data

	Input parameter -:
	##input_param_start##
            tile_id:20371
            type:test
            revert_api:0#0#0#0
            course_id:3478
            layer:2
            page:1
            subject_id:503
	##input_param_end##
	Response -:

	True case 
	##output_code_start## 
        {
            "status": true,
            "message": "Test data",
            "time": 1633440723,
            "data": {
                "layer": "2",
                "list": [
                    {
                        "id": "20768",
                        "title": "Youtube Live",
                        "image_icon": "",
                        "c_code": "",
                        "is_live": "0",
                        "total": "1"
                    }
                ]
            }
        }
	##output_code_end## 

	False case 
	##output_code_start## 
	{"status":false,"message":"Not data found.","data":[]}
	##output_code_end##
DIVIDER-SEGMENT

################################ GET TEST DATA L3 ########################
<h4>27) GET TEST DATA L3</h4>

	Method -:      Post 
	Service url -: course/get_master_data

	Input parameter -:
	##input_param_start##
            tile_id:20371
            type:test
            revert_api:0#0#0#0
            course_id:3478
            layer:2
            page:1
            subject_id:503
            topic_id:503
	##input_param_end##
	Response -:

	True case 
	##output_code_start## 
        {
            "status": true,
            "message": "Test data",
            "time": 1633440723,
            "data": {
                "layer": "3",
                "list": [
                    {
                        "id": "1109319",
                        "image": "",
                        "result_date": 0,
                        "test_series_name": " Weekly Test-21 (19-September)",
                        "test_code": "0",
                        "test_type": "1",
                        "set_type": "0",
                        "total_marks": "10",
                        "start_date": "",
                        "end_date": "",
                        "lang_id": "1",
                        "is_locked": "1",
                        "total_questions": "10",
                        "time_in_mins": "10",
                        "report_id": "0",
                        "submission_type": "0", //0-s3,1-api
                        "marks": "2",
                        "state": "",// ""-attempt now, 0-resume,1-result
                        "correct_count": "0",
                        "incorrect_count": "0",
                        "is_reattempt": "0",
                        "lang_used": ""
                    }
                ]
            }
        }
	##output_code_end## 

	False case 
	##output_code_start## 
	{"status":false,"message":"Not data found.","data":[]}
	##output_code_end##
DIVIDER-SEGMENT

################################ GET CONTENT DATA L1 ########################
<h4>28) GET CONTENT DATA L1</h4>

	Method -:      Post 
	Service url -: course/get_master_data

	Input parameter -:
	##input_param_start##
            tile_id:20371
            type:content
            revert_api:0#0#0#0
            course_id:3478
            layer:1
            page:1
	##input_param_end##
	Response -:

	True case 
	##output_code_start## 
        {
            "status": true,
            "message": "Content data",
            "time": 1633440723,
            "data": {
                "layer": "1",
                "list": [
                    {
                        "id": "503",
                        "title": "Courses Chapters",
                        "image_icon": "",
                        "c_code": "",
                        "is_live": "0",
                        "total": "1"
                    }
                ]
            }
        }
	##output_code_end## 

	False case 
	##output_code_start## 
	{"status":false,"message":"Not Data.","data":[]}
	##output_code_end##
DIVIDER-SEGMENT

################################ GET CONTENT DATA L2 ########################
<h4>29) GET CONTENT DATA L2</h4>

	Method -:      Post 
	Service url -: course/get_master_data

	Input parameter -:
	##input_param_start##
            tile_id:20371
            type:content
            revert_api:0#0#0#0
            course_id:3478
            layer:2
            page:1
            subject_id:503
	##input_param_end##
	Response -:

	True case 
	##output_code_start## 
        {
            "status": true,
            "message": "Content data",
            "time": 1633440723,
            "data": {
                "layer": "2",
                "list": [
                    {
                        "id": "20768",
                        "title": "Youtube Live",
                        "image_icon": "",
                        "c_code": "",
                        "is_live": "0",
                        "total": "1"
                    }
                ]
            }
        }
	##output_code_end## 

	False case 
	##output_code_start## 
	{"status":false,"message":"Not data found.","data":[]}
	##output_code_end##
DIVIDER-SEGMENT

################################ GET CONTENT DATA L3 ########################
<h4>30) GET CONTENT DATA L3</h4>

	Method -:      Post 
	Service url -: course/get_master_data

	Input parameter -:
	##input_param_start##
            tile_id:20371
            type:content
            revert_api:0#0#0#0
            course_id:3478
            layer:2
            page:1
            subject_id:503
            topic_id:503
	##input_param_end##
	Response -:

	True case 
	##output_code_start## 
        {
            "status": true,
            "message": "Content data",
            "time": 1633440723,
            "data": {
                "layer": "3",
                "list": [
                    {
                        "id": "1109319",
                        "image": "",
                        "title": "Weekly Test-21 (19-September)",
                        "test_code": "0",
                        "test_type": "1",
                        "set_type": "0",
                        "total_marks": "10",
                        "start_date": "",
                        "end_date": "",
                        "lang_id": "1",
                        "total_questions": "10",
                        "time_in_mins": "10",
                        "report_id": "0",
                        "submission_type": "0", //0-s3,1-api
                        "marks": "2",
                        "state": "",
                        "correct_count": "0",
                        "incorrect_count": "0",
                        "is_reattempt": "0",
                        "lang_used": "",
                        "is_locked": "1",
                        "file_type": "t"//1 =pdf,2 =ppt,3 =video,4=epub,5=doc,6=image,7-concept,8-link,t-test
                    },
                    {
                        "id": "1109353",
                        "file_type": "8", //1 =pdf,2 =ppt,3 =video,4=epub,5=doc,6=image,7-concept,8-link,t-test
                        "video_type": "0", //0=normal_video, 1=youtube,2=vimeo,3=vimeo_streaming,4-YT Stream,5-AWS Stream,6-JW
                        "file_url": "",
                        "is_download": "0", //0-no,1-yes
                        "thumbnail_url": "",
                        "title": "YouTube",
                        "description": "",
                        "video_length": "0",
                        "chat_node": "",
                        "live_status": "0",
                        "open_in_app": "",
                        "playtime": "0",
                        "is_locked": "1",
                        "is_live": "0",
                        "is_chat_locked": "0"
                    }
                ]
            }
        }
	##output_code_end## 

	False case 
	##output_code_start## 
	{"status":false,"message":"Not data found.","data":[]}
	##output_code_end##
DIVIDER-SEGMENT

################################ GET COURSE FILTERS ########################
<h4>31) GET COURSE FILTERS</h4>

	Method -:      Post 
	Service url -: course/get_course_filters

	Input parameter -:
	##input_param_start##
            sub_cat_id:629
	##input_param_end##
	Response -:

	True case 
	##output_code_start## 
        {
            "status": true,
            "message": "Filter Displayed",
            "time": 1633440723,
            "data": {
                "languages": [
                    {
                        "id": "1",
                        "language": "English"
                    }
                ],
                "subjects": [
                    {
                        "id": "1",
                        "title": " Computer Science Engineering"
                    }
                ]
            }
        }
	##output_code_end## 

	False case 
	##output_code_start## 
	{"status":false,"message":"Not data found.","data":[]}
	##output_code_end##
DIVIDER-SEGMENT

################################ PAYMENT APPLY COUPON ########################
<h4>32) PAYMENT APPLY COUPON</h4>

	Method -:      Post 
	Service url -: payment/apply_coupon

	Input parameter -:
	##input_param_start##
            course_id:629
            parent_id:0 //if purchasing parent course
            coupon_code:TESTING
	##input_param_end##
	Response -:

	True case 
	##output_code_start## 
        {
            "status": true,
            "message": "Promo Code Applied.",
            "time": 1633440723,
            "data": {
                "id": "1",
                "coupon_type": "2",//1-Flat,2-Percentage
                "coupon_value": "100",
                "course_id": {
                    "course_id": "7410"
                }
            }
        }
	##output_code_end## 

	False case 
	##output_code_start## 
	{"status":false,"message":"Invalid","data":[]}
	##output_code_end##
DIVIDER-SEGMENT

################################ PAYMENT FREE ########################
<h4>33) PAYMENT FREE</h4>

	Method -:      Post 
	Service url -: payment/free_transaction

	Input parameter -:
	##input_param_start##
            course_id:629
            parent_id:0 //if purchasing parent course
            coupon_applied:0 //if have coupon id
	##input_param_end##
	Response -:

	True case 
	##output_code_start## 
        {"status":true,"time": 1633440723,"message":"Added","data":[]}
	##output_code_end## 

	False case 
	##output_code_start## 
	{"status":false,"message":"Already Added","data":[]}
	##output_code_end##
DIVIDER-SEGMENT

################################ INIT PAYMENT ########################
<h4>34) INIT PAYMENT</h4>

	Method -:      Post 
	Service url -: payment/f_payment

	Input parameter -:
	##input_param_start##
            type:1
            course_id:9135
            parent_id:0 //if purchasing parent course
            course_price:3000
            tax:0
            pay_via:3
            coupon_applied:0
	##input_param_end##
	Response -:

	True case 
	##output_code_start## 
        {
            "status": true,
            "message": "Payment initialized.",
            "data": {
                "pre_transaction_id": "order_IDsC16rAoxzfgu"
            },
            "time": 1635231229
        }
	##output_code_end## 

	False case 
	##output_code_start## 
	{"status":false,"message":"Already Added","data":[]}
	##output_code_end##
DIVIDER-SEGMENT

################################ COMPLETE PAYMENT ########################
<h4>35) COMPLETE PAYMENT</h4>

	Method -:      Post 
	Service url -: payment/f_payment

	Input parameter -:
	##input_param_start##
            course_id:9135
            parent_id:0 //if purchasing parent course
            type:2
            pre_transaction_id:order_IDsbiPOLsyKmJG
            transaction_status:1 //0=>initialized,1=>complete,2=>failed,3=>RI,4=>rc,5=>Declined,6=>Transfered	
            post_transaction_id:mohit
	##input_param_end##
	Response -:

	True case 
	##output_code_start## 
        {"status":true,"message":"Payment Completed","data":[],"time":1635241878}
	##output_code_end## 

	False case 
	##output_code_start## 
	{"status":false,"message":"Already Added","data":[]}
	##output_code_end##
DIVIDER-SEGMENT


################################ INIT EXTEND PAYMENT ########################
<h4>36) INIT EXTEND PAYMENT</h4>

	Method -:      Post 
	Service url -: payment/f_payment

	Input parameter -:
	##input_param_start##
            course_id:7410
            parent_id:0 //if purchasing parent course
            type:3
            txn_id:1245155
            extender_id:1
            pay_via:2
	##input_param_end##
	Response -:

	True case 
	##output_code_start## 
        {
            "status": true,
            "message": "Payment initialized.",
            "data": {
                "pre_transaction_id": "order_IDsC16rAoxzfgu"
            },
            "time": 1635231229
        }
	##output_code_end## 

	False case 
	##output_code_start## 
	{"status":false,"message":"Already Added","data":[]}
	##output_code_end##
DIVIDER-SEGMENT

################################ COMPLETE EXTEND PAYMENT ########################
<h4>37) COMPLETE EXTEND PAYMENT</h4>

	Method -:      Post 
	Service url -: payment/f_payment

	Input parameter -:
	##input_param_start##
            type:4
            parent_id:0 //if purchasing parent course
            txn_id:1245155
            pre_transaction_id:837d83cc6516f370bf53bf970715c0fc
            transaction_status:1 //0=>initialized,1=>complete,2=>failed,3=>RI,4=>rc,5=>Declined,6=>Transferred
            post_transaction_id:d
	##input_param_end##
	Response -:

	True case 
	##output_code_start## 
        {"status":true,"message":null,"data":{},"time":1635514216}
	##output_code_end## 

	False case 
	##output_code_start## 
	{"status":false,"message":"Already Added","data":{}}
	##output_code_end##
DIVIDER-SEGMENT

################################ GET LIVE VIDEOS ########################
<h4>38) GET LIVE VIDEOS</h4>

	Method -:      Post 
	Service url -: course/get_live_videos

	Input parameter -:
	##input_param_start##
            page:1
            type:0 //0-live,1-upcoming,2-completed
	##input_param_end##
	Response -:

	True case 
	##output_code_start## 
        {
            "status": true,
            "message": "Live Videos Displayed",
            "time": 1633440723,
            "data": [
                {
                    "id": "1109367",
                    "file_type": "3",
                    "video_type": "2",
                    "file_url": "",
                    "is_download": "0",
                    "thumbnail_url": "https://utkarsh-efs.s3.ap-south-1.amazonaws.com/file_meta/video_def_2.png",
                    "title": "Micro processor new Video",
                    "description": "<p>Just added this new video...</p>\r\n",
                    "video_length": "0",
                    "chat_node": "",
                    "live_status": "0",
                    "open_in_app": "1",
                    "playtime": "0",
                    "is_live": "0",
                    "course_name": "",
                    "is_locked": "",
                    "end_date":""//valid to
                    "payload": {
                        "topic_id": "38198",
                        "tile_id": "36357",
                        "tile_type": "video",
                        "revert_api": "0#0#0#0",
                        "course_id": "1"
                    }
                }
            ]
        }
	##output_code_end## 

	False case 
	##output_code_start## 
	{"status":false,"message":"Not Found","data":[]}
	##output_code_end##
DIVIDER-SEGMENT

################################ GET LIVE TESTS ########################
<h4>39) GET LIVE TESTS</h4>

	Method -:      Post 
	Service url -: course/get_live_tests

	Input parameter -:
	##input_param_start##
            page:1
            type:0 //0-live,1-upcoming,2-completed
	##input_param_end##
	Response -:

	True case 
	##output_code_start## 
        {
            "status": true,
            "message": "Live Test Displayed",
            "time": 1633440723,
            "data": [
                {
                    "id": "1109330",
                    "result_date": "0",
                    "image": "",
                    "test_series_name": " Test2023",
                    "test_code": "8721yiuw",
                    "test_type": "0",
                    "set_type": "0",
                    "start_date": "1635750000",
                    "end_date": "1638244500",
                    "is_locked": "1",
                    "is_reattempt": "0",
                    "report_id": "315",
                    "submission_type": "0", //0-s3,1-api
                    "marks": "-9",
                    "state": "1",
                    "lang_used": "1",
                    "course_name": "",
                    "payload": {
                        "topic_id": "38198",
                        "tile_id": "36357",
                        "tile_type": "test",
                        "revert_api": "0#0#0#0",
                        "course_id": "1"
                    }
                }
            ]
        }
	##output_code_end## 

	False case 
	##output_code_start## 
	{"status":false,"message":"Not Found","data":[]}
	##output_code_end##
DIVIDER-SEGMENT

################################ GET TEST INSTRUCTIONS ########################
<h4>40) GET TEST INSTRUCTIONS</h4>

	Method -:      Post 
	Service url -: test/get_instructions

	Input parameter -:
	##input_param_start##
            test_id:1
            course_id:0
	##input_param_end##
	Response -:

	True case 
	##output_code_start## 
        {
            "status": true,
            "message": "Data Downloaded",
            "data": {
                "test_basic": {
                    "id": "1109318",
                    "lang_id": "2",
                    "test_series_name": "Test Paper-5 (राजस्थान का भूगोल एवं समसामयिकी)",
                    "image": "https://utkarsh-efs.s3.ap-south-1.amazonaws.com/file_meta/test.png",
                    "test_type": "1",
                    "total_questions": "10",
                    "time_in_mins": "10",
                    "total_marks": "10",
                    "is_reattempt": "1",
                    "time_boundation": "0",
                    "reward_points": "0",
                    "set_type": "0",
                    "is_calc_allowed": "0"
                },
                "test_sections": [
                    {
                        "id": "161743",
                        "section_id": "1",
                        "section_timing": "10",
                        "name": " Computer Science Engineering",
                        "marks_per_question": "1",
                        "negative_marks": "0",
                        "total_questions": "10"
                    }
                ]
            },
            "time": 1635059729
        }
	##output_code_end## 

	False case 
	##output_code_start## 
	{"status":false,"message":"Not Found","data":[]}
	##output_code_end##
DIVIDER-SEGMENT

################################ GET TEST DATA ########################
<h4>41) GET TEST DATA</h4>

	Method -:      Post 
	Service url -: test/get_test_data

	Input parameter -:
	##input_param_start##
            test_id:1
            course_id:0
	##input_param_end##
	Response -:

	True case 
	##output_code_start## 
        {
            "status": true,
            "message": "Data Downloaded",
            "data": {
                "user_details": {
                    "id": "5036587",
                    "name": "Mohit",
                    "profile_picture": "",
                    "email": "mohit1@gmail.com",
                    "c_code": "+91",
                    "mobile": "9716247619",
                    "erp_token": "",
                    "lang": "0"
                },
                "test_basic": {
                    "id": "1109318",
                    "lang_id": [
                        "1",
                        "2"
                    ],
                    "test_series_name": "Test Paper-5 (राजस्थान का भूगोल एवं समसामयिकी)",
                    "image": "https://utkarsh-efs.s3.ap-south-1.amazonaws.com/file_meta/test.png",
                    "test_type": "0",
                    "total_questions": "0",
                    "time_in_mins": "0",
                    "total_marks": "0",
                    "is_reattempt": "1",
                    "time_boundation": "0",
                    "reward_points": "0",
                    "set_type": "0",
                    "is_calc_allowed": "0"
                },
                "test_sections": [
                    {
                        "id": "161743",
                        "section_id": "1",
                        "section_timing": "10",
                        "name": " Computer Science Engineering",
                        "marks_per_question": "1",
                        "is_partial_marking": "0",
                        "no_of_questions": "10",
                        "negative_marks": "0",
                        "section_cutoff": "0"
                    }
                ],
                "questions": [
                    {
                        "id": "1160",
                        "section_id": "161743",
                        "config_id": "1634034323",
                        "question": "The Himalayas are an example of range mountains",
                        "question_type": "SC",
                        "option_1": "",
                        "option_2": "",
                        "option_3": "",
                        "option_4": "",
                        "option_5": "",
                        "paragraph_text": ""
                    }
                ],
                "questions_hindi": [],
                "resume_dump": {}
            },
            "time": 1635070205
        }
	##output_code_end## 

	False case 
	##output_code_start## 
	{"status":false,"message":"Not Found","data":[]}
	##output_code_end##
DIVIDER-SEGMENT

################################ SAVE TEST DATA ########################
<h4>42) SAVE TEST DATA</h4>

	Method -:      Post 
	Service url -: test/save_test

	Input parameter -:
	##input_param_start##
            test_id:1
            course_id:0
            question_dump:[]
            time_remain:0
            last_view:0
            lang_used:0
            state:0
	##input_param_end##
	Response -:

	True case 
	##output_code_start## 
        {
            "status": true,
            "message": "Result Updated",
            "data": {
                "date": 0,
                "result_page": 1,//1-instant,2-never,3-on timstamp
                "result_date": "",
                "first_attempt": 0
            },
            "time": 1635071864
        }
	##output_code_end## 

	False case 
	##output_code_start## 
	{"status":false,"message":"Not Found","data":[]}
	##output_code_end##
DIVIDER-SEGMENT

################################ GET TEST RESULT ########################
<h4>43) GET TEST RESULT</h4>

	Method -:      Post 
	Service url -: test/get_test_result

	Input parameter -:
	##input_param_start##
            test_id:1
            course_id:0
	##input_param_end##
	Response -:

	True case 
	##output_code_start## 
        {
            "status": true,
            "message": "Result Displayed",
            "data": {
                "id": "1109318",
                "image": "https://utkarsh-efs.s3.ap-south-1.amazonaws.com/file_meta/test.png",
                "test_series_name": "Test Paper-5 (राजस्थान का भूगोल एवं समसामयिकी)",
                "test_code": "0",
                "difficulty_level": "1",
                "test_type": "0",
                "backend_user_id": "0",
                "description": "<p>sdf</p>\r\n",
                "description_2": "<p>Test Paper - 5</p>\r\n",
                "total_questions": "0",
                "consider_time": "1",
                "time_in_mins": "0",
                "total_marks": "0",
                "subject_id": "503",
                "topic_id": "18324",
                "allow_user_move": "0",
                "time_boundation": "0",
                "auto_assigning": "1",
                "start_date": "1634991600",
                "end_date": "1635337200",
                "publish": "1",
                "reward_points": "0",
                "set_type": "0",
                "lang_id": "1",
                "test_assets": "{\"pdf\": \"\", \"epub\": \"\", \"video\": \"\"}",
                "result_date": "0",
                "is_reattempt": "1",
                "created": "1631968032",
                "marking_scheme": "2",
                "is_calc_allowed": "0",
                "total_user_attempt": 0,
                "user_rank": 1,
                "marks": 0,
                "best_score": 0,
                "avg_score": 0,
                "correct_count": 0,
                "incorrect_count": 0,
                "non_attempt": 0,
                "time_remain": 0,
                "question_dump": [],
                "cut_off": [],
                "percentile": "0",
                "top_ten_list": [],
                "test_sections": [
                    {
                        "id": "161743",
                        "section_id": "1",
                        "section_timing": "10",
                        "name": " Computer Science Engineering",
                        "marks_per_question": "1",
                        "is_partial_marking": "0",
                        "no_of_questions": "10",
                        "negative_marks": "0",
                        "section_cutoff": "0"
                    }
                ],
                "questions": []
            },
            "time": 1635072711
        }
	##output_code_end## 

	False case 
	##output_code_start## 
	{"status":false,"message":"Not Found","data":[]}
	##output_code_end##
DIVIDER-SEGMENT

################################ ADD REVISION SET ########################
<h4>44) ADD REVISION SET</h4>

	Method -:      Post 
	Service url -: revision/add_revision

	Input parameter -:
	##input_param_start##
            id:1 //optional if want update existing then provide id
            course_id:2290
            type:2 //1-YT Video,2-Web Link,3-flash card
            file_id:1109367
            tile_id:1
            link: //YT Video ID or WEB URL (in case of 3 need json array)
            title: //if type:3 else optional
	##input_param_end##
	Response -:

	True case 
	##output_code_start## 
        {
            "status": true,
            "message": "Revision Added.",
            "data": {
                "id": "1109374",
                "file_type": "11",
                "file_url": "https://stackoverflow.com/questions/10896233/how-can-i-retrieve-youtube-video-details-from-video-url-using-php",
                "is_download": "0",
                "thumbnail_url": "https://cdn.sstatic.net/Sites/stackoverflow/Img/favicon.ico?v=ec617d715196",
                "title": "How can I retrieve YouTube video details from video URL using PHP? - Stack Overflow",
                "description": "",
                "video_type": "0",
                "is_locked": "0",
                "is_live": "0",
                "is_chat_locked": "0",
                "video_length": "0",
                "chat_node": "",
                "live_status": "0",
                "open_in_app": "",
                "playtime": "0"
            },
            "time": 1635253076
        }
	##output_code_end## 

	False case 
	##output_code_start## 
	{"status":false,"message":"Not Found","data":{}}
	##output_code_end##
DIVIDER-SEGMENT

################################ DELETE REVISION SET ########################
<h4>45) DELETE REVISION SET</h4>

	Method -:      Post 
	Service url -: revision/delete_revision

	Input parameter -:
	##input_param_start##
            course_id:2290
            revision_id:2
	##input_param_end##
	Response -:

	True case 
	##output_code_start## 
        {"status":true,"message":"Revision Deleted.","data":[],"time":1635328767}
	##output_code_end## 

	False case 
	##output_code_start## 
	{"status":false,"message":"Not Found","data":{}}
	##output_code_end##
DIVIDER-SEGMENT

################################ GET NOTIFICATIONS ########################
<h4>46) GET NOTIFICATIONS</h4>

	Method -:      Post 
	Service url -: notification/get_notifications

	Input parameter -:
	##input_param_start##
            page:1
	##input_param_end##
	Response -:

	True case 
	##output_code_start## 
        {
            "status": true,
            "message": "User Notification.",
            "data": [
                {
                    "id": "1",
                    "message": "Hi",
                    "action_element": "0", //1-notification,2-course detail,3-user_profile,4-video,5-image,6-url
                    "action_element_id": "0",
                    "extra": {
                        "users_message": "Attend live session at 18-09-2021 6:45 PM",
                        "file_id": "1107925",
                        "topic_id": "11800",
                        "tile_id": "1583",
                        "notification_type": "video",
                        "course_id": "7926",
                        "notification_text": "कक्षा IX हिन्दी (COURSE A) \nLive Doubt Session \nPart- 11 || Live Doubt Session (Course A)\n ."
                    },
                    "view_state": "0",
                    "created": "1608119468"
                }
            ],
            "time": 1635318578
        }
	##output_code_end## 

	False case 
	##output_code_start## 
	{"status":false,"message":"Not Found","data":{}}
	##output_code_end##
DIVIDER-SEGMENT

################################ SET NOTIFICATION TO VIEWED ########################
<h4>47) SET NOTIFICATION TO VIEWED</h4>

	Method -:      Post 
	Service url -: notification/mark_as_read

	Input parameter -:
	##input_param_start##
            id:2290
	##input_param_end##
	Response -:

	True case 
	##output_code_start## 
        {"status":true,"message":"Marked As Viewed","data":[],"time":1635318686}
	##output_code_end## 

	False case 
	##output_code_start## 
	{"status":false,"message":"Not Found","data":{}}
	##output_code_end##
DIVIDER-SEGMENT

################################ SET ALL NOTIFICATION TO VIEWED ########################
<h4>48) SET ALL NOTIFICATION TO VIEWED</h4>

	Method -:      Post 
	Service url -: notification/set_all_read

	Input parameter -:
	##input_param_start##

	##input_param_end##
	Response -:

	True case 
	##output_code_start## 
        {"status":true,"message":"All notification marked as viewed.","data":[],"time":1635318796}
	##output_code_end## 

	False case 
	##output_code_start## 
	{"status":false,"message":"Not Found","data":{}}
	##output_code_end##
DIVIDER-SEGMENT

################################ TG RETRIEVE COURSES ########################
<h4>49) TG RETRIEVE COURSES</h4>

	Method -:      Post 
	Service url -: test/retrieve_courses

	Input parameter -:
	##input_param_start##

	##input_param_end##
	Response -:

	True case 
	##output_code_start## 
        {
            "status": true,
            "message": "Courses Displayed",
            "data": [
                {
                    "id": "503",
                    "title": "Courses Chapters",
                    "cover_image": "https://utkarsh-efs.s3.ap-south-1.amazonaws.com/letters/C.png"
                }
            ],
            "time": 1635319953
        }
	##output_code_end## 

	False case 
	##output_code_start## 
	{"status":false,"message":"Not Found","data":{}}
	##output_code_end##
DIVIDER-SEGMENT

################################ TG GET ALL SUBJECTS ########################
<h4>50) TG GET ALL SUBJECTS</h4>

	Method -:      Post 
	Service url -: test/gen_get_course_subjects

	Input parameter -:
	##input_param_start##
            course_ids:1,2
	##input_param_end##
	Response -:

	True case 
	##output_code_start## 
        {
            "status": true,
            "message": "Subjects Displayed",
            "data": [
                {
                    "id": "503",
                    "title": "Courses Chapters",
                    "image": "https://utkarsh-efs.s3.ap-south-1.amazonaws.com/letters/C.png",
                }
            ],
            "time": 1635319953
        }
	##output_code_end## 

	False case 
	##output_code_start## 
	{"status":false,"message":"Not Found","data":{}}
	##output_code_end##
DIVIDER-SEGMENT

################################ TG GET QUE COUNT ########################
<h4>51) TG GET QUE COUNT</h4>

	Method -:      Post 
	Service url -: test/gen_get_que_count

	Input parameter -:
	##input_param_start##
            subject_ids:1,2
	##input_param_end##
	Response -:

	True case 
	##output_code_start## 
        {
            "status": true,
            "message": "Question Count",
            "data": {
                "easy": 0,
                "medium": 0,
                "hard": 0
            },
            "time": 1635339511
        }
	##output_code_end## 

	False case 
	##output_code_start## 
	{"status":false,"message":"Not Found","data":{}}
	##output_code_end##
DIVIDER-SEGMENT

################################ TG GET QUESTIONS ########################
<h4>52) TG GET QUESTIONS</h4>

	Method -:      Post 
	Service url -: test/gen_get_questions

	Input parameter -:
	##input_param_start##
            subject_ids:1,2
            limit:50
            type:0 //1-easy,2-medium,3-hard
            que_count:0
	##input_param_end##
	Response -:

	True case 
	##output_code_start## 
        {
            "status": true,
            "message": "Questions Displayed",
            "data": [
                {
                    "id": "1",
                    "config_id": "1634033164",
                    "paragraph_text":"sdf"
                    "question": "Capital of Karnataka",
                    "question_type": "MC",
                    "option_1": "Bengaluru",
                    "option_2": "Mysuru",
                    "option_3": "Mangaluru",
                    "option_4": "Chennai",
                    "option_5": "",
                    "answer": "1"
                }
            ],
            "time": 1635326917
        }
	##output_code_end## 

	False case 
	##output_code_start## 
	{"status":false,"message":"Not Found","data":{}}
	##output_code_end##
DIVIDER-SEGMENT

################################ GET POLL ########################
<h4>53) GET POLL</h4>

	Method -:      Post 
	Service url -: poll/get_content_meta

	Input parameter -:
	##input_param_start##
            token:1109371
	##input_param_end##
	Response -:

	True case 
	##output_code_start## 
        {
            "status": true,
            "message": "Content Displayed",
            "data": {
                "pdf": [
                    {
                        "id": "3",
                        "video_id": "1109371",
                        "pdf_title": "dfg",
                        "is_downloadable": "0",
                        "pdf_url": "https://utkarsh-dev.s3.ap-south-1.amazonaws.com/admin_v1/file_manager/videos/pdf/1530599sample_3.pdf",
                        "pdf_thumbnail": "https://utkarsh-dev.s3.ap-south-1.amazonaws.com/admin_v1/file_manager/videos/pdf/2311632download_3.jpg",
                        "page_count": "22",
                        "creation_time": "1634889004"
                    }
                ],
                "poll": [
                    {
                        "id": "1",
                        "video_id": "1109371",
                        "question": "Question",
                        "option_1": "a",
                        "option_2": "b",
                        "option_3": "c",
                        "option_4": "d",
                        "answer": "1",
                        "valid_till": "1634889004",
                        "created_by": "1",
                        "attempt_1": "0",
                        "attempt_2": "0",
                        "attempt_3": "0",
                        "attempt_4": "0",
                        "created": "1634889004",
                        "status": "1",
                        "my_answer": "0"
                    }
                ],
                "index": [
                    {
                        "id": "1",
                        "user_id": "0",
                        "v_fk": "1109371",
                        "time": "08:02:00",
                        "info": "sdfsdfdsf"
                    }
                ],
                "bookmark": [
                    {
                        "id": "2",
                        "user_id": "5036587",
                        "v_fk": "1109371",
                        "time": "08:02:00",
                        "info": "sdfsdfdsf"
                    }
                ]
            },
            "time": 1635345326
        }
	##output_code_end## 

	False case 
	##output_code_start## 
	{"status":false,"message":"Not Found","data":{}}
	##output_code_end##
DIVIDER-SEGMENT

################################ SUBMIT POLL ########################
<h4>54) SUBMIT POLL</h4>

	Method -:      Post 
	Service url -: poll/submit_poll

	Input parameter -:
	##input_param_start##
            poll_id:1109371
            answer:1
	##input_param_end##
	Response -:

	True case 
	##output_code_start## 
        {"status":true,"message":"Poll Submitted","data":[],"time":1635345804}
	##output_code_end## 

	False case 
	##output_code_start## 
	{"status":false,"message":"Not Found","data":{}}
	##output_code_end##
DIVIDER-SEGMENT

################################ ADD VIDEO INDEX ########################
<h4>55) ADD VIDEO INDEX</h4>

	Method -:      Post 
	Service url -: poll/add_video_index

	Input parameter -:
	##input_param_start##
            video_id:1109371
            time:01:00:00
            info:test
	##input_param_end##
	Response -:

	True case 
	##output_code_start## 
        {
            "status": true,
            "message": "Poll Submitted",
            "data": {
                "id": "3",
                "user_id": "5036587",
                "v_fk": "1109371",
                "time": "01:00:00",
                "info": "test"
            },
            "time": 1635353469
        }
	##output_code_end## 

	False case 
	##output_code_start## 
	{"status":false,"message":"Not Found","data":{}}
	##output_code_end##
DIVIDER-SEGMENT

################################ DELETE VIDEO INDEX ########################
<h4>56) DELETE VIDEO INDEX</h4>

	Method -:      Post 
	Service url -: poll/delete_video_index

	Input parameter -:
	##input_param_start##
            index_id:1
	##input_param_end##
	Response -:

	True case 
	##output_code_start## 
        {"status":true,"message":"Deleted","data":[],"time":1635355567}
	##output_code_end## 

	False case 
	##output_code_start## 
	{"status":false,"message":"Not Found","data":[]}
	##output_code_end##
DIVIDER-SEGMENT

################################ GET APP FAQ ########################
<h4>57) GET APP FAQ</h4>

	Method -:      Post 
	Service url -: master_hit/get_app_faq

	Input parameter -:
	##input_param_start##

	##input_param_end##
	Response -:

	True case 
	##output_code_start## 
        {
            "status": true,
            "message": "FAQ Displayed",
            "data": [
                {
                    "id": "1",
                    "question": "Question",
                    "description": "Answer"
                }
            ],
            "time": 1635433182
        }
	##output_code_end## 

	False case 
	##output_code_start## 
	{"status":false,"message":"Not Found","data":[]}
	##output_code_end##
DIVIDER-SEGMENT

################################ HD GET MY QUERIES ########################
<h4>58) HD GET MY QUERIES</h4>

	Method -:      Post 
	Service url -: help_desk/get_my_queries

	Input parameter -:
	##input_param_start##
            page:1
	##input_param_end##
	Response -:

	True case 
	##output_code_start## 
        {
            "status": true,
            "message": "Queries Displayed",
            "data": [
                {
                    "id": "2",
                    "query_id": "UT-0000002",
                    "user_id": "5036587",
                    "course_id": "0",
                    "category": "Crashes",
                    "title": "App Crash",
                    "description": "App Got Crash",
                    "file": "",
                    "close_date": "0",
                    "closed_by": "0",
                    "time": "1635433956"
                }
            ],
            "time": 1635433831
        }
	##output_code_end## 

	False case 
	##output_code_start## 
	{"status":false,"message":"Not Found","data":[]}
	##output_code_end##
DIVIDER-SEGMENT

################################ HD SUBMIT QUERY ########################
<h4>59) HD SUBMIT QUERY</h4>

	Method -:      Post 
	Service url -: help_desk/get_my_queries

	Input parameter -:
	##input_param_start##
            category:Crashes
            title:App Crash
            description:App Got Crash
	##input_param_end##
	Response -:

	True case 
	##output_code_start## 
        {"status":true,"message":"Thank you.","data":[],"time":1635434375}
	##output_code_end## 

	False case 
	##output_code_start## 
	{"status":false,"message":"Not Found","data":[]}
	##output_code_end##
DIVIDER-SEGMENT

################################ HD GET QUERY REPLIES ########################
<h4>60) HD GET QUERY REPLIES</h4>

	Method -:      Post 
	Service url -: help_desk/get_query_replies

	Input parameter -:
	##input_param_start##
            query_id:2
	##input_param_end##
	Response -:

	True case 
	##output_code_start## 
        {
            "status": true,
            "message": "Query Replies Displayed",
            "data": [
                {
                    "id": "1",
                    "text": "f",
                    "type": "2",
                    "create_date": "1635434259"
                }
            ],
            "time": 1635434448
        }
	##output_code_end## 

	False case 
	##output_code_start## 
	{"status":false,"message":"Not Found","data":[]}
	##output_code_end##
DIVIDER-SEGMENT

################################ HD SUBMIT QUERY REPLIES ########################
<h4>61) HD SUBMIT QUERY REPLIES</h4>

	Method -:      Post 
	Service url -: help_desk/submit_query_reply

	Input parameter -:
	##input_param_start##
            query_id:2
            text:ok
	##input_param_end##
	Response -:

	True case 
	##output_code_start## 
        {"status":true,"message":"Reply Submitted","data":[],"time":1635434553}
	##output_code_end## 

	False case 
	##output_code_start## 
	{"status":false,"message":"Not Found","data":[]}
	##output_code_end##
DIVIDER-SEGMENT

################################ UPDATE PROFILE ########################
<h4>62) UPDATE PROFILE</h4>

	Method -:      Post 
	Service url -: users/update_profile

	Input parameter -:
	##input_param_start##
            name:M
            profile_picture:c,
            state: uttar pradesh
            city: noida
            aadhar_id:454656567865
            address:address 
	##input_param_end##
	Response -:

	True case 
	##output_code_start## 
        {"status":true,"message":"Profile updated","data":[],"time":1635515442}
	##output_code_end## 

	False case 
	##output_code_start## 
	{"status":false,"message":"Not Found","data":[]}
	##output_code_end##
DIVIDER-SEGMENT

################################ GET MY ORDERS ########################
<h4>63) GET MY ORDERS</h4>

	Method -:      Post 
	Service url -: course/get_my_orders

	Input parameter -:
	##input_param_start##
            page:1
            type:0
	##input_param_end##
	Response -:

	True case 
	##output_code_start## 
        {
            "status": true,
            "message": "Course Found.",
            "data": [
                {
                    "id": "7410",
                    "title": "RPSC Junior Accountant Online Course",
                    "cover_image": "https://utkarsh-efs.s3.ap-south-1.amazonaws.com/v1/books/7410/37.png",
                    "batch_id": "0",
                    "expiry_date": "1666512649",
                    "purchase_date": "1634976589",
                    "mrp": "0",
                    "txn_id": "1245155",
                    "invoice_no": "1245155",
                    "payment_ids": "1245155",
                    "transaction_status":1 //1-active,6-transferred
                }
            ],
            "time": 1635761501
        }
	##output_code_end## 

	False case 
	##output_code_start## 
	{"status":false,"message":"Not Found","data":[]}
	##output_code_end##
DIVIDER-SEGMENT
################################ SEARCH USER ########################
<h4>64) SEARCH USER</h4>

	Method -:      Post 
	Service url -: users/search_user

	Input parameter -:
	##input_param_start##
            mobile:9582163098
	##input_param_end##
	Response -:

	True case 
	##output_code_start## 
        {
            "status": true,
            "message": "Profile Found.",
            "data": [
                {
                    "id": "7410",
                    "name": "RPSC",
                    "email": "test@gmail.com",
                    "profile_picture": ""
                }
            ],
            "time": 1635761501
        }
	##output_code_end## 

	False case 
	##output_code_start## 
	{"status":false,"message":"Profile Not Found","data":[]}
	##output_code_end##
DIVIDER-SEGMENT

################################ TRANSFER COURSE ########################
<h4>65) TRANSFER COURSE</h4>

	Method -:      Post 
	Service url -: payment/transfer_course

	Input parameter -:
	##input_param_start##
            txn_ids:[2,1,5,7],
            transfer_to_id:1,
            transfer_to_mobile:xxxxxx
	##input_param_end##
	Response -:

	True case 
	##output_code_start## 
        {
            "status": true,
            "message": "Success.",
            "data": [],
            "time": 1635761501
        }
	##output_code_end## 

	False case 
	##output_code_start## 
	{"status":false,"message":"Failed","data":[]}
	##output_code_end##
DIVIDER-SEGMENT

################################ GET TEST LEARN ########################
<h4>66) GET TEST LEARN</h4>

	Method -:      Post 
	Service url -: test/get_test_learn

	Input parameter -:
	##input_param_start##
            test_id:1
            course_id:0
	##input_param_end##
	Response -:

	True case 
	##output_code_start## 
        {
            "status": true,
            "message": "Result Displayed",
            "data": {
                "id": "1109316",
                "image": "https://utkarsh-efs.s3.ap-south-1.amazonaws.com/file_meta/test.png",
                "test_series_name": "Weekly Test-39 (19-September)",
                "test_code": "0",
                "difficulty_level": "0",
                "test_type": "1",
                "backend_user_id": "0",
                "description": "",
                "description_2": "September-2021",
                "total_questions": "25",
                "consider_time": "0",
                "time_in_mins": "25",
                "total_marks": "25",
                "subject_id": "503",
                "topic_id": "17213",
                "allow_user_move": "0",
                "time_boundation": "0",
                "auto_assigning": "127",
                "start_date": "0",
                "end_date": "0",
                "publish": "1",
                "reward_points": "0",
                "set_type": "0",
                "lang_id": "2",
                "test_assets": "{\"pdf\": \"\", \"epub\": \"\", \"video\": \"\"}",
                "result_date": "0",
                "is_reattempt": "1",
                "created": "1631968021",
                "marking_scheme": "0",
                "is_calc_allowed": "0",
                "not_in_use": "0",
                "solution_url": "",
                "questions": []
            },
            "time": 1635072711
        }
	##output_code_end## 

	False case 
	##output_code_start## 
	{"status":false,"message":"Not Found","data":[]}
	##output_code_end##
DIVIDER-SEGMENT

################################ GET TEST LEADER BOARD ########################
<h4>67) GET TEST LEADER BOARD</h4>

	Method -:      Post 
	Service url -: test/get_test_leaderboard

	Input parameter -:
	##input_param_start##
            test_id:1
            course_id:0
	##input_param_end##
	Response -:

	True case 
	##output_code_start## 
        {
            "status": true,
            "message": "Result Displayed",
            "data": {
                "top_ten_list": [
                    {
                        "user_id": "5036587",
                        "marks": "-4",
                        "name": "Mohit",
                        "profile_picture": ""
                    }
                ]
            },
            "time": 1635072711
        }
	##output_code_end## 

	False case 
	##output_code_start## 
	{"status":false,"message":"Not Found","data":[]}
	##output_code_end##
DIVIDER-SEGMENT

################################ LOGOUT ########################
<h4>68) LOGOUT</h4>

	Method -:      Post 
	Service url -: user/logout

	Input parameter -:
	##input_param_start##
	##input_param_end##
	Response -:

	True case 
	##output_code_start## 
        {
            "status": true,
            "message": "Logged out",
            "data": [],
            "time": 1635072711
        }
	##output_code_end## 

	False case 
	##output_code_start## 
	{"status":false,"message":"Logged out","data":[]}
	##output_code_end##
DIVIDER-SEGMENT

################################ DELETE NOTIFICATION ########################
<h4>69) DELETE NOTIFICATION</h4>

	Method -:      Post 
	Service url -: notification/delete_notification

	Input parameter -:
	##input_param_start##
            id:1
	##input_param_end##
	Response -:

	True case 
	##output_code_start## 
        {
            "status": true,
            "message": "Deleted out",
            "data": [],
            "time": 1635072711
        }
	##output_code_end## 

	False case 
	##output_code_start## 
	{"status":false,"message":"Deleted out","data":[]}
	##output_code_end##
DIVIDER-SEGMENT

################################ ON REQUEST META SOURCE ########################
<h4>70) ON REQUEST META SOURCE</h4>

	Method -:      Post 
	Service url -: meta_distributer/on_request_meta_source

	Input parameter -:
	##input_param_start##
            name:1109803_5_0
            course_id:3441
            tile_id:36357
            type:video
	##input_param_end##
	Response -:

	True case 
	##output_code_start## 
        {
            "status": true,
            "message": "Link view.",
            "data": {
                "link": "https://d2qnoev5qjpur5.cloudfront.net/out/v1/61e52357b44e4441bd1f092264f74350/index.m3u8",
                "content_type": 2,
                "token": ""
            },
            "time": 1637933895,
            "cd_time": 1637933895,
            "interval": 0,
            "limit": 0
        }
	##output_code_end## 

	False case 
	##output_code_start## 
	{"status":false,"message":"Logged out","data":[]}
	##output_code_end##
DIVIDER-SEGMENT
################################ CREATE ANNOTATION ########################
<h4>71) CREATE ANNOTATION</h4>

	Method -:      Post 
	Service url -: meta_distributer/create_annotation

	Input parameter -:
	##input_param_start##
            file_id:11
            quote:sdfsdf
            text:dfsdfsd
            ranges:[],
            id: 0 //0 if add else provide id for update
	##input_param_end##
	Response -:

	True case 
	##output_code_start## 
        {
            "status": true,
            "message": "Done.",
            "data": {"id":0},
            "time": 1637933895,
            "cd_time": 1637933895,
            "interval": 0,
            "limit": 0
        }
	##output_code_end## 

	False case 
	##output_code_start## 
	{"status":false,"message":"Logged out","data":{}}
	##output_code_end##
DIVIDER-SEGMENT

################################ DELETE ANNOTATION ########################
<h4>72) DELETE ANNOTATION</h4>

	Method -:      Post 
	Service url -: meta_distributer/delete_annotation

	Input parameter -:
	##input_param_start##
            annotation_id:11
	##input_param_end##
	Response -:

	True case 
	##output_code_start## 
        {
            "status": true,
            "message": "Done.",
            "data": [],
            "time": 1637933895,
            "cd_time": 1637933895,
            "interval": 0,
            "limit": 0
        }
	##output_code_end## 

	False case 
	##output_code_start## 
	{"status":false,"message":"Failed","data":[]}
	##output_code_end##
DIVIDER-SEGMENT
################################ GET API VERSIONS  ########################
<h4>73) GET API VERSIONS</h4>

	Method -:      Post 
	Service url -: change_detector/get_api_versions

	Input parameter -:
	##input_param_start##
	##input_param_end##
	Response -:

	True case 
	##output_code_start## 
        {
            "status": true,
            "message": "Api's Found.",
            "data": {
                "master":{
                    "ut_010": "0.0001"
                },
                "uw_master":[
                    {
                        "meta_id":"",
                        "code":"",
                        "version":"",
                        "exp":"",
                    }
                ]
            },
            "time": 1638213672,
            "cd_time": 1638213672,
            "interval": 0,
            "limit": 0
        }
	##output_code_end## 

	False case 
	##output_code_start## 
	{"status":false,"message":"Failed","data":[]}
	##output_code_end##
DIVIDER-SEGMENT
################################ REMOVE COURSE FROM LIBRARY ########################
<h4>74) REMOVE COURSE FROM LIBRARY</h4>

	Method -:      Post 
	Service url -: payment/remove_course

	Input parameter -:
	##input_param_start##
            txn_id:"0"
            course_id:"0"
	##input_param_end##
	Response -:

	True case 
	##output_code_start## 
        {
            "status": true,
            "message": "Api's Found.",
            "data": [],
            "time": 1638213672,
            "cd_time": 1638213672,
            "interval": 0,
            "limit": 0
        }
	##output_code_end## 

	False case 
	##output_code_start## 
	{"status":false,"message":"Failed","data":[]}
	##output_code_end##
DIVIDER-SEGMENT

################################ GET FILE NAMES ########################
<h4>75) GET FILE NAMES</h4>

	Method -:      Post 
	Service url -: course/get_file_names

	Input parameter -:
	##input_param_start##
            file_ids: 1,2
	##input_param_end##
	Response -:

	True case 
	##output_code_start## 
        {
            "status": true,
            "message": "Files Displayed",
            "data": [
                {
                    "id": "8146",
                    "title": "Sections 4.11-4.20",
                    "file_url":"sdf"
                }
            ],
            "time": 1638346672,
            "cd_time": 0,
            "interval": 0,
            "limit": 0
        }
	##output_code_end## 

	False case 
	##output_code_start## 
	{"status":false,"message":"Failed","data":[]}
	##output_code_end##
DIVIDER-SEGMENT

################################ GET PAY GATEWAY CREDENTIALS ########################
<h4>76) GET PAY GATEWAY CREDENTIALS</h4>

	Method -:      Post 
	Service url -: master_hit/get_pay_gateway

	Input parameter -:
	##input_param_start##

	##input_param_end##
	Response -:

	True case 
	##output_code_start## 
        {
            "status": true,
            "message": "Details Displayed",
            "data": {
                "rzp": {
                    "mode": "0",
                    "key": "",
                    "secret": ""
                }
            },
            "time": 1638621917,
            "cd_time": 123456,
            "interval": 0,
            "limit": 0
        }
	##output_code_end## 

	False case 
	##output_code_start## 
	{"status":false,"message":"Failed","data":[]}
	##output_code_end##
DIVIDER-SEGMENT
################################ GET COUPONS ########################
<h4>77) GET COUPONS</h4>

	Method -:      Post 
	Service url -: coupon/get_coupon

	Input parameter -:
	##input_param_start##

	##input_param_end##
	Response -:

	True case 
	##output_code_start## 
        {
            "status": true,
            "message": "Coupon displayed.",
            "data": {
                "available": [
                    {
                        "id": "1",
                        "image": "",
                        "coupon_tilte": "TESTING",
                        "coupon_type": "2",
                        "coupon_for": "1",
                        "max_discount": "10",
                        "max_usage": "10",
                        "coupon_value": "1",
                        "end": "1642769400",
                        "redeem_json": [],
                        "courses": [
                            {
                                "id": "3",
                                "title": "Olympiad Books Practice Sets - English Class 7th",
                                "cover_image": "https://utkarsh-efs.s3.ap-south-1.amazonaws.com/v1/books/3/41RlNqevsuL.jpg.jpg",
                                "course_sp": "80",
                                "mrp": "57.4",
                                "tax": "12.6",
                                "discount": "0.7",
                                "final_mrp": "69.3"
                            }
                        ]
                    }
                ],
                "redeemed": [],
                "expired": []
            },
            "time": 1642675452,
            "interval": 10,
            "limit": 0,
            "cd_time": 16410164367899
        }
	##output_code_end## 

	False case 
	##output_code_start## 
	{"status":false,"message":"Failed","data":[]}
	##output_code_end##
DIVIDER-SEGMENT

################################ CHECK ASSIGNED COUPONS ########################
<h4>78) CHECK ASSIGNED COUPONS</h4>

	Method -:      Post 
	Service url -: coupon/is_coupon_available

	Input parameter -:
	##input_param_start##

	##input_param_end##
	Response -:

	True case 
	##output_code_start## 
        {
            "status": true,
            "message": "Coupon available.",
            "data": {
                "total_assigned_coupon": "7"
            },
            "time": 1642830561,
            "interval": 10,
            "limit": 0,
            "cd_time": "16428277475655"
        }
	##output_code_end## 

	False case 
	##output_code_start## 
	{"status":false,"message":"Failed","data":[]}
	##output_code_end##
DIVIDER-SEGMENT

################################ GET COUPON OVER COURSE ########################
<h4>79) GET COUPON OVER COURSE</h4>

	Method -:      Post 
	Service url -: coupon/get_coupon_over_course

	Input parameter -:
	##input_param_start##
            course_id:1
	##input_param_end##
	Response -:

	True case 
	##output_code_start## 
        {
            "status": true,
            "message": "Coupon displayed.",
            "data": {
                "id": "3",
                "title": "appsquadz testing-001",
                "cover_image": "https://d1tkmrqto1uygb.cloudfront.net/admin_v1/bundle_management/course/75327963_utkarsh.jpg",
                "course_sp": "36000",
                "mrp": "41",
                "validity": "365 Days",
                "discount": "50",
                "tax": "9",
                "final_mrp": "50",
                "coupon": {
                    "id": "64",
                    "image": "",
                    "coupon_tilte": "Demo Coupon",
                    "coupon_type": "2",
                    "coupon_for": "2",
                    "max_discount": "100",
                    "max_usage": "5",
                    "coupon_value": "50",
                    "end": "1643451900",
                    "redeem_json": []
                }
            },
            "time": 1642853014,
            "interval": 10,
            "limit": 0,
            "cd_time": 16428462218429
        }
	##output_code_end## 

	False case 
	##output_code_start## 
	{"status":false,"message":"Failed","data":[]}
	##output_code_end##
DIVIDER-SEGMENT
################################ GET SUGGESTED COURSE ########################
<h4>80) GET SUGGESTED COURSE</h4>

	Method -:      Post 
	Service url -: course/get_suggested_course

	Input parameter -:
	##input_param_start##
            course_id:1
	##input_param_end##
	Response -:

	True case 
	##output_code_start## 
        {
            "status": true,
            "message": "Course Found.",
            "data": [
              {
                "id": "90",
                "title": "NDA MOCK TEST\t",
                "cover_image": "https://d1d6x43owzjgqt.cloudfront.net/44/admin_v1/bundle_management/course/589561790_7575385CGL_II.jpg",
                "mrp": "0",
                "course_sp": "0",
                "validity": "90 Days"
              },
              {
                "id": "91",
                "title": "Current Affair 2022",
                "cover_image": "https://d1d6x43owzjgqt.cloudfront.net/44/admin_v1/bundle_management/course/650910291_7575385CGL_II.jpg",
                "mrp": "11",
                "course_sp": "1",
                "validity": "10/03/2022:23/06/2022"
              },
              {
                "id": "92",
                "title": "Batch 12:30PM - 03:00PM\t",
                "cover_image": "https://d1d6x43owzjgqt.cloudfront.net/44/admin_v1/bundle_management/course/603450092_7575385CGL_II.jpg",
                "mrp": "13",
                "course_sp": "1",
                "validity": "60 Days"
              },
              {
                "id": "96",
                "title": "Daily Quiz",
                "cover_image": "https://d1d6x43owzjgqt.cloudfront.net/44/admin_v1/bundle_management/course/71057296_7575385CGL_II.jpg",
                "mrp": "0",
                "course_sp": "0",
                "validity": "5 Days"
              }
            ],
            "time": 1648473893,
            "interval": 10,
            "limit": 0,
            "cd_time": "16484634144797"
        }
	##output_code_end## 

	False case 
	##output_code_start## 
	{"status":false,"message":"Failed","data":[]}
	##output_code_end##
DIVIDER-SEGMENT
################################ ADD WISHLIST ITEM ########################
<h4>81) ADD WISHLIST ITEM</h4>

	Method -:      Post 
	Service url -: course/add_wishlist_item

	Input parameter -:
	##input_param_start##
            course_id:1
	##input_param_end##
	Response -:

	True case 
	##output_code_start## 
        {
            "status": true,
            "message": "Course has been added to wishlist.",
            "data": [],
            "time": 1648551340,
            "interval": 10,
            "limit": 0,
            "cd_time": "16484634144797"
        }
	##output_code_end## 

	False case 
	##output_code_start## 
	{"status":false,"message":"Failed","data":[]}
	##output_code_end##
DIVIDER-SEGMENT
################################ GET WISHLIST ITEMS ########################
<h4>82) GET WISHLIST ITEMS</h4>

	Method -:      Post 
	Service url -: course/get_wishlist_items

	Input parameter -:
	##input_param_start##
            
	##input_param_end##
	Response -:

	True case 
	##output_code_start## 
        {
            "status": true,
            "message": "Course Wishlist",
            "data": [
                {
                    "id": "1",
                    "course_name": "course1",
                    "mrp": "100",
                    "course_sp": "50",
                    "validity_type": "0",
                    "start_date": "0",
                    "end_date": "0",
                    "validity": "0",
                    "created_on": "1648549644",
                    "is_paid": "1",
                    "is_purchased": 0
                },
                {
                    "id": "3",
                    "course_name": "NDA MOCK TEST\t",
                    "mrp": "0",
                    "course_sp": "0",
                    "validity_type": "0",
                    "start_date": "0",
                    "end_date": "0",
                    "validity": "90",
                    "created_on": "1648551340",
                    "is_paid": "0",
                    "is_purchased": 0
                }
            ],
            "time": 1648551635,
            "interval": 10,
            "limit": 0,
            "cd_time": "16484634144797"
        }
	##output_code_end## 

	False case 
	##output_code_start## 
	{"status":false,"message":"Failed","data":[]}
	##output_code_end##
DIVIDER-SEGMENT
################################ REMOVE WISHLIST ITEM ########################
<h4>83) REMOVE WISHLIST ITEM</h4>

	Method -:      Post 
	Service url -: course/remove_wishlist_item

	Input parameter -:
	##input_param_start##
            course_id:1
	##input_param_end##
	Response -:

	True case 
	##output_code_start## 
        {
            "status": true,
            "message": "Item has been removed from wishlist.",
            "data": [],
            "time": 1648552873,
            "interval": 10,
            "limit": 0,
            "cd_time": "16484634144797"
        }
	##output_code_end## 

	False case 
	##output_code_start## 
	{"status":false,"message":"Failed","data":[]}
	##output_code_end##
DIVIDER-SEGMENT
################################ COURSE ACTIVATION ########################
<h4>84) COURSE ACTIVATION</h4>

	Method -:      Post 
	Service url -: course/activate_course

	Input parameter -:
	##input_param_start##
            course_id:1
            activation_key:sadfgnjfdbxvzcxv 
	##input_param_end##
	Response -:

	True case 
	##output_code_start## 
        {
            "status": true,
            "message": "Your course has been activated successfully.",
            "data": [],
            "time": 1648709027,
            "interval": 10,
            "limit": 0,
            "cd_time": 0
        }
	##output_code_end## 

	False case 
	##output_code_start## 
	{"status":false,"message":"Failed","data":[]}
	##output_code_end##
DIVIDER-SEGMENT
################################ USER ACTIVATION ########################
<h4>85) USER ACTIVATION</h4>

	Method -:      Post 
	Service url -: users/activate_user

	Input parameter -:
	##input_param_start##
            activation_key:sadfgnjfdbxvzcxv 
	##input_param_end##
	Response -:

	True case 
	##output_code_start## 
        {
            "status": true,
            "message": "User has been verified successfully.",
            "data": [],
            "time": 1648709027,
            "interval": 10,
            "limit": 0,
            "cd_time": 0
        }
	##output_code_end## 

	False case 
	##output_code_start## 
	{"status":false,"message":"Failed","data":[]}
	##output_code_end##
DIVIDER-SEGMENT
    </pre>
</div>
<script type="text/javascript" language="javascript" >
    $('#container').addClass('sidebar-closed');
    function searchapi(str) {
        $('.api-cat li').hide();
        var txt = str;
        $('.api-cat li').each(function () {
            if ($(this).text().toUpperCase().indexOf(str.toUpperCase()) != -1) {
                $(this).show();
            }
        });
    }
    
    $('#main-content').css('margin-left', "0px");
    $(document).ready(function () {
        var text = $('.api_file_meta').html();
        text = text.replace(/Method -:/g, '<span class="btn btn-info btn-xs bold">METHOD -: </span>');

        text = text.replace(/Service url -:/g, '<span class="btn btn-success btn-xs bold">SERVICE URL -: </span>');
        text = text.replace(/Input parameter -:/g, '<span class="bold">INPUT PARAMETER -:</span>');
        text = text.replace(/True case/g, '<span class="btn btn-success btn-xs bold">True case</span>');
        text = text.replace(/False case/g, '<span class="btn btn-danger btn-xs bold">False case </span>');
        text = text.replace(/DIVIDER-SEGMENT/g, '<span style=" border: 1px solid;" class="col-md-12"></span>');
        text = text.replace(/Response -:/g, '<span class="bold">RESPONSE -: </span>');
        text = text.replace(/##output_code_start##/g, '<figure class="highlight" style="background: #2e2f33 none repeat scroll 0 0;color: white;"> ');
        text = text.replace(/##output_code_end##/g, '</figure>');
        text = text.replace(/##input_param_start##/g, '<figure class="highlight" style="background:#929499;color: white;"> ');
        text = text.replace(/##input_param_end##/g, '</figure>');
        $('.api_file_meta').html(text);
        $('.api_file_meta h4').css('padding-top', '65px');

        $(".api_file_meta h4").each(function (index) {
            $(this).attr('id', 'api' + index);
            $(".api-cat").append('<li><a href="#api' + index + '"><i class=" fa fa-angle-right"></i> ' + $(this).html() + '</a></li>')
        });
    });

</script> 