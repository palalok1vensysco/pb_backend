<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Admin extends MX_Controller {
    function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->library('email');
        $this->load->library('form_validation', 'uploads');
        $this->load->helper(array('form', 'url'));
        $this->load->helper('html');
        //$this->load->helper('common_helper');
        $this->load->helper('custom_helper');
        $this->load->helper('push_helper');
        $this->load->model('Admin_model');
        //$this->load->model('Users_model');
        $this->load->helper('cookie');
        //$this->form_validation->CI = & $this;
        if ($this->router->fetch_method() != "index") {//pre($this->session->userdata['id']);
            if (empty($this->session->userdata['backend_user'])) { 
               redirect('admin_panel/Admin');
            }
        }
    }
      ////////////////////////////////////////
     //                                    //
    //   Modified by: Alok Kumar Pal      //
   //   Created On: 17 January-2022      //
  //                                    //
 ////////////////////////////////////////
    

    function set_login_cookie($input) {
        if (isset($input['remember']) and $input['remember'] == "Remember Me") {
            $this->input->set_cookie('user_name', $input['remember'], time() + 86500, '', '/');
            $this->input->set_cookie('user_password', $input['password'], time() + 86500, '', '/');
        }
        return true;
    }
    
    public function index() {
        if (isset($this->session->userdata['profile'])) { //die('working');
            $data = array('title' => 'Dashboard', 'pageTitle' => 'Dashboard');
            redirect('api_doc/api/api_list');die;
        }
        if ($this->input->post()) { 
            $this->form_validation->set_rules('username', 'User Name', 'trim|required');
            $this->form_validation->set_rules('password', 'Password', 'trim|required');
            if ($this->form_validation->run() == TRUE) {
                $this->set_login_cookie($this->input->post());
                $row = $this->Admin_model->is_login_success($this->input->post());
                if ($row) { 
                    $mySession = array('id' => $row->id, 'name' => $row->name, 'email' => $row->email, 'user_type' => $row->user_type, 'profile_image' => $row->profile_image);
                    $this->session->set_userdata(array("profile"=>$mySession));
                    redirect('api_doc/api/api_list'); die;
                }else{
                    $this->session->set_flashdata('error_message', 'Wrong username and password');
                }
            }
        }
        $data = array('title' => 'Admin Portal Login Page');
        $this->load->view('login/login', $data);
    }

     public function logout() {
        $this->session->unset_userdata();
        $this->session->sess_destroy();
        redirect('admin_panel/Admin');
    }

}
