<?php

use Aws\S3\S3Client;

use Aws\S3\Exception\S3Exception;
use Aws\MediaLive\MediaLiveClient;
use Aws\CloudFront\CloudFrontClient;
use Aws\Exception\AwsException;
defined('BASEPATH') OR exit('No direct script access allowed');

class Testing extends MX_Controller {

    function __construct() {
        parent::__construct();
        /* !!!!!! Warning !!!!!!!11
         *  admin panel initialization
         *  do not over-right or remove admin/auth_panel_ini/auth_ini
         */
        $this->load->helper(['aes', 'compress', 'aul', 'custom']);
        $this->load->library(['upload', 's3_upload']);
        $this->load->model(['live_module/Credentials_model', 'live_module/Channels_model', 'live_module/Inputs_model', 'live_module/Media_package_model']);
    }
    

    public function test(){
        $post_data = json_decode('{"id":"673","start_time":"11:54 AM","end_time":"01:05 PM","turn_of_channel":"0","end_diff":"14","start_diff":"0","go_live":"1"}', true);
        $this->db->select('aw.*,aend.endpoint_id');
        $this->db->from('aws_channel aw');
        $this->db->join('aws_media_package_endpoint aend', 'aw.media_package_ids = aend.channel_id AND aend.endpoint_id LIKE CONCAT("%non_drm_hls_endpoint_%")');
        $this->db->where('aw.id', $post_data['id']);
        $this->db->order_by('aw.id', 'desc');
        $channl = $this->db->get()->row_array();
        // echo $this->db->last_query();die;
        $insert_file_id = "146100";
        $lib = $this->db->get_where('file_library', ['id' => $insert_file_id])->row_array();
        $token = explode('_', $lib['token'])[2];
        $manifest = aes_cbc_decryption($lib['file_url_hls'], $token);
        $channl['start_time'] =  strtotime($post_data['start_time']);
        $channl['end_time'] =  strtotime($post_data['end_time']);
        // pre($channl);die;
        $vod_return_data = $this->Media_package_model->schedule_harvest($channl, 2, $insert_file_id, $manifest, $token);
        pre($vod_return_data);
    }



    function case1() {

        die('not-found');

        $account_id         = '10000177'; 
        $backend_user_id    = '177';
        $client_user_id     = '0';

        $this->db->select('file_library_guidely_11feb.*');
        $this->db->where('id >', 687);
        $this->db->where('file_type',3);
        $this->db->where('file_url !=', '');
        $this->db->order_by('id','asc');
        $result             = $this->db->get('file_library_guidely_11feb')->result_array(); //'backend_user_id','client_user_id',
        //pre($result); die;
        foreach ($result as $key => $each) {
            $insarry        = array();
            $platform=[];
            if(!empty($each['drm_dash_url'])){
                $platform[]   = "1";
            }
            if(!empty($each['drm_ios_url'])){
                $platform[]   = "2";
            }            
            $platform[]   = "3";
            $platform[]   = "4";
            $insarry['platform']    = implode(',', $platform);            

            $cols_array = array('drm_dash_url','drm_ios_url','thumbnail_url','title','title_2','description','description_2','file_type','live_status','is_vod','channel_id','playtime','channel_url_type','video_type','token','mediaconvert_tracking','mediaconvert_tracking_dash','mediaconvert_tracking_ios','original_video_url','bitrate_channel','file_size','file_name','video_length','vod_conversion_type');
            foreach ($cols_array as $col_nam) {                
                $insarry[$col_nam] = $each[$col_nam];
            }           

            $extra_cols             = array('file_url_dash','channel_sess_id','mediaconvert_tracking_hls_non_drm','mediaconvert_tracking_dash_non_drm','dwnld_enc_url','dwnld_encrypted_urls','drm_del_mp_dash','drm_del_mp_hls','drm_del_mp_job_json');
            foreach ($extra_cols as $col_nam) {                
                $insarry[$col_nam] = '';
            }

            $extra_cols = array('opted_dwnld_vod','drm_enabled','download_mode','download_status','output_via','drm_del_mode','is_list');
            foreach ($extra_cols as $col_nam) {                
                $insarry[$col_nam] = '0';
            }

            $client_ids = array( 
                        0 => 0,
                        75 => 183,
                        79 => 186,
                        82 => 187,
                        83 => 188,
                        84 => 180
                    );

            $insarry['client_user_id']      = $client_ids[$each['client_user_id']]; 
            $insarry['file_url_hls']        = $each['file_url'];  
            $insarry['backend_user_id']     = $backend_user_id; 
            $insarry['account_id']          = $account_id; 
            $insarry['vod_s3_size']         = 0; 
            $insarry['creation_time']       = time(); 
            $insarry['modify_time']         = time(); 
            //pre($insarry);
            $this->db->insert('file_library',$insarry);
            $insert_id = $this->db->insert_id();

            $tokenarr               = explode('_', $each['token']);
            $tokenarr[0]            = $insert_id;
            $token                  = implode('_', $tokenarr);

            $this->db->where('id',$insert_id);
            $this->db->update('file_library', array('token' => $token));

            //echo $this->db->last_query();
            //die('case1');
        }
        die('done');
    }


    function case2_update_file() {

        die('not-found');

        $account_id         = '10000161'; 
        $this->db->where('account_id',$account_id);
        $this->db->where('platform !','');
        //$this->db->where('id > ', 1530);
       // $this->db->order_by('id','desc');
        $result = $this->db->get('file_library')->result_array(); //'backend_user_id','client_user_id',
        //pre($result); die;
        $i=0;
        foreach ($result as $each) {$i++;
            $platform = [];
            //$platform = array(3,4);
            //if(!empty($each['drm_dash_url'])){
              //  $platform[]   = "1";
            //}
            //if(!empty($each['drm_ios_url'])){
            //    $platform[]   = "2";
            //}            
            //$platform[]   = "3";
            $platform[]   = "4";

            $updatearry['platform']    = implode(',', $platform);

            //$tokenarr               = explode('_', $each['token']);
            //$tokenarr[0]            = $each['id'];
            //$updatearry['token']    =  implode('_', $tokenarr);

            $this->db->where('id',$each['id']);
            $this->db->update('file_library',$updatearry);

        }echo $i;
        die('done');
    }

    function case3_update_file() {

        die('not-found');
        //$account_id         = '10000177';  
        //$this->db->where('account_id',$account_id);
        $this->db->select('id,backend_user_id,client_user_id,original_video_url,token');
        $this->db->where('client_user_id !=',0);
        $this->db->where('original_video_url !=', '');
        $result = $this->db->get('file_library_guidely')->result_array(); //'backend_user_id','client_user_id',
        
        foreach ($result as $each) {
            $this->db->select('id,backend_user_id,client_user_id,original_video_url,token');
            $this->db->where('original_video_url',$each['original_video_url']);
            $result1 = $this->db->get('file_library')->row_array();
            //pre($each);pre($result1); die;
            
            $cuid = array( 
                        75 => 183,
                        79 => 186,
                        82 => 187,
                        83 => 188,
                        84 => 180
                    );

            $this->db->where('id',$result1['id']);
            $this->db->update('file_library', array('client_user_id' => $cuid[$each['client_user_id']] ) );
            //pre($each);pre($result1); die;

        }
        die('done');
    }


}


