<?php

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Artist extends MX_Controller
{

    protected $redis_magic;

    protected $CHANG_ACCESS_KEY;
    protected $CHANG_BUCKET_KEY;
    protected $CHANG_CLOUDFRONT;
    protected $CHANG_REGION;

    function __construct()
    {
        parent::__construct();
        /* !!!!!! Warning !!!!!!!11
         *  admin panel initialization
         *  do not over-right or remove auth_panel/auth_panel_ini/auth_ini
         */
        modules::run('auth_panel/auth_panel_ini/auth_ini');
        $this->load->library('form_validation');
        $this->load->model("artist_model");

        $this->load->helper(['aes', 'aul', 'custom', 'services']);
        $this->load->helper(['url']);
        $this->load->library('form_validation', 'uploads');
        $this->load->model("Library_model");

        $this->redis_magic = new Redis_magic("session");

        $this->retrieve_s3crendential();
        // $this->redis_magic = new Redis_magic("data");

    }


    // artist type start here


    public function add_artists_type()
    {
        if ($this->input->post()) {
            $this->form_validation->set_rules('title', 'Title', 'required|is_unique_with_status[artists_type.title]');
            $this->form_validation->set_rules('status', 'Status', 'required|in_list[0,1]');
            $category_new = $_POST['title'];
            if ($this->form_validation->run() == FALSE) {
            } else {
                $insert_data = array(
                    'title' => $this->input->post('title'),
                    'lang_id' => $this->input->post('lang_id') ?? 1,
                    'status' => $this->input->post('status') ?? 0,
                    'created_at' => time(),
                    'modified_at' => time(),
                    'created_by' => $this->session->userdata('active_backend_user_id')
                );
                $this->db->insert('artists_type', $insert_data);
                backend_log_genration($this,"Artists Type {$this->input->post('title')} has been created by User(ID : {$this->session->userdata('active_backend_user_id')}).","Artists  Type");                             
                page_alert_box('success', 'Added', 'New artist type added successfully');
                redirect($_SERVER['HTTP_REFERER']);
            }
        }
        $view_data['page'] = 'add_artists_type';
        $data['page_data'] = $this->load->view('artist/add_artists_type', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function edit_artists_type($id)
    {
        $this->db->select('*');
        $this->db->where('id', $id);
        $view_data['category'] = $this->db->get('artists_type')->row_array();
        if (empty($view_data['category'])) {
            page_alert_box('error', 'Error', 'Category Id is missing!!..');
            redirect(base_url() . 'auth_panel/Artist/artist/add_artists_type');
        }
        if ($this->input->post()) {
            $this->form_validation->set_rules('title', 'Title', 'required|edit_unique_with_status[artists_type.title.' . $id . ']');
            $this->form_validation->set_rules('status', 'Status', 'required|in_list[0,1]');
            $category_new = $_POST['title'];
            if ($this->form_validation->run() == FALSE) {
            } else {
                $update_data = array(
                    'title' => $this->input->post('title'),
                    'status' => $this->input->post('status') ?? 0,
                    'modified_at' => time(),
                    'created_by' => $this->session->userdata('active_backend_user_id')
                );
                $this->db->update('artists_type', $update_data, ['id' => $id]);
                backend_log_genration($this,"Artists Type {$this->input->post('title')} has been changed by User(ID : {$this->session->userdata('active_backend_user_id')}).","Artists Type");                                             
                page_alert_box('success', 'Updated', 'Artist type has been updated successfully');
                redirect(base_url('auth_panel/Artist/artist/add_artists_type'));
            }
        }

        $view_data['page'] = 'edit_artist_type';
        $data['page_data'] = $this->load->view('artist/edit_artists_type', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function ajax_artists_type()
    {
        $requestData = $_REQUEST;

        $columns = array(
            0 => 'id',
            1 => 'title',
            2 => 'status',
            3 => 'created_at'
        );

        $this->db->select('COUNT(id) as total');
        $this->db->from('artists_type');
        $this->db->where('status<>',2);
        $query = $this->db->get();
        $totalData = $query->row()->total;
        $totalFiltered = $totalData;

        $this->db->select('id,lang_id,title,created_at,modified_at,status');

        if (!empty($requestData['columns'][0]['search']['value'])) {
            $this->db_read->where('id', $requestData['columns'][0]['search']['value']);
        }
        if ($text = $requestData['columns'][1]['search']['value']) {
            $this->db_read->like('title', $text);
        }
        if (isset($requestData['columns'][2]['search']['value']) && $requestData['columns'][2]['search']['value'] != "") {
            $this->db_read->where('status', $requestData['columns'][2]['search']['value']);
        }
        $this->db_read->where('status !=', 2);
        $this->db_read->from('artists_type');
        
            if (isset($requestData['order'][0]['column']) && isset($requestData['order'][0]['dir'])) {
                $orderByColumn = $columns[$requestData['order'][0]['column']];
                $orderByDirection = $requestData['order'][0]['dir'];
                $this->db_read->order_by($orderByColumn, $orderByDirection);
            }
            
            $this->db_read->limit($requestData['length'], $requestData['start']);
            $result = $this->db_read->get()->result();

        $data = array();
        foreach ($result as $r) {
            $nestedData = array();
            $nestedData[] = ++$requestData['start'];
            $nestedData[] = ucfirst($r->title);
            $nestedData[] = $r->status ? "Disable" : "Enable";
            $nestedData[] = $r->created_at ? get_time_format($r->created_at) : "--NA--";
            $nestedData[] = $r->modified_at ? get_time_format($r->modified_at) : "--NA--";
            
            $nestedData[] = "<div class='dropdown toggle_menus_icons'> <button class='btn dropdown_ttgl text-white dropdown-toggle ' type='button' data-toggle='dropdown'><i class='fa fa-ellipsis-v' aria-hidden='true'></i></button>
            <ul class='dropdown-menu'>               
                <li><a title='Edit' href='" . base_url('auth_panel/Artist/artist/edit_artists_type/') . $r->id . "'>Edit</a></li>
                <li><a title='Delete' onclick=\"return confirm('Are you sure you want to delete?')\" href='" . base_url('auth_panel/Artist/artist/delete_artists_type/') . $r->id . "'>Delete</a></li>
            </ul>
            </div>";

            
            $data[] = $nestedData;

        }
        $json_data = array(
            "draw" => intval($requestData['draw']), // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
            "recordsTotal" => intval($totalData), // total number of records
            "recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
            "data" => $data, // total data array
        );
        echo json_encode($json_data);  // send data as json format
    }
    public function delete_artists_type($id)
    {
        $this->db->where('status !=', 2);
        $this->db->where('id', $id);
        $this->db->limit('1');
        $view_data['category'] = $this->db->get('artists_type')->num_rows();
        if (empty($view_data['category'])) {
            page_alert_box('error', 'Error', 'artists_type Id is missing!!..');
            redirect(base_url() . 'auth_panel/Artist/artist/add_artists_type');
        }
        $this->db->where('status !=', 2);
        $this->db->where('id', $id);
        $this->db->limit('1');
        $artists = $this->db->get('artists')->num_rows();
        if (!empty($artists)) {
            page_alert_box('error', 'Error', 'Please delete atteched artist first!!..');
            redirect(base_url() . 'auth_panel/Artist/artist/add_artists_type');
        }
        $this->db->update('artists_type', ['status' => 2, 'modified_at' => time()], ['id' => $id]);
        backend_log_genration($this,"Artists Type has been deleted by User(ID : {$this->session->userdata('active_backend_user_id')}).","Artists Type");                             
        page_alert_box('success', 'Updated', 'Category has been Deleted successfully');
        redirect(base_url() . 'auth_panel/Artist/artist/add_artists_type');
    }
    private function retrieve_s3crendential()
    {
        $s3details = json_decode(get_db_meta_key($this->db, "s3bucket_detail"), "");
        // print_r($s3details) ;die;
        if ($s3details) {
            $this->CHANG_ACCESS_KEY = $s3details->access_key;
            $this->CHANG_BUCKET_KEY = $s3details->bucket_key;
            $this->CHANG_CLOUDFRONT = $s3details->cloudfront;
            $this->CHANG_REGION = $s3details->region;
        }
    }

    public function add_artist()
    { 
        $view_data = array();      
        if ($this->input->post()) {
            $this->form_validation->set_rules("artists_type_id", "artists type id", "required|trim");
            $this->form_validation->set_rules("name", "Artist Name", "required|trim");
            $this->form_validation->set_rules("status", "status", "required|trim|in_list[0,1]"); 
            if(empty($_FILES['profile_image'])){
                $this->form_validation->set_rules("profile_image", "Profile Image", "required"); 
            }           
            if ($this->form_validation->run() !== true) {
                // $error = $this->form_validation->get_all_errors();
                // page_alert_box("error", "Add Batch User", array_values($error)[0]);
                // redirect_to_back();
            } else {
                $poster = $this->input->post('bg_img');
                if (!$poster && isset($_FILES['profile_image']) && $_FILES['profile_image']['size'] > 0) {
                    $poster = amazon_s3_upload($_FILES['profile_image'], "file_manager/videos", rand());
                    $poster = str_replace('https://' . $this->CHANG_BUCKET_KEY . ".s3." . $this->CHANG_REGION . ".amazonaws.com", $this->CHANG_CLOUDFRONT, $poster);
                }
                $insert_data = array(
                    'artists_type_id' => $this->input->post('artists_type_id'),
                    'name' => $this->input->post('name'),
                    'lang_id' => $this->input->post('lang_id') ?? 1,
                    'status' => $this->input->post('status') ?? 0,
                    'profile_image' => $poster ?? "",
                    'created_at' => time(),
                    'modified_at' => time(),
                    'created_by' => $this->session->userdata('active_backend_user_id')
                );
                $this->db->insert('artists', $insert_data);
                backend_log_genration($this,"Artist has been created by User(ID : {$this->session->userdata('active_backend_user_id')}).","Artist");                             
                page_alert_box('success', 'Added', 'Artist added successfully');
                redirect($_SERVER['HTTP_REFERER']);
            }
        }
        $view_data['page'] = "add_artist";
        $view_data['artists_types'] = $this->artist_model->get_artists_type();
        $data['page_data'] = $this->load->view('artist/add_artist', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function edit_artist($id)
    {
        $view_data['artist'] = $this->artist_model->get_artist_by_id($id);
        if (empty($view_data['artist'])) {
            page_alert_box('error', 'Error', 'artist Id is missing!!..');
            redirect(base_url() . 'admin-panel/artist-list');
        } 
        if ($this->input->post()) {
            $this->form_validation->set_rules('artists_type_id', 'artists type id', 'required');
            $this->form_validation->set_rules('name', 'Artist Name', 'required');
            $this->form_validation->set_rules("status", "status", "required|trim|in_list[0,1]"); 
            if ($this->form_validation->run() == FALSE) {
                echo "sdsd";
                die;
            } else {
                $update_data = array(
                    'artists_type_id' => $this->input->post('artists_type_id'),
                    'name' => $this->input->post('name'),
                    'lang_id' => $this->input->post('lang_id') ?? 0,
                    'status' => $this->input->post('status') ?? 0,
                    'modified_at' => time()
                );
                if (!empty($_FILES['profile_image']['name'])) {
                    $image_url = amazon_s3_upload($_FILES['profile_image'], 'banner', $result);
                    $image_url = str_replace('https://' . $this->CHANG_BUCKET_KEY . ".s3." . $this->CHANG_REGION . ".amazonaws.com", $this->CHANG_CLOUDFRONT, $image_url);
                    $update_data['profile_image'] =   $image_url;
                } else {
                    $update_data['profile_image'] =  $view_data['artist']['profile_image'];
                }
                $this->db->update('artists', $update_data, ['id' => $id]); 
                backend_log_genration($this,"Artist has been changed by User(ID : {$this->session->userdata('active_backend_user_id')}).","Artist");                             
                page_alert_box('success', 'Artist Added', 'Artist has been updated successfully');
                redirect('admin-panel/artist-list');
            }
        }
        $view_data['page'] = "edit_artist";
        $view_data['artist'] = $this->artist_model->get_artist_by_id($id);
        $view_data['artists_types'] = $this->artist_model->get_artists_type();
        $data['page_data'] = $this->load->view('artist/edit_artist', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function artist_list()
    {
        $data['page_title'] = "Artist's List";
        $view_data['page'] = "artist_list";
        $view_data['videos'] = $this->artist_model->get_artist_list();
        $data['page_data'] = $this->load->view('artist/artist_list', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function ajax_all_artist_list()
    {
        $requestData = $_REQUEST;

        $columns = array(
            0 => 'id',
            1 => 'name',
            2 => 'description',
            3 => 'profile_image',
            4 => 'creation_time',
            5 => 'status',
        );

        $this->db_read->select('COUNT(id) as total');
        $this->db_read->where('status !=', 2);
        $this->db_read->from('artists');
        $query = $this->db_read->get();
        $totalData = $query->row()->total;

        $totalFiltered = $totalData;

        $this->db_read->select('a.id, at.title, name, profile_image, a.status, a.created_at');

        if ($text = $requestData['columns'][0]['search']['value']) {
            $this->db_read->like('at.title', $text);
        }
        if ($text = $requestData['columns'][1]['search']['value']) {
            $this->db_read->like('a.name', $text);
        }
        
        if(isset($requestData['start'])){
            $this->db_read->order_by($columns[$requestData['order'][0]['column']], 'DESC');
            $this->db_read->limit($requestData['length'], $requestData['start']);
             } 

        $this->db_read->from('artists a');
        $this->db_read->join('artists_type at', 'at.id = a.artists_type_id and at.status != 2');
        $this->db_read->where('a.status !=', 2);
        $query = $this->db_read->get();
        $result = $query->result();

        $data = array();      
        foreach ($result as $r) {  // preparing an array
            $nestedData = array();
            $nestedData[] = ++$requestData['start'];
            $nestedData[] = $r->title;
            $nestedData[] = $r->name;
            $nestedData[] = "<img width='100px' height='100px' alt='profile' src='" . $r->profile_image . "'>";
            $nestedData[] = ($r->status == 0) ? 'Active' : 'Disabled';
            $nestedData[] = $r->created_at ? get_time_format($r->created_at) : "--NA--";
            // $nestedData[] = $r->created_at;
            $nestedData[] = "<div class='dropdown toggle_menus_icons'> <button class='btn dropdown_ttgl text-white dropdown-toggle ' type='button' data-toggle='dropdown'><i class='fa fa-ellipsis-v' aria-hidden='true'></i></button>
            <ul class='dropdown-menu'>
           <li><a class='' href='" . base_url('admin-panel/edit-artist/') . $r->id . "'><i class='fa fa-pencil'></i> Edit</a></li>
           <li><a  class='' onclick=\"return confirm('Are you sure you want to delete?')\" href='" . AUTH_PANEL_URL . "Artist/artist/delete_artist/" . $r->id . "'><i class='fa fa-trash'></i> Delete</a></li>
            </ul>
            </div>";
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


    public function delete_artist($id)
    {
        $delete_user = $this->artist_model->delete_artist($id);
        if ($this->db->affected_rows() > 0) {
            update_api_version_new($this->db, 'menu_master');
        }
        page_alert_box('success', 'Artist deleted', 'Artist has been deleted successfully');
        backend_log_genration($this,"Artist ID {$id} has been deleted by User(ID : {$this->session->userdata('active_backend_user_id')}).","Artist");                
        redirect(AUTH_PANEL_URL . 'Artist/artist/artist_list');        
    }
}
