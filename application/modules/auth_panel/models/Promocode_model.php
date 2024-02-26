<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Promocode_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function insert_promocode($insert_data) {
        $this->db->insert('premium_promocode_master', $insert_data);
        return $this->db->insert_id();
    }

    public function get_promocode_by_id($id) {
        $this->db->where('id', $id);
        return $this->db->get('premium_promocode_master')->row_array();
    }

    public function update_promocode($id, $insert_data) {
        $this->db->where('id', $id);
        return $this->db->update('premium_promocode_master', $insert_data);
    }

    public function delete_promocode($id) {
        $data = array('status' => 1);
        $this->db->where('id', $id);
        $result = $this->db->update("premium_promocode_master", $data);
    }
    
    public function delete_voucher($id) {
        $data = array('status' => 2);
        $this->db->where('id', $id);
        $result = $this->db->update("premium_vouchers", $data);
    }

}
