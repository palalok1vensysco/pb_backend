<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH . 'third_party/aws/aws-autoloader.php');
//----added by Akhilesh start------
// require APPPATH.'/third_party/vendor/autoload.php';
//zoom
require_once APPPATH . '/helpers/jwt/src/JWT.php';
require_once APPPATH . '/helpers/jwt/src/BeforeValidException.php';
require_once APPPATH . '/helpers/jwt/src/ExpiredException.php';
require_once APPPATH . '/helpers/jwt/src/SignatureInvalidException.php';

use \Firebase\JWT\JWT;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
//------added by Akhilesh end-----
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

class Library extends MX_Controller {

    protected $redis_magic = null;

    //-------------
    protected $CHANG_ACCESS_KEY;
    protected $CHANG_BUCKET_KEY;
    protected $CHANG_CLOUDFRONT;
    protected $CHANG_REGION;

    protected $CHANG_COGNITO;
    //------------

    function __construct() {
        parent::__construct();
        $this->load->helper(['aes', 'aul', 'custom']);
        modules::run('auth_panel/auth_panel_ini/auth_ini');
        define('Z_API_KEY', '');
        define('Z_SECRET_KEY', '');  
        $this->load->library('form_validation', 'uploads');
        $this->load->model("Library_model");
        $this->load->model("Category_model");
        $this->load->model("Movies_model");
         $this->load->model("Premium_video_model");
        $this->redis_magic = new Redis_magic("session");
        
        //----------
        $this->retrieve_s3crendential();
    }

  
    private function countPages($path) {
        $pdftext = file_get_contents($path);
        $num = preg_match_all("/\/Page\W/", $pdftext, $dummy);
        return $num;
    }

    private function retrieve_s3crendential() {
        $s3details = json_decode(get_db_meta_key($this->db, "s3bucket_detail"), "");
      //  print_r($s3details) ;die;
        if ($s3details) {
            $this->CHANG_ACCESS_KEY = $s3details->access_key;
            $this->CHANG_BUCKET_KEY = $s3details->bucket_key;
            $this->CHANG_CLOUDFRONT = $s3details->cloudfront;
            $this->CHANG_REGION = $s3details->region;            
        }
      
    }

    public function index() {

        if ($this->input->post()) {
            $this->submit_pdf_library(1);
        }
        $view_data['page'] = 'add_pdf';
        $data['page_title'] = "Add Pdf";
        $view_data['breadcrum']=array('Pdf'=>"file_manager/library/index");
        $data['page_data'] = $this->load->view('file_manager/add_pdf', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function add_link() {

        if ($this->input->post()) {
            $this->submit_pdf_library(8);
        }

        $view_data['page'] = 'add_link';
        $data['page_title'] = "Add Link";
        $view_data['breadcrum']=array('Links'=>"file_manager/library/add_link");
        $data['page_data'] = $this->load->view('file_manager/add_link', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function download_pdf() {
        $file = $this->input->get("pdf_url");
        header('Content-Type: application/pdf');
        header("Content-Disposition: attachment; filename=\"$file\"");
        readfile($file);
    }

    public function ajax_link_list() {
        // storing  request (ie, get/post) global array to a variable
        $requestData = $_REQUEST;
        $user_data = $this->session->userdata('active_user_data');
        $instructor_id = $user_data->instructor_id;
        $backend_user_id = $user_data->id;
        $where = "";

        $columns = array(
        // datatable column index  => database column name
            0 => 'ctfmm.id',
            1 => 'ctfmm.title',
            2 => 'ctfmm.course_names',
            3 => 'csm.name',
            4 => 'cstm.topic',
            5 => 'ctfmm.is_download',
            6 => 'bu.username',
            7 => 'ctfmm.created',
        );

        $query = "SELECT count(ctfmm.id) as total FROM course_topic_file_meta_master ctfmm where ctfmm.file_type =8 ";
        $query .=  (defined("APP_ID") ? "" . app_permission("ctfmm.app_id") . "" : "");
        $query = $this->db->query($query);
        $query = $query->row_array();
        $totalData = (count($query) > 0) ? $query['total'] : 0;

        $filtered_sql = $sql = "SELECT ctfmm.id as id,ctfmm.title as title ,ctfmm.file_url,ctfmm.thumbnail_url as URL,ctfmm.page_count as page_count, csm.name as subject,cstm.topic as topic,ctfmm.course_names,ctfmm.subject_id,ctfmm.topic_id,ctfmm.created,bu.username
                FROM course_topic_file_meta_master as  ctfmm
                join course_subject_master as csm on ctfmm.subject_id = csm.id
                join course_subject_topic_master as cstm on ctfmm.topic_id = cstm.id
                join backend_user bu on bu.id = ctfmm.backend_user_id
                where  ctfmm.file_type = 8
                ";
        $where = "";
        if (!empty($requestData['columns'][0]['search']['value'])) {
            $where .= " AND ctfmm.id LIKE '" . $requestData['columns'][0]['search']['value'] . "%' ";
        }
        if (!empty($requestData['columns'][1]['search']['value'])) {
            $where .= " AND ctfmm.title LIKE '" . $requestData['columns'][1]['search']['value'] . "%' ";
        }
        if (!empty($requestData['columns'][2]['search']['value'])) {
            $where .= " AND ctfmm.course_names LIKE '%" . $requestData['columns'][2]['search']['value'] . "%' ";
        }


        if (!empty($requestData['columns'][3]['search']['value'])) {
            $where .= " AND csm.name LIKE '" . $requestData['columns'][3]['search']['value'] . "%' ";
        }

        if (!empty($requestData['columns'][4]['search']['value'])) {
            $where .= " AND cstm.topic LIKE '" . $requestData['columns'][4]['search']['value'] . "%' ";
        }

        if (!empty($requestData['columns'][5]['search']['value'])) {
            $where .= " AND bu.username LIKE '" . $requestData['columns'][5]['search']['value'] . "%' ";
        }

        $where .=  (defined("APP_ID") ? "" . app_permission("ctfmm.app_id") . "" : "");
        $sql .= $where;
        $filtered_sql .= $where;

        $totalFiltered = $totalData;

        $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   "; // adding length


        $result = $this->db->query($sql)->result();
        $data = array();
        foreach ($result as $r) {
            // preparing an array
            $nestedData = array();

            $nestedData[] = $r->id;
            $nestedData[] = "<a class='bold' href='" . AUTH_PANEL_URL . "file_manager/library/edit_link/" . $r->id . "' target='__blank' title='Edit Link'>{$r->title}</a>";
            $nestedData[] = $r->course_names;
            $nestedData[] = $r->subject . "[ID : {$r->subject_id}]";
            $nestedData[] = $r->topic . "[ID : {$r->topic_id}]";
            $nestedData[] = "<img  height = '60px' width ='60' src= " . $r->URL . ">";
            $nestedData[] = ($r->username) ? $r->username : "--NA--";
            $nestedData[] = ($r->created) ? get_time_format($r->created) : "--NA--";
            $nestedData[] = "<a href='" . $r->file_url . "' class='btn btn-info btn-xs bold' title='View Link'  target= '_blank'><i class='fa fa-eye'></a>";
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
    public function create_clip(){



        if($this->input->post('v_id')){
            $id=$this->input->post('v_id');   
        }
       
        if (!$id) {
            echo json_encode(array("type" => "error", "title" => "Error!", "message" => "Id is not Available"));
            die;
        }
            $data=array(
            'videoID'=>$id,
            'startTime' => '00:00:00',
            'endTime' => '00:00:50',
            'title'=>  'test file video',
            'description' => 'this is my test',
            'destinationFolder' => 'abcd'
        );
        //pre($data);die;
            $accesskey= base64_encode(VC_ACCESS_KEY);
            $secret=base64_encode(VC_SECRET_KEY); //pre($vc_key); pre($accesskey); die;
            $headers = array("accessKey:$accesskey","secretKey:$secret");
            $ch = curl_init();
            //curl_setopt($ch, CURLOPT_URL, "https://www.videocrypt.in/index.php/rest_api/courses/course/getDurationByUrl");
            curl_setopt($ch, CURLOPT_URL, "https://api.videocrypt.com/createClip");
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            $data = curl_exec($ch);
            curl_close($ch);        
          //  $play_list = json_decode($data,true);
            
                 echo $data;
           

            }

    public function url_validator($type, $url) {
        $normal_pattern = "/(https?\:\/\/)?(utkarsh-prod\.s3\.ap-south-1\.amazonaws\.com)\/.+$/";
        $youtube_pattern = "/(https?\:\/\/)?(www\.youtube\.com|youtu\.be)\/.+$/";
        $jw_pattern = "/(https?\:\/\/)?(content\.jwplatform\.com)\/.+$/";
        switch ($type) {
            case 0:
                $data = 1; //preg_match($normal_pattern, $url);
                $message = "Your uploaded file url is not valid.";
                break;
            case 1:
                $data = 1;
                $message = "Your youtube video url is not valid.";
                break;
            case 4:
                $data = 1;
                $message = "Your youtube video url is not valid.";
                break;
            case 6:
                $data = preg_match($jw_pattern, $url);
                $message = "Your jw video url is not valid.";
                break;
            default:
                break;
        }
        $result['result'] = $data;
        $result['message'] = $message;
        return $result;
    }

    private function is_file_exists($data){
    //print_r($data);die;
            $app_id = (defined("APP_ID") && APP_ID)?APP_ID:0; 
        $this->db->select('id,title');
        $this->db->where('title', $data);
        $this->db->where('app_id', $app_id);      
        $app = $this->db->get("course_topic_file_meta_master");
        return ($app->num_rows() > 0 )?true:false;
    }

    public function add_video($id = NULL, $video_id = null) {
        if(empty($_POST)){
            return;
        }
        $this->load->model("ShowsModel"); 
        $view_data['shows'] = $this->ShowsModel->get_by_id($id);
        // pre($view_data['shows']);die;
        if(empty($view_data['shows'])){
            page_alert_box('error', 'Error', 'Shows Id is missing!!..');
            redirect(base_url() . 'admin-panel/add-content');
        }
        if(!empty($video_id)){
            $view_data['edit_video'] = $this->db->get_where('media_library', ['id' => $video_id, 'show_id' => $id])->row_array();
            if(empty($view_data['edit_video'])) {
                page_alert_box('error', 'Error', 'Video Id is missing!!..');
                redirect(base_url() . 'admin-panel/add-content/' . $id);
            }
        }
        if($this->input->get('season_id')){
            $season_id = $this->input->get('season_id');
            $view_data['specific_season'] = $this->db->get_where('seasons', ['show_id' => $id, 'id' => $season_id])->row_array();
            if(empty($view_data['specific_season'])){
                page_alert_box('error', 'Error', 'Seasion Id is missing!!..');
                redirect($_SERVER['HTTP_REFERER']);
            }
            // if ($this->input->post()) {
                
            // }
            $this->form_validation->set_rules('by_method', 'By Method', 'required|in_list[0,1]');   
            if($shows['type'] == 1){
                $this->form_validation->set_rules('file_url', 'File Url', 'required');   
            } else{
                $this->form_validation->set_rules('by_method', 'by method', 'required|in_list[1,2]');   
                $this->form_validation->set_rules('content_type', 'Content Type', 'required|in_list[0,1]');   
                if ($this->input->post('by_method') == 1){
                    $this->form_validation->set_rules('channel_id', 'Channel Id', 'required');   
                }else{
                    $this->form_validation->set_rules('videocript_id', 'Videocript Id', 'required');   
                }
            }
            $this->form_validation->set_rules('title', 'Title', 'required');   
            if(!empty($this->input->post('skip_season'))){
                $this->form_validation->set_rules('skip_season', 'skip_season', 'required|in_list[1]');   
            }   
            if(!empty($this->input->post('trailer_chk'))){
                $this->form_validation->set_rules('trailer_chk', 'Trailer', 'required|in_list[1]');   
            }
            $this->form_validation->set_rules('published_date', 'published Date', 'required');   
            $this->form_validation->set_rules('description', 'Description', 'required');  
            if(!empty($video_id)){
                if(empty($_FILES['thumbnail']['name'])){
                    $this->form_validation->set_rules('thumbnail', 'Thumbnail', 'required');  
                } 
               if(empty($_FILES['poster']['name'])){
                    $this->form_validation->set_rules('poster', 'Poster', 'required');  
                } 
            }
            if ($this->input->post('by_method') == 1 && !empty($this->input->post('video_upload'))) {

                    $title = $this->input->post('title');
                    $bitrate = $this->input->post("bitrat") ? implode(",", $this->input->post("bitrat")) : '';
                  $pltform = $this->input->post("pltform") ? implode(",", $this->input->post("pltform")) : '';
                  $v_url = $this->input->post('video_upload');
                    $drm = $this->input->post('is_drm_protected') ?? 0;
                    $plfm = ($this->input->post('is_drm_protected') == 1) ? $pltform : '4';
                  $dwn = $this->input->post('is_download') ? $this->input->post('is_download') : '0';
                $vid = $this->check_video($title, $bitrate, $v_url, $drm, $dwn, $plfm);
                    if (empty($vid)) {
                        page_alert_box('error', "Video id is not created", 'Please check credentials.');
                        redirect_to_back();
                    }
                }


            $by_method = $this->input->post('by_method');
            $backend_user_id = $this->session->userdata('active_backend_user_id');
            $this->form_validation->set_rules('title', 'File Title', 'required');
            $this->form_validation->set_rules('media_type', 'Video Type', 'required');
            $media_type = $this->input->post("media_type");
            $cate_type = $this->input->post("cate_type");
            $is_drm_protected= $this->input->post("is_drm_protected");
            if (!empty($_FILES['thumbnail']['name'])) {
                if ($_FILES['thumbnail']['size'] > 1048576) {
                    page_alert_box("error", "Add Video PDF", "PDF thumbnail file doesn't excced size more than 1 MB.");
                    redirect_to_back();
                }
            }






            if (!empty($_FILES['poster']['name'])) {
                if ($_FILES['poster']['size'] > 1048576) {
                    page_alert_box("error", "Add Video PDF", "PDF Poster file doesn't excced size more than 1 MB.");
                    redirect_to_back();
                }
            }

            if ($this->form_validation->run() == FALSE) {
                $errors = $this->form_validation->get_all_errors();
                page_alert_box("error", "Add Video", array_values($errors)[0]);
            } else {
                if ($this->input->post('by_method') == 1) {
                    $result = $this->url_validator($media_type, $this->input->post('video_file'));
                    if (!$result['result']) {
                        page_alert_box('error', $result['message'], 'Please try again with valid url.');
                        redirect_to_back();
                    }
                }
                $input = $this->input->post('title');
                $VideoTitles = $this->input->post('title');
                $VideoTitles=preg_replace('/[^ -\x{2122}]\s+|\s*[^ -\x{2122}]/u','',$VideoTitles); 
                $insert_data = array(
                    'title' => $VideoTitles,
                    'media_type' => $media_type,
                    'season_id' => ($this->input->get("season_id")),
                    'is_download' => ($this->input->post("is_download")) ? $this->input->post("is_download") : 0,
                    'backend_user_id' => $backend_user_id,
                    'published_date' => $this->input->post("published_date"),
                    'description' => $this->input->post("description"),
                    'skip_intro'=>$this->input->post('skip_intro') ?? 0,
                    'skip_time'=>$this->input->post('skip_time') ?? 0,
                    // 'file_url' => $hls_url ? $hls_url : "",
                     'vdc_id' => $this->input->post('videocript_id'),
                    "created" => time(),
                    "show_id" => $id,
                    'is_drm_protected'=>$this->input->post('is_drm_protected')??0,
                    'lang_id' => $this->input->post("lang_id[]") ?? 1,
                    'by_method' => $this->input->post("by_method") ?? 1,
                    'episode' => $this->input->post("episode") ?? 0, 
                    'is_trailer' => $this->input->post("is_trailer") ?? 0,
                    'is_live' => $this->input->post("content_type") ?? 0
                );
                
                if($this->input->post("content_type") == 1){
                    $insert_data['channel_id'] = $this->input->post("channel_id");
                    $this->db->select("file_url_hls");
                    $aws_channel = $this->db->get_where('aws_channel', ['id' => $this->input->post("channel_id")])->row_array();
                    if(!empty($aws_channel['file_url_hls'])){
                        $insert_data['file_url'] = $aws_channel['file_url_hls'];
                    }
                }
                if($media_type == 0){
                    $insert_data['file_url'] = "";
                }else{
                    $insert_data['vdc_id'] = "";
                    $insert_data['file_url'] = $this->input->post("file_url") ?? "";
                }
                
                if(!empty($insert_data['vdc_id'])){
                    $videocrypt=$this->fetch_videocrypt_playlist($this->input->post('videocript_id'),'array');
                    
                if(!empty($videocrypt['result']) && $videocrypt['result'] == 1){
                    if($this->input->post('play_via')==0){
                        $insert_data['file_url']=$videocrypt['data']['file_url_hls'];
                    }
                    if($this->input->post('play_via')==1){
                        $insert_data['file_url']=$videocrypt['data']['file_url_dash'];
                    }
                    if($this->input->post('is_drm_protected')==1){                            
                        $insert_data['is_drm_protected']=$this->input->post('is_drm_protected');
                        $insert_data['drm_dash_url']=$videocrypt['data']['drm_dash_url'];
                        $insert_data['drm_hls_url']=$videocrypt['data']['drm_hls_url'];                           
                    }   
                    if($videocrypt['data']['download_url']!=""){
                        $insert_data['bitrate_urls']=json_encode($videocrypt['data']['download_url']);
                    }
                    $insert_data['vdc_id']=$videocrypt['data']['id'];
                    if(!empty($videocrypt['data']['vod_vtt']) && !empty($videocrypt['data']['vod_srt'])){                        
                        $insert_data['vod_vtt_url']=$videocrypt['data']['vod_vtt'];
                        $insert_data['vod_srt_url']=$videocrypt['data']['vod_srt'];
                    }
                    $insert_data['vdc_json']=json_encode($videocrypt['data']);
                    sscanf($videocrypt['data']['duration'], "%d:%d:%d", $hours, $minutes, $seconds);
                   
                }
                   
                }else{
                   // $insert_data['playtime'] =($this->input->post('playtime')?($this->input->post('playtime') * 60) : 0);
                }
                $thumbnail = $this->input->post('thumbnail');
                $poster = $this->input->post('poster');
                if (!$thumbnail && isset($_FILES['thumbnail']) && $_FILES['thumbnail']['size'] > 0) 
                    $thumbnail = amazon_s3_upload($_FILES['thumbnail'], "file_manager/media_library", rand());
                    $thumbnail=str_replace( 'https://'.$this->CHANG_BUCKET_KEY . ".s3." . $this->CHANG_REGION . ".amazonaws.com",$this->CHANG_CLOUDFRONT, $thumbnail);

                    
                   //  print_r($thumbnail1); die;
                if (!$poster && isset($_FILES['poster']) && $_FILES['poster']['size'] > 0)
                    $poster = amazon_s3_upload($_FILES['poster'], "file_manager/media_library", rand());
                    $poster=str_replace( 'https://'.$this->CHANG_BUCKET_KEY . ".s3." . $this->CHANG_REGION . ".amazonaws.com",$this->CHANG_CLOUDFRONT, $poster);

                $insert_data['thumbnail_url'] = $thumbnail;
                $insert_data['poster_url'] = $poster;
                if(!empty($video_id)){
                    if($thumbnail == ""){
                        $insert_data['thumbnail_url'] = $view_data['edit_video']['thumbnail_url'];
                    }
                    if($poster == ""){
                        $insert_data['poster_url'] = $view_data['edit_video']['thumbnail_url'];
                    }
                    unset($insert_data['created']);
                    $this->db->update('media_library', $insert_data, ['id' => $video_id]);
                    // echo $this->db->last_query();die;
                    page_alert_box('success', 'Action performed', 'File Updated successfully');
                }else{
                    $this->db->insert('media_library', $insert_data);
                    page_alert_box('success', 'Action performed', 'File added successfully');
                }
                // echo $this->db->last_query();die;
                // pre($insert_data);die;
                redirect($_SERVER['HTTP_REFERER']);
            }
        }    
die;
    }

     private function save_notification($movie_id='', $device_type='', $message='', $transaction_status='',$payment_mode='',$userid='') {    
        $insert_data = array(
            'action_element' => "0",
            'action_element_id' => (isset($movie_id) ? "" . $movie_id . "" : "0"),
            'device_type' => $device_type,
            'title' => $message,
            'message' => ($message) ? $message : $title,
            'created' => time(),
            'app_id' => (defined("APP_ID") ? "" . APP_ID . "" : "0"),
            'ppv_paid' => 1,
            'transaction_status' => $transaction_status,
            'type' => $payment_mode
        );
        $this->db->insert("user_activity_generator", $insert_data);
        if (is_array($userid)) {
            $user_id = array_unique($userid);
            foreach ($user_id as $uid) {
                $insert_array[] = array(
                    'user_id' => $uid,
                    'n_id' => $this->db->insert_id(),
                    'view_state' => 0,
                    'app_id' => (defined("APP_ID") ? "" . APP_ID . "" : "0")
                );
            }
        } else {
            $insert_array[] = array(
                'user_id' => $userid,
                'n_id' => $this->db->insert_id(),
                'view_state' => 0,
                'app_id' => (defined("APP_ID") ? "" . APP_ID . "" : "0")
            );
        }

        $this->db->insert_batch('user_activity_relation', $insert_array);
    }

    public function attach_video_to_course() {
        if ($this->input->post()) {
            $input_data = $_POST();
            $result = $this->Library_model->attach_video_to_course($input_data);
            if ($result) {
                page_alert_box("success", "Attach file in course", "File has been attached with courses successfully.");
                // backend_log_genration($this, "File has been attached with courses successfully.", "Attach file in course", $input_data);
            } else {
                page_alert_box("error", "Attach file in course", "Something went wrong.");
            }
            redirect_to_back();
        }
    }

     public function ajax_video_file_list() {
        // storing  request (ie, get/post) global array to a variable
        $requestData = $_REQUEST;
       // print_r($requestData);
        $user_data = $this->session->userdata('active_user_data');
        //$instructor_id = $user_data->instructor_id;
        $backend_user_id = $user_data->id;
        $where =   ' where 1=1 ';
        $where .=  (defined("APP_ID") ? "" . app_permission("ctfmm.app_id") . "" : "");
        $where .= " AND ctfmm.file_type = 3 ";
        $columns = array(
            // datatable column index  => database column name
            0 => 'ctfmm.id',
            1 => 'ctfmm.title',
            2 => 'ctfmm.category',
            3 => 'ctfmm.artists',
            4 => 'cstm.view_mode',
            5 => 'ctfmm.thumbnail_url',
            6 => 'ctfmm.poster',
            7 => 'ctfmm.video_type',
            8 => 'bu.username',
            9 => 'ctfmm.created',
            12 => 'ctfmm.id'
        );
        $where = " where ctfmm.status =0  ";
        $where = " where ctfmm.ppv =0  ";
        $where .=  (defined("APP_ID") ? "" . app_permission("ctfmm.app_id") . "" : "");
        $query = "SELECT count(ctfmm.id) as total FROM course_topic_file_meta_master ctfmm ";
        $total_query = $this->db->query($query . $where)->row_array();
        $totalData = (count($total_query) > 0) ? $total_query['total'] : 0;

        $sql = "SELECT ctfmm.id as id,ctfmm.title as title ,lang.lang_name ,cate.cat_name,cate.category_type,ctfmm.file_url,ctfmm.join_url,ctfmm.drm_dash_url,ctfmm.video_type,ctfmm.thumbnail_url as URL,ctfmm.poster_url as poster,ctfmm.created,ctfmm.course_names,bu.username,ctfmm.topic_id,ctfmm.extra_params,ar.name as artist,ctfmm.category,ctfmm.ppv,ctfmm.view_mode,(case when vl.is_liked != '' then vl.is_liked else '0' END) as is_liked
            FROM course_topic_file_meta_master as  ctfmm
            left join video_likes as vl on vl.video_id = ctfmm.id 
            left join languages as lang on lang.id = ctfmm.lang_id 
            left join backend_user bu on bu.id = ctfmm.backend_user_id
            left join artists ar on ar.id = ctfmm.artists_type
            left join categories cate on cate.id = ctfmm.category ";
           // echo($sql);die;

        $sql .= (defined("APP_ID") ? "" . app_permission("ctfmm.app_id") . "" : "");
        $where .= " AND ctfmm.ppv = 0" ;
        $where .= " AND ctfmm.status = 0" ;
        if (!empty($requestData['columns'][0]['search']['value'])) {
            $where .= " AND ctfmm.id LIKE '" . $requestData['columns'][0]['search']['value'] . "%' ";
        }
        if (!empty($requestData['columns'][1]['search']['value'])) {
           // $where .= " AND ctfmm.title LIKE '" . $requestData['columns'][1]['search']['value'] . "%' ";
             $where .= " AND ctfmm.title LIKE '%" . str_replace("'","",$requestData['columns'][1]['search']['value']) .  "%' ";
        }
        if (!empty($requestData['columns'][2]['search']['value'])) {
            $where .= " AND ctfmm.category LIKE '%" . $requestData['columns'][2]['search']['value'] . "%' ";
        }
        if (!empty($requestData['columns'][3]['search']['value'])) {
            $where .= " AND ctfmm.artists_type LIKE '" . $requestData['columns'][3]['search']['value'] . "%' ";
        }
        if ($requestData['columns'][6]['search']['value'] != "") {
            $where .= " AND ctfmm.video_type = " . $requestData['columns'][6]['search']['value'];
        }
        if ($requestData['columns'][7]['search']['value'] != "") {
            $where .= " AND ctfmm.video_type LIKE '" . $requestData['columns'][7]['search']['value'] . "%'";
        }


        if ($requestData['columns'][9]['search']['value'] != "") {
            $where .= " AND ctfmm.created LIKE '" . $requestData['columns'][9]['search']['value'] . "%'";
        }
        if ($requestData['columns'][4]['search']['value'] != "") {
            $where .= " AND ctfmm.view_mode LIKE '" . $requestData['columns'][4]['search']['value'] . "%'";
        }
        //print_r($requestData['columns'][4]);die;
        $query .= "left join backend_user bu on bu.id = ctfmm.backend_user_id" . $where;
        $totalFiltered = $this->db->query($query)->row()->total;

        
        $sql .= $where;
       // $sql = "ctfmm.status = 0";
        $sql .= " GROUP BY ctfmm.id";
       $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . "DESC" . "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
        $result = $this->db->query($sql)->result();
        $data = array();
   // eho $this->db->last_query($sql);die;
        
        $video_type = array("Normal", "Youtube", "Vimeo", "Vimeo Streaming", "Youtube Streaming", "AWS Streaming", "JW Video","Videocrypt VOD","Videocrypt LIVE","Zoom Live","Videocrypt Fast Live");
        $start = array();
        $start = 0;
        foreach ($result as $r) { 
                        $manage = "";
            if($r->category_type == '2' || $r->category_type == '3'){
               $manage = "<a class='' title='Add Episode' href='" . AUTH_PANEL_URL . "file_manager/library/add_season_episode/".$r->id."/".$r->category_type."'><i class='fa fa-plus'></i> Add Episode</a>
                <a class=''href='" . AUTH_PANEL_URL . "file_manager/library/season_episode_list/".$r->id."/".$r->category_type."'><i class='fa fa-edit'></i> Manage Episodes</a>";
            }
            $size_button = '';
            if ($r->video_type == '0') {
                $size_button = "<a  class='btn-sm btn btn-warning btn-xs bold download_offline hide' data-id='{$r->id}' href='javascript:void(0)'>Offline <i class='fa fa-download'></i></a>";
            }
           //  $action  = "<a class='btn btn-sm text-white display_color' onclick=\"return confirm('Are you sure you want to delete?')\" href='" . AUTH_PANEL_URL . "file_manager/library/delete_video/" . $r->id . "'>Delete</a>&nbsp;";

             if($r->video_type == '4' || $r->video_type == '8'){
                $action  = "<a class='' onclick=\"return confirm('Are you sure you want to delete?')\" href='" . AUTH_PANEL_URL . "file_manager/library/delete_video/" . $r->id . "'><i class='fa fa-trash'></i> Delete</a><a class=''  href='" . AUTH_PANEL_URL . "file_manager/library/edit_video_library/" . $r->id . "'><i class='fa fa-pencil'></i> Edit</a>&nbsp;";
            }
            else
            {
                 $action  = "<a class=''  href='" . AUTH_PANEL_URL . "file_manager/library/edit_video_library/" . $r->id . "'><i class='fa fa-pencil'></i> Edit</a>";
            }
            $default_img = ($r->video_type == 1 || $r->video_type == 4) ? "youtube-default.png" : "video_default.png";
            $thumbnail_url = ($r->URL) ? $r->URL : AUTH_ASSETS . "img/" . $default_img;
            $poster_url = ($r->poster) ? $r->poster : AUTH_ASSETS . "img/" . $default_img;
            // preparing an array
            $nestedData = array();         
            $nestedData[]=  ++$requestData['start'];  //++$start;

            $nestedData[] = '<span class="pull-left">' . $r->id . '</span>';
            $nestedData[] = "<a class='bold' href='" . AUTH_PANEL_URL . "file_manager/library/edit_video_library/" . $r->id . "'>" . $r->title . "</a> &nbsp;" . $size_button;
            $nestedData[] = $r->cat_name;
            $nestedData[] = $r->artist;
            $nestedData[] = "<img width ='60px' src= " . $thumbnail_url . ">";
            $nestedData[] = "<img width ='60px' src= " . $poster_url . ">";
            $nestedData[] = $video_type[$r->video_type];
            $nestedData[] = $r->lang_name;
            $nestedData[] = $r->username;
            $nestedData[] = ($r->created > 0) ? get_time_format($r->created) : "N/A";
            $nestedData[] = ($r->view_mode=="0"? "Free":"Paid");
           
             $nestedData[] ="<div class='dropdown toggle_menus_icons'> <button class='btn dropdown_ttgl text-white dropdown-toggle' type='button' data-toggle='dropdown'><i class='fa fa-ellipsis-v' aria-hidden='true'></i></button>
            <ul class='dropdown-menu' aria-haspopup='true' aria-expanded='false'>
              
               <li>
                 $action
               </li>
                          
            </ul>
        </div>";
        if($r->category_type == '2' || $r->category_type == '3'){
          $nestedData[] ="<div class='dropdown toggle_menus_icons'> <button class='btn dropdown_ttgl text-white dropdown-toggle' type='button' data-toggle='dropdown'><i class='fa fa-ellipsis-v' aria-hidden='true'></i></button>
            <ul class='dropdown-menu' aria-haspopup='true' aria-expanded='false'>
              
               <li>
                 $manage
               </li>
                          
            </ul>
        </div>";
    }else{
        $nestedData[] = $manage;
    }
    $nestedData[] = $r->is_liked;
          //  $nestedData[] =  $manage;
            $data[] = $nestedData;
        }

        $json_data = array(
            "draw" => intval($requestData['draw']), // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
            "recordsTotal" => intval($totalData), // total number of records
            "recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
            "data" => $data, // total data array
        );

        $json_data = json_encode($json_data);  // send data as json format
        echo s3_to_cf($json_data); // send data as json format
    }

    public function delete_video($id)
    {
        if(!empty($id)){
            // $res = $this->db->delete('course_topic_file_meta_master',['id'=>$id]);
             $videoarr = array(
                    'status'=>1
                );
             $this->db->where('id',$id);
             $res = $this->db->update('course_topic_file_meta_master',$videoarr);
            if($res){
                //update status of notification start
                    $notifiarr = array(
                        'status'=>1
                    );
                     $this->db->where('action_element_id',$id);
                     $res = $this->db->update('user_activity_generator',$notifiarr);
                 //update status of notification start
                  //--update version start by ak--
                      
                          update_api_version_new($this->db, 'dashboard',$id);
                         update_api_version_new($this->db, 'detail_page',$id);
                     
                    //--update version end--
                page_alert_box("success", "success!", "Video Deleted Successfully");
                redirect_to_back();

            }else{
                page_alert_box("error", "error!", "Unable to delete video");
            }
        }else{
            page_alert_box("error", "error!", "Unable to delete video");
        }

    }

    public function review_course_video($id){
        $view_data['video_id'] = $id;
        $view_data['page'] = 'course_video_feedback';
        $data['page_title'] = "Course Video Feedback";
        $data['page_data'] = $this->load->view('file_manager/course_video_feedback', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function ajax_review_course_video(){
        $requestData = $_REQUEST;
        $columns = array(
            // datatable column index  => database column name
            0 => 'cvfm.id',
            1 => 'bu..title',
            2 => 'cvfm.point',
            3 => 'cvfm.text',
            4 => 'cvfm.created_by',
        );

        $query = "SELECT count(cvfm.id) as total FROM course_video_feedback_master cvfm ";
        $total_query = $this->db->query($query)->row_array();
        $totalData = (count($total_query) > 0) ? $total_query['total'] : 0;

        $sql = "SELECT cvfm.id, cvfm.point, cvfm.created_by, cvfm.text, bu.title FROM course_video_feedback_master cvfm
            join backend_user bu ON bu.id = cvfm.user_id";

        // getting records as per search parameters
        if (!empty($requestData['columns'][0]['search']['value'])) {
            $sql .= " AND cvfm.id LIKE '" . $requestData['columns'][0]['search']['value'] . "%' ";
        }
        if ($requestData['columns'][1]['search']['value'] != "") {
            $sql .= " AND bu.title LIKE '" . $requestData['columns'][1]['search']['value'] . "%'";
        }
        if (!empty($requestData['columns'][4]['search']['value'])) {
            $sql .= " AND cvfm.created_by = '" . $requestData['columns'][4]['search']['value'] . "%' ";
        }
        
        $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   "; // adding length

        $result = $this->db->query($sql)->result();

        $totalFiltered = count($result);

        $data = array();
        foreach ($result as $r) {
            $nestedData = array();
            $nestedData[] = $r->id;
            $nestedData[] = $r->username;
            $nestedData[] = $r->point;
            $nestedData[] = $r->text;
            $nestedData[] = ($r->created_on > 0) ? get_time_format($r->created_on) : "N/A";
            $data[] = $nestedData;
        }

        $json_data = array(
            "draw" => intval($requestData['draw']), // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
            "recordsTotal" => intval($totalData), // total number of records
            "recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
            "data" => $data, // total data array
        );

        $json_data = json_encode($json_data);  // send data as json format
        echo s3_to_cf($json_data); // send data as json format
    }

    
    function fetch_video_playlist() {
        $id = $this->input->post("id");
        if (!$id) {
            echo json_encode(array("type" => "error", "title" => "Error!", "message" => "Id is not Available"));
            die;
        }

        $this->db->select("id,file_url,token,playtime");
        $this->db->where("id", $id);
        app_permission("app_id",$this->db);
        $file_meta = $this->db->get("course_topic_file_meta_master")->row_array();

        $token = explode("_", $file_meta['token'])[2];
        $url = aes_cbc_decryption($file_meta['file_url'], $token);
        $play_list = $this->retrieve_play_list_from_vod($url, $id, $token);
        if (!$play_list) {
            echo json_encode(array("type" => "error", "title" => "Error!", "message" => "Play List is not Available"));
            die;
        }
        echo json_encode(array("type" => "success", "title" => "Success..", "message" => "Play LIst is  Displayed", "data" => $play_list));
    }


    public function available_video_in_vod() {
        $input = $this->input->post();
        if (!$input) {
            echo json_encode(array("type" => "error", "title" => "Error!", "message" => "Content is not Available"));
            die;
        }

        $this->db->select("id,token");
        $this->db->where("id", $input['id']);
        app_permission("app_id",$this->db);
        $file_meta = $this->db->get("course_topic_file_meta_master")->row_array();

        $token = explode("_", $file_meta['token'])[2];

        $oldmask = umask(0);

        $link = $this->input->post("link");
        $target_location = getcwd() . '/uploads/encrypted_video/';

        $file_name = $input['file_name'];
        $target_location .= $file_name;
        shell_exec("ffmpeg -i $link -bsf:a aac_adtstoasc -vcodec copy -c copy -crf 50 " . $target_location);

        $encrypted_file = aes_cbc_encryption_file($target_location);

        $enc = array();
        if ($encrypted_file) {
            $enc["url"] = $this->s3_upload->upload_s3($encrypted_file, $input['id'], "file_manager/media_library/encrypted/");
            $enc["url"] = aes_cbc_encryption($enc['url'], $token);
            $enc['name'] = $file_name;
            $enc['size'] = $this->convert_filesize(filesize($encrypted_file));
            $enc['encrypt_type'] = 1;
            if ($encrypted_file)
                unlink($encrypted_file);
        }
        if (file_exists($target_location)) {
            unlink($target_location);
        }
        umask($oldmask);

        $this->db->where("id", $file_meta['id']);
        $this->db->update("course_topic_file_meta_master", array("page_count" => 1, "video_type" => 0));

        backend_log_genration($this, 'Video Mode Changed To VOD Available S.No -: ' . $file_meta['id'], 'VOD_VIDEO');
        echo json_encode(array("type" => "success", "title" => "Success", "message" => $file_name . " Video Convert Succefully To Download Mode", "data" => $enc));
    }

    function mediaconvert_tracking() {
        $input = $this->input->post();
        if (!$input) {
            echo json_encode(array("type" => "error", "title" => "Error!", "message" => "Content is not Available"));
            die;
        }
        $this->db->select("id,mediaconvert_tracking");
        $this->db->where("id", $input['id']);
        app_permission("app_id",$this->db);
        $file_meta = $this->db->get("course_topic_file_meta_master")->row_array();

        $media_convert_list = $file_meta['mediaconvert_tracking'] ? json_decode($file_meta['mediaconvert_tracking'], true) : array();

        if ($input['job_id']) {
            $job_meta = modules::run('auth_panel/live_module/media_convert/track_job', $input['job_id']);

            foreach ($media_convert_list as $key => $value) {
                if ($value['id'] == $input['job_id']) {
                    $media_convert_list[$key]['status'] = $job_meta['status'];
                    $media_convert_list[$key]['percent'] = $job_meta['percent'];
                }
            }
            $this->db->where("id", $input['id']);
            $this->db->set("mediaconvert_tracking", json_encode($media_convert_list));
            $this->db->update("course_topic_file_meta_master");
        }
        if ($media_convert_list)
            echo json_encode(array("type" => "success", "title" => "Success", "message" => "Media Convert Queues Refreshed", "data" => $media_convert_list));
        else
            echo json_encode(array("type" => "error", "title" => "Warning", "message" => "Media Convert Queues Refreshing Failed", "data" => $media_convert_list));
    }

    public function upload_video_in_vod() {
        $input = $this->input->post();
        if (!$input) {
            echo json_encode(array("type" => "error", "title" => "Error!", "message" => "Content is not Available"));
            die;
        }

        $this->db->select("id,token");
        $this->db->where("id", $input['id']);
        app_permission("app_id",$this->db);
        $file_meta = $this->db->get("course_topic_file_meta_master")->row_array();

        $token = explode("_", $file_meta['token'])[2];

        $enc["url"] = aes_cbc_encryption($input['url'], $token);
        $enc['name'] = $input['name'];
        $enc['size'] = $input['size'];
        $enc['encrypt_type'] = 3;

        $this->db->where("id", $file_meta['id']);
        $this->db->update("course_topic_file_meta_master", array("page_count" => 1));

        backend_log_genration($this, 'Video Mode Changed To VOD Available S.No -: ' . $input['id'], 'VOD_VIDEO');
        echo json_encode(array("type" => "success", "title" => "Success", "message" => $input['name'] . " Video Convert Succefully To Download Mode", "data" => $enc));
    }

    /*
     * Very Important function for take mpd
     */

    private function test() {
        $this->db->select("id,file_url,drm_dash_url,token");
        $this->db->where("file_type", 3);
        $this->db->limit(200, 2600);
        $this->db->where("drm_dash_url !=", "");
        app_permission("app_id",$this->db);
        $data = $this->db->get("course_topic_file_meta_master")->result_array();

        echo count($data);
        pre($data);
        foreach ($data as $value) {
            $token = explode("_", $value['token'])[2];

            $file_url = aes_cbc_decryption($value['file_url'], $token);

            $s3_url = AMS_BUCKET_BASE . $file_url;
            $main_file_name = explode("/", $s3_url);
            $main_file_name = str_replace(".m3u8", "", end($main_file_name));
            $s3_url = str_replace(AMS_BUCKET_NAME . ".s3." . AMS_REGION . ".amazonaws.com", S3_CLOUDFRONT_DOMAIN, $s3_url);
            exec("ffmpeg -i $s3_url 2>&1", $a, $b);
            $play_list = array();
            foreach ($a as $valu) {
                if (strpos($valu, "m3u8") && strpos($valu, "reading")) {
                    preg_match('#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', $valu, $match);
                    foreach ($match as $link) {
                        if (strpos($link, "m3u8")) {
                            $play_list[] = $link;
                        }
                    }
                }
            }
            $file_url = end($play_list);
            $file_url = explode(".net/", $file_url)[1];
            $file_url = str_replace("vod/", "vod_drm/", $file_url);
            $file_url = str_replace(".m3u8", ".mpd", $file_url);
            $enc = aes_cbc_encryption($file_url, $token);

            $this->db->where("id", $value['id']);
            $this->db->set("drm_dash_url", $enc);
            $this->db->update("course_topic_file_meta_master");
        }
        echo '<br>done';
    }

    private function disabled() {
        page_alert_box("error", "Error!", "Funcionality Disabled By Developer For Now Due To Last Moment Changes As per requirement");
        redirect_to_back();
    }

    function available_video_in_drm_protection() {
        $id = $this->input->get("id");
        if (!$id) {
            page_alert_box("error", "Error!", "Id is not Available");
            redirect_to_back();
        }
        
        $this->db->select("id,file_url,channel_id,video_type,token,playtime,mediaconvert_tracking");
        $this->db->where("id", $id);
        app_permission("app_id",$this->db);
        $file_meta = $this->db->get("course_topic_file_meta_master")->row_array();

        $token = explode("_", $file_meta['token'])[2];

        if ($file_meta['video_type'] == 5) {
            $this->db->where("id", $file_meta['channel_id']);
            $aws_channel = $this->db->get("aws_channel")->row_array();
            if ($aws_channel && $aws_channel['output_b']) {
                $this->db->where("id", $id);
                $this->db->set("drm_dash_url", aes_cbc_encryption($aws_channel['output_b'], $token));
                $this->db->update("course_topic_file_meta_master");
                page_alert_box("success", "DRM Mode Enabled", "DRM enabled");
            } else {
                page_alert_box("error", "DRM Not Available in this channel", "DRM unavailable");
            }
            redirect_to_back();
        }
        $this->disabled();

        $url = aes_cbc_decryption($file_meta['file_url'], $token);

        $s3_url = "https://" . S3_CLOUDFRONT_DOMAIN . "/" . $url;
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
        if (!$play_list) {
            page_alert_box("error", "Playlist is not available", "Cloudfront did not found playlist.");
            redirect_to_back();
        }

        $mediaconvert_job = modules::run('auth_panel/live_module/media_convert/create_job_dash', explode(".net/", end($play_list))[1], "file_manager/media_library/vod_drm/$id/", $id);
        $url = convert_normal_to_dash($url, $id);
        $url = aes_cbc_encryption($url, $token);

        $mediaconvert_tracking = $file_meta['mediaconvert_tracking'] ? json_decode($file_meta['mediaconvert_tracking']) : array();
        $mediaconvert_tracking[] = $mediaconvert_job;

        $update = array(
            "drm_dash_url" => $url,
            "mediaconvert_tracking" => json_encode($mediaconvert_tracking)
        );

        $this->db->where('id', $id);
        $this->db->update("course_topic_file_meta_master", $update);

        page_alert_box("success", "Video Operation Done", "Now onward this video will be available in VOD mode");
        backend_log_genration($this, 'Video Mode Changed To DRM VOD Available S.No -: ' . $id, 'DRM_VIDEO');
        redirect_to_back();
    }

    private function retrieve_play_list_from_vod($url, $file_id, $token) {
        $s3_url = AMS_BUCKET_BASE . $url;
        $main_file_name = explode("/", $s3_url);
        $main_file_name = str_replace(".m3u8", "", end($main_file_name));
        $s3_url = str_replace(AMS_BUCKET_NAME . ".s3." . AMS_REGION . ".amazonaws.com", S3_CLOUDFRONT_DOMAIN, $s3_url);
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
            "240b" => "240x426",
            "360b" => "360x640",
            "480b" => "480x854",
            "720b" => "720x1280"
        );

        $data = array();
        foreach ($play_list as $link) {
            $check_bit = explode("/", $link);
            $check_bit = end($check_bit);
            $check_bit = str_replace($main_file_name, "", $check_bit);
            $check_bit = explode("_", $check_bit)[0];

            $file_name = ($size_wise_names[$check_bit] ?? "") . ".mp4";

            $data[] = array(
                "name" => $file_name,
                "link" => $link
            );
        }

        return $data;
    }

    private function convert_filesize($bytes, $decimals = 2) {
        $size = array('B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$size[$factor];
    }

    private function get_concept_detail($id) {
        $this->db->where("id", $id);
        if (defined("APP_ID"))
            app_permission("app_id",$this->db);
        $data = $this->db->get("course_topic_file_meta_master")->row_array();

        $desc = "";
        if ($data['file_url']) {

             $data['file_url'] = str_replace(AMS_BUCKET_NAME . ".s3." . AMS_REGION . ".amazonaws.com", S3_CLOUDFRONT_DOMAIN, $data['file_url']);

            $data['file_url'] = str_replace(".epub", ".ws", $data['file_url']);
            $data['file_url'] = str_replace(" ", "+", $data['file_url']);
            $desc = strpos($data['file_url'], ".ws") ? @file_get_contents($data['file_url']) : "";
            if (!$desc) {
                $url = explode(".com/", $data['file_url']);
                $s3_base = $url[0] . ".com/";

                $url = $url[1];
                $url = explode("/", $url);
                array_pop($url);
                $url = implode("/", $url);

                $s3Client = new S3Client([
                    'version' => 'latest',
                    'region' => AMS_REGION,
                    'credentials' => [
                        'key' => AMS_S3_KEY,
                        'secret' => AMS_SECRET,
                    ],
                ]);
                $result = $s3Client->listObjects([
                    'Bucket' => "utkarsh-efs", // REQUIRED
                    'Prefix' => $url . "/",
                ]);

                $result = $result->toArray();

                $final_url = "";
                if (isset($result['Contents'])) {
                    foreach ($result['Contents'] as $key => $value) {
                        if (strpos($value['Key'], ".ws")) {
                            $final_url = $value['Key'];
                            break;
                        }
                    }
                    $final_url = $s3_base . $final_url;
                }

                if ($final_url && strpos($final_url, ".ws")) {
                    $this->db->where("id", $id);
                    $this->db->set("file_url", $final_url);
                    $this->db->update("course_topic_file_meta_master");
                }
                $desc = @file_get_contents($final_url);
            }

            if ($desc) {

                $url = explode("/", $data['file_url']);
                array_pop($url);
                $url = implode("/", $url);
                $desc = str_replace("../Images", $url . "/Images", $desc);
                $desc = str_replace("../Images", $url . "/extract/OEBPS/Images", $desc);
                $desc = str_replace("../Styles", $url . "/extract/OEBPS/Styles", $desc);
                $desc = str_replace("../fonts", $url . "/extract/OEBPS/fonts", $desc);
                $desc = str_replace('"Images', '"' . $url . "/extract/OEBPS/Images", $desc);
            }
        } else {
            if (!empty($data['test_json'])) {
                $lang = json_decode($data['test_json'], true);
                $desc = (array_key_exists('lang_id', $lang) && $lang['lang_id'] == "1") ? $data['description'] : $data['description_2'];
            }
        }

        return $description = $desc ? $desc : "<p>No Data Found.</p>";
    }
 
   
    private function get_jw_download_url($jw_url) {
        if (strpos($jw_url, 'https://content.jwplatform.com/media_library/') !== false) {
            $file_url = str_replace('https://content.jwplatform.com/media_library/', '', $jw_url);
            $file_url = str_replace('.mp4', '', $file_url);
            $jw_id = explode('-', $file_url)[0];

            $bitrate_url = array(
                array('title' => "240", 'url' => "https://cdn.jwplayer.com/media_library/" . $jw_id . "-notrbHqj.mp4"),
                array('title' => "360", 'url' => "https://cdn.jwplayer.com/media_library/" . $jw_id . "-7spyIB2w.mp4"),
                array('title' => "480", 'url' => "https://cdn.jwplayer.com/media_library/" . $jw_id . "-UfCB11Rv.mp4"),
                array('title' => "720", 'url' => "https://cdn.jwplayer.com/media_library/" . $jw_id . "-6UkV0qNY.mp4"),
            );
            return json_encode($bitrate_url);
        } else {
            return '';
        }
    }

    function set_video_study_json() {
        $input = $this->input->post();

        $bitrate_url = array(
            array('title' => 240, 'url' => $input['bitrate_urls'][0]),
            array('title' => 360, 'url' => $input['bitrate_urls'][1]),
            array('title' => 480, 'url' => $input['bitrate_urls'][2]),
            array('title' => 720, 'url' => $input['bitrate_urls'][3]),
        );

        $this->db->where("id", $input['id']);
        $this->db->set("is_download", 1);
        $this->db->set("bitrate_urls", json_encode($bitrate_url));
        $this->db->update("course_topic_file_meta_master");
        page_alert_box("success", "Video Updated", "Study Meta Updated Successfully");
        redirect_to_back();
    }

    public function add_audio() {
        if ($this->input->post()) {
            $user_data = $this->session->userdata('active_user_data');
            $backend_user_id = $user_data->id;

            $this->form_validation->set_rules('title', 'File Title', 'required');
            $this->form_validation->set_rules('description', 'Description', 'required');
            $this->form_validation->set_rules('subject_id', 'Subject', 'required');
            $this->form_validation->set_rules('topic_id', 'Topic', 'required');
            $this->form_validation->set_rules('course_id[]', 'Topic', 'required');

            if (!empty($_FILES['image_file']['name'])) {
                if ($_FILES['image_file']['size'] > 1048576) {
                    page_alert_box("error", "Add Video PDF", "Audio file doesn't excced size more than 1 MB.");
                    redirect_to_back();
                }
            } else {
                $this->form_validation->set_rules('image_file', 'Audio', 'required');
            }

            if ($this->form_validation->run() == FALSE) {
                $error = $this->form_validation->get_all_errors();
                page_alert_box("error", "Add Audio", array_values($error)[0]);
            } else {
                $course_links = "";
                $course_ids = $this->input->post("course_id[]") ? implode(",", $this->input->post("course_id[]")) : array();
                if ($course_ids)
                    $course_links = $this->Library_model->get_course_names($this->input->post("course_id[]"));
                $insert_data = array(
                    'title' => $this->input->post('title'),
                    'file_url' => $this->input->post('file_url'),
                    'description' => $this->input->post('description'),
                    'subject_id' => $this->input->post('subject_id'),
                    'course_ids' => $course_ids,
                    'course_names' => $course_links,
                    'topic_id' => $this->input->post('topic_id'),
                    'file_type' => 10,
                    'created' => time(),
                    'backend_user_id' => $backend_user_id,
                    'app_id'  => (defined("APP_ID") ? "" . APP_ID . "" : "0")
                );


                $this->db->insert('course_topic_file_meta_master', $insert_data);
                $insert_id = $this->db->insert_id();
                if (!empty($_FILES['image_file']['name'])) {
                    $file = amazon_s3_upload($_FILES['image_file'], "file_manager/audio", $insert_id);
                } else {
                    $file = '';
                }

                $this->db->where("id", $insert_id);
                $this->db->set("file_url", $file);
                $this->db->update("course_topic_file_meta_master");
                if (!empty($this->input->post("attach_course_id"))) {
                    $_POST['file_id'] = $insert_id;
                    $attach_video = $this->attach_video_to_course();
                }
                page_alert_box('success', 'Action performed', 'File added successfully');
            }
        }

        $view_data['page'] = 'add_audio';
        $data['page_title'] = "Add Audio";
        $view_data['breadcrum']=array('Audio'=>"file_manager/library/add_audio");
        $data['page_data'] = $this->load->view('file_manager/add_audio', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function upload_text_file_to_s3($content, $id) {
        file_put_contents('uploads/notes.ws', $content, FILE_APPEND);
        $target_location = getcwd() . '/uploads/notes.ws';
        if ($target_location) {
            $this->load->library('s3_upload');
            $url = $this->s3_upload->upload_s3_file($target_location, ADMIN_VERSION . "/file_manager/notes/$id", $id);

            if (file_exists($target_location))
                unlink($target_location);
            return $url;
        }
    }

    public function ajax_image_file_list() {
        // storing  request (ie, get/post) global array to a variable
        $requestData = $_REQUEST;
        $user_data = $this->session->userdata('active_user_data');
        $instructor_id = $user_data->instructor_id;
        $backend_user_id = $user_data->id;
        $where = "";
        if ($instructor_id != 0) {
            $where = "AND ctfmm.backend_user_id = $backend_user_id";
        }
        $where .=  (defined("APP_ID") ? "" . app_permission("ctfmm.app_id") . "" : "");

        $columns = array(
        // datatable column index  => database column name
            0 => 'ctfmm.id',
            1 => 'ctfmm.title',
            2 => 'ctfmm.course_names',
            3 => 'csm.name',
            4 => 'cstm.topic',
            5 => 'ctfmm.thumbnail_url',
            6 => 'bu.username',
            7 => 'ctfmm.created'
        );

        $query = "SELECT count(ctfmm.id) as total FROM course_topic_file_meta_master ctfmm where ctfmm.file_type =6 $where ";
        $query = $this->db->query($query)->row_array();
        $totalData = (count($query) > 0) ? $query['total'] : 0;
        $totalFiltered = $totalData;

        $sql = "SELECT ctfmm.id as id,ctfmm.title as title ,ctfmm.file_url as URL, csm.name as subject,cstm.topic as topic,ctfmm.course_names,ctfmm.subject_id,ctfmm.topic_id,ctfmm.created,bu.username
                FROM course_topic_file_meta_master as  ctfmm
                join course_subject_master as csm on ctfmm.subject_id = csm.id
                join course_subject_topic_master as cstm on ctfmm.topic_id = cstm.id
                left join backend_user bu on bu.id = ctfmm.backend_user_id
                where  ctfmm.file_type = 6 $where ";

        // getting records as per search parameters
        if (!empty($requestData['columns'][0]['search']['value'])) {

            $sql .= " AND ctfmm.id LIKE '" . $requestData['columns'][0]['search']['value'] . "%' ";
        }
        if (!empty($requestData['columns'][1]['search']['value'])) {
            $sql .= " AND ctfmm.title LIKE '" . $requestData['columns'][1]['search']['value'] . "%' ";
        }
        if (!empty($requestData['columns'][2]['search']['value'])) {
            $sql .= " AND ctfmm.course_names LIKE '%" . $requestData['columns'][2]['search']['value'] . "%' ";
        }
        if (!empty($requestData['columns'][3]['search']['value'])) {
            $sql .= " AND csm.name LIKE '" . $requestData['columns'][3]['search']['value'] . "%' ";
        }
        if (!empty($requestData['columns'][4]['search']['value'])) {
            $sql .= " AND cstm.topic LIKE '" . $requestData['columns'][4]['search']['value'] . "%' ";
        }

        if (!empty($requestData['columns'][5]['search']['value'])) {
            $sql .= " AND bu.username LIKE '" . $requestData['columns'][5]['search']['value'] . "%' ";
        }

        $query = $this->db->query($sql)->result();

        $totalFiltered = count($query); // when there is a search parameter then we have to modify total number filtered rows as per search result.

        $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   "; // adding length


        $result = $this->db->query($sql)->result();
        $data = array();
        foreach ($result as $r) {
            $nestedData = array();
            $nestedData[] = $r->id;
            $nestedData[] = "<a class='bold' href='" . AUTH_PANEL_URL . "file_manager/library/edit_image_library/" . $r->id . "' title='Edit Pdf' target='__blank'>" . $r->title . "</a>";
            $nestedData[] = $r->course_names;
            $nestedData[] = $r->subject . "[ID : {$r->subject_id}]";
            $nestedData[] = $r->topic . "[ID : {$r->topic_id}]";
            $nestedData[] = "<img width ='60px' src= " . $r->URL . ">";
            $nestedData[] = $r->username ? $r->username : '--NA--';
            $nestedData[] = $r->created ? get_time_format($r->created) : '--NA--';
            $nestedData[] = "<a class='btn btn-info btn-xs bold' href='" . $r->URL . "' title='View Image' target='__blank' ><i class='fa fa-eye'></a>";
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


    public function ajax_audio_file_list() {
        // storing  request (ie, get/post) global array to a variable
        $requestData = $_REQUEST;
        $user_data = $this->session->userdata('active_user_data');
        $instructor_id = $user_data->instructor_id;
        $backend_user_id = $user_data->id;
        $where = "";
        if ($instructor_id != 0) {
            $where = "AND ctfmm.backend_user_id = $backend_user_id";
        }
        $where .=  (defined("APP_ID") ? "" . app_permission("ctfmm.app_id") . "" : "");

        $columns = array(
        // datatable column index  => database column name
            0 => 'ctfmm.id',
            1 => 'ctfmm.title',
            2 => 'ctfmm.course_names',
            3 => 'csm.name',
            4 => 'cstm.topic',
            5 => 'ctfmm.thumbnail_url',
            6 => 'bu.username',
            7 => 'ctfmm.created'
        );

        $query = "SELECT count(ctfmm.id) as total FROM course_topic_file_meta_master ctfmm where ctfmm.file_type =10 $where ";
        $query = $this->db->query($query)->row_array();
        $totalData = (count($query) > 0) ? $query['total'] : 0;
        $totalFiltered = $totalData;

        $sql = "SELECT ctfmm.id as id,ctfmm.title as title ,ctfmm.file_url as URL, csm.name as subject,cstm.topic as topic,ctfmm.course_names,ctfmm.subject_id,ctfmm.topic_id,ctfmm.created,bu.username
                FROM course_topic_file_meta_master as  ctfmm
                join course_subject_master as csm on ctfmm.subject_id = csm.id
                join course_subject_topic_master as cstm on ctfmm.topic_id = cstm.id
                left join backend_user bu on bu.id = ctfmm.backend_user_id
                where  ctfmm.file_type = 10 $where ";

        // getting records as per search parameters
        if (!empty($requestData['columns'][0]['search']['value'])) {

            $sql .= " AND ctfmm.id LIKE '" . $requestData['columns'][0]['search']['value'] . "%' ";
        }
        if (!empty($requestData['columns'][1]['search']['value'])) {
            $sql .= " AND ctfmm.title LIKE '" . $requestData['columns'][1]['search']['value'] . "%' ";
        }
        if (!empty($requestData['columns'][2]['search']['value'])) {
            $sql .= " AND ctfmm.course_names LIKE '%" . $requestData['columns'][2]['search']['value'] . "%' ";
        }
        if (!empty($requestData['columns'][3]['search']['value'])) {
            $sql .= " AND csm.name LIKE '" . $requestData['columns'][3]['search']['value'] . "%' ";
        }
        if (!empty($requestData['columns'][4]['search']['value'])) {
            $sql .= " AND cstm.topic LIKE '" . $requestData['columns'][4]['search']['value'] . "%' ";
        }

        if (!empty($requestData['columns'][5]['search']['value'])) {
            $sql .= " AND bu.username LIKE '" . $requestData['columns'][5]['search']['value'] . "%' ";
        }
        if (!empty($requestData['columns'][7]['search']['value'])) {
            $sql .= " AND ctfmm.created LIKE '" . $requestData['columns'][7]['search']['value'] . "%' ";
        }

        $query = $this->db->query($sql)->result();

        $totalFiltered = count($query); // when there is a search parameter then we have to modify total number filtered rows as per search result.

        $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   "; // adding length


        $result = $this->db->query($sql)->result();
        $data = array();
        foreach ($result as $r) {
            $nestedData = array();
            $nestedData[] = $r->id;
            $nestedData[] = "<a class='bold' href='" . AUTH_PANEL_URL . "file_manager/library/edit_image_library/" . $r->id . "' title='Edit Pdf' target='__blank'>" . $r->title . "</a>";
            $nestedData[] = $r->course_names;
            $nestedData[] = $r->subject . "[ID : {$r->subject_id}]";
            $nestedData[] = $r->topic . "[ID : {$r->topic_id}]";
            $nestedData[] = "<img width ='60px' src= " . $r->URL . ">";
            $nestedData[] = $r->username ? $r->username : '--NA--';
            $nestedData[] = $r->created ? get_time_format($r->created) : '--NA--';
            $nestedData[] = "<a class='btn btn-info btn-xs bold' href='" . $r->URL . "' title='View Image' target='__blank' ><i class='fa fa-eye'></a>";
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


    public function edit_video_library($id) {
        $view_data['video_detail'] = $this->Library_model->get_library_file_by_id($id);
        // echo "<pre>";print_r($view_data['video_detail']);die;
        if(!empty($view_data)){       
            $test_json = json_decode($view_data['video_detail']['test_json'], true);
            if (!$test_json)
                $test_json = array();
            if ($this->input->post()) {
                $this->form_validation->set_rules('title', 'File Title', 'required');
                $this->form_validation->set_rules('playtime', 'Playtime', 'required');
                $this->form_validation->run();
                if ($errors = $this->form_validation->get_all_errors()) {
                    page_alert_box("error", "Validation error", array_values($errors)[0]);
                    redirect(AUTH_PANEL_URL.'/file_manager/library/edit_video_library/'.$id);
                } else {
                    $insert_id = $id;
                    $job_meta = array();
                    $video_type = $this->input->post("video_type");
                    $file = $thumbnail = $drm_dash_url = $poster_url = "";
                    $drm_hls_url = "";
                    if (!empty($_FILES['video_file']['name']) || $this->input->post("custom_s3_url")) {
                        $url = $this->input->post("custom_s3_url");
                        //print_r($url);die;
                        if ($url) {
                            $file = explode(".com/", $url)[1];
                        } else if (!empty($_FILES['video_file']['name'])) {
                            $time = time();
        $target_location = getcwd() . '/uploads/bitrate';//1920*1280
        $original = Upload("video_file", $target_location . "/", $time . "_1920x1280");
        if ($original) {
            $file = $this->s3_upload->upload_s3($target_location . '/' . $original, $id, "file_manager/media_library/original_direct/");
             $file=str_replace( 'https://'.$this->CHANG_BUCKET_KEY . ".s3." . $this->CHANG_REGION . ".amazonaws.com",$this->CHANG_CLOUDFRONT, $file);

            if ($original)
                unlink($original);
                }
            }
        }
        //  else if ($this->input->post('video_file')) {
        //     $file = $this->input->post('video_file');
        // }

       if ($this->input->post('video_file_tail')) {
            $video_file_tail = $this->input->post('video_file_tail');
        }
        if (!empty($_FILES['thumbnail']['name'])) {
            $thumbnail = amazon_s3_upload($_FILES['thumbnail'], "file_manager/media_library", $id);
             $thumbnail=str_replace( 'https://'.$this->CHANG_BUCKET_KEY . ".s3." . $this->CHANG_REGION . ".amazonaws.com",$this->CHANG_CLOUDFRONT, $thumbnail);
        }         
         if (!empty($_FILES['poster']['name'])) {
            $poster_url = amazon_s3_upload($_FILES['poster'], "file_manager/media_library", $id);
            $poster_url=str_replace( 'https://'.$this->CHANG_BUCKET_KEY . ".s3." . $this->CHANG_REGION . ".amazonaws.com",$this->CHANG_CLOUDFRONT, $poster_url);
        }
        if ($studio_id = $this->input->post("studio_id"))
            $test_json["studio_id"] = $studio_id;
        }
        if(($this->input->post("video_type"))!= '1' && ($this->input->post("video_type"))!= '8' ){
            if ($this->input->post("published_date")=='') {
            
                page_alert_box("error", "Release Date", "Please Add Release date");
                redirect_to_back();
         
        }
        }
       
        if(($this->input->post("published_date"))>date("Y-m-d")){
            page_alert_box("error", "Invalid Release Date", "Please Select a Valid release date");
            redirect_to_back();

        }
        $genres_type = "";
        if(!empty($this->input->post("genres_type_general"))){
            $genres_type = $this->input->post("genres_type_general");
        }
           
        // if(!empty($this->input->post("genres_type_video")))
        // {
        //     $genres_type = $this->input->post("genres_type_video");
        // }
          
        // if(!empty($this->input->post("genres_type_webseries")))
        // {
        //     $genres_type = $this->input->post("genres_type_webseries");
        // }
            
            $artists_type = $this->input->post("artists_type[]") ? implode(",", $this->input->post("artists_type[]")) : '';

              $paid_status = $this->input->post("movie_view");
                if($paid_status =="1"){
                    $ppv_status ="1";
                }else{
                    $ppv_status = "0";
                }    
             $app_id = (defined("APP_ID") && APP_ID)?APP_ID:0; 
                    $this->db->select('category_type');
                    $this->db->where('id', $this->input->post("cate_type"));
                    $this->db->where('app_id', $app_id);      
                    $category_type = $this->db->get("categories")->result_array();        
            $update_data = array(
            'title' => $this->input->post('title'),
            'description' => $this->input->post('description'),
            'description_2' => $this->input->post('description_2') ?? "",
            'topic_id' => $this->input->post('topic_id'),
            'video_type' => $video_type,
            'page_count' => $this->input->post("open_with"),
            'is_download' => $this->input->post('is_download'),
            'multiplayer' => $this->input->post("multiplayer"),
            'published_date' => strtotime($this->input->post("published_date")),
             'view_mode' => $this->input->post("movie_view"),
             'category' => $this->input->post("cate_type"),
             'category_type' => $category_type['0']['category_type']??0,
             'published_date'=>strtotime($this->input->post("published_date")),
             'transcribe'=>$this->input->post("transcribe"),
             'ppv_status' => $ppv_status,
             'ppv' => $this->input->post('ppv')??0,
             'skip_intro' => $this->input->post('skip_intro')??0,
             'skip_time' => $this->input->post('skip_time')??0,
             'attach_video'=>($this->input->post('skip_intr'))?$this->input->post('skip_intr'):0,
             'channel_id' => $this->input->post('channels')??0,
             'artists_type' => $artists_type,
             'aggregator_id' => $this->input->post('aggregator'),
            'genres_type' => ($genres_type != null || $genres_type == "" )? $genres_type : "0",   
            'file_type' => 3,
            'is_drm_protected'=>$this->input->post('is_drm_protected')??0,
            'drm_dash_url'=> $this->input->post('drm_dash_url')??"",
            'drm_hls_url'=> $this->input->post('drm_hls_url')??"",
            'test_json' => json_encode($test_json),
            'extra_params' => json_encode(array("demo_percent" => $this->input->post('demo_percent'),"video_type_file" => $this->input->post("video_type_file"),"videotoken" => $this->input->post("videotoken")??"0", "feedback_video" => $this->input->post('feedback_video')??"0", "floating_number" => $this->input->post('floating_number'),"vod_chat" => $this->input->post('vod_chat')??"0" ,"is_limited"=>$this->input->post('video_limit')))
        );


        if($video_type == 7 || $video_type ==8)
        {
        if ($this->input->post('video_file')) {
                $file = $this->input->post('video_file');
            }
            
        }
        
        elseif($video_type == 10)
        {
            if(($this->input->post("fast_channels"))!= ''){
                $this->db->select('playback_url');
                $this->db->where('ch_id',$this->input->post("fast_channels"));
              $hls_url1 = $this->db->get("fast_channel")->row_array(); 
              $file = $hls_url1['playback_url'];
              $update_data['fast_id'] = $this->input->post("fast_channels");
              if($hls_url1['channel_state'] == 'STOPPED')
              {
                $update_data['data_live'] =  0;
              }
              else
              {
                $update_data['data_live'] =  0;
              }

            
            }
        }
        else{

        }
        //for live video update start date
             if ($file)
                $update_data['file_url'] = $file; //aes_cbc_encryption($file, $token);
            if ($thumbnail)
                $update_data['thumbnail_url'] = $thumbnail;
            if ($poster_url)
                $update_data['poster_url'] = $poster_url;
            if ($drm_dash_url)
                $update_data['drm_dash_url'] = $drm_dash_url;
            if(!empty($video_file_tail)){
                $update_data['video_file_tail'] = $video_file_tail;
                $update_data['vdc_tail_id'] = $this->input->post('videocript_tail_id');
              //  $update_data['playtime_tail'] = $this->input->post('playtime_tail');
            }
              
                 //aes_cbc_encryption($drm_dash_url, $token);
            if ($job_meta)
                $update_data['mediaconvert_tracking'] = json_encode(array($job_meta));

                if ($video_type == 6) {//jw
                    $update_data['bitrate_urls'] = $this->get_jw_download_url($file);
                    $update_data['is_download'] = 1;
                }

        if($video_type==7){
            $videocrypt=$this->fetch_videocrypt_playlist($this->input->post('videocript_id'),'array');
            if($videocrypt['result']==1){
                if($this->input->post('play_via')==0){
                    $update_data['file_url']=$videocrypt['data']['file_url_hls'];
                }
                if($this->input->post('play_via')==1){
                    $update_data['file_url']=$videocrypt['data']['file_url_dash'];
                }
                if($this->input->post('is_drm_protected')==1){                          
                    $update_data['is_drm_protected']=$this->input->post('is_drm_protected');
                    $update_data['drm_dash_url']=$videocrypt['data']['drm_dash_url'];
                    $update_data['drm_hls_url']=$videocrypt['data']['drm_hls_url'];
                }
                if($videocrypt['data']['download_url']!=""){
                    $update_data['bitrate_urls']=json_encode($videocrypt['data']['download_url']);
                }
                $update_data['vdc_id']=$videocrypt['data']['id'];
                //Akhilesh start//************************ */
               if(!empty($videocrypt['data']['vod_vtt']) && !empty($videocrypt['data']['vod_srt'])){                        
                   $update_data['vod_vtt_url']=$videocrypt['data']['vod_vtt'];
                   $update_data['vod_srt_url']=$videocrypt['data']['vod_srt'];
               }
                //akhilesh end
                $update_data['vdc_json']=json_encode($videocrypt['data']);
                sscanf($videocrypt['data']['duration'], "%d:%d:%d", $hours, $minutes, $seconds);
                $update_data['playtime']  = isset($seconds) ? $hours * 3600 + $minutes * 60 + $seconds : $hours * 60 + $minutes;
            }else{
                page_alert_box('error', 'Error', 'Please try again with valid video Id.');
                redirect_to_back();
            }

            if(!empty($this->input->post('videocript_tail_id'))){
                    $videocrypt_tail=$this->fetch_videocrypt_playlist($this->input->post('videocript_tail_id'),'array');
                
                if($videocrypt_tail['result']==1){
                    if($this->input->post('play_via')==0){
                       $update_data['video_file_tail']=$videocrypt_tail['data']['file_url_hls'];
                    }
                    if($this->input->post('play_via')==1){
                        $update_data['video_file_tail']=$videocrypt_tail['data']['file_url_dash'];
                    }                    
                 
                    $update_data['vdc_tail_id']=$videocrypt_tail['data']['id']; if(!empty($videocrypt['data']['vod_vtt']) && !empty($videocrypt['data']['vod_srt'])){                        
                        $update_data['vod_vtt_url']=$videocrypt['data']['vod_vtt'];
                        $update_data['vod_srt_url']=$videocrypt['data']['vod_srt'];
                    }
                    //-----akhilesh start-----
                     if(!empty($videocrypt['data']['vod_vtt']) && !empty($videocrypt['data']['vod_srt'])){                        
                        $update_data['vod_vtt_url']=$videocrypt['data']['vod_vtt'];
                        $update_data['vod_srt_url']=$videocrypt['data']['vod_srt'];
                    }
                    //------akhilesh end----
                    $update_data['vdc_tail_json']=json_encode($videocrypt_tail['data']);
                    sscanf($videocrypt_tail['data']['duration'], "%d:%d:%d", $hours, $minutes, $seconds);
                    $update_data['playtime_tail']  = isset($seconds) ? $hours * 3600 + $minutes * 60 + $seconds : $hours * 60 + $minutes;
                }else{
                     page_alert_box('error', 'Error', 'Please try again with valid video Id.');
                     redirect_to_back();
                }
            }

        }
        // else{
        //     $update_data['playtime'] =($this->input->post('playtime')?($this->input->post('playtime') * 60) : 0);
        // }
       // echo "<pre>";print_r($update_data);die;
        $this->db->where('id', $insert_id);
        $this->db->update('course_topic_file_meta_master', $update_data);   
       //--update version start by ak--
        if ($this->db->affected_rows() > 0) {         
              update_api_version_new($this->db, 'dashboard',$insert_id);
             update_api_version_new($this->db, 'detail_page',$insert_id);
         }
        //--update version end--  
        page_alert_box('success', 'Action performed', 'File updated successfully');
        backend_log_genration($this, 'Video Updated S.No -: ' . $insert_id, 'EDIT_VIDEO');
        redirect_to_back();
    }

    $data['page_title'] = "Edit Video ";
    app_permission("app_id",$this->db);
    $view_data['polls'] = $this->db->get_where('course_topic_file_poll', array('video_id' => $id))->result();
    app_permission("app_id",$this->db);
    $view_data['studio_list'] = $this->db->select("id,name")->get_where("studio_management", array("status" => 1))->result_array();
    app_permission("app_id",$this->db);
    // $view_data['channels'] = $this->db->select("
    //     id,channel_name,studio_id")->get_where('aws_channel')->result_array();
    
    $courseids = explode(",", $view_data['video_detail']['course_ids']);
    app_permission("app_id",$this->db);
   
    // $view_data['video_pdf_list'] = $this->Library_model->get_video_pdf_list($view_data['video_detail']['id']);
    app_permission("app_id",$this->db);
    $f_list = $this->db->get("application_meta")->result_array();
    $view_data['f_lists'] = json_decode($f_list[0]['functionality']);
    $view_data['breadcrum']=array('media_library'=>"file_manager/library/add_video",$view_data['video_detail']['title']=>'');
    //----new added view data from add start------
            $app_id = $_SESSION['active_user_data']->app_id;
        $view_data['genres'] = $this->Movies_model->get_category();
        $view_data['artists'] = $this->guru_model->get_guru_list();
        $view_data['agg_detail'] = $this->Category_model->get_aggregator_list();
        $view_data['categories'] = $this->db->get('categories')->result_array();
        $view_data['seasons'] = $this->Premium_video_model->get_season_name();
        $view_data['web_genres'] = $this->Premium_video_model->get_sub_category(); 
        $view_data['web_categories'] = $this->Premium_video_model->get_categories();
        $view_data['web_authors'] = $this->Premium_video_model->get_authors();
        $view_data['web_premium_plan'] = $this->Premium_video_model->get_plans();

        $view_data['tv_categories'] = $this->Movies_model->get_categories();
        $view_data['tv_sub_caegories'] = $this->Movies_model->get_sub_category();
        $view_data['tv_authors'] = $this->Tv_serial_model->get_authors();
        $view_data['tv_premium_plan'] = $this->Tv_serial_model->get_plans();
       
        $view_data['video_guru'] = $this->guru_model->get_guru_list();
       // $view_data['video_artist'] = $this->Artist_control_model->get_artist();
        $view_data['video_album'] = $this->Album_control_model->get_albums();
        $view_data['video_plans'] = $this->Premium_video_model->get_plans();
        
        $this->db->select('fast_channel.ch_id ,fast_channel.playback_url,fast_channel.name');
        $view_data['fast_channel'] = $this->db->get('fast_channel')->result_array();

           app_permission("app_id",$this->db);
        $this->db->select('aws_channel.id,CONCAT(channel_name," (",aws_channel.channel_id,")") as name,aws_channel.output_a as output,output_b,output_c,aws_channel.studio_id');
        $view_data['channels'] = $this->db->get('aws_channel')->result_array();
       
    //--new addded view data from add end-------
    $data['page_data'] = $this->load->view('file_manager/edit_video', $view_data, TRUE);

    echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
}

}

    public function edit_audio_library($id) {
        if ($this->input->post()) {
            $this->form_validation->set_rules('title', 'File Title', 'required');
            $this->form_validation->set_rules('description', 'Description', 'required');
            $this->form_validation->set_rules('subject_id', 'Subject', 'required');
            $this->form_validation->set_rules('topic_id', 'Topic', 'required');
            $this->form_validation->set_rules('course_id[]', 'Topic', 'required');

            if (!empty($_FILES['image_file']['name']) && $_FILES['image_file']['size'] > 1048576) {
                page_alert_box("error", "Add Video PDF", "Audio file doesn't excced size more than 1 MB.");
                redirect_to_back();
            }

            if ($this->form_validation->run() == FALSE) {
                $error = $this->form_validation->get_all_errors();
                page_alert_box("error", "Edit Image", array_values($error)[0]);
            } else {
                $course_links = "";
                $course_ids = $this->input->post("course_id[]") ? implode(",", $this->input->post("course_id[]")) : array();
                if ($course_ids)
                    $course_links = $this->Library_model->get_course_names($this->input->post("course_id[]"));
                $update_data = array(
                    'title' => $this->input->post('title'),
                    'description' => $this->input->post('description'),
                    'subject_id' => $this->input->post('subject_id'),
                    'course_ids' => $course_ids,
                    'course_names' => $course_links,
                    'topic_id' => $this->input->post('topic_id'),
                    'file_type' => 10,
                );
                if (!empty($_FILES['image_file']['name'])) {
                    $file = amazon_s3_upload($_FILES['image_file'], "file_manager/audio", $id);
                    $update_data['file_url'] = $file;
                }

                $file_id = $this->input->post('id');

                $this->db->where('id', $file_id);
                $this->db->update('course_topic_file_meta_master', $update_data);
                //update in course_segment_element
                if ($this->db->affected_rows() > 0) {
                    $this->db->where('type !=', 'test');
                    $this->db->where('element_fk', $file_id);
                    $this->db->update('course_segment_element', array('l1_id' => $update_data['subject_id'], 'l2_id' => $update_data['topic_id']));
                }
                backend_log_genration(
                        $this,
                        "Audio file (ID : {$file_id}) has been added successfully",
                        "Add Audio"
                );
                page_alert_box('success', 'Action performed', 'File updated successfully');
            }
        }
        $view_data['page'] = 'edit_audio';
        $data['page_title'] = "Edit Audio ";
        $view_data['video_detail'] = $this->Library_model->get_library_file_by_id($id);
        $courseids = explode(",", $view_data['video_detail']['course_ids']);
        app_permission("app_id",$this->db);
        $view_data['course_list'] = $this->db->select("id,title")->where_in("id", $courseids)->get_where("course_master", array("state" => 0))->result();
        $view_data['course_attached_detail'] = $this->Library_model->get_course_attached_detail($id, "audio");
        
        if(isset($view_data['link_detail']['title'])){ 
        $view_data['breadcrum']=array('Audio'=>"file_manager/library/add_audio",$view_data['link_detail']['title']=>'');
        } else {
        $view_data['breadcrum']=array('Audio'=>"file_manager/library/add_audio");


        }

        $data['page_data'] = $this->load->view('file_manager/edit_audio', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function edit_image_library($id) {
        if ($this->input->post()) {
            $this->form_validation->set_rules('title', 'File Title', 'required');
            $this->form_validation->set_rules('description', 'Description', 'required');
            $this->form_validation->set_rules('subject_id', 'Subject', 'required');
            $this->form_validation->set_rules('topic_id', 'Topic', 'required');
            $this->form_validation->set_rules('course_id[]', 'Topic', 'required');

            if (!empty($_FILES['image_file']['name']) && $_FILES['image_file']['size'] > 1048576) {
                page_alert_box("error", "Add Video PDF", "Image file doesn't excced size more than 1 MB.");
                redirect_to_back();
            }

            if ($this->form_validation->run() == FALSE) {
                $error = $this->form_validation->get_all_errors();
                page_alert_box("error", "Edit Image", array_values($error)[0]);
            } else {
                $course_links = "";
                $course_ids = $this->input->post("course_id[]") ? implode(",", $this->input->post("course_id[]")) : array();
                if ($course_ids)
                    $course_links = $this->Library_model->get_course_names($this->input->post("course_id[]"));
                $update_data = array(
                    'title' => $this->input->post('title'),
                    'description' => $this->input->post('description'),
                    'subject_id' => $this->input->post('subject_id'),
                    'course_ids' => $course_ids,
                    'course_names' => $course_links,
                    'topic_id' => $this->input->post('topic_id'),
                    'file_type' => 6,
                );
                if (!empty($_FILES['image_file']['name'])) {
                    $file = amazon_s3_upload($_FILES['image_file'], "file_manager/image", $id);
                    $update_data['file_url'] = $file;
                }

                $file_id = $this->input->post('id');

                $this->db->where('id', $file_id);
                $this->db->update('course_topic_file_meta_master', $update_data);
                //update in course_segment_element
                if ($this->db->affected_rows() > 0) {
                    $this->db->where('type !=', 'test');
                    $this->db->where('element_fk', $file_id);
                    $this->db->update('course_segment_element', array('l1_id' => $update_data['subject_id'], 'l2_id' => $update_data['topic_id']));
                }
                backend_log_genration(
                        $this,
                        "Image file (ID : {$file_id}) has been added successfully",
                        "Add Image"
                );
                page_alert_box('success', 'Action performed', 'File updated successfully');
            }
        }
        $view_data['page'] = 'edit_image';
        $data['page_title'] = "Edit Image ";
        $view_data['video_detail'] = $this->Library_model->get_library_file_by_id($id);
        $courseids = explode(",", $view_data['video_detail']['course_ids']);
        app_permission("app_id",$this->db);
        $view_data['course_list'] = $this->db->select("id,title")->where_in("id", $courseids)->get_where("course_master", array("state" => 0))->result();
        $view_data['course_attached_detail'] = $this->Library_model->get_course_attached_detail($id, "image");
        
        if(isset($view_data['link_detail']['title'])){ 
        $view_data['breadcrum']=array('Image'=>"file_manager/library/add_image",$view_data['link_detail']['title']=>'');
        } else {
        $view_data['breadcrum']=array('Image'=>"file_manager/library/add_image");


        }

        $data['page_data'] = $this->load->view('file_manager/edit_image', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function set_video_frames() {
        $array = array(
            'v_fk' => $this->input->post('v_fk'),
            'time' => $this->input->post('hours') . ":" . $this->input->post('minutes') . ":" . $this->input->post('seconds'),
            'info' => $this->input->post('info'),
        );
        $this->db->insert('video_lib_time_access', $array);
        redirect(AUTH_PANEL_URL . "file_manager/library/edit_video_library/" . $this->input->post('v_fk'));
    }

    public function video_addvertisement(){
        if($this->input->post()){
        $addvertise_data = array(
            'v_fk' => $this->input->post('v_fk'),
            'time' => $this->input->post('hours') . ":" . $this->input->post('minutes') . ":" . $this->input->post('seconds'),
            'course_id' => $this->input->post('course_id') ? $this->input->post('course_id') : "",
            'title' => $this->input->post('title'),
            'duration' => $this->input->post('duration'),
            'type' => $this->input->post('select_type'),
            'skipable' => $this->input->post('skipable'),
            'url' => $this->input->post('url'),
            'description' => $this->input->post('description'),
            'app_id' => APP_ID

        );

        if ($_FILES['add_thumbnail']['name']) {
                    $file = amazon_s3_upload($_FILES['add_thumbnail'], "file_manager/addvertise_image", $id);
                    $addvertise_data['thumbnail'] = $file;
        }
        $this->db->insert('video_addvertisement', $addvertise_data);
        page_alert_box("success", "Addvertisement", "Added successfully");
    }
        redirect(AUTH_PANEL_URL . "file_manager/library/edit_video_library/" . $this->input->post('v_fk'));
    }

    public function delete_video_addvertisement() {
        $this->db->where("v_fk", $this->input->get('v_fk'));
        $this->db->where("id", $this->input->get('id'));
        $this->db->delete('video_addvertisement');
        redirect(AUTH_PANEL_URL . "file_manager/library/edit_video_library/" . $this->input->get('v_fk'));
    }

    public function delete_video_frames() {
        $this->db->where("v_fk", $this->input->get('v_fk'));
        $this->db->where("id", $this->input->get('id'));
        $this->db->delete('video_lib_time_access');
        redirect(AUTH_PANEL_URL . "file_manager/library/edit_video_library/" . $this->input->get('v_fk'));
    }

    public function get_download_url() {
        $file_type = $this->input->post('file_type');
        $is_download = $this->input->post('is_download') ? 1 : 0;
        $file_url = $this->input->post('file_url');
        if ($is_download == 1) {
            $this->available_video_in_download();
            die;
        }
        if ($file_type == 6) {
            if (strpos($file_url, 'https://content.jwplatform.com/media_library/') !== false) {
                $file_url = str_replace('https://content.jwplatform.com/media_library/', '', $file_url);
                $file_url = str_replace('.mp4', '', $file_url);
                $file_url = explode('-', $file_url)[0];
            }
            $result = file_get_contents("https://cdn.jwplayer.com/v2/media/" . $file_url);
            $result = json_decode($result, true);
            if (isset($result['playlist']) && $result['playlist'][0]['sources']) {
                $sources = $result['playlist'][0]['sources'];
                $urls = array();
                if ($sources) {
                    foreach ($sources as $s) {
                        if ($s['type'] == "video/mp4") {
                            $urls[] = $s['file'];
                        }
                    }
                }
                echo json_encode(array('status' => 1, 'message' => 'Download Url Fetched Successfully', 'data' => $urls));
            } else {
                echo json_encode(array('status' => 2, 'message' => 'JW File Url Is Not Valid', 'data' => array()));
            }
        } else if ($file_type == 0) {
            exec("ffmpeg -i $file_url 2>&1", $a, $b);

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
                    "link" => $link,
                    "size" => 0
                );
            }

            echo json_encode(array('status' => 1, 'message' => 'Download Url Fetched Successfully', 'data' => $data));
        } else {
            echo json_encode(array('status' => 2, 'message' => 'Your request is not valid.', 'data' => array()));
        }
    }

    public function available_video_in_download() {
        $input = $this->input->post();
        $input['link'] = str_replace('https://utk-media.s3.ap-south-1.amazonaws.com', 'https://dhi119srci8li.cloudfront.net', $input['link']);
        $target_location = getcwd() . '/uploads/harvest_video/';
        ini_set('memory_limit', '-1');
        $video_id = $input['id'];
        $file_name = $video_id . '.mp4';
        $target_location .= $file_name;
        $m3u8_url = $input['link'];
        shell_exec("ffmpeg -i $m3u8_url -bsf:a aac_adtstoasc -vcodec copy -c copy -crf 50 " . $target_location);

        $enc = array();
        if ($target_location) {
            $this->load->library('s3_upload');
            $mp4_url = $this->s3_upload->upload_s3($target_location, "file_library/media_library/harvesting/raw_hq/$video_id/", $video_id);
            $size = $this->convert_filesize(filesize($target_location));
            if (file_exists($target_location))
                unlink($target_location);
        }

        $bitrate_url = array(
            array('title' => "240", 'url' => strpos($input['link'], "index_1.m3u8") ? $mp4_url : '', 'size' => strpos($input['link'], "index_1.m3u8") ? $size : ""),
            array('title' => "360", 'url' => strpos($input['link'], "index_2.m3u8") ? $mp4_url : '', 'size' => strpos($input['link'], "index_2.m3u8") ? $size : ""),
            array('title' => "480", 'url' => strpos($input['link'], "index_3.m3u8") ? $mp4_url : '', 'size' => strpos($input['link'], "index_3.m3u8") ? $size : ""),
            array('title' => "720", 'url' => strpos($input['link'], "index_4.m3u8") ? $mp4_url : '', 'size' => strpos($input['link'], "index_4.m3u8") ? $size : ""),
        );

        if ($video_id && is_numeric($video_id)) {
            $this->db->where("id", $video_id);
            $this->db->update("course_topic_file_meta_master", array("page_count" => 1, "bitrate_urls" => json_encode($bitrate_url)));
        }
        echo json_encode(array("type" => "success", "title" => "Success", "message" => " Video Convert Succefully To Download Mode", "data" => array()));
    }

    public function cancel_class() {
        if ($video_id = $this->input->post('video_id')) {
            app_permission("app_id",$this->db);
            $segments = $this->db->select('course_id,element_fk,l2_id,segment_fk,v_name')->get_where('course_segment_element', array('type' => 'video', 'element_fk' => $video_id))->result_array();
            app_permission("app_id",$this->db);
            $class_status = $this->db->get_where('chat_user', array('video_id' => $video_id))->row_array();
            if ($class_status['still_live'] == 1) {
                echo json_encode(array('status' => false, 'message' => 'Live Class Already Running', 'data' => "Running"));
            } else if ($class_status['still_live'] == 2) {
                echo json_encode(array('status' => false, 'message' => 'Live Class Already Cancelled', 'data' => "Already Cancelled"));
            } else {
                $this->db->where('video_id', $video_id);
                $update_chat = $this->db->update('chat_user', array('still_live' => 3));
                echo json_encode(array('status' => true, 'message' => 'Live Class Cancelled Successfully', 'data' => "Cancelled"));
            }
            if ($segments) {
                $payload = array(
                    "state" => "notification",
                    "message" => $segments['v_name'] . " class been cancelled.",
                    "tile_type" => "video"
                );
                $this->redis_magic->SET("live_class_detector_noti#" . $video_id, json_encode($payload));
                $this->redis_magic->DEL("t:live_class_detector_noti#" . $video_id);
                $this->redis_magic->HMSET("live_class_detector", "t:live_class_detector_noti#" . $video_id, time());
            }
        }
    }


    function fetch_videocrypt_playlist($id=null,$return='json') { 
        if($this->input->post('v_id')){
            $id=$this->input->post('v_id');   
        }
       
        if (!$id) {
            echo json_encode(array("type" => "error", "title" => "Error!", "message" => "Id is not Available"));
            die;
        }
        $data=array('id'=>$id);
        $accesskey= base64_encode(VC_ACCESS_KEY);
        $secret=base64_encode(VC_SECRET_KEY); //pre($vc_key); pre($accesskey); pre($secret); die;
        $headers = array('Content-Type: application/json',"accessKey:$accesskey","secretKey:$secret");
        $ch = curl_init();
        //curl_setopt($ch, CURLOPT_URL, "https://www.videocrypt.in/index.php/rest_api/courses/course/getDurationByUrl");
        curl_setopt($ch, CURLOPT_URL, "https://api.videocrypt.com/getVideoDetails");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        $data = curl_exec($ch);
        // pre($data);die;
        curl_close($ch);        
        $play_list = json_decode($data,true);

     
        if($return!='json'){
            return $play_list;
        }
        if (!$data) {
            //echo json_encode(array("type" => "error", "title" => "Error!", "message" => "Play List is not Available"));
            die;
        }
        // echo json_encode($data);die;
        echo json_encode(array("type" => "success", "title" => "Success..", "message" => "Video  is Displayed", "data" => $play_list['data']));
    }

    // NEW API MEDIA PANEL START
    //  function fetch_media_panel_data($id=null,$return='json') {  pre("dwebvhedew");die;
    //     if($this->input->post('v_id')){
    //         $id=$this->input->post('v_id');   
    //     }
       
    //     if (!$id) {
    //         echo json_encode(array("type" => "error", "title" => "Error!", "message" => "Id is not Available"));
    //         die;
    //     }
    //     $vc_key = json_decode(aes_cbc_decryption(get_db_meta_key($this->db, "vc_key"),'')); 
    //     if($vc_key->vc_access_key && $vc_key->vc_secret_key){
    //         $data=array('id'=>$id);
    //         $accesskey= base64_encode($vc_key->vc_access_key);
    //         $secret=base64_encode($vc_key->vc_secret_key); //pre($vc_key); pre($accesskey); die;
    //         $headers = array('Content-Type: application/json',"accessKey:$accesskey","secretKey:$secret");
    //         $ch = curl_init();
    //         curl_setopt($ch, CURLOPT_URL, "https://api.videocrypt.com/getVideoDetails");
    //         curl_setopt($ch, CURLOPT_POST, true);
    //         curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    //         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //         curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    //         $data = curl_exec($ch);
    //         curl_close($ch);        
    //         $play_list = json_decode($data,true);
    //         if($return!='json'){
    //             return $play_list;
    //         }
    //         if (!$data) {
    //             echo json_encode(array("type" => "error", "title" => "Error!", "message" => "Play List is not Available"));
    //             die;
    //         }
    //         echo json_encode(array("type" => "success", "title" => "Success..", "message" => "Video  is Displayed", "data" => $play_list['data']));
    //     }else{
    //         echo json_encode(array("type" => "error", "title" => "Error!", "message" => "Kindly update video crypt keys in configuration page."));
    //                 die;
    //     }
    // }
    // END MEDIA PANEL API


    public function video_logs($video_id=""){
         $view_data['page'] = 'video_logs';
        $data['page_title'] = "Video Logs";
        $view_data['video_id']=$video_id;
        $view_data['breadcrum']=array('video_logs'=>"file_manager/library/video_logs");
        $data['page_data'] = $this->load->view('file_manager/video_logs', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function ajax_video_logs(){
        
        $requestData = $_REQUEST;
        $columns = array(
            0 => 'ctfmm.id',
            1 => 'ctfmm.title',
            2 => 'ctfmm.course_names',
           );
        $where= " ctfmm.file_type=3 and ctfmm.app_id=".APP_ID;
        $query = "SELECT count(id) as total FROM course_topic_file_meta_master ctfmm where $where";
        $query = $this->db->query($query)->row_array();
        $totalData = (count($query) > 0) ? $query['total'] : 0;
        $totalFiltered = $totalData;
        $sql = "SELECT ctfmm.id,ctfmm.title as title ,ctfmm.course_names,
            (select count(id) from video_logs where video_logs.video_id=ctfmm.id ) as total_views,
            (select count(distinct(user_id)) from video_logs where video_logs.video_id=ctfmm.id ) as total_unique_views,
            (SELECT SUM(end_time) FROM video_logs where video_id=ctfmm.id) as watched_time 
            from  course_topic_file_meta_master ctfmm where $where";

        if (!empty($requestData['columns'][0]['search']['value'])) {
             $sql .= " AND ctfmm.id LIKE '" . $requestData['columns'][0]['search']['value'] . "%' ";
        }
        if (!empty($requestData['columns'][1]['search']['value'])) {
            $sql .= " AND ctfmm.title LIKE '" . $requestData['columns'][1]['search']['value'] . "%' ";
        }
        if (!empty($requestData['columns'][2]['search']['value'])) {
            $sql .= " AND ctfmm.course_names LIKE '" . $requestData['columns'][2]['search']['value'] . "%' ";
        }
        $query = $this->db->query($sql)->result();
        $totalFiltered = count($query);

        $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   "; // adding length

        $result = $this->db->query($sql)->result();
        $total_users=$this->db->select("count(id) as total")->get_where("users",array("app_id"=>APP_ID))->row()->total;
        $data = array();
        foreach ($result as $r) {
            $nestedData = array();
            $nestedData[] = $r->id;
            // $nestedData[] = "<a class='bold' href='" . AUTH_PANEL_URL . "file_manager/library/edit_video_library/" . $r->id . "' title='Edit Video' target='__blank'>" . $r->title . "</a>";
            $nestedData[] = $r->title ;
            $nestedData[] = $r->course_names;
            $nestedData[] = $r->total_views ;
            $nestedData[] = ($r->total_unique_views==''?0:$r->total_unique_views);
            $nestedData[] = gmdate("H:i:s",($r->total_unique_views>0?round($r->watched_time/$r->total_unique_views,2):0));
            $nestedData[] = round((($r->total_unique_views/$total_users)*100),2)."%";
            $data[] = $nestedData;
        }

        $json_data = array(
            "draw" => intval($requestData['draw']),
            "recordsTotal" => intval($totalData), // total number of records
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data, // total data array
        );
        $json_data = json_encode($json_data);
        echo s3_to_cf($json_data); // send data as json format
    }
     public function add_extra_class() {
        if ($this->input->post()) { 
             // pre($this->input->post());die;
            $this->add_extra_class_to_db();
        }
        $view_data['languages'] = get_language($this, 1);
        $view_data['master_category'] = get_master_category();
        $view_data['breadcrum']=array('Add Extra Class'=>"file_manager/add_extra_class");
        // print_r($view_dataq['master_category'])
        $data['page_data'] = $this->load->view('file_manager/add_extra_class', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }
    private function add_extra_class_to_db() {
        if($this->input->post('file_type') == 3){
            $file_type = 'Video';
        }else
        {
            $file_type = "";
        }
        // }else if($this->input->post('file_type') == 3){
        //     $file_type = 'Video';
        // }else if($this->input->post('file_type') == 6){
        //     $file_type = 'Image';
        // }else if($this->input->post('file_type') == 7){
        //     $file_type = 'Concept';
        // }else if($this->input->post('file_type') == 8){
        //     $file_type = 'Objective test';
        // }else if($this->input->post('file_type') == 9){
        //     $file_type = 'Subjective test';
        // }else{
        $data = array(
            "user_id" => $this->input->post('user_id'),
            "file_id" => $this->input->post('file_id'),
            "file_type" => $file_type,
            "start_date" => strtotime($this->input->post('start_date')),
            "end_date" => strtotime($this->input->post("end_date")),
            'creation_date' => time(),
            // 'app_id' => (defined("APP_ID") ? "" . APP_ID . "" : "0")
        );
        //echo "<pre>";print_r($data);die;
        $this->db->insert('extra_classes', $data);
        $id = $this->db->insert_id();
        page_alert_box('success', 'Extra Classes Added', 'Extra Classes Added successfully.');
        /* put a log */
        // backend_log_genration($this, 'Added new course -: ' . $this->input->post('title'), 'COURSE');
        redirect(AUTH_PANEL_URL . "file_manager/library/add_extra_class");
    }
    public function delete_extra_class($id) {
        // print_r($id);die;
        // $course_id = $_GET['course_fk_id']; 
        $status = $this->Library_model->delete_extra_class($id);
        page_alert_box('success', 'Action performed', 'Extra Class deleted successfully');
        if ($status) {
            redirect('auth_panel/file_manager/library/add_extra_class');
        }
    }

    public function pdf_search() {
        $input = $this->input->get();       
        // if (isset($input['q']) && isset($input['filter']) && $input['filter'] == 'yes' && strlen($input['q']) > 2) {
        if (isset($input['q']) && !empty($input['filter']) && $input['filter'] == 'yes'){
            $this->db->select("id,CONCAT('[Id: ',id,']',': ',title) as text");
            if(!empty($input['q'])){
                $this->db->group_start();
                if (is_numeric($input['q'])) {
                    $this->db->or_where('title', $input['q']);
                    $this->db->or_where('id', $input['q']);
                }else
                    $this->db->like("title", $input['q']);

                $this->db->group_end();
            }
            $this->db->where("file_type", 1);
            app_permission("app_id",$this->db);
            $filter = $this->db->get('course_topic_file_meta_master')->result_array();
            // echo $this->db->last_query();die;
            if ($filter)
                echo json_encode($filter);
            else
                echo json_encode(array());
        } else {
            echo json_encode(array());
        }
    }
    public function video_search() {
        $input = $this->input->get();       
        // if (isset($input['q']) && isset($input['filter']) && $input['filter'] == 'yes' && strlen($input['q']) > 2) {
        if (isset($input['q']) && !empty($input['filter']) && $input['filter'] == 'yes'){
            $this->db->select("id,CONCAT('[Id: ',id,']',': ',title) as text");
            $this->db->group_start();
            if (is_numeric($input['q'])) {
                $this->db->or_where('title', $input['q']);
                $this->db->or_where('id', $input['q']);
            }else
                $this->db->like("title", $input['q']);
            $this->db->group_end();
            $this->db->where("file_type", 3);
            app_permission("app_id",$this->db);
            $filter = $this->db->get('course_topic_file_meta_master')->result_array();
            //echo $this->db->last_query();die;
            if ($filter)
                echo json_encode($filter);
            else
                echo json_encode(array());
        } else {
            echo json_encode(array());
        }
    }
    public function image_search() {
        $input = $this->input->get();       
        // if (isset($input['q']) && isset($input['filter']) && $input['filter'] == 'yes' && strlen($input['q']) > 2) {
        if (isset($input['q']) && !empty($input['filter']) && $input['filter'] == 'yes'){
            $this->db->select("id,CONCAT('[Id: ',id,']',': ',title) as text");
            $this->db->group_start();
            if (is_numeric($input['q'])) {
                $this->db->or_where('title', $input['q']);
                $this->db->or_where('id', $input['q']);
            }else
                $this->db->like("title", $input['q']);
            $this->db->group_end();
            $this->db->where("file_type", 6);
            app_permission("app_id",$this->db);
            $filter = $this->db->get('course_topic_file_meta_master')->result_array();
            //echo $this->db->last_query();die;
            if ($filter)
                echo json_encode($filter);
            else
                echo json_encode(array());
        } else {
            echo json_encode(array());
        }
    }
    public function concept_search() {
        $input = $this->input->get();       
        // if (isset($input['q']) && isset($input['filter']) && $input['filter'] == 'yes' && strlen($input['q']) > 2) {
        if (isset($input['q']) && !empty($input['filter']) && $input['filter'] == 'yes'){
            $this->db->select("id,CONCAT('[Id: ',id,']',': ',title) as text");
            $this->db->group_start();
            if (is_numeric($input['q'])) {
                $this->db->or_where('title', $input['q']);
                $this->db->or_where('id', $input['q']);
            }else
                $this->db->like("title", $input['q']);
            $this->db->group_end();
            $this->db->where("file_type", 7);
            app_permission("app_id",$this->db);
            $filter = $this->db->get('course_topic_file_meta_master')->result_array();
            //echo $this->db->last_query();die;
            if ($filter)
                echo json_encode($filter);
            else
                echo json_encode(array());
        } else {
            echo json_encode(array());
        }
    }
    public function objective_series() {
        $input = $this->input->get();       
        // if (isset($input['q']) && isset($input['filter']) && $input['filter'] == 'yes' && strlen($input['q']) > 2) {
        if (isset($input['q']) && !empty($input['filter']) && $input['filter'] == 'yes'){
            $this->db->select("id,CONCAT('[Id: ',id,']',': ',test_series_name) as text");
            $this->db->group_start();
            if (is_numeric($input['q'])) {
                $this->db->or_where('test_series_name', $input['q']);
                $this->db->or_where('id', $input['q']);
            }else
                $this->db->like("test_series_name", $input['q']);
            $this->db->group_end();
            $this->db->where("set_type", 0);
            app_permission("app_id",$this->db);
            $filter = $this->db->get('course_test_series_master')->result_array();
            //echo $this->db->last_query();die;
            if ($filter)
                echo json_encode($filter);
            else
                echo json_encode(array());
        } else {
            echo json_encode(array());
        }
    }
    public function subjective_series() {
        $input = $this->input->get();       
        // if (isset($input['q']) && isset($input['filter']) && $input['filter'] == 'yes' && strlen($input['q']) > 2) {
        if (isset($input['q']) && !empty($input['filter']) && $input['filter'] == 'yes'){
            $this->db->select("id,CONCAT('[Id: ',id,']',': ',test_series_name) as text");
            $this->db->group_start();
            if (is_numeric($input['q'])) {
                $this->db->or_where('test_series_name', $input['q']);
                $this->db->or_where('id', $input['q']);
            }else
                $this->db->like("test_series_name", $input['q']);
            $this->db->group_end();
            $this->db->where("set_type", 3);
            app_permission("app_id",$this->db);
            $filter = $this->db->get('course_test_series_master')->result_array();
            //echo $this->db->last_query();die;
            if ($filter)
                echo json_encode($filter);
            else
                echo json_encode(array());
        } else {
            echo json_encode(array());
        }
    }
    public function ajax_extra_list() {
        // storing  request (ie, get/post) global array to a variable
        $requestData = $_REQUEST;
        $columns = array(
            // datatable column index  => database column name
            0 => 'id',
            1 => 'users.name',
            2 => 'name_2',
            3 => '',
        );
        $app_id = APP_ID;
        $query = "SELECT count(id) as total FROM course_type_master where 1 = 1";
        $query .= app_permission("app_id");
        $query = $this->db->query($query);
        $query = $query->row_array();
        $totalData = (count($query) > 0) ? $query['total'] : 0;
        $totalFiltered = $totalData;

        $sql = "select ctfmm.*,ctsm.test_series_name,users.name,ec.file_type,ec.file_type,ec.start_date,ec.end_date,ec.creation_date,ec.id as ids from extra_classes ec left JOIN course_topic_file_meta_master ctfmm on ctfmm.id=ec.file_id and ec.file_type!='test' and ec.file_type!='subjective' left JOIN course_test_series_master ctsm on ctsm.id=file_id and (ec.file_type='test' or ec.file_type='subjective') JOIN users on users.id=ec.user_id where ctfmm.app_id = $app_id";
         // print_r($sql);die;

        if (!empty($requestData['columns'][1]['search']['value'])) {   //name
            $sql .= " AND users.name LIKE '%" . $requestData['columns'][1]['search']['value'] . "%' ";
        }
        
        $sql .= app_permission("app_id", $this->db);
        $query = $this->db->query($sql)->result();

        $totalFiltered = count($query); // when there is a search parameter then we have to modify total number filtered rows as per search result.

        $sql .= " ORDER BY id desc LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";  // adding length

        $result = $this->db->query($sql)->result();
        //echo $this->db->last_query();die;
        $data = array();
        $start = 0;
        foreach ($result as $r) {  // preparing an array
           // print_r($r);die;
            $nestedData = array();
            $nestedData[] = ++$start;
            $nestedData[] = $r->name;
            $nestedData[] = $r->file_type;
            $nestedData[] = $r->title;
            $nestedData[] = get_time_format($r->start_date);
            $nestedData[] = get_time_format($r->end_date);
            $nestedData[] = get_time_format($r->creation_date);
            $action = '';
            // if ($r->name != "Test Series" && $r->name != "Classroom") {
                $nestedData[] = "<a class='btn-xs bold btn btn-info' href='" . AUTH_PANEL_URL . "file_manager/library/edit_extra_class/" . $r->ids . "'><i class='fa fa-pencil'></i>&nbsp Edit</a>&nbsp"
                        . "<a class='btn-xs bold btn btn-danger' href='" . AUTH_PANEL_URL . "file_manager/library/delete_extra_class/" . $r->ids . "'><i class='fa fa-trash'></i>&nbsp Delete</a>";
            // } else
            //     $nestedData[] = "N/A";
            $data[] = $nestedData;
        }

        $json_data = array(
            "draw" => intval($requestData['draw']), // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they             first check the draw number, so we are sending same number in draw.
            "recordsTotal" => intval($totalFiltered), // total number of records
            "recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
            "data" => $data   // total data array
        );

        echo json_encode($json_data);  // send data as json format
    }
    public function edit_extra_class($id) {
        if ($this->input->post()) {
                $update_data = array(
                     "user_id" => $this->input->post('user_id'),
                     "file_id" => $this->input->post('file_id'),
                     "file_type" => $file_type,
                     "start_date" => strtotime($this->input->post('start_date')),
                     "end_date" => strtotime($this->input->post("end_date")),
                     'creation_date' => time(),
                );
                $this->db->where('id', $id);
                $this->db->update('extra_classes', $update_data);
                page_alert_box('success', 'Extra Class Updated', 'Extra Class has been updated successfully');
                redirect(AUTH_PANEL_URL . 'file_manager/library/add_extra_class');
        }
         $query = "select ctfmm.*,ctsm.test_series_name,users.name,ec.file_type,ec.file_type,ec.start_date,ec.end_date,ec.creation_date,ec.id as ids from extra_classes ec left JOIN course_topic_file_meta_master ctfmm on ctfmm.id=file_id and ec.file_type!='test' and ec.file_type!='subjective' left JOIN course_test_series_master ctsm on ctsm.id=file_id and (ec.file_type='test' or ec.file_type='subjective') JOIN users on users.id=ec.user_id where ec.id = $id"; 
         $view_data['extra_data'] = $this->db->query($query)->row_array();
        $view_data['page'] = 'edit_extraa';
        $data['page_title'] = "Extra class edit";
        $this->db->where('id', $id);
        app_permission("app_id", $this->db);
        $view_data['breadcrum']=array('Edit Extra Class'=>"file_manager/edit_extra_class");
        $data['page_data'] = $this->load->view('file_manager/edit_extra_class', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function add_season_episode($id = NULL,$cate = NULL) { 
      
        if ($this->input->post()) {   
            $this->form_validation->set_rules('videocript_id', 'Videocrypt ID', 'trim|required');
            $this->form_validation->set_rules('video_file', 'Video File', 'trim|required');
            $this->form_validation->set_rules('description', 'Description', 'trim|required');
            // if (!empty($_FILES['thumbnail']['name'])) {
            //     if ($_FILES['thumbnail']['size'] > 1048576) {
            //         page_alert_box("error", "Add Video ", " thumbnail file doesn't excced size more than 1 MB.");
            //         redirect_to_back();
            //     }
            // }
            
            if ($this->form_validation->run() == FALSE) {
               //$view_data['validation_error'] = '1';
            } else {
               
                $type_id=$this->input->post('cate_id'); 
                if($type_id == 2){

                   
                  
                $token = random_token();
                $file = $thumbnail = $insert_id ='';
                $url_json = array();
                if(($this->input->post("published_date"))>date("Y-m-d")){
                    page_alert_box("error", "Invalid Release Date", "Please Select a Valid release date");
                    redirect_to_back();

                }
                $insert_data = array(
                    'season_id' => $this->input->post('ctfmm_id'),
                    'type_id' => $this->input->post('ctfmm_id'),
                    'video_type' => $this->input->post('video_type'),
                    'token' => $this->input->post('videocript_id'),
                    'episode_title' => $this->input->post('title'),
                    'episode_url' => $this->input->post('video_file'),
                    'view_mode' => $this->input->post("movie_view"),
                    'transcribe'  => $this->input->post("transcribe"),
                    'runtime' =>$this->input->post('playtime'),
                    'ep_no' =>  $this->input->post('episode_no'),
                    'release_date' =>strtotime($this->input->post('published_date')) ,
                    'app_id' =>  (defined("APP_ID") ? "" . APP_ID . "" : "0"),
                    'episode_description' => $this->input->post('description'),
                    'skip_intro'=>($this->input->post('skip_intro'))?$this->input->post('skip_intro'):0,
                   'skip_time'=>($this->input->post('skip_time'))?$this->input->post('skip_time'):0,
                );
                
                $thumbnail = $this->input->post('thumbnail');
                if (!$thumbnail && isset($_FILES['thumbnail']) && $_FILES['thumbnail']['size'] > 0) {
                    $thumbnail = amazon_s3_upload($_FILES['thumbnail'], "file_manager/media_library", $thumbnail); 
                
                $thumbnail=str_replace( 'https://'.$this->CHANG_BUCKET_KEY . ".s3." . $this->CHANG_REGION . ".amazonaws.com",$this->CHANG_CLOUDFRONT, $thumbnail);               
                    $insert_data['thumbnail_url'] =  ($thumbnail)?$thumbnail:""; 
                }
                    //----poster start ---
                    $poster = $this->input->post('poster');
                     if (!empty($_FILES['poster']['name'])) {
                            $poster_url = amazon_s3_upload($_FILES['poster'], "file_manager/media_library", $poster);
                            $poster_url=str_replace( 'https://'.$this->CHANG_BUCKET_KEY . ".s3." . $this->CHANG_REGION . ".amazonaws.com",$this->CHANG_CLOUDFRONT, $poster_url);
                        }
                    $insert_data['poster_url'] =  ($poster_url)?$poster_url:"";  
                    //----poster end ----
                $insert_data['episode_url']=$this->input->post('play_via')==1?$this->input->post('video_file_dash'):$this->input->post('video_file');
                $videocrypt=$this->fetch_videocrypt_playlist($this->input->post('videocript_id'),'array'); 
                if($videocrypt['result']==1){
                    if($this->input->post('play_via')==0){
                        $insert_data['episode_url']=$videocrypt['data']['file_url_hls'];
                    }
                    if($this->input->post('play_via')==1){
                        $insert_data['episode_url']=$videocrypt['data']['file_url_dash'];
                    }
                    if($this->input->post('is_drm_protected')==1){                            
                        $insert_data['is_drm_protected']=$this->input->post('is_drm_protected');
                        $insert_data['drm_dash_url']=$videocrypt['data']['drm_dash_url'];
                        $insert_data['drm_hls_url']=$videocrypt['data']['drm_hls_url'];
                    }
                    if($videocrypt['data']['download_url']!=""){
                        $insert_data['bitrate_urls']=json_encode($videocrypt['data']['download_url']);
                    }
                    $insert_data['vdc_id']=$videocrypt['data']['id'];
                    $insert_data['vdc_json']=json_encode($videocrypt['data']);
                    sscanf($videocrypt['data']['duration'], "%d:%d:%d", $hours, $minutes, $seconds);
                    $insert_data['playtime']  = isset($seconds) ? $hours * 3600 + $minutes * 60 + $seconds : $hours * 60 + $minutes;
                        //  print_r($insert_data); die;

                    $this->db->insert('premium_episodes',$insert_data); 
                $insert_id = $this->db->insert_id(); //pre($insert_id);die;
                if($insert_id)
                         update_api_version_new($this->db, 'episodes_w');
                page_alert_box('success', 'Added', 'Episode added successfully');
                }
                else{
                     page_alert_box('error', 'Error', 'Please try again with valid video Id.');
                     redirect_to_back();
                }
            }
            else {
                $token = random_token();
                $file = $thumbnail = $insert_id ='';
                $url_json = array();
                $insert_data = array(
                    'season_id' => $this->input->post('ctfmm_id'),
                    'type_id' => $this->input->post('ctfmm_id'),
                    'token' => $this->input->post('videocript_id'),
                    'episode_title' => $this->input->post('title'),
                    'episode_url' => $this->input->post('video_file'),
                    'runtime' =>$this->input->post('playtime'),
                    'video_type' => $this->input->post('video_type'),
                    'ep_no' =>  $this->input->post('episode_no'),
                    'release_date' => strtotime($this->input->post('published_date')),
                    'app_id' =>  (defined("APP_ID") ? "" . APP_ID . "" : "0"),
                    'episode_description' => $this->input->post('description'),
                    'skip_intro'=>($this->input->post('skip_intro'))?$this->input->post('skip_intro'):0,
                   'skip_time'=>($this->input->post('skip_time'))?$this->input->post('skip_time'):0,
                );
               
                $thumbnail = $this->input->post('thumbnail');
                if (!$thumbnail && isset($_FILES['thumbnail']) && $_FILES['thumbnail']['size'] > 0) 
                    $thumbnail = amazon_s3_upload($_FILES['thumbnail'], "file_manager/media_library", $thumbnail);
                    
                   // $thumbnail = amazon_s3_upload($_FILES['thumbnail'], "file_manager/media_library", $insert_id);
                    $thumbnail=str_replace( 'https://'.$this->CHANG_BUCKET_KEY . ".s3." . $this->CHANG_REGION . ".amazonaws.com",$this->CHANG_CLOUDFRONT, $thumbnail);

                     //----poster start ---
                    $poster = $this->input->post('poster');
                     if (!empty($_FILES['poster']['name'])) {
                            $poster_url = amazon_s3_upload($_FILES['poster'], "file_manager/media_library", $poster);
                            $poster_url=str_replace( 'https://'.$this->CHANG_BUCKET_KEY . ".s3." . $this->CHANG_REGION . ".amazonaws.com",$this->CHANG_CLOUDFRONT, $poster_url);
                        }
                    $insert_data['poster_url'] =  ($poster_url)?$poster_url:"";  
                    //----poster end ----
                    
                $insert_data['episode_url']=$this->input->post('play_via')==1?$this->input->post('video_file_dash'):$this->input->post('video_file');
                $videocrypt=$this->fetch_videocrypt_playlist($this->input->post('videocript_id'),'array'); 
                if($videocrypt['result']==1){
                    if($this->input->post('play_via')==0){
                        $insert_data['episode_url']=$videocrypt['data']['file_url_hls'];
                    }
                    if($this->input->post('play_via')==1){
                        $insert_data['episode_url']=$videocrypt['data']['file_url_dash'];
                    }
                    if($this->input->post('is_drm_protected')==1){                            
                        $insert_data['is_drm_protected']=$this->input->post('is_drm_protected');
                        $insert_data['drm_dash_url']=$videocrypt['data']['drm_dash_url'];
                        $insert_data['drm_hls_url']=$videocrypt['data']['drm_hls_url'];
                    }
                    if($videocrypt['data']['download_url']!=""){
                        $insert_data['bitrate_urls']=json_encode($videocrypt['data']['download_url']);
                    }
                    $insert_data['thumbnail_url'] =  ($thumbnail)?$thumbnail:""; 
                    $insert_data['vdc_id']=$videocrypt['data']['id'];
                    $insert_data['vdc_json']=json_encode($videocrypt['data']);
                    sscanf($videocrypt['data']['duration'], "%d:%d:%d", $hours, $minutes, $seconds);
                    $insert_data['playtime']  = isset($seconds) ? $hours * 3600 + $minutes * 60 + $seconds : $hours * 60 + $minutes;
                    //pre($insert_data);die;
                    $this->db->insert('tv_serial_episodes',$insert_data); 
                    $insert_id = $this->db->insert_id(); //pre($insert_id);die;
                    if($insert_id)
                         update_api_version_new($this->db, 'episodes_tv');
                         $insert_data['thumbnail_url']=$thumbnail;
                    app_permission("app_id",$this->db);
                    $this->db->where('id', $insert_id);
                    $this->db->update('tv_serial_episodes', $insert_data);
                    page_alert_box('success', 'Added', 'Episode added successfully');
                }
                else{
                     page_alert_box('error', 'Error', 'Please try again with valid video Id.');
                     redirect_to_back();
                }
            }
                if($insert_id){
                    
                redirect(AUTH_PANEL_URL . 'file_manager/library/season_episode_list/'.$id.'/'.$cate);
            }
                //
            }

        }
        $view_data['ctfmm_id'] = $id;
        $view_data['cate'] = $cate;
        $view_data['page'] = 'Add_Season_Episode';
        $data['page_data'] = $this->load->view('file_manager/add_season_episode', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function season_episode_list($id,$cate) {
        $view_data['season_id'] = $id;
        $view_data['cate_id'] = $cate;
        $view_data['cid'] = $this->uri->segment('6');
        $view_data['page'] = 'premium_season';
        $data['page_data'] = $this->load->view('premium_media_library/episode_list', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

   public function ajax_get_episode_list() {
        // storing  request (ie, get/post) global array to a variable
        $requestData = $_REQUEST;
        $id = $requestData['id'];
        $ctype = $requestData['ctype'];
        $id = $requestData['id'];
        $columns = array(
            // datatable column index  => database column name
            0 => 'id',
            1 => 'title',
            2 => 'episode_title',
            3 => 'episode_description',
            4 => 'status',
            5 => 'ep_no'
        );

        $query = "SELECT count(id) as total
                                From premium_episodes where status !=2 and season_id=$id 
                                ";
        $query = $this->db->query($query);
        $query = $query->row_array();
        $totalData = (count($query) > 0) ? $query['total'] : 0;
        $totalFiltered = $totalData;

        if($ctype=="2"){
             $sql = "SELECT ep.id,ep.season_id,ep.episode_title,ep.episode_description,ep.position,ep.status,ep.ep_no,ep.token,ps.title as season_title
                From premium_episodes as ep
                LEFT JOIN course_topic_file_meta_master as ps  ON ep.season_id = ps.id
                where ep.status !=2 and ps.id = $id
                "; 

                 if (!empty($requestData['columns'][1]['search']['value'])) {
                $sql .= " AND ps.title LIKE '" . '%' . $requestData['columns'][1]['search']['value'] . "%' ";
            }
            if (!empty($requestData['columns'][2]['search']['value'])) {
                $sql .= " AND ep.episode_title LIKE '" . '%' . $requestData['columns'][2]['search']['value'] . "%' ";
            }
             if (!empty($requestData['columns'][5 ]['search']['value'])) {
                $sql .= " AND ep.ep_no LIKE '" . '%' . $requestData['columns'][2]['search']['value'] . "%' ";
            }
            if (!empty($requestData['columns'][3]['search']['value'])) {
                $sql .= " AND ep.episode_description LIKE '" . '%' . $requestData['columns'][3]['search']['value'] . "%' ";
            }
            if (isset($requestData['columns'][4]['search']['value']) && $requestData['columns'][4]['search']['value'] != "") {  //salary
                $sql .= " AND ep.status = " . $requestData['columns'][4]['search']['value'];
            }
            $sql.=" order by ep.ep_no asc ";

        }
        else{
            $sql = "SELECT tv.id,tv.season_id,tv.episode_title,tv.episode_description,tv.position,tv.status,tv.ep_no,tv.token,ps.title as season_title
                From tv_serial_episodes as tv
                LEFT JOIN course_topic_file_meta_master as ps  ON tv.season_id = ps.id
                where tv.status !=2 and ps.id = $id
                "; 

                 if (!empty($requestData['columns'][1]['search']['value'])) {
                    $sql .= " AND ps.title LIKE '" . '%' . $requestData['columns'][1]['search']['value'] . "%' ";
                }
                if (!empty($requestData['columns'][2]['search']['value'])) {
                    $sql .= " AND tv.episode_title LIKE '" . '%' . $requestData['columns'][2]['search']['value'] . "%' ";
                }
             if (!empty($requestData['columns'][5 ]['search']['value'])) {
                $sql .= " AND tv.ep_no LIKE '" . '%' . $requestData['columns'][5]['search']['value'] . "%' ";
            }
            if (!empty($requestData['columns'][3]['search']['value'])) {
                $sql .= " AND tv.episode_description LIKE '" . '%' . $requestData['columns'][3]['search']['value'] . "%' ";
            }
            if (isset($requestData['columns'][4]['search']['value']) && $requestData['columns'][4]['search']['value'] != "") {  //salary
                $sql .= " AND tv.status = " . $requestData['columns'][4]['search']['value'];
            }
            $sql.=" order by tv.ep_no asc ";

        }

        $query = $this->db->query($sql)->result();
        $totalFiltered = count($query); // when there is a search parameter then we have to modify total number filtered rows as per search result.
        $sql .= "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   "; // adding length
        $result = $this->db->query($sql)->result();
        $data = array();
        $start = 0;
        foreach ($result as $r) {
            $short_desc = $this->word_formatter($r->episode_description);
            $status = ($r->status == 0) ? 'Active' : 'Disabled';
            $alert_status = ($r->status == 0) ? 'success' : 'danger';
            // preparing an array
            $nestedData = array();
            $nestedData[] = ++$start;
            $nestedData[] = $r->season_title;
            $nestedData[] = $r->ep_no;
            $nestedData[] = $r->episode_title;
            $nestedData[] = $short_desc . "...";
            $msg =  ($status == "Active") ? "Disable" : "Active";

             $nestedData[] ="<a class='btn-xs bold btn btn-$alert_status' onclick=\"return confirm('Are you sure you want to $msg ?')\" href='" . AUTH_PANEL_URL . "media_library/premium_video/lock_unlock_episodes/" . $r->id . '/' . $r->status . '/'. $r->season_id . "'>$status</a>&nbsp;";
           
           
             $nestedData[] = "
             <a style = '' class='btn-xs bold btn btn-success' title='View Video' href='" . AUTH_PANEL_URL . "media_library/premium_video/view_episode?id=" . $r->id . "&data_id=".$r->id."&token=".$r->token."&data_type=2'><i class='fa fa-eye'></i></a>&nbsp;
          <a style = 'display: block;' class='btn-xs bold btn btn-primary' onclick=\"return confirm('Are you sure you want to edit?')\" href='" . AUTH_PANEL_URL . "file_manager/library/edit_episode/" . $r->id .'/' . $ctype .'/' . $r->season_id . "'><i class='fa fa-edit'></i></a>&nbsp;
             <a class='btn-xs bold btn btn-danger' onclick=\"return confirm('Are you sure you want to Delete?')\" href='" . AUTH_PANEL_URL . "file_manager/library/delete_episodes/" . $r->id . '/' . $r->season_id . '/' .$ctype. "'><i class='fa fa-trash-o'></i></a>&nbsp;
                 ";

            $data[] = $nestedData;
        }

        $json_data = array(
            "draw" => intval($requestData['draw']), // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
            "recordsTotal" => intval($totalData), // total number of records
            "recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
            "data" => $data, // total data array
        );

        echo json_encode($json_data); // send data as json format
    }

    public function delete_episodes($id, $season_id,$cate) { // pre($id);pre($season_id);pre($cate);die;
        $delete_season = $this->Premium_video_model->delete_episodes($id,$season_id,$cate);
        page_alert_box('success', 'Deleted', 'Episodes has been deleted successfully');
        redirect(AUTH_PANEL_URL . 'file_manager/library/episode_list/' . $season_id . '/'. $cate);
    }

    public function get_categorywise_geners($id=null) { 
        $cate_id = $id;
        $cate = $this->Library_model->get_category($cate_id);
        echo json_encode($cate);
    }

    private function word_formatter($string) {
        $string = explode(" ", strip_tags($string));
        if ($string && count($string) > 25) {
            $string = array_slice($string, 0, 25, true);
        }
        return implode(" ", $string);
    }

    public function episode_list($id,$cate) {
        $view_data['season_id'] = $id;
        $view_data['cate_id'] = $cate;
        $view_data['page'] = 'premium_season';
        $data['page_data'] = $this->load->view('premium_media_library/episode_list', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }
    
    public function edit_episode($id) {          
        $test_json = array();
    if($this->input->post()) {
        $this->form_validation->set_rules('title', 'File Title', 'required');
        $this->form_validation->set_rules('playtime', 'Playtime', 'required');
        $this->form_validation->run();
        $type = $this->uri->segment('6');
        $fmmid = $this->uri->segment('7');
        // echo $id;
        // echo $type;
        // echo $fmmid;
        //get category_type start
            $query = "SELECT category FROM course_topic_file_meta_master ctfmm where ctfmm.id =$fmmid ";
            $query .=  (defined("APP_ID") ? "" . app_permission("ctfmm.app_id") . "" : "");
            $query = $this->db->query($query);
            $category_id = $query->row();
        //get category_type end
    if ($errors = $this->form_validation->get_all_errors()) {
            page_alert_box("error", "Validation error", array_values($errors)[0]);
            redirect(AUTH_PANEL_URL.'/file_manager/library/edit_episode/'.$id.'/'.$type);
            } 
           
     else {
            $insert_id = $id;
            $job_meta = array();
            $episode_type = $this->input->post("episode_type");
            $video_type = $this->input->post("video_type");
            $file = $thumbnail = $drm_dash_url = $poster_url = "";
            $drm_hls_url = "";
             
    if($video_type=="7"){
        $videocrypt=$this->fetch_videocrypt_playlist($this->input->post('videocript_id'),'array');
        if($videocrypt['result']==1){
            if($this->input->post('play_via')==0){
                $update_data['episode_url']=$videocrypt['data']['file_url_hls'];
            }
            if($this->input->post('play_via')==1){
                $update_data['episode_url']=$videocrypt['data']['file_url_dash'];
            }
            if($this->input->post('is_drm_protected')==1){                          
                $update_data['is_drm_protected']=$this->input->post('is_drm_protected');
                $update_data['drm_dash_url']=$videocrypt['data']['drm_dash_url'];
                $update_data['drm_hls_url']=$videocrypt['data']['drm_hls_url'];
            }
            if($videocrypt['data']['download_url']!=""){
                $update_data['bitrate_urls']=json_encode($videocrypt['data']['download_url']);
            }
            $update_data['vdc_id']=$videocrypt['data']['id'];
            $update_data['vdc_json']=json_encode($videocrypt['data']);
            sscanf($videocrypt['data']['duration'], "%d:%d:%d", $hours, $minutes, $seconds);
            $update_data['playtime']  = isset($seconds) ? $hours * 3600 + $minutes * 60 + $seconds : $hours * 60 + $minutes;
        }else{
            page_alert_box('error', 'Error', 'Please try again with valid video Id.');
            redirect_to_back();
        }
    }else{
        $update_data['playtime'] =($this->input->post('playtime')?($this->input->post('playtime') * 60) : 0);
    }

    if($episode_type=="3"){
          if (!empty($_FILES['video_file']['name']) || $this->input->post("custom_s3_url")) {
                    $url = $this->input->post("custom_s3_url");
                    if ($url) {
                        $file = explode(".com/", $url)[1];
                    } else if (!empty($_FILES['video_file']['name'])) {
                        $time = time();
            $target_location = getcwd() . '/uploads/bitrate';//1920*1280
            $original = Upload("video_file", $target_location . "/", $time . "_1920x1280");
            if ($original) {
                $file = $this->s3_upload->upload_s3($target_location . '/' . $original, $id, "file_manager/media_library/original_direct/");
                $file=str_replace( 'https://'.$this->CHANG_BUCKET_KEY . ".s3." . $this->CHANG_REGION . ".amazonaws.com",$this->CHANG_CLOUDFRONT, $file);

                if ($original)
                    unlink($original);
            }
        }
    } else if ($this->input->post('video_file')) {
        $file = $this->input->post('video_file');
    }
    if (!empty($_FILES['thumbnail']['name'])) {
        $thumbnail = amazon_s3_upload($_FILES['thumbnail'], "file_manager/media_library", $id);
        $thumbnail=str_replace( 'https://'.$this->CHANG_BUCKET_KEY . ".s3." . $this->CHANG_REGION . ".amazonaws.com",$this->CHANG_CLOUDFRONT, $thumbnail);
    } 

      if (!empty($_FILES['poster']['name'])) {
    $poster_url = amazon_s3_upload($_FILES['poster'], "file_manager/media_library", $id);
    $poster_url=str_replace( 'https://'.$this->CHANG_BUCKET_KEY . ".s3." . $this->CHANG_REGION . ".amazonaws.com",$this->CHANG_CLOUDFRONT, $poster_url);
}
       
       // echo "<pre>";print_r($update_data);
        $update_data = array(
              'episode_title' => $this->input->post('title'),
            'episode_description' => $this->input->post('episode_description'),
            'video_type' => $video_type,
            'vdc_id' => $this->input->post("videocript_id"),
            'ppv' => $this->input->post('ppv'),
            'release_date' => $this->input->post("published_date"),
            'view_mode' => $this->input->post("movie_view"),
            'ppv_status' => $this->input->post("movie_view"),
            'is_drm_protected' => $this->input->post("is_drm_protected"),
            //'thumbnail_url' =>$thumbnail,
            'episode_url' => $file,
            'ep_no' => $this->input->post("episode_no"),
            'skip_intro'=>($this->input->post('skip_intro'))?$this->input->post('skip_intro'):0,
           'skip_time'=>($this->input->post('skip_time'))?$this->input->post('skip_time'):0            
        );

         if ($file)
            $update_data['episode_url'] = $file; //aes_cbc_encryption($file, $token);
        if ($thumbnail)
            $update_data['thumbnail_url'] = $thumbnail;
        if ($poster_url)
        $update_data['poster_url'] = $poster_url;
      if($fmmid)
        $update_data['type_id'] = $category_id->category;
        //echo "<pre>";print_r($update_data);die;
        $this->db->where('id', $insert_id);
        $this->db->update('tv_serial_episodes', $update_data);
        //echo $this->db->last_query();die;
        page_alert_box('success', 'Action performed', 'File updated successfully');
        backend_log_genration($this, 'Video Updated S.No -: ' . $insert_id, 'EDIT_VIDEO');
       // redirect_to_back();
    } //end tv seriess
    else{
          if (!empty($_FILES['video_file']['name']) || $this->input->post("custom_s3_url")) {
                    $url = $this->input->post("custom_s3_url");
                    if ($url) {
                        $file = explode(".com/", $url)[1];
                    } else if (!empty($_FILES['video_file']['name'])) {
                        $time = time();
            $target_location = getcwd() . '/uploads/bitrate';//1920*1280
            $original = Upload("video_file", $target_location . "/", $time . "_1920x1280");
            if ($original) {
                $file = $this->s3_upload->upload_s3($target_location . '/' . $original, $id, "file_manager/media_library/original_direct/");
                $file=str_replace( 'https://'.$this->CHANG_BUCKET_KEY . ".s3." . $this->CHANG_REGION . ".amazonaws.com",$this->CHANG_CLOUDFRONT, $file);

                if ($original)
                    unlink($original);
            }
        }
    } else if ($this->input->post('video_file')) {
        $file = $this->input->post('video_file');
    }
    if (!empty($_FILES['thumbnail']['name'])) {
        $thumbnail = amazon_s3_upload($_FILES['thumbnail'], "file_manager/media_library", $id);
        $thumbnail=str_replace( 'https://'.$this->CHANG_BUCKET_KEY . ".s3." . $this->CHANG_REGION . ".amazonaws.com",$this->CHANG_CLOUDFRONT, $thumbnail);
    }

    if (!empty($_FILES['poster']['name'])) {
        $poster_url = amazon_s3_upload($_FILES['poster'], "file_manager/media_library", $id);
        $poster_url=str_replace( 'https://'.$this->CHANG_BUCKET_KEY . ".s3." . $this->CHANG_REGION . ".amazonaws.com",$this->CHANG_CLOUDFRONT, $poster_url);
    } 
      
        $update_data = array(
              'episode_title' => $this->input->post('title'),
            'episode_description' => $this->input->post('episode_description'),
            'video_type' => $video_type,
            'vdc_id' => $this->input->post("videocript_id"),
            'ppv' => $this->input->post('ppv'),
            'release_date' => $this->input->post("published_date"),
            'view_mode' => $this->input->post("movie_view"),
            'ppv_status' => $this->input->post("movie_view"),
            'is_drm_protected' => $this->input->post("is_drm_protected"),
          //  'thumbnail_url' =>$thumbnail,
            'episode_url' => $file,
            'ep_no' => $this->input->post("episode_no"),
            'skip_intro'=>($this->input->post('skip_intro'))?$this->input->post('skip_intro'):0,
           'skip_time'=>($this->input->post('skip_time'))?$this->input->post('skip_time'):0              
        );
          if ($file)
            $update_data['episode_url'] = $file; //aes_cbc_encryption($file, $token);
        if ($thumbnail)
            $update_data['thumbnail_url'] = $thumbnail;
        if ($poster_url)
        $update_data['poster_url'] = $poster_url;
         if($fmmid)
        $update_data['type_id'] = $category_id->category;

        //pre($update_data); die;
                           
        $this->db->where('id', $insert_id);
        $this->db->update('premium_episodes', $update_data);
        page_alert_box('success', 'Action performed', 'File updated successfully');
        backend_log_genration($this, 'Video Updated S.No -: ' . $insert_id, 'EDIT_VIDEO');
        //redirect_to_back();
     } //end web series
    } //end validation 
}//end post
app_permission("app_id",$this->db);
$f_list = $this->db->get("application_meta")->result_array();
$view_data['f_lists'] = json_decode($f_list[0]['functionality']);

$type = $this->uri->segment('6');
$view_data['episode_type'] = $type;
$view_data['video_detail'] = $this->Library_model->get_episod_file_by_id($id,$type);
    $data['page_data'] = $this->load->view('premium_media_library/edit_episode', $view_data, TRUE);
echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);

}


    public function check_video($title, $bitrate, $v_url, $drm, $dwn, $plfm)
    {
            $data = array('title' => $title, 'bitrate_channel' => $bitrate, 'custom_s3_url' => $v_url, 'drm_enabled' => $drm, 'platform' => $plfm, 'file_size' => '0', 'video_length' => '0', 'download_mode' => $dwn, 'opted_dwnld_vod' => $bitrate);
            $accesskey= base64_encode(VC_ACCESS_KEY);
            $secret=base64_encode(VC_SECRET_KEY); //pre($vc_key); pre($accesskey); pre($secret); die;
            $headers = array('Content-Type: application/json',"accessKey:$accesskey","secretKey:$secret");
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://api.videocrypt.com/addVideo");
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            $data = curl_exec($ch);
            curl_close($ch);
            $data = json_decode($data);
            // pre($data);
            // pre("df");
              // die;
            if (!empty($data->data[0]->video_id)) {
                return $data->data[0]->video_id;
            } else {
                return $data = "";
            }
    }


}
