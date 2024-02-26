<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Library_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }


    public function get_all_category(){
        return $this->db->select('id, title')->where('status', 0)->get('categories')->result_array();
    }

    public function get_categorywise_geners($category_id) {                       
        $this->db->select('g.id id, g.title title');
        $this->db->from('gener_catgegory_relation gcr');
        $this->db->join('genres g', 'gcr.genres_id = g.id');
        $this->db->where('gcr.category_id', $category_id);
        $res = $this->db->get()->result_array();                    
        return $res;
   }
    public function get_genreswise_show($genre_id) {                       
        $this->db->select('s.id id, s.title title');
        $this->db->from('show_genres_relation sgr');
        $this->db->join('shows s', 'sgr.show_id = s.id');
        $this->db->where('sgr.genres_id', $genre_id);
        $res = $this->db->get()->result_array();                    
        return $res;
   }

    public function get_library_file_by_id($id) {
        $this->db->where('id', $id);
        return $this->db->get('course_topic_file_meta_master')->row_array();
    }

     public function get_episod_file_by_id($id,$type) {
        app_permission("app_id",$this->db);
         if($type=="2"){           
        $this->db->where('id', $id);
        return $this->db->get('premium_episodes')->row_array();
         
        }else{
            $this->db->where('id', $id);
        return $this->db->get('tv_serial_episodes')->row_array();
        }  
    }

    public function get_related_category() {
     
         $where_arr =array();        
           // $where_arr["id"]="1";
        $this->db->where($where_arr);
    	$cate = $this->db->get('categories')->result_array();
        return $cate;
        //pre($this->db->last_query());pre($cate);die;
    }

    public function get_lang() {
        // $this->db->where('find_in_set("'.APP_ID.'", app_id)');
         $appid = ((defined("APP_ID") && APP_ID)? "" . APP_ID . "" : "0");
         $where_arr =array();
       
        $this->db->where($where_arr);
    	$lang = $this->db->get('languages')->result_array();
        return $lang;
        //pre($this->db->last_query());pre($cate);die;
    }

    public function get_category($cate_id){
        $a = array();
        $this->db->where('cate_id', $cate_id);
        // $this->db->where('app_id' , APP_ID);
        $cates = $this->db->get('category')->row();
        
         //pre($this->db->last_query());die;
        if(!empty($cates))
        $geners = explode(',',$cates->genres);
        

        if(isset($cates) && !empty($cates)){
            $this->db->select('id,sub_category_name');
            $this->db->where_in('id',$geners);
            $cate = $this->db->get('sub_category')->result();
            return $cate;
        }else{
            return false;
        }
        // echo $this->db->last_query();die;
        
    }

     function send_notification($message, $user) {
        $this->load->helper("push");

        $push_data = json_encode(
                array(
                    'notification_code' => 90001,
                    'message' => $message,
                    'data' => array("message_target" => ''),
                    'title' =>'Test result evaluated'
                )
        );

         if ($user['device_token'] && $user['device_type'] < 3) {
       // if ($user['device_token']) {
          $result =   generatePush($user['device_type'], $user['device_token'], $push_data);
         // return $result;
        }
    }

}
