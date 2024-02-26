<?php
use Aws\S3\S3Client;
use Aws\MediaPackageVod\MediaPackageVodClient;
defined('BASEPATH') OR exit('No direct script access allowed');

class Premium_tv_serials extends MX_Controller {

    function __construct() {
        parent::__construct();
        /* !!!!!! Warning !!!!!!!11
         *  admin panel initialization
         *  do not over-right or remove auth_panel/auth_panel_ini/auth_ini
         */
        modules::run('auth_panel/auth_panel_ini/auth_ini');
        // $this->load->library(['form_validation','s3_upload']);
        // $this->load->helper('aes');
        $this->load->library(['form_validation','s3_upload']);
        $this->load->helper(['aes', 'aul','services','cookie','custom']);
        //$this->load->helper(['compress_helper']);
        //$this->load->helper(['compress', 'aul','services','cookie','custom']);
        $this->load->model("Tv_serial_model");
        $this->load->model("guru_model");
        $this->load->model("Movies_model");
        
       
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
                $enc["url"] = "https://mvfplayerbucket.s3.ap-south-1.amazonaws.com/file_library/videos/original/1588012383_1920x1280"; //$this->s3_upload->upload_s3($encrypted_file, "file_library/videos/encrypted/");
//                $enc['name'] = explode("_", $current_name);
                $enc['name'] = "1920x1280.mp4"; //end($enc['name']);
                $enc['size'] = "1.01 MB"; //$this->getFormatSizeUnits(filesize($encrypted_file));
                $s3_files["encrypted_url"][] = $enc;
            } 
            if ($key == 0) {
                if ($this->input->post("custom_movie_url"))
                    $s3_files['original'] = str_replace('%2F', "/", $this->input->post("custom_movie_url"));
                else
                    $s3_files["original"] = $this->s3_upload->upload_s3($target_location . "/" . $current_name, "file_library/videos/original/");
            }
            if (file_exists($target_location . "/" . $current_name) && !$this->input->post("custom_s3_url"))
                unlink($target_location . "/" . $current_name);
        }
        //pre($s3_files['original']);die;
        /* Encryption End on files */
        umask($oldmask);
        /* creating job of original file in s3 */
        if ($s3_files["original"])
            modules::run('auth_panel/live_module/media_convert/index', $s3_files["original"], "file_library/videos/vod/");
        if ($is_drm==1)
            modules::run('auth_panel/live_module/media_convert/create_job_dash', $s3_files["original"], "file_library/videos/vod/");
        return $s3_files;
    }

    public function add_tv_serial() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('season_title', 'TV Serial Title', 'trim|required');
             $this->form_validation->set_rules('description', 'TV Serial Description', 'required');
            $this->form_validation->set_rules('category_ids', 'Sub Category', 'trim|required');
            
            $this->form_validation->set_rules('published_date', 'Published date from', 'required');
            if ($this->form_validation->run() == FALSE) {
                $view_data['validation_error'] = '1';
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
                if (!empty($_FILES['season_thumbnail']['name'])) {
                   $season_thumbnail = $this->amazon_s3_upload($_FILES['season_thumbnail'], "season_thumbnail/Thumbnail");
                } else {
                    $season_thumbnail = '';
                }
                if (!empty($_FILES['poster']['name'])) {
                   $poster = $this->amazon_s3_upload($_FILES['poster'], "season_poster/poster");
                } else {
                    $poster = '';
                }
                
                if ($this->input->post('related_sub') != '') {
                    $related_sub = implode(",", $this->input->post('related_sub'));
                } else {
                        $related_sub = '0';
                }
                $insert_data = array(
                    'season_title' => $this->input->post('season_title'),
                    'url_type' => $this->input->post('url_type'),
                    'category_ids' => ucwords($this->input->post('category_ids')),
                    'language' => ucwords($this->input->post('language')),
                    'age_18' => ucwords($this->input->post('age_restrict')),
                    'author_id' => $related_artist,
                   // 'season_type' => $this->input->post('season_type'),
                    'description' => $this->input->post('description'),
                    'published_date' => (strtotime($_POST['published_date']) * 1000),
                    'promo_video' => $this->input->post('promo_video'),
                    'season_thumbnail' => $season_thumbnail,
                    'poster' => $poster,
                    'view_mode' => ucwords($this->input->post('view')),
                    'subscription' => $related_sub,
                );


                if(defined("APP_ID") && APP_ID)
                    $insert_data['app_id'] = APP_ID;

                $tv_serial_id = $this->Tv_serial_model->insert_tv_serial($insert_data);
                page_alert_box('success', 'Added', 'TV Serial added successfully');
                redirect(AUTH_PANEL_URL . 'videos/premium_tv_serials/add_tv_serial');
            }
        }

        // $view_data['categories'] = $this->Tv_serial_model->get_categories();
         $view_data['categories'] = $this->Movies_model->get_categories();
        
         $view_data['sub_caegories'] = $this->Movies_model->get_sub_category();
        $view_data['authors'] = $this->Tv_serial_model->get_authors();
        $view_data['premium_plan'] = $this->Tv_serial_model->get_plans();
        $view_data['page'] = 'premium_tv_serials';
        $data['page_data'] = $this->load->view('tv_serial/add_tv_serial', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function view_tv_serial() {

        $id=$_GET['id'];
        $token=$_GET['token'];
        $data_id=$_GET['data_id'];
        $data_type=$_GET['data_type'];
        $view_data['categories'] = $this->Tv_serial_model->get_categories();
        $view_data['mobile_menu_category'] = $this->Tv_serial_model->get_mobile_menu_category();
        $view_data['authors'] = $this->Tv_serial_model->get_authors();
        $view_data['season_details'] = $this->Tv_serial_model->get_season_details($id);
        $view_data['page'] = 'premium_tv_serials';
        $view_data['updated_at'] = $this->db->where('id',1)->get('video_meta')->row()->updated_at;
        $view_data['videos'] = $this->Movies_model->get_video_list();
        $data['page_data'] = $this->load->view('tv_serial/view_tv_serial', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function edit_tv_serial($tv_serial_id) {
        if ($this->input->post()) {
            $this->form_validation->set_rules('season_title', 'TV Serial Title', 'trim|required');
             $this->form_validation->set_rules('description', 'TV Serial Description', 'required');
            
            if ($this->form_validation->run() == FALSE) {
                
            } else {
               // $id = $this->input->post('id');
                /*
                 * token is combined by three variables and combined by "_" underscore
                 * first one-primary id
                 * socond one-video type
                 * third-key //16 digit
                 */
                $token = random_token();
                $file = $thumbnail = '';
                $url_json = array();

                if ($this->input->post('related_artist') != '') {
                    $related_artist = implode(",", $this->input->post('related_artist'));
                } else {

                   

                    $artist_name = $this->Movies_model->get_default_artist();
                    $related_artist = $artist_name['id'];


                
                }
               
             /*   if ($this->input->post('related_sub') != '') {
                    $related_sub = implode(",", $this->input->post('related_sub'));
                } else {
                        $related_sub = '0';
                }*/
                $update_data = array(
                    'season_title' => $this->input->post('season_title'),
                     'url_type' => $this->input->post('url_type'),
                    'promo_video' => $this->input->post('promo_video'),
                    'category_ids' => ucwords($this->input->post('category_ids')),
                    'language' => ucwords($this->input->post('language')),
                    'age_18' => ucwords($this->input->post('age_restrict')),
                    'author_id' => $related_artist,
                    'season_type' => $this->input->post('season_type'),
                    'description' => $this->input->post('description'),
                    'published_date' => $this->input->post('published_date'),
                    
                    'view_mode' =>$this->input->post('view'),
                  //  'subscription' => $related_sub,


                );

               //  echo '<pre>'; print_r($update_data);echo '</pre>';
               // die;
                
              
              //  $season_id = $this->Premium_video_model->update_season($insert_data, $id);
                //                echo '<pre>'; print_r($season_array);echo '</pre>';
//                die;
                // $plans=$this->input->post('view');
                // if($plans==2)
                // {
                //  if ($this->input->post('related_sub') != '') {
                //     $related_sub = implode(",", $this->input->post('related_sub'));

                //      $update_data['subscription']=$related_sub;
                // }
                // }
                // else {
                //      $related_sub = '0';
                //       $update_data['subscription']=$related_sub;
                //  } 
                if (!empty($_FILES['season_thumbnail']['name'])) {
                   $season_thumbnail = $this->amazon_s3_upload($_FILES['season_thumbnail'], "season_thumbnail/Thumbnail");
                   $update_data['season_thumbnail']=$season_thumbnail;

                } 
                // if (!empty($_FILES['poster']['name'])) {
                //    $poster = $this->amazon_s3_upload($_FILES['poster'], "season_poster/poster");
                //    $update_data['poster']=$poster;

                // } 
//                if (!empty($_FILES['promo_video']['name'])) {
//                    $promo_video = $this->amazon_s3_upload($_FILES['promo_video'], "promo_video/Seasons");
//                    $drm_encrypted_url = "file_library/videos/vod_drm_encrypted/$season_id/";
//                     $s3_files = $this->video_operation("promo_video");
//                     if ($s3_files) {
//                         $video_url=$file = $s3_files['original'];
//                         $file = convert_normal_to_m3u8($file);
// //                        if ($this->input->post('is_drm_protected')) {
// //                            $change_url = "vod_drm_encrypted/$id";
// //                            $hls_encrypted_url = str_replace("original", $change_url, $file);
// //                        }
//                         $file = explode(".com/", $file)[1];
//                         $url_json = $s3_files['encrypted_url'];
//                     }
                 
//                 foreach ($url_json as $key => $value) {
//                     $url_json[$key]['url'] = aes_cbc_encryption($value['url'], $token);
//                 }
//                 $update_data = array(
//                     'promo_video' => aes_cbc_encryption($file, $token),
//                     'encrypted_urls' => json_encode($url_json),
//                     'token' => $tv_serial_id . "_" . '5' . "_" . $token
//                 );
//             }
               //  echo '<pre>'; print_r($update_data);echo '</pre>';
               // die;
                
                 $this->db->where('id',$tv_serial_id)->update('tv_serial',$update_data);

                // echo '<pre>'; print_r($update_data);echo '</pre>';
               // die;
                
             //   redirect(AUTH_PANEL_URL . 'videos/premium_video/add_season');
                page_alert_box('success', 'Updated', 'TV Serial has been updated successfully');
                redirect($_SERVER['HTTP_REFERER']);
            }
        }
        // $view_data['categories'] = $this->Tv_serial_model->get_categories();
         $view_data['categories'] = $this->Movies_model->get_categories();
        
         $view_data['sub_caegories'] = $this->Movies_model->get_sub_category();
        $view_data['artists'] = $this->Tv_serial_model->get_authors();
        $view_data['premium_plan'] = $this->Tv_serial_model->get_plans();
        $view_data['season_details'] = $this->Tv_serial_model->get_season_details($tv_serial_id);
        $view_data['page'] = 'premium_tv_serials';
        $data['page_data'] = $this->load->view('tv_serial/edit_tv_serial', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function ajax_get_tv_serial_list() {
        // storing  request (ie, get/post) global array to a variable
        $requestData = $_REQUEST;
         $output_csv = $output_pdf = false;
        if (isset($_POST['input_json'])) {
            //$requestData = json_decode($_POST['input_json'], true);
            if (ISSET($_POST['download_pdf'])) {
                $output_pdf = true;
            } else {
                $output_csv = true;
            }
        }  

        $columns = array(
            // datatable column index  => database column name
            0 => 'id',
            1 => 'season_title',
            2 => 'category_ids',
            3 => 'author_id',
            4 => 'view_mode',
            5 => 'status_st'
        );

        $query = "SELECT count(id) as total
                                From tv_serial where status !=2 
                                ";
        $query = $this->db->query($query);
        $query = $query->row_array();
        $totalData = (count($query) > 0) ? $query['total'] : 0;
        $totalFiltered = $totalData;

        $sql = "SELECT ps.id,ps.season_title,ps.description,ps.category_ids,ps.author_id,ps.status status_st,ps.published_date,view_mode,ps.token,sc.sub_category_name as category_name
            From tv_serial as ps
            LEFT JOIN sub_category sc  ON ps.category_ids = sc.id
                where ps.status !=2
                ";
        $sql .=  (defined("APP_ID") ? "" . app_permission("ps.app_id") . "" : "");        
        if (!empty($requestData['columns'][1]['search']['value'])) {
            //salary
            $sql .= " AND ps.season_title LIKE '" . '%' . $requestData['columns'][1]['search']['value'] . "%' ";
        }
        if (!empty($requestData['columns'][2]['search']['value'])) {
            $sql .= " AND ps.description LIKE '" . '%' . $requestData['columns'][2]['search']['value'] . "%' ";
        }
        if (!empty($requestData['columns'][3]['search']['value'])) 
         {
            $cat_id = $requestData['columns'][3]['search']['value'];
            $sql .= " AND FIND_IN_SET($cat_id,sc.id) ";
        }
        // if (!empty($requestData['columns'][4]['search']['value'])) {
        //     $sql .= " AND view_mode =". $requestData['columns'][4]['search']['value'];
        // }
         if (isset($requestData['columns'][4]['search']['value']) && $requestData['columns'][4]['search']['value'] != "") {  //salary
            $sql .= " AND view_mode = " . $requestData['columns'][4]['search']['value'];
        }
        if (isset($requestData['columns'][5]['search']['value']) && $requestData['columns'][5]['search']['value'] != "") {  //salary
            $sql .= " AND ps.status = " . $requestData['columns'][5]['search']['value'];
        }
        $sql.=" ORDER BY " . "ps.id desc";


        $query = $this->db->query($sql)->result();

        $totalFiltered = count($query); // when there is a search parameter then we have to modify total number filtered rows as per search result.
//        $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   "; // adding length
        if(isset($requestData['start']))
        $sql .= "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   "; // adding length

        //print_r($this->db->last_query());
        $result = $this->db->query($sql)->result();
        $data = array();

        if ($output_csv == true) {
            // for csv loop
            $head = array('Sr.No', 'Season Title','Description', 'Status', 'Registered On');
            $start = 0;
            foreach ($result as $r) {
                $nestedData = array();
                $nestedData[] = ++$start; //$r->id;
                $nestedData[] = $r->season_title; 
                $nestedData[] = $this->word_formatter($r->description);
                $nestedData[] = $r->category_name;
                $nestedData[] = $r->published_date ? $r->published_date: "--NA--";
                $data[] = $nestedData;
            }
            //print_r($data);die;
            if ($output_csv == true) {
                $this->all_premium_tv_series_to_csv_download($data, $filename = "Category.csv", $delimiter = ";", $head);
                die;
            }
        }

        $start = 0;
        foreach ($result as $r) {
            if($r->view_mode == 1){
                $r->view_mode = "Free";
            }
            elseif($r->view_mode == 0){
                $r->view_mode = "Premium";
            }

             $short_desc = $this->word_formatter($r->description);
            $status = ($r->status_st == 0) ? 'Active' : 'Disabled';
            $alert_status = ($r->status_st == 0) ? 'success' : 'danger';
             $status1 = ($r->status_st == 0) ? 'Disable' : 'Enable';
            // preparing an array
            $nestedData = array();
            $nestedData[] = ++$start;
            $nestedData[] = $r->id;
            $nestedData[] = $r->season_title;
            $nestedData[] = $short_desc . "...";
            $nestedData[] = $r->category_name;
            $nestedData[] = "<span class='bold'>".$r->view_mode."</span>";
            $pussb_date = (int)$r->published_date;
                $pub_date=date("d-m-Y", ($pussb_date/ 1000) );
            $nestedData[] = $pub_date;
            // $nestedData[] = ($r->status == 0 ) ? '<span class="btn btn-xs bold btn-success">Active</span>' : '<span class="btn btn-xs btn-warning">Locked</span>';

            $nestedData[] = "<a class='btn-xs bold btn btn-$alert_status view_vid' onclick=\"return confirm('Are you sure you want to $status1?')\" href='" . AUTH_PANEL_URL . "videos/premium_tv_serials/lock_unlock_tv_serial/" . $r->id . '/' . $r->status_st . "'>$status</a>&nbsp;";
            $nestedData[] = "
            <a class='btn-xs bold btn btn-success preview' title='View Video' href='" . AUTH_PANEL_URL . "videos/premium_tv_serials/view_tv_serial?id=" . $r->id . "&data_id=".$r->id."&token=".$r->token."&data_type=5'><i class='fa fa-eye'></i></a>&nbsp;
         <a class='btn-xs bold btn btn-primary' onclick=\"return confirm('Are you sure you want to edit?')\" href='" . AUTH_PANEL_URL . "videos/premium_tv_serials/edit_tv_serial/" . $r->id . "' ><i class='fa fa-edit'></i></a>&nbsp;
                
                                    <a class='btn-xs bold btn btn-danger' onclick=\"return confirm('Are you sure you want to Delete?')\" href='" . AUTH_PANEL_URL . "videos/premium_tv_serials/delete_tv_serial/" . $r->id . '/' . $r->status_st . "'><i class='fa fa-trash-o'></i></a>&nbsp;
                ";
            $nestedData[] = "
            <a class='btn-xs bold btn btn-success' title='Add Episode' href='" . AUTH_PANEL_URL . "videos/premium_tv_serials/add_tv_serial_episode/" . $r->id . "'><i class='fa fa-plus'></i>Add Episode</a>&nbsp;&nbsp;&nbsp;
         <a class='btn-xs bold btn btn-primary'href='" . AUTH_PANEL_URL . "videos/premium_tv_serials/episode_list/" . $r->id . "'><i class='fa fa-edit'></i>Manage Episodes</a>&nbsp;
<!--<a class='btn-sm btn btn-success btn-xs bold' href='" . AUTH_PANEL_URL . "videos/premium_tv_serials/getDuration/'>Get Duration</a>;--!>
                
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
    private function word_formatter($string) {
        $string = explode(" ", strip_tags($string));
        if ($string && count($string) > 25) {
            $string = array_slice($string, 0, 25, true);
        }
        return implode(" ", $string);
    }

    public function lock_unlock_tv_serial($id, $status) {
        if ($status == 0) {
            $header = 'Disable';
            $title = 'Season has been locked successfully';
        }
        if ($status == 1) {
            $header = 'Enable';
            $title = 'Season has been unlocked successfully';
        }
        $delete_videos = $this->Tv_serial_model->lock_unlock_tv_serial($id, $status);
        page_alert_box('success', $header, $title);
       // redirect(AUTH_PANEL_URL . 'videos/premium_video/add_season');
         redirect(BASE_URL . 'admin-panel/add-tv-serial');
    }

    public function delete_tv_serial($id) {
        $delete_season = $this->Tv_serial_model->delete_tv_serial($id);
        page_alert_box('success', 'Deleted', 'TV Serial has been deleted successfully');
        //redirect(AUTH_PANEL_URL . 'videos/premium_video/add_season');
         redirect(BASE_URL . 'admin-panel/add-tv-serial');
    }

    public function save_position_tv_serial() {

        $ids = $_POST['ids'];
        $counter = 1;
        foreach ($ids as $id) {
            $this->db->where('id', $id);
            $array = array('position' => $counter);
            $this->db->update('tv_serial', $array);
            $counter++;
        }
        echo json_encode(array('status' => true, 'message' => 'position saved'));
        die;
    }

//XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX--->SEASON BLOG END <---XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
//XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX--->EPISODES BLOG START <---XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

    public function add_tv_serial_episode($id = NULL) {
        if ($this->input->post()) {
            $this->form_validation->set_rules('season_id', 'Season', 'trim|required');
            $this->form_validation->set_rules('episode_title', 'Title', 'trim|required');
            $this->form_validation->set_rules('episode_description', 'Description', 'trim|required');
            if (empty($_FILES['movie_url']['name']) && !$this->input->post('custom_movie_url')) {
                $this->form_validation->set_rules('movie_url', 'File', 'required');
            }
            $season_id = $this->input->post('season_id');

             if (!empty($_FILES['thumbnail1_url']['name'])) {
                   $thumbnail1_url = $this->amazon_s3_upload($_FILES['thumbnail1_url'], "episode_thumbnail_url/Thumbnails");
                } else {
                    $thumbnail1_url = '';
                }
           

            if ($this->form_validation->run() == FALSE) {
               $view_data['validation_error'] = '1';
            } else {
                 /*
                 * token is combined by three variables and combined by "_" underscore
                 * first one-primary id
                 * socond one-video type
                 * third-key //16 digit
                 */
                $token = random_token();
                $file = $thumbnail = '';
                $url_json = array();

                $insert_data = array(
                    'season_id' => $season_id,
                    'episode_title' => $this->input->post('episode_title'),
                     'url_type' => $this->input->post('url_type'),
                    'episode_description' => $this->input->post('episode_description'),
                    'ep_no' => $this->input->post('ep_no'),
                    'thumbnail_url' =>$thumbnail1_url,
                   'episode_url' => $this->input->post('movie_url'),
                    //'runtime' =>  $this->input->post('hours') . ":" . $this->input->post('minutes') . ":" . $this->input->post('seconds'),
                    'release_date' => $this->input->post('released_date'),
                    //'publish' => ucwords($this->input->post('publish')),
                     //'download' => ucwords($this->input->post('download')),

                    'creation_time' => milliseconds(),
                    'uploaded_by' => $this->session->userdata('active_backend_user_id')
                );
               
                $id = $this->Tv_serial_model->insert_episode($insert_data);

               //  if (!empty($_FILES['movie_url']['name']) || $this->input->post("custom_movie_url")) {
               //     //$movie_url = $this->amazon_s3_upload($_FILES['movie_url'], "movie_url/Movies");
                   
               //      $s3_files = $this->video_operation("movie_url");
               //      if ($s3_files) {
               //          $video_url=$movie_url = $s3_files['original'];
               //          $movie_url = convert_normal_to_m3u8($movie_url);
                        
               //          $movie_url = explode(".com/", $movie_url)[1];
               //          $url_json = $s3_files['encrypted_url'];
               //      }
               //  } 

               //  foreach ($url_json as $key => $value) {
               //      $url_json[$key]['url'] = aes_cbc_encryption($value['url'], $token);
               //  }
               //  $update_data = array(
               //      'episode_url' => aes_cbc_encryption($movie_url, $token),
               //      'encrypted_urls' => json_encode($url_json),
               //      'token' => $id . "_" . '0' . "_" . $token
               //  );
               //  //echo '<pre>'; print_r($update_data);echo '</pre>';
               // //die;
               //  $this->db->where('id',$id)->update('tv_serial_episodes',$update_data);

                page_alert_box('success', 'Added', 'Episode added successfully');
                redirect(AUTH_PANEL_URL . 'videos/premium_tv_serials/episode_list/' . $season_id);
            }
//            redirect(AUTH_PANEL_URL . 'videos/premium_video/add_season_episode/' . $season_id);
        }
        $view_data['season_id'] = $id;
        $view_data['page'] = 'premium_tv_serials';
        $data['page_data'] = $this->load->view('tv_serial/add_tv_serial_episode', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }



    public function episode_list($id) {
        $view_data['season_id'] = $id;
        $view_data['page'] = 'premium_tv_serials';
        $data['page_data'] = $this->load->view('tv_serial/episode_list', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function view_episode($id = null) {
        $id=$_GET['id'];
        $token=$_GET['token'];
        $data_id=$_GET['data_id'];
        $data_type=$_GET['data_type'];
        $view_data['episode_details'] = $this->Tv_serial_model->get_episode_by_id($id);
        $view_data['page'] = 'premium_tv_serials';
        $data['page_data'] = $this->load->view('tv_serial/view_tv_serial_episode', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function edit_episode($id) {
        $cat_id ='2';
        $view_data['episode_details'] = $this->Tv_serial_model->get_episode_by_id($id);
        $view_data['frame'] = $this->Tv_serial_model->get_time_frames($id,$cat_id);
        $view_data['page'] = 'premium_tv_serials';
        $data['page_data'] = $this->load->view('tv_serial/edit_tv_serial_episode', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function edit_tv_serial_episode() {
        if ($this->input->post()) {//pre($_POST); pre($_FILES); die;
            $this->form_validation->set_rules('season_id', 'Season', 'trim|required');
            $this->form_validation->set_rules('episode_title', 'Title', 'trim|required');
            $this->form_validation->set_rules('episode_description', 'Title', 'trim|required');

           
            if ($this->form_validation->run() == FALSE) {
                
            } else {
                $id = $this->input->post('id');
                /*
                 * token is combined by three variables and combined by "_" underscore
                 * first one-primary id
                 * socond one-video type
                 * third-key //16 digit
                 */
                $token = random_token();
                $file = $thumbnail = '';
                $url_json = array();

                $update_data = array(
                    'season_id' => $this->input->post('season_id'),
                     'url_type' => $this->input->post('url_type'),
                    'episode_title' => $this->input->post('episode_title'),
                    'episode_description' => $this->input->post('episode_description'),    
                   'runtime' =>  $this->input->post('hours') . ":" . $this->input->post('minutes') . ":" . $this->input->post('seconds'),
                   'ep_no' => $this->input->post('ep_no'),
                    'release_date' => $this->input->post('released_date'),
                     'episode_url' => $this->input->post('movie_url'),
                    'publish' => ucwords($this->input->post('publish')),
                     'download' => ucwords($this->input->post('download')),
                    'modified_time' => milliseconds(),
                    'uploaded_by' => $this->session->userdata('active_backend_user_id')
                );
                
                $season_id=$this->input->post('season_id');

              //  $this->Premium_video_model->update_episode($update_data, $id);

            if (!empty($_FILES['thumbnail_url']['name'])) {
                   $thumbnail1_url = $this->amazon_s3_upload($_FILES['thumbnail_url'], "episode_thumbnail_url/Thumbnails");
                     $update_data['thumbnail_url']=$thumbnail1_url;


                } 

            //    $m_url = $this->input->post('custom_movie_url');
            //     if (!empty($m_url)) {
                   
                
            //      if (!empty($_FILES['movie_url']['name']) || $this->input->post("custom_movie_url")) {
            //        //$movie_url = $this->amazon_s3_upload($_FILES['movie_url'], "movie_url/Movies");
                   
            //         $s3_files = $this->video_operation("movie_url");
            //         if ($s3_files) {
            //             $video_url=$movie_url = $s3_files['original'];
            //             $movie_url = convert_normal_to_m3u8($movie_url);
                        
            //             $movie_url = explode(".com/", $movie_url)[1];
            //             $url_json = $s3_files['encrypted_url'];
            //         }
            //     } 

            //     foreach ($url_json as $key => $value) {
            //         $url_json[$key]['url'] = aes_cbc_encryption($value['url'], $token);
            //     }
            //     $update_data = array(
            //         'episode_url' => aes_cbc_encryption($movie_url, $token),
            //         'encrypted_urls' => json_encode($url_json),
            //         'token' => $id . "_" . '0' . "_" . $token
            //     );
            // }
                //echo '<pre>'; print_r($update_dataa);echo '</pre>';
               //die;
                
                 $this->db->where('id',$id)->update('tv_serial_episodes',$update_data);

                page_alert_box('success', 'Updated', 'Episode has been updated successfully');
                redirect(AUTH_PANEL_URL . 'videos/premium_tv_serials/episode_list/' . $season_id);
//                redirect($_SERVER['HTTP_REFERER']);
            }
        }
    }

     public function time_frame()
    {
        if ($this->input->post()) {
            $this->form_validation->set_rules('frame_type', 'FRAME', 'required');

            if ($this->form_validation->run() == FALSE) {
//                $view_data['add_premium_plan_display'] = 'block';
            } else {
                //  echo "2".'<pre>'; print_r($_POST); die;
                 /*
                 * token is combined by three variables and combined by "_" underscore
                 * first one-primary id
                 * socond one-video type
                 * third-key //16 digit
                 */
                 $token = random_token();
                $file = $thumbnail = '';
                $url_json = array();
                $category ='2';
                $insert_data = array(
                    'web_series_id' => $this->input->post('id'),
                    'frame_type' => $this->input->post('frame_type'), 
                     'category_id' => $category,
                    'hrs' => $this->input->post('hours'),
                    'mins' => $this->input->post('minutes'),
                    'sec' => $this->input->post('seconds'),
                    'creation_time' => milliseconds(),
                    'uploaded_by' => $this->session->userdata('active_backend_user_id')
                );
               // echo '<pre>'; print_r($insert_data); die;
                $frame_id = $this->Premium_video_model->insert_frame($insert_data);
                if (!empty($_FILES['frame_type']['name'])) {
                   $frame_type = $this->amazon_s3_upload($_FILES['frame_type'], "frame_type/Advertisement");
                   $drm_encrypted_url = "file_library/videos/vod_drm_encrypted/$frame_id/";
                   
                    $s3_files = $this->video_operation("frame_type");
                    if ($s3_files) {
                        $video_url=$file = $s3_files['original'];
                        $file = convert_normal_to_m3u8($file);
//                        
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
                redirect($_SERVER['HTTP_REFERER']);
            }
        }
        
    }

public function time_frame_delete($id)
{
    $delete_video = $this->Movies_model->delete_frame($id);
        page_alert_box('success', 'Time Frame deleted', 'Time Frame has been deleted successfully');
         redirect($_SERVER['HTTP_REFERER']);

}

    public function ajax_get_episode_list() {
        // storing  request (ie, get/post) global array to a variable
        $requestData = $_REQUEST;
        $id = $requestData['id'];
        $columns = array(
            // datatable column index  => database column name
            0 => 'id',
            1 => 'season_title',
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

        $sql = "SELECT ep.id,ep.season_id,ep.episode_title,ep.episode_description,ep.position,ep.status,ep.ep_no,ep.token,ps.season_title as season_title
                From tv_serial_episodes as ep
                LEFT JOIN tv_serial as ps  ON ep.season_id = ps.id
                where ep.status !=2 and season_id = $id
                ";
        //order by position asc 
        // getting records as per search parameters
//      if (!empty($requestData['columns'][0]['search']['value'])) {
//          //name
//          $sql .= " AND id LIKE '" . $requestData['columns'][0]['search']['value'] . "%' ";
//      }
        if (!empty($requestData['columns'][1]['search']['value'])) {
            $sql .= " AND ps.season_title LIKE '" . '%' . $requestData['columns'][1]['search']['value'] . "%' ";
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

        $query = $this->db->query($sql)->result();

        $totalFiltered = count($query); // when there is a search parameter then we have to modify total number filtered rows as per search result.
//        $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   "; // adding length
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
            // $nestedData[] = ($r->status == 0 ) ? '<span class="btn btn-xs bold btn-success">Active</span>' : '<span class="btn btn-xs btn-warning">Locked</span>';
            $nestedData[] ="<a class='btn-xs bold btn btn-$alert_status' onclick=\"return confirm('Are you sure you want to $status?')\" href='" . AUTH_PANEL_URL . "videos/premium_tv_serials/lock_unlock_episodes/" . $r->id . '/' . $r->status . '/'. $r->season_id . "'>$status</a>&nbsp;";
            $nestedData[] = "
            <a class='btn-xs bold btn btn-success' title='View Video' href='" . AUTH_PANEL_URL . "videos/premium_tv_serials/view_episode?id=" . $r->id . "&data_id=".$r->id."&token=".$r->token."&data_type=2'><i class='fa fa-eye'></i></a>&nbsp;
         <a class='btn-xs bold btn btn-primary' onclick=\"return confirm('Are you sure you want to edit?')\" href='" . AUTH_PANEL_URL . "videos/premium_tv_serials/edit_episode/" . $r->id . "'><i class='fa fa-edit'></i></a>&nbsp;
        <a class='btn-xs bold btn btn-danger' onclick=\"return confirm('Are you sure you want to Delete?')\" href='" . AUTH_PANEL_URL . "videos/premium_tv_serials/delete_episodes/" . $r->id . '/' . $r->season_id . "'><i class='fa fa-trash-o'></i></a>&nbsp;
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

    public function lock_unlock_episodes($id, $status, $season_id) {
        if ($status == 0) {
            $header = 'Disable';
            $title = 'Episodes has been locked successfully';
        }
        if ($status == 1) {
            $header = 'Enable';
            $title = 'Episodes has been unlocked successfully';
        }
        $delete_videos = $this->Tv_serial_model->lock_unlock_episodes($id, $status);
        page_alert_box('success', $header, $title);
        redirect(AUTH_PANEL_URL . 'videos/premium_tv_serials/episode_list/' . $season_id);
    }

    public function delete_episodes($id, $season_id) {
        $delete_season = $this->Tv_serial_model->delete_episodes($id);
        page_alert_box('success', 'Deleted', 'Episodes has been deleted successfully');
        redirect(AUTH_PANEL_URL . 'videos/premium_tv_serials/episode_list/' . $season_id);
    }

    public function save_position_episodes() {

        $ids = $_POST['ids'];
        $counter = 1;
        foreach ($ids as $id) {
            $this->db->where('id', $id);
            $array = array('position' => $counter);
            $this->db->update('premium_episodes', $array);
            $counter++;
        }
        echo json_encode(array('status' => true, 'message' => 'position saved'));
        die;
    }

       public function get_request_for_csv_download($device_type="") {
            $this->ajax_get_tv_serial_list($device_type);
        }


    public function all_premium_tv_series_to_csv_download($array, $filename = "Users.csv", $delimiter = ";", $header) {
        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '";');
        $f = fopen('php://output', 'w');
        fputcsv($f, $header);
        foreach ($array as $line) {
            fputcsv($f, $line);
        }
    }


//XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX--->EPISODES BLOG START <---XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

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
    
    public function ajax_generate_video_url($type=5)
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

    public function ajax_generate_video_urll($type=51)
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

}




