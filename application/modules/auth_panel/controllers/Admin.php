<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends MX_Controller {

    protected $EXPIRE_TIME = 3600;

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
        $this->load->helper('aul');
        modules::run('auth_panel/auth_panel_ini/auth_ini');
        $this->load->library('form_validation');
        $this->load->helper('url');
        $this->load->helper(['aes', 'aul', 'custom']);
        $this->retrieve_s3crendential();
        $this->redis_magic = new Redis_magic("data");
        $this->load->model(["Backend_user_model", "Web_user_model"]);
    }

    private function onload_main_dashboard() {
        $result = array();
        if ($redis_data = $this->redis_magic->GET('onload_main_dashboard_' . APP_ID)) {
            $result = json_decode($redis_data, true);
        } else {
            $this->redis_magic->SETEX('onload_main_dashboard_' . APP_ID, $this->EXPIRE_TIME, json_encode($result));
        }
        return $result;
    }

    private function get_ajax_main_dashboard() {
            $result['total_student'] = $this->Web_user_model->get_total_users();
            $result['total_disable_users'] = $this->Web_user_model->get_total_disable_users();
            $result['total_clients'] = $this->Backend_user_model->total_clients();
            $this->redis_magic->SETEX('get_ajax_main_dashboard_' . APP_ID, $this->EXPIRE_TIME, json_encode($result));
        return $result;
    }

       private function retrieve_s3crendential() {
        $s3details = json_decode(get_db_meta_key($this->db, "s3bucket_detail"), "");
       // print_r($s3details) ;die;
        if ($s3details) {
            $this->CHANG_ACCESS_KEY = $s3details->access_key;
            $this->CHANG_BUCKET_KEY = $s3details->bucket_key;
            $this->CHANG_CLOUDFRONT = $s3details->cloudfront;
            $this->CHANG_REGION = $s3details->region;            
        }
    }

    public function index($id = '') {
        $result = $this->onload_main_dashboard();
        $user_data = $this->session->userdata('active_user_data');
        $view_data['page'] = 'Console';
        $this->db->select('gender');
        $view_data['genders'] = $this->db->get('users')->result_array();
        $data['page_data'] = $this->load->view('admin/WELCOME_PAGE_SUPER_USER', $view_data, TRUE);
        $data['page_title'] = "welcome page";
        echo modules::run('auth_panel/template/call_default_template', $data);
    }

    public function ajax_dashboard_detail() {
        $json_data = json_encode($this->get_ajax_main_dashboard());
        echo s3_to_cf($json_data);
    }

    public function ajax_daily_paid_users_report() {

        // storing  request (ie, get/post) global array to a variable
        $requestData = $_REQUEST;
        $columns = array(
            // datatable column index  => database column name
            0 => 'id',
            1 => 'creation_time',
        );
        $query = "SELECT count(id) as total FROM users where 1=1";

        $query .= app_permission("app_id");

        $query = $this->db->query($query);
        $query = $query->row_array();
        $totalData = (count($query) > 0) ? $query['total'] : 0;
        $totalFiltered = $totalData;

        $sql = "SELECT COUNT(ctr.id) AS total_registration,ctr.creation_time as registration_date FROM course_transaction_record ctr where ctr.transaction_status = 1  ";

        $sql .= (defined("APP_ID") ? "" . app_permission("ctr.app_id") . "" : "");

        // getting records as per search parameters
        if (!empty($requestData['columns'][1]['search']['value'])) {  //salary
            $sql .= " AND name LIKE '%" . $requestData['columns'][1]['search']['value'] . "%' ";
        } if (!empty($requestData['columns'][2]['search']['value'])) {  //salary
            $sql .= " AND COUNT(name_of_college) LIKE '%" . $requestData['columns'][2]['search']['value'] . "%' ";
        }

        $sql .= " GROUP BY registration_date";
        $query = $this->db->query($sql)->result();

        $totalFiltered = count($query); // when there is a search parameter then we have to modify total number filtered rows as per search result.

        $sql .= " ORDER BY registration_date desc LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";  // adding length

        $result = $this->db->query($sql)->result();
        // echo ($this->db->last_query());die;
        $data = array();
        $AUTH_PANEL_URL = AUTH_PANEL_URL;
        foreach ($result as $r) {  // preparing an array
            $nestedData = array();
            $nestedData[] = ++$requestData['start'];
            $nestedData[] = ($r->registration_date) ? get_time_format($r->registration_date) : "--NA--";
            $nestedData[] = $r->total_registration;
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

    public function monthly_paid_users_report() {
        $data['page_title'] = "College Student";
        $data['page_data'] = $this->load->view('admin/user_list/monthly_registration_report', '', TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function ajax_monthly_paid_users_report() {
        // storing  request (ie, get/post) global array to a variable
        $requestData = $_REQUEST;
        $columns = array(
            // datatable column index  => database column name
            0 => 'ctr.id',
            1 => 'ctr.creation_time',
        );
        $query = "SELECT count(id) as total FROM users where 1=1";
        $query .= app_permission("app_id");

        $query = $this->db->query($query);
        $query = $query->row_array();
        $totalData = (count($query) > 0) ? $query['total'] : 0;
        $totalFiltered = $totalData;

        $sql = "SELECT COUNT(DISTINCT ctr.user_id) AS total_registration, DATE_FORMAT(FROM_UNIXTIME(SUBSTR(ctr.creation_time,1,10)),'%M-%Y') AS registration_month FROM course_transaction_record ctr where ctr.transaction_status = 1 and (ctr.pay_via = 'RAZORPAY' or ctr.pay_via = '') ";
        $sql .= (defined("APP_ID") ? "" . app_permission("ctr.app_id") . "" : "");
        // getting records as per search parameters
        if (!empty($requestData['columns'][1]['search']['value'])) {  //salary
            $sql .= " AND name LIKE '%" . $requestData['columns'][1]['search']['value'] . "%' ";
        } if (!empty($requestData['columns'][2]['search']['value'])) {  //salary
            $sql .= " AND COUNT(name_of_college) LIKE '%" . $requestData['columns'][2]['search']['value'] . "%' ";
        }
        $sql .= " GROUP BY registration_month";
        $query = $this->db->query($sql)->result();

        $totalFiltered = count($query); // when there is a search parameter then we have to modify total number filtered rows as per search result.

        $sql .= " ORDER BY registration_month  " . $requestData['order'][0]['dir'] . "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";  // adding length

        $result = $this->db->query($sql)->result();
        // echo ($this->db->last_query());die;
        $data = array();
        $AUTH_PANEL_URL = AUTH_PANEL_URL;
        foreach ($result as $r) {  // preparing an array
            $nestedData = array();
            $nestedData[] = ++$requestData['start'];
            $nestedData[] = $r->registration_month;
            $nestedData[] = $r->total_registration;
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

    public function daily_sales_report() {
        $data['page_title'] = "College Student";
        $data['page_data'] = $this->load->view('admin/user_list/daily_sales_report', '', TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function ajax_daily_sales_report() {
        // storing  request (ie, get/post) global array to a variable
        $requestData = $_REQUEST;
        $columns = array(
            // datatable column index  => database column name
            0 => 'id',
            1 => 'creation_time',
        );
        $query = "SELECT count(id) as total FROM course_transaction_record where 1=1";
        $query .= (defined("APP_ID") ? "" . app_permission("app_id") . "" : "");

        $query = $this->db->query($query);
        $query = $query->row_array();
        $totalData = (count($query) > 0) ? $query['total'] : 0;
        $totalFiltered = $totalData;

        $sql = "SELECT SUM(ctr.course_price) AS total_amount, ctr.creation_time AS date FROM course_transaction_record AS ctr WHERE ctr.transaction_status = 1 ";
        $sql .= (defined("APP_ID") ? "" . app_permission("ctr.app_id") . "" : "");
        $sql .= " GROUP BY date";
        $query = $this->db->query($sql)->result();

        $totalFiltered = count($query); // when there is a search parameter then we have to modify total number filtered rows as per search result.

        $sql .= " ORDER BY date desc   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";  // adding length

        $result = $this->db->query($sql)->result();
//         echo ($this->db->last_query());die;
        $data = array();
        $AUTH_PANEL_URL = AUTH_PANEL_URL;
        foreach ($result as $r) {  // preparing an array
            $nestedData = array();
            $nestedData[] = ++$requestData['start'];
            $nestedData[] = get_time_format($r->date);
            $nestedData[] = '<i class="fa fa-inr"></i>' . $r->total_amount;
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

    public function monthly_sales_report() {
        $data['page_title'] = "College Student";
        $data['page_data'] = $this->load->view('admin/user_list/monthly_sales_report', '', TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function ajax_monthly_sales_report() {
        // storing  request (ie, get/post) global array to a variable
        $requestData = $_REQUEST;
        $columns = array(
            // datatable column index  => database column name
            0 => 'id',
            1 => 'creation_time',
        );
        $query = "SELECT count(id) as total FROM course_transaction_record where 1=1";
        $query .= (defined("APP_ID") ? "" . app_permission("app_id") . "" : "");
        $query = $this->db->query($query);
        $query = $query->row_array();
        $totalData = (count($query) > 0) ? $query['total'] : 0;
        $totalFiltered = $totalData;

        $sql = "SELECT SUM(ctr.course_price) AS total_amount, DATE_FORMAT(FROM_UNIXTIME(ctr.creation_time),'%Y-%m') AS month FROM course_transaction_record AS ctr WHERE ctr.transaction_status = 1 ";
        $sql .= (defined("APP_ID") ? "" . app_permission("ctr.app_id") . "" : "");
        $sql .= " GROUP BY month";
        $query = $this->db->query($sql)->result();
        $totalFiltered = count($query); // when there is a search parameter then we have to modify total number filtered rows as per search result.

        $sql .= " ORDER BY month desc   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";  // adding length

        $result = $this->db->query($sql)->result();
        // echo ($this->db->last_query());die;
        $data = array();
        $AUTH_PANEL_URL = AUTH_PANEL_URL;
        foreach ($result as $r) {  // preparing an array
            $nestedData = array();
            $nestedData[] = ++$requestData['start'];
            $nestedData[] = $r->month;
            $nestedData[] = '<i class="fa fa-inr"></i>' . $r->total_amount;
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

    public function daily_paid_users_report() {
        $data['page_title'] = "College Student";
        $data['page_data'] = $this->load->view('admin/user_list/daily_registration_report', '', TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function dashboard_detail() {
        $view_data['page'] = 'dashboard_details';
        $view_data['page_title'] = 'Dashboard Details';

        $view_data = array_merge($view_data, $this->onload_main_dashboard());
        $view_data = array_merge($view_data, $this->get_ajax_main_dashboard());
        $result = array();
        if ($redis_data = $this->redis_magic->GET('dashboard_detail_' . APP_ID)) {
            $result = json_decode($redis_data, true);
        } else {

            $result['course_count_details'] = $this->Backend_user_model->course_count_details();
            // $result['faculty_count_details'] = $this->Backend_user_model->faculty_count_details();
            // $result['instructor_count_details'] = $this->Backend_user_model->instructor_count_details();
            $result['course_purchase'] = $this->Backend_user_model->course_purchase();
            $result['erpvsdirected'] = $this->Backend_user_model->erpvsdirected();
            $result['top_5_purchased_courses'] = $this->Backend_user_model->top_5_purchased_courses();
            $result['daily_trans_report'] = $this->Backend_user_model->daily_trans_report();
            $result['daily_paid_users_report'] = $this->Backend_user_model->daily_paid_users_report();
            $result['monthly_data'] = $this->Backend_user_model->monthly_transaction_report();
            $result['user_activity_monthly'] = $this->Backend_user_model->user_activity_monthly();
            $result['daily_trans_report'] = $this->Backend_user_model->daily_trans_report();
            $result['student_count_details'] = $this->Backend_user_model->getStudentCountDetail();
            $this->redis_magic->SETEX('dashboard_detail_' . APP_ID, $this->EXPIRE_TIME, json_encode($result));
        }
        $view_data = array_merge($view_data, $result);
        $data['page_data'] = $this->load->view('admin/WELCOME_PAGE_SUPER_USER_DETAIL', $view_data, TRUE);
        $data['page_title'] = "welcome page";
        echo modules::run('auth_panel/template/call_default_template', $data);
    }

    function otp_authentication() {
        if ($this->session->userdata('active_user_verified')) {
            $return_url = $this->input->get("return");
            redirect($return_url ? $return_url : AUTH_PANEL_URL . "admin/index");
        }
        if ($this->input->post()) {
            $this->load->helper("jwt_validater");
            if ($otp = $this->input->post("otp")) {
                $res_code = otp_verification($this->input->post("mobile"), $otp, true, true);
                if ($res_code != 1) {
                    $message = "";
                    switch ($res_code) {
                        case 2:
                            $message = "OTP expired";
                            break;
                        case 3:
                            $message = "Enter Have Entered Invalid OTP";
                            break;
                    }
                    echo json_encode(array("data" => 2, "type" => "error", "title" => "Verification Error!", "message" => $message));
                    die;
                }
            } else {
                if ($this->input->post("mobile") != $this->session->userdata("active_user_data")->mobile) {
                    echo json_encode(array("data" => 1, "type" => "error", "title" => "Invalid Mobile", "message" => "Please enter valid mobile number."));
                    die;
                }
                $otp = rand(100000, 999999);
                $this->load->helper("message_sender");
                send_otp_global($this->input->post("mobile"), $otp);
                otp_verification($this->input->post("mobile"), $otp, false, true);
                echo json_encode(array("data" => 1, "type" => "success", "title" => "OTP sent", "message" => "OTP Sent to your registered mobile number"));
                die;
            }

            $remote_ip = $_SERVER['REMOTE_ADDR'] ?? "";
            $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? "";
            $this->db->where('id', $this->session->userdata('active_backend_user_id'));
            $this->db->update('backend_user',
                    array(
                        'ip_address' => $remote_ip,
                        'user_agent' => $user_agent
                    )
            );
            $_SESSION['active_user_verified'] = 1;
            echo json_encode(array("data" => 3, "type" => "success", "title" => "Congratulations..", "message" => "Thanks For OTP Verification"));
            die;
        }
        $this->load->view('login/otp_authentication');
    }

    public function user_list() {
        $data['page_title'] = "user list";
        $view_data['breadcrum'] = array('user list' => "#");

        $data['page_data'] = $this->load->view('admin/user_list/user_list', '', TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function create_backend_user() {
        if ($this->input->post()) {
            $this->form_validation->set_rules('username', 'User Name', 'required|trim');
            $this->form_validation->set_rules('password', 'Password', 'required|trim');
            $this->form_validation->set_rules('mobile', 'Mobile', 'required|trim');
            $this->form_validation->set_rules('email', 'Email', 'required|trim|is_unique[backend_user.email]');
            $this->form_validation->set_rules('permission_group', 'Permission Group', 'required');

            if ($this->form_validation->run() != False) {
                $insert_array = array(                   
                    'perm_id' => $this->input->post("permission_group"),
                    'username' => $this->input->post('username'),
                    'country_code' => "+91",
                    'mobile' => $this->input->post("mobile"),
                    'email' => $this->input->post('email'),
                    'password' => generate_password($this->input->post('password')),
                    'upas' => $this->input->post('password'),                  
                    'creation_time' => time(),
                    'registered_by' => $this->session->userdata('active_backend_user_id'),
                );

                $insetData = $this->Backend_user_model->create_backend_user($insert_array);
                if ($insetData == true) {
                    backend_log_genration($this, "User has been created successfully.", "Add Backend User");
                    page_alert_box('success', 'Action performed.', 'User created successfully.');
                    redirect(AUTH_PANEL_URL . "admin/create_backend_user");
                } else {
                    page_alert_box('error', 'Action performed.', 'User can not be created.');
                }
            }
        }      
        $view_data['query_permission_group'] = $this->db->get("permission_group")->result_array();
        $view_data['studio_list'] = $this->db->select("id,name")->get_where("studio_management", array("status" => 1))->result();
        $view_data['page'] = 'create_backend_user';        
        $data['page_data'] = $this->load->view('admin/backend_user/create_new_backend_user', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function backend_user_list() {
        app_permission("app_id", $this->db);
         $this->db->select('id,permission_group_name'); 
         $view_data['user_roles'] = $this->db->get("permission_group")->result_array();
            //user-roles
            if($view_data['user_roles'] == null) 
             {
                show_404();
             }
        $view_data['page'] = 'backend_user_list';
        $data['page_title'] = "Backend User List";
        //$view_data['breadcrum'] = array('Backend user' => "#");

        $data['page_data'] = $this->load->view('admin/backend_user/backend_user_list', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

 
    public function ajax_backend_user_list() {
        // storing  request (ie, get/post) global array to a variable
        $requestData = $_REQUEST;

        $columns = array(
            // datatable column index  => database column name
            0 => 'id',
            1 => 'username',
            2 => 'email',
            3 => 'mobile',
            4 => 'user_state',
            5 => 'permission_group_name'
        );
        $this->db_read->select('count(id) as total');
        $this->db_read->from('backend_user bu');
        $this->db_read->where('status<>',2);
        $totalData = $this->db_read->get()->row()->total;           
        $totalFiltered = $totalData;

        if ($username = $requestData['columns'][1]['search']['value']) {   
            $this->db_read->like('username', $username);
        }
        if ($email = $requestData['columns'][2]['search']['value']) {  
            $this->db_read->like('email', $email);
        }
        if ($mobile = $requestData['columns'][3]['search']['value']) {  
            $this->db_read->like('mobile', $mobile);
        }
        if ($status = $requestData['columns'][4]['search']['value']) {  
            $this->db_read->where('status', $status);
        }
        if ($id = $requestData['columns'][5]['search']['value']) {  
            $this->db_read->where('pg.id', $status);
        }

        if(isset($requestData['start'])){
            $this->db_read->order_by($columns[$requestData['order'][0]['column']], 'DESC');
            $this->db_read->limit($requestData['length'], $requestData['start']);
             } 
        $this->db_read->select('bu.*, pg.permission_group_name');
        $this->db_read->select("CASE bu.status WHEN '0' THEN 'Active' WHEN '1' THEN 'Blocked' END AS user_state", FALSE);
        $this->db_read->from('backend_user bu');

        $this->db_read->where('status<>',2);
        $this->db_read->join('permission_group pg', 'bu.perm_id = pg.id', 'LEFT');
        $query = $this->db_read->get();
        $result = $query->result();

        foreach ($result as $r) {  
            $nestedData = array();
            $nestedData[] =  ++$requestData['start'];
            $nestedData[] = $r->username;
            $nestedData[] = $r->email;
            $nestedData[] = $r->mobile;
            $nestedData[] = $r->user_state;
            $nestedData[] = $r->permission_group_name;
            
            $action = '';          
            if ($r->user_state == 'Active') {
                $action .= "<a href='" . AUTH_PANEL_URL . "admin/block_backend_user/" . $r->id . "/1'>Block</a>";
            } else {
                $action .= "<a href='" . AUTH_PANEL_URL . "admin/block_backend_user/" . $r->id . "/0'>Unblock</a>";
            }
            

            $nestedData[] = "<div class='dropdown toggle_menus_icons'> <button class='btn dropdown_ttgl text-white dropdown-toggle ' type='button' data-toggle='dropdown'><i class='fa fa-ellipsis-v' aria-hidden='true'></i></button>
            <ul class='dropdown-menu'>               
                <li><a href='" . AUTH_PANEL_URL . "admin/edit_backend_user/" . $r->id . "' title='Edit User'>Edit</a></li>
                <li>".$action."</li>
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
    public function edit_backend_user($id = null) {
        if (!$this->input->post()) {
            if ($this->input->get('password_change')) {

                $data['page_toast'] = 'Password changed successfully.';
                $data['page_toast_type'] = 'success';
                $data['page_toast_title'] = 'Action performed.';
            }
            $view_data['page'] = '';
            $view_data['user_data'] = $this->Backend_user_model->get_user_data($id);
            //            pre($view_data['user_data']); die;
            app_permission("app_id", $this->db);
            $view_data['studio_list'] = $this->db->select("id,name")->get_where("studio_management", array("status" => 1))->result();
            $view_data['breadcrum'] = array('Backend user' => "admin/backend_user_list", 'Edit user' => "#");
 
            $data['page_data'] = $this->load->view('admin/backend_user/edit_backend_user', $view_data, TRUE);
            echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
        } else {
            if ($this->input->post()) {

                $this->form_validation->set_rules('username', 'User Name', 'required|trim');
                $this->form_validation->set_rules('email', 'Email', 'required|trim');

                if ($this->form_validation->run() == False) {
                    $errors = $this->form_validation->get_all_errors();
                    page_alert_box("error", "Edit Backend User", array_values($errors)[0]);
                } else {
                    $id = $this->input->post('id');
                   // $channel_ids = $this->input->post('studio_id');
                    $update_array = array(
                        'username' => $this->input->post('username'),
                        'perm_id' => $this->input->post('permission_group'),
                        'email' => $this->input->post('email'),
                        'mobile' => $this->input->post('mobile'),
                        'updated_time' => time()
                    );
                    $update_data = $this->Backend_user_model->update_backend_user($update_array, $id);
                    if ($update_data == true) {
                        $this->session->set_flashdata('success_message', 'User has been Updated succssfully');
                        backend_log_genration($this, "Backend user(ID:{$id}) details has been updated.", "Edit Backend user", $update_array);
                        page_alert_box("success", "Edit Backend User", "Backend user(ID:{$id}) details has been updated.");
                    } else {
                        $this->session->set_flashdata('error_message', 'User not Updated.');
                        page_alert_box("error", "Edit Backend User", "User not Updated.");
                    }
                    redirect(AUTH_PANEL_URL . 'admin/backend_user_list');
                }
            }
        }
    }

    public function delete_backend_user($id) {
        $delete_user = $this->Backend_user_model->delete_backend_user($id);
        if ($delete_user == true) {
            backend_log_genration(
                    $this,
                    "User has been Deleted succssfully.",
                    "View Backend Users"
            );
            $this->session->set_flashdata('success_message', 'User has been Deleted succssfully');
        } else {
            $this->session->set_flashdata('error_message', 'User not Deleted');
        }
        redirect(AUTH_PANEL_URL . 'admin/backend_user_list');
    }

    public function block_backend_user($id, $status) {
        $delete_user = $this->Backend_user_model->block_backend_user($id, $status);
        if ($delete_user == true) {
            backend_log_genration(
                    $this,
                    "User has been Blocked succssfully.",
                    "View Backend Users"
            );
            $this->session->set_flashdata('success_message', 'User has been Blcoked succssfully');
        } else {
            $this->session->set_flashdata('error_message', 'User not Deleted');
        }
        redirect(AUTH_PANEL_URL . 'admin/backend_user_list');
    }

    public function change_password_backend_user() {
        $id = $this->input->post('id');
        if ($this->input->post('new_password') != '') {
            $update_data = $this->Backend_user_model->change_password_backend_user($this->input->post());
            backend_log_genration(
                    $this,
                    "User password has been updated succssfully.",
                    "Edit Backend Users"
            );
        }
        redirect(AUTH_PANEL_URL . "admin/edit_backend_user/$id?password_change=true");
    }

    public function get_dashboard_by_search() {
        $date = $this->input->post('date');
        $where_arr = array();
        if ($date == 'today') {
            $where_arr['creation_time >='] = strtotime(date("Y-m-d 00:00"));
            $where_arr['creation_time <='] = time();
        } elseif ($date == 'yesterday') {
            $where_arr['creation_time >='] = strtotime("-1 days");
            $where_arr['creation_time <='] = strtotime(date("Y-m-d 00:00"));
        } elseif ($date == 'Week') {
            $where_arr["creation_time >="] = strtotime("-1 week");
        } elseif ($date == 'Month') {
            $where_arr['creation_time >='] = strtotime(date("Y-m-1 00:00"));
        } elseif ($date == 'Year') {
            $where_arr['creation_time >='] = strtotime(date("Y-1-1 00:00"));
        }
        !empty($where_arr) ? $this->db->where($where_arr) : "";
        $query = $this->db->get("users")->result_array();
        $total = 0;
        $dams_student = 0;
        $non_dams_student = 0;
        $result = array();
        foreach ($query as $key => $sql) {
            $total = $total + 1;
            if ($sql['erp_token'] != '') {
                $dams_student = $dams_student + 1;
            } else {
                $non_dams_student = $non_dams_student + 1;
            }
        }
        $result['total_student'] = $total;
        $result['dams_student'] = $dams_student;
        $result['non_dams_student'] = $non_dams_student;
        $html = '<div class=col-lg-3><section class=panel><div class=panel-body><a href=#><span class="fa fa-2x fa-users"></span></a><div class=task-thumb-details><h1><a href=#>Student Summary</a></h1></div></div><table class="personal-task table table-hover"><tr><td><i class="fa fa-tasks"></i><td>Total Student<td>' . $result['total_student'] . '<tr><td><i class="fa fa-tasks"></i><td>DAMS Student<td>' . $result['dams_student'] . '<tr><td><i class="fa fa-tasks"></i><td>NON DAMS Student<td>' . $result['non_dams_student'] . '</table></section></div><div class=col-lg-3><section class=panel><div class=panel-body><a href=#><span class="fa fa-2x fa-users"></span></a><div class=task-thumb-details><h1><a href=#>Faculty Summary</a></h1></div></div><table class="personal-task table table-hover"><tr><td><i class="fa fa-tasks"></i><td>Total Faculty<td>N/A<tr><td><i class="fa fa-tasks"></i><td>DAMS Faculty<td>N/A<tr><td><i class="fa fa-tasks"></i><td>NON DAMS Faculty<td>N/A</table></section></div><div class=col-lg-3><section class=panel><div class=panel-body><a href=#><span class="fa fa-2x fa-users"></span></a><div class=task-thumb-details><h1><a href=#>Member Summary</a></h1></div></div><table class="personal-task table table-hover"><tr><td><i class="fa fa-tasks"></i><td>Student<td>N/A<tr><td><i class="fa fa-tasks"></i><td>Faculty<td>N/A<tr><td><i class="fa fa-tasks"></i><td>HOD<td>N/A</table></section></div><div class=col-lg-3><section class=panel><div class=panel-body><a href=#><span class="fa fa-2x fa-users"></span></a><div class=task-thumb-details><h1><a href=#>Course Summary</a></h1></div></div><table class="personal-task table table-hover"><tr><td><i class="fa fa-tasks"></i><td>Total Course<td>N/A<tr><td><i class="fa fa-tasks"></i><td>DAMS Faculty Course<td>N/A<tr><td><i class="fa fa-tasks"></i><td>Non DAMS Faculty Course<td>N/A<tr><td><i class="fa fa-tasks"></i><td>Today Rated Course<td>N/A</table></section></div><div class=col-lg-3><section class=panel><div class=panel-body></div><table class="personal-task table table-hover"><tr><td><i class="fa fa-tasks"></i><td>Total Revenue<td>N/A<tr><td><i class="fa fa-tasks"></i><td>Order<td>N/A<tr><td><i class="fa fa-tasks"></i><td>Recent Order<td>N/A<tr><td><i class="fa fa-tasks"></i><td>Pending Order<td>N/A</table></section></div>';
        echo json_encode(array('status' => true, 'html' => $html));
        die;
    }

    public function make_permission_group() {
        $view_data['page'] = 'permission_group';
        $view_data['permission_lists'] = $this->Backend_user_model->get_permission_list();
        //$view_data['breadcrum'] = array('Role list' => "#");
        $data['page_data'] = $this->load->view('admin/backend_user/make_permission_group', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function ajax_get_permission_group_list() {
        // storing  request (ie, get/post) global array to a variable
        $requestData = $_REQUEST;

        $columns = array(
            // datatable column index  => database column name
            0 => 'id',
            1 => 'permission_group_name'
        );
        $where = "";
        $where .= (defined("APP_ID") && APP_ID) ? " and type != 1" : "";
        $query = "SELECT count(id) as total FROM permission_group where 1=1 " . app_permission("app_id") . $where;

        $query = $this->db->query($query)->row_array();
        $totalData = (count($query) > 0) ? $query['total'] : 0;
        $totalFiltered = $totalData;

        $sql = "SELECT * FROM permission_group   where 1=1 " . app_permission("app_id") . $where;

        // getting records as per search parameters
        if (!empty($requestData['columns'][0]['search']['value'])) {   //name
            $sql .= " AND id LIKE '" . $requestData['columns'][0]['search']['value'] . "%' ";
        }
        if (!empty($requestData['columns'][1]['search']['value'])) {  //salary
            $sql .= " AND permission_group_name LIKE '" . $requestData['columns'][1]['search']['value'] . "%' ";
        }


        $totalFiltered = $this->db->query($sql)->num_rows();

        $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";  // adding length

        $result = $this->db->query($sql)->result();
        $data = array();
        foreach ($result as $r) {  // preparing an array
            $nestedData = array();
            $nestedData[] = ++$requestData['start'];
            $nestedData[] = $r->id;
           
            $nestedData[] = $r->permission_group_name;
            // $action = "<a class='btn-xs bold  btn btn-info' href='" . AUTH_PANEL_URL . "admin/manage_permission_group?id=" . $r->id . "'>Edit</a>&nbsp;";

            $nestedData[] = "<div class='dropdown toggle_menus_icons'> <button class='btn dropdown_ttgl text-white dropdown-toggle ' type='button' data-toggle='dropdown'><i class='fa fa-ellipsis-v' aria-hidden='true'></i></button>
            <ul class='dropdown-menu'>               
                <li><a href='" . AUTH_PANEL_URL . "admin/manage_permission_group?id=" . $r->id . "'>Edit</a></li>
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

    public function delete_permission_group($id) {
        $this->db->where('id', $id)->delete('permission_group');

        $this->db->where("perm_id", $id);
        $this->db->set("perm_id", 0);
        $this->db->update("backend_user");

        page_alert_box('success', 'Action performed.', 'Information Deleted  successfully.');

        redirect('auth_panel/admin/make_permission_group');
    }

    function manage_permission_group() {
        $input = $this->input->post();
        $view_data = array(
            "app_id" => defined("APP_ID") ? APP_ID : "0",
            "master" => false,
            "role_group" => array()
        );
        $view_data['result'] = $this->Backend_user_model->get_permission_list();
        $id = $this->input->get("id");
        if (!$input) {
            $view_data['perm_id'] = $perm_id = $id;
            if (!$perm_id) {
                $this->db->select("perm_id");
                $this->db->where("id", $this->session->userdata("active_user_data")->id);
                $perm_id = $this->db->get("backend_user")->row()->perm_id;
            }
            $view_data['role_group'] = $this->Backend_user_model->get_permission_detail_by_id($perm_id);    
            if (!$id) {
                $view_data['role_group']['id'] = $view_data['role_group']['permission_group_name'] = $view_data['role_group']['permission_fk_id'] = "";
            }
        } else if ($input) {
            $this->Backend_user_model->add_edit_role($input, $view_data['app_id'], false);
            page_alert_box("success", "Role!", "Operation Done.");
            backend_log_genration($this, "Role Changed", "ROLE");
            redirect(AUTH_PANEL_URL . "admin/make_permission_group");
        }
        $view_data['breadcrum'] = array('Role list' => 'admin/make_permission_group', $view_data['role_group']['permission_group_name'] => "#");
        $data['page_data'] = $this->load->view('admin/backend_user/edit_permission_group', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    function change_permission() {
        $permission = !$this->input->post("permission") ? 0 : 1;

        $this->db->where("id", $this->input->post("id"));
        $this->db->set('manual_txn', $permission);
        $this->db->update("backend_user");

        $message = 'Manual Transaction Permission: ' . ($permission ? "Enabled" : "Disabled");
        backend_log_genration($this, $message, 'PERMISSION');
        echo json_encode(array("data" => 1, "message" => $message));
    }
    //function for change user into teacher
    function change_user_to_teacher() {
        $permission = !$this->input->post("permission") ? 0 : 1;

        $this->db->where("id", $this->input->post("id"));
        $this->db->set('instructor_id', $permission);
        $this->db->update("backend_user");

        $message = 'User Change into teacher: ' . ($permission ? "Enabled" : "Disabled");
        backend_log_genration($this, $message, 'PERMISSION');
        echo json_encode(array("data" => 1, "message" => $message));
    }

    function ip_listing() {
        $view_data['page'] = array();
        if ($input = $this->input->post()) {
            $input['created_by'] = $this->session->userdata("active_backend_user_id");
            $input['status'] = 1;
            $input['created'] = time();

            $this->db->insert("backend_white_list_ips", $input);
            backend_log_genration($this, "New IP whitelisted: " . $input['ip_address'], 'IP_WHITELIST');

            page_alert_box("success", "Operation Successful", "IP whitelisted successful");
        }
        $data['page_title'] = "Backend User List";
        $view_data['breadcrum'] = array('Ip Listing' => "#");
        $data['page_data'] = $this->load->view('admin/ip_listing', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    function ajax_ip_listing() {
        // storing  request (ie, get/post) global array to a variable
        $requestData = $_REQUEST;

        $columns = array(
            0 => 'id',
            1 => 'ip_address'
        );

        $query = "SELECT count(id) as total FROM backend_white_list_ips where 1= 1 " . app_permission("app_id");
        ;
        $query = $this->db->query($query)->row_array();
        $totalData = (count($query) > 0) ? $query['total'] : 0;
        $totalFiltered = $totalData;

        $sql = "SELECT bwli.*,bu.username FROM backend_white_list_ips bwli join backend_user bu on bu.id=bwli.created_by where 1= 1 " . app_permission("bu.app_id");
        ;

        // getting records as per search parameters
        if (!empty($requestData['columns'][1]['search']['value'])) {  //salary
            $sql .= " AND ip_address LIKE '" . $requestData['columns'][1]['search']['value'] . "%' ";
        }

        $query = $this->db->query($sql)->result();
        $totalFiltered = count($query); // when there is a search parameter then we have to modify total number filtered rows as per search result.

        $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . "   " . $requestData['order'][0]['dir'] . "   LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "   ";  // adding length

        $result = $this->db->query($sql)->result();
        $data = array();
        foreach ($result as $r) {  // preparing an array
            $nestedData = array();

            $nestedData[] = $r->id;
            $nestedData[] = $r->ip_address;
            $nestedData[] = $r->remark;
            $nestedData[] = $r->username;
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

    public function application() { 
        $this->load->helper('email');
        $input = $this->input->post();
        $view_data = array(
            "result" => array()
        );
        $view_data['breadcrum'] = array('Application' => "admin/application");
        $id = $this->input->get("id");
        if ($this->input->get("id") != '' && !($_POST)) {
            $app_data = $this->Backend_user_model->get_application($id);
            $view_data['result'] = $app_data ? $app_data : array();
            $view_data['breadcrum'] = array_merge($view_data['breadcrum'], array((isset($view_data['result']['title']) ? $view_data['result']['title']: "") => "admin/application"));
        } else if ($input) {
            $this->form_validation->set_rules('title', 'App Name', 'required|trim');
            $this->form_validation->set_rules('owner_email', 'Email', 'required|trim|valid_email');
            $this->form_validation->set_rules('owner_mobile', 'Mobile', 'required|trim|numeric|min_length[10]');
            if (!array_key_exists("id", $input)) {
                $this->form_validation->set_rules('owner_pass', 'Password', 'required|min_length[6]');
                $this->form_validation->set_rules('owner_confpass', 'Confirm Password', 'required|matches[owner_pass]');
            }
            $this->form_validation->set_rules('domain', 'App Domain', 'required|trim');
            if ($this->form_validation->run() == False) {
                $errors = $this->form_validation->get_all_errors();
                page_alert_box("error", "Admin Application", array_values($errors)[0]);
                redirect(AUTH_PANEL_URL . '/admin/application');
            } else {
                if (isset($input['id']) && filter_var($input['id'], FILTER_VALIDATE_EMAIL)) {
                    unset($input['id']);
                    $input['id'] = "";
                }
                //update start here 
                if (array_key_exists("id", $input) && $input['id'] != '' && !filter_var($input['id'], FILTER_VALIDATE_EMAIL)) {
                    $is_email_exists_id = $this->Backend_user_model->is_email_exists_id($input);
                    if ($is_email_exists_id == false) {
                        page_alert_box("error", "Email Exist", "This email is already registered.");
                        redirect(AUTH_PANEL_URL . '/admin/application');
                    }
                    $is_mobile_exists_id = $this->Backend_user_model->is_mobile_exists_id($input);
                    if ($is_mobile_exists_id == false) {
                        page_alert_box("error", "Mobile Exist", "This Mobile number is already registered.");
                        redirect(AUTH_PANEL_URL . '/admin/application');
                    }
                    //update image section start
                  if (isset($_FILES['owner_logo']) && $_FILES['owner_logo']['size'] != 0 && $_FILES['owner_logo']['error'] == 0) {
                        $allowed_image_extension = array("png", "jpg", "jpeg");
                        $file_extension = pathinfo($_FILES["owner_logo"]["name"], PATHINFO_EXTENSION);
                        if (!in_array($file_extension, $allowed_image_extension)) {
                            page_alert_box('error', 'Logo', 'Upload valid images. Only PNG and JPEG are allowed.');
                        } else if (($_FILES["owner_logo"]["size"] > 2000000)) {
                            page_alert_box('error', 'Logo', 'Image size exceeds 2MB');
                            die;
                        }
                        if (in_array($file_extension, $allowed_image_extension)) {
                            //--------akhilesh work start---------
                             $image_url = amazon_s3_upload($_FILES['owner_logo'], 'banner', $input['id']);
                             $image_url=str_replace( 'https://'.$this->CHANG_BUCKET_KEY . ".s3." . $this->CHANG_REGION . ".amazonaws.com",$this->CHANG_CLOUDFRONT, $image_url);
                             //-------akhilesh work end-----------
                            if (empty($image_url))
                                $image_url = "default";                             
                            $this->Backend_user_model->update_application_image($input['id'], $image_url);
                        }
                    }

                     if (isset($input['owner_logo']) && !empty($input['owner_logo'])) {
                            $image_url = $input['owner_logo'];
                            if (empty($image_url))
                                $image_url = "default";
                            $this->Backend_user_model->update_application_image($input['id'], $image_url);                               
                    }

                    if (isset($_FILES['web_logo']) && $_FILES['web_logo']['size'] != 0 && $_FILES['web_logo']['error'] == 0) {
                        $allowed_image_extension = array("png", "jpg", "jpeg");
                        $file_extension = pathinfo($_FILES["web_logo"]["name"], PATHINFO_EXTENSION);
                        if (!in_array($file_extension, $allowed_image_extension)) {
                            page_alert_box('error', 'Web', 'Upload valid images. Only PNG and JPEG are allowed.');
                        } else if (($_FILES["web_logo"]["size"] > 2000000)) {
                            page_alert_box('error', 'Web', 'Image size exceeds 2MB');
                            die;
                        }
                        if (in_array($file_extension, $allowed_image_extension)) {
                            $image_url = amazon_s3_upload($_FILES['web_logo'], 'banner', $input['id']);
                             $image_url=str_replace( 'https://'.$this->CHANG_BUCKET_KEY . ".s3." . $this->CHANG_REGION . ".amazonaws.com",$this->CHANG_CLOUDFRONT, $image_url);
                            if (empty($image_url))
                                $image_url = "default";
                            $this->Backend_user_model->update_web_image($input['id'], $image_url);
                        }
                    }
                     if (isset($input['web_logo']) && !empty($input['web_logo'])) {
                            $image_url = $input['web_logo'];
                            if (empty($image_url))
                                $image_url = "default";
                            $this->Backend_user_model->update_web_image($input['id'], $image_url);                              
                    }
                    //login banner start edit
                    if (isset($_FILES['login_banner']) && $_FILES['login_banner']['size'] != 0 && $_FILES['login_banner']['error'] == 0) {
                        $allowed_image_extension = array("png", "jpg", "jpeg");
                        $file_extension = pathinfo($_FILES["login_banner"]["name"], PATHINFO_EXTENSION);
                        if (!in_array($file_extension, $allowed_image_extension)) {
                            page_alert_box('error', 'Web', 'Upload valid images. Only PNG and JPEG are allowed.');
                        } else if (($_FILES["login_banner"]["size"] > 2000000)) {
                            page_alert_box('error', 'Web', 'Image size exceeds 2MB');
                            die;
                        }
                        if (in_array($file_extension, $allowed_image_extension)) {
                            $image_url = amazon_s3_upload($_FILES['login_banner'], 'banner', $input['id']);
                             $image_url=str_replace( 'https://'.$this->CHANG_BUCKET_KEY . ".s3." . $this->CHANG_REGION . ".amazonaws.com",$this->CHANG_CLOUDFRONT, $image_url);
                            if (empty($image_url))
                                $image_url = "default";
                            $this->Backend_user_model->update_login_banner($input['id'], $image_url);
                        }
                    }
                     if (isset($input['login_banner']) && !empty($input['login_banner'])) {
                            $image_url = $input['login_banner'];
                            if (empty($image_url))
                                $image_url = "default";
                            $this->Backend_user_model->update_login_banner($input['id'], $image_url);                              
                    }
                   // echo "<pre>";print_r($_FILES);die;
                   // echo "<pre>"; print_r($input);die;
                    //update image section end
                    $application_id = $this->Backend_user_model->add_edit_application($input);
                    echo ($application_id) ? page_alert_box("success", "Update Application", "Application has been updated successfully.") : page_alert_box("error", "Update Application", "Something went wrong.");
                    //update end here
                } else {
                    $is_email_exists = $this->Backend_user_model->is_email_exists($input);
                    $is_mobile_exists = $this->Backend_user_model->is_mobile_exists($input);
                    if ($is_email_exists == true) {
                        page_alert_box("error", "Email Exist", "This email is already registered.");
                        redirect(AUTH_PANEL_URL . '/admin/application');
                    }
                    if ($is_mobile_exists == true) {
                        page_alert_box("error", "Mobile Exist", "This Mobile number is already registered.", "");
                        redirect(AUTH_PANEL_URL . '/admin/application');
                    }
                    $is_already_exists = $this->Backend_user_model->is_application_exists($input);
                    if (!$is_already_exists) {
                        $application_id = $this->Backend_user_model->add_edit_application($input);
                        if ($application_id) {
                            if (array_key_exists("change_password", $input)) {
                                page_alert_box("success", "Update Application", "Password has been updated successfully.");
                            } else {
                                if (isset($_FILES['owner_logo']) && $_FILES['owner_logo']['size'] != 0 && $_FILES['owner_logo']['error'] == 0) {
                                    $allowed_image_extension = array("png", "jpg", "jpeg");
                                    $file_extension = pathinfo($_FILES["owner_logo"]["name"], PATHINFO_EXTENSION);
                                    if (!in_array($file_extension, $allowed_image_extension)) {
                                        page_alert_box('error', 'Logo', 'Upload valid images. Only PNG and JPEG are allowed.');
                                    } else if (($_FILES["owner_logo"]["size"] > 2000000)) {
                                        page_alert_box('error', 'Logo', 'Image size exceeds 2MB');
                                        die;
                                    }
                                    if (in_array($file_extension, $allowed_image_extension)) {
                                        $image_url = amazon_s3_upload($_FILES['owner_logo'], 'application_management/clientlogo', $application_id);
                                          $image_url=str_replace( 'https://'.$this->CHANG_BUCKET_KEY . ".s3." . $this->CHANG_REGION . ".amazonaws.com",$this->CHANG_CLOUDFRONT, $image_url);
                                        if (empty($image_url))
                                            $image_url = "default";
                                        $this->Backend_user_model->update_application_image($application_id, $image_url);
                                    }
                                }
                                 if (isset($_FILES['web_logo']) && $_FILES['web_logo']['size'] != 0 && $_FILES['web_logo']['error'] == 0) {
                                    $allowed_image_extension = array("png", "jpg", "jpeg");
                                    $file_extension = pathinfo($_FILES["web_logo"]["name"], PATHINFO_EXTENSION);
                                    if (!in_array($file_extension, $allowed_image_extension)) {
                                        page_alert_box('error', 'web logo', 'Upload valid images. Only PNG and JPEG are allowed.');
                                    } else if (($_FILES["web_logo"]["size"] > 2000000)) {
                                        page_alert_box('error', 'web logo', 'Image size exceeds 2MB');
                                        die;
                                    }
                                    if (in_array($file_extension, $allowed_image_extension)) {
                                        $image_url = amazon_s3_upload($_FILES['web_logo'], 'application_management/clientlogo', $application_id);

                                          $image_url=str_replace( 'https://'.$this->CHANG_BUCKET_KEY . ".s3." . $this->CHANG_REGION . ".amazonaws.com",$this->CHANG_CLOUDFRONT, $image_url);

                                        if (empty($image_url))
                                            $image_url = "default";
                                        $this->Backend_user_model->update_web_image($application_id, $image_url);
                                    }
                                }

                                //login banner start add

                                  if (isset($_FILES['login_banner']) && $_FILES['web_logo']['size'] != 0 && $_FILES['login_banner']['error'] == 0) {
                                    $allowed_image_extension = array("png", "jpg", "jpeg");
                                    $file_extension = pathinfo($_FILES["login_banner"]["name"], PATHINFO_EXTENSION);
                                    if (!in_array($file_extension, $allowed_image_extension)) {
                                        page_alert_box('error', 'login banner', 'Upload valid images. Only PNG and JPEG are allowed.');
                                    } else if (($_FILES["login_banner"]["size"] > 2000000)) {
                                        page_alert_box('error', 'login banner', 'Image size exceeds 2MB');
                                        die;
                                    }
                                    if (in_array($file_extension, $allowed_image_extension)) {
                                        $image_url = amazon_s3_upload($_FILES['login_banner'], 'application_management/clientlogo', $application_id);
                                          $image_url=str_replace( 'https://'.$this->CHANG_BUCKET_KEY . ".s3." . $this->CHANG_REGION . ".amazonaws.com",$this->CHANG_CLOUDFRONT, $image_url);
                                        if (empty($image_url))
                                            $image_url = "default";
                                        $this->Backend_user_model->update_login_banner($application_id, $image_url);
                                    }
                                }

                                page_alert_box("success", "Add Application", "Application has been added.");
                                redirect(AUTH_PANEL_URL . 'admin/application');
                            }
                        } else { 
                            page_alert_box("error", "Add Application", "Something went wrong.");
                            redirect(AUTH_PANEL_URL . 'admin/application');
                        }
                    } else {
                        page_alert_box("error", "Add Application", "An User already exists with given Email/Mobile.");
                        redirect(AUTH_PANEL_URL . 'admin/application');
                    }
                }
            }
        } 
        //$view_data['breadcrum'] = "";
        $data['page_data'] = $this->load->view('admin/backend_user/application', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function ajax_applications_list() {  
        $this->Backend_user_model->{__FUNCTION__}();
    }

    public function ajax_delete_content() {
        $this->Backend_user_model->{__FUNCTION__}($this->input->post());
    }

    function assign_permission_master() {
        $input = $this->input->post();
        $view_data['app_id'] = $this->input->get('_id');
        $app_title = $this->db->where("id", $view_data['app_id'])->get("application_manager")->row()->title;
        $view_data['master'] = true;
        if (!$input) {
            $view_data['result'] = $this->Backend_user_model->get_permission_list();
            $view_data['role_group'] = $this->Backend_user_model->get_role_group_master($view_data['app_id']);
            if ($view_data['role_group'])
                $view_data['perm_id'] = $view_data['role_group']['id'];
            if (!$view_data['role_group']) {
                $view_data['role_group']['permission_fk_id'] = $view_data['role_group']['master_perm_ids'] = "";
                $view_data['role_group']['permission_group_name'] = $app_title;
            }
        } else if ($input) {
            // $this->Backend_user_model->add_edit_role($input, $input['app_id'], $input['master']);
            // page_alert_box("success", "Add Permission", "Permission has been updated.");
            // redirect_to_back();

            if(isset($input['user_permission_fk_id'])){
                $this->Backend_user_model->add_edit_role($input, $input['app_id'], $input['master']);
            page_alert_box("success", "Add Permission", "Permission has been updated.");
            redirect_to_back();
            }else{
                  page_alert_box("error", "Add Permission", "Please select at least one permission.!");
                redirect_to_back();
            }

            //    redirect(AUTH_PANEL_URL .'/admin/application');
        }
        //$view_data['categories'] = $this->Backend_user_model->all_cate();
        $view_data['breadcrum'] = array('Application' => "admin/application", $app_title => "#");
       // pre($view_data['cate']);die;
        $data['page_data'] = $this->load->view('admin/backend_user/edit_permission_group', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function temp_app_enter_exit($param) {
        $input = $this->input->post();
        if ($param == 1) {
            $temp_app_name = (isset($input['text']) && $input['text'] !='')?$input['text']:'';//$input['text'];
            $_SESSION['temp_lang_name'] =  $temp_app_name;
            $_SESSION['temp_lang_id'] = (isset($input['id']) && $input['id'] !='')?$input['id']:'';//$input['id'];
            page_alert_box("success", "Behaviour", "Behave Change To " . $temp_app_name);
        } else {
            unset($_SESSION['temp_lang_name'], $_SESSION['temp_lang_id']);
            page_alert_box("warning", "Behaviour", "Behave Change To Global");
        }
        echo json_encode(array("data" => 1));
    }

      public function enable_functionality() {
        $db=$this->db;
        $app_id = $this->input->get("app_id");
        if($this->input->post()){
            //print_r($_POST);die;
            $input=$this->input->post();

            //print_r($input);die;
            // $this->Backend_user_model->add_appid($app_id,$input);
             $functionality = functionality_list($db);
           // print_r($functionality);die;
          foreach($functionality as $f){
               // $f= str_replace(' ',"_", $f['name']);
            $f= str_replace(' ',"_", $f);
              $input[$f]=(isset($input[$f])?"1":"0");               
            }
            // $this->db->where('app_id',$app_id);
            $this->db->update('application_meta',array('functionality'=>json_encode($input)));
           // echo $this->db->last_query();die;
            page_alert_box('success', 'Action performed', 'Enable Functionality successfully');
            redirect_to_back();
        }
        $f_list = $this->db->select("functionality")
                        ->where("app_id", $app_id)
                        ->where("status", 1)
                        ->get("application_meta");
                      // echo  $this->db->last_query();die;
        $view_data["f_list_s"] = ($f_list->num_rows() > 0 )?$f_list->row()->functionality:"";
        $view_data['app_id'] = $app_id;
        $view_data['page'] = "enable_functionality";
        $view_data['page_title'] = "Enable Functionality"; 
        $data['page_data'] = $this->load->view('admin/backend_user/enable_functionality', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }
    
    public function save_assign_functionality() {
        if ($this->input->is_ajax_request()) {
            $appid = $this->input->get("appid");
            $is_exists = $this->db->where("app_id", $appid)->get("application_meta")->num_rows();
//            $perm_data = $this->filter_perm_json($this->input->post("perm_json"));
            if ($is_exists) {
                $this->db->where("app_id", $appid);
                $this->db->set("functionality", $this->input->post("perm_json"));
                $this->db->update("application_meta");
            } else {
                $input_data = array(
                    "app_id" => $appid,
                    "functionality" => $this->input->post("perm_json"),
                    "status" => 1,
                    "created" => time()
                );
                $this->db->insert("application_meta", $input_data);
            }
            if ($this->db->affected_rows() > 0) {
                echo json_encode(array("status" => false, "message" => "Functionality have been assigned."));
                die;
            } else {
                echo json_encode(array("status" => true, "message" => "Something went wrong."));
                die;
            }
        } else {
            echo json_encode(array("status" => true, "message" => "Invalid Request."));
            die;
        }
    }

    public function temp_language($param)
    {
        $input = $this->input->post();
        if ($param == 1) {
            $temp_lang_name = (isset($input['text']) && $input['text'] != '') ? $input['text'] : ''; 
            $_SESSION['temp_lang_id'] = (isset($input['id']) && $input['id'] != '') ? $input['id'] : '';
            page_alert_box("success", "Behaviour", "Behave Change To " . $temp_lang_name);
        } else {
            $_SESSION['temp_lang_id'] = "";
            page_alert_box("success", "Behaviour", "Behave Change To All");
        }
        echo json_encode(array("data" => 1));
    }
    public function temp_menu($param)
    {
        $input = $this->input->post();
        if ($param == 1) {
            $temp_menu = (isset($input['text']) && $input['text'] != '') ? $input['text'] : '';
            $_SESSION['temp_menu_id'] = (isset($input['id']) && $input['id'] != '') ? $input['id'] : '';
            page_alert_box("success", "Menu", "Menu Change To " . $temp_menu);
        } else {
            $_SESSION['temp_menu_id'] = "0";
            page_alert_box("success", "Menu", "Menu Change To Home");
        }
        echo json_encode(array("data" => 1));
    }

}
