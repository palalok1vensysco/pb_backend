<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH . '/third_party/aws/aws-autoloader.php');

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
use Aws\MediaLive\MediaLiveClient;
use Aws\CloudFront\CloudFrontClient;
use Aws\Exception\AwsException;

class Channels extends MX_Controller {

    function __construct() {
        parent::__construct();
        /* !!!!!! Warning !!!!!!!11
         *  admin panel initialization
         *  do not over-right or remove auth_panel/auth_panel_ini/auth_ini
         */
        $this->load->helper(['aul', 'custom']);
        modules::run('auth_panel/auth_panel_ini/auth_ini');
        $this->load->library('form_validation');
        $this->redis_magic = new Redis_magic("session");
        $this->load->model(['live_module/Credentials_model', 'live_module/Channels_model']);
    }

    public function fetch_channel(){
        $accesskey= base64_encode(VC_ACCESS_KEY);
            $secret=base64_encode(VC_SECRET_KEY); 
            $data = [];
            $headers = array("accessKey:$accesskey","secretKey:$secret");
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://api.videocrypt.com/getChannel");
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            $data = curl_exec($ch);
            curl_close($ch);        
            $server_output = json_decode($data, true);
            // pre($server_output);die;
            if(!empty($server_output['status'])){
                $count_data = count($server_output['data']['channels']);
                $channel_ids = [];
                for ($i=0; $i < $count_data; $i++) {
                    $input_data = $server_output['data']['inputs'][$i];
                    $output_data = $server_output['data']['channels'][$i];
                    $arr = [];
                    $arr = array(
                        'rtmp_url' => "rtmp://" . $input_data['ip_a'] . ":" . $input_data['port_a'] . "/" . $input_data['destination_a_name'],
                        'rtmp_key' =>  $input_data['destination_a_key'],
                        'channel_id' => $output_data['channel_id'],
                        'channel_name' => $output_data['channel_name'],
                        'media_package_ids' => $output_data['media_package_ids'],
                        'drm_enabled' => $output_data['drm_enabled'],
                        'file_url_hls' => "https://" . $output_data['cf_domain'] . "/" . $output_data['output_non_drm_hls'],
                        'state' => $output_data['state'],
                        'status' => 0,
                        'channel_id' => $output_data['channel_id'],
                        'created_by' => $this->session->userdata('active_backend_user_id')
                    );
                    $channel_ids[] = $arr['channel_id'];
                    $aws_channel = $this->db->get_where('aws_channel', ['channel_id' => $arr['channel_id']])->row_array();
                    if(!empty($aws_channel)){
                        $arr['modified_at'] = time();
                        $this->db->update('aws_channel', $arr, ['id' => $aws_channel['id']]);
                    }else{
                        $arr['created_at'] = time();
                        $this->db->insert('aws_channel', $arr);
                    }
                }
                $this->db->where_not_in('channel_id', $channel_ids);
                $this->db->update('aws_channel', ['status' => 2]);
                page_alert_box('success', 'Success', "data fetched successfully!!..");
                redirect_to_back();
            }

            if(empty($server_output['status'])){
               page_alert_box('error', 'Error', $server_output['message']);
               redirect_to_back();
            }
    }

    public function index() {
        $input = $this->input->post();
        $view_data['breadcrum']=array('Channel'=>"#");
        $data['page_data'] = $this->load->view('live_module/channels', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

   
    public function ajax_channel(){
        $output_csv = $output_pdf = false;
              $requestData = $_REQUEST;
        $columns = array(
            // datatable column index  => database column name
            0 => 'id',
            1 => 'channel_name',
            2 => 'channel_id',
            3 => 'state',
            4 => 'rtmp_url',
            5 => 'rtmp_key',
            6 => 'created_at',
            7 => 'modified_time'
        );

        $query = "SELECT count(id) as total
                  FROM aws_channel where status in(0,1)";
        $query = $this->db->query($query);
        $query = $query->row_array();
        $totalData = (count($query) > 0) ? $query['total'] : 0;
        $totalFiltered = $totalData;
        $sql = "SELECT * FROM aws_channel where status in(0,1)";
        if (!empty($requestData['columns'][0]['search']['value'])) {
            $sql .= " AND channel_name LIKE '" . $requestData['columns'][0]['search']['value'] . "%' ";
        }

        if (!empty($requestData['columns'][1]['search']['value'])) {
            $sql .= " AND channel_id LIKE '" . $requestData['columns'][1]['search']['value'] . "%' ";
        }

        $query = $this->db->query($sql)->result();

        $totalFiltered = count($query); 
        if(isset($requestData['start'])){
       $sql .= " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
       } // adding length

        $result = $this->db->query($sql)->result();
        
        $data = array();
        foreach ($result as $r) {
            // preparing an array
            $nestedData = array();
            $nestedData[] = ++$requestData['start'];
            $nestedData[] = ucfirst($r->channel_name);
            $nestedData[] = $r->channel_id;
            $nestedData[] = $r->state;
            $nestedData[] = $r->rtmp_url;
            $nestedData[] = $r->rtmp_key;
            $nestedData[] = $r->created_at ? get_date_format($r->created_at) : "--NA--";
            // $nestedData[] = "<a class='btn-xs bold btn btn-primary' onclick=\"return confirm('Are you sure you want to edit?')\" href='" . base_url('auth_panel/category/category/map_edit/') . $r->id . "'><i class='fa fa-edit'></i></a>&nbsp;
            // ";
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


}
