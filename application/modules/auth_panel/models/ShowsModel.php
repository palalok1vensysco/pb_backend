<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ShowsModel extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function insert($data) {
        if(!empty($data['shows_id'])) {
            unset($data['created_at']);
            $data['modified_at'] = time();
            $shows_id = $data['shows_id'];
            unset($data['shows_id']);
            $this->db->update('shows', $data, ['id' => $shows_id]);
            return $this->db->affected_rows();
        }else{
            $this->db->insert('shows', $data);        
            return $this->db->insert_id();
        }
    }
    public function delete($id) {
        $this->db->where('id', $id);
        $data['status'] = '2';
        $result = $this->db->update('shows', $data);
        return $result;
    }
    public function update_status($id,$status) {        
        $this->db->where('id', $id);
        $data['status'] = ($status == 0) ? 1 : 0;        
        $result = $this->db->update('shows', $data);
        return $result;
    }

    public function get_by_id($id){     
        $this->db->select('s.*, group_concat(sgr.genres_id) as genres_id');
        $this->db->from('shows s');        
        $this->db->where('s.status !=',2);
        $this->db->where('s.id',$id);
        $this->db->join('show_genres_relation sgr', 'sgr.show_id = s.id', 'left');
        $this->db->group_by('s.id');
        $res = $this->db->get()->row_array();
        return $res;
    }

    public function update($data,$id) {
        $this->db->where('id', $id);
        $result = $this->db->update('shows', $data);
        return $result;
    }
    
    public function add_aggregator($data) {
        $this->db->insert('aggregator', $data);
       
        return $this->db->insert_id();
    }
  
    public function get_aggregator_list(){
        // $app_id = (defined("APP_ID") && APP_ID)?APP_ID:0;
        $this->db->select('id,name');
        $this->db->where('status', '0');
        $res = $this->db->get('aggregator')->result_array();

        return $res;
    }
    public function get_category_by_id($id) {
        // $app_id = (defined("APP_ID") && APP_ID)?APP_ID:0; 
        $this->db->select('id,genres,type_id');
        $this->db->where('status', '0');
        $this->db->where('id', $id);
       // $this->db->where('app_id', $app_id);
        $result = $this->db->get('category')->row_array();
       // pre($this->db->last_query()); die;
        return $result;
    }

    public function get_categories_by_id_data($type_id) {
        // $app_id = (defined("APP_ID") && APP_ID)?APP_ID:0;
        $this->db->select('id,genres,category_name,type_id');
        $this->db->where('status', '0');
        $this->db->where('type_id', $type_id);
       // $this->db->where('app_id', $app_id);
        $result = $this->db->get('category')->row_array();
        return $result;
    }
    

    public function get_categories_by_id($id) {
       //  $app_id = (defined("APP_ID") && APP_ID)?APP_ID:0; 
         // $this->db->where('find_in_set("'.APP_ID.'", app_id)');
        // $this->db->where('app_id', $app_id);
        $result = $this->db->get('categories')->result_array();

        // pre($this->db->last_query()); die;

        return $result;
    }

    public function update_category($data) {
        $this->db->where('id', $data['id']);
        $result = $this->db->update('category', $data);
        return $result;
    }
    public function get_generes() {
         // $this->db->where('app_id', APP_ID);
        $this->db->where('status', '0');
        $result = $this->db->get('sub_category')->result_array();
      //  echo $this->db->last_query();die;
        return $result;
    }
    public function get_category_geners($cate_id){
        // $app_id = (defined("APP_ID") && APP_ID)?APP_ID:0;
        $this->db->where('cate_id', $cate_id);
        // $this->db->where('app_id' , APP_ID);
        $cates = $this->db->get('category')->row();
        
         //pre($this->db->last_query());die;
        $geners = explode(',',$cates->genres);
        

        if(isset($cates) && !empty($cates)){
            $this->db->select('id,sub_category_name');
            $this->db->where_in('id',$geners);
            $cate = $this->db->get('sub_category')->result();
        }
        return $cate;
    }

    

    public function category() {
        $result = $this->db->get('categories')->result();
        return $result;
    }          

   public function is_generse_exists($data){
    //print_r($data);die;
            // $app_id = (defined("APP_ID") && APP_ID)?APP_ID:0; 
        $this->db->select('id,cate_id,type_id');
        $this->db->where('cate_id', $data['cat_name']);
        // $this->db->where('app_id', $app_id);      
        $app = $this->db->get("category");
       // echo $this->db->last_query();die;
        return ($app->num_rows() > 0 )?true:false;
    }
}
