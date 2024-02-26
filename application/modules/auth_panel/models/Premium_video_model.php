<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Premium_video_model extends CI_Model {

    function __construct() {
        parent::__construct();
     }

    public function insert_plan($data) {
        $this->db->insert('premium_plan', $data);
        return $this->db->insert_id();
    }
    public function country_plan($insertdata) {
        $this->db->insert('country_price', $insertdata);
        return $this->db->insert_id();

    }
    public function country_plan_by_id($id) {
        $this->db->select('*');
         $this->db->where('status', '0');
        $this->db->where('id', $id);
       // $this->db->where('app_id',APP_ID);
        $result = $this->db->get('country_price')->row_array();
        return $result;
    }

    public function country_update_plan($data) {
       // $data['modified_time'] = milliseconds();
        $this->db->where('id', $data['id']);
       // $this->db->where('app_id',APP_ID);
        $result = $this->db->update('country_price', $data);
        return $result;
    }
   

     public function insert_season_name($data) {
        $this->db->insert('premium_season_name', $data);
        return $this->db->insert_id();
    }

    public function get_season_name() {
        // ram comment
        return true;
        $this->db->select('id,season_name');
        $this->db->where('status', '0');
        // $this->db->where('app_id',APP_ID);
         $result = $this->db->get('premium_season_name')->result_array();
        //pre($this->db->last_query());die;
        return $result;
      }

      public function get_season_id($season_name) {
        $this->db->select('id,season_name');
        $this->db->where('status', '0');
        // $this->db->where('app_id',APP_ID);
         $this->db->where('season_name', $season_name);
        $result = $this->db->get('premium_season_name')->row_array();
        return $result;
      }

    public function get_plan_by_id($id) {
        $this->db->select('*');
        $this->db->where('status', '0');
        $this->db->where('id', $id);
        // $this->db->where('app_id',APP_ID);
        $result = $this->db->get('premium_plan')->row_array();
        return $result;
    }

    public function update_plan($data) {
        $data['modified_time'] = milliseconds();
        $this->db->where('id', $data['id']);
        // $this->db->where('app_id',APP_ID);
        $result = $this->db->update('premium_plan', $data);
        return $result;
    }

    public function delete_premium_plan($id) {
        $this->db->where('id', $id);
        // $this->db->where('app_id',APP_ID);
        $data['status'] = '2';
        $data['modified_time'] = milliseconds();
        $result = $this->db->update('premium_plan', $data);
        return $result;
    }

//XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX---> PREMIUM PLAN BLOG END <---XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX            
//XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX---> CATEGORY BLOG START <---XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX        

    public function insert_category($data) {
        $this->db->insert('premium_category', $data);
        return $this->db->insert_id();
    }

    public function get_category_by_id($id) {
        $this->db->select('id,cat_name');
        $this->db->where('status', '0');
        $this->db->where('id', $id);
        // $this->db->where('app_id',APP_ID);
        $result = $this->db->get('premium_category')->row_array();
        return $result;
    }

    public function update_category($data) {
        $this->db->where('id', $data['id']);
        // $this->db->where('app_id',APP_ID);
        $result = $this->db->update('premium_category', $data);
        return $result;
    }

    public function delete_premium_category($id) {
        $this->db->where('id', $id);
        // $this->db->where('app_id',APP_ID);
        $data['status'] = '2';
        $result = $this->db->update('premium_category', $data);
        return $result;
    }

//XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX---> CATEGORY BLOG END <---XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX            
//XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX---> AUTHOR BLOG START <---XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX    
   

    public function insert_author($data) {
        $this->db->insert('premium_author', $data);
        return $this->db->insert_id();
    }

    public function get_author_by_id($id) {
        $this->db->select('id,p_author_name,cat_ids');
        $this->db->where('status', '0');
        $this->db->where('id', $id);
        // $this->db->where('app_id',APP_ID);
        $result = $this->db->get('premium_author')->row_array();
        return $result;
    }

    public function update_author($data, $id) {
        $this->db->where('id', $id);
        // $this->db->where('app_id',APP_ID);
        $result = $this->db->update('premium_author', $data);
        return $result;
    }

    public function delete_premium_author($id) {
        $this->db->where('id', $id);
        // $this->db->where('app_id',APP_ID);
        $data['status'] = '2';
        $result = $this->db->update('premium_author', $data);
        return $result;
    }

    public function get_categories(){
        $this->db->select('genres');
        $this->db->where('type_id','2');
        $this->db->where('status', '0');
        // $this->db->where('app_id',APP_ID);
        $result=$this->db->get('category')->result_array();
        return $result;
    }
    public function get_sub_category(){
        $this->db->select('id,sub_category_name');
        $this->db->where(array('status'=>'0'));
        // $this->db->where('app_id',APP_ID);
        $result=$this->db->get('sub_category')->result_array();
        return $result;
    }

    public function get_mobile_menu_category() {
        $this->db->select('id,menu_title');
        $this->db->where(array('status' => '0')); 
        // $this->db->where('app_id',APP_ID);
        $result = $this->db->get('mobile_menu')->result_array();
        return $result;
    }
    public function get_android_tv_category() {
        $this->db->select('id,menu_title');
        $this->db->where(array('status' => '0', 'menu_type_id' => '6')); 
        // $this->db->where('app_id',APP_ID);
        $result = $this->db->get('tv_menu')->result_array();
        return $result;
    }

    public function get_authors() {
        $this->db->select('id,name,description');
        $this->db->where('status', '0');
        // $this->db->where('app_id',APP_ID);
        $result = $this->db->get('artists')->result_array();
        return $result;
    }

    public function insert_season($data) { 
       $this->db->insert('premium_season', $data);
     return $this->db->insert_id();
    }

    public function insert_episode($data) { 
        $this->db->insert('premium_episodes', $data);
        return $this->db->insert_id();
    }

    public function lock_unlock_season($id, $status) {
        if ($status == 0) {
            $data['status'] = 1;
        }
        if ($status == 1) {
            $data['status'] = 0;
        }
        $data['modified_time'] = milliseconds();
        $this->db->where('id', $id);
        // $this->db->where('app_id',APP_ID);
        $result = $this->db->update('premium_season', $data);
        return $result;
    }

    public function delete_season($id) {
        $this->db->where('id', $id);
        // $this->db->where('app_id',APP_ID);
        $data['status'] = '2';
        $result = $this->db->update('premium_season', $data);
        return $result;
    }

    public function get_season_details($id) {
        $this->db->select('*');
        $this->db->where('status', '0');
        $this->db->where('id', $id);
        // $this->db->where('app_id',APP_ID);
        $result = $this->db->get('premium_season')->row_array();
        return $result;
    }

    public function update_season($data, $id) {
        $this->db->where('id', $id);
        // $this->db->where('app_id',APP_ID);
        $result = $this->db->update('premium_season', $data);
        return $result;
    }

    public function get_plans() {
        $this->db->where('status', '0');
        // $this->db->where('app_id',APP_ID);
        $result = $this->db->get('premium_plan')->result_array();
        return $result;
    }

    public function get_episode_details($season_id) {
        $this->db->select('*');
        $this->db->where('status', '0');
        $this->db->where('season_id', $season_id);
        // $this->db->where('app_id',APP_ID);
        $result = $this->db->get('premium_episodes')->result_array();
        return $result;
    }

    public function get_episode_by_id($id) {
        $this->db->select('id,season_id,episode_title,episode_description,thumbnail_url,episode_url,ep_no,release_date,runtime,publish,download,url_type');
        $this->db->where('id', $id);
        // $this->db->where('app_id',APP_ID);
        $result = $this->db->get('premium_episodes')->row_array();
        return $result;
    }

    public function update_episode($data, $id) { //echo '<pre>'; print_r($data); die;
       $this->db->where('id', $id);
       // $this->db->where('app_id',APP_ID);
       $result = $this->db->update('premium_episodes', $data);
        return $result;
    }

    public function lock_unlock_episodes($id, $status) {
        if ($status == 0) {
            $data['status'] = 1;
        }
        if ($status == 1) {
            $data['status'] = 0;
        }
        $data['modified_time'] = milliseconds();
        $this->db->where('id', $id);
        // $this->db->where('app_id',APP_ID);
        $result = $this->db->update('premium_episodes', $data);
        return $result;
    }

    public function delete_episodes($id,$season_id,$cate) {
        if($cate == 2){
            $this->db->where('id', $id);
            // $this->db->where('app_id',APP_ID);
            $this->db->where('season_id', $season_id);
            $data['status'] = '2';
            $result = $this->db->update('premium_episodes', $data);
        }elseif ($cate == 3) {
            $this->db->where('id', $id);
            // $this->db->where('app_id',APP_ID);
            $this->db->where('season_id', $season_id);
            $data['status'] = '2';
            $result = $this->db->update('tv_serial_episodes', $data);
        }
        
        return $result;
    }

    public function get_time_frames($season_id,$cat_id) {
        $this->db->where('status', '0');
        $this->db->where('web_series_id', $season_id);
        $this->db->where('category_id', $cat_id);
        // $this->db->where('app_id',APP_ID);
        $result = $this->db->get('time_frame')->result_array();
        return $result;
    }

     public function insert_frame($data) { 
       $this->db->insert('time_frame', $data);
       return $this->db->insert_id();
    } 

 public function delete_frame($id) {
        $data['status'] = 2;
        $this->db->where('id', $id);
        // $this->db->where('app_id',APP_ID);
        $result = $this->db->update('time_frame', $data);
        return $result;
    }
    public function get_video_list() {
        $this->db->where('status', '0');
        // $this->db->where('app_id',APP_ID);
        return $this->db->get("premium_season")->result_array();
    }

       public function block_user_plan($id, $status) {
        $data = array('status' => $status);
        $this->db->where('id', $id);
        return $this->db->update("premium_plan", $data);
    }
         
}
