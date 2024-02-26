<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_panel_ini extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('template');
    }

    public function auth_ini() {
        /* default template path */
        if (!defined('AUTH_TEMPLATE')) {
            define("AUTH_TEMPLATE", "auth_panel/template/");
        }

        /* default template conatant name  */
        if (!defined('AUTH_DEFAULT_TEMPLATE')) {
            define("AUTH_DEFAULT_TEMPLATE", "auth_panel/template/call_default_template");
        }

        /* default auth panel assets path  */
        if (!defined('AUTH_PANEL_URL')) {
            define("AUTH_PANEL_URL", base_url() . 'auth_panel/');
        }

        /* default auth files assets path */
        if (!defined('AUTH_ASSETS')) {
            define("AUTH_ASSETS", base_url() . "auth_panel_assets/");
        }
        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        if (!defined("FULL_URL"))
            define("FULL_URL", $actual_link);

        $active_backend_user_flag = $this->session->userdata('active_backend_user_flag');
        $active_backend_user_id = $this->session->userdata('active_backend_user_id');

        /* if ajax request and session is not set */
        // if ($this->input->is_ajax_request() && $active_backend_user_flag != True ){
        //   echo json_encode(array('status'=>false,'error_code'=>10001,'message'=>'Authentication Failure'));
        //   die;
        // }


        if (!$this->input->is_ajax_request() && $active_backend_user_flag != True) {
            redirect(site_url('auth_panel/login/index?return=' . urlencode(FULL_URL)));
            die;
        }
        if (!$this->session->userdata('active_user_data')->mobile && $this->router->fetch_class() != "profile_edit") {
            page_alert_box('warning', "Profile Complete", "Please update your mobile number.");
            redirect(AUTH_PANEL_URL . "profile/profile_edit?return=" . urlencode(FULL_URL));
        } else if ($this->session->userdata('active_user_data')->mobile && !$this->session->userdata('active_user_verified') && $this->router->fetch_method() != "otp_authentication") {
            redirect(AUTH_PANEL_URL . "admin/otp_authentication?return=" . urlencode(FULL_URL));
        }

        if (!defined('WEB_PANEL_URL')) {
            define("WEB_PANEL_URL", base_url() . 'index.php/web_panel/');
        }
    }

    public function not_authorize() {
        modules::run('auth_panel/auth_panel_ini/auth_ini');
        $data['page_data'] = $this->load->view('template/not_authorize', array(), TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

}
