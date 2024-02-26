<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Web_user_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->redis_magic = new Redis_magic("session");
    }

    public function get_user_profile($id) {
        $this->db->select("u.*,up.profile as screen_share");
        $this->db->where('u.id', $id);        
        $this->db->join("users_profile up", "up.user_id=u.id", "left");
        return $this->db->get('users u')->row_array();
    }

    public function get_user_devices($user_id, $user_device_id = null) {        
        $this->db->select("ud.id, ud.device_token, ud.device_type, ud.device_id, ud.device_model,ud.current_status");
        $this->db->where('ud.user_id', $user_id);
        if(!empty($user_device_id)){                             
            $this->db->where('ud.id', $user_device_id);
            return $this->db->get('user_devices ud')->row_array();
        }
        $res = $this->db->get('user_devices ud')->result_array();                
        return $res;
    }

    public function get_user_profile_list($user_id) {
        $this->db->select("*");
        $this->db->where('up.user_id', $user_id);
        $this->db->where('up.status', 0);
        return $this->db->get('users_profile up')->result_array();
    }

    public function update_user_status($status, $id) {
        if ($status == 'delete') {
            $data = array('status' => 2);
            $this->db->where('id', $id);
            return $this->db->update('users', $data);
        } elseif ($status == 'disable') {
            $data = array('status' => 1);
            $this->db->where('id', $id);            
            $this->load->helper("jwt_validater");
            reset_session($id);
            return $this->db->update('users', $data);
        } elseif ($status == 'enable') {
            $data = array('status' => 0);
            $this->db->where('id', $id);
            $this->db->update('users', $data);
            return $this->db->update('users', $data);
        }
        
    }

    public function update_user_name($data) {
        //print_r($data);
        $this->db->set('name', $data['name']);
        $this->db->where('id', $data['id']);
        $this->db->update('users');
        //echo $this->db->last_query();
        return true;
    }

    public function update_desg_name($data) {
        //print_r($data);
        $this->db->set('designation', $data['designation']);
        $this->db->where('id', $data['id']);
        $this->db->update('users');
        //echo $this->db->last_query();
        return true;
    }

    public function update_speciality($data) {
        //print_r($data);
        $this->db->set('speciality', $data['speciality']);
        $this->db->where('id', $data['id']);
        $this->db->update('users');
        //echo $this->db->last_query();
        return true;
    }

    public function get_total_users() {
        //if (!$totalData = $this->redis_magic->get('Web_userajax_all_user_list_'.APP_ID)) {             
        $query = "SELECT count(id) as total FROM users as u where 1 =1"; //AND device_type!=3
        if (defined("APP_ID"))
        $query .= app_permission("app_id");
            $query = $this->db->query($query)->row_array();
            $totalData = (count($query) > 0) ? $query['total'] : 0;
            //$this->redis_magic->SETEX('Web_userajax_all_user_list_'.APP_ID, 3600, $totalData);
        //}
        return $totalData;
    }
    public function get_total_disable_users() {
        //if (!$totalData = $this->redis_magic->get('Web_userajax_all_user_list_'.APP_ID)) {             
        $query = "SELECT count(id) as total FROM users as u where status = 1"; //AND device_type!=3
        if (defined("APP_ID"))
        $query .= app_permission("app_id");
            $query = $this->db->query($query)->row_array();
            $totalData = (count($query) > 0) ? $query['total'] : 0;
           // $this->redis_magic->SETEX('Web_userajax_all_user_list_'.APP_ID, 36, $totalData);
        //}
        return $totalData;
    }

    public function update_user_data($data) {
        $user_id = $data['user_id'];
        unset($data['user_id']);

        if (array_key_exists("email", $data))
            $this->db->where("email", $data['email']);
        if (array_key_exists("mobile", $data))
            $this->db->where("mobile", $data['mobile']);        
        $this->db->where("id !=", $user_id);
        $check_user = $this->db->get("users");
        if ($check_user->num_rows() > 0) {
            return 2;
        } else {
            $this->db->where("id", $user_id);
            $this->db->set($data);
            if ($this->db->update("users"))
                return 1;
            else
                return 0;
        }
    }
 

}
