<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH . 'third_party/aws/aws-autoloader.php');
//----added by Akhilesh start------
require APPPATH.'/third_party/vendor/autoload.php';
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

class Fast_channel extends MX_Controller {

    protected $redis_magic = null;

    //-------------
    protected $CHANG_ACCESS_KEY;
    protected $CHANG_BUCKET_KEY;
    protected $CHANG_CLOUDFRONT;
    protected $CHANG_REGION;
    //------------

    function __construct() {
        parent::__construct();
        $this->load->helper(['aes', 'aul', 'custom']);
        modules::run('auth_panel/auth_panel_ini/auth_ini');
        define('Z_API_KEY', '94dSY8yOS7aP-Vsj1Bz6aA');
        define('Z_SECRET_KEY', 'BzBN9QOQtwOlCQCbB9CbOFfZHiM6UjhNOMw3');  
        $this->load->library('form_validation', 'uploads');
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
        //print_r($s3details) ;die;
        if ($s3details) {
            $this->CHANG_ACCESS_KEY = $s3details->access_key;
            $this->CHANG_BUCKET_KEY = $s3details->bucket_key;
            $this->CHANG_CLOUDFRONT = $s3details->cloudfront;
            $this->CHANG_REGION = $s3details->region;            
        }
      
    }

    public function fast_channel_list() {

        $view_data['page'] = 'Fast Channel';
        $data['page_title'] = 'Fast Channel';
        $view_data['breadcrum']=array('fast_channel'=>"Fast_channel/fast_channel_list");
        $data['page_data'] = $this->load->view('fast_channel_list', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    function start_channel($id) { 
       
        $vc_key = json_decode(aes_cbc_decryption(get_db_meta_key($this->db, "vc_key"),'')); 
        if($vc_key->vc_access_key && $vc_key->vc_secret_key){
            $data=array('channel_id'=>$id);
            $accesskey= base64_encode($vc_key->vc_access_key);
            $secret=base64_encode($vc_key->vc_secret_key); //pre($vc_key); pre($accesskey); die;
            $headers = array('Content-Type: application/json',"accessKey:$accesskey","secretKey:$secret");
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://api.videocrypt.com/startChannelAssembly");
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            $data = curl_exec($ch);
            curl_close($ch);        
            $play_list = json_decode($data,true);
            redirect(AUTH_PANEL_URL .'Fast_channel/fast_channel_list/', 'refresh');
           
           
        }else{
            echo json_encode(array("type" => "error", "title" => "Error!", "message" => "Kindly update video crypt keys in configuration page."));
                    die;
        }
    }

    function stop_channel($id) { 
       
        $vc_key = json_decode(aes_cbc_decryption(get_db_meta_key($this->db, "vc_key"),'')); 
        if($vc_key->vc_access_key && $vc_key->vc_secret_key){
            $data=array('channel_id'=>$id);
            $accesskey= base64_encode($vc_key->vc_access_key);
            $secret=base64_encode($vc_key->vc_secret_key); //pre($vc_key); pre($accesskey); die;
            $headers = array('Content-Type: application/json',"accessKey:$accesskey","secretKey:$secret");
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://api.videocrypt.com/stopChannelAssembly");
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            $data = curl_exec($ch);
            curl_close($ch);        
            $play_list = json_decode($data,true);
            redirect(AUTH_PANEL_URL .'Fast_channel/fast_channel_list/', 'refresh');
           
           
        }else{
            echo json_encode(array("type" => "error", "title" => "Error!", "message" => "Kindly update video crypt keys in configuration page."));
                    die;
        }
    }


 
 
    function ajax_fetch_channel($return='json') { 
       
        $vc_key = json_decode(aes_cbc_decryption(get_db_meta_key($this->db, "vc_key"),'')); 
        if($vc_key->vc_access_key && $vc_key->vc_secret_key){
            $accesskey= base64_encode($vc_key->vc_access_key);
            $secret=base64_encode($vc_key->vc_secret_key); 
            $headers = array('Content-Type: application/json',"accessKey:$accesskey","secretKey:$secret");
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://api.videocrypt.com/getAllChannels");
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
           // curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            $data = curl_exec($ch);
            curl_close($ch);        
            $play_list = json_decode($data,true);
            $app_id = (defined("APP_ID") && APP_ID)?APP_ID:0; 
            foreach ($play_list['data'] as $r) {
                $this->db->select('ch_id');
                $this->db->where('app_id',$app_id);
                $this->db->where('ch_id',$r['id']);
                $select_data = $this->db->get('fast_channel')->row_array();
                if(empty($select_data)){

                    $arr= array(
                    'ch_id' => $r['id'],
                    'account_id' => $r['account_id'],
                    'app_id' => $app_id,
                    'name' => $r['name'],
                    'playback_mode' => $r['playback_mode'],
                    'playback_url' => $r['playback_url'],
                    'channel_vod_type' => $r['channel_vod_type'],
                    'channel_state' => $r['channel_state'],
                    'aws_arn' => $r['aws_arn'],
                    'created_date'  => $r['created_date'],
                    'schedule_count' => $r['schedule_count'],
                    );
                    $this->db->insert('fast_channel', $arr);

                }
            }

            $requestData = $_REQUEST;
            $user_data = $this->session->userdata('active_user_data');
            $backend_user_id = $user_data->id;
            $where = "";
    
            $columns = array(
            // datatable column index  => database column name
                0 => 'fc.id',
                1 => 'fc.name',
                // 2 => 'ctfmm.course_names',
                // 3 => 'csm.name',
                // 4 => 'cstm.topic',
                // 5 => 'ctfmm.is_download',
                // 6 => 'bu.username',
                // 7 => 'ctfmm.created',
            );
    
            $query = "SELECT count(id) as total FROM fast_channel  where ch_id !='' ";
            $query .=  (defined("APP_ID") ? "" . app_permission("app_id") . "" : "");
            $query = $this->db->query($query);
            $query = $query->row_array();
            $totalData = (count($query) > 0) ? $query['total'] : 0;
    
            $filtered_sql = $sql = "SELECT * FROM fast_channel fc";

            $where = " where ch_id !='' ";

            if (!empty($requestData['columns'][0]['search']['value'])) {
                $where .= " AND fc.id LIKE '" . $requestData['columns'][0]['search']['value'] . "%' ";
            }
            if (!empty($requestData['columns'][1]['search']['value'])) {
                $where .= " AND fc.name LIKE '" . $requestData['columns'][1]['search']['value'] . "%' ";
            }
            
    
            $where .=  (defined("APP_ID") ? "" . app_permission("fc.app_id") . "" : "");
            $sql .= $where;
            $filtered_sql .= $where;
    
            $totalFiltered = $totalData;
    
            $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   "; // adding length
    
    
            $result = $this->db->query($sql)->result();
        
          // pre($play_list['data']);die;

            
        $data = array();

        foreach ($result as $r) {  // preparing an array
            $nestedData = array();
            $nestedData[] =  ++$requestData['start'];
            $nestedData[] = $r->name;
            $nestedData[] = $r->playback_mode;
            $nestedData[] = $r->channel_vod_type;
            $nestedData[] = $r->created_date;
            $nestedData[] = $r->channel_state; 
            $nestedData[] = $r->schedule_count;
            $control = "&nbsp;<a  onclick=\"return confirm('Warning !!!!  Are you sure you want to stop ?');\" class=' btn-xs bold btn btn-danger' href='" . AUTH_PANEL_URL . "Fast_channel/stop_channel/" . $r->id . "'><i class='fa fa-close'></i>&nbsp Stop</a>";
            $nestedData[] = "<a onclick=\"return confirm('Warning !!!!  Are you sure you want to start ?');\" class='btn-xs bold btn btn-info' href='" . AUTH_PANEL_URL . "Fast_channel/start_channel/" . $r->id . "'><i class='fa fa-toggle-right'></i>&nbsp Start</a>&nbsp;$control";
            $data[] = $nestedData;
        }

        $json_data = array(
            "draw" => intval($requestData['draw']), // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they 			first check the draw number, so we are sending same number in draw.
            "recordsTotal" => intval($totalData), // total number of records
            "recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
            "data" => $data   // total data array
        );
        echo json_encode($json_data);  // send data as json format            
        }else{
            echo json_encode(array("type" => "error", "title" => "Error!", "message" => "Kindly update video crypt keys in configuration page."));
                    die;
        }
    }



  
    private function word_formatter($string) {
        $string = explode(" ", strip_tags($string));
        if ($string && count($string) > 25) {
            $string = array_slice($string, 0, 25, true);
        }
        return implode(" ", $string);
    }

    
    
   

}
