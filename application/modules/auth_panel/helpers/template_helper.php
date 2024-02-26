<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if (!function_exists('page_alert_box')) {

    function page_alert_box($type = '', $title = '', $message = '') {
        $_SESSION['page_alert_box_type'] = $type;
        $_SESSION['page_alert_box_title'] = $title;
        $_SESSION['page_alert_box_message'] = $message;
    }

}

if (!function_exists('redirect_to_back')) {

    function redirect_to_back() {
        echo '<script>window.history.go(-1);</script>';
        die;
    }


}

if (!function_exists("get_landing_pages")) {
    function get_landing_pages($obj)
    {
        if ($obj) {
            $obj->select("id,landing_page,landing_page_title");
            $obj->where("status", 0);
            $return = $obj->get("landing_pages")->result_array();
            return $return ? $return : false;
        }
        return false;
    }
}

if (!function_exists("menu_side")) {

    //0-Home,1-Main,2-Top,3-Bottom,4-Side,5-Side Bottom,6-profile,7=> Top Header
    function menu_side($key = "")
    {
        $menu_location = array("0" => "Home", "1" => "Main Menu", "2" => "Top Menu", "3" => "Bottom Menu", "4" => "Side Menu", "5" => "Side Bottom Menu", "6" => "Profile Menu", "7" => "Top Header Menu");
        return ($key != "" && $key < 8) ? $menu_location[$key] : $menu_location;
    }

}

if (!function_exists('backend_log_genration')) {

    function backend_log_genration($CI, $comment = "", $segment = "", $data = array()) {
        if (is_array($data)) {
            $data['user_device'] = getallheaders()["User-Agent"];
            $data['remote_ip'] = !empty($_SERVER["HTTP_X_FORWARDED_FOR"]) ? $_SERVER["HTTP_X_FORWARDED_FOR"] : $_SERVER['REMOTE_ADDR'];
        } else {
            $data = array(
                "user_device" => getallheaders()["User-Agent"],
                "remote_ip" => !empty($_SERVER["HTTP_X_FORWARDED_FOR"]) ? $_SERVER["HTTP_X_FORWARDED_FOR"] : $_SERVER['REMOTE_ADDR']
            );
        }

        $array = array(
            'user_id' => $CI->session->userdata("active_backend_user_id") ?? 0,
            'comment' => $comment,
            'segment' => $segment,
            'creation_time' => time(),
            'json' => $data ? json_encode($data) : json_encode($_POST)
        );
        $CI->db->insert('backend_user_activity_log', $array);
    }

}

if (!function_exists('convert_normal_to_m3u8')) {

    function convert_normal_to_m3u8($url, $video_id) {
        $url = str_replace(".mp4", ".m3u8", $url);
        $url = str_replace("original/", "vod/$video_id/", $url);
        return $url;
    }

}

if (!function_exists('convert_normal_to_dash')) {

    function convert_normal_to_dash($url, $video_id) {
        $url = str_replace(".mp4", ".mpd", $url);
        $url = str_replace(".m3u8", ".mpd", $url);
        $url = str_replace("original", "vod_drm", $url);
        $url = str_replace("vod/", "vod_drm/", $url);
        $url = str_replace("harvesting/", "vod_drm/", $url);
        return $url;
    }

}

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

if (!function_exists('amazon_s3_upload')) {

    function amazon_s3_upload($name, $aws_path, $id) {

        $CI = & get_instance();
        $_FILES['file'] = $name;

        require_once APPPATH . 'third_party/aws/aws-autoloader.php';
        $s3Client = new S3Client([
            'version' => 'latest',
            'region' => AMS_REGION,
            'credentials' => [
                'key' => AMS_S3_KEY,
                'secret' => AMS_SECRET,
            ],
        ]);
        $file_name = explode(".", $_FILES["file"]["name"]);
        $file_name[0] = $id . "_" . $file_name[0];
        $file_name = implode(".", $file_name);

        $data = array(
            'Bucket' => AMS_BUCKET_NAME,
            'Key' => APP_ID . '/' . ADMIN_VERSION . '/' . $aws_path . '/' . rand(0, 7896756) . $file_name,
            'SourceFile' => $_FILES["file"]["tmp_name"],
            'ContentType' => $_FILES["file"]["type"],
            'ACL' => 'bucket-owner-full-control',
            'StorageClass' => 'REDUCED_REDUNDANCY',
            'Metadata' => array(
                'id' => $id,
                'route' => $GLOBALS['perm_url'],
                'admin_id' => $GLOBALS['admin_id']
            )
        );

        $result = $s3Client->putObject($data);
        $result = $result->toArray();
        return $result['ObjectURL'];
    }

}

if (!function_exists('amazon_s3_upload_via_link')) {

    function amazon_s3_upload_via_link($url, $aws_path, $id) {

        $CI = & get_instance();
        $s3bucket_detail = json_decode(get_db_meta_key($CI->db, "s3bucket_detail"), true);

        require_once APPPATH . 'third_party/aws/aws-autoloader.php';
        $s3Client = new S3Client([
            'version' => 'latest',
            'region' => empty($s3bucket_detail) ? AMS_REGION : $s3bucket_detail['region'],
            'credentials' => [
                'key' => empty($s3bucket_detail) ? AMS_S3_KEY : $s3bucket_detail['access_key'],
                'secret' => empty($s3bucket_detail) ? AMS_SECRET : $s3bucket_detail['secret_key'],
            ],
        ]);

        $fileURL = $url;
           
        if (!file_exists('tmp')) {
            mkdir('tmp');
        }
                
        $tempFilePath = 'tmp/' . basename($fileURL);
        $tempFile = fopen($tempFilePath, "w") or die("Error: Unable to open file.");
        $fileContents = file_get_contents($fileURL);
        $tempFile = file_put_contents($tempFilePath, $fileContents);

        $data = 
            array(
                'Bucket'=> empty($s3bucket_detail) ? AMS_BUCKET_NAME : $s3bucket_detail['bucket_key'],
                'Key' =>  (defined("APP_ID") ? APP_ID : 0) . '/' . ADMIN_VERSION . '/' . $aws_path . '/' . rand(0, 7896756) . basename($fileURL),
                'SourceFile' => $tempFilePath,
                'ACL' => 'bucket-owner-full-control',
                'StorageClass' => 'REDUCED_REDUNDANCY',
                'Metadata' => array(
                    'id' => $id,
                    'route' => $GLOBALS['perm_url'],
                    'admin_id' => $GLOBALS['admin_id']
                )
            );

        $result = $s3Client->putObject($data);
        $result = $result->toArray();
        return $result['ObjectURL'];

    }
}

if (!function_exists("get_language")) {

    function get_language($type = "") {
        $CI = & get_instance();
        if ($type)
            $CI->db->where("id <", 10);
        $languages = $CI->db->get("language_code")->result_array();
        return $languages;
    }

}

if (!function_exists("get_language_name")) {

    function get_language_name($CI, $id) {
        $CI->db->where("id", $id);
        $languages = $CI->db->get("language_code")->row()->language;
        return $languages;
    }

}

if (!function_exists('generate_password')) {

    function generate_password($password) {
        $options = array(
            'cost' => 10
        );
        return password_hash($password, PASSWORD_BCRYPT, $options);
    }

}

if (!function_exists('get_transaction_status')) {

    function get_transaction_status($status) {
        $txn_status = array(
            0 => "<span style='cursor:default' class='badge badge-dark'>Pending</span>",
            1 => "<span style='cursor:default' class='badge badge-success'>Complete</span>",
            2 => "<span style='cursor:default' class='badge badge-danger'>Cancel</span>",
            3 => "<span style='cursor:default' class='badge badge-info'>Refund Req.</span>",
            4 => "<span style='cursor:default' class='badge badge-warning'>Refunded</span>",
            5 => "<span style='cursor:default' class='badge badge-light'>Declined</span>",
            6 => "<span style='cursor:default' class='badge badge-primary'>Transfered</span>",
            7 => "<span style='cursor:default' class='badge badge-danger'>Deleted</span>",
            '-1' => "<span style='cursor:default' class='badge badge-warning'>Processing</span>"
        );
        return isset($txn_status[$status]) ? $txn_status[$status] : "<span style='cursor:default' class='badge badge-dark'>Unknown</span>";
    }

}

if (!function_exists('get_time_format')) {

    function get_time_format($timestamp, $format = 0) {
        $format_arr = array(
            0 => "d/M/Y H:i ",
            1 => "d/m/Y H:i ",
        );
        return date($format_arr[$format], $timestamp);
    }

}
if (!function_exists('get_date_format')) {

function get_date_format($timestamp) {
    // Convert timestamp to date format
    $date = date("Y-m-d H:i:s", $timestamp);
    return $date;
}
}

if (!function_exists('update_api_version')) {

    function update_api_version($db, $api_no, $meta_id = 0, $user_id = 0) {
        $global_versioning = array(9, 10, 12);
        $code = "ut_" . str_pad($api_no, 3, "0", STR_PAD_LEFT);
        $redis_magic_data = new Redis_magic("data");
        if ($api_no == 9) {
            $redis_magic_data->DEL('master_hit_content');
        } else if ($api_no == 11) {
//            $redis_magic_data->HMSET("get_course_overview", $meta_id, array("data" => ''));
            $redis_magic_data->HMSET("complete_cd0", $meta_id, array("data" => ''));
            $redis_magic_data->HMSET("complete_cd1", $meta_id, array("data" => ''));
        } else if ($api_no == 12) {
            $redis_magic_data->DEL("my_courses:" . $user_id);
        }

        if (in_array($api_no, $global_versioning)) {
            if ($user_id)
                $db->where("user_id", $user_id);
            // $db->where("app_id", APP_ID);
            $db->set($code, $code . "+0.001", false);
            $db->update("api_cache_version");
        } else {
            $db->where("code", $code);
            if ($meta_id)
                $db->where("meta_id", $meta_id);
            // $db->where("app_id", APP_ID);
            $db->set("version", "version+0.001", false);
            $db->update("api_uw_cache_version");
        }

        $redis_magic_data->SET("API_CD", round(microtime(true) * 10000)); //change detector
    }

}

if (!function_exists('update_api_version_new')) {

    function update_api_version_new($db, $api_no, $meta_id = 0, $user_id = 0) {
        $global_versioning = array('menu_master', 'dashboard','detail_page', 'banner','episodes_w','episodes_tv');
        $code = "ut_" . str_pad($api_no, 3, "0", STR_PAD_LEFT);
        $redis_magic_data = new Redis_magic("data");
        if ($api_no == 9) {
            $redis_magic_data->DEL('master_hit_content');
        } else if ($api_no == 11) {
//            $redis_magic_data->HMSET("get_course_overview", $meta_id, array("data" => ''));
            $redis_magic_data->HMSET("complete_cd0", $meta_id, array("data" => ''));
            $redis_magic_data->HMSET("complete_cd1", $meta_id, array("data" => ''));
        } else if ($api_no == 12) {
            $redis_magic_data->DEL("my_courses:" . $user_id);
        }

        if (in_array($api_no, $global_versioning)) {
            if ($user_id)
                $db->where("user_id", $user_id);
            // $db->where("app_id", APP_ID);
            $db->set($code, $code . "+0.001", false);
            $db->update("api_cache_version");
        } else {
            $db->where("code", $code);
            if ($meta_id)
                $db->where("meta_id", $meta_id);
            // $db->where("app_id", APP_ID);
            $db->set("version", "version+0.001", false);
            $db->update("api_uw_cache_version");
        }

        $redis_magic_data->SET("API_CD", round(microtime(true) * 10000)); //change detector
    }

}

if (!function_exists('device_type')) {

    function device_type($type = "") {
        $device_type = array("All", "Android", "iOS", "Website","Tv");
        if ($type == "0") {
            $device_type[0] = "--NA--";
        }
        return ($type != "") ? $device_type[$type] : $device_type;
    }

}


if (!function_exists("s3_to_cf")) {

    function s3_to_cf($str) {
        $str = str_replace(AMS_BUCKET_NAME . ".s3." . AMS_REGION . ".amazonaws.com", S3_CLOUDFRONT_DOMAIN, $str);
        if (strpos($str, "ut-production-efs")) {
            $str = str_replace("ut-production-efs.s3." . AMS_REGION . ".amazonaws.com", "s3-efs.utkarshapp.com", $str);
        }
        return $str;
    }

}

if (!function_exists('app_permission')) {

    function app_permission($column, $obj = NULL) {
        // if (defined("APP_ID") && APP_ID) {
        //     if ($obj)
        //         $obj->where("$column", APP_ID);
        //     else
        //         return " AND $column=" . APP_ID;
        // } else
            return " ANd 1 = 1 ";
    }

}

if (!function_exists('breadcrum')) {

    function breadcrumbs($data = array()) {
        $CI = & get_instance();
        $CI->load->library('breadcrumbs');
        $CI->breadcrumbs->add('Dashboard', base_url());
        foreach ($data as $key => $value) {
            $CI->breadcrumbs->add(ucwords($key), AUTH_PANEL_URL . $value);
        }
        return $CI->breadcrumbs->output();
    }

}

if (!function_exists('return_data')) {

    function return_data($status = false, $mesage = "", $result = array(), $auth_code = "") { //mobile and message.
        require_once APPPATH . 'libraries/Redis_magic.php';
        $return = array(
            "status" => $status,
            "message" => $mesage,
            "data" => $result,
        );

        $return["time"] = time();
        $return["interval"] = $GLOBALS['interval'] ?? '';
        $return['limit'] = $GLOBALS['limit'] ?? '';

        if ($auth_code)
            $return['auth_code'] = $auth_code;

        $whitelist_watcher = array(
            5, //Mohit
            2, //panda
            30, //Piyush
            17, //Sunil
            47, //Ankur
            24, //
        );

        $redis_magic = new Redis_magic("data");
        // $return["cd_time"] = $redis_magic->GET("API_CD"); //change detector
        // $return["cd_time"] = $return["cd_time"] ? (int) $return["cd_time"] : 0;
        // $u_cd_time = $redis_magic->GET("API_CD_" . USER_ID); //UW change detector
        // if ($return["cd_time"] < $u_cd_time)
        //     $return["cd_time"] = $u_cd_time;

        $return = json_encode($return);
        $perm = '';
        if (strpos($perm, "test/") == 0) {
            $return = str_replace("\/funlearn\/downloadEpubImage?source=upload\/", "https://" . (PROJECT_MODE == "prod" ? "ut-production-efs" : "utkarsh-efs") . ".s3.ap-south-1.amazonaws.com/" . (PROJECT_MODE == "prod" ? "efs_" : "") . "v1/", $return);
//            $return = str_replace("<img", "<img style='width:100%;'", $return);
        }
        $return = str_replace(AMS_BUCKET_NAME . ".s3." . AMS_REGION . ".amazonaws.com", S3_CLOUDFRONT_DOMAIN, $return);
        if (PROJECT_MODE == "prod")
            $return = str_replace("ut-production-efs.s3." . AMS_REGION . ".amazonaws.com", "s3-efs.utkarshapp.com", $return);

        if ((defined("USER_ID") && in_array(USER_ID, $whitelist_watcher)))
            publish_api_data($return, $redis_magic);

        if (defined("ENCRYPTION_FLAG")) {
            $return = aes_cbc_encryption($return, ENCRYPTION_FLAG);
        }
        echo $return;
        die;
    }

}

if(!function_exists("functionality_list")){
    function functionality_list($db){
        //return array("Course Combo","Youtube","Youtube Live","Manual Transaction password","Limited","Course Activation","feedback video","Demo Percentage","Token Management","Coupon Enabled", "Zoom Live","vod chat","vod token", "Total Paid Amount","Course type content","Course Home Screen","Topper video","Physical Book","Physical Content","Course View Type Offline","Course View Type Current Affair","Course View Type Test Series","Main Category Image Option","Payment Installment","Poll Option Hidden","Daily Assignment","New Course","Assign Subjective Teacher","Rank Enable","Manual Question Style","Manual Difficulty level","Manual Status","Manual Valid Till","Manual Question Type","Manual Marking","Course Syllabus Pdf","Gst Rate","Chat Attachment PDF","Chat Attachment Image","Chat Attachment URL","Chat Attachment Audio");
    	//$fun_list = $db->get('fun_list')->result_array();
        return array("Movie","Web Series","Tv Serieals","Video","Videocrypt VOD","Videocrypt Live","Videocrypt Fast Live","Live","Youtube","Youtube Live","paid","Category Type","Footer Android url","Footer iOS url");
        return $fun_list;
    }
}


if(!function_exists("get_app_functionality")){
    function get_app_functionality($db){
        $db->select("functionality");
        // $db->where("app_id",APP_ID);
        $app_meta   =   $db->get("application_meta")->row();
        return  $app_meta?json_decode($app_meta->functionality,true):false;
    }
}