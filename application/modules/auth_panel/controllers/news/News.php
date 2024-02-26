<?php
use Aws\S3\S3Client;
use Aws\MediaPackageVod\MediaPackageVodClient;
defined('BASEPATH') OR exit('No direct script access allowed');

class News extends MX_Controller {

    function __construct() {
        parent::__construct();
        /* !!!!!! Warning !!!!!!!11
         *  admin panel initialization
         *  do not over-right or remove auth_panel/auth_panel_ini/auth_ini
         */
        modules::run('auth_panel/auth_panel_ini/auth_ini');
       // $this->load->library('form_validation');
         $this->load->library(['form_validation','s3_upload']);
        $this->load->helper(['aes', 'compress', 'aul','services','cookie','custom']);
        $this->load->model("news_model");
        $this->load->model("notification_model");
       // $this->load->helper("services");
       // $this->load->library('aws_s3_file_upload');
        $this->load->helper("push_helper");
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
                $enc["url"] = "https://mahua-tv.s3.ap-south-1.amazonaws.com/file_library/videos/original/1588012383_1920x1280"; //$this->s3_upload->upload_s3($encrypted_file, "file_library/videos/encrypted/");
//                $enc['name'] = explode("_", $current_name);
                $enc['name'] = "1920x1280.mp4"; //end($enc['name']);
                $enc['size'] = "1.01 MB"; //$this->getFormatSizeUnits(filesize($encrypted_file));
                $s3_files["encrypted_url"][] = $enc;
            } 
            if ($key == 0) {
                if ($this->input->post("custom_s3_url"))
                    $s3_files['original'] = str_replace('%2F', "/", $this->input->post("custom_s3_url"));
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
    

    public function add_news() {
        $this->cleardir();
        if ($this->input->post()) {//pre($_POST);pre($_FILES);die;
            $this->form_validation->set_rules('title', 'Title Name', 'required');
            $this->form_validation->set_rules('description', 'Description', 'required');
            $this->form_validation->set_rules('published_date', 'Published Date', 'required');
            if ($this->form_validation->run() == FALSE) {
            } else {
                $token = random_token();
                $file = $thumbnail = '';
                $url_json = array();
                
                 if (!empty($_FILES['n_image']['name'])) {
                   $n_image = $this->amazon_s3_upload($_FILES['n_image'], "n_image/News");
                } else {
                    $n_image = '';
                }

                $insert_data = array(
                    'title' => $this->input->post('title'),
                    'description' => $this->input->post('description'),
                    'genres' => ucwords($this->input->post('genres')),
                    'image' => $n_image,
                    'uploaded_by' => $this->session->userdata('active_backend_user_id'),
                    'published_date' => (strtotime($_POST['published_date']) * 1000),
                    'creation_time' => milliseconds()
                );

                if(defined("APP_ID") && APP_ID)
                    $insert_data['app_id'] = APP_ID;

                $id = $this->news_model->insert_news($insert_data);

                if (!empty($_FILES['n_url']['name'])) {
                   $n_url = $this->amazon_s3_upload($_FILES['n_url'], "n_url/News");
                   $drm_encrypted_url = "file_library/videos/vod_drm_encrypted/$id/";
                   
                    $s3_files = $this->video_operation("n_url");
                    if ($s3_files) {
                        $video_url=$file = $s3_files['original'];
                        $file = convert_normal_to_m3u8($file);
//                        
                        $file = explode(".com/", $file)[1];
                        $url_json = $s3_files['encrypted_url'];
                    }
                } else {
                    $n_url = '';
                }

                foreach ($url_json as $key => $value) {
                    $url_json[$key]['url'] = aes_cbc_encryption($value['url'], $token);
                }
                $update_data = array(
                    'n_url' => aes_cbc_encryption($file, $token),
                    'encrypted_urls' => json_encode($url_json),
                    'token' => $id . "_" . '4' . "_" . $token
                );
                $this->db->where('id',$id)->update('news',$update_data);
//              

                page_alert_box('success', 'News Added', 'News has been added successfully');
                redirect(AUTH_PANEL_URL . 'news/news/news_list');
            }

        }

        $view_data['page'] = "add_news";
           $view_data['categories'] = $this->news_model->get_categories();
           $view_data['sub_caegories'] = $this->news_model->get_sub_categories();
        $data['page_data'] = $this->load->view('news/add_news', $view_data, TRUE);

        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function edit_news($id) {
        $this->cleardir();
        if ($this->input->post()) {
            $this->form_validation->set_rules('title', 'News Title', 'required');
            $this->form_validation->set_rules('description', 'Description', 'required');
            $this->form_validation->set_rules('published_date', 'Published Date', 'required');
            if ($this->form_validation->run() == FALSE) {
                
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

                $update_data = array(
                    'title' => $this->input->post('title'),
                    'description' => $this->input->post('description'),
                   // 'image' => $n_image,
                   // 'language' => ucwords($this->input->post('language')),
                    'genres' => ucwords($this->input->post('genres')),
                    'uploaded_by' => $this->session->userdata('active_backend_user_id'),
                    'published_date' => (strtotime($_POST['published_date']) * 1000),
                    'creation_time' => milliseconds()
                );
              //  $id = $this->news_model->insert_news($insert_data);
                 if (!empty($_FILES['n_image']['name'])) {
                   $n_image = $this->amazon_s3_upload($_FILES['n_image'], "n_image/News");
                   $update_data['image']=$n_image;
                } 

                $m_url = $this->input->post('custom_s3_url');
                if (!empty($m_url)) {

                if (!empty($_FILES['n_url']['name'])) {
                   $n_url = $this->amazon_s3_upload($_FILES['n_url'], "n_url/News");
                   $drm_encrypted_url = "file_library/videos/vod_drm_encrypted/$id/";
                   
                    $s3_files = $this->video_operation("n_url");
                    if ($s3_files) {
                        $video_url=$file = $s3_files['original'];
                        $file = convert_normal_to_m3u8($file);
//                        
                        $file = explode(".com/", $file)[1];
                        $url_json = $s3_files['encrypted_url'];
                    }
                } else {
                    $n_url = '';
                }

                foreach ($url_json as $key => $value) {
                    $url_json[$key]['url'] = aes_cbc_encryption($value['url'], $token);
                }
                $update_data = array(
                    'n_url' => aes_cbc_encryption($file, $token),
                    'encrypted_urls' => json_encode($url_json),
                    'token' => $id . "_" . '3' . "_" . $token
                );
            }
                $this->db->where('id',$id)->update('news',$update_data);

              //  print_r($update_data);die;

                page_alert_box('success', 'News Updated', 'News has been updated successfully');
                redirect(AUTH_PANEL_URL . 'news/news/news_list');
            }
        }
        $view_data['page'] = "edit_news";
         $view_data['categories'] = $this->news_model->get_categories();
           $view_data['sub_caegories'] = $this->news_model->get_sub_categories();
            $cat_id ='4';
        $view_data['frame'] = $this->news_model->get_time_frames($id,$cat_id);
        $view_data['news'] = $this->news_model->get_news_by_id($id);
        $data['page_data'] = $this->load->view('news/edit_news', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function news_list() {
        $data['page_title'] = "News List";
        $view_data['page'] = "news_list";
        
        $view_data['news'] = $this->news_model->get_news_list();
       // pre($view_data);die;
        $data['page_data'] = $this->load->view('news/news_list', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
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
                 $cat_id ='4';
                $insert_data = array(
                        'movie_id' => $this->input->post('id'),
                    'frame_type' => $this->input->post('frame_type'), //in days...
                    'category_id' => $cat_id,
                    'hrs' => $this->input->post('hours'),
                    'mins' => $this->input->post('minutes'),
                    'sec' => $this->input->post('seconds'),
                    'creation_time' => milliseconds(),
                    'uploaded_by' => $this->session->userdata('active_backend_user_id')
                );
               // echo '<pre>'; print_r($insert_data); die;
                $frame_id = $this->news_model->insert_frame($insert_data);
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
    $delete_video = $this->news_model->delete_frame($id);
        page_alert_box('success', 'Time Frame deleted', 'Time Frame has been deleted successfully');
        redirect($_SERVER['HTTP_REFERER']);

}

    public function ajax_all_news_list() {
        // storing  request (ie, get/post) global array to a variable
        $requestData = $_REQUEST;
        $columns = array(
            // datatable column index  => database column name
            0 => 'id',
            1 => 'title',
            2 => 'description',
            3 => 'image',
            4 => 'creation_time',
            5 => 'views_count'
        );

        $query = "SELECT count(id) as total
				  FROM news where status=0";

        $query = $this->db->query($query);
        $query = $query->row_array();
        $totalData = (count($query) > 0) ? $query['total'] : 0;
        $totalFiltered = $totalData;
        $sql = "SELECT id,published_date,title,views_count,description,image
				FROM news where status=0";
        $sql .=  (defined("APP_ID") ? "" . app_permission("app_id") . "" : "");        
        // getting records as per search parameters
        if (!empty($requestData['columns'][0]['search']['value'])) {   //name
            $sql .= " AND id LIKE '" . $requestData['columns'][0]['search']['value'] . "%' ";
        }
        if (!empty($requestData['columns'][1]['search']['value'])) {  //salary
            $sql .= " AND title LIKE '" . $requestData['columns'][1]['search']['value'] . "%' ";
        }
        if (!empty($requestData['columns'][2]['search']['value'])) {  //salary
            $sql .= " AND description LIKE '" . '%' . $requestData['columns'][2]['search']['value'] . "%' ";
        }


        $query = $this->db->query($sql)->result();

        $totalFiltered = count($query); // when there is a search parameter then we have to modify total number filtered rows as per search result.
//        $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";  // adding length
        $sql .= " ORDER BY published_date " . $requestData['order'][0]['dir'] . "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";  // adding length

        $result = $this->db->query($sql)->result();

        $data = array();

        foreach ($result as $r) {  // preparing an array
            $short_desc = $this->word_formatter($r->description);
            $image_array = explode(',', $r->image);
            $nestedData = array();
            $nestedData[] = "<input name='selected_id' type='checkbox' value='$r->id'/>&nbsp&nbsp" . ++$requestData['start'];
//            $nestedData[] = ++$requestData['start'];
            $nestedData[] = $r->title;
//            $nestedData[] = $r->description;
            $nestedData[] = $short_desc . "...";
            $nestedData[] = "<img width='50px' height='50px' src='" . $image_array[0] . "'></a>";
            $nestedData[] = "<a class='btn-xs bold btn btn-success' title='View News' href='" . AUTH_PANEL_URL . "news/news/add_adds/" . $r->id . "'><i class='fa fa-eye'></i></a>&nbsp;";
            $nestedData[] = date("d-m-Y h:i:s A", $r->published_date / 1000);
            $nestedData[] = "<a class='btn-xs bold btn btn-success' title='View News' href='" . AUTH_PANEL_URL . "news/news/view_news/" . $r->id . "'><i class='fa fa-eye'></i></a>&nbsp;
			<a class='btn-xs bold btn btn-primary' title='Edit News' onclick=\"return confirm('Are you sure you want to edit?')\" href='" . AUTH_PANEL_URL . "news/news/edit_news/" . $r->id . "'><i class='fa fa-edit'></i></a>&nbsp;
			    <a class='btn-xs bold btn btn-danger' title='Delete' onclick=\"return confirm('Are you sure you want to delete?')\" href='" . AUTH_PANEL_URL . "news/news/delete_news/" . $r->id . "'><i class='fa fa-trash-o'></i></a>&nbsp;";
            $data[] = $nestedData;
        }

        $json_data = array(
            "draw" => intval($requestData['draw']), // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
            "recordsTotal" => intval($totalData), // total number of records
            "recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
            "data" => $data   // total data array
        );

        echo json_encode($json_data);  // send data as json format
    }

    private function word_formatter($string) {
        $string = explode(" ", strip_tags($string));
        if ($string && count($string) > 25) {
            $string = array_slice($string, 0, 25, true);
        }
        return implode(" ", $string);
    }

        public function add_adds($id) {
        $view_data['page'] = 'news';
        $data['page_data'] = $this->load->view('news/add_adds', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }



    public function delete_news($id) {
        $delete_user = $this->news_model->delete_news($id);
        page_alert_box('success', 'News deleted', 'News has been deleted successfully');
        redirect(AUTH_PANEL_URL . 'news/news/news_list');
    }

    public function view_news($id) {
        $view_data['news'] = $this->news_model->get_news_by_id($id);
        $data['page_data'] = $this->load->view('news/view_news', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function send_notification($id) {
        $news = $this->news_model->get_news_by_id_notification($id);
        if ($news) {
            //return_data(true,'Push Notification sent.',$news);	
            print_r(json_encode($news));
        }
    }

    function compress_image($source_file, $target_file, $nwidth, $nheight, $quality) {
        //Return an array consisting of image type, height, widh and mime type.
        $image_info = getimagesize($source_file);
        if (!($nwidth > 0))
            $nwidth = $image_info[0];
        if (!($nheight > 0))
            $nheight = $image_info[1];

        if (!empty($image_info)) {
            switch ($image_info['mime']) {
                case 'image/jpeg' :
                    if ($quality == '' || $quality < 0 || $quality > 100)
                        $quality = 75; //Default quality





                        
// Create a new image from the file or the url.
                    $image = imagecreatefromjpeg($source_file);
                    $thumb = imagecreatetruecolor($nwidth, $nheight);
                    //Resize the $thumb image
                    imagecopyresized($thumb, $image, 0, 0, 0, 0, $nwidth, $nheight, $image_info[0], $image_info[1]);
                    //Output image to the browser or file.
                    return imagejpeg($thumb, $target_file, $quality);

                    break;

                case 'image/png' :
                    if ($quality == '' || $quality < 0 || $quality > 9)
                        $quality = 6; //Default quality





                        
// Create a new image from the file or the url.
                    $image = imagecreatefrompng($source_file);
                    $thumb = imagecreatetruecolor($nwidth, $nheight);
                    //Resize the $thumb image
                    imagecopyresized($thumb, $image, 0, 0, 0, 0, $nwidth, $nheight, $image_info[0], $image_info[1]);
                    // Output image to the browser or file.
                    return imagepng($thumb, $target_file, $quality);
                    break;

                case 'image/gif' :
                    if ($quality == '' || $quality < 0 || $quality > 100)
                        $quality = 75; //Default quality





                        
// Create a new image from the file or the url.
                    $image = imagecreatefromgif($source_file);
                    $thumb = imagecreatetruecolor($nwidth, $nheight);
                    //Resize the $thumb image
                    imagecopyresized($thumb, $image, 0, 0, 0, 0, $nwidth, $nheight, $image_info[0], $image_info[1]);
                    // Output image to the browser or file.
                    return imagegif($thumb, $target_file, $quality); //$success = true;
                    break;

                default:
                    echo "<h4>File type not supported!</h4>";
                    break;
            }
        }
    }

    private function cleardir() {

        $folder_path = "resize-image";

// List of name of files inside 
// specified folder 
        $files = glob($folder_path . '/*');

// Deleting all the files in the list 
        foreach ($files as $file) {

            if (is_file($file))

            // Delete the given file 
                unlink($file);
        }
    }


    public function send_push_notification() {
        if ($this->input->post('device_type') == 1) {
            /* android */
            $token = $this->input->post('device_token');
            $device = "android";
            generatePush($device, $token, $this->input->post('message'));
        } else if ($this->input->post('device_type') == 2) {
            /* ios */
            $token = $this->input->post('device_token');
            $device = "ios";
            generatePush($device, $token, $this->input->post('message'));
        }
    }

//    public function multiple_upload() 
//	{
//        if(isset($_FILES) && !empty($_FILES)){
//            pre($_FILES);
//            pre($_POST);die;
//        }
//        $view_data['page'] = "add_news";
//        $data['page_data'] = $this->load->view('news/add_news_new', $view_data, TRUE);
//        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
//	}
//	
//	public function upload_multiple_files() 
//	{
//		$verifyToken = md5('unique_salt' . $_POST['timestamp']);
//		if(!empty($_FILES) && $_POST['token'] == $verifyToken) {
//			// Validate the filetype
//			$fileParts = pathinfo($_FILES['Filedata']['name']);
//			// Set the allowed file extensions
//			$fileTypes = array('jpg', 'jpeg', 'gif', 'png','mp4'); // Allowed file extensions
//			if(in_array(strtolower($fileParts['extension']), $fileTypes)) {
//				// Set the uplaod directory	
//				$config['upload_path'] = 'uploads/images';
//				// Set the allowed file extensions
//				$config['allowed_types'] = $fileTypes;
//				$this->load->library('upload', $config);
//				$file_name = $_FILES['Filedata']['name'];
//				$file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
//				 if($this->upload->do_upload('Filedata')) {
//				   echo 1;  // file uploaded successfully
//				} 
//			
//			} else {
//				// The file type wasn't allowed
//				echo 'Invalid file type.';
//
//			}
//		}
//	}
//	public function check_exists() {
//		$targetFolder = '/digital_library/uploads/images/'; // Relative to the root and should match the upload folder in the uploader script
//		if (file_exists($_SERVER['DOCUMENT_ROOT'] . $targetFolder . '/' . $_POST['filename'])) {
//			echo 1;
//		} else {
//			echo 0;
//		}
//		die;
//	}

    public function delete_all_selected_data() {
        $array = $this->input->post('selected_ids');
        foreach ($array as $id) {
            $this->db->where('id', $id);
            $this->db->update('news', ['status' => 2,'deleted_by'=>1]);
        }
        echo json_encode(array("data" => 1));
    }
    
    public function add_articles() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('title', 'Title Name', 'required');
            $this->form_validation->set_rules('description', 'Description', 'required');
            if (empty($_FILES['image']['name'])) {
                $this->form_validation->set_rules('image', 'Image', 'required');
            }
            if ($this->form_validation->run() == FALSE) {
                
            } else {
                if (isset($_FILES['image']['name']) && !empty($_FILES['image']['name'])) {
                        $image = $this->aws_s3_file_upload->aws_s3_file_upload($_FILES['image'], 'news/images');
                }
                $insert_data = array(
                    'title' => $this->input->post('title'),
                    'description' => $this->input->post('description'),
                    'image' => $image
                );
                $id = $this->news_model->insert_instant_articles($insert_data);
                page_alert_box('success', 'News Article', 'Article has been added successfully');
                redirect(AUTH_PANEL_URL . 'news/news/article_list');
            }
        }
        $view_data['page'] = "add_articles";
        $data['page_data'] = $this->load->view('news/add_articles', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }
    
     public function article_list() {
        $requestData = $_REQUEST;
        $columns = array(
            0 => 'id',
            1 => 'title',
            2 => 'description',
            3 => 'image'
        );

        $query = "SELECT count(id) as total
				  FROM fb_instant_articles where status=0";

        $query = $this->db->query($query);
        $query = $query->row_array();
        $totalData = (count($query) > 0) ? $query['total'] : 0;
        $totalFiltered = $totalData;
        $sql = "SELECT id,title,description,image
				FROM fb_instant_articles where status=0";

        // getting records as per search parameters

        if (!empty($requestData['columns'][0]['search']['value'])) {   //name
            $sql .= " AND id LIKE '" . $requestData['columns'][0]['search']['value'] . "%' ";
        }
        if (!empty($requestData['columns'][1]['search']['value'])) {  //salary
            $sql .= " AND title LIKE '" . $requestData['columns'][1]['search']['value'] . "%' ";
        }
        if (!empty($requestData['columns'][2]['search']['value'])) {  //salary
            $sql .= " AND description LIKE '" . '%' . $requestData['columns'][2]['search']['value'] . "%' ";
        }


        $query = $this->db->query($sql)->result();

        $totalFiltered = count($query); // when there is a search parameter then we have to modify total number filtered rows as per search result.
//        $sql .= " ORDER BY id ". $requestData['order'][0]['dir'] . "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";  // adding length
        $sql .= " ORDER BY id desc";
       
        $result = $this->db->query($sql)->result();

        $data = array();

        foreach ($result as $r) {  // preparing an array
            $short_desc = $this->word_formatter($r->description);
            $nestedData = array();
            $nestedData[] = ++$requestData['start'];
            $nestedData[] = $r->title;
            $nestedData[] = $short_desc . "...";
            $nestedData[] = "<img width='50px' height='50px' src='" . $r->image . "'></a>";
            $nestedData[] = "<a class='btn-xs bold btn btn-success' title='View News' href='" . AUTH_PANEL_URL . "news/news/view_news/" . $r->id . "'><i class='fa fa-eye'></i></a>&nbsp;
			<a class='btn-xs bold btn btn-primary' title='Edit News' onclick=\"return confirm('Are you sure you want to edit?')\" href='" . AUTH_PANEL_URL . "news/news/edit_news/" . $r->id . "'><i class='fa fa-edit'></i></a>&nbsp;
			    <a class='btn-xs bold btn btn-danger' title='Delete' onclick=\"return confirm('Are you sure you want to delete?')\" href='" . AUTH_PANEL_URL . "news/news/delete_news/" . $r->id . "'><i class='fa fa-trash-o'></i></a>&nbsp;";
            $data[] = $nestedData;
        }

        $json_data = array(
            "draw" => intval($requestData['draw']), // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
            "recordsTotal" => intval($totalData), // total number of records
            "recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
            "data" => $data   // total data array
        );

        echo json_encode($json_data);  // send data as json format
    }

}

