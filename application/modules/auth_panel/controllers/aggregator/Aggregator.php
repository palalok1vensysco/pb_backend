<?php

defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'third_party/aws/aws-autoloader.php');
require_once APPPATH . '/helpers/jwt/src/JWT.php';
require_once APPPATH . '/helpers/jwt/src/BeforeValidException.php';
require_once APPPATH . '/helpers/jwt/src/ExpiredException.php';
require_once APPPATH . '/helpers/jwt/src/SignatureInvalidException.php';

use \Firebase\JWT\JWT;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

class Aggregator extends MX_Controller
{

    protected $redis_magic = null;


    function __construct()
    {
        parent::__construct();
        $this->load->helper(['aes', 'aul', 'custom']);
        modules::run('auth_panel/auth_panel_ini/auth_ini');
        $this->load->library('form_validation', 'uploads');
        $this->load->model("Library_model");
        $this->load->model("Category_model");
        $this->redis_magic = new Redis_magic("session");
    }
    public function add_aggregator()
    {
        if ($this->input->post()) {
            $category_new = $_POST['title'];
            $sql = "select title from aggregator where title = '$category_new'";
            $query = $this->db->query($sql);
            $checkrows = $query->num_rows();
            if ($checkrows == 0) {
                $insert_data = array(
                    'title' => $this->input->post('title'),
                    'status' => 1,
                    'created_at' => time(),
                );
                $id = $this->Category_model->add_aggregator($insert_data);
                if ($id) {
                    if (!empty($_FILES['bg_video']['name'])) {
                        $bg_video = amazon_s3_upload($_FILES['bg_video'], "file_manager/videos", $id);
                    }
                }
                if (!empty($_FILES['thumbnail']['name'])) {
                    $trhumbnail_url = amazon_s3_upload($_FILES['thumbnail'], "file_manager/videos", $id);
                }

                if ($bg_video) {
                    $this->db->set('bg_video', $bg_video);
                    $this->db->where('id', $id);
                    $this->db->update('aggregator');
                }
                if ($trhumbnail_url) {
                    $this->db->set('thumbnail', $trhumbnail_url);
                    $this->db->where('id', $id);
                    $this->db->update('aggregator');
                }
                update_api_version_new($this->db, 'menu_master');
                backend_log_genration($this, "Aggregator {$this->input->post('title')} Map has been created by User(ID : {$this->session->userdata('active_backend_user_id')}).", "Aggregator");
                page_alert_box('success', 'Added', 'New Category added successfully');
                redirect($_SERVER['HTTP_REFERER']);
            } else {
                page_alert_box('error', 'Duplicate', 'Category already exist');
                redirect($_SERVER['HTTP_REFERER']);
            }
        }

        $view_data['page'] = 'add_aggregator';
        $data['page_title'] = "Add Aggregator";
        $view_data['breadcrum'] = array('Videos' => "findiaott/Add_video");
        $data['page_data'] = $this->load->view('aggregator/Add_aggregator', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }


   
    public function ajax_aggregator_list()
    {

        $columns = array(
            0 => 'id',
            1 => 'title'
        );
        $requestData = $_REQUEST;
        $this->db_read->select('COUNT(id) as total');
        $this->db_read->where('status !=', 2);
        $this->db_read->from('aggregator');
        $query = $this->db_read->get();
        $totalData = $query->row()->total;
        
        $totalFiltered = $totalData;

        $this->db_read->select('id, title, thumbnail, created_at,status');

        if ($text = $requestData['columns'][1]['search']['value']) {
            $this->db_read->like('title', $text);
        }
               
        if(isset($requestData['start'])){
            $this->db_read->order_by($columns[$requestData['order'][0]['column']], 'DESC');
            $this->db_read->limit($requestData['length'], $requestData['start']);
             }
        $this->db_read->from('aggregator');    
        $query = $this->db_read->get();
        $result = $query->result();

        $data = array();
        
        foreach ($result as $r) {
            $action_btn = $r->status == 1 ? "Enable" : "Disable";
            $alert = $r->status == 1 ? "Disable" : "Enable";
            $toggle = $r->status == 1 ? "fa fa-toggle-on" : "fa fa-toggle-off";

            $action = "<div class='dropdown toggle_menus_icons'> <button class='btn dropdown_ttgl text-white dropdown-toggle ' type='button' data-toggle='dropdown'><i class='fa fa-ellipsis-v' aria-hidden='true'></i></button>
                <ul class='dropdown-menu'>               
                    <li><a class='' href='" . AUTH_PANEL_URL . "aggregator/Aggregator/edit_aggregator/" . $r->id . "'>Edit</a></li>
                    <li><a class='' onclick=\"return confirm('Are you sure you want to " . $alert . "?')\" href='" . AUTH_PANEL_URL . "aggregator/Aggregator/disable_aggregator/" . $r->id . "'>" . $action_btn . "</a></li>
                </ul>
                </div>";
            $thumbnail = $r->thumbnail;            
            $nestedData = array();
            $nestedData[] =  ++$requestData['start'];  //++$start;
            $nestedData[] = $r->title;
            $nestedData[] = "<img width ='60px' src= " . $thumbnail . ">";
            $nestedData[] = ($r->created_at > 0) ? get_time_format($r->created_at) : "N/A";
            $nestedData[] = $action;
            $data[] = $nestedData;
        }

        $json_data = array(
            "draw" => intval($requestData['draw']),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );

        $json_data = json_encode($json_data);
        echo s3_to_cf($json_data);
    }

    public function disable_aggregator($id)
    {
        if (!empty($id)) {
            $res = $this->db->select('status')->get_where('aggregator', ['id' => $id])->row_array();
            $status = $res['status'] == 1 ? 0 : 1;
            $videoarr = array(
                'status' => $status
            );
            $this->db->where('id', $id);
            $res = $this->db->update('aggregator', $videoarr);
            if ($res) {
                //update status of notification start
                $msg = (($status == 1) ? "Aggregator Activated" : "Aggregator Deactivated");
                backend_log_genration($this,"{{ $msg }} by User(ID : {$this->session->userdata('active_backend_user_id')}).","Aggregator");
                page_alert_box("success", "success!", $msg);
                redirect_to_back();
            } else {
                page_alert_box("error", "error!", "Unable to perform action");
            }
        } else {
            page_alert_box("error", "error!", "Unable to perform action");
        }        
    }




    public function edit_aggregator($id)
    {
        $view_data['agg_detail'] = $this->Category_model->get_aggregator_by_id($id);
        if (!empty($view_data)) {
            if ($this->input->post()) {
                $insert_id = $id;
                if (!empty($_FILES['bg_video']['name'])) {
                    $thumbnail = amazon_s3_upload($_FILES['bg_video'], "file_manager/videos", $id);
                }
                if (!empty($_FILES['thumbnail']['name'])) {
                    // pre("aagyi");die;
                    $poster_url = amazon_s3_upload($_FILES['thumbnail'], "file_manager/videos", $id);
                }
                $update_data = array(
                    'title' => $this->input->post('title')
                );               
                if (isset($thumbnail))
                    $update_data['bg_video'] = $thumbnail;
                if (isset($poster_url))
                    $update_data['thumbnail'] = $poster_url;
                $this->db->where('id', $insert_id);
                $updat_db =  $this->db->update('aggregator', $update_data);
                if ($updat_db) {
                    backend_log_genration($this,"Aggregator ID {$id} has been changed by User(ID : {$this->session->userdata('active_backend_user_id')}).","Aggregator");                    
                    page_alert_box('success', 'Action performed', 'Aggregator updated successfully');
                    redirect_to_back();
                } else {
                    page_alert_box('error', 'Action Not  performed', 'Aggregator not updated');
                    redirect_to_back();
                }
            }
        }
        $data['page_title'] = "Edit Aggregator";        
        $data['page_data'] = $this->load->view('aggregator/edit_aggregator', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }
}
