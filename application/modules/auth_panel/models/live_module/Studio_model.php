<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Studio_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function channel_list($studio_id = "") {
        if ($studio_id) {
            $this->db->group_start();
            $this->db->or_where("studio_id", $studio_id);
            $this->db->or_where("studio_id", "0");
            $this->db->group_end();
        } else {
            $this->db->where("studio_id", "0");
        }

        app_permission("app_id",$this->db);
        return $this->db->select("id,channel_name")->get("aws_channel")->result();
    }
     public function delete_studio($id) {
        $this->db->where('id', $id);
        app_permission("app_id",$this->db);
        $status = $this->db->delete('studio_management');
        if ($status) {
            return true;
        } else {
            return false;
        }
    }


}
