<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Configuration extends MX_Controller {

    function __construct() {
        parent::__construct();
        /* !!!!!! Warning !!!!!!!11
         *  admin panel initialization
         *  do not over-right or remove auth_panel/auth_panel_ini/auth_ini
         */
        modules::run('auth_panel/auth_panel_ini/auth_ini');
        $this->load->library('form_validation');
         $this->load->helper('services');
    }

    public function device_limit() {
        if ($_POST) {
            $array = array(
                "device_limit" => $this->input->post('device_limit')
            );

            $this->db->where('id', 1);
            $this->db->update('configuration', $array);
            if ($this->db->affected_rows() > 0) {
                page_alert_box("success", "Operation Successful", "Configuration changed successfully");
            } else {
                page_alert_box("warning", "Operation Successful", "There was no changes");
            }
        }
        $view_data['page']  = "configuration";
        $data['page_data'] = $this->load->view('configuration/configuration_view', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }
    public function social_login() {
        if ($_POST) {
            $array = array(
                "is_apple" => $this->input->post('is_apple'),
                "is_google" => $this->input->post('is_google'),
                "is_facebook" => $this->input->post('is_facebook')
            );
            $this->db->where('id', $_POST['id']);
            $this->db->update('social_login', $array);
            if ($this->db->affected_rows() > 0) {
                page_alert_box("success", "Operation Successful", "Social login status changed successfully");
            } else {
                page_alert_box("warning", "Operation Successful", "There was no changes");
            }
        }
        $view_data['page']  = "configuration";
        $data['page_data'] = $this->load->view('configuration/configuration_view', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    

}
