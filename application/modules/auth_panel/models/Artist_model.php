<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Artist_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function insert_artist($data) {
        
        $this->db->insert('artists', $data);
        return $this->db->insert_id();
    }

    public function update_artist($data) {
        
        $this->db->where('id', $data['id']);
        $this->db->update('artists', $data);
        return $this->db->affected_rows();
    }

    public function get_artists_type() {
        $this->db->where('status', '0');
        $result = $this->db->get('artists_type')->result_array();
        return $result;
    }
    public function get_artist_list() {
        $this->db->where('status', '0');
        $result = $this->db->get('artists')->result_array();
        return $result;
        
    }

    public function get_artist_by_id($id) {
        $this->db->select('a.*');
        $this->db->where('a.id', $id);
        $this->db->where('at.status', '0');
        $this->db->join('artists_type at', 'a.artists_type_id = at.id');
        $result = $this->db->get('artists a')->row_array();
        // echo $this->db->last_query();die;
        return $result;
    }

    public function delete_artist($id) {
        app_permission("app_id",$this->db);
        $this->db->where('id', $id);
        $data['status'] = '2';
        $result = $this->db->update('artists', $data);
        return $result;
    }

    public function get_artist_for_images() {
        $this->db->select('id,name');
        $this->db->where('status', '0');
        $this->db->where('id !=', 1);
        $result = $this->db->get('artists')->result_array();
        return $result;
    }

    public function insert_artist_images($data) {
        $this->db->insert('guru_images', $data);
        return $this->db->insert_id();
    }

    public function delete_artist_image($id) {
        $this->db->where('id', $id);
        $data['status'] = '2';
        $result = $this->db->update('guru_images', $data);
        return $result;
    }

    public function lock_unlock_artist_image($id, $status) {
        if ($status == 0) {
            $data['status'] = 1;
        }
        if ($status == 1) {
            $data['status'] = 0;
        }
        $data['modified_time'] = milliseconds();
        $this->db->where('id', $id);
        $result = $this->db->update('guru_images', $data);
        return $result;
    }
    public function get_guru_images_by_id($id) {
        $this->db->where('id', $id);
        $this->db->where('status !=',2);
        $result = $this->db->get('guru_images')->row_array();
        return $result;
    }
    public function update_artist_images($data){
		$this->db->where('id',$data['id']); 
		$this->db->update('guru_images',$data);
		return $this->db->affected_rows();
	}

}
