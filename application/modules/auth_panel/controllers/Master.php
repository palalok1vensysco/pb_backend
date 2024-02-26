<?php

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Master extends MX_Controller {
    protected $CHANG_ACCESS_KEY;
    protected $CHANG_BUCKET_KEY;
    protected $CHANG_CLOUDFRONT;
    protected $CHANG_REGION;

    protected $redis_magic;
    public function __construct() {
        parent::__construct();
        /* !!!!!! Warning !!!!!!!11
         *  admin panel initialization
         *  do not over-right or remove auth_panel/auth_panel_ini/auth_ini
         */
        $this->load->helper('aul');
        modules::run('auth_panel/auth_panel_ini/auth_ini');
        /* do not remove helper and grocey_crud
         * It will put you in danger
         */  

        $this->load->helper(['aes', 'aul', 'custom']);
        $this->load->helper(['url']);
        $this->load->library('form_validation', 'uploads');
        $this->load->model("Library_model");

        //$this->redis_magic = new Redis_magic("session");
        
        $this->retrieve_s3crendential();
        $this->redis_magic = new Redis_magic("data");
    }


    private function countPages($path) {
        $pdftext = file_get_contents($path);
        $num = preg_match_all("/\/Page\W/", $pdftext, $dummy);
        return $num;
    }

    private function retrieve_s3crendential() {
        $s3details = json_decode(get_db_meta_key($this->db, "s3bucket_detail"), "");
       // print_r($s3details) ;die;
        if ($s3details) {
            $this->CHANG_ACCESS_KEY = AMS_S3_KEY;
            $this->CHANG_BUCKET_KEY = AMS_SECRET;
            $this->CHANG_CLOUDFRONT = S3_CLOUDFRONT_DOMAIN;
            $this->CHANG_REGION = AMS_REGION;            
        }
    }

    public function state_management() {
        if ($this->input->post()) {//print_r($_POST); die;			
            $this->form_validation->set_rules('name', 'State Name', 'required|is_unique[states.name]');
            if ($this->form_validation->run() == FALSE) {
                
            } else {
                $insert_data = array(
                    'name' => $this->input->post('name'),
                    'country_id'=>101,
                    'app_id' => ((defined("APP_ID") && APP_ID)? "" . APP_ID . "" : "0")
            );
                //$insert_data['status'] = 1;
                //$insert_data['creation'] = time();
                $this->db->insert('states', $insert_data);
                //backend_log_genration('State Added', 'STATE MANAGEMENT', $_POST);

                page_alert_box('success', 'State Added', 'State has been added successfully');
                redirect($_SERVER["HTTP_REFERER"]);
            }
        }
        $view_data['page'] = "state_management";
        $view_data['breadcrum'] = array('State Management' => '#');
        $data['page_data'] = $this->load->view('master/state/add_state', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function disable_state($subject_id) {
        app_permission("app_id",$this->db);
        $this->db->where('id', $subject_id);
        $this->db->update('states', array('status' => 1));
//        backend_log_genration('State Disabled', 'STATE MANAGEMENT', array('id' => $subject_id));

//        $this->db->where('division_master_id', $subject_id);
//        $this->db->update('district_master', array('status' => 1));
//
//        $this->db->where('division_master_id', $subject_id);
//        $this->db->update('school_master', array('status' => 1));
//
//        $this->db->where('division_master_id', $subject_id);
//        $this->db->update('users', ['registration_step' => 3, 'is_registration_complete' => 2]);

        page_alert_box('success', 'State Disabled', 'State has been disabled successfully');
        redirect($_SERVER["HTTP_REFERER"]);
    }

    public function delete_state($subject_id) {
        app_permission("app_id",$this->db);
        $this->db->where('id', $subject_id);
        $this->db->delete('states');
        //$this->chain_delete_1($subject_id);
        //backend_log_genration('Division Deleted', 'STATE MANAGEMENT', array('id' => $subject_id));

        page_alert_box('success', 'State Deleted', 'State has been deleted successfully');
        redirect($_SERVER["HTTP_REFERER"]);
    }

    private function chain_delete_1($division_id) {
        if ($division_id) {
            app_permission("app_id",$this->db);
            $this->db->where('division_master_id', $division_id);
            $this->db->delete('district_master');

            $this->db->where('division_master_id', $division_id);
            $this->db->delete('school_master');

            $this->db->where('division_master_id', $division_id);
            $this->db->update('users', ['registration_step' => 3, 'is_registration_complete' => 2]);
        }
        return true;
    }

    public function enable_state($subject_id) {
        app_permission("app_id",$this->db);
        $this->db->where('id', $subject_id);
        $this->db->update('states', array('status' => 0));
        backend_log_genration('State Enabled', 'STATE MANAGEMENT', array('id' => $subject_id));

        page_alert_box('success', 'State Enabled', 'State has been enabled successfully');
        redirect($_SERVER["HTTP_REFERER"]);
    }

    public function ajax_state_list() {
        // if($this->session->active_user_data->id == "" || $this->session->active_user_data->username == "" || $this->session->active_user_data->password == "" || $this->session->__ci_last_regenerate == "")
        // {

        //     echo json_encode(['status'=>false,'message'=>'you are not authenticated']);
        //     exit();
        // }
        // $admin = $this->db->select('*')->from('backend_user')->where('email',$this->session->active_user_data->email)->where('password',$this->session->active_user_data->password)->get()->result_array();

        // if(empty($admin))
        // {
        //      echo json_encode(['status'=>false,'message'=>'you are not authenticated']);
        //         exit();
        // }

        // storing  request (ie, get/post) global array to a variable
        $requestData = $_REQUEST;
        $columns = array(
            // datatable column index  => database column name
            0 => 'id',
            1 => 'id',
            2 => 'name',
//            3 => 'status',
            
        );
        $where = "country_id = 101";
        $query = "SELECT count(id) as total FROM states where $where";
        $query .= app_permission("app_id");
        $query = $this->db->query($query);
        $query = $query->row_array();
        $totalData = (count($query) > 0) ? $query['total'] : 0;
        $totalFiltered = $totalData;

        $sql = "SELECT * FROM states where $where";
         $sql .= app_permission("app_id");
        // getting records as per search parameters
        if (!empty($requestData['columns'][0]['search']['value'])) {   //name
            $sql .= " AND id LIKE '" . $requestData['columns'][0]['search']['value'] . "%' ";
        }
        if (!empty($requestData['columns'][1]['search']['value'])) {  //salary
            $sql .= " AND name LIKE '%" . $requestData['columns'][1]['search']['value'] . "%' ";
        }        
        $query = $this->db->query($sql)->result();        
        $totalFiltered = count($query); // when there is a search parameter then we have to modify total number filtered rows as per search result.
        
        $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";  // adding length
        $result = $this->db->query($sql)->result(); 
        //echo $this->db->last_query();die;
        $data = array();
        foreach ($result as $r) {  // preparing an array
            //echo "<pre>";print_r($result);die;
            $nestedData = array();
            $nestedData[] = '<input type="checkbox" name="check_id" class="check_id" value="' . $r->id . '">';
            $nestedData[] = $r->id;
            $nestedData[] = $r->name;
            //$nestedData[] = ($r->status == 1 ) ? '<b><i class="text-danger">Disabled</i></b>' : '<b><i class="text-success">Enabled</i></b>';
            //$nestedData[] = '<img width="30" height="30" style="background:' . $r->color_code . '" src="' . $r->image . '">';
            //$nestedData[] = date('d-M-Y', $r->creation);
            //$nestedData[] = $r->updated;
//            if ($r->status == 0) {
//                $control = "<a  onclick=\"return confirm('Warning !!!!  Do you really want to disable?');\" class=' btn-xs bold btn btn-danger' href='" . AUTH_PANEL_URL . "school/disable_division/" . $r->id . "'>Disable</a>";
//            } else {
//                $control = "<a  onclick=\"return confirm('Warning !!!!  Do you really want to enable?');\" class=' btn-xs bold btn btn-warning' href='" . AUTH_PANEL_URL . "school/enable_division/" . $r->id . "'> Enable</a>";
//            }
            $control = "&nbsp;<a  onclick=\"return confirm('Warning !!!!  Do you really want to delete?');\" class=' btn-xs bold btn btn-danger' href='" . AUTH_PANEL_URL . "master/delete_state/" . $r->id . "'> Delete</a>";
            $nestedData[] = "<a class='btn-xs bold btn btn-info' href='" . AUTH_PANEL_URL . "master/edit_state/" . $r->id . "'><i class='fa fa-pencil'></i>&nbsp Edit</a>&nbsp;$control";
            $data[] = $nestedData;
        }

        $json_data = array(
            "draw" => intval($requestData['draw']), // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they 			first check the draw number, so we are sending same number in draw.
            "recordsTotal" => intval($totalData), // total number of records
            "recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
            "data" => $data   // total data array
        );

        echo json_encode($json_data);  // send data as json format
    }

    public function edit_state($id) {
        if ($this->input->post()) {

            $this->form_validation->set_message('edit_unique', 'Sorry, This state name already exist.');
            $this->form_validation->set_rules('name', 'State Name', 'trim|required|edit_unique[states.name.' . $id . ']');
            
            if ($this->form_validation->run() == TRUE) {
                $update_data = array(
                    'name' => $this->input->post('name'),
                );

                $this->db->where('id', $id);
                $this->db->update('states', $update_data);
                //backend_log_genration('State Updated', 'STATE MANAGEMENT', array('id' => $id, 'postdata' => $_POST));

                page_alert_box('success', 'State Updated', 'State has been updated successfully');
                redirect(AUTH_PANEL_URL . 'master/state_management');
            }
        }
        $view_data['page'] = 'edit_state';
        $data['page_title'] = "Edit State";

        $this->db->where('id', $id);
        app_permission("app_id",$this->db);
        $view_data['subject'] = $this->db->get('states')->row_array();
        $view_data['page'] = "state_management";
        $view_data['breadcrum'] = array("State Management"=>"master/state_management", $view_data['subject']['name'] =>'#');
        $data['page_data'] = $this->load->view('master/state/edit_state', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }  
    

    function city_management($id = null) {
        $input = $this->input->post();
        //pre($input); die;
        if ($id && !$input) {
            app_permission("app_id",$this->db);
            $this->db->where('id', $id);
            $view_data['result'] = $this->db->get('cities')->row_array();
        } else if ($input) {
            if (array_key_exists('id', $input)) {
                $update_data = array(
                    'state_id' => $this->input->post('division_master_id'),
                    'name' => $this->input->post('name'),
                );
                $this->db->where('id', $id);
                $this->db->update('cities', $update_data);
                page_alert_box('success', 'City Updated', 'City has been updated successfully');
            } else {
                $this->db->where('state_id', $this->input->post('division_master_id'));
                $this->db->where('name', $this->input->post('name'));
                if (defined("APP_ID"))
                //$this->db->where("app_id",APP_ID);
                app_permission("app_id",$this->db);
                $get_dist = $this->db->get('cities')->row_array();
                if ($get_dist) {
                    page_alert_box('error', 'City could not be added', 'This City already exist');
                } else {
                    $insert_data = array(
                        'state_id' => $this->input->post('division_master_id'),
                        'name' => $this->input->post('name'),
                        'app_id' => ((defined("APP_ID") && APP_ID) ? "" . APP_ID . "" : "0"),
                    );
                    // pre($insert_data);die;
                    $this->db->insert('cities', $insert_data);
                    page_alert_box('success', 'City Added', 'City has been added successfully');
                }
            }
        }

        $view_data['page'] = 'edit_city';
        $data['page_title'] = "City Management";

        $this->db->select('id,name');
        $this->db->order_by('name', 'asc');        
        //$this->db->where('status', 0);
        $this->db->where('country_id', 101);
        app_permission("app_id",$this->db);
        $view_data['data'] = $this->db->get('states')->result_array();       
        $view_data['page'] = "city_management";
        $view_data['breadcrum'] = !empty($id) ? array("City Management"=>"master/city_management", $view_data['result']['name']=>"#") : array("City Management"=> '#');
        $data['page_data'] = $this->load->view('master/district/district_management', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function disable_district($district_id) {
        $this->db->where('id', $district_id);
        app_permission("app_id",$this->db);
        $this->db->update('district_master', array('status' => 1));
        backend_log_genration('District Disabled', 'CATEGORY MANAGEMENT', array('id' => $district_id));

        page_alert_box('success', 'District Disabled', 'District has been disabled successfully');
        redirect($_SERVER["HTTP_REFERER"]);
    }

    public function delete_district($district_id) {
        $this->db->where('id', $district_id);
        app_permission("app_id",$this->db);
        $this->db->delete('cities');
        //$this->chain_delete_2($district_id);
        //backend_log_genration('Cities Deleted', 'CITY MANAGEMENT', array('id' => $district_id));

        page_alert_box('success', 'City Deleted', 'City has been deleted successfully');
        redirect($_SERVER["HTTP_REFERER"]);
    }

    private function chain_delete_2($district_id) {
        if ($district_id) {
            $this->db->where('division_master_id', $district_id);
            $this->db->delete('school_master');

            $this->db->where('division_master_id', $district_id);
            $this->db->update('users', ['registration_step' => 3, 'is_registration_complete' => 2]);
        }
        return true;
    }

    public function enable_district($district_id) {
        $this->db->where('id', $district_id);
        app_permission("app_id",$this->db);
        $this->db->update('district_master', array('status' => 0));
        backend_log_genration('District Enabled', 'CATEGORY MANAGEMENT', array('id' => $district_id));

        page_alert_box('success', 'District Enabled', 'District has been enabled successfully');
        redirect($_SERVER["HTTP_REFERER"]);
    }

    public function ajax_district_list() {
        // storing  request (ie, get/post) global array to a variable
        $requestData = $_REQUEST;
        $columns = array(
            // datatable column index  => database column name
            0 => 'id',
            1 => 'id',
            2 => 'name',
            3 => 'states.name',
        );
        $where = "1=1";
        $query = "SELECT count(cities.id) as total FROM cities 
                  LEFT JOIN states on states.id=cities.state_id
                  WHERE $where AND states.country_id = 101";
        //$query .= (defined("APP_ID") ? "" . app_permission("app_id",$this->db) . "" : "0");
        $query .= app_permission("cities.app_id");
        $query = $this->db->query($query);
        $query = $query->row_array();
        $totalData = (count($query) > 0) ? $query['total'] : 0;
        $totalFiltered = $totalData;

        $sql = "SELECT cities.*,cities.id,states.name as division_name  FROM cities
                        LEFT JOIN states on states.id=cities.state_id
                        WHERE $where AND states.country_id = 101";
        $sql .=  app_permission("cities.app_id");
        // getting records as per search parameters        
        
        if (!empty($requestData['columns'][0]['search']['value'])) {
            $sql .= " AND id LIKE '" . $requestData['columns'][0]['search']['value'] . "%' ";
        }
        if (!empty($requestData['columns'][1]['search']['value'])) {
            $sql .= " AND cities.name LIKE '" . $requestData['columns'][1]['search']['value'] . "%' ";
        }
        if (!empty($requestData['columns'][2]['search']['value'])) {
            $sql .= " AND states.name LIKE '" . $requestData['columns'][2]['search']['value'] . "%' ";
        }
        
        $query = $this->db->query($sql)->result();
        $totalFiltered = count($query); 
        // when there is a search parameter then we have to modify total number filtered rows as per search result.

        $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";
        
        $result = $this->db->query($sql)->result();
        $data = array();
        // pre($result);
        foreach ($result as $r) {  // preparing an array
            $nestedData = array();
            $nestedData[] = '<input type="checkbox" name="check_id" class="check_id" value="' . $r->id . '">';
            $nestedData[] = $r->id;
            $nestedData[] = $r->name;
            $nestedData[] = $r->division_name;            
            $control = "&nbsp;<a  onclick=\"return confirm('Warning !!!!  Do you really want to delete?');\" class=' btn-xs bold btn btn-danger' href='" . AUTH_PANEL_URL . "master/delete_district/" . $r->id . "'> Delete</a>";

            $nestedData[] = "<a class='btn-xs bold btn btn-info' href='" . AUTH_PANEL_URL . "master/city_management/" . $r->id . "'>Edit</a>&nbsp;$control";
            $data[] = $nestedData;
        }
        $json_data = array(
            "draw" => intval($requestData['draw']), // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they 			first check the draw number, so we are sending same number in draw.
            "recordsTotal" => intval($totalData), // total number of records
            "recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
            "data" => $data   // total data array
        );
        echo json_encode($json_data);  // send data as json format
    }
    
    

    function ajax_get_district() {
        $input = $this->input->post();
        if ($input) {
            $this->db->select('id,name');
            $this->db->where('division_master_id', $input['id']);
            $this->db->where('status', 0);
            $this->db->order_by('name', 'asc');
            $district_list = $this->db->get('district_master')->result_array();
            $html = "<option value=''>Select</option>";
            foreach ($district_list as $s) {
                $html .= "<option value='" . $s['id'] . "'>" . $s['name'] . "</option>";
            }
            echo json_encode(array('data' => 1, 'result' => $html));
            die;
        }
        echo json_encode(array('data' => 2));
    }

    function ajax_get_city() {
        $input = $this->input->post();
        if ($input) {
            $this->db->select('id,name');
            $this->db->where('district_master_id', $input['id']);
            $this->db->where('status', 0);
            $this->db->order_by('name', 'asc');
            $district_list = $this->db->get('city_master')->result_array();
            $html = "<option value=''>Select</option>";
            foreach ($district_list as $s) {
                $html .= "<option value='" . $s['id'] . "'>" . $s['name'] . "</option>";
            }
            echo json_encode(array('data' => 1, 'result' => $html));
            die;
        }
        echo json_encode(array('data' => 2));
    }

    public function bulk_delete() {
        //pre($this->input->post()); die;
        if ($this->input->post('school_id_array')) {
            $school_id = explode(',', $this->input->post('school_id_array'));
            $this->db->where_in('id', $school_id);
            $this->db->delete('school_master');
            backend_log_genration('School Deleted', 'SCHOOL MANAGEMENT', array('id' => $school_id));
            $this->db->where_in('school_master_id', $school_id);
            $this->db->update('users', ['registration_step' => 3, 'is_registration_complete' => 2]);
            page_alert_box('success', 'School Deleted', 'School has been deleted successfully');
            redirect($_SERVER["HTTP_REFERER"]);
        } else {
            redirect('auth_panel/admin');
        }
    }

    public function bulk_delete_state() {
        //pre($this->input->post()); die;
        if ($this->input->post('school_id_array')) {
            $div_id = explode(',', $this->input->post('school_id_array'));
            $this->db->where_in('id', $div_id);
            $this->db->delete('states');
//            backend_log_genration('State Deleted', 'STATE MANAGEMENT', array('id' => $this->input->post('school_id_array')));

//            $this->db->where_in('division_master_id', $div_id);
//            $this->db->delete('district_master');
//
//            $this->db->where_in('division_master_id', $div_id);
//            $this->db->delete('school_master');
//
//            $this->db->where_in('division_master_id', $div_id);
//            $this->db->update('users', ['registration_step' => 3, 'is_registration_complete' => 2]);

            page_alert_box('success', 'State Deleted', 'State(s) has been deleted successfully');
            redirect($_SERVER["HTTP_REFERER"]);
        } else {
            redirect('auth_panel/admin');
        }
    }

    public function bulk_delete_district() {
        //pre($this->input->post()); die;
        if ($this->input->post('school_id_array')) {
            $dist_id = explode(',', $this->input->post('school_id_array'));

            $this->db->where_in('id', $dist_id);
            $this->db->delete('cities');
//            backend_log_genration('District Deleted', 'DISTRICT MANAGEMENT', array('id' => $dist_id));
//
//            $this->db->where_in('district_master_id', $dist_id);
//            $this->db->delete('school_master');
//
//            $this->db->where_in('district_master_id', $dist_id);
//            $this->db->update('users', ['registration_step' => 3, 'is_registration_complete' => 2]);

            page_alert_box('success', 'City Deleted', 'City has been deleted successfully');
            redirect($_SERVER["HTTP_REFERER"]);
        } else {
            redirect('auth_panel/admin');
        }
    }
    
    
    
    
    public function college_management() {
        if ($this->input->post()) {//print_r($_POST); die;			
            $this->form_validation->set_rules('name', 'College Name', 'required|is_unique[states.name]');
            if ($this->form_validation->run() == FALSE) {
                
            } else {
                $this->db->select('max(id) as max_id');
                $this->db->order_by('id','desc');
                $this->db->limit(1);
                $c_id = $this->db->get('college_master')->row()->max_id;
                $insert_data = array('id'=>$c_id+1,
                    'name' => $this->input->post('name'),
                    'app_id' => (defined("APP_ID") ? "" . APP_ID . "" : "0"),
                                );
                $this->db->insert('college_master', $insert_data);
                page_alert_box('success', 'College Added', 'College has been added successfully');
                redirect($_SERVER["HTTP_REFERER"]);
            }
        }
        $view_data['page'] = "college_management";
        $data['page_data'] = $this->load->view('master/college/add_college', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function delete_banner($subject_id) {
        $this->db->where('id', $subject_id);
        $this->db->delete('banner_master');
          //--update version start by ak--
            if ($this->db->affected_rows() > 0) {
                update_api_version_new($this->db, 'banner');
             }
         //--update version end-- 
        page_alert_box('success', 'Banner Deleted', 'Banner has been deleted successfully');
        redirect($_SERVER["HTTP_REFERER"]);
    }
  
    
    public function ajax_college_list() {                        
        $requestData = $_REQUEST;
        $columns = array(            
            0 => 'id',
            1 => 'id',
            2 => 'name',            
        );
        $where = "1=1";
        $query = "SELECT count(id) as total FROM college_master where $where";
        // $query .= (defined("APP_ID") ? "" . app_permission("app_id",$this->db) . "" : "0");
        $query .= app_permission("app_id");
        $query = $this->db->query($query);
        $query = $query->row_array();
        $totalData = (count($query) > 0) ? $query['total'] : 0;
        $totalFiltered = $totalData;

        $sql = "SELECT * FROM college_master where $where";
        $sql .= app_permission("app_id");
        // getting records as per search parameters
        if (!empty($requestData['columns'][0]['search']['value'])) {   //name
            $sql .= " AND id LIKE '" . $requestData['columns'][0]['search']['value'] . "%' ";
        }
        if (!empty($requestData['columns'][1]['search']['value'])) {  //salary
            $sql .= " AND name LIKE '%" . $requestData['columns'][1]['search']['value'] . "%' ";
        }

        $query = $this->db->query($sql)->result();

        $totalFiltered = count($query); // when there is a search parameter then we have to modify total number filtered rows as per search result.
        
        $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";  // adding length

        $result = $this->db->query($sql)->result();

        $data = array();

        foreach ($result as $r) {  // preparing an array
            $nestedData = array();
            $nestedData[] = '<input type="checkbox" name="check_id" class="check_id" value="' . $r->id . '">';
            $nestedData[] = $r->id;
            $nestedData[] = $r->name;
            $control = "&nbsp;<a  onclick=\"return confirm('Warning !!!!  Do you really want to delete?');\" class=' btn-xs bold btn btn-danger' href='" . AUTH_PANEL_URL . "master/delete_college/" . $r->id . "'> Delete</a>";
            $nestedData[] = "<a class='btn-xs bold btn btn-info' href='" . AUTH_PANEL_URL . "master/edit_college/" . $r->id . "'><i class='fa fa-pencil'></i>&nbsp Edit</a>&nbsp;$control";
            $data[] = $nestedData;
        }

        $json_data = array(
            "draw" => intval($requestData['draw']), // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they 			first check the draw number, so we are sending same number in draw.
            "recordsTotal" => intval($totalData), // total number of records
            "recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
            "data" => $data   // total data array
        );

        echo json_encode($json_data);  // send data as json format
    }

    public function edit_college($id) {
        if ($this->input->post()) {

            $this->form_validation->set_message('edit_unique', 'Sorry, This College name already exist.');
            $this->form_validation->set_rules('name', 'State Name', 'trim|required|edit_unique[college_master.name.' . $id . ']');
            
            if ($this->form_validation->run() == TRUE) {
                $update_data = array(
                    'name' => $this->input->post('name'),
                );

                $this->db->where('id', $id);
                $this->db->update('college_master', $update_data);
                page_alert_box('success', 'College Updated', 'College has been updated successfully');
                redirect(AUTH_PANEL_URL . 'master/college_management');
            }
        }
        $view_data['page'] = 'edit_college';
        $data['page_title'] = "Edit college";

        $this->db->where('id', $id);
        $view_data['subject'] = $this->db->get('college_master')->row_array();
        $view_data['page'] = "college_management";
        $data['page_data'] = $this->load->view('master/college/edit_college', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }
    
    
   
    public function bulk_delete_college() {
        //pre($this->input->post()); die;
        if ($this->input->post('school_id_array')) {
            $div_id = explode(',', $this->input->post('school_id_array'));
            $this->db->where_in('id', $div_id);
            $this->db->delete('college_master');
            page_alert_box('success', 'College Deleted', 'College(s) has been deleted successfully');
            redirect($_SERVER["HTTP_REFERER"]);
        } else {
            redirect('auth_panel/admin');
        }
    }

      private function is_banner_exists($data){
    //print_r($data);die;
            $app_id = (defined("APP_ID") && APP_ID)?APP_ID:0; 
        $this->db->select('id,banner_title');
        $this->db->where('banner_title', $data);
        // $this->db->where('app_id', $app_id);      
        $app = $this->db->get("banner_master");
        // echo $this->db->last_query();die;
        return ($app->num_rows() > 0 )?true:false;
    }
    
    public function banner_management(){ 
        $view_data =array();
        if($this->input->get("id")){
            $view_data['banner'] = $this->db->get_where("banner_master",array("id"=>$this->input->get("id"), 'status !=' => 2))->row_array();
            // pre($view_data['banner']);die;
            if(empty($view_data['banner'])){
                page_alert_box('error', 'Error', 'banner Id is missing!!..');
                redirect(base_url() . 'auth_panel/master/banner_management');
            }
            $view_data['shows'] = $this->fetch_video_id($view_data['banner']['category_type']);
            // pre($view_data['shows']);die;
        }
        $this->load->model("Backend_user_model");
        if($this->input->post()){
            $this->form_validation->set_rules('banner_type', 'banner type', 'required[in_list[0,1]]');
            $this->form_validation->set_rules('link_type', 'link_type', 'required[in_list[0,1]]');
            if($this->input->post('link_type') == 0){
                $this->form_validation->set_rules('link', 'link', 'required');
            }else{
                $this->form_validation->set_rules('video_id', 'Video Id', 'required');
                $this->form_validation->set_rules('show_id', 'Show Id', 'required');
            }
            $this->form_validation->set_rules('banner_title', 'banner title', 'required');
            if($this->input->get("id")) {
                if($this->input->post('banner_type') == 0){
                   if (empty($_FILES['image']['name'])) {
                        $this->form_validation->set_rules('image', 'Image', 'required');
                    }  
                    if (empty($_FILES['mobile_thumbnail']['name'])) {
                        $this->form_validation->set_rules('mobile_thumbnail', 'mobile thumbnail', 'required');
                    }
                }else{
                    if (empty($_FILES['image_mobile']['name'])) {
                        $this->form_validation->set_rules('image_mobile', 'image mobile', 'required');
                    }
                }
            }
            $this->form_validation->set_rules('status', 'status', 'required[in_list[0,1]]');
            
            if($this->form_validation->run() !== true){
                $error = $this->form_validation->get_all_errors();
                pre($error);die;
                page_alert_box("error", "Add Batch User", array_values($error)[0]);
                redirect_to_back();
            }else{
                  //---check already exist start by ak---
                $inputdata = $this->input->post();
                if ($_FILES && $_FILES['image']['name']) {
                    $image_url = amazon_s3_upload($_FILES['image'], 'banner', time());
                    $banner_url = $image_url=str_replace( 'https://'.$this->CHANG_BUCKET_KEY . ".s3." . $this->CHANG_REGION . ".amazonaws.com",$this->CHANG_CLOUDFRONT, $image_url);
                }
                if ($_FILES && $_FILES['image_mobile']['name']) {
                    $mobile_url = amazon_s3_upload($_FILES['image_mobile'], 'banner', time());
                    $banner_url = $mobile_url=str_replace( 'https://'.$this->CHANG_BUCKET_KEY . ".s3." . $this->CHANG_REGION . ".amazonaws.com",$this->CHANG_CLOUDFRONT, $mobile_url);
                }
                if ($_FILES && $_FILES['mobile_thumbnail']['name']) {
                    $thumbnail_url = amazon_s3_upload($_FILES['mobile_thumbnail'], 'banner', time());
                    $thumbnail_url = $thumbnail_url=str_replace( 'https://'.$this->CHANG_BUCKET_KEY . ".s3." . $this->CHANG_REGION . ".amazonaws.com",$this->CHANG_CLOUDFRONT, $thumbnail_url);
                }
                    $insert_data = array(
                                    'link_type' => $this->input->post('link_type'),
                                    'category_type' => $this->input->post('cate_type'),
                                    'lang_id' => $this->input->post('lang_id') ?? 1,
                                    'show_id' => $this->input->post('video_id')?$this->input->post('video_id'):null,
                                    'banner_type' => $this->input->post('banner_type'),
                                    'video_id' => $this->input->post('show_id')?$this->input->post('show_id'):null,
                                    'hyperlink' => $this->input->post('link'),
                                    'title' => $this->input->post('banner_title'),
                                    'banner_url' => $banner_url,
                                    'banner_thumbnail' => $thumbnail_url,
                                    'description' => $this->input->post('banner_description'),
                                    'location' => $this->input->post('banner_location'),
                                    'status' => $this->input->post('status') ?? 0,
                                    'created_at' => time(),
                                    'modified_at' => time(),
                                    'created_by' => $this->session->userdata('active_backend_user_id')
                               );
                if($this->input->get("id")){
                    unset($insert_data['created_at']);
                    if(empty($insert_data['banner_url'])){
                        $insert_data['banner_url'] = $view_data['banner']['banner_url'];
                    }
                    if(empty($insert_data['banner_thumbnail'])){
                        $insert_data['banner_thumbnail'] = $view_data['banner']['banner_thumbnail'];
                    }
                    backend_log_genration($this,"Banner has been changed by User(ID : {$this->session->userdata('active_backend_user_id')}).","Banner Management");
                    $this->db->update('banner_master', $insert_data, ['id' => $this->input->get("id")]);
                    $result = $this->db->affected_rows();
                }else{
                    backend_log_genration($this,"Banner has been created by User(ID : {$this->session->userdata('active_backend_user_id')}).","Banner Management");
                    $this->db->insert('banner_master', $insert_data);
                    $result = $this->db->insert_id();
                }
                if ($result) {
                    $banner_alt_txt = !empty($this->input->get("id"))?"updated":"added.";                    
                    page_alert_box("success","Banner Management","Banner has been {$banner_alt_txt} successfully.");
                    redirect(AUTH_PANEL_URL . 'master/banner_management');
                }
            }
        }   
        if($this->input->get("id")){
            $view_data['breadcrum'] = array('Banner management' => "master/banner_management", $view_data['banner']['title'] => "#");
        }
        else{
            $view_data['breadcrum'] = array('Banner management' => "#");
        }
        $view_data['categories'] = $this->db->get_where("categories", ['status' => '0'])->result_array();
        $view_data['page'] = "banner_management";
        $view_data['page_title'] = "Banner Management";
        $data['page_data'] = $this->load->view('master/banner/add_banner', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);        
    }
    
    
    public function ajax_banner_list(){
        $requestData = $_REQUEST;
        $columns = array(
            
            0 => 'id',
            1 => 'title',
            2 => 'banner_type',
            5 => 'banner_master.status',
            4 => 'banner_master.created_at'
        );

        $this->db_read->select('COUNT(id) as total');
        $this->db_read->from('banner_master');
        $this->db_read->where('status !=', 2);
        $query = $this->db_read->get();
        $result = $query->row_array();
        $totalData = (count($result) > 0) ? $result['total'] : 0;
        $totalFiltered = $totalData;
        if ($title = $requestData['columns'][1]['search']['value']) {   
            $this->db_read->like('banner_master.title', $title);
        }
        if ($banner_type = $requestData['columns'][2]['search']['value']) {  
            $this->db_read->like('banner_type', $banner_type);
        }
        if (isset($requestData['columns'][3]['search']['value']) && $requestData['columns'][3]['search']['value'] != "") {
            $this->db_read->where('banner_master.status', $requestData['columns'][3]['search']['value']);
        }
        
        $this->db_read->select("banner_master.*,cat.title as cat_name");
        $this->db_read->join('categories cat', 'cat.id = banner_master.category_type and cat.status = 0');
        $this->db_read->where('banner_master.status !=', 2);
        $this->db_read->group_by('banner_master.id');
        $this->db_read->limit($requestData['length'],$requestData['start']);
        $result = $this->db_read->get("banner_master")->result();
        $data = array();
        foreach ($result as $r) {  
            $nestedData = array();
            $nestedData[] = ++$requestData['start'];
            $nestedData[] = $r->title;
            $nestedData[] = $r->cat_name;
            $nestedData[] = '<img src="'.$r->banner_url.'" width="150" >';
            $nestedData[] = ($r->banner_type == 1) ?  "App" : "Web banner";
            $nestedData[] = ($r->status == 0)?'<span class="badge badge-success green_color" >Active</span>':'<span class="badge badge-danger red_color">In-Active</span>';
            $nestedData[] =get_time_format($r->created_at);
            $nestedData[] = get_time_format($r->modified_at);
            $nestedData[] = "<div class='dropdown toggle_menus_icons'> <button class='btn dropdown_ttgl text-white dropdown-toggle ' type='button' data-toggle='dropdown'><i class='fa fa-ellipsis-v' aria-hidden='true'></i></button>
            <ul class='dropdown-menu'>
            <li> <a class='' href='" . AUTH_PANEL_URL . "master/banner_management?id={$r->id}'><i class='fa fa-pencil'></i>&nbsp Edit</a> </li>
            <li><a  onclick=\"return confirm('Warning !!!!  Do you really want to delete?');\" class=' ' href='" . AUTH_PANEL_URL . "master/delete_banner/" . $r->id . "'><i class='fa fa-trash' aria-hidden='true'></i>&nbsp Delete </a></li>
            </ul>
            </div>";
            $data[] = $nestedData;

        }

        $json_data = array(
            "draw" => intval($requestData['draw']), // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they             first check the draw number, so we are sending same number in draw.
            "recordsTotal" => intval($totalData), // total number of records
            "recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
            "data" => $data   // total data array
        );
        echo json_encode($json_data);  // send data as json format
    }

    public function refresh_banner(){
       // pre("function me aya ");die;
        $return = array();
        if($this->input->post('app_id')){
           // update_api_version($this->db,9);
            $return = array("status"=>true);
        } else 
            $return = array("status"=>false);
        echo json_encode($return); die;
    }

    public function fetch_video_id($type_id = null){ 
        if(!empty($type_id)){
            $input['type_id'] = $type_id;
        }else{
            $input = $this->input->post();
        }
        if(!empty($input['type_id'])){
            $this->db->select('id,title');
            $this->db->where('category_id', $input['type_id']);
            $this->db->where('status',0);
            $video_id = $this->db->get('shows')->result_array();
            // echo $this->db->last_query();die;
            if(!empty($type_id)){
                return $video_id;
            }else{
                echo json_encode($video_id);
            }
        }
    }
    public function fetch_show_by_video_id(){ 
        $input = $this->input->post();
        if(!empty($input)){
            $this->db->select('id,title');
            $this->db->where('show_id', $input['show_id']);
            // $this->db->where('status',0);
            $media_library = $this->db->get('media_library')->result_array();
            echo json_encode($media_library);
        }
    }

    public function user_coments(){

       // $this->ajax_comment_list();
       

         $app_id = (defined("APP_ID") && APP_ID)?APP_ID:0; 
        $this->db->select('id,reaction,user_coment');
       
        // $this->db->where('app_id', $app_id);   
        $data= $this->db->get('user_coments')->result_array();
        $view_data['page'] = "Coment_Section";
        $view_data['page_title'] = "Coment Section";       
        $data['page_data'] = $this->load->view('master/user_coments', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }


    public function ajax_comment_list(){

        // storing  request (ie, get/post) global array to a variable
        $requestData = $_REQUEST;
        // pre($requestData);die;
        $columns = array(
            // datatable column index  => database column name
            0 => 'id',
            1 => 'id',
            2 => 'name',

            
        );
        $where = "1=1";
        $query = "SELECT count(id) as total FROM user_coments where $where";
      
        $query .= app_permission("app_id");
        $query = $this->db->query($query);
        $query = $query->row_array();
        $totalData = (count($query) > 0) ? $query['total'] : 0;
        $totalFiltered = $totalData;

        $sql="SELECT user_coments.reaction,user_coments.id, user_coments.user_coment,user_coments.active_status,course_topic_file_meta_master.title as movie_name  FROM course_topic_file_meta_master
                         JOIN user_coments on user_coments.movie_id=course_topic_file_meta_master.id
                         ";


        //$sql = "SELECT * FROM user_coments where $where";
        $sql .= app_permission("user_coments.app_id");
        // getting records as per search parameters
        if (!empty($requestData['columns'][0]['search']['value'])) {   //name
            $sql .= " AND id LIKE '" . $requestData['columns'][0]['search']['value'] . "%' ";
        }
        if (!empty($requestData['columns'][1]['search']['value'])) {  //salary
            $sql .= " AND user_coments.reaction LIKE '%" . $requestData['columns'][1]['search']['value'] . "%' ";
        }
        if (!empty($requestData['columns'][2]['search']['value'])) {  //salary
            $sql .= " AND  course_topic_file_meta_master.title LIKE '%" . $requestData['columns'][2]['search']['value'] . "%' ";
        }

        $query = $this->db->query($sql)->result();
       // pre($query);die;

        $totalFiltered = count($query); 
        
       

        $result = $this->db->query($sql)->result();
        

        $data = array();

        foreach ($result as $r) {  // preparing an array
            $nestedData = array();
            $nestedData[] = $r->id;
            $nestedData[] = $r->reaction;
             $nestedData[] = $r->movie_name;
            $nestedData[] = $r->user_coment;
             $nestedData[] = ($r->active_status == 1) ? '<span class="badge badge-success green_color" >Enabled</span>': (($r->active_status == 2)  ? '<span class="badge badge-danger red_color" >Disabled</span>' : '<span class="badge badge-warning orange_color" >Pending</span>');

            $control = "<div class='dropdown toggle_menus_icons'> <button class='btn dropdown_ttgl text-white dropdown-toggle ' type='button' data-toggle='dropdown'><i class='fa fa-ellipsis-v' aria-hidden='true'></i></button>
            <ul class='dropdown-menu'>
            <li> <a class='' onclick=\"return confirm('Warning !!!!  Do you really want to Enable Comment?');\" href='" . AUTH_PANEL_URL . "master/approve_comment?id={$r->id}'><i class='fa fa-pencil'></i>&nbsp Enable</a> </li>
            <li><a  onclick=\"return confirm('Warning !!!!  Do you really want to Disable Comment?');\" class=' ' href='" . AUTH_PANEL_URL . "master/disapprove_comment/" . $r->id . "'><i class='fa fa-trash' aria-hidden='true'></i>&nbsp Disable </a></li>
            </ul>
            </div>";
            $nestedData[] = $control;
             $data[] = $nestedData;
            
        }

        $json_data = array(
            "draw" => intval($requestData['draw']), 
            "recordsTotal" => intval($totalData), // total number of records
            "recordsFiltered" => intval($totalFiltered), 
            "data" => $data   // total data array
        );
        //print_r($json_data);
        echo json_encode($json_data);  // send data as json format
    }

        public function approve_comment() {
        $this->db->where('id', $_GET['id']);
         $this->db->SET('active_status', 1);
        $this->db->update('user_coments');
          //--update version start by ak--
            if ($this->db->affected_rows() > 0) {
                update_api_version_new($this->db, 'user_coments');
             }
         //--update version end-- 
        page_alert_box('success', 'Comment Approved', 'Comment has been Approved successfully');
        redirect($_SERVER["HTTP_REFERER"]);
    }

    public function disapprove_comment($id) {
        $this->db->where('id', $id);
         $this->db->SET('active_status', 2);
        $this->db->update('user_coments');
        
          //--update version start by ak--
            if ($this->db->affected_rows() > 0) {
                update_api_version_new($this->db, 'user_coments');
             }
         //--update version end-- 
        page_alert_box('success', 'Comment Approved', 'Comment has been Disapproved successfully');
        redirect($_SERVER["HTTP_REFERER"]);
    }

}
