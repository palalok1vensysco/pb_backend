<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH . '/third_party/aws/aws-autoloader.php');

use Aws\MediaLive\MediaLiveClient;
use Aws\CloudFront\CloudFrontClient;
use Aws\Exception\AwsException;

class Inputs extends MX_Controller {

    function __construct() {
        parent::__construct();
        /* !!!!!! Warning !!!!!!!11
         *  admin panel initialization
         *  do not over-right or remove auth_panel/auth_panel_ini/auth_ini
         */
        $this->load->helper(array('aes','aul'));
        modules::run('auth_panel/auth_panel_ini/auth_ini');
        $this->load->library('form_validation');
        $this->load->model(['live_module/Credentials_model', 'live_module/Inputs_model']);
    }

    public function index() {
        $input = $this->input->post();
        if ($input) {
            $this->Inputs_model->index($input);
        }
        $view_data['inputs'] = $this->Inputs_model->get_inputs($input);
        $view_data['breadcrum']=array('Inputs'=>"#");
        $data['page_data'] = $this->load->view('live_module/inputs', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function update_input() {
        $this->Inputs_model->update_input();
        page_alert_box("warning", "Setting Changed", "Input Crededentials Rotated");

        if ($this->input->is_ajax_request())
            echo json_encode(array("data" => 1));
        else
            redirect($_SERVER['HTTP_REFERER']);
    }

    function delete_input($id, $input_id) {
        $this->Inputs_model->delete_input($id, $input_id);
    }

    public function fetch_videocrypt_channels(){

        $vc_key = json_decode(aes_cbc_decryption(get_db_meta_key($this->db, "vc_key"),''));
        $accesskey= base64_encode($vc_key->vc_access_key);
        $secret=base64_encode($vc_key->vc_secret_key);
        
        // $accesskey= "TTdBSlNPVVFWRUs0RzJORDY5SFA=";
        // $secret="WHc3QmhnS0VSNUZBK1ZRQ29HWk1wc2M2UFMyYUltM2pmSFR5TGtPdg==";

        $headers = array('Content-Type: application/json',"accessKey:$accesskey","secretKey:$secret");
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://www.videocrypt.in/index.php/rest_api/channel/get_channel");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        curl_close($ch);        
        $data = json_decode($data,true);

        if(!empty($data) &&   $data['status']==1){
            if(!empty($data['data'])){
                if(!empty($data['data']['inputs'])){
                    foreach($data['data']['inputs'] as $input){
                        $input_exist=$this->db->get_where('aws_channel_input',array('input_id'=>$input['input_id'],'app_id'=>APP_ID))->row_array();
                        if(empty($input_exist)){
                            unset($input['created_by']);
                            unset($input['id']);
                            $input['app_id']=APP_ID;
                            $this->db->insert('aws_channel_input',$input);
                        }else{
                            unset($input['created_by']);
                            unset($input['id']);
                            $this->db->where('id',$input_exist['id']);
                            $this->db->update('aws_channel_input',$input);
                        }
                    }
                }
                if(!empty($data['data']['channels'])){
                    foreach($data['data']['channels'] as $channels){
                        $aws_channel_input=$this->db->get_where('aws_channel',array('channel_id'=>$channels['channel_id'],'app_id'=>APP_ID))->row_array();
                        if(empty($aws_channel_input)){
                            $insert_channel=array(
                             'channel_id'=>$channels['channel_id'],
                             'input_ids'=>$channels['input_ids'],
                             'media_package_ids'=>$channels['media_package_ids'],
                             'channel_name'=>$channels['channel_name'], 
                             'state'=>$channels['state'],
                             'arn'=>$channels['arn'], 
                             'channel_class'=>$channels['channel_class'], 
                             'codec'=>$channels['codec'], 
                             'bit_rate'=>$channels['bit_rate'], 
                             'resolution'=>$channels['resolution'], 
                             'log_level'=>$channels['log_level'], 
                             'json'=>$channels['json'], 
                             'remark'=>$channels['remark'], 
                             'created'=>$channels['created'], 
                             'app_id'=>APP_ID);
                             $insert_channel['output_a']="";
                             $insert_channel['output_b']="";
                             $insert_channel['output_c']="";
                                if($channels['output_non_drm_hls']!=""){
                                    $insert_channel['output_a']='https://'.$channels['cf_domain'].'/'.$channels['output_non_drm_hls'];
                                }
                                if($channels['output_drm_dash']!=""){
                                    $insert_channel['output_b']='https://'.$channels['cf_domain'].'/'.$channels['output_drm_dash'];
                                }
                                if($channels['output_drm_hls']!=""){
                                    $insert_channel['output_c']='https://'.$channels['cf_domain'].'/'.$channels['output_drm_hls'];
                                }

                            $this->db->insert('aws_channel',$insert_channel);
                        }else{
                            $insert_channel=array(
                            'channel_id'=>$channels['channel_id'],
                            'input_ids'=>$channels['input_ids'],
                            'media_package_ids'=>$channels['media_package_ids'],
                            'channel_name'=>$channels['channel_name'], 
                            'state'=>$channels['state'],
                            'arn'=>$channels['arn'], 
                            'channel_class'=>$channels['channel_class'], 
                            'codec'=>$channels['codec'], 
                            'bit_rate'=>$channels['bit_rate'], 
                            'resolution'=>$channels['resolution'], 
                            'log_level'=>$channels['log_level'], 
                            'json'=>$channels['json'], 
                            'remark'=>$channels['remark'], 
                            'created'=>$channels['created'], 
                            'app_id'=>APP_ID);
                            $insert_channel['output_a']="";
                             $insert_channel['output_b']="";
                             $insert_channel['output_c']="";
                                if($channels['output_non_drm_hls']!=""){
                                    $insert_channel['output_a']='https://'.$channels['cf_domain'].'/'.$channels['output_non_drm_hls'];
                                }
                                if($channels['output_drm_dash']!=""){
                                    $insert_channel['output_b']='https://'.$channels['cf_domain'].'/'.$channels['output_drm_dash'];
                                }
                                if($channels['output_drm_hls']!=""){
                                    $insert_channel['output_c']='https://'.$channels['cf_domain'].'/'.$channels['output_drm_hls'];
                                }
                            $this->db->where('id',$aws_channel_input['id']);
                            $this->db->update('aws_channel',$insert_channel);
                        }
                    }
                }
                if(!empty($data['data']['media_package_channel'])){
                    foreach($data['data']['media_package_channel'] as $aws_media_package_channel){
                        $media_package_channel=$this->db->get_where('aws_media_package_channel',array('channel_id'=>$aws_media_package_channel['channel_id'],'app_id'=>APP_ID))->row_array();
                        if(empty($media_package_channel)){
                           unset($aws_media_package_channel['IngestEndpoints']);
                           unset($aws_media_package_channel['id']);
                           $aws_media_package_channel['app_id']=APP_ID;
                            $this->db->insert('aws_media_package_channel',$aws_media_package_channel);
                        }else{
                            
                           unset($aws_media_package_channel['IngestEndpoints']);
                           unset($aws_media_package_channel['id']);
                            $this->db->where('id',$media_package_channel['id']);
                            $this->db->update('aws_media_package_channel',$aws_media_package_channel);
                        }
                    }
                }
                if(!empty($data['data']['media_package_channel_endpoint'])){
                    foreach($data['data']['media_package_channel_endpoint'] as $media_package_channel_endpoint){
                        $aws_mpep_exist=$this->db->get_where('aws_media_package_endpoint',array('channel_id'=>$media_package_channel_endpoint['channel_id'],'app_id'=>APP_ID))->row_array();
                        if(empty($aws_mpep_exist)){

                            $aws_mp_ep= array( 
                            'channel_id'=>$media_package_channel_endpoint['channel_id'],
                            'endpoint_id'=>$media_package_channel_endpoint['endpoint_id'],
                            'description'=>$media_package_channel_endpoint['description'],
                            'arn'=>$media_package_channel_endpoint['arn'],
                            'start_over_window'=>$media_package_channel_endpoint['start_over_window'],
                            'segment_duration'=>$media_package_channel_endpoint['segment_duration'],
                            'stream_order'=>$media_package_channel_endpoint['stream_order'],
                            'url'=>$media_package_channel_endpoint['url'],
                            'cf_domain'=>$media_package_channel_endpoint['url'],
                            'json'=>$media_package_channel_endpoint['json'],
                            'remark'=>$media_package_channel_endpoint['remark'],
                            'created'=>$media_package_channel_endpoint['created'],
                            'app_id'=>APP_ID );

                            $this->db->insert('aws_media_package_endpoint',$aws_mp_ep);
                        }else{
                            
                            $aws_mp_ep= array( 
                                'channel_id'=>$media_package_channel_endpoint['channel_id'],
                                'endpoint_id'=>$media_package_channel_endpoint['endpoint_id'],
                                'description'=>$media_package_channel_endpoint['description'],
                                'arn'=>$media_package_channel_endpoint['arn'],
                                'start_over_window'=>$media_package_channel_endpoint['start_over_window'],
                                'segment_duration'=>$media_package_channel_endpoint['segment_duration'],
                                'stream_order'=>$media_package_channel_endpoint['stream_order'],
                                'url'=>$media_package_channel_endpoint['url'],
                                'cf_domain'=>$media_package_channel_endpoint['url'],
                                'json'=>$media_package_channel_endpoint['json'],
                                'remark'=>$media_package_channel_endpoint['remark'],
                                'created'=>$media_package_channel_endpoint['created'],
                                'app_id'=>APP_ID );
                            $this->db->where('id',$aws_mpep_exist['id']);
                            $this->db->update('aws_media_package_endpoint',$aws_mp_ep);
                        }
                    }
                }
            }
            echo json_encode(array("type" => "success", "title" => "success", "message" => "successfully synced"));die;
        }


        if (!$data) {
            echo json_encode(array("type" => "error", "title" => "error!", "message" => "Play List is not Available"));
            die;
        }
    }

}
