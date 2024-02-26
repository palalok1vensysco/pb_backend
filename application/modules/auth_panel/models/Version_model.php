<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Version_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function update_version($input_data) {
        app_permission("app_id",$this->db);
        $this->db->where("platform",$input_data["platform"]);
        $is_exist =  $this->db->get("version_control")->row();
        $updateData = array(
            "version" => $input_data["version"],
            "free_v" => $input_data["free_v"] ?? "",
        );
        if($is_exist){
            if(array_key_exists("min_version", $input_data))
                $updateData['min_version'] = $input_data["min_version"];
            if(array_key_exists("force_update", $input_data))
                $updateData['force_update'] = $input_data["force_update"];
            if(array_key_exists("free_v", $input_data))
                $updateData['free_v'] = $input_data["free_v"];
            
            app_permission("app_id",$this->db);
            $this->db->where("id",$is_exist->id);
            $this->db->set($updateData);
            $updateVersion = $this->db->update("version_control");
            return ($updateVersion)?true:false;
        }else{
            $updateData['app_id'] = (defined("APP_ID") && APP_ID) ?APP_ID:0;
            $updateData["platform"] = $input_data["platform"];
            $addVersion = $this->db->insert("version_control",$updateData);
            return ($addVersion)?true:false;
        }
    }
    public function delete_version_review($id) {
        $this->db->where('id', $id);
        $status = $this->db->delete('menus');
        if ($status) {
            return true;
        } else {
            return false;
        }
    }

    public function get_language_list() {
        $this->db->where("status", 0);
        return $this->db->get("languages")->result_array();
    }

}
