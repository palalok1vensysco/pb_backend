<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends MX_Controller {

    function __construct() {
        parent::__construct();
        /* !!!!!! Warning !!!!!!!11
         *  admin panel initialization
         *  do not over-right or remove auth_panel/auth_panel_ini/auth_ini
         */
        $this->load->library('form_validation');
        modules::run('auth_panel/auth_panel_ini/auth_ini');
    }
    
    private function update_segment_info(){
            $this->load->helper("template");
        $q = "SELECT ctm.course_fk,GROUP_CONCAT((free_segment_count+paid_segment_count),': ',topic_name SEPARATOR ', ') as info FROM course_topic_master as ctm
                    where (free_segment_count+paid_segment_count)>0 ".app_permission('app_id')."group by ctm.course_fk";
        
        $query = $this->db->query($q);
        $topic = $query->result();
        foreach($topic as $c){
            $this->db->where("id",$c->course_fk);
            app_permission("app_id",$this->db);
            $this->db->set("segment_information",$c->info);
            $this->db->update("course_master");
        }
    }

    public function index(){
//        $this->update_segment_info();
        $data['page_title'] = "API";
        $data['page_data'] = $this->load->view('api/apis', array(), TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

    public function live_watcher() {

        $data['page_title'] = "live_watcher";
        $data['page_data'] = $this->load->view('api/live_watcher', array(), TRUE);
        echo modules::run(AUTH_DEFAULT_TEMPLATE, $data);
    }

}
