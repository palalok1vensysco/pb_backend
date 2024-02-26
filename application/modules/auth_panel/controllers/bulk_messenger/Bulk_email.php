<?php

defined('BASEPATH') OR exit('No direct script access allowed');
   
 class Bulk_email extends MX_Controller {

    function __construct() {
        parent::__construct();
        /* !!!!!! Warning !!!!!!!11
         *  admin panel initialization
         *  do not over-right or remove auth_panel/auth_panel_ini/auth_ini
         */
        modules::run('auth_panel/auth_panel_ini/auth_ini');  
        $this->load->library('form_validation');
    }   
       
    public function send_bulk_email() {
        /* if($_POST){
          $this->trigger_message();
          } */
        $view_data['page'] = 'bulk_email';
        $data['page_data'] = $this->load->view('bulk_messenger/send_email_message', $view_data, TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    } 

}
