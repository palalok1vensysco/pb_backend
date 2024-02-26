<?php
use Aws\S3\S3Client;
use Aws\MediaPackageVod\MediaPackageVodClient;
defined('BASEPATH') OR exit('No direct script access allowed');

class Movies extends MX_Controller {

    function __construct() {
        parent::__construct();
        /* !!!!!! Warning !!!!!!!11
         *  admin panel initialization
         *  do not over-right or remove auth_panel/auth_panel_ini/auth_ini
         */
        modules::run('auth_panel/auth_panel_ini/auth_ini');
        $this->load->library(['form_validation','s3_upload']);
        $this->load->helper(['aes', 'aul','services','cookie','custom']);
        $this->load->model("Movies_model");
        $this->load->model("guru_model");
    }

    public function amazon_s3_upload($name, $aws_path) {
        $_FILES['file'] = $name;
        require_once FCPATH . 'aws/aws-autoloader.php';      

        $s3Client = new S3Client([
            'version' => 'latest',
            'region' => AMS_REGION,
            'credentials' => [
                'key' => AMS_S3_KEY,
                'secret' => AMS_SECRET,
            ],
        ]);
        $result = $s3Client->putObject(array(
            'Bucket' => AMS_BUCKET_NAME,
            'Key' => $aws_path . '/' . rand(0, 7896756) . str_replace([':', ' ', '/', '*', '#', '@', '%'], "_", $_FILES["file"]["name"]),
            'SourceFile' => $_FILES["file"]["tmp_name"],
            'ContentType' => $_FILES["file"]["type"],
            'ACL' => 'public-read',
            'StorageClass' => 'REDUCED_REDUNDANCY',
            'Metadata' => array('param1' => 'value 1', 'param2' => 'value 2'),
        ));
        $data = $result->toArray();
        return $data['ObjectURL'];
    }

    private function video_operation($name,$is_drm=0) {
        $time = time();
        $target_location = getcwd() . '/uploads/bitrate';
        //1920*1280
        $all_names[] = $original = Upload($name, $target_location . "/", $time . "_1920x1280");
        //pre($original);
        $oldmask = umask(0);
        //1280*720
        $all_names[] = "b"; //$this->video_bit_rate($original, 1280, 720, $target_location, $time);
        //640*480
        $all_names[] = "c"; //$this->video_bit_rate($original, 640, 480, $target_location, $time);
        //320*240
        $all_names[] = "d"; //$this->video_bit_rate($original, 320, 240, $target_location, $time);

        /* Encryption Start on files */
        $s3_files = array("original" => "", "encrypted_url" => array());
        
        foreach ($all_names as $key => $current_name) {
            $encrypted_file = "a"; //aes_cbc_encryption_file($target_location . '/' . $current_name);
            if ($encrypted_file) {
                $enc = array();
                $enc["url"] = "https://mahua-tv.s3.ap-south-1.amazonaws.com/file_library/videos/original/1588012383_1920x1280"; 
                $enc['name'] = "1920x1280.mp4"; 
                $enc['size'] = "1.01 MB"; 
                $s3_files["encrypted_url"][] = $enc;
            } 
            if ($key == 0) {
                if ($this->input->post("custom_movie_url"))
                    $s3_files['original'] = str_replace('%2F', "/", $this->input->post("custom_movie_url"));
                else
                    $s3_files["original"] = $this->s3_upload->upload_s3($target_location . "/" . $current_name, "file_library/videos/original/");
            }
            if (file_exists($target_location . "/" . $current_name) && !$this->input->post("custom_s3_url"))
                if(is_file($target_location . "/" . $current_name)){
                    unlink($target_location . "/" . $current_name); 
                }
        }
        /* Encryption End on files */
        umask($oldmask);
        /* creating job of original file in s3 */
        if ($s3_files["original"])
            modules::run('auth_panel/live_module/media_convert/index', $s3_files["original"], "file_library/videos/vod/");
        if ($is_drm==1)
            modules::run('auth_panel/live_module/media_convert/create_job_dash', $s3_files["original"], "file_library/videos/vod/");
        return $s3_files;
    }
    

    public function movies_list() {
        $data['page_title'] = "Movie List";
        $view_data['page'] = 'movies_list';
        $view_data['updated_at'] = $this->db->where('id',1)->get('video_meta')->row()->updated_at;
        $view_data['videos'] = $this->Movies_model->get_video_list();
         $view_data['sub_category'] = $this->Movies_model->get_category();
        $view_data['categories'] = $this->Movies_model->get_categories();
         $view_data['sub_caegories'] = $this->Movies_model->get_sub_category();
        $data['page_data'] = $this->load->view('movies/movies_list', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function add_movies() { 
        if ($this->input->post()) {
            $this->form_validation->set_rules('sub_category', 'Sub Category Name', 'required');
            if ($this->form_validation->run() == FALSE) {
            } else {
                $token = random_token();
                $file = $thumbnail = '';
                $url_json = array();

                
                if (!empty($_FILES['thumbnail_url']['name'])) {
                   $thumbnail_url = $this->amazon_s3_upload($_FILES['thumbnail_url'], "thumbnail_url/Thumbnail");
                } else {
                    $thumbnail_url = '';
                }
                if (!empty($_FILES['poster_url']['name'])) {
                   $poster_url = $this->amazon_s3_upload($_FILES['poster_url'], "poster_url/Movies");

                } else {
                    $poster_url = '';
                }
                
                 if ($this->input->post('related_artist') != '') {
                    $related_artist = implode(",", $this->input->post('related_artist'));
                } else {
                    $artist_name = $this->Movies_model->get_default_artist();
                    $related_artist = $artist_name['id'];
                }
                 if ($this->input->post('related_sub') != '') {
                    $related_sub = implode(",", $this->input->post('related_sub'));
                } else {
                    
                    $related_sub = '0';
                }
                $insert_data = array(
                    'movie_category' => ucwords($this->input->post('sub_category')),
                    'movie_url' => $this->input->post('movie_url'),
                    'age_18' => ucwords($this->input->post('age_restrict')),
                    'movie_is_cover' => ucwords($this->input->post('is_cover')),
                    'movie_artists' => $related_artist,
                    'movie_thumbnail_url' => $thumbnail_url,
                    'movie_poster_url' => $poster_url,
                    'movie_trailer_url' => $this->input->post('movie_trail'),
                    'url_t_type' => $this->input->post('url_t_type'),
                     'url_type' => $this->input->post('url_type'),
                    'movie_view_type' => ucwords($this->input->post('movie_view')),
                    'movie_title' => $this->input->post('movie_title'),
                    'movie_description' => $this->input->post('movie_desc'),
                    'movie_plans' =>  ucwords($this->input->post('movie_view')),
                    'creation_time' => milliseconds(),
                    'uploaded_by' => $this->session->userdata('active_backend_user_id'),
                    'app_id' =>  $temp_app_id
                );
                if(defined("APP_ID") && APP_ID)
                    $insert_data['app_id'] = APP_ID;
                $id = $this->Movies_model->insert_movie($insert_data);
                page_alert_box('success', 'Video Added', 'New video added successfully');
                redirect('admin-panel/edit-movies/'.$id);
            }
        }
        $view_data['sub_category'] = $this->Movies_model->get_category();//pre($view_data['sub_category']);
        $view_data['categories'] = $this->Movies_model->get_categories();
         $view_data['premium_plan'] = $this->Movies_model->get_plans();//pre($view_data['premium_plan']);
         $view_data['sub_caegories'] = $this->Movies_model->get_sub_category();//pre($view_data['sub_caegories']);
        $view_data['guru'] = $this->guru_model->get_guru_list();//die;
        $view_data['page'] = 'add_movies';
        $data['page_data'] = $this->load->view('movies/add_movies', $view_data, TRUE);

        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function edit_movie($id){
        if ($this->input->post()) {
            $this->form_validation->set_rules('movie_title', 'Movie title', 'required');
            $this->form_validation->set_rules('movie_desc', 'Movie description', 'required');
            if ($this->form_validation->run() == FALSE) {
            } else {
                $token = random_token();
                $file = $thumbnail = '';
                $url_json = array();
                 if ($this->input->post('related_artist') != '') {
                    $related_artist = implode(",", $this->input->post('related_artist'));
                } else {
                    $artist_name = $this->Movies_model->get_default_artist();
                    $related_artist = $artist_name['id'];
                }
                 if ($this->input->post('related_sub') != '') {
                    $related_sub = implode(",", $this->input->post('related_sub'));
                } else {
                    
                    $related_sub = '0';
                }

                $update_data = array(
                    'id' => $this->input->post('id'),
                    'movie_category' => ucwords($this->input->post('sub_category')),
                    'movie_url' => $this->input->post('movie_url'),
                    'movie_trailer_url' => $this->input->post('movie_trail'),
                    'movie_language' => ucwords($this->input->post('movie_language')),
                    'age_18' => ucwords($this->input->post('age_restrict')),
                    'movie_is_cover' => ucwords($this->input->post('is_cover')),
                    'movie_artists' => $related_artist,
                    'url_t_type' => $this->input->post('url_t_type'),
                    'url_type' => $this->input->post('url_type'),
                    'movie_view_type' => ucwords($this->input->post('movie_view')),
                    'movie_release' => $this->input->post('movie_release'),
                    'movie_title' => $this->input->post('movie_title'),
                    'movie_description' => $this->input->post('movie_desc'),
                    'movie_publish' => ucwords($this->input->post('movie_publish')),
                    'movie_download' => ucwords($this->input->post('movie_download')),
                    'movie_plans' =>  ucwords($this->input->post('movie_view')),
                    'modified_time' => milliseconds(),
                    'uploaded_by' => $this->session->userdata('active_backend_user_id')
                );

                if (!empty($_FILES['thumbnail_url']['name'])) {

                   $thumbnail_url = $this->amazon_s3_upload($_FILES['thumbnail_url'], "thumbnail_url/Thumbnail");
                   $update_data['movie_thumbnail_url']=$thumbnail_url;

                } 
                if (!empty($_FILES['poster_url']['name'])) {
                   $poster_url = $this->amazon_s3_upload($_FILES['poster_url'], "poster_url/Movies");
                   $update_data['movie_poster_url']=$poster_url;
                }
                $this->db->where('id',$id)->update('movies',$update_data);
              page_alert_box('success', 'Updated', 'Movie has been updated successfully');
            redirect('admin-panel/list-movies');
        }  
        }
        $data['page_title'] = "Movie Edit";
        $view_data['page'] = 'movies_list';

        $cat_id ='1';
         $view_data['sub_category'] = $this->Movies_model->get_category();
        $view_data['categories'] = $this->Movies_model->get_categories();
        
         $view_data['sub_caegories'] = $this->Movies_model->get_sub_category();
        $view_data['frame'] = $this->Movies_model->get_time_frames($id,$cat_id);
       $view_data['category'] = $this->Movies_model->get_category();
        $view_data['guru'] = $this->guru_model->get_guru_list();
        $view_data['video'] = $this->Movies_model->get_video_by_id($id);
        $view_data['premium_plan'] = $this->Movies_model->get_plans();
        $data['page_data'] = $this->load->view('movies/edit_movies', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function time_frame()
    {
        if ($this->input->post()) {
            $this->form_validation->set_rules('frame_type', 'FRAME', 'required');
            if ($this->form_validation->run() == FALSE) {
            } else {
                $token = random_token();
                $file = $thumbnail = '';
                $url_json = array();
                $cat_id ='1';
                $insert_data = array(
                        'movie_id' => $this->input->post('id')??0,
                    'frame_type' => $this->input->post('frame_type'), //in days...
                    'category_id' => $cat_id,
                    'hrs' => $this->input->post('hours'),
                    'mins' => $this->input->post('minutes'),
                    'sec' => $this->input->post('seconds'),
                    'creation_time' => milliseconds(),
                    'uploaded_by' => $this->session->userdata('active_backend_user_id')
                );
                $frame_id = $this->Movies_model->insert_frame($insert_data);
                if (!empty($_FILES['frame_type']['name'])) {
                   $frame_type = $this->amazon_s3_upload($_FILES['frame_type'], "frame_type/Advertisement");
                   $drm_encrypted_url = "file_library/videos/vod_drm_encrypted/$frame_id/";
                   
                    $s3_files = $this->video_operation("frame_type");
                    if ($s3_files) {
                        $video_url=$file = $s3_files['original'];
                        $file = convert_normal_to_m3u8($file);
                        $file = explode(".com/", $file)[1];
                        $url_json = $s3_files['encrypted_url'];
                    }
                } else {
                    $frame_type = '';
                }

                foreach ($url_json as $key => $value) {
                    $url_json[$key]['url'] = aes_cbc_encryption($value['url'], $token);
                }
                $update_data = array(
                    'add_url' => aes_cbc_encryption($file, $token),
                    'encrypted_urls' => json_encode($url_json),
                    'token' => $frame_id . "_" . '6' . "_" . $token
                );
                $this->db->where('id',$frame_id)->update('time_frame',$update_data);

                page_alert_box('success', 'Added', 'New Time Frame added successfully');
                //redirect($_SERVER['HTTP_REFERER']);
                redirect(AUTH_PANEL_URL . 'news/news/news_list');
            }
        }
        
    }

    public function time_frame_delete($id)
    {
        $delete_video = $this->Movies_model->delete_frame($id);
            page_alert_box('success', 'Time Frame deleted', 'Time Frame has been deleted successfully');
            redirect($_SERVER['HTTP_REFERER']);

    }

    public function get_request_for_csv_download() {
        $this->ajax_all_video_list();
    }

    public function ajax_all_video_list() {
        // storing  request (ie, get/post) global array to a variable
        $output_csv = false;
        $requestData = $_REQUEST;
        if (isset($_POST['input_json'])) {
            $requestData = json_decode($_POST['input_json'], true);
            $output_csv = true;
        }
        $columns = array(
            // datatable column index  => database column name
            0 => 'id',
            1 => 'movie_title',
            2 => 'movie_artists',
            3 => 'movie_description',
            4 => 'movie_thumbnail_url',
            5 => 'movie_url',
           // 6 => 'movie_category',

            //5 => 'likes',
           // 6 => 'views',
//			7 => 'creation_time',
         //   8 => 'is_sankirtan',
        //    9 => 'is_popular',
            10 => 'movie_publish'
        );
        
        $query = "SELECT count(id) as total
				  FROM movies where status=0";

        $query = $this->db->query($query);
        $query = $query->row_array();
        $totalData = (count($query) > 0) ? $query['total'] : 0;
        $totalFiltered = $totalData;

        $sql = "SELECT m.id as movie_id,DATE_FORMAT(FROM_UNIXTIME(m.creation_time/1000), '%d-%m-%Y') as creation_time,movie_title,movie_artists,art.name,sc.sub_category_name,sc.id,movie_view_type,
                movie_description,movie_thumbnail_url,movie_poster_url,movie_language,movie_category,movie_release,movie_url,m.token,movie_publish,sc.sub_category_name,m.status 
                FROM movies as m left join sub_category as sc on sc.id=m.movie_category 
                left join artists as art on art.id=m.movie_artists
                where m.status != 2";
         $sql .=  (defined("APP_ID") ? "" . app_permission("m.app_id") . "" : "");

        if (!empty($requestData['columns'][1]['search']['value'])) {
            $sql .= " AND movie_title LIKE '" . $requestData['columns'][1]['search']['value'] . "%' ";
        }

       if (!empty($requestData['columns'][2]['search']['value'])) {
           $sql .= " AND art.name LIKE '" . $requestData['columns'][2]['search']['value'] . "%' ";
       }
        if (!empty($requestData['columns'][3]['search']['value'])) {
            $sql .= " AND m.movie_description LIKE '" . '%' . $requestData['columns'][3]['search']['value'] . "%' ";
        }
        if (isset($requestData['columns'][7]['search']['value']) && $requestData['columns'][7]['search']['value'] != "") {  //salary
            $sql .= " AND movie_view_type = " . $requestData['columns'][7]['search']['value'];
        }
        if (isset($requestData['columns'][8]['search']['value']) && $requestData['columns'][8]['search']['value'] != "") {  //salary
            $sql .= " AND m.status = " . $requestData['columns'][8]['search']['value'];
        }
        
         if (!empty($requestData['columns'][6]['search']['value'])) 
         {
            $cat_id = $requestData['columns'][6]['search']['value'];
            $sql .= " AND FIND_IN_SET($cat_id,sc.id) ";
        }

        if (!empty($requestData['columns'][5]['search']['value'])) {
            if ($requestData['columns'][5]['search']['value'] == 1) {
                $sql .= " AND is_popular=1";
            }
            if ($requestData['columns'][5]['search']['value'] == 2) {
                $sql .= " AND is_popular=0";
            }
            if ($requestData['columns'][5]['search']['value'] == '') {
                $sql .= " AND is_popular=0";
            }
        }

        if (!empty($requestData['columns'][10]['search']['value'])) {  //salary
            $date = explode(',', $requestData['columns'][10]['search']['value']);
            $start = $date[0];
            $end = $date[1];
            $sql .= "  AND  published_date >= '$start' and published_date <= '$end'";
        }


        $query = $this->db->query($sql)->result();

        $totalFiltered = count($query); // when there is a search parameter then we have to modify total number filtered rows as per search result.
        if(isset($requestData['start']))
        $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   "; // adding length
        $result = $this->db->query($sql)->result();

        $data = array();

        if ($output_csv == true) {
            // for csv loop
            $head = array('Sr.No', 'Movie Title','Movies Id','Description', 'Status', 'Registered On');
            $start = 0;
            foreach ($result as $r) {
                $nestedData = array();
                $nestedData[] = ++$start; //$r->id;
                $nestedData[] = $r->movie_title; 
                $nestedData[] = $r->movie_id;
                $nestedData[] = $r->movie_description;
                $nestedData[] = ($r->status == 0 ) ? 'Active' : 'Disabled';
                $nestedData[] = $r->creation_time ? $r->creation_time: "--NA--";
                $data[] = $nestedData;
            }
            if ($output_csv == true) {
                $this->all_movies_to_csv_download($data, $filename = "Category.csv", $delimiter = ";", $head);
                die;
            }
        }

        foreach ($result as $r) {
            
            if($r->movie_view_type == 0){
                $r->movie_view_type = "Paid";
            }else{
                $r->movie_view_type = "Free";
            }

            $nestedData = array();
            $nestedData[] = "<input name='selected_id' type='checkbox' value='$r->id'/>&nbsp&nbsp" . ++$requestData['start'];
			$nestedData[] = $r->movie_id;
            $nestedData[] = $r->movie_title;

             $get_artist = $this->db->select('name')->where_in('id', explode(',', $r->movie_artists))->get('artists')->result_array();
            $category_arr = $artist_arr = array();
            foreach ($get_artist as $artist) {
                $artist_arr[] = $artist['name'];
            }
            $artistt = (implode(',', $artist_arr));
        
             $nestedData[] = $artistt;
            $short_desc = $this->word_formatter($r->movie_description);
            $nestedData[] = $short_desc . "...";
            $nestedData[] = "<img width='50px' height='50px' src='" . $r->movie_thumbnail_url . "'></a>";
             $nestedData[] = "<img width='50px' height='50px' src='" . $r->movie_poster_url . "'></a>";
            $website_video_url = base_url() . "video/$r->id";
            $nestedData[] = $r->sub_category_name;
            $nestedData[] = "<sapn class='bold'>".$r->movie_view_type."</span>";
            $nestedData[] = date('Y-m-d',strtotime($r->movie_release));
            $status = ($r->status == 0) ? 'Active' : 'Disabled';
             $alert_status = ($r->status == 0) ? 'success' : 'danger';
            $nestedData[] =" <a class='btn-xs bold btn btn-$alert_status view_vid' onclick=\"return confirm('Are you sure you want to $alert_status?')\" href='" . AUTH_PANEL_URL . "movies/Movies/lock_unlock_movies/" . $r->movie_id . '/' . $r->status . "'> $status</a>&nbsp;";
            $nestedData[] = "
            <a class='btn-xs bold btn btn-primary' onclick=\"return confirm('Are you sure you want to edit?')\" href='" . base_url('admin-panel/edit-movies/') . $r->movie_id . "'><i class='fa fa-edit'></i></a>&nbsp;
				<a class='btn-xs bold btn btn-danger' onclick=\"return confirm('Are you sure you want to delete?')\" href='" . AUTH_PANEL_URL . "movies/Movies/delete_video/" . $r->movie_id . "'><i class='fa fa-trash-o'></i></a>&nbsp;
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

    public function lock_unlock_movies($id, $status) {
        if ($status == 0) {
            $header = 'Disable';
            $title = 'Season has been locked successfully';
        }
        if ($status == 1) {
            $header = 'Enable';
            $title = 'Season has been unlocked successfully';
        }
        $delete_videos = $this->Movies_model->lock_unlock_movie($id, $status);
        page_alert_box('success', $header, $title);
        redirect(AUTH_PANEL_URL . 'movies/movies/movies_list');
    }

    private function word_formatter($string) {
        $string = explode(" ", strip_tags($string));
        if ($string && count($string) > 25) {
            $string = array_slice($string, 0, 25, true);
        }
        return implode(" ", $string);
    }
/*
    public function all_video_to_csv_download($array, $filename = "Video.csv", $delimiter = ";", $header) {
        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '";');
        $f = fopen('php://output', 'w');
        fputcsv($f, $header);
        foreach ($array as $line) {
            fputcsv($f, $line);
        }
    }

    public function view_video($id) {
//        $view_data['mobile_menu_category'] = $this->Movies_model->get_mobile_menu_category();
//        $view_data['android_tv_category'] = $this->Movies_model->get_android_tv_category();
        $view_data['video'] = $this->Movies_model->get_video_by_id($id);
        $data['page_data'] = $this->load->view('videos/view_video', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }
*/
      public function delete_all_selected_data() {
        $array = $this->input->post('selected_ids');
        foreach ($array as $id) {
            $this->db->where('id', $id);
            $this->db->update('movies', ['status' => 2]);
        }
        echo json_encode(array("data" => 1));
    }

    

    public function delete_video($id) {
        $delete_video = $this->Movies_model->delete_video($id);
        page_alert_box('success', 'Video deleted', 'Video has been deleted successfully');
        redirect(BASE_URL . 'admin-panel/list-movies');

//		redirect(AUTH_PANEL_URL . 'videos/video_control/video_list');
    }


    private function cleardir() {

        $folder_path = "resize-image";

// List of name of files inside
        // specified folder
        $files = glob($folder_path . '/*');

// Deleting all the files in the list
        foreach ($files as $file) {

            if (is_file($file)) {

                // Delete the given file
                unlink($file);
            }
        }
    }
    
    public function ajax_generate_video_url($type=1)
    {
        $url = base_url("/data_model/live_modules/on_request_create_video_link");
        $document = ['file_url' => $url, 'name' => $_POST['token'],'type'=>$type];
        $res=file_curl_contents($document);
        $type='application/x-mpegURL';
        $video_type=$_POST['video_type'];
        $token= $_POST['token'];
        $video =$res['data'];
        $token_str=explode("_",$token);
        $token=end($token_str);
        $bucket_path='https://'.AMS_BUCKET_NAME.'.s3.'.AMS_REGION.'.amazonaws.com/';
        if($video_type==1)
        {
            $type='video/youtube';
            $video_url=aes_cbc_decryption($video,$token);
        }
        else
        {
            $type='application/x-mpegURL';
            $video_url=aes_cbc_decryption($video,$token);
        }
        echo json_encode(['status'=>true,'message'=>'url listed.','data'=>['url'=>$video_url,'type'=>$type]]);
        die;
    }
    public function all_movies_to_csv_download($array, $filename = "Users.csv", $delimiter = ";", $header) {
        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '";');
        $f = fopen('php://output', 'w');
        fputcsv($f, $header);
        foreach ($array as $line) {
            fputcsv($f, $line);
        }
    }

}

