<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends MX_Controller {

    function __construct() {
        $this->load->helper(array('form', 'url'));

                $this->load->library('form_validation');
 $this->load->library('session');
    }

    public function index() {
     $c=$this->db->table_exists('users');  echo '<pre>';
       print_r($c);die;
        echo 'LB 1';
    }

     public function merge(){
        phpinfo();die;
        $this->load->database();
        $a=$this->db->get('backend_user_permission')->result_array();
        foreach ($a as $c) {
           $data=$this->db->get_where('backend_user_permission',array('permission_perm'=>$c['permission_perm']))->row_array();
            $this->db->where('id',$data['id']);
            $this->db->update('backend_user_permission',array('permission_merge'=>ucfirst(strtolower(trim($data['permission_merge']))),'permission_name'=>ucfirst(strtolower(trim($data['permission_name'])))));
        }
    }

    public function create_application(){
        $this->form_validation->set_rules('token', 'token', 'trim|required');        
        $this->form_validation->set_rules('owner_email', 'Username', 'trim|required');
        $this->form_validation->set_rules('owner_mobile', 'Username', 'trim|required');
        $this->form_validation->set_rules('title', 'Username', 'trim|required');
        $this->form_validation->set_rules('domain', 'Username', 'trim|required');
        $this->form_validation->set_rules('owner_pass', 'Username', 'trim|required');
        $this->form_validation->run();
        $error = $this->form_validation->get_all_errors();
        if ($error) {
            echo json_encode(array('status'=>false, 'message'=>array_values($error)[0], 'data'=>array()));die;
        }
        if ($this->input->post('token')!='dcbiyt8dr6e18729ywoqfegviyuid') {
            echo json_encode(array('status'=>false, 'message'=>'Invalid Token', 'data'=>array()));die;
        }
        $this->load->helper('custom');
        $input=$this->input->post();
        $data=array();
        $this->load->model('auth_panel/Backend_user_model','Backend_user_model');
        $is_already_exists = $this->Backend_user_model->is_application_exists($input);
            if((!$is_already_exists) || ($is_already_exists && !empty($input['id']))) {
                $application_id = $this->Backend_user_model->add_edit_application($input);
                $data['applicationid'] = $application_id;
            }else{
                $status=false;
                $msg='An Application already exists with given Email/Mobile.';
            }

            echo json_encode(array('status'=>$status,'data'=>$data,'message'=>$msg));die;     

     }

     public function vdc_webhook(){
        $input= file_get_contents("php://input");
        $input=json_decode($input,true);
        if($input['type']=="deleteChannel"){
            $this->db->where("channel_id",$input['channel_id']);
            $channel=$this->db->get("aws_channel")->row();
            if($channel){
            $input_ids=json_decode($channel->input_ids);           
            $this->db->where("channel_id",$input['channel_id']);
            $channel=$this->db->delete("aws_channel");        

            $this->db->where("input_id",$input_ids[0]->InputId);
            $channel=$this->db->delete("aws_channel_input");           
            
            $this->db->where("channel_id",$input["mp_channel_id"]);
            $channel=$this->db->delete("aws_media_package_channel");
            
            $this->db->where("channel_id",$input["mp_channel_id"]);
            $channel=$this->db->delete("aws_media_package_endpoint");
            }
        }
        if($input['type']=="harvestToVodUpdate"){
     
            $this->db->where("vdc_id",$input['id']);
            $this->db->update("course_topic_file_meta_master",array("file_url"=>$input["file_url_hls"],"drm_dash_url"=>$input["drm_dash_url"],
            "drm_hls_url"=>$input["drm_hls_url"],"bitrate_urls"=>$input["download_url"]));
        }
        if($input['type']=="downloadUpdate"){           
                $this->db->where("vdc_id",$input['id']);
                $this->db->update("course_topic_file_meta_master",array("bitrate_urls"=>$input["download_url"]));
            }
        
    }

     
     public function tnc($id){
        echo $this->db->get_where('application_meta',array('app_id'=>$id))->row()->term_and_policy;
     }

     public function privacy($id){
      echo $this->db->get_where('application_meta',array('app_id'=>$id))->row()->privacy_policy;
     }
     public function payment_privacy($id){
        echo $this->db->get_where('application_meta',array('app_id'=>$id))->row()->payment_privacy;
       }



}
