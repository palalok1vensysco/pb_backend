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
        if ($this->input->post()) {
            $this->form_validation->set_rules('category_id', 'Category', 'required');
            $this->form_validation->set_rules('genres_id[]', 'Genres', 'required');
            if ($this->form_validation->run() == FALSE) {
            } else {
                $input = $this->input->post();

                $category_id = $this->input->post('category_id');
                $genres_ids = $this->input->post('genres_id');
                if(!empty($genres_ids)){
                    $this->db->where('category_id', $category_id);
                    // $this->db->where_not_in(['genres_id' => $genres_ids]);
                    // $data = $this->db->delete('gener_catgegory_relation');
                    foreach($genres_ids as $genres_id){
                        $arr = ['genres_id' => $genres_id, 'category_id' => $category_id];
                        $count = $this->db_read->get_where('gener_catgegory_relation', $arr)->num_rows();
                        if(empty($count)){
                            $this->db->insert('gener_catgegory_relation', $arr);
                        }
                    }
                }
                backend_log_genration($this,"Category Map has been created by User(ID : {$this->session->userdata('active_backend_user_id')}).","Map Category");                             
                page_alert_box('success', 'Added', 'Category Mapped With Genre successfully');
                redirect($_SERVER['HTTP_REFERER']);
             }
        }
        $view_data['page'] = 'map_category';
        $this->db_read->where('status', 0);
        $view_data['categories'] = $this->db_read->get('categories')->result_array();
        $view_data['genres'] = $this->Category_model->get_generes();
        $data['page_data'] = $this->load->view('category/map_categoryy', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function edit_map_category($category_id){
        if ($this->input->post()) {
            $this->form_validation->set_rules('category_id', 'Category', 'required');
            $this->form_validation->set_rules('genres_id[]', 'Genres', 'required');
            if ($this->form_validation->run() == FALSE) {
            } else {
                $input = $this->input->post();
                $category_id = $this->input->post('category_id');
                $genres_ids = $this->input->post('genres_id');

                if(!empty($genres_ids)){
                    $this->db->where('category_id', $category_id);                    
                    $data = $this->db->delete('gener_catgegory_relation');
                    foreach($genres_ids as $genres_id){
                        $arr = ['genres_id' => $genres_id, 'category_id' => $category_id];
                        $count = $this->db_read->get_where('gener_catgegory_relation', $arr)->num_rows();
                        // if(empty($count)){   
                            $this->db->insert('gener_catgegory_relation', $arr);
                        // }
                    }                    
                }
                backend_log_genration($this,"Category Map has been changed by User(ID : {$this->session->userdata('active_backend_user_id')}).","Map Category");                             
                page_alert_box('success', 'Added', 'Data Mapped successfully');
                redirect($_SERVER['HTTP_REFERER']);
             }
        }
        $view_data['page'] = 'edit_map_category';
        $this->db_read->where('status', 0);
        $view_data['categories'] = $this->db_read->get('categories')->result_array();
        $view_data['genres'] = $this->Category_model->get_generes();
        $view_data['get_category_geners'] = $this->Category_model->get_gener_catgegory_relation_by_category_id($category_id);                        
        $data['page_data'] = $this->load->view('category/edit_map_category', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function get_categorywise_geners($id=null){
        $cate_id = $id;
        $cate = $this->Category_model->get_category_geners($cate_id);
        echo json_encode($cate);
    }

    public function add_category() {
        if ($this->input->post()) {
             $this->form_validation->set_rules('title', 'Title', 'required|is_unique_with_status[categories.title]');
             $this->form_validation->set_rules('status', 'Status', 'required|in_list[0,1]');
            $category_new = $_POST['title'];
            if ($this->form_validation->run() == FALSE) {                
            } else {
                $insert_data = array(
                    'title' => $this->input->post('title'),
                    'lang_id' => LANG_ID ?? 1,
                    'status' => $this->input->post('status') ?? 0,
                    'created_at' => time(),
                    'modified_at' => time(),
                    'created_by' => $this->session->userdata('active_backend_user_id')
                );
                $id = $this->Category_model->add_category($insert_data);
                backend_log_genration($this,"Category {$this->input->post('title')} has been created by User(ID : {$this->session->userdata('active_backend_user_id')}).","Category");                             
                page_alert_box('success', 'Added', 'New Category added successfully');
                redirect($_SERVER['HTTP_REFERER']);
            }
        }     
        $view_data['page'] = 'add_category';
        $data['page_data'] = $this->load->view('category/add_categoryy', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    // public function edit_category($id) {
    //     if ($this->input->post()) {
    //         $this->form_validation->set_rules('title', 'Category Name', 'required|edit_unique[categories.title, $id]');
    //         if ($this->form_validation->run() == FALSE) {
                
    //         } else {
    //             $update_data = array(
    //                 'id' => $this->input->post('id'),
    //                 'title' => $this->input->post('title'),
    //                 'modified_at' => time(),
    //                 'created_by' => $this->session->userdata('active_backend_user_id')
    //             );
    //             $this->db->where('id',$id)->update('category',$update_data);
    //             backend_log_genration($this,"Category {$this->input->post('title')} has been changed by User(ID : {$this->session->userdata('active_backend_user_id')}).","Category");                
    //             page_alert_box('success', 'Updated', 'Category has been updated successfully');
    //             redirect($_SERVER['HTTP_REFERER']);
    //         }
    //     }
    //     $view_data['category'] = $this->Category_model->get_category_by_id($id);
    //     $view_data['categories'] = $this->Category_model->get_categories_by_id($id);
    //     $view_data['genres'] = $this->Category_model->get_generes();
    //     $view_data['id'] = $id;
    //     $view_data['page'] = 'edit_category';
    //     $data['page_data'] = $this->load->view('category/edit_category', $view_data, TRUE);
    //     echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    // }
    // Geetesh code start---------------
    public function delete_category($id){
        $this->db_read->where('status !=', 2);
        $this->db_read->where('id',$id);
        $this->db_read->where(get_language());
        $this->db_read->limit(1);
        $view_data['category'] = $this->db_read->get('categories')->num_rows();
        if(empty($view_data['category'])){
            page_alert_box('error', 'Error', 'Category Id is missing!!..');
            redirect(base_url() . 'admin-panel/add-category');
        }
        $this->db_read->where('status !=', 2);
        // $this->db_read->where(get_language());
        $this->db_read->where('category_id',$id);
        $this->db_read->limit(1);
        $shows = $this->db_read->get('shows')->num_rows();
        if(!empty($shows)){
            page_alert_box('error', 'Error', 'Please delete atteched shows first!!..');
            redirect(base_url() . 'admin-panel/add-category');
        }
        backend_log_genration($this,"Category ID {$id} has been deleted by User(ID : {$this->session->userdata('active_backend_user_id')}).","Category");                
        $this->db->update('categories',['status' => 2, 'modified_at' => time()], ['id' => $id]);
        page_alert_box('success', 'Updated', 'Category has been Deleted successfully');
        redirect(base_url() . 'admin-panel/add-category');
    }

    public function edit_cate($id){
        $this->db_read->select('*');
        $this->db_read->where('id',$id);
        $this->db_read->where(get_language());
        $view_data['category'] = $this->db_read->get('categories')->row_array();
        // echo $this->db_read->last_query();die;
        if(empty($view_data['category'])){
            page_alert_box('error', 'Error', 'Category Id is missing!!..');
            redirect(base_url() . 'admin-panel/add-category');
        }
        if($this->input->post()){
            $this->form_validation->set_rules('title', 'Title', 'required|edit_unique_with_status[categories.title.' . $id . ']');
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
                $this->db->update('categories',$update_data, ['id' => $id]);
                backend_log_genration($this,"Category {$this->input->post('title')} has been changed by User(ID : {$this->session->userdata('active_backend_user_id')}).","Category");                             
                page_alert_box('success', 'Updated', 'Category has been updated successfully');
                redirect(base_url() . 'admin-panel/add-category');
            }
        }
        
       $view_data['page'] = 'edit_category';
       $data['page_data'] = $this->load->view('category/edit_cat', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    // public function delete_cate($id){
    //     $this->db->where('id', $id);
    //     $this->db->delete('categories');
    //     backend_log_genration($this,"Category {$this->input->post('title')} has been deleted by User(ID : {$this->session->userdata('active_backend_user_id')}).","Category");                             
    //     page_alert_box('success', 'Category Deleted', 'Sub Category has been deleted successfully');
    //     redirect(base_url() . 'admin-panel/add-category');
    // }


    public function ajax_category_list() {
        $requestData = $_REQUEST;
  
  $columns = array(
      0 => 'id',
      1 => 'cat_name',
      5 => 'genres',
      6 => 'type_id',
      2 => 'creation_time',
      3 => 'modified_time',
      4 => 'status',
  );
  $this->db_read->select('count(id) as total');
  $this->db_read->from('category as c');
  $this->db_read->where('status !=', 2);
  $totalData = $this->db_read->get()->row()->total;
  $totalFiltered = $totalData;

  $this->db->select('category.id, cat_name, category.type_id, category.creation_time, category.modified_time, genres, type_id, status');

 if (!empty($requestData['columns'][0]['search']['value'])) {
      $this->db_read->where('id', $requestData['columns'][0]['search']['value']);
  }

  if ($text = $requestData['columns'][1]['search']['value']) {
      $this->db_read->like('cat_name', $text);
  }
  if(isset($requestData['start'])){
      $this->db_read->order_by($columns[$requestData['order'][0]['column']], 'DESC');
      $this->db_read->limit($requestData['length'], $requestData['start']);
  } 

 $this->db->from('category');
 $this->db->join('categories as cate', 'cate.id = category.cate_id');
 $this->db->where('status !=', 2);

 $result = $this->db->get()->result();
 
  $data = array();
  foreach ($result as $r) {
     $nestedData[] = $categoryy;
     $nestedData[] = $r->creation_time ? get_date_format($r->creation_time) : "--NA--";
     $nestedData[] = $r->modified_time ? get_date_format($r->modified_time) : "--NA--";
     $nestedData[] = "<a class='btn-xs bold btn btn-primary' href='" . base_url('auth_panel/category/category/map_edit/') . $r->id . "'><i class='fa fa-edit'></i></a>&nbsp;
      ";
      $data[] = $nestedData;
  }
  $json_data = array(
      "draw" => intval($requestData['draw']), 
      "recordsTotal" => intval($totalData), 
      "recordsFiltered" => intval($totalFiltered), 
      "data" => $data, 
  );
  echo json_encode($json_data); 
}


/*--------------Category Listing Ajax Call  Start---------------------*/
public function ajax_category() {
  $requestData = $_REQUEST;
  $where = '';
  
  $columns = array(
      0 => 'id',
      1 => 'title',
      2 => 'status',
      3 => 'created_at'
  );
  
  $this->db_read->select('count(id) as total');
  $this->db_read->from('categories as c');
  $this->db_read->where('status !=', 2);
  $totalData = $this->db_read->get()->row()->total;
  $totalFiltered = $totalData;

  $this->db_read->select('id, lang_id, title, created_at, modified_at, status');

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
  $this->db_read->from('categories as c');
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
          <li><a  href='" . base_url('auth_panel/category/category/edit_cate/') . $r->id . "'>Edit</a></li>
          <li><a  onclick=\"return confirm('Are You Sure You Want To Delete?')\" href='" . base_url('auth_panel/category/category/delete_category/') . $r->id . "'>Delete</a></li>
      </ul>
      </div>";    

      $data[] = $nestedData;
  }
  $json_data = array(
      "draw" => intval($requestData['draw']), 
      "recordsTotal" => intval($totalData),
      "recordsFiltered" => intval($totalFiltered), 
      "data" => $data, 
  );
  echo json_encode($json_data);  
}
/*--------------------Category Listing Ajax Call  End------------------------*/

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ Mapping List ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ 
public function ajax_map_category_list() {
  
  $requestData = $_REQUEST;
 
  $columns = array(
      0 => 'id',
      1 => 'title',
      5 => 'genres',
      6 => 'type_id',
      2 => 'creation_time',
      3 => 'modified_time',
      4 => 'status',
  );

  $this->db->select('COUNT(c.id) as total');
  $this->db->from('gener_catgegory_relation gcr');
  $this->db->join('categories c', 'gcr.category_id = c.id');
  $this->db->join('genres g', 'g.id = gcr.genres_id');
  $this->db->where('c.status', 0);
  $this->db->where('g.status', 0);
  $this->db->group_by('c.id');
  $totalData = $this->db->get()->num_rows();
  $totalData = ($totalData) ? $totalData : 0;
  $totalFiltered = $totalData;

  $this->db_read->select('c.title, GROUP_CONCAT(g.title) as genres_title, c.id');
  
  if ($text = $requestData['columns'][1]['search']['value']) {
      $this->db_read->like('c.title', $text);
  }
  
  if(isset($requestData['start'])){
      $this->db_read->order_by($columns[$requestData['order'][0]['column']], 'DESC');
      $this->db_read->limit($requestData['length'], $requestData['start']);
       } 
       

  $this->db_read->from('gener_catgegory_relation gcr');
  $this->db_read->join('categories c', 'gcr.category_id = c.id');
  $this->db_read->join('genres g', 'g.id = gcr.genres_id');
  $this->db_read->where('c.status', 0);
  $this->db_read->where('g.status', 0);
  $this->db_read->group_by('c.id');

  $result = $this->db_read->get()->result();
   
  $data = array();
  
  foreach ($result as $r) {
      $nestedData = array();
      $nestedData[] = ++$requestData['start'];
      $nestedData[] = ucfirst($r->title);
      $nestedData[] = $r->genres_title;            
      $nestedData[] = "<div class='dropdown toggle_menus_icons'> <button class='btn dropdown_ttgl text-white dropdown-toggle ' type='button' data-toggle='dropdown'><i class='fa fa-ellipsis-v' aria-hidden='true'></i></button>
      <ul class='dropdown-menu'>               
          <li><a href='" . base_url('auth_panel/category/category/edit_map_category/') . $r->id . "'>Edit</a></li>
          <li><a onclick=\"return confirm('Are you sure you want to delete?')\" href='" . base_url('auth_panel/category/category/delete_map_category/') . $r->id . "'>Delete</a></li>
      </ul>
      </div>";

      $data[] = $nestedData;
  }
  $json_data = array(
      "draw" => intval($requestData['draw']), 
      "recordsTotal" => intval($totalData),
      "recordsFiltered" => intval($totalFiltered),
      "data" => $data, 
  );
  echo json_encode($json_data); 
}
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ Mapping List ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ Delete Map Category ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    public function delete_map_category($category_id){
        $this->db->where('category_id', $category_id);
        $data = $this->db->delete('gener_catgegory_relation');
        backend_log_genration($this,"Category Map has been deleted by User(ID : {$this->session->userdata('active_backend_user_id')}).","Map Category");                             
        page_alert_box('success', 'Added', 'map category deleted successfully');
        redirect($_SERVER['HTTP_REFERER']);
    }
    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ Delete Map Category ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~



}

