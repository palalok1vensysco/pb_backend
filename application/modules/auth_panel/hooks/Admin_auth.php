<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_auth extends MX_Controller {

    function __construct() {
        
    }

    public function index() {
        if ($this->router->fetch_module() == "auth_panel") {
            $this->load->library("session");
           $_POST = $this->security->xss_clean($_POST);
           $_GET = $this->security->xss_clean($_GET);
            $restrict = false;
            $host = $_SERVER['HTTP_HOST'] ?? "";
            if ($host != "localhost") {
//                $restrict = true;
            }
            
            //Get Project Name
            $this->load->helper("custom");
            $app_id = (defined("APP_ID") && APP_ID)?APP_ID:(!empty($this->session->userdata("temp_app_id"))?$this->session->userdata("temp_app_id"):'0');
             define("CONFIG_PROJECT_NICK_NAME",str_replace(" ","",CONFIG_PROJECT_FULL_NAME));
            
            
            if ($restrict) {
                $response = array('error' => 'You are not authorized by ' . CONFIG_PROJECT_FULL_NAME . ' Team for access this page.');
                $this->output
                        ->set_status_header(200)
                        ->set_content_type('application/json', 'utf-8')
                        ->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
                        ->_display();
                exit;
            }

            $class = $this->router->fetch_class();
            $method = $this->router->fetch_method();
            $active_bar = $class . "/" . $method;

            $GLOBALS['perm_url'] = strtolower($active_bar);
            $GLOBALS['admin_id'] = 0;
            $GLOBALS['perm'] = array();
            if ($class == "hook" || $active_bar == "login/logout" || ($active_bar == "admin/otp_authentication" && !$this->session->userdata('active_user_verified'))) {
                
            } else {
                switch ($class) {
                    case "web_user":
                    case "instructor_user_details":
                        if ($this->input->get("user"))
                            $active_bar .= "?user=" . $this->input->get("user");
                        break;
                    case "current_affair":
                        if ($this->input->get("post_type"))
                            $active_bar .= "?post_type=" . $this->input->get("post_type");
                        break;
                    case "course_transactions":
                        if ($this->input->get("status"))
                            $active_bar .= "?status=" . $this->input->get("status");
                        break;
                }
                $session_userdata = $this->session->userdata();

                //-----------new start----
                  if (isset($session_userdata['active_user_data'])) 
                {
                    $check_this = $this->db->select('*')->from('backend_user_permission')->where("permission_perm LIKE '%$active_bar%'")->get()->row_array();
                    $this->db->where('id', $session_userdata['active_user_data']->perm_id);
                     $this->db->like('permission_fk_id', $check_this['id']);
                    $data_check = $this->db->get('permission_group')->row_array();
                    if (empty($data_check)) 
                    {

                        $this->not_authorized(0);
                    }
                }
                //-----------new end ----------
                if ($active_bar != "login/index" || $active_bar != 'login/encrypt_str' || $active_bar != 'decrypt_str') {
                    $this->load->helper("template");
                    if(isset($session_userdata['active_backend_user_id'])) {
                        $GLOBALS['admin_id'] = $session_userdata['active_backend_user_id'];
                        if (isset($_SESSION['temp_app_id']) && $_SESSION['temp_app_id']!='')
                            $permission = $this->db->query("SELECT pg.permission_fk_id FROM permission_group pg where type=1 ")->row_array();
                        else
                            $permission = $this->db->query("SELECT pg.permission_fk_id FROM backend_user as bu left join permission_group as pg on pg.id = bu.perm_id where bu.id = '" . $session_userdata['active_backend_user_id'] . "' ")->row_array();
                        // pre($permission);die;
                         if ($permission['permission_fk_id']) {
                            $this->db->where('FIND_IN_SET(id,"' . $permission['permission_fk_id'] . '")<>0');
                            $GLOBALS['perm'] = $perm = $this->db->get('backend_user_permission')->result_array();
                           // echo "<pre>";print_r($perm);
                            // $GLOBALS['app'] = array_search("admin/application", array_column($perm, 'permission_perm'));
                           if(!defined("LANG_ID")){
                                define("LANG_ID", $this->session->userdata('temp_lang_id') ??   $this->session->userdata('lang_id'));
                           }
                        } else {
                            $this->not_authorized(1);
                        }
                    } else if (!$session_userdata) {
                        $this->not_authorized(1);
                    }
                }
            }
        }
    }

    private function not_authorized($type) {
        //print_r($type);die;
        if ($type == 0) {
            if ($this->input->is_ajax_request()) { //pre($_REQUEST['columns']); die('landing');
                if (isset($_REQUEST['columns'])) {
                    echo json_encode(array(
                        "draw" => intval(0), // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
                        "recordsTotal" => intval(0), // total number of records
                        "recordsFiltered" => intval(0), // total number of records after searching, if there is no searching then totalFiltered = totalData
                        "data" => array(), // total data array
                        "message" => "NOT_AUTHORIZE"
                    ));
                } else {
                    echo json_encode(array("message" => "NOT_AUTHORIZE", 'type' => "error", "title" => "Permission", "message" => "You have not permission for sdfhsdghsdghg this task."));
                }
                die;
            } else {
                redirect("auth_panel/auth_panel_ini/not_authorize");
            }
        } else {
            if ($this->input->is_ajax_request()) {
                echo json_encode(array("message" => "LOGOUT", 'type' => "error", "title" => "Permission", "message" => "You have not permission for perform this task."));
                die;
            } else {
                redirect("auth_panel/login/logout");
            }
        }
    }

}
