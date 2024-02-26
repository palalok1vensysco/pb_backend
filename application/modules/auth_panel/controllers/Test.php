<?php


defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends MX_Controller {

    function __construct() {
        parent::__construct();
        /* !!!!!! Warning !!!!!!!11
         *  admin panel initialization
         *  do not over-right or remove auth_panel/auth_panel_ini/auth_ini
         */
        modules::run('auth_panel/auth_panel_ini/auth_ini');
        $this->load->library('form_validation');
         $this->load->helper("push_helper");
    }

    // public function live() {
    //     $view_data['page'] = "test";
    //     $data['page_data'] = $this->load->view('test', $view_data, TRUE);
    //     echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    // }


    public function notify_user() {

        $device="android";
        $token="fgXnQbH38i8:APA91bEPYAdGdvZC3-7-36LvSp-0GMCR0G-Xo3TboAjL52Qdx-2eiFQgjhP1fgpXDQBJ5BclZE1fmSX2GSvOK7j0JTpj8-U_DjHtundD7pswnITr-LeZhnxiFPEMvWdLt-RF1n7VcGre" ;
        $message = "Hello WOrld";
         $ssd=generatePush($device, $token,$message);
        // if()
        // {
        //    echo "True";
        // }
        // else
        // {
        //     echo "False";
        // }
    }
}
