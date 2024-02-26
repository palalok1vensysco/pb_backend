<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Category extends MX_Controller {

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
        $this->load->model("Category_model");
        $this->load->model("Sub_Category_model");
       // $this->load->model("guru_model");
        $this->load->helper('cookie');
    }
  

//XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX---> CATEGORY BLOG START <---XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
    public function map_category() {
      
        if ($this->input->post()) {//echo '<pre>'; print_r($_POST); die;
            $this->form_validation->set_rules('cat_name', 'Category Name', 'required');
            if ($this->form_validation->run() == FALSE) {
            } else {
                 if ($this->input->post('related_genres') != '') {
                    $related_genre = implode(",", $this->input->post('related_genres'));
                } else {
                  $related_genre = '';
                }
       

                $this->db->select('cat_name');
                $this->db->where('id', $this->input->post('cat_name'));
                $cat_name = $this->db->get('categories')->row_array();

              //  pre($cat_name);die;
              

                $insert_data = array(
                    'type_id' => ucwords($this->input->post('cat_name')),
                    'cate_id' => ucwords($this->input->post('cat_name')),
                    'category_name' => $cat_name['cat_name'],
                     'genres' => $related_genre,
                     'app_id' =>(defined("APP_ID") ? "" . APP_ID . "" : "0"),
                    'creation_time' => milliseconds(),
                    'uploaded_by' => $this->session->userdata('active_backend_user_id')
                );
             
               
             // pre( $insert_data); die;
                $id = $this->Category_model->insert_category($insert_data);
              //  pre($id);die;
                page_alert_box('success', 'Added', 'New Category Map successfully');
                redirect($_SERVER['HTTP_REFERER']);
            }
        }
        $view_data['page'] = 'map_category';
        // $app_id  = (defined("APP_ID") ? "" . APP_ID . "" : "0");
        // $this->db->where('app_id',$app_id);
        $this->db->where('find_in_set("'.APP_ID.'", app_id)');
        $view_data['categories'] = $this->db->get('categories')->result_array();
        $app_id  = (defined("APP_ID") ? "" . APP_ID . "" : "0");
        $this->db->where('app_id',$app_id);
        $view_data['genres'] = $this->Category_model->get_generes();
        $data['page_data'] = $this->load->view('category/map_categoryy', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }
    public function get_categorywise_geners($id=null){
        $cate_id = $id;
        $cate = $this->Category_model->get_category_geners($cate_id);
        echo json_encode($cate);
    }

    public function add_category() {
        if ($this->input->post()) {
             $this->form_validation->set_rules('name', 'Category Name', 'required');
            $category_new = $_POST['name'];
            $appid = ((defined("APP_ID") && APP_ID)? "" . APP_ID . "" : "0");
            $sql = "select cat_name from categories where cat_name = '$category_new' and app_id='$appid'";
                $query = $this->db->query($sql);
                $checkrows=$query->num_rows();
               // echo $this->db->last_query($sql);die;
            if ($this->form_validation->run() == FALSE) {                
            } else {
                if($checkrows == 0)
                {
                    $appid1 = ((defined("APP_ID") && APP_ID)? "" . APP_ID . "" : "0");
                    $insert_data = array(
                        'cat_name' => $this->input->post('name'),
                        'app_id' =>$appid1,
                        'creation_time' => milliseconds(),
                        'updated_time' => time(),
                    );
                    $id = $this->Category_model->add_category($insert_data);
                    page_alert_box('success', 'Added', 'New Category added successfully');
                    redirect($_SERVER['HTTP_REFERER']);
                }
                else
                {
                    page_alert_box('error', 'Duplicate', 'Category already exist');
                    redirect($_SERVER['HTTP_REFERER']);
                }
            }
        }
        $view_data['page'] = 'add_category';
        $data['page_data'] = $this->load->view('category/add_categoryy', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function edit_category($id) {
        if ($this->input->post()) {
            $this->form_validation->set_rules('cat_name', 'Category Name', 'required');
            if ($this->form_validation->run() == FALSE) {
                
            } else {
                $update_data = array(
                    'id' => $this->input->post('id'),
                    'cate_id' => $this->input->post('cat_name'),
                    'modified_time' => milliseconds(),
                    'uploaded_by' => $this->session->userdata('active_backend_user_id')
                );
                if ($this->input->post('related_genres') != '') {
                    $related_genre = implode(",", $this->input->post('related_genres'));
                   $update_data['genres']=$related_genre;
                }
                $this->db->where('id',$id)->update('category',$update_data);
                $view_data['category'] = $this->Category_model->get_category_by_id($id);
                $view_data['categories'] = $this->Category_model->get_categories_by_id($id);
                $view_data['genres'] = $this->Category_model->get_generes();
                $data['page_data'] = $this->load->view('category/edit_category', $view_data, TRUE);
                //$data['page_data'] = $this->load->view('category/map_categoryy', $view_data, TRUE);
                page_alert_box('success', 'Updated', 'Category has been updated successfully');

                
              // redirect($_SERVER['HTTP_REFERER']);
            }
        }
        $view_data['category'] = $this->Category_model->get_category_by_id($id);
        $view_data['categories'] = $this->Category_model->get_categories_by_id($id);
        $view_data['genres'] = $this->Category_model->get_generes();
        $view_data['id'] = $id;
        $view_data['page'] = 'edit_category';
        $data['page_data'] = $this->load->view('category/edit_category', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }
    // Geetesh code start---------------
    public function edit_cate($id){
        if($this->input->post()){
            $update_data = array(
               'cat_name' => $this->input->post('name'),
                'updated_time' => time()
            );
          
            $this->db->where('id',$id);
            $this->db->update('categories',$update_data);
            page_alert_box('success', 'Updated', 'Category has been updated successfully');
            redirect(base_url() . 'admin-panel/add-category');

        }
        $this->db->select('*');
        $this->db->where('id',$id);
        $view_data['category'] = $this->db->get('categories')->row_array();
       $view_data['page'] = 'edit_categoryy';
       $data['page_data'] = $this->load->view('category/edit_cat', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function delete_cate($id){
         $this->db->where('id', $id);
         $this->db->delete('categories');
         page_alert_box('success', 'Category Deleted', 'Sub Category has been deleted successfully');
        redirect(base_url() . 'admin-panel/add-category');

    }
     public function map_edit($id){ //print_r($this->input->post()); die;

        if($this->input->post())
        
         {
            $update_data = array(
               // 'id' => $this->input->post('id'),
                'cate_id' => $this->input->post('cat_name'),
                'modified_time' => milliseconds(),
                'uploaded_by' => $this->session->userdata('active_backend_user_id')
            );
            if ($this->input->post('related_genres') != '') {
                $related_genre = implode(",", $this->input->post('related_genres'));
               $update_data['genres']=$related_genre;
            }
            $this->db->where('id',$id)->update('category',$update_data);
            page_alert_box('success', 'Updated', 'Map Category has been updated successfully');
           redirect($_SERVER['HTTP_REFERER']);
        }

        $this->db->select('*');
        $this->db->where('id',$id);
        $view_data['categ'] = $this->db->get('category')->row_array();
        //echo $this->db->last_query();die;

         $data_id=$view_data['categ']['type_id'];
        //_idecho ($data_);die;
         
       $view_data['category'] = $this->Category_model->get_category_by_id($id);
       //$view_data['categories'] = $this->Category_model->get_categories_by_id($data_id);
       $this->db->where('find_in_set("'.APP_ID.'", app_id)');
        $view_data['categories'] = $this->db->get('categories')->result_array();
     //  echo $this->db->last_query()
       $view_data['genres'] = $this->Category_model->get_generes();
       $view_data['id'] = $data_id;
       $view_data['page'] = 'edit_category';
       $data['page_data'] = $this->load->view('category/map_edit', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }
    // Geetesh end code----------------

    public function delete_category($id) {
        $delete_user = $this->Category_model->delete_category($id);
        page_alert_box('success', 'Category Deleted', 'Sub Category has been deleted successfully');
        redirect(BASE_URL . 'admin-panel/add-category');
    }

    public function ajax_category_list() {
        $output_csv = $output_pdf = false;
              $requestData = $_REQUEST;
        if (isset($_POST['input_json'])) {
            if (ISSET($_POST['download_pdf'])) {
                $output_pdf = true;
            } else {
                $output_csv = true;
            }
        }
        
        $columns = array(
            // datatable column index  => database column name
            0 => 'id',
            1 => 'cat_name',
            5 => 'genres',
            6 => 'type_id',
            2 => 'creation_time',
            3 => 'modified_time',
            4 => 'status',
        );

        $query = "SELECT count(id) as total
                  FROM category where status=0";
        $query .=  (defined("APP_ID") ? "" . app_permission("category.app_id") . "" : "");
        $query = $this->db->query($query);
        $query = $query->row_array();
        $totalData = (count($query) > 0) ? $query['total'] : 0;
        $totalFiltered = $totalData;
        $sql = "SELECT category.id,cat_name,category.type_id,DATE_FORMAT(FROM_UNIXTIME(category.creation_time/1000), '%d-%m-%Y') as creation_time,DATE_FORMAT(FROM_UNIXTIME(category.modified_time/1000), '%d-%m-%Y') as modified_time,genres,type_id,status
                FROM category join categories as cate on cate.id = category.cate_id where status=0";
        $sql .= (defined("APP_ID") ? "" . app_permission("category.app_id") . "" : "");
        // getting records as per search parameters
        if (!empty($requestData['columns'][0]['search']['value'])) {
            $sql .= " AND id LIKE '" . $requestData['columns'][0]['search']['value'] . "%' ";
        }

        if (!empty($requestData['columns'][1]['search']['value'])) {
            $sql .= " AND cat_name LIKE '" . $requestData['columns'][1]['search']['value'] . "%' ";
        }

        $query = $this->db->query($sql)->result();

        $totalFiltered = count($query); // when there is a search parameter then we have to modify total number filtered rows as per search result.
        //        $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   "; // adding length
        if(isset($requestData['start'])){
       $sql .= " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
       } // adding length

        $result = $this->db->query($sql)->result();
        // echo $this->db->last_query();die;
        
      $data=array();
        if ($output_csv == true) {
            // for csv loop
            $head = array('Sr.No', 'Category Name','Type Id', 'genres','Status', 'Registered On','Modified On');
            $start = 0;
            foreach ($result as $r) {
                $nestedData = array();
                $nestedData[] = ++$start; //$r->id;
                $nestedData[] = $r->cat_name; 
                $nestedData[] = $r->type_id; 
                $get_categories = $this->db->select('sub_category_name')->where_in('id', explode(',', $r->genres))->get('sub_category')->result_array();
               // echo $this->db->last_query();die;
            

                $category_arr = array();
                foreach ($get_categories as $category) {
                    $category_arr[] = $category['sub_category_name'];
                }
                $categoryy = (implode(',', $category_arr));
                $nestedData[] = $categoryy;
                $nestedData[] = ($r->status == 0 ) ? 'Active' : 'Disabled';
                $nestedData[] = $r->creation_time ? $r->creation_time: "--NA--";
                $nestedData[] = $r->modified_time ? $r->modified_time: "--NA--";
                $data[] = $nestedData;
            }
            if ($output_csv == true) {
                $this->all_category_to_csv_download($data, $filename = "Category.csv", $delimiter = ";", $head);
                die;
            }
        }
        $data = array();
        foreach ($result as $r) {
            // preparing an array
            $nestedData = array();
            $nestedData[] = ++$requestData['start'];
            $nestedData[] = ucfirst($r->cat_name);
            $get_categories = $this->db->select('sub_category_name')->where_in('id', explode(',', $r->genres))->get('sub_category')->result_array();

            $category_arr = array();
            foreach ($get_categories as $category) {
                $category_arr[] = $category['sub_category_name'];
            }
            $categoryy = (implode(',', $category_arr));
            $nestedData[] = $categoryy;
           // $nestedData[] = $r->id;
            $nestedData[] = $r->creation_time;
            $nestedData[] = $r->modified_time;
            
            $nestedData[] = "<a class='btn-xs bold btn btn-primary' onclick=\"return confirm('Are you sure you want to edit?')\" href='" . base_url('auth_panel/category/category/map_edit/') . $r->id . "'><i class='fa fa-edit'></i></a>&nbsp;
            ";
            $data[] = $nestedData;
        }
        $json_data = array(
          //  "draw" => intval($requestData['draw']), // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
            "recordsTotal" => intval($totalData), // total number of records
            "recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
            "data" => $data, // total data array
        );
        echo json_encode($json_data); // send data as json format
    }

    public function ajax_category() {
        $output_csv = $output_pdf = false;
              $requestData = $_REQUEST;
        if (isset($_POST['input_json'])) {
            if (ISSET($_POST['download_pdf'])) {
                $output_pdf = true;
            } else {
                $output_csv = true;
            }
        }
        
        $columns = array(
            // datatable column index  => database column name
            0 => 'id',
            1 => 'cat_name',
            5 => 'genres',
            2 => 'creation_time',
            3 => 'updated_time',
        );
        $where = ' where 1';

        $query = "SELECT count(id) as total
                  FROM categories $where ";
        $query .= (defined("APP_ID") ? "" . app_permission("app_id") . "" : "");
        $query = $this->db->query($query);
        $query = $query->row_array();
        $totalData = (count($query) > 0) ? $query['total'] : 0;
        $totalFiltered = $totalData;
        $sql = "SELECT id,cat_name,DATE_FORMAT(FROM_UNIXTIME(creation_time), '%d-%m-%Y') as creation_time,DATE_FORMAT(FROM_UNIXTIME(updated_time), '%d-%m-%Y') as modified_time
                FROM categories $where ";
        
        //$sql .= (defined("APP_ID") ? "" . app_permission("app_id") . "" : "");
        $sql .= " and find_in_set(".APP_ID.", app_id)";
        // getting records as per search parameters
        if (!empty($requestData['columns'][0]['search']['value'])) {
            $sql .= " AND id LIKE '" . $requestData['columns'][0]['search']['value'] . "%' ";
        }
        if (!empty($requestData['columns'][1]['search']['value'])) {
            $sql .= " AND cat_name LIKE '" . $requestData['columns'][1]['search']['value'] . "%' ";
        }
        $query = $this->db->query($sql)->result();
        $totalFiltered = count($query); // when there is a search parameter then we have to modify total number filtered rows as per search result.
        //        $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   "; // adding length
        if(isset($requestData['start'])){
       $sql .= " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
       } // adding length

        $result = $this->db->query($sql)->result();
       // echo $this->db->last_query($sql);die;
        if ($output_csv == true) {
            // for csv loop
            $head = array('Sr.No', 'Category Name','Type Id', 'Status', 'Registered On');
            $start = 0;
            foreach ($result as $r) {
                $nestedData = array();
                $nestedData[] = ++$start; //$r->id;
                $nestedData[] = $r->cat_name; 
                $nestedData[] = $r->creation_time ? $r->creation_time: "--NA--";//date('Y-m-d',strtotime($r->creation_time))
                $nestedData[] = $r->modified_time;
                $data[] = $nestedData;
            }
            if ($output_csv == true) {
                $this->all_category_to_csv_download($data, $filename = "Category.csv", $delimiter = ";", $head);
                die;
            }
        }
        $data = array();
        $ids = 0;
        foreach ($result as $r) {           
            // preparing an array
            $nestedData = array();
            $nestedData[] = ++$requestData['start'];
            $nestedData[] = ucfirst($r->cat_name);
           //$r->creation_time
           
             $nestedData[] = $r->modified_time;

            //   $nestedData[] = "NA";  
          
            $nestedData[] = $r->creation_time;
            
            $nestedData[] = "<a class='btn-xs bold btn btn-primary' onclick=\"return confirm('Are you sure you want to edit?')\" href='" . base_url('auth_panel/category/category/edit_cate/') . $r->id . "'><i class='fa fa-edit'></i></a>&nbsp;&nbsp;
            ";
            $data[] = $nestedData;
            $ids++;
           // print_r($data);die;
        }
        $json_data = array(
            "draw" => intval($requestData['draw']), // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
            "recordsTotal" => intval($totalData), // total number of records
            "recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
            "data" => $data, // total data array
        );
        echo json_encode($json_data); // send data as json format
    }

    public function get_request_for_csv_download($device_type="") {
        $this->ajax_category_list($device_type);
    }


    public function all_category_to_csv_download($array, $filename = "Users.csv", $delimiter = ";", $header) {
        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '";');
        $f = fopen('php://output', 'w');
        fputcsv($f, $header);
        foreach ($array as $line) {
            fputcsv($f, $line);
        }
    }

    //XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX---> CATEGORY BLOG END <---XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

}

