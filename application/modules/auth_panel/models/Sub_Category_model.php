<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Sub_Category_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

           
//XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX---> CATEGORY BLOG START <---XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX        


    public function get_category_by_id($id) {
        $this->db->select('id,title,is_popular,thumbnail,status');
        $this->db->where('status !=', '2');
        $this->db->where('id', $id);
        $result = $this->db->get('genres')->row_array();
        return $result;
    }

    public function update_category($data) {
        $this->db->where('id', $data['id']);
        $result = $this->db->update('sub_category', $data);
        return $result;
    }

    public function delete_sub_category($id) {
        $this->db->where('id', $id);
        $data['status'] = '2';
        $result = $this->db->update('sub_category', $data);
        return $result;
    }

//XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX---> CATEGORY BLOG END <---XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX            
}
