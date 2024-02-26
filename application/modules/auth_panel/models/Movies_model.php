<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Movies_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function get_video_list() {
        $this->db->where('status', '0');
        return $this->db->get("movies")->result_array();
    }

    public function get_video($id) {
       // $id = $id['id'];
        $this->db->where('id', $id);
        $result = $this->db->get('movies')->row_array();
        return $result;
    }
    
    public function get_category() {
        $this->db->where('status', '0');
        //$this->db->where('app_id',APP_ID);
        $result = $this->db->get('sub_category')->result_array();
        return $result;
    }

    public function get_categories(){
        $this->db->select('genres');
        $this->db->where('type_id','1');
        $this->db->where('status', '0');
        //$this->db->where('app_id',APP_ID);
        $result=$this->db->get('category')->result_array();
        return $result;
    }
    public function get_sub_category(){
        $this->db->select('id,sub_category_name');
        $this->db->where(array('status'=>'0'));
        //$this->db->where('app_id',APP_ID);
        $result=$this->db->get('sub_category')->result_array();
        return $result;
    }

    public function get_plans() {
        $this->db->where('status', '0');
       // $this->db->where('app_id',APP_ID);
        $result = $this->db->get('premium_plan')->result_array();
        return $result;
    }

public function get_time_frames($season_id,$cat_id) {
        $this->db->where('status', '0');
        $this->db->where('movie_id', $season_id);
        $this->db->where('category_id', $cat_id);
       // $this->db->where('app_id',APP_ID);
        $result = $this->db->get('time_frame')->result_array();
        return $result;
    }

    public function get_video_by_id($id) {
        $this->db->where('id', $id);
       // $this->db->where('app_id',APP_ID);
        $result = $this->db->get('movies')->row_array();
        return $result;
    }

    public function insert_movie($data) { 
       $this->db->insert('movies', $data);
       return $this->db->insert_id();
    } 
     public function insert_frame($data) { 
       $this->db->insert('time_frame', $data);
       return $this->db->insert_id();
    } 

    public function update_video($data) { //echo '<pre>'; print_r($data); die;
         $id = $data['id'];
        $this->db->where('id', $id);
     //   $this->db->where('app_id',APP_ID);
       $result = $this->db->update('movies', $data);
        return $result;
    }

    

    public function delete_video($id) {
        $data['status'] = 2;
        $this->db->where('id', $id);
        $this->db->where('app_id',APP_ID);
        $result = $this->db->update('movies', $data);
        return $result;
    }

    public function delete_frame($id) {
        $data['status'] = 2;
        $this->db->where('id', $id);
        $this->db->where('app_id',APP_ID);
        $result = $this->db->update('time_frame', $data);
        return $result;
    }

    public function get_default_artist() {
        $this->db->select('id,name,description');
        $this->db->where(array('name' => 'ACTOR', 'description' => 'nothing'));
       // $this->db->where('app_id',APP_ID);
        $result = $this->db->get('artists')->row_array();
        return $result;
    }

     public function get_default_plans() {
        $this->db->select('id,plan_name');
        $result = $this->db->get('premium_plan')->row_array();
        return $result;
    }

    public function added_videos() {
        $query = $this->db->query("SELECT id, video_title 
                from movies
                where category=17 AND status=0");
        return $query->result_array();
    }

    public function lock_unlock_movie($id, $status) {
        if ($status == 0) {
            $data['status'] = 1;
        }
        if ($status == 1) {
            $data['status'] = 0;
        }
        $data['modified_time'] = milliseconds();
        $this->db->where('id', $id);
        $this->db->where('app_id',APP_ID);
        $result = $this->db->update('movies', $data);
        return $result;
    }   

}
