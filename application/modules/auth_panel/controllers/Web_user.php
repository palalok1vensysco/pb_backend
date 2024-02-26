<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Web_user extends MX_Controller {

    protected $redis_magic;

    function __construct() {
        parent::__construct();
        /* !!!!!! Warning !!!!!!!11
         *  admin panel initialization
         *  do not over-right or remove auth_panel/auth_panel_ini/auth_ini
         */
        modules::run('auth_panel/auth_panel_ini/auth_ini');
        $this->load->model('web_user_model');
        $this->load->library('form_validation');
        $this->redis_magic = new Redis_magic("data");
        $this->load->helper('jwt_validater_helper');
    }


    public function all_user_list() {
        if ($this->input->get('user') && $this->input->get('user') != "all") {

            ($this->input->get('user') == 'android') ? $view_data['page'] = 'android' : $view_data['page'] = 'ios';
            ($this->input->get('user') == 'instructor') ? $view_data['page'] = 'instructor' : '';
            ($this->input->get('user') == 'expert') ? $view_data['page'] = 'expert' : '';
            ($this->input->get('user') == 'windows') ? $view_data['page'] = 'windows' : '';
            ($this->input->get('user') == 'erp') ? $view_data['page'] = 'erp' : '';
        } else {
            $view_data['page'] = 'all';
        }

        $view_data['breadcrum']=array($view_data['page']=>"#");
        $data['page_data'] = $this->load->view('web_user/all_user', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }


    public function ajax_all_user_list($device_type) {
        $where = '';

        $requestData = $_REQUEST;
        $columns = array(
            0 => 'id',
            1 => 'name',
            2 => 'email',
            3 => 'mobile',
            4 => 'status',
            5 => 'created_at'
           
        );
       
        /* ------------------------------------------ */
        if ($this->input->get('period') != "") {
            $period = $this->input->get('period');
            $today = time();
        
            if ($period == "today") {
                $this->db_read->where('created_at <=', time());
                $this->db_read->where('created_at >=', strtotime(date('Y-m-d 00:00')));

            } elseif ($period == "yesterday") {
                $yesterday = strtotime(date("y-m-d 00:00", strtotime("-1 days")));
                $this->db_read->where('created_at >=', $yesterday);
                $this->db_read->where('created_at <=', strtotime(date('Y-m-d 00:00')));
            } elseif ($period == "7days") {
                $last_week = strtotime("-1 week");
                $this->db_read->where('created_at >=', $last_week);
            } elseif ($period == "current_month") {
                $current_month = strtotime(date('Y-m-1 00:00'));
                $this->db_read->where('created_at >=', $current_month);
            } elseif ($period == "all") {
            }
        }
        
        $this->db_read->select('count(id) as total');
        $this->db_read->from('users as u');
        $this->db_read->where('status !=', 2);
        if (!empty($where)) {
            $this->db_read->where($where);
        }
        
        $totalData = $this->db_read->get()->row()->total;
        $totalFiltered = $totalData;
        
        $this->db_read->select('id, name, email, mobile, created_at, status');
       
        if (!empty($where)) {
        $this->db_read->where($where);
            } 

        // Apply search conditions
        if (!empty($requestData['columns'][0]['search']['value'])) {
            $this->db_read->like('name', $requestData['columns'][0]['search']['value']);
            $this->db_read->where('id', $requestData['columns'][0]['search']['value']);
        }
    
        if ($text = $requestData['columns'][2]['search']['value']) {
            $this->db_read->like('mobile', $text);
        }
    
        if ($text = $requestData['columns'][1]['search']['value']) {
            $this->db_read->like('email', $text);
        }
    
        if (!empty($requestData['columns'][3]['search']['value'])) {
            $this->db_read->where('device_type', $requestData['columns'][3]['search']['value']);
        }
    
        if (isset($requestData['columns'][5]['search']['value']) && $requestData['columns'][5]['search']['value'] != "") {
            $this->db_read->where('status', $requestData['columns'][5]['search']['value']);
        }
    
        $this->db_read->order_by($columns[$requestData['order'][0]['column']], 'DESC');
        $this->db_read->limit($requestData['length'], $requestData['start']);
        $this->db_read->where('status !=', 2);
        $this->db_read->from('users as u');
        $result=$this->db_read->get()->result();
        $data = array();
        
        foreach ($result as $r) {  
            $nestedData = array();
            $nestedData[] = ++$requestData['start'];
            
            $nestedData[] = "<div style='width: 100%;'><img style='width: 18px;' src='" . AUTH_ASSETS . "images/name.png'>
                        <a href='" . AUTH_PANEL_URL . "web_user/user_profile/". $r->id . "'>" . ($r->name ? ($r->name.' ('.$r->id.')') : ($r->id)) . "</a></div>";
            $nestedData[] = "<div style='width: 100%;'><img style='width: 18px;' src='" . AUTH_ASSETS . "images/telephone.png'>{$r->mobile}</div>";  
            $nestedData[] = "<div style='width: 100%;'><img style='width: 18px;' src='" . AUTH_ASSETS . "images/email.png'>{$r->email}</div>";  
            $nestedData[] = ($r->status == 0 ) ? '<span class="badge badge-xs badge-success" style="background-color: darkgreen;">Active</span>' : '<span class="badge badge-sm badge-danger" style="background-color: red;">Disabled</span>';
            $nestedData[] = $r->created_at ? get_time_format($r->created_at) : "--NA--";
           
           $nestedData[] = "<div class='dropdown toggle_menus_icons'> <button class='btn dropdown_ttgl text-white dropdown-toggle ' type='button' data-toggle='dropdown'><i class='fa fa-ellipsis-v' aria-hidden='true'></i></button>
           <ul class='dropdown-menu'>               
               <li><a  class=''  href='" . AUTH_PANEL_URL . "web_user/user_profile/" . $r->id . "' title='View Profile' >view</li>
           </ul>
           </div>";    
            $data[] = $nestedData;
        }

        $json_data = array(
            "draw" => intval($requestData['draw']),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalData), 
            "data" => $data,
            "posted_data" => $this->input->post()
        );
    
        echo json_encode($json_data);
        


    }

   
    public function all_user_to_csv_download($array, $filename = "export.csv", $delimiter = ";", $header=array()) {
        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '";');
        $f = fopen('php://output', 'w');
        fputcsv($f, $header);
        foreach ($array as $line) {
            fputcsv($f, $line);
        }
    }

    public function user_profile($id=null) {        
        $view_data['page'] = array("all"=>'web_user/all_user_list?user=all');
        $view_data['user_data'] = $this->web_user_model->get_user_profile($id);
        if(empty($view_data['user_data'])){
            page_alert_box('error', 'Action Not performed', 'User is not exist in our DB.');
            redirect(site_url('auth_panel/web_user/all_user_list?user=all'));   
        }
        $view_data['user_devices'] = $this->web_user_model->get_user_devices($id);
        $view_data['user_profile'] = $this->web_user_model->get_user_profile_list($id);
        $view_data['redis_session'] = $this->redis_magic->HGETALL('user_session', $id);
        $view_data['breadcrum']= array_merge($view_data['page'], array($view_data['user_data']['name']=>'#'));
        // pre($view_data['user_devices']);die;
        $data['page_data'] = $this->load->view('web_user/user_profile', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }


    public function user_logout($user_id, $user_device_id){    
        header('Content-Type: application/json; charset=utf-8');
        $user_device = $this->web_user_model->get_user_devices($user_id, $user_device_id);        
        if(empty($user_device)){
            echo json_encode(['status' => false, 'message' => "Something went wrong this device is not exist!!.."]);die;
        }
        $this->db->update('user_devices', ['current_status' => 0], ['id' => $user_device_id]);
        backend_log_genration($this,"User(ID : {$user_id}) has been Logout.","User Profile");
        echo json_encode(['status' => true, 'message' => "Status updated Successfully!!.."]);die;
    }

    public function delete_user($status, $id) {
        $status = $this->web_user_model->update_user_status($status, $id);
        backend_log_genration(
                $this,
                "User(ID : {$id}) has been deleted.",
                "User Profile"
        );
        if ($status == 'TRUE') {
            redirect(AUTH_PANEL_URL . "web_user/all_user_list?user=all");
        }
    }

    public function disable_user($status="", $id="") {
        $status = $this->web_user_model->update_user_status($status, $id);
        backend_log_genration(
                $this,
                "User(ID : {$id}) has been disabled.",
                "User Profile"
        );
        if ($status) {
            redirect_to_back();            
        }
    }

    public function enable_user($status, $id) {
        $status = $this->web_user_model->update_user_status($status, $id);
        backend_log_genration(
                $this,
                "User(ID : {$id}) has been enabled.",
                "User Profile"
        );
        if ($status) {
            redirect_to_back();
            // redirect('auth_panel/web_user/user_profile/' . $id);
        }
    }

    public function active_user($status, $id) {
        if ($status == 'active') {
            $status = 'enable';
        }
        $status = $this->web_user_model->update_user_status($status, $id);

        if ($status) {
            redirect('auth_panel/web_user/user_profile/' . $id);
        }
    }



    public function detach_device($id="") {
        if ($id > 0) {
            $this->db->where('id', $id);
            $this->db->set('device_id', '');
            $update = $this->db->update('users');
            backend_log_genration($this, 'User Detatched with user id:' . $id . '.', 'DETACH DEVICE');

            $this->reset_session($id);
        }
    }

    private function refresh_tag_ids_new($user_id, $reset = false) {
        if ($reset == true) {
            $this->db->where('id', $user_id);
            $this->db->set('expert_tag_id', '', FALSE);
            $update = $this->db->update('users');
        } else {

            $this->db->where('id', $user_id);
            $t_id = $this->db->select('expert_tag_id')->get('users')->row()->expert_tag_id;
            $t_id = explode(',', $t_id);
            $t_id = array_unique($t_id);
            $t_id = implode(',', $t_id);

            $this->db->where('id', $user_id);
            $this->db->set('expert_tag_id', $t_id);
            $update = $this->db->update('users');
        }
    }




    public function ajax_all_user_location() {

// storing  request (ie, get/post) global array to a variable
        $requestData = $_REQUEST;

        $columns = array(
// datatable column index  => database column name
            0 => 'id',
            1 => 'name',
            2 => 'email',
            3 => 'country',
            4 => 'state',
            5 => 'city'
        );

        $query = "SELECT count(id) as total FROM user_registerd_location where 1 = 1";
        $query .= (defined("APP_ID") ? "" . app_permission("app_id") . "" : "");
        $query = $this->db->query($query)->row_array();
        $totalData = (count($query) > 0) ? $query['total'] : 0;
        $totalFiltered = $totalData;

        $sql = "SELECT url.*,u.name,u.email,u.created_at FROM  user_registerd_location as url
                JOIN users as u ON url.user_id = u.id
                where 1 = 1 ";
       $sql .= (defined("APP_ID") ? "" . app_permission("u.app_id") . "" : "");
// getting records as per search parameters
        if (!empty($requestData['columns'][0]['search']['value'])) {   //name
            $sql .= " AND id LIKE '" . $requestData['columns'][0]['search']['value'] . "%' ";
        }
        if (!empty($requestData['columns'][1]['search']['value'])) {  //salary
            $sql .= " AND name LIKE '" . $requestData['columns'][1]['search']['value'] . "%' ";
        }

        if (!empty($requestData['columns'][2]['search']['value'])) {  //salary
            $sql .= " AND email LIKE '" . $requestData['columns'][2]['search']['value'] . "%' ";
        }

        if (!empty($requestData['columns'][3]['search']['value'])) {  //salary
            $sql .= " AND country LIKE '" . $requestData['columns'][3]['search']['value'] . "%' ";
        }

        if (!empty($requestData['columns'][4]['search']['value'])) {  //salary
            $sql .= " AND state LIKE '" . $requestData['columns'][4]['search']['value'] . "%' ";
        }

        if (!empty($requestData['columns'][5]['search']['value'])) {  //salary
            $sql .= " AND city LIKE '" . $requestData['columns'][5]['search']['value'] . "%' ";
        }


//echo $requestData['columns'][5]['search']['value'];
        $query = $this->db->query($sql)->result();

        $totalFiltered = count($query); // when there is a search parameter then we have to modify total number filtered rows as per search result.

        $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";  // adding length

        $result = $this->db->query($sql)->result();

        $data = array();

        foreach ($result as $r) {  // preparing an array
            $nestedData = array();
            $nestedData[] = $r->id;
            $nestedData[] = $r->name;
            $nestedData[] = $r->email;
            $nestedData[] = $r->country;
            $nestedData[] = $r->state;
            $nestedData[] = $r->city;
            $nestedData[] = $r->latitude;
            $nestedData[] = $r->longitude;
            $nestedData[] = $r->ip_address;
            $nestedData[] = date("d-m-Y", $r->created_at / 1000);
            $nestedData[] = "<a class='btn-xs bold btn btn-info' href='#'>View</a>";
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

    public function login_details($user_id="") { 
        $user = $this->db->get_where('users',['id' => $user_id])->row_array();
        if(empty($user)) {
            page_alert_box('error', 'Action Not performed', 'User is not exist in our DB.');
            redirect(site_url('auth_panel/web_user/all_user_list?user=all'));
        }
        $view_data['user_id'] = $user_id;
        $view_data['page'] = "";
        //pre($view_data);die;
        $data['page_data'] = $this->load->view('web_user/login_details', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function ajax_login_details($user_id) {
        $requestData = $_REQUEST;

        $columns = array(
            0 => 'id',
            1 => 'latitude',
            2 => 'longitude',
            3 => 'ip_address',
            4 => '',
            5 => '',
            6 => ''
        );

        $this->db_read->select('count(id) as total');
        $this->db_read->from('user_login_history');
        $this->db_read->where('user_device_info_id', $user_id);
        $totalData = $this->db_read->get()->row()->total;
        $totalFiltered = $totalData;

        // $this->db_read->order_by($columns[$requestData['order'][0]['column']], 'DESC');
        // $this->db_read->limit($requestData['length'], $requestData['start']);

        $this->db->select('ulh.*, ud.device_type');
        $this->db->from('user_login_history ulh');
        $this->db->join('user_devices ud', 'ud.id = ulh.user_device_info_id', 'LEFT');
        $this->db->where('ulh.user_device_info_id', $user_id);
        
        $query = $this->db->get();
        $result = $query->result();

        $data = array();

        foreach ($result as $r) {  // preparing an array
            $nestedData = array();
            $nestedData[] = $r->id;
            $nestedData[] = $r->latitude;
            $nestedData[] = $r->longitude;
            $nestedData[] = $r->ip_address;
            $nestedData[] = device_type($r->device_type);
            $nestedData[] = $r->os_version;
            $nestedData[] = $r->mac_id;
            $nestedData[] = $r->manufacturer;
            $nestedData[] = $r->app_version;
            $nestedData[] = $r->created_at ? get_time_format($r->created_at) : "N/A";
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

    /*     * *******************End User************************* */

    public function reset_session($user_id) {
        $update = array(
            'device_token' => ""
        );
        $this->db->where('id', $user_id);
        $this->db->update('users', $update);
        reset_session($user_id);
        page_alert_box('success', 'Action performed', 'User Session destroyed successfully');
        redirect('auth_panel/web_user/user_profile/' . $user_id);
    }



    public function all_user_report($data, $type) {
        $view_data['report'] = $data;
        $view_data['type'] = $type;
        $view_data['page'] = 'report';
        $data['page_data'] = $this->load->view('web_user/user_csv_report', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }


    public function update_email_mobile() {
        if ($this->input->post()) {
            $input_data = $this->security->xss_clean($this->input->post());
            $this->form_validation->set_rules("user_id", "User Id", "required|trim|is_natural_no_zero");
            if (array_key_exists("email", $this->input->post()))
                $this->form_validation->set_rules("email", "User Email", "required|trim|valid_email");
            if (array_key_exists("mobile", $this->input->post()))
                $this->form_validation->set_rules("mobile", "User Mobile", "required|trim|exact_length[10]|numeric");

            if ($this->form_validation->run() == false) {
                $errors = $this->form_validation->get_all_errors();
                page_alert_box("error", "User Profile", array_values($errors)[0]);
            } else {
                $update = $this->web_user_model->update_user_data($input_data);
                if (array_key_exists("email", $input_data)) {
                    $update_info = "Email";
                } elseif (array_key_exists("mobile", $input_data)) {
                    $update_info = "Mobile";
                }
                if ($update == "1") {
                    page_alert_box("success", "User Profile", $update_info . " has been updated successfully.");
                    backend_log_genration(
                            $this,
                            "User {$update_info} has been updated successfully.",
                            "User Profile",
                            $input_data
                    );
                } elseif ($update == "2") {
                    page_alert_box("error", "User Profile", $update_info . " already exists!");
                } else {
                    page_alert_box("error", "User Profile", "Something went wrong!");
                }
            }
        } else {
            page_alert_box("error", "User Profile", "Invalid Data.");
        }
        echo true;
        die;
    }

    public function update_user_pass() {
        if ($this->input->post()) {
            $this->form_validation->set_rules("user_pass", "User password", "required|min_length[8]");
            $this->form_validation->set_rules("user_id", "User Id", "required|is_natural_no_zero");
            if ($this->form_validation->run() == false) {
                $errors = $this->form_validation->get_all_errors();
                page_alert_box("error", "User Profile", array_values($errors)[0]);
            } else {

                $user_pass = $this->input->post("user_pass");
                $user_id = $this->input->post("user_id");

                $this->db->where("id", $user_id);
                $this->db->set("password", generate_password($user_pass));
                $update = $this->db->update("users");
                if ($update) {
                    page_alert_box("success", "User Password has been updated successfully", "User Profile");
                } else {
                    page_alert_box("error", "Something went wrong due to technical issue.", "User Profile");
                }
            }
        }
        echo true;
        die;
    }


   
    public function user_activation(){
        
        if($this->input->post()){
            $this->form_validation->set_rules("user_id","User Id","required|trim|is_natural_no_zero");
            if($this->form_validation->run() == false){
                $error = $this->form_validation->get_all_errors();
                page_alert_box("error","Course User Activation", array_values($error)[0]);
                redirect(AUTH_PANEL_URL."web_user/user_activation");
            }
            $result = $this->web_user_model->add_activation_key($this->input->post());
            if($result){
                page_alert_box("success","Course User Activation","Activation key has been generated successfully.");
                backend_log_genration($this,"Activation key has been generated successfully.","Course User Activation");
                redirect(AUTH_PANEL_URL."web_user/user_activation");
            }else{
                page_alert_box("error","Course User Activation","Invalid activation request.");
            }
        }
        $view_data['page'] = "user_activation";
        $view_data['page_title'] = "User Activation";
        $data['page_data'] = $this->load->view('web_user/user_activation', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }
    
  
}
