<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH . 'third_party/aws/aws-autoloader.php');
//----added by Akhilesh start------
// require APPPATH.'/third_party/vendor/autoload.php';
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

class Sub_Category extends MX_Controller {

    protected $CHANG_ACCESS_KEY;
    protected $CHANG_BUCKET_KEY;
    protected $CHANG_CLOUDFRONT;
    protected $CHANG_REGION;

    function __construct() {
        parent::__construct();
        /* !!!!!! Warning !!!!!!!11
         *  admin panel initialization
         *  do not over-right or remove auth_panel/auth_panel_ini/auth_ini
         */
        modules::run('auth_panel/auth_panel_ini/auth_ini');
        define('Z_API_KEY', '94dSY8yOS7aP-Vsj1Bz6aA');
        define('Z_SECRET_KEY', 'BzBN9QOQtwOlCQCbB9CbOFfZHiM6UjhNOMw3');  
        $this->load->helper(['aes', 'aul', 'custom']);
        $this->load->library('form_validation');
        //$this->load->library('aws_s3_file_upload');
        $this->load->helper('services');
        $this->load->model("Sub_Category_model");
       // $this->load->model("guru_model");
        $this->load->helper('cookie');
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
  

//XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX---> CATEGORY BLOG START <---XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
    public function add_sub_category() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('title', 'title', 'required|is_unique_with_status[genres.title]');
            $this->form_validation->set_rules('status', '', 'required');
            $this->form_validation->set_rules('thumbnail', 'Thumbnaiil Image ');
            if ($this->form_validation->run() == FALSE) {
                
            } else {
                $poster = $this->input->post('bg_img');
                if (!$poster && isset($_FILES['bg_img']) && $_FILES['bg_img']['size'] > 0){
                    $poster = amazon_s3_upload($_FILES['bg_img'], "file_manager/videos", rand());
                    $poster=str_replace( 'https://'.$this->CHANG_BUCKET_KEY . ".s3." . $this->CHANG_REGION . ".amazonaws.com",$this->CHANG_CLOUDFRONT, $poster);
                }
                $insert_data = array(
                    'title' => ucwords($this->input->post('title')),
                    'lang_id' => $this->input->post('lang_id') ?? 0,
                    'is_popular' => $this->input->post('is_popular') ?? 0,
                    'thumbnail' => $poster ?? "",
                    'created_at' => time(),
                    'modified_at' => time(),
                    'created_by' => $this->session->userdata('active_backend_user_id')
                );
                $this->db->insert('genres', $insert_data);
                backend_log_genration($this,"Genres {$this->input->post('title')} has been created by User(ID : {$this->session->userdata('active_backend_user_id')}).","Genres");
                page_alert_box('success', 'Added', 'New Genres added successfully');
                redirect($_SERVER['HTTP_REFERER']);
               
            }
        }
        $view_data['page'] = 'add_genres';
        $data['page_data'] = $this->load->view('sub_category/add_sub_category', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

   public function edit_sub_category($id) {
        $view_data['sub_category'] = $sub_category = $this->Sub_Category_model->get_category_by_id($id);
        if(empty($sub_category)){
            page_alert_box('error', 'Error', 'Genres Id is missing!!..');
            redirect(base_url() . 'admin-panel/add-category');
        }
        if ($this->input->post()) {
            $this->form_validation->set_rules('title', 'Title', 'required|edit_unique_with_status[genres.title.' . $id . ']');
            $this->form_validation->set_rules('status', 'status', 'required');
            if ($this->form_validation->run() == FALSE) {
                
            } else {
                $poster = $sub_category['thumbnail'];
                if ( isset($_FILES['bg_img']) && $_FILES['bg_img']['size'] > 0){
                    $poster = amazon_s3_upload($_FILES['bg_img'], "file_manager/videos", $id);
                    $poster=str_replace( 'https://'.$this->CHANG_BUCKET_KEY . ".s3." . $this->CHANG_REGION . ".amazonaws.com",$this->CHANG_CLOUDFRONT, $poster);
                }
               
                $update_data = array(
                    'title' => $this->input->post('title'),
                    'status' => $this->input->post('status') ?? 0,
                    'thumbnail' => $poster,
                    'is_popular' => $_POST['is_popular'] ? $_POST['is_popular'] : "0",
                    'modified_at' => time()
                );
                $this->db->update('genres', $update_data, ['id' => $id]);             
               backend_log_genration($this,"Genres {$this->input->post('title')} has been changed by User(ID : {$this->session->userdata('active_backend_user_id')}).","Category");                             
               page_alert_box('success', 'Updated', 'Genres has been updated successfully');
               redirect('admin-panel/add-sub-category');
            //  redirect($_SERVER['HTTP_REFERER']);
            }
        }
        
        $view_data['page'] = 'edit_sub_category';
        $data['page_data'] = $this->load->view('sub_category/edit_sub_category', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

//     public function delete_sub_category($id) {
//         $delete_user = $this->Sub_Category_model->delete_sub_category($id);
//         backend_log_genration($this,"Category ID {$id} has been deleted by User(ID : {$this->session->userdata('active_backend_user_id')}).","Category");                
//         page_alert_box('success', 'Genres Deleted', 'Genres has been deleted successfully');
//         redirect(BASE_URL . 'admin-panel/add-sub-category');
// //        redirect(AUTH_PANEL_URL . 'sub_category/sub_category/add_sub_category');
//     }

public function ajax_sub_category_list($device=null,$genre=null) {

    $requestData = $_REQUEST;

    $columns = array(
        0 => 'id',
        1 => 'title',
        1 => 'is_popular',
        2 => 'status',
        3 => 'created_at',
        4 => 'modified_at'
    );

    $this->db_read->select('count(id) as total');
    $this->db_read->from('users as u');
    $this->db_read->where('status !=', 2);
    $totalData = $this->db_read->get()->row()->total;
    $totalFiltered = $totalData;

    $this->db_read->select('id, title, is_popular, created_at, modified_at, status, thumbnail');
    if (isset($genre) && !empty($genre)) {
        $this->db_read->where('sub_category_name', $genre);
    }
    
    if (!empty($requestData['columns'][0]['search']['value'])) {
        $this->db_read->where('id', $requestData['columns'][0]['search']['value']);
    }

    if ($text = $requestData['columns'][1]['search']['value']) {
        $this->db_read->like('title', $text);
    }
    
    if (isset($requestData['columns'][2]['search']['value']) && $requestData['columns'][2]['search']['value'] != "") {
        $this->db_read->where('status', $requestData['columns'][2]['search']['value']);
    }
    if (isset($requestData['order'][0]['column'])) {
    $this->db_read->order_by($columns[$requestData['order'][0]['column']], 'DESC');
    }
    $this->db_read->limit($requestData['length'], $requestData['start']);
    $this->db_read->from('genres');

    $result = $this->db_read->get()->result();
    $ids=0;
    $data = array();
    foreach ($result as $r) {
        $nestedData = array();
        $nestedData[] =++$requestData['start'];
        $nestedData[] = ucfirst($r->title);                
        $nestedData[] = $r->thumbnail!=''?"<img  height = '60px' width ='60px' src= " . $r->thumbnail . ">":'';
        if($r->is_popular == 1){
            $nestedData[] = "<a class='btn-xs bold btn btn-success' style='background-color: green;' onclick=\"return confirm('Are you sure you want to change status?')\" href='" . base_url('auth_panel/sub_category/sub_category/update_popular_status/') . $r->id . "'>Yes</a> ";
        }else{
            
            $nestedData[] = "<a class='btn-xs bold btn btn-danger' style='background-color: red; hover' onclick=\"return confirm('Are you sure you want to change status?')\" href='" . base_url('auth_panel/sub_category/sub_category/update_popular_status/') . $r->id . "'>No</a>";
        }
        $nestedData[] = $r->status ? 'Disable' : 'Enable'; 
        $nestedData[] = $r->created_at ? get_date_format($r->created_at) : "--NA--";
        $nestedData[] = $r->modified_at ? get_date_format($r->modified_at) : "--NA--";

        $nestedData[] = "<div class='dropdown toggle_menus_icons'> <button class='btn dropdown_ttgl text-white dropdown-toggle ' type='button' data-toggle='dropdown'><i class='fa fa-ellipsis-v' aria-hidden='true'></i></button>
            <ul class='dropdown-menu'>               
                <li><a class='' href='" . base_url('admin-panel/edit-sub-category/') . $r->id . "'>Edit</a></li>
                <li><a class='' onclick=\"return confirm('Are you sure you want to delete?')\" href='" . base_url('auth_panel/sub_category/sub_category/delete_gener/') . $r->id . "'>Delete</a></li>
            </ul>
            </div>";



        $data[] = $nestedData;
        $ids++;
    }

    $json_data = array(
        "draw" => intval($requestData['draw']), // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
        "recordsTotal" => intval($totalData), // total number of records
        "recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
        "data" => $data, // total data array
    );

    echo json_encode($json_data); // send data as json format
}

    public function delete_gener($id){
        $this->db->where('status !=', 2);
        $this->db->where('id',$id);
        $this->db->limit(1);
        $view_data['genres'] = $this->db->get('genres')->num_rows();
        if(empty($view_data['genres'])){
            page_alert_box('error', 'Error', 'Genres Id is missing!!..');
            redirect(base_url() . 'admin-panel/add-sub-category');
        }
        $this->db->from('show_genres_relation sgr');
        $this->db->join('shows s', 's.id = ' . $id);
        $this->db->where('s.status !=', 2);
        $this->db->limit(1);
        $data  = $this->db->get()->num_rows();
        // echo $this->db->last_query();die;
        if(!empty($data)){
            page_alert_box('error',  'Please delete atteched shows first!!..');
            redirect($_SERVER['HTTP_REFERER']);
        }
        $this->db->update('genres',['status' => 2, 'modified_at' => time()], ['id' => $id]);
        backend_log_genration($this,"Category ID {$id} has been deleted by User(ID : {$this->session->userdata('active_backend_user_id')}).","Category");
        page_alert_box('success', 'Updated', 'Genres has been Deleted successfully');
        redirect(base_url() . 'admin-panel/add-sub-category');
    }

    public function update_popular_status($id){
        $this->db->where('status !=', 2);
        $this->db->where('id',$id);
        $view_data['genres'] = $this->db->get('genres')->row_array();
        if(empty($view_data['genres'])){
            page_alert_box('error', 'Error', 'Genres Id is missing!!..');
            redirect(base_url() . 'admin-panel/add-sub-category');
        }
        $popular = $view_data['genres']['is_popular'];
        if($popular == 0){
            $this->db->update('genres',['is_popular' => 1, 'modified_at' => time()], ['id' => $id]);
        }else{
            $this->db->update('genres',['is_popular' => 0, 'modified_at' => time()], ['id' => $id]);
        }
        page_alert_box('success', 'Updated', 'Genres Status has been Updated successfully');
        redirect(base_url('auth_panel/sub_category/sub_category/add_sub_category'));
    }

    public function save_position_category() {

        $ids = $_POST['ids'];
        $counter = 1;
        foreach ($ids as $id) {
            $this->db->where('id', $id);
            $array = array('position' => $counter);
            $this->db->update('sub_category', $array);
            $counter++;
        }
        echo json_encode(array('status' => true, 'message' => 'position saved'));
        die;
    }

//XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX---> CATEGORY BLOG END <---XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX


// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ Dragable~~~~~~~~~~~~~~~~~~~
    public function all_popular()
    {   
        $this->db_read->order_by("popular_order_by", "asc");
        $all = $this->db_read->select('*')->from('genres')->where('is_popular', 0)->where('genres.status = 0')->get()->result_array();
        echo json_encode($all); 
    }

    public function save_position()
    {
        $ids = $_POST['id']; 
        $counter = 1;
        foreach ($ids as $id) {
            $this->db->where('id', $id);
            $array = array('popular_order_by' => $counter);
            $this->db->update('genres', $array);
            $counter++;
        }
        backend_log_genration($this, "Generes {$this->input->post('title')} has been swap by User(ID : {$this->session->userdata('active_backend_user_id')}).", "Generes");
        echo json_encode(array('status' => true, 'message' => 'Position swap successfully'));
        die;
    }

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ End Dragable~~~~~~~~~~~~~~~~~~~


}

