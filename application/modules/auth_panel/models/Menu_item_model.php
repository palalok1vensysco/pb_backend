<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Menu_item_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }


//XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX---> TV MENU BLOG START <---XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX    
    public function get_menu_type_master() {
        $this->db->select('id,type');
        $this->db->where('status', '0');
        $result = $this->db->get('menu_type_master')->result_array();
        return $result;
    }

    public function insert_menu_item($data) {
        $this->db->insert('menu_item', $data);
        return $this->db->insert_id();
    }

    public function get_menu_item_by_id($id) {
        $this->db->select('id,menu_type_id,menu_title');
        $this->db->where('status', '0');
        $this->db->where('id', $id);
        $result = $this->db->get('menu_item')->row_array();
        return $result;
    }

    public function update_menu_item($data, $id) {
        $this->db->where('id', $id);
        $result = $this->db->update('menu_item', $data);
        return $result;
    }

    public function delete_menu_item($id) {
        $this->db->where('id', $id);
        $data['status'] = '2';
        $result = $this->db->update('menu_item', $data);
        return $result;
    }

    public function lock_unlock_menu_item($id, $status) {
        if ($status == 0) {
            $data['status'] = 1;
        }
        if ($status == 1) {
            $data['status'] = 0;
        }
        $data['modified_time'] = milliseconds();
        $this->db->where('id', $id);
        $result = $this->db->update('menu_item', $data);
        return $result;
    }

    public function add_to_fixed_menu_item($mobile_menu_id) {
        $data = array('mobile_menu_id' => $mobile_menu_id);
        $this->db->insert('fixed_menu_type_master', $data);
        return $this->db->insert_id();
    }

    public function add_to_manage_menu_item($mobile_menu_id) {
        $this->db->where('mobile_menu_id', $mobile_menu_id);
        $this->db->delete('fixed_menu_type_master');
        return true;;
    }

//XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX--->  MOBILE MENU BLOG END <---XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX    
}
