<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Premium_section extends MX_Controller {

    function __construct() {
        parent::__construct();
        /* !!!!!! Warning !!!!!!!11
         *  admin panel initialization
         *  do not over-right or remove auth_panel/auth_panel_ini/auth_ini
         */
        modules::run('auth_panel/auth_panel_ini/auth_ini');
        $this->load->library('form_validation');
    }

    public function premium_section() {
        if ($_POST) {
            $array = array(
                "status" => $this->input->post('status')
            );
            $this->db->where('id', 1);
            $this->db->update('premium_section', $array);
            if ($this->db->affected_rows() > 0) {
                page_alert_box("success", "Operation Successful", "Settings changed successfully");
            } else {
                page_alert_box("warning", "Operation Successful", "There was no changes");
            }
        }
        $view_data['page']  = "menu_item";
        $data['page_data'] = $this->load->view('menu_master/premium_section', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    

}
