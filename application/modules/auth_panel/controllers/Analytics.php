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

class Analytics extends MX_Controller {

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
        $this->load->model("Library_model");
        $this->load->model("Category_model");
        $this->load->model("Movies_model");
         $this->load->model("guru_model");
         $this->load->model("Premium_video_model");
        $this->redis_magic = new Redis_magic("session");
        $this->load->model("Tv_serial_model");
        $this->load->model("Songs_model");
        $this->load->model("Album_control_model");
        $this->load->model("Artist_control_model");
        //----------
        $this->retrieve_s3crendential();
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

    public function index() {

        
        $view_data['page'] = 'application_analytics';
        $data['page_title'] = "Application Analytics";
        $view_data['breadcrum']=array('Analytics'=>"analytics/index");
        $data['page_data'] = $this->load->view('Analytics/analytics', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }
   
    public function most_watched_list() {
       
        $requestData = $_REQUEST;
       // print_r($requestData);
        $user_data = $this->session->userdata('active_user_data');
        //$instructor_id = $user_data->instructor_id;
        $backend_user_id = $user_data->id;
        $where =   ' where 1=1';
        
        $where .=  (defined("APP_ID") ? "" . app_permission("app_id") . "" : "");
        // $query = "SELECT count(id) as total FROM aggregator ";
        // $total_query = $this->db->query($query . $where)->row_array();
        // $totalData = (count($total_query) > 0) ? $total_query['total'] : 0;
        $totalData = 5;
        $sql = "SELECT id as file_id, title, poster_url, published_date FROM course_topic_file_meta_master ";
          $where .= " ORDER BY RAND() LIMIT 5";
        //$totalFiltered = $this->db->query($query)->row()->total;

        $totalFiltered = 5;
        $sql .= $where;
       
        $result = $this->db->query($sql)->result();
        $data = array();
       $start = array();
        $start = 0;
        foreach ($result as $r) { //pre($r);die;
             
               $poster_url = $r->poster_url;
            // preparing an array
            $nestedData = array();         
            $nestedData[]=  ++$requestData['start'];  //++$start;
            $nestedData[] = $r->file_id;
            $nestedData[] = $r->title;
           
           
            $nestedData[] = "<img width ='60px' src= " . $poster_url . ">";
            $nestedData[] = rand(100, 150); 
           
            $nestedData[] = $r->published_date;
           
           
    
            $data[] = $nestedData;
        }

        $json_data = array(
            "draw" => intval($requestData['draw']), // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
            "recordsTotal" => intval($totalData), // total number of records
            "recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
            "data" => $data, // total data array
        );

        $json_data = json_encode($json_data);  // send data as json format
        echo s3_to_cf($json_data); // send data as json format
    }
}