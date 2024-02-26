<?php

use Aws\S3\S3Client;
defined('BASEPATH') OR exit('No direct script access allowed');

class ContentManagementController extends MX_Controller {

    function __construct() {
        parent::__construct();
        /* !!!!!! Warning !!!!!!!11
         *  admin panel initialization
         *  do not over-right or remove auth_panel/auth_panel_ini/auth_ini
         */
        modules::run('auth_panel/auth_panel_ini/auth_ini');
        $this->load->library('form_validation');
        $this->load->library('aws_s3_file_upload');
        $this->load->helper('services');
        $this->load->model("ShowsModel");        
        $this->load->model("SeasonModel");        
        $this->load->helper('cookie');
    }

    public function amazon_s3_upload($name, $aws_path) {
        $_FILES['file'] = $name;
        require_once FCPATH . 'aws/aws-autoloader.php';      

        $s3Client = new S3Client([
            'version' => 'latest',
            'region' => AMS_REGION,
            'credentials' => [
                'key' => AMS_S3_KEY,
                'secret' => AMS_SECRET,
            ],
        ]);
        $result = $s3Client->putObject(array(
            'Bucket' => AMS_BUCKET_NAME,
            'Key' => $aws_path . '/' . rand(0, 7896756) . str_replace([':', ' ', '/', '*', '#', '@', '%'], "_", $_FILES["file"]["name"]),
            'SourceFile' => $_FILES["file"]["tmp_name"],
            'ContentType' => $_FILES["file"]["type"],
            'ACL' => 'public-read',
            'StorageClass' => 'REDUCED_REDUNDANCY',
            'Metadata' => array('param1' => 'value 1', 'param2' => 'value 2'),
        ));
        $data = $result->toArray();
        return $data['ObjectURL'];
    }
  
    // public function getSeasonDetails($show_id, $id = null){
    //     header('Content-Type: application/json; charset=utf-8');
    //     $data['season'] = $this->db->db->get_where('seasons', ['show_id' => $show_id, 'id' => $id])->row_array();
    //     if(empty($data['season'])){
    //         echo json_encode(['status' => false, 'message' => 'Something went wrong!!..']);   
    //     }
    //     $data['page_data'] = $this->load->view('season/add_content', $view_data, TRUE);
    // }


    public function add_content($id = '', $video_id = ''){

        // pre($_POST); die;

        $view_data['video_id'] = $video_id;
        if(!empty($video_id)){
            $view_data['edit_video'] = $this->db->get_where('media_library', ['id' => $video_id, 'show_id' => $id, 'status !=' => 2])->row_array();
            // pre($view_data['edit_video']);die;
            if(empty($view_data['edit_video'])) {
                page_alert_box('error', 'Error', 'Video Id is missing!!..');
                $season_id =  "";
                if($this->input->get('season_id')){
                    $season_id = "?season_id=" . $season_id;
                }
                redirect(base_url() . 'admin-panel/add-content/' . $id . $season_id);
            }
            if($this->input->get('season_id')){
                $season_id = $this->input->get('season_id');
                $view_data['specific_season'] = $this->db->get_where('seasons', ['show_id' => $id, 'id' => $season_id, 'status !=' => 2])->row_array();
                if(empty($view_data['specific_season'])){
                    page_alert_box('error', 'Error', 'Seasion Id is missing!!..');
                    redirect(base_url() . 'admin-panel/add-content/' . $id);
                }
            }
            
        }
        if(!empty($id)){
            $view_data['id'] = $id;
            $view_data['shows'] = $this->ShowsModel->get_by_id($id);
            // pre($view_data['shows']);die;
            if(empty($view_data['shows'])){
                page_alert_box('error', 'Error', 'Shows Id is missing!!..');
                redirect(base_url() . 'admin-panel/add-content');
            }
            if(!empty($view_data['shows']['skip_season'])){
                if(!$this->input->get('season_id')){
                    $seasons_data = $this->db->get_where('seasons', ['show_id' => $id, 'status !=' => 2])->row_array();
                    if(!empty($seasons_data)){
                        redirect(base_url() . 'admin-panel/add-content/' . $id ."?season_id=" . $seasons_data['id']);
                    }
                }    
            }
            if($this->input->get('season_id')){
                $season_id = $this->input->get('season_id');
                $view_data['specific_season'] = $this->db->get_where('seasons', ['show_id' => $id, 'id' => $season_id, 'status !=' => 2])->row_array();
                if(empty($view_data['specific_season'])){
                    page_alert_box('error', 'Error', 'Seasion Id is missing!!..');
                    redirect(base_url() . 'admin-panel/add-content/' . $id);
                }
                $view_data['showaActors'] = $this->getshowaActors($id, $season_id);
                if(!empty($view_data['showaActors'])){
                    // pre($view_data['showaActors']);die;
                    $this->db->select("artists_type_id,artists_name, GROUP_CONCAT(artists_name) as artists_name, GROUP_CONCAT(artists_id) as artists_id");
                    $this->db->group_by('artists_type_id');
                    $view_data['show_artists_relation'] = $this->db->get_where('show_artists_relation', ['show_id' => $id, 'season_id' => $season_id])->result_array();
                    // pre($show_artists_relation);die;
                }
            }
            $this->db->select('id, channel_name');
            $view_data['channels'] = $this->db->get_where('aws_channel', ['status' => 0])->result_array();
        }   

        // if(empty($id)){         
        if ($this->input->post()) {
            
            $this->form_validation->set_rules('type', 'Media Type', 'required|in_list[0,1]');        
            $this->form_validation->set_rules('category_id', 'Category', 'required');        
            $this->form_validation->set_rules('aggregator_id', 'Aggregator ', 'required');        
            $this->form_validation->set_rules('title', 'Title', 'required');        
            $this->form_validation->set_rules('released_on', 'Released On', 'required');        
            $this->form_validation->set_rules('genres_id[]', 'Genres', 'required');        
            $this->form_validation->set_rules('video_time', 'Video Time', 'required');        
            $this->form_validation->set_rules('description', 'Description', 'required'); 
            if(!empty($this->input->post('skip_season'))){
                $this->form_validation->set_rules('skip_season', 'Skip Season', 'required|in_list[1]'); 
            } 
            if(!empty($this->input->post('still_live'))){
                $this->form_validation->set_rules('still_live', 'Still Live', 'required|in_list[1]'); 
            }       
            if(empty($id)){
                if (empty($_FILES['image']['name'])) {
                    $this->form_validation->set_rules('image', 'Image', 'required'); 
                }
                if (empty($_FILES['poster_url']['name'])) {
                    $this->form_validation->set_rules('poster_url', 'Poster Url', 'required'); 
                }
                if (empty($_FILES['banner_icon']['name'])) {
                    $this->form_validation->set_rules('banner_icon', 'Banner Icon', 'required'); 
                }
                if (empty($_FILES['detail_banner']['name'])) {
                    $this->form_validation->set_rules('detail_banner', 'Detail Banner', 'required'); 
                }
            }
            if ($this->form_validation->run() == TRUE) {                  
                if (!empty($_FILES['image']['name'])) {
                    $image = $this->amazon_s3_upload($_FILES['image'], "shows/thumbnail");
                } else {
                    $image = '';
                }
                if (!empty($_FILES['poster_url']['name'])) {
                    $poster_url = $this->amazon_s3_upload($_FILES['poster_url'], "shows/poster_url");
                } else {
                    $poster_url = '';
                }
                if (!empty($_FILES['banner_icon']['name'])) {
                    $banner_icon = $this->amazon_s3_upload($_FILES['banner_icon'], "shows/banner_icon");
                } else {
                    $banner_icon = '';
                }
                if (!empty($_FILES['detail_banner']['name'])) {
                    $detail_banner = $this->amazon_s3_upload($_FILES['detail_banner'], "shows/detail_banner");
                } else {
                    $detail_banner = '';
                }
                if(!empty($id) & !empty($this->input->post('skip_season'))) {
                    $arr_season = array('status !=' => 2, 'show_id' => $id);
                    $this->db->limit(2);
                    $seasons_data_chk = $this->db->get_where('seasons', $arr_season)->num_rows();
                    if($seasons_data_chk == 2){
                        page_alert_box('error', 'Error', 'You can not update skip season please add only one season!!..');
                        redirect(base_url('admin-panel/add-content/'.$id));
                    }
                } 	               
                if($view_data['shows']['type'] != $this->input->post("type") && !empty($id)){
                    $check = ($this->input->post("type") == 0) ? 1 : 0;
                    $this->db->limit('1'); 
                    $media_data = $this->db->get_where('media_library', ['status !=' => 2, 'media_type' => $check, 'show_id' => $id])->num_rows();
                    // echo $this->db->last_query();die;
                    if(!empty($media_data)){
                        page_alert_box('error', 'Error', 'Please delete your attached then update your media type!!..');
                        redirect($_SERVER['HTTP_REFERER']);
                    }
                }
                $insert_data = array(
                    'category_id' => $this->input->post('category_id'),                        
                    'aggregator_id' => $this->input->post('aggregator_id'),                        
                    'title' => $this->input->post('title'),                        
                    'description' => $this->input->post('description'),                        
                    'thumbnail' => $image,
                    'poster_url' => $poster_url,
                    'banner_icon' => $banner_icon,
                    'detail_banner' => $detail_banner,
                    'video_time' => $this->input->post('video_time') ?? 0,
                    'still_live' => $this->input->post('still_live') ?? 0,
                    'released_on' => $this->input->post('released_on') ?? 0,
                    'skip_season' => $this->input->post('skip_season') ?? 0,
                    'type' => $this->input->post('type') ?? 0,
                    'status' => 0,
                    'created_at'=> time(),
                    'modified_at'=> time(),
                    'created_by'=> $this->session->userdata('active_backend_user_id')
                );

                  

                if(!empty($id)){
                    $insert_data['shows_id'] = $id;
                    
                    if($image == ''){
                        $insert_data['thumbnail'] = $view_data['shows']['thumbnail'];
                    }
                    if($poster_url == ''){
                        $insert_data['poster_url'] = $view_data['shows']['poster_url'];
                    }
                    if($banner_icon == ''){
                        $insert_data['banner_icon'] = $view_data['shows']['banner_icon'];
                    }
                    if($detail_banner == ''){
                        $insert_data['detail_banner'] = $view_data['shows']['detail_banner'];
                    }
                    if(!empty($this->input->post('skip_season'))){
                        $seasons_data = $this->db->get_where('seasons', ['show_id' => $id, 'status !=' => 2])->num_rows();
                        if(empty($seasons_data)) {
                            $arr_season = array('title' => 'Season 1', 'status' => 0, 'show_id' => $id);
                            $this->db->insert('seasons', $arr_season);
                            $season_insert_id = $this->db->insert_id();
                        }
                    }
                    $this->ShowsModel->insert($insert_data);                           
                }else{
                    $id = $this->ShowsModel->insert($insert_data);
                    if($id && !empty($this->input->post('skip_season'))) {
                        $arr_season = array('title' => 'Season 1', 'status' => 0, 'show_id' => $id);
                        $this->db->insert('seasons', $arr_season);
                        $season_insert_id = $this->db->insert_id();
                    }                        
                }
                if($id){
                    $genres_ids = $this->input->post('genres_id');
                    if(!empty($genres_ids)){
                        $this->db->where('show_id', $id);
                        $this->db->where_not_in(['genres_id' => $genres_ids]);
                        $data = $this->db->delete('show_genres_relation');
                        foreach($genres_ids as $genres_id){
                            $arr = ['genres_id' => $genres_id, 'show_id' => $id];
                            $count = $this->db->get_where('show_genres_relation', $arr)->num_rows();
                            if(empty($count)){
                                $this->db->insert('show_genres_relation', $arr);
                            }
                        }
                    }
                }       
                if(!empty($insert_data['shows_id'])){
                    page_alert_box('success', 'Added', 'Shows Updated successfully');
                }else{
                    page_alert_box('success', 'Added', 'New Shows added successfully');

                }
                $query_string = "";
                if(!empty($season_insert_id)){
                    $query_string = "?season_id=" . $season_insert_id;
                }
                redirect(base_url('admin-panel/add-content/'.$id .$query_string));                                        
            }        
        }
        // }
        else{
            $view_data['seasons'] = $this->SeasonModel->get_season_by_show_id($id);        
            // print_r($view_data['seasons']); die;
        
        }

        $view_data['categories'] = $this->db->where('status', 0)->get('categories')->result_array();
        $view_data['aggregators'] = $this->db->where('status', 0)->get('aggregator')->result_array();
        // pre($view_data['shows']);die;
        if(!empty($view_data['shows']['category_id'])){
            $view_data['genres'] =  $this->getGenres($view_data['shows']['category_id'], true);
        }
        // $view_data['genres'] = $this->db->where('status', 0)->get('genres')->result_array();        
        $view_data['artists_types'] = $this->db->where('status', 0)->get('artists_type')->result_array();        
        $view_data['artists'] = $this->db->where('status', 0)->get('artists')->result_array();
        // if($this->input->get('season_id') == 68){
            // pre($view_data['artists']);die;
        // }
        $view_data['page'] = 'add_content';        
        $data['page_data'] = $this->load->view('season/add_content', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function delete_show_artist($id){
        $this->db->delete('show_artists_relation', ['id' => $id]);
        if($this->db->affected_rows()){
            echo json_encode(['status' => true, 'message' => 'artist relation deleted successfully!!.']);die;
        }
        echo json_encode(['status' => false, 'message' => 'Something went wrong!!.']);die;
    }

    public function addArtistWithRelation($id){
        $input = json_decode(file_get_contents('php://input'), true);
        $artists_type_id = [];
        $arr = [];
        $where_data = $where = "";
        foreach($input['json_data'] as $key => $json_data){
            if(!empty($json_data)){
                $where_data .= " when artists_type_id = ". $key ." THEN artists_id NOT IN ( ". implode(',', $json_data).") ";
            }
        }
        if(!empty($where_data)){
            $where .= " And CASE ". $where_data ." ELSE 1 = 1
    END";
        }
        
        if(!empty($input['data'])){
            $datas = $input['data'];
            foreach($datas as $data){
                $arr = array(
                    'artists_type_id' => $data['artist_type']['artist_type_id'],
                    'artists_type_name' => $data['artist_type']['artist_type_name'],
                    'season_id' => $input['season_id'] ?? $this->input->get('season_id'),
                    'show_id' => $id,
                    'created_at' => time(),
                    'modified_at'=> time()
                );
                foreach($data['artists'] as $d){
                    $arr['artists_id'] = $d['artist_id'];
                    $arr['artists_name'] = $d['artist_name'];
                    $this->db->limit(1);
                    $show_artists_relation = $this->db->get_where('show_artists_relation', ['show_id' => $id, 'season_id' => $arr['season_id'], 'artists_id' => $arr['artists_id'], 'artists_type_id' => $arr['artists_type_id']])->num_rows();
                    if(empty($show_artists_relation)){
                        $this->db->insert('show_artists_relation', $arr);
                    }
                }
            }
            $show_artists_relation = $this->db->query("Delete FROM show_artists_relation where show_id = ". $id ." and season_id = " . $arr['season_id'] . " $where ");
            $return = $this->getshowaActors($id, $input['season_id'] ?? $this->input->get('season_id'));
            echo json_encode($return);
        }
    }
    public function getshowaActors($show_id, $season_id){
        // echo $season_id;die;
        return $this->db->get_where('show_artists_relation', ['show_id' => $show_id, 'season_id' => $season_id])->result_array();
    }

    public function getGenres($category_id, $id = null){
        $this->db->select('g.id, g.title');
        $this->db->from('gener_catgegory_relation gcr');
        $this->db->join('genres g', 'g.id = gcr.genres_id');
        $this->db->where('g.status', 0);
        $this->db->where('gcr.category_id', $category_id);
        $this->db->group_by('g.id');
        $data = $this->db->get()->result_array();
        if(!empty($id)){
            return $data;
        }
        // echo $this->db->last_query();die;
        echo json_encode($data);die;
    }

    public function getArtists($artists_type_id){
        $this->db->select('id, name');
        $this->db->from('artists');
        $this->db->where('status', 0);
        $this->db->where('artists_type_id', $artists_type_id);
        $data = $this->db->get()->result_array();
        echo json_encode($data);die;
    }


    public function add_season($id) {
        $view_data['shows'] = $this->ShowsModel->get_by_id($id);
        if(empty($view_data['shows'])){
            page_alert_box('error', 'Error', 'Shows Id is missing!!..');
            redirect(base_url() . 'admin-panel/add-content');
        }
        if($this->input->get('season_id')){
            $season_id = $this->input->get('season_id');
            $view_data['specific_season'] = $this->db->get_where('seasons', ['show_id' => $id, 'id' => $season_id])->row_array();
            if(empty($view_data['specific_season'])){
                page_alert_box('error', 'Error', 'Seasion Id is missing!!..');
                redirect(base_url() . 'admin-panel/add-content/' . $id);
            }
        }
        $this->form_validation->set_rules('title', 'Title', 'required');
        $this->form_validation->set_rules('status', 'Status', 'required|in_list[0,1]');
        if (empty($_FILES['thumbnail_season']['name']) && empty($id)) {
            $this->form_validation->set_rules('thumbnail_season', 'Thumbnail Season', 'required');
        }
        $category_new = $this->input->post('title');
        if ($this->form_validation->run() == TRUE) {                           
            if (!empty($_FILES['thumbnail_season']['name'])) {
                $image = $this->amazon_s3_upload($_FILES['thumbnail_season'], "season/thumbnail");
            } else {
                 $image = '';
             }    	   
            $insert_data = array(
                'show_id' => $id,                        
                'title' => $this->input->post('title'),                        
                'thumbnail' => $image,
                'status' => 0,
                'created_at'=> strtotime("now"),
                'modified_at'=> strtotime("now"),
                'created_by'=> $this->session->userdata('active_backend_user_id')
            );
            if(!empty($view_data['specific_season']['thumbnail']) && $image == ""){
                $insert_data['thumbnail'] = $view_data['specific_season']['thumbnail'];
            }
            $res= $this->SeasonModel->insert_season($insert_data);                                     
            if($res){
                if($this->input->get('season_id')){
                    $season_id = $this->input->get('season_id');
                    page_alert_box('success', 'Added', 'Season Updated successfully');
                }else{
                    $season_id = $res;
                    page_alert_box('success', 'Added', 'New Season added successfully');
                }
                redirect(base_url('admin-panel/add-content/'.$id . "?season_id=" . $season_id));
            }
            else{
                page_alert_box('error', 'Warning', 'Something went wrong');
                redirect($_SERVER['HTTP_REFERER']);
            }
            
        }
        page_alert_box('error', 'Validation Errror', 'Something went wrong');
        redirect($_SERVER['HTTP_REFERER']);     
    }




    public function edit_content($id){
        
        if(empty($id)){
            if ($this->input->post()) {
                $this->form_validation->set_rules('title', 'Title', 'required');
            //    $category_new = $this->input->post('title');                  
            if ($this->form_validation->run() == TRUE) {                           
                if (!empty($_FILES['image']['name'])) {
                    $image = $this->amazon_s3_upload($_FILES['image'], "shows/thumbnail");
                    } else {
                        $image = '';
                    } 	               

                $insert_data = array(
                    'category_id' => $this->input->post('category_id'),                        
                    'title' => $this->input->post('title'),                        
                    'thumbnail' => $image,
                    'status' => 0,
                    'created_at'=> strtotime("now"),
                    'modified_at'=> strtotime("now"),
                    'created_by'=> $this->session->userdata('active_backend_user_id')
                );
                
                $id = $this->ShowsModel->insert($insert_data);                                     
                page_alert_box('success', 'Added', 'New Shows added successfully');
                
                redirect(base_url('admin-panel/add-content/'.$id));        
                redirect($_SERVER['HTTP_REFERER']);
                
            }
            }
        }else{
            $view_data['id'] = $id;        
        }

        $view_data['categories'] = $this->db->where('status', 0)->get('categories')->result_array();
        $view_data['page'] = 'add_content';        
        
        $data['page_data'] = $this->load->view('season/add_content', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function add() {
        if ($this->input->post()) {
             $this->form_validation->set_rules('title', 'Title', 'required');
            $category_new = $this->input->post('title');
            // $appid = ((defined("APP_ID") && APP_ID)? "" . APP_ID . "" : "0");
            $sql = "select title from seasons where title = '$category_new'";
                $query = $this->db->query($sql);
                $checkrows=$query->num_rows();
               // echo $this->db->last_query($sql);die;
            if ($this->form_validation->run() == TRUE) {                           
                if (!empty($_FILES['image']['name'])) {
                    $image = $this->amazon_s3_upload($_FILES['image'], "shows/thumbnail");
                 } else {
                     $image = '';
                 } 	               

                $insert_data = array(
                    'category_id' => $this->input->post('category_id'),                        
                    'title' => $this->input->post('title'),                        
                    'thumbnail' => $image,
                    'status' => 0,
                    'created_at'=> strtotime("now"),
                    'modified_at'=> strtotime("now"),
                    'created_by'=> $this->session->userdata('active_backend_user_id')
                );
                
                $id = $this->ShowsModel->insert($insert_data);                                     
                page_alert_box('success', 'Added', 'New Shows added successfully');
                redirect($_SERVER['HTTP_REFERER']);
                
            }
        }
        // app_permission("app_id",$this->db);
        // $f_list = $this->db->get("application_meta")->result_array();
        $view_data['categories'] = $this->db->where('status', 0)->get('categories')->result_array();
        $view_data['page'] = 'add_season';
        $data['page_data'] = $this->load->view('shows/add', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function ajax_list() {
        $output_csv = $output_pdf = false;
        $requestData = $_REQUEST;
          
        $columns = array(
            // datatable column index  => database column name
            0 => 'id',
            1 => 'category_id',                        
            1 => 'title',                        
            2 => 'thumbnail',
            3 => 'modified_at',
            4 => 'status',
        );

        $this->db_read->select('count(id) as total');
        $this->db->from('shows');
        $this->db->where('status !=', 2);
        $query = $this->db->get();
        $result = $query->row_array();
        $totalData = (count($result) > 0) ? $result['total'] : 0;

        $totalFiltered = $totalData;
        
        if ($text = $requestData['columns'][0]['search']['value']) {
            $this->db_read->where('id',$text);
        }

        if ($title = $requestData['columns'][1]['search']['value']) {
            $this->db_read->like('title',$title);
        }

        if(isset($requestData['start'])){
            $this->db_read->order_by($columns[$requestData['order'][0]['column']], 'DESC');
            $this->db_read->limit($requestData['length'], $requestData['start']);
        } 

        $this->db->from('shows');
        $this->db->join('categories', 'shows.category_id = categories.id', 'inner');
        $this->db->where_in('shows.status', array(0, 1));
        $query = $this->db->get();
        $result = $query->result();
                 
        $data = array();
        $id = 0;
        foreach ($result as $r) {
            $nestedData = array();
            $nestedData[] = ++$id;
            $nestedData[] = $r->cat_name;
            $nestedData[] = $r->title;
            $nestedData[] = "<img width='200px' height='80px' src='".$r->thumbnail."'></a>"; 
            $nestedData[] = $r->created_at ? get_date_format($r->created_at) : "--NA--";
            $nestedData[] = ($r->status == 0 ) ? 'Enabled' : 'Disabled';                
            $nestedData[] = "<a class='btn-xs bold btn btn-primary' title='Edit' onclick=\"return confirm('Are you sure you want to Edit?')\" href='" . AUTH_PANEL_URL . "contentManagement/ShowsController/edit/" . $r->id . "'><i class='fa fa-edit'></i></a>&nbsp;
				<a class='btn-xs bold btn btn-danger' title='Delete' onclick=\"return confirm('Are you sure you want to delete?')\" href='" . AUTH_PANEL_URL . "contentManagement/ShowsController/delete/" . $r->id . "'><i class='fa fa-trash-o'></i></a>&nbsp;
                <a class='btn-xs bold btn btn-warning' title='Enabled/Disabled' href='" . AUTH_PANEL_URL . "season/seasonController/update_season_status/" . $r->id ."/".$r->status."'><i class='fa fa-ban' aria-hidden='true'></i></a>&nbsp;
			";
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
    
    public function ajax_season_list($id) {
        $output_csv = $output_pdf = false;
        $requestData = $_REQUEST;
          
        $columns = array(
            // datatable column index  => database column name
            0 => 'id',
            1 => 'title',                        
            2 => 'created'
        );
        $where = " and show_id = $id and season_id = " . $_GET['season_id'];
        $query = "SELECT count(id) as total
                  FROM media_library where status= 0  $where";        
        $query = $this->db_read->query($query);
        $query = $query->row_array();
        $totalData = (count($query) > 0) ? $query['total'] : 0;
        $totalFiltered = $totalData;
        $sql = "SELECT *
                FROM media_library where status in (0,1) $where";       
        if (!empty($requestData['columns'][0]['search']['value'])) {
            $sql .= " AND id LIKE '" . $requestData['columns'][0]['search']['value'] . "%' ";
        }

        if (!empty($requestData['columns'][1]['search']['value'])) {
            $sql .= " AND title LIKE '" . $requestData['columns'][1]['search']['value'] . "%' ";
        }

        $query = $this->db_read->query($sql)->result();

        if(isset($requestData['start'])){
            $sql .= " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
        } // adding length
       
        $result = $this->db_read->query($sql)->result();
       // echo $this->db_read->last_query();die;
        $data = array();
        $id = 0;
        foreach ($result as $r) {
            $nestedData = array();
            $nestedData[] = ++$id;
            $nestedData[] = $r->title; 
            $nestedData[] = ($r->status == 0 ) ? 'Enabled' : 'Disabled';                
            $nestedData[] = $r->created ? get_date_format($r->created) : "--NA--";
            // $nestedData[] = "";     
            if($r->status == 0){
                $status = "Disabled";
            }else{
                $status = "Enabled";
            }
            $nestedData[] = "<div class='dropdown toggle_menus_icons'> <button class='btn dropdown_ttgl text-white dropdown-toggle ' type='button' data-toggle='dropdown'><i class='fa fa-ellipsis-v' aria-hidden='true'></i></button>
            <ul class='dropdown-menu'>               
                <li><a  title='Edit' href='" . site_url() . "admin-panel/add-content/" . $r->show_id . "/" . $r->id . "?season_id=" . $_GET['season_id'] ."'>Edit</a>
                </li>
                <li><a   onclick=\"return confirm('Are you sure you want to delete?')\" title='Delete' href='" . site_url() . "auth_panel/contentManagement/ContentManagementController/delete_video_status/" . $r->show_id . "/" . $r->id . "?season_id=" . $_GET['season_id'] ."'>Delete</a>
                </li>
                <li><a onclick=\"return confirm('Are you sure you want to update the status?')\" title='Enabled/Disabled' href='" . site_url() . "auth_panel/contentManagement/ContentManagementController/update_video_status/" . $r->show_id . "/" . $r->id . "?season_id=" . $_GET['season_id'] ."'>$status</a>
                </li>
                <li><a data-id=" . $r->id ." data-title='" . $r->title ."' class='video_search_tail' title='Preview' href='javascript:;'>Preview</a></li>
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
        echo json_encode($json_data); // send data as json format
    }

    private function checkVideo(){
        if(!empty($video_id)){
            $view_data['edit_video'] = $this->db->get_where('media_library', ['id' => $video_id, 'show_id' => $id, 'status !=' => 2])->row_array();
            if(empty($view_data['edit_video'])) {
                page_alert_box('error', 'Error', 'Video Id is missing!!..');
                $season_id =  "";
                if($this->input->get('season_id')){
                    $season_id = "?season_id=" . $season_id;
                }
                redirect(base_url() . 'admin-panel/add-content/' . $id . $season_id);
            }
            if($this->input->get('season_id')){
                $season_id = $this->input->get('season_id');
                $view_data['specific_season'] = $this->db->get_where('seasons', ['show_id' => $id, 'id' => $season_id])->row_array();
                if(empty($view_data['specific_season'])){
                    page_alert_box('error', 'Error', 'Seasion Id is missing!!..');
                    redirect(base_url() . 'admin-panel/add-content/' . $id);
                }
            }
        }
        return $view_data;
    }
    public function delete_season($id){
        if($this->input->get('season_id')){
            $season_id = $this->input->get('season_id');
            $view_data['specific_season'] = $this->db->get_where('seasons', ['show_id' => $id, 'id' => $season_id, 'status !=' => 2])->row_array();
            if(empty($view_data['specific_season'])){
                page_alert_box('error', 'Error', 'Seasion Id is missing!!..');
                redirect(base_url() . 'admin-panel/add-content/' . $id);
            }
            $this->db->limit(1);
            $media_library = $this->db->get_where('media_library', ['show_id' => $id, 'season_id' => $season_id, 'status !=' => 2])->num_rows();
            if(!empty($media_library)){
                page_alert_box('error', 'Error', 'You can not delete the season please delete atteched video first!!..');
                redirect($_SERVER['HTTP_REFERER']);
            }
            $this->db->update('seasons',['status' => 2, 'modified_at' => time()], ['id' => $view_data['specific_season']['id']]);
            page_alert_box('success', 'Updated', 'Season has been Delete successfully');
        }
         redirect(base_url() . 'admin-panel/add-content/' . $id);
    }
    public function delete_video_status($id, $video_id){
        $view_data['edit_video'] = $this->checkVideo($id, $video_id);
        $popular = $view_data['edit_video']['status'];
        $this->db->update('media_library',['status' => 2, 'modified_at' => time()], ['id' => $video_id]);
        page_alert_box('success', 'Updated', 'Video has been Delete successfully');
        redirect($_SERVER['HTTP_REFERER']);
    }
    public function update_video_status($id, $video_id){
        $view_data['edit_video'] = $this->checkVideo($id, $video_id);
        $popular = $view_data['edit_video']['status'];
        if($popular == 0){
            $this->db->update('media_library',['status' => 1, 'modified_at' => time()], ['id' => $video_id]);
        }else{
            $this->db->update('media_library',['status' => 0, 'modified_at' => time()], ['id' => $video_id]);
        }
        page_alert_box('success', 'Updated', 'Video Status has been Updated successfully');
        redirect($_SERVER['HTTP_REFERER']);
    }
    public function delete($id) {
        $delete_user = $this->ShowsModel->delete($id);
        page_alert_box('success', 'Season Deleted', 'Season has been deleted successfully');
        redirect(base_url('admin-panel/add-shows'));        
    }

    public function edit($id) {
        if ($this->input->post()) {
            $this->form_validation->set_rules('title', 'Title', 'required');
           $category_new = $this->input->post('title');           
            $sql = "select title from seasons where title = '$category_new'";
            $query = $this->db->query($sql);
            $checkrows=$query->num_rows();  

           if ($this->form_validation->run() == TRUE) {                           
                if (!empty($_FILES['image']['name'])) {
                    $image = $this->amazon_s3_upload($_FILES['image'], "season/thumbnail");
                }else{
                    $image = '';
                }	               
           
                    if($image){
                        $update_data = array(
                            'title' => $this->input->post('title'),                        
                            'thumbnail' => $image,
                            'status' => 0,
                            'created_at'=> strtotime("now"),
                            'modified_at'=> strtotime("now"),
                            'created_by'=> $this->session->userdata('active_backend_user_id')
                        );                   
                    }
                    else{
                        $update_data = array(
                            'title' => $this->input->post('title'),                                                
                            'status' => 0,
                            'created_at'=> strtotime("now"),
                            'modified_at'=> strtotime("now"),
                            'created_by'=> $this->session->userdata('active_backend_user_id')
                        );   
                    }                                         
                    $res = $this->ShowsModel->update($update_data,$id);                                         
                    page_alert_box('success', 'Updated', 'Updated Season Successfully');
                    redirect($_SERVER['HTTP_REFERER']);               
            }
       }    
        $view_data['categories'] = $this->db->where('status', 0)->get('categories')->result_array();
        $view_data['shows'] = $this->ShowsModel->get_by_id($id);        
        $view_data['id'] = $id;
        $view_data['page'] = 'edit_season';        
        $data['page_data'] = $this->load->view('shows/edit', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function update_status($id,$staus) {
        $delete_user = $this->ShowsModel->update_season_status($id,$staus);
        page_alert_box('success', 'Season Deleted', 'Season has been deleted successfully');

        redirect(base_url('admin-panel/add-shows'));        
    }





// //XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX---> CATEGORY BLOG START <---XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
//     public function map_category() {
      
//         if ($this->input->post()) {//echo '<pre>'; print_r($_POST); die;
//             $this->form_validation->set_rules('cat_name', 'Category Name', 'required');
//             if ($this->form_validation->run() == FALSE) {
//             } else {
//                 $input = $this->input->post();
//                 $is_gners_already_exists = $this->Category_model->is_generse_exists($input);
                
//                   if ($is_gners_already_exists == true) {
//                         page_alert_box("error", "Category Name", "This Category Name is already exist.", "");
//                         redirect(AUTH_PANEL_URL . '/category/category/map_category');
//                     }
//                    // print_r($is_gners_already_exists);die;
//             if (!$is_gners_already_exists) {
//                  if ($this->input->post('related_genres') != '') {
//                     $related_genre = implode(",", $this->input->post('related_genres'));
//                     } else {
//                       $related_genre = '';
//                     }
//                 $this->db->select('cat_name');
//                 $this->db->where('id', $this->input->post('cat_name'));
//                 $cat_name = $this->db->get('categories')->row_array();
//                 $insert_data = array(
//                     'type_id' => ucwords($this->input->post('cat_name')),
//                     'cate_id' => ucwords($this->input->post('cat_name')),
//                     'category_name' => $cat_name['cat_name'],
//                      'genres' => $related_genre,
//                      // 'app_id' =>(defined("APP_ID") ? "" . APP_ID . "" : "0"),
//                     'creation_time' => milliseconds(),
//                     'uploaded_by' => $this->session->userdata('active_backend_user_id')
//                 );
             
               
//              // pre( $insert_data); die;
//                 $id = $this->Category_model->insert_category($insert_data);
//               //  pre($id);die;
//                 if($id)
//                     update_api_version_new($this->db, 'menu_master');
//                 page_alert_box('success', 'Added', 'New Category Map successfully');
//                 redirect($_SERVER['HTTP_REFERER']);
//              }
//             }
//         }
//         $view_data['page'] = 'map_category';
//         // $app_id  = (defined("APP_ID") ? "" . APP_ID . "" : "0");
//         // $this->db->where('app_id',$app_id);
//         // $this->db->where('find_in_set("'.APP_ID.'", app_id)');
//         $view_data['categories'] = $this->db->get('categories')->result_array();
//         // $app_id  = (defined("APP_ID") ? "" . APP_ID . "" : "0");
//         // $this->db->where('app_id',$app_id);
//         $view_data['genres'] = $this->Category_model->get_generes();
//         $data['page_data'] = $this->load->view('category/map_categoryy', $view_data, TRUE);
//         echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
//     }
//     public function get_categorywise_geners($id=null){
//         $cate_id = $id;
//         $cate = $this->Category_model->get_category_geners($cate_id);
//         echo json_encode($cate);
//     }

   

//     public function edit_category($id) {
//         if ($this->input->post()) {
//             $this->form_validation->set_rules('cat_name', 'Category Name', 'required');
//             if ($this->form_validation->run() == FALSE) {
                
//             } else {
//                 $update_data = array(
//                     'id' => $this->input->post('id'),
//                     'cate_id' => $this->input->post('cat_name'),
//                     'modified_time' => milliseconds(),
//                     'uploaded_by' => $this->session->userdata('active_backend_user_id')
//                 );
//                 if ($this->input->post('related_genres') != '') {
//                     $related_genre = implode(",", $this->input->post('related_genres'));
//                    $update_data['genres']=$related_genre;
//                 }
//                 $this->db->where('id',$id)->update('category',$update_data);

//                 $view_data['category'] = $this->Category_model->get_category_by_id($id);
//                 $view_data['categories'] = $this->Category_model->get_categories_by_id($id);
//                 $view_data['genres'] = $this->Category_model->get_generes();
//                 $data['page_data'] = $this->load->view('category/edit_category', $view_data, TRUE);
//                 //$data['page_data'] = $this->load->view('category/map_categoryy', $view_data, TRUE);
//                 page_alert_box('success', 'Updated', 'Category has been updated successfully');

                
//               // redirect($_SERVER['HTTP_REFERER']);
//             }
//         }
//         $view_data['category'] = $this->Category_model->get_category_by_id($id);
//         $view_data['categories'] = $this->Category_model->get_categories_by_id($id);
//         $view_data['genres'] = $this->Category_model->get_generes();
//         $view_data['id'] = $id;
//         $view_data['page'] = 'edit_category';
//         $data['page_data'] = $this->load->view('category/edit_category', $view_data, TRUE);
//         echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
//     }
//     // Geetesh code start---------------
//     public function edit_cate($id){
//         if($this->input->post()){
//            $update_data = array(
//                'cat_name' => $this->input->post('name'),
//                'category_type' => ($this->input->post('category_type')?$this->input->post('category_type'):"0"),
//                'poster_style'  => $this->input->post('poster_style'),
//                 'updated_time' => time()
//             );

//             $update_data1 = array(
//                 'category_name' => $this->input->post('name'),
//                 //  'updated_time' => time()
//              );
//             $this->db->where('id',$id);
//             $this->db->update('categories',$update_data);
//             $this->db->where('type_id',$id);
//             $this->db->update('category',$update_data1);
//              //--update version start by ak--
//                       //  if ($this->db->affected_rows() > 0) {
//                             update_api_version_new($this->db, 'menu_master');
//                            // echo $this->db->last_query();
//                         //    echo "hiiiii";die;
//                            // echo json_encode(array("data" => 1, "result" => array()));
//                        //  }
//              //--update version end-- 
//             page_alert_box('success', 'Updated', 'Category has been updated successfully');
//             redirect(base_url() . 'admin-panel/add-category');

//         }
//         $this->db->select('*');
//         $this->db->where('id',$id);
//         $view_data['category'] = $this->db->get('categories')->row_array();
//        $view_data['page'] = 'edit_category';
//        $data['page_data'] = $this->load->view('category/edit_cat', $view_data, TRUE);
//         echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
//     }

//     public function delete_cate($id){
//          $this->db->where('id', $id);
//          $this->db->delete('categories');
//          page_alert_box('success', 'Category Deleted', 'Sub Category has been deleted successfully');
//         redirect(base_url() . 'admin-panel/add-category');

//     }
//      public function map_edit($id){ //print_r($this->input->post()); die;

//         if($this->input->post())        
//          {
//                $this->db->select('cat_name');
//                 $this->db->where('id', $this->input->post('cat_name'));
//                 $cat_name = $this->db->get('categories')->row_array();
//               //  pre($cat_name);die;
//             $update_data = array(
//                // 'id' => $this->input->post('id'),
//                 'type_id' =>  $this->input->post('cat_name'),
//                 'cate_id' => $this->input->post('cat_name'),
//                 'category_name' => $cat_name['cat_name'],
//                 'modified_time' => milliseconds(),
//                 'uploaded_by' => $this->session->userdata('active_backend_user_id')
//             );
//             if ($this->input->post('related_genres') != '') {
//                 $related_genre = implode(",", $this->input->post('related_genres'));
//                $update_data['genres']=$related_genre;
//             }
//             $this->db->where('id',$id)->update('category',$update_data);
//             update_api_version_new($this->db, 'menu_master');
//            // echo $this->db->last_query();die;
//             page_alert_box('success', 'Updated', 'Map Category has been updated successfully');
//            redirect(base_url('auth_panel/category/category/map_category'));
//         }

//         $this->db->select('*');
//         $this->db->where('id',$id);
//         $view_data['categ'] = $this->db->get('category')->row_array();
//          $data_id=$view_data['categ']['type_id'];
//         //_idecho ($data_);die;
         
//        $view_data['category'] = $this->Category_model->get_category_by_id($id);
//        $view_data['categories'] = $this->Category_model->get_categories_by_id($data_id);
//        // echo $this->db->last_query();
//        $view_data['genres'] = $this->Category_model->get_generes();
//        $view_data['id'] = $data_id;
//        $view_data['map_id'] = $id;
//        $view_data['page'] = 'edit_category';
//        $data['page_data'] = $this->load->view('category/map_edit', $view_data, TRUE);
//         echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
//     }
//     // Geetesh end code----------------

//     public function delete_category($id) {
//         $delete_user = $this->Category_model->delete_category($id);
//         page_alert_box('success', 'Category Deleted', 'Sub Category has been deleted successfully');
//         redirect(BASE_URL . 'admin-panel/add-category');
//     }



//     public function ajax_category() {
//         $output_csv = $output_pdf = false;
//               $requestData = $_REQUEST;
//         if (isset($this->input->post('input_json'))) {
//             if (ISSET($this->input->post('download_pdf'))) {
//                 $output_pdf = true;
//             } else {
//                 $output_csv = true;
//             }
//         }
        
//         $columns = array(
//             // datatable column index  => database column name
//             0 => 'id',
//             1 => 'cat_name',
//             5 => 'genres',
//             2 => 'creation_time',
//             3 => 'updated_time',
//         );
//         $where = ' where 1';

//         $query = "SELECT count(id) as total
//                   FROM categories $where ";
//         // $query .= (defined("APP_ID") ? "" . app_permission("app_id") . "" : "");
//         $query = $this->db->query($query);
//         $query = $query->row_array();
//         app_permission("app_id",$this->db);  
//         $totalData = (count($query) > 0) ? $query['total'] : 0;
//         $totalFiltered = $totalData;
//         $sql = "SELECT id,cat_name,category_type,creation_time,updated_time,category_type
//                 FROM categories $where ";
        
//         // $sql .= (defined("APP_ID") ? "" . app_permission("app_id") . "" : "");
//         //$sql .= " and find_in_set(".APP_ID.", app_id)";
//         // getting records as per search parameters
//         if (!empty($requestData['columns'][0]['search']['value'])) {
//             $sql .= " AND id LIKE '" . $requestData['columns'][0]['search']['value'] . "%' ";
//         }
//         if (!empty($requestData['columns'][1]['search']['value'])) {
//             $sql .= " AND cat_name LIKE '" . $requestData['columns'][1]['search']['value'] . "%' ";
//         }
//         $query = $this->db->query($sql)->result();
//        // $totalFiltered = count($query); // when there is a search parameter then we have to modify total number filtered rows as per search result.
//         //        $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   "; // adding length
//         if(isset($requestData['start'])){
//        $sql .= " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
//        } // adding length

//         $result = $this->db->query($sql)->result();
//         // echo $this->db->last_query();die;
//         //print_r($result);die;
//         if ($output_csv == true) {
//             // for csv loop
//             $head = array('Sr.No', 'Category Name','category_type','Registered On', 'modified date',);
//             $id = 0;
//             foreach ($result as $r) {
//                 $nestedData = array();
//                 $nestedData[] = ++$id;
//                 $nestedData[] = $r->cat_name; 
//                 $nestedData[] = ($r->category_type == 2) ? "Web series" : (($r->category_type == 3)  ? "Video" : "Video");
//                 $nestedData[] = $r->creation_time ? get_time_format($r->creation_time) : "--NA--";
               
//                 $nestedData[] = $r->creation_time ? get_time_format($r->creation_time) : "--NA--";
                
//                 // $nestedData[] = $r->creation_time; //
//                 // $nestedData[] = $r->modified_time;
//                 //date('Y-m-d',strtotime($r->creation_time))
//                 $data[] = $nestedData;
//             }
//             if ($output_csv == true) {
//                 $this->all_category_to_csv_download($data, $filename = "Category.csv", $delimiter = ";", $head);
//                 die;
//             }
//         }
//         $data = array();
//         foreach ($result as $r) {         
//             // preparing an array
//             $nestedData = array();
//             $nestedData[] = ++$requestData['start'];
//             $nestedData[] = ucfirst($r->cat_name);
//             $nestedData[] = ($r->category_type == 2) ? "Web series" : (($r->category_type == 3)  ? "Video" : "Video");
//             $nestedData[] = $r->creation_time ? get_time_format($r->creation_time) : "--NA--";
//             $nestedData[] = $r->updated_time ? get_time_format($r->updated_time) : "--NA--";
//         //    $nestedData[] = $r->creation_time ? $r->creation_time: "--NA--";//
//         //     $nestedData[] = $r->modified_time ? $r->modified_time: "--NA--";

//             $nestedData[] = "<a class='btn-xs bold btn btn-primary' onclick=\"return confirm('Are you sure you want to edit?')\" href='" . base_url('auth_panel/category/category/edit_cate/') . $r->id . "'><i class='fa fa-edit'></i></a>&nbsp;&nbsp;
//             ";
//             $data[] = $nestedData;
//             //print_r($data);
//         }
//         $json_data = array(
//             "draw" => intval($requestData['draw']), // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
//             "recordsTotal" => intval($totalData), // total number of records
//             "recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
//             "data" => $data, // total data array
//         );
//         echo json_encode($json_data);  // send data as json format
//     }

//     public function get_request_for_csv_download($device_type="") {
//         $this->ajax_category($device_type);
//     }
//     public function get_request_csv_download($device_type="") {
//         $this->ajax_category_list($device_type);
//     }


//     public function all_category_to_csv_download($array, $filename = "Users.csv", $delimiter = ";", $header) {
//         header('Content-Type: application/csv');
//         header('Content-Disposition: attachment; filename="' . $filename . '";');
//         $f = fopen('php://output', 'w');
//         fputcsv($f, $header);
//         foreach ($array as $line) {
//             fputcsv($f, $line);
//         }
//     }
//     public function all_category_csv_download($array, $filename = "Users.csv", $delimiter = ";", $header) {
//         header('Content-Type: application/csv');
//         header('Content-Disposition: attachment; filename="' . $filename . '";');
//         $f = fopen('php://output', 'w');
//         fputcsv($f, $header);
//         foreach ($array as $line) {
//             fputcsv($f, $line);
//         }
//     }
    //XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX---> CATEGORY BLOG END <---XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX


    
    public function create_cloudfront_url()
    {
        $input = $this->input->post();
        $course_id = !empty($input['v_id']) ? $input['v_id'] : 0;
        if(isset($input['mode']) && $input['mode']=="episode")
        {
            $query = "SELECT  vdc_id,ctfmm.id,episode_url,drm_dash_url,ctfmm.is_drm_protected,drm_hls_url,media_type
            FROM media_library
            WHERE ctfmm.id = '" . $input['v_id'] . "'";
            $media_library = $this->db->query($query)->row_array();
            
        }
        else
        {
            $query = "SELECT  vdc_id,ctfmm.id,file_url,drm_dash_url,ctfmm.is_drm_protected,drm_hls_url,media_type
            FROM media_library ctfmm
            WHERE ctfmm.id = '" . $input['v_id'] . "'";
            $media_library = $this->db->query($query)->row_array();
        }
        if ($media_library['drm_dash_url'] && $media_library["is_drm_protected"] == 1) {
            $file_url = $media_library['drm_dash_url'];
        } else {
            $file_url = $media_library['file_url']??$media_library['episode_url'];
        }
        $content_id = $cf_domain = "";
        if ($media_library['media_type'] == 5) {
            /* For live streaming Tokens */
            $this->db->select("endpoint_id,cf_domain");
            $this->db->like("url", $file_url);
            $content_id = $this->db->get("aws_media_package_endpoint")->row_array();
            if (!$content_id) {
                echo json_encode(
                    array(
                        'data' => 2,
                        'message' => "Unable to generate Video URL. Please try again after some time",
                        'type' => "",
                        'token' => ""
                    )
                );
                die;
            }
            $cf_domain = $content_id['cf_domain'];
        } elseif ($media_library['is_drm_protected'] == 0 && ($media_library['media_type'] == 7 || $media_library['media_type'] == 0)) {
            /* For Media Convert tokens */
            echo json_encode(
                array(
                    'data' => 1,
                    'url' => $file_url,
                    'type' => 'm3u8',
                    'token' => ""
                )
            );
            die;
        } else if ($media_library['is_drm_protected'] && ($media_library['media_type'] == 8 || $media_library['media_type'] == 0)) {
            /* For Media Convert tokens */
            $content_id = $media_library['vdc_id'];
        }
        $header = array();
        $header[] = "Cache-Control:no-cache";
        $header[] = "device_type:1";
        $post_data['name'] = $content_id;
        $header[] = "account_id:bypass001";
        $header[] = "user_id:" . session_id();
        $header[] = "device_id:1";
        $header[] = "version:1";
        $header[] = "device_name:Admin";
        $post_data['device_name'] = 'Admin';
        $post_data['flag'] = '1';
        $request_time = time();
        $api = 'https://www.videocrypt.in/index.php/rest_api/courses/course/on_request_create_video_link';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($server_output, true);

      //  media_api_log($this->db, $header, $api, $post_data, $data, $request_time);


      //  write_log($server_output, "response");
        if (isset($data['status']) && $data['status'] == true) {
            $api_data = $data['data']['link'];
            echo json_encode(
                array(
                    'data' => 1,
                    'url' => $api_data['file_url'],
                    'type' => 'mpd',
                    'token' => $api_data['token']
                )
            );
            die;
        } else {
            echo json_encode(['status' => false, 'message' => 'url not foundw', 'data' => []]);
            die;
        }
    }

}

