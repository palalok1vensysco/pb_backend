		<?php
use Aws\S3\S3Client;
use Aws\MediaPackageVod\MediaPackageVodClient;
defined('BASEPATH') OR exit('No direct script access allowed');

class Banner extends MX_Controller {

	function __construct() {
		parent::__construct();		
		modules::run('auth_panel/auth_panel_ini/auth_ini');
		$this->load->library(['form_validation','s3_upload']);
        $this->load->helper(['aes', 'compress', 'aul','services','cookie','custom']);
        $this->load->model("banner_model");

		// $this->load->library('form_validation');
		// $this->load->model("banner_model");
		// $this->load->helper("services");
		// $this->load->library('aws_s3_file_upload');
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



	public function add_banner(){ 
		$this->cleardir();
		if($this->input->post()) { //echo pre($_POST); die;
			$this->form_validation->set_rules('published-date-use', 'published date', 'required');
			$this->form_validation->set_rules('position', 'position', 'required');
			$this->form_validation->set_rules('description', 'description', 'required');

			if(empty($_FILES['image']['name'])){
				$this->form_validation->set_rules('image', 'Image', 'required');
			}

			if ($this->form_validation->run() == TRUE) { 
             
            } 
			else {

				if (!empty($_FILES['image']['name'])) {
                   $image = $this->amazon_s3_upload($_FILES['image'], "banner_image/image");
                } else {
                    $image = '';
                } 	
				//pre($image); die;
				$insert_data = array(
					'title' => $this->input->post('title'),
					'description' => $this->input->post('description'),
					'image' => $image,
					'published_date' => $this->input->post('published_date'),
					'creation_time' => milliseconds(),
					'uploaded_by' => $this->session->userdata('active_backend_user_id')
				);
		//pre($insert_data); die;
				//echo '<pre>'; echo $thumbnail_url.','.$video_url; die;
				$id =  $this->banner_model->insert_banner($insert_data);
				page_alert_box('success','Banner Added','New Banner has been added successfully');
				redirect(AUTH_PANEL_URL.'banner/banner/banner_list');
            }

		}
		$view_data['page']  = "add_banner";
		$data['page_data'] = $this->load->view('banner/add_banner',$view_data, TRUE);
		echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
	}

	public function edit_banner($id){ 
		$this->cleardir();
		if($this->input->post()) { 
			$this->form_validation->set_rules('title', 'Title', 'required');
			$this->form_validation->set_rules('published_date', 'published_date', 'required');
			if ($this->form_validation->run() == FALSE) { 
             
            } 
			else { 	
				//profile_image file	  					
				
				$update_data = array(
					'id' => $this->input->post('id'),
					'title' => $this->input->post('title'),
					'description' => $this->input->post('description'),
					// 'position' => $this->input->post('position'),
					//'image' => $image,
					'published_date' => $this->input->post('published_date'),
					'creation_time' => milliseconds()
				);
				if (!empty($_FILES['image']['name'])) {

                   $image = $this->amazon_s3_upload($_FILES['image'], "banner_image/image");
                   $update_data['image']=$image;

                } 

				//echo '<pre>'; print_r($update_data['image']); die;
				$update =  $this->banner_model->update_banner($update_data);
				page_alert_box('success','Banner Updated','Banner has been updated successfully');
				redirect(AUTH_PANEL_URL.'banner/banner/banner_list');
            }

		}
		$view_data['page']  = "edit_banner";
		$view_data['banner'] = $this->banner_model->get_banner_by_id($id);
		$data['page_data'] = $this->load->view('banner/edit_banner',$view_data, TRUE);
		echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
	}


	public function banner_list() {
		$data['page_title'] = "banner's List";
		$view_data['page']  = "banner_list";
		$view_data['banners'] = $this->banner_model->get_banner_list();
		$data['page_data'] = $this->load->view('banner/banner_list', $view_data, TRUE);
		echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
	}


	public function ajax_all_banner_list() {
		// storing  request (ie, get/post) global array to a variable
		$requestData = $_REQUEST;
		$columns = array(
			// datatable column index  => database column name
			0 => 'id',
			1 => 'title',
			// 3 => 'position',
			4 => 'published_date',
			5 => 'image',
			6 => 'creation_time'
			
		);
		//$where = " and parent_id = '".$_SESSION['active_backend_user_id']."'";
		$query = "SELECT count(id) as total
				  FROM banner where status=0";

		$query = $this->db->query($query);
		$query = $query->row_array();
		$totalData = (count($query) > 0) ? $query['total'] : 0;
		$totalFiltered = $totalData;

		$sql = "SELECT id,DATE_FORMAT(FROM_UNIXTIME(creation_time/1000), '%d-%m-%Y') as creation_time,title,position,published_date,image
				FROM banner where status=0";

		// getting records as per search parameters
	
		if (!empty($requestData['columns'][0]['search']['value'])) {  
			$sql.=" AND id LIKE '" . $requestData['columns'][0]['search']['value'] . "%' ";
		}
		if (!empty($requestData['columns'][1]['search']['value'])) {  
			$sql.=" AND title LIKE '" . $requestData['columns'][1]['search']['value'] . "%' ";
		}
		
		$query = $this->db->query($sql)->result();

		$totalFiltered = count($query); // when there is a search parameter then we have to modify total number filtered rows as per search result.

		$sql.=" ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";  // adding length

		$result = $this->db->query($sql)->result();
	
		$data = array();

		foreach ($result as $r) {  // preparing an array
			$nestedData = array();
			$nestedData[] = ++$requestData['start'];
			$nestedData[] = $r->title;
			// $nestedData[] = $r->position;
			$nestedData[] = "<img width='200px' height='80px' src='".$r->image."'></a>";
			$nestedData[] = $r->published_date;
			$nestedData[] = $r->creation_time;
			$nestedData[] = "<a class='btn-xs bold btn btn-primary' title='Edit' onclick=\"return confirm('Are you sure you want to Edit?')\" href='" . base_url('admin-panel/edit-banner/') . $r->id . "'><i class='fa fa-edit'></i></a>&nbsp;
				<a class='btn-xs bold btn btn-danger' title='Delete' onclick=\"return confirm('Are you sure you want to delete?')\" href='" . AUTH_PANEL_URL . "banner/banner/delete_banner/" . $r->id . "'><i class='fa fa-trash-o'></i></a>&nbsp;
			";
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

	
	
	
	public function delete_banner($id) {
		$delete_user = $this->banner_model->delete_banner($id);
		page_alert_box('success','Banner Deleted','Banner has been deleted successfully');
		redirect(AUTH_PANEL_URL . 'banner/banner/banner_list');
	}



	/*<a class='btn-xs bold btn btn-info' href='" . AUTH_PANEL_URL . "banner/banner/edit_banner/" . $r->id . "'>Edit</a>&nbsp;"*/
	
		function compress_image($source_file, $target_file, $nwidth, $nheight, $quality) {
  //Return an array consisting of image type, height, widh and mime type.
  $image_info = getimagesize($source_file);
  if(!($nwidth > 0)) $nwidth = $image_info[0];
  if(!($nheight > 0)) $nheight = $image_info[1];
  
  if(!empty($image_info)) {
    switch($image_info['mime']) {
      case 'image/jpeg' :
        if($quality == '' || $quality < 0 || $quality > 100) $quality = 75; //Default quality
        // Create a new image from the file or the url.
        $image = imagecreatefromjpeg($source_file);
        $thumb = imagecreatetruecolor($nwidth, $nheight);
        //Resize the $thumb image
        imagecopyresized($thumb, $image, 0, 0, 0, 0, $nwidth, $nheight, $image_info[0], $image_info[1]);
        //Output image to the browser or file.
        return imagejpeg($thumb, $target_file, $quality); 
        
        break;
      
      case 'image/png' :
        if($quality == '' || $quality < 0 || $quality > 9) $quality = 6; //Default quality
        // Create a new image from the file or the url.
        $image = imagecreatefrompng($source_file);
        $thumb = imagecreatetruecolor($nwidth, $nheight);
        //Resize the $thumb image
        imagecopyresized($thumb, $image, 0, 0, 0, 0, $nwidth, $nheight, $image_info[0], $image_info[1]);
        // Output image to the browser or file.
        return imagepng($thumb, $target_file, $quality);
        break;
        
      case 'image/gif' :
        if($quality == '' || $quality < 0 || $quality > 100) $quality = 75; //Default quality
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
	private function cleardir(){
		
		$folder_path = "resize-image"; 
   
// List of name of files inside 
// specified folder 
$files = glob($folder_path.'/*');  
   
// Deleting all the files in the list 
foreach($files as $file) { 
   
    if(is_file($file))  
    
        // Delete the given file 
        unlink($file);  
} 
	
	}
	

}
