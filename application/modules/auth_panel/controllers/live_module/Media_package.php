<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH . '/third_party/aws/aws-autoloader.php');

use Aws\MediaPackage;

class Media_package extends MX_Controller {

    function __construct() {
        parent::__construct();
        /* !!!!!! Warning !!!!!!!11
         *  admin panel initialization
         *  do not over-right or remove auth_panel/auth_panel_ini/auth_ini
         */
        $this->load->helper('aul');
        if ($this->router->fetch_method() != "auto_harvest_job_to_vod")
            modules::run('auth_panel/auth_panel_ini/auth_ini');
        $this->load->model(['live_module/Credentials_model', 'live_module/Media_package_model']);
    }

    function index() {        
        $input = $this->input->post();
        if ($input) {
            $this->Media_package_model->index($input);
        }
        $view_data['channels'] = $this->Media_package_model->get_media_package_channels();
        //echo "<pre>";print_r($view_data);die;
        $view_data['breadcrum']=array('Media Package'=>"#");
        $data['page_data'] = $this->load->view('live_module/media_package_channel', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    function delete_media_package_channel($id, $channel_id) {
        $this->Media_package_model->delete_media_package_channel($id, $channel_id);
    }

    function add_endpoint_to_channel() {
        $input = $this->input->post();
        if ($input) {
            $this->Media_package_model->add_endpoint_to_channel($input);
        }
        $view_data['endpoints'] = $this->Media_package_model->get_media_package_end_points();
        $view_data['channel_id'] = $this->input->get("id");

        $data['page_data'] = $this->load->view('live_module/media_package_endpoint', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    function delete_media_package_endpoint($id, $channel_id, $endpoint_id) {
        $this->Media_package_model->delete_media_package_endpoint($id, $channel_id, $endpoint_id);
    }
    
    function update_media_package_endpoint($id, $channel_id, $endpoint_id) {
        $this->Media_package_model->update_media_package_endpoint($id, $channel_id, $endpoint_id);
    }
    

    function create_asset($url, $id, $resource_id, $type = "dash") {
        return $this->Media_package_model->{__FUNCTION__}($url, $id, $resource_id, $type);
    }

    function ajax_re_record_harvest() {
        $input = $this->input->post();
        if ($input) {
            $this->db->where("id", $input['harvest_id']);
            $data = $this->db->get("aws_media_package_harvesting")->row_array();

            $data['json'] = json_decode($data['json'], true);

            $data['json']['harvest_parent_id'] = $input['harvest_id'];

            $ingest_content = array(
                "id" => $data['video_id'],
                "endpoint_id" => $data['channel_id'],
                "video_id" => $data['video_id'],
                "from" => strtotime($input["session"]["record_from"]),
                "to" => strtotime($input["session"]["record_to"])
            );

            $harvest_data = Modules::run("auth_panel/live_module/media_package/schedule_harvest", $ingest_content);
            if ($harvest_data) {
                $harvest_data['created'] = time();
                $harvest_data['video_id'] = $data['video_id'];
                $harvest_data['json'] = json_encode($data['json']);
                $harvest_data['created_by'] = $this->session->userdata("active_backend_user_id");
                $this->db->insert("aws_media_package_harvesting", $harvest_data);

                $harvest_id = $this->db->insert_id();

                backend_log_genration($this, 'Harvest Job Re-Record(' . $input['harvest_id'] . ') with ID-: ' . $harvest_id, 'LIVE_CLASS');
            }
            echo json_encode(array("type" => "success", "title" => "Success!", "message" => "Video Re-Recording successful"));
        } else {
            echo json_encode(array("type" => "error", "title" => "Error!", "message" => "Invalid Params"));
        }
    }

    function schedule_harvest($input) {
        return $this->Media_package_model->schedule_harvest($input);
    }

    function describe_harvest($harvest_id) {
        return $this->Media_package_model->describe_harvest($harvest_id);
    }

    function harvest_job() {
        $view_data['breadcrum']=array(' Harvest Jobs List'=>"#");
        $data['page_data'] = $this->load->view('live_module/media_package_harvest_job', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    function ajax_harvest_jobs_list() {
        // storing  request (ie, get/post) global array to a variable
        $requestData = $_REQUEST;

        $columns = array(
            // datatable column index  => database column name
            0 => 'amph.id',
            1 => 'ctfmm.title',
            3 => 'amph.channel_id',
            4 => 'amph.harvest_id',
            5 => 'amph.harvest_from',
            6 => 'amph.harvest_to',
            9 => 'created',
        );
        $query = "SELECT count(id) as total FROM aws_media_package_harvesting where 1=1";
        $query .= app_permission("app_id");
        $query = $this->db->query($query)->row_array();
        $totalData = (count($query) > 0) ? $query['total'] : 0;
        $totalFiltered = $totalData;

        $sql = "SELECT amph.*,ctfmm.title as video_title,ctfmm.thumbnail_url
                    FROM aws_media_package_harvesting as amph
                    join course_topic_file_meta_master ctfmm on ctfmm.id = amph.video_id
                    where 1 ";//ctfmm.is_vod,ctfmm.encrypted_urls
        $sql .= app_permission("amph.app_id");

        if (!empty($requestData['columns'][1]['search']['value'])) { //salary
            $sql .= " AND ctfmm.title LIKE '" . $requestData['columns'][1]['search']['value'] . "%' ";
        }

        $totalFiltered = $this->db->query($sql)->num_rows();

        $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   "; // adding length
        $result = $this->db->query($sql)->result();
        $data = array();
        foreach ($result as $r) {
            $nestedData = array();
            $nestedData[] = $r->id;
            $nestedData[] = "<a href='" . AUTH_PANEL_URL . "file_manager/library/edit_video_library/" . $r->video_id . "'>$r->video_title</a>";
            $nestedData[] = "<img  width='50px' src='" . $r->thumbnail_url . "'>";
            $nestedData[] = $r->channel_id;
            $nestedData[] = $r->harvest_id;
            $nestedData[] = get_time_format(strtotime($r->harvest_from));
            $nestedData[] = get_time_format(strtotime($r->harvest_to));

            $class = "";
            switch ($r->status) {
                case "IN_PROGRESS":
                    $class = "warning";
                    break;
                case "SUCCEEDED":
                    $class = "success";
                    break;
                case "FAILED":
                    $class = "danger";
                    break;
            }
            $nestedData[] = 1 ? "<span class='badge badge-success'>Yes</span>" : "<span class='badge badge-danger'>No</span>";
            $is_approved = "<span class='badge badge-danger disabled'>N/A</span>";
            if ($r->status == "SUCCEEDED") {
                $is_approved = $r->is_approved ? "<span class='badge badge-success disabled'>Approved</span>" : "<button class='btn btn-info btn-xs preview_content' data-url='" . $r->video_id . "' data-id='" . $r->id . "'><i class='fa fa-eye'></i> Preview</button>";
            }
            $nestedData[] = $is_approved;
            $nestedData[] = get_time_format($r->created);
            $nestedData[] = "<span class='badge badge-$class'>$r->status</span>";

            $action = "";
            if ($r->status == "IN_PROGRESS")
                $action = "<button class='btn-xs bold harvest_job_refresh btn btn-info hide' data-id='" . $r->id . "'><i class='fa fa-refresh'></i></button>";
            else if ($r->status == "SUCCEEDED") {
                if ($r->s3_key) {
//                    $action = " <button class='btn-xs bold btn btn-info download_offline' data-video_id='" . $r->video_id . "' data-id='" . $r->id . "'>VOD <i class='fa fa-download'></i></button>";
                    if (!$r->is_approved)
                        $action .= "<button  class='btn-xs bold trigger_media_convert btn btn-warning' data-id='" . $r->id . "'>To VOD <i class='fa fa-arrow-right'></i></button>";
                    //$action .= " <button class='btn-xs bold btn btn-danger delete_vod' data-id='" . $r->id . "'><i class='fa fa-trash'></i> VOD</button>";
                } else {
                    $action = "<span class='badge badge-danger'>Vod Deleted</span>";
                }
            }
            if (($r->status == "FAILED" || $r->status == "SUCCEEDED")) {
                if ($r->created + (86400 * 2) > time())
                    $action .= "<button class='btn-xs bold re_record_video btn btn-info' data-id='" . $r->id . "'>Re-record</button>";
                else
                    $action .= "<button class='btn btn-xs btn-info' disabled title='Recording Expired. You can record upto 2 days only.'>Re-record</button>";
            }
            $nestedData[] = $action;

            $data[] = $nestedData;
        }

        $json_data = array(
            "draw" => intval($requestData['draw']), // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
            "recordsTotal" => intval($totalData), // total number of records
            "recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
            "data" => $data, // total data array
        );
        $json_data = json_encode($json_data);
        echo s3_to_cf($json_data); // send data as json format
    }

    function harvest_tracking() {
        $input = $this->input->post();
        if ($input) {
            $this->db->where("id", $input['id']);
            $data = $this->db->get("aws_media_package_harvesting")->row_array();
            if ($data['status'] == "IN_PROGRESS") {
                $track_data = $this->describe_harvest($data['harvest_id']);
                //$data['status'] != "SUCCEEDED" &&
                if ($track_data['status'] == "SUCCEEDED") {
                    $result = $this->Media_package_model->harvest_job_to_vod($data);
                    if ($result) {
                        $this->load->helper('template');
                        backend_log_genration($this, 'Video Mode Changed To DRM VOD Available S.No -: ' . $data['video_id'], 'MANUAL_DRM_VIDEO');
                    }
                }
                $this->db->where("id", $input['id']);
                $this->db->set("status", $track_data['status']);
                $this->db->update("aws_media_package_harvesting");
                
                echo json_encode(array("type" => "success", "title" => "Success", "message" => "MP Harvest Status: " . $track_data['status']));
            } else if ($data['status'] == "FAILED") {
                echo json_encode(array("type" => "error", "title" => "Failed", "message" => "MP Harvest Status: " . $data['status']));
            } else {
                echo json_encode(array("type" => "warning", "title" => "In Processing", "message" => "MP Harvest Status: " . $data['status']));
            }
        }
    }

    function harvest_job_to_vod() {
        $this->load->helper("custom");
        $id = $this->input->post("id");

        $this->db->where("id", $id);
        $job_meta = $this->db->get("aws_media_package_harvesting")->row_array();
        if ($job_meta) {
            $result = $this->Media_package_model->harvest_job_to_vod($job_meta);
            if ($result) {
                backend_log_genration($this, 'Video Mode Changed To DRM VOD Available S.No -: ' . $id, 'DRM_VIDEO');
                echo json_encode(array("type" => "success", "title" => "Video Operation Done", "message" => "Now onward this video will be available in VOD mode"));
            } else {
                echo json_encode(array("type" => "error", "title" => "Playlist is not available", "message" => "Parser did not found playlist."));
            }
        }
    }

    function auto_harvest_job_to_vod() {
        $input = $this->input->post();
        $log = "Time: " . date('Y-m-d, H:i A') . PHP_EOL;
        $log = $log . "Header " . json_encode(getallheaders()) . PHP_EOL;
        $log = $log . "Request " . file_get_contents('php://input') . PHP_EOL . PHP_EOL;
        file_put_contents('uploads/lambda.txt', $log, FILE_APPEND);

        $data = file_get_contents('php://input');
        if ($data) {
            $data = json_decode($data, true);
            if (array_key_exists("Message", $data) && $data['Message']) {
                $data = json_decode($data['Message'], true);
            }
            $job_data = $data['detail']['harvest_job'] ?? array();
            if ($job_data) {
                $this->db->where("harvest_id", $job_data['id']);
                $job_meta = $this->db->get("aws_media_package_harvesting")->row_array();
                if ($job_meta) {
                    if ($job_data['status'] == "SUCCEEDED") {
                        $result = $this->Media_package_model->harvest_job_to_vod($job_meta);
                        if ($result) {
                            $this->load->helper('template');
                            backend_log_genration($this, 'Video Mode Changed To DRM VOD Available S.No -: ' . $job_meta['video_id'], 'AUTO_DRM_VIDEO');
                        }
                    }
                    //In case of job failure or any error happen
                    $this->db->where("id", $job_meta['id']);
                    $this->db->update("aws_media_package_harvesting", array("status" => $job_data['status']));
                }
            }
        }
    }

    function fetch_video_playlist() {
        $id = $this->input->post("id");
        $video_id = $this->input->post("video_id");
        if (!$id) {
            echo json_encode(array("type" => "error", "title" => "Error!", "message" => "Id is not Available"));
            die;
        }
        $this->load->helper(["aes", "custom"]);

        $this->db->where("id", $id);
        $harvest_meta = $this->db->get("aws_media_package_harvesting")->row_array();

        $this->db->select("id,file_url,encrypted_urls,token,playtime");
        $this->db->where("id", $video_id);
        $file_meta = $this->db->get("course_topic_file_meta_master")->row_array();

        $token = explode("_", $file_meta['token'])[2];
        $play_list = $this->retrieve_play_list_from_vod($harvest_meta['s3_key'], $id, $token);
        if (!$play_list) {
            echo json_encode(array("type" => "error", "title" => "Error!", "message" => "Play List is not Available"));
            die;
        }

        $encrypted_urls = json_decode($file_meta['encrypted_urls'], true);
        if (!$encrypted_urls)
            $encrypted_urls = array();

        foreach ($play_list as $key => $link) {
            $search_index = array_search($link['name'], array_column($encrypted_urls, 'name'), true);
            if ($search_index !== false) {
                $play_list[$key]['size'] = $encrypted_urls[$search_index]['size'];
            } else {
                $play_list[$key]['size'] = "";
            }
        }
        echo json_encode(array("type" => "success", "title" => "Success..", "message" => "Play List is Displayed", "data" => $play_list));
    }
    
    function download_video($m3u8_url){
        $result = $this->Media_package_model->download_video($m3u8_url);
        return $result;
    }
    function approve_harvest_content() {
        $id = $this->input->post("id");
        if (!$id) {
            echo json_encode(array("type" => "error", "title" => "Error!", "message" => "Id is not Available"));
            die;
        }

        $this->db->where("id", $id);
        $this->db->update("aws_media_package_harvesting", array("is_approved" => 1));

        backend_log_genration($this, 'Harvest job approved S.No -: ' . $id, 'HARVEST_APPROVED');
        echo json_encode(array("type" => "success", "title" => "Success..", "message" => "Video Approved Successfully"));
    }

    private function retrieve_play_list_from_vod($url, $file_id, $token) {
        $s3_url = 'https://ni-media.s3.' . AMS_REGION . '.amazonaws.com/' . $url;
        $main_file_name = explode("/", $s3_url);
        $main_file_name = str_replace(".m3u8", "", end($main_file_name));
//        $s3_url = str_replace(AMS_BUCKET_NAME . ".s3." . AMS_REGION . ".amazonaws.com", S3_CLOUDFRONT_DOMAIN, $s3_url);
        exec("ffmpeg -i $s3_url 2>&1", $a, $b);
        $play_list = array();
        foreach ($a as $value) {
            if (strpos($value, "m3u8") && strpos($value, "reading")) {
                preg_match('#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', $value, $match);
                foreach ($match as $link) {
                    if (strpos($link, "m3u8")) {
                        $play_list[] = $link;
                    }
                }
            }
        }

        $size_wise_names = array(
            "0" => "240x426",
            "1" => "360x640",
            "2" => "480x854",
            "3" => "720x1280",
            "4" => "1080x1920"
        );

        $data = array();
        foreach ($play_list as $key => $link) {
            $file_name = $size_wise_names[$key] . ".mp4";

            $data[] = array(
                "name" => $file_name,
                "link" => $link
            );
        }

        return $data;
    }

//    function ajax_delete_video() {
//        require_once FCPATH . 'aws/aws-autoloader.php';
//        
//        $this->db->where("id", $this->input->post("id"));
//        $harvest_content = $this->db->get("aws_media_package_harvesting")->row_array();
//        
//        $s3Client = new S3Client([
//            'version' => 'latest',
//            'region' => AMS_REGION,
//            'credentials' => [
//                'key' => AMS_S3_KEY,
//                'secret' => AMS_SECRET,
//            ],
//        ]);
//        $result = $s3Client->deleteObject(array(
//            'Bucket' => $harvest_content['s3_name'],
//            'Key' => $harvest_content['s3_key'],
//        ));
//        $data = $result->toArray();
//        return $data['ObjectURL'];
//    }
}
