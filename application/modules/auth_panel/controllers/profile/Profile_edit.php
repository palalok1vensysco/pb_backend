<?php
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile_edit extends MX_Controller {

	function __construct() {
		parent::__construct();
		/* !!!!!! Warning !!!!!!!11
		 *  admin panel initialization
		 *  do not over-right or remove auth_panel/auth_panel_ini/auth_ini
		 */
		modules::run('auth_panel/auth_panel_ini/auth_ini');
		$this->load->library('form_validation');
  }
	
	public function amazon_s3_upload($name,$aws_path) {
		$_FILES['file'] = $name;
		require_once(FCPATH.'aws/aws-autoloader.php');
				
						$s3Client = new S3Client([  
						'version'     => 'latest',
						'region'      => 'ap-south-1',
						'credentials' => [        
						'key'    => AMS_S3_KEY,
						'secret' => AMS_SECRET,  
						],
						]);
						$result = $s3Client->putObject(array(   
							'Bucket' => AMS_BUCKET_NAME,
							'Key' => $aws_path.'/'.rand(0,7896756).str_replace([':', ' ', '/', '*','#','@','%',], '',"_",$_FILES["file"]["name"]), 
							'SourceFile' => $_FILES["file"]["tmp_name"], 
							'ContentType' => 'image', 
							'ACL' => 'public-read',
							'StorageClass' => 'REDUCED_REDUNDANCY', 
							'Metadata' => array(        'param1' => 'value 1', 'param2' => 'value 2' )        
						));
				$data=$result->toArray();			
				return $data['ObjectURL'];
	
	}

	public function index() {
		if(isset($_POST['change_password'])){
			$this->update_password();
		}

		if(isset($_POST['change_profile'])){
			$this->update_profile();
		}
		if(isset($_POST['change_image'])){
			$this->update_image();
		}

		$data['page_data'] = $this->load->view('profile/edit_profile', array(), TRUE);
		echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
	}

	private function update_password(){
		$this->form_validation->set_rules('current_password', 'Current Password', 'required');
		$this->form_validation->set_rules('new_password', 'Password Confirmation', 'required');
		$this->form_validation->set_rules('renew_password', 'Password Confirmation', 'required|matches[new_password]');
		if ($this->form_validation->run() == FALSE){

		}else{
			//echo "<pre>";print_r($this->input->post());
			$this->db->where('id',$this->session->userdata('active_backend_user_id'));
           $user = $this->db->get('backend_user')->row_array();
          // echo $this->db->last_query();die;
			//-----
			 if (password_verify($this->input->post('current_password'), $user['password'])) {
			 	//echo $this->db->last_query();die;
                //unset($user['password']);
                $this->db->where('id',$this->session->userdata('active_backend_user_id'));
                $this->db->update('backend_user',array('password'=>generate_password($this->input->post('new_password'))));
               // echo $this->db->last_query();die;
                page_alert_box('success','Password Updated','Password updated successfully');
			backend_log_genration($this,'Updated the password.',
							'PASSWORD');
            } else {
            	page_alert_box('error','Invalid old Password!','Invalid Password!');
            	//echo "hiii";die;
                //return_data(false, "Invalid Password!");
            }
			
		//	$this->db->update('backend_user',array('password'=>md5($this->input->post('new_password'))));
			// $this->db->update('backend_user',array('password'=>generate_password($this->input->post('new_password'))));
			

			
		}
	}

	private function update_profile(){

		$user = $this->db->where('id',$this->session->userdata('active_backend_user_id'))->get('backend_user')->row();
		//print_r($this->db->last_query()); 
		//echo "<pre>";print_r($user->email);
		//print_r($_POST);
		//die;
		$email_unique ="";
		if($user->email != $this->input->post('email')){
			$email_unique ="|is_unique[backend_user.email]";
		}
		$mobile_unique ="";
		if($user->mobile != $this->input->post('mobile')){
			$mobile_unique ="|is_unique[backend_user.mobile]";
		}
		$this->form_validation->set_rules('name', 'name', 'required');
		$this->form_validation->set_rules('email', 'email', 'required|valid_email'.$email_unique);
		$this->form_validation->set_rules('mobile', 'mobile', 'required'.$email_unique);
		if ($this->form_validation->run() == FALSE){

		}else{
			$this->db->where('id',$this->session->userdata('active_backend_user_id'));
			$this->db->update('backend_user',array(
					'username'=>$this->input->post('name'),
					'email'=>$this->input->post('email'),
					'mobile'=>$this->input->post('mobile'),
				)
			);

			page_alert_box('success','Profile Updated','Profile updated successfully');
			backend_log_genration($this,'Updated the Profile.',
							'PROFILE');
		}
	}
	
	private function update_image(){
		if(!empty($_FILES["profile_picture"])){
			
			$image_info = getimagesize($_FILES["profile_picture"]["tmp_name"]);
			$image_width = $image_info[0];
			$image_height = $image_info[1];
			
			//if($image_width == $image_height){				
				$file  = $this->amazon_s3_upload($_FILES['profile_picture'],"course_file_meta");
				$this->db->where('id',$this->session->userdata('active_backend_user_id'));
				app_permission("app_id",$this->db);
				$this->db->update('backend_user',array('profile_picture'=>$file));
				page_alert_box('success','Profile Image','Profile image updated successfully.');
				//backend_log_genration($this->session->userdata('active_backend_user_id'),'Updated the profile image.','PROFILE');
//			}
//			else{
//				page_alert_box('error','Profile Image','Profile image should have same height and width');				
//			}
			
		}else{
			page_alert_box('error','Profile Image','Please select Profile image');
		}
		
	}
}
