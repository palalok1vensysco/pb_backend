<?php

use Aws\S3\S3Client;
defined('BASEPATH') OR exit('No direct script access allowed');

class SeasonController extends MX_Controller {

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
  

    public function add_season() {
        if ($this->input->post()) {
             $this->form_validation->set_rules('title', 'Title', 'required');
            $category_new = $_POST['title'];
            // $appid = ((defined("APP_ID") && APP_ID)? "" . APP_ID . "" : "0");
            $sql = "select title from seasons where title = '$category_new'";
                $query = $this->db->query($sql);
                $checkrows=$query->num_rows();
               // echo $this->db->last_query($sql);die;
            if ($this->form_validation->run() == TRUE) {                           
                if (!empty($_FILES['thumbnail_season']['name'])) {
                    $image = $this->amazon_s3_upload($_FILES['thumbnail_season'], "season/thumbnail");
                 } else {
                     $image = '';
                 } 	             
                $insert_data = array(
                    'title' => $this->input->post('title'),                        
                    'thumbnail' => $image,
                    'status' => 0,
                    'created_at'=> strtotime("now"),
                    'modified_at'=> strtotime("now"),
                    'created_by'=> $this->session->userdata('active_backend_user_id')
                );
                
                $id = $this->SeasonModel->insert_season($insert_data);                                     
                page_alert_box('success', 'Added', 'New Season added successfully');
                redirect($_SERVER['HTTP_REFERER']);
                
            }
        }
        // app_permission("app_id",$this->db);
        // $f_list = $this->db->get("application_meta")->result_array();
        // $view_data['f_lists'] = json_decode($f_list[0]['functionality']);
        $view_data['page'] = 'add_season';
        $data['page_data'] = $this->load->view('season/add_season', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function ajax_season_list() {
        $output_csv = $output_pdf = false;
        $requestData = $_REQUEST;
          
        $columns = array(
            // datatable column index  => database column name
            0 => 'id',
            1 => 'title',                        
            2 => 'thumbnail',
            3 => 'modified_at',
            4 => 'status',
        );

        $query = "SELECT count(id) as total
                  FROM seasons where status= 0";        
        $query = $this->db->query($query);
        $query = $query->row_array();
        $totalData = (count($query) > 0) ? $query['total'] : 0;
        $totalFiltered = $totalData;
        $sql = "SELECT id,title,thumbnail,created_at,status
                FROM seasons where status in (0,1)";       
        if (!empty($requestData['columns'][0]['search']['value'])) {
            $sql .= " AND id LIKE '" . $requestData['columns'][0]['search']['value'] . "%' ";
        }

        if (!empty($requestData['columns'][1]['search']['value'])) {
            $sql .= " AND title LIKE '" . $requestData['columns'][1]['search']['value'] . "%' ";
        }

        $query = $this->db->query($sql)->result();

       // $totalFiltered = count($query); // when there is a search parameter then we have to modify total number filtered rows as per search result.
        //        $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   "; // adding length
        if(isset($requestData['start'])){
       $sql .= " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
       } // adding length
       
        $result = $this->db->query($sql)->result();
                 
        $data = array();
        $id = 0;
        foreach ($result as $r) {
            $nestedData = array();
            $nestedData[] = ++$id;
            $nestedData[] = $r->title;
            $nestedData[] = "<img width='200px' height='80px' src='".$r->thumbnail."'></a>"; 
            $nestedData[] = $r->created_at ? get_date_format($r->created_at) : "--NA--";
            $nestedData[] = ($r->status == 0 ) ? 'Enabled' : 'Disabled';                
            $nestedData[] = "<a class='btn-xs bold btn btn-primary' title='Edit' onclick=\"return confirm('Are you sure you want to Edit?')\" href='" . AUTH_PANEL_URL . "contentManagement/SeasonController/edit_season/" . $r->id . "'><i class='fa fa-edit'></i></a>&nbsp;
				<a class='btn-xs bold btn btn-danger' title='Delete' onclick=\"return confirm('Are you sure you want to delete?')\" href='" . AUTH_PANEL_URL . "contentManagement/SeasonController/delete_season/" . $r->id . "'><i class='fa fa-trash-o'></i></a>&nbsp;
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

    public function delete_season($id) {
        $delete_user = $this->SeasonModel->delete_season($id);
        page_alert_box('success', 'Season Deleted', 'Season has been deleted successfully');

        redirect(base_url('admin-panel/add-season'));        
    }

    public function edit_season($id) {
        if ($this->input->post()) {
            $this->form_validation->set_rules('title', 'Title', 'required');
           $category_new = $_POST['title'];           
            $sql = "select title from seasons where title = '$category_new'";
            $query = $this->db->query($sql);
            $checkrows=$query->num_rows();  

           if ($this->form_validation->run() == TRUE) {                           
                if (!empty($_FILES['thumbnail_season']['name'])) {
                    $image = $this->amazon_s3_upload($_FILES['thumbnail_season'], "season/thumbnail");
                }else{
                    $image = '';
                }	               
            echo $image;die;
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
                    $res = $this->SeasonModel->update_season($update_data,$id);                                         
                    page_alert_box('success', 'Updated', 'Updated Season Successfully');
                    redirect($_SERVER['HTTP_REFERER']);               
            }
       }    
        $view_data['seasons'] = $this->SeasonModel->get_seasons_by_id($id);        
        $view_data['id'] = $id;
        $view_data['page'] = 'edit_season';        
        $data['page_data'] = $this->load->view('season/edit_season', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function update_season_status($id,$staus) {
        $delete_user = $this->SeasonModel->update_season_status($id,$staus);
        page_alert_box('success', 'Season Deleted', 'Season has been deleted successfully');

        redirect(base_url('admin-panel/add-season'));        
    }

    public function list_content(){
        $view_data['page'] = 'List Content';        
        $data['page_data'] = $this->load->view('season/list_content', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }
    public function add_content(){
        $view_data['page'] = 'edit_season';        
        $data['page_data'] = $this->load->view('season/add_content', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }


    public function ajax_list_content(){
        $output_csv = $output_pdf = false;
        $requestData = $_REQUEST;
          
        $columns = array(
            // datatable column index  => database column name
            0 => 'id',
            1 => 'category',                        
            2 => 'title',                        
            3 => 'thumbnail',                        
            4 => 'created_at'
        );
        $query = "SELECT count(shows.id) as total
                  FROM shows join categories c on c.id = shows.category_id and c.status = 0  where shows.status in (0,1)";        
        $query = $this->db->query($query);
        $query = $query->row_array();
        $totalData = (count($query) > 0) ? $query['total'] : 0;
        $totalFiltered = $totalData;
        $sql = "SELECT shows.*,c.title as category_name
                FROM shows join categories c on c.id = shows.category_id and c.status = 0  where shows.status in (0,1)";       
        if (!empty($requestData['columns'][0]['search']['value'])) {
            $sql .= " AND shows.type = '" . $requestData['columns'][0]['search']['value'] . "%' ";
        }

        if (!empty($requestData['columns'][1]['search']['value'])) {
            $sql .= " AND shows.title LIKE '" . $requestData['columns'][1]['search']['value'] . "%' ";
        }

        $query = $this->db->query($sql)->result();

        if(isset($requestData['start'])){
            $sql .= " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
        } // adding length
       
        $result = $this->db->query($sql)->result();
       // echo $this->db->last_query();die;
        $data = array();
        $id = 0;
        foreach ($result as $r) {
            $nestedData = array();
            $nestedData[] = ++$id;
            $nestedData[] = $r->category_name; 
            $nestedData[] = ($r->type == 0) ? "Video" : "Audio"; 
            $nestedData[] = $r->title; 
            $nestedData[] = "<img width='200px' height='80px' src='".$r->thumbnail."'></a>";
            $nestedData[] = ($r->status == 0 ) ? 'Enabled' : 'Disabled';                
            $nestedData[] = $r->created_at ? get_date_format($r->created_at) : "--NA--";

            $nestedData[] = "<div class='dropdown toggle_menus_icons'> <button class='btn dropdown_ttgl text-white dropdown-toggle ' type='button' data-toggle='dropdown'><i class='fa fa-ellipsis-v' aria-hidden='true'></i></button>
            <ul class='dropdown-menu'>               
                <li><a title='Edit' onclick=\"return confirm('Are you sure you want to Edit?')\" href='" . site_url() . "admin-panel/add-content/" . $r->id . "'>Edit</a></li>
                <li><a class='' title='Delete' onclick=\"return confirm('Are you sure you want to delete?')\" href='" . site_url() . "auth_panel/season/SeasonController/delete_show/" . $r->id . "'>Delete</a></li>
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



    public function delete_show($id){
        $this->db->select('skip_season');
        $shows = $this->db->get_where('shows', ['id' => $id, 'status !=' => 2])->row_array();
        if(!empty($shows['skip_season'])){
            $this->db->limit(1);
            $media_library = $this->db->get_where('media_library', ['show_id' => $id, 'status !=' => 2])->row_array();
            if(!empty($media_library)){
                page_alert_box('error', 'Error', 'please delete atteched Video first!!..');
                redirect($_SERVER['HTTP_REFERER']);  
            }
             $this->db->update('seasons', ['status' => 2], ['show_id' => $id]);
        }else{
            $this->db->limit(1);
            $seasons = $this->db->get_where('seasons', ['show_id' => $id, 'status !=' => 2])->row_array();
            if(!empty($seasons) && empty($seasons['skip_season'])){
                page_alert_box('error', 'Error', 'please delete atteched seasons first!!..');
                redirect($_SERVER['HTTP_REFERER']);    
            }
        }
        // $delete_user = $this->ShowsModel->delete($id);
        $this->db->update('shows', ['status' => 2], ['id' => $id]);
        page_alert_box('success', 'Season Deleted', 'Show has been deleted successfully');
        redirect($_SERVER['HTTP_REFERER']);
    }


}