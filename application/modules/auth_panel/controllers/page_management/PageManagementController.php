<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class PageManagementController extends MX_Controller {

    protected $redis_magic;

    function __construct() {
        parent::__construct();
        /* !!!!!! Warning !!!!!!!11
         *  admin panel initialization
         *  do not over-right or remove auth_panel/auth_panel_ini/auth_ini
         */
        modules::run('auth_panel/auth_panel_ini/auth_ini');
        $this->load->library('form_validation');
        $this->load->library("security");                        
    }
  
    function page_management() {   
        if ($this->input->post()) {
            $this->form_validation->set_rules('title', 'page name', 'required|is_unique_with_status[pages.title]');
            $this->form_validation->set_rules('status', 'status', 'required');
            $this->form_validation->set_rules('description', 'description', 'required');        
           if ($this->form_validation->run() == FALSE) {                
           } else {
               $insert_data = array(    
                   'lang_id' => $this->input->post('lang_id') ?? 1,
                   'title' => $this->input->post('title'),
                   'link' => $this->input->post('link'),
                   'description' => $this->input->post('description'),
                   'status' => $this->input->post('status') ?? 0,
                   'created_at' => time(),
                   'modified_at' => time(),
                   'created_by' => $this->session->userdata('active_backend_user_id')
               );
               $this->db->insert('pages', $insert_data);
               backend_log_genration($this,"Website Page {$this->input->post('title')} has been created by User(ID : {$this->session->userdata('active_backend_user_id')}).","Website Page");
               page_alert_box('success', 'Added', 'New Page added successfully');
               redirect($_SERVER['HTTP_REFERER']);
           }
       }      
       $view_data['page'] = 'webite_pages';
       $data['page_data'] = $this->load->view('pageManagement/page_management', $view_data, TRUE);
       echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);        
    }

    public function ajax_page_list() {
        $output_csv = $output_pdf = false;
        $requestData = $_REQUEST;
          
        $columns = array(
            0 => 'id',
            1 => 'title',                        
            2 => 'status',
            3 => 'created_at',
            4 => 'modified_at',
        );

        $this->db_read->select('COUNT(id) as total');
        $this->db_read->from('pages');
        $this->db_read->where('status !=', 2);
        $query = $this->db_read->get();
        $result = $query->row_array();
        $totalData = (count($result) > 0) ? $result['total'] : 0;

        $totalFiltered = $totalData;
        $this->db_read->select('id,title,created_at,status,modified_at');
                
                if ($title = $requestData['columns'][1]['search']['value']) {   
                    $this->db_read->like('title', $title);
                }
                if (isset($requestData['columns'][2]['search']['value']) && $requestData['columns'][3]['search']['value'] != "") {
                    $this->db_read->where('status', $requestData['columns'][2]['search']['value']);
                }        

                if(isset($requestData['start'])){
                    $this->db_read->order_by($columns[$requestData['order'][0]['column']], 'DESC');
                    $this->db_read->limit($requestData['length'], $requestData['start']);
                    }
        $this->db_read->from('pages');    
        $query = $this->db_read->get();
        $result = $query->result();
        $data = array();
        $id = 0;
        foreach ($result as $r) {
            $nestedData = array();
            $nestedData[] = ++$id;
            $nestedData[] = $r->title;            
            $nestedData[] = ($r->status == 0 ) ? 'Enabled' : 'Disabled';                
            $nestedData[] = $r->created_at ? get_date_format($r->created_at) : "--NA--";
            $nestedData[] = $r->created_at ? get_date_format($r->modified_at) : "--NA--";
            $action_btn = $r->status == 1 ? "Enable" : "Disable";

            $nestedData[] = "<div class='dropdown toggle_menus_icons'> <button class='btn dropdown_ttgl text-white dropdown-toggle ' type='button' data-toggle='dropdown'><i class='fa fa-ellipsis-v' aria-hidden='true'></i></button>
            <ul class='dropdown-menu'>               
                <li><a title='Edit' onclick=\"return confirm('Are you sure you want to Edit?')\" href='" . AUTH_PANEL_URL . "page_management/PageManagementController/edit_page/" . $r->id . "'>Edit</a></li>
                <li><a title='Delete' onclick=\"return confirm('Are you sure you want to delete?')\" href='" . AUTH_PANEL_URL . "page_management/PageManagementController/delete_page/" . $r->id . "'>Delete</a></li>
                <li><a title='Enabled/Disabled' href='" . AUTH_PANEL_URL . "page_management/PageManagementController/update_page_status/" . $r->id ."/".$r->status."'>".$action_btn."</a></li>
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

     
    public function delete_page($id) {
        $this->db->where('id', $id);
        $data['status'] = '2';
        $this->db->update('pages', $data);  
        backend_log_genration($this,"Website Page ID {$id} deleted has been deleted by User(ID : {$this->session->userdata('active_backend_user_id')}).","Website Page");
        page_alert_box('success', 'Page Deleted', 'Page has been deleted successfully');
        redirect(base_url('admin-panel/add-page'));        
    }

    public function edit_page($id) {
        if ($this->input->post()) {
            $this->form_validation->set_rules('title', 'Title', 'required');
           $category_new = $_POST['title'];           
            $sql = "select title from seasons where title = '$category_new'";
            $query = $this->db->query($sql);
            $checkrows=$query->num_rows();  

           if ($this->form_validation->run() == TRUE) {                                                      
                $update_data = array(
                    'lang_id' => $this->input->post('lang_id') ?? 1,
                    'title' => $this->input->post('title'),
                    'link' => $this->input->post('link'),
                    'description' => $this->input->post('description'),
                    'status' => $this->input->post('status') ?? 0,                    
                    'modified_at' => time(),
                    'created_by' => $this->session->userdata('active_backend_user_id')
                ); 
                $this->db->where('id', $id);        
                $this->db->update('pages', $update_data);                                                                      
                backend_log_genration($this,"Website Page {$this->input->post('title')} has been changed by User(ID : {$this->session->userdata('active_backend_user_id')}).","Website Page");
                page_alert_box('success', 'Updated', 'Page updated successfully');
                redirect($_SERVER['HTTP_REFERER']);               
            }
        }            
        $view_data['id'] = $id;        
        $view_data['page_detail'] = $this->db->select('*')->where('id', $id)->get('pages')->row_array();                
        $view_data['page'] = 'edit_page';
        $data['page_data'] = $this->load->view('pageManagement/edit_page_management', $view_data, TRUE);               
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function update_page_status($id,$status) {
        $this->db->where('id', $id);
        $data['status'] = ($status == 0) ? 1 : 0;        
        $result = $this->db->update('pages', $data);        
        page_alert_box('success', 'Page Status', 'Page status changed successfully');
        redirect(base_url('admin-panel/add-page'));        
        
    }
}
