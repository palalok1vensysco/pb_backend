<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Api_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

//XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX---> API BLOG START <---XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX        
 public function insert_api($data) {
        $this->db->insert('api_master', $data);
        return $this->db->insert_id();
    }

    public function get_api_by_id($id) {
        $this->db->select('*');
        $this->db->where('status', '0');
        $this->db->where('id', $id);
        $result = $this->db->get('api_master')->row_array();
        return $result;
    }

    public function update_api($data) {
        $data['modified_time'] = milliseconds();
        $this->db->where('id', $data['id']);
        $result = $this->db->update('api_master', $data);
        return $result;
    }

    public function delete_api($id) {
        $this->db->where('id', $id);
        $data['status'] = '2';
        $data['modified_time'] = milliseconds();
        $result = $this->db->update('api_master', $data);
        return $result;
    }

//XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX---> API BLOG END <---XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX            

}
