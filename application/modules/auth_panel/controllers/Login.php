<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->library('form_validation', 'uploads');
        $this->load->helper("template");
        $this->load->model("Backend_user_model");
    }

    
      public function response(){
     $this->load->view('response');

    }


     public function captcha() {
        $random_alpha = md5(rand());
        $captcha_code = substr($random_alpha, 0, 6);
        $_SESSION["captcha"] = $captcha_code;
        $target_layer = imagecreatetruecolor(97, 34);
        $captcha_background = imagecolorallocate($target_layer, 1, 124, 194);
        imagefill($target_layer, 20, 20, $captcha_background);
        $captcha_text_color = imagecolorallocate($target_layer, 255, 255, 255);
        imagestring($target_layer, 8, 20, 10, $captcha_code, $captcha_text_color);
        header("Content-type: image/jpeg");
        imagejpeg($target_layer);
    }

    // private function random_strings($length_of_string) {
    //     $str_result = '0123456789';
    //     return substr(str_shuffle($str_result), 0, $length_of_string);
    // }


    public function index() { 
        $error['error'] = "";
        if ($this->session->userdata('active_backend_user_flag') && $this->session->userdata('active_backend_user_flag')) {
            redirect(site_url('auth_panel/admin/index'));
            die;
        }
       $captcha = $_SESSION['captcha'] ?? "";
        if ($this->input->post()) {
            $this->form_validation->set_error_delimiters(' ', ' ');
            $this->form_validation->set_rules('password', 'Password', 'required|trim');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email|trim');

            if ($this->form_validation->run() == FALSE) {
                
            }
            if ($captcha != $this->input->post("captcha")) {
                $error['error'] = "Invalid Captcha !!";
           } else {
                
                $this->db->Where("email", $this->input->post('email'));
                $this->db->Where("status !=", '2');
                $result = $this->db->get('backend_user')->row();
                if (!empty($result) && $result->status == 0) {
                    if (password_verify($this->input->post('password'), $result->password)) {
                    $is_verfied = 1;//change to 0 for otp screen
                    $remote_ip = $_SERVER['REMOTE_ADDR'] ?? "";
                    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? "";
                    if ($result->ip_address == $remote_ip && $result->user_agent == $user_agent)
                        $is_verfied = 1;
                    $app_data = $this->Backend_user_model->get_app_details();
                    unset($result->user_agent, $result->ip_address);
                    $newdata = array(
                        'active_backend_user_flag' => True,
                        'active_backend_user_id' => $result->id,
                        'active_user_data' => $result,
                        'active_user_verified' => $is_verfied,
                        'lang_id' => 1,
                        'lang_name' => 1,
                        'active_app_data'=>($app_data?$app_data:""),
                    );
                    //  if ($result->instructor_id == 0) {
                        $this->session->set_userdata($newdata);
                        backend_log_genration($this, 'Logged In', 'LOGIN');
                        unset($_SESSION['captcha']);
                        $url = $this->input->get("return");
                        if (!$url)
                            $url = site_url('auth_panel/admin/index');
                        redirect($url);
                        die;
                    // } elseif ($result->instructor_id != 0) {
                    //     unset($_SESSION['captcha']);
                    //     $this->session->set_userdata($newdata);
                    //     redirect(site_url('auth_panel/admin/index'));
                    //     die;
                    // }
                }else{
                    $error['error'] = "Invalid Credentials !!";
                }
            }
                    /*                     * *****  Check For Instructor User Ends ****** */
                 elseif (!empty($result) && $result->status == 1) {
                    $error['error'] = "Blocked Account !! Please contact the admin.";
                } elseif (empty($result) && $this->input->post('email') != '' && $this->input->post('password') != '') {
                    $error['error'] = "Invalid Credentials !!";
                }
            }
            // }
        }
        $error['full_url'] = $this->input->get("return");
        // $error['app_detail']=$this->db->get_where("application_manager",array('admin_domain'=>$_SERVER['HTTP_HOST']))->row_array();
        $this->load->view('login/login', $error);
    }

    public function logout() {
        $this->session->sess_destroy();
        redirect(site_url('auth_panel/login/index'));
    }

    public function encrypt_str(){
        if($this->input->post()){
            $encrypt_str = $this->input->post("encrypt_json");
            $token = $this->input->post("token");
            $this->load->helper("aes");
            $result = aes_cbc_encryption($encrypt_str,$token);
            echo $result;
        }
    }

    public function decrypt_str(){
        if($this->input->post()){
            $decrypt_str = $this->input->post("decrypt_str");
            $token = $this->input->post("token");
            $this->load->helper("aes");
            $result = json_decode(aes_cbc_decryption($decrypt_str,$token));
            print_r($result);
        }
    }

}
