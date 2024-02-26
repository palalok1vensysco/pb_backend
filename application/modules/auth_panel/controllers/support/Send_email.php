<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Send_email extends MX_Controller {

    function __construct() {
        parent::__construct();
        /* !!!!!! Warning !!!!!!!11
         *  admin panel initialization
         *  do not over-right or remove auth_panel/auth_panel_ini/auth_ini
         */
        modules::run('auth_panel/auth_panel_ini/auth_ini');
        $this->load->library('form_validation');
        $this->load->helper("services");
        $this->load->library('aws_s3_file_upload');
        $this->load->helper('html');
        $this->load->library('email');
    }

    public function index() {
        if ($this->input->post()) {
            $this->email->from('info@totalbhakti.com', 'SANSKAR');
//                    $this->email->from('sanskartvapp@gmail.com', 'SANSKAR');
            $this->email->to($this->input->post('to_email'));
            $this->email->cc($this->input->post('cc_email'));
            $this->email->subject($this->input->post('subject'));
            //$this->email->message($this->load->view('', $email_data, True));
//            $this->email->attach("https://bhaktiappproduction.s3.ap-south-1.amazonaws.com/course_file_meta/3409841Ariph.jpg", "inline");
            $this->email->message($this->input->post('message'));
            $this->email->set_mailtype("html");
//            $this->email->set_mailtype("text");
            $this->email->send();
            //echo '<pre>'; print_r($this->input->post()); die;
            page_alert_box('success', 'Email sent', 'Email has been sent to ' . $this->input->post('to_email') . ' successfully');
            redirect(AUTH_PANEL_URL . 'support/send_email');
        }
        $view_data['page'] = "send_email";
        $data['page_data'] = $this->load->view('support/send_email', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

}
