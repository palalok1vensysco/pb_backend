<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Reality_show_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

public function get_reality_show_type() {
        $this->db->where('status', '0');
        $result = $this->db->get('reality_show_type')->result_array();
        return $result;
    }
 public function get_reality_show_status() {
        $this->db->where('status', '0');
        $result = $this->db->get('reality_status')->result_array();
        return $result;
    }   
    //Insert Judges
public function insert_judges($data) {
        $this->db->insert('judges', $data);
        return $this->db->insert_id();
    }

 public function get_reality_show_data($id)
 {
   $this->db->where('status!=',2);
   $this->db->where('id', $id);
        $result = $this->db->get('reality_show')->row_array();
        return $result; 
 }  

 public function insert_reality_show($data) {
        $this->db->insert('reality_show', $data);
        return $this->db->insert_id();
    }
   




 public function get_judge_by_id($id) {
        $this->db->where('id', $id);
        $this->db->where('status', '0');
        $result = $this->db->get('judges')->row_array();
        return $result;
    }


     public function get_candidate_data($id) {
        $this->db->where('id', $id);
        $this->db->where('status', '0');
        $result = $this->db->get('reality_candidate_registeration')->row_array();
        return $result;
    }

    

 public function delete_judge($id) {
        $this->db->where('id', $id);
        $result = $this->db->delete('judges');
        return $result;
    }


public function get_reality_judge() {
        $this->db->where('status', '0');
        $result = $this->db->get('judges')->result_array();
        return $result;
    }


    public function insert_guru($data) {
        $this->db->insert('artists', $data);
        return $this->db->insert_id();
    }

    public function update_guru($data) {
        $this->db->where('id', $data['id']);
        $this->db->update('artists', $data);
        return $this->db->affected_rows();
    }

    public function get_guru_list() {
        $this->db->where('status', '0');
        $result = $this->db->get('artists')->result_array();
        return $result;
    }

    public function get_guru_by_id($id) {
        $this->db->where('id', $id);
        $this->db->where('status', '0');
        $result = $this->db->get('artists')->row_array();
        return $result;
    }

    public function delete_guru($id) {
        $this->db->where('id', $id);
        $data['status'] = '2';
        $result = $this->db->update('artists', $data);
        return $result;
    }

    public function get_guru_for_images() {
        $this->db->select('id,name');
        $this->db->where('status', '0');
        $this->db->where('id !=', 1);
        $result = $this->db->get('artists')->result_array();
        return $result;
    }

    public function insert_guru_images($data) {
        $this->db->insert('guru_images', $data);
        return $this->db->insert_id();
    }

    public function delete_guru_image($id) {
        $this->db->where('id', $id);
        $data['status'] = '2';
        $result = $this->db->update('guru_images', $data);
        return $result;
    }

    public function lock_unlock_guru_image($id, $status) {
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
    public function update_guru_images($data){
		$this->db->where('id',$data['id']); 
		$this->db->update('guru_images',$data);
		return $this->db->affected_rows();
	}

}
