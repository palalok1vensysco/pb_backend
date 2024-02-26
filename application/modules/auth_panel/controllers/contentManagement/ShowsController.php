<?php

use Aws\S3\S3Client;
defined('BASEPATH') OR exit('No direct script access allowed');

class ShowsController extends MX_Controller {

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
  

    public function add() {
        if ($this->input->post()) {
             $this->form_validation->set_rules('title', 'Title', 'required');
            $category_new = $_POST['title'];
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

        $query = "SELECT count(id) as total
                  FROM shows where status= 0";        
        $query = $this->db->query($query);
        $query = $query->row_array();
        $totalData = (count($query) > 0) ? $query['total'] : 0;
        $totalFiltered = $totalData;
        $sql = "SELECT categories.title as cat_name, shows.id,shows.category_id,shows.title,shows.thumbnail,shows.created_at,shows.status 
                FROM shows INNER JOIN categories ON shows.category_id = categories.id where shows.status in (0,1)";       
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

    public function delete($id) {
        $delete_user = $this->ShowsModel->delete($id);
        page_alert_box('success', 'Season Deleted', 'Season has been deleted successfully');
        redirect(base_url('admin-panel/add-shows'));        
    }

    public function edit($id) {
        if ($this->input->post()) {
            $this->form_validation->set_rules('title', 'Title', 'required');
           $category_new = $_POST['title'];           
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
//         if (isset($_POST['input_json'])) {
//             if (ISSET($_POST['download_pdf'])) {
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

}

