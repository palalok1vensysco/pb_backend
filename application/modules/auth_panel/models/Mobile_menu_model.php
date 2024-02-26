<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Mobile_menu_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

//XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX---> MOBILE MENU TYPE BLOG START <---XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX    

    public function insert_menu_type_master($data) {
        $this->db->insert('menu_type_master', $data);
        return $this->db->insert_id();
    }

    public function get_menu_type_master_by_id($id) {
        $this->db->select('id,type');
        $this->db->where('id', $id);
        $result = $this->db->get('menu_type_master')->row_array();
        return $result;
    }

    public function update_menu_type_master($data, $id) {
        $this->db->where('id', $id);
        $result = $this->db->update('menu_type_master', $data);
        return $result;
    }

    public function delete_menu_type_master($id) {
        $this->db->where('id', $id);
        $data['status'] = '2';
        $result = $this->db->update('menu_type_master', $data);
        return $result;
    }

//XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX--->  MOBILE MENU TYPE BLOG END <---XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX        
//XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX---> MOBILE MENU BLOG START <---XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX    
    public function get_menu_type_master() {
        $this->db->select('id,category_name');
        $this->db->where('status', '0');
        $result = $this->db->get('category')->result_array();
        return $result;
    }

    public function get_menu_category() {
        $this->db->select('menu_name,menu_code');
        $this->db->where('status', '0');
        $result = $this->db->get('menu_category')->result_array();
        return $result;
    }

    public function insert_mobile_menu($data) {
        $this->db->insert('mobile_menu', $data);
        return $this->db->insert_id();
    }

    public function get_mobile_menu_by_id($id) {
        $this->db->select('id,category_id,menu_title,menu_category');
        $this->db->where('status', '0');
        $this->db->where('id', $id);
        $result = $this->db->get('mobile_menu')->row_array();
        return $result;
    }

    public function update_mobile_menu($data, $id) {
        $this->db->where('id', $id);
        $result = $this->db->update('mobile_menu', $data);
        return $result;
    }

    public function delete_mobile_menu($id) {
        $this->db->where('id', $id);
        $data['status'] = '2';
        $result = $this->db->update('mobile_menu', $data);
        return $result;
    }

    public function lock_unlock_mobile_menu($id, $status) {
        if ($status == 0) {
            $data['status'] = 1;
        }
        if ($status == 1) {
            $data['status'] = 0;
        }
        $data['modified_time'] = milliseconds();
        $this->db->where('id', $id);
        $result = $this->db->update('mobile_menu', $data);
        return $result;
    }

    public function add_to_fixed_mobile_menu($mobile_menu_id) {
        $data = array('mobile_menu_id' => $mobile_menu_id);
        $this->db->insert('fixed_menu_type_master', $data);
        return $this->db->insert_id();
    }

    public function add_to_manage_mobile_menu($mobile_menu_id) {
        $this->db->where('mobile_menu_id', $mobile_menu_id);
        $this->db->delete('fixed_menu_type_master');
        return true;;
    }

//XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX--->  MOBILE MENU BLOG END <---XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX    
}
