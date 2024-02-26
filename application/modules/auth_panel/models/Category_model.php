<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Category_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function insert_category($data) {
        $this->db->insert('category', $data);
        return $this->db->insert_id();
        
    }

    public function add_category($data) {
        $this->db->insert('categories', $data);
        return $this->db->insert_id();
    }
    public function add_aggregator($data) {
        $this->db->insert('aggregator', $data);
       
        return $this->db->insert_id();
    }
    public function get_aggregator_by_id($id){
        $this->db->select('title, thumbnail, bg_video');
        $this->db->where('id',$id);
        $res = $this->db->get('aggregator')->row_array();

        return $res;
    }
    public function get_aggregator_list(){
        $this->db->select('id,name');
        $this->db->where('status', '0');
        $res = $this->db->get('aggregator')->result_array();

        return $res;
    }
    public function get_category_by_id($id) {
        $this->db->select('id,genres,type_id');
        $this->db->where('status', '0');
        $this->db->where('id', $id);
        $result = $this->db->get('category')->row_array();
        return $result;
    }

    public function get_categories_by_id_data($type_id) {
        $this->db->select('id,genres,category_name,type_id');
        $this->db->where('status', '0');
        $this->db->where('type_id', $type_id);
        $result = $this->db->get('category')->row_array();
        return $result;
    }
    

    public function get_categories_by_id($id) {
        $result = $this->db->get('categories')->result_array();
        return $result;
    }

    public function update_category($data) {
        $this->db->where('id', $data['id']);
        $result = $this->db->update('category', $data);
        return $result;
    }
    public function get_generes() {
        $this->db->where('status', '0');
        $result = $this->db->get('genres')->result_array();
        return $result;
    }
    public function get_category_geners($cate_id){
        $this->db->where('cate_id', $cate_id);
        $cates = $this->db->get('category')->row();
        $geners = explode(',',$cates->genres);
        

        if(isset($cates) && !empty($cates)){
            $this->db->select('id,sub_category_name');
            $this->db->where_in('id',$geners);
            $cate = $this->db->get('genres')->result();
        }
        return $cate;
    }

    public function delete_category($id) {
        $this->db->where('id', $id);
        $data['status'] = '2';
        $result = $this->db->update('category', $data);
        return $result;
    }    

    public function category() {
        $result = $this->db->get('categories')->result();
        return $result;
    }          

   public function is_generse_exists($data){
        $this->db->select('id,cate_id,type_id');
        $this->db->where('cate_id', $data['title']);     
        $app = $this->db->get("category");
        return ($app->num_rows() > 0 )?true:false;
    }

    public function get_gener_catgegory_relation_by_category_id($cate_id){
        $this->db->where('category_id', $cate_id);
        $res = $this->db->get('gener_catgegory_relation')->result_array();        
        return $res;
    }
    


}
