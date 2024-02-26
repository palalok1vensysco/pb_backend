<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class SeasonModel extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function insert_season($data) {
        if($this->input->get('season_id')){
            unset($data['created_at']);
            $data['modified_at'] = time();
            $this->db->update('seasons', $data, ['id' => $this->input->get('season_id')]);
            return $this->db->affected_rows();
        }else{
            $this->db->insert('seasons', $data);        
            return $this->db->insert_id();
        }       
    }
    public function delete_season($id) {
        $this->db->where('id', $id);
        $data['status'] = '2';
        $result = $this->db->update('seasons', $data);
        return $result;
    }
    public function update_season_status($id,$status) {        
        $this->db->where('id', $id);
        $data['status'] = ($status == 0) ? 1 : 0;        
        $result = $this->db->update('seasons', $data);
        return $result;
    }

    public function get_seasons_by_id($id){
        // $app_id = (defined("APP_ID") && APP_ID)?APP_ID:0;
        $this->db->select('id,title,thumbnail,status');
        $this->db->where('id',$id);
        $res = $this->db->get('seasons')->row_array();
        return $res;
    }
    

    public function update_season($data,$id) {
        $this->db->where('id', $id);
        $result = $this->db->update('seasons', $data);
        return $result;
    }

    public function get_season_by_show_id($show_id){        
        $this->db->select('id,title,thumbnail');
        $this->db->where('show_id',$show_id);
        $this->db->where('status',0);
        $res = $this->db->get('seasons')->result_array();
        return $res;
    }
    


    
//     public function add_aggregator($data) {
//         $this->db->insert('aggregator', $data);
       
//         return $this->db->insert_id();
//     }
  
//     public function get_aggregator_list(){
//         // $app_id = (defined("APP_ID") && APP_ID)?APP_ID:0;
//         $this->db->select('id,name');
//         $this->db->where('status', '0');
//         $res = $this->db->get('aggregator')->result_array();

//         return $res;
//     }
//     public function get_category_by_id($id) {
//         // $app_id = (defined("APP_ID") && APP_ID)?APP_ID:0; 
//         $this->db->select('id,genres,type_id');
//         $this->db->where('status', '0');
//         $this->db->where('id', $id);
//        // $this->db->where('app_id', $app_id);
//         $result = $this->db->get('category')->row_array();
//        // pre($this->db->last_query()); die;
//         return $result;
//     }

//     public function get_categories_by_id_data($type_id) {
//         // $app_id = (defined("APP_ID") && APP_ID)?APP_ID:0;
//         $this->db->select('id,genres,category_name,type_id');
//         $this->db->where('status', '0');
//         $this->db->where('type_id', $type_id);
//        // $this->db->where('app_id', $app_id);
//         $result = $this->db->get('category')->row_array();
//         return $result;
//     }
    

//     public function get_categories_by_id($id) {
//        //  $app_id = (defined("APP_ID") && APP_ID)?APP_ID:0; 
//          // $this->db->where('find_in_set("'.APP_ID.'", app_id)');
//         // $this->db->where('app_id', $app_id);
//         $result = $this->db->get('categories')->result_array();

//         // pre($this->db->last_query()); die;

//         return $result;
//     }

//     public function update_category($data) {
//         $this->db->where('id', $data['id']);
//         $result = $this->db->update('category', $data);
//         return $result;
//     }
//     public function get_generes() {
//          // $this->db->where('app_id', APP_ID);
//         $this->db->where('status', '0');
//         $result = $this->db->get('sub_category')->result_array();
//       //  echo $this->db->last_query();die;
//         return $result;
//     }
//     public function get_category_geners($cate_id){
//         // $app_id = (defined("APP_ID") && APP_ID)?APP_ID:0;
//         $this->db->where('cate_id', $cate_id);
//         // $this->db->where('app_id' , APP_ID);
//         $cates = $this->db->get('category')->row();
        
//          //pre($this->db->last_query());die;
//         $geners = explode(',',$cates->genres);
        

//         if(isset($cates) && !empty($cates)){
//             $this->db->select('id,sub_category_name');
//             $this->db->where_in('id',$geners);
//             $cate = $this->db->get('sub_category')->result();
//         }
//         return $cate;
//     }

    

//     public function category() {
//         $result = $this->db->get('categories')->result();
//         return $result;
//     }          

//    public function is_generse_exists($data){
//     //print_r($data);die;
//             // $app_id = (defined("APP_ID") && APP_ID)?APP_ID:0; 
//         $this->db->select('id,cate_id,type_id');
//         $this->db->where('cate_id', $data['cat_name']);
//         // $this->db->where('app_id', $app_id);      
//         $app = $this->db->get("category");
//        // echo $this->db->last_query();die;
//         return ($app->num_rows() > 0 )?true:false;
//     }
}
